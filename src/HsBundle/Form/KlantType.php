<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\KlantType as AppKlantType;
use HsBundle\Entity\Klant;
use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Form\AppDateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class KlantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data'] instanceof Klant
            && $options['data']->getKlant() instanceof AppKlant
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
            $builder->add('klant', AppKlantType::class);
        }

        $builder
            ->add('inschrijving', AppDateType::class, ['data' => new \DateTime('today')])
            ->add('onHold')
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Klant::class,
        ]);
    }
}
