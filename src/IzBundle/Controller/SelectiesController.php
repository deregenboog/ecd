<?php

namespace IzBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use IzBundle\Entity\IzKlant;
use AppBundle\Controller\SymfonyController;
use IzBundle\Form\IzDeelnemerSelectieType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Filter\IzDeelnemerSelectie;
use Symfony\Component\Form\FormError;
use AppBundle\Exception\AppException;
use IzBundle\Entity\IzDeelnemer;
use IzBundle\Form\IzEmailMessageType;

/**
 * @Route("/selecties")
 */
class SelectiesController extends SymfonyController
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(IzDeelnemerSelectieType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($form->get('filter')->isClicked()) {
                    return $this->email($form->getData());
                }
                if ($form->get('download')->isClicked()) {
                    return $this->download($form->getData());
                }
            } catch (AppException $e) {
                $form->addError(new FormError('De zoekopdracht leverde geen resultaten op.'));
            }
        }

        return [
            'action' => 'index',
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/email")
     */
    public function emailAction(Request $request)
    {
        $form = $this->createForm(IzEmailMessageType::class);
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
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
        }

        return $this->redirectToRoute('iz_selecties_index');
    }

    private function download(IzDeelnemerSelectie $filter)
    {
        ini_set('memory_limit', '512M');

        $izKlanten = $this->getKlanten($filter);
        $izVrijwilligers = $this->getVrijwilligers($filter);

        if (0 === count($izKlanten) + count($izVrijwilligers)) {
            throw new AppException();
        }

        $filename = sprintf('selecties_%s.xlsx', date('Ymd_His'));

        return $this->get('iz.export.selectie')
            ->create($izKlanten)
            ->create($izVrijwilligers)
            ->getResponse($filename)
        ;
    }

    private function email(IzDeelnemerSelectie $filter)
    {
        ini_set('memory_limit', '512M');

        // exclude people that don't want to receive e-mails
        $filter->communicatie[] = 'geen_email';

        $izKlanten = $this->getKlanten($filter);
        $izVrijwilligers = $this->getVrijwilligers($filter);

        if (0 === count($izKlanten) + count($izVrijwilligers)) {
            throw new AppException();
        }

        // convert IzKlant and IzVrijwilliger collections to IzDeelnemer collection
        $izDeelnemers = $this->getEntityManager()->getRepository(IzDeelnemer::class)
            ->createQueryBuilder('izDeelnemer')
            ->where('izDeelnemer IN (:iz_klanten) OR izDeelnemer IN (:iz_vrijwilligers)')
            ->setParameters([
                'iz_klanten' => $izKlanten,
                'iz_vrijwilligers' => $izVrijwilligers,
            ])
            ->getQuery()
            ->getResult()
        ;

        $form = $this->createForm(IzEmailMessageType::class, null, [
            'from' => $this->getMedewerker()->getEmail(),
            'to' => $izDeelnemers,
        ]);

        return [
            'action' => 'email',
            'form' => $form->createView(),
            'izKlanten' => $izKlanten,
            'izVrijwilligers' => $izVrijwilligers,
        ];
    }

    private function getKlanten(IzDeelnemerSelectie $filter)
    {
        if (in_array('klanten', $filter->personen)) {
            return $this->get('IzBundle\Service\KlantDao')->findAll(null, $filter);
        }

        return new ArrayCollection();
    }

    private function getVrijwilligers(IzDeelnemerSelectie $filter)
    {
        if (in_array('vrijwilligers', $filter->personen)) {
            $izVrijwilligers = $this->get('IzBundle\Service\VrijwilligerDao')->findAll(null, $filter);
        }

        return new ArrayCollection();
    }
}
