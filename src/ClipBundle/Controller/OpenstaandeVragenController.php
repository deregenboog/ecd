<?php

namespace ClipBundle\Controller;

use ClipBundle\Service\VraagDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use ClipBundle\Entity\Vraag;

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
                    'client' => ['naam'],
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
