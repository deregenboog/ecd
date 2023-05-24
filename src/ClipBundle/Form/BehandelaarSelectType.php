<?php

namespace ClipBundle\Form;

use ClipBundle\Entity\Behandelaar;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BehandelaarSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            if (!$event->getData() && $options['medewerker']) {
                $em = $event->getForm()->getConfig()->getOption('em');
                $behandelaar = $em->getRepository(Behandelaar::class)->findOneByMedewerker($options['medewerker']);
                $event->setData($behandelaar);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'current' => null,
            'medewerker' => null,
            'placeholder' => '',
            'class' => Behandelaar::class,
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('behandelaar')
                        ->where('behandelaar.actief = true OR behandelaar = :current')
                        ->orWhere('behandelaar.medewerker = :medewerker')
                        ->setParameter('current', $options['current'])
                        ->setParameter('medewerker', $options['medewerker'])
                        ->orderBy('behandelaar.displayName')
                    ;
                };
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
