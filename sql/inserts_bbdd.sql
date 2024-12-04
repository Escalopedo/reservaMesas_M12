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

INSERT INTO tbl_horarios (hora_inicio, hora_fin) VALUES
('12:00:00', '13:00:00'),
('13:00:00', '14:00:00'),
('14:00:00', '15:00:00'),
('20:00:00', '21:00:00'),
('21:00:00', '22:00:00');


INSERT INTO tbl_ocupacion (id_mesa, id_usuario, fecha_inicio, fecha_final, fecha_reserva, id_horario, tipo_ocupacion, estado_ocupacion) VALUES
(1, 1, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(2, 2, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(3, 3, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(4, 4, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(5, 1, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(6, 2, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(7, 3, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(8, 4, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(9, 1, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(10, 2, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(11, 3, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(12, 4, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(13, 1, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(14, 2, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(15, 3, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(16, 4, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(17, 1, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(18, 2, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(19, 3, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(20, 4, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(21, 1, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(22, 2, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(23, 3, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(24, 4, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(25, 1, NULL, NULL, NULL, NULL, 'Actual', 'Disponible'),
(26, 2, NULL, NULL, NULL, NULL, 'Actual', 'Disponible');


INSERT INTO tbl_ocupacion (id_mesa, id_usuario, fecha_inicio, fecha_final, fecha_reserva, id_horario, tipo_ocupacion, estado_ocupacion) VALUES
(1, 5, '2024-12-04 12:00:00', '2024-12-04 13:00:00', '2024-12-04 12:00:00', 1, 'Reserva', 'Confirmada'),
(2, 5, '2024-12-04 13:00:00', '2024-12-04 14:00:00', '2024-12-04 13:00:00', 2, 'Reserva', 'Confirmada'),
(3, 5, '2024-12-04 14:00:00', '2024-12-04 15:00:00', '2024-12-04 14:00:00', 3, 'Reserva', 'Confirmada'),
(4, 5, '2024-12-04 20:00:00', '2024-12-04 21:00:00', '2024-12-04 20:00:00', 4, 'Reserva', 'Confirmada'),
(5, 5, '2024-12-04 21:00:00', '2024-12-04 22:00:00', '2024-12-04 21:00:00', 5, 'Reserva', 'Confirmada');
