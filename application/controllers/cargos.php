<?php

class Cargos extends MY_Controller {
    
    function __construct() {
        parent::__construct();
		$this->load->library('form_validation');		
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('codegen_model','',TRUE);

	}	
	
	function index(){
		$this->manage();
	}

	function manage(){
        if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargos/manage'))
               {
            //template data
            $this->template->set('title', 'Administrar cargos');
            $this->data['style_sheets']= array(
                        'css/jquery.dataTables_themeroller.css' => 'screen'
                    );
            $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/jquery.dataTables.defaults.js'
                    );
            $this->data['message']=$this->session->flashdata('message');
            $this->template->load($this->template_file, 'cargos/cargos_list',$this->data); 
           }
           else {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                          redirect(base_url().'index.php/inicio');
                   }
               } 
           else
            {
              redirect(base_url().'index.php/auth/login');
            }
    }
	
    function add(){        
        if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargos/add'))
               {
            $this->load->library('form_validation');    
        		$this->data['custom_error'] = '';
        		$this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');   
            $this->form_validation->set_rules('descripcion', 'Descripcion', 'required|trim|xss_clean|max_length[128]'); 
            $this->form_validation->set_rules('fechacreacion', 'Fechacreacion', 'required'); 
            $this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]');  
            if ($this->form_validation->run() == false)
            {
                 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

            } else
            {    
                $date = array( 
                                'FECHACREACION' => $this->input->post('fechacreacion')
                        );
                $data = array(
                        
                                'NOMBRECARGO' => $this->input->post('nombre'),
                                'DESCRIPCIONCARGO' => set_value('descripcion'),
                                'IDESTADO' => $this->input->post('estado_id')
                );
                 
    			if ($this->codegen_model->add('CARGOS',$data, $date) == TRUE)
    			{
    			  $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El cargo se ha creado con éxito.</div>');
                  redirect(base_url().'index.php/cargos/');
    			}
    			else
    			{
    				$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

    			}
    		}
                $this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');
                $this->template->set('title', 'Nuevo cargo');
                $this->template->load($this->template_file, 'cargos/cargos_add', $this->data);
            }
           else {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                          redirect(base_url().'index.php/cargos');
                   }
               } 
           else
            {
              redirect(base_url().'index.php/auth/login');
            }
    }	


	function edit(){    
        if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargos/edit'))
               {  
                $ID =  $this->uri->segment(3);
                    if ($ID==""){
                      $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                          redirect(base_url().'index.php/cargos');
                    }else{
                        $this->load->library('form_validation');  
                		$this->data['custom_error'] = '';
                		$this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]'); 
                        $this->form_validation->set_rules('descripcion', 'Descripcion', 'required|trim|xss_clean|max_length[128]'); 
                        $this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]');  
                        if ($this->form_validation->run() == false)
                        {
                             $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
                            
                        } else
                        {                            
                            $data = array(
                                    'NOMBRECARGO' => $this->input->post('nombre'),
                                    'DESCRIPCIONCARGO' => $this->input->post('descripcion'),
                                    'IDESTADO' => $this->input->post('estado_id')

                            );
                           
                			if ($this->codegen_model->edit('CARGOS',$data,'IDCARGO',$this->input->post('id')) == TRUE)
                			{
                				$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El cargo se ha editado correctamente.</div>');
                                redirect(base_url().'index.php/cargos/');
                			}
                			else
                			{
                				$this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';

                			}
                		}
                		    $this->data['result'] = $this->codegen_model->get('CARGOS','IDCARGO,NOMBRECARGO,DESCRIPCIONCARGO,IDESTADO','IDCARGO = '.$this->uri->segment(3),1,1,true);
                            $this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');
                            $this->template->set('title', 'Editar cargo');
                            $this->template->load($this->template_file, 'cargos/cargos_edit', $this->data); 
                        }
           }else {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                          redirect(base_url().'index.php/cargos');
                   }
               } 
           else
            {
              redirect(base_url().'index.php/auth/login');
            }
        
    }
	
    function delete(){
         if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargos/delete'))
               {  
            $ID =  $this->uri->segment(3);
            if ($ID==""){
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
                redirect(base_url().'index.php/cargos');
            }else{
               $data = array(
                                            'IDESTADO' => '2'

                                      );
                          if($this->codegen_model->edit('CARGOS',$data,'IDCARGO',$ID) == TRUE){
                            //$this->codegen_model->delete('CARGOS','IDCARGO',$ID);  
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El cargo se eliminó correctamente.</div>');
                            redirect(base_url().'index.php/cargos/');
                          }
        }
          }
           else {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                          redirect(base_url().'index.php/cargos');
                   }
               } 
           else
            {
              redirect(base_url().'index.php/auth/login');
            }
    }
 
     function datatable (){
        if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargos/manage'))
               { 
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargos/edit'))
               { 
            $this->load->library('datatables');
            $this->datatables->select('CARGOS.IDCARGO,CARGOS.NOMBRECARGO,CARGOS.DESCRIPCIONCARGO,CARGOS.FECHACREACION,E.NOMBREESTADO');
                $this->datatables->from('CARGOS');
                $this->datatables->join('ESTADOS E','E.IDESTADO = CARGOS.IDESTADO', ' left ');
                $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/cargos/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
                                                           </div>
                                                       </div>', 'CARGOS.IDCARGO');
            }else{
                $this->load->library('datatables');
            $this->datatables->select('CARGOS.IDCARGO,CARGOS.NOMBRECARGO,CARGOS.DESCRIPCIONCARGO,CARGOS.FECHACREACION,E.NOMBREESTADO');
                $this->datatables->from('CARGOS');
                $this->datatables->join('ESTADOS E','E.IDESTADO = CARGOS.IDESTADO', ' left ');
                $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
                                                           </div>
                                                       </div>', 'CARGOS.IDCARGO');
            }
            echo $this->datatables->generate();
            }else {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                          redirect(base_url().'index.php/cargos');
                   }
               } 
           else
            {
              redirect(base_url().'index.php/auth/login');
            }           
    }
}

/* End of file cargos.php */
/* Location: ./system/application/controllers/cargos.php */