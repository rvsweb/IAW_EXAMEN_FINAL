CREATE TABLE videojuegos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) UNIQUE NOT NULL,
    descripcion TEXT,
    categoria   ENUM('Accion', 'Aventura', 'RPG', 'Deportes') NOT NULL,
    disponible  ENUM('SI', 'NO') NOT NULL DEFAULT 'SI',
    precio      DECIMAL(5,2) NOT NULL
);

INSERT INTO videojuegos (nombre, descripcion, categoria, disponible, precio) VALUES
('The Legend of Zelda', 'Aventura épica en el reino de Hyrule', 'Aventura', 'SI', 59.99),
('FIFA 24', 'Simulador de fútbol con equipos reales', 'Deportes', 'SI', 69.99),
('Elden Ring', 'RPG de mundo abierto creado por FromSoftware', 'RPG', 'SI', 49.99),
('Call of Duty', 'Shooter en primera persona multijugador', 'Accion', 'SI', 59.99),
('NBA 2K24', 'Simulador de baloncesto profesional', 'Deportes', 'NO', 64.99),
('Final Fantasy XVI', 'RPG con historia cinematográfica', 'RPG', 'SI', 54.99),
('God of War', 'Acción y aventura en la mitología nórdica', 'Accion', 'SI', 39.99),
('Hollow Knight', 'Aventura metroidvania en mundo subterráneo', 'Aventura', 'SI', 14.99),
('Dark Souls III', 'RPG desafiante con combate estratégico', 'RPG', 'NO', 29.99),
('Gran Turismo 7', 'Simulador de carreras realista', 'Deportes', 'SI', 59.99);

INSERT INTO videojuegos (nombre, descripcion, categoria, disponible, precio) VALUES
('Assassins Creed Valhalla', 'Aventura vikinga en la era medieval', 'Aventura', 'SI', 44.99),
('Cyberpunk 2077', 'RPG futurista en Night City', 'RPG', 'SI', 39.99),
('Resident Evil 4', 'Survival horror con acción intensa', 'Accion', 'SI', 59.99),
('F1 2024', 'Simulador oficial de Fórmula 1', 'Deportes', 'NO', 54.99),
('The Witcher 3', 'RPG de mundo abierto con Geralt de Rivia', 'RPG', 'SI', 29.99),
('Uncharted 4', 'Aventura de tesoros con Nathan Drake', 'Aventura', 'SI', 19.99),
('Halo Infinite', 'Shooter sci-fi con Master Chief', 'Accion', 'NO', 49.99),
('Tony Hawk Pro Skater', 'Juego de skateboarding arcade', 'Deportes', 'SI', 34.99),
('Skyrim', 'RPG de fantasía en mundo abierto', 'RPG', 'SI', 24.99),
('Tomb Raider', 'Aventura arqueológica con Lara Croft', 'Aventura', 'SI', 29.99);
