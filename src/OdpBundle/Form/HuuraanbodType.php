<?php

namespace OdpBundle\Form;

use AppBundle\Form\AppDateType;
use OdpBundle\Entity\Huuraanbod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\AppTextareaType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use OdpBundle\Entity\Verslag;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class HuuraanbodType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medewerker', MedewerkerType::class)
            ->add('startdatum', AppDateType::class)
            ->add('actief')
        ;

        if (!$options['data']->getId()) {
            $builder
                ->add('opmerking', AppTextareaType::class, ['mapped' => false])
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    $huuraanbod = $event->getData();
                    $verslag = new Verslag();
                    $verslag
                        ->setMedewerker($huuraanbod->getMedewerker())
                        ->setDatum(new \DateTime())
                        ->setOpmerking($event->getForm()->get('opmerking')->getData())
                    ;
                    $huuraanbod->addVerslag($verslag);
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
}
