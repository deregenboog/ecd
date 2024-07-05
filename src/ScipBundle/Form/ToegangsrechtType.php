<?php

namespace ScipBundle\Form;

use AppBundle\Form\BaseType;
use ScipBundle\Entity\Toegangsrecht;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToegangsrechtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medewerker', MedewerkerSelectType::class, [
                'required' => true,
            ])
            ->add('projecten', ProjectSelectType::class, [
                'required' => true,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Toegangsrecht::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
