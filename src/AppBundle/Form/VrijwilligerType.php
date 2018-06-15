<?php

namespace AppBundle\Form;

use AppBundle\Entity\Postcode;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Util\PostcodeFormatter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VrijwilligerType extends AbstractType
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
                'required' => true,
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
            ->add('opmerking', AppTextareaType::class, ['required' => false])
            ->add('geenPost', null, ['label' => 'Geen post'])
            ->add('geenEmail')
            ->add('vogAangevraagd', null, ['label' => 'VOG aangevraagd'])
            ->add('vogAanwezig', null, ['label' => 'VOG aanwezig'])
            ->add('overeenkomstAanwezig', null, ['label' => 'Vrijwilligersovereenkomst aanwezig'])
            ->add('submit', SubmitType::class)
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                /* @var Klant $data */
                $data = $event->getData();
                if ($data->getPostcode()) {
                    $data->setPostcode(PostcodeFormatter::format($data->getPostcode()));

                    try {
                        $postcode = $this->entityManager->getRepository(Postcode::class)->find($data->getPostcode());
                        if ($postcode) {
                            $data
                                ->setWerkgebied($postcode->getStadsdeel())
                                ->setPostcodegebied($postcode->getPostcodegebied())
                            ;
                        }
                    } catch (\Exception $e) {
                        // ignore
                    }
                }
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vrijwilliger::class,
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
