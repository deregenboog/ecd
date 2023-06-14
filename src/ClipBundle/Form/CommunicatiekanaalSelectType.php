<?php

namespace ClipBundle\Form;

use AppBundle\Form\BaseSelectType;
use ClipBundle\Entity\Communicatiekanaal;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommunicatiekanaalSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Communicatiekanaal::class,

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return BaseSelectType::class;
    }
}
