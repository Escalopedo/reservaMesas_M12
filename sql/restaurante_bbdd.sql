-- Crear la base de datos
CREATE DATABASE restaurante_bbdd;
USE restaurante_bbdd;

-- Tabla de roles
CREATE TABLE tbl_roles (
    id_rol INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre_rol VARCHAR(50) NOT NULL UNIQUE
);

-- Tabla de usuarios
CREATE TABLE tbl_usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre_usuario VARCHAR(50) NOT NULL,
    apellidos_usuario VARCHAR(50) NOT NULL,
    username VARCHAR(25) NOT NULL UNIQUE,
    password CHAR(60) NOT NULL,
    id_rol INT NOT NULL
);

-- Tabla de salas
CREATE TABLE tbl_sala (
    id_sala INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    ubicacion_sala VARCHAR(25) NOT NULL
);

-- Tabla de mesas
CREATE TABLE tbl_mesa (
    id_mesa INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_sala INT NOT NULL,
    numero_sillas_mesa INT NOT NULL
);

-- Tabla de horarios
CREATE TABLE tbl_horarios (
    id_horario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    UNIQUE (hora_inicio, hora_fin) -- Evitar franjas duplicadas
);

-- Tabla de ocupaciones
CREATE TABLE tbl_ocupacion (
    id_ocupacion INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_mesa INT NOT NULL,
    id_usuario INT NULL,
    fecha_inicio DATETIME NULL,
    fecha_final DATETIME NULL,
    fecha_reserva DATE NULL, -- Fecha de la reserva
    id_horario INT NULL, -- Franja horaria de la reserva
    tipo_ocupacion ENUM('Actual', 'Reserva') NOT NULL DEFAULT 'Actual',
    estado_ocupacion VARCHAR(25) NOT NULL
);

-- Declarar las FOREIGN KEYS por separado

-- Relación entre tbl_usuarios y tbl_roles
ALTER TABLE tbl_usuarios
ADD CONSTRAINT FK_usuario_rol
FOREIGN KEY (id_rol) REFERENCES tbl_roles (id_rol);

-- Relación entre tbl_mesa y tbl_sala
ALTER TABLE tbl_mesa
ADD CONSTRAINT FK_mesa_sala
FOREIGN KEY (id_sala) REFERENCES tbl_sala (id_sala);

-- Relación entre tbl_ocupacion y tbl_mesa
ALTER TABLE tbl_ocupacion
ADD CONSTRAINT FK_ocupacion_mesa
FOREIGN KEY (id_mesa) REFERENCES tbl_mesa (id_mesa);

-- Relación entre tbl_ocupacion y tbl_usuarios
ALTER TABLE tbl_ocupacion
ADD CONSTRAINT FK_ocupacion_usuario
FOREIGN KEY (id_usuario) REFERENCES tbl_usuarios (id_usuario);

-- Relación entre tbl_ocupacion y tbl_horarios
ALTER TABLE tbl_ocupacion
ADD CONSTRAINT FK_ocupacion_horario
FOREIGN KEY (id_horario) REFERENCES tbl_horarios (id_horario);
