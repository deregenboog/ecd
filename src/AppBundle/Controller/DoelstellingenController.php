<?php

namespace AppBundle\Controller;

use AppBundle\Exception\AppException;
use AppBundle\Exception\ReportException;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\DoelstellingType;
use AppBundle\Model\MedewerkerSubjectInterface;
use AppBundle\Report\AbstractReport;
use AppBundle\Entity\Doelstelling;
use AppBundle\Service\DoelstellingDaoInterface;
use AppBundle\Form\DoelstellingFilterType;
use JMS\DiExtraBundle\Annotation as DI;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/app/doelstellingen")
 */
class DoelstellingenController extends AbstractController
{
    protected $title = 'Doelstellingen';
    protected $entityName = 'doelstelling';
    protected $entityClass = Doelstelling::class;
    protected $formClass = DoelstellingType::class;
    protected $filterFormClass = DoelstellingFilterType::class;
    protected $baseRouteName = "app_doelstellingen_";

    /**
     * @var DoelstellingDaoInterface $dao
     *
     * @DI\Inject("AppBundle\Service\DoelstellingDao")
     */
    protected $dao = DoelstellingDao::class;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("app.export.report")
     */
    protected $export;

    /**
     * @var DoelstellingType Form with services tagged with app.doelstelling
     * @DI\Inject("app.doelstelling")
     */
    private $doelstellingenServices = [];



    /**
     * DoelstellingenController constructor.
     *
     * @param $services Injected via services tags. All sercices tagged app.doelstelling are injected here as a service
     */
//    public function __construct($services = null,$b=null,$c=null)
//    {
//        if(!$services === null) $this->doelstellingenServices = iterator_to_array($services);
//
//    }
    /**
     * @Route("/")
     * @Template
     */
    public function indexAction(Request $request)
    {

        if (in_array('index', $this->disabledActions)) {
            throw new AccessDeniedHttpException();
        }

        if ($this->filterFormClass) {
            $form = $this->getForm($this->filterFormClass);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->has('download') && $form->get('download')->isClicked()) {
                    return $this->download($form->getData());
                }
            }
            $filter = $form->getData();
        } else {
            $filter = null;
        }
        $page = $request->get('page', 1);

        $pagination = $this->dao->findAll($page, $filter,$this->doelstellingenServices->getRepos());

        return [
            'filter' => isset($form) ? $form->createView() : null,
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/s")
     * @Template
     */
    public function sindexAction(Request $request)
    {
        if (!$this->formClass) {
            throw new \InvalidArgumentException(get_class($this).'::formClass must be set.');
        }

        $formOptions = [];
        if ($request->query->has('rapportage')) {
            // get reporting service
            /** @var AbstractReport $report */
            $report = $this->container->get($request->query->get('rapportage')['rapport']);
            $formOptions = $report->getFormOptions();
        }

        $form = $this->getForm($this->formClass, null, $formOptions);
      //  $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // get reporting service
            /* @var AbstractReport $report */
            $report = $this->container->get($form->get('rapport')->getData());
            $report->setFilter($form->getData());

            try {
                if ($form->get('download')->isClicked()) {
                    return $this->download($report);
                }

                return [
                    'title' => $report->getTitle(),
                    'startDate' => $report->getStartDate(),
                    'endDate' => $report->getEndDate(),
                    'reports' => $report->getReports(),
                    'form' => $form->createView(),
                ];
            } catch (ReportException $exception) {
                $form->addError(new FormError($exception->getMessage()));

                return [
                    'form' => $form->createView(),
                    'title' => '',
                ];
            }
        }

        return [
            'form' => $form->createView(),
            'title' => '',
        ];
    }


    protected function processForm(Request $request, $entity = null)
    {
        if (!$this->formClass) {
            throw new AppException(get_class($this).'::formClass not set!');
        }

        $form = $this->getForm($this->formClass, $entity, [
            'medewerker' => $this->getMedewerker(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($entity instanceof MedewerkerSubjectInterface && !$entity->getMedewerker()) {
                $entity->setMedewerker($this->getMedewerker());
            }
            try {
                if ($entity->getId()) {
                    $this->beforeUpdate($entity);
                    $this->dao->update($entity);
                } else {
                    $this->beforeCreate($entity);
                    $this->dao->create($entity);
                }
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');
            } catch (\Exception $e) {
                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->afterFormSubmitted($request, $entity);
        }

        return array_merge([
            'entity' => $entity,
            'form' => $form->createView(),
        ], $this->addParams($entity, $request));
    }
}
