<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use IzBundle\Entity\Intervisiegroep;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntervisiegroepType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $intervisiegroep Intervisiegroep */
        $intervisiegroep = $options['data'];

        $builder
            ->add('naam', null, [
                'required' => true,
            ])
            ->add('startdatum', AppDateType::class, [
                'required' => true,
            ])
            ->add('einddatum', AppDateType::class, [
                'required' => false,
            ])
            ->add('medewerker', MedewerkerType::class, [
                'required' => true,
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
            'class' => Intervisiegroep::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
