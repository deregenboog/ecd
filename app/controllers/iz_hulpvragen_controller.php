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
        $form = $this->createForm(IzHulpvraagFilterType::class);
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
        ;

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
        ini_set('memory_limit', '512M');

        $hulpvragen = $builder->getQuery()->getResult();

        $this->autoRender = false;
        $filename = sprintf('iz-hulpvragen-%s.xls', (new \DateTime())->format('d-m-Y'));

        $export = $this->container->get('iz.export.hulpvragen');
        $export->create($hulpvragen)->send($filename);
    }
}
