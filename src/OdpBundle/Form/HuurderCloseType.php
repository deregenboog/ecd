<?php

namespace OdpBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateType;
use OdpBundle\Entity\Huurder;
use OdpBundle\Entity\HuurderAfsluiting;
use AppBundle\Form\BaseType;
use Doctrine\ORM\EntityRepository;

class HuurderCloseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $huurder = $options['data'];

        $builder
            ->add('afsluitdatum', AppDateType::class, ['data' => new \DateTime()])
            ->add('afsluiting', null, [
                'class' => HuurderAfsluiting::class,
                'label' => 'Reden afsluiting',
                'required' => true,
                'placeholder' => 'Selecteer een item',
                'query_builder' => function(EntityRepository $repository) use ($huurder) {
                    $builder = $repository->createQueryBuilder('afsluiting')
                        ->where('afsluiting.actief = true')
                    ;
                    if ($huurder instanceof Huurder) {
                        $builder->orWhere('afsluiting = :current')
                            ->setParameter('current', $huurder->getAfsluiting())
                        ;
                    }
                },
            ])
            ->add('submit', SubmitType::class, ['label' => 'Afsluiten'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Huurder::class,
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
