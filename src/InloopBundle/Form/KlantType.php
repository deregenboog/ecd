<?php

namespace InloopBundle\Form;

use AppBundle\Entity\Klant;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\LandSelectType;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\NationaliteitSelectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('voornaam')
        ->add('tussenvoegsel')
        ->add('achternaam')
        ->add('roepnaam')
        ->add('geslacht')
        ->add('geboortedatum', AppDateType::class)
        ->add('land', LandSelectType::class)
        ->add('doorverwijzenNaarAmoc', CheckboxType::class, [
            'label' => 'Ik wil deze persoon wegens taalproblemen doorverwijzen naar AMOC',
            'required' => false,
        ])
        ->add('nationaliteit', NationaliteitSelectType::class)
        ->add('bsn')
        ->add('laatsteTbcControle', AppDateType::class, [
            'required' => false,
        ])
        ->add('medewerker', MedewerkerType::class)
        ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Klant::class,
        ]);
    }
}
