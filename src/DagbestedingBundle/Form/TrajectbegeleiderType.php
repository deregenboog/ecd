<?php

namespace DagbestedingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use DagbestedingBundle\Entity\Trajectbegeleider;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\BaseType;

class TrajectbegeleiderType extends AbstractType
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
                        ->leftJoin(Trajectbegeleider::class, 'trajectbegeleider', 'WITH', 'trajectbegeleider.medewerker = medewerker')
                        ->where('trajectbegeleider.id IS NULL')
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
            'data_class' => Trajectbegeleider::class,
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
