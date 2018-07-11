<?php

namespace GaBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\VrijwilligerFilterType;
use Doctrine\ORM\EntityRepository;
use GaBundle\Entity\Intake;
use GaBundle\Filter\VrijwilligerIntakeFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Vrijwilliger;

class VrijwilligerIntakeFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (key_exists('vrijwilliger', $options['enabled_filters'])) {
            $builder->add('vrijwilliger', VrijwilligerFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['vrijwilliger'],
            ]);
        }

        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('medewerker')
                        ->innerJoin(Vrijwilliger::class, 'vrijwilliger', 'WITH', 'vrijwilliger.medewerker = medewerker')
                        ->orderBy('medewerker.voornaam')
                    ;
                },
            ]);
        }

        if (in_array('intakedatum', $options['enabled_filters'])) {
            $builder->add('intakedatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('afsluitdatum', $options['enabled_filters'])) {
            $builder->add('afsluitdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VrijwilligerIntakeFilter::class,
            'enabled_filters' => [
                'vrijwilliger' => ['id', 'naam', 'geboortedatumRange'],
                'medewerker',
                'intakedatum',
                'afsluitdatum',
                'filter',
                'download',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FilterType::class;
    }
}
