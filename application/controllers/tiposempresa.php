<?php

class Tiposempresa extends MY_Controller {
    
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
        if ($this->ion_auth->is_admin())
           {
            //template data
            $this->template->set('title', 'Administrar tiposempresa');
            $this->data['style_sheets']= array(
                        'css/jquery.dataTables_themeroller.css' => 'screen'
                    );
            $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/jquery.dataTables.defaults.js'
                    );
            $this->data['message']=$this->session->flashdata('message');
            $this->template->load($this->template_file, 'tiposempresa/tiposempresa_list',$this->data); 
           } 
           else
            {
              redirect(base_url().'index.php/auth/login');
            }
    }
	
    function add(){        
        if ($this->ion_auth->is_admin())
           {
            $this->load->library('form_validation');    
    		$this->data['custom_error'] = '';
    		$this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');   
            if ($this->form_validation->run() == false)
            {
                 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

            } else
            {    
                
                $data = array(
                        'NOM_TIPO_EMP' => set_value('nombre')
                );
                 
    			if ($this->codegen_model->add('TIPOEMPRESA',$data) == TRUE)
    			{
    			  $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El cargo se ha creado con éxito.</div>');
                  redirect(base_url().'index.php/tiposempresa/');
    			}
    			else
    			{
    				$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

    			}
    		}
                
                $this->template->set('title', 'Nuevo tipo de empresa');
                $this->template->load($this->template_file, 'tiposempresa/tiposempresa_add', $this->data);
            }
            else
            {
              redirect(base_url().'index.php/auth/login');
            }
    }	


	function edit(){    
        if ($this->ion_auth->is_admin())
           {    
            $this->load->library('form_validation');  
    		$this->data['custom_error'] = '';
    		$this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]'); 
            if ($this->form_validation->run() == false)
            {
                 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
                
            } else
            {                            
                $data = array(
                        'NOM_TIPO_EMP' => $this->input->post('nombre')
                );
               
    			if ($this->codegen_model->edit('TIPOEMPRESA',$data,'CODTIPOEMPRESA',$this->input->post('id')) == TRUE)
    			{
    				$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El cargo se ha editado correctamente.</div>');
                    redirect(base_url().'index.php/tiposempresa/');
    			}
    			else
    			{
    				$this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';

    			}
    		}
    		    $this->data['result'] = $this->codegen_model->get('TIPOEMPRESA','CODTIPOEMPRESA,NOM_TIPO_EMP','CODTIPOEMPRESA = '.$this->uri->segment(3),1,1,true);
                $this->template->set('title', 'Editar cargo');
                $this->template->load($this->template_file, 'tiposempresa/tiposempresa_edit', $this->data); 
            }
            else
            {
              redirect(base_url().'index.php/auth/login');
            }
        
    }
	
    function delete(){
         if ($this->ion_auth->is_admin())
           {
            $ID =  $this->uri->segment(3);
            $this->codegen_model->delete('TIPOEMPRESA','CODTIPOEMPRESA',$ID);  
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El cargo se eliminó correctamente.</div>');
            redirect(base_url().'index.php/tiposempresa/');
          }
          else
            {
              redirect(base_url().'index.php/auth/login');
            }
    }
 
     function datatable (){
        if ($this->ion_auth->is_admin())
           {
            $this->load->library('datatables');
            $this->datatables->select('TIPOEMPRESA.CODTIPOEMPRESA,TIPOEMPRESA.NOM_TIPO_EMP');
            $this->datatables->from('TIPOEMPRESA');
            $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                       <div class="btn-group">
                                                          <a href="'.base_url().'index.php/tiposempresa/edit/$1" class="btn btn-small" title="Editar"><i class="fa fa-pencil-square-o"></i></a>
                                                       </div>
                                                   </div>', 'TIPOEMPRESA.CODTIPOEMPRESA');
            echo $this->datatables->generate();
            }
            else
            {
              redirect(base_url().'index.php/auth/login');
            }           
    }
}

/* End of file categorias.php */
/* Location: ./system/application/controllers/categorias.php */