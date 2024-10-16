<?php

namespace HsBundle\Form;

use AppBundle\Form\BaseType;
use HsBundle\Entity\Herinnering;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HerinneringType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => Herinnering::TYPES,
            ])
//             ->add('klus')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Herinnering::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
