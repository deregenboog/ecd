<?php

namespace InloopBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use InloopBundle\Filter\SchorsingFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use InloopBundle\Entity\Schorsing;
use AppBundle\Form\BaseType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\JaNeeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class AgressieDoelwitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $i = $options['index'];

        $builder
            ->add("typeDoelwitAgressie$i", ChoiceType::class, [
                'label' => 'Tegen wie is de agressie gericht?',
                'expanded' => true,
                'choices' => [
                    'medewerker' => 1,
                    'stagiair' => 2,
                    'vrijwilliger' => 3,
                ],
            ])
            ->add("doelwitAgressie$i", null, [
                'label' => 'Naam',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Schorsing::class,
            'inherit_data' => true,
            'index' => 0,
        ]);
    }
}
