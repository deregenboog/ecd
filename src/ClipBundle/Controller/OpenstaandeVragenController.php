<?php

namespace ClipBundle\Controller;

use AppBundle\Export\ExportInterface;
use ClipBundle\Filter\VraagFilter;
use ClipBundle\Service\VraagDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/openstaandevragen")
 *
 * @Template
 */
class OpenstaandeVragenController extends AbstractVragenController
{
    protected $title = 'Openstaande vragen';

    /**
     * @var VraagDaoInterface
     */
    protected $dao;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function __construct(VraagDaoInterface $dao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->export = $export;
    }

    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $filter = new VraagFilter();
        $filter->openstaand = true;

        if ($this->filterFormClass) {
            $form = $this->getForm($this->filterFormClass, $filter);
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

    protected function getForm($type, $data = null, array $options = [])
    {
        $options['enabled_filters'] = [
            'id',
            'startdatum',
            'soort',
            'behandelaar',
            'client' => ['naam'],
            'hulpCollegaGezocht',
            'filter',
            'download',
        ];

        return $this->createForm($type, $data, $options);
    }
}
