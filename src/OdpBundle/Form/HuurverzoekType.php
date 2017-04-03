<?php

namespace OdpBundle\Form;

use AppBundle\Form\AppDateType;
use OdpBundle\Entity\Huurverzoek;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\AppTextareaType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use OdpBundle\Entity\Verslag;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;

class HuurverzoekType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medewerker', MedewerkerType::class)
            ->add('startdatum', AppDateType::class, ['data' => new \DateTime()])
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
                            ->setDatum($event->getData()->getAanmelddatum())
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
            'data_class' => Huurverzoek::class,
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
