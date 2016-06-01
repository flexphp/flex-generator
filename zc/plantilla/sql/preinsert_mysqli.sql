INSERT INTO estados_usuario (nombre) VALUES ('Activo');
INSERT INTO estados_usuario (nombre) VALUES ('Inactivo');
INSERT INTO estados_usuario (nombre) VALUES ('Bloqueado');

INSERT INTO tipos_usuario (nombre) VALUES ('Administrador');

INSERT INTO usuarios (login, nombre, apellidos, correo, clave, tipo_usuario, estado) VALUES ('zc', 'Zero', 'Codigo', 'fredy.mendivelso@hotmail.com', sha1('orez'), 1, 1);