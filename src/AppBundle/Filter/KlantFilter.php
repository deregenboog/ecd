<?php

namespace AppBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use Doctrine\ORM\QueryBuilder;

class KlantFilter implements FilterInterface
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $naam;

    /**
     * @var \DateTime
     */
    public $geboortedatum;

    /**
     * @var string
     */
    public $stadsdeel;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
            ->andWhere('klant.id = :klant_id')
            ->setParameter('klant_id', $this->id)
            ;
        }

        if ($this->naam) {
            $builder
                ->andWhere('CONCAT(klant.voornaam, klant.roepnaam, klant.tussenvoegsel, klant.achternaam) LIKE :klant_naam')
                ->setParameter('klant_naam', "%{$this->naam}%")
            ;
        }

        if ($this->geboortedatum) {
            $builder
                ->andWhere('klant.geboortedatum = :klant_geboortedatum')
                ->setParameter('klant_geboortedatum', $this->geboortedatum)
            ;
        }

        if (isset($this->stadsdeel['naam'])) {
            $builder
                ->andWhere('klant.werkgebied = :klant_stadsdeel')
                ->setParameter('klant_stadsdeel', $this->stadsdeel['naam'])
            ;
        }
    }
}
