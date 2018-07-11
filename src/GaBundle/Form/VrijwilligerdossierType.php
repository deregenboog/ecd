<?php

namespace GaBundle\Form;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\VrijwilligerType;
use GaBundle\Entity\Vrijwilligerdossier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VrijwilligerdossierType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dossier = $options['data'];

        if ($this->hasVrijwilliger($dossier)) {
            $builder->add('vrijwilliger', ChoiceType::class, [
                'mapped' => false,
                'disabled' => true,
                'choices' => [
                    $dossier->getVrijwilliger()->getNaam() => null,
                ],
            ]);
        } else {
            $builder->add(
                $builder->create('vrijwilliger', VrijwilligerType::class)
                    ->remove('opmerking')
                    ->remove('geenPost')
                    ->remove('geenEmail')
            );
        }

        $builder
            ->add('medewerker', MedewerkerType::class)
            ->add($builder->create('aanmelding', AanmeldingType::class, ['label' => 'Aanmelding'])
                ->remove('medewerker')
            )
        ;

        if (!$dossier->getAanmelding()) {
            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $dossier = $event->getData();
                $dossier->getAanmelding()->setMedewerker($dossier->getMedewerker());
            });
        }

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vrijwilligerdossier::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }

    private function hasVrijwilliger(Vrijwilligerdossier $dossier = null)
    {
        return $dossier && $dossier->getVrijwilliger() instanceof Vrijwilliger && $dossier->getVrijwilliger()->getId();
    }
}
