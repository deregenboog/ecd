<?php

namespace AppBundle\Form;

use AppBundle\Entity\Zrm;
use AppBundle\Entity\ZrmV2;
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
        if (isset($options['data']) && !$options['data'] instanceof Zrm) {
            return;
        }

        if (isset($options['data'])) {
            $options['data_class'] = get_class($options['data']);
        }

        $fieldsAndLables = call_user_func([$options['data_class'], 'getFieldsAndLabels']);
        foreach ($fieldsAndLables as $field => $label) {
            $builder->add($field, ZrmItemType::class, ['label' => $label]);
        }

        if (!isset($options['request_module']) &&
            !(isset($options['data']) && $options['data']->getRequestModule())
        ) {
            $builder->add('requestModule', ChoiceType::class, [
                'required' => true,
                'label' => 'Module',
                'placeholder' => 'Selecteer eem module',
                'choices' => [
//                     'GroepsactiviteitenIntake' => 'GroepsactiviteitenIntake',
//                     'Hi5' => 'Hi5',
                    'Intake' => 'Intake',
                    'IzIntake' => 'IzIntake',
                    'Klant' => 'Klant',
                    'MaatschappelijkWerk' => 'MaatschappelijkWerk',
                ],
            ]);
        }

        $builder->add('medewerker', MedewerkerType::class);

        $builder->add('submit', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => ZrmV2::class,
            'data_class' => ZrmV2::class,
            'request_module' => null,
        ]);
    }

    public function getParent()
    {
        return BaseType::class;
    }
}
