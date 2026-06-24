CREATE DATABASE IF NOT EXISTS mi_bd
    CHARACTER SET utf8
    COLLATE utf8_general_ci;

USE mi_bd;

CREATE TABLE IF NOT EXISTS usuarios (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    nombre  VARCHAR(100) NOT NULL,
    email   VARCHAR(100) NOT NULL UNIQUE,
    edad    TINYINT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO usuarios (nombre, email, edad) VALUES
    ('Juan Pérez',   'juan@example.com',   28),
    ('María García', 'maria@example.com',  32),
    ('Carlos López', 'carlos@example.com', 25);
