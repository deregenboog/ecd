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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Medewerker;

class TrajectbegeleiderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $trajectbegeleider = $options['data'];
        if ($this->isNew($trajectbegeleider)) {
            $builder
                ->add('medewerker', MedewerkerType::class, [
                    'placeholder' => '',
                    'required' => false,
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository->createQueryBuilder('medewerker')
                            ->leftJoin(Trajectbegeleider::class, 'trajectbegeleider', 'WITH', 'trajectbegeleider.medewerker = medewerker')
                            ->where('trajectbegeleider.id IS NULL')
                            ->orderBy('medewerker.voornaam')
                        ;
                    },
                ])
                ->add('naam', null, ['required' => false])
            ;
        } elseif ($trajectbegeleider->getMedewerker()) {
            $builder->add('medewerker', EntityType::class, [
                'disabled' => true,
                'class' => Medewerker::class,
                'query_builder' => function (EntityRepository $repository) use ($trajectbegeleider) {
                    return $repository->createQueryBuilder('medewerker')
                        ->innerJoin(Trajectbegeleider::class, 'trajectbegeleider', 'WITH', 'trajectbegeleider.medewerker = medewerker')
                        ->where('trajectbegeleider = :trajectbegeleider')
                        ->setParameter('trajectbegeleider', $trajectbegeleider)
                    ;
                },
            ]);
        } else {
            $builder->add('naam', null, ['required' => true]);
        }

        $builder
            ->add('actief')
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

    private function isNew(Trajectbegeleider $trajectbegeleider = null)
    {
        return is_null($trajectbegeleider) || is_null($trajectbegeleider->getId());
    }
}
