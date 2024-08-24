<?php
include '../config/db.php';
include '../templates/header.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die('ID inválido.');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Token CSRF inválido');
    }

    $stmt = $pdo->prepare('DELETE FROM pedidos WHERE id = :id');
    $stmt->execute([':id' => $id]);

    header('Location: index.php');
    exit();
} else {
    $stmt = $pdo->prepare('SELECT * FROM pedidos WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        die('Pedido no encontrado.');
    }
}
?>

<h1>Eliminar Pedido</h1>
<p>¿Estás seguro de que deseas eliminar el pedido de <strong><?= htmlspecialchars($pedido['nombre'], ENT_QUOTES, 'UTF-8') ?></strong>?</p>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"> <!-- Token CSRF -->
    <button type="submit" class="btn btn-danger">Eliminar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include '../templates/footer.php'; ?>
