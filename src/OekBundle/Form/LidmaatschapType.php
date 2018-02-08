<?php

namespace OekBundle\Form;

use Doctrine\ORM\EntityRepository;
use OekBundle\Entity\Deelnemer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use OekBundle\Entity\Groep;
use OekBundle\Entity\Lidmaatschap;
use AppBundle\Form\BaseType;

class LidmaatschapType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data']->getGroep() instanceof Groep) {
            $builder->add('groep', EntityType::class, [
                'label' => 'Groep',
                'class' => Groep::class,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('groep')
                        ->where('groep = :groep')
                        ->setParameter('groep', $options['data']->getGroep())
                    ;
                },
            ]);
        } elseif ($options['data']->getDeelnemer() instanceof Deelnemer) {
            $builder->add('groep', EntityType::class, [
                'label' => 'Groep',
                'placeholder' => 'Selecteer een groep',
                'class' => Groep::class,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('groep')
                        ->orderBy('groep.naam', 'ASC')
                    ;

                    if (count($options['data']->getDeelnemer()->getGroepen()) > 0) {
                        $builder
                            ->where('groep NOT IN (:groepen)')
                            ->setParameter('groepen', $options['data']->getDeelnemer()->getGroepen())
                        ;
                    }

                    return $builder;
                },
            ]);
        }

        if ($options['data']->getDeelnemer() instanceof Deelnemer) {
            $builder->add('deelnemer', EntityType::class, [
                'label' => 'Deelnemer',
                'class' => Deelnemer::class,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('deelnemer')
                        ->where('deelnemer = :deelnemer')
                        ->setParameter('deelnemer', $options['data']->getdeelnemer())
                    ;
                },
            ]);
        } elseif ($options['data']->getGroep() instanceof Groep) {
            $builder->add('deelnemer', EntityType::class, [
                'label' => 'Deelnemer',
                'placeholder' => 'Selecteer een deelnemer',
                'class' => Deelnemer::class,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('deelnemer')
                        ->select('deelnemer, klant')
                        ->innerJoin('deelnemer.klant', 'klant')
                        ->orderBy('klant.voornaam')
                        ->addOrderBy('klant.achternaam')
                    ;

                    if (count($options['data']->getGroep()->getDeelnemers()) > 0) {
                        $builder
                            ->where('klant NOT IN (:deelnemers)')
                            ->setParameter('deelnemers', $options['data']->getGroep()->getDeelnemers())
                        ;
                    }

                    return $builder;
                },
            ]);
        }

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lidmaatschap::class,
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
