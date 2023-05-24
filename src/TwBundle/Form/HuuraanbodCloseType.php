<?php

namespace TwBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use Doctrine\ORM\EntityRepository;
use TwBundle\Entity\Huuraanbod;
use TwBundle\Entity\HuuraanbodAfsluiting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HuuraanbodCloseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $huuraanbod = $options['data'];

        $builder
            ->add('afsluitdatum', AppDateType::class, ['data' => new \DateTime()])
            ->add('afsluiting', null, [
                'class' => HuuraanbodAfsluiting::class,
                'label' => 'Reden afsluiting',
                'required' => true,
                'placeholder' => 'Selecteer een item',
                'query_builder' => function (EntityRepository $repository) use ($huuraanbod) {
                    $builder = $repository->createQueryBuilder('afsluiting')
                        ->where('afsluiting.actief = true')
                    ;
                    if ($huuraanbod instanceof Huuraanbod) {
                        $builder->orWhere('afsluiting = :current')
                            ->setParameter('current', $huuraanbod->getAfsluiting())
                        ;
                    }
                },
            ])
            ->add('submit', SubmitType::class, ['label' => 'Afsluiten'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Huuraanbod::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
