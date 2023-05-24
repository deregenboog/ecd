<?php

namespace ScipBundle\Form;

use AppBundle\Form\AppTimeType;
use AppBundle\Form\BaseType;
use ScipBundle\Entity\Beschikbaarheid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BeschikbaarheidType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $days = ['maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag', 'zondag'];
        foreach ($days as $day) {
            $builder
                ->add($day.'Van', AppTimeType::class, [
                    'required' => false,
                ])
                ->add($day.'Tot', AppTimeType::class, [
                    'required' => false,
                ])
            ;
        }

        $builder->add('submit', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Beschikbaarheid::class,
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
