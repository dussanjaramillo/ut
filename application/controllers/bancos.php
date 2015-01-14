<?php

class Bancos extends MY_Controller
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
        $this->load->model('codegen_model', '', TRUE); //siempre usar este método salvo casos muy espeíficos
        //recomendaciones : tener siempre a la mano documentación de: bootstrap 2.3, jquery UI, codeigniter, datatables
    }
    
    function index()
    {
        $this->manage(); //dejar libre el index por si cambia la página de entrada al módulo
    }
    
    function manage() //sugerencia  tratar de mantener el nombrado similar de los métodos 
    {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bancos/manage')) //importante! controlador/método
                {
                //template data
                $this->template->set('title', 'Bancos');
                //si se necesitan estilos, js, o contenido adicional en las cabecera o en el pie de página, siempre que se requiera de js o estilos css
                //la primera opción SIEMPRE! debe ser bootstrap 2.3 y jquery UI (ya incluidos en la plantilla principal), si no se encuentra solución con esos mirar si ya existe en el sistema
                //alguna que se adapte, como última opción buscar heramientas open por internet o usar las propias
                //esto con el fin de no llenar de plugis y homogeneizar el sistema.
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts']  = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js'
                ); //datatables es un plugin bastante poderoso y extendible, SIEMPRE! buscar las soluciones pensadas en la tabla, la recomendación es siempre ver la documentación y lso foros disponibles en internet
                $this->data['message']      = $this->session->flashdata('message'); //setea mensajes da mostrar luego de la redirección
                $this->template->load($this->template_file, 'bancos/bancos_list', $this->data);
            } else { //usar siempre bootstrap 2.3 para mensajes, formulario y estilos en general, como alternativa que también se puede usar está jquery ui
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
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bancos/add')) {
                $this->load->library('form_validation'); //por seguridad SIEMPRE! realizar validaciones del lado del servidor para todos los campos en los formularios y siempre usar validaciones del core de codeigniter
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('idBanco', 'IdBanco', 'required|trim|xss_clean|'); //realizar SIEMPRE! validaciones |trim|xss_clean| a los campos de texto
                $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[30]');
                $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');
                if ($this->form_validation->run() == false) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    
                } else {
                    $date = array(
                        'FECHACREACION' => date("d/m/Y")
                    );
                    $data = array(
                        'IDBANCO' => set_value('idBanco'),
                        'NOMBREBANCO' => set_value('nombre'),
                        'IDESTADO' => set_value('estado_id')
                    );
                    
                    if ($this->codegen_model->add('BANCO', $data, $date) == TRUE) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El banco se ha creado con éxito.</div>');
                        redirect(base_url() . 'index.php/bancos/');
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
                $this->template->load($this->template_file, 'bancos/bancos_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bancos');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    
    function edit()
    {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bancos/edit')) {
                $ID = $this->uri->segment(3);
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/bancos');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[30]');
                    $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');
                    if ($this->form_validation->run() == false) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                        
                    } else {
                        $data = array(
                            'NOMBREBANCO' => $this->input->post('nombre'),
                            'IDESTADO' => $this->input->post('estado_id')
                        );
                        
                        if ($this->codegen_model->edit('BANCO', $data, 'IDBANCO', $this->input->post('id')) == TRUE) {
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El banco se ha editado correctamente.</div>');
                            redirect(base_url() . 'index.php/bancos/');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                            
                        }
                    }
                    $this->data['result']  = $this->codegen_model->get('BANCO', 'IDBANCO,NOMBREBANCO,IDESTADO', 'IDBANCO = ' . $this->uri->segment(3), 1, 1, true);
                    $this->data['estados'] = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    
                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen'
                    );
                    $this->data['javascripts']  = array(
                        'js/chosen.jquery.min.js'
                    );
                    
                    $this->template->load($this->template_file, 'bancos/bancos_edit', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bancos');
            }
            
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
        
    }
    
    function delete()
    {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bancos/delete')) {
                $ID = $this->uri->segment(3);
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
                    redirect(base_url() . 'index.php/bancos');
                } else {
                    $data = array(
                        'IDESTADO' => '2'
                        
                    );
                    if ($this->codegen_model->edit('BANCO', $data, 'IDBANCO', $ID) == TRUE) {
                        //$this->codegen_model->delete('BANCO','IDBANCO',$ID);
                        $this->template->set('title', 'Bancos');
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El banco se eliminó correctamente.' . $ID . '</div>');
                        redirect(base_url() . 'index.php/bancos/');
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bancos');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    function datatable()
    {
        if ($this->ion_auth->logged_in()) { //nunca dejar métodos sin seguridad
            
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bancos/manage')) { //importante  permisos de donse va a imprimir la tabla
                
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bancos/edit')) { //importante esta tabla puede tener botones o enlaces donde se debe verificar si tiene permiso para verlos y acceder a ellospermisos de donse va a imprimir la tabla
                    
                    $this->load->library('datatables');
                    $this->datatables->select('BANCO.IDBANCO,BANCO.NOMBREBANCO,BANCO.FECHACREACION,E.NOMBREESTADO');
                    $this->datatables->from('BANCO');
                    $this->datatables->join('ESTADOS E', 'E.IDESTADO = BANCO.IDESTADO', ' left ');
                    $this->datatables->add_column('edit', '<div class="btn-toolbar">
				<div class="btn-group">
				<a href="' . base_url() . 'index.php/bancos/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
				</div>
				</div>', 'BANCO.IDBANCO');
                } else {
                    
                    $this->load->library('datatables'); //consulta sin restricciones para el administrador
                    $this->datatables->select('BANCO.IDBANCO,BANCO.NOMBREBANCO,BANCO.FECHACREACION,E.NOMBREESTADO');
                    $this->datatables->from('BANCO');
                    $this->datatables->join('ESTADOS E', 'E.IDESTADO = BANCO.IDESTADO', ' left ');
                    $this->datatables->add_column('edit', '<div class="btn-toolbar">
				<div class="btn-group">
				<a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
				</div>
				</div>', 'BANCO.IDBANCO');
                }
                echo $this->datatables->generate();
            } else {
                
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bancos');
            }
        } else {
            
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
}


/* End of file bancos.php */
/* Location: ./system/application/controllers/bancos.php */