<?php

namespace InloopBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Aanmelding;
use InloopBundle\Entity\Afsluiting;
use InloopBundle\Entity\Locatie;
use InloopBundle\Filter\KlantFilter;
use InloopBundle\Filter\LocatieFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocatieFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('naam', $options['enabled_filters'])) {
            $builder->add('naam');
        }

        if (in_array('locatieTypes', $options['enabled_filters'])) {
//            $builder->add('locatieTypes', EntityType::class, [
//                'class' => \InloopBundle\Entity\LocatieType::class,
//                'required' => false,
//                'query_builder' => function (EntityRepository $repository): QueryBuilder {
//                    return $repository->createQueryBuilder('locatie_type')
//                        ->orderBy('locatie_type.naam')
//                    ;
//
//               },
//            ]);
            $builder->add('locatieTypes',LocatieTypeSelectType::class,['multiple'=>true]);
        }

        if (in_array('status', $options['enabled_filters'])) {
            $builder->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Actieve locaties' => LocatieFilter::STATUS_ACTIEF,
                    'Inactieve locaties' => LocatieFilter::STATUS_INACTIEF,
                ],
            ]);
        }

        $builder
            ->add('filter', SubmitType::class, ['label' => 'Filteren'])
//             ->add('download', SubmitType::class, ['label' => 'Downloaden'])
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
            'data_class' => LocatieFilter::class,
            'data' => new LocatieFilter(),
            'enabled_filters' => [
                'naam',
                'locatieTypes',
                'status',
            ],
        ]);
    }
}
