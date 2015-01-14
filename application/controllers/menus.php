<?php

class Menus extends MY_Controller {

  function __construct() {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->helper(array('form', 'url', 'codegen_helper'));
    $this->load->model('codegen_model', '', TRUE);
  }

  function index() {
    if ($this->ion_auth->is_admin()) {
      //template data
      $this->template->set('title', 'Administrar men�s');
      $this->data['style_sheets'] = array(
          'css/jquery.dataTables_themeroller.css' => 'screen'
      );
      $this->data['javascripts'] = array(
          'js/jquery.dataTables.min.js',
          'js/jquery.dataTables.defaults.js'
      );
      $this->data['message'] = $this->session->flashdata('message');
      $this->template->load($this->template_file, 'menus/menus_list', $this->data);
    } else {
			echo "error en admin";
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function add() {
    if ($this->ion_auth->is_admin()) {
      $this->load->library('form_validation');
      $this->data['custom_error'] = '';
      $showForm = 0;
      $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[30]');
      $this->form_validation->set_rules('url', 'M&eacute;todo', 'required|trim|xss_clean|max_length[60]');
      $this->form_validation->set_rules('modulo_id', 'M&oacute;dulo', 'required|numeric|greater_than[0]');
      $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');
      $this->form_validation->set_rules('icono', '&Iacute;cono', 'required|trim|xss_clean|max_length[100]');
      $this->form_validation->set_rules('en_menu', 'En men&uacute;', 'required|trim|xss_clean|max_length[100]');
      if ($this->form_validation->run() == false) {
        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
      } else {

        $data = array(
            'NOMBREMENU' => set_value('nombre'),
            'URL' => set_value('url'),
            'IDMODULO' => set_value('modulo_id'),
            'IDESTADO' => set_value('estado_id'),
            'ICONOMENU' => set_value('icono'),
            'IN_MENU' => set_value('en_menu')
        );

        if ($this->codegen_model->add('MENUS', $data) == TRUE) {
          $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El menú se ha creado con &eacute;xito.</div>');
          redirect(base_url() . 'index.php/menus/');
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
      $this->template->set('title', 'Nuevo elemento del menú');
      $this->data['estados'] = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
      $this->data['modulos'] = $this->codegen_model->getSelect('MODULOS', 'IDMODULO,NOMBREMODULO');
      $this->data['iconos'] = $this->codegen_model->getSelect(' ICONO', 'IDICONO,NOMBREICONO,TRADUCCION');
      $this->template->load($this->template_file, 'menus/menus_add', $this->data);
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function edit() {
    if ($this->ion_auth->is_admin()) {
      $this->load->library('form_validation');
      $this->data['custom_error'] = '';
      $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[30]');
      $this->form_validation->set_rules('url', 'M&eacute;todo', 'required|trim|xss_clean|max_length[60]');
      $this->form_validation->set_rules('modulo_id', 'M&oacute;dulo', 'required|numeric|greater_than[0]');
      $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');
      $this->form_validation->set_rules('icono', '&Iacute;cono', 'required|trim|xss_clean|max_length[128]');
      $this->form_validation->set_rules('en_menu', 'En men&uacute;', 'required|trim|xss_clean|max_length[100]');
      if ($this->form_validation->run() == false) {
        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
      } else {
        $data = array(
            'NOMBREMENU' => $this->input->post('nombre'),
            'URL' => $this->input->post('url'),
            'IDMODULO' => $this->input->post('modulo_id'),
            'IDESTADO' => $this->input->post('estado_id'),
            'ICONOMENU' => $this->input->post('icono'),
            'IN_MENU' => set_value('en_menu')
        );

        if ($this->codegen_model->edit('MENUS', $data, 'IDMENU', $this->input->post('id')) == TRUE) {
          $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El menú se ha editado correctamente.</div>');
          redirect(base_url() . 'index.php/menus/');
        } else {
          $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
        }
      }
      $this->template->set('title', 'Editar elemento del menú');
      $this->data['result'] = $this->codegen_model->get('MENUS', 'IDMENU,NOMBREMENU,URL,IDMODULO,IDESTADO,IN_MENU', 'IDMENU = ' . $this->uri->segment(3), 1, 1, true);
      $this->data['estados'] = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
      $this->data['modulos'] = $this->codegen_model->getSelect('MODULOS', 'IDMODULO,NOMBREMODULO');
      $this->data['iconos'] = $this->codegen_model->getSelect(' ICONO', 'IDICONO,NOMBREICONO,TRADUCCION');
      //add style an js files for inputs selects
      $this->data['style_sheets'] = array(
          'css/chosen.css' => 'screen'
      );
      $this->data['javascripts'] = array(
          'js/chosen.jquery.min.js'
      );

      $this->template->load($this->template_file, 'menus/menus_edit', $this->data);
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function delete() {
    if ($this->ion_auth->is_admin()) {
      $ID = $this->uri->segment(3);
			$this->template->set('title', 'Menus');
			if($this->codegen_model->delete('MENUS', 'IDMENU', $ID) == TRUE) :
				$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El men&uacute; se elimin&oacute; correctamente.</div>');
			else :
				$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>El men&uacute; no se elimin&oacute; correctamente debido a que existen usuarios con permiso a este men&uacute;.</div>');
			endif;
      redirect(base_url() . 'index.php/menus/');
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function datatable() {
    if ($this->ion_auth->is_admin()) {
      $this->load->library('datatables');
      $this->datatables->select('MENUS.IDMENU,MENUS.NOMBREMENU,MENUS.URL,M.NOMBREMODULO,MENUS.IN_MENU,E.NOMBREESTADO');
      $this->datatables->from('MENUS');
      $this->datatables->join('ESTADOS E', 'E.IDESTADO = MENUS.IDESTADO', ' left ');
      $this->datatables->join('MODULOS M', 'M.IDMODULO = MENUS.IDMODULO', ' left ');
      $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                       <div class="btn-group">
                                                          <a href="' . base_url() . 'index.php/menus/edit/$1" class="btn btn-small" title="Editar"><i class="fa fa-pencil-square-o"></i></a>
                                                       </div>
                                                   </div>', 'MENUS.IDMENU');
      echo $this->datatables->generate();
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

}

/* End of file categorias.php */
/* Location: ./system/application/controllers/categorias.php */