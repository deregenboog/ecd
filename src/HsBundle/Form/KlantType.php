<?php

namespace HsBundle\Form;

use AppBundle\Entity\Postcode;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Util\PostcodeFormatter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use HsBundle\Entity\Klant;
use TwBundle\Entity\Huurovereenkomst;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlantType extends AbstractType
{
    use MedewerkerTypeTrait;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
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
            ->add('status', ChoiceType::class,[
                'required' => false,
                'choices' => (new Klant())->getStatussen(),
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
                /* @var $klant Klant */
                $klant = $event->getData();

                if (!$klant->getPostcode()) {
                    return;
                }

                /* @var $postcode Postcode */
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
    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
