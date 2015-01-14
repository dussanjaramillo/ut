<?php

class Gestion extends MY_Controller
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
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('gestion/manage')) {
                //template data
                $this->template->set('title', 'Gestión');
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts']  = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js'
                );
                $this->data['message']      = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'gestion/gestion_list', $this->data);
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
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('gestion/add')) {
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $showForm                   = 0;
                $this->form_validation->set_rules('tipogestion', 'Tipo Gestión', 'required|trim|xss_clean|max_length[100]');
                $this->form_validation->set_rules('t_proceso', 'Tipo Proceso', 'required|numeric|greater_than[0]');
                if ($this->form_validation->run() == false) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    
                } else {
                    $date = array(
                        'FECHA_CREACION' => date("d/m/Y")
                    );
                    $data = array(
                        //'COD_GESTION' => $this->codegen_model->getSequence('dual', 'COD_GESTIONSEQ.NEXTVAL'),
                        'TIPOGESTION' => $this->input->post('tipogestion'),
                        //'DESCRIPCION' => $this->input->post('descripcion'),
                        'CODPROCESO' => $this->input->post('t_proceso')
                    );
                    
                    if ($this->codegen_model->add('TIPOGESTION', $data, $date) == TRUE) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La gestión se agregó con éxito.</div>');
                        redirect(base_url() . 'index.php/gestion/');
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
                $this->data['procesos'] = $this->codegen_model->getSelect('TIPOPROCESO', 'COD_TIPO_PROCESO,TIPO_PROCESO');
                    
                $this->template->load($this->template_file, 'gestion/gestion_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/gestion');
            }
            
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    
    function edit()
    {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('gestion/edit')) {
                
                $ID = $this->uri->segment(3);
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/gestion');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->form_validation->set_rules('tipogestion', 'Tipo Gestión', 'required|trim|xss_clean|max_length[100]');
                    $this->form_validation->set_rules('t_proceso', 'Tipo Proceso', 'required|numeric|greater_than[0]');
                    if ($this->form_validation->run() == false) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                        
                    } else {
                        $data = array(
                            //'COD_GESTION' => $this->input->post('tipogestion'),
                            'TIPOGESTION' => $this->input->post('tipogestion'),
                            'CODPROCESO' => $this->input->post('t_proceso')
                        );
                        
                        if ($this->codegen_model->edit('TIPOGESTION', $data, 'COD_GESTION', $this->input->post('id')) == TRUE) {
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La gestión se ha editado correctamente.</div>');
                            redirect(base_url() . 'index.php/gestion/');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                            
                        }
                    }
                    $this->data['result']  = $this->codegen_model->get('TIPOGESTION', 'COD_GESTION,TIPOGESTION,CODPROCESO', 'COD_GESTION = ' . $this->uri->segment(3), 1, 1, true);
                    $this->data['estados'] = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['procesos'] = $this->codegen_model->getSelect('TIPOPROCESO', 'COD_TIPO_PROCESO,TIPO_PROCESO');
                    
                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen'
                    );
                    $this->data['javascripts']  = array(
                        'js/chosen.jquery.min.js'
                    );
                    
                    $this->template->load($this->template_file, 'gestion/gestion_edit', $this->data);
                    
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/gestion');
            }
            
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
        
    }
    
    function delete()
    {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('gestion/delete')) {
                $ID = $this->uri->segment(3);
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
                    redirect(base_url() . 'index.php/gestion');
                } else {
                    $data = array(
                        'IDESTADO' => '2'
                        
                    );
                    //if ($this->codegen_model->edit('TIPOGESTION', $data, 'COD_GESTION', $ID) == TRUE) {
                    if ($this->codegen_model->delete('TIPOGESTION','TIPOGESTION',$ID) == TRUE) {             
                        $this->template->set('title', 'Gestión');
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha eliminado la gestión correctamente.</div>');
                        redirect(base_url() . 'index.php/gestion/');
                    }
                    
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/gestion');
            }
            
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    function datatable()
    {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('gestion/manage')) {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('gestion/edit')) {
                    $this->load->library('datatables');
                    $this->datatables->select('TIPOGESTION.COD_GESTION,
                                                TIPOGESTION.TIPOGESTION,
                                                T.TIPO_PROCESO,
                                                TIPOGESTION.FECHA_CREACION');
                    $this->datatables->from('TIPOGESTION');
                    $this->datatables->join('TIPOPROCESO T', 'T.COD_TIPO_PROCESO = TIPOGESTION.CODPROCESO', 'inner');
                    $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="' . base_url() . 'index.php/gestion/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
                                                           </div>
                                                       </div>', 'TIPOGESTION.COD_GESTION');
                } else {
                    $this->load->library('datatables');
                    $this->datatables->select('TIPOGESTION.COD_GESTION,
                                                TIPOGESTION.TIPOGESTION,
                                                T.TIPO_PROCESO,
                                                TIPOGESTION.FECHA_CREACION');
                    $this->datatables->from('TIPOGESTION');
                    $this->datatables->join('TIPOPROCESO T', 'T.COD_TIPO_PROCESO = TIPOGESTION.CODPROCESO', 'inner');
                    $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
                                                           </div>
                                                       </div>', 'TIPOGESTION.COD_GESTION');
                }
                echo $this->datatables->generate();
                
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/gestion');
            }
            
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
}


/* End of file gestion.php */
/* Location: ./system/application/controllers/gestion.php */