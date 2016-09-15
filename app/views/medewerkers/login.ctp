<div class="centeredContentWrap">
<?php
echo $form->create('Medewerker', array('action' => 'login', 'class' => 'centered'));

// Don't use $password, to avoid encrypting it. Use $passwd instead.
echo $form->inputs(array(
  'legend' => __('Login', true),
	'username',
	  'passwd',
	  ));
echo $form->hidden('password');
echo $form->end(__('Login', true));
// echo $html->link('Forgot my password', array('action' => 'forgot_password'));
// echo " ";
// echo $html->link('Register', array('admin' => false, 'action' => 'register'));

?>
</div>
