<?php

namespace HsBundle\Form;

use AppBundle\Form\BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\AppDateType;
use HsBundle\Entity\HsDeclaratie;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class HsDeclaratieType extends BaseType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medewerker')
            ->add('datum', AppDateType::class, ['data' => new \DateTime('today')])
            ->add('hsDeclaratieCategorie', null, [
                'label' => 'Declaratiecategorie',
                'placeholder' => 'Selecteer een declaratiecategorie',
            ])
            ->add('info')
            ->add('bedrag', MoneyType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HsDeclaratie::class,
        ]);
    }
}
