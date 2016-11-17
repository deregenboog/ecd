<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\IzHulpaanbod;

class IzHulpaanbodFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('izVrijwilliger', IzVrijwilligerFilterType::class)
            ->add('izProject', null, [
                'required' => false,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('izProject')->orderBy('izProject.naam', 'ASC');
                },
            ])
            ->add('medewerker', null, [
                'required' => false,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')->orderBy('medewerker.achternaam', 'ASC');
                },
            ])
            ->add('submit', SubmitType::class, ['label' => 'Filteren'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IzHulpaanbod::class,
            'data' => null,
            'method' => 'GET',
        ]);
    }
}
