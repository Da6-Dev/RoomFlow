<?php 
// Coloque no início de um arquivo de alta visibilidade, como o seu Layout.php

$lockFile = __DIR__ . '/last_archive_run.txt';
$runInterval = 6000; // 24 horas em segundos

// Verifica se o arquivo existe e quando foi modificado pela última vez
if (!file_exists($lockFile) || (time() - filemtime($lockFile)) > $runInterval) {
    
    // Toca no arquivo para atualizar sua data de modificação e evitar execuções simultâneas
    touch($lockFile);

    // Inclua e execute a lógica de arquivamento
    // Certifique-se de que os caminhos estão corretos
    require_once __DIR__ . '/../Models/Database.php';
    require_once __DIR__ . '/../Models/ReservationsModel.php';
    
    $reservationsModel = new ReservationsModel();
    $reservationsModel->arquivarReservasExpiradas();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $Title ?? 'RoomFlow' ?></title>
    <link rel="stylesheet" href="/RoomFlow/public/css/style.css">
</head>

    <!-- Incluindo a Navbar -->
    <?php include __DIR__ . '/partials/navbar.php'; ?>

    <main>
        <?= $content ?>
    </main>

    <!-- Incluindo o Footer -->
    <?php include __DIR__ . '/partials/footer.php'; ?>

</body> 
</html>
