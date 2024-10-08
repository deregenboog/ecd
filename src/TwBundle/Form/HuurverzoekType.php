<?php

namespace TwBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwBundle\Entity\Huurverzoek;
use TwBundle\Entity\Verslag;

class HuurverzoekType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medewerker', MedewerkerType::class)
            ->add('startdatum', AppDateType::class)
            ->add('projecten', ProjectSelectType::class, ['multiple' => true])
        ;

        if (!$options['data']->getId()) {
            $builder
                ->add('opmerking', AppTextareaType::class, [
                    'required' => false,
                    'mapped' => false,
                ])
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    if ($event->getForm()->get('opmerking')->getData()) {
                        $verslag = new Verslag();
                        $verslag
                            ->setOpmerking($event->getForm()->get('opmerking')->getData())
                            ->setMedewerker($event->getData()->getMedewerker())
                        ;
                        $event->getData()->addVerslag($verslag);
                    }
                })
            ;
        }

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Huurverzoek::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
