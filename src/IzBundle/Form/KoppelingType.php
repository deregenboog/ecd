<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\MedewerkerType;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KoppelingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $hulpvraag = $options['data'];
        $hulpaanbod = $hulpvraag->getKoppeling()->getHulpaanbod();

        if ($hulpvraag instanceof Hulpvraag) {
            $builder->add('hulpvraag', DummyChoiceType::class, [
                'dummy_label' => (string) $hulpvraag,
            ]);
        }

        if ($hulpaanbod instanceof Hulpaanbod) {
            $builder->add('hulpaanbod', DummyChoiceType::class, [
                'dummy_label' => (string) $hulpaanbod,
            ]);
        }

        $builder
            ->add('medewerker', MedewerkerType::class, [
                'label' => 'Coördinator',
                'required' => true,
            ])
            ->add('koppelingStartdatum', AppDateType::class, [
                'label' => 'Startdatum koppeling',
                'required' => true,
            ])
            ->add('tussenevaluatiedatum', AppDateType::class, [
                'label' => 'Datum tussenevaluatie',
                'required' => true,
            ])
            ->add('eindevaluatiedatum', AppDateType::class, [
                'label' => 'Datum eindevaluatie',
                'required' => true,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hulpvraag::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
