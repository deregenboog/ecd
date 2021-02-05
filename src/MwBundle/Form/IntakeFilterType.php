<?php

namespace MwBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use Doctrine\ORM\EntityRepository;
use InloopBundle\Entity\Locatie;
use InloopBundle\Filter\IntakeFilter;
use MwBundle\Entity\Verslag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntakeFilterType extends AbstractType implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected $wachtlijstLocaties = array();
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

        if (in_array('locatie', $options['enabled_filters'])) {
//            $builder->add('locatie', LocatieSelectType::class, [
//                'required' => false,
//            ]);
            $builder->add('locatie', EntityType::class, [
                'required' => false,
                'class'=>Locatie::class,
                'query_builder' => function (EntityRepository $repository) {
                    $builder = $repository->createQueryBuilder('locatie')
                        ->select("locatie")
                        ->where('locatie.naam IN (:wachtlijstLocaties)')
                        ->orderBy('locatie.naam')
                        ->setParameter("wachtlijstLocaties",$this->wachtlijstLocaties)
                    ;
                    $sql = $builder->getQuery()->getSQL();
                    return $builder;
                    ;
                },
            ]);
        }

        if (in_array('datum', $options['enabled_filters'])) {
            $builder->add('datum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }
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
            'data_class' => IntakeFilter::class,
            'enabled_filters' => [
                'klant' => ['id', 'voornaam', 'achternaam', 'geslacht'],
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
