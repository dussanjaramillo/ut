<?php

class Consultarusuariofiscalizador extends MY_Controller
{
    
    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array(
            'form',
            'url',
            'codegen_helper'
        ));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('asigna_model');
        
    }
    
    function index()
    {
        $this->manage();
    }
    
    function manage()
    {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarusuariofiscalizador/manage')) {
                
                $this->template->set('title', 'Consultar Usuario Fiscalizador');
                
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts']  = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/jquery.dataTables.columnFilter.js'
                );
                $this->data['regional']     = $this->codegen_model->getSelect('REGIONAL', 'COD_REGIONAL,NOMBRE_REGIONAL');
                $this->data['message']      = $this->session->flashdata('message');
                $this->data['fiscalizadores'] = $this->asigna_model->fiscalizadores();
                $this->template->load($this->template_file, 'consultarusuariofiscalizador/consultarusuariofiscalizador_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
            
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    function deactivate($id = NULL)
    {
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarusuariofiscalizador/manage')) {
            $this->ion_auth->deactivate($id);
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se desactivó el usuario correctamente</div>');
            redirect(base_url() . 'index.php/consultarusuariofiscalizador/');
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
        
    }
    
    function activate($id = NULL)
    {
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarusuariofiscalizador/manage')) {
            $this->ion_auth->activate($id);
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se activó el usuario correctamente</div>');
            redirect(base_url() . 'index.php/consultarusuariofiscalizador/');
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
        
    }
    
    function edit()
    {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarusuariofiscalizador/edit')) {
                $ID = $this->uri->segment(3);
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/consultarusuariofiscalizador');
                } else {
                    $this->load->library('form_validation');
                    
                    $this->data['custom_error'] = '';
                    
                    
                    $this->form_validation->set_rules('direccion', 'Dirección', 'required|xss_clean|trim');
                    $this->form_validation->set_rules('telefono', 'Teléfono', 'trim|xss_clean|max_length[12]');
                    $this->form_validation->set_rules('celular', 'Celular', 'required|xss_clean|trim|numeric');
                    $this->form_validation->set_rules('emaile', 'Correo Empresarial', 'trim|required|xss_clean|valid_email');
                    $this->form_validation->set_rules('emailp', 'Correo Personal', 'trim|required|xss_clean|valid_email');
                    $this->form_validation->set_rules('regional_id', 'Regional', 'trim|required|xss_clean|numeric|greater_than[0]');
                    $this->form_validation->set_rules('estado_id', 'Estado', 'trim|required|xss_clean|numeric|greater_than[0]');
                    
                    
                    if ($this->form_validation->run() == false) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                        
                    } else {
                        
                        $date = array(
                            'MODIFICADO' => date("d/m/Y")
                        );
                        $data = array(
                            
                            'DIRECCION' => set_value('direccion'),
                            'TELEFONO' => $this->input->post('telefono'),
                            'CELULAR' => $this->input->post('celular'),
                            'EMAIL' => $this->input->post('emaile'),
                            'CORREO_PERSONAL' => $this->input->post('emailp'),
                            'COD_REGIONAL' => $this->input->post('regional_id'),
                            'ACTIVO' => $this->input->post('estado_id'),
                            'USUARIO_MODIFICACION' => $this->session->userdata('username')
                            
                        );
                        //se usa la funcion edita, ya que esta permite actualizar campos date, similar a la funcion add 
                        if ($this->codegen_model->edita('USUARIOS', $data, $date, 'IDUSUARIO', $this->input->post('id')) == TRUE) {
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El usuario se ha actualizado correctamente.</div>');
                            redirect(base_url() . 'index.php/consultarusuariofiscalizador/');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                            
                        } // 
                    }
                    $this->data['result']       = $this->codegen_model->get('USUARIOS', 'IDUSUARIO,NOMBRES,APELLIDOS,TELEFONO,CELULAR,EMAIL,CORREO_PERSONAL,IDGRUPO,COD_REGIONAL,DIRECCION,ACTIVO,CREADO,MODIFICADO,USUARIO_MODIFICACION,USUARIO_CREACION', 'IDUSUARIO = ' . $this->uri->segment(3), 1, 1, true);
                    //$this->data['sesion'] = $this->session->userdata('username','old_last_login');		
                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen'
                    );
                    $this->data['javascripts']  = array(
                        'js/chosen.jquery.min.js'
                    );
                    
                    $this->data['grupos']   = $this->codegen_model->getSelect('GRUPOS', 'IDGRUPO,NOMBREGRUPO');
                    $this->data['regional'] = $this->codegen_model->getSelect('REGIONAL', 'COD_REGIONAL,NOMBRE_REGIONAL');
                    $this->data['estados']  = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->template->load($this->template_file, 'consultarusuariofiscalizador/consultarusuariofiscalizador_edit', $this->data);
                    
                    
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/consultarusuariofiscalizador');
            }
            
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
        
    }
    
    function datatable()
    {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarusuariofiscalizador/manage')) {
                
                if ($this->ion_auth->is_admin() || $this->session->userdata('regional') == 999) {
                    
                    
                    
                    $this->load->library('datatables');
                    $this->datatables->select('USUARIOS.IDUSUARIO,
															USUARIOS.NOMBRES,
															USUARIOS.APELLIDOS,
															USUARIOS.DIRECCION,
															USUARIOS.TELEFONO,
															USUARIOS.CELULAR,
															USUARIOS.EMAIL,
															USUARIOS.CORREO_PERSONAL,
															R.NOMBRE_REGIONAL,
															USUARIOS.ACTIVO');
                    $this->datatables->from('USUARIOS');
                    $this->datatables->join('REGIONAL R', 'R.COD_REGIONAL = USUARIOS.COD_REGIONAL', ' inner ');
                    //$this->datatables->join('ESTADOS E','E.IDESTADO = USUARIOS.ACTIVO', ' inner ');
                    $this->datatables->where('FISCALIZADOR', 'S');
                    
                    
                    
                    
                    $this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="' . base_url() . 'index.php/consultarusuariofiscalizador/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'USUARIOS.IDUSUARIO');
                } else {
                    
                    if ($this->ion_auth->in_menu('consultarusuariofiscalizador/edit')) {
                        
                        
                        $this->load->library('datatables');
                        $this->datatables->select('USUARIOS.IDUSUARIO,
																USUARIOS.NOMBRES,
																USUARIOS.APELLIDOS,
																USUARIOS.DIRECCION,
																USUARIOS.TELEFONO,
																USUARIOS.CELULAR,
																USUARIOS.EMAIL,
																USUARIOS.CORREO_PERSONAL,
																R.NOMBRE_REGIONAL,
																USUARIOS.ACTIVO');
                        $this->datatables->from('USUARIOS');
                        $this->datatables->join('REGIONAL R', 'R.COD_REGIONAL = USUARIOS.COD_REGIONAL', ' inner ');
                        //$this->datatables->join('ESTADOS E','E.IDESTADO = USUARIOS.ACTIVO', ' inner ');
                        $this->datatables->where('FISCALIZADOR', 'S');
                        $this->datatables->where('USUARIOS.COD_REGIONAL', $this->session->userdata('regional'));
                        
                        
                        $this->datatables->add_column('edit', '<div class="btn-toolbar">
																														 <div class="btn-group">
																																<a href="' . base_url() . 'index.php/consultarusuariofiscalizador/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
																														 </div>
																												 </div>', 'USUARIOS.IDUSUARIO');
                    } else {
                        
                        
                        $this->load->library('datatables');
                        $this->datatables->select('USUARIOS.IDUSUARIO,
																USUARIOS.NOMBRES,
																USUARIOS.APELLIDOS,
																USUARIOS.DIRECCION,
																USUARIOS.TELEFONO,
																USUARIOS.CELULAR,
																USUARIOS.EMAIL,
																USUARIOS.CORREO_PERSONAL,
																R.NOMBRE_REGIONAL,
																USUARIOS.ACTIVO');
                        $this->datatables->from('USUARIOS');
                        $this->datatables->join('REGIONAL R', 'R.COD_REGIONAL = USUARIOS.COD_REGIONAL', ' inner ');
                        //$this->datatables->join('ESTADOS E','E.IDESTADO = USUARIOS.ACTIVO', ' inner ');
                        $this->datatables->where('FISCALIZADOR', 'S');
                        $this->datatables->where('USUARIOS.COD_REGIONAL', $this->session->userdata('regional'));
                        
                        
                        $this->datatables->add_column('edit', '<div class="btn-toolbar">
																														 <div class="btn-group">
																																<a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
																														 </div>
																												 </div>', 'USUARIOS.IDUSUARIO');
                    }
                }
                echo $this->datatables->generate();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/consultarusuariofiscalizador');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
}