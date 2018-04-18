<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Koppeling;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KoppelingCloseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var $koppeling Koppeling */
        $koppeling = $options['data'];

        $koppeling->setStatus(null);

        if ($koppeling->getHulpvraag() instanceof Hulpvraag) {
            $builder->add('hulpvraag', DummyChoiceType::class, [
                'dummy_label' => (string) $koppeling->getHulpvraag(),
            ]);
        }

        if ($koppeling->getHulpaanbod() instanceof Hulpaanbod) {
            $builder->add('hulpaanbod', DummyChoiceType::class, [
                'dummy_label' => (string) $koppeling->getHulpaanbod(),
            ]);
        }

        $builder
            ->add('afsluitdatum', AppDateType::class, [
                'required' => true,
            ])
            ->add('afsluitreden', null, [
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('einde')
                        ->orderBy('einde.naam')
                    ;
                },
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Koppeling::class,
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
