<?php

namespace TwBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwBundle\Entity\SuperDocument;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('naam');

        if (!$options['data']->getId()) {
            $builder->add('file', FileType::class, [
                'label' => 'Document',
            ]);
        }

        $builder
            ->add('datum', AppDateType::class)
            ->add('medewerker', MedewerkerType::class)
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SuperDocument::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
