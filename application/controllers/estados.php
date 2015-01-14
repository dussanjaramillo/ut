<?php

class Estados extends MY_Controller
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
        
    }
    
    function index()
    {
        $this->manage();
    }
    
    function manage()
    {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estados/manage')) {
                //template data
                $this->template->set('title', 'Tipo Estados');
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts']  = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js'
                );
                $this->data['message']      = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'estados/estados_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
            
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    function add()
    {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estados/add')) {
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $showForm                   = 0;
                $this->form_validation->set_rules('nombreestado', 'Nombre Estado', 'required|trim|xss_clean|max_length[100]');
                $this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('tipoestado', 'Tipo Estado', 'required|numeric|greater_than[0]');
                $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');
                if ($this->form_validation->run() == false) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    
                } else {
                    $date = array(
                        'FECHA_CREACION' => date("d/m/Y")
                    );
                    $data = array(
                        //'IDTIPOESTADO' => $this->codegen_model->getSequence('dual', 'IDTIPOESTADOSEQ.NEXTVAL'),
                        'IDESTADO_P' => $this->input->post('tipoestado'),
                        'NOMBRE_ESTADO' => $this->input->post('nombreestado'),
                        'DESCRIPCION_ESTADO' => $this->input->post('descripcion'),
                        'IDESTADO' => $this->input->post('estado_id')
                    );
                    
                    if ($this->codegen_model->add('ESTADO_PARAMETRIZACION', $data, $date) == TRUE) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Estado se ha creado con éxito.</div>');
                        redirect(base_url() . 'index.php/estados/');
                    } else {
                        $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
                        
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/chosen.css' => 'screen'
                );
                $this->data['javascripts']  = array(
                    'js/chosen.jquery.min.js'
                );
                $this->data['estados']      = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['estadost']     = $this->codegen_model->getSelect('TIPOESTADO', 'IDESTADO_P,TIPOESTADO_P');
                $this->template->load($this->template_file, 'estados/estados_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/estados');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    
    function edit()
    {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estados/edit')) {
                $ID = $this->uri->segment(3);
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/estados');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->form_validation->set_rules('nombreestado', 'Nombre Estado', 'required|trim|xss_clean|max_length[100]');
                    $this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean|max_length[200]');
                    $this->form_validation->set_rules('tipoestado', 'Tipo Estado', 'required|numeric|greater_than[0]');
                    $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');
                    if ($this->form_validation->run() == false) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                        
                    } else {
                        $data = array(
                            'IDESTADO_P' => $this->input->post('tipoestado'),
                            'NOMBRE_ESTADO' => $this->input->post('nombreestado'),
                            'DESCRIPCION_ESTADO' => $this->input->post('descripcion'),
                            'IDESTADO' => $this->input->post('estado_id')
                        );
                        
                        if ($this->codegen_model->edit('ESTADO_PARAMETRIZACION', $data, 'COD_TIPOESTADO', $this->input->post('id')) == TRUE) {
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Estado se ha editado correctamente.</div>');
                            redirect(base_url() . 'index.php/estados/');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                            
                        }
                    }
                    $this->data['result']   = $this->codegen_model->get('ESTADO_PARAMETRIZACION', 'COD_TIPOESTADO,NOMBRE_ESTADO,DESCRIPCION_ESTADO,IDESTADO_P,IDESTADO', 'COD_TIPOESTADO = ' . $this->uri->segment(3), 1, 1, true);
                    $this->data['estados']  = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['estadost'] = $this->codegen_model->getSelect('TIPOESTADO', 'IDESTADO_P,TIPOESTADO_P');
                    
                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen'
                    );
                    $this->data['javascripts']  = array(
                        'js/chosen.jquery.min.js'
                    );
                    
                    $this->template->load($this->template_file, 'estados/estados_edit', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/estados');
            }
            
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
        
    }
    
    function delete()
    {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estados/delete')) {
                $ID = $this->uri->segment(3);
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
                    redirect(base_url() . 'index.php/estados');
                } else {
                    
                    $data = array(
                        'IDESTADO' => '2'
                        
                    );
                    if ($this->codegen_model->edit('ESTADO_PARAMETRIZACION', $data, 'COD_TIPOESTADO', $ID) == TRUE) {
                        //$this->codegen_model->delete('ESTADO_PARAMETRIZACION','COD_TIPOESTADO',$ID);             
                        $this->template->set('title', 'Estados');
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Estado se eliminó correctamente.' . $ID . '</div>');
                        redirect(base_url() . 'index.php/estados/');
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/estados');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    function datatable()
    {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estados/manage')) {
                
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estados/edit')) {
                    $this->load->library('datatables');
                    $this->datatables->select('ESTADO_PARAMETRIZACION.COD_TIPOESTADO,
                    							T.TIPOESTADO_P,
                    							ESTADO_PARAMETRIZACION.NOMBRE_ESTADO,
                    							ESTADO_PARAMETRIZACION.DESCRIPCION_ESTADO,
                    							ESTADO_PARAMETRIZACION.FECHA_CREACION,
                    							E.NOMBREESTADO');
                    $this->datatables->from('ESTADO_PARAMETRIZACION');
                    $this->datatables->join('TIPOESTADO T', 'T.IDESTADO_P = ESTADO_PARAMETRIZACION.IDESTADO_P', ' inner ');
                    $this->datatables->join('ESTADOS E', 'E.IDESTADO = ESTADO_PARAMETRIZACION.IDESTADO', ' inner ');
                    $this->datatables->where('ESTADO_PARAMETRIZACION.IDESTADO <> 2');
                    $this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="' . base_url() . 'index.php/estados/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'ESTADO_PARAMETRIZACION.COD_TIPOESTADO');
                } else {
                    $this->load->library('datatables');
                    $this->datatables->select('ESTADO_PARAMETRIZACION.COD_TIPOESTADO,
                    							T.TIPOESTADO_P,
                    							ESTADO_PARAMETRIZACION.NOMBRE_ESTADO,
                    							ESTADO_PARAMETRIZACION.DESCRIPCION_ESTADO,
                    							ESTADO_PARAMETRIZACION.FECHA_CREACION,
                    							E.NOMBREESTADO');
                    $this->datatables->from('ESTADO_PARAMETRIZACION');
                    $this->datatables->join('TIPOESTADO T', 'T.IDESTADO_P = ESTADO_PARAMETRIZACION.IDESTADO_P', ' inner ');
                    $this->datatables->join('ESTADOS E', 'E.IDESTADO = ESTADO_PARAMETRIZACION.IDESTADO', ' inner ');
                    $this->datatables->where('ESTADO_PARAMETRIZACION.IDESTADO <> 2');
                    $this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'ESTADO_PARAMETRIZACION.COD_TIPOESTADO');
                }
                echo $this->datatables->generate();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/estados');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
}


/* End of file estados.php */
/* Location: ./system/application/controllers/estados.php */