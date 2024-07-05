<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiveEntityType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('e')
                    ->where('e.actief = true')
                ;
            },
//            'preset' => true,
            'placeholder' => '',
            'current' => null,
            'required' => false,
        ]);

        parent::configureOptions($resolver);
    }

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
