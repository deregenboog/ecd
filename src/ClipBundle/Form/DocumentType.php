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
            ->add('medewerker', MedewerkerType::class, [
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $current = $options['data'] ? $options['data']->getMedewerker() : null;

                    return $repository->createQueryBuilder('medewerker')
                        ->leftJoin(Behandelaar::class, 'behandelaar', 'WITH', 'behandelaar.medewerker = medewerker')
                        ->where('behandelaar.actief = true OR medewerker = :current')
                        ->setParameter('current', $current)
                        ->orderBy('medewerker.voornaam')
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
