<?php

namespace DagbestedingBundle\Form;

use AppBundle\Form\BaseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DagdelenRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('submit_top', SubmitType::class, ['label' => 'Opslaan'])
            ->add('dagdelen_range', CollectionType::class, [
                'entry_type' => DagdelenType::class,
            ])
            ->add('submit_bottom', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DagdelenRangeModel::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
