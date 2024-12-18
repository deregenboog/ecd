<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\AbstractController;
use AppBundle\Controller\DisableIndexActionTrait;
use AppBundle\Entity\Klant;
use AppBundle\Form\KlantMergeType;
use AppBundle\Service\KlantDaoInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/klanten")
 *
 * @Template
 */
class KlantenController extends AbstractController
{
    use DisableIndexActionTrait;

    protected $entityName = 'klant';
    protected $entityClass = Klant::class;
    protected $baseRouteName = 'app_admin_klanten_';

    /**
     * @var KlantDaoInterface
     */
    protected $dao;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(KlantDaoInterface $dao, LoggerInterface $logger)
    {
        $this->dao = $dao;
        $this->logger = $logger;
    }

    /**
     * @Route("/duplicates/{mode}")
     *
     * @Template
     */
    public function duplicatesAction($mode = null)
    {
        if (!$mode) {
            return [];
        }

        $duplicates = $this->dao->findDuplicates($mode);

        return [
            'mode' => $mode,
            'duplicates' => $duplicates,
        ];
    }

    /**
     * @Route("/admin/merge/{ids}")
     *
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
                $this->dao->create($entity);
                $this->moveAssociations($entity, $selected, $em);
                $this->disableMerged($entity, $selected, $em);
                $em->commit();

                $this->addFlash('success', 'De dossiers zijn samengevoegd.');
            } catch (\Exception $e) {
                $em->rollback();

                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);

                return $this->redirectToRoute('app_admin_klanten_duplicates');
            }

            // reload entity and update calculated fields
            $em->clear();
            $entity = $this->dao->find($entity->getId());
            $entity->updateCalculatedFields();
            $this->dao->update($entity);

            return $this->redirectToRoute('app_klanten_view', ['id' => $entity->getId()]);
        }

        return [
            'form' => $form->createView(),
            'klanten' => $klanten,
        ];
    }

    /**
     * @param Klant   $entity  new entity
     * @param Klant[] $klanten original entities
     */
    private function moveAssociations($entity, $klanten, EntityManagerInterface $em)
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
        $this->logger->debug('Start moving associations', $context);

        foreach ($dqls as $dql) {
            $count = $em->createQuery($dql)->execute();
            $this->logger->debug($dql, ['count' => $count]);
        }

        $this->logger->debug('Finished moving associations', $context);
    }

    /**
     * @param Klant   $entity  new entity
     * @param Klant[] $klanten
     */
    private function disableMerged($entity, $klanten, EntityManagerInterface $em)
    {
        foreach ($klanten as $klant) {
            $klant->setMerged($entity);
            $context = ['id' => $klant->getId(), 'merged_id' => $entity->getId()];
            $this->logger->debug('Disabling merged', $context);
        }

        $em->flush();
    }
}
