<?php

namespace InloopBundle\Form;

use ClipBundle\Entity\Behandelaar;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use InloopBundle\Entity\Locatie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocatieTypeSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return EntityType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => \InloopBundle\Entity\LocatieType::class,
            'placeholder' => '',
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('locatie_type')
                        ->orderBy('locatie_type.naam')
                    ;
                    return $builder;
                };
            },
        ]);
    }
}
