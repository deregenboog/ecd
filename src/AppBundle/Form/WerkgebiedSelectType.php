<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\KlantType;
use AppBundle\Form\BaseType;
use ClipBundle\Entity\Client;
use Doctrine\ORM\EntityRepository;
use ClipBundle\Entity\Behandelaar;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Geslacht;
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
            'query_builder' => function(EntityRepository $repository) {
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
