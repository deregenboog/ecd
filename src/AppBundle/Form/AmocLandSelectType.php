<?php

namespace AppBundle\Form;

use AppBundle\Entity\AmocLand;
use AppBundle\Entity\Land;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AmocLandSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Landen',
            'class' => Land::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('land')
                    ->innerJoin(AmocLand::class, 'amocLand', 'WITH', 'amocLand.land = land')
                    ->orderBy('land.land');
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
