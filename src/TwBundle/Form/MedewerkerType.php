<?php

namespace TwBundle\Form;

use AppBundle\Entity\Medewerker;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use TwBundle\Entity\Coordinator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedewerkerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('medewerker')
                    ->innerJoin(Coordinator::class, 'coordinator', 'WITH', 'coordinator.medewerker = medewerker')
                    ->orderBy('medewerker.voornaam')
                ;
            },
            'preset' => true,
//            'class' => Coordinator::class,
            'placeholder' => 'Selecteer een medewerker',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        if ($options['preset']) {
//            $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
//                if (!$event->getData()) {
//                    $event->getForm()->setData($this->medewerker);
//                }
//            });
//        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return \AppBundle\Form\MedewerkerType::class;
    }

    public function getBlockPrefix()
    {
        return 'tw_medewerker';
    }
}
