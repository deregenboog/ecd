<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use IzBundle\Entity\IzProject;
use AppBundle\Form\StadsdeelFilterType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use IzBundle\Filter\IzDeelnemerSelectie;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\FilterType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use IzBundle\Entity\Doelstelling;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use IzBundle\Filter\DoelstellingFilter;
use AppBundle\Entity\Werkgebied;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

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
            ->add('project', IzProjectType::class, [
                'required' => false,
            ])
            ->add('centraleStad', CheckboxType::class, [
                'required' => false,
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
