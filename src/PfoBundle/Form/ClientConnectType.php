<?php

namespace PfoBundle\Form;

use AppBundle\Form\BaseType;
use Doctrine\ORM\EntityRepository;
use PfoBundle\Entity\Client;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientConnectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $client Client */
        $client = $options['data'];

        $builder
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
                'query_builder' => function (EntityRepository $repository) use ($client) {
                    return $repository->createQueryBuilder('client')
                        ->innerJoin('client.gekoppeldeClienten', 'gekoppeldeClient')
                        ->where('client <> :self')
                        ->orderBy('client.achternaam, client.voornaam')
                        ->setParameters([
                            'self' => $client,
                        ])
                    ;
                },
            ])
            ->add('gekoppeldeClient', EntityType::class, [
                'label' => 'Client koppelen',
                'mapped' => false,
                'required' => false,
                'multiple' => false,
                'class' => Client::class,
                'query_builder' => function (EntityRepository $repository) use ($client) {
                    $gekoppeldeClienten = $client ? $client->getGekoppeldeClienten() : [];

                    return $repository->createQueryBuilder('client')
                        ->leftJoin('client.hoofdclienten', 'hoofdclient')
                        ->leftJoin('client.gekoppeldeClienten', 'gekoppeldeClient')
                        ->where('client <> :self AND hoofdclient.id IS NULL AND gekoppeldeClient.id IS NULL')
                        ->orWhere('client <> :self AND client IN (:gekoppeldeClienten)')
                        ->orderBy('client.achternaam, client.voornaam')
                        ->setParameters([
                            'self' => $client,
                            'gekoppeldeClienten' => $client->getGekoppeldeClienten(),
                        ])
                    ;
                },
            ])
            ->add('gekoppeldeClienten', EntityType::class, [
                'required' => false,
                'by_reference' => false,
                'multiple' => true,
                'expanded' => true,
                'class' => Client::class,
                'query_builder' => function (EntityRepository $repository) use ($client) {
                    $gekoppeldeClienten = $client ? $client->getGekoppeldeClienten() : [];

                    return $repository->createQueryBuilder('client')
                        ->leftJoin('client.hoofdclienten', 'hoofdclient')
                        ->leftJoin('client.gekoppeldeClienten', 'gekoppeldeClient')
                        ->where('client <> :self AND hoofdclient.id IS NULL AND gekoppeldeClient.id IS NULL')
                        ->orWhere('client <> :self AND client IN (:gekoppeldeClienten)')
                        ->orderBy('client.achternaam, client.voornaam')
                        ->setParameters([
                            'self' => $client,
                            'gekoppeldeClienten' => $client->getGekoppeldeClienten(),
                        ])
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
