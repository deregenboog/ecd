<?php

namespace AppBundle\Controller;

use AppBundle\Form\ConfirmationType;
use AppBundle\Service\AbstractDao;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Filter\FilterInterface;
use AppBundle\Export\ExportInterface;
use AppBundle\Exception\AppException;

class AbstractController extends SymfonyController
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
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $filter = null;

        if ($this->filterFormClass) {
            $form = $this->createForm($this->filterFormClass);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->has('download') && $form->get('download')->isClicked()) {
                    return $this->download($form->getData());
                }
                $filter = $form->getData();
            }
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
     */
    public function viewAction($id)
    {
        return ['entity' => $this->dao->find($id)];
    }

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        $entity = new $this->entityClass();

        return $this->processForm($request, $entity);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        return $this->processForm($request, $entity);
    }

    protected function processForm(Request $request, $entity)
    {
        $form = $this->createForm($this->formClass, $entity);
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
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToView($entity);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $this->dao->delete($entity);
                $this->addFlash('success', $this->entityName.' is verwijderd.');

                if ($url = $request->get('redirect')) {
                    return $this->redirect($url);
                }

                return $this->redirectToIndex();
            } else {
                if ($url = $request->get('redirect')) {
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

    public function getTemplatePath()
    {
        return $this->templatePath;
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
}
