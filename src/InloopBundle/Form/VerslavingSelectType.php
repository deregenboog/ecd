<?php

namespace InloopBundle\Form;

use Doctrine\ORM\EntityRepository;
use InloopBundle\Entity\Verslaving;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VerslavingSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Verslaving::class,
            'required' => false,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('verslaving')
                    ->where('verslaving.datumVan <= DATE(CURRENT_TIMESTAMP())')
                    ->andWhere('verslaving.datumTot IS NULL OR verslaving.datumTot > DATE(CURRENT_TIMESTAMP())')
                    ->orderBy('verslaving.naam')
                ;
            },
        ]);
    }

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
