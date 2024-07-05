<?php

namespace MwBundle\Form;

use AppBundle\Form\AppDateType;
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $ris = $builder->create('risForm', ContainerType::class, ['label' => 'RIS'])
            ->add('risDatumTot', AppDateType::class, ['required' => false])
        ;

        //        $trajectbegeleider = $builder->create('trajectbegeleiderForm', ContainerType::class, ['label' => 'Trajectbegeleider/maatschappelijk werker'])
        //            ->add('trajectbegeleider', MedewerkerType::class, ['required' => false])
        //        ;

        $trajecthouder = $builder->create('trajecthouderForm', ContainerType::class, ['label' => 'Hulpverlening extern'])
            ->add('trajecthouderExternOrganisatie', null, ['label' => 'Organisatie', 'required' => false])
            ->add('trajecthouderExternNaam', null, ['label' => 'Naam', 'required' => false])
            ->add('trajecthouderExternEmail', null, ['label' => 'E-mail', 'required' => false])
            ->add('trajecthouderExternTelefoon', null, ['label' => 'Telefoonnummer', 'required' => false])
        ;

        $instantie = $builder->create('instantieForm', ContainerType::class, ['label' => 'Uitkerende instantie'])
            ->add('instantie', ChoiceType::class, [
                'placeholder' => '',
                'choices' => array_flip(Info::INSTANTIES),
                'required' => false,
            ])
            ->add('registratienummer', null, ['required' => false]);

        $budgettering = $builder->create('budgetteringForm', ContainerType::class, ['label' => 'Budgettering'])
            ->add('budgettering', null, ['required' => false])
            ->add('contactpersoon', null, ['required' => false]);

        $klantmanager = $builder->create('klantmanagerForm', ContainerType::class, ['label' => 'Klantmanager'])
            ->add('klantmanagerNaam', null, ['label' => 'Naam', 'required' => false])
            ->add('klantmanagerEmail', null, ['label' => 'E-mail', 'required' => false])
            ->add('klantmanagerTelefoon', null, ['label' => 'Telefoonnummer', 'required' => false]);

        $woningnet = $builder->create('woningnetForm', ContainerType::class, ['label' => 'Woningnet'])
            ->add('inschrijfnummer', null, ['required' => false])
            ->add('wachtwoord', null, ['required' => false]);
        $builder->add($woningnet);

        $builder
             ->add('isGezin')
            ->add($ris)
//            ->add($trajectbegeleider)
            ->add($trajecthouder)

            ->add('overigeContactpersonenExtern', AppTextareaType::class, [
                'attr' => ['rows' => 5],
                'required' => false,
            ])
            ->add($instantie)
            ->add($budgettering)
            ->add($klantmanager)
            ->add('sociaalNetwerk', AppTextareaType::class, [
                'attr' => ['rows' => 5],
                'required' => false,
            ])
            ->add('bankrekeningnummer', null, ['required' => false])
            ->add('polisnummerZiektekostenverzekering', null, ['required' => false])
            ->add($woningnet)
            ->add('telefoonnummer', null, ['required' => false])
            ->add('contact', AppTextareaType::class, [
                'attr' => ['rows' => 5],
                'required' => false,
            ])
            ->add('adres', AppTextareaType::class, [
                'attr' => ['rows' => 5],
                'required' => false,
            ])
            ->add('overigen', AppTextareaType::class, [
                'attr' => ['rows' => 5],
                'required' => false,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Info::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
