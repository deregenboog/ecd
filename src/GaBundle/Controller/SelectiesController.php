<?php

namespace GaBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Exception\UserException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\NoResultException;
use GaBundle\Entity\Dossier;
use GaBundle\Export\SelectionExport;
use GaBundle\Filter\SelectieFilter;
use GaBundle\Form\EmailMessageType;
use GaBundle\Form\SelectieType;
use GaBundle\Service\KlantdossierDaoInterface;
use GaBundle\Service\VrijwilligerdossierDaoInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/selecties")
 */
class SelectiesController extends SymfonyController
{
    /**
     * @var KlantdossierDaoInterface
     */
    protected $klantDossierDao;

    /**
     * @var VrijwilligerDossierDaoInterface
     */
    protected $vrijwilligerDossierDao;

    /**
     * @var SelectionExport
     */
    protected $export;

    public function __construct(KlantDossierDaoInterface $klantDossierDao, VrijwilligerdossierDaoInterface $vrijwilligerDossierDao, SelectionExport $export)
    {
        $this->klantDossierDao = $klantDossierDao;
        $this->vrijwilligerDossierDao = $vrijwilligerDossierDao;
        $this->export = $export;
    }

    /**
     * @Route("/")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $form = $this->getForm(SelectieType::class);
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
//        $form = $this->getForm(EmailMessageType::class);
//        $form->handleRequest($this->getRequest());
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            /** @var Mailer $mailer */
//            $mailer = $this->container->get('mailer');
//
//            /** @var Message $message */
//            $message = $mailer->createMessage()
//                ->setFrom($form->get('from')->getData())
//                ->setTo(explode(', ', $form->get('to')->getData()))
//                ->setSubject($form->get('subject')->getData())
//                ->setBody($form->get('text')->getData(), 'text/plain')
//            ;
//
//            try {
//                $sent = $mailer->send($message);
//                if ($sent) {
//                    $this->addFlash('success', 'E-mail is verzonden.');
//                } else {
//                    $this->addFlash('danger', 'E-mail kon niet verzonden worden.');
//                }
//            } catch(UserException $e) {
////                $this->logger->error($e->getMessage(), ['exception' => $e]);
//                $message =  $e->getMessage();
//                $this->addFlash('danger', $message);
////                return $this->redirectToRoute('app_klanten_index');
//            } catch (\Exception $e) {
//                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
//                $this->addFlash('danger', $message);
//            }
//        }
        $this->addFlash('success', 'E-mails versturen via selecties is uitgeschakeld.');
        return $this->redirectToRoute('ga_selecties_index');
    }

    private function download(SelectieFilter $filter)
    {
        ini_set('memory_limit', '512M');

        $klanten = $this->getKlanten($filter);
        $vrijwilligers = $this->getVrijwilligers($filter);

        if (0 === (is_array($klanten) || $klanten instanceof \Countable ? count($klanten) : 0) + (is_array($vrijwilligers) || $vrijwilligers instanceof \Countable ? count($vrijwilligers) : 0)) {
            throw new NoResultException();
        }

        $filename = sprintf('selecties_%s.xlsx', date('Ymd_His'));

        return $this->export->create($klanten)->create($vrijwilligers)->getResponse($filename);
    }

    private function email(SelectieFilter $filter)
    {
        ini_set('memory_limit', '512M');

        // exclude people that don't want to receive e-mails
        $filter->communicatie[] = 'email';

        $klanten = $this->getKlanten($filter);
        $vrijwilligers = $this->getVrijwilligers($filter);

        if (0 === (is_array($klanten) || $klanten instanceof \Countable ? count($klanten) : 0) + (is_array($vrijwilligers) || $vrijwilligers instanceof \Countable ? count($vrijwilligers) : 0)) {
            throw new NoResultException();
        }

        // convert KlantDossier and VrijwilligerDossier collections to one Dossier collection
        $dossiers = $this->getEntityManager()->getRepository(Dossier::class)
            ->createQueryBuilder('dossier')
            ->where('dossier IN (:klanten) OR dossier IN (:vrijwilligers)')
            ->setParameters([
                'klanten' => $klanten,
                'vrijwilligers' => $vrijwilligers,
            ])
            ->getQuery()
            ->getResult()
        ;

        if (0 === (is_array($dossiers) || $dossiers instanceof \Countable ? count($dossiers) : 0)) {
            throw new NoResultException();
        }

        $form = $this->getForm(EmailMessageType::class, null, [
            'from' => $this->getMedewerker()->getEmail(),
            'to' => $dossiers,
        ]);

        return [
            'action' => 'email',
            'form' => $form->createView(),
        ];
    }

    private function getKlanten(SelectieFilter $filter)
    {
        if (in_array('klanten', $filter->personen)) {
            return $this->klantDossierDao->findAll(null, $filter);
        }

        return new ArrayCollection();
    }

    private function getVrijwilligers(SelectieFilter $filter)
    {
        if (in_array('vrijwilligers', $filter->personen)) {
            return $this->vrijwilligerDossierDao->findAll(null, $filter);
        }

        return new ArrayCollection();
    }
}
