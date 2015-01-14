<?php

class Tiposcartera extends MY_Controller {
		
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposcartera/manage'))
							 {
								//template data
								$this->template->set('title', 'Administrar Tipo Cartera');
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen'
												);
								$this->data['javascripts']= array(
														'js/jquery.dataTables.min.js',
														'js/jquery.dataTables.defaults.js'
												);
								$this->data['message']=$this->session->flashdata('message');
								$this->template->load($this->template_file, 'tiposcartera/tiposcartera_list',$this->data); 
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposcartera/add'))
							 {
										$this->load->library('form_validation');    
										$this->data['custom_error'] = '';
										$showForm=0;
										$this->form_validation->set_rules('nombrecartera', 'Nombre Cartera', 'required|trim|xss_clean|max_length[128]');
										$this->form_validation->set_rules('tipot', 'Tipo Cartera', 'required'); 
										$this->form_validation->set_rules('fechacreacion', 'Fecha Creación', 'required'); 
										$this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]');    
												if ($this->form_validation->run() == false)
										{
												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{    
												$date = array( 
																'FECHA_CREACION' => set_value('fechacreacion')
																);
												$data = array(
																'NOMBRE_CARTERA' => set_value('nombrecartera'),
																'TIPO' => set_value('tipot'),
																'COD_ESTADO' => set_value('estado_id')
												);
												 
									if ($this->codegen_model->add('TIPOCARTERA',$data, $date) == TRUE)
									{
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Tipo cartera se ha creado con éxito.</div>');
													redirect(base_url().'index.php/tiposcartera/');
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
															
										$this->template->load($this->template_file, 'tiposcartera/tiposcartera_add', $this->data);
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/tiposcartera');
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposcartera/edit'))
							 {    
								$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
													redirect(base_url().'index.php/tiposcartera');
										}else{
															$this->load->library('form_validation');  
															$this->data['custom_error'] = '';
															$this->form_validation->set_rules('nombrecartera', 'Nombre Cartera', 'required');
															$this->form_validation->set_rules('tipot', 'Tipo Cartera', 'required');
															$this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]');  
															if ($this->form_validation->run() == false)
															{
																	 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
																	
															} else
															{                            
																	$data = array(
																					'NOMBRE_CARTERA' => $this->input->post('nombrecartera'),
																					'TIPO' => $this->input->post('tipot'),
																					'COD_ESTADO' => $this->input->post('estado_id')
																	);
																 
														if ($this->codegen_model->edit('TIPOCARTERA',$data,'COD_TIPOCARTERA',$this->input->post('id')) == TRUE)
														{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La tasa de pago se ha editado correctamente.</div>');
																			redirect(base_url().'index.php/tiposcartera/');
														}
														else
														{
															$this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';

														}
													}
															$this->data['result'] = $this->codegen_model->get('TIPOCARTERA','COD_TIPOCARTERA,NOMBRE_CARTERA, TIPO, FECHA_CREACION, COD_ESTADO','COD_TIPOCARTERA = '.$this->uri->segment(3),1,1,true);
															$this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');
																	
																	//add style an js files for inputs selects
																	$this->data['style_sheets']= array(
																					'css/chosen.css' => 'screen'
																			);
																	$this->data['javascripts']= array(
																					'js/chosen.jquery.min.js'
																			);

																	$this->template->load($this->template_file, 'tiposcartera/tiposcartera_edit', $this->data); 
											}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/tiposcartera');
									 }
								
						}else
								{
								redirect(base_url().'index.php/auth/login');
								}
				
		}
	
		function delete(){
				 if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposcartera/delete'))
							 {
										$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
													redirect(base_url().'index.php/tiposcartera');
										}else{
											 $data = array(
                                    				'COD_ESTADO' => '2'

				                            	);
				           				if($this->codegen_model->edit('TIPOCARTERA',$data,'COD_TIPOCARTERA',$ID) == TRUE){
												//$this->codegen_model->delete('TIPOCARTERA','COD_TIPOCARTERA',$ID);             
												$this->template->set('title', 'tiposcartera');
												$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El tipo de cartera se eliminó correctamente.'.$ID.'</div>');
												redirect(base_url().'index.php/tiposcartera/');
										}
									}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/tiposcartera');
									 }
					}else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}
 
		 function datatable (){
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposcartera/manage'))
							 {
							 
							 if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposcartera/edit'))
							 {
								$this->load->library('datatables');
								$this->datatables->select('TIPOCARTERA.COD_TIPOCARTERA,TIPOCARTERA.NOMBRE_CARTERA,  TIPOCARTERA.TIPO, TIPOCARTERA.FECHA_CREACION, E.NOMBREESTADO');
								$this->datatables->from('TIPOCARTERA'); 
								$this->datatables->join('ESTADOS E','E.IDESTADO = TIPOCARTERA.COD_ESTADO', ' inner ');
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="'.base_url().'index.php/tiposcartera/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'TIPOCARTERA.COD_TIPOCARTERA');
							}else{
								$this->load->library('datatables');
								$this->datatables->select('TIPOCARTERA.COD_TIPOCARTERA,TIPOCARTERA.NOMBRE_CARTERA, TIPOCARTERA.TIPO, TIPOCARTERA.FECHA_CREACION, E.NOMBREESTADO');
								$this->datatables->from('TIPOCARTERA'); 
								$this->datatables->join('ESTADOS E','E.IDESTADO = TIPOCARTERA.COD_ESTADO', ' inner ');
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'TIPOCARTERA.COD_TIPOCARTERA');
							}
								echo $this->datatables->generate();
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/tiposcartera');
									 }
						}else
						{
							redirect(base_url().'index.php/auth/login');
						}           
		}
}


/* End of file tiposcartera.php */
/* Location: ./system/application/controllers/tiposcartera.php */