<?php

namespace MwBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use AppBundle\Form\StadsdeelSelectType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Locatie;
use MwBundle\Filter\IntakeFilter;
use InloopBundle\Service\LocatieDao;
use InloopBundle\Service\LocatieDaoInterface;
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

    /**
     * IntakeFilterType constructor.
     * @param  $wachtlijstLocaties
     */
    protected $wachtlijstLocaties = array();


    public function __construct(LocatieDao $locatieDao)
    {
//        $this->wachtlijstLocaties = $locatieDao->getWachtlijstLocaties();
    }


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
//                'data'=>$this->wachtlijstLocaties,
                'query_builder' => function (EntityRepository $repository) {
                    $builder = $repository->createQueryBuilder('locatie')
                        ->select("locatie")
                        ->where('locatie.wachtlijst > 0')
                        ->orderBy('locatie.naam')
//                        ->setParameter("wachtlijstLocaties",$this->wachtlijstLocaties)
                    ;
                    $sql = $builder->getQuery()->getSQL();
                    return $builder;
//                    ;
                },
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
            'data_class' => IntakeFilter::class,
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
