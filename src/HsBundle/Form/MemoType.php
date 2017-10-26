<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HsBundle\Entity\Memo;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use AppBundle\Form\AppDateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;

class MemoType extends AbstractType
{
    use MedewerkerTypeTrait;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addMedewerkerType($builder, $options);

        $builder
            ->add('datum', AppDateTimeType::class, ['data' => new \DateTime('now')])
            ->add('memo', TextareaType::class, ['attr' => ['cols' => 80, 'rows' => 20]])
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
