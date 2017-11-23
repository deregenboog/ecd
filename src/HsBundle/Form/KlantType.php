<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use HsBundle\Entity\Klant;
use AppBundle\Form\AppDateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use AppBundle\Entity\Postcode;
use AppBundle\Util\PostcodeFormatter;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class KlantType extends AbstractType
{
    use MedewerkerTypeTrait;

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
            ->add('geslacht', null, [
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('geslacht')
                        ->orderBy('geslacht.id', 'DESC');
                },
            ])
        ;

        $builder
            ->add('adres')
            ->add('postcode')
            ->add('plaats')
            ->add('email')
            ->add('mobiel')
            ->add('telefoon')
        ;

        $builder->add('inschrijving', AppDateType::class);
        $this->addMedewerkerType($builder, $options);

        $builder
            ->add('bewindvoerder', TextareaType::class, ['required' => false])
            ->add('afwijkendFactuuradres', ChoiceType::class, [
                'expanded' => true,
                'choices' => [
                    'Nee' => 0,
                    'Ja' => 1,
                ],
            ])
            ->add('hulpverlener', HulpverlenerType::class)
            ->add('submit', SubmitType::class)
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                if ($data['postcode']) {
                    $data['postcode'] = PostcodeFormatter::format($data['postcode']);
                    $event->setData($data);
                }
            })
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                /** @var $klant Klant */
                $klant = $event->getData();
                /** @var $postcode Postcode */
                $postcode = $this->entityManager->find(Postcode::class, $klant->getPostcode());
                if ($postcode) {
                    $klant->setWerkgebied($postcode->getStadsdeel());
                    $klant->setPostcodegebied($postcode->getPostcodegebied());
                } else {
                    $klant->setWerkgebied(null);
                    $klant->setPostcodegebied(null);
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
            'data_class' => Klant::class,
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
