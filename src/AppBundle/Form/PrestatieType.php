<?php

namespace AppBundle\Form;

use AppBundle\Entity\Geslacht;
use AppBundle\Entity\Prestatie;
use AppBundle\Service\PrestatieDaoInterface;
use InloopBundle\Form\LocatieSelectType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrestatieType extends AbstractType
{
    private $choices = [];
    private $daos = [];
    public function __construct(array $options = [])
    {
        dump($options);
        foreach ($options as $className=>$prestatieDao) {
            if(!$prestatieDao instanceof PrestatieDaoInterface) continue;

            $this->choices[$prestatieDao::getPrestatieLabel()] = get_class($prestatieDao);
            $this->daos[get_class($prestatieDao)] = $prestatieDao;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $range = range(2017, (new \DateTime('next year'))->format('Y'));
        $repo = null;

        $builder

            ->add('repository', ChoiceType::class, [
                'required' => true,
                'placeholder' => 'Selecteer een module',
                'choices' => $this->choices,
                'mapped'=>false,
            ])
            ->add('jaar', ChoiceType::class, [
                'choices' => array_combine($range, $range),
            ])

            ->add('categorie', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'Stadsdeel',
                'choices' => [
                    'Centrale stad' => Prestatie::CATEGORIE_CENTRALE_STAD,
                    'Fondsen' => Prestatie::CATEGORIE_FONDSEN,
                ],
            ])
            ->add('stadsdeel')
            ->add('aantal')
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
        $formModifier =  function (?FormInterface $form, $data) {
            $kpis = [];
            //if the data is from the select field, it is a string, key to daos.
            if(is_string($data) && $this->daos[$data] instanceof PrestatieDaoInterface)
            {
                $dao = $this->daos[$data];
                $kpis = $dao->getKpis();
            }
            $form->add('kpi', ChoiceType::class, [
                'placeholder' => '',
                'choices' => $kpis,
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


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Prestatie::class,
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
