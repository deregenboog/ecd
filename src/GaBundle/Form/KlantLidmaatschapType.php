<?php

namespace GaBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use GaBundle\Entity\KlantLidmaatschap;
use GaBundle\Entity\Lidmaatschap;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlantLidmaatschapType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var $lidmaatschap KlantLidmaatschap */
        $lidmaatschap = $options['data'];

        if ($lidmaatschap->getKlant()) {
            $builder->add('klant', DummyChoiceType::class, [
                'dummy_label' => (string) $lidmaatschap->getKlant(),
            ]);
        } else {
            $builder->add('klant', KlantSelectType::class, [
                'groep' => $lidmaatschap->getGroep(),
            ]);
        }

        if ($lidmaatschap->getGroep()) {
            $builder->add('groep', DummyChoiceType::class, [
                'dummy_label' => (string) $lidmaatschap->getGroep(),
            ]);
        } else {
            $builder->add('groep', GroepSelectType::class, [
                'klant' => $lidmaatschap->getKlant(),
            ]);
        }

        $builder
            ->add('startdatum', AppDateType::class)
            ->add('communicatieEmail')
            ->add('communicatiePost')
            ->add('communicatieTelefoon')
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => KlantLidmaatschap::class,
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
