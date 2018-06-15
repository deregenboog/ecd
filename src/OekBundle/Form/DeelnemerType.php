<?php

namespace OekBundle\Form;

use AppBundle\Entity\Klant;
use AppBundle\Form\BaseType;
use AppBundle\Form\KlantType;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use OekBundle\Entity\Deelnemer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeelnemerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $deelnemer = $options['data'];

        if ($deelnemer instanceof Deelnemer
            && $deelnemer->getKlant() instanceof Klant
            && $deelnemer->getKlant()->getId()
        ) {
            // show disabled field with client if client is already set
            $builder->add('klant', null, [
                'disabled' => true,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('klant')
                        ->where('klant = :klant')
                        ->setParameter('klant', $options['data']->getKlant())
                    ;
                },
            ]);
        } else {
            $builder->add('klant', KlantType::class);
        }

        $builder->add('medewerker', MedewerkerType::class);

        $builder->add('aanmelding', AanmeldingType::class, [
            'label' => 'Aanmelding',
        ]);
        $builder->get('aanmelding')->remove('medewerker');

        if (!$deelnemer->getAanmelding()) {
            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $deelnemer = $event->getData();
                $deelnemer->getAanmelding()->setMedewerker($deelnemer->getMedewerker());
            });
        }

        $builder->add('opmerking', null, [
            'attr' => [
                'rows' => 15,
                'cols' => 50,
            ],
        ]);

        $builder->add('voedselbankklant');

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deelnemer::class,
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
