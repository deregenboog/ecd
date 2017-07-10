<?php

namespace DagbestedingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\KlantType;
use DagbestedingBundle\Entity\Deelnemer;
use AppBundle\Form\BaseType;

class DeelnemerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data']->getKlant()->getId()) {
            $builder
                ->add('medewerker', MedewerkerType::class)
            ;
        } else {
            $builder
                ->add('klant', KlantType::class)
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    $event->getData()->setMedewerker($event->getData()->getKlant()->getMedewerker());
                })
            ;
        }

        $builder
            ->add('risDossiernummer')
            ->add('aanmelddatum', AppDateType::class)
            ->add('afsluitdatum', AppDateType::class, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deelnemer::class,
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
