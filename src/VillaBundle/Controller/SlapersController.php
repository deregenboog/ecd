<?php

namespace VillaBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Controller\DossierStatusControllerInterface;
use AppBundle\Controller\DossierStatusControllerTrait;
use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use AppBundle\Model\HasDossierStatusInterface;
use AppBundle\Service\KlantDao;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use VillaBundle\Entity\Afsluiting;
use VillaBundle\Entity\Slaper;
use VillaBundle\Form\SlaperFilterType;
use VillaBundle\Form\SlaperType;
use VillaBundle\Service\SlaperDao;

/**
 * @Route("/slapers")
 *
 * @Template
 */
class SlapersController extends AbstractController implements DossierStatusControllerInterface
{
    use DossierStatusControllerTrait;

    protected $entityName = 'slaper';
    protected $entityClass = Slaper::class;
    protected $formClass = SlaperType::class;
    protected $filterFormClass = SlaperFilterType::class;
    protected $baseRouteName = 'villa_slapers_';

    protected $searchFilterTypeClass = AppKlantFilterType::class;
    protected $searchEntity = AppKlant::class;
    protected $searchEntityName = 'appKlant';

//    protected $addMethod = 'doAdd';

    /**
     * @var SlaperDao
     */
    protected $dao;

    /**
     * @var KlantDao
     */
    private $klantDao;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function __construct(SlaperDao $dao, KlantDao $klantDao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->klantDao = $klantDao;
        $this->searchDao = $klantDao;
        $this->export = $export;
    }

    protected function getDownloadFilename()
    {

        return sprintf('villa-slapers-%s.xlsx', (new \DateTime())->format('d-m-Y'));
    }


    /**
     * @Route("/{id}/open")
     * @Template("open.html.twig")
     */
    public function openAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        if(!$entity instanceof HasDossierStatusInterface)
        {
            throw new UserException("Kan geen dossierstatus bewerken van een entiteit die geen dossierstatus heeft.");
        }

        $hDs = $entity->getHuidigeDossierstatus();
        if(get_class($hDs) !== $hDs->getClosedClass() )
        {
            throw new UserException("Kan dossier niet openen want er is geen geldige dossierstatus (afgesloten).");
        }

        $aanmeldingClassname = $hDs->getOpenClass();
        $pDs = $entity->getPreviousDossierStatus();

        $lastYear = (new \DateTime('now'))->modify('-12 months');


        if(null !== $pDs && $pDs instanceof $aanmeldingClassname && $pDs->getDatum() >= $lastYear)
        {
            throw new UserException("Kan dossier niet openen want de vorige startdatum is korter dan 12 maanden geleden. Verwijder de afsluiting om het dossier opnieuw te openen.");
        }

        $aanmelding = new $aanmeldingClassname();
        $entity->addDossierStatus($aanmelding);

        $form = $this->getForm($aanmelding->getFormType(), $aanmelding);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);
                $this->addFlash('success', 'Het dossier is (opnieuw) geopend.');

                return $this->redirectToRoute($this->baseRouteName.'view', ['id' => $entity->getId()]);
            } catch(UserException $e) {
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
        }

        return [
            'form' => $form->createView(),
            'entity' => $entity,
        ];
    }

    public function beforeEdit($entity): void
    {
        /** @var Slaper $entity */
        $entity;
        if($entity->getHuidigeDossierStatus() instanceof Afsluiting)
        {
            throw new UserException("Kan geen dossier bewerken dat afgesloten is.");
        }
    }
}
