
<?php

ob_start();
print_r($guest);
$content = ob_get_clean(); // Captura o conteúdo da página
include __DIR__ . '/layout.php'; // Inclui o layout base

?>
    