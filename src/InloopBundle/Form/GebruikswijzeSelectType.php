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
                    ->where('gebruikswijze.datumVan <= NOW()')
                    ->andWhere('gebruikswijze.datumTot IS NULL OR gebruikswijze.datumTot > NOW()')
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
