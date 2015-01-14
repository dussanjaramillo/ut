<?php

class Prueba extends MY_Controller {
    
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
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('prueba/manage'))
               {
            //template data
            $this->template->set('title', 'Administrar Prueba');
            $this->data['style_sheets']= array(
                        'css/jquery.dataTables_themeroller.css' => 'screen'
                    );
            $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/jquery.dataTables.defaults.js'
                    );
            $this->data['message']=$this->session->flashdata('message');
            $this->template->load($this->template_file, 'prueba/prueba_list',$this->data); 
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
	 
    // IDBANCO, NOMBREBANCO, IDESTADO, FECHACREACION  
    function add(){        
        if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('prueba/add'))
               {
            $this->load->library('form_validation');    
        		$this->data['custom_error'] = '';
        		$this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');   
            //$this->form_validation->set_rules('descripcion', 'Descripcion', 'required|trim|xss_clean|max_length[128]'); 
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
                        
                                'IDBANCO'     => $this->input->post('idBanco'),
                                'NOMBREBANCO' => $this->input->post('nombre'),
                                'IDESTADO'    => $this->input->post('estado_id')
                );
                 
    			if ($this->codegen_model->add('BANCO',$data, $date) == TRUE)
    			{
    			  $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El cargo se ha creado con éxito.</div>');
                  redirect(base_url().'index.php/prueba/');
    			}
    			else
    			{
    				$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

    			}
    		}
                $this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');
                $this->template->set('title', 'Nuevo cargo');
                $this->template->load($this->template_file, 'prueba/prueba_add', $this->data);
            }
           else {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                          redirect(base_url().'index.php/prueba');
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
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('prueba/edit'))
               {    
                $ID =  $this->uri->segment(3);
                    if ($ID==""){
                      $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                          redirect(base_url().'index.php/prueba');
                    }else{
                              $this->load->library('form_validation');  
                          $this->data['custom_error'] = '';
                          $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');  
                              $this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]');  
                              if ($this->form_validation->run() == false)
                              {
                                   $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
                                  
                              } else
                              {                            
                                  $data = array(
                                          'NOMBREBANCO' => $this->input->post('nombre'),
                                          'IDESTADO' => $this->input->post('estado_id')
                                  );
                                 
                            if ($this->codegen_model->edit('BANCO',$data,'IDBANCO',$this->input->post('id')) == TRUE)
                            {
                              $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El banco se ha editado correctamente.</div>');
                                      redirect(base_url().'index.php/prueba/');
                            }
                            else
                            {
                              $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';

                            }
                          }
                              $this->data['result'] = $this->codegen_model->get('BANCO','IDBANCO, NOMBREBANCO, IDESTADO','IDBANCO = '.$this->uri->segment(3),1,1,true);
                              $this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO, NOMBREESTADO');
                                  
                                  //add style an js files for inputs selects
                                  $this->data['style_sheets']= array(
                                          'css/chosen.css' => 'screen'
                                      );
                                  $this->data['javascripts']= array(
                                          'js/chosen.jquery.min.js'
                                      );

                                  $this->template->load($this->template_file, 'prueba/prueba_edit', $this->data); 
                      }
                }else {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                          redirect(base_url().'index.php/prueba');
                   }
                
            }else
                {
                redirect(base_url().'index.php/auth/login');
                }
        
    }
	
    function delete(){
         if ($this->ion_auth->is_admin())
           {
            $ID =  $this->uri->segment(3);
            $this->codegen_model->delete('BANCO','IDBANCO',$ID);             
            $this->template->set('title', 'prueba');
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El usuario se eliminó correctamente.</div>');
            redirect(base_url().'index.php/prueba/');
          }
         else
            {
              redirect(base_url().'index.php/auth/login');
            }
    }
 
    // IDBANCO, NOMBREBANCO, IDESTADO, FECHACREACION
     function datatable (){
        if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('prueba/manage'))
               { 
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('prueba/edit'))
               { 
            $this->load->library('datatables');
            $this->datatables->select('BANCO.IDBANCO, BANCO.NOMBREBANCO, E.NOMBREESTADO, BANCO.FECHACREACION');
                $this->datatables->from('BANCO');
                $this->datatables->join('ESTADOS E','E.IDESTADO = BANCO.IDESTADO', ' left ');
                $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/prueba/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
                                                           </div>
                                                       </div>', 'BANCO.IDBANCO');
            }else{
                $this->load->library('datatables');
            $this->datatables->select('BANCO.IDBANCO, BANCO.NOMBREBANCO, BANCO.IDESTADO, BANCO.FECHACREACION');
                $this->datatables->from('BANCO');
                $this->datatables->join('ESTADOS E','E.IDESTADO = BANCO.IDESTADO', ' left ');
                $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
                                                           </div>
                                                       </div>', 'BANCO.IDBANCO');
            }
            echo $this->datatables->generate();
            }else {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                          redirect(base_url().'index.php/prueba');
                   }
               } 
           else
            {
              redirect(base_url().'index.php/auth/login');
            }           
    }
}

/* End of file cargos.php */
/* Location: ./system/application/controllers/prueba.php */