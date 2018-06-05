<?php

namespace IzBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use AppBundle\Form\BaseType;
use AppBundle\Form\AppDateType;
use IzBundle\Entity\Hulpvraag;
use AppBundle\Form\DummyChoiceType;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Reservering;
use AppBundle\Form\MedewerkerType;

class ReserveringType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $hulpvraag = $options['data']->getHulpvraag();
        $hulpaanbod = $options['data']->getHulpaanbod();

        if ($hulpvraag instanceof Hulpvraag) {
            $builder->add('hulpvraag', DummyChoiceType::class, [
                'dummy_label' => (string) $hulpvraag,
            ]);
        }

        if ($hulpaanbod instanceof Hulpaanbod) {
            $builder->add('hulpaanbod', DummyChoiceType::class, [
                'dummy_label' => (string) $hulpaanbod,
            ]);
        }

        $builder
            ->add('medewerker', MedewerkerType::class)
            ->add('startdatum', AppDateType::class, [
                'required' => true,
            ])
            ->add('einddatum', AppDateType::class, [
                'required' => true,
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
            'data_class' => Reservering::class,
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
