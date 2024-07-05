<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulpaanbod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HulpaanbodCloseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('einddatum', AppDateType::class, [
                'label' => 'Afsluitdatum',
                'required' => true,
                'data' => new \DateTime('today'),
            ])
            ->add('eindeVraagAanbod', null, [
                'label' => 'Afsluitreden',
                'required' => true,
                'placeholder' => '',
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('einde')
                        ->orderBy('einde.naam')
                    ;
                },
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hulpaanbod::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
