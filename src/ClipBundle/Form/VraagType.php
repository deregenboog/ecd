<?php

namespace ClipBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use ClipBundle\Entity\Contactmoment;
use ClipBundle\Entity\Vraag;
use ClipBundle\Entity\Behandelaar;
use ClipBundle\Entity\Hulpvrager;
use ClipBundle\Entity\Leeftijdscategorie;
use ClipBundle\Entity\Communicatiekanaal;
use ClipBundle\Entity\Viacategorie;
use ClipBundle\Entity\Client;

class VraagType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var $vraag Vraag */
        $vraag = $options['data'];

        $builder
            ->add('soort', VraagsoortSelectType::class, [
                'required' => true,
                'current' => $options['data'] ? $options['data']->getSoort() : null,
            ])
            ->add('omschrijving', null, [
                'required' => true,
            ])
            ->add('hulpvrager', HulpvragerSelectType::class, [
                'required' => false,
                'current' => $options['data'] ? $options['data']->getHulpvrager() : null,
            ])
            ->add('leeftijdscategorie', LeeftijdscategorieSelectType::class, [
                'required' => false,
                'current' => $options['data'] ? $options['data']->getLeeftijdscategorie() : null,
            ])
            ->add('communicatiekanaal', CommunicatiekanaalSelectType::class, [
                'required' => false,
                'current' => $options['data'] ? $options['data']->getCommunicatiekanaal() : null,
            ])
        ;

        $this->addEmptyClientFields($builder, $vraag->getClient());

        $builder
            ->add('startdatum', AppDateType::class)
            ->add('behandelaar', BehandelaarSelectType::class, [
                'medewerker' => $options['medewerker'],
                'current' => $options['data'] ? $options['data']->getBehandelaar() : null,
            ])
        ;

        if (1 === count($vraag->getContactmomenten())) {
            $this->addEmptyContactmomentFields($builder, $vraag->getContactmoment());
        }

        $builder->add('submit', SubmitType::class);
    }

    private function addEmptyClientFields(FormBuilderInterface $builder, Client $client)
    {
        $builder->add('client', ClientType::class, [
            'data' => $client,
        ]);

        $builder->get('client')
            ->remove('person')
            ->remove('address')
            ->remove('aanmelddatum')
            ->remove('behandelaar')
            ->remove('submit')
        ;

        if ($client->getWerkgebied()) {
            $builder->get('client')->remove('werkgebied');
        }

        if ($client->getEtniciteit()) {
            $builder->get('client')->remove('etniciteit');
        }

        if ($client->getViacategorie()) {
            $builder->get('client')->remove('viacategorie');
        }

        if (0 === $builder->get('client')->count()) {
            $builder->remove('client');
        }
    }

    private function addEmptyContactmomentFields(FormBuilderInterface $builder, Contactmoment $contactmoment)
    {
        $builder->add('contactmoment', ContactmomentType::class, [
            'data' => $contactmoment,
        ]);

        $builder->get('contactmoment')
            ->remove('datum')
            ->remove('behandelaar')
            ->remove('submit')
        ;

        if ($contactmoment->getVraag()->getId()) {
            $builder->get('contactmoment')->remove('opmerking');
        }

        if (0 === $builder->get('contactmoment')->count()) {
            $builder->remove('contactmoment');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vraag::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }

    private function isNew(Vraag $vraag = null)
    {
        return is_null($vraag) || is_null($vraag->getId());
    }
}
