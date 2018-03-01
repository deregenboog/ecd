<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;
use IzBundle\Entity\BinnengekomenVia;
use IzBundle\Entity\IzEindeVraagAanbod;
use IzBundle\Entity\EindeKoppeling;
use IzBundle\Entity\Afsluiting;

class AfsluitingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('actief')
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Afsluiting::class,
        ]);
    }

    public function getParent()
    {
        return BaseType::class;
    }
}
