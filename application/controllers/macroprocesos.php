<?php

class Macroprocesos extends MY_Controller {
    
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
            $this->template->set('title', 'Macroprocesos');
            $this->data['style_sheets']= array(
                        'css/jquery.dataTables_themeroller.css' => 'screen'
                    );
            $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/jquery.dataTables.defaults.js'
                    );
            $this->data['message']=$this->session->flashdata('message');
            $this->template->load($this->template_file, 'macroprocesos/macroprocesos_list',$this->data); 
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
            $showForm=0;
    		$this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
    		$this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]'); 
            $this->form_validation->set_rules('icono', 'Ícono',  'required|trim|xss_clean|max_length[128]');   
            if ($this->form_validation->run() == false)
            {
                 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

            } else
            {    
                
                $data = array(
                        'NOMBREMACROPROCESO' => set_value('nombre'),
                        'IDESTADO' => set_value('estado_id'),
                        'ICONO' => set_value('icono')
                );
                 
    			if ($this->codegen_model->add('MACROPROCESO',$data) == TRUE)
    			{
    			  $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El macroproceso se ha creado con éxito.</div>');
                  redirect(base_url().'index.php/macroprocesos/');
    			}
    			else
    			{
    				$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

    			}
    		}
                 //add style an js files for inputs selects
                $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                    );
                $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                    );
                $this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');
                $this->data['iconos']  = $this->codegen_model->getSelect(' ICONO','IDICONO,NOMBREICONO,TRADUCCION');

                $this->template->load($this->template_file, 'macroprocesos/macroprocesos_add', $this->data);
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
            $this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]');  
            $this->form_validation->set_rules('icono', 'Ícono',  'required|trim|xss_clean|max_length[128]');
            if ($this->form_validation->run() == false)
            {
                 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
                
            } else
            {                            
                $data = array(
                        'NOMBREMACROPROCESO' => $this->input->post('nombre'),
                        'IDESTADO' => $this->input->post('estado_id'),
                        'ICONO' => $this->input->post('icono')
                );
               
    			if ($this->codegen_model->edit('MACROPROCESO',$data,'CODMACROPROCESO',$this->input->post('id')) == TRUE)
    			{
    				$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El macroproceso se ha editado correctamente.</div>');
                    redirect(base_url().'index.php/macroprocesos/');
    			}
    			else
    			{
    				$this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';

    			}
    		}
    		    $this->data['result'] = $this->codegen_model->get('MACROPROCESO','CODMACROPROCESO,NOMBREMACROPROCESO,IDESTADO,ICONO','CODMACROPROCESO = '.$this->uri->segment(3),1,1,true);
    		    $this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');
                $this->data['iconos']  = $this->codegen_model->getSelect(' ICONO','IDICONO,NOMBREICONO,TRADUCCION');
                //add style an js files for inputs selects
                $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                    );
                $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                    );

                $this->template->load($this->template_file, 'macroprocesos/macroprocesos_edit', $this->data); 
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
            $this->codegen_model->delete('MACROPROCESO','CODMACROPROCESO',$ID);             
            $this->template->set('title', 'Macroprocesos');
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El macroproceso se eliminó correctamente.</div>');
            redirect(base_url().'index.php/macroprocesos/');
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
            $this->datatables->select('MACROPROCESO.CODMACROPROCESO,MACROPROCESO.NOMBREMACROPROCESO,E.NOMBREESTADO');
            $this->datatables->from('MACROPROCESO');
            $this->datatables->join('ESTADOS E','E.IDESTADO = MACROPROCESO.IDESTADO', ' left ');
            $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                       <div class="btn-group">
                                                          <a href="'.base_url().'index.php/macroprocesos/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
                                                       </div>
                                                   </div>', 'MACROPROCESO.CODMACROPROCESO');
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