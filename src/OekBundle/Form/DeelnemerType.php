<?php

namespace OekBundle\Form;

use AppBundle\Entity\Klant;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\KlantType;
use AppBundle\Form\MedewerkerType;
use OekBundle\Entity\Deelnemer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeelnemerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $deelnemer Deelnemer */
        $deelnemer = $options['data'];

        if ($deelnemer instanceof Deelnemer
            && $deelnemer->getKlant() instanceof Klant
            && $deelnemer->getKlant()->getId()
        ) {
            $builder->add('klant', DummyChoiceType::class, [
                'dummy_label' => (string) $deelnemer,
            ]);
        } else {
            $builder->add('klant', KlantType::class);
        }

        $builder->add('medewerker', MedewerkerType::class);

        $builder->add('aanmelding', AanmeldingType::class, [
            'label' => 'Aanmelding',
        ]);
        $builder->get('aanmelding')->remove('medewerker');

        if (!$deelnemer->getAanmelding()) {
            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $deelnemer = $event->getData();
                $deelnemer->getAanmelding()->setMedewerker($deelnemer->getMedewerker());
            });
        }

        $builder->add('opmerking', null, [
            'attr' => [
                'rows' => 15,
                'cols' => 50,
            ],
        ]);

        $builder->add('voedselbankklant');

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deelnemer::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
