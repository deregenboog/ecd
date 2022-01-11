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
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/intervisiegroepen")
 * @Template
 */
class IntervisiegroepenController extends AbstractController
{
    protected $title = 'Intervisiegroepen';
    protected $entityName = 'intervisiegroep';
    protected $entityClass = Intervisiegroep::class;
    protected $formClass = IntervisiegroepType::class;
    protected $filterFormClass = IntervisiegroepFilterType::class;
    protected $baseRouteName = 'iz_intervisiegroepen_';

    /**
     * @var IntervisiegroepDaoInterface
     *
     * @DI\Inject("IzBundle\Service\IntervisiegroepDao")
     */
    protected $dao;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("iz.export.intervisiegroepen")
     */
    protected $export;

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
            /** @var Swift_Mailer $mailer */
            $mailer = $this->container->get('mailer');

            /** @var Swift_Mime_Message $message */
            $message = $mailer->createMessage()
                ->setFrom($form->get('from')->getData())
                ->setTo(explode(', ', $form->get('to')->getData()))
                ->setSubject($form->get('subject')->getData())
                ->setBody($form->get('text')->getData(), 'text/plain')
            ;

            // add attachments
            if ($form->get('file1')->getData()) {
                $message->attach(\Swift_Attachment::fromPath($form->get('file1')->getData()->getPathName()));
            }
            if ($form->get('file2')->getData()) {
                $message->attach(\Swift_Attachment::fromPath($form->get('file2')->getData()->getPathName()));
            }
            if ($form->get('file3')->getData()) {
                $message->attach(\Swift_Attachment::fromPath($form->get('file3')->getData()->getPathName()));
            }

            try {
                $sent = $mailer->send($message);
                if ($sent) {
                    $this->addFlash('success', 'E-mail is verzonden.');
                } else {
                    $this->addFlash('danger', 'E-mail kon niet verzonden worden.');
                }
            } catch(UserException $e) {
//                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
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
    public function downloadAction(Request $request, $id)
    {
        ini_set('memory_limit', '512M');

        $entity = $this->dao->find($id);

        $filename = sprintf('selecties_%s.xlsx', date('Ymd_His'));

        return $this->get('iz.export.vrijwilligers')
            ->create($entity->getVrijwilligers())
            ->getResponse($filename)
        ;
    }
}
