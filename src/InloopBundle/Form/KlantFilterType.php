<?php

namespace InloopBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use Doctrine\ORM\EntityRepository;
use InloopBundle\Entity\Aanmelding;
use InloopBundle\Entity\Afsluiting;
use AppBundle\Entity\Locatie;
use InloopBundle\Filter\KlantFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlantFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', AppKlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }

        if (in_array('gebruikersruimte', $options['enabled_filters'])) {
            $builder->add('gebruikersruimte', LocatieSelectType::class, [
                'required' => false,
                'gebruikersruimte' => true,
            ]);
        }

        if (in_array('laatsteIntakeLocatie', $options['enabled_filters'])) {
            $builder->add('laatsteIntakeLocatie', EntityType::class, [
                'class' => Locatie::class,
                'required' => false,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('locatie')
                        ->orderBy('locatie.naam')
                    ;
                },
            ]);
        }

        if (in_array('laatsteIntakeDatum', $options['enabled_filters'])) {
            $builder->add('laatsteIntakeDatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('huidigeStatus', $options['enabled_filters'])) {
            $builder->add('huidigeStatus', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Aangemeld' => Aanmelding::class,
                    'Afgesloten' => Afsluiting::class,
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
            'data_class' => KlantFilter::class,
            'data' => new KlantFilter(),
            'enabled_filters' => [
                'klant' => ['id', 'voornaam', 'achternaam', 'geboortedatumRange', 'geslacht'],
                'gebruikersruimte',
                'laatsteIntakeLocatie',
                'laatsteIntakeDatum',
                'huidigeStatus',
            ],
        ]);
    }
}
