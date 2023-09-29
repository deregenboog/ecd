<?php

namespace MwBundle\Form;

use AppBundle\Form\BaseType;
use MwBundle\Entity\AfsluitredenKlant;
use MwBundle\Entity\Resultaat;
use MwBundle\Service\ResultaatDao;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultaatType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('actief')
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Resultaat::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
