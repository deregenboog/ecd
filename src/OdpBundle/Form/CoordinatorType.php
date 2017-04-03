<?php

namespace OdpBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use OdpBundle\Entity\Coordinator;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\BaseType;

class CoordinatorType extends AbstractType
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

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }
}
