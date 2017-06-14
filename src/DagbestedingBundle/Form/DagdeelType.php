<?php

namespace DagbestedingBundle\Form;

use AppBundle\Form\BaseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DagdeelType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ochtendAanwezig', CheckboxType::class, ['required' => false])
            ->add('middagAanwezig', CheckboxType::class, ['required' => false])
            ->add('avondAanwezig', CheckboxType::class, ['required' => false])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }
}
