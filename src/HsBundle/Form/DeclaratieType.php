<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\AppDateType;
use HsBundle\Entity\Declaratie;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\MedewerkerType;

class DeclaratieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medewerker', MedewerkerType::class)
            ->add('datum', AppDateType::class, ['data' => new \DateTime('today')])
            ->add('declaratieCategorie', null, [
                'label' => 'Declaratiecategorie',
                'placeholder' => 'Selecteer een declaratiecategorie',
            ])
            ->add('info')
            ->add('bedrag', MoneyType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Declaratie::class,
        ]);
    }
}
