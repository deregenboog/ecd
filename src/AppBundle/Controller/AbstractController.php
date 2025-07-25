<?php

namespace AppBundle\Controller;

use AppBundle\Exception\AppException;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\ConfirmationType;
use AppBundle\Model\MedewerkerSubjectInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Vich\UploaderBundle\Handler\DownloadHandler;

abstract class AbstractController extends SymfonyController
{
    /**
     * Entity to deal with in this controller.
     *
     * @var object
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var string
     */
    protected $formClass;

    /**
     * @var array
     */
    protected $formOptions = [];

    /**
     * @var string
     */
    protected $filterFormClass;

    /**
     * If adding entities needs searching for klanten or vrijwiliigers (as is the case with many modules)
     * then one needs to search first for existing entities. this is done with this filter type.
     *
     * @var string
     */
    protected $searchFilterTypeClass;

    /**
     * This is the dao used for searching entities and if they exist.
     *
     * @var AbstractDao
     */
    protected $searchDao;

    /**
     * This entity is created when there is no existing.
     * (ie. klant or vrvijwilliger).
     *
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

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $addMethod;

    /**
     * @required
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @required
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @required
     *
     * @return void
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
     *
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
     * @Route("/download/{filename}")
     */
    public function downloadAction(DownloadHandler $downloadHandler, $filename)
    {
        $document = $this->dao->findByFilename($filename);

        return $downloadHandler->downloadObject($document, 'file');
    }

    /**
     * @Route("/{id}/view")
     *
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
        try {
            $entity = $this->dao->find($id);
        } catch (EntityNotFoundException $entityNotFoundException) {
            $message = $this->getParameter('kernel.debug') ? $entityNotFoundException->getMessage() : 'Kan '.$this->entityClass.' niet inladen. Waarschijnlijk omdat deze verwijderd of inactief is.';
        } catch (UserException $e) {
            $message = $e->getMessage();
        } catch (\Exception $exception) {
            $message = $this->getParameter('kernel.debug') ? $exception->getMessage() : 'Kan '.$this->entityClass.' niet inladen. Onbekende fout.';
        }
        if ($message) {
            $this->addFlash('danger', $message);

            return $this->redirect($request->get('redirect'));
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
     *
     * @Template
     */
    public function addAction(Request $request)
    {
        if (in_array('add', $this->disabledActions)) {
            throw new AccessDeniedHttpException();
        }
        if (
            ( !isset($this->addMethod) || !is_callable($this, $this->addMethod) )
            && !isset($this->searchFilterTypeClass)) {
            return $this->doAdd($request);
        } elseif (isset($this->searchFilterTypeClass)) {
            if ($request->get('entity') || $request->get('klant')) {
                return $this->doAdd($request);
            }
            return $this->doSearch($request);
        } else {
            return $this->{$this->addMethod}($request);
        }
    }

    protected function doAdd(Request $request)
    {
        $entityId = $request->get('entity');

        if ('new' === $entityId) {
            $searchEntity = new $this->searchEntity();
        } elseif (null == $entityId) {
            $entity = new $this->entityClass();

            return $this->processForm($request, $entity);
        } else {
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
        $creationForm = $this->getForm($this->formClass, $subEntity, $this->formOptions);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {
                $this->beforeCreate($subEntity);
                $this->dao->create($subEntity);
                $this->afterCreate($subEntity);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

                return $this->redirectToView($subEntity);
            } catch (UserException $e) {
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
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
        if (!isset($this->searchFilterTypeClass)) {
            return;
        }
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
     *
     * @Template
     */
    public function editAction(Request $request, $id)
    {

        if (in_array('edit', $this->disabledActions)) {
            throw new AccessDeniedHttpException();
        }

        $entity = $this->dao->find($id);
        $this->beforeEdit($entity);

        $return = $this->processForm($request, $entity);

        $this->afterEdit($entity);

        return $return;
    }

    protected function processForm(Request $request, $entity = null)
    {
        if (!$this->formClass) {
            throw new AppException(get_class($this).'::formClass not set!');
        }

        $form = $this->getForm($this->formClass, $entity, array_merge($this->formOptions, [
            'medewerker' => $this->getMedewerker(),
        ]));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($entity instanceof MedewerkerSubjectInterface && !$entity->getMedewerker()) {
                $entity->setMedewerker($this->getMedewerker());
            }
            try {
                if ($entity->getId()) {
                    $this->beforeUpdate($entity);
                    $this->dao->update($entity);
                    $this->afterUpdate($entity);
                } else {
                    $this->beforeCreate($entity);
                    $this->dao->create($entity);
                    $this->afterCreate($entity);
                }
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');
            } catch (UserException $e) {
                //                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->afterFormSubmitted($request, $entity, $form);
        }

        return array_merge([
            'entity' => $entity,
            'form' => $form->createView(),
            'redirect' => $request->get('redirect'),
        ], $this->addParams($entity, $request));
    }

    protected function getForm($type, $data = null, array $options = [])
    {
        $this->beforeGetForm($type, $data, $options);
        $form = parent::getForm($type, $data, $options);

        return $this->afterGetForm($form);
    }

    /**
     * @Route("/{id}/delete")
     *
     * @Template
     *
     * @var Request
     * @var int
     * @var bool    check on entity
     */
    public function deleteAction(Request $request, $id)
    {
        if (in_array('delete', $this->disabledActions)) {
            throw new AccessDeniedHttpException();
        }

        $entity = $this->dao->find($id);

        try{
            if(!$this->beforeDeleteCheck($entity)) {
                return $this->redirectToIndex();
            }
        } catch (\Exception $e) {
            $this->addFlash('danger', ucfirst($this->entityName).' kan niet verwijderd worden: '.$e->getMessage());
            return $this->redirectToIndex();
        }

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = $request->get('redirect');
            if ($form->get('yes')->isClicked()) {
                $viewUrl = $this->generateUrl($this->baseRouteName.'view', ['id' => $id]);

                try {
                    $this->beforeDelete($entity);
                    if ($this->checkSoftDelete($entity)) {
                        $entity->setActief(false);
                        $this->dao->update($entity);
                    } else {
                        $this->dao->delete($entity);
                    }
                    $this->afterDelete($entity);
                    $this->addFlash('success', ucfirst($this->entityName).' is verwijderd.');

                    if (!$this->forceRedirect) {
                        if ($url && false === strpos($viewUrl, $url)) {
                            return $this->redirect($url);
                        }
                    }

                    return $this->redirectToIndex();
                } catch (ForeignKeyConstraintViolationException $exception) {
                    /**
                     * Regex to filter out the foreign key which prevents the deletion.
                     * From there, with class metadata and reflection, the tablename gets matched to the entity,
                     * so a helpful errormessage can be displayed.
                     *
                     * https://regex101.com/r/0Fajyz/1
                     */
                    $re = '/.*\(`.*`\..*`(.*)`, CONSTRAINT .*/m';

                    preg_match_all($re, $exception->getMessage(), $matches, PREG_SET_ORDER, 0);
                    $entityRaw = null;
                    if (sizeof($matches) > 0 && is_array($matches[0]) && null !== $matches[0][1]) {
                        $entityRaw = $matches[0][1];
                    }
                    $md = $this->entityManager->getMetadataFactory()->getAllMetadata();
                    $entityName = "'onbekend'";
                    foreach ($md as $classMetadata) {
                        if ($classMetadata->getTableName() == $entityRaw) {
                            $refl = $classMetadata->getReflectionClass();
                            $entityName = $refl->getShortName();
                        }
                    }

                    $this->addFlash('danger', ucfirst($this->entityName).sprintf(' kan niet verwijderd worden omdat er nog een of meerdere onderdelen van het type %s aanwezig zijn. Verwijder deze eerst om verder te gaan.', strtolower($entityName)));
                }
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

        try {
            $url = $this->generateUrl($this->baseRouteName.'index');
        } catch (RouteNotFoundException $e) {
            $url = $this->generateUrl('home');
        }

        return $this->redirect($url);
    }

    protected function redirectToView($entity)
    {
        if (!$this->baseRouteName) {
            throw new AppException(get_class($this).'::baseRouteName not set!');
        }
        $url = '/';
        try {
            $url = $this->generateUrl($this->baseRouteName.'view', ['id' => $entity->getId()]);
        } catch (RouteNotFoundException $e) {
            $this->redirectToIndex();
        } catch (InvalidParameterException $e) {
            $this->redirectToIndex();
        }

        return $this->redirect($url);
    }

    protected function createEntity($parentEntity = null)
    {
        $entity = $this->getEntity();
        if (null !== $parentEntity) {
            $class = (new \ReflectionClass($parentEntity))->getShortName();
            if (is_callable([$entity, 'set'.$class])) {
                $entity->{'set'.ucfirst($class)}($parentEntity);
            }
        }

        return $entity;
    }

    /**
     * Can be overriden to implement ie. logic to fill the new entity with data from controller/request.
     *
     * @return object
     */
    protected function getEntity()
    {
        $this->entity = $this->entity ?? new $this->entityClass();

        return $this->entity;
    }

    protected function beforeFind($id): void
    {
    }

    protected function afterFind($entity): void
    {
    }

    protected function beforeDelete($entity): void
    {
    }

    /// Check if the entity can be deleted.
    protected function beforeDeleteCheck($entity): bool
    {
        return true;
    }

    /// If the entity is soft-deletable, then this method can be used to check if it should be soft-deleted.
    protected function checkSoftDelete($entity): bool
    {
        return method_exists($entity, 'setActief');
    }
    

    protected function afterDelete($entity): void
    {
    }

    protected function beforeEdit($entity): void
    {
    }

    protected function afterEdit($entity): void
    {
    }

    protected function beforeUpdate($entity): void
    {
    }

    protected function afterUpdate($entity): void
    {
    }

    protected function beforeCreate($entity): void
    {
    }

    protected function afterCreate($entity): void
    {
    }

    protected function beforeGetForm(&$type, &$data, &$options): void
    {
    }

    protected function afterGetForm($form): Form
    {
        return $form;
    }

    protected function addParams($entity, Request $request): array
    {
        return [];
    }

    protected function afterFormSubmitted(Request $request, $entity, $form = null)
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
