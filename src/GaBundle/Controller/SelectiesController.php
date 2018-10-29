<?php

namespace GaBundle\Controller;

use AppBundle\Controller\SymfonyController;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\NoResultException;
use GaBundle\Filter\SelectieFilter;
use GaBundle\Form\SelectieType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use GaBundle\Form\EmailMessageType;
use GaBundle\Entity\Intake;

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
        $form = $this->createForm(EmailMessageType::class);
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

        // convert KlantIntake and VrijwilligerIntake collections to one Intake collection
        $intakes = $this->getEntityManager()->getRepository(Intake::class)
            ->createQueryBuilder('intake')
            ->where('intake IN (:klanten) OR intake IN (:vrijwilligers)')
            ->setParameters([
                'klanten' => $klanten,
                'vrijwilligers' => $vrijwilligers,
            ])
            ->getQuery()
            ->getResult()
        ;

        if (0 === count($intakes)) {
            throw new NoResultException();
        }

        $form = $this->createForm(EmailMessageType::class, null, [
            'from' => $this->getMedewerker()->getEmail(),
            'to' => $intakes,
        ]);

        return [
            'action' => 'email',
            'form' => $form->createView(),
            'klanten' => $klanten,
            'vrijwilligers' => $vrijwilligers,
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
