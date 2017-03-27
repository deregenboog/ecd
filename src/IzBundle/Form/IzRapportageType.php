<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class IzRapportageType extends AbstractType
{
    private $choices = [];

    public function __construct(array $options)
    {
        foreach ($options as $category => $reports) {
            foreach ($reports as $id => $report) {
                $this->choices[$category][$report->getTitle()] = $id;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startdatum', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'data' => new \DateTime('first day of January this year'),
                'attr' => ['placeholder' => 'dd-mm-jjjj'],
            ])
            ->add('einddatum', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'data' => (new \DateTime('today')),
                'attr' => ['placeholder' => 'dd-mm-jjjj'],
            ])
            ->add('rapport', ChoiceType::class, [
                'required' => true,
                'placeholder' => 'Selecteer een rapport',
                'choices' => $this->choices,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET',
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
