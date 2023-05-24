<?php

namespace GaBundle\Form;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use GaBundle\Entity\Activiteit;
use GaBundle\Entity\Deelname;
use GaBundle\Entity\Dossier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiviteitSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'dossier' => '',
            'placeholder' => '',
            'required' => true,
            'class' => Activiteit::class,
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('activiteit')->orderBy('activiteit.datum, activiteit.naam');

                    if ($options['dossier']) {
                        $this->excludeForDossier($options['dossier'], $builder);
                    }

                    return $builder;
                };
            },
            'preferred_choices' => function (Options $options) {
                return $options['em']->getRepository(Activiteit::class)->createQueryBuilder('activiteit')
                    ->where('activiteit.datum BETWEEN :start AND :end')
                    ->setParameters([
                        'start' => new \DateTime('-1 month'),
                        'end' => new \DateTime('+1 month'),
                    ])
                    ->getQuery()
                    ->getResult()
                ;
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return EntityType::class;
    }

    private function excludeForDossier(Dossier $dossier, QueryBuilder $builder)
    {
        $map = function (Deelname $deelname) {
            if ($deelname->getActiviteit()) {
                return $deelname->getActiviteit()->getId();
            }
        };

        $activiteitIds = array_map($map, $dossier->getDeelnames()->toArray());
        $activiteitIds = array_filter($activiteitIds);

        if (count($activiteitIds) > 0) {
            $builder->andWhere('activiteit.id NOT IN (:activiteit_ids)')
            ->setParameter('activiteit_ids', $activiteitIds);
        }
    }
}
