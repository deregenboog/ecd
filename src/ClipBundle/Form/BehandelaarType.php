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
        if (isset($options['data']) && $options['data']->getId()) {
            $builder->add($this->createMedewerkerType($builder, $options['data']));
        } else {
            $builder->add($this->createMedewerkerType($builder));
        }

        $builder
            ->add('actief')
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
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

    private function createMedewerkerType(FormBuilderInterface $builder, Behandelaar $behandelaar = null)
    {
        $options = [
            'required' => true,
            'class' => Medewerker::class,
        ];

        if (!$behandelaar || !$behandelaar->getId()) {
            $options['placeholder'] = '';
            $options['query_builder'] = function (EntityRepository $repository) {
                return $repository->createQueryBuilder('medewerker')
                    ->leftJoin(Behandelaar::class, 'behandelaar', 'WITH', 'behandelaar.medewerker = medewerker')
                    ->where('behandelaar.id IS NULL')
                    ->orderBy('medewerker.voornaam')
                ;
            };
        } else {
            $options['disabled'] = true;
            $options['query_builder'] = function (EntityRepository $repository) use ($behandelaar) {
                return $repository->createQueryBuilder('medewerker')
                    ->innerJoin(Behandelaar::class, 'behandelaar', 'WITH', 'behandelaar.medewerker = medewerker')
                    ->where('behandelaar = :behandelaar')
                    ->setParameter('behandelaar', $behandelaar)
                ;
            };
        }

        return $builder->create('medewerker', EntityType::class, $options);
    }
}
