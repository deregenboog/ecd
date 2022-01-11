<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\ConfirmationType;
use HsBundle\Entity\Klant;
use HsBundle\Form\KlantFilterType;
use HsBundle\Form\KlantType;
use HsBundle\Service\KlantDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

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
    protected $baseRouteName = 'hs_klanten_';

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("HsBundle\Service\KlantDao")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("hs.export.klant")
     */
    protected $export;

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        $klant = new Klant();
        $form = $this->getForm(KlantType::class, $klant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->create($klant);
                $this->addFlash('success', 'Klant is opgeslagen.');

                return $this->redirectToRoute('hs_memos_add', [
                    'klant' => $klant->getId(),
                    'redirect' => $this->generateUrl('hs_klanten_view', ['id' => $klant->getId()]).'#memos',
                ]);
            } catch(UserException $e) {
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
            }
            catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToIndex();
        }

        return [
            'creationForm' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/delete")
     * @Template
     * @var Request $request
     * @var int $id
     * @var bool Check $actief on entity.
     */
    public function deleteAction(Request $request, $id)
    {
        if (in_array('delete', $this->disabledActions)) {
            throw new AccessDeniedHttpException();
        }

        $entity = $this->dao->find($id);

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $url = $request->get('redirect');
                $viewUrl = $this->generateUrl($this->baseRouteName.'view', ['id' => $entity->getId()]);

                if($entity->isDeletable() )
                {
                    $this->dao->delete($entity);
                    $this->addFlash('success', ucfirst($this->entityName).' is verwijderd.');
                }
                else
                {
                    $this->addFlash('error', ucfirst($this->entityName).'heeft nog facturen/klussen of andere gegevens aan zich gekoppeld en is daarom niet verwijderd.');
                }



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
