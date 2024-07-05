<?php

namespace AppBundle\Form;

use AppBundle\Entity\Werkgebied;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StadsdeelSelectType extends AbstractType
{
    public static $showOnlyZichtbaar = 1;

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Stadsdeel',
            'required' => false,
            'class' => Werkgebied::class,
            'placeholder' => '',
            'query_builder' => function (EntityRepository $repository) {
                $retVal = $repository->createQueryBuilder('werkgebied')
                    ->andWhere('werkgebied.zichtbaar >= :zichtbaar')
                    ->orderBy('werkgebied.naam')
                    ->setParameter('zichtbaar', self::$showOnlyZichtbaar)
                ;

                return $retVal;
            },
        ]);
    }

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
