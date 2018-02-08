<?php

namespace PfoBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use PfoBundle\Entity\Groep;

class ClientFilter implements FilterInterface
{
    /**
     * @var bool
     */
    public $actief = true;

    /**
     * @var string
     */
    public $voornaam;

    /**
     * @var string
     */
    public $achternaam;

    /**
     * @var Groep
     */
    public $groep;

    /**
     * @var Medewerker
     */
    public $medewerker;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->voornaam) {
            $parts = preg_split('/\s+/', $this->voornaam);
            foreach ($parts as $i => $part) {
                $builder
                    ->andWhere("client.voornaam LIKE :client_voornaam_part_{$i}")
                    ->setParameter("client_voornaam_part_{$i}", "%{$part}%")
                ;
            }
        }

        if ($this->achternaam) {
            $parts = preg_split('/\s+/', $this->achternaam);
            foreach ($parts as $i => $part) {
                $builder
                    ->andWhere("CONCAT_WS(' ', client.tussenvoegsel, client.achternaam) LIKE :client_achternaam_part_{$i}")
                    ->setParameter("client_achternaam_part_{$i}", "%{$part}%")
                ;
            }
        }

        if ($this->groep) {
            $builder
                ->andWhere('groep = :groep')
                ->setParameter('groep', $this->groep)
            ;
        }

        if ($this->medewerker) {
            $builder
                ->andWhere('medewerker = :medewerker')
                ->setParameter('medewerker', $this->medewerker)
            ;
        }

        if ($this->actief) {
            $builder
                ->andWhere('client.afsluitdatum IS NULL OR client.afsluitdatum > :today')
                ->setParameter('today', new \DateTime('today'))
            ;
        } else {
            $builder
                ->andWhere('client.afsluitdatum <= :today')
                ->setParameter('today', new \DateTime('today'))
            ;
        }
    }
}
