<?php

use AppBundle\Entity\IzKlant;
use AppBundle\Entity\IzHulpvraag;
use AppBundle\Entity\Medewerker;
use AppBundle\Form\IzHulpvraagFilterType;
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

    private $sortFieldWhitelist = [
        'klant.id',
        'klant.voornaam',
        'klant.achternaam',
        'klant.geboortedatum',
        'klant.werkgebied',
        'klant.laatsteZrm',
        'izProject.naam',
        'medewerker.achternaam',
    ];

    public function index()
    {
        $form = $this->createForm(IzHulpvraagFilterType::class);
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
            $filter = $form->getData();
            if ($id = $filter->getIzKlant()->getKlant()->getId()) {
                $builder->andWhere('klant.id = :id')->setParameter('id', $id);
            }
            if ($voornaam = $filter->getIzKlant()->getKlant()->getVoornaam()) {
                $builder->andWhere('klant.voornaam LIKE :voornaam')->setParameter('voornaam', "%{$voornaam}%");
            }
            if ($achternaam = $filter->getIzKlant()->getKlant()->getAchternaam()) {
                $builder->andWhere('klant.achternaam LIKE :achternaam')->setParameter('achternaam', "%{$achternaam}%");
            }
            if ($geboortedatum = $filter->getIzKlant()->getKlant()->getGeboortedatum()) {
                $builder->andWhere('klant.geboortedatum = :geboortedatum')->setParameter('geboortedatum', $geboortedatum);
            }
            if ($izProject = $filter->getIzProject()) {
                $builder->andWhere('izHulpvraag.izProject = :izProject')->setParameter('izProject', $izProject);
            }
            if ($medewerker = $filter->getMedewerker()) {
                $builder->andWhere('izHulpvraag.medewerker = :medewerker')->setParameter('medewerker', $medewerker);
            }
            if ($werkgebied = $filter->getIzKlant()->getKlant()->getWerkgebied()) {
                $builder->andWhere('klant.werkgebied LIKE :werkgebied')->setParameter('werkgebied', "%{$werkgebied}%");
            }
        }

        $izHulpvragen = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
            'defaultSortFieldName' => 'klant.achternaam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('form', $form->createView());
        $this->set('izHulpvragen', $izHulpvragen);
    }
}
