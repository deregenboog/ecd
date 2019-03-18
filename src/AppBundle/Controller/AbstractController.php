<?php

namespace AppBundle\Controller;

use AppBundle\Exception\AppException;
use AppBundle\Export\ExportInterface;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\ConfirmationType;
use AppBundle\Service\AbstractDao;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
            $form = $this->createForm($this->filterFormClass);
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
        $entity = $this->dao->find($id);
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

        $entity = new $this->entityClass();

        return $this->processForm($request, $entity);
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

        $form = $this->createForm($this->formClass, $entity, [
            'medewerker' => $this->getMedewerker(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($entity->getId()) {
                    $this->dao->update($entity);
                } else {
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

    /**
     * @Route("/{id}/delete")
     * @Template
     */
    public function deleteAction(Request $request, $id)
    {
        if (in_array('delete', $this->disabledActions)) {
            throw new AccessDeniedHttpException();
        }

        $entity = $this->dao->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $url = $request->get('redirect');
                $viewUrl = $this->generateUrl($this->baseRouteName.'view', ['id' => $entity->getId()]);

                $this->dao->delete($entity);
                $this->addFlash('success', ucfirst($this->entityName).' is verwijderd.');

                if ($url && false === strpos($viewUrl, $url)) {
                    return $this->redirect($url);
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
