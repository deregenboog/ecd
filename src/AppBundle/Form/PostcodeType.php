<?php

namespace AppBundle\Form;

use AppBundle\Entity\Postcode;
use AppBundle\Util\PostcodeFormatter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostcodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('postcode')
            ->add('stadsdeel', StadsdeelSelectType::class, [
                'required' => true,
            ])
            ->add('postcodegebied', PostcodegebiedSelectType::class)
            ->add('submit', SubmitType::class)
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                if ($data['postcode']) {
                    $data['postcode'] = PostcodeFormatter::format($data['postcode']);
                    $event->setData($data);
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Postcode::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
