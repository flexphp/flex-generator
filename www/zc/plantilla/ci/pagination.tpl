<?php

if(!defined('BASEPATH')){
    exit('Direct Access Not Allowed');
}

/**
 * Configuracion para usar BS3
 */
$config['full_tag_open'] = '<div class="text-center"><ul class="pagination pagination-small pagination-centered">';
$config['full_tag_close'] = '</ul></div>';
$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';
$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
$config['next_tag_open'] = "<li>";
$config['next_tagl_close'] = "</li>";
$config['prev_tag_open'] = "<li>";
$config['prev_tagl_close'] = "</li>";
$config['first_tag_open'] = "<li>";
$config['first_tagl_close'] = "</li>";
$config['last_tag_open'] = "<li>";
$config['last_tagl_close'] = "</li>";
$config['first_link'] = '<<';
$config['last_link'] = '>>';
// Se hace en el ajax De la forma http://url/index.php/controlador/buscar/#
$config['uri_segment'] = 3;
// Numero de enlace antes y despues de la pagina actual
$config['num_links'] = 2;
// $config['display_pages'] = FALSE;
$config['per_page'] = {_porPagina_};
// Necesario para contruir la paginacion adecuadamente
$config['use_page_numbers'] = TRUE;