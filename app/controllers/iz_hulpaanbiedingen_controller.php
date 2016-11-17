<?php

use AppBundle\Entity\IzVrijwilliger;
use AppBundle\Entity\IzHulpaanbod;
use AppBundle\Form\IzHulpaanbodFilterType;

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

    private $sortFieldWhitelist = [
        'vrijwilliger.id',
        'vrijwilliger.voornaam',
        'vrijwilliger.achternaam',
        'vrijwilliger.geboortedatum',
        'vrijwilliger.werkgebied',
        'vrijwilliger.laatsteZrm',
        'izProject.naam',
        'medewerker.achternaam',
    ];

    public function index()
    {
        $form = $this->createForm(IzHulpaanbodFilterType::class);
        $form->handleRequest($this->request);

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

        if ($form->isValid()) {
            $filter = $form->getData();
            if ($id = $filter->getIzVrijwilliger()->getVrijwilliger()->getId()) {
                $builder->andWhere('vrijwilliger.id = :id')->setParameter('id', $id);
            }
            if ($voornaam = $filter->getIzVrijwilliger()->getVrijwilliger()->getVoornaam()) {
                $builder->andWhere('vrijwilliger.voornaam LIKE :voornaam')->setParameter('voornaam', "%{$voornaam}%");
            }
            if ($achternaam = $filter->getIzVrijwilliger()->getVrijwilliger()->getAchternaam()) {
                $builder->andWhere('vrijwilliger.achternaam LIKE :achternaam')->setParameter('achternaam', "%{$achternaam}%");
            }
            if ($geboortedatum = $filter->getIzVrijwilliger()->getVrijwilliger()->getGeboortedatum()) {
                $builder->andWhere('vrijwilliger.geboortedatum = :geboortedatum')->setParameter('geboortedatum', $geboortedatum);
            }
            if ($izProject = $filter->getIzProject()) {
                $builder->andWhere('izHulpaanbod.izProject = :izProject')->setParameter('izProject', $izProject);
            }
            if ($medewerker = $filter->getMedewerker()) {
                $builder->andWhere('izHulpaanbod.medewerker = :medewerker')->setParameter('medewerker', $medewerker);
            }
            if ($werkgebied = $filter->getIzVrijwilliger()->getVrijwilliger()->getWerkgebied()) {
                $builder->andWhere('vrijwilliger.werkgebied LIKE :werkgebied')->setParameter('werkgebied', "%{$werkgebied}%");
            }
        }

        $izHulpaanbiedingen = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
            'defaultSortFieldName' => 'vrijwilliger.achternaam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('form', $form->createView());
        $this->set('izHulpaanbiedingen', $izHulpaanbiedingen);
    }
}
