<?php

namespace ErOpUitBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use ErOpUitBundle\Entity\Uitschrijfreden;
use ErOpUitBundle\Entity\Vrijwilliger;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VrijwilligerCloseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $vrijwilliger Vrijwilliger */
        $vrijwilliger = $options['data'];

        $builder
            ->add('dossier', DummyChoiceType::class, [
                'dummy_label' => (string) $vrijwilliger,
            ])
            ->add('uitschrijfdatum', AppDateType::class)
            ->add('uitschrijfreden', EntityType::class, [
                'class' => Uitschrijfreden::class,
                'placeholder' => '',
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vrijwilliger::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
