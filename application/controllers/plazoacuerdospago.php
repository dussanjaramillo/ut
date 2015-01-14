<?php

class Plazoacuerdospago extends MY_Controller {

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
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('plazoacuerdospago/manage')) {
        //template data
        $this->template->set('title', 'Plazo acuerdos de pago');
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen'
        );
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js'
        );
        $this->data['message'] = $this->session->flashdata('message');
        $this->template->load($this->template_file, 'plazoacuerdospago/plazoacuerdospago_list', $this->data);
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
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('plazoacuerdospago/add')) {
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $showForm = 0;
        $this->form_validation->set_rules('plazo', 'Plazo', 'required|trim|xss_clean|max_length[128]');
        $this->form_validation->set_rules('unidad', 'Unidad', 'required|trim|xss_clean|max_length[128]');
        $this->form_validation->set_rules('periodicidad', 'Periodicidad', 'required|trim|xss_clean|max_length[128]');
        $this->form_validation->set_rules('maxcuotas', 'Max. cuotas', 'required|numeric');
        $this->form_validation->set_rules('concepto', 'Concepto', 'required|numeric');
        $this->form_validation->set_rules('instancia', 'Instancia', 'required|trim|xss_clean|max_length[128]');
        $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');
        if ($this->form_validation->run() == false) {
          $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
        } else {
          $date = array(
              'FECHA_CREACION' => $this->input->post('fechacreaciond')
          );
          $data = array(
              'COD_PLAZO_ACUERDO_PAGO' => $this->codegen_model->getSequence('dual', 'ID_PLAZO_ACUERDO_PAGO_SEQ.NEXTVAL'),
              'PLAZO' => set_value('plazo'),
              'UNIDAD' => set_value('unidad'),
              'PERIODICIDAD' => set_value('periodicidad'),
              'MAX_CUOTAS' => set_value('maxcuotas'),
              'COD_CONCEPTO' => set_value('concepto'),
              'INSTANCIA' => set_value('instancia'),
              'COD_ESTADO' => set_value('estado_id')
          );

          if ($this->codegen_model->add('PLAZO_ACUERDO_PAGO', $data, $date) == TRUE) {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El plazo de acuerdo de pago se ha creado con éxito.</div>');
            redirect(base_url() . 'index.php/plazoacuerdospago/');
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
        $this->data['conceptos'] = $this->codegen_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION, NOMBRE_CONCEPTO');

        $this->template->load($this->template_file, 'plazoacuerdospago/plazoacuerdospago_add', $this->data);
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/plazoacuerdospago');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function edit() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('plazoacuerdospago/edit')) {
        $ID = $this->uri->segment(3);
        if ($ID == "") {
          $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
          redirect(base_url() . 'index.php/plazoacuerdospago');
        } else {
          $this->load->library('form_validation');
          $this->data['custom_error'] = '';
          $this->form_validation->set_rules('plazo', 'Plazo', 'required|trim|xss_clean|max_length[128]');
          $this->form_validation->set_rules('unidad', 'Unidad', 'required|trim|xss_clean|max_length[128]');
          $this->form_validation->set_rules('periodicidad', 'Periodicidad', 'required|trim|xss_clean|max_length[128]');
          $this->form_validation->set_rules('maxcuotas', 'Max. cuotas', 'required|numeric');
          $this->form_validation->set_rules('concepto', 'Concepto', 'required|numeric');
          $this->form_validation->set_rules('instancia', 'Instancia', 'required|trim|xss_clean|max_length[128]');
          $this->form_validation->set_rules('estado_id', 'Estado', 'required|greater_than[0]');
          if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
          } else {
            $data = array(
                'VALORTASA' => $this->input->post('valortasa'),
                'IDESTADO' => $this->input->post('estado_id')
            );

            if ($this->codegen_model->edit('TASA_ACUERDO_PAGO', $data, 'IDTASA', $this->input->post('id')) == TRUE) {
              $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El plazo de acuerdo de pago se ha editado correctamente.</div>');
              redirect(base_url() . 'index.php/plazoacuerdospago/');
            } else {
              $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
            }
          }
          $this->data['result'] = $this->codegen_model->get('PLAZO_ACUERDO_PAGO', 'COD_PLAZO_ACUERDO_PAGO,PLAZO,UNIDAD,PERIODICIDAD,MAX_CUOTAS,INSTANCIA,COD_CONCEPTO,COD_ESTADO', 'COD_PLAZO_ACUERDO_PAGO = ' . $this->uri->segment(3), 1, 1, true);
          $this->data['estados'] = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
          $this->data['conceptos'] = $this->codegen_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION, NOMBRE_CONCEPTO');

          //add style an js files for inputs selects
          $this->data['style_sheets'] = array(
              'css/chosen.css' => 'screen'
          );
          $this->data['javascripts'] = array(
              'js/chosen.jquery.min.js'
          );

          $this->template->load($this->template_file, 'plazoacuerdospago/plazoacuerdospago_edit', $this->data);
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/plazoacuerdospago');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function delete() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('plazoacuerdospago/delete')) {
        $ID = $this->uri->segment(3);
        if ($ID == "") {
          $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
          redirect(base_url() . 'index.php/plazoacuerdospago');
        } else {
          $data = array(
              'COD_ESTADO' => '2'
          );
          if ($this->codegen_model->edit('PLAZO_ACUERDO_PAGO', $data, 'COD_PLAZO_ACUERDO_PAGO', $ID) == TRUE) {
            //$this->codegen_model->delete('PLAZO_ACUERDO_PAGO','ID_PLAZO_ACUERDO_PAGO',$ID);             
            $this->template->set('title', 'Plazo acuerdos de pago');
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El plazo de acuerdo de pago se eliminó correctamente.' . $ID . '</div>');
            redirect(base_url() . 'index.php/plazoacuerdospago/');
          }
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/plazoacuerdospago');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function datatable() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('plazoacuerdospago/manage')) {

        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('plazoacuerdospago/edit')) {
          $this->load->library('datatables');
          $this->datatables->select('PAP.COD_PLAZO_ACUERDO_PAGO, PAP.PLAZO,PAP.UNIDAD,PAP.PERIODICIDAD,PAP.MAX_CUOTAS,PAP.INSTANCIA, C.NOMBRE_CONCEPTO,PAP.FECHA_CREACION, E.NOMBREESTADO');
          $this->datatables->from(' PLAZO_ACUERDO_PAGO PAP');
          $this->datatables->join('ESTADOS E', 'E.IDESTADO = PAP.COD_ESTADO', ' inner ');
          $this->datatables->join('CONCEPTOSFISCALIZACION C', 'C.COD_CPTO_FISCALIZACION = PAP.COD_CONCEPTO', ' inner ');
          $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                  <div class="btn-group">
                                                    <a href="' . base_url() . 'index.php/plazoacuerdospago/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
                                                  </div>
                                                 </div>', 'PAP.COD_PLAZO_ACUERDO_PAGO');
        } else {
          $this->load->library('datatables');
          $this->datatables->select('PAP.COD_PLAZO_ACUERDO_PAGO, PAP.PLAZO,PAP.UNIDAD,PAP.PERIODICIDAD,PAP.MAX_CUOTAS,PAP.INSTANCIA, C.NOMBRE_CONCEPTO,PAP.FECHA_CREACION, E.NOMBREESTADO');
          $this->datatables->from(' PLAZO_ACUERDO_PAGO PAP');
          $this->datatables->join('ESTADOS E', 'E.IDESTADO = PAP.COD_ESTADO', ' inner ');
          $this->datatables->join('CONCEPTOSFISCALIZACION C', 'C.COD_CPTO_FISCALIZACION = PAP.COD_CONCEPTO', ' inner ');
          $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                  <div class="btn-group">
                                                    <a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
                                                  </div>
                                                 </div>', 'PAP.COD_PLAZO_ACUERDO_PAGO');
        }
        echo $this->datatables->generate();
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/plazoacuerdospago');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

}

/* End of file plazoacuerdospago.php */
/* Location: ./system/application/controllers/plazoacuerdospago.php */