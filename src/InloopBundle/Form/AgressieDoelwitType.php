<?php

namespace InloopBundle\Form;

use AppBundle\Form\BaseType;
use InloopBundle\Entity\Schorsing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgressieDoelwitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $i = $options['index'];

        $builder
            ->add("typeDoelwitAgressie$i", ChoiceType::class, [
                'label' => 'Functie',
                'required' => false,
                'expanded' => false,
                'choices' => array_flip(Schorsing::DOELWITTEN),
            ])
            ->add("doelwitAgressie$i", null, [
                'label' => 'Naam',
                'required' => false,
            ])
        ;
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Schorsing::class,
            'inherit_data' => true,
            'index' => 0,
        ]);
    }
}
