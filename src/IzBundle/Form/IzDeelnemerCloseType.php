<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;
use AppBundle\Form\AppDateType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\IzDeelnemer;

class IzDeelnemerCloseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('afsluitdatum', AppDateType::class, [
                'data' => new \DateTime('today'),
            ])
            ->add('afsluiting', null, [
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('afsluiting')
                        ->where('afsluiting.actief = true')
                        ->orderBy('afsluiting.naam')
                    ;
                },
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => IzDeelnemer::class,
        ]);
    }

    public function getParent()
    {
        return BaseType::class;
    }
}
