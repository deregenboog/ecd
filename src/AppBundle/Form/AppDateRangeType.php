<?php

namespace AppBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Form\Model\AppDateRangeModel;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class AppDateRangeType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start', AppDateType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'van (dd-mm-jjjj)'],
            ])
            ->add('end', AppDateType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'tot (dd-mm-jjjj)'],
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
}
