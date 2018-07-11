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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use GaBundle\Controller\LidmaatschapafsluitredenenController;
use GaBundle\Entity\LidmaatschapAfsluitreden;

class KlantLidmaatschapCloseType extends AbstractType
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
                'dummy_label' => (string) $lidmaatschap->getKlant(),
            ])
            ->add('groep', DummyChoiceType::class, [
                'dummy_label' => (string) $lidmaatschap->getGroep(),
            ])
            ->add('einddatum', AppDateType::class)
            ->add('afsluitreden', EntityType::class, [
                'class' => LidmaatschapAfsluitreden::::class,
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
