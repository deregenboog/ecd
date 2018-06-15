<?php

namespace PfoBundle\Form;

use AppBundle\Form\FilterType;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use PfoBundle\Entity\Client;
use PfoBundle\Entity\Groep;
use PfoBundle\Filter\ClientFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('voornaam', $options['enabled_filters'])) {
            $builder->add('voornaam', null, [
                'attr' => ['placeholder' => 'Voornaam'],
            ]);
        }

        if (in_array('achternaam', $options['enabled_filters'])) {
            $builder->add('achternaam', null, [
                'attr' => ['placeholder' => 'Achternaam'],
            ]);
        }

        if (in_array('groep', $options['enabled_filters'])) {
            $builder->add('groep', EntityType::class, [
                'required' => false,
                'class' => Groep::class,
            ]);
        }

        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', MedewerkerType::class, [
                'required' => false,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('medewerker')
                        ->innerJoin(Client::class, 'client', 'WITH', 'client.medewerker = medewerker')
                        ->orderBy('medewerker.voornaam');
                },
            ]);
        }

        if (in_array('actief', $options['enabled_filters'])) {
            $builder->add('actief', ChoiceType::class, [
                'choices' => [
                    'Actief' => 1,
                    'Afgesloten' => 0,
                ],
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ClientFilter::class,
            'data' => new ClientFilter(),
            'enabled_filters' => [
                'voornaam',
                'achternaam',
                'groep',
                'medewerker',
                'actief',
                'filter',
                'download',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FilterType::class;
    }
}
