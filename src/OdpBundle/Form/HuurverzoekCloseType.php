<?php

namespace OdpBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateType;
use OdpBundle\Entity\HuurverzoekAfsluiting;
use OdpBundle\Entity\Huurverzoek;
use Doctrine\ORM\EntityRepository;

class HuurverzoekCloseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $huurverzoek = $options['data'];

        $builder
            ->add('afsluitdatum', AppDateType::class, ['data' => new \DateTime()])
            ->add('afsluiting', null, [
                'class' => HuurverzoekAfsluiting::class,
                'label' => 'Reden afsluiting',
                'required' => true,
                'placeholder' => 'Selecteer een item',
                'query_builder' => function(EntityRepository $repository) use ($huurverzoek) {
                    $builder = $repository->createQueryBuilder('afsluiting')
                        ->where('afsluiting.actief = true')
                    ;
                    if ($huurverzoek instanceof Huurverzoek) {
                        $builder->orWhere('afsluiting = :current')
                            ->setParameter('current', $huurverzoek->getAfsluiting())
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
            'data_class' => Huurverzoek::class,
        ]);
    }
}
