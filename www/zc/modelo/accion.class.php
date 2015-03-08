<?php

/**
 * Clase base para la creacion de elemento HTML
 */
require_once 'elementos.class.php';
/**
 * Crea la funcion de agregar (insert)
 */
require_once 'agregar.class.php';

/**
 * Crea acciones: agregar, buscar, modificar, eliminar, cancelar, defecto
 */
class accion extends elementos {

    /**
     * Formulario o tabla relacionado
     * @var string
     */
    protected $_tabla = null;

    /**
     * Tipo de accion a ejecutar
     * @var string
     */
    protected $_accion = null;

    /**
     * Conjunto de elementos tipo input del formulario, corresponde a los campos
     * @var array
     */
    protected $_campos = array();

    /**
     * Almacena la funcion que ejecuta todo el proceso en el modelo
     * @var string
     */
    protected $_funcion = '';

    /**
     * Crear las acciones, segun el tipo de elemento
     * @param array $caracteristicas Caracteristicas de la accion a crear
     * @param string $tabla Nombre de la tabla a manejar
     * @param string $accion Tipo de boton a crear
     */
    function __construct($caracteristicas, $tabla, $accion) {
        $this->_accion = $accion;
        $this->_tabla = strtolower($tabla);
        $this->_campos = $caracteristicas;
    }

    /**
     * Selecciona el tipo de accion crear segun el tipo de boton seleccionado
     */
    public function crear() {
        switch ($this->_accion) {
            case ZC_ACCION_AGREGAR:
                $accion = new agregar($this->_campos, $this->_tabla, $this->_accion);
                break;
            case ZC_ACCION_BUSCAR:
//                $accion = new buscar($this->_campos, $this->_accion);
                break;
            case ZC_ACCION_MODIFICAR:
//                $accion = new modificar($this->_campos, $this->_accion);
                break;
            case ZC_ACCION_BORRAR:
//                $accion = new borrar($this->_campos, $this->_accion);
                break;
            case ZC_ELEMENTO_RESTABLECER:
                break;
            case ZC_ELEMENTO_CANCELAR:
                break;
            case ZC_ELEMENTO_BOTON:
            default :
                break;
        }
        // Establece la accion creada
        $this->_html = (isset($accion)) ? $accion->crear()->devolver() : $this->comando('$resultado = implode(\'+\', func_get_args());$cta = 1;', 12);
        $this->_funcion = (isset($accion)) ? $accion->funcion()->devolverFuncion() : $this->comando('//$rpta[\'resultado\'] = implode(\'|\', $datos);');
        return $this;
    }

    /**
     * Adecua la sintaxis del comando pasado por parametro
     * @param string $cmd Comando a agregar
     * @param int $espacios Numero de espacios en la sangria izquierda
     * @return string
     */
    protected function comando($cmd, $espacios = 0) {
        return insertarEspacios($espacios) . $cmd . FIN_DE_LINEA;
    }

    /**
     * Asigna los variables segun tipo
     * @param array $campos Elementos a inicializar
     */
    protected function inicializar(){
        $cmd = $this->comando('$data = array();', 12);
        foreach ($this->_campos as $nro => $campo) {
            switch ($campo[ZC_ELEMENTO]) {
                case ZC_ELEMENTO_CHECKBOX:
                    $cmd .= $this->comando("\$data['{$this->_campos[$nro][ZC_ID]}'] = implode(',', json_decode(\${$campo[ZC_ID]}, true));", 12);
                    break;
                default:
                    $cmd .= $this->comando("\$data['{$this->_campos[$nro][ZC_ID]}'] = \${$campo[ZC_ID]};", 12);
                    break;
            }
        }
        return $cmd;
    }

    public function devolverFuncion(){
        return $this->_funcion;
    }
}
