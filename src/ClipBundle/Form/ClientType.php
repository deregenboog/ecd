<?php

namespace ClipBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\KlantType;
use AppBundle\Form\BaseType;
use ClipBundle\Entity\Client;
use Doctrine\ORM\EntityRepository;
use ClipBundle\Entity\Behandelaar;

class ClientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('aanmelddatum', AppDateType::class);

        if ($options['data']->getKlant()->getId()) {
            $builder->add('medewerker', MedewerkerType::class, [
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $current = $options['data'] ? $options['data']->getMedewerker() : null;

                    return $repository->createQueryBuilder('medewerker')
                        ->leftJoin(Behandelaar::class, 'behandelaar', 'WITH', 'behandelaar.medewerker = medewerker')
                        ->where('behandelaar.actief = true OR medewerker = :current')
                        ->setParameter('current', $current)
                        ->orderBy('medewerker.voornaam')
                    ;
                },
            ]);
        } else {
            $builder
                ->add('klant', KlantType::class)
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    $event->getData()->setMedewerker($event->getData()->getKlant()->getMedewerker());
                })
            ;
        }

        $builder
            ->add('viacategorie', null, [
                'label' => 'Hoe bekend',
                'placeholder' => '',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) use ($options) {
                    $current = $options['data'] ? $options['data']->getViacategorie() : null;

                    return $repository->createQueryBuilder('viacategorie')
                        ->where('viacategorie.actief = true OR viacategorie = :current')
                        ->setParameter('current', $current)
                    ;
                },
            ])
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
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
