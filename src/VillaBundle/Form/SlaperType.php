<?php

namespace VillaBundle\Form;

use App\VillaBundle\Form\DossierStatussenType;
use AppBundle\Entity\Klant;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseDossierStatusType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\KlantType;
use AppBundle\Form\MedewerkerType;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use VillaBundle\Entity\Aanmelding;
use VillaBundle\Entity\Slaper;

class SlaperType extends BaseDossierStatusType
{

    public bool $test = false;
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
            $help = '';
            if($slaper->getKlant()->getBsn() == "") {
                $help = 'Bestaande klant heeft geen BSN. Graag later aanpassen aan op de basis klantkaart.';
            }
            $builder->add('klant', DummyChoiceType::class, [
                'dummy_label' => (string) $slaper,
                'help'=>$help,
                'priority'=>1000,
            ]);
        } else {
            $builder->add('klant', KlantType::class,[
                'bsn_required'=>true,
                'priority'=>1000,
            ]);
        }

//        $this->addDossierStatusField($builder);


        $builder->add('type', ChoiceType::class,[
            'choices'=>array_flip(Slaper::$types),
            'required'=>'true',
        ]);
        $builder->add('medewerker', MedewerkerType::class,[
            'help'=>'De medewerker die de eigenaar van dit dossier is.',
        ]);
        $builder->add('contactpersoon', TextType::class,[
            'required'=>false,
        ]);
        $builder->add('contactgegevensContactpersoon', TextType::class,[
            'required'=>false,
            'help'=>'Emailadres of telefoonnummer'
        ]);
        $builder->add('redenSlapen', CKEditorType::class,[
            'help'=>'Wat is de reden/achtergrond voor het slapen in de Villa?'
        ]);
        $builder->add('opmerking', CKEditorType::class,[
            'required'=>false,
            'help'=>'Alles wat goed is om te noteren en onthouden.'
        ]);

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Slaper::class,
            'openDossierStatusFormType' => AanmeldingType::class,
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return BaseDossierStatusType::class;
    }
}
