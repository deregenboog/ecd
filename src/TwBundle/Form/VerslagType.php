<?php

namespace TwBundle\Form;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwBundle\Entity\Klant;
use TwBundle\Entity\SuperVerslag;
use TwBundle\Entity\Verslag;

class VerslagType extends AbstractType
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medewerker', MedewerkerType::class)
            ->add('datum', AppDateType::class)
            ->add('opmerking', AppTextareaType::class)
            ->add('delenMw', CheckboxType::class, [
                'label' => 'Delen met MW?',
            ])
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])

        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            return; // does not work at the moment. Refactor of Verslagen and entities should be done first. Not feasible atm.

            /** @var Verslag $verslag */
            $verslag = $event->getData();

            $klant = $verslag->getKlant();
            if (!$klant instanceof Klant) {
                return;
            }

            $dienstenEvent = new DienstenLookupEvent($klant->getAppKlant()->getId());
            if ($dienstenEvent->getKlantId()) {
                $this->eventDispatcher->dispatch($dienstenEvent, Events::DIENSTEN_LOOKUP);
            }

            $diensten = $dienstenEvent->getDiensten();
            $mwKlant = null;
            foreach ($diensten as $dienst) {
                if ('Maatschappelijk werk' == $dienst->getNaam()) {
                    $mwKlant = true;
                }
            }

            if (null !== $mwKlant) {
                $event->getForm()->add('delenMw', CheckboxType::class, [
                    'label' => 'Delen met MW?',
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SuperVerslag::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
