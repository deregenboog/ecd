<?php

namespace HsBundle\Form;

use AppBundle\Form\BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use AppBundle\Filter\FilterInterface;
use HsBundle\Entity\HsVrijwilliger;

class HsVrijwilligerSelectType extends BaseType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('form', HiddenType::class, ['mapped' => false, 'data' => self::class])
            ->add('vrijwilliger', null, [
                'required' => false,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('vrijwilliger');

                    if ($options['filter'] instanceof FilterInterface) {
                        $options['filter']->applyTo($builder);
                    }

                    $builder->leftJoin(HsVrijwilliger::class, 'hsVrijwilliger', 'WITH', 'hsVrijwilliger.vrijwilliger = vrijwilliger')
                        ->andWhere('hsVrijwilliger.id IS NULL');

                    return $builder;
                },
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HsVrijwilliger::class,
            'filter' => null,
        ]);
    }
}
