<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Export\AbstractExport;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\KlantFilterType;
use AppBundle\Form\KlantType;
use AppBundle\Service\KlantDao;
use AppBundle\Service\KlantDaoInterface;
use Doctrine\Common\Collections\Criteria;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @Route("/klanten")
 * @Template
 */
class KlantenController extends AbstractController
{
    protected $entityName = 'klant';
    protected $entityClass = Klant::class;
    protected $formClass = KlantType::class;
    protected $filterFormClass = KlantFilterType::class;
    protected $baseRouteName = 'app_klanten_';
    protected $searchFilterTypeClass = KlantFilterType::class;
    protected $searchEntity = Klant::class;

    /**
     * @var KlantDaoInterface
     */
    protected $searchDao;

    /**
     * @var KlantDaoInterface
     *
     */
    protected $dao;

    /**
     * @var AbstractExport
     */
    protected $export;

    /** @var array|mixed $tbc_countries List of countries where TBC check is mandatory */
    protected $tbc_countries = [];

    public function __construct(KlantDaoInterface $dao, KlantDaoInterface $searchDao, $export, $tbc_countries = [])
    {
        $this->searchDao=$searchDao;
        $this->dao=$dao;
        $this->export = $export;

        $this->tbc_countries=$tbc_countries;


    }

    /**
     * @Template
     */
    public function _dienstenAction($id)
    {
        $event = new DienstenLookupEvent($id);
        if ($event->getKlantId()) {
            $this->eventDispatcher->dispatch($event,Events::DIENSTEN_LOOKUP);
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
            $url = $request->get('redirect');
            if ($form->get('yes')->isClicked()) {
                $viewUrl = $this->generateUrl($this->baseRouteName.'view', ['id' => $entity->getId()]);


                $docs->removeElement($entity);

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
    protected function addParams($entity, Request $request): array
    {
        assert($entity instanceof Klant);

        $event = new DienstenLookupEvent($entity->getId());
        if ($event->getKlantId()) {
            $this->eventDispatcher->dispatch($event,Events::DIENSTEN_LOOKUP);
        }

        return [
            'diensten' => $event->getDiensten(),
            'tbc_countries' => $this->tbc_countries
        ];
    }


    /**
     * @Route("/{klant}/addPartner")
     * ParamConverter("klant", class="AppBundle\Entity\Klant")
     */
    public function addPartnerAction(Request $request, $klant)
    {
        if ($request->get('partner')) {
            return $this->doAddPartner($request, $klant);
        }

        $redirect = $request->get('redirect');
        $ret =  $this->doSearch($request);

        if(!is_array($ret)) { //when no match is found, not 'create new' but other behaviour...

            $this->container->get('session')->getFlashBag()->clear();
            $this->addFlash('info', sprintf('De zoekopdracht leverde geen resultaten op. Zoek opnieuw of ga terug.'));
            return $this->doSearch(new Request());
        }
        $ret['klant'] = $klant;
        $ret['redirect'] = $redirect;

        return $ret;
    }

    protected function doAddPartner($request, $klant)
    {
        $klant = $this->dao->find($klant);
        $partnerId = $request->get('partner');
        if ($partnerId !== 'remove') {

            $partner = $this->dao->find($partnerId);
            $klant->setPartner($partner);
            $partner->setPartner($klant);// wederkerig

            $this->addFlash("info",sprintf("Partner (%s) gekoppeld aan klant (%s) en vice versa",$partner,$klant));
        } else if ($partnerId == 'remove')
        {
            $partner = $klant->getPartner();
            $klant->setPartner(null);
            $partner->setPartner(null);//wederkerig

            $this->addFlash("info",sprintf("Partner is verwijderd van klant (%s) en vice versa",$klant));
        }

        $this->dao->update($klant);
        if($request->get('redirect'))
        {
            return $this->redirect($request->get('redirect'));
        }
        return $this->redirectToView($klant);

    }
}
