<?php
// Verifica si la sesión ya está activa antes de llamar a session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluye el archivo de configuración de la base de datos
require 'config/db.php';

// Variable para almacenar mensajes de error
$error = '';
$success = '';

// Verifica si se ha enviado el formulario de creación de usuario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtiene y sanitiza las entradas del usuario
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Validaciones básicas
    if (empty($username) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        try {
            // Verifica si el nombre de usuario ya existe
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $error = "El nombre de usuario ya existe.";
            } else {
                // Hash de la contraseña
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Inserta el nuevo usuario
                $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->execute();
                
                $success = "Usuario creado exitosamente.";
            }
        } catch (PDOException $e) {
            $error = "Error en la consulta a la base de datos: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario - Lavandería Flamingo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Crear Usuario</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="create_user.php">
            <div class="mb-3">
                <label for="username" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
        </form>
    </div>
</body>
</html>
