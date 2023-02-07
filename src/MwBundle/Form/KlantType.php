<?php

namespace MwBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseSelectType;
use AppBundle\Form\BaseType;
use MwBundle\Entity\Klant;
use MwBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('aanmelding', AanmeldingType::class)
            // ->add('datum', AppDateType::class)
            // ->add('project', BaseSelectType::class, [
            //     'class' => Project::class,
            //     'disabled' => $options['mode'] != BaseType::MODE_ADD,
            // ])
            // ->add('binnenVia', BinnenViaOptieKlantSelectType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Klant::class,
            // 'class' => Klant::class,
            'mode' => BaseType::MODE_ADD,
            // 'allow_extra_fields' => true,
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
