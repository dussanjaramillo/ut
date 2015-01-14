<?php

class Acuerdonomisional extends MY_Controller {

    function __construct() {
        parent::__construct();

//        $POST_MAX_SIZE = ini_get('post_max_size');
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url', 'codegen_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->data['javascripts'] = array(
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/validateForm.js',
            'js/bootstrap.js',
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js'
        );
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen'
        );

        $this->data['style_sheets'] = array('css/validationEngine.jquery.css' => 'screen');
        $this->load->model('modelacuerdodepago_model');
        $this->load->model('modelacuerdoconsulta_model');

        $this->data['user'] = $this->ion_auth->user()->row();
        define("ID_USER", $this->data['user']->IDUSUARIO);
    }

    function index() {
        $this->ingresoacuerdodepago();
    }

    function ingresoacuerdodepago() {

        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );

                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/jquery.number.js',
                    'js/jquery.number.min.js'
                );


//                $this->data['autorizacion'] = $this->modelacuerdodepago_model->autorizacioncordinador();
//                $this->data[] = array();
                $this->template->set('title', 'Ingreso Acuerdo de Pago');
                $this->data['title'] = 'Ingreso Acuerdo de Pago';

                $this->template->load($this->template_file, 'acuerdonomisional/acuerdonomisional', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function acuerdonomisional() {

        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );

                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/jquery.number.js',
                    'js/jquery.number.min.js'
                );


                $this->data['autorizacion'] = $this->modelacuerdodepago_model->autorizacioncordinador();
//                $this->data[] = array();
                $this->template->set('title', 'Documento Legalización');
                $this->data['title'] = 'Documento Legalización';

                $this->template->load($this->template_file, 'acuerdonomisional/acuerdonomisional', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

}

?>