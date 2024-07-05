<?php

namespace MwBundle\Form;

use Doctrine\ORM\EntityRepository;
use MwBundle\Entity\BinnenViaOptieKlant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BinnenViaOptieKlantSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => BinnenViaOptieKlant::class,
            'label' => 'Binnen via',
            'placeholder' => '',
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('binnenViaOptiesKlant')
                    ->orderBy('binnenViaOptiesKlant.naam');
            },
        ]);
    }

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
