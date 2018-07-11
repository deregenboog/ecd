<?php

namespace GaBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\BaseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiviteitenReeksType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $activiteit = $options['data']->getActiviteit();

        $builder
            ->add($builder->create('activiteit', ActiviteitType::class, [
                'data' => $activiteit,
            ])->remove('datum'))
            ->add('periode', AppDateRangeType::class, [
            ])
            ->add('frequentie', ChoiceType::class, [
                'expanded' => true,
                'choices' => [
                    'iedere' => 0,
                    '1e' => 1,
                    '2e' => 2,
                    '3e' => 3,
                    '4e' => 4,
                ],
            ])
            ->add('weekdag', ChoiceType::class, [
                'expanded' => true,
                'choices' => [
                    'maandag' => 'Monday',
                    'dinsdag' => 'Tuesday',
                    'woensdag' => 'Wednesday',
                    'donderdag' => 'Thurday',
                    'vrijdag' => 'Friday',
                    'zaterdag' => 'Saturday',
                    'zondag' => 'Sunday',
                ],
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
            'data_class' => ActiviteitenReeksModel::class,
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
