<?php

namespace GaBundle\Controller;

use AppBundle\Controller\SymfonyController;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\NoResultException;
use GaBundle\Filter\SelectieFilter;
use GaBundle\Form\SelectieType;
use IzBundle\Entity\IzDeelnemer;
use IzBundle\Entity\IzKlant;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Form\IzEmailMessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/selecties")
 */
class SelectiesController extends SymfonyController
{
    /**
     * @Route("/")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(SelectieType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($form->get('filter')->isClicked()) {
                    return $this->email($form->getData());
                }
                if ($form->get('download')->isClicked()) {
                    return $this->download($form->getData());
                }
            } catch (NoResultException $e) {
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

        return $this->redirectToRoute('ga_selecties_index');
    }

    private function download(SelectieFilter $filter)
    {
        ini_set('memory_limit', '512M');

        $klanten = $this->getKlanten($filter);
        $vrijwilligers = $this->getVrijwilligers($filter);

        if (0 === count($klanten) + count($vrijwilligers)) {
            throw new NoResultException();
        }

        $filename = sprintf('selecties_%s.xlsx', date('Ymd_His'));

        return $this->get('ga.export.selectie2')
            ->create($klanten)
            ->create($vrijwilligers)
            ->getResponse($filename)
        ;
    }

    private function email(SelectieFilter $filter)
    {
        ini_set('memory_limit', '512M');

        // exclude people that don't want to receive e-mails
        $filter->communicatie[] = 'email';

        $klanten = $this->getKlanten($filter);
        $vrijwilligers = $this->getVrijwilligers($filter);

        if (0 === count($klanten) + count($vrijwilligers)) {
            throw new NoResultException();
        }

        // convert IzKlant and IzVrijwilliger collections to IzDeelnemer collection
        $izDeelnemers = $this->getEntityManager()->getRepository(IzDeelnemer::class)
            ->createQueryBuilder('izDeelnemer')
            ->where('izDeelnemer IN (:iz_klanten) OR izDeelnemer IN (:iz_vrijwilligers)')
            ->setParameters([
                'iz_klanten' => $klanten,
                'iz_vrijwilligers' => $vrijwilligers,
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
            'izKlanten' => $klanten,
            'izVrijwilligers' => $vrijwilligers,
        ];
    }

    private function getKlanten(SelectieFilter $filter)
    {
        if (in_array('klanten', $filter->personen)) {
            return $this->get('GaBundle\Service\KlantIntakeDao')->findAll(null, $filter);
        }

        return new ArrayCollection();
    }

    private function getVrijwilligers(SelectieFilter $filter)
    {
        if (in_array('vrijwilligers', $filter->personen)) {
            return $this->get('GaBundle\Service\VrijwilligerIntakeDao')->findAll(null, $filter);
        }

        return new ArrayCollection();
    }
}
