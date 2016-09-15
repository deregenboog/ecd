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
 * @subpackage	  cake.cake.libs.view.templates.layouts
 * @since		  CakePHP(tm) v 0.10.0.1076
 * @license		  MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<?php
	if ($this->Session->check('Message.flash')) {
		echo "<div class='status_message'>".$this->Session->flash()."</div>";
	}
// Include no extra HTML or comments here, we use this layout to send JSON
// objects as AJAX responses :-(
?><?= $content_for_layout;?>
<?= $this->Js->writeBuffer();?>
