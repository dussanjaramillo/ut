<?php

class Tipocertificados extends MY_Controller {

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

  function manage() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipocertificados/manage')) {
        //template data
        $this->template->set('title', 'Tipo Certificados');
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen'
        );
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js'
        );
        $this->data['message'] = $this->session->flashdata('message');
        $this->template->load($this->template_file, 'tipocertificados/tipocertificados_list', $this->data);
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
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipocertificados/add')) {
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]|is_unique[TIPOCERTIFICADO.NOMBRE_CERTIFICADO]');
        $this->form_validation->set_message('is_unique', 'El tipo certificado ya se encuentra registrado');
        $this->form_validation->set_rules('descripcion', 'Descripcion', 'required|trim|xss_clean|max_length[128]');
        $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');
        if ($this->form_validation->run() == false) {
          $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
        } else {
          $date = array(
              'FECHA_CREACION' => date("d/m/Y")
          );
          $data = array(
              'NOMBRE_CERTIFICADO' => $this->input->post('nombre'),
              'DESCRIPCION' => set_value('descripcion'),
              'ESTADO' => $this->input->post('estado_id')
          );

          if ($this->codegen_model->add('TIPOCERTIFICADO', $data, $date) == TRUE) {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El certificado se ha creado con éxito.</div>');
            redirect(base_url() . 'index.php/tipocertificados/');
          } else {
            $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
          }
        }
        $this->data['estados'] = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
        $this->template->set('title', 'Nuevo Certificado');
        $this->template->load($this->template_file, 'tipocertificados/tipocertificados_add', $this->data);
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/tipocertificados');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function edit() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipocertificados/edit')) {
        $ID = $this->uri->segment(3);
        if ($ID == "") {
          $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
          redirect(base_url() . 'index.php/tipocertificados');
        } else {
          $this->load->library('form_validation');
          $this->data['custom_error'] = '';
          $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
          $this->form_validation->set_rules('descripcion', 'Descripcion', 'required|trim|xss_clean|max_length[128]');
          $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');
          if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
          } else {
            $data = array(
                'NOMBRE_CERTIFICADO' => $this->input->post('nombre'),
                'DESCRIPCION' => $this->input->post('descripcion'),
                'ESTADO' => $this->input->post('estado_id')
            );

            if ($this->codegen_model->edit('TIPOCERTIFICADO', $data, 'COD_TIPO_CERTIFICADO', $this->input->post('id')) == TRUE) {
              $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Certificado se ha editado correctamente.</div>');
              redirect(base_url() . 'index.php/tipocertificados/');
            } else {
              $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
            }
          }
          $this->data['result'] = $this->codegen_model->get('TIPOCERTIFICADO', 'COD_TIPO_CERTIFICADO, NOMBRE_CERTIFICADO, DESCRIPCION, ESTADO', 'COD_TIPO_CERTIFICADO = ' . $this->uri->segment(3), 1, 1, true);
          $this->data['estados'] = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
          $this->template->set('title', 'Editar Certificado');
          $this->template->load($this->template_file, 'tipocertificados/tipocertificados_edit', $this->data);
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/tipocertificados');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function delete() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipocertificados/delete')) {
        $ID = $this->uri->segment(3);

        if ($ID == "") {
          $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
          redirect(base_url() . 'index.php/tipocertificados');
        } else {
          $data = array(
              'ESTADO' => '2'
          );
          if ($this->codegen_model->edit('TIPOCERTIFICADO', $data, 'COD_TIPO_CERTIFICADO', $ID) == TRUE) {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El certificado se eliminó correctamente.</div>');
            redirect(base_url() . 'index.php/tipocertificados/');
          }
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/tipocertificados');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function dataTable() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipocertificados/manage')) {
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipocertificados/edit')) {
          $this->load->library('datatables');
          $this->datatables->select('TIPOCERTIFICADO.COD_TIPO_CERTIFICADO,
                                                TIPOCERTIFICADO.NOMBRE_CERTIFICADO,
                                                TIPOCERTIFICADO.DESCRIPCION,
                                                TIPOCERTIFICADO.FECHA_CREACION,
                                                E.NOMBREESTADO');
          $this->datatables->from('TIPOCERTIFICADO');
          $this->datatables->join('ESTADOS E', 'E.IDESTADO = TIPOCERTIFICADO.ESTADO', ' inner ');
          $this->datatables->where('TIPOCERTIFICADO.ESTADO <> 2');
          $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="' . base_url() . 'index.php/tipocertificados/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
                                                           </div>
                                                       </div>', 'TIPOCERTIFICADO.COD_TIPO_CERTIFICADO');
        } else {
          $this->load->library('datatables');
          $this->datatables->select('TIPOCERTIFICADO.COD_TIPO_CERTIFICADO,
                                                TIPOCERTIFICADO.NOMBRE_CERTIFICADO,
                                                TIPOCERTIFICADO.DESCRIPCION,
                                                TIPOCERTIFICADO.FECHA_CREACION,
                                                E.NOMBREESTADO');
          $this->datatables->from('TIPOCERTIFICADO');
          $this->datatables->join('ESTADOS E', 'E.IDESTADO = TIPOCERTIFICADO.ESTADO', ' inner ');
          $this->datatables->where('TIPOCERTIFICADO.ESTADO <> 2');
          $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
                                                           </div>
                                                       </div>', 'TIPOCERTIFICADO.COD_TIPO_CERTIFICADO');
        }
        echo $this->datatables->generate();
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/tipocertificados');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

}

/* End of file tipocertificados.php */
/* Location: ./system/application/controllers/tipocertificados.php */