-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS lavanderia_flamingo;
USE lavanderia_flamingo;

-- Crear tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar usuarios de prueba
INSERT INTO users (username, password) VALUES 
('user1', 'user1'),
('user2', 'user2');

-- Crear tabla de clientes
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    email VARCHAR(100) UNIQUE NOT NULL
);

-- Insertar clientes de prueba
INSERT INTO clientes (nombre, telefono, direccion, email) VALUES 
('Juan Perez', '555-1234', 'Calle Falsa 123', 'juan.perez@example.com'),
('Ana Gomez', '555-5678', 'Avenida Siempre Viva 742', 'ana.gomez@example.com'),
('Carlos Lopez', '555-8765', 'Boulevard de la Esperanza 9', 'carlos.lopez@example.com');

-- Crear tabla de pedidos
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    fecha DATE NOT NULL,
    estado VARCHAR(50) NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
);

-- Insertar pedidos de prueba
INSERT INTO pedidos (cliente_id, fecha, estado) VALUES 
(1, '2024-08-01', 'Pendiente'),
(2, '2024-08-10', 'En proceso'),
(3, '2024-08-15', 'Completado'),
(1, '2024-08-20', 'Cancelado');
