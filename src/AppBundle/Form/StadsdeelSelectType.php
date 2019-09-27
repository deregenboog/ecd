<?php

namespace AppBundle\Form;

use AppBundle\Entity\Werkgebied;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StadsdeelSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Stadsdeel',
            'required' => false,
            'class' => Werkgebied::class,
            'placeholder' => '',
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
