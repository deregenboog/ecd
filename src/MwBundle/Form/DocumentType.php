<?php

namespace MwBundle\Form;

use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use MwBundle\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Count;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('documenten', FileType::class, [
            'label' => 'Documenten (selecteer meerdere bestanden)',
            'multiple' => true,
            'mapped' => false,
            'required' => true,
            'constraints' => [
                new Count([
                    'max' => $options['max_files'],
                    'maxMessage' => 'U kunt maximaal {{ limit }} bestand(en) tegelijk uploaden.',
                ])
            ],
        ])
            ->add('medewerker', MedewerkerType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class, // MwBundle\Entity\Document
            'max_files' => 1, // Default value if not overridden by the controller
        ]);

        $resolver->setAllowedTypes('max_files', 'int');
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
