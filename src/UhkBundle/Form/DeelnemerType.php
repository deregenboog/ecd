<?php

namespace UhkBundle\Form;

use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\KlantType;
use UhkBundle\Entity\Deelnemer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class DeelnemerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $deelnemer Deelnemer */
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

        $builder
            ->add('aanmeldNaam', null, [
                'required' => true,
            ])
            ->add('contactpersoonNazorg', null, [
                'required' => true,
            ])

            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }
}
