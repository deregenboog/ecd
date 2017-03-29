<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\IzVrijwilliger;

class IzVrijwilligerSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vrijwilliger', null, [
                'required' => false,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('vrijwilliger')
                        ->leftJoin(IzVrijwilliger::class, 'izVrijwilliger', 'WITH', 'izVrijwilliger.vrijwilliger = vrijwilliger')
                        ->andWhere('izVrijwilliger.id IS NULL')
                        ->orderBy('vrijwilliger.achternaam, vrijwilliger.tussenvoegsel, vrijwilliger.voornaam')
                    ;

                    if ($options['filter'] instanceof FilterInterface) {
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
            'data_class' => IzVrijwilliger::class,
            'filter' => null,
        ]);
    }
}
