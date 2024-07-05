<?php

namespace InloopBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use InloopBundle\Entity\Locatie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocatieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('locatieTypes', LocatieTypeSelectType::class, [
                'multiple' => true,
            ])
            ->add('gebruikersruimte', CheckboxType::class, [
                'required' => false,
            ])
            ->add('datumVan', AppDateType::class)
            ->add('datumTot', AppDateType::class, ['required' => false])
            ->add('submit', SubmitType::class)
        ;
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Locatie::class,
        ]);
    }
}
