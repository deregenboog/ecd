<?php

namespace VillaBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use VillaBundle\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use VillaBundle\Entity\Overnachting;

class OvernachtingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('datum', AppDateType::class,[
            'attr'=>['readonly'=>true],
        ]);
        $builder->add('opmerking');

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Overnachting::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
