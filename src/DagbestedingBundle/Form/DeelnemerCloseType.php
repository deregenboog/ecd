<?php

namespace DagbestedingBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use DagbestedingBundle\Entity\Deelnemer;
use DagbestedingBundle\Entity\Deelnemerafsluiting;
use DagbestedingBundle\Entity\Trajectafsluiting;
use IzBundle\Form\AfsluitingSelectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeelnemerCloseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('afsluitdatum', AppDateType::class, ['data' => new \DateTime()])
            ->add('afsluiting', null, [
                'class' => Deelnemerafsluiting::class,
                'label' => 'Reden afsluiting',
                'required' => true,
                'placeholder' => 'Selecteer een item',
            ])
        ;
        /// check if there are open trajecten
        if ($builder->getData()->hasOpenTrajecten()) {
            /// This is a custom field for afsluitings of trajects of this deelnemer.
            $builder->add('afsluiting_trajecten', AfsluitingSelectType::class, [
                'class' => Trajectafsluiting::class,
                'label' => 'Reden afsluiting van trajecten',
                'required' => true,
                'placeholder' => 'Selecteer een item',
                'mapped' => false,
            ]);
        }

        $builder->add('submit', SubmitType::class, ['label' => 'Afsluiten']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deelnemer::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
