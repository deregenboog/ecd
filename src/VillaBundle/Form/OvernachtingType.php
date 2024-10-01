<?php

namespace VillaBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use VillaBundle\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use VillaBundle\Entity\Overnachting;

class OvernachtingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('datum', AppDateType::class,[
            'attr'=>['readonly'=>true],
        ]);
        $builder->add('opmerking');


        /**
         * With the PRE_SET_DATA event we add the buttons depending on if it is an editted entity,
         * or a newly created. Based on that, the appearance of certain buttons are managed.
         */
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $entity = $event->getData();
            $form = $event->getForm();

            if ($entity && $entity->getId() !== null) {
                $form->add("remove",SubmitType::class, [
                    "label"=>"Verwijderen",
                    "attr"=>[
                        "data-callback"=>"removeEvent"
                    ],
                ]);
            }
            $form->add("submit",SubmitType::class, [
                "label"=>"Opslaan",
                "attr"=>[
                    "data-callback"=>"saveEvent"
                ],
            ]);
        });

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Overnachting::class,
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
