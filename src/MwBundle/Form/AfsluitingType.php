<?php

namespace MwBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\LandSelectType;
use Doctrine\ORM\EntityRepository;
use InloopBundle\Form\LocatieSelectType;
use MwBundle\Entity\Afsluiting;
use MwBundle\Entity\AfsluitredenKlant;
use MwBundle\Entity\Resultaat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
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
                'choice_attr' => function (AfsluitredenKlant $reden, $key, $value) {
                    // adds a class land_0 or land_1
                    return ['class' => 'land_'.(int) $reden->isLand()];
                },
            ])
            ->add('locatie', LocatieSelectType::class, [
            'required' => true,
            ])
            ->add('resultaat', EntityType::class, [
                'class' => Resultaat::class,
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'placeholder' => 'Resulta(a)t(en)',
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('resultaat')
                        ->where('resultaat.actief = true')
                        ->orderBy('resultaat.naam')
                    ;
                },
            ])
            ->add('land', LandSelectType::class, [
                'required' => false,
                'placeholder' => '',
                'label' => 'Land van bestemming',
            ])
            ->add('datumRepatriering', AppDateType::class, ['required' => false])
            ->add('kosten', MoneyType::class, ['required' => false])
            ->add('zachteLanding')
            ->add('toelichting')
            ->add('inloopSluiten', CheckboxType::class, [
                'label' => 'Inloopdossier (indien aanwezig) ook sluiten?',
                'required' => false,
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            if ($form->get('reden')->getData()->isLand()) {
                if (!$form->get('land')->getData()) {
                    $form->get('land')->addError(new FormError('Selecteer een land'));
                }
                if (!$form->get('kosten')->getData()) {
                    $form->get('kosten')->addError(new FormError('Voer de kosten in'));
                }
                if (!$form->get('datumRepatriering')->getData()) {
                    $form->get('datumRepatriering')->addError(new FormError('Voer de datum in'));
                }
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Afsluiting::class,
            'allow_extra_fields' => true,
            'mode' => BaseType::MODE_ADD,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
