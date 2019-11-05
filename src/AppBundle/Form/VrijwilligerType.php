<?php

namespace AppBundle\Form;

use AppBundle\Entity\Overeenkomst;
use AppBundle\Entity\Postcode;
use AppBundle\Entity\Vog;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Util\PostcodeFormatter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VrijwilligerType extends AbstractType
{
    /**
     * @var EntityManager
     */
    private $entityManager;


    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('voornaam')
            ->add('tussenvoegsel')
            ->add('achternaam')
            ->add('roepnaam')
            ->add('geslacht', null, [
                'required' => true,
                'placeholder' => '',
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('geslacht')
                        ->orderBy('geslacht.id', 'DESC');
                },
            ])
            ->add('geboortedatum', AppDateType::class, ['required' => false])
            ->add('land', LandSelectType::class, ['required' => true])
            ->add('nationaliteit', NationaliteitSelectType::class, ['required' => true])
            ->add('bsn', null, ['label' => 'BSN'])
            ->add('medewerker', MedewerkerType::class, ['required' => true])
            ->add('adres')
            ->add('postcode')
            ->add('plaats')
            ->add('email')
            ->add('mobiel')
            ->add('telefoon')
            ->add('opmerking', AppTextareaType::class, ['required' => false])
            ->add('geenPost', null, ['label' => 'Geen post'])
            ->add('geenEmail')
            ->add('vogAangevraagd', null, ['label' => 'VOG aangevraagd'])
            ->add('geinformeerdOpslaanGegevens', null, ['label' => 'Geinformeerd opslaan gegevens'])
        ;

        if (!$options['data'] || !$options['data']->getVog()) {
            $builder->add('vog', DocumentType::class, [
                'required' => false,
                'label' => 'VOG',
                'data_class' => Vog::class,
                'data' => $options['data'] ? $options['data']->getVog() : null,
            ])->get('vog')
                ->remove('medewerker')
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    /* @var Vog vog */
                    $vog = $event->getData();
                    if ($vog) {
                        // assign Medewerker from parent form
                        $vog->setMedewerker($event->getForm()->getParent()->get('medewerker')->getData());
                    }
                })
            ;
        }

        if (!$options['data'] || !$options['data']->getOvereenkomst()) {
            $builder->add('overeenkomst', DocumentType::class, [
                'required' => false,
                'label' => 'Overeenkomst',
                'data_class' => Overeenkomst::class,
                'data' => $options['data'] ? $options['data']->getOvereenkomst() : null,
            ])->get('overeenkomst')
                ->remove('medewerker')
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    /* @var Overeenkomst $overeenkomst */
                    $overeenkomst = $event->getData();
                    if ($overeenkomst) {
                        // assign Medewerker from parent form
                        $overeenkomst->setMedewerker($event->getForm()->getParent()->get('medewerker')->getData());
                    }
                })
            ;
        }
        $builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $event){
            Vrijwilliger::KoppelPostcodeWerkgebiedClosure($event, $this->entityManager);
        });

        $builder
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vrijwilliger::class,
            'data' => null,
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
