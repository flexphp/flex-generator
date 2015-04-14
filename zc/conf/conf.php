<?php

// Horario por defecto
date_default_timezone_set('America/Bogota');
// Define el fin de linea para los archivos creados
define("FIN_DE_LINEA", "\n");
// Fin de linea para los archivos html
define('FIN_DE_LINEA_HTML', '<br/>');
// Ruta donde se encuentra el generador de codigo
define("RUTA_GENERADOR_CODIGO", "../zc");
// Numero de registro por pagina, se usa para paginar los resultados
define("ZC_REGISTROS_POR_PAGINA", 10);
// Nombre de la bista que muestra el menu de navegacion
define("ZC_NAVEGACION_PAGINA", "vista_navegacion");
// Nombre de la hoja para crear ventana de logueo, se define en la hoja de calculo
define("ZC_LOGIN_PAGINA", "zlogin");
// Nombre de la tabla contra la que se hace la validacion del login
define("ZC_LOGIN_TABLA", "usuarios");
//Tipos de elementos aceptados
define("ZC_ID", "id");
define("ZC_ETIQUETA", "como_se_llama");
// Configuracion del formulario
define("ZC_TIPO_WS", "tipo_de_servicio");
define("ZC_FORMULARIO_NOMBRE", "nombre_formulario");
define("ZC_FORMULARIO_METODO", "se_envia");
define("ZC_NOMBRE_ACCION_CLIENTE", "nombre_accion");
// Tipos de WS
define("ZC_WS_SOAP", "soap");
define("ZC_WS_REST", "rest");
// Tipos de elementos aceptados
define("ZC_ELEMENTO", "que_quieres");
define("ZC_ELEMENTO_CAJA_TEXTO", "caja");
define("ZC_ELEMENTO_BOTON", "boton");
define("ZC_ELEMENTO_RADIO", "circulo");
define("ZC_ELEMENTO_CHECKBOX", "cuadrado");
define("ZC_ELEMENTO_SELECT", "listado");
define("ZC_ELEMENTO_OPCIONES", "posibles_valores");
define("ZC_ELEMENTO_RESTABLECER", "limpiar");
define("ZC_ELEMENTO_CANCELAR", "cancelar");
// Tipos de datos aceptados
define("ZC_DATO", "que_dato_recibira");
define("ZC_DATO_NUMERICO", "numero");
define("ZC_DATO_EMAIL", "correo");
define("ZC_DATO_FECHA", "fecha");
define("ZC_DATO_CONTRASENA", "clave");
define("ZC_DATO_URL", "url");
define("ZC_DATO_TEXTO", "texto");
define("ZC_DATO_AREA_TEXTO", "mucho texto");
define("ZC_DATO_ERROR", "mensaje_de_error_tipo_dato");
define("ZC_DATO_ERROR_PREDETERMINADO", "Tipo de dato incorrecto");
define("ZC_DATO_WS", "dato_ws");
// Restricciones de longitud
define("ZC_LONGITUD_PREDETERMINADA", 40);
define("ZC_LONGITUD_MAXIMA", "longitud_maxima_es");
define("ZC_LONGITUD_MAXIMA_ERROR", "mensaje_de_error_longitud_maxima");
define("ZC_LONGITUD_MAXIMA_ERROR_PREDETERMINADO", "Supera la longitud permitida (&[Longitud]&)");
define("ZC_LONGITUD_MINIMA", "longitud_minima_es");
define("ZC_LONGITUD_MINIMA_ERROR", "mensaje_de_error_longitud_minima");
define("ZC_LONGITUD_MINIMA_ERROR_PREDETERMINADO", "Se esperan al menos (&[Longitud]&) caracteres");
// Restricciones de obligatorieadad
define("ZC_OBLIGATORIO", "es_obligatorio");
define("ZC_OBLIGATORIO_SI", "si");
define("ZC_OBLIGATORIO_NO", "no");
define("ZC_OBLIGATORIO_ERROR", "mensaje_de_error_es_obligatorio");
define("ZC_OBLIGATORIO_ERROR_PREDETERMINADO", "Campo obligatorio");
// Configuracion de la base datos
define("ZC_MOTOR", "se_guardara_en");
define("ZC_MOTOR_MYSQL", "mysql");
define("ZC_MOTOR_DEFAULT_CHARSET", "latin1");
define("ZC_MOTOR_DEFAULT_COLLATION", "latin1_swedish_ci");
define("ZC_MOTOR_AUTOINCREMENTAL", "auto");
// Seperador de los listas que no utilizan tablas de datos
define("ZC_MOTOR_SEPARADOR", '=');
// Seperador de los parametros para hacer el join entres las tablas
define("ZC_MOTOR_JOIN_SEPARADOR", '::');
define("ZC_MOTOR_JOIN_IZQUIERDA", 'left');
define("ZC_MOTOR_JOIN_DERECHA", 'right');
// Configuracion del conexion a base de datos
define("ZC_CONEXION_SERVIDOR", "localhost");
define("ZC_CONEXION_PUERTO", "3306");
define("ZC_CONEXION_BD", "ejemplo");
define("ZC_CONEXION_USUARIO", "root");
define("ZC_CONEXION_CLAVE", "1q2w3e4r");
// Acciones predefinidas en base de datos
define("ZC_ACCION_AGREGAR", "agregar");
define("ZC_ACCION_BUSCAR", "buscar");
define("ZC_ACCION_MODIFICAR", "modificar");
define("ZC_ACCION_BORRAR", "borrar");
define("ZC_ACCION_NUEVO", "nuevo");
define("ZC_ACCION_PRECARGAR", "precargar");
define("ZC_ACCION_AJAX", "ajax");
define("ZC_ACCION_LOGIN", "login");
// Acciones a las que no se les aplica validacion de datos
define("ZC_ACCION_SIN_VALIDACION", ZC_ACCION_BUSCAR . "," . ZC_ACCION_BORRAR);
// Mensajes de error en caso de que no se seleccionen filtros de busqueda
define("ZC_MENSAJE_ERROR_BUSCAR", "Seleccione los filtros de busqueda.");
