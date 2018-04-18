<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\CKEditorType;
use AppBundle\Form\MedewerkerSelectType;
use IzBundle\Entity\Intake;
use IzBundle\Entity\IzKlant;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Entity\Verslag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntakeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var $intake Intake */
        $intake = $options['data'];

        $builder
            ->add('intakedatum', AppDateType::class)
            ->add('medewerker', MedewerkerSelectType::class)
        ;

        if (!$intake->getId()) {
            $builder
                ->add('verslag', CKEditorType::class, ['mapped' => false])
                ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                    $opmerking = $event->getForm()->get('verslag')->getData();
                    $intake = $event->getData();

                    $verslag = new Verslag();
                    $verslag
                        ->setMedewerker($intake->getMedewerker())
                        ->setIzDeelnemer($intake->getIzDeelnemer())
                        ->setOpmerking($opmerking)
                    ;
                    $intake->getIzDeelnemer()->addVerslag($verslag);
                })
            ;
        }

        if ($intake) {
            if ($intake->getIzDeelnemer() instanceof IzKlant) {
                $builder->add('gezinMetKinderen', CheckboxType::class, [
                    'required' => false,
                ]);
            } elseif ($intake->getIzDeelnemer() instanceof IzVrijwilliger) {
                $builder->add('stagiair', CheckboxType::class, [
                    'required' => false,
                ]);
            }
        }

        $builder->add('submit', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Intake::class,
        ]);
    }

    public function getParent()
    {
        return BaseType::class;
    }
}
