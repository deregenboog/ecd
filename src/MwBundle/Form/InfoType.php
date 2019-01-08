<?php

namespace MwBundle\Form;

use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\ContainerType;
use AppBundle\Form\MedewerkerType;
use MwBundle\Entity\Info;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $casemanager = $builder->create('casemanagerForm', ContainerType::class, ['label' => 'Casemanager DRG'])
            ->add('casemanager', MedewerkerType::class)
            ->add('casemanagerEmail', null, ['label' => 'E-mail'])
            ->add('casemanagerTelefoon', null, ['label' => 'Telefoonnummer'])
        ;

        $trajectbegeleider = $builder->create('trajectbegeleiderForm', ContainerType::class, ['label' => 'Trajectbegeleider'])
            ->add('trajectbegeleider', MedewerkerType::class)
            ->add('trajectbegeleiderEmail', null, ['label' => 'E-mail'])
            ->add('trajectbegeleiderTelefoon', null, ['label' => 'Telefoonnummer'])
        ;

        $trajecthouder = $builder->create('trajecthouderForm', ContainerType::class, ['label' => 'Trajecthouder extern'])
            ->add('trajecthouderExternOrganisatie', null, ['label' => 'Organisatie'])
            ->add('trajecthouderExternNaam', null, ['label' => 'Naam'])
            ->add('trajecthouderExternEmail', null, ['label' => 'E-mail'])
            ->add('trajecthouderExternTelefoon', null, ['label' => 'Telefoonnummer'])
        ;

        $instantie = $builder->create('instantieForm', ContainerType::class, ['label' => 'Uitkerende instantie'])
            ->add('instantie', ChoiceType::class, [
                'placeholder' => '',
                'choices' => array_flip(Info::INSTANTIES),
            ])
            ->add('registratienummer');

        $budgettering = $builder->create('budgetteringForm', ContainerType::class, ['label' => 'Budgettering'])
            ->add('budgettering')
            ->add('contactpersoon');

        $klantmanager = $builder->create('klantmanagerForm', ContainerType::class, ['label' => 'Klantmanager'])
            ->add('klantmanagerNaam', null, ['label' => 'Naam'])
            ->add('klantmanagerEmail', null, ['label' => 'E-mail'])
            ->add('klantmanagerTelefoon', null, ['label' => 'Telefoonnummer']);

        $woningnet = $builder->create('woningnetForm', ContainerType::class, ['label' => 'Woningnet'])
            ->add('inschrijfnummer')
            ->add('wachtwoord');
        $builder->add($woningnet);

        $builder
            ->add($casemanager)
            ->add($trajectbegeleider)
            ->add($trajecthouder)
            ->add('overigeContactpersonenExtern', AppTextareaType::class, [
                'attr' => ['rows' => 5],
            ])
            ->add($instantie)
            ->add($budgettering)
            ->add($klantmanager)
            ->add('sociaalNetwerk', AppTextareaType::class, [
                'attr' => ['rows' => 5],
            ])
            ->add('bankrekeningnummer')
            ->add('polisnummerZiektekostenverzekering')
            ->add($woningnet)
            ->add('telefoonnummer')
            ->add('contact', AppTextareaType::class, [
                'attr' => ['rows' => 5],
            ])
            ->add('adres', AppTextareaType::class, [
                'attr' => ['rows' => 5],
            ])
            ->add('overigen', AppTextareaType::class, [
                'attr' => ['rows' => 5],
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
            'data_class' => Info::class,
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
