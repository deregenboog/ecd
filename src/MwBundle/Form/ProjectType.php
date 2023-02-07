<?php

namespace MwBundle\Form;

use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use MwBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('medewerkers', MedewerkerType::class, [
                'required' => true,
                'preset' => false,
                'multiple' => true,
            ])
            ->add('wachtlijst')
            ->add('actief')
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
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
