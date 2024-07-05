<?php

namespace HsBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use HsBundle\Entity\Klus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlusCancelType extends AbstractType
{
    use MedewerkerTypeTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('annuleringsdatum', AppDateType::class, [
                'required' => true,
                'data' => new \DateTime('today'),
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Klus::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
