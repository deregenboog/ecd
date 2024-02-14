<?php

namespace VillaBundle\Form;

use AppBundle\Entity\Klant;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\KlantType;
use AppBundle\Form\MedewerkerType;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use VillaBundle\Entity\Slaper;

class SlaperType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $slaper Slaper */
        $slaper = $options['data'];

        if ($slaper instanceof Slaper
            && $slaper->getKlant() instanceof Klant
            && $slaper->getKlant()->getId()
        ) {
            $builder->add('klant', DummyChoiceType::class, [
                'dummy_label' => (string) $slaper,
            ]);
        } else {
            $builder->add('klant', KlantType::class);
        }

        $builder->add('dossierStatus',AanmeldingType::class);
        $builder->add('type', ChoiceType::class,[
            'choices'=>array_flip(Slaper::$types),
            'required'=>'true',
        ]);
        $builder->add('medewerker', MedewerkerType::class);
        $builder->add('contactpersoon', TextType::class);
        $builder->add('contactgegevensContactpersoon', TextType::class);
        $builder->add('redenSlapen', CKEditorType::class);
        $builder->add('opmerking', CKEditorType::class);




        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Slaper::class,
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
