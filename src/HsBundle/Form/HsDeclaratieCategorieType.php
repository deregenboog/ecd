<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HsBundle\Entity\HsDeclaratieCategorie;

class HsDeclaratieCategorieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HsDeclaratieCategorie::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return \AppBundle\Form\BaseType::class;
    }
}
