<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php echo $this->Html->charset('UTF-8'); ?>
    <title>
        <?php __('DRG Intakeregistratie'); ?>
        <?php echo $title_for_layout; ?>
    </title>
    <?= $html->meta('icon', $html->url('/favicon.ico')) ?>
    <?= $this->Html->css('print_screen', 'stylesheet', array('media' => 'screen')) ?>
    <?= $this->Html->css('print', 'stylesheet', array('media' => 'print')) ?>
</head>
<body id="print<?= $htmlBodyId ?>">
    <div class="logo"><?= $this->Html->image('regenboog.png'); ?></div>
    <?php echo $content_for_layout; ?>
</body>
</html>
