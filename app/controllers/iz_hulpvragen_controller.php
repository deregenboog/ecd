<?php

use IzBundle\Entity\IzKlant;
use IzBundle\Entity\IzHulpvraag;
use AppBundle\Entity\Medewerker;
use IzBundle\Form\IzHulpvraagFilterType;
use Symfony\Component\HttpFoundation\Request;

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
        'klant' => ['id', 'naam', 'geboortedatum', 'stadsdeel'],
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
        $form->handleRequest($this->request);

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

        if ($form->isValid()) {
            $filter = $form->getData()->applyTo($builder);
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
            'defaultSortFieldName' => 'izHulpvraag.startdatum',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('form', $form->createView());
        $this->set('pagination', $pagination);
    }
}
