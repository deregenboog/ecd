<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use DagbestedingBundle\Exception\DagbestedingException;
use HsBundle\Exception\HsException;
use AppBundle\Exception\AppException;

class AbstractChildController extends AbstractController
{
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
        list($parentEntity, $parentDao) = $this->getParentConfig($request);
        $entity = new $this->entityClass();

        $form = $this->createForm($this->formClass, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if (!$parentEntity && $this->allowEmpty) {
                    $this->dao->create($entity);
                } else {
                    $parentEntity->{$this->addMethod}($entity);
                    $parentDao->update($parentEntity);
                }
                $this->addFlash('success', ucfirst($this->entityName).' is toegevoegd.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
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
