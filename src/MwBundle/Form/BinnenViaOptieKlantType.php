<?php

namespace MwBundle\Form;


use AppBundle\Form\BaseType;
use MwBundle\Entity\BinnenVia;
use MwBundle\Entity\BinnenViaOptieVW;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BinnenViaOptieKlantType extends BinnenViaType
{


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

            'class'=>BinnenViaOptieKlant::class,
        ]);
    }


}
