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

class HulpaanbodConnectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $hulpaanbod = $options['data'];

        if ($hulpaanbod instanceof Hulpaanbod) {
            $builder->add('hulpaanbod', DummyChoiceType::class, [
                'dummy_label' => (string) $hulpaanbod,
            ]);
        }

        $builder
            ->add('hulpvraag', null, [
                'query_builder' => function (EntityRepository $repository) use ($hulpaanbod) {
                    return $repository->createQueryBuilder('hulpvraag')
                        ->select('hulpvraag, izKlant, klant')
                        ->innerJoin('hulpvraag.project', 'project', 'WITH', 'project.heeftKoppelingen = true')
                        ->innerJoin('hulpvraag.izKlant', 'izKlant')
                        ->leftJoin('hulpvraag.reserveringen', 'reservering')
                        ->innerJoin('izKlant.intake', 'intake')
                        ->innerJoin('izKlant.klant', 'klant')
                        ->andWhere('hulpvraag.startdatum >= :today') // hulpvraag gestart
                        ->andWhere('hulpvraag.einddatum IS NULL OR hulpvraag.einddatum <= :today') // hulpvraag niet afgesloten
                        ->andWhere('reservering.id IS NULL OR :today NOT BETWEEN reservering.startdatum AND reservering.einddatum') // hulpvraag niet gereserveerd
                        ->andWhere('hulpvraag.hulpaanbod IS NULL') // hulpvraag niet gekoppeld
                        ->andWhere('izKlant.afsluitDatum IS NULL') // klant niet afgesloten
                        ->orderBy('klant.achternaam')
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
            'data_class' => Hulpaanbod::class,
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
