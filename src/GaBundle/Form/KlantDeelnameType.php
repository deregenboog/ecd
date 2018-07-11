<?php

namespace GaBundle\Form;

use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use GaBundle\Entity\Deelname;
use GaBundle\Entity\KlantDeelname;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlantDeelnameType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var $deelname Deelname */
        $deelname = $options['data'];

        if ($deelname->getKlant()) {
            $builder->add('klant', DummyChoiceType::class, [
                'dummy_label' => (string) $deelname->getKlant(),
            ]);
        } else {
            $builder->add('klant', KlantSelectType::class, [
                'activiteit' => $deelname->getActiviteit(),
            ]);
        }

        if ($deelname->getActiviteit()) {
            $builder->add('activiteit', DummyChoiceType::class, [
                'dummy_label' => (string) $deelname->getActiviteit(),
            ]);
        } else {
            $builder->add('activiteit', ActiviteitSelectType::class, [
                'klant' => $deelname->getKlant(),
            ]);
        }

        if ($deelname->getKlant() && $deelname->getActiviteit()) {
            $builder->add('status', ChoiceType::class, [
                'required' => true,
                'expanded' => true,
                'choices' => [
                    Deelname::STATUS_AANWEZIG => Deelname::STATUS_AANWEZIG,
                    Deelname::STATUS_AFWEZIG => Deelname::STATUS_AFWEZIG,
                ],
            ]);
        }

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => KlantDeelname::class,
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
