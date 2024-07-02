<?php

namespace AppBundle\Form;

use AppBundle\Entity\Geslacht;
use InloopBundle\Form\LocatieSelectType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
        if (in_array('locatie', $options['enabled_filters'])) {
            $builder->add('locatie', LocatieSelectType::class, [
                'placeholder' => 'Alle locaties',
                'required' => false,
            ]);
        }

        if (in_array('startdatum', $options['enabled_filters'])) {
            $builder->add('startdatum', AppDateType::class, [
                'required' => true,
                'data' => new \DateTime('first day of January this year'),
            ]);
        }

        if (in_array('einddatum', $options['enabled_filters'])) {
            $builder->add('einddatum', AppDateType::class, [
                'required' => true,
                'data' => (new \DateTime('today')),
            ]);
        }

        if (in_array('geslacht', $options['enabled_filters'])) {
            $builder->add('geslacht', EntityType::class, [
                'class' => Geslacht::class,
                'required' => false,
                'placeholder' => 'Man en vrouw',
            ]);
        }

        if (in_array('referentieperiode', $options['enabled_filters'])) {
            $builder->add('referentieperiode', ChoiceType::class, [
                'required' => true,
                'expanded' => true,
                'choices' => [
                    'het voorgaande jaar' => 0,
                    'het afgelopen jaar' => 1,
                    'dezelfde periode een jaar eerder' => 2,
                ],
            ]);
        }

        if (in_array('amoc_landen', $options['enabled_filters'])) {
            $builder->add('amoc_landen', AmocLandSelectType::class, [
                'required' => true,
                'multiple' => true,
                'attr' => [
                    'select-all' => null,
                    'select-none' => null,
                ],
            ]);
        }

        $builder
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
            'allow_extra_fields' => true,
            'enabled_filters' => [
                'startdatum',
                'einddatum',
            ],
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
