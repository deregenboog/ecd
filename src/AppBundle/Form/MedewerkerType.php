<?php

namespace AppBundle\Form;

use AppBundle\Entity\Medewerker;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MedewerkerType extends AbstractType
{
    /**
     * @var Medewerker
     */
    private $medewerker;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->medewerker = $tokenStorage->getToken()->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['preset'] && $options['preset'] == true) {
            $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                if (!$event->getData()) {
                    $form = $event->getForm();
                    $form->setData($this->medewerker);
                }
            });
        }
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
            'preset' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
