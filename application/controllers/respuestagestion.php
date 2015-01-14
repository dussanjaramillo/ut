<?php

class Respuestagestion extends MY_Controller {

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
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('respuestagestion/manage')) {
        //template data
        $this->template->set('title', 'Respuesta Gestión');
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen'
        );
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js'
        );
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['idgestionn'] = $this->codegen_model->getSelect('TIPOGESTION', 'COD_GESTION, TIPOGESTION');

        $this->template->load($this->template_file, 'respuestagestion/respuestagestion_filtro', $this->data);
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
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('respuestagestion/add')) {
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $showForm = 0;
        $this->form_validation->set_rules('nombrerespuesta', 'Nombre Respuesta', 'required');
        //$this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean|max_length[200]');
        $this->form_validation->set_rules('fechacreacion', 'Fechacreacion', 'required');
        $this->form_validation->set_rules('tipogestion', 'Tipo Gestion', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');
        if ($this->form_validation->run() == false) {
          $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
        } else {
          $date = array(
              'FECHACREACION' => $this->input->post('fechacreacion')
          );
          $data = array(
              'COD_RESPUESTA' => $this->codegen_model->getSequence('dual', 'IDRESPUESTAGESTIONSEQ.NEXTVAL'),
              'COD_TIPOGESTION' => $this->input->post('tipogestion'),
              'NOMBRE_GESTION' => $this->input->post('nombrerespuesta'),
              //'DESCRIPCIONRESP' => $this->input->post('descripcion'),
              'ESTADO' => $this->input->post('estado_id')
          );

          if ($this->codegen_model->add('RESPUESTAGESTION', $data, $date) == TRUE) {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Respuesta se ha creado con éxito.</div>');
            redirect(base_url() . 'index.php/respuestagestion/');
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
        $this->data['estados'] = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO, NOMBREESTADO');
        $this->data['idgestion'] = $this->codegen_model->getSelect('TIPOGESTION', 'COD_GESTION, TIPOGESTION');//$this->codegen_model->getSelect('GESTION', 'COD_GESTION, TIPOGESTION');
        $this->template->load($this->template_file, 'respuestagestion/respuestagestion_add', $this->data);
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/respuestagestion');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function edit() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('respuestagestion/edit')) {
        $ID = $this->uri->segment(3);
        if ($ID == "") {
          $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
          redirect(base_url() . 'index.php/respuestagestion');
        } else {
          $this->load->library('form_validation');
          $this->data['custom_error'] = '';
          $this->form_validation->set_rules('nombrerespuesta', 'Nombre Respuesta', 'required');
          //$this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean|max_length[200]');
          $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');
          if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
          } else {
            $data = array(
                'NOMBRE_GESTION' => $this->input->post('nombrerespuesta'),
                //'DESCRIPCIONRESP' => $this->input->post('descripcion'),
                'ESTADO' => $this->input->post('estado_id')
            );

            if ($this->codegen_model->edit('RESPUESTAGESTION', $data, 'COD_RESPUESTA', $this->input->post('id')) == TRUE) {
              $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La respuesta se ha editado correctamente.</div>');
              redirect(base_url() . 'index.php/respuestagestion/');
            } else {
              $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
            }
          }
          $this->data['result'] = $this->codegen_model->get('RESPUESTAGESTION', 'COD_RESPUESTA, NOMBRE_GESTION, COD_TIPOGESTION, ESTADO', 'COD_RESPUESTA = ' . $this->uri->segment(3), 1, 1, true);
          $this->data['estados'] = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO, NOMBREESTADO');
          $this->data['idgestion'] = $this->codegen_model->getSelect('TIPOGESTION', 'COD_GESTION, TIPOGESTION');//$this->codegen_model->getSelect('GESTION', 'COD_GESTION, TIPOGESTION');

          //add style an js files for inputs selects
          $this->data['style_sheets'] = array(
              'css/chosen.css' => 'screen'
          );
          $this->data['javascripts'] = array(
              'js/chosen.jquery.min.js'
          );

          $this->template->load($this->template_file, 'respuestagestion/respuestagestion_edit', $this->data);
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/respuestagestion');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function delete() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('respuestagestion/delete')) {
        $ID = $this->uri->segment(3);
        if ($ID == "") {
          $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
          redirect(base_url() . 'index.php/respuestagestion');
        } else {
          $data = array(
              'ESTADO' => '2'
          );
          if ($this->codegen_model->edit('RESPUESTAGESTION', $data, 'COD_RESPUESTA', $ID) == TRUE) {
            //$this->codegen_model->delete('RESPUESTAGESTION','COD_RESPUESTA',$ID);             
            $this->template->set('title', 'Respuesta Gestión');
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La respuesta se eliminó correctamente.' . $ID . '</div>');
            redirect(base_url() . 'index.php/respuestagestion/');
          }
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/respuestagestion');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function filtro() {

    $id = $this->input->post('id');
    $datos['respuestas'] = $this->codegen_model->getoption('RESPUESTAGESTION', 'COD_TIPOGESTION', $id);
    $this->load->view('respuestagestion/respuestagestion_filtro2', $datos);
  }

  function filtrofinal() {

    $this->form_validation->set_rules('tipogestion', 'Tipo Gestion', 'required|numeric');
    $this->form_validation->set_rules('respuestagestion', 'Respuesta Gestion', 'required|numeric');
    if ($this->form_validation->run() == false) {
      $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Seleccione los datos.</div>');
      redirect(base_url() . 'index.php/respuestagestion');
    } else {
      $idgest = $this->input->post('tipogestion');
      $idresp = $this->input->post('respuestagestion');
      redirect(base_url() . 'index.php/respuestagestion/manage/' . $idgest . '/' . $idresp . '');
    }
  }

  function datatable() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('respuestagestion/manage')) {
        $idgestionn = $this->uri->segment(3);
        $idrespuestan = $this->uri->segment(4);

        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('respuestagestion/edit')) {
          $this->load->library('datatables');
          $this->datatables->select('RESPUESTAGESTION.COD_RESPUESTA, T.TIPOGESTION, RESPUESTAGESTION.NOMBRE_GESTION');
          $this->datatables->from('RESPUESTAGESTION');
          $array = array(
              'COD_TIPOGESTION' => $idgestionn,
              'COD_RESPUESTA' => $idrespuestan
          );
          $this->datatables->where($array);
          $this->datatables->join('TIPOGESTION T', 'T.COD_GESTION = RESPUESTAGESTION.COD_TIPOGESTION', ' inner ');
          //$this->datatables->join('ESTADOS E', 'E.IDESTADO = RESPUESTAGESTION.IDESTADO', ' inner ');
          $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                  <div class="btn-group">
                                                    <a href="' . base_url() . 'index.php/respuestagestion/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
                                                  </div>
                                                 </div>', 'RESPUESTAGESTION.COD_RESPUESTA');
        } else {
          $this->load->library('datatables');
          $this->datatables->select('RESPUESTAGESTION.COD_RESPUESTA,GES.TIPOGESTION,RESPUESTAGESTION.NOMBREGESTION,RESPUESTAGESTION.DESCRIPCIONRESP,RESPUESTAGESTION.FECHACREACION,E.NOMBREESTADO');
          $this->datatables->from('RESPUESTAGESTION');
          $array = array(
              'IDTIPOGESTION' => $idgestionn,
              'COD_RESPUESTA' => $idrespuestan
          );
          $this->datatables->where($array);
          $this->datatables->join('GESTION GES', 'GES.COD_GESTION=RESPUESTAGESTION.IDTIPOGESTION', ' inner ');
          $this->datatables->join('ESTADOS E', 'E.IDESTADO = RESPUESTAGESTION.IDESTADO', ' inner ');
          $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                  <div class="btn-group">
                                                    <a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
                                                  </div>
                                                 </div>', 'RESPUESTAGESTION.COD_RESPUESTA');
        }
        echo $this->datatables->generate();
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/respuestagestion');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

}

/* End of file respuestagestion.php */
/* Location: ./system/application/controllers/respuestagestion.php */