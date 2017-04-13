<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HsBundle\Entity\Klus;
use AppBundle\Form\AppDateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use HsBundle\Entity\Memo;

class KlusType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('klant', null, [
                'required' => false,
                'placeholder' => 'Homeservice',
            ])
            ->add('startdatum', AppDateType::class, ['data' => new \DateTime('today')])
            ->add('datum', AppDateType::class, ['data' => new \DateTime('today')])
            ->add('einddatum', AppDateType::class, ['data' => new \DateTime('today')])
            ->add('activiteit')
            ->add('medewerker')
        ;

        if (!$options['data']->getId()) {
            $builder
                ->add('dienstverleners')
                ->add('vrijwilligers')
                ->add('memo', TextareaType::class, [
                    'mapped' => false,
                    'attr' => ['rows' => 10, 'cols' => 80],
                ])
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    $form = $event->getForm();
                    $klus = $event->getData();
                    if (!$form->get('memo')->isEmpty()) {
                        $memo = new Memo($klus->getMedewerker());
                        $memo->setMemo($form->get('memo')->getData());
                        $klus->addMemo($memo);
                    }
                })
            ;
        }

        $builder
            ->add('onHold')
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Klus::class,
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
