<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseType extends AbstractType
{
    public const MODE_ADD = 'add';
    public const MODE_EDIT = 'edit';
    public const MODE_CLOSE = 'close';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $this->removeSubmitTypes($event->getForm());
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'medewerker' => null,
        ]);
    }

    private function removeSubmitTypes(FormInterface $form)
    {
        foreach ($form->all() as $name => $child) {
            if ($form->getParent() !== null && $child->getConfig()->getType()->getInnerType() instanceof SubmitType) {
                $form->remove($name);
            } else {
                $this->removeSubmitTypes($child);
            }
        }
    }
}
