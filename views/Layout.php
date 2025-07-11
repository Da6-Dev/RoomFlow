<?php

// Inclua e execute a lógica de arquivamento
// Certifique-se de que os caminhos estão corretos
require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/ReservationsModel.php';

$reservationsModel = new ReservationsModel();

// 1. Arquiva reservas que já expiraram
$reservationsModel->arquivarReservasExpiradas();

// 2. Atualiza o status das acomodações para ocupado/disponível (NOVA LINHA)
$reservationsModel->atualizarStatusAcomodacoes();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $Title ?? 'RoomFlow' ?></title>
    <link rel="stylesheet" href="/RoomFlow/public/css/style.css">
</head>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<main>
    <?= $content ?>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>

</body>

</html>