<?php

namespace OekBundle\Controller;

use AppBundle\Controller\SymfonyController;
use OekBundle\Entity\OekTraining;
use OekBundle\Form\OekTrainingFilterType;
use OekBundle\Form\OekTrainingType;
use AppBundle\Form\ConfirmationType;
use OekBundle\Form\OekEmailMessageType;
use OekBundle\Entity\OekGroep;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/oek/trainingen")
 */
class TrainingenController extends SymfonyController
{
    private $enabledFilters = [
        'id',
        'naam',
        'oekGroep',
        'startdatum',
        'einddatum',
    ];

    private $sortFieldWhitelist = [
        'oekTraining.id',
        'oekTraining.naam',
        'oekGroep.naam',
        'oekTraining.startdatum',
        'oekTraining.einddatum',
    ];

    /**
     * @Route("/")
     */
    public function index()
    {
        $repository = $this->getEntityManager()->getRepository(OekTraining::class);
        $builder = $repository->createQueryBuilder('oekTraining')
            ->leftJoin('oekTraining.oekDeelnames', 'oekDeelname')
            ->leftJoin('oekDeelname.oekKlant', 'oekKlant')
            ->innerJoin('oekTraining.oekGroep', 'oekGroep');

        $filter = $this->createForm(OekTrainingFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->getRequest());
        if ($filter->isValid()) {
            $filter->getData()->applyTo($builder);
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => 'oekTraining.startdatum',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        return ['filter' => $filter->createView(), 'pagination' => $pagination];
    }

    /**
     * @Route("/{id}/view")
     */
    public function view($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekTraining::class);

        return ['oekTraining' => $repository->find($id)];
    }

    /**
     * @Route("/add")
     */
    public function add(Request $request)
    {
        $entityManager = $this->getEntityManager();

        $oekTraining = new OekTraining();
        if ($oekGroepId = $request->get('oekGroep')) {
            /** @var OekGroep $oekGroep */
            $oekGroep = $entityManager->find(OekGroep::class, $oekGroepId);
            $oekTraining->setOekGroep($oekGroep);
        }

        $form = $this->createForm(OekTrainingType::class, $oekTraining);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $entityManager->persist($oekTraining);
            $entityManager->flush();

            $this->addFlash('success', 'Training is opgeslagen.');

            return $this->redirectToRoute('oek_trainingen_view', ['id' => $oekTraining->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekTraining::class);
        $oekTraining = $repository->find($id);

        $form = $this->createForm(OekTrainingType::class, $oekTraining);
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Training is opgeslagen.');

            return $this->redirectToRoute('oek_trainingen_view', ['id' => $oekTraining->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekTraining::class);
        $oekTraining = $repository->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $entityManager->remove($oekTraining);
                $entityManager->flush();

                $this->addFlash('success', 'Training is verwijderd.');
            }

            return $this->redirectToRoute('oek_trainingen_index');
        }

        return ['form' => $form->createView(), 'oekTraining' => $oekTraining];
    }

    /**
     * @Route("/{id}/email_deelnemers")
     */
    public function email_deelnemers($oekTrainingId)
    {
        /** @var OekTraining $oekTraining */
        $oekTraining = $this->getEntityManager()->getRepository(OekTraining::class)
            ->find($oekTrainingId);

        $form = $this->createForm(OekEmailMessageType::class, null, [
            'from' => $this->Session->read('Auth.Medewerker.LdapUser.mail'),
            'to' => $oekTraining->getOekKlanten(),
        ]);
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
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

            return $this->redirectToRoute('oek_trainingen_view', ['id' => $oekTraining->getId()]);
        }

        return ['form' => $form->createView(), 'oekTraining' => $oekTraining];
    }
}
