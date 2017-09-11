<?php

namespace ClipBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use ClipBundle\Entity\Contactmoment;
use ClipBundle\Entity\Vraag;
use Doctrine\ORM\EntityRepository;
use ClipBundle\Entity\Behandelaar;
use AppBundle\Form\AppTextareaType;

class VraagType extends AbstractType
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
            ->add('startdatum', AppDateType::class)
            ->add('hulpvrager', null, [
                'placeholder' => '',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) use ($options) {
                    $current = $options['data'] ? $options['data']->getHulpvrager() : null;

                    return $repository->createQueryBuilder('hulpvrager')
                        ->where('hulpvrager.actief = true OR hulpvrager = :current')
                        ->setParameter('current', $current)
                    ;
                },
            ])
            ->add('leeftijdscategorie', null, [
                'placeholder' => '',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) use ($options) {
                    $current = $options['data'] ? $options['data']->getLeeftijdscategorie() : null;

                    return $repository->createQueryBuilder('leeftijdscategorie')
                        ->where('leeftijdscategorie.actief = true OR leeftijdscategorie = :current')
                        ->setParameter('current', $current)
                    ;
                },
            ])
            ->add('communicatiekanaal', null, [
                'label' => 'Locatie',
                'placeholder' => '',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) use ($options) {
                    $current = $options['data'] ? $options['data']->getCommunicatiekanaal() : null;

                    return $repository->createQueryBuilder('communicatiekanaal')
                        ->where('communicatiekanaal.actief = true OR communicatiekanaal = :current')
                        ->setParameter('current', $current)
                    ;
                },
            ])
            ->add('soort', null, [
                'placeholder' => '',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) use ($options) {
                    $current = $options['data'] ? $options['data']->getSoort() : null;

                    return $repository->createQueryBuilder('soort')
                        ->where('soort.actief = true OR soort = :current')
                        ->setParameter('current', $current)
                    ;
                },
            ])
            ->add('omschrijving', AppTextareaType::class)
        ;

        if (!isset($options['data']) || !$options['data']->getId()) {
            $builder->add('contactmoment', ContactmomentType::class, [
                'required' => true,
                'label' => 'Eerste contactmoment',
            ]);
            $builder->get('contactmoment')->remove('medewerker')->remove('datum');
            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event, $eventName) {
                /* @var $vraag Vraag */
                $vraag = $event->getData();
                $vraag->getContactmoment()
                    ->setDatum($vraag->getStartdatum())
                    ->setMedewerker($vraag->getMedewerker())
                ;
            });
        }

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vraag::class,
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
