<?php

namespace OekBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Export\ExportInterface;
use AppBundle\Filter\FilterInterface;
use JMS\DiExtraBundle\Annotation as DI;
use OekBundle\Form\DeelnemerFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wachtlijst")
 * @Template
 */
class WachtlijstController extends AbstractController
{
    protected $title = 'Wachtlijst deelnemers';
    protected $filterFormClass = DeelnemerFilterType::class;
    protected $baseRouteName = 'oek_deelnemers_';
    protected $disabledActions = ['view', 'add', 'edit', 'delete'];

    /**
     * @var DeelnemerDaoInterface
     *
     * @DI\Inject("OekBundle\Service\DeelnemerDao")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("oek.export.wachtlijst")
     */
    protected $export;

    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $filter = null;
        if ($this->filterFormClass) {
            $form = $this->createForm($this->filterFormClass, null, ['enabled_filters' => [
                'klant' => ['id', 'naam', 'stadsdeel'],
                'groep',
                'aanmelddatum',
            ]]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->has('download') && $form->get('download')->isClicked()) {
                    return $this->download($form->getData());
                }
            }
            $filter = $form->getData();
        }

        $page = $request->get('page', 1);
        $pagination = $this->dao->findWachtlijst($page, $filter);

        return [
            'filter' => isset($form) ? $form->createView() : null,
            'pagination' => $pagination,
        ];
    }

    protected function download(FilterInterface $filter)
    {
        ini_set('memory_limit', '512M');

        $filename = $this->getDownloadFilename();
        $collection = $this->dao->findWachtlijst(null, $filter);

        return $this->export->create($collection)->getResponse($filename);
    }

    protected function getDownloadFilename()
    {
        return sprintf('op-eigen-kracht-deelnemers-%s.xlsx', (new \DateTime())->format('d-m-Y'));
    }
}
