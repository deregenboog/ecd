<?php

namespace InloopBundle\Form;

use Doctrine\ORM\EntityRepository;
use InloopBundle\Entity\Gebruikswijze;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GebruikswijzeSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Gebruikswijze::class,
            'multiple' => true,
            'expanded' => true,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('gebruikswijze')
                    ->where('gebruikswijze.datumVan <= DATE(CURRENT_TIMESTAMP())')
                    ->andWhere('gebruikswijze.datumTot IS NULL OR gebruikswijze.datumTot > DATE(CURRENT_TIMESTAMP())')
                    ->orderBy('gebruikswijze.naam')
                ;
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityType::class;
    }
}
