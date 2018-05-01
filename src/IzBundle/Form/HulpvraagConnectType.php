<?php

namespace IzBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use AppBundle\Form\BaseType;
use AppBundle\Form\AppDateType;
use IzBundle\Entity\Hulpaanbod;

class HulpvraagConnectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $hulpvraag = $options['data'];

        $builder
            ->add('hulpaanbod', null, [
                'query_builder' => function (EntityRepository $repository) use ($hulpvraag) {
                    return $repository->createQueryBuilder('hulpaanbod')
                        ->select('hulpaanbod, izVrijwilliger, vrijwilliger')
                        ->innerJoin('hulpaanbod.izVrijwilliger', 'izVrijwilliger')
                        ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
                        ->where('hulpaanbod.hulpvraag IS NULL')
                        ->andWhere('hulpaanbod.project = :project')
                        ->andWhere('hulpaanbod.startdatum >= :today')
                        ->andWhere('hulpaanbod.einddatum IS NULL')
                        ->orderBy('vrijwilliger.achternaam')
                        ->setParameters([
                            'project' => $hulpvraag->getProject(),
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
            'data_class' => Hulpvraag::class,
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
