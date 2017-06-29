<?php

namespace ClipBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use ClipBundle\Entity\Behandelaar;
use Symfony\Component\Form\FormBuilderInterface;

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
                    ->innerJoin(Behandelaar::class, 'behandelaar', 'WITH', 'behandelaar.medewerker = medewerker')
                    ->where('behandelaar.actief = true')
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
        return 'clip_medewerker';
    }
}
