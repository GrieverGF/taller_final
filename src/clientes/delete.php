<?php
session_start();
include '../config/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die('ID inválido.');
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Token CSRF inválido');
    }

    try {
        $stmt = $pdo->prepare('DELETE FROM clientes WHERE id = :id');
        $stmt->execute([':id' => $id]);

        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        die('Error al eliminar el cliente: ' . $e->getMessage());
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

<h1>Eliminar Cliente</h1>
<p>¿Estás seguro de que deseas eliminar a <strong><?= htmlspecialchars($cliente['nombre'], ENT_QUOTES, 'UTF-8') ?></strong>?</p>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"> <!-- Token CSRF -->
    <button type="submit" class="btn btn-danger">Eliminar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include '../templates/footer.php'; ?>
