<?php

class Aplicaciondepago extends MY_Controller {

  function __construct() {

    parent::__construct();
    $this->load->library('form_validation');
    $this->load->helper(array('form', 'url', 'codegen_helper'));
    $this->load->model('codegen_model', '', TRUE);
  }

  function index() {
    $this->manage();
  }

  function manage() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {
        //template data
        $this->template->set('title', 'Administración Aplicacion de Pagos');
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen'
        );
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js'
        );
        $this->data['message'] = $this->session->flashdata('message');
        $this->template->load($this->template_file, 'aplicaciondepago/aplicaciondepago_list', $this->data);
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function add() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/add')) {
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $showForm = 0;
        $this->form_validation->set_rules('nombreestado', 'Nombre Estado', 'required');
        $this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean|max_length[200]');
        $this->form_validation->set_rules('fechacreacion', 'Fechacreacion', 'required');
        $this->form_validation->set_rules('tipoestado', 'Tipo Estado', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');
        if ($this->form_validation->run() == false) {
          $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
        } else {
          $date = array(
              'FECHACREACION' => $this->input->post('fechacreacion')
          );
          $data = array(
              'IDTIPOESTADO' => $this->codegen_model->getSequence('dual', 'IDTIPOESTADOSEQ.NEXTVAL'),
              'IDESTADO_P' => $this->input->post('tipoestado'),
              'NOMBREESTADOT' => $this->input->post('nombreestado'),
              'DESCRIPCIONESTADO' => $this->input->post('descripcion'),
              'IDESTADO' => $this->input->post('estado_id'),
          );

          if ($this->codegen_model->add('TIPOESTADO', $data, $date) == TRUE) {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Estado se ha creado con éxito.</div>');
            redirect(base_url() . 'index.php/aplicaciondepago/');
          } else {
            $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
          }
        }
        //add style an js files for inputs selects
        $this->data['style_sheets'] = array(
            'css/chosen.css' => 'screen'
        );
        $this->data['javascripts'] = array(
            'js/chosen.jquery.min.js'
        );
        $this->data['estados'] = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
        $this->data['estadost'] = $this->codegen_model->getSelect('ESTADO_PARAMETRIZACION', 'IDESTADO_P,TIPOESTADO_P');
        $this->template->load($this->template_file, 'aplicaciondepago/aplicaciondepago_add', $this->data);
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/aplicaciondepago');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function datatable() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

        $select = 'select max(ID_REGISTRO) from APLICACION_DE_PAGO';
        $this->load->library('datatables');
        $this->datatables->select('ID_REGISTRO,DISTRIBUCION_CAPITAL,DISTRIBUCION_INTERES_CTE,DISTRIBUCION_INTERES_MORA,FECHACREACION');
        $this->datatables->from('APLICACION_DE_PAGO');
        $this->datatables->where('ID_REGISTRO', $select);
        $this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="' . base_url() . 'index.php/aplicaciondepago/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'ID_REGISTRO');

        echo $this->datatables->generate();
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/aplicaciondepago');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

}

/* End of file aplicaciondepago.php */
/* Location: ./system/application/controllers/aplicaciondepago.php */