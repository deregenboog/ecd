<?php

namespace OekBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\ExportInterface;
use OekBundle\Entity\Training;
use OekBundle\Form\EmailMessageType;
use OekBundle\Form\TrainingFilterType;
use OekBundle\Form\TrainingType;
use OekBundle\Service\TrainingDao;
use OekBundle\Service\TrainingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/trainingen")
 * @Template
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
     * @var TrainingDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @var ExportInterface
     */
    protected $exportPresentielijst;

    /**
     * @var ExportInterface
     */
    protected $exportDeelnemerslijst;

    /**
     * @param TrainingDao $dao
     * @param \ArrayObject $entities
     * @param ExportInterface $exportPresentielijst
     * @param ExportInterface $exportDeelnemerslijst
     */
    public function __construct(TrainingDao $dao, \ArrayObject $entities, ExportInterface $exportPresentielijst, ExportInterface $exportDeelnemerslijst)
    {
        $this->dao = $dao;
        $this->entities = $entities;
        $this->exportPresentielijst = $exportPresentielijst;
        $this->exportDeelnemerslijst = $exportDeelnemerslijst;
    }


    /**
     * @Route("/{id}/email")
     */
    public function emailAction($id, MailerInterface $mailer)
    {
        /** @var Training $training */
        $training = $this->dao->find($id);

        $form = $this->getForm(EmailMessageType::class, null, [
            'from' => $this->getMedewerker()->getEmail(),
            'to' => $training->getDeelnemers(),
        ]);

        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {


            $message = (new Email())
                ->addFrom(new Address($this->getMedewerker()->getEmail(),$this->getMedewerker()->getNaam()))
                ->addTo(new Address($this->getMedewerker()->getEmail(),'Op eigen kracht trainingen ('.$this->getMedewerker()->getNaam().')'))
                ->subject($form->get('subject')->getData())
                ->text($form->get('text')->getData())
            ;
            foreach(explode(", ",$form->get('to')->getData()) as $rcpt)
            {
                $message->addTo($rcpt);
            }

            try {
                $sent = $mailer->send($message);
                $this->addFlash('success', 'Email is succesvol verzonden');
            } catch (TransportException $e) {
                $sent = false;
                $this->addFlash('danger', 'Email kon niet worden verzonden ('.$e->getMessage().')');
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
