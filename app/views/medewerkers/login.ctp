<div class="centeredContentWrap">
    <br>
    <p>
        <center><h2>Inloggen met Windows-wachtwoord</h2></center>
        <br>
        <strong>LET OP: gebruik je Windows-gebruikersnaam en -wachtwoord om in loggen</strong>
    </p>
    <br>
    <?= $form->create('Medewerker', array('action' => 'login', 'class' => 'centered')) ?>
    <?php  ?>
    <?= $form->inputs(['legend' => __('Login', true), 'username', 'passwd']) // Don't use 'password' (to avoid encrypting it), use 'passwd' instead ?>
    <?= $form->hidden('password') ?>
    <?= $form->end(__('Login', true)) ?>
</div>
