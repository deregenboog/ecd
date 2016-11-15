<?php

namespace App\Form;

use App\Entity\Klant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\HsKlant;
use Doctrine\ORM\EntityRepository;

class HsKlantType extends AbstractType
{
	const MODE_ENTER = 'enter';
	const MODE_SELECT = 'select';

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		switch ($options['mode']) {
			case self::MODE_ENTER:
				$builder->add('klant', KlantType::class);
				$builder->get('klant')->remove('email')->remove('medewerker');
				break;
			case self::MODE_SELECT:
				$builder->add('klant', null, [
					'query_builder' => function(EntityRepository $repository) use ($options) {
						$builder = $repository->createQueryBuilder('k');

						if ($options['filter']) {
							$builder
								->where('k.voornaam LIKE :voornaam')
								->setParameter('voornaam', $options['filter']->getVoornaam());
						}

						return $builder;
					}
				]);
				break;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => HsKlant::class,
			'mode' => self::MODE_ENTER,
			'filter' => null,
		]);
	}
}
