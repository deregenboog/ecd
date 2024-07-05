<?php

namespace MwBundle\Form;

use MwBundle\Entity\BinnenViaOptieKlant;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BinnenViaOptieKlantType extends BinnenViaType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => BinnenViaOptieKlant::class,
        ]);
    }
}
