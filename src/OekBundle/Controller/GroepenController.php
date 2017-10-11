<?php

namespace OekBundle\Controller;

use AppBundle\Controller\SymfonyController;
use Doctrine\ORM\EntityRepository;
use OekBundle\Entity\OekGroep;
use OekBundle\Form\OekGroepType;
use AppBundle\Form\ConfirmationType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/oek/groepen")
 */
class GroepenController extends SymfonyController
{
    private $sortFieldWhitelist = [
        'oekGroep.id',
        'oekGroep.naam',
        'oekGroep.aantalBijeenkomsten',
    ];

    /**
     * @Route("/")
     */
    public function index()
    {
        $entityManager = $this->getEntityManager();
        /** @var EntityRepository $repository */
        $repository = $entityManager->getRepository(OekGroep::class);

        $builder = $repository->createQueryBuilder('oekGroep')
            ->select(
                'oekGroep',
                'COUNT(DISTINCT oekLidmaatschap) AS oekLidmaatschappen',
                'COUNT(DISTINCT oekTraining) AS oekTrainingen'
            )
            ->leftJoin('oekGroep.oekLidmaatschappen', 'oekLidmaatschap')
            ->leftJoin('oekGroep.oekTrainingen', 'oekTraining')
            ->groupBy('oekGroep')
        ;

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => 'oekGroep.naam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        return compact('pagination');
    }

    /**
     * @Route("/{id}/view")
     */
    public function view($id)
    {
        $oekGroep = $this->getEntityManager()->find(OekGroep::class, $id);

        return compact('oekGroep');
    }

    /**
     * @Route("/add")
     */
    public function add()
    {
        $entityManager = $this->getEntityManager();

        $oekGroep = new OekGroep();

        $form = $this->createForm(OekGroepType::class, $oekGroep);
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            $entityManager->persist($oekGroep);
            $entityManager->flush();

            $this->addFlash('success', 'Groep is aangemaakt.');

            return $this->redirectToRoute('oek_groepen_view', ['id' => $oekGroep->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekGroep::class);
        $oekGroep = $repository->find($id);

        $form = $this->createForm(OekGroepType::class, $oekGroep);
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Groep is gewijzigd.');

            return $this->redirectToRoute('oek_groepen_view', ['id' => $oekGroep->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekGroep::class);
        $oekGroep = $repository->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $entityManager->remove($oekGroep);
                $entityManager->flush();

                $this->addFlash('success', 'Groep is verwijderd.');
            }

            return $this->redirectToRoute('oek_groepen_index');
        }

        return ['form' => $form->createView(), 'oekGroep' => $oekGroep];
    }
}
