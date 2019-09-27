<?php

namespace GaBundle\Form;

use AppBundle\Service\NameFormatter;
use Doctrine\ORM\EntityRepository;
use GaBundle\Entity\Klantdossier;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlantdossierSelectType extends DossierSelectType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'activiteit' => null,
            'groep' => null,
            'label' => 'Deelnemer',
            'placeholder' => '',
            'required' => true,
            'class' => Klantdossier::class,
            'choice_label' => function (Klantdossier $dossier) {
                return NameFormatter::formatFormal($dossier->getKlant());
            },
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('dossier')
                        ->innerJoin('dossier.klant', 'klant')
                        ->orderBy('klant.achternaam')
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
