<?php

namespace AppBundle\Form;

use AppBundle\Entity\Werkgebied;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WerkgebiedSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Stadsdeel',
            'placeholder' => '',
            'class' => Werkgebied::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('werkgebied')
                    ->orderBy('werkgebied.naam');
            },
        ]);
    }

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
