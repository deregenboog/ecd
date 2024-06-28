<?php

namespace IzBundle\Form;

use AppBundle\Form\BaseType;
use IzBundle\Entity\Afsluiting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AfsluitingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('doelgroepen', ChoiceType::class, [
                'choices' => array_flip(Afsluiting::DOELGROEPEN_LABELS),
                'multiple' => true,
                'by_reference' => false,
            ])
            ->add('actief')
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Afsluiting::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
