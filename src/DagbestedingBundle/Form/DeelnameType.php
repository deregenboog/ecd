<?php

namespace DagbestedingBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use Doctrine\ORM\EntityRepository;
use DagbestedingBundle\Entity\Deelname;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DeelnameType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $deelname Deelname */
        $deelname = $options['data'];


        if ($deelname && $deelname->getProject()) {
            $builder->add('project', DummyChoiceType::class, [
                'dummy_label' => (string) $deelname->getProject(),
            ]);
        } else {
            $builder->add('project', ProjectSelectType::class, [
                'required' => true,
                'placeholder' => ''
            ]);
        }

        if ($deelname && $deelname->getTraject()) {
            $builder->add('traject', DummyChoiceType::class, [
                'label' => 'Traject',
                'dummy_label' => (string) $deelname->getTraject(),
            ]);
        } else {
//            $builder->add('traject', null, [
//                'label' => 'Traject',
//                'required' => true,
//                'placeholder' => '',
//                'query_builder' => function (EntityRepository $repository) {
//                    return $repository->createQueryBuilder('deelnemer')
//                        ->innerJoin('deelnemer.klant', 'klant')
//                        ->orderBy('klant.achternaam, klant.voornaam')
//                    ;
//                },
//            ]);
        }

        $builder
            ->add('actief', CheckboxType::class, [
                'required' => false,
            ])
            ->add('beschikbaarheid', BeschikbaarheidType::class, [
                'required' => false,
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
            'data_class' => Deelname::class,
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
