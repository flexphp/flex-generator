<?php

class usuario extends conexion {

    private $_login, $_clave;

    function __construct() {
        $this->conectar();
    }

    function datosLogueo($datos) {
        if (is_array($datos)) {
            $this->_login = $this->escaparCadena($datos['login']);
            $this->_clave = $this->escaparCadena($datos['clave']);
        } else {
            throw new Exception("Datos no validos");
        }
        return true;
    }

    function loguear() {
        $sql = "
			SELECT usr.id
			FROM tb_usuarios usr
				JOIN tb_claves clv ON usr.id = clv.id_usuario
			WHERE usr.login = ':login'
				AND clv.clave = ':clave'
				AND usr.estado = 1
				AND clv.estado = 1
		";
        $sql = str_replace(array(':login', ':clave'), array($this->_login, md5($this->_clave)), $sql);

        $ressql = $this->query($sql);
        $cta = $this->numeroFilas($ressql);
        if ($cta > 0) {
            $info = $this->obtenerDato($ressql);
            $this->_id = $info['id'];
            return true;
        } else {
            throw new Exception("Por favor verifique los datos de acceso");
        }
        return false;
    }

}
