<?php

namespace TwBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwBundle\Entity\Huurovereenkomst;
use TwBundle\Entity\HuurovereenkomstAfsluiting;

class HuurovereenkomstCloseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $huurovereenkomst = $options['data'];

        $builder
            ->add('afsluitdatum', AppDateType::class, ['data' => new \DateTime()])
            ->add('afsluiting', null, [
                'class' => HuurovereenkomstAfsluiting::class,
                'label' => 'Reden afsluiting',
                'required' => true,
                'placeholder' => 'Selecteer een item',
                'query_builder' => function (EntityRepository $repository) use ($huurovereenkomst) {
                    $builder = $repository->createQueryBuilder('afsluiting')
                        ->where('afsluiting.actief = true')
                    ;
                    if ($huurovereenkomst instanceof Huurovereenkomst) {
                        $builder->orWhere('afsluiting = :current')
                            ->setParameter('current', $huurovereenkomst->getAfsluiting())
                        ;
                    }
                },
            ])
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Huurovereenkomst::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
