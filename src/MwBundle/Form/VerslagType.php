<?php

namespace MwBundle\Form;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use InloopBundle\Form\LocatieSelectType;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\Doorverwijzing;
use MwBundle\Entity\Trajecthouder;
use MwBundle\Entity\Verslag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VerslagType extends AbstractType
{
    /** @var EventDispatcher */
    private $eventDispatcher;
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('medewerker', MedewerkerType::class)
            ->add('datum', AppDateType::class, [
                'required' => true,

            ])
            ->add('locatie', LocatieSelectType::class,[
                'locatietypes'=>$options['locatieTypes'],
                'required'=>true
            ])
            ->add('aantalContactmomenten', IntegerType::class)

            ->add('opmerking', CKEditorType::class, ['attr' => ['rows' => 10,'cols'=>50],'required'=>true])
            ->add('access', ChoiceType::class,[
                'required'=>true,
                'label'=>'Zichtbaar voor',
                'placeholder'=>'Kies een optie',
                'choices'=>[
                    Verslag::$accessTypes[Verslag::ACCESS_MW]=>Verslag::ACCESS_MW,
                    Verslag::$accessTypes[Verslag::ACCESS_ALL]=>Verslag::ACCESS_ALL,
                    ],
                ])
            ->add('submit', SubmitType::class)
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
                /** @var Verslag  $verslag */
                $verslag = $event->getData();

                $dienstenEvent = new DienstenLookupEvent($verslag->getKlant()->getId());
                if ($dienstenEvent->getKlantId()) {
                    $this->eventDispatcher->dispatch($dienstenEvent,Events::DIENSTEN_LOOKUP);
                }

                $diensten = $dienstenEvent->getDiensten();
                $twKlant = null;
                foreach($diensten as $dienst)
                {
                    if($dienst->getNaam() == "Tijdelijk wonen")
                    {
                        $twKlant = $dienst->getEntity();
                    }
                }

                if($twKlant !== null)
                {
                     $event->getForm()->add('delenTw',CheckboxType::class,[
                        'label'=>'Delen met TW?',
                         'required'=>false,
                        ]);
                }
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Verslag::class,
//            'attr' => ['novalidate' => 'novalidate'],
            'locatieTypes'=>["Maatschappelijk werk","Virtueel"],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
