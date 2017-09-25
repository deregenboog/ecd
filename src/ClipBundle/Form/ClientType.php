<?php

namespace ClipBundle\Form;

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

class ClientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('behandelaar', EntityType::class, [
                'placeholder' => '',
                'label' => 'Medewerker',
                'class' => Behandelaar::class,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $current = $options['data'] ? $options['data']->getBehandelaar() : null;

                    return $repository->createQueryBuilder('behandelaar')
                        ->where('behandelaar.actief = true OR behandelaar = :current')
                        ->setParameter('current', $current)
                        ->orderBy('behandelaar.displayName')
                    ;
                },
            ])
            ->add('aanmelddatum', AppDateType::class)
            ->add('viacategorie', null, [
                'label' => 'Hoe bekend',
                'placeholder' => '',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) use ($options) {
                    $current = $options['data'] ? $options['data']->getViacategorie() : null;

                    return $repository->createQueryBuilder('viacategorie')
                        ->where('viacategorie.actief = true OR viacategorie = :current')
                        ->setParameter('current', $current)
                    ;
                },
            ])
        ;

        if ($options['data']->getKlant()->getId()) {
            $builder->add('behandelaar', EntityType::class, [
                'placeholder' => '',
                'label' => 'Medewerker',
                'class' => Behandelaar::class,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $current = $options['data'] ? $options['data']->getBehandelaar() : null;

                    return $repository->createQueryBuilder('behandelaar')
                        ->where('behandelaar.actief = true OR behandelaar = :current')
                        ->setParameter('current', $current)
                        ->orderBy('behandelaar.displayName')
                    ;
                },
            ]);
        } else {
            $builder->add('klant', KlantType::class);
        }

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }
}
