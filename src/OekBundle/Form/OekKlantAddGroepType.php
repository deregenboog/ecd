<?php

namespace OekBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use OekBundle\Form\Model\OekKlantFacade;
use OekBundle\Entity\OekGroep;

class OekKlantAddGroepType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oekGroep', EntityType::class, [
            'label' => 'Groep',
            'placeholder' => 'Selecteer een groep',
            'class' => OekGroep::class,
            'query_builder' => function (EntityRepository $repository) use ($options) {
                /* @var OekKlantFacade $oekKlant */
                $oekKlant = $options['data'];

                $builder = $repository->createQueryBuilder('groep');

                if (count($oekKlant->getOekGroepen()) > 0) {
                    $builder
                        ->where('groep NOT IN (:groepen)') // is not member of group
                        ->setParameter('groepen', $oekKlant->getOekGroepen())
                    ;
                }

                return $builder;
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OekKlantFacade::class,
        ]);
    }
}
