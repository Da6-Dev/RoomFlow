<?php 

ob_start();

?>


<h1><?php echo $title; ?></h1>
<h1><?php echo $description; ?></h1>


<?php 

$content = ob_get_clean();
include __DIR__ . '/Layout.php'

?>