<?php

namespace GaBundle\Controller;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\VrijwilligerFilterType;
use AppBundle\Service\VrijwilligerDao;
use GaBundle\Entity\Vrijwilligerdossier;
use GaBundle\Form\VrijwilligerdossierFilterType;
use GaBundle\Form\VrijwilligerdossierType;
use GaBundle\Service\VrijwilligerdossierDao;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vrijwilligerdossiers")
 * @Template
 */
class VrijwilligerdossiersController extends DossiersController
{
    protected $entityName = 'vrijwilliger';
    protected $entityClass = Vrijwilligerdossier::class;
    protected $formClass = VrijwilligerdossierType::class;
    protected $filterFormClass = VrijwilligerdossierFilterType::class;
    protected $baseRouteName = 'ga_vrijwilligerdossiers_';

    /**
     * @var VrijwilligerdossierDao
     */
    protected $dao;

    /**
     * @var VrijwilligerDao
     */
    private $vrijwilligerDao;

    /**
     * @var ExportInterface
     */
    protected $export;

    /**
     * @param VrijwilligerdossierDao $dao
     * @param VrijwilligerDao $vrijwilligerDao
     * @param ExportInterface $export
     */
    public function __construct(VrijwilligerdossierDao $dao, VrijwilligerDao $vrijwilligerDao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->vrijwilligerDao = $vrijwilligerDao;
        $this->export = $export;
    }


    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        if ($request->get('vrijwilliger')) {
            return $this->doAdd($request);
        }

        return $this->doSearch($request);
    }

    protected function getDownloadFilename()
    {
        return sprintf('groepsactiviteiten-vrijwilligers-%s.xls', (new \DateTime())->format('d-m-Y'));
    }

    protected function doSearch(Request $request)
    {
        $form = $this->getForm(VrijwilligerFilterType::class, null, [
            'enabled_filters' => ['id', 'naam', 'bsn', 'geboortedatum'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $count = (int) $this->vrijwilligerDao->countAll($form->getData());
            if (0 === $count) {
                $this->addFlash('info', sprintf('De zoekopdracht leverde geen resultaten op. Maak een nieuwe %s aan.', $this->entityName));

                return $this->redirectToRoute($this->baseRouteName.'add', ['vrijwilliger' => 'new']);
            }

            if ($count > 100) {
                $form->addError(new FormError('De zoekopdracht leverde teveel resultaten op. Probeer het opnieuw met een specifiekere zoekopdracht.'));

                return [
                    'form' => $form->createView(),
                ];
            }

            return [
                'form' => $form->createView(),
                'vrijwilligers' => $this->vrijwilligerDao->findAll(null, $form->getData()),
            ];
        }

        return [
            'form' => $form->createView(),
        ];
    }

    protected function doAdd(Request $request)
    {
        $vrijwilligerId = $request->get('vrijwilliger');
        if ('new' === $vrijwilligerId) {
            $vrijwilliger = new Vrijwilliger();
        } else {
            $vrijwilliger = $this->vrijwilligerDao->find($vrijwilligerId);
        }

        // redirect if already exists
        $dossier = $this->dao->findOneByVrijwilliger($vrijwilliger);
        if ($dossier) {
            return $this->redirectToView($dossier);
        }

        $dossier = new Vrijwilligerdossier($vrijwilliger);
        $this->formClass = VrijwilligerdossierType::class;
        $this->forceRedirect = true;

        return $this->processForm($request, $dossier);
    }
}
