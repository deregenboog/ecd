<?php

namespace TwBundle\Form;

use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwBundle\Entity\Coordinator;

class CoordinatorType extends AbstractType
{
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Coordinator::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
