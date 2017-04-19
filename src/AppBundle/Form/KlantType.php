<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use Doctrine\ORM\EntityRepository;

class KlantType extends AbstractType
{
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
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('geslacht')
                        ->orderBy('geslacht.id', 'DESC');
                },
            ])
            ->add('geboortedatum', AppDateType::class, ['required' => false])
            ->add('land')
            ->add('nationaliteit')
            ->add('bsn', null, ['label' => 'BSN'])
            ->add('medewerker', MedewerkerType::class)
            ->add('adres')
            ->add('postcode')
            ->add('plaats')
            ->add('email')
            ->add('mobiel')
            ->add('telefoon')
            ->add('opmerking')
            ->add('geenPost', null, ['label' => 'Geen post'])
            ->add('geenEmail')
        ;
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
