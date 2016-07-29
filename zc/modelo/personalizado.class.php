<?php

// Clase padre de donde herada los metodos
require_once RUTA_GENERADOR_CODIGO . '/modelo/Apaginas.class.php';

/**
 * Creacion de paginas tipo personalizado
 */
class personalizado extends Apaginas {

    /**
     * Plantilla a utilizar para crear la vista de creacion y modificacion de la tabla
     * @return string
     */
    public function devolverPlantillaVista() {
        $tpl = 'htmlPersonalizadoFluid.tpl';
        return $tpl;
    }
}
