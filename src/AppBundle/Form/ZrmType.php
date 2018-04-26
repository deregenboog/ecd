<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;
use AppBundle\Form\AppDateType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\MedewerkerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Entity\Zrm;

class ZrmType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['data']::getFieldsAndLabels() as $field => $label) {
            $builder->add($field, ZrmItemType::class, ['label' => $label]);
        }

        if (!isset($options['data']) || !$options['data']->getRequestModule()) {
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
