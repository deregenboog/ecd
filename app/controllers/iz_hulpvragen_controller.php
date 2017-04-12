<?php

use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\IzKlant;
use IzBundle\Entity\IzHulpvraag;
use AppBundle\Entity\Medewerker;
use IzBundle\Form\IzHulpvraagFilterType;

class IzHulpvragenController extends AppController
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
        'startdatum',
        'klant' => ['id', 'naam', 'geboortedatumRange', 'stadsdeel'],
        'izProject',
        'medewerker',
    ];

    private $sortFieldWhitelist = [
        'izHulpvraag.startdatum',
        'izProject.naam',
        'klant.id',
        'klant.voornaam',
        'klant.achternaam',
        'klant.geboortedatum',
        'klant.werkgebied',
        'klant.laatsteZrm',
        'medewerker.achternaam',
    ];

    public function index()
    {
        $form = $this->createForm(IzHulpvraagFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $form->handleRequest($this->getRequest());

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(IzHulpvraag::class);

        $builder = $repository->createQueryBuilder('izHulpvraag')
            ->innerJoin('izHulpvraag.izKlant', 'izKlant')
            ->innerJoin('izHulpvraag.izProject', 'izProject')
            ->innerJoin('izHulpvraag.medewerker', 'medewerker')
            ->innerJoin('izKlant.klant', 'klant')
            ->where('izHulpvraag.izHulpaanbod IS NULL')
            ->andWhere('izHulpvraag.einddatum IS NULL')
            ->andWhere('izKlant.izAfsluiting IS NULL')
            ->andWhere('klant.disabled = false');

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData()->applyTo($builder);

            if ($form->get('download')->isClicked()) {
                return $this->download($builder);
            }
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => 'izHulpvraag.startdatum',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('form', $form->createView());
        $this->set('pagination', $pagination);
    }

    public function download(QueryBuilder $builder)
    {
        $hulpvragen = $builder->getQuery()->getResult();

        $filename = sprintf('iz-hulpvragen-%s.xls', (new \DateTime())->format('d-m-Y'));
        $this->header('Content-type: application/vnd.ms-excel');
        $this->header(sprintf('Content-Disposition: attachment; filename="%s";', $filename));
        $this->header('Content-Transfer-Encoding: binary');

        $this->set('hulpvragen', $hulpvragen);
        $this->render('download', false);
    }
}
