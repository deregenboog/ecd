<?php

namespace InloopBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\LandSelectType;
use Doctrine\ORM\EntityRepository;
use InloopBundle\Entity\Afsluiting;
use InloopBundle\Entity\RedenAfsluiting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AfsluitingType extends AbstractType
{
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
                'choice_attr' => function (RedenAfsluiting $reden, $key, $value) {
                    // adds a class land_0 or land_1
                    return ['class' => 'land_'.(int) $reden->isLand()];
                },
            ])
            ->add('land', LandSelectType::class, [
                'required' => false,
                'placeholder' => '',
                'label' => 'Land van bestemming',
            ])
            ->add('mwSluiten', CheckboxType::class, [
                'label' => 'MW dossier (indien aanwezig) ook sluiten?',
                'required' => false,
                'mapped' => false,
            ])
            ->add('toelichting')
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            if ($form->get('reden')->getData()->isLand() && !$form->get('land')->getData()) {
                $form->get('land')->addError(new FormError('Selecteer een land'));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Afsluiting::class,
            'allow_extra_fields' => true,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
