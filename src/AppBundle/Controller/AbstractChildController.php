<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\AppException;
use AppBundle\Service\AbstractDao;

class AbstractChildController extends AbstractController
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
     * @var bool
     */
    protected $allowEmpty = false;

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        if (!$this->addMethod && !$this->allowEmpty) {
            throw new \RuntimeException('Property $addMethod must be set in class '.get_class($this));
        }

        list($parentEntity, $this->parentDao) = $this->getParentConfig($request);
        $entity = $this->createEntity($parentEntity);
        if ($parentEntity && $this->addMethod) {
            $parentEntity->{$this->addMethod}($entity);
        }

        $form = $this->createForm($this->formClass, $entity, [
            'medewerker' => $this->getMedewerker(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if (!$parentEntity && $this->allowEmpty) {
                    $this->dao->create($entity);
                } else {
                    $this->parentDao->update($parentEntity);
                }
                $this->addFlash('success', ucfirst($this->entityName).' is toegevoegd.');
            } catch (\Exception $e) {
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

        if (!$this->allowEmpty) {
            throw new AppException(sprintf('Kan geen %s aan deze entiteit toevoegen', $this->entityName));
        }
    }
}
