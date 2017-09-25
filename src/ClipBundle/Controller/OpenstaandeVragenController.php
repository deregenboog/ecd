<?php

namespace ClipBundle\Controller;

use ClipBundle\Service\VraagDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Export\GenericExport;
use ClipBundle\Form\VraagFilterType;
use AppBundle\Controller\AbstractChildController;
use ClipBundle\Entity\Vraag;
use ClipBundle\Form\VraagType;
use ClipBundle\Form\VraagCloseType;
use AppBundle\Export\ExportInterface;
use AppBundle\Controller\AbstractController;

/**
 * @Route("/openstaandevragen")
 */
class OpenstaandeVragenController extends VragenController
{
    protected $title = 'Openstaande vragen';

    /**
     * @var VraagDaoInterface
     *
     * @DI\Inject("clip.dao.vraag")
     */
    protected $dao;

    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $filter = null;

        if ($this->filterFormClass) {
            $form = $this->createForm($this->filterFormClass, null, [
                'enabled_filters' => [
                    'id',
                    'startdatum',
                    'soort',
                    'behandelaar',
                    'client' => ['klant' => ['naam']],
                ],
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->has('download') && $form->get('download')->isClicked()) {
                    return $this->download($form->getData());
                }
                $filter = $form->getData();
            }
        }

        $page = $request->get('page', 1);
        $pagination = $this->dao->findAllOpen($page, $filter);

        return [
            'filter' => isset($form) ? $form->createView() : null,
            'pagination' => $pagination,
        ];
    }
}
