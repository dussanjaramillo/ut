<?php

class Reportes extends MY_Controller {
    
    function reportelinea(){
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                  $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/reportelinea', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
      
    function reporteliquidacion(){
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                  'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                  'js/jquery.dataTables.min.js',
                  'js/jquery.dataTables.defaults.js'
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/reporteliquidacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    function reporteresolucion(){
      if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/reporteresolucion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }  
    }
    function reportecartera(){
       if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                 $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
             
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/reportecartera', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }   
    }
    function reporteacuerdos(){
       if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/reporteacuerdos', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }    
    }
    function estadocuenta(){
     if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/estadocuenta', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }     
}
function generarcertificaciones(){
    if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

               $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->load->model('modelreportes_model');
                $this->data['regional'] = $this->modelreportes_model->regional();
                $this->load->model('modelreportes_model');
                $this->data['ciudad'] = $this->modelreportes_model->ciudad();
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/generarcertificaciones', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }     
}
function conciliacion(){
    if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->load->model('modelreportes_model');
                $this->data['regional'] = $this->modelreportes_model->regional();
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/conciliacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }     
}
function reporteedad(){
    if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
             
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/reporteedad', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }  
}
function reportesugpp(){
    if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/reportesugpp', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }  
}
function incumplimientopago(){
    if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/incumplimientopago', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
}
function informefiscalizacion(){
    if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

               $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->load->model('modelreportes_model');
                $this->data['regional'] = $this->modelreportes_model->regional();
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/informefiscalizacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
}
function reporteriesgo(){
    if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                  'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                  'js/jquery.dataTables.min.js',
                  'js/jquery.dataTables.defaults.js'
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/reporteriesgo', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
}
function modulocartera(){
    if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                  'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                  'js/jquery.dataTables.min.js',
                  'js/jquery.dataTables.defaults.js'
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/modulocartera', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
}
function reporteinterno(){
    if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

               $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                 $this->load->model('modelreportes_model');
                $this->data['regional'] = $this->modelreportes_model->regional();
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/reporteinterno', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
}
function control(){
     if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                  'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                  'js/jquery.dataTables.min.js',
                  'js/jquery.dataTables.defaults.js'
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/control', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
}
function informevisitas(){
   if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                 $this->load->model('modelreportes_model');
                $this->data['regional'] = $this->modelreportes_model->regional();
                $this->load->model('modelreportes_model');
                $this->data['fiscalizador'] = $this->modelreportes_model->fiscalizador();
                 $this->load->model('modelreportes_model');
                $this->data['regional1'] = $this->modelreportes_model->regional();
                $this->load->model('modelreportes_model');
                $this->data['fiscalizador1'] = $this->modelreportes_model->fiscalizador();
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/informevisitas', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        } 
}
function asignacionfiscalizacion(){
   if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                  $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Asignación Fiscalización');
                $this->data['title'] = 'Asignación Fiscalización';
                $this->load->model('modelreportes_model');
                $this->data['regional'] = $this->modelreportes_model->regional();
                $this->load->model('modelreportes_model');
                $this->data['fiscalizador'] = $this->modelreportes_model->fiscalizador();
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/asignacionfiscalizacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }  
}
function estadofiscalizacion(){
    if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->load->model('modelreportes_model');
                $this->data['regional'] = $this->modelreportes_model->regional();
                $this->load->model('modelreportes_model');
                $this->data['fiscalizador'] = $this->modelreportes_model->fiscalizador();
                 $this->load->model('modelreportes_model');
                $this->data['regional1'] = $this->modelreportes_model->regional();
                $this->load->model('modelreportes_model');
                $this->data['fiscalizador1'] = $this->modelreportes_model->fiscalizador();
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/estadofiscalizacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        } 
}
function reportesabogado(){
     if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                 $this->load->model('modelreportes_model');
                $this->data['regional'] = $this->modelreportes_model->regional();
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/reportesabogado', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        } 
}
function reportespersonalizados(){
    if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/reportespersonalizados', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        } 
}
function reportescontrato(){
    if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/reportescontrato', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        } 
}
function multasministerio(){
    if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/multasministerio', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
}
function gestionempresa(){
    if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                 $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->load->model('modelreportes_model');
                $this->data['regional'] = $this->modelreportes_model->regional();
                $this->load->model('modelreportes_model');
                $this->data['regional1'] = $this->modelreportes_model->regional();
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/gestionempresa', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
}
function informacionexogena(){
     if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/informacionexogena', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
}
function reportereciproca(){
    if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

               $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/reportereciproca', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
}
function reclasificacionfiltros(){
    if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                 $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/reclasificacionfiltros', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
}
function contratoaprendizaje(){
    if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                 $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                 $this->load->model('modelreportes_model');
                $this->data['regional'] = $this->modelreportes_model->regional();
                $this->data['regional1'] = $this->modelreportes_model->regional();
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'reportes/contratoaprendizaje', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
}
}