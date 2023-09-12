<?php

namespace MwBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseSelectType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use Doctrine\ORM\Mapping\Entity;
use InloopBundle\Form\LocatieSelectType;
use InloopBundle\Form\LocatieTypeSelectType;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add("locatie",LocatieSelectType::class,[
                'locatietypes'=>'Maatschappelijk Werk'
            ])
            ->add('project', BaseSelectType::class, [
                'class' => Project::class,
                'disabled' => $options['mode'] != BaseType::MODE_ADD,
                'required'=>true,
            ])
            ->add('binnenViaOptieKlant', BinnenViaOptieKlantSelectType::class)
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
            // 'allow_extra_fields' => true,
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
