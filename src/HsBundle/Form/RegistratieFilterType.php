<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use AppBundle\Form\FilterType;
use AppBundle\Form\AppDateRangeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use HsBundle\Entity\Activiteit;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use HsBundle\Filter\RegistratieFilter;
use HsBundle\Entity\Arbeider;
use Doctrine\ORM\EntityRepository;
use HsBundle\Entity\Dienstverlener;
use HsBundle\Entity\Vrijwilliger;

class RegistratieFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('arbeider', $options['enabled_filters'])) {
            $builder->add('arbeider', EntityType::class, [
                'required' => false,
                'label' => 'Dienstverlener/vrijwilliger',
                'class' => Arbeider::class,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('arbeider')
                        ->leftJoin(Dienstverlener::class, 'dienstverlener', 'WITH', 'dienstverlener = arbeider')
                        ->leftJoin(Vrijwilliger::class, 'vrijwilliger', 'WITH', 'vrijwilliger = arbeider')
                        ->leftJoin('dienstverlener.klant', 'klant')
                        ->leftJoin('vrijwilliger.vrijwilliger', 'basisvrijwilliger')
                        ->orderBy('basisvrijwilliger.achternaam, klant.achternaam')
                    ;
                },
                'group_by' => function ($value, $key, $index) {
                    if ($value instanceof Dienstverlener) {
                        return 'Dienstverleners';
                    } else {
                        return 'Vrijwilligers';
                    }
                },
            ]);
        }

        if (in_array('datum', $options['enabled_filters'])) {
            $builder->add('datum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (key_exists('klant', $options['enabled_filters'])) {
            $builder
                ->add('klant', KlantFilterType::class, [
                    'enabled_filters' => $options['enabled_filters']['klant'],
                ])
                ->get('klant')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    $data = $event->getData();
                    $data->status = null;
                    $event->setData($data);
                })
            ;
        }

        if (in_array('activiteit', $options['enabled_filters'])) {
            $builder->add('activiteit', EntityType::class, [
                'required' => false,
                'class' => Activiteit::class,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RegistratieFilter::class,
            'data' => new RegistratieFilter(),
            'enabled_filters' => [
                'arbeider',
                'datum',
                'activiteit',
                'klant' => ['naam', 'stadsdeel'],
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
