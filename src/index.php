<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'templates/header.php';
?>
<div class="container mt-5">
    <h1>Bienvenido, <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?> a Lavandería Flamingo</h1>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Gestión de Clientes</h5>
                    <p class="card-text">Añadir, editar o eliminar clientes.</p>
                    <a href="clientes/index.php" class="btn btn-primary">Ir a Clientes</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Gestión de Pedidos</h5>
                    <p class="card-text">Ver y gestionar los pedidos de los clientes.</p>
                    <a href="pedidos/index.php" class="btn btn-primary">Ir a Pedidos</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Cerrar Sesión</h5>
                    <p class="card-text">Salir de la aplicación de manera segura.</p>
                    <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
