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
        $form = $this->createForm(IzKoppelingFilterType::class);
        $form->handleRequest($this->request);

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

        $pagination = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
            'defaultSortFieldName' => 'izHulpvraag.koppelingStartdatum',
            'defaultSortDirection' => 'desc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('form', $form->createView());
        $this->set('pagination', $pagination);
    }
}
