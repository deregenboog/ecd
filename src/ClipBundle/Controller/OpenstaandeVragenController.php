<?php

namespace ClipBundle\Controller;

use ClipBundle\Service\VraagDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use ClipBundle\Entity\Vraag;
use AppBundle\Controller\AbstractController;
use ClipBundle\Form\VraagFilterType;

/**
 * @Route("/openstaandevragen")
 */
class OpenstaandeVragenController extends AbstractController
{
    protected $title = 'Openstaande vragen';
    protected $filterFormClass = VraagFilterType::class;
    protected $baseRouteName = 'clip_vragen_';

    /**
     * @var VraagDaoInterface
     *
     * @DI\Inject("clip.dao.vraag")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("clip.export.vragen")
     */
    protected $export;

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
                    'client' => ['naam'],
                    'filter',
                    'download',
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
