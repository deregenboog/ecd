<?php

namespace ClipBundle\Form;

use ClipBundle\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\BaseType;
use Doctrine\ORM\EntityRepository;
use ClipBundle\Entity\Behandelaar;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DocumentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('naam');

        if (!$options['data']->getId()) {
            $builder->add('file', FileType::class, [
                'label' => 'Document',
            ]);
        }

        $builder
            ->add('behandelaar', EntityType::class, [
                'placeholder' => '',
                'label' => 'Medewerker',
                'class' => Behandelaar::class,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $current = $options['data'] ? $options['data']->getBehandelaar() : null;

                    return $repository->createQueryBuilder('behandelaar')
                        ->where('behandelaar.actief = true OR behandelaar = :current')
                        ->setParameter('current', $current)
                        ->orderBy('behandelaar.displayName')
                    ;
                },
            ])
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

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }
}
