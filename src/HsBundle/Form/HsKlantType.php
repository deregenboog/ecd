<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Klant;
use AppBundle\Form\KlantType;
use HsBundle\Entity\HsKlant;
use AppBundle\Form\AppDateType;

class HsKlantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data'] instanceof HsKlant
            && $options['data']->getKlant() instanceof Klant
            && $options['data']->getKlant()->getId()
        ) {
            $builder->add('klant', null, [
                'disabled' => true,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('klant')
                        ->where('klant = :klant')
                        ->setParameter('klant', $options['data']->getKlant())
                    ;
                },
            ]);
        } else {
            $builder->add('klant', KlantType::class);
        }

        $builder->add('inschrijving', AppDateType::class, ['data' => new \DateTime('today')]);
        $builder->add('onHold');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HsKlant::class,
        ]);
    }
}
