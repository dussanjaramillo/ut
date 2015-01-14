<?php

class Ciiu extends MY_Controller {

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
    if ($this->ion_auth->is_admin()) {
      //template data
      $this->template->set('title', 'Aplicaciones');
      $this->data['style_sheets'] = array(
          'css/jquery.dataTables_themeroller.css' => 'screen'
      );
      $this->data['javascripts'] = array(
          'js/jquery.dataTables.min.js',
          'js/jquery.dataTables.defaults.js'
      );
      $this->data['message'] = $this->session->flashdata('message');
      $this->template->load($this->template_file, 'ciiu/ciiu_list', $this->data);
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function add() {
    if ($this->ion_auth->is_admin()) {
      $this->load->library('form_validation');
      $this->data['custom_error'] = '';
      $this->form_validation->set_rules('division', 'División', 'required|numeric|greater_than[0]|max_length[50]');
      $this->form_validation->set_rules('grupo', 'Grupo', 'required|numeric|greater_than[0]|max_length[5]');
      $this->form_validation->set_rules('clase', 'Clase', 'required|numeric|greater_than[0]|max_length[6]');
      $this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean|max_length[250]');
      if ($this->form_validation->run() == false) {
        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
      } else {

        $data = array(
            'DIVISION' => set_value('division'),
            'GRUPO' => set_value('grupo'),
            'CLASE' => set_value('clase'),
            'DESCRIPCION' => set_value('descripcion')
        );

        if ($this->codegen_model->add('CIIU', $data) == TRUE) {
          $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>el CIIU se ha creado con éxito.</div>');
          redirect(base_url() . 'index.php/ciiu/');
        } else {
          $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
        }
      }


      $this->template->load($this->template_file, 'ciiu/ciiu_add', $this->data);
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function edit() {
    if ($this->ion_auth->is_admin()) {
      $this->load->library('form_validation');
      $this->data['custom_error'] = '';
      $this->form_validation->set_rules('division', 'División', 'required|numeric|greater_than[0]|max_length[50]');
      $this->form_validation->set_rules('grupo', 'Grupo', 'required|numeric|greater_than[0]|max_length[5]');
      $this->form_validation->set_rules('clase', 'Clase', 'required|numeric|greater_than[0]|max_length[6]');
      $this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean|max_length[250]');
      if ($this->form_validation->run() == false) {
        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
      } else {
        $data = array(
            'DIVISION' => $this->input->post('division'),
            'GRUPO' => $this->input->post('grupo'),
            'CLASE' => $this->input->post('clase'),
            'DESCRIPCION' => $this->input->post('descripcion')
        );

        if ($this->codegen_model->edit('CIIU', $data, 'COD_CIUU', $this->input->post('id')) == TRUE) {
          $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>el CIIU se ha editado correctamente.</div>');
          redirect(base_url() . 'index.php/ciiu/');
        } else {
          $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
        }
      }
      $iid = $this->uri->segment(3);
      if(!empty($iid)) $where = 'COD_CIUU = ' . $this->uri->segment(3);
      else $where = "";
      $this->data['result'] = $this->codegen_model->get('CIIU', 'COD_CIUU, DIVISION, GRUPO, CLASE,DESCRIPCION', $where, 1, 1, true);
      echo "<pre>";print_r($this->data['result']);echo "</pre>";
      $this->template->load($this->template_file, 'ciiu/ciiu_edit', $this->data);
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function delete() {
    if ($this->ion_auth->is_admin()) {
      $ID = $this->uri->segment(3);
      $this->codegen_model->delete('CIIU', 'COD_CIUU', $ID);
      $this->template->set('title', 'Aplicaciones');
      $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La aplicación se eliminó correctamente.</div>');
      redirect(base_url() . 'index.php/ciiu/');
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function datatable() {
    if ($this->ion_auth->is_admin()) {
      $this->load->library('datatables');
      $this->datatables->select('CIIU.COD_CIUU, CIIU.DIVISION, CIIU.GRUPO, CIIU.CLASE, CIIU.DESCRIPCION');
      $this->datatables->from('CIIU');
      $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                              <div class="btn-group">
                                                <a href="' . base_url() . 'index.php/ciiu/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
                                              </div>
                                             </div>', 'CIIU.COD_CIUU');
      echo $this->datatables->generate();
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

}

/* End of file categorias.php */
/* Location: ./system/application/controllers/categorias.php */