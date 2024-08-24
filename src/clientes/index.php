<?php
include '../config/db.php';
include '../templates/header.php';

// Obtener clientes
try {
    $stmt = $pdo->query('SELECT * FROM clientes');
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error al obtener los clientes: ' . $e->getMessage());
}
?>

<h1>Clientes</h1>
<a href="create.php" class="btn btn-primary">Añadir Cliente</a>
<table class="table mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Email</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clientes as $cliente): ?>
            <tr>
                <td><?= htmlspecialchars($cliente['id'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($cliente['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($cliente['telefono'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($cliente['direccion'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($cliente['email'], ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <a href="edit.php?id=<?= urlencode($cliente['id']) ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="delete.php?id=<?= urlencode($cliente['id']) ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../templates/footer.php'; ?>
