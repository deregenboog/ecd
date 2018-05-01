<?php

namespace MwBundle\Form;

use AppBundle\Form\RapportageType as BaseRapportageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use InloopBundle\Entity\Locatie;
use Doctrine\ORM\EntityRepository;

class RapportageType extends BaseRapportageType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('locatie', EntityType::class, [
            'placeholder' => 'Alle locaties',
            'required' => false,
            'class' => Locatie::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('locatie')
                    ->orderBy('locatie.naam');
            },
        ]);

        parent::buildForm($builder, $options);
    }
}
