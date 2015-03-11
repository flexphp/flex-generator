<?php

/**
 * Crea acciones: buscar, depende de class "accion" por la funcion  accion::devolver
 */
class buscar extends accion {

    /**
     * Crea la accion de buscar, es decir todo el proceso de creacion del SELECT en SQL
     * @param array $caracteristicas Caracteristicas de la accion
     * @param string $accion  Accion a crear
     */
    function __construct($caracteristicas, $tabla, $accion) {
        parent::__construct($caracteristicas, $tabla, $accion);
    }

    /**
     * Selecciona crea la accion buscar. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function crear() {
        if ($this->_accion !== ZC_ACCION_BUSCAR) {
            // No es la accion esperada, no crea nada
            throw new Exception(__FUNCTION__ . ': Error en la accion, se esperaba: ' . ZC_ACCION_BUSCAR);
        }
        $php = '';
        $php .= $this->comando('//Establece los valores de cada uno de los campos', 12);
        $php .= $this->inicializar($this->_campos);
        $php .= $this->comando('', 12);
        $php .= $this->comando('// Se instancia un nuevo controlador, desde la funcion no es posible acceder al $this original', 12);
        $php .= $this->comando('//Nombre de la tabla afectada', 12);
        $php .= $this->comando('$tabla = \'' . $this->_tabla . '\';', 12);
        $php .= $this->comando('$CI = new CI_Controller;', 12);
        $php .= $this->comando('$CI->load->model(\'modelo_\' . $tabla, $tabla);', 12);
        $php .= $this->comando('// Ejecucion de la accion', 12);
        $php .= $this->comando('$rpta = $CI->$tabla->agregar($data);', 12);
        $php .= $this->comando('switch (true){', 12);
        $php .= $this->comando('case (isset($rpta[\'error\']) && \'\' != $rpta[\'error\']):', 12);
        $php .= $this->comando('// Errores durante la ejecucion', 16);
        $php .= $this->comando('$Resultado[0][\'error\'] = json_encode($rpta[\'error\']);', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('default:', 12);
        $php .= $this->comando('// Resultado', 16);
        $php .= $this->comando('$resultado = $rpta[\'resultado\'];', 16);
        $php .= $this->comando('$cta = $rpta[\'cta\'];', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('}', 12);
        $this->_html = $php;
        return $this;
    }

    /**
     * Selecciona crea la accion buscar. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function funcion() {
        if ($this->_accion !== ZC_ACCION_BUSCAR) {
            // No es la accion esperada, no crea nada
            throw new Exception(__FUNCTION__ . ': Error en la accion, se esperaba: ' . ZC_ACCION_BUSCAR);
        }
        $php = '';
        $php .= $this->comando('function buscar($campos){');
        $php .= $this->comando('$rpta = array();', 8);
        $php .= $this->comando('$validacion = $this->' . $this->_tabla . '->validacionTest($campos);', 8);
        $php .= $this->comando('switch (true){', 8);
        $php .= $this->comando('case (isset($validacion[\'error\']) && \'\' != $validacion[\'error\']):', 12);
        $php .= $this->comando('// Errores durante la validacion de campos', 16);
        $php .= $this->comando('$rpta[\'error\'] = json_encode($validacion[\'error\']);', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('case !$this->db->initialize():', 12);
        $php .= $this->comando('// Error en la conexion a la base de campos', 16);
        $php .= $this->comando('$rpta[\'error\'] = json_encode(\'Error, intentelo nuevamente.\');', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('case $this->db->select(' . $this->_tabla . ', $campos) == false:', 12);
        $php .= $this->comando('// Mensaje/causa de error devuelto', 16);
        $php .= $this->comando('$rpta[\'error\'] = json_encode($this->db->_error_message());', 16);
        $php .= $this->comando('default:', 12);
        $php .= $this->comando('// Devuelve el id insertado campo y el numero de filas efectadas', 16);
        $php .= $this->comando('$rpta[\'resultado\'] = $this->db->insert_id();', 16);
        $php .= $this->comando('$rpta[\'cta\'] = $this->db->affected_rows() > 0;', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('}', 8);
        $php .= $this->comando('return $rpta;', 8);
        $php .= $this->comando('}', 4);
        $this->_funcion = $php;
        return $this;
    }

    /**
     * Crea filtros de busqueda
     * variable $resultado (IMPORTANTE)
     */
    public function filtro() {
        if ($this->_accion !== ZC_ACCION_BUSCAR) {
            // No es la accion esperada, no crea nada
            throw new Exception(__FUNCTION__ . ': Error en la accion, se esperaba: ' . ZC_ACCION_BUSCAR);
        }

        $filtrosNumericos = array(
            '=' => '',
            '=' => 'igual a',
            '>' => 'mayor a',
            '>=' => 'mayor o igual a',
            '<' => 'menor a',
            '<=' => 'menor o igual a',
            '!=' => 'deferente de',
        );
        $filtrosTexto = array(
            '=' => '',
            '=' => 'igual a',
            '% before' => 'inicia con',
            '%' => 'contiene',
            '% after' => 'termina con',
        );

        $operadorNumerico = '';
        $operadorTexto = '';

        foreach ($filtrosNumericos as $key => $value) {
            $operadorNumerico .= "<option value='$key'>$value</option>" . FIN_DE_LINEA;
        }
        foreach ($filtrosTexto as $key => $value) {
            $operadorTexto .= "<option value='$key'>$value</option>" . FIN_DE_LINEA;
        }

        $html = '';
        $elementos = '';
        $columnas = '';
        $elementos .= "<select class='form-control zc-filtros-busqueda'>" . FIN_DE_LINEA;
        foreach ($this->_campos as $nro => $campo) {
            if ($campo[ZC_DATO] == ZC_DATO_CONTRASENA) {
                // Los campos tipo contrasena no admiten busquedas
                continue;
            }
            $elementos .= "<option value='{$campo[ZC_ID]}'>{$campo[ZC_ETIQUETA]}</option>" . FIN_DE_LINEA;
            $operador = "<select id='operador-{$campo[ZC_ID]}' name='operador-{$campo[ZC_ID]}' class='form-control'>" . FIN_DE_LINEA;
            $agregar = "<button class='btn zc-filtros-agregar btn-success' id='agregar-{$campo[ZC_ID]}' name='agregar-{$campo[ZC_ID]}'><span class='glyphicon glyphicon-plus' aria-hidden='true'></span></button>" . FIN_DE_LINEA;
            //El boton de quitar se agrega en el proceso jQuery
            $buscar = "<button class='btn btn-primary zc-filtros-buscar'><span class='glyphicon glyphicon-search' aria-hidden='true'></span>Buscar</button>" . FIN_DE_LINEA;
            $ocultar = "<button class='btn btn-default zc-filtros-ocultar' title='Ocultar filtros'><span class='glyphicon glyphicon-chevron-up' aria-hidden='true'></span></button>" . FIN_DE_LINEA;
            $mostrar = "<button class='btn btn-default zc-filtros-mostrar hidden' title='Mostrar filtros'><span class='glyphicon glyphicon-chevron-down' aria-hidden='true'></span></button>" . FIN_DE_LINEA;
            switch ($campo[ZC_DATO]) {
                case ZC_DATO_NUMERICO:
                case ZC_DATO_FECHA:
                    $operador .= $operadorNumerico;
                    break;
                case ZC_DATO_URL:
                case ZC_DATO_TEXTO:
                case ZC_DATO_EMAIL:
                case ZC_DATO_AREA_TEXTO:
                case ZC_DATO_CONTRASENA:
                default :
                    $operador .= $operadorTexto;
                    break;
            }
            $operador .= '</select>' . FIN_DE_LINEA;
            // Inactiva la creacion de campos con validaciones de obligatoriedad y sin longitudes
            $this->_campos[$nro][ZC_OBLIGATORIO] = null;
            $this->_campos[$nro][ZC_LONGITUD_MAXIMA] = -1;
            $this->_campos[$nro][ZC_LONGITUD_MINIMA] = -1;
            // Los tipos de caja especiales los valida como cajas para evitar validaciones de datos, por ejemplo para los email
            $this->_campos[$nro][ZC_DATO] = (in_array($this->_campos[$nro][ZC_DATO], array(ZC_DATO_EMAIL, ZC_DATO_URL))) ? ZC_DATO_TEXTO : $this->_campos[$nro][ZC_DATO];
            $caja = new caja($this->_campos[$nro]);
            $valor = $caja->crear()->devolverElemento() . FIN_DE_LINEA;

            $oculto = ($html == '') ? '' : 'hidden';
            // La distribucion de estos div debe ser igual a la de los creados por la funcion ZCAccionAgregarFiltro
            $html .= "<div class='row zc-filtros zc-filtros-{$campo[ZC_ID]} {$oculto}'>" .
                    "<div class='col-md-1'></div>" .
                    "<div class='col-md-2'>{_elementos_}</div>" .
                    "<div class='col-md-2'>{$operador}</div>" .
                    "<div class='col-md-2'>{$valor}</div>" .
                    "<div class='col-md-1'>{$agregar}</div>" .
                    "<div class='col-md-1'>{$buscar}</div>" .
                    "<div class='col-md-1'>{$ocultar}{$mostrar}</div>" .
                    "<div class='col-md-1'>{_columnas_}</div>" .
                    "<div class='col-md-1'></div>" .
                    "</div>" . FIN_DE_LINEA;
        }
        $elementos .= "</select>" . FIN_DE_LINEA;
        $this->_filtro = str_replace('{_columnas_}', $columnas, str_replace('{_elementos_}', $elementos, $html));
        return $this;
    }

}
