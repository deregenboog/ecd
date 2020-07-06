<?php

namespace OdpBundle\Form;

use OdpBundle\Entity\VormVanOvereenkomst;
use Doctrine\ORM\EntityRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VormVanOvereenkomstType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => VormVanOvereenkomst::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('vormvanovereenkomst')
                    ->orderBy('vormvanovereenkomst.id');
            },
            'label' => 'Vorm van overeenkomst'
//            'preferred_choices' => function (Land $land) {
//                return in_array($land->getNaam(), ['Nederland', 'Onbekend']);
//            },
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
