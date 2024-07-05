<?php

namespace DagbestedingBundle\Form;

use AppBundle\Form\BaseType;
use DagbestedingBundle\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            $builder->add('file', FileType::class, [
                'label' => 'Document',
            ]);
            $builder->add('type', ChoiceType::class, [
                'choices' => Document::TYPES,
            ]);
        }

        $builder
            ->add('medewerker', MedewerkerType::class)
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
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
