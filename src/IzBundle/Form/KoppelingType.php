<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Koppeling;
use IzBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KoppelingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $hulpvraag = $options['data']->getHulpvraag();
        $hulpaanbod = $options['data']->getHulpaanbod();
        assert($hulpvraag || $hulpaanbod);

        if ($hulpvraag) {
            $builder->add('hulpvraag', DummyChoiceType::class, [
                'dummy_label' => (string) $hulpvraag,
            ]);
        } else {
            $builder->add('hulpvraag', null, [
                'query_builder' => function (EntityRepository $repository) use ($hulpaanbod) {
                    return $repository->createQueryBuilder('hulpvraag')
                     ->select('hulpvraag, izKlant, klant')
                    ->innerJoin('hulpvraag.izKlant', 'izKlant')
                    ->innerJoin('izKlant.klant', 'klant')
                    ->leftJoin('hulpvraag.koppeling', 'koppeling')
                    ->where('koppeling.id IS NULL')
                    ->andWhere('hulpvraag.project = :project')
                    ->andWhere('hulpvraag.startdatum >= :today')
                    ->andWhere('hulpvraag.einddatum IS NULL')
                    ->orderBy('klant.achternaam')
                    ->setParameters([
                        'project' => $hulpaanbod->getProject(),
                        'today' => new \DateTime('today'),
                    ]);
                },
            ]);
        }

        if ($hulpaanbod) {
            $builder->add('hulpaanbod', DummyChoiceType::class, [
                'dummy_label' => (string) $hulpaanbod,
            ]);
        } else {
            $builder->add('hulpaanbod', null, [
                'query_builder' => function (EntityRepository $repository) use ($hulpvraag) {
                    return $repository->createQueryBuilder('hulpaanbod')
                    ->select('hulpaanbod, izVrijwilliger, vrijwilliger')
                    ->innerJoin('hulpaanbod.izVrijwilliger', 'izVrijwilliger')
                    ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
                    ->leftJoin('hulpaanbod.koppeling', 'koppeling')
                    ->where('koppeling.id IS NULL')
                    ->andWhere('hulpaanbod.project = :project')
//                     ->andWhere('hulpaanbod.startdatum >= :today')
//                     ->andWhere('hulpaanbod.einddatum IS NULL')
                    ->orderBy('vrijwilliger.achternaam')
                    ->setParameters([
                        'project' => $hulpvraag->getProject(),
//                         'today' => new \DateTime('today'),
                    ]);
                },
            ]);
        }

        $builder
            ->add('startdatum', AppDateType::class, [
                'label' => 'Startdatum koppeling',
                'required' => true,
            ])
            ->add('status', null, [
                'required' => true,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Koppeling::class,
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
