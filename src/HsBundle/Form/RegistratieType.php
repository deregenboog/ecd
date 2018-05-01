<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HsBundle\Entity\Registratie;
use AppBundle\Form\AppDateType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\AppTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;
use HsBundle\Entity\Arbeider;
use HsBundle\Entity\Klus;
use HsBundle\Entity\Dienstverlener;
use HsBundle\Entity\Vrijwilliger;

class RegistratieType extends AbstractType
{
    use MedewerkerTypeTrait;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var $registratie Registratie */
        $registratie = $options['data'];

        if ($registratie->getKlus() && !$registratie->getArbeider()) {
            $builder->add('arbeider', null, [
                'label' => 'Dienstverlener/vrijwilliger',
                'required' => true,
                'query_builder' => function (EntityRepository $repository) use ($registratie) {
                    return $repository->createQueryBuilder('arbeider')
                        ->leftJoin(Dienstverlener::class, 'dienstverlener', 'WITH', 'dienstverlener = arbeider')
                        ->leftJoin(Vrijwilliger::class, 'vrijwilliger', 'WITH', 'vrijwilliger = arbeider')
                        ->leftJoin('dienstverlener.klant', 'klant')
                        ->leftJoin('vrijwilliger.vrijwilliger', 'basisvrijwilliger')
                        ->where('arbeider IN (:dienstverleners) OR arbeider IN (:vrijwilligers)')
                        ->orderBy('basisvrijwilliger.achternaam, klant.achternaam')
                        ->setParameters([
                            'dienstverleners' => $registratie->getKlus()->getDienstverleners(),
                            'vrijwilligers' => $registratie->getKlus()->getVrijwilligers(),
                        ])
                    ;
                },
                'group_by' => function ($value, $key, $index) {
                    if ($value instanceof Dienstverlener) {
                        return 'Dienstverleners';
                    } else {
                        return 'Vrijwilligers';
                    }
                },
            ]);
        } elseif ($registratie->getArbeider() && !$registratie->getKlus()) {
            $builder->add('klus', null, [
                'required' => true,
                'query_builder' => function (EntityRepository $repository) use ($registratie) {
                    return $repository->createQueryBuilder('klus')
                        ->leftJoin('klus.dienstverleners', 'dienstverlener')
                        ->leftJoin('klus.vrijwilligers', 'vrijwilliger')
                        ->where('dienstverlener = :arbeider OR vrijwilliger = :arbeider')
                        ->andWhere('klus.status = :status')
                        ->setParameter('arbeider', $registratie->getArbeider())
                        ->setParameter('status', Klus::STATUS_IN_BEHANDELING)
                    ;
                },
            ])
            ;
        }

        $this->addMedewerkerType($builder, $options);

        $builder
            ->add('activiteit', null, [
                'placeholder' => 'Selecteer een activiteit',
                'expanded' => true,
                'query_builder' => function (EntityRepository $repository) use ($registratie) {
                    $builder = $repository->createQueryBuilder('activiteit')->orderBy('activiteit.naam');

                    if ($registratie->getKlus() && count($registratie->getKlus()->getActiviteiten()) > 0) {
                        $builder
                            ->where('activiteit IN (:activiteiten)')
                            ->setParameter('activiteiten', $registratie->getKlus()->getActiviteiten())
                        ;
                    }

                    return $builder;
                },
            ])
            ->add('datum', AppDateType::class)
            ->add('start', AppTimeType::class)
            ->add('eind', AppTimeType::class)
//             ->add('reiskosten', MoneyType::class, ['required' => false])
        ;

        $builder->add('submit', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Registratie::class,
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
