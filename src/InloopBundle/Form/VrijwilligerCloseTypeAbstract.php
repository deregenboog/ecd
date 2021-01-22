<?php

namespace InloopBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VrijwilligerCloseTypeAbstract extends AbstractType
{
    protected $class;
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('afsluitdatum', AppDateType::class, [
                'required' => true,
            ])
            ->add('afsluitreden', null, [
                'placeholder' => '',
                'required' => true,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('afsluitreden')
                        ->where('afsluitreden.actief = true')
                        ->orWhere('afsluitreden = :current')
                        ->orderBy('afsluitreden.naam')
                        ->setParameter('current', $options['data'] ? $options['data']->getAfsluitreden() : null)
                    ;
                },
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->class,
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
