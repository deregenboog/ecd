<?php

namespace UhkBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\JaNeeType;
use AppBundle\Form\KlantFilterType;
use AppBundle\Form\MedewerkerFilterType;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use UhkBundle\Entity\Deelnemer;
use UhkBundle\Filter\DeelnemerFilter;
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

        if (in_array('aanmelddatum', $options['enabled_filters'])) {
            $builder->add('aanmelddatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', MedewerkerType::class, [
                'required' => false,
                'preset' => false,
            ]);
        }

        if (in_array('actief', $options['enabled_filters'])) {
            $builder->add('actief', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen actieve deelnemers tonen',
            ]);
        }

        $builder
            ->add('filter', SubmitType::class, ['label' => 'Filteren'])
        ;
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
                'klant' => ['id', 'naam','stadsdeel'],
                'aanmelddatum',
                'medewerker',
                'filter',
                'actief',
//                'download',
            ],
        ]);
    }
}
