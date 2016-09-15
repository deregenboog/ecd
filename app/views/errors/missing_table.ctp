<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	  Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link		  http://cakephp.org CakePHP(tm) Project
 * @package		  cake
 * @subpackage	  cake.cake.libs.view.templates.errors
 * @since		  CakePHP(tm) v 0.10.0.1076
 * @license		  MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<h2><?php __('Missing Database Table'); ?></h2>
<p class="error">
				<strong><?php __('Error'); ?>: </strong>
				<?php printf(__('Database table %1$s for model %2$s was not found.', true), '<em>'.$table.'</em>',	'<em>'.$model.'</em>'); ?>
</p>
<p class="notice">
				<strong><?php __('Notice'); ?>: </strong>
	<?php
	$fixture = Inflector::singularize($table);
	$fixture_alias = 'app.'.$fixture;
	$fixture_file = APP.'tests'.DS.'fixtures'.DS.$fixture.'_fixture.php';
	// debug($fixture_file);
	$exists = file_exists($fixture_file);
	debug(Debugger::trace());

	if ($exists) {
		echo 'Testing? Try using the fixture '.$fixture_file.' by including it as \''.$fixture_alias.'\',';
	} else {
		echo 'Testing? Try creating the fixture '.$fixture_file.' and including it as \''.$fixture_alias.'\',';
	}
	echo '<br/><br/>Handling plurals can be tricky, make sure the fixture declares var $table = \''.$table.'\' and var $import = array ( \'table\' => \''.$table.'\' )';

		echo '<br/><br/>Developing? Try updating your database by running LAUNCH_ME_to_update_DB.sh';

		?>
</p>
