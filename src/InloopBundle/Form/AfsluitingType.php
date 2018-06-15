<?php

namespace InloopBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use Doctrine\ORM\EntityRepository;
use InloopBundle\Entity\Afsluiting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AfsluitingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datum', AppDateType::class)
            ->add('reden', null, [
                'required' => true,
                'placeholder' => 'Selecteer een reden',
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('reden')
                        ->where('reden.actief = true')
                        ->orderBy('reden.gewicht, reden.naam')
                    ;
                },
            ])
        ;

        $builder->get('reden')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            if ($form->getData() && $form->getData()->isLand()) {
                $form->getParent()->add('land', null, [
                    'required' => true,
                    'placeholder' => '',
                    'label' => 'Land van bestemming',
                ]);
            }
        });

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            if ($form->has('land') && !$form->get('land')->getData()) {
                $form->get('land')->addError(new FormError('Selecteer een land'));
            }
        });

        $builder
            ->add('toelichting')
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Afsluiting::class,
            'allow_extra_fields' => true,
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
