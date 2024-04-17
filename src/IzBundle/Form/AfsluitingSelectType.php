<?php

namespace IzBundle\Form;

use AppBundle\Form\BaseSelectType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Afsluiting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AfsluitingSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['doelgroepen']);

        $resolver->setDefaults([
            'label' => 'Afsluitreden',
            'class' => Afsluiting::class,
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repo) use ($options) {
                    return $repo->createQueryBuilder('afsluiting')
                        ->where('bit_and(afsluiting.doelgroepen, :doelgroepen) <> 0')
                        ->setParameter('doelgroepen', $options['doelgroepen'])
                        ->orderBy('afsluiting.naam')
                    ;
                };
            },
        ]);
    }

    public function getParent(): ?string
    {
        return BaseSelectType::class;
    }
}
