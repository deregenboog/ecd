<?php

use AppBundle\Entity\Klant;
use OdpBundle\Entity\OdpHuurder;
use OdpBundle\Entity\OdpHuurovereenkomst;
use OdpBundle\Filter\OdpHuurovereenkomstFilter;
use OdpBundle\Form\OdpHuurderType;
use AppBundle\Form\KlantFilterType;
use OdpBundle\Form\OdpHuurderSelectType;
use Doctrine\DBAL\Driver\PDOException;
use OdpBundle\Form\OdpHuurderFilterType;
use AppBundle\Form\ConfirmationType;
use AppBundle\Entity\Medewerker;
use OdpBundle\Entity\HsMemo;
use OdpBundle\Form\HsMemoType;
use OdpBundle\Form\OdpHuurovereenkomstFilterType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OdpHuurovereenkomstenController extends AppController
{
    /**
     * Don't use CakePHP models.
     */
    public $uses = [];

    /**
     * Use Twig.
     */
    public $view = 'AppTwig';

    private $enabledFilters = [
        'id',
        'odpHuurder' => ['klant' => ['naam']],
        'odpVerhuurder' => ['klant' => ['naam']],
        'startdatum',
        'einddatum',
//        'medewerker'
    ];

    private $sortFieldWhitelist = [
        'odpHuurovereenkomst.id',
        'odpHuurderKlant.achternaam',
        'odpVerhuurderKlant.achternaam',
        'odpHuurovereenkomst.startdatum',
        'odpHuurovereenkomst.einddatum',
        'medewerker'
    ];

    public function index()
    {
        $filter = $this->createForm(OdpHuurovereenkomstFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->request);

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OdpHuurovereenkomst::class);

        $builder = $repository->createQueryBuilder('odpHuurovereenkomst')
            ->innerJoin('odpHuurovereenkomst.odpHuurverzoek', 'odpHuurverzoek')
            ->innerJoin('odpHuurovereenkomst.odpHuuraanbod', 'odpHuuraanbod')
            ->innerJoin('odpHuurverzoek.odpHuurder', 'odpHuurder')
            ->innerJoin('odpHuuraanbod.odpVerhuurder', 'odpVerhuurder')
            ->innerJoin('odpHuurder.klant', 'odpHuurderKlant')
            ->innerJoin('odpVerhuurder.klant', 'odpVerhuurderKlant')
            ->andWhere('odpHuurderKlant.disabled = false')
            ->andWhere('odpVerhuurderKlant.disabled = false')
        ;

        if ($filter->isValid()) {
            $filter->getData()->applyTo($builder);
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
            'defaultSortFieldName' => 'odpHuurovereenkomst.id',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('filter', $filter->createView());
        $this->set('pagination', $pagination);
    }
}
