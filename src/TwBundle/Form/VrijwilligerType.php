<?php

namespace TwBundle\Form;

use InloopBundle\Form\VrijwilligerTypeAbstract;
use TwBundle\Entity\Vrijwilliger;
use TwBundle\TwBundle;
use Symfony\Component\Form\FormBuilderInterface;


class VrijwilligerType extends VrijwilligerTypeAbstract
{
    protected $dataClass = Vrijwilliger::class;
    protected $locatieSelectClass = LocatieSelectType::class;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove("tweedeFase");
        $builder->remove("startDatum");
        $builder->remove("medewerkerLocatie");
    }
}
