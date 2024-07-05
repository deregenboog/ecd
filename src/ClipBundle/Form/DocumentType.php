<?php

namespace ClipBundle\Form;

use AppBundle\Form\BaseType;
use ClipBundle\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('naam');

        if (!$options['data']->getId()) {
            $builder->add('file', FileType::class);
        }

        $builder
            ->add('behandelaar', BehandelaarSelectType::class, [
                'current' => $options['data'] ? $options['data']->getBehandelaar() : null,
                'medewerker' => $options['medewerker'],
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
