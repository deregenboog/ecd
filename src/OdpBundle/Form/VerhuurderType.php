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
use OdpBundle\Entity\Verhuurder;
use OdpBundle\Entity\Verslag;

class VerhuurderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data']->getKlant()->getId()) {
            $builder->add('medewerker', MedewerkerType::class);
        } else {
            $builder->add('klant', KlantType::class);
        }

        $builder
            ->add('aanmelddatum', AppDateType::class, ['data' => new \DateTime()])
            ->add('woningbouwcorporatie')
            ->add('woningbouwcorporatieToelichting', null, ['label' => 'Woningbouwcorporatie anders/toelichting'])
        ;

        if (!$options['data']->getId()) {
            $builder->add('opmerking', TextareaType::class, [
                'label' => 'Intakeverslag',
                'mapped' => false,
                'attr' => ['rows' => 10],
            ]);
        }

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            /* @var $verhuurder Verhuurder */
            $verhuurder = $event->getData();
            $form = $event->getForm();

            if (!$form->has('medewerker')) {
                $verhuurder->setMedewerker($verhuurder->getKlant()->getMedewerker());
            }

            if ($event->getForm()->has('opmerking')) {
                $verslag = new Verslag();
                $verslag
                    ->setDatum($verhuurder->getAanmelddatum())
                    ->setOpmerking($event->getForm()->get('opmerking')->getData())
                    ->setMedewerker($verhuurder->getMedewerker())
                ;
                $verhuurder->addVerslag($verslag);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Verhuurder::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return \AppBundle\Form\BaseType::class;
    }
}
