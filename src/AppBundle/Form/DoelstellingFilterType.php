<?php

namespace AppBundle\Form;

use AppBundle\Entity\Werkgebied;
use AppBundle\Form\FilterType;
use AppBundle\Form\ProjectSelectType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Doelstelling;
use AppBundle\Filter\DoelstellingFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DoelstellingFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $range = range(2017, (new \DateTime('next year'))->format('Y'));

        $builder
            ->add('repository', null, [
                'required' => false,
                'attr' => ['placeholder' => 'Module'],
            ])
            ->add('jaar', ChoiceType::class, [
                'choices' => array_combine($range, $range),
                'required' => false,
            ])
            ->add('kpi', ChoiceType::class, [
                'required' => false,
            ])
            ->add('categorie', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Stadsdeel' => '-',
                    'Centrale stad' => Doelstelling::CATEGORIE_CENTRALE_STAD,
                    'Fondsen' => Doelstelling::CATEGORIE_FONDSEN,
                ],
            ])
            ->add('stadsdeel', EntityType::class, [
                'class' => Werkgebied::class,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('s')->orderBy('s.naam', 'ASC');
                },
                'required' => false,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DoelstellingFilter::class,
            'enabled_filters' => ['filter'],
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
