<?php

namespace GaBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\VrijwilligerType;
use GaBundle\Entity\KlantIntake;
use GaBundle\Entity\VrijwilligerIntake;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VrijwilligerIntakeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $intake KlantIntake */
        $intake = $options['data'];

        if ($intake->getVrijwilliger() && $intake->getVrijwilliger()->getId()) {
            $builder->add('vrijwilliger', DummyChoiceType::class, [
                'dummy_label' => $intake->getVrijwilliger(),
            ]);
        } else {
            $builder->add('vrijwilliger', VrijwilligerType::class);
        }

        $builder
            ->add('intakedatum', AppDateType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VrijwilligerIntake::class,
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
