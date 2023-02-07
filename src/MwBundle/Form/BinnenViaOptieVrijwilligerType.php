<?php

namespace MwBundle\Form;

use MwBundle\Entity\BinnenViaOptieVrijwilliger;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BinnenViaOptieVrijwilligerType extends BinnenViaType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => BinnenViaOptieVrijwilliger::class,
        ]);
    }
}
