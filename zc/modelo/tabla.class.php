<?php

/**
 * Clase padre de donde herada los metodos
 */
require_once RUTA_GENERADOR_CODIGO . '/modelo/Apaginas.class.php';

/**
 * Crear compartida para la creacion de paginas html
 */
class tabla extends Apaginas {
    // Se utilizan los mismos metodos de la clase abstracta

    /**
     * Define si de se debe crear la vista de busqueda
     */
    public function esLogin(){
        return false;
    }
}
