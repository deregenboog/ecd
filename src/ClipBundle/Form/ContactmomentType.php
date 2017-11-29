<?php

namespace ClipBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use ClipBundle\Entity\Contactmoment;
use Doctrine\ORM\EntityRepository;
use ClipBundle\Entity\Behandelaar;
use AppBundle\Form\AppTextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ContactmomentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('behandelaar', BehandelaarSelectType::class, [
                'medewerker' => $options['medewerker'],
                'current' => $options['data'] ? $options['data']->getBehandelaar() : null,
            ])
            ->add('datum', AppDateType::class)
            ->add('opmerking', AppTextareaType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contactmoment::class,
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
