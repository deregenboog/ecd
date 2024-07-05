<?php

namespace TwBundle\Form;

use AppBundle\Filter\FilterInterface;
use AppBundle\Form\BaseType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwBundle\Entity\Klant;

class HuurderSelectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('klant', null, [
                'required' => false,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('klant')
                        ->leftJoin(Klant::class, 'klant', 'WITH', 'klant.appKlant = klant')
                        ->andWhere('klant.id IS NULL')
                        ->orderBy('appKlant.achternaam, appKlant.tussenvoegsel, appKlant.voornaam')
                    ;

                    if ($options['filter'] instanceof FilterInterface) {
                        $options['filter']->applyTo($builder);
                    }

                    return $builder;
                },
            ])
            ->add('submit', SubmitType::class, ['label' => 'Verder'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Klant::class,
            'filter' => null,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
