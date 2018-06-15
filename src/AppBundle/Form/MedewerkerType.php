<?php

namespace AppBundle\Form;

use AppBundle\Entity\Medewerker;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedewerkerType extends AbstractType
{
    /**
     * @var Medewerker
     */
    private $medewerker;

    public function __construct(EntityManager $entityManager)
    {
        if (isset($_SESSION['Auth']['Medewerker']['id'])) {
            $medewerkId = $_SESSION['Auth']['Medewerker']['id'];
            $this->medewerker = $entityManager->find(Medewerker::class, $medewerkId);
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            if (!$event->getData()) {
                $event->getForm()->setData($this->medewerker);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Medewerker::class,
            'placeholder' => 'Selecteer een medewerker',
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('medewerker')
                    ->where('medewerker.actief = true')
                    ->orderBy('medewerker.voornaam');
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityType::class;
    }
}
