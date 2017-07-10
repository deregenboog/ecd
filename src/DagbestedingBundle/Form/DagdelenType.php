<?php

namespace DagbestedingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class DagdelenType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('submit1', SubmitType::class, ['label' => 'Opslaan'])
            ->add('dagdelen', CollectionType::class, [
                'entry_type' => CollectionType::class,
                'entry_options' => [
                    'entry_type' => DagdeelType::class,
                ],
            ])
            ->add('submit2', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DagdelenModel::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }
}
