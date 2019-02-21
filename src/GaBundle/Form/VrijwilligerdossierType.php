<?php

namespace GaBundle\Form;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\VrijwilligerType;
use AppBundle\Service\NameFormatter;
use GaBundle\Entity\Vrijwilligerdossier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\MedewerkerType;

class VrijwilligerdossierType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dossier = $options['data'];

        $builder->add('aanmelddatum', AppDateType::class);
        $builder->add('medewerker', MedewerkerType::class);

        if ($this->hasVrijwilliger($dossier)) {
            $builder->add('vrijwilliger', DummyChoiceType::class, [
                'dummy_label' => NameFormatter::formatInformal($dossier->getVrijwilliger()),
            ]);
        } else {
            $builder->add('vrijwilliger', VrijwilligerType::class);
        }

        $builder->add('submit', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vrijwilligerdossier::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }

    private function hasVrijwilliger(Vrijwilligerdossier $dossier = null)
    {
        return $dossier && $dossier->getVrijwilliger() instanceof Vrijwilliger && $dossier->getVrijwilliger()->getId();
    }
}
