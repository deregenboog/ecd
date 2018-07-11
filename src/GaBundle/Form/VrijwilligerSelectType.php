<?php

namespace GaBundle\Form;

use AppBundle\Entity\Vrijwilliger;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use GaBundle\Entity\Activiteit;
use GaBundle\Entity\Groep;
use GaBundle\Entity\VrijwilligerIntake;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VrijwilligerSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'activiteit' => null,
            'groep' => null,
            'placeholder' => '',
            'required' => true,
            'class' => Vrijwilliger::class,
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('vrijwilliger')->orderBy('vrijwilliger.achternaam');

                    if ($options['activiteit']) {
                        $this->excludeForActiviteit($options['activiteit'], $builder);
                    }

                    if ($options['groep']) {
                        $this->excludeForGroep($options['groep'], $builder);
                    }

                    return $builder;
                };
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityType::class;
    }

    private function excludeForActiviteit(Activiteit $activiteit, QueryBuilder $builder)
    {
        // get volunteers already participating...
        $vrijwilligers = [];
        foreach ($activiteit->getVrijwilligerDeelnames() as $deelname) {
            $vrijwilligers[] = $deelname->getVrijwilliger();
        }
        $vrijwilligers = array_unique($vrijwilligers);

        // ...and exclude them from choices
        if (count($vrijwilligers)) {
            $builder
                ->innerJoin(VrijwilligerIntake::class, 'intake', 'WITH', 'intake.vrijwilliger = vrijwilliger')
                ->andWhere('vrijwilliger NOT IN (:vrijwilligers)')
                ->setParameter('vrijwilligers', $vrijwilligers)
            ;
        }
    }

    private function excludeForGroep(Groep $groep, QueryBuilder $builder)
    {
        // get volunteers already member...
        $vrijwilligers = [];
        foreach ($groep->getVrijwilligerLidmaatschappen() as $lidmaatschap) {
            $vrijwilligers[] = $lidmaatschap->getVrijwilliger();
        }
        $vrijwilligers = array_unique($vrijwilligers);

        // ...and exclude them from choices
        if (count($vrijwilligers)) {
            $builder
                ->innerJoin(VrijwilligerIntake::class, 'intake', 'WITH', 'intake.vrijwilliger = vrijwilliger')
                ->andWhere('vrijwilliger NOT IN (:vrijwilligers)')
                ->setParameter('vrijwilligers', $vrijwilligers)
            ;
        }
    }
}
