<?php

namespace AppBundle\Form;

use AppBundle\Export\GenericExport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DownloadVrijwilligersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = [];
        foreach ($options['download_services'] as $i => $service) {
            /* @var int $i */
            /* @var GenericExport $service */
            $choices[$service->getFriendlyName()] = $i;
        }

        $builder
            ->add('onderdeel', ChoiceType::class, [
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'choices' => $choices,
            ])
            ->add('download', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'download_services' => [],
        ]);
    }
}
