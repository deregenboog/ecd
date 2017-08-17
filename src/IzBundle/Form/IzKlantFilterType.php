<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\FilterType;
use AppBundle\Entity\Medewerker;
use IzBundle\Entity\IzProject;
use IzBundle\Filter\IzKlantFilter;
use IzBundle\Entity\IzHulpvraag;
use AppBundle\Entity\Klant;
use AppBundle\Form\KlantFilterType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use AppBundle\Form\AppDateRangeType;

class IzKlantFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('afsluitDatum', $options['enabled_filters'])) {
            $builder->add('afsluitDatum', AppDateRangeType::class, [
                'required' => false,
                'label' => false,
            ]);
        }

        if (in_array('openDossiers', $options['enabled_filters'])) {
            $builder->add('openDossiers', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen open dossiers',
            ]);
        }

        if (key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }

        if (in_array('izProject', $options['enabled_filters'])) {
            $builder->add('izProject', EntityType::class, [
                'required' => false,
                'label' => 'Project',
                'class' => IzProject::class,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('izProject')
                        ->where('izProject.einddatum IS NULL OR izProject.einddatum >= :now')
                        ->orderBy('izProject.naam', 'ASC')
                        ->setParameter('now', new \DateTime());
                },
            ]);
        }

        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(IzHulpvraag::class, 'izHulpvraag', 'WITH', 'izHulpvraag.medewerker = medewerker')
                        ->where('medewerker.actief = :true')
                        ->setParameter('true', true)
                        ->orderBy('medewerker.voornaam', 'ASC');
                },
            ]);
        }

        if (in_array('zonderActieveHulpvraag', $options['enabled_filters'])) {
            $builder->add('zonderActieveHulpvraag', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen dossiers zonder actieve hulpvraag',
            ]);
        }

        if (in_array('zonderActieveKoppeling', $options['enabled_filters'])) {
            $builder->add('zonderActieveKoppeling', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen dossiers zonder actieve koppeling',
            ]);
        }

        $builder
            ->add('filter', SubmitType::class, ['label' => 'Filteren'])
            ->add('download', SubmitType::class, ['label' => 'Downloaden'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IzKlantFilter::class,
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
