<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HsBundle\Entity\HsMemo;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use AppBundle\Form\AppDateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class HsMemoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medewerker')
            ->add('datum', AppDateTimeType::class, ['data' => new \DateTime('now')])
            ->add('intake')
            ->add('memo', TextareaType::class, ['attr' => ['cols' => 80, 'rows' => 20]])
            ->add('referer', HiddenType::class, ['mapped' => false])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HsMemo::class,
        ]);
    }
}
