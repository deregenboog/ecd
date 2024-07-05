<?php

namespace OekBundle\Form;

use AppBundle\Form\BaseType;
use OekBundle\Entity\Verwijzing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VerwijzingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('naam');
        $builder->add('actief');
        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Verwijzing::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
