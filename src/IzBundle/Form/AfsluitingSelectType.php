<?php

namespace IzBundle\Form;

use AppBundle\Form\BaseSelectType;
use IzBundle\Entity\Afsluiting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AfsluitingSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Afsluitreden',
            'class' => Afsluiting::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseSelectType::class;
    }
}
