<?php

namespace IzBundle\Form;

use AppBundle\Entity\Werkgebied;
use AppBundle\Form\FilterType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Doelstelling;
use IzBundle\Filter\DoelstellingFilter;
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
            ->add('jaar', ChoiceType::class, [
                'choices' => array_combine($range, $range),
                'required' => false,
            ])
            ->add('project', ProjectSelectType::class, [
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
