<?php

namespace HsBundle\Form;

use AppBundle\Form\AppDateTimeType;
use AppBundle\Form\BaseType;
use HsBundle\Entity\Memo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemoType extends AbstractType
{
    use MedewerkerTypeTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addMedewerkerType($builder, $options);

        $builder
            ->add('datum', AppDateTimeType::class, ['data' => new \DateTime('now')])
            ->add('onderwerp')
            ->add('memo', TextareaType::class, ['attr' => ['rows' => 20]])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Memo::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
