<?php

class Consultarempresaspagosplanillaunica extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array(
            'form',
            'url',
            'codegen_helper'
        ));
        $this->load->model('codegen_model', '', TRUE);
    }

    function index() {
        $this->manage();
    }

/*
* | ::::::: Main page
* | :::::::::::::::::::::::::::::::::
*/

    function manage() {
        if ( $this->ion_auth->logged_in() ) {
            if ($this->ion_auth->is_admin() ) {

// |::::::: Page Title
                $this->template->set('title', 'Consultar pagos planilla');

// |::::::: CSS
                $this->data['style_sheets']= array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/TableTools.css' => 'screen'
                );

// |::::::: JS
                $this->data['javascripts']= array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/jquery.dataTables.columnFilter.js',
                    'js/ZeroClipboard.js',
                    'js/TableTools.min.js'
                );

// |::::::: ??
                $this->data['message']=$this->session->flashdata('message');


// |::::::: Data
                $this->data['tipo_id']      = $this->codegen_model->getSelect('TIPODOCUMENTO','CODTIPODOCUMENTO,NOMBRETIPODOC');
                $this->data['regional']     = $this->codegen_model->getSelect('REGIONAL', 'COD_REGIONAL,NOMBRE_REGIONAL');

// BORRAR ::::::::::::::::::::::::::::::::::::::::::::::::::::
                $this->data['dump_e']    = $this->db->list_fields('EMPRESA');
                $this->data['dump_r']    = $this->db->list_fields('CARGA_EXTRACTO');
                //PlanillaUnica_Enc
                $this->data['dump_m']    = $this->db->list_fields('PLANILLAUNICA_ENC');
                //PlanillaUnica_Det
                $this->data['dump_o']    = $this->db->list_fields('PLANILLAUNICA_DET');
// BORRAR FIN ::::::::::::::::::::::::::::::::::::::::::::::::

// |::::::: Validacion

                $this->load->library('form_validation');
                $this->data['custom_error'] = '';

                $this->form_validation->set_rules('tipo_id', 'Tipo de Identificación', 'numeric|greater_than[0]');
                $this->form_validation->set_rules('n_id', 'Numero Identificacion', 'numeric|max_length[20]');
                $this->form_validation->set_rules('n_planilla', 'Planilla', 'numeric|max_length[20]');
                $this->form_validation->set_rules('n_razo_soci', 'Razon Social', 'max_length[128]');

// |::::::: Validacion FAIL
                if ($this->form_validation->run() == false) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
            }
// |::::::: Validacion SUCCESS
            else {

            }


// |::::::: Call view
                $this->template->load($this->template_file, 'consultarempresaspagosplanillaunica/consultarempresaspagosplanillaunica_main',$this->data);
            } else {
                redirect(base_url() . 'index.php/auth/login');
            }
        }
        else {
            redirect(base_url().'index.php/auth/login');
        }
    }


/*
* | ::::::: Data
* | :::::::::::::::::::::::::::::::::
*/

    function datatable (){

        if ($this->ion_auth->logged_in()) {

            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarempresaspagosplanillaunica/manage') ) {

                $this->load->library('datatables');

                $this->datatables->select('
                    EMPRESA.CODEMPRESA,
                    EMPRESA.RAZON_SOCIAL,
                    EMPRESA.TELEFONO_FIJO,
                    EMPRESA.DIRECCION,
                    M.NOMBREMUNICIPIO,
                    R.NOMBRE_REGIONAL,
                    T.NOM_TIPO_EMP,
                    EMPRESA.CIIU,
                    EMPRESA.NUM_EMPLEADOS,
                    A.NOM_TIPO_ENT,
                    E.NOMBREESTADO');

                $this->datatables->from('EMPRESA');

                $this->datatables->join('REGIONAL R','R.COD_REGIONAL = EMPRESA.COD_REGIONAL', ' left ');
                $this->datatables->join('ESTADOS E','E.IDESTADO = EMPRESA.ACTIVO', ' left ');
                $this->datatables->join('MUNICIPIO M','M.CODMUNICIPIO = EMPRESA.COD_MUNICIPIO', ' left ');
                $this->datatables->join('TIPOEMPRESA T','T.CODTIPOEMPRESA = EMPRESA.COD_TIPOEMPRESA', ' left ');
                $this->datatables->join('TIPOENTIDAD A','A.CODTIPOENTIDAD = EMPRESA.COD_TIPOENTIDAD', ' left ');

                echo $this->datatables->generate();
            }
            else {

                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url().'index.php/consultarempresaspagosplanillaunica');
            }
        }
        else {

            redirect(base_url().'index.php/auth/login');
        }
    }

}

/* End of file categorias.php */
/* Location: ./system/application/controllers/categorias.php */