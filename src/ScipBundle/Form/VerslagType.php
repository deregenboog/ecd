<?php

namespace ScipBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\JaNeeType;
use ScipBundle\Entity\Verslag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VerslagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tekst', AppTextareaType::class, [
                'required' => false,
            ])
            ->add('datum', AppDateType::class, [
                'required' => false,
            ])
            ->add('isEvaluatie', JaNeeType::class, [
                'required' => true,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Verslag::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
