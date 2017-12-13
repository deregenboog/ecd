<?php

namespace ClipBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use AppBundle\Form\FilterType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateRangeType;
use ClipBundle\Filter\VraagFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use ClipBundle\Entity\Vraagsoort;
use ClipBundle\Entity\Behandelaar;
use Doctrine\ORM\EntityRepository;

class VraagFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, []);
        }

        if (array_key_exists('client', $options['enabled_filters'])) {
            $builder->add('client', ClientFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['client'],
            ]);
        }

        if (in_array('soort', $options['enabled_filters'])) {
            $builder->add('soort', EntityType::class, [
                'class' => Vraagsoort::class,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('soort')
                        ->where('soort.actief = true')
                        ->orderBy('soort.naam')
                    ;
                },
                'required' => false,
            ]);
        }

        if (in_array('behandelaar', $options['enabled_filters'])) {
            $builder->add('behandelaar', BehandelaarFilterType::class);
        }

        if (in_array('startdatum', $options['enabled_filters'])) {
            $builder->add('startdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('afsluitdatum', $options['enabled_filters'])) {
            $builder->add('afsluitdatum', AppDateRangeType::class, [
                'required' => false,
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
            'data_class' => VraagFilter::class,
            'enabled_filters' => [
                'id',
                'startdatum',
                'afsluitdatum',
                'soort',
                'behandelaar',
                'client' => ['klant' => ['naam']],
            ],
        ]);
    }
}
