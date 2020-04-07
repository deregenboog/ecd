<?php

namespace AppBundle\Form;

use AppBundle\Entity\Geslacht;
use AppBundle\Entity\Doelstelling;
use AppBundle\Repository\DoelstellingRepositoryInterface;
use AppBundle\Service\DoelstellingDaoInterface;
use InloopBundle\Form\LocatieSelectType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DoelstellingType extends AbstractType
{
    private $choices = [];
    private $repos = [];
    public function __construct(array $options = [])
    {
        foreach ($options as $className=>$prestatieRepo) {
            if(!$prestatieRepo instanceof DoelstellingRepositoryInterface) continue;

            $this->choices[$prestatieRepo::getPrestatieLabel()] = get_class($prestatieRepo);
            $this->repos[get_class($prestatieRepo)] = $prestatieRepo;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $range = range((new \DateTime('previous year'))->modify('-1 year')->format('Y'), (new \DateTime('next year'))->format('Y'));
        $repo = null;

        $builder

            ->add('repository', ChoiceType::class, [
                'required' => true,
                'placeholder' => 'Selecteer een module',
                'choices' => $this->choices,
//                'mapped'=>false,
            ])
            ->add('bulk', CheckboxType::class,[
                'required'=>false,
                'mapped'=>false,
                'label'=>'Bulk invoer?',
            ])
            ->add('jaar', ChoiceType::class, [
                'choices' => array_combine($range, $range),
            ])

            ->add('categorie', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'Stadsdeel',
                'choices' => [
                    'Centrale stad' => Doelstelling::CATEGORIE_CENTRALE_STAD,
                    'Fondsen' => Doelstelling::CATEGORIE_FONDSEN,
                ],
            ])
            ->add('stadsdeel', WerkgebiedSelectType::class, [
                'required'=>false,
            ])

        ;
        $formModifier =  function (?FormInterface $form, $data) {
            $kpis = [];
            //if the data is from the select field, it is a string, key to daos.
            if(is_string($data) && strlen($data) > 1 && $this->repos[$data] instanceof DoelstellingRepositoryInterface)
            {
                $dao = $this->repos[$data];
                $kpis = $dao->getKpis();

            }
            $form->add('kpi', ChoiceType::class, [
                'placeholder' => '',
                'choices' => $kpis,
//                'required' => false,
            ]);
        };
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($formModifier) {
                $formModifier($event->getForm(),$event->getData());
            }
        );
        $builder->get('repository')->addEventListener(
            FormEvents::POST_SUBMIT,function(FormEvent $event) use($formModifier) {
           $formModifier($event->getForm()->getParent(),$event->getData());
        });

        $builder->add('aantal')
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
            ;

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Doelstelling::class,
//            'allow_extra_fields'=>true,
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
