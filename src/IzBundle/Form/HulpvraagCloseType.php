<?php

namespace IzBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Medewerker;
use AppBundle\Form\KlantFilterType;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Project;
use IzBundle\Filter\HulpvraagFilter;
use Symfony\Component\Form\AbstractType;
use AppBundle\Form\BaseType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\MedewerkerType;
use IzBundle\Entity\Hulpaanbod;

class HulpvraagCloseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('einddatum', AppDateType::class, [
                'data' => new \DateTime('today'),
            ])
            ->add('eindeVraagAanbod', null, [
                'query_builder' => function(EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('einde')
                        ->orderBy('einde.naam')
                    ;
                },
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hulpvraag::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }
}
