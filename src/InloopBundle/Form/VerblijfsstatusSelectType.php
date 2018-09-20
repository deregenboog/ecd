<?php

namespace InloopBundle\Form;

use AppBundle\Entity\Verblijfsstatus;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VerblijfsstatusSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Verblijfsstatus::class,
            'placeholder' => '',
            'required' => false,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('verblijfsstatus')
                    ->where('verblijfsstatus.datumVan <= NOW()')
                    ->andWhere('verblijfsstatus.datumTot IS NULL OR verblijfsstatus.datumTot > NOW()')
                    ->orderBy('verblijfsstatus.id')
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
