<?php

if (isset($person['voornaam']) && trim($person['voornaam'])) {
    $name = trim($person['voornaam']);
} else {
    $name = $person['model'];
}

#Dear <?php echo $name; ,
#<br />
#<br />
?>
<?php echo nl2br(trim($email_content)); ?>
