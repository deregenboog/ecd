<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HsBundle\Entity\HsKlus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use HsBundle\Entity\HsVrijwilliger;
use Doctrine\ORM\EntityRepository;

class HsKlusType extends AbstractType
{
    const MODE_ADD = 'add';
    const MODE_UPDATE = 'update';
    const MODE_ADD_VRIJWILLIGER = 'add_vrijwilliger';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        switch ($options['mode']) {
            default:
            case self::MODE_ADD:
            case self::MODE_UPDATE:
                $builder
                    ->add('datum')
                    ->add('hsActiviteit')
                    ->add('medewerker')
                    ->add('hsVrijwilligers', null, [
                        'label' => 'Vrijwilliger',
//                         'query_builder' => function (EntityRepository $repository) use ($options) {
//                             return $repository->createQueryBuilder('v')
//                                 ->where('v NOT IN (:vrijwilligers)')
//                                 ->setParameter('vrijwilligers', $options['data']->getHsVrijwilligers()->toArray());
//                         },
                    ]);
                break;
            case self::MODE_ADD_VRIJWILLIGER:
                $builder->add('hsVrijwilligers', EntityType::class, [
                    'label' => 'Vrijwilliger',
                    'placeholder' => '',
                    'class' => HsVrijwilliger::class,
                    'query_builder' => function (EntityRepository $repository) use ($options) {
                        return $repository->createQueryBuilder('v')
                            ->where('v NOT IN (:vrijwilligers)')
                            ->setParameter('vrijwilligers', $options['data']->getHsVrijwilligers()->toArray());
                    },
                ]);
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HsKlus::class,
            'mode' => self::MODE_ADD,
        ]);
    }
}
