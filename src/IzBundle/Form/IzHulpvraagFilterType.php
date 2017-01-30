<?php
namespace IzBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Medewerker;
use AppBundle\Form\KlantFilterType;
use IzBundle\Entity\IzHulpvraag;
use IzBundle\Entity\IzProject;
use IzBundle\Filter\IzHulpvraagFilter;

class IzHulpvraagFilterType extends IzKoppelingFilterType
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('startdatum', $options['enabled_filters'])) {
            $builder->add('startdatum', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => [
                    'placeholder' => 'dd-mm-jjjj'
                ]
            ]);
        }

        if (key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }
        
        if (in_array('izProject', $options['enabled_filters'])) {
            $builder->add('izProject', EntityType::class, [
                'required' => false,
                'class' => IzProject::class,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('izProject')
                        ->where('izProject.einddatum IS NULL OR izProject.einddatum >= :now')
                        ->orderBy('izProject.naam', 'ASC')
                        ->setParameter('now', new \DateTime());
                }
            ]);
        }

        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(IzHulpvraag::class, 'izHulpvraag', 'WITH', 'izHulpvraag.medewerker = medewerker')
                        ->orderBy('medewerker.achternaam', 'ASC');
                }
            ]);
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IzHulpvraagFilter::class,
            'enabled_filters' => []
        ]);
    }
}
