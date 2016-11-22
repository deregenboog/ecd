<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\IzHulpvraag;

class IzHulpvraagFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('izKlant', IzKlantFilterType::class)
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
            ->add('reset', ResetType::class, ['label' => 'Filter wissen'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IzHulpvraag::class,
            'data' => null,
            'method' => 'GET',
        ]);
    }
}
