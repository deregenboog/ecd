<?php

namespace MwBundle\Form;

use AppBundle\Form\RapportageType as BaseRapportageType;
use InloopBundle\Form\LocatieSelectType;
use Symfony\Component\Form\FormBuilderInterface;

class RapportageType extends BaseRapportageType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('locatie', LocatieSelectType::class, [
            'locatietypes' => [
                'Maatschappelijk werk',
                'Virtueel',
            ],
        ]);
        parent::buildForm($builder, $options);
    }
}
