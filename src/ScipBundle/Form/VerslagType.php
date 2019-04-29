<?php

namespace ScipBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use ScipBundle\Entity\Verslag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VerslagType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tekst', AppTextareaType::class, [
                'required' => false,
            ])
            ->add('medewerker', MedewerkerType::class, [
                'required' => true,
            ])
            ->add('datum', AppDateType::class, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Verslag::class,
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
