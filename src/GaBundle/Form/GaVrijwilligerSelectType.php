<?php

namespace GaBundle\Form;

use AppBundle\Form\BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Filter\VrijwilligerFilter;
use AppBundle\Form\FilterType;
use GaBundle\Entity\GaVrijwilligerIntake;

class GaVrijwilligerSelectType extends BaseType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vrijwilliger', null, [
                'required' => true,
                'placeholder' => 'Selecteer een item',
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('vrijwilliger')
                        ->leftJoin(GaVrijwilligerIntake::class, 'gaVrijwilligerIntake', 'WITH', 'gaVrijwilligerIntake.vrijwilliger = vrijwilliger')
                        ->andWhere('gaVrijwilligerIntake.id IS NULL')
                    ;

                    if ($options['filter'] instanceof VrijwilligerFilter) {
                        $options['filter']->applyTo($builder);
                    }

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
            'data_class' => GaVrijwilligerIntake::class,
            'filter' => null,
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
