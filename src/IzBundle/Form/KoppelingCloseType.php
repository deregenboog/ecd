<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KoppelingCloseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $hulpvraag = $options['data'];
        $hulpaanbod = $hulpvraag->getHulpaanbod();

        if ($hulpvraag instanceof Hulpvraag) {
            $builder->add('hulpvraag', DummyChoiceType::class, [
                'dummy_label' => (string) $hulpvraag,
            ]);
        }

        if ($hulpaanbod instanceof Hulpaanbod) {
            $builder->add('hulpaanbod', DummyChoiceType::class, [
                'dummy_label' => (string) $hulpaanbod,
            ]);
        }

        $builder
            ->add('koppelingEinddatum', AppDateType::class, [
                'label' => 'Einddatum koppeling',
                'required' => true,
                'data' => new \DateTime(),
            ]);
        $builder
            ->add('afsluitredenKoppeling', AfsluitredenKoppelingSelectType::class, [
                'required' => true,
                'current' => $options['data'] ? $options['data']->getAfsluitredenKoppeling() : null,
            ]);
        $builder
            ->add('succesindicatoren', SuccesindicatorSelectType::class, [
                'required' => true,
                'by_reference' => false,
                'current' => $options['data'] ? $options['data']->getSuccesindicatoren() : null,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hulpvraag::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
