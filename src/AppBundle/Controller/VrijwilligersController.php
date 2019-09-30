<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Document;
use AppBundle\Entity\Overeenkomst;
use AppBundle\Entity\Vog;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Export\AbstractExport;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\VrijwilligerFilterType;
use AppBundle\Form\VrijwilligerType;
use AppBundle\Repository\DocumentenRepository;
use AppBundle\Service\VrijwilligerDaoInterface;
use Doctrine\Common\Collections\Criteria;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @Route("/vrijwilligers")
 * @Template
 */
class VrijwilligersController extends AbstractController
{
    protected $title = 'Vrijwilligers';
    protected $entityName = 'vrijwilliger';
    protected $entityClass = Vrijwilliger::class;
    protected $formClass = VrijwilligerType::class;
    protected $filterFormClass = VrijwilligerFilterType::class;
    protected $baseRouteName = 'app_vrijwilligers_';

    /**
     * @var VrijwilligerDaoInterface
     *
     * @DI\Inject("AppBundle\Service\VrijwilligerDao")
     */
    protected $dao;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("app.export.vrijwilligers")
     */
    protected $export;

    /**
     * @Route("/{vrijwilliger}/{documentId}/deleteDocument/")
     * @param Request $request
     * @param $documentId
     */
    public function deleteDocumentAction(Request $request, $vrijwilliger, $documentId)
    {
        if (in_array('delete', $this->disabledActions)) {
            throw new AccessDeniedHttpException();
        }

        $vrijwilliger = $this->dao->find($vrijwilliger);

        //$criteria = Criteria::create()->where(Criteria::expr()->eq("id", $documentId));
        $docs = $vrijwilliger->getDocumenten(); //->matching($criteria)->first();
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

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $url = $request->get('redirect');
                $viewUrl = $this->generateUrl($this->baseRouteName.'view', ['id' => $entity->getId()]);

                $vrijwilliger->removeDocument($entity);
                /**
                 * Somehow, it wont remove...
                 */
               $docDao = new \AppBundle\Service\DocumentDao($this->getEntityManager());
               $docDao->delete($entity);

                if($entity instanceof Vog)
                {
                    $vrijwilliger->setVogAanwezig(false);
                }
                else if($entity instanceof Overeenkomst)
                {
                    $vrijwilliger->setOvereenkomstAanwezig(false);
                }


                $this->dao->update($vrijwilliger);

                $shortname = new \ReflectionClass($this);
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
}
