<?php

namespace IzBundle\Form;

use AppBundle\Form\BaseSelectType;
use IzBundle\Entity\ContactOntstaan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactOntstaanSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => ContactOntstaan::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseSelectType::class;
    }
}
