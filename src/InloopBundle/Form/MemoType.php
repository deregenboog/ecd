<?php

namespace InloopBundle\Form;

use AppBundle\Form\AppDateTimeType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use InloopBundle\Entity\Memo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datum', AppDateTimeType::class, ['data' => new \DateTime('now')])
            ->add('medewerker', MedewerkerType::class)
            ->add('onderwerp')
            ->add('memo', TextareaType::class, ['attr' => ['rows' => 20]])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Memo::class,
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
