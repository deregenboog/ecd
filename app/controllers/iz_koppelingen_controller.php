<?php

use IzBundle\Entity\IzKlant;
use IzBundle\Entity\IzHulpvraag;
use AppBundle\Entity\Medewerker;
use Symfony\Component\HttpFoundation\Request;
use IzBundle\Form\IzKoppelingFilterType;

class IzKoppelingenController extends AppController
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
        'koppelingStartdatum',
        'koppelingEinddatum',
        'lopendeKoppelingen',
        'klant' => ['naam', 'stadsdeel'],
        'vrijwilliger' => ['naam'],
        'izProject',
        'izHulpvraagMedewerker',
        'izHulpaanbodMedewerker',
    ];

    private $sortFieldWhitelist = [
        'izHulpvraag.koppelingStartdatum',
        'izHulpvraag.koppelingEinddatum',
        'klant.achternaam',
        'klant.werkgebied',
        'vrijwilliger.achternaam',
        'izProject.naam',
        'medewerker.achternaam',
    ];

    public function index()
    {
        $form = $this->createForm(IzKoppelingFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $form->handleRequest($this->getRequest());

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(IzHulpvraag::class);

        $builder = $repository->createQueryBuilder('izHulpvraag')
            ->innerJoin('izHulpvraag.izKlant', 'izKlant')
            ->innerJoin('izKlant.klant', 'klant')
            ->innerJoin('izHulpvraag.izProject', 'izProject')
            ->innerJoin('izHulpvraag.medewerker', 'medewerker')
            ->innerJoin('izHulpvraag.izHulpaanbod', 'izHulpaanbod')
            ->innerJoin('izHulpaanbod.izVrijwilliger', 'izVrijwilliger')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->andWhere('klant.disabled = false')
            ->andWhere('vrijwilliger.disabled = false')
        ;

        if ($form->isValid()) {
            $form->getData()->applyTo($builder);
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => 'izHulpvraag.koppelingStartdatum',
            'defaultSortDirection' => 'desc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('form', $form->createView());
        $this->set('pagination', $pagination);
    }
}
