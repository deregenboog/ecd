<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Exception\UserException;
use Doctrine\ORM\EntityNotFoundException;
use InloopBundle\Entity\AccessFields;
use InloopBundle\Form\AccessFieldsType;
use InloopBundle\Form\IntakeType;
use InloopBundle\Form\ToegangType;
use InloopBundle\Pdf\PdfIntake;
use InloopBundle\Security\Permissions;
use InloopBundle\Service\AccessFieldsDaoInterface;
use InloopBundle\Service\IntakeDaoInterface;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/accessfields")
 *
 * @Template
 */
class AccessFieldsController extends AbstractController
{
    protected $entityName = 'accessfields';
    protected $entityClass = AccessFields::class;
    protected $formClass = AccessFieldsType::class;
    protected $filterFormClass = AccessFieldsType::class;
    protected $baseRouteName = 'inloop_accessfields_';

    /**
     * @var AccessFieldsDaoInterface
     */
    protected $dao;
    /**
     * @var IntakeDaoInterface
     */
    protected $intakeDao;

    public function __construct(AccessFieldsDaoInterface $dao, IntakeDaoInterface $intakeDao)
    {
        $this->dao = $dao;
        $this->intakeDao = $intakeDao;
    }

    /**
     * @Route("/add/{id}")
     *
     * @ParamConverter("klant", class="AppBundle\Entity\Klant")
     */
    public function addAction(Request $request)
    {
        $intake = $this->intakeDao->find($request->get('id'));
        if (!$intake) {
            throw new EntityNotFoundException('Intake not found');
        }
        $entity = $this->dao->find($request->get('id'));
        $klant = $request->get('klant');
        
        if ($entity) {
            $entity = clone $klant->getLaatsteIntake();
            $this->getEntityManager()->detach($entity);
            $this->formClass = IntakeType::class; // because it is not the first one, dont show toegang form.
        } else {
            $entity = new AccessFields($intake);
            $entity->setKlant($klant);
        }

        $form = $this->getForm($this->formClass, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->create($entity);
                $intake->setAccessFields($entity);
                $this->intakeDao->update($intake);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');
            } catch (UserException $e) {
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->afterFormSubmitted($request, $entity, null);
        }

        return array_merge([
            'entity' => $entity,
            'form' => $form->createView(),
            'redirect' => $request->get('redirect'),
        ], $this->addParams($entity, $request));
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        return $this->processForm($request, $entity);
    }
}
