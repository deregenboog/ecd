<?php

namespace PfoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\BaseType;
use PfoBundle\Entity\Groep;
use AppBundle\Form\AppDateType;
use PfoBundle\Entity\Verslag;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;

class VerslagType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medewerker', MedewerkerType::class, [
                'required' => true,
            ])
            ->add('type', ChoiceType::class, [
                'placeholder' => '',
                'required' => true,
                'choices' => [
                    'Telefonisch' => 'Telefonisch',
                    'E-mail' => 'E-Mail',
                    'In persoon' => 'In persoon',
                    'Extern' => 'Extern',
                ],
            ])
            ->add('clienten', null, [
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function(EntityRepository $repository) use ($options) {
                    $client = $options['data']->getClienten()[0];

                    $clienten = [$client];
                    if ($client->getHoofdclient()) {
                        $clienten[] = $client->getHoofdclient();
                        foreach ($client->getHoofdclient()->getGekoppeldeClienten() as $gekoppeldeClient) {
                            $clienten[] = $gekoppeldeClient;
                        }
                    }
                    foreach ($client->getGekoppeldeClienten() as $gekoppeldeClient) {
                        $clienten[] = $gekoppeldeClient;
                    }

                    return $repository->createQueryBuilder('client')
                        ->where('client IN (:clienten)')
                        ->setParameter('clienten', $clienten)
                    ;
                },
            ])
            ->add('verslag', TextareaType::class, ['attr' => ['rows' => 10]])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Verslag::class,
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
