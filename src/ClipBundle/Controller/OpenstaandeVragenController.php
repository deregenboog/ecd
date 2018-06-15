<?php

namespace ClipBundle\Controller;

use AppBundle\Export\ExportInterface;
use ClipBundle\Entity\Vraag;
use ClipBundle\Filter\VraagFilter;
use ClipBundle\Service\VraagDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/openstaandevragen")
 */
class OpenstaandeVragenController extends AbstractVragenController
{
    protected $title = 'Openstaande vragen';

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
        $filter = new VraagFilter();
        $filter->openstaand = true;

        if ($this->filterFormClass) {
            $form = $this->createForm($this->filterFormClass, $filter);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->has('download') && $form->get('download')->isClicked()) {
                    return $this->download($form->getData());
                }
            }
            $filter = $form->getData();
        }

        $page = $request->get('page', 1);
        $pagination = $this->dao->findAll($page, $filter);

        return [
            'filter' => isset($form) ? $form->createView() : null,
            'pagination' => $pagination,
        ];
    }

    protected function createForm($type, $data = null, array $options = [])
    {
        $options['enabled_filters'] = [
            'id',
            'startdatum',
            'soort',
            'behandelaar',
            'client' => ['naam'],
            'filter',
            'download',
        ];

        return parent::createForm($type, $data, $options);
    }
}
