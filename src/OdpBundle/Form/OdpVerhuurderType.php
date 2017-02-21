<?php

namespace OdpBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use OdpBundle\Entity\OdpVerhuurder;
use AppBundle\Form\AppDateType;
use AppBundle\Form\KlantType;
use AppBundle\Form\MedewerkerType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class OdpVerhuurderType extends AbstractType
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
        
        $builder->add('aanmelddatum', AppDateType::class);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $odpHuurder = $event->getData();
            $odpHuurder->setMedewerker($odpHuurder->getKlant()->getMedewerker());
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OdpVerhuurder::class,
            'enabled_filters' => [],
        ]);
    }
}
