<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Postcode;
use AppBundle\Exception\UserException;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\PostcodeFilterType;
use AppBundle\Form\PostcodeType;
use AppBundle\Service\PostcodeDao;
use AppBundle\Service\PostcodeDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/postcodes")
 * @Template
 * @Security("has_role('ROLE_ADMIN')")
 */
class PostcodesController extends AbstractController
{
    protected $title = 'Postcodes';
    protected $entityName = 'postcode';
    protected $entityClass = Postcode::class;
    protected $filterFormClass = PostcodeFilterType::class;
    protected $baseRouteName = 'app_postcodes_';
    protected $disabledActions = ['view'];

    /**
     * @var PostcodeDao
     */
    protected $dao;

    /**
     * @param PostcodeDao $dao
     */
    public function __construct(PostcodeDao $dao)
    {
        $this->dao = $dao;
    }


    /**
     * @Route("/add")
     * @Template
     */
    public function addAction(Request $request)
    {
        $entity = new Postcode();
        $entity->setSystem(false);

        $form = $this->getForm(PostcodeType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->create($entity);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');
            } catch(UserException $e) {
                $message = $e->getMessage();
                $this->addFlash('danger',$message);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->afterFormSubmitted($request, $entity, null);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/edit")
     * @Template
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        if ($entity->isSystem()) {
            $this->addFlash('warning', 'Deze postcode kan niet gewijzigd worden.');

            return $this->redirectToIndex();
        }

        $form = $this->getForm(PostcodeType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');
            } catch(UserException $e) {
                $message = $e->getMessage();
                $this->addFlash('danger',$message);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->afterFormSubmitted($request, $entity, null);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/delete")
     * @Template
     */
    public function deleteAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        if ($entity->isSystem()) {
            $this->addFlash('warning', 'Deze postcode kan niet verwijderd worden.');

            return $this->redirectToIndex();
        }

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $this->dao->delete($entity);
                $this->addFlash('success', ucfirst($this->entityName).' is verwijderd.');
            }

            return $this->redirectToIndex();
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }
}
