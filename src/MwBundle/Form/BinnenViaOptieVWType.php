<?php

namespace MwBundle\Form;

use MwBundle\Entity\BinnenViaOptieVW;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BinnenViaOptieVWType extends BinnenViaType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => BinnenViaOptieVW::class,
        ]);
    }
}
