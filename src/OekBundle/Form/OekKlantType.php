<?php

namespace OekBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Klant;
use AppBundle\Form\KlantType;
use OekBundle\Entity\OekKlant;
use AppBundle\Form\MedewerkerType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use AppBundle\Form\BaseType;

class OekKlantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $oekKlant = $options['data'];
        if ($oekKlant instanceof OekKlant
            && $oekKlant->getKlant() instanceof Klant
            && $oekKlant->getKlant()->getId()
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

        if (!$oekKlant->getOekAanmelding()) {
            $builder->add('oekAanmelding', OekAanmeldingType::class, [
                'label' => 'Aanmelding',
            ]);
            $builder->get('oekAanmelding')->remove('medewerker');
            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $oekKlant = $event->getData();
                $oekKlant->getOekAanmelding()->setMedewerker($oekKlant->getMedewerker());
            });
        }

        $builder->add('opmerking', null, [
            'attr' => [
                'rows' => 15,
                'cols' => 50,
            ],
        ]);

        $builder->add('voedselbankKlant');

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OekKlant::class,
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
