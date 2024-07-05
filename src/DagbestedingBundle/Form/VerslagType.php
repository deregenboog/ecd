<?php

namespace DagbestedingBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use DagbestedingBundle\Entity\Verslag;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VerslagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medewerker', \AppBundle\Form\MedewerkerType::class)
            ->add('datum', AppDateType::class)
            ->add('opmerking', CKEditorType::class)
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Verslag::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
