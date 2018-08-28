<?php

namespace InloopBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\JaNeeType;
use InloopBundle\Entity\Schorsing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SchorsingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('locaties', LocatieSelectType::class, [
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('datumTot', AppDateType::class, [
                'label' => 'Schorsen t/m',
            ])
            ->add('redenen', RedenSelectType::class, [
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('redenOverig', null, [
                'label' => false,
            ])
            ->add('agressie', JaNeeType::class, [
                'label' => 'Is de agressie gericht tegen een medewerker, stagair of vrijwilliger?',
                'attr' => [
                    'class' => 'agressie agressie_parent',
                ],
            ])
        ;

        foreach (range(1, 4) as $i) {
            $builder->add("agressiedoelwit_$i", AgressieDoelwitType::class, [
                'index' => $i,
                'label' => "Betrokkene $i",
                'attr' => [
                    'class' => 'agressie  agressie_children',
                ],
            ]);
        }

        $builder
            ->add('opmerking')
            ->add('locatiehoofd')
            ->add('bijzonderheden')
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Schorsing::class,
        ]);
    }
}
