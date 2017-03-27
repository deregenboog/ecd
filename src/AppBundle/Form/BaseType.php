<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class BaseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            foreach ($event->getForm()->all() as $child) {
                $this->removeSubmitTypes($child);
            }
        });
    }

    private function removeSubmitTypes(FormInterface $form)
    {
        foreach ($form->all() as $name => $child) {
            if ($child->getConfig()->getType()->getInnerType() instanceof SubmitType) {
                $form->remove($name);
            } else {
                $this->removeSubmitTypes($child);
            }
        }
    }
}
