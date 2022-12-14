<?php

namespace TwBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType;
use Doctrine\ORM\EntityRepository;
use TwBundle\Entity\Huurovereenkomst;
use TwBundle\Filter\HuurovereenkomstFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HuurovereenkomstFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, [
                'required' => false,
            ]);
        }

        if (array_key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', \TwBundle\Form\KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }

        if (array_key_exists('verhuurderKlant', $options['enabled_filters'])) {
            $builder->add('verhuurderKlant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['verhuurderKlant'],
            ]);
        }

        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(Huurovereenkomst::class, 'huurovereenkomst', 'WITH', 'huurovereenkomst.medewerker = medewerker')
                        ->where('medewerker.actief = :true')
                        ->setParameter('true', true)
                        ->orderBy('medewerker.voornaam', 'ASC')
                    ;
                },
            ]);
        }

        if (in_array('startdatum', $options['enabled_filters'])) {
            $builder->add('startdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('opzegdatum', $options['enabled_filters'])) {
            $builder->add('opzegdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('einddatum', $options['enabled_filters'])) {
            $builder->add('einddatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('vorm', $options['enabled_filters'])) {
            $builder->add('vorm', ChoiceType::class, [
                'required' => false,
                'choices' => Huurovereenkomst::getVormChoices(),
            ]);
        }

        if (in_array('afsluitdatum', $options['enabled_filters'])) {
            $builder->add('afsluitdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('actief', $options['enabled_filters'])) {

            $builder->add('actief', ChoiceType::class,[
                'choices' => [
                    'Lopende koppelingen' => 'lopend',
                    'Afgesloten koppelingen' => 'afgesloten',
                    'Alle koppelingen' => 'all'
                  ],
                'data'=>true,
            ]);
        }
        if (in_array('opzegbriefVerstuurd', $options['enabled_filters'])) {
            $builder->add('opzegbriefVerstuurd', CheckboxType::class, [
                'required' => false,
                'label' => 'Opzegbrief verstuurd?',
                'data' => false,
            ]);
        }
        if (in_array('isReservering', $options['enabled_filters'])) {
            $isReservering = false;
           if($options['data'] && is_null($options['data']->isReservering) && is_array($options['empty_data'])) {
               if(isset($options['empty_data']['isReservering'])) $isReservering = $options['empty_data']['isReservering'];
           }


            $builder->add('isReservering', CheckboxType::class, [
                'required' => false,
                'label' => 'Reserveringen',
                'data' => $isReservering,

            ]);
        }
        if(array_key_exists('klant',$options['enabled_filters']) && in_array('aanmelddatum',$options['enabled_filters']['klant'])){
            $builder->add('aanmelddatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('project', $options['enabled_filters'])) {
            $builder->add('project', ProjectSelectFilterType::class, [
                'label' => 'Project',
                'required' => false,
//                'data' => false,
            ]);
        }
        $builder
            ->add('filter', SubmitType::class, ['label' => 'Filteren'])
            ->add('download', SubmitType::class, ['label' => 'Downloaden'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FilterType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HuurovereenkomstFilter::class,
            'data' => new HuurovereenkomstFilter(),
            'enabled_filters' => [
                'id',
                'klant' => ['automatischeIncasso', 'aanmelddatum','appKlant' => ['naam']],
                'verhuurderKlant' => ['naam', 'plaats'],
                'medewerker',
                'startdatum',
                'opzegdatum',
                'einddatum',
                'vorm',
                'afsluitdatum',
                'actief',
                'project',
                'isReservering',
                'opzegbriefVerstuurd',
            ],
        ]);
    }
}
