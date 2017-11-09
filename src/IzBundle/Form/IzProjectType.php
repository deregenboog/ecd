<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use IzBundle\Entity\IzProject;
use AppBundle\Form\StadsdeelFilterType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use IzBundle\Filter\IzDeelnemerSelectie;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\FilterType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use IzBundle\Entity\Doelstelling;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use IzBundle\Filter\DoelstellingFilter;
use AppBundle\Entity\Werkgebied;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class IzProjectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => IzProject::class,
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('p')
                    ->where('p.startdatum <= :now')
                    ->andWhere('p.einddatum IS NULL OR p.einddatum > :now')
                    ->orderBy('p.naam', 'ASC')
                    ->setParameter('now', new \DateTime())
                ;
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityType::class;
    }
}
