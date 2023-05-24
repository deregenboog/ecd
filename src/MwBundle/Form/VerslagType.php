<?php

namespace MwBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use InloopBundle\Form\LocatieSelectType;
use MwBundle\Entity\Contactsoort;
use MwBundle\Entity\Doorverwijzing;
use MwBundle\Entity\Trajecthouder;
use MwBundle\Entity\Verslag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
            ->add('locatie', LocatieSelectType::class)
            ->add('aantalContactmomenten', IntegerType::class)

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
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
