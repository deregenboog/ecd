<?php

namespace BuurtboerderijBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use BuurtboerderijBundle\Entity\Vrijwilliger;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VrijwilligerCloseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('afsluitdatum', AppDateType::class, [
                'required' => true,
            ])
            ->add('afsluitreden', null, [
                'placeholder' => '',
                'required' => true,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('afsluitreden')
                        ->where('afsluitreden.actief = true')
                        ->orWhere('afsluitreden = :current')
                        ->orderBy('afsluitreden.naam')
                        ->setParameter('current', $options['data'] ? $options['data']->getAfsluitreden() : null)
                    ;
                },
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vrijwilliger::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
