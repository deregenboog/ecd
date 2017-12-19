<?php

namespace ClipBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use ClipBundle\Entity\Contactmoment;
use ClipBundle\Entity\Vraag;
use ClipBundle\Entity\Behandelaar;
use ClipBundle\Entity\Hulpvrager;
use ClipBundle\Entity\Leeftijdscategorie;
use ClipBundle\Entity\Communicatiekanaal;
use ClipBundle\Entity\Viacategorie;
use ClipBundle\Entity\Client;

class VragenType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('soort')
            ->add('soorten', VraagsoortSelectType::class, [
                'required' => true,
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VragenModel::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return VraagType::class;
    }
}
