<?php

namespace AppBundle\Controller;


use AppBundle\Exception\AppException;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\ConfirmationType;
use AppBundle\Model\MedewerkerSubjectInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

abstract class AbstractController extends SymfonyController
{
    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var string
     */
    protected $formClass;

    /**
     * @var string
     */
    protected $filterFormClass;

    /**
     * If adding entities needs searching for klanten or vrijwiliigers (as is the case with many modules)
     * then one needs to search first for existing entities. this is done with this filter type
     * @var string
     */
    protected $searchFilterTypeClass;

    /**
     * This is the dao used for searching entities and if they exist.
     * @var AbstractDao
     */
    protected $searchDao;

    /**
     * This entity is created when there is no existing.
     * (ie. klant or vrvijwilliger)
     * @var object Entity to create when nothing found
     */
    protected $searchEntity;
    /**
     * @var string
     */
    protected $templatePath;

    /**
     * @var AbstractDao
     */
    protected $dao;

    /**
     * @var ExportInterface
     */
    protected $export;

    /**
     * @var string
     */
    protected $baseRouteName;

    /**
     * @var array
     */
    protected $disabledActions = [];

    /**
     * @var bool
     */
    protected $forceRedirect = false;

    public function setDao(AbstractDao $dao)
    {
        $this->dao = $dao;

        return $this;
    }

    public function setExport(ExportInterface $export)
    {
        $this->export = $export;

        return $this;
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
        $pagination = $this->dao->findAll($page, $filter);

        return [
            'filter' => isset($form) ? $form->createView() : null,
            'pagination' => $pagination,
        ];
    }

    protected function download(FilterInterface $filter)
    {
        if (!$this->export) {
            throw new AppException(get_class($this).'::export not set!');
        }

        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');

        $filename = $this->getDownloadFilename();
        $collection = $this->dao->findAll(null, $filter);

        return $this->export->create($collection)->getResponse($filename);
    }

    protected function getDownloadFilename()
    {
        $refl = new \ReflectionClass($this);

        return sprintf(
            '%s-%s-%s.xlsx',
            strtolower(str_replace('Bundle\\Controller', '', $refl->getNamespaceName())),
            strtolower(str_replace('Controller', '', $refl->getShortName())),
            (new \DateTime())->format('Y-m-d')
        );
    }

    /**
     * @Route("/{id}/view")
     * @Template
     */
    public function viewAction(Request $request, $id)
    {
        if (in_array('view', $this->disabledActions)) {
            throw new AccessDeniedHttpException();
        }

        $this->beforeFind($id);
        $entity = false;
        $message = null;
        try
        {
            $entity = $this->dao->find($id);
        }
        catch(EntityNotFoundException $entityNotFoundException)
        {
            $message = $this->container->getParameter('kernel.debug') ? $entityNotFoundException->getMessage() : 'Kan '.$this->entityClass.' niet inladen. Waarschijnlijk omdat deze verwijderd of inactief is.';

        } catch (UserException $e)
        {
            $message = $e->getMessage();
        }
        catch(\Exception $exception){
            $message = $this->container->getParameter('kernel.debug') ? $exception->getMessage() : 'Kan '.$this->entityClass.' niet inladen. Onbekende fout.';

        }
        if($message){
            $this->addFlash('danger', $message);
            return $this->redirect($request->get("redirect"));
        }

        $this->afterFind($entity);

        if (!$entity) {
            return $this->redirectToIndex();
        }

        $params = ['entity' => $entity];

        $params = array_merge($params, $this->addParams($entity, $request));
        return $params;
    }

    /**
     * @Route("/add")
     * @Template
     */
    public function addAction(Request $request)
    {
        if (in_array('add', $this->disabledActions)) {
            throw new AccessDeniedHttpException();
        }
        if (!isset($this->addMethod) || !is_callable($this, $this->addMethod) && !isset($this->searchFilterTypeClass))
        {
            return $this->doAdd($request);
        }
        else if(isset($this->searchFilterTypeClass))
        {
            if ($request->get('entity')) {
                return $this->doAdd($request);
            }

            return $this->doSearch($request);
        }
        else
        {
            return $this->{$this->addMethod}($request);
        }
    }

    protected function doAdd(Request $request)
    {
        $entityId = $request->get('entity');

        if ('new' === $entityId) {
            $searchEntity = new $this->searchEntity();
        }
        else if($entityId == null)
        {
            $entity = new $this->entityClass();

            return $this->processForm($request, $entity);
        }
        else {
            $searchEntity = $this->searchDao->find($entityId);
            if ($searchEntity) {
                // redirect if already exists
                $subEntity = $this->dao->findOneBySearchEntity($searchEntity);
                if ($subEntity) {
                    return $this->redirectToView($subEntity);
                }
            }
        }

        $subEntity = new $this->entityClass($searchEntity);
        $creationForm = $this->getForm($this->formClass, $subEntity);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {
                $this->dao->create($subEntity);
                $this->addFlash('success', ucfirst($this->entityName) . ' is opgeslagen.');

                return $this->redirectToView($subEntity);
            } catch (UserException $e) {
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToIndex();
        }

        return [
            'entity' => $subEntity,
            'creationForm' => $creationForm->createView(),
        ];
    }

    protected function doSearch(Request $request)
    {
        if(!isset($this->searchFilterTypeClass)) return;
        $filterForm = $this->getForm($this->searchFilterTypeClass, null, [
            'enabled_filters' => ['id', 'naam', 'bsn', 'geboortedatum'],
        ]);
        $filterForm->handleRequest($request);


        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $count = (int) $this->searchDao->countAll($filterForm->getData());
            if (0 === $count) {
                $this->addFlash('info', sprintf('De zoekopdracht leverde geen resultaten op. Maak een nieuwe %s aan.', $this->entityName));

                return $this->redirectToRoute($this->baseRouteName.'add', ['entity' => 'new']);
            }

            if ($count > 100) {
                $filterForm->addError(new FormError('De zoekopdracht leverde teveel resultaten op. Probeer het opnieuw met een specifiekere zoekopdracht.'));

                return [
                    'filterForm' => $filterForm->createView(),
                ];
            }

            return [
                'zoekresultaten' => $this->searchDao->findAll(null, $filterForm->getData()),
                'filterForm' => $filterForm->createView(),

            ];
        }

        return [
            'filterForm' => $filterForm->createView(),
        ];
    }

    /**
     * @Route("/{id}/edit")
     * @Template
     */
    public function editAction(Request $request, $id)
    {
        if (in_array('edit', $this->disabledActions)) {
            throw new AccessDeniedHttpException();
        }

        $entity = $this->dao->find($id);

        return $this->processForm($request, $entity);
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


            } catch(UserException $e) {
//                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
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

    /**
     * @Route("/{id}/delete")
     * @Template
     * @var Request $request
     * @var int $id
     * @var bool Check $actief on entity.
     */
    public function deleteAction(Request $request, $id)
    {
        if (in_array('delete', $this->disabledActions)) {
            throw new AccessDeniedHttpException();
        }

        $entity = $this->dao->find($id);

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $url = $request->get('redirect');
                $viewUrl = $this->generateUrl($this->baseRouteName.'view', ['id' => $id]);

                if(method_exists($entity,"setActief"))
                {
                    $entity->setActief(false);
                    $this->dao->update($entity);
                }
                else
                {
                    $this->dao->delete($entity);
                }

                $this->addFlash('success', ucfirst($this->entityName).' is verwijderd.');

                if (!$this->forceRedirect) {
                    if ($url && false === strpos($viewUrl, $url)) {
                        return $this->redirect($url);
                    }
                }

                return $this->redirectToIndex();
            } else {
                if (isset($url)) {
                    return $this->redirect($url);
                }

                return $this->redirectToView($entity);
            }
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    protected function redirectToIndex()
    {
        if (!$this->baseRouteName) {
            throw new AppException(get_class($this).'::baseRouteName not set!');
        }

        return $this->redirectToRoute($this->baseRouteName.'index');
    }

    protected function redirectToView($entity)
    {
        if (!$this->baseRouteName) {
            throw new AppException(get_class($this).'::baseRouteName not set!');
        }

        return $this->redirectToRoute($this->baseRouteName.'view', ['id' => $entity->getId()]);
    }

    protected function createEntity($parentEntity = null)
    {
        return new $this->entityClass();
    }

    protected function beforeFind($id)
    {
        return;
    }

    protected function afterFind($entity)
    {
        return;
    }

    protected function beforeUpdate($entity)
    {
        return;
    }

    protected function beforeCreate($entity)
    {
        return;
    }

    protected function addParams($entity, Request $request)
    {
        return [];
    }

    protected function afterFormSubmitted(Request $request, $entity)
    {
        if (!$this->forceRedirect) {
            $url = $request->get('redirect');
            if ($url) {
                return $this->redirect($url);
            }
        }

        return $this->redirectToView($entity);
    }
}
