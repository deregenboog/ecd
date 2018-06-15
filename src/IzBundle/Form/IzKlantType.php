<?php

namespace IzBundle\Form;

use AppBundle\Entity\Klant;
use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\KlantType;
use IzBundle\Entity\IzKlant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IzKlantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data'] instanceof IzKlant
            && $options['data']->getKlant() instanceof Klant
            && $options['data']->getKlant()->getId()
        ) {
            $builder->add('klant', DummyChoiceType::class, [
                'dummy_label' => (string) $options['data'],
            ]);
        } else {
            $builder
                ->add('klant', KlantType::class, ['required' => true])
                ->get('klant')
                ->remove('opmerking')
                ->remove('geenPost')
                ->remove('geenEmail')
            ;
        }

        $builder
            ->add('datumAanmelding', AppDateType::class)
            ->add('organisatieAanmelder', null, [
                'required' => false,
            ])
            ->add('naamAanmelder', null, [
                'required' => false,
            ])
            ->add('emailAanmelder', null, [
                'required' => false,
            ])
            ->add('telefoonAanmelder', null, [
                'required' => false,
            ])
            ->add('notitie', AppTextareaType::class, [
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
            'class' => IzKlant::class,
        ]);
    }

    public function getParent()
    {
        return BaseType::class;
    }
}
