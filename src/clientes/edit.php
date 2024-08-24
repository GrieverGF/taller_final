<?php
include '../config/db.php';
include '../templates/header.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die('ID inválido.');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8');
    $telefono = htmlspecialchars($_POST['telefono'], ENT_QUOTES, 'UTF-8');
    $direccion = htmlspecialchars($_POST['direccion'], ENT_QUOTES, 'UTF-8');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if ($nombre && $email) {
        try {
            $stmt = $pdo->prepare('UPDATE clientes SET nombre = :nombre, telefono = :telefono, direccion = :direccion, email = :email WHERE id = :id');
            $stmt->execute([
                ':nombre' => $nombre,
                ':telefono' => $telefono,
                ':direccion' => $direccion,
                ':email' => $email,
                ':id' => $id
            ]);

            header('Location: index.php');
            exit();
        } catch (PDOException $e) {
            die('Error al actualizar los datos: ' . $e->getMessage());
        }
    } else {
        echo 'Error en los datos proporcionados.';
    }
} else {
    try {
        $stmt = $pdo->prepare('SELECT * FROM clientes WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cliente) {
            die('Cliente no encontrado.');
        }
    } catch (PDOException $e) {
        die('Error al obtener los datos del cliente: ' . $e->getMessage());
    }
}
?>

<h1>Editar Cliente</h1>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"> <!-- Token CSRF -->
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($cliente['nombre'], ENT_QUOTES, 'UTF-8') ?>" required>
    </div>
    <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" class="form-control" id="telefono" name="telefono" value="<?= htmlspecialchars($cliente['telefono'], ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="mb-3">
        <label for="direccion" class="form-label">Dirección</label>
        <input type="text" class="form-control" id="direccion" name="direccion" value="<?= htmlspecialchars($cliente['direccion'], ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($cliente['email'], ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
</form>

<?php include '../templates/footer.php'; ?>
