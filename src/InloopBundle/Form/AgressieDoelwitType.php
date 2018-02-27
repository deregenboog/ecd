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
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $i = $options['index'];

        $builder
            ->add("typeDoelwitAgressie$i", ChoiceType::class, [
                'label' => "Functie",
                'required' => false,
                'expanded' => false,
                'choices' => [
                    'medewerker' => 1,
                    'stagiair' => 2,
                    'vrijwilliger' => 3,
                ],
            ])
            ->add("doelwitAgressie$i", null, [
                'label' => "Naam",
                'required' => false,
            ])
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
            'data_class' => Schorsing::class,
            'inherit_data' => true,
            'index' => 0,
        ]);
    }
}
