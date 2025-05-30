<?php

namespace DagbestedingBundle\Form;

use App\DagbestedingBundle\Form\ResultaatgebiedsoortSelectType;
use App\DagbestedingBundle\Form\TrajectsoortSelectType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseSelectType;
use AppBundle\Form\BaseType;
use DagbestedingBundle\Entity\Locatie;
use DagbestedingBundle\Entity\Traject;
use DagbestedingBundle\Entity\Trajectafsluiting;
use DagbestedingBundle\Entity\Trajectcoach;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrajectType extends AbstractType
{
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
                    ->add('closeDeelnemer', CheckboxType::class, [
                        'label' => 'Deelnemer ook afsluiten na afsluiten traject?',
                        'mapped' => false,
                        'required' => false,
                ])
                    ->add('submit', SubmitType::class, ['label' => 'Afsluiten'])
                ;
                break;

            case BaseType::MODE_ADD:
            case BaseType::MODE_EDIT:
            default:
                $builder
                    ->add('soort', TrajectsoortSelectType::class, [
                        'required' => true,
                    ])
                    ->add('resultaatgebiedsoort', ResultaatgebiedsoortSelectType::class, [
                        'required' => true,
                    ])
                    ->add('ondersteuningsplanVerwerkt')
                    ->add('startdatum', AppDateType::class)
                    ->add('evaluatiedatum', AppDateType::class, [
                        'attr' => [
                            'placeholder' => 'dd-mm-jjjj: '.(new \DateTime())->modify(Traject::TERMIJN_EVALUATIE)->format('d-m-Y'),
                        ],
                        'required' => false,
                    ])
                    ->add('trajectcoach', BaseSelectType::class, [
                        'class' => Trajectcoach::class,
                    ])
                    ->add('locaties', BaseSelectType::class, [
                        'class' => Locatie::class,
                        'multiple' => true,
                        'expanded' => true,
                        'current' => $options['data']->getLocaties(),
                    ])

                    ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
                ;
                break;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Traject::class,
            'mode' => BaseType::MODE_ADD,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
