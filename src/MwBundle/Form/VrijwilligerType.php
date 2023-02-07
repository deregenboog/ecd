<?php

namespace MwBundle\Form;

use InloopBundle\Form\VrijwilligerTypeAbstract;
use MwBundle\Entity\Vrijwilliger;
use Symfony\Component\Form\FormBuilderInterface;

class VrijwilligerType extends VrijwilligerTypeAbstract
{
    protected $dataClass = Vrijwilliger::class;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove('tweedeFase');
        $builder->remove('startDatum');
        $builder->remove('medewerkerLocatie');
    }
}
