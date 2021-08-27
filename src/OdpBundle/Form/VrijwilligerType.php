<?php

namespace OdpBundle\Form;

use InloopBundle\Form\VrijwilligerTypeAbstract;
use OdpBundle\Entity\Vrijwilliger;
use OdpBundle\OdpBundle;
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
