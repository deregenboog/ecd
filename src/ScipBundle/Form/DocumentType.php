<?php

namespace ScipBundle\Form;

use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use ScipBundle\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('type', ChoiceType::class, [
                'placeholder' => 'Overige documenten',
                'choices' => [
                    Document::TYPE_VOG => Document::TYPE_VOG,
                    Document::TYPE_OVEREENKOMST => Document::TYPE_OVEREENKOMST,
                ],
            ]);

        if (!isset($options['data']) || !$options['data']->getId()) {
            $builder->add('file', FileType::class, [
                'label' => 'Document',
            ]);
        }

        $builder
            ->add('medewerker', MedewerkerType::class, [
            ])
            ->add('submit', SubmitType::class);
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

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }
}
