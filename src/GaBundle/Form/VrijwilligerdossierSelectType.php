<?php

namespace GaBundle\Form;

use AppBundle\Service\NameFormatter;
use Doctrine\ORM\EntityRepository;
use GaBundle\Entity\Vrijwilligerdossier;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VrijwilligerdossierSelectType extends DossierSelectType
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

                    if ($options['groep']) {
                        $this->excludeForGroep($options['groep'], $builder);
                    }

                    return $builder;
                };
            },
        ]);
    }
}
