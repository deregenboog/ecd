<?php

namespace PfoBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use PfoBundle\Entity\Client;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
