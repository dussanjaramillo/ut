<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * Community Auth - MY_Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.1.3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2013, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */
class MY_Controller extends CI_Controller {

  public $template_file = 'templates/main2';

  /**
   * Class constructor
   */
  public function __construct() {
    // creación dinámica del menú
    parent::__construct();
    header('Pragma: no-cache');
    $this->load->helper('array');
    $this->load->model('menu_usuario_model', '', TRUE);
    $permisos_usuarios = $this->uri->segment(1) . '/' . $this->uri->segment(2);
        if(!isset($_SESSION['menus'])):
            session_start();
        endif;
		if ($this->ion_auth->logged_in()) {
			if(isset($_SESSION['menus'])) unset($_SESSION['menus']);
			$user = $this->ion_auth->user()->row();
			if ($user->LASTSESSIONID != $this->session->userdata('token')) {
				$this->ion_auth->logout();
				$this->session->set_flashdata('message', '
						<div class="alert alert-block"><button type="button" class="close" data-dismiss="alert">&times;</button>
								 <h4>Atención!</h4>
								 Usted fue desconectado porque alguien inició sesión en otro equipo con el mismo usuario
						</div>');
			}
		}
		if(!isset($_SESSION['menus']) || empty($_SESSION['menus'])) :
			$menu_navs = array();
			$modulos_nav = array();
			$aplicaciones_nav = array();
			$macroprocesos_nav = array();
			if ($this->ion_auth->is_admin()) {
				$this->data['nav_menus'] = $this->menu_usuario_model->getmenusadmin();
			} elseif ($this->ion_auth->logged_in()) {
				$this->data['nav_menus'] = $this->menu_usuario_model->getmenus();
			} else {
				//nada, si no es administrador y tampoco está logeado como otro tipo de usuario no consulta el menú
			}
	
			if ($this->ion_auth->logged_in()) {
				$menu_navs = object_to_array($this->data['nav_menus']);
				$this->template->set('nav_menus', $menu_navs);
				if ($menu_navs) {
					foreach ($menu_navs as $key => $value) {
						$aplicaciones_nav[$value['APLICACIONID']] = array('CODPROCESO' => $value['CODPROCESO'], 'NOMBREAPLICACION' => $value['NOMBREAPLICACION'], 'URLAPLICACION' => $value['URLAPLICACION'], 'ICONOAPLICACION' => $value['ICONOAPLICACION']);
						$modulos_nav[$value['MODULOID']] = array('APLICACIONID' => $value['APLICACIONID'], 'NOMBREMODULO' => $value['NOMBREMODULO'], 'MODULOURL' => $value['MODULOURL'], 'ICONOMODULO' => $value['ICONOMODULO']);
						$macroprocesos_nav[$value['CODMACROPROCESO']] = array('NOMBREMACROPROCESO' => $value['NOMBREMACROPROCESO'], 'ICONOMACROPROCESO' => $value['ICONOMACROPROCESO']);
					}
				}
			}
			$_SESSION['menus'] =	array(
															'nav_menus'  => $menu_navs,
															'nav_aplicaciones'  => $aplicaciones_nav,
															'nav_modulos'  => $modulos_nav,
															'nav_macroprocesos'  => $macroprocesos_nav,
														);
		endif;
		$this->template->set('nav_menus', $_SESSION['menus']['nav_menus']);
		$this->template->set('nav_aplicaciones', $_SESSION['menus']['nav_aplicaciones']);
		$this->template->set('nav_modulos', $_SESSION['menus']['nav_modulos']);
		$this->template->set('nav_macroprocesos', $_SESSION['menus']['nav_macroprocesos']);
		$this->template->set('permisos_usuarios', $permisos_usuarios);
		if (!$this->ion_auth->logged_in()) :
				if(isset($_SESSION['menus'])) unset($_SESSION['menus']);
		endif;
  }

}

/* End of file MY_Controller.php */
/* Location: /application/libraries/MY_Controller.php */