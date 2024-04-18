<?php

namespace AppBundle\Form;

use AppBundle\Entity\Doelstelling;
use AppBundle\Model\Doelstellingcijfer;
use AppBundle\Repository\DoelstellingRepositoryInterface;
use AppBundle\Service\DoelstellingDao;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DoelstellingType extends AbstractType
{
    private AuthorizationCheckerInterface $authorizationChecker;

    private DoelstellingDao $doelstellingDao;

    private iterable $repositories;

    private array $choices = [];

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        DoelstellingDao $doelstellingDao,
        iterable $repositories
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->doelstellingDao = $doelstellingDao;
        $this->repositories = $repositories;
        $this->choices = $this->getRepositoryChoices(true);
    }

    private function getRepositoryChoices($onlyAvailableOptions = true): array
    {
        $choices = [];
        foreach ($this->repositories as $repository) {
            if (!$repository instanceof DoelstellingRepositoryInterface) {
                continue;
            }

            $cijfers = $this->doelstellingDao->getAvailableDoelstellingcijfers(
                $repository,
                $onlyAvailableOptions
            );

            foreach ($cijfers as $cijfer) {
                /** @var Doelstellingcijfer $cijfer */
                $label = $cijfer->getLabel();
                $classMethod = get_class($repository).'::'.$cijfer->getLabel();
                if ($this->authorizationChecker->isGranted('edit', $classMethod)) {
                    $choices[$repository->getCategory()][$label] = $classMethod;
                }
            }
        }

        return $choices;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $years = range(
            (new \DateTime('previous year'))->modify('-1 year')->format('Y'),
            (new \DateTime('next year'))->format('Y')
        );

        $disabled = false;
        if (isset($options['data']) && null !== $options['data']->getRepository()) {
            $this->choices = $this->getRepositoryChoices(false);
            $disabled = true;
        }

        $builder
            ->add('repository', ChoiceType::class, [
                'required' => true,
                'placeholder' => 'Selecteer een module',
                'choices' => $this->choices,
                'disabled' => $disabled,
            ])
            ->add('jaar', ChoiceType::class, [
                'choices' => array_combine($years, $years),
            ])
            ->add('aantal', null, [
                'required' => true,
                'label' => 'Aantal (prestatie)',
            ])
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Doelstelling::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
