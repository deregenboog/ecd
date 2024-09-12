<?php

namespace OekraineBundle\Form;

use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use AppBundle\Form\MedewerkerSelectType;
use OekraineBundle\Entity\Aanmelding;
use OekraineBundle\Entity\Afsluiting;
use OekraineBundle\Filter\BezoekerFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BezoekerFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('appKlant', $options['enabled_filters'])) {
            $builder->add('appKlant', AppKlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['appKlant'],
            ]);
        }

        if (in_array('woonlocatie', $options['enabled_filters'])) {
            $builder->add('woonlocatie', LocatieSelectType::class, [
                'required' => false,
            ]);
        }
        if (in_array('mentalCoach', $options['enabled_filters'])) {
            $builder->add('mentalCoach', MedewerkerSelectType::class, [
                'required' => false,
                'multiple'=>false,
                'roles'=>  [
                        'ROLE_OEKRAINE_PSYCH',
                    ],
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
             ->add('download', SubmitType::class, ['label' => 'Downloaden'])
        ;
    }

    public function getParent(): ?string
    {
        return FilterType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BezoekerFilter::class,
            'data' => new BezoekerFilter(),
            'enabled_filters' => [
                'appKlant' => ['id', 'voornaam', 'achternaam', 'geboortedatumRange', 'geslacht', 'maatschappelijkWerker'],
                'woonlocatie',
                'mentalCoach',
                'huidigeStatus',
            ],
        ]);
    }
}
