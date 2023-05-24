<?php

namespace ScipBundle\Form;

use AppBundle\Form\FilterType;
use AppBundle\Form\JaNeeType;
use AppBundle\Form\KlantFilterType;
use ScipBundle\Entity\Deelnemer;
use ScipBundle\Filter\DeelnemerFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeelnemerFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }

        if (in_array('actief', $options['enabled_filters'])) {
            $builder->add('actief', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen actieve vrijwilligers tonen',
            ]);
        }

        if (in_array('project', $options['enabled_filters'])) {
            $builder->add('project', ProjectSelectType::class, [
                'required' => false,
            ]);
        }

        if (in_array('type', $options['enabled_filters'])) {
            $builder->add('type', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    Deelnemer::TYPE_WMO => Deelnemer::TYPE_WMO,
                    Deelnemer::TYPE_ONDERAANNEMER => Deelnemer::TYPE_ONDERAANNEMER,
                ],
            ]);
        }

        if (in_array('label', $options['enabled_filters'])) {
            $builder->add('label', LabelSelectType::class, [
                'required' => false,
            ]);
        }

        if (in_array('vog', $options['enabled_filters'])) {
            $builder->add('vog', JaNeeType::class, [
                'required' => false,
                'expanded' => false,
            ]);
        }

        if (in_array('overeenkomst', $options['enabled_filters'])) {
            $builder->add('overeenkomst', JaNeeType::class, [
                'required' => false,
                'expanded' => false,
            ]);
        }

        $builder->add('filter', SubmitType::class, ['label' => 'Filteren']);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return FilterType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeelnemerFilter::class,
            'data' => new DeelnemerFilter(),
            'enabled_filters' => [
                'klant' => ['id', 'naam'],
                'actief',
                'project',
                'type',
                'label',
                'vog',
                'overeenkomst',
            ],
        ]);
    }
}
