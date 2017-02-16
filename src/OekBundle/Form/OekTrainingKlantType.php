<?php

namespace OekBundle\Form;

use Doctrine\ORM\EntityRepository;
use OekBundle\Entity\OekKlant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use OekBundle\Entity\OekTraining;
use OekBundle\Form\Model\OekTrainingKlantModel;

class OekTrainingKlantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data']->getOekTraining()) {
            $builder->add('oekTraining', EntityType::class, [
                'label' => 'Training',
                'class' => OekTraining::class,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('oekTraining')
                        ->where('oekTraining = :oek_training')
                        ->setParameter('oek_training', $options['data']->getOekTraining())
                    ;
                },
            ]);
        } elseif ($options['data']->getOekKlant()) {
            $builder->add('oekTraining', EntityType::class, [
                'label' => 'Training',
                'placeholder' => 'Selecteer een training',
                'class' => OekTraining::class,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('oekTraining')
                        ->orderBy('oekTraining.naam', 'ASC')
                    ;

                    if (count($options['data']->getOekKlant()->getOekGroepen()) > 0) {
                        $builder
                            ->where('oekTraining NOT IN (:oek_trainingen)')
                            ->setParameter('oek_trainingen', $options['data']->getOekKlant()->getOekTrainingen())
                        ;
                    }

                    return $builder;
                },
            ]);
        }

        if ($options['data']->getOekKlant()) {
            $builder->add('oekKlant', EntityType::class, [
                'label' => 'Deelnemer',
                'class' => OekKlant::class,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('oekKlant')
                        ->where('oekKlant = :oek_klant')
                        ->setParameter('oek_klant', $options['data']->getOekKlant())
                    ;
                },
            ]);
        } elseif ($options['data']->getOekTraining()) {
            $builder->add('oekKlant', EntityType::class, [
                'label' => 'Deelnemer',
                'placeholder' => 'Selecteer een deelnemer',
                'class' => OekKlant::class,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('oekKlant')
                        ->select('oekKlant, klant')
                        ->innerJoin('oekKlant.klant', 'klant')
                        ->orderBy('klant.voornaam')
                        ->addOrderBy('klant.achternaam')
                    ;

                    if (count($options['data']->getOekGroep()->getOekKlanten()) > 0) {
                        $builder
                            ->where('oekKlant NOT IN (:oek_klanten)')
                            ->setParameter('oek_klanten', $options['data']->getOekTraining()->getOekKlanten())
                        ;
                    }

                    return $builder;
                },
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OekTrainingKlantModel::class,
        ]);
    }
}
