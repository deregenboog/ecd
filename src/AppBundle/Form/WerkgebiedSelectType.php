<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Werkgebied;

class WerkgebiedSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityType::class;
    }
}
