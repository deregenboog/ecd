<?php

namespace MwBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseSelectType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\BinnenViaOptieKlant;
use MwBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AanmeldingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datum', AppDateType::class)
            ->add('medewerker', MedewerkerType::class)
            ->add('project', BaseSelectType::class, [
                'class' => Project::class,
                'disabled' => BaseType::MODE_ADD != $options['mode'],
            ])
            ->add('binnenVia', BaseSelectType::class, [
                'class' => BinnenViaOptieKlant::class,
                'disabled' => BaseType::MODE_ADD != $options['mode'],
            ])
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
            'data_class' => Aanmelding::class,
            'class' => Aanmelding::class,
            'mode' => BaseType::MODE_ADD,
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
