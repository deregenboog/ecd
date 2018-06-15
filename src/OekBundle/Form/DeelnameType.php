<?php

namespace OekBundle\Form;

use AppBundle\Form\BaseType;
use Doctrine\ORM\EntityRepository;
use OekBundle\Entity\Deelname;
use OekBundle\Entity\DeelnameStatus;
use OekBundle\Entity\Deelnemer;
use OekBundle\Entity\Training;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeelnameType extends AbstractType
{
    const MODE_ADD = 'add';
    const MODE_EDIT = 'edit';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (self::MODE_ADD === $options['mode']) {
            $this->buildAddForm($builder, $options);
        } elseif (self::MODE_EDIT === $options['mode']) {
            $this->buildEditForm($builder, $options);
        }

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    private function buildAddForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data']->getTraining() instanceof Training) {
            $builder->add('training', EntityType::class, [
                'label' => 'Training',
                'class' => Training::class,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('training')
                        ->where('training = :training')
                        ->setParameter('training', $options['data']->getTraining())
                    ;
                },
            ]);
        } elseif ($options['data']->getDeelnemer() instanceof Deelnemer) {
            $builder->add('training', EntityType::class, [
                'label' => 'Training',
                'placeholder' => 'Selecteer een training',
                'class' => Training::class,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('training')
                        ->where('training.einddatum >= :now')
                        ->setParameter('now', new \DateTime('today'))
                        ->orderBy('training.naam', 'ASC')
                    ;
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
                        ->setParameter('deelnemer', $options['data']->getDeelnemer())
                    ;
                },
            ]);
        } elseif ($options['data']->getTraining() instanceof Training) {
            $builder->add('klant', EntityType::class, [
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

                    if (count($options['data']->getTraining()->getDeelnemers()) > 0) {
                        $builder
                            ->where('deelnemer NOT IN (:deelnemers)')
                            ->setParameter('deelnemers', $options['data']->getTraining()->getDeelnemers())
                        ;
                    }

                    return $builder;
                },
            ]);
        }
    }

    private function buildEditForm(FormBuilderInterface $builder, array $options)
    {
        $statuses = DeelnameStatus::getAllStatuses();

        $builder->add('status', ChoiceType::class, [
            'choices' => array_combine($statuses, $statuses),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deelname::class,
            'mode' => self::MODE_ADD,
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
