<?php

namespace PfoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Klant;
use AppBundle\Form\MedewerkerType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use AppBundle\Form\BaseType;
use PfoBundle\Entity\Client;
use AppBundle\Form\AppDateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ClientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var $client Client */
        $client = $options['data'];

        $builder
            ->add('voornaam')
            ->add('tussenvoegsel')
            ->add('achternaam', null, [
                'required' => true,
            ])
            ->add('geslacht')
            ->add('geboortedatum', AppDateType::class, [
                'required' => false,
            ])
            ->add('adres')
            ->add('postcode')
            ->add('plaats')
            ->add('telefoon')
            ->add('mobiel')
            ->add('email')
            ->add('notitie', null, [
                'required' => false,
                'attr' => ['rows' => 10],
            ])
            ->add('medewerker', MedewerkerType::class, [
                'required' => true,
            ])
            ->add('groep', null, [
                'required' => true,
            ])
            ->add('aardRelatie', null, [
                'required' => true,
            ])
            ->add('dubbeleDiagnose', ChoiceType::class, [
                'choices' => [
                    'Nee' => Client::DUBBELE_DIAGNOSE_NEE,
                    'Ja' => Client::DUBBELE_DIAGNOSE_JA,
                    'Vermoedelijk' => Client::DUBBELE_DIAGNOSE_VERMOEDELIJK,
                ],
                'expanded' => true,
                'label' => 'Dubbele diagnose?',
            ])
            ->add('eerdereHulpverlening', ChoiceType::class, [
                'choices' => [
                    'Nee' => Client::EERDERE_HULPVERLENING_NEE,
                    'Ja' => Client::EERDERE_HULPVERLENING_JA,
                ],
                'expanded' => true,
                'label' => 'Eerder hulpverlening ontvangen?',
            ])
            ->add('via', null, [
                'required' => false,
                'attr' => ['rows' => 10],
            ])
            ->add('hulpverleners', null, [
                'label' => 'Andere betrokken hulpverleners',
                'required' => false,
                'attr' => ['rows' => 10],
            ])
            ->add('contacten', null, [
                'label' => 'Andere belangrijke contacten',
                'required' => false,
                'attr' => ['rows' => 10],
            ])
            ->add('begeleidingsformulierOverhandigd', AppDateType::class, [
                'required' => false,
            ])
            ->add('briefHuisartsVerstuurd', AppDateType::class, [
                'required' => false,
            ])
            ->add('evaluatieformulierOverhandigd', AppDateType::class, [
                'required' => false,
            ])
            ->add('afsluitdatum', AppDateType::class, [
                'required' => false,
            ])
            ->add('type', CheckboxType::class, [
                'label' => 'Hoofdclient',
                'mapped' => false,
                'data' => ($client && $client->getHoofdclient()) ? false : true,
            ])
            ->add('hoofdclient', EntityType::class, [
                'label' => 'Gekoppeld aan',
                'required' => false,
                'multiple' => false,
                'class' => Client::class,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->createQueryBuilder('client')
                        ->innerJoin('client.gekoppeldeClienten', 'gekoppeldeClient')
                        ->orderBy('client.achternaam, client.voornaam')
                    ;
                },
            ])
            ->add('gekoppeldeClient', EntityType::class, [
                'label' => 'Client koppelen',
                'mapped' => false,
                'required' => false,
                'multiple' => false,
                'class' => Client::class,
                'query_builder' => function(EntityRepository $repository) use ($client) {
                    $gekoppeldeClienten = $client ? $client->getGekoppeldeClienten() : [];

                    return $repository->createQueryBuilder('client')
                        ->leftJoin('client.hoofdclienten', 'hoofdclient')
                        ->leftJoin('client.gekoppeldeClienten', 'gekoppeldeClient')
                        ->where('hoofdclient.id IS NULL AND gekoppeldeClient.id IS NULL')
                        ->orWhere('client IN (:gekoppeldeClienten)')
                        ->orderBy('client.achternaam, client.voornaam')
                        ->setParameter(':gekoppeldeClienten', $client->getGekoppeldeClienten())
                    ;
                },
            ])
            ->add('gekoppeldeClienten', EntityType::class, [
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'class' => Client::class,
                'query_builder' => function(EntityRepository $repository) use ($client) {
                    $gekoppeldeClienten = $client ? $client->getGekoppeldeClienten() : [];

                    return $repository->createQueryBuilder('client')
                        ->leftJoin('client.hoofdclienten', 'hoofdclient')
                        ->leftJoin('client.gekoppeldeClienten', 'gekoppeldeClient')
                        ->where('hoofdclient.id IS NULL AND gekoppeldeClient.id IS NULL')
                        ->orWhere('client IN (:gekoppeldeClienten)')
                        ->orderBy('client.achternaam, client.voornaam')
                        ->setParameter(':gekoppeldeClienten', $client->getGekoppeldeClienten())
                    ;
                },
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
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
