<?php

class Usuarios extends MY_Controller {

    //var $usuarios_model;

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'email'));
        $this->load->model('usuarios_model');
        $this->load->file(APPPATH . "controllers/sgva_client.php", true);
        //$this->usuarios_model = Usuarios_model();
    }

    function index() {
        $this->manage();
    }

    function manage() {
        if ($this->ion_auth->is_admin()) {
            //template data
            $this->template->set('title', 'Usuarios');
            $this->data['style_sheets'] = array(
                'css/jquery.dataTables_themeroller.css' => 'screen'
            );
            $this->data['javascripts'] = array(
                'js/jquery.dataTables.min.js',
                'js/jquery.dataTables.defaults.js'
            );
            $this->data['message'] = $this->session->flashdata('message');
            $this->template->load($this->template_file, 'usuarios/usuarios_list', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function add() {
        if ($this->ion_auth->is_admin()) {
            $this->load->library('form_validation');
            $this->data['custom_error'] = '';
            $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[50]');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|max_length[150]');
            $this->form_validation->set_rules('cedula', 'Cédula', 'required|trim|xss_clean|max_length[12]|numeric|integer|min_length[6]');
            $this->form_validation->set_rules('nombres', 'Nombres', 'required|trim|xss_clean|max_length[50]');
            $this->form_validation->set_rules('apellidos', 'Apellidos', 'required|trim|xss_clean|max_length[100]');
            $this->form_validation->set_rules('cargo_id', 'Cargo', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('grupo_id', 'Grupo', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('regional_id', 'Regional', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('fiscalizador', 'Fiscalizador', 'required');

            $this->form_validation->set_rules('password', 'Contraseña', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[repassword]');
            $this->form_validation->set_rules('repassword', 'Confirmar contraseña', 'required');

            $this->form_validation->set_rules('direccion', 'Dirección', 'required|xss_clean|trim|max_length[100]');
            $this->form_validation->set_rules('telefono', 'Teléfono', 'xss_clean|trim|max_length[12]');
            $this->form_validation->set_rules('celular', 'Celular', 'required|xss_clean|trim|numeric');
            $this->form_validation->set_rules('emailp', 'Email Personal', 'trim|required|valid_email|max_length[100]');

            if ($this->ion_auth->username_check(mb_strtolower($this->input->post('nombre'))) || $this->ion_auth->email_check(mb_strtolower($this->input->post('email'))) || $this->ion_auth->id_check($this->input->post('cedula'))) {
                $this->data['custom_error'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Alias, email o documento ya se encuentra registrado</div>';
            } else {
                if ($this->form_validation->run() == false) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {

                    $id = $this->input->post('cedula');
                    $username = mb_strtolower($this->input->post('nombre'));
                    $email = mb_strtolower($this->input->post('email'));
                    $password = $this->input->post('password');
                    $group = array($this->input->post('grupo_id'));
                    $additional_data = array(
                        'IDUSUARIO' => set_value('cedula'),
                        'NOMBRES' => set_value('nombres'),
                        'APELLIDOS' => set_value('apellidos'),
                        'IDCARGO' => set_value('cargo_id'),
                        'COD_REGIONAL' => set_value('regional_id'),
                        'FISCALIZADOR' => set_value('fiscalizador'),
                        'DIRECCION' => set_value('direccion'),
                        'TELEFONO' => set_value('telefono'),
                        'CELULAR' => set_value('celular'),
                        'CORREO_PERSONAL' => set_value('emailp')
                    ); //echo "<pre>";print_r($additional_data);exit("</pre>");
                    //Crear Usuario fiscalizador en Sgva

                    $this->sgva_client = new sgva_client();
                    $resultado = $this->sgva_client->CrearFiscalizador($additional_data);
                    if ($this->ion_auth->register($id, $username, $password, $email, $additional_data, $group) !== FALSE) {
                        $this->usuarios_model->PermisosUsuario($group[0], $id);

                        if ($resultado):
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El usuario se ha creado con éxito en Cartera y ' . $resultado . '</div>');

                        else:
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El usuario se ha creado con éxito.</div>');

                        endif;
                        redirect(base_url() . 'index.php/usuarios/');
                    } else {
                        $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
                    }
                }
            } // username_check
            //add style an js files for inputs selects
            $this->data['style_sheets'] = array(
                'css/chosen.css' => 'screen'
            );
            $this->data['javascripts'] = array(
                'js/chosen.jquery.min.js'
            );
            $this->data['cargos'] = $this->usuarios_model->ObtenerCargos();
            $this->data['grupos'] = $this->usuarios_model->ObtenerGrupos();
            $this->data['regionales'] = $this->usuarios_model->ObtenerRegionales();
            $this->template->load($this->template_file, 'usuarios/usuarios_add', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function edit() {
        $ID = $this->uri->segment(3);
        if ($this->ion_auth->is_admin()) {
            $this->load->library('form_validation');
            $this->data['custom_error'] = '';
            $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[50]');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|max_length[150]');
            $this->form_validation->set_rules('cedula', 'Cédula', 'required|trim|xss_clean|max_length[12]|numeric|integer|min_length[6]');
            $this->form_validation->set_rules('nombres', 'Nombres', 'required|trim|xss_clean|max_length[50]');
            $this->form_validation->set_rules('apellidos', 'Apellidos', 'required|trim|xss_clean|max_length[100]');
            $this->form_validation->set_rules('cargo_id', 'Cargo', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('regional_id', 'Regional', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('grupo_id', 'Grupo', 'required|numeric|greater_than[0]');
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', 'Contraseña', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[repassword]');
                $this->form_validation->set_rules('repassword', 'Confirmar contraseña', 'required');
            }

            $this->form_validation->set_rules('direccion', 'Dirección', 'required|xss_clean|trim|max_length[100]');
            $this->form_validation->set_rules('telefono', 'Teléfono', 'xss_clean|trim|max_length[12]');
            $this->form_validation->set_rules('celular', 'Celular', 'required|xss_clean|trim|numeric');
            $this->form_validation->set_rules('emailp', 'Email Personal', 'trim|required|valid_email|max_length[100]');

            $datos_usuarios = array();
            $this->data['result'] = $this->usuarios_model->ObtenerUsuario($this->uri->segment(3));
            foreach ($this->data['result'] as $key => $value) {
                $datos_usuarios[$key] = $value;
            }

            if ($this->form_validation->run() == false) {
                $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
            } else {
                $errores = 0;
                if ($datos_usuarios['NOMBREUSUARIO'] != $this->input->post('nombre')) {
                    if ($this->ion_auth->username_check(strtolower($this->input->post('nombre')))) {
                        $this->data['custom_error'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Alias  ya se encuentra registrado</div>';
                        $errores = 1;
                    }
                }
                if ($datos_usuarios['IDUSUARIO'] != $this->input->post('cedula')) {
                    if ($this->ion_auth->id_check($this->input->post('cedula'))) {
                        $this->data['custom_error'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Documento ya se encuentra registrado</div>';
                        $errores = 1;
                    }
                }
                if ($datos_usuarios['EMAIL'] != $this->input->post('email')) {
                    if ($this->ion_auth->email_check(strtolower($this->input->post('email')))) {
                        $this->data['custom_error'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Email  ya se encuentra registrado</div>';
                        $errores = 1;
                    }
                }


                if ($errores == 0) {
                    $data = array(
                        'NOMBREUSUARIO' => $this->input->post('nombre'),
                        'EMAIL' => $this->input->post('email'),
                        'NOMBRES' => $this->input->post('nombres'),
                        'APELLIDOS' => $this->input->post('apellidos'),
                        'IDUSUARIO' => $this->input->post('cedula'),
                        'IDCARGO' => $this->input->post('cargo_id'),
                        'COD_REGIONAL' => $this->input->post('regional_id'),
                        'FISCALIZADOR' => $this->input->post('fiscalizador'),
                        'DIRECCION' => $this->input->post('direccion'),
                        'TELEFONO' => $this->input->post('telefono'),
                        'CELULAR' => $this->input->post('celular'),
                        'CORREO_PERSONAL' => $this->input->post('emailp')
                    );

                    if ($this->usuarios_model->EditarUsuario($data, $this->input->post('id')) == TRUE) {
                        if ($this->input->post('password')) {
                            $data_grupos = array(
                                'IDGRUPO' => $this->input->post('grupo_id'),
                            );
                        }
                        $data_grupos = array(
                            'IDGRUPO' => $this->input->post('grupo_id'),
                        );
                        //Si el grupo es diferente al que el usuario tenia, borramos los permisos de ese grupo
                        $id = $this->input->post('id');
                        $this->db->select("IDGRUPO");
                        $this->db->where("IDUSUARIO", $id);
                        $tmp = $this->db->get("USUARIOS_GRUPOS");
                        $tmp = $tmp->result_array();
                        $grupo_id = $tmp[0]['IDGRUPO'];
                        $newgroup = $this->input->post('grupo_id');
                        if ($grupo_id != $newgroup) :
                            $this->usuarios_model->PermisosUsuariosEditar($grupo_id, $id);
                        endif;

                        if ($this->usuarios_model->EditarUsuarioGrupo($data_grupos, $this->input->post('id')) == TRUE) {
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El usuario se ha editado correctamente.</div>');
                            redirect(base_url() . 'index.php/usuarios/');
                        } else {
                            $this->data['custom_error'] = '<div class="alert alert-error"><p>No se pudo cambiar el grupo</p></div>';
                        }
                    } else {
                        $this->data['custom_error'] = '<div class="alert alert-error"><p>Ha ocurrido un error</p></div>';
                    }
                    $this->data['custom_error'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se actualizó el usuario correctamente</div>';
                } //errores check
            }

            //add style an js files for inputs selects
            $this->data['style_sheets'] = array(
                'css/chosen.css' => 'screen'
            );
            $this->data['javascripts'] = array(
                'js/chosen.jquery.min.js'
            );
            $this->data['cargos'] = $this->usuarios_model->ObtenerCargos();
            $this->data['grupos'] = $this->usuarios_model->ObtenerGrupos();
            $this->data['regionales'] = $this->usuarios_model->ObtenerRegionales();
            $this->data['usuario_grupo'] = $this->usuarios_model->ObtenerIdGrupoUsuario($this->uri->segment(3));
            $this->template->load($this->template_file, 'usuarios/usuarios_edit', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function edit_user($id) {
        $ID = $this->uri->segment(3);
        if ($ID == $this->session->userdata('user_id')) {
            $this->load->library('form_validation');
            $this->data['custom_error'] = '';
            $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[50]');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|max_length[150]');
            $this->form_validation->set_rules('cedula', 'Cédula', 'required|trim|xss_clean|max_length[12]|numeric|integer|min_length[6]');
            $this->form_validation->set_rules('nombres', 'Nombres', 'required|trim|xss_clean|max_length[50]');
            $this->form_validation->set_rules('apellidos', 'Apellidos', 'required|trim|xss_clean|max_length[100]');
            // $this->form_validation->set_rules('cargo_id', 'Cargo',  'required|numeric|greater_than[0]');
            if ($this->form_validation->run() == false) {
                $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
            } else {
                $data = array(
                    'NOMBREUSUARIO' => $this->input->post('nombre'),
                    'EMAIL' => $this->input->post('email'),
                    'IDUSUARIO' => $this->input->post('cedula'),
                    'NOMBRES' => $this->input->post('nombres'),
                    'APELLIDOS' => $this->input->post('apellidos')
                );

                if ($this->ion_auth->update($this->session->userdata('user_id'), $data) == TRUE) {
                    $this->data['message'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Sus datos se han editado correctamente.</div>';
                } else {
                    $this->data['custom_error'] = '<div class="alert alert-error"><p>Ha ocurrido un error</p></div>';
                }
            }
            $this->data['result'] = $this->usuarios_model->ObtenerUsuario($this->session->userdata('user_id'));
            //add style an js files for inputs selects
            $this->data['style_sheets'] = array(
                'css/chosen.css' => 'screen'
            );
            $this->data['javascripts'] = array(
                'js/chosen.jquery.min.js'
            );
            $this->template->load($this->template_file, 'usuarios/usuarios_edit_user', $this->data);
        } else {
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function change_password() {
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('password', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[repassword]');
        $this->form_validation->set_rules('repassword', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');
        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
        } else {
            $user = $this->ion_auth->user()->row();
            $change = $this->ion_auth->change_password($user->EMAIL, $this->input->post('old'), $this->input->post('password'));
            if ($change) {
                //if the password was successfully changed
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                //logout
                $logout = $this->ion_auth->logout();
                redirect('auth/login', 'refresh');
            } else {
                $this->data['message'] = $this->ion_auth->errors();
            }
        }
        $this->template->load($this->template_file, 'usuarios/usuarios_change_password', $this->data);
    }

    function deactivate($id = NULL) {
        if ($this->ion_auth->is_admin()) {
            $this->ion_auth->deactivate($id);
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se desactivó el usuario correctamente</div>');
            redirect(base_url() . 'index.php/usuarios/');
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function activate($id = NULL) {
        if ($this->ion_auth->is_admin()) {
            $this->ion_auth->activate($id);
            $this->sgva_client = new sgva_client();
            $resultado = $this->sgva_client->CambiarEstadoFiscalizador($id);
            if ($resultado):
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se activó el usuario correctamente.'.$resultado.'</div>');
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se activó el usuario correctamente</div>');

            endif;
            redirect(base_url() . 'index.php/usuarios/');
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function permisos() {
        if ($this->ion_auth->is_admin()) {
            $this->data['custom_error'] = '';
            $ID = $this->uri->segment(3);
            $this->template->set('title', 'Editar permisos predeterminados para usuarios');
            $this->data['style_sheets'] = array(
                'css/blitzer/jquery-ui-1.10.3.custom.css' => 'screen',
                'css/jquery.dataTables_themeroller.css' => 'screen'
            );
            $this->data['javascripts'] = array(
                'js/jquery.dataTables.min.js',
                'js/jquery.dataTables.defaults.js'
            );
            $this->data['result'] = $this->usuarios_model->ObtenerUsuario($this->uri->segment(3));
            $this->template->load($this->template_file, 'usuarios/usuarios_permisos', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function predeterminar() {
        if ($this->ion_auth->is_admin()) {
            $this->load->library('form_validation');
            $id_menu = $this->input->post('id_menu');
            $id_usuario = $this->input->post('id_usuario');


            $this->form_validation->set_rules('id_menu', 'Menú', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('id_usuario', 'Usuario', 'required|numeric|greater_than[0]');

            if ($this->form_validation->run() == false) {
                echo $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
            } else {

                $data = array(
                    'IDMENU' => $id_menu,
                    'IDUSUARIO' => $id_usuario
                );

                if ($this->usuarios_model->AgregarPermisosUsuarios($data) == TRUE) {
                    echo $this->data['custom_error'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Agregado correctamente</div>';
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
            $this->form_validation->set_rules('id_permiso', 'Permiso', 'required|numeric|greater_than[0]');
            if ($this->form_validation->run() == false) {
                echo $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
            } else {
                $this->usuarios_model->BorrarPermiso($id_permiso);
                echo $this->data['custom_error'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Eliminado correctamente</div>';
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function elegir() {
        if ($this->ion_auth->is_admin()) {
            $usuarioid = $this->uri->segment(3);
            $this->load->library('datatables');
            $this->datatables->select('MENUS.IDMENU,MP.NOMBREMACROPROCESO,A.NOMBREAPLICACION,M.NOMBREMODULO,MENUS.NOMBREMENU,PU.IDPERMISO_USUARIO');
            $this->datatables->from('MENUS');
            $this->datatables->join('MODULOS M', 'M.IDMODULO = MENUS.IDMODULO', 'left');
            $this->datatables->join('APLICACIONES A', 'A.IDAPLICACION = M.IDAPLICACION', 'left');
            $this->datatables->join('MACROPROCESO MP', 'MP.CODMACROPROCESO = A.CODPROCESO', 'left');
            $this->datatables->join('PERMISOS_USUARIOS PU', 'PU.IDMENU = MENUS.IDMENU AND  PU.IDUSUARIO=' . $usuarioid, 'left');
            /* $this->datatables->where('PG.IDPERMISOGRUPO', NULL);
              $this->datatables->add_column('edit', '<div class="btn-toolbar">
              <div class="btn-group">
              <a href="'.base_url().'index.php/grupos/edit/$1" class="btn btn-small agrega" id="$1" title="Predeterminar acceso al menú para este grupo"><i class="icon-arrow-right"></i></a>
              </div>
              </div>', 'MENUS.IDMENU'); */
            echo $this->datatables->generate();
        }
    }

    function datatable() {
        if ($this->ion_auth->is_admin()) {
            $this->load->library('datatables');
            $this->datatables->select('USUARIOS.IDUSUARIO,USUARIOS.NOMBREUSUARIO,USUARIOS.EMAIL,USUARIOS.NOMBRES,USUARIOS.APELLIDOS,C.NOMBRECARGO,G.NOMBREGRUPO,USUARIOS.ULTIMOLOGIN,USUARIOS.ACTIVO');
            $this->datatables->from('USUARIOS');
            $this->datatables->join('CARGOS C', 'C.IDCARGO = USUARIOS.IDCARGO', ' inner ');
            $this->datatables->join('USUARIOS_GRUPOS GU', 'GU.IDUSUARIO = USUARIOS.IDUSUARIO', ' inner ');
            $this->datatables->join('GRUPOS G', 'GU.IDGRUPO = G.IDGRUPO', ' inner ');
            $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                <div class="btn-group">
                                                   <a href="' . base_url() . 'index.php/usuarios/edit/$1" class="btn btn-small" title="Editar"><i class="fa fa-pencil-square-o"></i></a>
                                                   <a href="' . base_url() . 'index.php/usuarios/permisos/$1" class="btn btn-small" title="Permisos de usuario"><i class="fa fa-eye"></i></a>
                                                </div>
                                            </div>', 'USUARIOS.IDUSUARIO');
            echo $this->datatables->generate();
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

}

/* End of file categorias.php */
/* Location: ./system/application/controllers/categorias.php */