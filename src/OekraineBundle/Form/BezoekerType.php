<?php

namespace OekraineBundle\Form;

use AppBundle\Entity\Klant;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\KlantType;
use AppBundle\Form\MedewerkerType;
use OekraineBundle\Entity\Bezoeker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BezoekerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data'] instanceof Bezoeker
            && $options['data']->getAppKlant() instanceof Klant
            && $options['data']->getAppKlant()->getId()
        ) {
            $builder->add('appKlant', DummyChoiceType::class, [
                'dummy_label' => (string) $options['data'],
                'label' => 'Klant',
            ]);
        } else {
            $builder->add('appKlant', KlantType::class, [
                'required' => true,
                'label' => 'Klant',
            ]);
        }
        $builder->add('mentalCoach', MedewerkerType::class, [
            'required'=>false,
        ])
        ->add('submit', SubmitType::class)
        ;
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bezoeker::class,
        ]);
    }
}
