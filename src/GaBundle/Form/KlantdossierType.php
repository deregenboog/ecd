<?php

namespace GaBundle\Form;

use AppBundle\Entity\Klant;
use AppBundle\Form\BaseType;
use AppBundle\Form\KlantType;
use AppBundle\Form\MedewerkerType;
use GaBundle\Entity\Klantdossier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlantdossierType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dossier = $options['data'];

        if ($this->hasKlant($dossier)) {
            $builder->add('klant', ChoiceType::class, [
                'mapped' => false,
                'disabled' => true,
                'choices' => [
                    $dossier->getKlant()->getNaam() => null,
                ],
            ]);
        } else {
            $builder->add(
                $builder->create('klant', KlantType::class)
                    ->remove('opmerking')
                    ->remove('geenPost')
                    ->remove('geenEmail')
            );
        }

        $builder
            ->add('medewerker', MedewerkerType::class)
            ->add($builder->create('aanmelding', AanmeldingType::class, ['label' => 'Aanmelding'])
                ->remove('medewerker')
            )
        ;

        if (!$dossier->getAanmelding()) {
            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $dossier = $event->getData();
                $dossier->getAanmelding()->setMedewerker($dossier->getMedewerker());
            });
        }

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);
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
