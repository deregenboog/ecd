<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Export\AbstractExport;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\KlantFilterType;
use AppBundle\Form\KlantType;
use AppBundle\Service\KlantDaoInterface;
use Doctrine\Common\Collections\Criteria;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @Route("/klanten")
 * @Template
 */
class KlantenController extends AbstractController
{
    protected $title = 'Klanten';
    protected $entityName = 'klant';
    protected $entityClass = Klant::class;
    protected $formClass = KlantType::class;
    protected $filterFormClass = KlantFilterType::class;
    protected $baseRouteName = 'app_klanten_';

    /**
     * @var KlantDaoInterface
     */
    protected $dao;

    /**
     * @var AbstractExport
     */
    protected $export;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("AppBundle\Service\KlantDao");
        $this->export = $container->get("app.export.klanten");

        return $previous;
    }

    /**
     * @Template
     */
    public function _dienstenAction($id)
    {
        $event = new DienstenLookupEvent($id);
        if ($event->getKlantId()) {
            $this->get('event_dispatcher')->dispatch($event, Events::DIENSTEN_LOOKUP);
        }

        return [
            'diensten' => $event->getDiensten(),
        ];
    }

    /**
     * @Route("/{klant}/{documentId}/deleteDocument/")
     * @param Request $request
     * @param $documentId
     */
    public function deleteDocumentAction(Request $request, $klant, $documentId)
    {
        if (in_array('delete', $this->disabledActions)) {
            throw new AccessDeniedHttpException();
        }

        $klant = $this->dao->find($klant);

        $docs = $klant->getDocumenten(); //->matching($criteria)->first();
        $entity = null;
        foreach($docs as $d)
        {
            if($d->getId() == $documentId){
                $entity = $d;
                break;
            }
        }

        if(!$this->isGranted('ROLE_ADMIN')
            && $entity->getMedewerker()->getId() != $this->getUser()->getId()
        ) {
            throw new AccessDeniedHttpException();
        }

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $url = $request->get('redirect');
                $viewUrl = $this->generateUrl($this->baseRouteName.'view', ['id' => $entity->getId()]);


                $docs->removeDocument($entity);

                /**
                 * Somehow, it wont remove...
                 */
                $docDao = new \AppBundle\Service\DocumentDao($this->getEntityManager());
                $docDao->delete($entity);

                $this->dao->update($klant);


                $this->addFlash('success', 'Document is verwijderd.');

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
        ];
    }

    protected function addParams($entity, Request $request)
    {
        assert($entity instanceof Klant);

        $event = new DienstenLookupEvent($entity->getId());
        if ($event->getKlantId()) {
            $this->get('event_dispatcher')->dispatch($event, Events::DIENSTEN_LOOKUP);
        }

        return [
            'diensten' => $event->getDiensten(),
            'tbc_countries' => $this->container->getParameter('tbc_countries')
        ];
    }
}
