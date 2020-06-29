<?php

namespace AppBundle\Controller;

use AppBundle\Exception\AppException;
use AppBundle\Form\ConfirmationType;
use AppBundle\Model\MedewerkerSubjectInterface;
use AppBundle\Service\AbstractDao;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

abstract class AbstractChildController extends AbstractController
{
    /**
     * @var AbstractDao
     */
    protected $parentDao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @var string
     */
    protected $addMethod;

    /**
     * @var string
     */
    protected $deleteMethod;

    /**
     * @var bool
     */
    protected $allowEmpty = false;

    /**
     * @Route("/add")
     * @Template
     */
    public function addAction(Request $request)
    {
        if (!$this->addMethod && !$this->allowEmpty) {
            throw new \RuntimeException('Property $addMethod must be set in class '.get_class($this));
        }

        list($parentEntity, $this->parentDao) = $this->getParentConfig($request);
        if (!$parentEntity && !$this->allowEmpty) {
            throw new AppException(sprintf('Kan geen %s aan deze entiteit toevoegen', $this->entityName));
        }

        $entity = $this->createEntity($parentEntity);


        $form = $this->getForm($this->formClass, $entity, [
            'medewerker' => $this->getMedewerker(),
        ]);

        $form->handleRequest($request);

        /**
         * 20200629: JTB:
         * re-arranged order of calling of $this->>addMethod.
         * It was called before the request was handled and thus the entity was populated by POSTed values.
         * Therefore, any logic in addMethod would fail since the entity was null.
         * Perhaps addMethod is not the right place for logic, but thats another story so far.
         *
         */
        if ($parentEntity && $this->addMethod) {
            $parentEntity->{$this->addMethod}($entity);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            if ($entity instanceof MedewerkerSubjectInterface && !$entity->getMedewerker()) {
                $entity->setMedewerker($this->getMedewerker());
            }
            try {
                $this->beforeCreate($entity);
                $this->persistEntity($entity, $parentEntity);
                $this->addFlash('success', ucfirst($this->entityName).' is toegevoegd.');
            } catch (\Exception $e) {
                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            if ($parentEntity) {
                return $this->redirectToView($parentEntity);
            }

            return $this->redirectToView($entity);
        }

        return [
            'entity' => $entity,
            'parent_entity' => $parentEntity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/delete/")
     * @TODO Nagaan of hier wel autorisatie op zit (nee?)
     * @Template
     */
    public function deleteAction(Request $request, $id)
    {
        if (in_array('delete', $this->disabledActions)) {
            throw new AccessDeniedHttpException();
        }

        $entity = $this->dao->find($id);

        list($parentEntity, $this->parentDao) = $this->getParentConfig($request);

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = $request->get('redirect');
            if ($form->get('yes')->isClicked()) {

                try {
                    $viewUrl = $this->generateUrl($this->baseRouteName.'view', ['id' => $entity->getId()]);
                }
                catch(RouteNotFoundException $e)
                {
                   $viewUrl = "";
                }


                if ($parentEntity && $this->deleteMethod) {
                    $parentEntity->{$this->deleteMethod}($entity);
                    $this->parentDao->update($parentEntity);
                }
                $this->dao->delete($entity);
                $this->addFlash('success', ucfirst($this->entityName).' is verwijderd.');

                if ($url && false === strpos($viewUrl, $url)) {
                    return $this->redirect($url);
                }

                return $this->redirectToIndex();
            } else {
                if ($url) {
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

    protected function createEntity($parentEntity = null)
    {
        $x = new $this->entityClass();
        if($parentEntity!== null)
        {
            $class = (new \ReflectionClass($parentEntity))->getShortName();
            if(is_callable([$x,"set".$class])) $x->{"set".ucfirst($class)}($parentEntity);
        }
        return $x;

    }

    protected function persistEntity($entity, $parentEntity)
    {
        if (!$parentEntity && $this->allowEmpty) {
            $this->dao->create($entity);
        } else {
            $this->parentDao->update($parentEntity);
        }
    }

    protected function getParentConfig(Request $request)
    {
        if (!$this->entities) {
            throw new AppException(sprintf('No entities are configured for controller %s', get_class($this)));
        }

        foreach ($this->entities as $entity) {
            if (is_null($entity['key'])) {
                $this->allowEmpty = true;
                continue;
            }
            if ($request->query->has($entity['key'])) {
                return [
                    $entity['dao']->find($request->query->get($entity['key'])),
                    $entity['dao'],
                ];
            }
        }
    }
}
