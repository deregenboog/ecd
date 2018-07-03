<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RapportageType extends AbstractType
{
    private $choices = [];

    public function __construct(array $options = [])
    {
        foreach ($options as $category => $reports) {
            if (is_array($reports)) {
                foreach ($reports as $id => $report) {
                    $this->choices[$category][$report->getTitle()] = $id;
                }
            } else {
                $id = $category;
                $report = $reports;
                $this->choices[$report->getTitle()] = $id;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startdatum', AppDateType::class, [
                'required' => true,
                'data' => new \DateTime('first day of January this year'),
            ])
            ->add('einddatum', AppDateType::class, [
                'required' => true,
                'data' => (new \DateTime('today')),
            ])
            ->add('rapport', ChoiceType::class, [
                'required' => true,
                'placeholder' => 'Selecteer een rapport',
                'choices' => $this->choices,
            ])
            ->add('show', SubmitType::class, [
                'label' => 'Rapport tonen',
            ])
            ->add('download', SubmitType::class, [
                'label' => 'Rapport downloaden',
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
        return BaseType::class;
    }
}
