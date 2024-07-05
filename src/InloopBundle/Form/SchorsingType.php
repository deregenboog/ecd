<?php

namespace InloopBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\JaNeeType;
use InloopBundle\Entity\Schorsing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SchorsingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alleLocaties', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Alle locaties',
            ])
            ->add('locaties', LocatieSelectType::class, [
                'multiple' => true,
                'expanded' => true,
                'locatietypes' => ['Inloop'],
            ])
            ->add('datumTot', AppDateType::class, [
                'label' => 'Schorsen t/m',
            ])
            ->add('redenen', RedenSelectType::class, [
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('redenOverig', null, [
                'label' => false,
                'required' => false,
            ])
            ->add('agressie', JaNeeType::class, [
                'label' => 'Is de agressie gericht tegen een medewerker, stagair of vrijwilliger?',
                'attr' => [
                    'class' => 'agressie agressie_parent',
                ],
            ])
        ;

        foreach (range(1, 4) as $i) {
            $builder->add("agressiedoelwit_$i", AgressieDoelwitType::class, [
                'index' => $i,
                'label' => 1 === $i ? "Tegen wie is de agressie gericht? Betrokkene $i" : "Betrokkene $i",
                'attr' => [
                    'class' => 'agressie  agressie_children',
                ],
            ]);
        }

        $builder
            ->add('aangifte', JaNeeType::class, [
                'label' => 'Is er aangifte gedaan?',
                'attr' => [
                    'class' => 'agressie  agressie_children',
                    'novalidate' => 'novalidate',
                ],
            ])
            ->add('nazorg', JaNeeType::class, [
                'label' => 'Is er nazorg nodig?',
                'attr' => [
                    'class' => 'agressie  agressie_children',
                    'novalidate' => 'novalidate',
                ],
            ])
            ->add('opmerking', null, ['required' => false])
            ->add('locatiehoofd', null, ['required' => false])
            ->add('bijzonderheden', null, ['required' => false])
            ->add('submit', SubmitType::class)

        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            if (!$event->getData()->getId()) {
                $event->getForm()
                    ->add('submitAndAddIncident', SubmitType::class, [
                        'label' => 'Opslaan en incident toevoegen',
                    ]);
            }
        });
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Schorsing::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
