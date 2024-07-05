<?php

namespace ClipBundle\Form;

use AppBundle\Form\AddressType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\PersonType;
use AppBundle\Form\WerkgebiedSelectType;
use ClipBundle\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
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
            ->add('organisatie')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
