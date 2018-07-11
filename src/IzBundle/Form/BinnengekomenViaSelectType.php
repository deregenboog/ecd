<?php

namespace IzBundle\Form;

use IzBundle\Entity\BinnengekomenVia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\BaseSelectType;

class BinnengekomenViaSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => BinnengekomenVia::class,
        ]);
    }

    public function getParent()
    {
        return BaseSelectType::class;
    }
}
