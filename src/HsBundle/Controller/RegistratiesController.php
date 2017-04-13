<?php

namespace HsBundle\Controller;

use HsBundle\Entity\Registratie;
use HsBundle\Entity\Klus;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Vrijwilliger;
use HsBundle\Form\RegistratieType;
use AppBundle\Entity\Medewerker;
use AppBundle\Controller\SymfonyController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use HsBundle\Service\RegistratieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use HsBundle\Entity\Arbeider;

/**
 * @Route("/hs/registraties")
 */
class RegistratiesController extends SymfonyController
{
    /**
     * @var RegistratieDaoInterface
     *
     * @DI\Inject("hs.dao.registratie")
     */
    private $dao;

    /**
     * @Route("/add/{klus}/{arbeider}")
     * @ParamConverter()
     */
    public function add(Klus $klus, Arbeider $arbeider = null)
    {
        $entity = new Registratie($klus, $arbeider);
        $form = $this->createForm(RegistratieType::class, $entity);
        $form->handleRequest($this->getRequest());
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
    public function edit($id)
    {
        $entity = $this->dao->find($id);
        $form = $this->createForm(RegistratieType::class, $entity);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $this->dao->update($entity);

            return $this->redirectToView($entity);
        }

        return ['form' => $form->createView()];
    }

    private function redirectToView(Registratie $entity)
    {
        return $this->redirectToRoute('hs_klussen_view', ['id' => $entity->getKlus()->getId()]);
    }
}
