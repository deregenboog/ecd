<?php

namespace OekBundle\Controller;

use OekBundle\Entity\Training;
use OekBundle\Form\TrainingFilterType;
use OekBundle\Form\TrainingType;
use OekBundle\Form\EmailMessageType;
use Symfony\Component\Routing\Annotation\Route;
use JMS\DiExtraBundle\Annotation as DI;
use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\ExportInterface;

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
     * @var ExportInterface
     *
     * @DI\Inject("oek.export.presentielijst")
     */
    protected $exportPresentielijst;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("oek.export.deelnemerslijst")
     */
    protected $exportDeelnemerslijst;

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
        ini_set('memory_limit', '512M');

        $training = $this->dao->find($id);
        $filename = sprintf('op-eigen-kracht-presentielijst-training-%s.xlsx', $training->getId());

        return $this->exportPresentielijst
            ->create($training)
            ->getResponse($filename);
    }

    /**
     * @Route("/{id}/deelnemerslijst")
     */
    public function deelnemerslijstAction($id)
    {
        ini_set('memory_limit', '512M');

        $training = $this->dao->find($id);
        $filename = sprintf('op-eigen-kracht-deelnemerslijst-training-%s.xlsx', $training->getId());

        return $this->exportDeelnemerslijst
            ->create($training->getDeelnames())
            ->getResponse($filename);
    }
}
