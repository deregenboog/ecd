<?php

namespace OdpBundle\Form;

use AppBundle\Form\BaseType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use OdpBundle\Entity\Coordinator;
use Doctrine\ORM\EntityRepository;

class MedewerkerType extends BaseType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('medewerker')
                    ->innerJoin(Coordinator::class, 'coordinator', 'WITH', 'coordinator.medewerker = medewerker')
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
        return 'odp_medewerker';
    }
}
