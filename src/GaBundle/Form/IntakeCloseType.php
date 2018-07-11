<?php

namespace GaBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use GaBundle\Entity\Intake;
use GaBundle\Entity\IntakeAfsluitreden;
use GaBundle\Entity\KlantIntake;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntakeCloseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $intake KlantIntake */
        $intake = $options['data'];

        $builder
            ->add('afsluitdatum', AppDateType::class)
            ->add('afsluitreden', EntityType::class, [
                'class' => IntakeAfsluitreden::class,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Intake::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }
}
