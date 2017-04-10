<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php echo $this->Html->charset('UTF-8'); ?>
    <title>
        <?php __('DRG Intakeregistratie'); ?>
        <?php echo $title_for_layout; ?>
    </title>
    <?php
        echo $html->meta('icon', $html->url('/rainbow.ico'));

        echo $this->Html->css('cake.generic', 'stylesheet', array('media' => 'screen'));
        echo $this->Html->css('datepicker');
        echo $this->Html->css('regenboog');
        echo $this->Html->css('jquery-ui-1.8.11.custom');

        echo $scripts_for_layout;

        echo $html->script('jquery');
        echo $html->script('datepicker');
        echo $html->script('jquery-ui-1.8.11.custom.min');
        echo $html->script('jquery.ui.datepicker-nl');
        echo $html->script('script');
        echo $html->script('fixFloat.jquery.js');
    ?>
</head>
<body id="<?= isset($htmlBodyId) ? $htmlBodyId : 'notDefined' ?>">
    <div id="container">
        <div id="header">
            <div id="title">
                <?php
                echo $this->Html->link(__('DRG Intakeregistratie', true), '/');
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
                <?php
                foreach ($menu_allowed as $controller => $text) {
                    if (isset($menuControllers[$controller]) &&
                            in_array($this->name, $menuControllers[$controller])) {
                        echo "<li>".
                            $this->Html->link(__($text, true), '/'.$controller, array('class' => 'selected')).
                            "</li>";
                    } else {
                        echo "<li>".
                            $this->Html->link(__($text, true), '/'.$controller).
                            "</li>";
                    }
                }
                if (!$user) {
                    echo "<li>".
                        $this->Html->link(__('Login', true),
                                '/medewerkers/login').
                        "</li>";
                }
                ?>
            </ul>
        </div>
        <div id="contentWrap">
            <div id="content">
            <div id="loading" style="display: none">
            Loading...
            </div>
                <?php echo $this->Session->flash(); ?>
                <?php echo $this->Session->flash('auth'); ?>

                <?php echo $content_for_layout; ?>
            </div>
        </div>
        <div id="footer">
            Ontwikkeld door Toltech Solutions
        </div>
    </div>
    <?php //echo $this->element('sql_dump'); ?>
    <?php echo $this->Js->writeBuffer();?>
</body>
</html>
