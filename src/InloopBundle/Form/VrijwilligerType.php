<?php

namespace InloopBundle\Form;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\VrijwilligerType as AppVrijwilligerType;
use Doctrine\ORM\EntityRepository;
use InloopBundle\Entity\Vrijwilliger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\MedewerkerType;

class VrijwilligerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data'] instanceof Vrijwilliger
            && $options['data']->getVrijwilliger() instanceof AppVrijwilliger
            && $options['data']->getVrijwilliger()->getId()
        ) {
            $builder->add('vrijwilliger', null, [
                'disabled' => true,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('vrijwilliger')
                        ->where('vrijwilliger = :vrijwilliger')
                        ->setParameter('vrijwilliger', $options['data']->getVrijwilliger())
                    ;
                },
            ]);
        } else {
            $builder
                ->add('vrijwilliger', AppVrijwilligerType::class)
                ->get('vrijwilliger')
                ->remove('opmerking')
                ->remove('geenPost')
                ->remove('geenEmail')
            ;
        }

        $builder
            ->add('aanmelddatum', AppDateType::class, [
                'required' => true,
            ])
            ->add('locaties', null, [
                'required' => true,
                'expanded' => true,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->createQueryBuilder('locatie')
                        ->where('locatie.datumVan <= :today')
                        ->andWhere("locatie.datumTot IS NULL OR locatie.datumTot = '0000-00-00' OR locatie.datumTot >= :today")
                        ->orderBy('locatie.naam')
                        ->setParameter('today', new \DateTime('today'))
                    ;
                },
            ])
            ->add('medewerker', MedewerkerType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vrijwilliger::class,
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
