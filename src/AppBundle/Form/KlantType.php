<?php

namespace AppBundle\Form;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Postcode;
use AppBundle\Util\PostcodeFormatter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
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
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Array TBC Countries from config parameter.
     */
    private $tbcCountries = [];

    public function __construct(EntityManager $entityManager,Array $args)
    {
        $this->entityManager = $entityManager;
        if(is_array($args['$tbc_countries']))
        {
            $this->tbc_countries = $args['$tbc_countries'];
        }


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
            ->add('geinformeerdOpslaanGegevens', CheckboxType::class, ['required'=>false]);

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
    public function getParent()
    {
        return BaseType::class;
    }
}
