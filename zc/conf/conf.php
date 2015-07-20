<?php

// Horario por defecto del sistema
date_default_timezone_set('America/Bogota');
// Ruta donde se encuentra el generador de codigo
define('RUTA_GENERADOR_CODIGO', '../zc');
// Define el fin de linea para los archivos creados
define('FIN_DE_LINEA', "\n");
// Define el fin de linea para los archivos html
define('FIN_DE_LINEA_HTML', '<br/>');
// Nombre de la vista que muestra el menu de navegacion
define('ZC_NAVEGACION_PAGINA', 'v_navegacion');
// Nombre de la hoja para crear ventana de logueo, se define en la hoja de calculo
define('ZC_LOGIN_PAGINA', 'Login');
// Nombre de la hoja de configuracion, se define en la hoja de calculo
define('ZC_CONFIG_PAGINA', 'config');
// Nombre de la hoja de listas desplegables, se define en la hoja de calculo
define('ZC_ZC_PAGINA', 'zerocode');
// Nombre de la tabla contra la que se hace la validacion del login
define('ZC_LOGIN_TABLA', 'usuarios');
// Nombre de la tabla, se extrae de la hoja de calculo
define('ZC_TABLA_BD', 'nombre_tabla');
// Identificador del campo dentro del formulario
define('ZC_ID', 'id');
// Etiqueta (Label) del campo
define('ZC_ETIQUETA', 'como_se_llama');
// Nombre del campo en la base de datos
define('ZC_CAMPO_BD', 'nombre_campo_bd');
// Descripcion del nombre en el formulario
define('ZC_FORMULARIO_NOMBRE', 'nombre_formulario');
// Tipo de envio del formulario POST|GET
define('ZC_FORMULARIO_METODO', 'se_envia');
// Tipo de formulario a crear tabla|autenticacion
define('ZC_FORMULARIO_TIPO', 'tipo_formulario');
// Tipo de web service a utilizar REST|SOAP
// Tipos de WS SOAP con XML, menos lento, mas seguro
// Tipos de WS REST con JSON, mas rapido, menos seguro
define('ZC_WS_TIPO', 'ws_tipo');
define('ZC_WS_SOAP', 'soap');
define('ZC_WS_REST', 'rest');
// Define el campo principal dentro del formulario, donde se coloca el puntero al cargar el formulario
define('ZC_AUTOFOCO', 'campo_principal');
// Tipos de elementos aceptados
define('ZC_ELEMENTO', 'que_quieres');
define('ZC_ELEMENTO_CAJA', 'caja');
define('ZC_ELEMENTO_RADIO', 'radio');
define('ZC_ELEMENTO_CHECKBOX', 'checkbox');
define('ZC_ELEMENTO_LISTA', 'lista');
define('ZC_ELEMENTO_OPCIONES', 'valores');
// Tipos de datos aceptados
define('ZC_DATO', 'que_dato_recibira');
define('ZC_DATO_NUMERICO', 'numero');
define('ZC_DATO_EMAIL', 'correo');
define('ZC_DATO_FECHA', 'fecha');
define('ZC_DATO_FECHA_HORA', 'fecha hora');
define('ZC_DATO_HORA', 'hora');
define('ZC_DATO_CONTRASENA', 'clave');
define('ZC_DATO_URL', 'url');
define('ZC_DATO_TEXTO', 'texto');
define('ZC_DATO_AREA_TEXTO', 'mucho texto');
define('ZC_DATO_WS', 'dato_ws');
define('ZC_DATO_ERROR', 'mensaje_de_error_tipo_dato');
define('ZC_DATO_ERROR_PREDETERMINADO', 'Tipo de dato incorrecto');
// Restricciones de longitud
define('ZC_LONGITUD_PREDETERMINADA', 40);
define('ZC_LONGITUD_MAXIMA', 'longitud_maxima_es');
define('ZC_LONGITUD_MAXIMA_ERROR', 'mensaje_de_error_longitud_maxima');
define('ZC_LONGITUD_MAXIMA_ERROR_PREDETERMINADO', 'Supera la longitud permitida (&[Longitud]&)');
define('ZC_LONGITUD_MINIMA', 'longitud_minima_es');
define('ZC_LONGITUD_MINIMA_ERROR', 'mensaje_de_error_longitud_minima');
define('ZC_LONGITUD_MINIMA_ERROR_PREDETERMINADO', 'Se esperan al menos (&[Longitud]&) caracteres');
// Restricciones de obligatorieadad
define('ZC_OBLIGATORIO', 'es_obligatorio');
define('ZC_OBLIGATORIO_SI', 'si');
define('ZC_OBLIGATORIO_NO', 'no');
define('ZC_OBLIGATORIO_ERROR', 'mensaje_de_error_es_obligatorio');
define('ZC_OBLIGATORIO_ERROR_PREDETERMINADO', 'Campo obligatorio');
// Define el valor predeterminado del campo
define('ZC_VALOR_PREDETERMINADO', 'por_defecto_es');
// Configuracion de la base datos
define('ZC_MOTOR_MYSQL', 'mysql');
define('ZC_MOTOR_AUTOINCREMENTAL', 'auto');
// Seperador de los listas (select html) que no utilizan tablas de datos
define('ZC_MOTOR_SEPARADOR', '=');
// Seperador de los parametros para hacer el join entres las tablas
define('ZC_MOTOR_JOIN_SEPARADOR', '::');
define('ZC_MOTOR_JOIN_IZQUIERDA', 'left');
define('ZC_MOTOR_JOIN_DERECHA', 'right');
// Acciones predefinidas
define('ZC_ACCION_BOTON', 'boton');
define('ZC_ACCION_RESTABLECER', 'limpiar');
define('ZC_ACCION_CANCELAR', 'cancelar');
define('ZC_ACCION_AGREGAR', 'agregar');
define('ZC_ACCION_BUSCAR', 'buscar');
define('ZC_ACCION_MODIFICAR', 'modificar');
define('ZC_ACCION_BORRAR', 'borrar');
define('ZC_ACCION_NUEVO', 'nuevo');
define('ZC_ACCION_PRECARGAR', 'precargar');
define('ZC_ACCION_AJAX', 'ajax');
define('ZC_ACCION_LOGUEAR', 'loguear');
// Acciones a las que no se les aplica validacion de datos
define('ZC_ACCION_SIN_VALIDACION', ZC_ACCION_BUSCAR . ',' . ZC_ACCION_BORRAR);
// Mensajes de error en caso de que no se seleccionen filtros de busqueda
define('ZC_MENSAJE_ERROR_BUSCAR', 'Seleccione los filtros de busqueda.');
// Opciones de configuracion, se configuran en la hoja llamada ZC_CONFIG_PAGINA
define('ZC_CONFIG_BD_MOTOR', 'bd_motor');
define('ZC_CONFIG_BD_SERVIDOR', 'bd_servidor');
define('ZC_CONFIG_BD_PUERTO', 'bd_puerto');
define('ZC_CONFIG_BD_ESQUEMA', 'bd_esquema');
define('ZC_CONFIG_BD_USUARIO', 'bd_usuario');
define('ZC_CONFIG_BD_CLAVE', 'bd_clave');
define('ZC_CONFIG_BD_CHARSET', 'bd_charset');
define('ZC_CONFIG_BD_COLLATION', 'bd_collation');
define('ZC_CONFIG_REGISTROS_POR_PAGINA', 'registros_por_pagina');
// Prefijos de los archivos
define('ZC_PREFIJO_VISTA', 'v_');
define('ZC_PREFIJO_LISTA', 'l_');
define('ZC_PREFIJO_CONTROLADOR', '');
define('ZC_PREFIJO_MODELO', 'm_');
define('ZC_PREFIJO_CONTROLADOR_WS', 'ws_');
define('ZC_PREFIJO_MODELO_WS', 'mws_');
// Nombre funciones utilizados a traves de los archivos
define('ZC_FUNCION_VALIDACION_DATOS', 'validarDatos');