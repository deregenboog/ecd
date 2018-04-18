<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                        ->leftJoin('hulpvraag.koppeling', 'koppeling')
                        ->innerJoin('izKlant.intake', 'intake')
                        ->innerJoin('izKlant.klant', 'klant')
                        ->andWhere('hulpvraag.startdatum <= :today') // hulpvraag gestart
                        ->andWhere('hulpvraag.einddatum IS NULL OR hulpvraag.einddatum >= :today') // hulpvraag niet afgesloten
                        ->andWhere('reservering.id IS NULL OR :today NOT BETWEEN reservering.startdatum AND reservering.einddatum') // hulpvraag niet gereserveerd
                        ->andWhere('koppeling.id IS NULL') // hulpvraag niet gekoppeld
                        ->andWhere('izKlant.afsluitDatum IS NULL') // klant niet afgesloten
                        ->orderBy('klant.achternaam')
                        ->setParameters([
                            'today' => new \DateTime('today'),
                        ])
                    ;
                },
            ])
            ->add('startdatum', AppDateType::class)
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
