<?php

namespace IzBundle\Form;

use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Intervisiegroep;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Entity\Lidmaatschap;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LidmaatschapType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $lidmaatschap Lidmaatschap */
        $lidmaatschap = $options['data'];

        if ($lidmaatschap->getVrijwilliger()) {
            $builder->add('vrijwilliger', DummyChoiceType::class, [
                'dummy_label' => (string) $lidmaatschap->getVrijwilliger(),
            ]);
        } else {
            $builder->add('vrijwilliger', EntityType::class, [
                'placeholder' => '',
                'class' => IzVrijwilliger::class,
                'query_builder' => function (EntityRepository $repository) use ($lidmaatschap) {
                    $builder = $repository->createQueryBuilder('izVrijwilliger')
                        ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
                        ->where('izVrijwilliger.afsluitDatum > DATE(\'NOW\') OR izVrijwilliger.afsluitDatum IS NULL')
                        ->orderBy('vrijwilliger.achternaam, vrijwilliger.tussenvoegsel, vrijwilliger.voornaam')
                    ;

                    $leden = $lidmaatschap->getIntervisiegroep()->getVrijwilligers();
                    if ((is_array($leden) || $leden instanceof \Countable ? count($leden) : 0) > 0) {
                        $builder->andWhere('izVrijwilliger NOT IN (:leden)')->setParameter('leden', $leden);
                    }

                    return $builder;
                },
            ]);
        }

        if ($lidmaatschap->getIntervisiegroep()) {
            $builder->add('intervisiegroep', DummyChoiceType::class, [
                'dummy_label' => (string) $lidmaatschap->getIntervisiegroep(),
            ]);
        } else {
            $builder->add('intervisiegroep', null, [
                'placeholder' => '',
                'class' => Intervisiegroep::class,
                'query_builder' => function (EntityRepository $repository) use ($lidmaatschap) {
                    $builder = $repository->createQueryBuilder('intervisiegroep')
                        ->where('DATE(\'NOW\') BETWEEN intervisiegroep.startdatum AND intervisiegroep.einddatum OR intervisiegroep.startdatum IS NULL OR intervisiegroep.einddatum IS NULL')
                        ->orderBy('intervisiegroep.naam')
                    ;

                    $intervisiegroepen = $lidmaatschap->getVrijwilliger()->getIntervisiegroepen();
                    if ($intervisiegroepen) {
                        $builder
                            ->andWhere('intervisiegroep NOT IN (:intervisiegroepen)')
                            ->setParameter('intervisiegroepen', $intervisiegroepen)
                        ;
                    }

                    return $builder;
                },
            ]);
        }

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lidmaatschap::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
