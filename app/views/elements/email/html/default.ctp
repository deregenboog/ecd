Default html template
<pre>
<?php 
print_r($params);
$params = $this->viewVars;
echo $content;
print_r($params);
?>
</pre>
END