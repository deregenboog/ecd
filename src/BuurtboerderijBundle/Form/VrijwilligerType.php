<?php

namespace BuurtboerderijBundle\Form;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\VrijwilligerType as AppVrijwilligerType;
use BuurtboerderijBundle\Entity\Vrijwilliger;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VrijwilligerType extends AbstractType
{
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
            ->add('medewerker', MedewerkerType::class, ['required' => true])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vrijwilliger::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
