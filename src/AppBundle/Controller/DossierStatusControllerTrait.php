<?php

namespace AppBundle\Controller;

use AppBundle\Exception\UserException;
use AppBundle\Model\HasDossierStatusInterface;
use Symfony\Component\HttpFoundation\Request;

trait DossierStatusControllerTrait
{
    /**
     * @Route("/{id}/editDossierStatus")
     * @Template("edit.html.twig")
     */
    public function editDossierStatusAction($id): array
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
                $this->dao->update($entity);
                $this->addFlash('success', 'De dossierstatus is bewerkt.');

                return $this->redirectToRoute($this->baseRouteName.'view', ['id' => $slaper->getId()]);
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
    public function openAction(Request $request, $id): array
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
    public function closeAction(Request $request, $id): array
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

}