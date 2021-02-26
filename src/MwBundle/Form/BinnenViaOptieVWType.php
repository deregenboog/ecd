<?php

namespace MwBundle\Form;


use MwBundle\Entity\BinnenVia;
use MwBundle\Entity\BinnenViaOptieVW;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BinnenViaOptieVWType extends BinnenViaType
{


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

            'class'=>BinnenViaOptieVW::class,
        ]);
    }

}
