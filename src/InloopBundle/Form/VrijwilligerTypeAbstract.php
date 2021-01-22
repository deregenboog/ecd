<?php

namespace InloopBundle\Form;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Form\AppDateTimeType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\JaNeeType;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\VrijwilligerType as AppVrijwilligerType;
use Doctrine\ORM\EntityRepository;
use InloopBundle\Entity\Vrijwilliger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VrijwilligerTypeAbstract extends AbstractType
{
    protected $dataClass; //Vrijwilliger::class;
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (//$options['data'] instanceof Vrijwilliger &&
            $options['data']->getVrijwilliger() instanceof AppVrijwilliger &&
            $options['data']->getVrijwilliger()->getId()
        ) {
            $builder->add('vrijwilliger', null, [
                'disabled' => true,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('vrijwilliger')
                        ->where('vrijwilliger = :vrijwilliger')
                        ->setParameter('vrijwilliger', $options['data']->getVrijwilliger())
                    ;
                },
            ]);
        } else {
            $builder
                ->add('vrijwilliger', AppVrijwilligerType::class)
                ->get('vrijwilliger')
                ->remove('opmerking')
                ->remove('geenPost')
                ->remove('geenEmail')
            ;
        }

        $builder
            ->add('aanmelddatum', AppDateType::class, [
                'required' => true,
            ])
            ->add('binnenVia', null, [
                'placeholder' => '',
                'required' => true,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('binnenVia')
                        ->where('binnenVia.actief = true')
                        ->orWhere('binnenVia = :current')
                        ->orderBy('binnenVia.naam')
                        ->setParameter('current', $options['data'] ? $options['data']->getBinnenVia() : null)
                    ;
                },
            ])
            ->add('locaties', LocatieSelectType::class, [
                'required' => true,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('medewerker', MedewerkerType::class, ['required' => true])
            ->add('stagiair',CheckboxType::class, [
            'required' => false
            ])
            ->add('notitieIntake',AppTextareaType::class,['required' => false])
//            ->add('datumNotitieIntake',AppDateTimeType::class, [
//                'data' => new \DateTime('now'),
//                'label'=>'Notitiedatum',
//                'required'=>false,
//            ])
            ->add('tweedeFase',CheckboxType::class,['mapped'=>false,'required'=>false])
            ->add('startDatum',AppDateType::class, [

                'required' =>false,

            ])
            ->add('medewerkerLocatie', MedewerkerType::class, [
                'required' => false,
                'preset'=>false
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
            'data_class' => $this->dataClass
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
