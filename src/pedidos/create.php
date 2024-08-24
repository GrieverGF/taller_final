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

    $cliente_id = filter_input(INPUT_POST, 'cliente_id', FILTER_VALIDATE_INT);
    $fecha = htmlspecialchars($_POST['fecha'], ENT_QUOTES, 'UTF-8');
    $estado = htmlspecialchars($_POST['estado'], ENT_QUOTES, 'UTF-8');

    if ($cliente_id && $fecha && $estado) {
        try {
            $stmt = $pdo->prepare('INSERT INTO pedidos (cliente_id, fecha, estado) VALUES (:cliente_id, :fecha, :estado)');
            $stmt->execute([
                ':cliente_id' => $cliente_id,
                ':fecha' => $fecha,
                ':estado' => $estado
            ]);

            header('Location: index.php');
            exit();
        } catch (PDOException $e) {
            die('Error al agregar el pedido: ' . $e->getMessage());
        }
    } else {
        echo 'Error en los datos proporcionados.';
    }
}

// Obtener clientes
try {
    $stmtClientes = $pdo->query('SELECT id, nombre FROM clientes');
    $clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error al obtener los clientes: ' . $e->getMessage());
}
?>

<div class="container mt-5">
    <h1>Añadir Pedido</h1>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
        <div class="mb-3">
            <label for="cliente_id" class="form-label">Cliente</label>
            <select class="form-control" id="cliente_id" name="cliente_id" required>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= htmlspecialchars($cliente['id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($cliente['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" class="form-control" id="fecha" name="fecha" required>
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <input type="text" class="form-control" id="estado" name="estado" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>

<?php include '../templates/footer.php'; ?>
