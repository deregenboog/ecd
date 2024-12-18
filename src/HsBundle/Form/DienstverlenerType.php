<?php

namespace HsBundle\Form;

use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\KlantType as AppKlantType;
use Doctrine\ORM\EntityRepository;
use HsBundle\Entity\Dienstverlener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DienstverlenerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data'] instanceof Dienstverlener
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
            $builder
                ->add('klant', AppKlantType::class)
                ->get('klant')
                ->remove('opmerking')
                ->remove('geenPost')
                ->remove('geenEmail')
            ;
        }

        $builder
            ->add('inschrijving', AppDateType::class)
            ->add('actief')
            ->add('rijbewijs', null, ['label' => 'Rijbewijs'])
            ->add('hulpverlener', HulpverlenerType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dienstverlener::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
