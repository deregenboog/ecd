<?php

namespace OekraineBundle\Form;

use AppBundle\Entity\Opmerking;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use ScipBundle\Form\MedewerkerSelectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpmerkingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categorie', null, [
                'required' => true,
                'placeholder' => '',
            ])
            ->add('beschrijving', AppTextareaType::class, [
                'required' => true,
            ])
            ->add('medewerker', MedewerkerType::class, [
                'required' => true,
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
            'data_class' => Opmerking::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
