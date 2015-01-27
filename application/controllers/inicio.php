<?php

class Inicio extends MY_Controller {
    
    function __construct() {
        parent::__construct();
		$this->load->library('form_validation');		
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('codegen_model','',TRUE);

	}	
	

    function index()
           {
        if ($this->ion_auth->logged_in()) {
            //template data
            $this->template->set('title', 'Página de bienvenida');
            $this->data['user']= $this->ion_auth->user()->row();
            $this->data['message']='<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Mensaje de bienvenida.</div>';
           	$this->template->load($this->template_file, 'inicio/inicio',$this->data); 
        } else {
              redirect(base_url().'index.php/auth/login');
            }
    }
        
    function register()
    {
                $this->load->library('form_validation');    
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('nombre', 'Alias / Apodo', 'required|trim|xss_clean|max_length[128]');               
                $this->form_validation->set_rules('email', 'Email',  'required|trim|xss_clean|max_length[128]');
                $this->form_validation->set_rules('cedula', 'Cédula o Nit',  'required|trim|xss_clean|max_length[128]|numeric');
                $this->form_validation->set_rules('nombres', 'Nombres',  'required|trim|xss_clean|max_length[128]');
                $this->form_validation->set_rules('apellidos', 'Apellidos',  'required|trim|xss_clean|max_length[128]');
                $this->form_validation->set_rules('cargo_id', 'Cargo',  'required|numeric|greater_than[0]'); 
                $this->form_validation->set_rules('password','Contraseña', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[repassword]');
                $this->form_validation->set_rules('repassword','Confirmar contraseña', 'required|trim|xss_clean|max_length[128]');
           
               if ($this->ion_auth->username_check(strtolower($this->input->post('nombre'))) || $this->ion_auth->email_check(strtolower($this->input->post('email'))) || $this->ion_auth->id_check($this->input->post('cedula')) || $this->ion_auth->empresa_check($this->input->post('c')) )
                {

                  if(!$this->ion_auth->id_check($this->input->post('c')))
                  {
                   if(!$this->ion_auth->empresa_check($this->input->post('c')))
                   {
                    $this->data['custom_error'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><p>El nit o cédula no se encuentra autorizado en el sistema, por favor verifique y vuelva a intentarlo.</p></div>';
                  }
                  else {
                    if ($this->ion_auth->username_check(strtolower($this->input->post('nombre'))))
                    {
                     $this->data['custom_error'] ='<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Alias ya se encuentra registrado</div>';
                   }
                   else
                   {
                     if ($this->ion_auth->email_check(strtolower($this->input->post('email'))))
                     {
                       $this->data['custom_error'] ='<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Email ya se encuentra registrado</div>';
                     } 
                     else
                     {
                       if ($this->ion_auth->id_check($this->input->post('cedula')))
                       {
                        $this->data['custom_error'] ='<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Nit o cédula ya se encuentra registrado</div>';
                      }
                    }
                  }  
                }
              }else{
                $this->data['custom_error'] ='<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><p>El usuario ya se encuentra registrado en la base de datos.</p>';
              }


                } 
               else
                {    
                  if ($this->form_validation->run() == false)
                  {
                   $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                  } else
                  {   
                   $id       = $this->input->post('cedula');
                   $username = strtolower($this->input->post('nombre'));
                   $email    = strtolower($this->input->post('email'));
                   $group    = array(3);
                   $password = $this->input->post('password');
                   $additional_data = array(
                        'IDUSUARIO' => set_value('cedula'),
                        'NOMBRES' => set_value('nombres'),
                        'APELLIDOS' => set_value('apellidos'),
                        'IDCARGO' => set_value('cargo_id')
                   );
                    if ($this->ion_auth->register($id,$username, $password, $email, $additional_data, $group))
                     {
                       $this->data['results'] = $this->codegen_model->getSelect('PERMISOS_GRUPOS','*','WHERE IDGRUPO=3');
                       foreach ($this->data['results'] as $key => $value) {
                          $permisos_data= array(
                                   'IDUSUARIO' => $id,
                                   'IDMENU'    => $value->IDMENU
                           );
                         $this->codegen_model->add('PERMISOS_USUARIOS',$permisos_data);
                        }
                       $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha registrado con éxito, por favor verifique su correo elerctrónico para completar la activación de su cuenta.</div>');
                       redirect(base_url().'/');
                     }
                     else
                     {
                       $this->data['custom_error'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><p>An Error Occured.</p></div>';

                     }
                   }
                }
         
                  
             $this->template->set('title', 'Registro de usuarios');
             //add style an js files for inputs selects
                $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                    );
                $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                    );
             $this->data['cargos']  = $this->codegen_model->getSelect('CARGOS','IDCARGO,DESCRIPCIONCARGO');
             $this->template->load('templates/main', 'inicio/registro',$this->data); 
            
    }

    function  id_check() {
       ;
       if(!$this->ion_auth->id_check($this->input->post('c')))
       {
          if(!$this->ion_auth->empresa_check($this->input->post('c')))
          {
            echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><p>El nit o cédula no se encuentra autorizado en el sistema, por favor verifique y vuelva a intentarlo.</p></div>';
          }
       }else{
          echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><p>El usuario ya se encuentra registrado en la base de datos.</p>';
       }
    }
}

/* End of file categorias.php */
/* Location: ./system/application/controllers/categorias.php */