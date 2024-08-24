<?php
include '../config/db.php';
include '../templates/header.php';

$stmt = $pdo->query('SELECT pedidos.id, clientes.nombre, pedidos.fecha, pedidos.estado FROM pedidos JOIN clientes ON pedidos.cliente_id = clientes.id');
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Pedidos</h1>
<a href="create.php" class="btn btn-primary">Añadir Pedido</a>
<table class="table mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?= htmlspecialchars($pedido['id'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($pedido['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($pedido['fecha'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($pedido['estado'], ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <a href="edit.php?id=<?= urlencode($pedido['id']) ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="delete.php?id=<?= urlencode($pedido['id']) ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../templates/footer.php'; ?>
