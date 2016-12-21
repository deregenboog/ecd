<?php

namespace InloopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use InloopBundle\Entity\Schorsing;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Form\JaNeeType;
use InloopBundle\Form\SchorsingDatumTotType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SchorsingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//             ->add('locaties', null, ['required' => true, 'expanded' => true])

        ->add('optie', ChoiceType::class, [
            'mapped' => false,
            'label' => false,
            'expanded' => true,
            'choices' => [
                'De rest van de dag (1 dag)' => 0,
                'Vandaag en morgen (2 dagen)' => 1,
                '3 dagen' => 2,
                '5 dagen' => 4,
                'Tot en met datum' => -1,
            ],
        ])
        ->add('datumTot', DateType::class, [
            'mapped' => false,
            'label' => false,
            'widget' => 'single_text',
        ]);

        $builder->get('datumTot')->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
//                         $data = $event->getData();
                $form = $event->getForm();
//                         if ($form->getParent()->get('optie')->getData() < 0) {
//                             $form->getParent()->get('datumTot')->setData(new \DateTime('now'));
                $form->setData(new \DateTime('now'));
//                             var_dump($form->getData()); die;
//                         } else {
//                             var_dump(''); die;
//                         }
            }
        );


//             ->add('datumTot', SchorsingDatumTotType::class)

//             ->add('redenen', null, ['required' => true, 'expanded' => true])
//             ->add('agressie', JaNeeType::class, [
//                 'required' => false,
//             ])
//             ->add('doelwitAgressie1', null, [
//                 'required' => false,
//                 'label' => 'Agressiedoelwit 1',
//             ])
//             ->add('typeDoelwitAgressie1', ChoiceType::class, [
//                 'required' => false,
//                 'label' => false,
//                 'expanded' => true,
//                 'choices' => [
//                     'Medewerker' => Schorsing::DOELWIT_MEDEWERKER,
//                     'Stagiair' => Schorsing::DOELWIT_STAGIAIR,
//                     'Vrijwilliger' => Schorsing::DOELWIT_VRIJWILLIGER,
//                 ],
//             ])
//             ->add('doelwitAgressie2', null, [
//                 'required' => false,
//                 'label' => 'Agressiedoelwit 2',
//             ])
//             ->add('typeDoelwitAgressie2', ChoiceType::class, [
//                 'required' => false,
//                 'label' => false,
//                 'expanded' => true,
//                 'choices' => [
//                     'Medewerker' => Schorsing::DOELWIT_MEDEWERKER,
//                     'Stagiair' => Schorsing::DOELWIT_STAGIAIR,
//                     'Vrijwilliger' => Schorsing::DOELWIT_VRIJWILLIGER,
//                 ],
//             ])
//             ->add('doelwitAgressie3', null, [
//                 'required' => false,
//                 'label' => 'Agressiedoelwit 3',
//             ])
//             ->add('typeDoelwitAgressie3', ChoiceType::class, [
//                 'required' => false,
//                 'label' => false,
//                 'expanded' => true,
//                 'choices' => [
//                     'Medewerker' => Schorsing::DOELWIT_MEDEWERKER,
//                     'Stagiair' => Schorsing::DOELWIT_STAGIAIR,
//                     'Vrijwilliger' => Schorsing::DOELWIT_VRIJWILLIGER,
//                 ],
//             ])
//             ->add('doelwitAgressie4', null, [
//                 'required' => false,
//                 'label' => 'Agressiedoelwit 4',
//             ])
//             ->add('typeDoelwitAgressie4', ChoiceType::class, [
//                 'required' => false,
//                 'label' => false,
//                 'expanded' => true,
//                 'choices' => [
//                     'Medewerker' => Schorsing::DOELWIT_MEDEWERKER,
//                     'Stagiair' => Schorsing::DOELWIT_STAGIAIR,
//                     'Vrijwilliger' => Schorsing::DOELWIT_VRIJWILLIGER,
//                 ],
//             ])
//             ->add('aangifte', JaNeeType::class)
//             ->add('nazorg', JaNeeType::class)
        ;
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
