<?php

namespace DagbestedingBundle\Form;

use AppBundle\Form\BaseType;
use DagbestedingBundle\Entity\Resultaatgebiedsoort;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultaatgebiedsoortType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('actief')
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Resultaatgebiedsoort::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
