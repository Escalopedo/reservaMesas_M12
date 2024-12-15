INSERT INTO tbl_sala (ubicacion_sala) VALUES
('Terraza 1'),
('Terraza 2'),
('Terraza 3'),
('Sala 1'),
('Sala 2'),
('Sala Privada 1'),
('Sala Privada 2'),
('Sala Privada 3'),
('Sala Privada 4');


INSERT INTO tbl_usuarios (nombre_usuario, apellidos_usuario, username, password, id_rol) VALUES
('Juan', 'García López', 'juang', '$2y$10$GMV4gk/G7uCYrrRf/4YnmukMLnLwYcTElPR3ssLZuS8cZ4D8JBI8W', 1),
('María', 'Pérez Fernández', 'mariap', '$2y$10$GMV4gk/G7uCYrrRf/4YnmukMLnLwYcTElPR3ssLZuS8cZ4D8JBI8W', 1),
('Carlos', 'Rodríguez Sánchez', 'carlosr', '$2y$10$GMV4gk/G7uCYrrRf/4YnmukMLnLwYcTElPR3ssLZuS8cZ4D8JBI8W', 1),
('Laura', 'Martínez Gómez', 'lauram', '$2y$10$GMV4gk/G7uCYrrRf/4YnmukMLnLwYcTElPR3ssLZuS8cZ4D8JBI8W', 1),
('Eric', 'Alcázar', 'ealcazar', '$2y$10$GMV4gk/G7uCYrrRf/4YnmukMLnLwYcTElPR3ssLZuS8cZ4D8JBI8W', 2);


-- Insertar mesas asociadas a cada sala
INSERT INTO tbl_mesa (id_sala, numero_sillas_mesa) VALUES
(1, 6),
(4, 8),  
(4, 8),  
(5, 8),  
(5, 8),  
(2, 6),
(1, 6), 
(4, 6),
(4, 6),
(5, 6),
(5, 6),
(2, 6), 
(6, 4), 
(4, 8),  
(4, 8),  
(5, 8),  
(5, 8),  
(9, 4), 
(7, 4), 
(3, 2), 
(3, 4), 
(3, 4), 
(3, 2), 
(8, 4); 

INSERT INTO tbl_roles (nombre_rol) VALUES
('Camarero'),
('Administrador');


INSERT INTO tbl_horarios (hora_inicio, hora_fin) VALUES
('12:00:00', '13:00:00'),
('13:00:00', '14:00:00'),
('14:00:00', '15:00:00'),
('20:00:00', '21:00:00'),
('21:00:00', '22:00:00');

INSERT INTO tbl_reservas (id_mesa, id_usuario, fecha_reserva, id_horario, estado_reserva) VALUES
(1, 1, '2024-12-10', 1, 'Confirmada'),
(4, 2, '2024-12-10', 2, 'Confirmada'),
(5, 3, '2024-12-11', 3, 'Confirmada'),
(6, 4, '2024-12-12', 4, 'Confirmada'),
(7, 5, '2024-12-13', 5, 'Confirmada');


INSERT INTO tbl_ocupacion (id_mesa, id_usuario, id_reserva, estado_ocupacion) VALUES
(1, 1, 1, 'Ocupado'),
(2, 1, 1, 'Ocupado'),
(3, 1, 1, 'Ocupado'),
(4, 2, 2, 'Ocupado'),
(5, 3, 3, 'Ocupado'),
(6, 4, 4, 'Ocupado'),
(7, 5, 5, 'Ocupado'),
(8, 1, 1, 'Disponible'),
(9, 2, 2, 'Disponible'),
(10, 3, 3, 'Disponible'),
(11, 4, 4, 'Disponible'),
(12, 5, 5, 'Disponible'),
(13, 5, 5, 'Disponible'),
(14, 5, 5, 'Disponible'),
(15, 5, 5, 'Disponible'),
(16, 5, 5, 'Disponible'),
(17, 5, 5, 'Disponible'),
(18, 5, 5, 'Disponible'),
(19, 5, 5, 'Disponible'),
(20, 5, 5, 'Disponible'),
(21, 5, 5, 'Disponible'),
(22, 5, 5, 'Disponible'),
(23, 5, 5, 'Disponible'),
(24, 5, 5, 'Disponible');
