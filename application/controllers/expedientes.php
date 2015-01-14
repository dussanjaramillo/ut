<?php
/**
 * Archivo para la administración de los metodos necesarios para visulizar los documentos de los diferentes procesos en la Dirección Jurídica
 *
 * @packageCartera
 * @subpackage Controllers
 * @author vferreira
 * @location./application/controllers/expedientes.php

 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Expedientes extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url', 'template', 'codegen_helper', 'traza_fecha_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('expediente_model');

        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen'
        );
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/tinymce/tinymce.jquery.min.js',
            'js/tinymce/plugins/moxiemanager/plugin.min.js',
            'js/tinymce/tinymce.js',
            'js/ajaxfileupload.js',
        );
        $sesion = $this->session->userdata;
        $this->data['user'] = $this->ion_auth->user()->row();
        if ($this->data['user']) {
            define("ID_USUARIO", $this->data['user']->IDUSUARIO);
            define("ID_GRUPO", $this->data['user']->IDGRUPO);
            define("COD_REGIONAL", $this->data['user']->COD_REGIONAL);
        }
    }

    function proceso_coactivo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bandejaunificada/procesos')) {
                $cod_proceso = ($this->input->post('cod_proceso')) ? $this->input->post('cod_proceso') : FALSE;
                //  echo $cod_proceso;
                $this->data['consulta'] = $this->expediente_model->documentos($cod_proceso);
                $this->data['cabecera'] = $this->expediente_model->cabecera($cod_proceso);
                $this->template->load($this->template_file, 'expedientes/documentos_proceso', $this->data);
            } else {
                $this->data['custom_error'] = '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos</div>';
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/login');
        }
    }

    function procesos_juridicos() {

        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('expedientes/procesos_juridicos')) {
                $this->data['user'] = $this->ion_auth->user()->row();
                $this->data['consulta'] = $this->expediente_model->consulta_procesos($this->input->post('iDisplayStart'), $this->input->post('sSearch'), $this->ion_auth->user()->row()->COD_REGIONAL);
                //  $this->data['abogados'] = $this->consultartitulos_model->consulta_abogado($this->ion_auth->user()->row()->COD_REGIONAL);
                $this->data['ruta'] = base_url() . 'index.php/expedientes/documentos_proceso';
                $this->template->load($this->template_file, 'expedientes/listar_procesos', $this->data);
            } else {
                redirect(base_url() . 'index.php/auth/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function documentos_proceso() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('codFiscalizacion')) {
                $this->data['user'] = $this->ion_auth->user()->row();
                $this->data['post'] = $this->input->post();
                $this->data['consulta'] = $this->expediente_model->consulta_expediente($this->input->post('iDisplayStart'), $this->input->post('sSearch'), $this->ion_auth->user()->row()->COD_REGIONAL, $this->data['post']['codFiscalizacion']);
                $empresa = $this->expediente_model->empresa_expediente($this->data['post']['cod_empresa']);
                $this->data['empresa'] = $empresa[0];
                $this->data['codigo_pj'] = $this->data['post']['codigo_pj'];
            } else {
                redirect(base_url() . 'index.php/auth/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

}
