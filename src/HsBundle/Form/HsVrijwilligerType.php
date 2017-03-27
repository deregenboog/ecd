<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HsBundle\Entity\HsVrijwilliger;
use AppBundle\Form\VrijwilligerType;
use AppBundle\Form\AppDateType;
use AppBundle\Entity\Vrijwilliger;
use Doctrine\ORM\EntityRepository;

class HsVrijwilligerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data'] instanceof HsVrijwilliger
            && $options['data']->getVrijwilliger() instanceof Vrijwilliger
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
            $builder->add('vrijwilliger', VrijwilligerType::class);
        }

        $builder->add('inschrijving', AppDateType::class, ['data' => new \DateTime('today')]);
        $builder->add('dragend', null, ['label' => 'Dragende vrijwilliger']);
        $builder->add('rijbewijs', null, ['label' => 'Rijbewijs']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HsVrijwilliger::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return \AppBundle\Form\BaseType::class;
    }
}
