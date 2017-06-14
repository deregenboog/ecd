<?php

use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Entity\IzHulpaanbod;
use IzBundle\Form\IzHulpaanbodFilterType;
use IzBundle\Export\IzHulpaanbiedingenExport;

class IzHulpaanbiedingenController extends AppController
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
        'vrijwilliger' => ['id', 'voornaam', 'achternaam', 'geboortedatumRange', 'stadsdeel'],
        'izProject',
        'medewerker',
    ];

    private $sortFieldWhitelist = [
        'izHulpaanbod.startdatum',
        'izProject.naam',
        'vrijwilliger.id',
        'vrijwilliger.voornaam',
        'vrijwilliger.achternaam',
        'vrijwilliger.geboortedatum',
        'vrijwilliger.werkgebied',
        'vrijwilliger.laatsteZrm',
        'medewerker.achternaam',
    ];

    public function index()
    {
        $form = $this->createForm(IzHulpaanbodFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $form->handleRequest($this->getRequest());

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(IzHulpaanbod::class);

        $builder = $repository->createQueryBuilder('izHulpaanbod')
            ->innerJoin('izHulpaanbod.izVrijwilliger', 'izVrijwilliger')
            ->innerJoin('izHulpaanbod.izProject', 'izProject')
            ->innerJoin('izHulpaanbod.medewerker', 'medewerker')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->where('izHulpaanbod.izHulpvraag IS NULL')
            ->andWhere('izHulpaanbod.einddatum IS NULL')
            ->andWhere('izVrijwilliger.izAfsluiting IS NULL')
            ->andWhere('vrijwilliger.disabled = false');

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData()->applyTo($builder);

            if ($form->get('download')->isClicked()) {
                return $this->download($builder);
            }
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => 'izHulpaanbod.startdatum',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('form', $form->createView());
        $this->set('pagination', $pagination);
    }

    public function download(QueryBuilder $builder)
    {
        ini_set('memory_limit', '512M');

        $hulpaanbiedingen = $builder->getQuery()->getResult();

        $this->autoRender = false;
        $filename = sprintf('iz-hulpaanbiedingen-%s.xls', (new \DateTime())->format('d-m-Y'));

        $export = $this->container->get('iz.export.hulpaanbiedingen');
        $export->create($hulpaanbiedingen)->send($filename);
    }
}
