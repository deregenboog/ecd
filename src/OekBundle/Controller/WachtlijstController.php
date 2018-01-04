<?php

namespace OekBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Entity\Klant;
use OekBundle\Entity\OekKlant;
use OekBundle\Form\OekKlantFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wachtlijst")
 */
class WachtlijstController extends SymfonyController
{
    private $enabledFilters = [
        'klant' => ['id', 'naam', 'stadsdeel'],
        'groep',
        'aanmelddatum',
        'afsluitdatum',
    ];

    private $sortFieldWhitelist = [
        'klant.id',
        'klant.achternaam',
        'werkgebied.naam',
        'oekGroep.naam',
        'oekAanmelding.datum',
        'oekAfsluiting.datum',
    ];

    /**
     * @Route("/")
     */
    public function index()
    {
        $repository = $this->getEntityManager()->getRepository(OekKlant::class);
        $builder = $repository->createQueryBuilder('oekKlant')
            ->select('oekKlant, klant, oekAanmelding, oekAfsluiting, verwijzingAanmelding, verwijzingAfsluiting, oekDossierStatus, oekLidmaatschap, oekGroep')
            ->innerJoin('oekKlant.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->leftJoin('oekKlant.oekAanmelding', 'oekAanmelding')
            ->leftJoin('oekKlant.oekAfsluiting', 'oekAfsluiting')
            ->leftJoin('oekAanmelding.verwijzing', 'verwijzingAanmelding')
            ->leftJoin('oekAfsluiting.verwijzing', 'verwijzingAfsluiting')
            ->leftJoin('oekKlant.oekDossierStatus', 'oekDossierStatus')
            ->innerJoin('oekKlant.oekLidmaatschappen', 'oekLidmaatschap')
            ->innerJoin('oekLidmaatschap.oekGroep', 'oekGroep')
        ;

        $filter = $this->createFilter();
        if ($filter->isValid()) {
            $filter->getData()->applyTo($builder);
            if ($filter->get('download')->isClicked()) {
                return $this->download($builder);
            }
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => 'klant.achternaam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
            'wrap-queries' => true, // because of HAVING clause in filter
        ]);

        return ['filter' => $filter->createView(), 'pagination' => $pagination];
    }

    public function download(QueryBuilder $builder)
    {
        $oekKlanten = $builder->getQuery()->getResult();

        $response = $this->render('@Oek/wachtlijst/download.csv.twig', compact('oekKlanten'));

        $filename = sprintf('op-eigen-kracht-deelnemers-%s.xls', (new \DateTime())->format('d-m-Y'));
        $response->headers->set('Content-type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s";', $filename));
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /**
     * @return FormInterface
     */
    private function createFilter()
    {
        $filter = $this->createForm(OekKlantFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->getRequest());

        return $filter;
    }
}
