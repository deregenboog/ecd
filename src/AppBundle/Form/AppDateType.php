<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // convert two digit year to four digit year
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $matches = [];
            if (preg_match('/^(\d{2}-\d{2}-)(\d{2})$/', $event->getData(), $matches)) {
                $event->setData($matches[1].(2000 + $matches[2]));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy',
            'attr' => ['placeholder' => 'dd-mm-jjjj'],
            'attr'=> ['readonly'=>false],
            'html5' => false,
        ]);
    }

    public function getParent(): ?string
    {
        return DateType::class;
    }

    public static function getQuarterStart()
    {
        $today = new \DateTime('today');
        $m = $today->format('n');
        switch ($m) {
            case 1:
            case 2:
            case 3:
                return new \DateTime('first day of this year');
            case 4:
            case 5:
            case 6:
                return new \DateTime('first day of april this year');
            case 7:
            case 8:
            case 9:
                return new \DateTime('first day of july this year');
            case 10:
            case 11:
            case 12:
                return new \DateTime('first day of september this year');
        }
    }

    public static function getLastFullQuarterEnd()
    {
        $today = new \DateTime('today');
        $m = $today->format('n');
        switch ($m) {
            case 1:
            case 2:
            case 3:
                return new \DateTime('last day of last year');
            case 4:
            case 5:
            case 6:
                return new \DateTime('last day of march this year');
            case 7:
            case 8:
            case 9:
                return new \DateTime('last day of june this year');
            case 10:
            case 11:
            case 12:
                return new \DateTime('last day of september this year');
        }
    }
}
