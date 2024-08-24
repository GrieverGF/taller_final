<?php
session_start();
include '../config/db.php';
include '../templates/header.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Token CSRF inválido');
    }

    $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8');
    $telefono = htmlspecialchars($_POST['telefono'], ENT_QUOTES, 'UTF-8');
    $direccion = htmlspecialchars($_POST['direccion'], ENT_QUOTES, 'UTF-8');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if ($nombre && $email) {
        try {
            $stmt = $pdo->prepare('INSERT INTO clientes (nombre, telefono, direccion, email) VALUES (:nombre, :telefono, :direccion, :email)');
            $stmt->execute([
                ':nombre' => $nombre,
                ':telefono' => $telefono,
                ':direccion' => $direccion,
                ':email' => $email
            ]);

            header('Location: index.php');
            exit();
        } catch (PDOException $e) {
            die('Error al insertar el cliente: ' . $e->getMessage());
        }
    } else {
        echo 'Error en los datos proporcionados.';
    }
}
?>


<h1>Añadir Cliente</h1>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"> <!-- Token CSRF -->
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" class="form-control" id="telefono" name="telefono">
    </div>
    <div class="mb-3">
        <label for="direccion" class="form-label">Dirección</label>
        <input type="text" class="form-control" id="direccion" name="direccion">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>

<?php include '../templates/footer.php'; ?>
