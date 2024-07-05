<?php

namespace AppBundle\Controller;

use AppBundle\Exception\AppException;
use AppBundle\Exception\UserException;
use AppBundle\Form\ConfirmationType;
use AppBundle\Model\MedewerkerSubjectInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
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
     *
     * @Template
     */
    public function addAction(Request $request)
    {
        if (!$this->addMethod && !$this->allowEmpty) {
            throw new \RuntimeException('Property $addMethod must be set in class '.get_class($this));
        }

        [$parentEntity, $this->parentDao] = $this->getParentConfig($request);
        if (!$parentEntity && !$this->allowEmpty) {
            throw new AppException(sprintf('Kan geen %s aan deze entiteit toevoegen. Extra data: \n queryString: %s \nUser: %s', $this->entityName, $request->getQueryString(), $request->getUserInfo()));
        }

        $entity = $this->createEntity($parentEntity);

        if ($parentEntity && $this->addMethod) {
            $parentEntity->{$this->addMethod}($entity);
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
                $this->beforeCreate($entity);
                $this->persistEntity($entity, $parentEntity);
                $this->afterCreate($entity);
                $this->addFlash('success', ucfirst($this->entityName).' is toegevoegd.');
            } catch (UserException $e) {
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
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

        $return = [
            'entity' => $entity,
            'parent_entity' => $parentEntity,
            'form' => $form->createView(),
        ];

        return array_merge($return, $this->addParams($entity, $request));
    }

    /**
     * @Route("/{id}/delete/")
     *
     * @TODO Nagaan of hier wel autorisatie op zit (nee?)
     *
     * @Template
     */
    public function deleteAction(Request $request, $id)
    {
        if (in_array('delete', $this->disabledActions)) {
            throw new AccessDeniedHttpException();
        }

        $entity = $this->dao->find($id);

        [$parentEntity, $this->parentDao] = $this->getParentConfig($request);

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = $request->get('redirect');
            if ($form->get('yes')->isClicked()) {
                try {
                    $viewUrl = $this->generateUrl($this->baseRouteName.'view', ['id' => $entity->getId()]);
                } catch (RouteNotFoundException $e) {
                    $viewUrl = '';
                }
                $this->beforeDelete($entity);
                if ($parentEntity && $this->deleteMethod) {
                    $parentEntity->{$this->deleteMethod}($entity);
                    $this->parentDao->update($parentEntity);
                }

                try {
                    $this->dao->delete($entity);
                    $this->addFlash('success', ucfirst($this->entityName).' is verwijderd.');
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
                $this->afterDelete($entity);

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

        $return = [
            'entity' => $entity,
            'entity_name' => $this->entityName,
            'form' => $form->createView(),
        ];

        return array_merge($return, $this->addParams($entity, $request));
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
