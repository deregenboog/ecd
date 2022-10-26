<?php

namespace AppBundle\Controller;

use AppBundle\Exception\AppException;
use AppBundle\Exception\ReportException;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\DoelstellingType;
use AppBundle\Model\MedewerkerSubjectInterface;
use AppBundle\Report\AbstractReport;
use AppBundle\Entity\Doelstelling;

use AppBundle\Service\DoelstellingDao;
use AppBundle\Service\DoelstellingDaoInterface;
use AppBundle\Form\DoelstellingFilterType;
use AppBundle\Filter\DoelstellingFilter;

use JMS\DiExtraBundle\Annotation as DI;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * This controller/doelstellingen module works as follows:
 *
 * The goal is to set target numbers on given KPI's existing in the application.
 * To calculate the KPI's, one can make them available by tagging a repository with 'app.doelstelling'
 * Important is that this repository service defitinion is not constructed via the usual factory way. That way it is generic object and not one which implements
 * DoelstellingRepositoryInterface.
 * This interface can be implemented with the DoelstellingRepositoryTrait
 *
 * Each method in this repository who AppBundle\Entity\Doelstelling as first argument is listed as available doelstelling.
 * Those methods are called to retrieve the actual KPI number.
 *
 * Only KPI's in bundles are shown where the user has access too.
 * For this, the AppBundle\Security\DoelstellingVoter is created.
 * If a string is given of a certain Repository::Method, which is the way the Doelstelling Entity know which method to call,
 * it checks if the user has the role for the bundle where this Repository lives in.
 *
 * The DoelstellingenDao is the way the doelstellingen are retrieved from database and connected to their actual numbers.
 * As this is a method call per row, it needs some caching. But this is @todo.
 *
 * Ideas: save results once asked in json as k=>v where key=date
 * temp table filled with results each day via command/cronjob
 *
 * Note: I have the idea that this is not the usual way Symfony works but I did not have another strategy to connect programmable data to database data in a 'modular' way.
 * Also, there where several issues with service injection and tagging, which made it very very frustrating to work with just services with the right tags. This didn't work
 * propably because of the use of JMI/DI instead of the default Symfony serviceContainer.
 *
 * Autowiring didn't work as expected.
 *
 * This can / should be revisited when upgraded.
 * First but foremost it is necessary to get all the right data. And see if one-dimensional is good enough. This is what I designed it for. If two or three dimensions needed
 * we should consider another approach.
 *
 */
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
    /**
     * @var DoelstellingDao $dao
     */
    protected $dao = DoelstellingDao::class;

    protected $baseRouteName = "app_doelstellingen_";

    /**
     * @var ExportInterface
     */
    protected $export;

    /**
     * @param DoelstellingDao|string $dao
     * @param ExportInterface $export
     */
    public function __construct(DoelstellingDao $dao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->export = $export;
    }


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
            $form = $this->getForm($this->filterFormClass, new DoelstellingFilter());
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

        $pagination = $this->dao->findAll($page, $filter);

        return [
            'filter' => isset($form) ? $form->createView() : null,
            'pagination' => $pagination,
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
            }catch(UserException $e) {
                $message = $e->getMessage();
                $this->addFlash('danger',$message);
            }
            catch (\Exception $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->afterFormSubmitted($request, $entity, null);
        }

        return array_merge([
            'entity' => $entity,
            'form' => $form->createView(),
        ], $this->addParams($entity, $request));
    }
}
