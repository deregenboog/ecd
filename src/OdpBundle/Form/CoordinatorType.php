<?php

namespace OdpBundle\Form;

use AppBundle\Form\BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use OdpBundle\Entity\Coordinator;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;

class CoordinatorType extends BaseType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medewerker', MedewerkerType::class, [
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('medewerker')
                        ->leftJoin(Coordinator::class, 'coordinator', 'WITH', 'coordinator.medewerker = medewerker')
                        ->where('coordinator.id IS NULL')
                        ->orderBy('medewerker.voornaam');
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
            'data_class' => Coordinator::class,
        ]);
    }
}
