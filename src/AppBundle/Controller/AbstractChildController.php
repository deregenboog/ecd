<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        if (!$this->addMethod) {
            throw new \RuntimeException('Property $addMethod must be set in class '.get_class($this));
        }

        list($parentEntity, $parentDao) = $this->getParentConfig($request);
        $entity = new $this->entityClass();

        $form = $this->createForm($this->formClass, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $parentEntity->{$this->addMethod}($entity);
                $parentDao->update($parentEntity);
                $this->addFlash('success', ucfirst($this->entityName).' is toegevoegd.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToView($parentEntity);
        }

        return [
            'entity' => $entity,
            'parent_entity' => $parentEntity,
            'form' => $form->createView(),
        ];
    }

    protected function getParentConfig(Request $request)
    {
        foreach ($this->entities as $entity) {
            if ($request->query->has($entity['key'])) {
                return [
                    $entity['dao']->find($request->query->get($entity['key'])),
                    $entity['dao'],
                ];
            }
        }

        throw new \RuntimeException(sprintf('Kan geen %s aan deze entiteit toevoegen', $this->entityName));
    }
}
