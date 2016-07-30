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
     * Crea la accion buscar. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function crear() {
        if ($this->_accion !== ZC_ACCION_BUSCAR) {
            // No es la accion esperada, no crea nada
            mostrarErrorZC(__FILE__, __FUNCTION__, ' Error en la accion, se esperaba: ' . ZC_ACCION_BUSCAR);
        }
        $php = '';
        $php .= $this->comando('// Se instancia un nuevo controlador, desde la funcion no es posible acceder al $this original', 12);
        $php .= $this->comando('$CI = new CI_Controller;', 12);
        $php .= $this->comando('$CI->load->model(\'' . $this->_modelo . '\', \'modelo\');', 12);
        $php .= $this->comando('// Ejecucion de la accion', 12);
        $php .= $this->comando('$rpta = $CI->modelo->buscar($filtros, \'buscar\', $pagina);', 12);

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
            mostrarErrorZC(__FILE__, __FUNCTION__, ' Error en la accion, se esperaba: ' . ZC_ACCION_BUSCAR);
        }
        $php = '';
        $php .= $this->comando('function buscar($campos, $accion, $pagina){', 4);
        $php .= $this->comando('$rpta = array();', 8);
        $php .= $this->comando('$campos = $this->security->xss_clean($campos);', 8);
        $php .= $this->comando('$pagina = (!is_int($pagina)) ? 1 : $pagina;', 8);
        $php .= $this->comando('$porPagina = ' . ZC_REGISTROS_POR_PAGINA . ';', 8);
        $php .= $this->comando('$validacion = $this->validarFiltros($campos, $accion);', 8);
        $php .= $this->comando('switch (true){', 8);
        $php .= $this->comando('case (isset($validacion[\'error\']) && count($validacion[\'error\']) > 0):', 12);
        $php .= $this->comando('// Errores durante la validacion de campos', 16);
        $php .= $this->comando('$rpta[\'error\'] = $validacion[\'error\'];', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('case !$this->db->initialize():', 12);
        $php .= $this->comando('// Error en la conexion a la base de campos', 16);
        $php .= $this->comando('$rpta[\'error\'] = \'Error, intentelo nuevamente.\';', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('default:', 12);
        $php .= $this->comando('// Agregar alias a los campos', 16);
        $php .= $this->comando('$this->db->select(implode(\', \', $this->_aliasCampo));', 16);
        $php .= $this->comando('// Tablas involucradas', 16);
        $php .= $this->comando('$this->db->from(\'' . $this->_tabla . '\');', 16);
        $php .= $this->comando('// Join de las tablas relacionadas', 16);
        $php .= $this->comando('foreach ($this->_tablasRelacionadas as $tabla => $datos) {', 16);
        $php .= $this->comando('$this->db->join($tabla, "' . $this->_tabla . '.{$datos[\'campo\']} = {$tabla}.id", $datos[\'join\']);', 20);
        $php .= $this->comando('}', 16);
        $php .= $this->comando('// Omite los registros que se encuentran en estado eliminado', 16);
        $php .= $this->comando('$this->db->where(array(\'' . $this->_tabla . '.zc_eliminado is null\' => null));', 16);
        $php .= $this->comando('// Paginacion de resultados', 16);
        $php .= $this->comando('$this->db->limit($porPagina, (($pagina - 1) * $porPagina));', 16);
        $php .= $this->comando('// Resultado consulta', 16);
        $php .= $this->comando('$ressql = $this->db->get();', 16);
        $php .= $this->comando('if (!$ressql) {', 16);
        $php .= $this->comando('// Error en la consulta', 20);
        $php .= $this->comando('$rpta[\'error\'] = $this->db->error();', 20);
        $php .= $this->comando('} else if ($ressql->num_rows() > 0) {', 16);
        $php .= $this->comando('// Numero total de elementos, esto segun los filtros de busqueda, necesario para la paginacion', 20);
        $php .= $this->comando('$rpta[\'cta\'] = $this->zc->totalRegistros($campos, \'' . $this->_tabla . '\');', 20);
        $php .= $this->comando('$i = 0;', 20);
        $php .= $this->comando('foreach($ressql->result_array() as $row){', 20);
        $php .= $this->comando('$rpta[\'info\'][$i] = $row;', 24);
        $php .= $this->comando('++$i;', 24);
        $php .= $this->comando('}', 20);
        $php .= $this->comando('}', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('}', 8);
        $php .= $this->comando('return ' . ((ZC_BD_ES_UTF) ? '$rpta;' : '$this->zc->utf8_converter($rpta);'), 8);
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
            mostrarErrorZC(__FILE__, __FUNCTION__, ' Error en la accion, se esperaba: ' . ZC_ACCION_BUSCAR);
        }

        $html = '';
        $listaFiltros = tabular("<select class='form-control zc-filtros-busqueda'>", 24);
        $listaCampos = $listaOperadores = '';

        //El boton de quitar se agrega en el proceso jQuery
        $agregar = tabular("<button title='Agregar filtro a la busqueda' class='btn btn-default zc-filtros-agregar'><span class='glyphicon glyphicon-plus-sign' aria-hidden='true'></span><span class='hidden-xs'>Agregar</span></button>", 24);
        $buscar = tabular("<button type='button' title='Realizar la busqueda' zc-accion-tipo='" . ZC_ACCION_BUSCAR . "' id='buscar' name='buscar' class='btn btn-primary zc-accion'><span class='glyphicon glyphicon-search' aria-hidden='true'></span><span class='hidden-xs'>Buscar</span></button>", 24);
        $ocultar = tabular("<button type='button' title='Ocultar filtros' class='btn btn-default zc-filtros-ocultar'><span class='glyphicon glyphicon-chevron-up' aria-hidden='true'></span></button>", 24);
        $mostrar = tabular("<button type='button' title='Mostrar filtros' class='btn btn-default zc-filtros-mostrar hidden'><span class='glyphicon glyphicon-chevron-down' aria-hidden='true'></span></button>", 24);
        $nuevo = tabular("<button type='button' title='Crear nuevo registro' class='btn btn-success zc-nuevo-registro'><span class='glyphicon glyphicon-plus' aria-hidden='true'></span>Nuevo<span class='hidden-xs'> registro</span></button>", 24);

        foreach ($this->_campos as $id => $campo) {
            if ($campo[ZC_DATO] == ZC_DATO_CONTRASENA) {
                // Los campos tipo contrasena no admiten busquedas
                continue;
            }
            switch (true) {
                case $campo[ZC_ELEMENTO] == ZC_ELEMENTO_CHECKBOX:
                case $campo[ZC_ELEMENTO] == ZC_ELEMENTO_RADIO:
                case $campo[ZC_ELEMENTO] == ZC_ELEMENTO_LISTA:
                    $operador = 'lista';
                    break;
                case $campo[ZC_DATO] == ZC_DATO_NUMERICO:
                case $campo[ZC_DATO] == ZC_DATO_FECHA:
                    $operador = 'numero';
                    break;
                case $campo[ZC_DATO] == ZC_DATO_URL:
                case $campo[ZC_DATO] == ZC_DATO_TEXTO:
                case $campo[ZC_DATO] == ZC_DATO_EMAIL:
                case $campo[ZC_DATO] == ZC_DATO_CONTRASENA:
                default :
                    $operador = 'texto';
                    break;
            }
            $oculto = ($listaOperadores == '') ? '' : ' hidden';
            $listaFiltros .= tabular("<option value='{$id}' zc-operador='{$operador}'>{$campo[ZC_ETIQUETA]}</option>", 28);
            // Inactiva la creacion de campos con validaciones de obligatoriedad y sin longitudes
            $this->_campos[$id][ZC_OBLIGATORIO] = null;
            $this->_campos[$id][ZC_LONGITUD_MAXIMA] = -1;
            $this->_campos[$id][ZC_LONGITUD_MINIMA] = -1;
            // Los tipos de caja especiales los valida como cajas para evitar validaciones de datos, por ejemplo para los email
            $this->_campos[$id][ZC_DATO] = (in_array($this->_campos[$id][ZC_DATO], array(ZC_DATO_EMAIL, ZC_DATO_URL))) ? ZC_DATO_TEXTO : $this->_campos[$id][ZC_DATO];
            // Crea el elemento
            $elemento = new elemento($this->_campos[$id]);
            $elemento->propiedad('controlador', $this->_tabla);
            $filtro = $elemento->crear();
            $listaCampos .= tabular("<div id='campo-{$id}' name='campo-{$id}' class='zc-campos{$oculto}'>", 24);
            $listaCampos .= tabular($filtro->devolverElemento(), 28);
            $listaCampos .= tabular('</div>', 24);
            if (!isset($tipo[$operador])) {
                // Verifica que el tipo de operador no haya sido incluido
                $tipo[$operador] = true;
                $listaOperadores .= $elemento->operadores($operador, $oculto);
            }
            unset($filtro);
        }
        $listaFiltros .= tabular('</select>', 24);
        // Los campos y filtros de busquedan se ajustan segun el tamano de la ventana
        $plantilla = '';
        $plantilla .= tabular("<div class='col-sm-4 col-md-2 col-lg-2 col-lg-offset-1'>", 20);
        $plantilla .= $listaFiltros;
        $plantilla .= tabular("</div>", 20);
        $plantilla .= tabular("<div class='col-sm-4 col-md-2 col-lg-2'>", 20);
        $plantilla .= $listaOperadores;
        $plantilla .= tabular("</div>", 20);
        $plantilla .= tabular("<div class='col-sm-4 col-md-3 col-lg-3'>", 20);
        $plantilla .= $listaCampos;
        $plantilla .= tabular("</div>", 20);
        $plantilla .= tabular("<div class='col-sm-12 col-md-5 col-lg-3 text-right'>", 20);
        $plantilla .= $agregar;
        $plantilla .= $buscar;
        $plantilla .= $ocultar;
        $plantilla .= $mostrar;
        $plantilla .= $nuevo;
        $plantilla .= tabular("</div>", 20);
        $this->_filtro = $plantilla;
        return $this;
    }

    /**
     * Varibles utilizadas durante la creacion de los servicios web
     * @return \buscar
     */
    public function inicializarAccion() {
        if (isset($this->_yaInicio)) {
            // Ya esta definido, no vuelve a asignarlos
            return $this;
        }
        $this->_inicializarCliente[] = "'filtros' => \$datos['filtros']";
        $this->_inicializarCliente[] = "'pagina' => \$datos['pagina']";
        $this->_inicializarServidor[] = "'filtros' => 'xsd:string'";
        $this->_inicializarServidor[] = "'pagina' => 'xsd:int'";
        $this->_parametrosServidor[] = '$filtros, $pagina';
        $this->_tipoPlantilla = 'jsLlamadosBuscarAjax.js';

        //Desactiva nuevas peticiones de inicializacion
        $this->_yaInicio = true;
        return $this;
    }

}
