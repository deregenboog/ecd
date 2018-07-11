<?php

namespace GaBundle\Form;

use Doctrine\ORM\EntityRepository;
use GaBundle\Entity\Vrijwilligerdossier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VrijwilligerdossierSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Vrijwilliger',
            'placeholder' => '',
            'required' => true,
            'class' => Vrijwilligerdossier::class,
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('dossier')
                        ->innerJoin('dossier.vrijwilliger', 'vrijwilliger')
                        ->where('dossier NOT IN (:exclude)')
                        ->setParameter('exclude', $options['exclude'])
                        ->orderBy('vrijwilliger.achternaam')
                    ;
                };
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
