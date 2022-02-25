<?php

namespace DagbestedingBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseSelectType;
use AppBundle\Form\BaseType;
use DagbestedingBundle\Entity\Locatie;
use DagbestedingBundle\Entity\Project;
use DagbestedingBundle\Entity\Resultaatgebiedsoort;
use DagbestedingBundle\Entity\Traject;
use DagbestedingBundle\Entity\Trajectafsluiting;
use DagbestedingBundle\Entity\Trajectsoort;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrajectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        switch ($options['mode']) {
            case BaseType::MODE_CLOSE:
                $builder
                    ->add('afsluitdatum', AppDateType::class, ['data' => new \DateTime()])
                    ->add('afsluiting', null, [
                        'class' => Trajectafsluiting::class,
                        'label' => 'Reden afsluiting',
                        'required' => true,
                        'placeholder' => 'Selecteer een item',
                    ])
                    ->add('submit', SubmitType::class, ['label' => 'Afsluiten'])
                ;
                break;

            case BaseType::MODE_ADD:
            case BaseType::MODE_EDIT:
            default:
                $builder
                    ->add('soort', EntityType::class, [
                        'class' => Trajectsoort::class,
                        'placeholder' => '',
                    ])
                    ->add('resultaatgebiedsoort', EntityType::class, [
                        'class' => Resultaatgebiedsoort::class,
                        'label' => 'Resultaatgebied',
                        'placeholder' => '',
                    ])
                    ->add('ondersteuningsplanVerwerkt')
                    ->add('startdatum', AppDateType::class)
                    ->add('trajectcoach')
                    ->add('locaties', BaseSelectType::class, [
                        'class' => Locatie::class,
                        'multiple' => true,
                        'expanded' => true,
                        'current' => $options['data']->getLocaties(),
                    ])
                    ->add('projecten', BaseSelectType::class, [
                        'class' => Project::class,
                        'multiple' => true,
                        'expanded' => true,
                        'current' => $options['data']->getProjecten(),
                    ])
                    ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
                ;
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Traject::class,
            'mode' => BaseType::MODE_ADD,
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
