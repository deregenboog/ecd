<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use DagbestedingBundle\Exception\DagbestedingException;

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
        list($parentEntity, $parentDao) = $this->getParentConfig($request);
        $entity = new $this->entityClass;

        $form = $this->createForm($this->formClass, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $parentEntity->{$this->addMethod}($entity);
                $parentDao->update($parentEntity);
                $this->addFlash('success', $this->entityName.' is toegevoegd.');
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

        throw new DagbestedingException(sprintf('Kan geen %s aan deze entiteit toevoegen', $this->entityName));
    }
}
