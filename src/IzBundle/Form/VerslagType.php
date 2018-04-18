<?php

namespace IzBundle\Form;

use AppBundle\Form\BaseType;
use AppBundle\Form\CKEditorType;
use AppBundle\Form\MedewerkerSelectType;
use IzBundle\Entity\Verslag;
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
            ->add('medewerker', MedewerkerSelectType::class)
            ->add('opmerking', CKEditorType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Verslag::class,
        ]);
    }

    public function getParent()
    {
        return BaseType::class;
    }
}
