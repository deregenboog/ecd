<?php

namespace OdpBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\AppTextareaType;
use OdpBundle\Entity\Verslag;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use OdpBundle\Entity\Document;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class DocumentType extends BaseType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('file', FileType::class, [
                'label' => 'Document',
            ])
            ->add('medewerker', MedewerkerType::class)
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
