<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Client;
use ClipBundle\Form\ClientCloseType;
use ClipBundle\Form\ClientFilterType;
use ClipBundle\Form\ClientType;
use ClipBundle\Service\ClientDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Export\ExportInterface;

/**
 * @Route("/clienten")
 */
class ClientenController extends AbstractController
{
    protected $title = 'Cliënten';
    protected $entityName = 'cliënt';
    protected $entityClass = Client::class;
    protected $formClass = ClientType::class;
    protected $filterFormClass = ClientFilterType::class;
    protected $baseRouteName = 'clip_clienten_';

    /**
     * @var ClientDaoInterface
     *
     * @DI\Inject("clip.dao.client")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("clip.export.clienten")
     */
    protected $export;

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(ClientCloseType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);

                $this->addFlash('success', $this->entityName.' is afgesloten.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToView($entity);
        }

        return [
            'client' => $entity,
            'form' => $form->createView(),
        ];
    }
}
