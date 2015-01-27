<?php

class Checklist extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url'));
        $this->load->model('checklist_model');
    }

    function index() {
        redirect(base_url() . 'index.php/checklist/listar');
    }

    function listar() {
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
        );
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js'
        );
        $this->data['listado'] = $this->checklist_model->listar();
        $this->template->load($this->template_file, 'checklist/listar', $this->data);
    }

    function edit() {
        $conceptos = array();
        $concepto = $this->input->post('concepto');
        $descripcion = $this->input->post('descripcion');
        $descripcion_old=  $this->input->post('descripcion_old');
        $cpts = $this->checklist_model->getconceptos()->result_array();
        foreach ($cpts as $value) {
            $conceptos[$value['COD_CONCEPTO']] = $value['NOMBRE_CONCEPTO'];
        }
        $this->data['listado'] = $this->checklist_model->listar($concepto, $descripcion)->result_array();
        $this->data['conceptos'] = $conceptos;
        $this->data['concepto'] = $concepto;
        $this->data['descripcion'] = $descripcion;
        $this->data['descripcion_old']=$descripcion_old;
        $this->template->load($this->template_file, 'checklist/edit', $this->data);
    }

    function guardar() {
        $guardar = $this->checklist_model->guardar(
                $this->input->post('concepto'), $this->input->post('descripcion'), $this->input->post('texto'), $this->input->post('orden'), $this->input->post('activo'), $this->input->post('descripcion_old')
        );
        $this->listar();
    }
    
    function guardar2() {
        $guardar = $this->checklist_model->guardar2(
                $this->input->post('concepto'), $this->input->post('descripcion'), $this->input->post('texto'), $this->input->post('orden'), $this->input->post('activo')
        );
        $this->listar();
    }
    
    function add(){
        $conceptos = array('' => '... Seleccione ...');
        $cpts = $this->checklist_model->getconceptos()->result_array();
        foreach ($cpts as $value) {
            $conceptos[$value['COD_CONCEPTO']] = $value['NOMBRE_CONCEPTO'];
        }
        $concepto = $this->input->post('concepto');
        $this->data['concepto'] = $concepto;
        $this->data['conceptos'] = $conceptos;
        $this->template->load($this->template_file, 'checklist/new', $this->data);
    }
}
