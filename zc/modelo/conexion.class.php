<?php

/**
 * Conexion a base de datos fuera de CI
 */
class conexion {

    /**
     * Recurso de conexion a la base de datos
     * @var resource
     */
    private $_link = null;

    /**
     * Ruta del archivo de conexion
     * @var string
     */
    private $_archivoConexion = '../conf/db.ini';

    /**
     * Parametriza los datos de conexion para se utilizados durante la ejecucion
     * @return \conexion
     * @throws Exception
     */
    private function datosConexion() {
        if (is_file($this->_archivoConexion)) {
            // Archivo con datos de conexion
            return miTrim(file($this->_archivoConexion));
        } elseif (defined('ZC_BD_SERVIDOR') && defined('ZC_BD_USUARIO') && defined('ZC_BD_CLAVE') && defined('ZC_BD_ESQUEMA')) {
            // Datos de conexion
            return array(ZC_BD_SERVIDOR, ZC_BD_USUARIO, ZC_BD_CLAVE, ZC_BD_ESQUEMA);
        } else {
            mostrarErrorZC(__FILE__, __FUNCTION__, 'Datos de conexion sin definir');
        }
        return $this;
    }

    /**
     * Establece la conexion de base de datos segun los parametros dados
     * @return resource
     * @throws Exception
     */
    protected function conectar() {
        if (!isset($this->_link)) {
            $datos = $this->datosConexion();
            $this->_link = mysqli_connect($datos[0], $datos[1], $datos[2]);
        }
        return $this->_link;
    }

    /**
     * Seleccionar la base de datos
     * @param string $bd Nombre de la base de datos
     */
    protected function seleccionarBD($bd) {
        mysqli_select_db($this->_link, $bd);
    }

    /**
     * Verifica que exista conexion con el servidor
     * @return boolean
     * @throws Exception
     */
    private function existeConexion() {
        if ($this->_link) {
            return true;
        } else {
            mostrarErrorZC(__FILE__, __FUNCTION__, 'Conexion a bd erronea: ' . mysqli_connect_error());
        }
        return false;
    }

    /**
     * Termina la conexion con el servidor
     * @return boolean
     * @throws Exception
     */
    private function desconectar() {
        if (isset($this->_link) && !mysqli_close($this->_link)) {
            mostrarErrorZC(__FILE__, __FUNCTION__, 'No se pudo desconectar');
        }
        return true;
    }

    /**
     * Ejecuta la sentencia en la conexion y base de datos configurada
     * @param type $sql Sentencia a ejecutar
     * @return boolean
     * @throws Exception
     */
    protected function query($sql) {
        if (strlen(trim($sql)) > 0) {
            if ($this->conectar()) {
                $ressql = mysqli_query($this->_link, $sql);
                if (!$ressql) {
                    mostrarErrorZC(__FILE__, __FUNCTION__, 'Error en sentencia : ' . mysqli_error($this->_link));
                }
                return $ressql;
            }
        } else {
            mostrarErrorZC(__FILE__, __FUNCTION__, 'Sentencia sin definir');
        }
        return false;
    }

    /**
     * Numero de filas afectadas durante la ejecucion
     * @param resource $ressql Recurso de sentencia ejecutado
     * @return int
     * @throws Exception
     */
    protected function numeroFilas($ressql) {
        if ($ressql) {
            return mysqli_num_rows($ressql);
        } else {
            mostrarErrorZC(__FILE__, __FUNCTION__, 'Sin definir recurso' . __FUNCTION__);
        }
        return 0;
    }

    /**
     * Envia un arreglo con los valores de la sentencia
     * @param resource $ressql Recurso de sentencia ejecutado
     * @return boolean
     * @throws Exception
     */
    protected function obtenerDato($ressql) {
        if ($ressql) {
            return mysqli_fetch_assoc($ressql);
        } else {
            mostrarErrorZC(__FILE__, __FUNCTION__, 'Sin definir recurso en ' . __FUNCTION__);
        }
        return false;
    }

    /**
     * Escapa caracteres especiales
     * @param string $var Variable a escapar
     * @return string
     * @throws Exception
     */
    protected function escaparCadena($var) {
        if (is_string($var) && trim($var) != '') {
            $varEscapado = mysqli_real_escape_string($this->_link, $var);
            if (!$varEscapado) {
                mostrarErrorZC(__FILE__, __FUNCTION__, 'Error escapando de: ' . $var);
            } else {
                $var = $varEscapado;
            }
        }

        return $var;
    }

    /**
     * Se desconecta de la base de datos a terminar la ejecucion
     */
    function __destruct() {
        $this->desconectar();
    }

}
