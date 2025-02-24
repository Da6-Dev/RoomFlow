<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $Title ?? 'Roomflox' ?></title>
    <link rel="stylesheet" href="/Roomflox/public/css/style.css">
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
