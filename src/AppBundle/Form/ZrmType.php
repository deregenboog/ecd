<?php

namespace AppBundle\Form;

use AppBundle\Entity\Zrm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ZrmType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['data'] instanceof Zrm) {
            return;
        }

        foreach ($options['data']::getFieldsAndLabels() as $field => $label) {
            $builder->add($field, ZrmItemType::class, ['label' => $label]);
        }

        if (!$options['data']->getRequestModule()) {
            $builder->add('requestModule', ChoiceType::class, [
                'required' => true,
                'label' => 'Module',
                'placeholder' => 'Selecteer eem module',
                'choices' => [
                    'GroepsactiviteitenIntake' => 'GroepsactiviteitenIntake',
                    'Hi5' => 'Hi5',
                    'Intake' => 'Intake',
                    'IzIntake' => 'IzIntake',
                    'Klant' => 'Klant',
                    'MaatschappelijkWerk' => 'MaatschappelijkWerk',
                ],
            ]);
        }

        $builder->add('submit', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Zrm::class,
        ]);
    }

    public function getParent()
    {
        return BaseType::class;
    }
}
