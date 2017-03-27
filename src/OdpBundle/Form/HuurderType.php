<?php

namespace OdpBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\KlantType;
use OdpBundle\Entity\Huurder;
use OdpBundle\Entity\Verslag;

class HuurderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['data']->getKlant()->getId()) {
            $builder->add('klant', KlantType::class);
        } else {
            $builder->add('medewerker', MedewerkerType::class);
        }

        $builder->add('aanmelddatum', AppDateType::class, ['data' => new \DateTime()]);

        if (!$options['data']->getId()) {
            $builder->add('opmerking', TextareaType::class, [
                'label' => 'Intakeverslag',
                'mapped' => false,
                'attr' => ['rows' => 10],
            ]);
        }

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            /* @var $huurder Huurder */
            $huurder = $event->getData();
            $form = $event->getForm();

            if (!$form->has('medewerker')) {
                $huurder->setMedewerker($huurder->getKlant()->getMedewerker());
            }

            if ($event->getForm()->has('opmerking')) {
                $verslag = new Verslag();
                $verslag
                    ->setDatum($huurder->getAanmelddatum())
                    ->setOpmerking($event->getForm()->get('opmerking')->getData())
                    ->setMedewerker($huurder->getMedewerker())
                ;
                $huurder->addVerslag($verslag);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Huurder::class,
        ]);
    }
}
