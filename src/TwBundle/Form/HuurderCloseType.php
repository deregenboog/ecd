<?php

namespace TwBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use Doctrine\ORM\EntityRepository;
use TwBundle\Entity\Klant;
use TwBundle\Entity\KlantAfsluiting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                'class' => KlantAfsluiting::class,
                'label' => 'Reden afsluiting',
                'required' => true,
                'placeholder' => 'Selecteer een item',
                'query_builder' => function (EntityRepository $repository) use ($huurder) {
                    $builder = $repository->createQueryBuilder('afsluiting')
                        ->where('afsluiting.actief = true')
                    ;
                    if ($huurder instanceof Klant) {
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
            'data_class' => Klant::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
