<?php

namespace AppBundle\Controller;

use AppBundle\Exception\UserException;
use AppBundle\Form\ConfirmationType;
use AppBundle\Model\HasDossierStatusInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


trait DossierStatusControllerTrait
{
    /**
     * @Route("/{id}/editDossierStatus")
     * @Template("edit.html.twig")
     */
    public function editDossierStatusAction($id)
    {

        $entity = $this->dao->find($id);
        if(!$entity instanceof HasDossierStatusInterface)
        {
            throw new UserException("Kan geen dossierstatus bewerken van een entiteit die geen dossierstatus heeft.");
        }

        $ds = $entity->getHuidigeDossierstatus();


        $form = $this->getForm($ds->getFormType(), $ds);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->updateDossierStatus($ds);
                $this->addFlash('success', 'De dossierstatus is bewerkt.');

                return $this->redirectToRoute($this->baseRouteName.'view', ['id' => $entity->getId()]);
            } catch(UserException $e) {
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
        }

        return [
            'form' => $form->createView(),
            'entity' => $entity,
        ];
    }

    /**
     * @Route("/{id}/open")
     * @Template("open.html.twig")
     */
    public function openAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        if(!$entity instanceof HasDossierStatusInterface)
        {
            throw new UserException("Kan geen dossierstatus bewerken van een entiteit die geen dossierstatus heeft.");
        }

        $ds = $entity->getHuidigeDossierstatus();
        if(get_class($ds) !== $ds->getClosedClass() )
        {
            throw new UserException("Kan dossier niet openen want er is geen geldige dossierstatus (afgesloten).");
        }

        $aanmeldingClassname = $ds->getOpenClass();
        $aanmelding = new $aanmeldingClassname();
        $entity->addDossierStatus($aanmelding);

        $form = $this->getForm($aanmelding->getFormType(), $aanmelding);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);
                $this->addFlash('success', 'Het dossier is (opnieuw) geopend.');

                return $this->redirectToRoute($this->baseRouteName.'view', ['id' => $entity->getId()]);
            } catch(UserException $e) {
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
        }

        return [
            'form' => $form->createView(),
            'entity' => $entity,
        ];
    }

    /**
     * @Route("/{id}/close")
     * @Template("close.html.twig")
     */
    public function closeAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        if(!$entity instanceof HasDossierStatusInterface)
        {
            throw new UserException("Kan geen dossierstatus bewerken van een entiteit die geen dossierstatus heeft.");
        }

        $ds = $entity->getHuidigeDossierstatus();
        if(get_class($ds) !== $ds->getOpenClass() )
        {
            throw new UserException("Kan dossier niet sluiten want er is geen geldige dossierstatus (open of aangemeld).");
        }

        $afsluitingClassname = $ds->getClosedClass();
        $afsluiting = new $afsluitingClassname();
        $entity->addDossierStatus($afsluiting);

        $form = $this->getForm($afsluiting->getFormType(), $afsluiting);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);
                $this->addFlash('success', 'Het dossier is afgesloten.');

                return $this->redirectToRoute($this->baseRouteName.'view', ['id' => $entity->getId()]);
            } catch(UserException $e) {
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
        }

        return [
            'form' => $form->createView(),
            'entity' => $entity,
        ];
    }

    /**
     * @Route("/{id}/deleteDossierStatus/{statusId}")]
     *
     * @Template("delete.html.twig")
     */
    public function deleteDossierStatusAction(Request $request, $id, $statusId)
    {
//        $this->dao = new ($this->entityManager, new Paginator($this->eventDispatcher));

        $entity = $this->dao->find($id);
        if(!$entity instanceof HasDossierStatusInterface )
        {
            throw new UserException("Kan geen dossierstatus bewerken van een entiteit die geen dossierstatus heeft.");
        }

        $ds = $entity->getDossierStatusById($statusId);
        if(null === $ds)
        {
            throw new UserException("Kan geen dossierstatus niet verwijderen want kan dossierstatus niet vinden..");
        }

        if(count($entity->getDossierStatussen()) < 2)
        {
            throw new UserException("Kan geen dossierstatus niet verwijderen want er moet altijd 1 dossierstatus aanwezig zijn.");
        }


        if (in_array('delete', $this->disabledActions)) {
            throw new AccessDeniedHttpException();
        }


        $entity = $ds;

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = $request->get('redirect');
            if ($form->get('yes')->isClicked()) {
                $viewUrl = $this->generateUrl($this->baseRouteName.'view', ['id' => $id]);


                $this->dao->removeDossierStatus($ds);

                $this->addFlash('success', 'Dossierstatus is verwijderd.');

                if (!$this->forceRedirect) {
                    if ($url && false === strpos($viewUrl, $url)) {
                        return $this->redirect($url);
                    }
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
            'entity_name'=>'dossierstatus'
        ];




    }

}