<?php

namespace GaBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use GaBundle\Entity\Lidmaatschap;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LidmaatschapReopenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $lidmaatschap Lidmaatschap */
        $lidmaatschap = $options['data'];

        $builder
            ->add('dossier', DummyChoiceType::class, [
                'dummy_label' => (string) $lidmaatschap->getDossier(),
            ])
            ->add('groep', DummyChoiceType::class, [
                'dummy_label' => (string) $lidmaatschap->getGroep(),
            ])
            ->add('startdatum', AppDateType::class, ['disabled' => true])
            ->add('communicatieEmail')
            ->add('communicatiePost')
            ->add('communicatieTelefoon')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lidmaatschap::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
