<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Medewerker;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

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

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Medewerker::class,
            'data' => $this->medewerker,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('medewerker')
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
