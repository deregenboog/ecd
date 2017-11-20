<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\KlantType;
use AppBundle\Form\BaseType;
use ClipBundle\Entity\Client;
use Doctrine\ORM\EntityRepository;
use ClipBundle\Entity\Behandelaar;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Geslacht;
use AppBundle\Util\PostcodeFormatter;
use AppBundle\Entity\Postcode;
use Doctrine\ORM\EntityManager;

class AddressType extends AbstractType
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
            ->add('adres')
            ->add('postcode')
            ->add('plaats')
            ->add('email')
            ->add('telefoon')
            ->add('mobiel')
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                if ($data['postcode']) {
                    $data['postcode'] = PostcodeFormatter::format($data['postcode']);
                    $event->setData($data);
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
            'inherit_data' => true,
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
