<?php
session_start();

$_SESSION['caminhoPai'] = "Acomodações";
$_SESSION['pagina'] = "Cadastrar Amenidade";
include_once('../Includes/navbar.php');


?>

<div class="container-fluid py-2">
    <?php if ($alertMessage): ?>
        <div class="alert <?php echo $alertClass; ?> text-white" role="alert" id="alertMessage">
            <?php echo $alertMessage; ?>
        </div>
    <?php endif; ?>

    <form action="../App/Controllers/cadAmenidades.php" method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                    <label class="form-label">Nome da Amenidade</label>
                    <input type="text" class="form-control" name="nome" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div><button type="submit" class="btn btn-primary">Cadastrar</button></div>
        </div>
    </form>
</div>

<?php include_once('../Includes/footer.php') ?>
