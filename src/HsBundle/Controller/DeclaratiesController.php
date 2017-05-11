<?php

namespace HsBundle\Controller;

use AppBundle\Controller\SymfonyController;
use HsBundle\Entity\Declaratie;
use HsBundle\Entity\Klus;
use HsBundle\Form\DeclaratieType;
use HsBundle\Form\RegistratieType;
use HsBundle\Service\RegistratieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/hs/declaraties")
 */
class DeclaratiesController extends SymfonyController
{
    /**
     * @var RegistratieDaoInterface
     *
     * @DI\Inject("hs.dao.declaratie")
     */
    private $dao;

    /**
     * @Route("/add/{klus}")
     * @ParamConverter()
     */
    public function add(Request $request, Klus $klus)
    {
        $entity = new Declaratie($klus);
        $form = $this->createForm(DeclaratieType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->dao->create($entity);

            return $this->redirectToView($entity);
        }

        return [
            'klus' => $klus,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function edit(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        $form = $this->createForm(DeclaratieType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->dao->update($entity);

            return $this->redirectToView($entity);
        }

        return ['form' => $form->createView()];
    }

    private function redirectToView(Declaratie $entity)
    {
        return $this->redirectToRoute('hs_klussen_view', ['id' => $entity->getKlus()->getId()]);
    }
}
