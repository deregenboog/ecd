<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Exception\UserException;
use AppBundle\Export\AbstractExport;
use IzBundle\Entity\Intervisiegroep;
use IzBundle\Form\IntervisiegroepFilterType;
use IzBundle\Form\IntervisiegroepType;
use IzBundle\Form\IzEmailMessageType;
use IzBundle\Service\IntervisiegroepDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/intervisiegroepen")
 * @Template
 */
class IntervisiegroepenController extends AbstractController
{
    protected $entityName = 'intervisiegroep';
    protected $entityClass = Intervisiegroep::class;
    protected $formClass = IntervisiegroepType::class;
    protected $filterFormClass = IntervisiegroepFilterType::class;
    protected $baseRouteName = 'iz_intervisiegroepen_';

    /**
     * @var IntervisiegroepDaoInterface
     */
    protected $dao;

    /**
     * @var AbstractExport
     */
    protected $export;

    /**
     * @var AbstractExport
     */
    protected $vrijwilligersExport;

    public function __construct(IntervisiegroepDaoInterface $dao, AbstractExport $export, AbstractExport $vrijwilligersExport)
    {
        $this->dao = $dao;
        $this->export = $export;
        $this->vrijwilligersExport = $vrijwilligersExport;
    }

    /**
     * @Route("/{id}/email")
     */
    public function emailAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->getForm(IzEmailMessageType::class, null, [
            'from' => $this->getMedewerker()->getEmail(),
            'to' => $entity->getVrijwilligers(),
        ]);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $message = (new Email())
                ->from($form->get('from')->getData())
                ->to(explode(', ', $form->get('to')->getData()))
                ->subject($form->get('subject')->getData())
                ->text($form->get('text')->getData(), 'text/plain')
            ;

            // add attachments
            if ($form->get('file1')->getData()) {
                $message->attachFromPath($form->get('file1')->getData()->getPathName());
            }
            if ($form->get('file2')->getData()) {
                $message->attachFromPath($form->get('file2')->getData()->getPathName());
            }
            if ($form->get('file3')->getData()) {
                $message->attachFromPath($form->get('file3')->getData()->getPathName());
            }

            /** @var MailerInterface $mailer */
            $mailer = $this->container->get('mailer');
            try {
                try {
                    $sent = true;
                    $mailer->send($message);
                } catch (TransportExceptionInterface $e) {
                    $sent = false;
                }
                if ($sent) {
                    $this->addFlash('success', 'E-mail is verzonden.');
                } else {
                    $this->addFlash('danger', 'E-mail kon niet verzonden worden.');
                }
            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
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
     * @Route("/{id}/download")
     */
    public function downloadExportAction(Request $request, $id)
    {
        ini_set('memory_limit', '512M');

        $entity = $this->dao->find($id);

        $filename = sprintf('selecties_%s.xlsx', date('Ymd_His'));

        return $this->vrijwilligersExport
            ->create($entity->getVrijwilligers())
            ->getResponse($filename)
        ;
    }
}
