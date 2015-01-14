<?php

class Operadorinformacion extends MY_Controller {

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
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('operadorinformacion/manage')) {
        //template data
        $this->template->set('title', 'Operador Información');
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen'
        );
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js'
        );
        $this->data['message'] = $this->session->flashdata('message');
        $this->template->load($this->template_file, 'operadorinformacion/operadorinformacion_list', $this->data);
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
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('operadorinformacion/add')) {
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $showForm = 0;
        $this->form_validation->set_rules('nombreoperador', 'Nombre Operador', 'required|trim|xss_clean|max_length[50]');
        $this->form_validation->set_rules('fechacreacion', 'fechacreacion', 'required');
        $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');
        if ($this->form_validation->run() == false) {
          $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
        } else {
          $date = array(
              'FECHA_CREACION' => $this->input->post('fechacreacion')
          );
          $data = array(
              'COD_OPERADOR_INFORMACION' => $this->codegen_model->getSequence('dual', 'IDOPERADORINFORMACIONSEQ.NEXTVAL'),
              'NOMBRE_OPERADOR' => $this->input->post('nombreoperador'),
              'IDESTADO' => $this->input->post('estado_id')
          );

          if ($this->codegen_model->add('OPERADORINFORMACION', $data, $date) == TRUE) {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Operador de información se agregó con éxito.</div>');
            redirect(base_url() . 'index.php/operadorinformacion/');
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
        $this->template->load($this->template_file, 'operadorinformacion/operadorinformacion_add', $this->data);
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/operadorinformacion');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function edit() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('operadorinformacion/edit') || $this->uri->segment(3) != "") {

        $ID = $this->uri->segment(3);
        if ($ID == "") {
          $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
          redirect(base_url() . 'index.php/operadorinformacion');
        } else {
          $this->load->library('form_validation');
          $this->data['custom_error'] = '';
          $this->form_validation->set_rules('nombreoperador', 'Nombre Operador', 'required|trim|xss_clean|max_length[50]');
          $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');
          if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
          } else {
            $data = array(
                'NOMBRE_OPERADOR' => $this->input->post('nombreoperador'),
                'IDESTADO' => $this->input->post('estado_id')
            );

            if ($this->codegen_model->edit('OPERADORINFORMACION', $data, 'COD_OPERADOR_INFORMACION', $this->input->post('id')) == TRUE) {
              $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Operador de información se ha editado correctamente.</div>');
              redirect(base_url() . 'index.php/operadorinformacion/');
            } else {
              $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
            }
          }
          $this->data['result'] = $this->codegen_model->get('OPERADORINFORMACION', 'COD_OPERADOR_INFORMACION, NOMBRE_OPERADOR, IDESTADO', 'COD_OPERADOR_INFORMACION = ' . $this->uri->segment(3), 1, 1, true);
          $this->data['estados'] = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO, NOMBREESTADO');

          //add style an js files for inputs selects
          $this->data['style_sheets'] = array(
              'css/chosen.css' => 'screen'
          );
          $this->data['javascripts'] = array(
              'js/chosen.jquery.min.js'
          );

          $this->template->load($this->template_file, 'operadorinformacion/operadorinformacion_edit', $this->data);
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/operadorinformacion');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function delete() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('operadorinformacion/delete')) {
        $ID = $this->uri->segment(3);
        if ($ID == "") {
          $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
          redirect(base_url() . 'index.php/operadorinformacion');
        } else {
          $data = array(
              'IDESTADO' => '2'
          );
          if ($this->codegen_model->edit('OPERADORINFORMACION', $data, 'COD_OPERADOR_INFORMACION', $ID) == TRUE) {
            //$this->codegen_model->delete('OPERADORINFORMACION','IDOPERADORINFORMACION',$ID);             
            $this->template->set('title', 'Operador Información');
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha eliminado el Operador de información correctamente.</div>');
            redirect(base_url() . 'index.php/operadorinformacion/');
          }
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/operadorinformacion');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function datatable() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('operadorinformacion/manage')) {
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('operadorinformacion/edit')) {
          $this->load->library('datatables');
          $this->datatables->select('OPERADORINFORMACION.COD_OPERADOR_INFORMACION,OPERADORINFORMACION.NOMBRE_OPERADOR,OPERADORINFORMACION.FECHA_CREACION,E.NOMBREESTADO');
          $this->datatables->from('OPERADORINFORMACION');
          $this->datatables->join('ESTADOS E', 'E.IDESTADO = OPERADORINFORMACION.IDESTADO', ' left ');
          $this->datatables->where('OPERADORINFORMACION.IDESTADO <> 2');
          $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                  <div class="btn-group">
                                                    <a href="' . base_url() . 'index.php/operadorinformacion/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
                                                  </div>
                                                 </div>', 'OPERADORINFORMACION.COD_OPERADOR_INFORMACION');
        } else {
          $this->load->library('datatables');
          $this->datatables->select('OPERADORINFORMACION.COD_OPERADOR_INFORMACION,OPERADORINFORMACION.NOMBRE_OPERADOR,OPERADORINFORMACION.FECHA_CREACION,E.NOMBREESTADO');
          $this->datatables->from('OPERADORINFORMACION');
          $this->datatables->join('ESTADOS E', 'E.IDESTADO = OPERADORINFORMACION.IDESTADO', ' left ');
          $this->datatables->where('OPERADORINFORMACION.IDESTADO <> 2');
          $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                  <div class="btn-group">
                                                    <a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
                                                  </div>
                                                 </div>', 'OPERADORINFORMACION.COD_OPERADOR_INFORMACION');
        }
        echo $this->datatables->generate();
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/operadorinformacion');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

}

/* End of file operadorinformacion.php */
/* Location: ./system/application/controllers/operadorinformacion.php */