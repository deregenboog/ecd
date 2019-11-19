<?php

namespace HsBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use Doctrine\ORM\EntityRepository;
use HsBundle\Entity\Klus;
use HsBundle\Entity\Memo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlusType extends AbstractType
{
    use MedewerkerTypeTrait;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startdatum', AppDateType::class)
            ->add('einddatum', AppDateType::class, [
                'required' => false,
            ])
            ->add('onHold')
            ->add('activiteiten', null, [
                'required' => true,
                'expanded' => true,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('activiteit')
                        ->orderBy('activiteit.naam')
                    ;
                },
            ])
        ;

        $this->addMedewerkerType($builder, $options);

        $builder
            ->add('dienstverleners', null, [
                'by_reference' => false, // force to call adder and remover
                'expanded' => true,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('dienstverlener')
                        ->innerJoin('dienstverlener.klant', 'klant')
                        ->where('dienstverlener.actief = true')
                        ->orderBy('klant.voornaam')
                    ;
                },
            ])
            ->add('vrijwilligers', null, [
                'by_reference' => false, // force to call adder and remover
                'expanded' => true,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('vrijwilliger')
                        ->innerJoin('vrijwilliger.vrijwilliger', 'basisvrijwilliger')
                        ->where('vrijwilliger.actief = true')
                        ->orderBy('basisvrijwilliger.voornaam')
                    ;
                },
            ])
        ;

        if (!$options['data']->getId()) {
            $builder
                ->add('memo', MemoType::class, [
                    'data' => new Memo(),
                    'mapped' => false,
                    'label' => 'Klusinfo',
                ])
                ->get('memo')->remove('medewerker')->remove('datum')
            ;
            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $klus = $event->getData();
                $memo = $event->getForm()->get('memo')->getData();
                $memo->setMedewerker($klus->getMedewerker());
                $klus->addMemo($memo);
            });
        }

        $builder->add('submit', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Klus::class,
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
