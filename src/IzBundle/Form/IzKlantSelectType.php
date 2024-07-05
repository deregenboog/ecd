<?php

namespace IzBundle\Form;

use AppBundle\Filter\FilterInterface;
use AppBundle\Form\BaseType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\IzKlant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IzKlantSelectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('klant', null, [
                'required' => false,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('klant')
                        ->leftJoin(IzKlant::class, 'izKlant', 'WITH', 'izKlant.klant = klant')
                        ->andWhere('izKlant.id IS NULL')
                        ->orderBy('klant.achternaam, klant.tussenvoegsel, klant.voornaam')
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
            'data_class' => IzKlant::class,
            'filter' => null,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
