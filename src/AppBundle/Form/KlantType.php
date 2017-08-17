<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Stadsdeel;
use AppBundle\Entity\Postcodegebied;

class KlantType extends AbstractType
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('voornaam')
            ->add('tussenvoegsel')
            ->add('achternaam')
            ->add('roepnaam')
            ->add('geslacht', null, [
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('geslacht')
                        ->orderBy('geslacht.id', 'DESC');
                },
            ])
            ->add('geboortedatum', AppDateType::class, ['required' => false])
            ->add('land')
            ->add('nationaliteit')
            ->add('bsn', null, ['label' => 'BSN'])
            ->add('medewerker', MedewerkerType::class)
            ->add('adres')
            ->add('postcode')
            ->add('plaats')
            ->add('email')
            ->add('mobiel')
            ->add('telefoon')
            ->add('opmerking')
            ->add('geenPost', null, ['label' => 'Geen post'])
            ->add('geenEmail')
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                /* @var Klant $data */
                $data = $event->getData();
                if ($data->getPostcode()) {
                    $data->setPostcode(preg_replace('/\s/', '', strtoupper($data->getPostcode())));

                    try {
                        $stadsdeel = $this->entityManager->getRepository(Stadsdeel::class)->findOneByPostcode($data->getPostcode());
                        if ($stadsdeel) {
                            $data->setWerkgebied($stadsdeel->getStadsdeel());
                        }
                    } catch (\Exception $e) {
                        // ignore
                    }

                    try {
                        $postcodegebied = $this->entityManager->getRepository(Postcodegebied::class)->findOneByPostcode($data->getPostcode());
                        if ($postcodegebied) {
                            $data->setPostcodegebied($postcodegebied->getPostcodegebied());
                        }
                    } catch (\Exception $e) {
                        // ignore
                    }
                }
            });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Klant::class,
            'data' => null,
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
