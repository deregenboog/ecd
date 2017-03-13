<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use HsBundle\Entity\Vrijwilliger;
use AppBundle\Form\VrijwilligerType as AppVrijwilligerType;
use AppBundle\Form\AppDateType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
            $builder->add('vrijwilliger', AppVrijwilligerType::class);
        }

        $builder
            ->add('inschrijving', AppDateType::class, ['data' => new \DateTime('today')])
            ->add('dragend', null, ['label' => 'Dragende vrijwilliger'])
            ->add('rijbewijs', null, ['label' => 'Rijbewijs'])
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
}
