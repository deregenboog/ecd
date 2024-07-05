<?php

namespace ClipBundle\Form;

use AppBundle\Form\BaseSelectType;
use ClipBundle\Entity\Communicatiekanaal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommunicatiekanaalSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Communicatiekanaal::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseSelectType::class;
    }
}
