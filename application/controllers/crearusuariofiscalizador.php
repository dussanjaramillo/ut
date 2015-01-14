<?php

class Crearusuariofiscalizador extends MY_Controller {
		
		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');		
		$this->load->helper(array('form','url','email','codegen_helper'));
		$this->load->model('codegen_model','',TRUE);

	}	
	
	function index(){
		$this->manage();
	}

	function manage(){
		
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('crearusuariofiscalizador/manage'))
							 {
								 $this->template->set('title', 'Crear Usuario Fiscalizador');
								//template data
								$this->add();
								
								$this->data['message']=$this->session->flashdata('message');
								
							 }else {
								$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
											redirect(base_url().'index.php/inicio');
							 } 

						}else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}
	
		function add(){        
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('crearusuariofiscalizador/add'))
							 {
										
										$this->load->library('form_validation');    
										$this->data['custom_error'] = '';
										
										$this->form_validation->set_rules('nombre', 'Nombres', 'required|xss_clean|trim|max_length[80]');
										$this->form_validation->set_rules('apellido', 'Apellidos', 'required|xss_clean|trim|max_length[80]');
										$this->form_validation->set_rules('cedula', 'Nro. Cédula', 'required|xss_clean|trim|numeric|');
										$this->form_validation->set_rules('direccion', 'Dirección', 'required|xss_clean|trim');
										$this->form_validation->set_rules('telefono', 'Teléfono', 'xss_clean|trim|max_length[12]');
										$this->form_validation->set_rules('celular', 'Celular', 'required|xss_clean|trim|numeric');
										$this->form_validation->set_rules('emaile', 'Correo Empresarial', 'trim|xss_clean|required|valid_email');
										$this->form_validation->set_rules('emailp', 'Correo Personal', 'trim|required|valid_email');
										$this->form_validation->set_rules('alias', 'Alias', 'required|trim|xss_clean|max_length[128]');
										$this->form_validation->set_rules('password','Contraseña', 'required|xss_clean|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[repassword]');
           							    $this->form_validation->set_rules('repassword','Confirmar contraseña', 'required|xss_clean');
    									$this->form_validation->set_rules('regional_id', 'Regional', 'required|numeric|greater_than[0]');
										//$this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]');
										$this->form_validation->set_rules('grupo_id', 'Grupo',  'required|numeric|trim|greater_than[0]');
										$this->form_validation->set_rules('cargo_id', 'Cargo',  'required|numeric|greater_than[0]');
										
										if ($this->form_validation->run() == false)
										{
											$this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										}
										else
							            {
								          	if ($this->ion_auth->username_check(strtolower($this->input->post('alias'))) || $this->ion_auth->email_check(strtolower($this->input->post('emaile'))) || $this->ion_auth->id_check($this->input->post('cedula')) )
							            	{
							             		$this->data['custom_error'] ='<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Alias, email o cédula ya se encuentra registrado</div>';
							            	} 
							            	else
											{    
																	 $id       = $this->input->post('cedula');
														             $username = strtolower($this->input->post('alias'));
														             $email    = strtolower($this->input->post('emaile'));
														             $password = $this->input->post('password');
														             $group    = array($this->input->post('grupo_id'));
														             $additional_data = array(
														                        'NOMBRES' => set_value('nombre'),
																				'APELLIDOS' => set_value('apellido'),
																				'IDUSUARIO' => set_value('cedula'),
																				'DIRECCION' => set_value('direccion'),
																				'TELEFONO' => set_value('telefono'),
																				'CELULAR' => set_value('celular'),
																				'CORREO_PERSONAL' => set_value('emailp'),
																				'FISCALIZADOR' => 'S',
																				'COD_REGIONAL' => set_value('regional_id'),
																				//'ACTIVO' => set_value('estado_id'),
																				'IDCARGO' => set_value('cargo_id'),
																				'USUARIO_MODIFICACION' => 'NUNCA',
																				//:::::recupera el nombre de usuario que se encuenra logueado actualmente 
																				'USUARIO_CREACION' => $this->session->userdata('username')
														                );
	                   												
													
																 
													if ($this->ion_auth->register($id,$username, $password, $email, $additional_data, $group))
									    			{
											            $this->data['results'] = $this->codegen_model->getSelect('PERMISOS_GRUPOS','*','WHERE IDGRUPO='.$this->input->post('grupo_id'));
											            foreach ($this->data['results'] as $key => $value) {
											            	$this->db->select("IDMENU");
												            $this->db->where("IDMENU", $value->IDMENU);
												            $this->db->where("IDUSUARIO", $id);
												            $res = $this->db->get("PERMISOS_USUARIOS");
											              	if($res->num_rows() == 0) :
												                $permisos_data = array(
												                    'IDUSUARIO' => $id,
												                    'IDMENU' => $value->IDMENU
												                );
												            endif;
												            //echo "<pre>";print_r($permisos_data);echo "</pre>";
															if(!empty($permisos_data)) :
													            $this->codegen_model->add('PERMISOS_USUARIOS', $permisos_data);
															endif;
											            }
											            
											    			  $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El usuario se ha creado con éxito.</div>');
											                  redirect(base_url().'index.php/crearusuariofiscalizador/');
									    			}
													else
													{
														$this->data['custom_error'] = '<div class="form_error"><p>Ha Ocurrido Un Error.</p></div>';

													}
												}

													
								        }


												
										//add style an js files for inputs selects
										$this->data['style_sheets']= array(
														'css/chosen.css' => 'screen'
												);
										$this->data['javascripts']= array(
														'js/chosen.jquery.min.js'
												);
										
										
										$this->data['cargos']  = $this->codegen_model->getSelect('CARGOS','IDCARGO,DESCRIPCIONCARGO,NOMBRECARGO');
										$this->data['regional']  = $this->codegen_model->getSelect('REGIONAL','COD_REGIONAL,NOMBRE_REGIONAL');
										$this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');
										$this->data['message']=$this->session->flashdata('message');
										$this->template->load($this->template_file, 'crearusuariofiscalizador/crearusuariofiscalizador_add', $this->data);
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/crearusuariofiscalizador');
									 }
							 }
						else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}

		function  id_check() {
	       
	       if($this->ion_auth->id_check($this->input->post('c')))
	       {
	          echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><p>El número de cedula ya se encuentra registrado en la base de datos.</p>';
	       }
	    }

	    function  email_check() {
	       
	       if($this->ion_auth->email_check(strtolower($this->input->post('c'))))
	       {
	          echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><p>El Correo Empresarial ya se encuentra registrado en la base de datos.</p>';
	       }
	    }

	    function  username_check() {
	       
	       if($this->ion_auth->username_check(strtolower($this->input->post('c'))))
	       {
	          echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><p>El Alias ya se encuentra registrado en la base de datos.</p>';
	       }
	    }

			
}
