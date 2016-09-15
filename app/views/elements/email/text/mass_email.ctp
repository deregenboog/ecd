<?php

if (trim($person['name1st_part'])) {
    $name = trim($person['name1st_part']);
} else {
    $name = $person['model'];
}

?>

Dear <?php echo $name; ?>,

<?php echo trim($email_content); ?>
