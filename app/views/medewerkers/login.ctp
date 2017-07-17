<div class="centeredContentWrap">
    <br>
    <p>
        <center><h2>Inloggen met Windows-wachtwoord</h2></center>
        <br>
        <strong>LET OP: gebruik je Windows-gebruikersnaam en -wachtwoord om in loggen</strong>
    </p>
    <br>
    <?= $this->Form->create('Medewerker', array('action' => 'login', 'class' => 'centered')) ?>
    <fieldset>
        <legend><?= __('Login', true) ?></legend>
        <?= $this->Form->input('username') ?>
        <?= $this->Form->input('passwd', ['autocomplete' => 'off']) // Don't use 'password' (to avoid encrypting it), use 'passwd' instead ?>
        <?= $this->Form->hidden('password') ?>
    </fieldset>
    <?= $this->Form->end(__('Login', true)) ?>
</div>
