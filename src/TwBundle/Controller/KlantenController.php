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

/**
 * @Route("/klanten")
 * @Template
 */
class KlantenController extends AbstractController
{
    public $entityClass = Klant::class;
    protected $entityName = 'klant';
    protected $formClass = KlantType::class;
    protected $filterFormClass = KlantFilterType::class;
    protected $baseRouteName = 'tw_klanten_';
    protected $addMethod = "addKlant";
    protected $searchFilterTypeClass = AppKlantFilterType::class;
    protected $searchEntity = AppKlant::class;
    protected $searchEntityName = 'appKlant';


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
     * @var KlantDaoInterface
     */
    protected $dao;

    /**
     * @var \AppBundle\Service\KlantDaoInterface
     */
    protected $searchDao;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function __construct(KlantDaoInterface $dao, \AppBundle\Service\KlantDaoInterface $searchDao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->searchDao = $searchDao;
        $this->export = $export;
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
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
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
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                    $message =  $e->getMessage();
                    $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
                } catch (\Exception $e) {
                    $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
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
        // $entityManager = $this->getEntityManager();
        // $appKlant = $entityManager->find(AppKlant::class, $id);
        $klantRepo = $this->getEntityManager()->getRepository(Klant::class);
        $klant = $klantRepo->findOneBy(['appKlant'=>$id]);

        if($klant==null)
        {
            //no klant... redirect to add
            return $this->redirectToRoute('tw_klanten_add',['entity'=>$id]);
        }
        return $this->redirectToRoute('tw_klanten_view',['id'=>$klant->getId()]);
    }

}
