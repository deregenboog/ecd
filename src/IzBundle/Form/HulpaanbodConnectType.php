<?php

namespace IzBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Medewerker;
use AppBundle\Form\KlantFilterType;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Project;
use IzBundle\Filter\HulpvraagFilter;
use Symfony\Component\Form\AbstractType;
use AppBundle\Form\BaseType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\MedewerkerType;
use IzBundle\Entity\Hulpaanbod;

class HulpaanbodConnectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $hulpaanbod = $options['data'];
        
        $builder
            ->add('hulpvraag', null, [
                'query_builder' => function(EntityRepository $repository) use ($hulpaanbod) {
                    return $repository->createQueryBuilder('hulpvraag')
                        ->select('hulpvraag, izKlant, klant')
                        ->innerJoin('hulpvraag.izKlant', 'izKlant')
                        ->innerJoin('izKlant.klant', 'klant')
                        ->where('hulpvraag.hulpaanbod IS NULL')
                        ->andWhere('hulpvraag.project = :project')
                        ->andWhere('hulpvraag.startdatum >= :today')
                        ->andWhere('hulpvraag.einddatum IS NULL')
                        ->orderBy('klant.achternaam')
                        ->setParameters([
                            'project' => $hulpaanbod->getProject(),
                            'today' => new \DateTime('today'),
                        ])
                    ;
                },
            ])
            ->add('koppelingStartdatum', AppDateType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hulpaanbod::class,
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
