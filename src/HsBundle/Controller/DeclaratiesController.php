<?php

namespace HsBundle\Controller;

use HsBundle\Entity\Declaratie;
use HsBundle\Entity\Klus;
use HsBundle\Form\DeclaratieType;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\AbstractController;
use HsBundle\Service\DeclaratieDaoInterface;

/**
 * @Route("/declaraties")
 */
class DeclaratiesController extends AbstractController
{
    protected $title = 'Declaraties';
    protected $entityName = 'declaratie';
    protected $entityClass = Declaratie::class;
    protected $formClass = DeclaratieType::class;
    protected $baseRouteName = 'hs_declaraties_';

    /**
     * @var DeclaratieDaoInterface
     *
     * @DI\Inject("hs.dao.declaratie")
     */
    protected $dao;

    /**
     * @Route("/add/{klus}")
     * @ParamConverter()
     */
    public function addAction(Request $request, Klus $klus)
    {
        $entity = new Declaratie($klus);
        $form = $this->createForm(DeclaratieType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->dao->create($entity);

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('hs_klussen_index');
        }

        return [
            'klus' => $klus,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('hs_klussen_index');
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request)
    {
        return $this->redirectToRoute('hs_klussen_index');
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request)
    {
        return $this->redirectToRoute('hs_klussen_index');
    }
}
