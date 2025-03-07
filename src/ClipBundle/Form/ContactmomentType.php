<?php

namespace ClipBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use ClipBundle\Entity\Contactmoment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactmomentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('behandelaar', BehandelaarSelectType::class, [
                'medewerker' => $options['medewerker'],
                'current' => $options['data'] ? $options['data']->getBehandelaar() : null,
            ])
            ->add('datum', AppDateType::class)
            ->add('opmerking', AppTextareaType::class, ['label' => 'Verslag'])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contactmoment::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
