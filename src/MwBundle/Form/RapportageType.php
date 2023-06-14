<?php

namespace MwBundle\Form;

use AppBundle\Form\RapportageType as BaseRapportageType;
use Doctrine\ORM\EntityRepository;
use InloopBundle\Entity\Locatie;
use InloopBundle\Form\LocatieSelectType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class RapportageType extends BaseRapportageType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('locatie',LocatieSelectType::class,[
            'locatietypes'=>[
                'Maatschappelijk werk',
                'Virtueel'
            ]
        ]);
        parent::buildForm($builder, $options);
    }
}
