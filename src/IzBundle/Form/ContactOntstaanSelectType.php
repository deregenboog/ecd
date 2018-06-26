<?php

namespace IzBundle\Form;

use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\ContactOntstaan;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactOntstaanSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder' => '',
            'class' => ContactOntstaan::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('contactOnstaan')
                    ->where('contactOnstaan.actief = true')
                    ->orderBy('contactOnstaan.naam')
                ;
            },
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
