<?php

namespace IzBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use AppBundle\Form\BaseType;
use AppBundle\Form\AppDateType;
use IzBundle\Entity\Hulpaanbod;
use AppBundle\Form\DummyChoiceType;

class HulpvraagConnectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $hulpvraag = $options['data'];

        if ($hulpvraag instanceof Hulpvraag) {
            $builder->add('hulpvraag', DummyChoiceType::class, [
                'dummy_label' => (string) $hulpvraag,
            ]);
        }

        $builder
            ->add('hulpaanbod', null, [
                'query_builder' => function (EntityRepository $repository) use ($hulpvraag) {
                    return $repository->createQueryBuilder('hulpaanbod')
                        ->select('hulpaanbod, izVrijwilliger, vrijwilliger')
                        ->innerJoin('hulpaanbod.project', 'project', 'WITH', 'project.heeftKoppelingen = true')
                        ->innerJoin('hulpaanbod.izVrijwilliger', 'izVrijwilliger')
                        ->leftJoin('hulpaanbod.reserveringen', 'reservering')
                        ->innerJoin('izVrijwilliger.intake', 'intake')
                        ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
                        ->andWhere('hulpaanbod.startdatum >= :today') // hulpaanbod gestart
                        ->andWhere('hulpaanbod.einddatum IS NULL OR hulpaanbod.einddatum <= :today') // hulpaanbod niet afgesloten
                        ->andWhere('reservering.id IS NULL OR :today NOT BETWEEN reservering.startdatum AND reservering.einddatum') // hulpaanbod niet gereserveerd
                        ->andWhere('hulpaanbod.hulpvraag IS NULL') // hulpaanbod niet gekoppeld
                        ->andWhere('izVrijwilliger.afsluitDatum IS NULL') // vrijwilliger niet afgesloten
                        ->orderBy('vrijwilliger.achternaam')
                        ->setParameters([
                            'today' => new \DateTime('today'),
                        ])
                    ;
                },
            ])
            ->add('koppelingStartdatum', AppDateType::class, [
                'label' => 'Startdatum koppeling',
                'required' => true,
            ])
            ->add('tussenevaluatiedatum', AppDateType::class, [
                'label' => 'Datum tussenevaluatie',
                'required' => true,
            ])
            ->add('eindevaluatiedatum', AppDateType::class, [
                'label' => 'Datum eindevaluatie',
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
            'data_class' => Hulpvraag::class,
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
