<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use AppBundle\Filter\FilterInterface;
use HsBundle\Entity\HsVrijwilliger;
use HsBundle\Entity\Vrijwilliger;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;

class VrijwilligerSelectType extends AbstractType
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
                    $builder = $repository->createQueryBuilder('appVrijwilliger');

                    if ($options['filter'] instanceof FilterInterface) {
                        $options['filter']->alias = 'appVrijwilliger';
                        $options['filter']->applyTo($builder);
                    }

                    $builder
                        ->leftJoin(Vrijwilliger::class, 'vrijwilliger', 'WITH', 'vrijwilliger.vrijwilliger = appVrijwilliger')
                        ->andWhere('vrijwilliger.id IS NULL')
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
            'data_class' => Vrijwilliger::class,
            'filter' => null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }
}
