<?php

namespace InloopBundle\Form;

use AppBundle\Form\RapportageType as BaseRapportageType;
use Symfony\Component\Form\FormBuilderInterface;

class RapportageType extends BaseRapportageType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('locatie', LocatieSelectType::class, [
            'locatietypes' => 'Inloop',
            'priority' => 10, // this is so it is added as the first option (default:0). calling parent:: after this statement will trhow various results.
        ]);
    }
}
