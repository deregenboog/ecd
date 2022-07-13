<?php

namespace TwBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;

use phpDocumentor\Reflection\Types\Null_;
use TwBundle\Entity\Klant;
use TwBundle\Exception\TwException;
use TwBundle\Form\HuurderCloseType;
use TwBundle\Form\KlantFilterType;
use TwBundle\Form\HuurderSelectType;
use TwBundle\Form\KlantRawType;
use TwBundle\Form\KlantType;
use TwBundle\Service\KlantDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;

use JMS\DiExtraBundle\Annotation as DI;


/**
 * @Route("/klanten")
 * @Template
 */
class KlantenController extends AbstractController
{
    public $title = 'Klanten';

    public $entityClass = Klant::class;
    protected $entityName = 'klant';
    protected $formClass = KlantType::class;
    protected $filterFormClass = KlantFilterType::class;
    protected $baseRouteName = 'tw_klanten_';
    protected $addMethod = "addKlant";
    protected $searchFilterTypeClass = AppKlantFilterType::class;
    protected $searchEntity = AppKlant::class;
    protected $searchEntityName = 'appKlant';

    /**
     * @var ExportInterface
     *
     * @DI\Inject("tw.export.klanten")
     */
    protected $export;


    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("TwBundle\Service\KlantDao")
     */
    protected $dao;

    /**
     * @var KlantDaoInterface
     * @DI\Inject("AppBundle\Service\KlantDao")
     */
    protected $searchDao;

    private $sortFieldWhitelist = [
        'klant.id',
        'klant.achternaam',
        'werkgebied.naam',
        'klant.automatischeIncasso',
        'klant.inschrijvingWoningnet',
        'klant.waPolis',
        'klant.aanmelddatum',
        'klant.afsluitdatum',
        'klant.wpi',
    ];

    /**
     * @deprecated
     */
    public function REMOVEME22020630JTBadd($klantId = null, UserInterface $user)
    {

        $entityManager = $this->getEntityManager();

        if ($this->getRequest()->query->has('klantId')) {
            $appKlant = new AppKlant();
            if ('new' !== $this->getRequest()->query->get('klantId')) {
                $klant = $entityManager->find(AppKlant::class, $this->getRequest()->query->get('klantId'));
            }

            $klant = new Klant();
            $klant->setAppKlant($appKlant);
//            $huurder->setAmbulantOndersteuner($user);

            $creationForm = $this->getForm(KlantType::class, $klant);
            $creationForm->handleRequest($this->getRequest());

            if ($creationForm->isSubmitted() && $creationForm->isValid()) {
                try {
                    $entityManager->persist($klant->getAppKlant());
                    $entityManager->persist($klant);
                    $entityManager->flush();

                    $this->addFlash('success', 'Klant is opgeslagen.');

                    return $this->redirectToRoute('tw_klanten_view', ['id' => $klant->getId()]);
                } catch(UserException $e) {
//                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                    $message =  $e->getMessage();
                    $this->addFlash('danger', $message);
                return $this->redirectToRoute('tw_klanten_index');
                } catch (\Exception $e) {
                    $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                    $this->addFlash('danger', $message);

                    return $this->redirectToRoute('tw_klanten_index');
                }
            }

            return [
                'creationForm' => $creationForm->createView(),
            ];
        }

        $filterForm = $this->getForm(KlantFilterType::class, null, [
            'enabled_filters' => ['naam', 'bsn', 'geboortedatum'],
        ]);
        $filterForm->add('submit', SubmitType::class, ['label' => 'Verder']);
        $filterForm->handleRequest($this->getRequest());

        $selectionForm = $this->getForm(HuurderSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->getRequest());

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            return ['selectionForm' => $selectionForm->createView()];
        }

        if ($selectionForm->isSubmitted() && $selectionForm->isValid()) {
            $huurder = $selectionForm->getData();
            if ($huurder->getKlant() instanceof Klant) {
                $id = $huurder->getKlant()->getId();
            } else {
                $id = 'new';
            }

            return $this->redirectToRoute('tw_klanten_add', ['klantId' => $id]);
        }

        return [
            'filterForm' => $filterForm->createView(),
        ];
    }


    /**
     * @Route("/{id}/close")
     */
    public function close($id)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getFilters()->enable('active');
        $huurder = $entityManager->find(Klant::class, $id);

        $form = $this->getForm(HuurderCloseType::class, $huurder);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Klant is afgesloten.');
            } catch(UserException $e) {
//                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToRoute('tw_klanten_view', ['id' => $huurder->getId()]);
        }

        return [
            'klant' => $huurder,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/reopen")
     */
    public function reopen($id)
    {
        $entityManager = $this->getEntityManager();
        $huurder = $entityManager->find(Klant::class, $id);

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $huurder->reopen();
                    $entityManager->flush();

                    $this->addFlash('success', 'Klant is heropend.');
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

            return $this->redirectToRoute('tw_klanten_view', ['id' => $huurder->getId()]);
        }

        return [
            'klant' => $huurder,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $huurder = $entityManager->find(Klant::class, $id);

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $entityManager->remove($huurder);
                $entityManager->flush();

                $this->addFlash('success', 'Klant is verwijderd.');

                return $this->redirectToRoute('tw_klanten_index');
            } else {
                return $this->redirectToRoute('tw_klanten_view', ['id' => $huurder->getId()]);
            }
        }

        return [
            'klant' => $huurder,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/addwithcheck")
     */
    public function addWithCheck($id = null)
    {
        $entityManager = $this->getEntityManager();
//        $appKlant = $entityManager->find(AppKlant::class, $id);
        $klantRepo = $this->getEntityManager()->getRepository("TwBundle:Klant");
        $klant = $klantRepo->findOneBy(['appKlant'=>$id]);

        if($klant==null)
        {
            //no klant... redirect to add
            return $this->redirectToRoute('tw_klanten_add',['entity'=>$id]);
        }
        return $this->redirectToRoute('tw_klanten_view',['id'=>$klant->getId()]);
    }

}
