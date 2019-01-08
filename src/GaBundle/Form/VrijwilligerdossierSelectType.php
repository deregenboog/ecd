<?php

namespace GaBundle\Form;

use AppBundle\Service\NameFormatter;
use Doctrine\ORM\EntityRepository;
use GaBundle\Entity\Vrijwilligerdossier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VrijwilligerdossierSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'activiteit' => null,
            'groep' => null,
            'label' => 'Vrijwilliger',
            'placeholder' => '',
            'required' => true,
            'class' => Vrijwilligerdossier::class,
            'choice_label' => function (Vrijwilligerdossier $dossier) {
                return NameFormatter::formatFormal($dossier->getVrijwilliger());
            },
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('dossier')
                        ->innerJoin('dossier.vrijwilliger', 'vrijwilliger')
                        ->orderBy('vrijwilliger.achternaam')
                    ;

                    if ($options['activiteit']) {
                        $this->excludeForActiviteit($options['activiteit'], $builder);
                    }

                    if ($options['groep'] && count($options['groep']) > 0) {
                        $builder
                            ->andWhere('dossier NOT IN (:dossiers)')
                            ->setParameter('dossiers', $options['groep']->getDossiers());
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
}
