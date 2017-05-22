<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Form\ConfirmationType;
use DagbestedingBundle\Entity\Verslag;
use DagbestedingBundle\Exception\DagbestedingException;
use DagbestedingBundle\Form\VerslagType;
use DagbestedingBundle\Service\VerslagDaoInterface;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/dagbesteding/verslagen")
 */
class VerslagenController extends AbstractController
{
    protected $title = 'Verslagen';
    protected $entityName = 'Verslag';

    /**
     * @var VerslagDaoInterface
     *
     * @DI\Inject("dagbesteding.dao.verslag")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("dagbesteding.verslag.entities")
     */
    protected $entities;

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        $entityManager = $this->getEntityManager();
        $entity = $this->findEntity($request, $entityManager);

        $form = $this->createForm(VerslagType::class, new Verslag());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($entity->addVerslag($form->getData()));
                $entityManager->flush();
                $this->addFlash('success', $this->entityName.' is toegevoegd.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('dagbesteding_index');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(VerslagType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);
                $this->addFlash('success', $this->entityName.' is bijgewerkt.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('dagbesteding_index');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $this->dao->delete($entity);
                $this->addFlash('success', $this->entityName.' is verwijderd.');
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('dagbesteding_index');
        }

        return [
            'form' => $form->createView(),
            'entity' => $entity,
        ];
    }

    private function findEntity(Request $request, EntityManager $entityManager)
    {
        foreach ($this->entities as $entity) {
            $key = $entity['key'];
            if ($request->query->has($key)) {
                $class = $entity['class'];
                $id = $request->query->get($key);

                return $entityManager->find($class, $id);
            }
        }

        throw new DagbestedingException('Kan geen document aan deze entiteit toevoegen');
    }
}
