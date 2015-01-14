<?php

class Grupos extends MY_Controller {

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
      $this->template->set('title', 'Administrar grupos');
      $this->data['style_sheets'] = array(
          'css/jquery.dataTables_themeroller.css' => 'screen'
      );
      $this->data['javascripts'] = array(
          'js/jquery.dataTables.min.js',
          'js/jquery.dataTables.defaults.js'
      );
      $this->data['message'] = $this->session->flashdata('message');
      $this->template->load($this->template_file, 'grupos/grupos_list', $this->data);
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function add() {
    if ($this->ion_auth->is_admin()) {
      $this->load->library('form_validation');
      $this->data['custom_error'] = '';
      $showForm = 0;
      $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[50]');
      $this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean');
      if ($this->form_validation->run() == false) {
        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
      } else {

        $data = array(
            'NOMBREGRUPO' => set_value('nombre'),
            'DESCRIPTION' => set_value('descripcion')
        );

        if ($this->codegen_model->add('GRUPOS', $data) == TRUE) {
          $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El grupo se ha creado con éxito.</div>');
          redirect(base_url() . 'index.php/grupos/');
        } else {
          $this->data['custom_error'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrió un error.</div>';
        }
      }
      $this->template->set('title', 'Nuevo grupo');
      $this->template->load($this->template_file, 'grupos/grupos_add', $this->data);
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function edit() {
    if ($this->ion_auth->is_admin()) {
      $this->load->library('form_validation');
      $this->data['custom_error'] = '';
      $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[50]');
      $this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean');
      if ($this->form_validation->run() == false) {
        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
      } else {
        $data = array(
            'NOMBREGRUPO' => $this->input->post('nombre'),
            'DESCRIPTION' => $this->input->post('descripcion')
        );

        if ($this->codegen_model->edit('GRUPOS', $data, 'IDGRUPO', $this->input->post('id')) == TRUE) {
          $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El grupo se ha editado correctamente.</div>');
          redirect(base_url() . 'index.php/grupos/');
        } else {
          $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
        }
      }
      $this->template->set('title', 'Editar grupo');
      $this->data['result'] = $this->codegen_model->get('GRUPOS', 'IDGRUPO,NOMBREGRUPO,DESCRIPTION', 'IDGRUPO = ' . $this->uri->segment(3), 1, 1, true);
      $this->data['applicationes'] = $this->codegen_model->get('APLICACIONES', 'IDAPLICACION,NOMBREAPLICACION');
      $this->data['modulos'] = $this->codegen_model->get('MODULOS', 'IDMODULO,NOMBREMODULO');
      $this->data['menus'] = $this->codegen_model->get('MENUS', 'IDMENU,NOMBREMENU');

      $this->template->load($this->template_file, 'grupos/grupos_edit', $this->data);
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function delete() {
    if ($this->ion_auth->is_admin()) {
      $ID = $this->uri->segment(3);
      $this->codegen_model->delete('GRUPOS', 'IDGRUPO', $ID);
      $this->template->set('title', 'Grupos');
      $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El grupo se eliminó correctamente.</div>');
      redirect(base_url() . 'index.php/grupos/');
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function permisos() {
    if ($this->ion_auth->is_admin()) {
      $this->data['custom_error'] = '';
      $ID = $this->uri->segment(3);
      $this->template->set('title', 'Editar permisos predeterminados para grupos');
      $this->data['style_sheets'] = array(
          'css/blitzer/jquery-ui-1.10.3.custom.css' => 'screen',
          'css/jquery.dataTables_themeroller.css' => 'screen'
      );
      $this->data['javascripts'] = array(
          'js/jquery.dataTables.min.js',
          'js/jquery.dataTables.defaults.js'
      );
      $this->data['result'] = $this->codegen_model->get('GRUPOS', 'IDGRUPO, NOMBREGRUPO', 'IDGRUPO = ' . $this->uri->segment(3), 1, 1, true);
      $this->template->load($this->template_file, 'grupos/grupos_permisos', $this->data);
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function predeterminar() {
    if ($this->ion_auth->is_admin()) {
      $this->load->library('form_validation');
      $id_menu = $this->input->post('id_menu');
      $id_grupo = $this->input->post('id_grupo');
      $id_forzar = $this->input->post('id_forzar');

      $this->form_validation->set_rules('id_menu', 'Menú', 'required|numeric|greater_than[0]');
      $this->form_validation->set_rules('id_grupo', 'Grupo', 'required|numeric|greater_than[0]');

      //echo '-------->'.$id_menu.'<----------->'.$id_grupo;
      if ($this->form_validation->run() == false) {
        echo $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
      } else {

        $data = array(
            'IDMENU' => $id_menu,
            'IDGRUPO' => $id_grupo
        );

        if ($this->codegen_model->add('PERMISOS_GRUPOS', $data) == TRUE) {
          $this->db->select("IDUSUARIO");
          $this->db->where("IDGRUPO", $id_grupo);
          $dato = $this->db->get("USUARIOS_GRUPOS");
          $dato = $dato->result_array();
          $data = array();
          foreach ($dato as $user) :
            $this->db->select("IDMENU");
            $this->db->where("IDMENU", $id_menu);
            $this->db->where("IDUSUARIO", $user['IDUSUARIO']);
            $res = $this->db->get("PERMISOS_USUARIOS");
            if($res->num_rows() == 0) :
              array_push($data, array(
                  'IDMENU' => $id_menu,
                  'IDUSUARIO' => $user['IDUSUARIO']
              ));
            endif;
          endforeach;
          if(count($dato) > 0) $this->db->insert_batch('PERMISOS_USUARIOS', $data);

          if ($id_forzar == 1) {
            $this->data['custom_error'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Agregado correctamente</div>';
          } else {
            $this->data['custom_error'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Agregado correctamente</div>';
          }
          echo $this->data['custom_error'];
        } else {
          echo $this->data['custom_error'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrió un error.</div>';
        }
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function despredeterminar() {

    if ($this->ion_auth->is_admin()) {

      $this->load->library('form_validation');
      $id_permiso = $this->input->post('id_permiso');
      $id_forzar = $this->input->post('id_forzar');
      $this->form_validation->set_rules('id_permiso', 'Permiso', 'required|numeric|greater_than[0]');
      if ($this->form_validation->run() == false) {
        echo $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
      } else {
        $this->db->select("IDGRUPO, IDMENU");
        $this->db->where("IDPERMISO_GRUPO", $id_permiso);
        $res = $this->db->get("PERMISOS_GRUPOS");
        $res = $res->result_array();
        $this->db->select("IDUSUARIO");
        $this->db->where("IDGRUPO", $res[0]['IDGRUPO']);
        $dato = $this->db->get("USUARIOS_GRUPOS");
        $dato = $dato->result_array();
        $data = array();
        foreach ($dato as $user) :
          $data[] = $user['IDUSUARIO'];
        endforeach;
        $this->db->where("IDMENU", $res[0]['IDMENU']);
        $this->db->where_in("IDUSUARIO", $data);
        $this->db->delete('PERMISOS_USUARIOS');
        $this->codegen_model->delete('PERMISOS_GRUPOS', 'IDPERMISO_GRUPO', $id_permiso);
        
        if ($id_forzar == 1) {
          echo $this->data['custom_error'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Eliminado correctamente</div>';
        } else {
          echo $this->data['custom_error'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Eliminado correctamente</div>';
        }
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  function elegir() {

    $grupoid = $this->uri->segment(3);
    $this->load->library('datatables');
    $this->datatables->select('MENUS.IDMENU,MP.NOMBREMACROPROCESO,A.NOMBREAPLICACION,M.NOMBREMODULO,MENUS.NOMBREMENU,PG.IDPERMISO_GRUPO');
    $this->datatables->from('MENUS');
    $this->datatables->join('MODULOS M', 'M.IDMODULO = MENUS.IDMODULO', 'left');
    $this->datatables->join('APLICACIONES A', 'A.IDAPLICACION = M.IDAPLICACION', 'left');
    $this->datatables->join('MACROPROCESO MP', 'MP.CODMACROPROCESO = A.CODPROCESO', 'left');
    $this->datatables->join('PERMISOS_GRUPOS PG', 'PG.IDMENU = MENUS.IDMENU AND  PG.IDGRUPO=' . $grupoid, 'left');
    /* $this->datatables->where('PG.IDPERMISOGRUPO', NULL);
      $this->datatables->add_column('edit', '<div class="btn-toolbar">
      <div class="btn-group">
      <a href="'.base_url().'index.php/grupos/edit/$1" class="btn btn-small agrega" id="$1" title="Predeterminar acceso al menú para este grupo"><i class="icon-arrow-right"></i></a>
      </div>
      </div>', 'MENUS.IDMENU'); */
    echo $this->datatables->generate();
  }

  function datatable() {
    if ($this->ion_auth->is_admin()) {
      $this->load->library('datatables');
      $this->datatables->select('GRUPOS.IDGRUPO,GRUPOS.NOMBREGRUPO,GRUPOS.DESCRIPTION');
      $this->datatables->from('GRUPOS');
      $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                       <div class="btn-group">
                                                          <a href="' . base_url() . 'index.php/grupos/edit/$1" class="btn btn-small" title="Editar"><i class="fa fa-pencil-square-o"></i></a>
                                                        <a href="' . base_url() . 'index.php/grupos/permisos/$1" class="btn btn-small" title="Editar permisos predeterminados"><i class="fa fa-eye"></i></a>
                                                       </div>
                                                   </div>', 'GRUPOS.IDGRUPO');
      echo $this->datatables->generate();
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

}

/* End of file categorias.php */
/* Location: ./system/application/controllers/categorias.php */