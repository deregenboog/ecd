<?php

namespace InloopBundle\Form;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\CallbackTransformer;

class SchorsingDatumTotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('optie', ChoiceType::class, [
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
            ->add('datum', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
            ])
        ;

//         $builder->addEventListener(
//             FormEvents::POST_SUBMIT,
//             function (FormEvent $event) {
//                 $data = $event->getData();
//                 $form = $event->getForm();
// //                 var_dump($form); die;
//                 if ($data['optie'] == -1) {
//                     $form->getParent()->getData()->setDatumTot($data['datum']);
// //                     $form->setData($data['datum']);
//                 } else {
//                     var_dump(''); die;
//                 }
//             }
//         );

        $builder->addModelTransformer(new CallbackTransformer(
            function ($tagsAsArray) {
                var_dump($tagsAsArray); die;
                // transform the array to a string
                return implode(', ', $tagsAsArray);
            },
            function ($tagsAsString) {
                var_dump($tagsAsString); die;
                // transform the string back to an array
                return explode(', ', $tagsAsString);
            }
        ));

//         $builder->addModelTransformer(DataTransformerInterface $modelTransformer) {
//             var_dump($mode); die;
//         }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//             'compound' => true,
        ]);
    }
}
