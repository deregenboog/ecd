<?php

namespace TwBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwBundle\Entity\VormVanOvereenkomst;

class VormVanOvereenkomstSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => VormVanOvereenkomst::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('vormvanovereenkomst')
                    ->orderBy('vormvanovereenkomst.id');
            },
            'label' => 'Vorm van overeenkomst',
//            'preferred_choices' => function (Land $land) {
//                return in_array($land->getNaam(), ['Nederland', 'Onbekend']);
//            },
        ]);
    }

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
