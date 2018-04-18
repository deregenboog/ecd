<?php

namespace AppBundle\Form;

use AppBundle\Entity\Zrm;
use Symfony\Component\Form\AbstractType;
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
        $zrm = ($options['data'] instanceof Zrm) ? $options['data'] : Zrm::create();

        foreach ($zrm::getFieldsAndLabels() as $field => $label) {
            $builder->add($field, ZrmItemType::class, [
                'label' => $label,
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
