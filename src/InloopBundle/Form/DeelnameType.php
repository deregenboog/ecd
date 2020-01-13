<?php

namespace InloopBundle\Form;

use AppBundle\Form\AppDateTimeType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use InloopBundle\Entity\Deelname;
use InloopBundle\Entity\Training;
use InloopBundle\Entity\Vrijwilliger;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeelnameType extends AbstractType
{
    const MODE_ADD = 'add';
    const MODE_EDIT = 'edit';


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datum', AppDateTimeType::class, ['data' => new \DateTime('now')])
            ->add('training', EntityType::class,['class'=> Training::class])
            ->add('submit', SubmitType::class)
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function OLDbuildForm(FormBuilderInterface $builder, array $options)
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
        /* @var $deelname Deelname */
        $deelname = $options['data'];

        if ($deelname->getTraining() instanceof Training) {
            $builder->add('training', DummyChoiceType::class, [
                'label' => 'Training',
                'dummy_label' => (string) $deelname->getTraining(),
            ]);
        } elseif ($deelname->getVrijwilliger() instanceof Vrijwilliger) {
            $builder->add('training', EntityType::class, [
                'label' => 'Training',
                'placeholder' => 'Selecteer een training',
                'class' => Training::class,
                'query_builder' => function (EntityRepository $repository) use ($deelname) {
                    return $repository->createQueryBuilder('training')
//                        ->where('training.einddatum >= :now')
//                        ->setParameter('now', new \DateTime('today'))
                        ->orderBy('training.naam', 'ASC')
                    ;
                },
            ]);
        }

        if ($deelname->getVrijwilliger() instanceof Vrijwilliger) {
            $builder->add('vrijwilliger', DummyChoiceType::class, [
                'label' => 'Vrijwilliger',
                'dummy_label' => (string) $deelname->getVrijwilliger(),
            ]);
        } elseif ($deelname->getTraining() instanceof Training) {
            $builder->add('deelnemer', EntityType::class, [
                'label' => 'Deelnemer',
                'placeholder' => 'Selecteer een deelnemer',
                'class' => Deelnemer::class,
                'query_builder' => function (EntityRepository $repository) use ($deelname) {
                    $builder = $repository->createQueryBuilder('deelnemer')
                        ->select('deelnemer, klant')
                        ->innerJoin('deelnemer.klant', 'klant')
                        ->orderBy('klant.achternaam')
                        ->addOrderBy('klant.voornaam')
                    ;

                    if (count($deelname->getTraining()->getDeelnemers()) > 0) {
                        $builder
                            ->where('deelnemer NOT IN (:deelnemers)')
                            ->setParameter('deelnemers', $deelname->getTraining()->getDeelnemers())
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
