<?php

namespace InloopBundle\Form;

use AppBundle\Form\BaseType;
use InloopBundle\Entity\Infobaliedoelgroep;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfobaliedoelgroepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Infobaliedoelgroep::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
