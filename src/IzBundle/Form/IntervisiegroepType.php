<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Intervisiegroep;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntervisiegroepType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam', null, [
                'required' => true,
            ])
            ->add('startdatum', AppDateType::class, [
                'required' => true,
            ])
            ->add('einddatum', AppDateType::class, [
                'required' => false,
            ])
            ->add('medewerker', MedewerkerType::class, [
                'required' => true,
            ])
        ;

        if (isset($options['data']) && $options['data']->getId()) {
            $builder->add('vrijwilligers', null, [
                'expanded' => true,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('izVrijwilliger')
                        ->innerJoin('izVrijwilliger.intervisiegroepen', 'intervisiegroep', 'WITH', 'intervisiegroep = :intervisiegroep')
                        ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
                        ->orderBy('vrijwilliger.achternaam')
                        ->setParameter('intervisiegroep', $options['data'])
                    ;
                },
            ]);
        }

        $builder->add('submit', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Intervisiegroep::class,
        ]);
    }

    public function getParent()
    {
        return BaseType::class;
    }
}
