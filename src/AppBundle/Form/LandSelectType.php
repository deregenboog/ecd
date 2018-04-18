<?php

namespace AppBundle\Form;

use AppBundle\Entity\Land;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LandSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            if (!$event->getData()) {
                $em = $event->getForm()->getConfig()->getOption('em');
                $defaultData = $em->getRepository(Land::class)->findOneBy(['afkorting2' => 'NL']);
                $event->setData($defaultData);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Land::class,
            'placeholder' => 'Selecteer een land',
            'label' => 'Geboorteland',
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('land')
                    ->orderBy('land.land');
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
