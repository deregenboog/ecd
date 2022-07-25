<?php

namespace OekraineBundle\Form;

use AppBundle\Entity\Klant;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\KlantType;
use AppBundle\Form\LandSelectType;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\NationaliteitSelectType;
use OekraineBundle\Entity\Bezoeker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BezoekerType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if ($options['data'] instanceof Bezoeker
            && $options['data']->getAppKlant() instanceof Klant
            && $options['data']->getAppKlant()->getId()
        ) {
            $builder->add('appKlant', DummyChoiceType::class, [
                'dummy_label' => (string) $options['data'],
            ]);
        } else {
            $builder->add('appKlant', KlantType::class, ['required' => true]);
        }
        $builder
//            ->add('medewerker', MedewerkerType::class, ['required' => true])
//            ->add('intake',IntakeType::class)

        ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bezoeker::class,
        ]);
    }
}
