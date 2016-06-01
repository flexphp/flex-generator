<?php

/**
 * Crea botones: button, reset
 */
class boton extends Aelemento {

    /**
     * Tipo de boton/accion a ejecutar
     * @var string
     */
    private $_tipo;

    /**
     * Clase css del boton creado
     * @var string
     */
    private $_presentacion;

    /**
     * Icono que acompana el boton
     * @var string
     */
    private $_icono;

    /**
     * Crear botones de accion a crear
     * @param array $caracteristicas Caracteristicas del boton a crear
     * @param string $tipo Tipo de boton a crear
     */
    function __construct($caracteristicas) {
        parent::__construct($caracteristicas);
        $this->tipo();
    }

    /**
     * Define todos los parametros del boton a crear, funcion principal de la clase
     */
    function crear() {
        if (isset($this->_tipo)) {
            // Verifica si se debe crear el boton, por ejemplo los botones de precargar y ajax no se crean
            $this->_html = tabular("<button" .
                    " id='{$this->_id}'" .
                    " name='{$this->_id}'" .
                    " type='{$this->_tipo}'" .
                    // Es la accion que se ejecuta en el llamado ajax
                    " zc-accion-tipo='{$this->_id}'" .
                    // zc-accion indica al sistema que es un boton en el cual debe hacer envio
                    " class='btn {$this->_presentacion}'" .
                    ">" .
                    " {$this->_icono}" .
                    " {$this->_etiqueta}" .
                    "</button>", 24);
        }
        return $this;
    }

    /**
     * Selecciona el tipo de boton a crear en el formulario
     */
    private function tipo() {
        switch ($this->_prop[ZC_ELEMENTO]) {
            case ZC_ACCION_AGREGAR:
                $this->_tipo = 'button';
                $this->_icono = '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
                $this->_presentacion = 'btn-primary zc-accion';
                // Para la funcionalidad de ocultar botones
                $this->_id = ZC_ACCION_AGREGAR;
                $this->_prop[ZC_ID] = ZC_ACCION_AGREGAR;
                break;
            case ZC_ACCION_MODIFICAR:
                $this->_tipo = 'button';
                $this->_icono = '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>';
                $this->_presentacion = 'btn-primary zc-accion';
                // Para la funcionalidad de ocultar botones
                $this->_id = ZC_ACCION_MODIFICAR;
                $this->_prop[ZC_ID] = ZC_ACCION_MODIFICAR;
                break;
            case ZC_ACCION_BORRAR:
                $this->_tipo = 'button';
                $this->_icono = '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>';
                $this->_presentacion = 'btn-danger zc-accion';
                // Para la funcionalidad de ocultar botones
                $this->_id = ZC_ACCION_BORRAR;
                $this->_prop[ZC_ID] = ZC_ACCION_BORRAR;
                break;
            case ZC_ACCION_LOGUEAR:
                $this->_tipo = 'button';
                $this->_icono = '<span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>';
                $this->_presentacion = 'btn-primary btn-lg btn-block zc-accion';
                // Para la funcionalidad de ocultar botones
                $this->_id = ZC_ACCION_LOGUEAR;
                $this->_prop[ZC_ID] = ZC_ACCION_LOGUEAR;
                break;
            case ZC_ACCION_BUSCAR:
                // Para la funcionalidad del buscador, este id debe ser 'buscar'
                $this->_prop[ZC_ID] = 'buscar';
            case ZC_ACCION_PRECARGAR:
            case ZC_ACCION_AJAX:
            case ZC_ACCION_INIT:
                $this->_tipo = null;
                break;
            case ZC_ACCION_CANCELAR:
                $this->_tipo = 'button';
                $this->_icono = '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
                //Agrega clase especial, determina que accion tomar
                $this->_presentacion = 'btn-warning hidden-xs zc-boton-cancelar';
                break;
            case ZC_ACCION_RESTABLECER:
                $this->_tipo = 'reset';
                $this->_presentacion = 'btn-default hidden-xs';
                break;
            case ZC_ACCION_BOTON:
            default :
                $this->_tipo = 'button';
                $this->_presentacion = 'btn-default';
                break;
        }
    }

}
