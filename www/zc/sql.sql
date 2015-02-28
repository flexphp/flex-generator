CREATE DATABASE db_proyecto;
USE db_proyecto;

CREATE TABLE tb_usuarios (
    id INT AUTO_INCREMENT,
    primer_nombre VARCHAR(40) NOT NULL,
    segundo_nombre VARCHAR(40) NULL,
    primer_apellido VARCHAR(40) NOT NULL,
    segundo_apellido VARCHAR(40) NULL,
    login VARCHAR(80) NOT NULL,
    estado TINYINT(1) NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT unico_login UNIQUE (login)
) ENGINE = MyISAM;


CREATE TABLE tb_claves(
	id INT AUTO_INCREMENT,
	id_usuario INT NOT NULL,
	INDEX idx_id_usuario_claves (id_usuario),
	clave VARCHAR(256) NOT NULL,
	estado TINYINT(1) NOT NULL,
	fecha_creacion DATETIME NOT NULL,
	fecha_modificacion DATETIME NULL,
	PRIMARY KEY(id),
	CONSTRAINT usuario_clave FOREIGN KEY (id_usuario) REFERENCES tb_usuarios (id) ON DELETE CASCADE
) ENGINE = MyISAM;

INSERT INTO tb_usuarios (primer_nombre, primer_apellido, login, estado) VALUES ('administrador', 'root', 'administrador', 1);
INSERT INTO tb_claves (id_usuario, clave, estado, fecha_creacion) VALUES (1, md5('123456'), 1, NOW());