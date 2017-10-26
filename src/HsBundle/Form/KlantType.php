<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use HsBundle\Entity\Klant;
use AppBundle\Form\AppDateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use AppBundle\Entity\Stadsdeel;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class KlantType extends AbstractType
{
    use MedewerkerTypeTrait;

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
        ;

        $this->addMedewerkerType($builder, $options);

        $builder
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
            ->add('inschrijving', AppDateType::class)
            ->add('bewindvoerder', TextareaType::class, ['required' => false])
            ->add('onHold')
            ->add('hulpverlener', HulpverlenerType::class)
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
