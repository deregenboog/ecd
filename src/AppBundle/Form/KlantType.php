<?php

namespace AppBundle\Form;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Postcode;
use AppBundle\Util\PostcodeFormatter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Faker\Provider\Address;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlantType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var array TBC Countries from config parameter.
     */
    private $tbcCountries = [];

    public function __construct(EntityManagerInterface $entityManager, array $tbc_countries)
    {
        $this->entityManager = $entityManager;
        $this->tbc_countries = $tbc_countries;
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
            ->add('overleden', CheckboxType::class, ['required' => false])
            ->add('land', LandSelectType::class, ['required' => true])
            ->add('nationaliteit', NationaliteitSelectType::class, ['required' => true])
            ->add('voorkeurstaal', TaalSelectType::class, ['required' => false])
            ->add('overigeTalen', TaalSelectType::class, [
                'required' => false,
                'multiple' => true,
                'by_reference' => false,
            ])
            ->add('bsn', null, ['label' => 'BSN'])
            ->add('coronaBesmetVanaf', AppDateType::class, ['required' => false])
            ->add('medewerker', MedewerkerType::class, ['required' => true])
            ->add('maatschappelijkWerker', MedewerkerType::class, ['required' => false,'preset'=>false])
            ->add('adres')
            ->add('postcode')
            ->add('plaats')
            ->add('briefadres',AppDateType::class,['label'=>'Is briefadres tot','required'=>false])
            ->add('email')
            ->add('mobiel')
            ->add('telefoon')

            ->add('opmerking', AppTextareaType::class, ['required' => false])
            ->add('geenPost',null, ['label' => 'Geen post'])
            ->add('geenEmail')

        ;

        try
        {
            if(null !== ($builder->getData()) && in_array((string)$builder->getData()->getLand(),$this->tbc_countries))
            {
                $builder->add('laatste_TBC_controle', AppDateType::class,
                    [
                        'label' => 'TBC-check?',
                        'required' => false,
                    ]
                );
            }
        }
        catch(FatalThrowableError $e)
        {

        }

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event){
            Klant::KoppelPostcodeWerkgebiedClosure($event, $this->entityManager);
        });
        $builder->add('submit', SubmitType::class);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Klant::class,
            'data' => null,

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
