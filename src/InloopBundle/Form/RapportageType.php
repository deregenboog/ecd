<?php

namespace InloopBundle\Form;

use AppBundle\Form\RapportageType as BaseRapportageType;
use Symfony\Component\Form\FormBuilderInterface;

class RapportageType extends BaseRapportageType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('locatie',LocatieSelectType::class,['locatietypes'=>'Inloop']);
        parent::buildForm($builder, $options);
    }
}
