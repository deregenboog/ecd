<?php

namespace AppBundle\Form;

use AppBundle\Form\Model\AppDateRangeModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppDateRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start', AppDateType::class, [
                'label' => 'Van',
                'attr' => ['placeholder' => 'Van (dd-mm-jjjj)'],
            ])
            ->add('end', AppDateType::class, [
                'label' => 'Tot',
                'attr' => ['placeholder' => 'Tot (dd-mm-jjjj)'],
            ])
        ;

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /* @var AppDateRangeModel $data */
            $data = $event->getData();
            if ($data instanceof AppDateRangeModel
                && $data->getStart() instanceof \DateTime
                && $data->getEnd() instanceof \DateTime
                && $data->getStart() > $data->getEnd()
            ) {
                $tmp = $data->getStart();
                $data->setStart($data->getEnd());
                $data->setEnd($tmp);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AppDateRangeModel::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }
}
