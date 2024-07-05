<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppDateTimeType extends AbstractType
{
    /**
     * Replaces the existing date and time field with app specific configuration.
     *
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = array_merge($builder->get('date')->getOptions(), [
            'attr' => ['placeholder' => 'dd-mm-jjjj'],
        ]);
        $builder->add('date', AppDateType::class, $options);

        $options = array_merge($builder->get('time')->getOptions(), [
            'attr' => ['placeholder' => 'uu:mm'],
        ]);
        $builder->add('time', AppTimeType::class, $options);

        // convert two digit year to four digit year
        $builder->get('date')->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $matches = [];
            if (preg_match('/^(\d{2}-\d{2}-)(\d{2})$/', $event->getData(), $matches)) {
                $event->setData($matches[1].(2000 + $matches[2]));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Datum en tijd',
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'date_format' => 'dd-MM-yyyy',
            'html5' => false,
        ]);
    }

    public function getParent(): ?string
    {
        return DateTimeType::class;
    }
}
