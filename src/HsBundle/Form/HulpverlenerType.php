<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\BaseType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use HsBundle\Entity\Hulpverlener;

class HulpverlenerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam', null, ['required' => false])
            ->add('organisatie', null, ['required' => false])
            ->add('telefoon', null, ['required' => false])
            ->add('email', EmailType::class, ['required' => false])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hulpverlener::class,
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
