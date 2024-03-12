<?php

namespace UhkBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\KlantType;
use AppBundle\Form\MedewerkerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use UhkBundle\Entity\Deelnemer;

class DeelnemerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var $deelnemer Deelnemer */
        $deelnemer = $options['data'];

        if ($deelnemer->getKlant() && $deelnemer->getKlant()->getId()) {
            $builder->add('klant', DummyChoiceType::class, [
                'label' => 'Deelnemer',
                'dummy_label' => (string) $deelnemer->getKlant(),
            ]);
        } else {
            $builder->add('klant', KlantType::class, [
                'label' => 'Deelnemer',
                'required' => true,
            ]);
        }

        $builder->add('aanmelddatum', AppDateType::class, [
            'label' => 'Aanmelddatum',
            'required' => true,
        ]);

        if ($deelnemer->getId()) {
            $builder->add('aanmelder', DummyChoiceType::class, [
                'label' => 'Aanmelder',
                'dummy_label' => (string) $deelnemer->getAanmelder(),
            ]);
        } else {
            $builder->add('aanmelder', MedewerkerType::class, [
                'label' => 'Aanmelder',
                'required' => true,
            ]);
        }

        $builder
            ->add('medewerker', MedewerkerType::class, [
                'label' => 'Medewerker',
                'required' => true,
            ])
            ->add('submit', SubmitType::class);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
