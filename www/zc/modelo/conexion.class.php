<?php

class conexion {

    private $_link = null;
    private $_archivoConexion = '../configuracion/db.ini';

    function __construct() {

    }

    private function datosConexion() {
        if (is_file($this->_archivoConexion)) {
            return miTrim(file($this->_archivoConexion));
        } else {
            throw new Exception("Archivo de conexion no encontrado");
        }
        return false;
    }

    protected function conectar() {
        $datos = $this->datosConexion();
        if ($datos) {
            $this->_link = mysqli_connect($datos[0], $datos[1], $datos[2]);
            if (!$this->_link) {
                throw new Exception("Conexion a bd inconsistente");
            }
            mysqli_select_db($this->_link, $datos[3]);
            return $this->_link;
        }
        return false;
    }

    private function existeConexion() {
        if ($this->_link) {
            return true;
        } else {
            throw new Exception("No existe conexion");
        }
        return false;
    }

    private function desconectar() {
        if (mysqli_close($this->_link)) {
            return true;
        } else {
            throw new Exception("No se pudo desconectar");
        }
        return false;
    }

    protected function query($sql) {
        if (strlen(trim($sql)) > 0) {
            if ($this->existeConexion()) {
                $ressql = mysqli_query($this->_link, $sql);
                if (!$ressql) {
                    throw new Exception("Error en sentencia");
                }
                return $ressql;
            }
        } else {
            throw new Exception("Sentencia sin definir");
        }
        return false;
    }

    protected function numeroFilas($ressql) {
        if ($ressql) {
            return mysqli_num_rows($ressql);
        } else {
            throw new Exception("Sin definir recurso");
        }
        return 0;
    }

    protected function obtenerDato($ressql) {
        if ($ressql) {
            return mysqli_fetch_assoc($ressql);
        } else {
            throw new Exception("Sin definir recurso en " . __FUNCTION__);
        }
        return false;
    }

    protected function escaparCadena($var) {
        if (is_string($var) && trim($var) != '') {
            $varEscapado = mysqli_real_escape_string($this->_link, $var);
            if (!$varEscapado) {
                throw new Exception("Error en parseo de: " . $var);
            } else {
                $var = $varEscapado;
            }
        }

        return $var;
    }

    function __destruct() {
        $this->desconectar();
    }

}
