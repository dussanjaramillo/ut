<?php

class Rutas extends MY_Controller {

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
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('rutas/manage')) {
        //template data
        $this->template->set('title', 'Ruta Archivos');
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen'
        );
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js'
        );
        $this->data['message'] = $this->session->flashdata('message');

        $this->template->load($this->template_file, 'rutas/rutas_list', $this->data);
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
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('rutas/add')) {
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $showForm = 0;
        $this->form_validation->set_rules('idbanco', 'Id Banco', 'required|numeric');
        $this->form_validation->set_rules('idorigendatos', 'Origen datos', 'required|numeric');
        $this->form_validation->set_rules('idtipoaccion', 'Tipo Acción', 'required|numeric');
        $this->form_validation->set_rules('idtipocarpeta', 'Tipo Carpeta', 'required|numeric');
        $this->form_validation->set_rules('nombreruta', 'Nombre ruta', 'required');
        $this->form_validation->set_rules('fechacreacion', 'Fechacreacion', 'required');
        $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');
        if ($this->form_validation->run() == false) {
          $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
        } else {
          $date = array(
              'FECHA_CREACION' => $this->input->post('fechacreacion')
          );
          $data = array(
              'COD_RUTA_ARCHIVO' => $this->codegen_model->getSequence('dual', 'IDRUTAARCHIVOSEQ.NEXTVAL'),
              'COD_ORIGEN_DATOS' => $this->input->post('idorigendatos'),
              'COD_BANCO' => $this->input->post('idbanco'),
              'COD_TIPO_ACCION' => $this->input->post('idtipoaccion'),
              'COD_TIPO_CARPETA' => $this->input->post('idtipocarpeta'),
              'RUTA' => $this->input->post('nombreruta'),
              'COD_ESTADO' => $this->input->post('estado_id')
          );

          if ($this->codegen_model->add('RUTA_ARCHIVOS', $data, $date) == TRUE) {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Ruta se ha creado con éxito.</div>');
            redirect(base_url() . 'index.php/rutas/');
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
        $this->data['origendatos'] = $this->codegen_model->getSelect('ORIGENDATOS', 'COD_ORIGENDATOS,NOMBRE_ORIGENDATOS');
        $this->data['tipoaccion'] = $this->codegen_model->getSelect('TIPOACCION', 'IDTIPOACCION,NOMBRETIPOACCION');
        $this->data['tipocarpeta'] = $this->codegen_model->getSelect('TIPOCARPETA', 'IDTIPOCARPETA,NOMBRETIPOCARPETA');
        $this->data['bancos'] = $this->codegen_model->getSelect('BANCO', 'IDBANCO,NOMBREBANCO');


        $this->template->load($this->template_file, 'rutas/rutas_add', $this->data);
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/rutas');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function edit() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('rutas/edit')) {
        $ID = $this->uri->segment(3);
        if ($ID == "") {
          $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
          redirect(base_url() . 'index.php/rutas');
        } else {
          $this->load->library('form_validation');
          $this->data['custom_error'] = '';
          $this->form_validation->set_rules('nombreruta', 'Nombre ruta', 'required');
          $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');

          if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
          } else {
            $data = array(
                'RUTA' => $this->input->post('nombreruta'),
                'COD_ESTADO' => $this->input->post('estado_id')
            );

            if ($this->codegen_model->edit('RUTA_ARCHIVOS', $data, 'COD_RUTA_ARCHIVO', $this->input->post('id')) == TRUE) {
              $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La ruta se ha editado correctamente.</div>');
              redirect(base_url() . 'index.php/rutas/');
            } else {
              $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
            }
          }
          $this->data['result'] = $this->codegen_model->get('RUTA_ARCHIVOS', 'COD_RUTA_ARCHIVO,COD_ORIGEN_DATOS,COD_TIPO_ACCION,COD_TIPO_CARPETA,COD_BANCO,RUTA,COD_ESTADO', 'COD_RUTA_ARCHIVO = ' . $this->uri->segment(3), 1, 1, true);
          $this->data['estados'] = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO, NOMBREESTADO');
          $this->data['origendatos'] = $this->codegen_model->getSelect('ORIGENDATOS', 'COD_ORIGENDATOS, NOMBRE_ORIGENDATOS');
          $this->data['tipoaccion'] = $this->codegen_model->getSelect('TIPOACCION', 'IDTIPOACCION,NOMBRETIPOACCION');
          $this->data['tipocarpeta'] = $this->codegen_model->getSelect('TIPOCARPETA', 'IDTIPOCARPETA,NOMBRETIPOCARPETA');
          $this->data['bancos'] = $this->codegen_model->getSelect('BANCO', 'IDBANCO,NOMBREBANCO');


          //add style an js files for inputs selects
          $this->data['style_sheets'] = array(
              'css/chosen.css' => 'screen'
          );
          $this->data['javascripts'] = array(
              'js/chosen.jquery.min.js'
          );

          $this->template->load($this->template_file, 'rutas/rutas_edit', $this->data);
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/rutas');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function delete() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('rutas/delete')) {
        $ID = $this->uri->segment(3);
        if ($ID == "") {
          $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
          redirect(base_url() . 'index.php/rutas');
        } else {
          $data = array(
              'COD_ESTADO' => '2'
          );
          if ($this->codegen_model->edit('RUTA_ARCHIVOS', $data, 'COD_RUTA_ARCHIVO', $ID) == TRUE) {
            //$this->codegen_model->delete('RUTA_ARCHIVOS','COD_RUTA_ARCHIVO',$ID);             
            $this->template->set('title', 'Respuesta Gestión');
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La ruta se eliminó correctamente.' . $ID . '</div>');
            redirect(base_url() . 'index.php/rutas/');
          }
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/rutas');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function datatable() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('rutas/manage')) {

        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('rutas/edit')) {
          $this->load->library('datatables');
          $this->datatables->select('RUTA_ARCHIVOS.COD_RUTA_ARCHIVO,ORD.NOMBRE_ORIGENDATOS,BANC.NOMBREBANCO,TPA.NOMBRETIPOACCION, RUTA_ARCHIVOS.RUTA,  TPC.NOMBRETIPOCARPETA,RUTA_ARCHIVOS.FECHA_CREACION,E.NOMBREESTADO');
          $this->datatables->from('RUTA_ARCHIVOS');
          $this->datatables->join('ORIGENDATOS ORD', 'ORD.COD_ORIGENDATOS=RUTA_ARCHIVOS.COD_ORIGEN_DATOS', ' inner ');
          $this->datatables->join('TIPOACCION TPA', 'TPA.IDTIPOACCION = RUTA_ARCHIVOS.COD_TIPO_ACCION', ' inner ');
          $this->datatables->join('TIPOCARPETA TPC', 'TPC.IDTIPOCARPETA = RUTA_ARCHIVOS.COD_TIPO_CARPETA', ' inner ');
          $this->datatables->join('BANCO BANC', 'BANC.IDBANCO = RUTA_ARCHIVOS.COD_BANCO', ' inner ');
          $this->datatables->join('ESTADOS E', 'E.IDESTADO = RUTA_ARCHIVOS.COD_ESTADO', ' inner ');
          $this->datatables->where('RUTA_ARCHIVOS.COD_ESTADO <> 2');
          $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                  <div class="btn-group">
                                                    <a href="' . base_url() . 'index.php/rutas/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
                                                  </div>
                                                 </div>', 'RUTA_ARCHIVOS.COD_RUTA_ARCHIVO');
        } else {
          $this->load->library('datatables');
          $this->datatables->select('RUTA_ARCHIVOS.COD_RUTA_ARCHIVO,ORD.NOMBRE_ORIGENDATOS,BANC.NOMBREBANCO,TPA.NOMBRETIPOACCION, RUTA_ARCHIVOS.RUTA,  TPC.NOMBRETIPOCARPETA,RUTA_ARCHIVOS.FECHA_CREACION,E.NOMBREESTADO');
          $this->datatables->from('RUTA_ARCHIVOS');
          $this->datatables->join('ORIGENDATOS ORD', 'ORD. IDORIGENDATOS=RUTA_ARCHIVOS.COD_ORIGEN_DATOS', ' inner ');
          $this->datatables->join('TIPOACCION TPA', 'TPA.IDTIPOACCION = RUTA_ARCHIVOS.COD_TIPO_ACCION', ' inner ');
          $this->datatables->join('TIPOCARPETA TPC', 'TPC.IDTIPOCARPETA = RUTA_ARCHIVOS.COD_TIPO_CARPETA', ' inner ');
          $this->datatables->join('BANCO BANC', 'BANC.IDBANCO = RUTA_ARCHIVOS.COD_BANCO', ' inner ');
          $this->datatables->join('ESTADOS E', 'E.IDESTADO = RUTA_ARCHIVOS.COD_ESTADO', ' inner ');
          $this->datatables->where('RUTA_ARCHIVOS.COD_ESTADO <> 2');
          $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                  <div class="btn-group">
                                                    <a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
                                                  </div>
                                                 </div>', 'RUTA_ARCHIVOS.COD_RUTA_ARCHIVO');
        }
        echo $this->datatables->generate();
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/rutas');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

}

/* End of file rutas.php */
/* Location: ./system/application/controllers/rutas.php */