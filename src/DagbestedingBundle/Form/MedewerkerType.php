<?php

namespace DagbestedingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DagbestedingBundle\Entity\Trajectbegeleider;
use Doctrine\ORM\EntityRepository;

class MedewerkerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('medewerker')
                    ->innerJoin(Trajectbegeleider::class, 'trajectbegeleider', 'WITH', 'trajectbegeleider.medewerker = medewerker')
                    ->orderBy('medewerker.voornaam')
                ;
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return \AppBundle\Form\MedewerkerType::class;
    }

    public function getBlockPrefix()
    {
        return 'dagbesteding_medewerker';
    }
}
