<?php

namespace ClipBundle\Controller;

use AppBundle\Export\ExportInterface;
use ClipBundle\Filter\VraagFilter;
use ClipBundle\Service\VraagDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/openstaandevragen")
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

    public function setContainer(?\Symfony\Component\DependencyInjection\ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->dao = $container->get("ClipBundle\Service\VraagDao");
        $this->export = $container->get('clip.export.vragen');
    }

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

    protected function createForm(string $type, $data = null, array $options = []): FormInterface
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

        return parent::createForm($type, $data, $options);
    }
}
