<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\VrijwilligerFilterType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Filter\HulpaanbodFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HulpaanbodFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('matching', $options['enabled_filters'])) {
            $builder->add('matching', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen matchende kandidaten tonen',
            ]);
        }

        if (in_array('startdatum', $options['enabled_filters'])) {
            $builder->add('startdatum', AppDateType::class, [
                'required' => false,
            ]);
        }

        if (key_exists('vrijwilliger', $options['enabled_filters'])) {
            $builder->add('vrijwilliger', VrijwilligerFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['vrijwilliger'],
            ]);
        }

        if (in_array('project', $options['enabled_filters'])) {
            $builder->add('project', ProjectSelectFilterType::class, [
            ]);
        }

        if (in_array('hulpvraagsoort', $options['enabled_filters'])) {
            $builder->add('hulpvraagsoort', HulpvraagsoortSelectFilterType::class, [
                'required' => false,
                'expanded' => false,
            ]);
        }

        if (in_array('doelgroep', $options['enabled_filters'])) {
            $builder->add('doelgroep', DoelgroepSelectType::class, [
                'required' => false,
                'expanded' => false,
            ]);
        }

        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', MedewerkerType::class, [
                'required' => false,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(Hulpaanbod::class, 'hulpaanbod', 'WITH', 'hulpaanbod.medewerker = medewerker')
                        ->where('medewerker.actief = true')
                        ->orderBy('medewerker.voornaam', 'ASC')
                    ;
                },
                'preset' => $options['preset_medewerker'],
            ]);
        }

        if (in_array('zoekterm', $options['enabled_filters'])) {
            $builder->add('zoekterm', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Zoekterm'],
            ]);
        }

        if (in_array('filter', $options['enabled_filters'])) {
            $builder->add('filter', SubmitType::class, ['label' => 'Filteren']);
        }

        if (in_array('download', $options['enabled_filters'])) {
            $builder->add('download', SubmitType::class, ['label' => 'Downloaden']);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HulpaanbodFilter::class,
            'data' => new HulpaanbodFilter(),
            'enabled_filters' => [
                'startdatum',
                'vrijwilliger' => ['id', 'voornaam', 'achternaam', 'geboortedatumRange', 'stadsdeel'],
                'project',
                'hulpvraagsoort',
                'doelgroep',
                'medewerker',
                'zoekterm',
                'filter',
                'download',
            ],
            'preset_medewerker' => false,
        ]);
    }

    public function getParent(): ?string
    {
        return KoppelingFilterType::class;
    }
}
