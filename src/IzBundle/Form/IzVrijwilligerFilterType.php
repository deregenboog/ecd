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
use IzBundle\Filter\IzVrijwilligerFilter;
use IzBundle\Entity\IzHulpaanbod;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Form\VrijwilligerFilterType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use AppBundle\Form\AppDateRangeType;

class IzVrijwilligerFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('afsluitDatum', $options['enabled_filters'])) {
            $builder->add('afsluitDatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('openDossiers', $options['enabled_filters'])) {
            $builder->add('openDossiers', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen open dossiers',
            ]);
        }

        if (key_exists('vrijwilliger', $options['enabled_filters'])) {
            $builder->add('vrijwilliger', VrijwilligerFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['vrijwilliger'],
            ]);
        }

        if (in_array('izProject', $options['enabled_filters'])) {
            $builder->add('izProject', EntityType::class, [
                'required' => false,
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
                        ->innerJoin(IzHulpaanbod::class, 'izHulpaanbod', 'WITH', 'izHulpaanbod.medewerker = medewerker')
                        ->orderBy('medewerker.voornaam', 'ASC');
                },
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
            'data_class' => IzVrijwilligerFilter::class,
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
