<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Form\KlantMergeType;
use AppBundle\Service\KlantDaoInterface;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/klanten")
 * @Template
 */
class KlantenController extends AbstractController
{
    protected $title = 'Klanten';
    protected $entityName = 'klant';
    protected $entityClass = Klant::class;
    protected $baseRouteName = 'app_admin_klanten_';

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("AppBundle\Service\KlantDao")
     */
    protected $dao;

    /**
     * @Route("/duplicates/{mode}")
     * @Template
     */
    public function duplicatesAction($mode = null)
    {
        if (!$mode) {
            return [];
        }

        $duplicates = $this->dao->findDuplicates($mode);

        return[
            'mode' => $mode,
            'duplicates' => $duplicates,
        ];
    }

    /**
     * @Route("/admin/merge/{ids}")
     * @Template
     */
    public function mergeAction(Request $request, $ids)
    {
        $ids = array_map('intval', explode(',', $ids));

        $klanten = [];
        foreach ($ids as $id) {
            $klanten[] = $this->dao->find($id);
        }

        if (count($klanten) < 2) {
            $this->addFlash('danger', 'Selecteer minstens twee klanten om samen te voegen.');

            return $this->redirectToRoute('app_klanten_duplicates');
        }

        $entity = clone $klanten[0];
        $form = $this->getForm(KlantMergeType::class, $entity, [
            'klanten' => $klanten,
            'medewerker' => $this->getMedewerker(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getEntityManager();
            $em->beginTransaction();
            try {
                $selected = $form->get('klanten')->getData();
                $logger = $this->getLogger('merge');
                $this->dao->create($entity);
                $this->moveAssociations($entity, $selected, $em, $logger);
                $this->disableMerged($entity, $selected, $em, $logger);
                $em->commit();

                // reload entity and update calculated fields
                $em->clear();
                $entity = $this->dao->find($entity->getId());
                $entity->updateCalculatedFields();
                $this->dao->update($entity);

                $this->addFlash('success', 'De dossiers zijn samengevoegd.');
            } catch (\Exception $e) {
                $em->rollback();

                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);

                return $this->redirectToRoute('app_klanten_index');
            }

            return $this->redirectToRoute('app_klanten_view', ['id' => $entity->getId()]);
        }

        return [
            'form' => $form->createView(),
            'klanten' => $klanten,
        ];
    }

    /**
     * @param Klant           $entity  new entity
     * @param Klant[]         $klanten original entities
     * @param EntityManager   $em
     * @param LoggerInterface $logger
     */
    private function moveAssociations($entity, $klanten, EntityManager $em, LoggerInterface $logger)
    {
        $dqls = [];
        $allMetadata = $em->getMetadataFactory()->getAllMetadata();
        foreach ($allMetadata as $classMetadata) {
            foreach ($classMetadata->getAssociationNames() as $name) {
                if (Klant::class === $classMetadata->getName() && 'merged' === $name) {
                    continue;
                }
                if (Klant::class === $classMetadata->getAssociationTargetClass($name)) {
                    foreach ($klanten as $klant) {
                        $dqls[] = sprintf('UPDATE %s a SET a.%s = %d WHERE a.%s = %d',
                            (string) $classMetadata->getName(),
                            (string) $name,
                            (int) $entity->getId(),
                            (string) $name,
                            (int) $klant->getId()
                        );
                    }
                }
            }
        }

        $context = [
            'new' => $entity->getId(),
            'old' => array_map(function ($klant) { return $klant->getId(); }, $klanten),
        ];
        $logger->debug('Start moving associations', $context);

        foreach ($dqls as $dql) {
            $count = $em->createQuery($dql)->execute();
            $logger->debug($dql, ['count' => $count]);
        }

        $logger->debug('Finished moving associations', $context);
    }

    /**
     * @param Klant           $entity  new entity
     * @param Klant[]         $klanten
     * @param EntityManager   $em
     * @param LoggerInterface $logger
     */
    private function disableMerged($entity, $klanten, EntityManager $em, LoggerInterface $logger)
    {
        foreach ($klanten as $klant) {
            $klant->setMerged($entity);
            $context = ['id' => $klant->getId(), 'merged_id' => $entity->getId()];
            $logger->debug('Disabling merged', $context);
        }

        $em->flush();
    }
}
