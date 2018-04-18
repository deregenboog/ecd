<?php

namespace AppBundle\Form;

use AppBundle\Entity\GgwGebied;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostcodegebiedSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Postcodegebied',
            'required' => false,
            'class' => GgwGebied::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('postcodegebied')
                    ->orderBy('postcodegebied.naam');
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
