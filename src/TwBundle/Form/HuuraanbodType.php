<?php

namespace TwBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use TwBundle\Entity\Huuraanbod;
use TwBundle\Entity\Verslag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HuuraanbodType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medewerker', MedewerkerType::class)
            ->add('project', ProjectSelectType::class)
            ->add('startdatum', AppDateType::class)
            ->add('vormvanovereenkomst', VormVanOvereenkomstType::class,[

            ])
            ->add('datumToestemmingAangevraagd', AppDateType::class, [
                'required' => false,
                'label' => 'Toestemming woningbouwcorporatie aangevraagd',
            ])
            ->add('datumToestemmingToegekend', AppDateType::class, [
                'required' => false,
                'label' => 'Toestemming woningbouwcorporatie toegekend',
            ])
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

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Huuraanbod::class,
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
