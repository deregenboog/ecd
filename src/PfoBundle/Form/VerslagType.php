<?php

namespace PfoBundle\Form;

use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use PfoBundle\Entity\Verslag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VerslagType extends AbstractType
{
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
                'query_builder' => function (EntityRepository $repository) use ($options) {
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
            ->add('verslag', CKEditorType::class, ['attr' => ['rows' => 10]])
            ->add('submit', SubmitType::class)
        ;

        $builder->get('verslag')->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            if ($data === strip_tags($data)) {
                $event->setData(nl2br($data));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Verslag::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
