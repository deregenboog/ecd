<?php

namespace ClipBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use ClipBundle\Entity\Client;
use ClipBundle\Entity\Behandelaar;
use AppBundle\Form\PersonType;
use AppBundle\Form\AddressType;
use AppBundle\Form\WerkgebiedSelectType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use AppBundle\Entity\Postcode;
use Doctrine\ORM\EntityManager;

class ClientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('person', PersonType::class, ['data_class' => Client::class])
            ->add('address', AddressType::class, ['data_class' => Client::class])
            ->add('werkgebied', WerkgebiedSelectType::class, [
                'required' => false,
            ])
            ->add('aanmelddatum', AppDateType::class)
            ->add('behandelaar', BehandelaarSelectType::class, [
                'medewerker' => $options['medewerker'],
                'current' => $options['data'] ? $options['data']->getBehandelaar() : null,
            ])
            ->add('etniciteit', EtniciteitSelectType::class, [
                'required' => false,
            ])
            ->add('viacategorie', ViacategorieSelectType::class, [
                'required' => false,
                'current' => $options['data'] ? $options['data']->getViacategorie() : null,
            ])
            ->add('submit', SubmitType::class)
        ;
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
