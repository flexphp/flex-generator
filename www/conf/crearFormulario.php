<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require 'conf.php';
require RUTA_GENERADOR_CODIGO . '/includes/libreria.inc.php';
require RUTA_GENERADOR_CODIGO . '/modelo/plantilla.class.php';
require RUTA_GENERADOR_CODIGO . '/modelo/formulario.class.php';
require RUTA_GENERADOR_CODIGO . '/modelo/xml.class.php';

error_reporting(E_ALL);

/**
 * Description of crearFormulario
 *
 * @author root
 */
try {
    /**
     * JQuery homologado
     */
    $jqueryjs = new plantilla();
    $jqueryjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/jquery.v2.1.1.min.js');
    $jqueryjs->crearPlantilla('../publico/js', 'js');

    /**
     * Parsley Homologado
     */
    $parsleyjs = new plantilla();
    $parsleyjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/parsley.v2.0.5.js');
    $parsleyjs->crearPlantilla('../publico/js', 'js');

    /**
     * Boostrap Homologado
     */
    $bootstrapjs = new plantilla();
    $bootstrapjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/bootstrap.v3.3.2.min.js');
    $bootstrapjs->crearPlantilla('../publico/js', 'js');

    /**
     * JS con funciones javascript utilizadas por el sistema
     */
    $zcjs = new plantilla();
    $zcjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/zc.v1.0.0.js');
    $zcjs->crearPlantilla('../publico/js', 'js');

    /**
     * CSS Homologado
     */
    $bootstrapcss = new plantilla();
    $bootstrapcss->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/css/bootstrap.min.css');
    $bootstrapcss->crearPlantilla('../publico/css', 'css');

    /**
     * CSS Homologado
     */
    $parsleycss = new plantilla();
    $parsleycss->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/css/parsley.css');
    $parsleycss->crearPlantilla('../publico/css', 'css');

    /**
      /**
     * Iconos homologados
     */
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/fonts', '../publico/fonts');

    /**
     * Opciones de formulario
     */
    $nf = new procesarXML();
    $nf->cargarArchivosXML('xml');
} catch (Exception $e) {
    die($e->getMessage());
}
