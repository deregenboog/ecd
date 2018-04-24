<?php

namespace DagbestedingBundle\Form;

use AppBundle\Form\AppDateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;
use DagbestedingBundle\Entity\Traject;
use DagbestedingBundle\Entity\Resultaatgebied;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use DagbestedingBundle\Entity\Trajectsoort;
use DagbestedingBundle\Entity\Resultaatgebiedsoort;
use DagbestedingBundle\Entity\Trajectafsluiting;

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
                    ->add('startdatum', AppDateType::class)
                    ->add('begeleider')
                    ->add('locaties', null, [
                        'expanded' => true,
                    ])
                    ->add('projecten', null, [
                        'expanded' => true,
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
