<?php

namespace VillaBundle\Form;

use InloopBundle\Form\VrijwilligerTypeAbstract;
use Symfony\Component\Form\FormBuilderInterface;
use VillaBundle\Entity\Vrijwilliger;

class VrijwilligerType extends VrijwilligerTypeAbstract
{
    protected $dataClass = Vrijwilliger::class;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->remove('locaties');
    }
}
