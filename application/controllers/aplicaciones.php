<?php

class Aplicaciones extends MY_Controller {
    
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
            $this->template->set('title', 'Aplicaciones');
            $this->data['style_sheets']= array(
                        'css/jquery.dataTables_themeroller.css' => 'screen'
                    );
            $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/jquery.dataTables.defaults.js'
                    );
            $this->data['message']=$this->session->flashdata('message');
            $this->template->load($this->template_file, 'aplicaciones/aplicaciones_list',$this->data); 
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
    		$this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[30]');               
            $this->form_validation->set_rules('macroproceso_id', 'Macroproceso',  'required|numeric|greater_than[0]');
    		$this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]'); 
            $this->form_validation->set_rules('icono', 'Ícono',  'required|trim|xss_clean|max_length[128]');   
            if ($this->form_validation->run() == false)
            {
                 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

            } else
            {    
                
                $data = array(
                        'NOMBREAPLICACION' => set_value('nombre'),
    					'CODPROCESO' => set_value('macroproceso_id'),
                        'IDESTADO' => set_value('estado_id'),
                        'ICONOAPLICACION' => set_value('icono')
                );
                 
    			if ($this->codegen_model->add('APLICACIONES',$data) == TRUE)
    			{
    			  $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La aplicación se ha creado con éxito.</div>');
                  redirect(base_url().'index.php/aplicaciones/');
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
                $this->data['macroprocesos']  = $this->codegen_model->getSelect(' MACROPROCESO','CODMACROPROCESO,NOMBREMACROPROCESO');

                $this->template->load($this->template_file, 'aplicaciones/aplicaciones_add', $this->data);
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
    		$this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[30]');               
            $this->form_validation->set_rules('macroproceso_id', 'Macroproceso',  'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]');  
            $this->form_validation->set_rules('icono', 'Ícono',  'required|trim|xss_clean|max_length[128]');
            if ($this->form_validation->run() == false)
            {
                 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
                
            } else
            {                            
                $data = array(
                        'NOMBREAPLICACION' => $this->input->post('nombre'),
    					'CODPROCESO' => $this->input->post('macroproceso_id'),
                        'IDESTADO' => $this->input->post('estado_id'),
                        'ICONOAPLICACION' => $this->input->post('icono')
                );
               
    			if ($this->codegen_model->edit('APLICACIONES',$data,'IDAPLICACION',$this->input->post('id')) == TRUE)
    			{
    				$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La aplicación se ha editado correctamente.</div>');
                    redirect(base_url().'index.php/aplicaciones/');
    			}
    			else
    			{
    				$this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';

    			}
    		}
    		    $this->data['result'] = $this->codegen_model->get('APLICACIONES','IDAPLICACION,NOMBREAPLICACION,CODPROCESO,IDESTADO,ICONOAPLICACION','IDAPLICACION = '.$this->uri->segment(3),1,1,true);
    		    $this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');
                $this->data['iconos']  = $this->codegen_model->getSelect(' ICONO','IDICONO,NOMBREICONO,TRADUCCION');
                $this->data['macroprocesos']  = $this->codegen_model->getSelect(' MACROPROCESO','CODMACROPROCESO,NOMBREMACROPROCESO');
                //add style an js files for inputs selects
                $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                    );
                $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                    );

                $this->template->load($this->template_file, 'aplicaciones/aplicaciones_edit', $this->data); 
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
            $this->codegen_model->delete('APLICACIONES','IDAPLICACION',$ID);             
            $this->template->set('title', 'Aplicaciones');
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La aplicación se eliminó correctamente.</div>');
            redirect(base_url().'index.php/aplicaciones/');
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
            $this->datatables->select('APLICACIONES.IDAPLICACION,APLICACIONES.NOMBREAPLICACION,M.NOMBREMACROPROCESO,E.NOMBREESTADO');
            $this->datatables->from('APLICACIONES');
            $this->datatables->join('ESTADOS E','E.IDESTADO = APLICACIONES.IDESTADO', ' left ');
            $this->datatables->join('MACROPROCESO M','M.CODMACROPROCESO = APLICACIONES.CODPROCESO', ' left ');
            $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                       <div class="btn-group">
                                                          <a href="'.base_url().'index.php/aplicaciones/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
                                                       </div>
                                                   </div>', 'APLICACIONES.IDAPLICACION');
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