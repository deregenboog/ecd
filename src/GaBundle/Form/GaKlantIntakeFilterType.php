<?php

namespace GaBundle\Form;

use AppBundle\Entity\Medewerker;
use Doctrine\ORM\EntityRepository;
use GaBundle\Entity\GaKlantIntake;
use GaBundle\Filter\GaKlantIntakeFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\KlantFilterType;
use AppBundle\Form\FilterType;
use AppBundle\Form\AppDateRangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GaKlantIntakeFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, ['enabled_filters' => $options['enabled_filters']['klant']]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GaKlantIntakeFilter::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return GaIntakeFilterType::class;
    }
}
