<?php

namespace IzBundle\Form;

use AppBundle\Filter\FilterInterface;
use AppBundle\Form\BaseType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\IzVrijwilliger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IzVrijwilligerSelectType extends AbstractType
{
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IzVrijwilliger::class,
            'filter' => null,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
