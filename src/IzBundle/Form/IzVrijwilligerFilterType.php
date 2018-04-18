<?php

namespace IzBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\VrijwilligerFilterType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Deelnemerstatus;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Intake;
use IzBundle\Entity\Koppelingstatus;
use IzBundle\Entity\Project;
use IzBundle\Filter\IzVrijwilligerFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IzVrijwilligerFilterType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('status', $options['enabled_filters'])) {
            $builder->add('status', ChoiceType::class, [
                'required' => false,
                'label' => false,
                'choices' => $this->getStatusChoices(),
            ]);
        }

        if (in_array('datumAanmelding', $options['enabled_filters'])) {
            $builder->add('datumAanmelding', AppDateRangeType::class, [
                'required' => false,
                'label' => false,
            ]);
        }

        if (in_array('afsluitDatum', $options['enabled_filters'])) {
            $builder->add('afsluitDatum', AppDateRangeType::class, [
                'required' => false,
                'label' => false,
            ]);
        }

        if (in_array('openDossiers', $options['enabled_filters'])) {
            $builder->add('openDossiers', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen open dossiers',
            ]);
        }

        if (key_exists('vrijwilliger', $options['enabled_filters'])) {
            $builder->add('vrijwilliger', VrijwilligerFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['vrijwilliger'],
            ]);
        }

        if (in_array('actief', $options['enabled_filters'])) {
            $builder->add('actief', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'Nu actief bij' => IzVrijwilligerFilter::ACTIEF_NU,
                    'Ooit actief bij' => IzVrijwilligerFilter::ACTIEF_OOIT,
                ],
            ]);
        }

        if (in_array('project', $options['enabled_filters'])) {
            $builder->add('project', EntityType::class, [
                'required' => false,
                'label' => 'Project',
                'class' => Project::class,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('project')
                        ->where('project.einddatum IS NULL OR project.einddatum >= :now')
                        ->orderBy('project.naam', 'ASC')
                        ->setParameter('now', new \DateTime());
                },
            ]);
        }

        if (in_array('intakeMedewerker', $options['enabled_filters'])) {
            $builder->add('intakeMedewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'label' => 'Medewerker intake',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(Intake::class, 'intake', 'WITH', 'intake.medewerker = medewerker')
                        ->where('medewerker.actief = :true')
                        ->setParameter('true', true)
                        ->orderBy('medewerker.voornaam', 'ASC')
                    ;
                },
            ]);
        }

        if (in_array('hulpaanbodMedewerker', $options['enabled_filters'])) {
            $builder->add('hulpaanbodMedewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'label' => 'Medewerker(s) hulpaanbod',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(Hulpaanbod::class, 'hulpaanbod', 'WITH', 'hulpaanbod.medewerker = medewerker')
                        ->where('medewerker.actief = :true')
                        ->setParameter('true', true)
                        ->orderBy('medewerker.voornaam', 'ASC')
                    ;
                },
            ]);
        }

        if (in_array('zonderActiefHulpaanbod', $options['enabled_filters'])) {
            $builder->add('zonderActiefHulpaanbod', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen dossiers zonder actief hulpaanbod',
            ]);
        }

        if (in_array('zonderActieveKoppeling', $options['enabled_filters'])) {
            $builder->add('zonderActieveKoppeling', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen dossiers zonder actieve koppeling',
            ]);
        }

        $builder
            ->add('filter', SubmitType::class, ['label' => 'Filteren'])
            ->add('download', SubmitType::class, ['label' => 'Downloaden'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IzVrijwilligerFilter::class,
            'enabled_filters' => [
                'status',
                'datumAanmelding',
                'afsluitDatum',
                'openDossiers',
                'vrijwilliger' => ['voornaam', 'achternaam', 'geboortedatumRange', 'stadsdeel'],
                'actief',
                'project',
                'intakeMedewerker',
                'hulpaanbodMedewerker',
                'zonderActiefHulpaanbod',
                'zonderActieveKoppeling',
                'filter',
                'download',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FilterType::class;
    }

    private function getStatusChoices()
    {
        $choices = ['Kan gekoppeld worden'];

        $deelnemerstatussen = $this->entityManager->getRepository(Deelnemerstatus::class)->findBy(['actief' => true]);
        foreach ($deelnemerstatussen as $status) {
            $choices[] = (string) $status;
        }

        $koppelingstatussen = $this->entityManager->getRepository(Koppelingstatus::class)->findBy(['actief' => true]);
        foreach ($koppelingstatussen as $status) {
            $choices[] = (string) $status;
        }

        sort($choices);

        return array_combine($choices, $choices);
    }
}
