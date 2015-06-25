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
            mostrarErrorZC(__FILE__, __FUNCTION__, ': Error en la accion, se esperaba: ' . ZC_ACCION_BUSCAR);
        }
        $php = '';
        $php .= $this->comando('//Establece los valores de cada uno de los campos', 12);
        $php .= $this->comando('', 12);
        $php .= $this->comando('// Se instancia un nuevo controlador, desde la funcion no es posible acceder al $this original', 12);
        $php .= $this->comando('//Nombre de la tabla afectada', 12);
        $php .= $this->comando('$tabla = \'' . $this->_tabla . '\';', 12);
        $php .= $this->comando('$CI = new CI_Controller;', 12);
        $php .= $this->comando('$CI->load->model(\'' . $this->_modelo . '\', $tabla);', 12);
        $php .= $this->comando('// Ejecucion de la accion', 12);
        $php .= $this->comando('$rpta = $CI->$tabla->buscar($filtros, \'buscar\', $pagina);', 12);
        $php .= $this->comando('switch (true){', 12);
        $php .= $this->comando('case (isset($rpta[\'error\']) && \'\' != $rpta[\'error\']):', 16);
        $php .= $this->comando('// Errores durante la ejecucion', 20);
        $php .= $this->comando('$Resultado[0][\'error\'] = json_encode($rpta[\'error\']);', 20);
        $php .= $this->comando('break;', 20);
        $php .= $this->comando('default:', 16);
        $php .= $this->comando('// Resultado', 20);
        $php .= $this->comando('$resultado = $rpta[\'resultado\'];', 20);
        $php .= $this->comando('$cta = $rpta[\'cta\'];', 20);
        $php .= $this->comando('break;', 20);
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
            mostrarErrorZC(__FILE__, __FUNCTION__, ': Error en la accion, se esperaba: ' . ZC_ACCION_BUSCAR);
        }
        $php = '';
        $php .= $this->comando('function buscar($campos, $accion, $pagina){', 4);
        $php .= $this->comando('$rpta = array();', 8);
        $php .= $this->comando('$pagina = (!is_int($pagina)) ? 1 : $pagina;', 8);
        $php .= $this->comando('$porPagina = ' . ZC_REGISTROS_POR_PAGINA . ';', 8);
        $php .= $this->comando('$validacion = $this->zc->validarFiltros($campos, $accion);', 8);
        $php .= $this->comando('switch (true){', 8);
        $php .= $this->comando('case (isset($validacion[\'error\']) && \'\' != $validacion[\'error\']):', 12);
        $php .= $this->comando('// Errores durante la validacion de campos', 16);
        $php .= $this->comando('$rpta[\'error\'] = json_encode($validacion[\'error\']);', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('case !$this->db->initialize():', 12);
        $php .= $this->comando('// Error en la conexion a la base de campos', 16);
        $php .= $this->comando('$rpta[\'error\'] = json_encode(\'Error, intentelo nuevamente.\');', 16);
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
        $php .= $this->comando('// Existen resultados', 16);
        $php .= $this->comando('if($ressql->num_rows() > 0){', 16);
        $php .= $this->comando('// Numero total de elementos, esto segun los filtros de busqueda', 20);
        $php .= $this->comando('$rpta[\'cta\'] = $ressql->num_rows();', 20);
        $php .= $this->comando('$i = 0;', 20);
        $php .= $this->comando('foreach($ressql->result_array() as $row){', 20);
        $php .= $this->comando('$rpta[\'resultado\'][$i] = $row;', 24);
        $php .= $this->comando('++$i;', 24);
        $php .= $this->comando('}', 20);
        $php .= $this->comando('}', 16);
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
            mostrarErrorZC(__FILE__, __FUNCTION__, ': Error en la accion, se esperaba: ' . ZC_ACCION_BUSCAR);
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
        $filtrosListas = array(
            '=' => '',
            '=' => 'igual a',
            '!=' => 'deferente de',
        );
        $filtrosTexto = array(
            '=' => '',
            '=' => 'igual a',
            'after%' => 'inicia con',
            'both%' => 'contiene',
            'before%' => 'termina con',
            '!=' => 'deferente de',
        );

        $operadorNumerico = '';
        $operadorLista = '';
        $operadorTexto = '';

        foreach ($filtrosNumericos as $key => $value) {
            $operadorNumerico .= "<option value='$key'>$value</option>" . FIN_DE_LINEA;
        }
        foreach ($filtrosListas as $key => $value) {
            $operadorLista .= "<option value='$key'>$value</option>" . FIN_DE_LINEA;
        }
        foreach ($filtrosTexto as $key => $value) {
            $operadorTexto .= "<option value='$key'>$value</option>" . FIN_DE_LINEA;
        }

        $html = '';
        $elementos = '';
        $columnas = '';
        $elementos .= "<select class='form-control zc-filtros-busqueda'>" . FIN_DE_LINEA;

        //El boton de quitar se agrega en el proceso jQuery
        $buscar = "<button type='button' title='Realizar la busqueda' zc-accion-tipo='" . ZC_ACCION_BUSCAR . "' id='buscar' name='buscar' class='btn btn-primary zc-accion'><span class='glyphicon glyphicon-search' aria-hidden='true'></span> Buscar</button>" . FIN_DE_LINEA;
        $ocultar = "<button type='button' title='Ocultar filtros' class='btn btn-default zc-filtros-ocultar'><span class='glyphicon glyphicon-chevron-up' aria-hidden='true'></span></button>" . FIN_DE_LINEA;
        $mostrar = "<button type='button' title='Mostrar filtros' class='btn btn-default zc-filtros-mostrar hidden'><span class='glyphicon glyphicon-chevron-down' aria-hidden='true'></span></button>" . FIN_DE_LINEA;
        $nuevo = "<button type='button' title='Crear nuevo registro' class='btn btn-success zc-nuevo-registro'><span class='glyphicon glyphicon-plus' aria-hidden='true'></span> Nuevo registro</button>" . FIN_DE_LINEA;
        foreach ($this->_campos as $nro => $campo) {
            if ($campo[ZC_DATO] == ZC_DATO_CONTRASENA) {
                // Los campos tipo contrasena no admiten busquedas
                continue;
            }
            $elementos .= "<option value='{$campo[ZC_ID]}'>{$campo[ZC_ETIQUETA]}</option>" . FIN_DE_LINEA;
            $operador = "<select id='operador-{$campo[ZC_ID]}' name='operador-{$campo[ZC_ID]}' class='form-control'>" . FIN_DE_LINEA;
            $agregar = "<button class='btn zc-filtros-agregar btn-default' title='Agregar filtro a la busqueda' id='agregar-{$campo[ZC_ID]}' name='agregar-{$campo[ZC_ID]}'><span class='glyphicon glyphicon-plus-sign' aria-hidden='true'></span> Agregar</button>" . FIN_DE_LINEA;
            switch (true) {
                case $campo[ZC_ELEMENTO] == ZC_ELEMENTO_CHECKBOX:
                case $campo[ZC_ELEMENTO] == ZC_ELEMENTO_RADIO:
                case $campo[ZC_ELEMENTO] == ZC_ELEMENTO_LISTA:
                    $operador .= $operadorLista;
                    break;
                case $campo[ZC_DATO] == ZC_DATO_NUMERICO:
                case $campo[ZC_DATO] == ZC_DATO_FECHA:
                    $operador .= $operadorNumerico;
                    break;
                case $campo[ZC_DATO] == ZC_DATO_URL:
                case $campo[ZC_DATO] == ZC_DATO_TEXTO:
                case $campo[ZC_DATO] == ZC_DATO_EMAIL:
                case $campo[ZC_DATO] == ZC_DATO_AREA_TEXTO:
                case $campo[ZC_DATO] == ZC_DATO_CONTRASENA:
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
            switch ($this->_campos[$nro][ZC_ELEMENTO]) {
                case ZC_ELEMENTO_RADIO:
                    $filtro = new radio($this->_campos[$nro]);
                    break;
                case ZC_ELEMENTO_LISTA:
                    $filtro = new lista($this->_campos[$nro], $this->_tabla);
                    break;
                case ZC_ELEMENTO_CHECKBOX:
                    $filtro = new checkbox($this->_campos[$nro]);
                    break;
                case ZC_ELEMENTO_CAJA:
                default :
                    $filtro = new caja($this->_campos[$nro]);
                    break;
            }
            $valor = $filtro->crear()->devolverElemento() . FIN_DE_LINEA;
            unset($filtro);

            $oculto = ($html == '') ? '' : 'hidden';
            // La distribucion de estos div debe ser igual a la de los creados por la funcion ZCAccionAgregarFiltro,
            // Se carga el boton de accion solo una vez
            $html .=
                    "<div class='col-md-8 zc-filtros zc-filtros-{$campo[ZC_ID]} {$oculto}'>" .
                    "   <div class='row'>" .
                    "       <div class='col-md-3'>{_elementos_}</div>" .
                    "       <div class='col-md-3'>{$operador}</div>" .
                    "       <div class='col-md-4'>{$valor}</div>" .
                    "       <div class='col-md-2'>{$agregar}</div>" .
                    "   </div>" .
                    "</div>" . FIN_DE_LINEA;
        }
        $plantilla = "<div class='row'>" .
                "" . $html .
                "    <div class='col-md-4'> " .
                "       <div class='row'>" .
                "           <div class='col-md-3'>{$buscar}</div>" .
                "           <div class='col-md-3'>{$ocultar}{$mostrar}</div>" .
                "           <div class='col-md-1'>{$nuevo}</div>" .
                "           <div class='col-md-3'>{_columnas_}</div>" .
                "       </div>" .
                "   </div>" .
                "</div>" . FIN_DE_LINEA;
        $elementos .= "</select>" . FIN_DE_LINEA;
        $this->_filtro = str_replace('{_columnas_}', $columnas, str_replace('{_elementos_}', $elementos, $plantilla));
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

        $this->_asignacionControlador[] = "\$datos['filtros'] = \$this->input->post('filtros');";
        $this->_asignacionControlador[] = "\$datos['pagina'] = \$this->input->post('pagina');";

        $this->_tipoPlantilla = 'jsLlamadosBuscarAjax.js';

        //Desactiva nuevas peticiones de inicializacion
        $this->_yaInicio = true;
        return $this;
    }

}
