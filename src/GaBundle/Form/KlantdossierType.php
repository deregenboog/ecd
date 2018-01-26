<?php

namespace GaBundle\Form;

use AppBundle\Entity\Klant;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\KlantType;
use AppBundle\Service\NameFormatter;
use GaBundle\Entity\Klantdossier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlantdossierType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dossier = $options['data'];

        $builder->add('aanmelddatum', AppDateType::class);

        if ($this->hasKlant($dossier)) {
            $builder->add('klant', DummyChoiceType::class, [
                'dummy_label' => NameFormatter::formatInformal($dossier->getKlant()),
            ]);
        } else {
            $builder->add('klant', KlantType::class);
        }

        $builder->add('submit', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Klantdossier::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }

    private function hasKlant(Klantdossier $dossier = null)
    {
        return $dossier && $dossier->getKlant() instanceof Klant && $dossier->getKlant()->getId();
    }
}
