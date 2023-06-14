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

        $builder->add('startdatum', AppDateType::class, [
                    'required' => true,
//                    'data' => new \DateTime('first day of January this year'),
            ])

            ->add('einddatum', AppDateType::class, [
                'required' => true,
//                'data' => (AppDateType::getLastFullQuarterEnd()),
            ])

            ->add('repository', null, [
                'required' => false,
                'attr' => ['placeholder' => 'Module'],
            ])
            ->add('jaar', ChoiceType::class, [
                'choices' => array_combine($range, $range),
                'required' => false,
            ])
            ->add('kostenplaats', null, [
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
            'enabled_filters' => [
                'filter',
                'download',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return FilterType::class;
    }
}
