<?php

namespace OekBundle\Form;

use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
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
    public const MODE_ADD = 'add';
    public const MODE_EDIT = 'edit';

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
        /* @var $deelname Deelname */
        $deelname = $options['data'];

        if ($deelname->getTraining() instanceof Training) {
            $builder->add('training', DummyChoiceType::class, [
                'label' => 'Training',
                'dummy_label' => (string) $deelname->getTraining(),
            ]);
        } elseif ($deelname->getDeelnemer() instanceof Deelnemer) {
            $builder->add('training', EntityType::class, [
                'label' => 'Training',
                'placeholder' => 'Selecteer een training',
                'class' => Training::class,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('training')
                        ->where('training.einddatum >= :now OR (training.einddatum IS NULL AND training.startdatum >= :now)')
                        ->setParameter('now', new \DateTime('today'))
                        ->orderBy('training.naam', 'ASC')
                    ;
                },
            ]);
        }

        if ($deelname->getDeelnemer() instanceof Deelnemer) {
            $builder->add('deelnemer', DummyChoiceType::class, [
                'label' => 'Deelnemer',
                'dummy_label' => (string) $deelname->getDeelnemer(),
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
                        ->leftJoin('deelnemer.afsluiting', 'afsluiting')
                        ->where('afsluiting IS NULL')
                        ->orderBy('klant.achternaam')
                        ->addOrderBy('klant.voornaam')
                    ;

                    if ((is_array($deelname->getTraining()->getDeelnemers()) || $deelname->getTraining()->getDeelnemers() instanceof \Countable ? count($deelname->getTraining()->getDeelnemers()) : 0) > 0) {
                        $builder
                            ->andWhere('deelnemer NOT IN (:deelnemers)')
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
        ])
            ->add('doorverwezenNaar', null, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deelname::class,
            'mode' => self::MODE_ADD,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
