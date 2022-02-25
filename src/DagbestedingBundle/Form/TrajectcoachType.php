<?php

namespace DagbestedingBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use DagbestedingBundle\Entity\Trajectcoach;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrajectcoachType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $trajectcoach = $options['data'];
        if ($this->isNew($trajectcoach)) {
            $builder
                ->add('medewerker', MedewerkerType::class, [
                    'placeholder' => '',
                    'required' => false,
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository->createQueryBuilder('medewerker')
                            ->leftJoin(Trajectcoach::class, 'trajectcoach', 'WITH', 'trajectcoach.medewerker = medewerker')
                            ->where('trajectcoach.id IS NULL')
                            ->orderBy('medewerker.voornaam')
                        ;
                    },
                ])
                ->add('naam', null, ['required' => false])
            ;
        } elseif ($trajectcoach->getMedewerker()) {
            $builder->add('medewerker', EntityType::class, [
                'disabled' => true,
                'class' => Medewerker::class,
                'query_builder' => function (EntityRepository $repository) use ($trajectcoach) {
                    return $repository->createQueryBuilder('medewerker')
                        ->innerJoin(Trajectcoach::class, 'trajectcoach', 'WITH', 'trajectcoach.medewerker = medewerker')
                        ->where('trajectcoach = :trajectcoach')
                        ->setParameter('trajectcoach', $trajectcoach)
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
            'data_class' => Trajectcoach::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }

    private function isNew(Trajectcoach $trajectcoach = null)
    {
        return is_null($trajectcoach) || is_null($trajectcoach->getId());
    }
}
