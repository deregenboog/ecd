<?php

namespace OekBundle\Form;

use Doctrine\ORM\EntityRepository;
use OekBundle\Entity\OekKlant;
use AppBundle\Form\BaseType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use OekBundle\Entity\OekGroep;
use OekBundle\Entity\OekLidmaatschap;

class OekLidmaatschapType extends BaseType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data']->getOekGroep() instanceof OekGroep) {
            $builder->add('oekGroep', EntityType::class, [
                'label' => 'Groep',
                'class' => OekGroep::class,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('oekGroep')
                        ->where('oekGroep = :oek_groep')
                        ->setParameter('oek_groep', $options['data']->getOekGroep())
                    ;
                },
            ]);
        } elseif ($options['data']->getOekKlant() instanceof OekKlant) {
            $builder->add('oekGroep', EntityType::class, [
                'label' => 'Groep',
                'placeholder' => 'Selecteer een groep',
                'class' => OekGroep::class,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('oekGroep')
                        ->orderBy('oekGroep.naam', 'ASC')
                    ;

                    if (count($options['data']->getOekKlant()->getOekGroepen()) > 0) {
                        $builder
                            ->where('oekGroep NOT IN (:oek_groepen)')
                            ->setParameter('oek_groepen', $options['data']->getOekKlant()->getOekGroepen())
                        ;
                    }

                    return $builder;
                },
            ]);
        }

        if ($options['data']->getOekKlant() instanceof OekKlant) {
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
        } elseif ($options['data']->getOekGroep() instanceof OekGroep) {
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
                            ->setParameter('oek_klanten', $options['data']->getOekGroep()->getOekKlanten())
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
            'data_class' => OekLidmaatschap::class,
        ]);
    }
}
