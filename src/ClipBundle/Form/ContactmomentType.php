<?php

namespace ClipBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use ClipBundle\Entity\Contactmoment;
use Doctrine\ORM\EntityRepository;
use ClipBundle\Entity\Behandelaar;

class ContactmomentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medewerker', MedewerkerType::class, [
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $current = $options['data'] ? $options['data']->getMedewerker() : null;

                    return $repository->createQueryBuilder('medewerker')
                        ->leftJoin(Behandelaar::class, 'behandelaar', 'WITH', 'behandelaar.medewerker = medewerker')
                        ->where('behandelaar.actief = true OR medewerker = :current')
                        ->setParameter('current', $current)
                        ->orderBy('medewerker.voornaam')
                    ;
                },
            ])
            ->add('datum', AppDateType::class)
            ->add('opmerking')
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contactmoment::class,
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
