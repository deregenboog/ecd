<?php

namespace OekBundle\Controller;

use AppBundle\Controller\SymfonyController;
use OekBundle\Entity\Training;
use OekBundle\Form\TrainingFilterType;
use OekBundle\Form\TrainingType;
use AppBundle\Form\ConfirmationType;
use OekBundle\Form\EmailMessageType;
use OekBundle\Entity\Groep;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use AppBundle\Controller\AbstractChildController;

/**
 * @Route("/trainingen")
 */
class TrainingenController extends AbstractChildController
{
    protected $title = 'Trainingen';
    protected $entityName = 'training';
    protected $entityClass = Training::class;
    protected $formClass = TrainingType::class;
    protected $filterFormClass = TrainingFilterType::class;
    protected $addMethod = 'addTraining';
    protected $baseRouteName = 'oek_trainingen_';

    /**
     * @var TrainingDaoInterface
     *
     * @DI\Inject("OekBundle\Service\TrainingDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("oek.training.entities")
     */
    protected $entities;

    /**
     * @Route("/{id}/email")
     */
    public function emailAction($id)
    {
        /** @var Training $training */
        $training = $this->dao->find($id);

        $form = $this->createForm(EmailMessageType::class, null, [
            'from' => $this->Session->read('Auth.Medewerker.LdapUser.mail'),
            'to' => $training->getDeelnemers(),
        ]);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Swift_Mailer $mailer */
            $mailer = $this->container->get('mailer');

            /** @var \Swift_Mime_Message $message */
            $message = $mailer->createMessage()
                ->setFrom($form->get('from')->getData())
                ->setTo($form->get('from')->getData())
                ->setBcc(explode(', ', $form->get('to')->getData()))
                ->setSubject($form->get('subject')->getData())
                ->setBody($form->get('text')->getData(), 'text/plain')
            ;

            if ($mailer->send($message)) {
                $this->addFlash('success', __('Email is succesvol verzonden', true));
            } else {
                $this->addFlash('danger', __('Email kon niet worden verzonden', true));
            }

            return $this->redirectToView($training);
        }

        return [
            'form' => $form->createView(),
            'training' => $training,
        ];
    }

    /**
     * @Route("/{id}/presentielijst")
     */
    public function presentielijstAction($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Training::class);
        $training = $repository->find($id);

        $aantalBijeenkomsten = $training->getGroep()->getAantalBijeenkomsten();
        $deelnames = $training->getDeelnames();

        $response = $this->render('@Oek/trainingen/presentielijst.csv.twig', [
            'aantalBijeenkomsten' => $aantalBijeenkomsten,
            'deelnames' => $deelnames,
        ]);

        $filename = sprintf('op-eigen-kracht-presentielijst-training-%s.xls', $training->getId());
        $response->headers->set('Content-type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s";', $filename));
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /**
     * @Route("/{id}/deelnemerslijst")
     */
    public function deelnemerslijstAction($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Training::class);
        $training = $repository->find($id);

        $deelnames = $training->getDeelnames();

        $response = $this->render('@Oek/trainingen/deelnemerslijst.csv.twig', [
            'deelnames' => $deelnames,
        ]);

        $filename = sprintf('op-eigen-kracht-deelnemerslijst-training-%s.xls', $training->getId());
        $response->headers->set('Content-type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s";', $filename));
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }
}
