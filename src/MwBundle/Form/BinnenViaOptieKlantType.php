<?php

namespace MwBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;

class BinnenViaOptieKlantType extends BinnenViaType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => BinnenViaOptieKlant::class,
        ]);
    }
}
