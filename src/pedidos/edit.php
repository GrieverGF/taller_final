<?php
include '../config/db.php';
include '../templates/header.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die('ID inválido.');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente_id = filter_input(INPUT_POST, 'cliente_id', FILTER_VALIDATE_INT);
    $fecha = filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING);
    $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING);

    if ($cliente_id && $fecha && $estado) {
        $stmt = $pdo->prepare('UPDATE pedidos SET cliente_id = :cliente_id, fecha = :fecha, estado = :estado WHERE id = :id');
        $stmt->execute([
            ':cliente_id' => $cliente_id,
            ':fecha' => $fecha,
            ':estado' => $estado,
            ':id' => $id
        ]);

        header('Location: index.php');
        exit();
    } else {
        echo 'Error en los datos proporcionados.';
    }
} else {
    $stmt = $pdo->prepare('SELECT * FROM pedidos WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        die('Pedido no encontrado.');
    }

    $stmtClientes = $pdo->query('SELECT id, nombre FROM clientes');
    $clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);
}
?>

<h1>Editar Pedido</h1>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"> <!-- Token CSRF -->
    <div class="mb-3">
        <label for="cliente_id" class="form-label">Cliente</label>
        <select class="form-control" id="cliente_id" name="cliente_id" required>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= htmlspecialchars($cliente['id'], ENT_QUOTES, 'UTF-8') ?>" <?= $pedido['cliente_id'] == $cliente['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cliente['nombre'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="fecha" class="form-label">Fecha</label>
        <input type="date" class="form-control" id="fecha" name="fecha" value="<?= htmlspecialchars($pedido['fecha'], ENT_QUOTES, 'UTF-8') ?>" required>
    </div>
    <div class="mb-3">
        <label for="estado" class="form-label">Estado</label>
        <input type="text" class="form-control" id="estado" name="estado" value="<?= htmlspecialchars($pedido['estado'], ENT_QUOTES, 'UTF-8') ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
</form>

<?php include '../templates/footer.php'; ?>
