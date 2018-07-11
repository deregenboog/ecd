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

class VrijwilligerLidmaatschapType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var $lidmaatschap VrijwilligerLidmaatschap */
        $lidmaatschap = $options['data'];

        if ($lidmaatschap->getVrijwilliger()) {
            $builder->add('vrijwilliger', DummyChoiceType::class, [
                'dummy_label' => (string) $lidmaatschap->getVrijwilliger(),
            ]);
        } else {
            $builder->add('vrijwilliger', VrijwilligerSelectType::class, [
                'groep' => $lidmaatschap->getGroep(),
            ]);
        }

        if ($lidmaatschap->getGroep()) {
            $builder->add('groep', DummyChoiceType::class, [
                'dummy_label' => (string) $lidmaatschap->getGroep(),
            ]);
        } else {
            $builder->add('groep', GroepSelectType::class, [
                'vrijwilliger' => $lidmaatschap->getVrijwilliger(),
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
