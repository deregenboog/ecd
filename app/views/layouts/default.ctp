<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?= $this->Html->charset('UTF-8') ?>
    <title>
        <?php __('ECD'); ?>
        <?= $title_for_layout; ?>
    </title>
    <?= $html->meta('icon', $html->url('/favicon.ico')) ?>
    <?= $this->Html->css('cake.generic', 'stylesheet', array('media' => 'screen')) ?>
    <?= $this->Html->css('datepicker') ?>
    <?= $this->Html->css('regenboog') ?>
    <?= $this->Html->css('jquery-ui-1.8.11.custom') ?>
    <?= $scripts_for_layout ?>
    <?= $html->script('jquery-1.12.4.min') ?>
    <?= $html->script('jquery-migrate-1.4.1.min') ?>
    <?= $html->script('datepicker') ?>
    <?= $html->script('jquery-ui-1.8.11.custom.min') ?>
    <?= $html->script('jquery.ui.datepicker-nl') ?>
    <?= $html->script('script') ?>
    <?= $html->script('fixFloat.jquery.js') ?>
</head>
<body id="<?= isset($htmlBodyId) ? $htmlBodyId : 'notDefined' ?>">
    <div id="container">
        <div id="header">
            <div id="title">
                <?= $this->Html->link($this->Html->image('drg-logo-50px.jpg'), '/', ['escape' => false]) ?>
                <?php
                    $user = $this->Session->read('Auth.Medewerker.LdapUser.displayname');
                    if (!$user) {
                        $user = $this->Session->read('Auth.Medewerker.LdapUser.cn');
                    }
                    if (!$user) {
                        $user = $this->Session->read('Auth.Medewerker.LdapUser.givenname');
                    }
                    if ($user) {
                        echo " -  Ingelogd als $user - ";
                        echo $this->Html->link(__('Logout', true), '/medewerkers/logout');
                    }
                ?>
            </div>
            <ul id="tabs">
                <?php foreach ($menu_allowed as $controller => $text): ?>
                    <?php if (isset($menuControllers[$controller]) && in_array($this->name, $menuControllers[$controller])): ?>
                        <li>
                            <?= $this->Html->link(__($text, true), '/'.$controller, array('class' => 'selected')) ?>
                        </li>
                    <?php else: ?>
                        <li>
                            <?= $this->Html->link(__($text, true), '/'.$controller) ?>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (!$user): ?>
                    <li>
                        <?= $this->Html->link(__('Login', true), '/medewerkers/login') ?>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <div id="contentWrap">
            <div id="content">
            <div id="loading" style="display: none">
                Loading...
            </div>
                <?= $this->Session->flash() ?>
                <?= $this->Session->flash('auth') ?>
                <?= $content_for_layout ?>
            </div>
        </div>
        <div id="footer">
            Ontwikkeld door Toltech Solutions
        </div>
    </div>
    <?php //echo $this->element('sql_dump'); ?>
    <?= $this->Js->writeBuffer() ?>
</body>
</html>
