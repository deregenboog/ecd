<?php

namespace OekraineBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use Doctrine\ORM\EntityRepository;
use OekraineBundle\Entity\Aanmelding;
use OekraineBundle\Entity\Afsluiting;
use OekraineBundle\Entity\Locatie;
use OekraineBundle\Filter\BezoekerFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BezoekerFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
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
    public function getParent()
    {
        return FilterType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BezoekerFilter::class,
            'data' => new BezoekerFilter(),
            'enabled_filters' => [
                'appKlant' => ['id', 'voornaam', 'achternaam', 'geboortedatumRange', 'geslacht'],
                'woonlocatie',
                'huidigeStatus',
            ],
        ]);
    }
}
