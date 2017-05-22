<?php

namespace DagbestedingBundle\Form;

use AppBundle\Form\AppDateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;
use DagbestedingBundle\Entity\Traject;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use DagbestedingBundle\Entity\Resultaatgebied;

class TrajectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, ['choices' => Traject::TYPES])
            ->add('resultaatgebied', ChoiceType::class, [
                'choices' => Resultaatgebied::TYPES,
                'mapped' => false,
            ])
            ->add('startdatum', AppDateType::class)
            ->add('begeleider')
            ->add('locaties')
            ->add('projecten')
        ;

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $traject = $event->getData();
            $resultaatgebied = new Resultaatgebied($traject);
            $resultaatgebied->setType($event->getForm()->get('resultaatgebied')->getData());
            $traject->addResultaatgebied($resultaatgebied);
        });

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Traject::class,
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
