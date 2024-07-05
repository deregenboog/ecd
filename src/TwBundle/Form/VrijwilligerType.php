<?php

namespace TwBundle\Form;

use InloopBundle\Form\VrijwilligerTypeAbstract;
use Symfony\Component\Form\FormBuilderInterface;
use TwBundle\Entity\Vrijwilliger;

class VrijwilligerType extends VrijwilligerTypeAbstract
{
    protected $dataClass = Vrijwilliger::class;
    protected $locatieSelectClass = LocatieSelectType::class;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove('tweedeFase');
        $builder->remove('startDatum');
        $builder->remove('medewerkerLocatie');
    }
}
