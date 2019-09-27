<?php

namespace ClipBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\BaseType;
use ClipBundle\Entity\Behandelaar;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BehandelaarType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $behandelaar = $options['data'];
        if ($this->isNew($behandelaar)) {
            $builder
                ->add('medewerker', EntityType::class, [
                    'placeholder' => '',
                    'required' => false,
                    'class' => Medewerker::class,
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository->createQueryBuilder('medewerker')
                            ->leftJoin(Behandelaar::class, 'behandelaar', 'WITH', 'behandelaar.medewerker = medewerker')
                            ->where('behandelaar.id IS NULL')
                            ->orderBy('medewerker.voornaam')
                        ;
                    },
                ])
                ->add('naam', null, ['required' => false])
            ;
        } elseif ($behandelaar->getMedewerker()) {
            $builder->add('medewerker', EntityType::class, [
                'disabled' => true,
                'class' => Medewerker::class,
                'query_builder' => function (EntityRepository $repository) use ($behandelaar) {
                    return $repository->createQueryBuilder('medewerker')
                        ->innerJoin(Behandelaar::class, 'behandelaar', 'WITH', 'behandelaar.medewerker = medewerker')
                        ->where('behandelaar = :behandelaar')
                        ->setParameter('behandelaar', $behandelaar)
                    ;
                },
            ]);
        } else {
            $builder->add('naam', null, ['required' => true]);
        }

        $builder
            ->add('actief')
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Behandelaar ::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }

    private function isNew(Behandelaar $behandelaar = null)
    {
        return is_null($behandelaar) || is_null($behandelaar->getId());
    }
}
