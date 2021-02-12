<?php

namespace VillaBundle\Form;

use InloopBundle\Form\VrijwilligerTypeAbstract;
use Symfony\Component\Form\FormBuilderInterface;
use VillaBundle\Entity\Vrijwilliger;
use VillaBundle\VillaBundle;


class VrijwilligerType extends VrijwilligerTypeAbstract
{
    protected $dataClass = Vrijwilliger::class;
    protected $locatieSelectClass = LocatieSelectType::class;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder,$options);
        $builder->remove("locaties");
    }
}
