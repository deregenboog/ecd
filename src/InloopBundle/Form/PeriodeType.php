<?php

namespace InloopBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use InloopBundle\Entity\Periode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PeriodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('datumVan', AppDateType::class, [
                'required' => true,
            ])
            ->add('datumTot', AppDateType::class, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Periode::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
