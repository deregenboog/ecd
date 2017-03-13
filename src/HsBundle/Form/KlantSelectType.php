<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use HsBundle\Entity\Klant;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use AppBundle\Filter\FilterInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class KlantSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('form', HiddenType::class, ['mapped' => false, 'data' => self::class])
            ->add('klant', null, [
                'required' => false,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('appKlant');

                    if ($options['filter'] instanceof FilterInterface) {
                        $options['filter']->alias = 'appKlant';
                        $options['filter']->applyTo($builder);
                    }

                    $builder
                        ->leftJoin(Klant::class, 'klant', 'WITH', 'klant.klant = appKlant')
                        ->andWhere('klant.id IS NULL')
                    ;

                    return $builder;
                },
            ])
            ->add('next', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Klant::class,
            'filter' => null,
        ]);
    }
}
