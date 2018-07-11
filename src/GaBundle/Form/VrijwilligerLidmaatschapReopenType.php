<?php

namespace GaBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use GaBundle\Entity\Lidmaatschap;
use GaBundle\Entity\VrijwilligerLidmaatschap;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VrijwilligerLidmaatschapReopenType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var $lidmaatschap KlantLidmaatschap */
        $lidmaatschap = $options['data'];

        $builder
            ->add('deelnemer', DummyChoiceType::class, [
                'dummy_label' => (string) $lidmaatschap->getVrijwilliger(),
            ])
            ->add('groep', DummyChoiceType::class, [
                'dummy_label' => (string) $lidmaatschap->getGroep(),
            ])
            ->add('startdatum', AppDateType::class)
            ->add('communicatieEmail')
            ->add('communicatiePost')
            ->add('communicatieTelefoon')
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VrijwilligerLidmaatschap::class,
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
