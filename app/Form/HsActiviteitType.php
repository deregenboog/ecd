<?php

namespace App\Form;

use Symfony\Component\Form\Forms;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmTypeGuesser;
use App\Entity\Klant;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\HsActiviteit;

class HsActiviteitType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('naam')
		;
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => HsActiviteit::class,
		]);
	}
}
