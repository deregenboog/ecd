<?php

namespace GaBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use GaBundle\Entity\Dossier;
use GaBundle\Entity\DossierAfsluitreden;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DossierCloseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $dossier Dossier */
        $dossier = $options['data'];

        $builder
            ->add('afsluitdatum', AppDateType::class)
            ->add('afsluitreden', EntityType::class, [
                'class' => DossierAfsluitreden::class,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dossier::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
