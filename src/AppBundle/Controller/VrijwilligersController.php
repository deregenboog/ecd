<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Overeenkomst;
use AppBundle\Entity\Vog;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Event\DienstenVrijwilligerLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Export\AbstractExport;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\VrijwilligerFilterType;
use AppBundle\Form\VrijwilligerType;
use AppBundle\Service\VrijwilligerDaoInterface;
use Doctrine\Common\Collections\Criteria;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vrijwilligers")
 *
 * @Template
 */
class VrijwilligersController extends AbstractController
{
    protected $entityName = 'vrijwilliger';
    protected $entityClass = Vrijwilliger::class;
    protected $formClass = VrijwilligerType::class;
    protected $filterFormClass = VrijwilligerFilterType::class;
    protected $baseRouteName = 'app_vrijwilligers_';

    /**
     * @var VrijwilligerDaoInterface
     */
    protected $dao;

    /**
     * @var AbstractExport
     */
    protected $export;

    public function __construct(VrijwilligerDaoInterface $dao, AbstractExport $export)
    {
        $this->dao = $dao;
        $this->export = $export;
    }

    /**
     * @Route("/{vrijwilliger}/{documentId}/deleteDocument/")
     */
    public function deleteDocumentAction(Request $request, $vrijwilliger, $documentId)
    {
        if (in_array('delete', $this->disabledActions)) {
            throw new AccessDeniedHttpException();
        }

        $vrijwilliger = $this->dao->find($vrijwilliger);

        // $criteria = Criteria::create()->where(Criteria::expr()->eq("id", $documentId));
        $docs = $vrijwilliger->getDocumenten(); // ->matching($criteria)->first();
        $entity = null;
        foreach ($docs as $d) {
            if ($d->getId() == $documentId) {
                $entity = $d;
                break;
            }
        }

        if (!$this->isGranted('ROLE_SECRETARIAAT') && null !== $entity
            && $entity->getMedewerker()->getId() != $this->getUser()->getId()
        ) {
            throw new AccessDeniedHttpException();
        }

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = $request->get('redirect');
            if ($form->get('yes')->isClicked()) {
                $viewUrl = $this->generateUrl($this->baseRouteName.'view', ['id' => $entity->getId()]);

                $vrijwilliger->removeDocument($entity);
                /**
                 * Somehow, it wont remove...
                 */
                $docDao = new \AppBundle\Service\DocumentDao($this->getEntityManager());
                $docDao->delete($entity);

                if ($entity instanceof Vog) {
                    $vrijwilliger->setVogAanwezig(false);
                } elseif ($entity instanceof Overeenkomst) {
                    $vrijwilliger->setOvereenkomstAanwezig(false);
                }

                $this->dao->update($vrijwilliger);

                $shortname = new \ReflectionClass($entity);
                $shortname = $shortname->getShortName();

                $this->addFlash('success', $shortname.' is verwijderd.');

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

    /**
     * @Template
     */
    public function _dienstenAction($id)
    {
        return [
            'diensten' => $this->lookupDiensten($id),
        ];
    }

    protected function addParams($entity, Request $request): array
    {
        assert($entity instanceof Vrijwilliger);

        return [
            'diensten' => $this->lookupDiensten($entity->getId()),
        ];
    }

    protected function lookupDiensten(int $vrijwilligerId): array
    {
        $event = new DienstenVrijwilligerLookupEvent($vrijwilligerId);
        $this->eventDispatcher->dispatch($event, Events::DIENSTEN_VRIJWILLIGER_LOOKUP);

        return $event->getDiensten();
    }
}
