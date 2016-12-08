<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EmailMessageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('from', null, [
                'required' => true,
                'label' => 'Afzender',
                'attr' => ['placeholder' => 'Afzender'],
            ])
            ->add('to', null, [
                'required' => true,
                'label' => 'Ontvangers',
                'attr' => ['placeholder' => 'Ontvanger(s)'],
            ])
            ->add('cc', null, [
                'required' => false,
                'label' => 'CC',
            ])
            ->add('bcc', null, [
                'required' => false,
                'label' => 'BCC',
            ])
            ->add('subject', null, [
                'required' => true,
                'attr' => ['placeholder' => 'Onderwerp'],
            ])
            ->add('text', TextareaType::class, [
                'required' => true,
                'attr' => ['placeholder' => 'Bericht'],
            ])
            ->add('file1', FileType::class, [
                'required' => false,
                'label' => 'Bestand 1',
            ])
            ->add('file2', FileType::class, [
                'required' => false,
                'label' => 'Bestand 2',
            ])
            ->add('file3', FileType::class, [
                'required' => false,
                'label' => 'Bestand 3',
            ])
        ;
    }
}
