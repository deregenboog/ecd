<?php

namespace OekBundle\Controller;

use AppBundle\Controller\SymfonyController;
use OekBundle\Form\OekVerwijzingType;
use OekBundle\Entity\OekVerwijzingNaar;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/oek/admin/verwijzingen_naar")
 */
class VerwijzingenNaarController extends SymfonyController
{
    private $sortFieldWhitelist = [
        'oekVerwijzing.id',
        'oekVerwijzing.naam',
        'oekVerwijzing.actief',
    ];

    /**
     * @Route("/")
     */
    public function index()
    {
        $repository = $this->getEntityManager()->getRepository(OekVerwijzingNaar::class);

        $builder = $repository->createQueryBuilder('oekVerwijzing');

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => 'oekVerwijzing.naam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        return compact('pagination');
    }

    /**
     * @Route("/add")
     */
    public function add()
    {
        $oekVerwijzing = new OekVerwijzingNaar();

        $form = $this->createForm(OekVerwijzingType::class, $oekVerwijzing);
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            $entityManager = $this->getEntityManager();
            $entityManager->persist($oekVerwijzing);
            $entityManager->flush();

            $this->addFlash('success', 'Verwijzing is aangemaakt.');

            return $this->redirectToRoute('oek_verwijzingennaar_index');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $oekVerwijzing = $entityManager->find(OekVerwijzingNaar::class, $id);

        $form = $this->createForm(OekVerwijzingType::class, $oekVerwijzing);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Verwijzing is gewijzigd.');

            return $this->redirectToRoute('oek_verwijzingennaar_index');
        }

        return ['form' => $form->createView()];
    }
}
