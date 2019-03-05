<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\AmocLand;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Land;
use AppBundle\Form\AppDateType;
use InloopBundle\Entity\Afsluiting;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\Registratie;
use InloopBundle\Entity\Schorsing;
use InloopBundle\Form\AfsluitingType;
use InloopBundle\Form\KlantFilterType;
use InloopBundle\Form\KlantType;
use InloopBundle\Pdf\PdfBrief;
use InloopBundle\Service\KlantDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use InloopBundle\Entity\Aanmelding;
use InloopBundle\Form\AanmeldingType;

/**
 * @Route("/klanten")
 * @Template
 */
class KlantenController extends AbstractController
{
    protected $title = 'Klanten';
    protected $entityName = 'klant';
    protected $entityClass = Klant::class;
    protected $formClass = KlantType::class;
    protected $filterFormClass = KlantFilterType::class;
    protected $baseRouteName = 'inloop_klanten_';

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\KlantDao")
     */
    protected $dao;

    /**
     * @Route("/{klant}/rapportage")
     * @ParamConverter("klant", class="AppBundle:Klant")
     */
    public function viewReport(Request $request, Klant $klant)
    {
        $form = $this->createFormBuilder(null, ['method' => 'GET'])
            ->add('startdatum', AppDateType::class, [
                'required' => true,
                'data' => new \DateTime('first day of January this year'),
            ])
            ->add('einddatum', AppDateType::class, [
                'required' => true,
                'data' => (new \DateTime('today')),
            ])
            ->add('show', SubmitType::class, [
                'label' => 'Rapport tonen',
            ])
            ->getForm()
        ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $builder = $this->getEntityManager()->getRepository(Registratie::class)->createQueryBuilder('registratie')
                ->where('registratie.klant = :klant')
                ->andWhere('DATE(registratie.binnen) BETWEEN :start_date AND :end_date')
                ->setParameters([
                    'klant' => $klant,
                    'start_date' => $form->get('startdatum')->getData(),
                    'end_date' => $form->get('einddatum')->getData(),
                ]);

            $data['bezoeken'] = (clone $builder)->select('COUNT(registratie.id)')->getQuery()->getSingleScalarResult();
            $data['douche'] = (clone $builder)->select('COUNT(registratie.id)')->andWhere('registratie.douche = true')->getQuery()->getSingleScalarResult();
            $data['kleding'] = (clone $builder)->select('COUNT(registratie.id)')->andWhere('registratie.kleding = true')->getQuery()->getSingleScalarResult();
            $data['maaltijd'] = (clone $builder)->select('COUNT(registratie.id)')->andWhere('registratie.maaltijd = true')->getQuery()->getSingleScalarResult();
            $data['activering'] = (clone $builder)->select('COUNT(registratie.id)')->andWhere('registratie.activering = true')->getQuery()->getSingleScalarResult();
            $data['bezoekenPerLocatie'] = (clone $builder)
                ->select('locatie.naam AS locatienaam, COUNT(registratie.id) AS aantal')
                ->innerJoin('registratie.locatie', 'locatie')
                ->groupBy('locatie.id')
                ->orderBy('aantal', 'DESC')
                ->getQuery()
                ->getScalarResult();

            $data['schorsingen'] = $this->getEntityManager()->getRepository(Schorsing::class)->createQueryBuilder('schorsing')
                ->select('COUNT(schorsing.id)')
                ->where('schorsing.klant = :klant')
                ->andWhere('schorsing.datumVan >= :start_date')
                ->andWhere('schorsing.datumTot <= :end_date')
                ->setParameters([
                    'klant' => $klant,
                    'start_date' => $form->get('startdatum')->getData(),
                    'end_date' => $form->get('einddatum')->getData(),
                ])
                ->getQuery()
                ->getSingleScalarResult();

            return [
                'data' => $data,
                'startDate' => $form->get('startdatum')->getData(),
                'endDate' => $form->get('einddatum')->getData(),
                'klant' => $klant,
                'form' => $form->createView(),
            ];
        }

        return [
            'klant' => $klant,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/herintakes")
     */
    public function viewHerintakesAction()
    {
        $em = $this->getEntityManager();
        $locaties = $em->getRepository(Locatie::class)->findBy([], ['naam' => 'ASC']);

        $data = [];
        foreach ($locaties as $locatie) {
            $klanten = $em->getRepository(Klant::class)->createQueryBuilder('klant')
                ->select('klant, intake')
                ->innerJoin('klant.laatsteIntake', 'intake', 'WITH', 'intake.intakedatum < :year_ago')
                ->innerJoin(Registratie::class, 'registratie', 'WITH', 'registratie.klant = klant')
                ->innerJoin('registratie.locatie', 'locatie', 'WITH', 'locatie = :locatie')
                ->where('registratie.binnen >= :month_ago')
                ->setParameters([
                    'month_ago' => new \DateTime('-1 month'),
                    'year_ago' => new \DateTime('-1 year'),
                    'locatie' => $locatie,
                ])
                ->getQuery()
                ->getResult()
            ;

            if (count($klanten) > 0) {
                foreach ($klanten as $klant) {
                    $data[$locatie->getNaam()][] = $klant;
                }
            }
        }

        return ['locaties' => $data];
    }

    /**
     * @Route("/{klant}/amoc.pdf")
     * @ParamConverter("klant", class="AppBundle:Klant")
     */
    public function amocAction(Klant $klant)
    {
        $html = $this->renderView('@Inloop/klanten/amoc_brief.pdf.twig', ['klant' => $klant]);
        $pdf = new PdfBrief($html);

        $response = new Response($pdf->Output(null, 'S'));
        $response->headers->set('Content-type', 'application/pdf');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $klant = $this->dao->find($id);
        $afsluiting = new Afsluiting($klant, $this->getMedewerker());

        $form = $this->createForm(AfsluitingType::class, $afsluiting);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getEntityManager();
                $entityManager->persist($afsluiting);
                $entityManager->flush();

                $this->addFlash('success', 'Inloopdossier is afgesloten');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('inloop_klanten_index');
        }

        return [
            'klant' => $klant,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/open")
     */
    public function openAction(Request $request, $id)
    {
        $klant = $this->dao->find($id);
        $aanmelding = new Aanmelding($klant, $this->getMedewerker());

        $form = $this->createForm(AanmeldingType::class, $aanmelding);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getEntityManager();
                $entityManager->persist($aanmelding);
                $entityManager->flush();

                $this->addFlash('success', 'Inloopdossier is heropend');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('inloop_klanten_index');
        }

        return [
            'klant' => $klant,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Template
     */
    public function _intakesAction($id)
    {
        $entity = $this->dao->find($id);

        return [
            'klant' => $entity,
        ];
    }

    /**
     * @Template
     */
    public function _opmerkingenAction($id)
    {
        $entity = $this->dao->find($id);

        return [
            'klant' => $entity,
        ];
    }

    /**
     * @Template
     */
    public function _schorsingenAction($id)
    {
        $entity = $this->dao->find($id);

        return [
            'klant' => $entity,
        ];
    }

    /**
     * @Template
     */
    public function _registratiesAction($id)
    {
        $entity = $this->dao->find($id);

        return [
            'klant' => $entity,
        ];
    }

    protected function addParams($entity, Request $request)
    {
        return [
            'amoc_landen' => $this->getAmocLanden(),
        ];
    }

    protected function getAmocLanden()
    {
        return $this->getDoctrine()->getEntityManager()->getRepository(Land::class)
            ->createQueryBuilder('land')
            ->innerJoin(AmocLand::class, 'amoc', 'WITH', 'amoc.land = land')
            ->getquery()
            ->getResult()
        ;
    }
}
