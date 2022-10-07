<?php

namespace OekraineBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use OekraineBundle\Form\LocatieSelectType;
use MwBundle\Entity\Contactsoort;
use MwBundle\Entity\Doorverwijzing;
use MwBundle\Entity\Trajecthouder;
use OekraineBundle\Entity\Verslag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VerslagType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medewerker', MedewerkerType::class)
            ->add('datum', AppDateType::class, [
                'required' => true,
            ])
            ->add('duur', ChoiceType::class, [
                'label' => 'Duur gesprek (aantal hulpverleners x aantal uren)',
                'placeholder' => '',
                'choices' => [
                        '0:20 uur' => 20,
                    '0:40 uur' => 40,
                    '1:00 uur' => 60,
                    '1:20 uur' => 80,
                    '1:40 uur' => 100,
                    '2:00 uur' => 120,
                    '4:00 uur' => 240,
                    '6:00 uur' => 360,
                    '8:00 uur' => 480,
                ],
            ])
            ->add('locatie', LocatieSelectType::class)
            ->add('contactsoort', EntityType::class, [
                'class' => Contactsoort::class,
                'required' => true,
                'expanded' => true,
            ])
//            ->add('opmerking', AppTextareaType::class, [
//                'required' => true,
//                'attr' => [
//                    'cols' => 50,
//                    'rows' => 10,
//                ],
//            ])
            ->add('opmerking', CKEditorType::class, ['attr' => ['rows' => 10,'cols'=>50],'required'=>true])
            ->add('access', ChoiceType::class,[
                'required'=>true,
                'label'=>'Zichtbaar voor',
                'choices'=>[
                    Verslag::$accessTypes[Verslag::ACCESS_MW]=>Verslag::ACCESS_MW,
                    Verslag::$accessTypes[Verslag::ACCESS_ALL]=>Verslag::ACCESS_ALL,
                    ],
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
            'data_class' => Verslag::class,
            'attr' => ['novalidate' => 'novalidate'],
            'inventarisaties' => [],
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
