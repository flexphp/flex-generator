<?php

// Ejecucion de sentencias en base de datos
require RUTA_GENERADOR_CODIGO . '/modelo/conexion.class.php';

/**
 * Clase para la creacion del modelo de base de datos dependiendo del motor
 */
class bd extends conexion {

    /**
     * Motor de base de datos seleccionado por el usuario (mysql)
     * @var string
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
     * Sentencias para crear los join, se unen a las sentencias la final del proceso
     * @var array
     */
    private $_join = array();

    /**
     * Valida los datos parametrizados por el usuario y crea modelo de base datos
     * @param array $caracteristicas Caracteristicas de los elementos
     * @param string $motor Base de datos usada para alamcenar los datos de la aplicacion
     */
    function __construct($motor) {
        $this->motor($motor);
        $this->equivalencias();
    }

    /**
     * Valida el motor utilizado para la creacion de la base de datos
     * @param string $motor
     */
    function motor($motor) {
        $this->_motor = strtolower(trim($motor));
        switch ($this->_motor) {
            case ZC_MOTOR_MYSQL:
                break;
            case '':
                mostrarErrorZC(__FILE__, __FUNCTION__, 'Seleccione una opcion de guardado de datos [' . $this->_motor . '].');
            default:
                mostrarErrorZC(__FILE__, __FUNCTION__, 'Motor (' . $this->_motor . ') no soportado por la herramienta, quizas proximamente.');
        }
    }

    private function equivalencias() {
        $this->_equivalencias[ZC_MOTOR_MYSQL] = array(
            ZC_DATO_TEXTO => 'VARCHAR',
            ZC_DATO_AREA_TEXTO => 'VARCHAR',
            ZC_DATO_CONTRASENA => 'VARCHAR',
            ZC_DATO_NUMERICO => 'INT',
            'ZC_DATO_NUMERICO_PEQUENO' => 'TINYINT',
            ZC_DATO_FECHA => 'DATE',
            ZC_DATO_FECHA_HORA => 'DATETIME',
            ZC_DATO_HORA => 'TIME',
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
            ZC_BD_CHARSET => 'DEFAULT CHARACTER SET',
            ZC_VALOR_PREDETERMINADO => 'DEFAULT',
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
            case ZC_DATO_TEXTO:
            case ZC_DATO_AREA_TEXTO:
            case ZC_DATO_EMAIL:
            case ZC_DATO_URL:
            case ZC_DATO_FECHA:
            case ZC_DATO_FECHA_HORA:
            case ZC_DATO_HORA:
            case ZC_DATO_CONTRASENA:
            case 'ZC_DATO_NUMERICO_PEQUENO':
                $tipo = $this->_equivalencias[$this->_motor][$dato];
                break;
            default:
                mostrarErrorZC(__FILE__, __FUNCTION__, ': Tipo de dato (' . $dato . ') sin equivalencia');
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
            case ZC_DATO_TEXTO:
            case ZC_DATO_AREA_TEXTO:
            case ZC_DATO_EMAIL:
            case ZC_DATO_URL:
                if (!is_numeric($maxima) || $maxima == 0) {
                    mostrarErrorZC(__FILE__, __FUNCTION__, ': Error en longitud maxima, por el tipo de campo es obligatorio colocarlo');
                }
                $longitud = "($maxima)";
                break;
            case ZC_DATO_CONTRASENA:
                // SHA1 = 40
                $longitud = "(40)";
                break;
            case ZC_DATO_FECHA:
            case ZC_DATO_FECHA_HORA:
            case ZC_DATO_HORA:
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
     * Determina si el campo es obligatorio
     * @param string $obligatorio Valor true|false o 1|0
     */
    protected function predeterminado($valor) {
        if ($valor != '') {
            return ' ' . $this->_equivalencias[$this->_motor][ZC_VALOR_PREDETERMINADO] . " '$valor'";
        }
        return '';
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
    private function campo($nombre, $dato, $longitud, $nulo, $default ,$comentario, $coma) {
        return tabular($nombre . ' ' . $this->dato($dato) . $this->longitud($dato, $longitud) . ' ' . $this->nulo($nulo) . $this->predeterminado($default) . ' ' . $this->comentario($comentario) . $coma, 4);
    }

    /**
     * Crea un campo autoincremental como identificador de la tabla
     * @return type
     */
    private function autoincremental() {
        return tabular('id INT ' . $this->nulo(ZC_OBLIGATORIO_SI) . ' ' . $this->_equivalencias[$this->_motor][ZC_MOTOR_AUTOINCREMENTAL] . ',', 4);
    }

    /**
     * Defiene el charset segun el motor
     * @return string
     */
    private function charset() {
        if (ZC_BD_CHARSET == '') {
            return '';
        }
        $charset = '';
        switch ($this->_motor) {
            case ZC_MOTOR_MYSQL:
                $charset = $this->_equivalencias[$this->_motor][ZC_BD_CHARSET] . ' ' . ZC_BD_CHARSET;
                break;
            default:
                mostrarErrorZC(__FILE__, __FUNCTION__, ': Motor (' . $this->_motor . ') no contemplado');
        }
        return $charset;
    }

    /**
     * Establece las sentencias para la creacion de la base de datos del sistema
     */
    private function esquema() {
        $bd = '';
        switch ($this->_motor) {
            case ZC_MOTOR_MYSQL:
                $bd = $this->_equivalencias[$this->_motor]['CREACION_BD'] . ' ' . $this->_equivalencias[$this->_motor]['CONDICIONAL_BD'] . ' ' . ZC_BD_ESQUEMA . ' ' . $this->charset();
                break;
            default:
                mostrarErrorZC(__FILE__, __FUNCTION__, ': Motor (' . $this->_motor . ') no contemplado');
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
                $nombre = tabular($this->_equivalencias[$this->_motor]['CREACION_TABLA'] . ' ' . $this->_equivalencias[$this->_motor]['CONDICIONAL_TABLA'] . ' ' . $this->_prop[0][ZC_ID] . ' (', 0);
                break;
            default:
                mostrarErrorZC(__FILE__, __FUNCTION__, ': Motor (' . $this->_motor . ') no contemplado');
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
                $key = tabular($this->_equivalencias[$this->_motor]['LLAVE_PRIMARIA'] . ' (' . $campo . ')' . $coma, 4);
                break;
            default:
                mostrarErrorZC(__FILE__, __FUNCTION__, ': Motor (' . $this->_motor . ') no contemplado');
        }
        return $key;
    }

    /**
     * Establece las sentencias para crear cada una de las tablas del sistema
     * @param array $prop Caracteristicas de las tablas
     * @return \bd
     */
    private function tabla($prop) {
        $this->_prop = $prop;
        unset($prop);
        // Determina el nombre de la tabla segun las caracteristicas del formulario
        $this->_prop[0][ZC_ID] = strtolower($this->_prop[0][ZC_ID]);
        if (in_array($this->_prop[0][ZC_ID], array(strtolower(ZC_LOGIN_PAGINA)))) {
            // La ventana de logueo no crea tabla, usa campos definidos en otros tablas
            return $this;
        }
        $this->verificar();
        $campos = '';
        $tabla = $this->nombreTabla();
        $campos .= $this->autoincremental();
        foreach ($this->_prop as $nro => $caracteristicas) {
            switch (true) {
                case $nro == 0:
                    // Primer elemento es la descripcion del formulario
                case $caracteristicas[ZC_DATO] === null:
                    // No hay tipo de dato definido, normalmente porque es boton
                    continue;
                default:
                    // Inserta coma si el siguiente campo existe
                    $coma = (isset($this->_prop[($nro + 1)])) ? ',' : '';
                    $campos .= $this->campo($caracteristicas[ZC_ID], $caracteristicas[ZC_DATO], $caracteristicas[ZC_LONGITUD_MAXIMA], $caracteristicas[ZC_OBLIGATORIO], $caracteristicas[ZC_VALOR_PREDETERMINADO], $caracteristicas[ZC_ETIQUETA], $coma);
                    if(error_get_last()){
                        var_dump($caracteristicas);
                        die;
                    }
                    $this->join($caracteristicas[ZC_ID], $caracteristicas[ZC_ELEMENTO_OPCIONES]);
                    break;
            }
        }
        $tabla .= $campos;
        // Agrega campo para determinar el estado del registro, los registros no se eliminan
        // de la base de datos, cambian de estado
        $tabla .= $this->campo('zc_eliminado', 'ZC_DATO_NUMERICO_PEQUENO', 1, ZC_OBLIGATORIO_NO, 0, 'Registro eliminado?', ',');
        $tabla .= $this->llave('id');
        $tabla .= ') ' . $this->charset();
        $this->_sentencias[] = $tabla;
        return $this;
    }

    /**
     * Crea los join de la tabla, solo si los tiene
     * @param string $campo Campo foraneo en la tabla
     * @param string $join Join entregado por el usuario en el XML
     * @return \bd
     */
    private function join($campo, $join) {
        $joinTabla = joinTablas($join);
        if (isset($joinTabla)) {
            $joinTabla['tabla'] = strtolower($joinTabla['tabla']);
            $this->_join[] = "ALTER TABLE {$this->_prop[0][ZC_ID]} ADD CONSTRAINT zc_fk_2_{$joinTabla['tabla']} FOREIGN KEY ({$campo}) REFERENCES {$joinTabla['tabla']}(id) ON UPDATE CASCADE ON DELETE RESTRICT";
        }
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
                mostrarErrorZC(__FILE__, __FUNCTION__, ": El campo NO tiene un identificador valido [a-Z_].");
            }
            // Nombre del campo
            $this->_prop[$nro][ZC_ID] =(isset($this->_prop[$nro][ZC_CAMPO_BD]) && '' != $this->_prop[$nro][ZC_CAMPO_BD]) ? trim($this->_prop[$nro][ZC_CAMPO_BD]) : trim($this->_prop[$nro][ZC_ID]);
            // Comentario
            $this->_prop[$nro][ZC_ETIQUETA] = (isset($this->_prop[$nro][ZC_ETIQUETA]) && '' != trim($this->_prop[$nro][ZC_ETIQUETA])) ? ucwords(trim($this->_prop[$nro][ZC_ETIQUETA])) : ucwords($this->_prop[$nro][ZC_ID]);
            // Tipo de elmento
            $this->_prop[$nro][ZC_ELEMENTO] = (isset($this->_prop[$nro][ZC_ELEMENTO]) && '' != trim($this->_prop[$nro][ZC_ELEMENTO])) ? trim($this->_prop[$nro][ZC_ELEMENTO]) : null;
            // Tipo de dato
            $this->_prop[$nro][ZC_DATO] = (isset($this->_prop[$nro][ZC_DATO]) && '' != $this->_prop[$nro][ZC_DATO]) ? $this->_prop[$nro][ZC_DATO] : null;
            // Obligatorio
            $this->_prop[$nro][ZC_OBLIGATORIO] = (isset($this->_prop[$nro][ZC_OBLIGATORIO])) ? $this->_prop[$nro][ZC_OBLIGATORIO] : ZC_OBLIGATORIO_NO;
            // Longitud
            $this->_prop[$nro][ZC_LONGITUD_MAXIMA] = (isset($this->_prop[$nro][ZC_LONGITUD_MAXIMA]) && is_int((int) $this->_prop[$nro][ZC_LONGITUD_MAXIMA])) ? $this->_prop[$nro][ZC_LONGITUD_MAXIMA] : ZC_LONGITUD_PREDETERMINADA;
            // Llaves foraneas
            $this->_prop[$nro][ZC_ELEMENTO_OPCIONES] = (isset($this->_prop[$nro][ZC_ELEMENTO_OPCIONES]) && '' != $this->_prop[$nro][ZC_ELEMENTO_OPCIONES]) ? $this->_prop[$nro][ZC_ELEMENTO_OPCIONES] : null;
            // Valor por defecto
            $this->_prop[$nro][ZC_VALOR_PREDETERMINADO] = (isset($this->_prop[$nro][ZC_VALOR_PREDETERMINADO])) ? $this->_prop[$nro][ZC_VALOR_PREDETERMINADO] : null;
        }
        return $this;
    }

    /**
     * Ejejcuta las sentencias en las tablas
     */
    public function ejecutar() {
        foreach ($this->_sentencias as $nro => $sentencia) {
            $this->query($sentencia);
            if ($nro == 0) {
                // Despues de crear base de datos (sentencia 0)
                // Se conecta a la base de datos creada
                $this->seleccionarBD(ZC_BD_ESQUEMA);
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
        return implode(';' . str_repeat(FIN_DE_LINEA, 2), $this->_sentencias);
    }

    /**
     * Agrega los join al fianl del scrip de creacion de sentencias
     * @return \bd
     */
    private function fin() {
        foreach ($this->_join as $join) {
            array_push($this->_sentencias, $join);
        }
        // Carga los insert por defecto
        $archivo = 'plantilla/sql/preinsert_' . $this->_motor . '.sql';
        if(is_file($archivo)) {
            // Valida que exista el archivo
            $insert = explode(';', file_get_contents($archivo));
            foreach ($insert as $sql) {
                $sql = trim(preg_replace('/\s+/', ' ', $sql));
                if (strlen($sql) > 0) {
                    array_push($this->_sentencias, $sql);   
                }
            }
        }
        return $this;
    }

    /**
     * Crea el archivo sql para la creacion de la base de datos
     * @return \bd
     */
    private function crear() {
        crearArchivo('sql/', 'script_' . $this->_motor, 'sql', $this->devolver());
        return $this;
    }

    /**
     * Agrupa toda la funcionalidad para crear la base de datos
     */
    public function crearModelo($tablas) {
        if (count($tablas) > 0) {
            $this->esquema();
            foreach ($tablas as $nro => $tabla) {
                $this->tabla($tabla);
            }
            $this->fin();
            $this->crear();
            $this->ejecutar();
        }
    }
}
