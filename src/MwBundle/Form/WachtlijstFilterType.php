<?php

namespace MwBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use AppBundle\Form\StadsdeelSelectType;
use InloopBundle\Form\LocatieSelectType;
use InloopBundle\Service\LocatieDao;
use MwBundle\Filter\WachtlijstFilter;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WachtlijstFilterType extends AbstractType implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * WachtlijstFilterType constructor.
     *
     * @param $wachtlijstLocaties
     */
    protected $wachtlijstLocaties = [];

    public function __construct(LocatieDao $locatieDao)
    {
        $this->wachtlijstLocaties = $locatieDao->getWachtlijstLocaties();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', AppKlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }

        if (in_array('locatie', $options['enabled_filters'])) {
            $builder->add('locatie', LocatieSelectType::class, [
                'locatietypes' => ['Wachtlijst'],
                'placeholder' => 'T6 wachtlijsten (default)',
            ]);
        }
        if (in_array('werkgebied', $options['enabled_filters'])) {
            $builder->add('werkgebied', StadsdeelSelectType::class, [
                'required' => false,
            ]);
        }

        if (in_array('datum', $options['enabled_filters'])) {
            $builder->add('datum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }
    }

    public function getParent(): ?string
    {
        return FilterType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WachtlijstFilter::class,
            'enabled_filters' => [
                'klant' => ['id', 'voornaam', 'achternaam', 'geslacht'],
                'werkgebied',
                'locatie',
                'datum',
                'filter',
            ],
        ]);
    }

    public function setWachtlijstLocaties($wachtlijstLocaties)
    {
        $this->wachtlijstLocaties = $wachtlijstLocaties;
    }
}
