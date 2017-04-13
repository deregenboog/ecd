<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\KlantType as AppKlantType;
use HsBundle\Entity\Klant;
use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Form\AppDateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Stadsdeel;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class KlantType extends AbstractType
{
    private $werkgebiedChoices = [];

    public function __construct(EntityManager $entityManager)
    {
        $this->werkgebiedChoices = $this->getWerkgebiedChoices($entityManager);
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
            ->add('medewerker', MedewerkerType::class)
            ->add('adres')
            ->add('postcode')
            ->add('plaats')
            ->add('werkgebied', ChoiceType::class, [
                'label' => 'Stadsdeel',
                'required' => false,
                'choices' => $this->werkgebiedChoices,
            ])
            ->add('email')
            ->add('mobiel')
            ->add('telefoon')
            ->add('inschrijving', AppDateType::class, ['data' => new \DateTime('today')])
            ->add('bewindvoerder', TextareaType::class, ['required' => false])
            ->add('onHold')
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Klant::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }

    private function getWerkgebiedChoices(EntityManager $entityManager)
    {
        $stadsdelen = $entityManager->getRepository(Stadsdeel::class)
            ->createQueryBuilder('stadsdeel')
            ->select('stadsdeel.stadsdeel')
            ->distinct(true)
            ->orderBy('stadsdeel.stadsdeel')
            ->getQuery()
            ->getResult()
        ;

        $choices = [];
        foreach ($stadsdelen as $stadsdeel) {
            $choices[$stadsdeel['stadsdeel']] = $stadsdeel['stadsdeel'];
        }

        return $choices;
    }
}
