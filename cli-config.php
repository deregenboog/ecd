<?php

require_once 'app/config/bootstrap-cli.php';

// Any way to access the EntityManager from  your application
$em = Registry::getInstance()->getManager();

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
	'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
	'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));