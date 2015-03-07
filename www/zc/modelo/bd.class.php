<?php

/**
 * Ejecucion de sentencias en base de datos
 */
require_once 'conexion.class.php';

/**
 * Clase para la creacion del modelo de base de datos dependiendo del motor
 */
class bd extends conexion {

    /**
     * Motor de base de datos seleccionado por el usuario (mysql)
     * @var strig
     */
    private $_motor;

    /**
     * Almacena las equivalencias entre los distintos motores de base de datos
     * @var array
     */
    private $_equivalencias = array();

    /**
     * Propiedades de los elementos
     * @var array
     */
    private $_prop = array();

    /**
     * Sentencias para ejecutar creacion del modelo de BC
     * @var array
     */
    private $_sentencias = array();

    /**
     * Valida los datos parametrizados por el usuario y crea modelo de base datos
     * @param array $caracteristicas Caracteristicas de los elementos
     * @param string $motor Base de datos usada para alamcenar los datos de la aplicacion
     */
    function __construct($caracteristicas, $motor) {
        $this->_motor = trim(strtolower($motor));
        $this->motor();
        $this->_prop = $caracteristicas;
        $this->verificar();
        $this->equivalencias();
    }

    function motor() {
        switch ($this->_motor) {
            case ZC_MOTOR_MYSQL:
                break;
            case '':
                throw new Exception(__FUNCTION__ . ': Seleccione una opcion de guardado de datos.');
            default:
                throw new Exception(__FUNCTION__ . ': Motor (' . $this->_motor . ') no soportado por la herramienta, proximamente.');
        }
    }

    private function equivalencias() {
        $this->_equivalencias[ZC_MOTOR_MYSQL] = array(
            ZC_DATO_ALFANUMERICO => 'VARCHAR',
            ZC_DATO_NUMERICO => 'INT',
            ZC_DATO_FECHA => 'DATE',
            ZC_DATO_URL => 'VARCHAR',
            ZC_DATO_EMAIL => 'VARCHAR',
            ZC_OBLIGATORIO_NO => 'NULL',
            ZC_OBLIGATORIO_SI => 'NOT NULL',
            ZC_ETIQUETA => 'COMMENT',
            ZC_MOTOR_AUTOINCREMENTAL => 'AUTO_INCREMENT',
            'CREACION_BD' => 'CREATE DATABASE',
            'CREACION_TABLA' => 'CREATE TABLE',
            'CONDICIONAL_BD' => 'IF NOT EXISTS',
            'CONDICIONAL_TABLA' => 'IF NOT EXISTS',
            'LLAVE_PRIMARIA' => 'PRIMARY KEY',
            'CHARSET' => 'DEFAULT CHARACTER SET',
        );
    }

    /**
     * Define el tipo de dato SQL segun el tipo de dato definido por el usuario
     * @param string $elemento Tipo de elemento seleccionado
     * @param string $dato Tipo de datos que recibe el elemento
     * @return type
     */
    private function dato($dato) {
        $tipo = '';
        switch ($dato) {
            case ZC_DATO_NUMERICO:
            case ZC_DATO_ALFANUMERICO:
            case ZC_DATO_EMAIL:
            case ZC_DATO_URL:
            case ZC_DATO_FECHA:
                $tipo = $this->_equivalencias[$this->_motor][$dato];
                break;
            default:
                throw new Exception(__FUNCTION__ . ': Tipo de dato (' . $dato . ') sin equivalencia');
        }
        return $tipo;
    }

    /**
     * Longitud maxima del campo
     * @param int $maxima Longitud maxima permitida por el campo
     * @return string
     */
    protected function longitud($dato, $maxima) {
        $longitud = '';
        switch ($dato) {
            case ZC_DATO_ALFANUMERICO:
            case ZC_DATO_EMAIL:
            case ZC_DATO_URL:
                if (!is_numeric($maxima) || $maxima == 0) {
                    throw new Exception(__FUNCTION__ . ': Error en longitud maxima, por el tipo de campo es obligatorio colocarlo');
                }
                $longitud = "($maxima)";
                break;
            case ZC_DATO_FECHA:
            case ZC_DATO_NUMERICO:
            default:
                break;
        }
        return $longitud;
    }

    /**
     * Determina si el campo es obligatorio
     * @param string $obligatorio Valor true|false o 1|0
     */
    protected function nulo($obligatorio) {
        return $this->_equivalencias[$this->_motor][$obligatorio];
    }

    /**
     * Comentario del campo en la base de datos
     * @param string $etiqueta Descripcion del campo
     * @return string
     */
    protected function comentario($etiqueta) {
        return $this->_equivalencias[$this->_motor][ZC_ETIQUETA] . " '$etiqueta'";
    }

    /**
     * Establece propiedades SQL del campo, no todos los elementos necesitan creacion de campo
     * un ejemplo son los botones, asi que estos se omiten durante el proceso
     * @param string $nombre Nombre del campo en la base de datos, corresponde al ZC_ID
     * @param string $dato Tipo de dato que recibe el campo ZC_DATO, se traduce al tipo SQL correspondiente
     * @param string $longitud Longitud maxima del campo ZC_LONGITUD_MAXIMA
     * @param string $nulo Determina valores obligatorios ZC_OBLIGATORIO
     * @param string $comentario Descripcion del campo, corresponde a la etiqueta ZC_ETIQUETA
     * @param string $coma Union de los campos
     * @return string
     */
    private function campo($nombre, $dato, $longitud, $nulo, $comentario, $coma) {
        return $coma . FIN_DE_LINEA . insertarEspacios(4) . $nombre . ' ' . $this->dato($dato) . $this->longitud($dato, $longitud) . ' ' . $this->nulo($nulo) . ' ' . $this->comentario($comentario);
    }

    /**
     * Crea un campo autoincremental como identificador de la tabla
     * @return type
     */
    private function autoincremental() {
        return insertarEspacios(4) . 'id INT ' . $this->nulo(ZC_OBLIGATORIO_SI) . ' ' . $this->_equivalencias[$this->_motor][ZC_MOTOR_AUTOINCREMENTAL] . ',';
    }

    /**
     * Defiene el charset segun el motor
     * @return string
     */
    private function charset() {
        if (ZC_MOTOR_DEFAULT_CHARSET == '') {
            return '';
        }
        $charset = '';
        switch ($this->_motor) {
            case ZC_MOTOR_MYSQL:
                $charset = $this->_equivalencias[$this->_motor]['CHARSET'] . ' ' . ZC_MOTOR_DEFAULT_CHARSET;
                break;
            default:
                throw new Exception(__FUNCTION__ . ': Motor (' . $this->_motor . ') no contemplado');
        }
        return $charset;
    }

    /**
     * Establece las sentencias para la creacion de la base de datos del sistema
     */
    private function db() {
        $bd = '';
        switch ($this->_motor) {
            case ZC_MOTOR_MYSQL:
                $bd = $this->_equivalencias[$this->_motor]['CREACION_BD'] . ' ' . $this->_equivalencias[$this->_motor]['CONDICIONAL_BD'] . ' ' . ZC_CONEXION_BD . ' ' . $this->charset();
                break;
            default:
                throw new Exception(__FUNCTION__ . ': Motor (' . $this->_motor . ') no contemplado');
        }
        $this->_sentencias[] = $bd;
        return $this;
    }

    /**
     * Nombre de la tabla, sentencia de creacion segun motor
     * @return string
     * @throws Exception
     */
    private function nombreTabla() {
        $nombre = '';
        switch ($this->_motor) {
            case ZC_MOTOR_MYSQL:
                $nombre = $this->_equivalencias[$this->_motor]['CREACION_TABLA'] . ' ' . $this->_equivalencias[$this->_motor]['CONDICIONAL_TABLA'] . ' ' . $this->_prop[0][ZC_ID] . ' (' . FIN_DE_LINEA;
                break;
            default:
                throw new Exception(__FUNCTION__ . ': Motor (' . $this->_motor . ') no contemplado');
        }
        return $nombre;
    }

    /**
     * Crea la restriccion de llave primaria para la tabla
     * @return string
     * @throws Exception
     */
    private function llave($campo, $coma = '') {
        $key = '';
        switch ($this->_motor) {
            case ZC_MOTOR_MYSQL:
                $key = $coma . insertarEspacios(4) . FIN_DE_LINEA . $this->_equivalencias[$this->_motor]['LLAVE_PRIMARIA'] . ' (' . $campo . ')';
                break;
            default:
                throw new Exception(__FUNCTION__ . ': Motor (' . $this->_motor . ') no contemplado');
        }
        return $key;
    }

    /**
     * Establece las sentencias para crear cada una de las tablas del sistema
     */
    private function tabla() {
        $campos = '';
        $tabla = $this->nombreTabla();
        $tabla .= $this->autoincremental();
        foreach ($this->_prop as $nro => $caracteristicas) {
            switch (true) {
                case $nro == 0:
                // Primer elemento es la descripcion del formulario
                case $caracteristicas[ZC_ELEMENTO] == ZC_ELEMENTO_BOTON:
                // Boton
                case $caracteristicas[ZC_ELEMENTO] == ZC_ELEMENTO_RESTABLECER:
                // Boton Limpiar
                case $caracteristicas[ZC_DATO] == null:
                    // No hay tipo de dato definido, normalmente porque es boton
                    continue;
                default:
                    // Inserta coma si el siguiente campo existe
                    $coma = ('' != $campos) ? ',' : '';
                    $campos .= $this->campo($caracteristicas[ZC_ID], $caracteristicas[ZC_DATO], $caracteristicas[ZC_LONGITUD_MAXIMA], $caracteristicas[ZC_OBLIGATORIO], $caracteristicas[ZC_ETIQUETA], $coma);
                    break;
            }
        }
        $tabla .= $campos;
        $tabla .= $this->llave('id', ',');
        $tabla .= FIN_DE_LINEA . ') ' . $this->charset();
        $this->_sentencias[] = $tabla;
        return $this;
    }

    /**
     * Esteblece las propiedades necesarias para la creacion de las tablas y base de datos
     * @return \bd
     * @throws Exception
     */
    private function verificar() {
        foreach ($this->_prop as $nro => $caracteristicas) {
            if (!isset($this->_prop[$nro][ZC_ID]) || '' == trim($this->_prop[$nro][ZC_ID]) || !is_string($this->_prop[$nro][ZC_ID])) {
                throw new Exception(__FUNCTION__ . ": El campo NO tiene un identificador valido [a-Z_].");
            }
            $this->_prop[$nro][ZC_ID] = strtolower(trim($this->_prop[$nro][ZC_ID]));
            // Comentario
            $this->_prop[$nro][ZC_ETIQUETA] = (isset($this->_prop[$nro][ZC_ETIQUETA]) && '' != trim($this->_prop[$nro][ZC_ETIQUETA])) ? $this->_prop[$nro][ZC_ETIQUETA] : $this->_prop[$nro][ZC_ID];
            $this->_prop[$nro][ZC_ETIQUETA] = ucwords(trim($this->_prop[$nro][ZC_ETIQUETA]));
            // Tipo de elmento
            $this->_prop[$nro][ZC_ELEMENTO] = (isset($this->_prop[$nro][ZC_ELEMENTO]) && '' != trim($this->_prop[$nro][ZC_ELEMENTO])) ? trim($this->_prop[$nro][ZC_ELEMENTO]) : null;
            // Tipo de dato
            $this->_prop[$nro][ZC_DATO] = (isset($this->_prop[$nro][ZC_DATO]) && '' != $this->_prop[$nro][ZC_DATO]) ? $this->_prop[$nro][ZC_DATO] : null;
            // Obligatorio
            $this->_prop[$nro][ZC_OBLIGATORIO] = (isset($this->_prop[$nro][ZC_OBLIGATORIO])) ? $this->_prop[$nro][ZC_OBLIGATORIO] : ZC_OBLIGATORIO_NO;
            // Longitud
            $this->_prop[$nro][ZC_LONGITUD_MAXIMA] = (isset($this->_prop[$nro][ZC_LONGITUD_MAXIMA]) && is_int((int) $this->_prop[$nro][ZC_LONGITUD_MAXIMA])) ? $this->_prop[$nro][ZC_LONGITUD_MAXIMA] : null;
        }
        return $this;
    }

    /**
     * Crea estrucutra de la base de datos y la tabla segun el tipo de motor seleccionado por el usuario
     */
    public function crear() {
        $this->db();
        $this->tabla();
    }

    /**
     * Ejejcuta las sentencias en las tablas
     */
    public function ejecutar() {
        $this->conectar();
        foreach ($this->_sentencias as $nro => $sentencia) {
            $this->query($sentencia);
            if ($nro == 0) {
                // Despues de crear base de datos (sentencia 0)
                // Se conecta a la base de datos creada
                $this->seleccionarBD(ZC_CONEXION_BD);
            }
        }
        return $this;
    }

    /**
     * Muestra el elemento en pantalla
     * Es un metodo publico, se utiliza desde fuera de la clase, ver class formulario
     */
    public function imprimir() {
        echo preprint($this->_sentencias);
    }

    /**
     * Retorna el codigo HTML dele elemento
     * Es un metodo publico, se utiliza desde fuera de la clase, ver class formulario
     * @return string
     */
    public function devolver() {
        return implode(';', $this->_sentencias);
    }

}
