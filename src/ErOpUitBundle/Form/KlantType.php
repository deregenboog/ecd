<?php

namespace ErOpUitBundle\Form;

use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\KlantType as AppKlantType;
use ErOpUitBundle\Entity\Klant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $klant Klant */
        $klant = $options['data'];

        if ($klant instanceof Klant
            && $klant->getKlant() instanceof AppKlant
            && $klant->getKlant()->getId()
        ) {
            $builder->add('klant', DummyChoiceType::class, [
                'dummy_label' => (string) $klant->getKlant(),
            ]);
        } else {
            $builder
                ->add('klant', AppKlantType::class)
                ->get('klant')
                ->remove('opmerking')
                ->remove('geenPost')
                ->remove('geenEmail')
            ;
        }

        $builder
            ->add('inschrijfdatum', AppDateType::class)
            ->add('communicatieEmail')
            ->add('communicatiePost')
            ->add('communicatieTelefoon')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Klant::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
