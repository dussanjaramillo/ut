<?php

class Admestructuras extends MY_Controller {
		
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/manage'))
							 {
								//template data
								$this->template->set('title', 'Administrar estructuras');
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen'
												);
								$this->data['javascripts']= array(
														'js/jquery.dataTables.min.js',
														'js/jquery.dataTables.defaults.js'
												);
								$this->data['message']=$this->session->flashdata('message');

								$this->template->load($this->template_file, 'admestructuras/admestructuras_list',$this->data); 
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/add'))
							 {
										$this->load->library('form_validation');    
										$this->data['custom_error'] = '';
										$showForm=0;
										$this->form_validation->set_rules('estructura', 'Estructura', 'required|trim|xss_clean|max_length[128]');
										$this->form_validation->set_rules('origenda', 'Origen Datos', 'required|numeric');
										$this->form_validation->set_rules('origendt', 'Tipo Estructura',  'required|numeric|greater_than[0]'); 
										$this->form_validation->set_rules('extensiont', 'Extensión',  'required|numeric|greater_than[0]');  
										$this->form_validation->set_rules('tipocarterac', 'Tipo Cartera',  'numeric');
										$this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]');

										if ($this->form_validation->run() == false)
										{
												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{    
												 $date = array( 
																'FECHA_CREACION' => $this->input->post('fechacreacion')
																);
												$data = array(
																'COD_FUNCIONALIDAD' => '',
																'COD_EXTENSION' => set_value('extensiont'),
																'NOMBRE_ESTRUCTURA' => set_value('estructura'),
																'COD_ORGIENDATOS' => set_value('origenda'),
																'COD_TIPOESTRUCTURA' => set_value('origendt'),
																'COD_TIPOCARTERA' => set_value('tipocarterac'),
																'COD_ESTADO' => set_value('estado_id')
																);
												 
									if ($this->codegen_model->add('ESTRUCTURA',$data,$date) == TRUE)
									{
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Estructura se ha creado con éxito.</div>');
													redirect(base_url().'index.php/admestructuras/');
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
										$this->data['tipoestructura']  = $this->codegen_model->getSelect('TIPOESTRUCTURA','COD_TIPOESTRUCTURA,NOMBRE_TIPO');
										$this->data['origendatos']  = $this->codegen_model->getSelect('ORIGENDATOS','COD_ORIGENDATOS,NOMBRE_ORIGENDATOS');
										$this->data['extensiones']  = $this->codegen_model->getSelect('EXTENSION_ARCHIVO','COD_EXTENSION,NOMBRE_EXTENSION');
										$this->data['tipocartera']  = $this->codegen_model->getSelect('TIPOCARTERA','COD_TIPOCARTERA,NOMBRE_CARTERA');
										$this->template->load($this->template_file, 'admestructuras/admestructuras_add',$this->data); 
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/admestructuras');
									 }
							 }
						else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}

	function addarchivo(){        
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/add'))
							 {
										$this->load->library('form_validation');    
										$this->data['custom_error'] = '';
										$showForm=0;
										$this->form_validation->set_rules('archivo', 'Archivo', 'required|trim|xss_clean|max_length[128]');
										$this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]');

										if ($this->form_validation->run() == false)
										{
												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{    
												 $date = array( 
																'FECHA_CREACION' => $this->input->post('fechacreacion')
																);
												$data = array(
																'NOMBRE_ARCHIVO' => set_value('archivo'),
																'COD_ESTRUCTURA' => $this->input->post('idestructura'),
																'COD_ESTADO' => set_value('estado_id')
																);
												 
									if ($this->codegen_model->add('ARCHIVOSESTRUCTURA',$data,$date) == TRUE)
									{
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El archivo se ha creado con éxito.</div>');
													redirect(base_url().'index.php/admestructuras/');
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
										$this->data['result'] = $this->codegen_model->get('ESTRUCTURA','COD_ESTRUCTURA,NOMBRE_ESTRUCTURA','COD_ESTRUCTURA = '.$this->uri->segment(3),1,1,true);
										$this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');
										
										$this->template->load($this->template_file, 'admestructuras/admestructuras_add_archivo',$this->data); 
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/admestructuras');
									 }
							 }
						else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}


	function addcampo(){        
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/add'))
							 {
										$this->load->library('form_validation');    
										$this->data['custom_error'] = '';
										$showForm=0;

										$this->form_validation->set_rules('posi', 'Posición',  'required|numeric');
										$this->form_validation->set_rules('nombrecampo', 'Nombre Campo', 'required|trim|xss_clean|max_length[128]');
										$this->form_validation->set_rules('desc', 'Descripción', 'required|trim|xss_clean|max_length[128]');
										$this->form_validation->set_rules('long', 'Longitud',  'required|numeric');
										$this->form_validation->set_rules('relleno', 'Carácter de Relleno',  'numeric');
										$this->form_validation->set_rules('formato', 'Formato',  'required');
										$this->form_validation->set_rules('constante', 'Constante', 'required|trim|xss_clean|max_length[128]');
										
										if ($this->form_validation->run() == false)
										{
												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{    
												 
												$data = array(
																'NOMBRE_CAMPO' => set_value('nombrecampo'),
																'POSICION' => set_value('posi'),
																'DESCRIPCION' => set_value('desc'),
																'LONGITUD' => set_value('long'),
																'CARACTER_RELLENO' => set_value('relleno'),
																'FORMATO' => $this->input->post('formato'),
																'CONSTANTE' => $this->input->post('constante'),
																'COD_ARCHIVO' => $this->input->post('idcodarchivo')
																);
												 
									if ($this->codegen_model->add('CAMPOS_IDENTIFICACION_ARCHIVO',$data) == TRUE)
									{
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Campo se ha añedido con éxito.</div>');
													redirect(base_url().'index.php/admestructuras/campos/'.$this->input->post('idcodarchivo').'');
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
										$this->data['result'] = $this->codegen_model->get('ARCHIVOSESTRUCTURA','COD_ARCHIVO, NOMBRE_ARCHIVO ,COD_ESTADO','COD_ARCHIVO = '.$this->uri->segment(3),1,1,true);
										$this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');


										$this->template->load($this->template_file, 'admestructuras/admestructuras_add_archivo_campo',$this->data); 
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/admestructuras');
									 }
							 }
						else
						{
							redirect(base_url().'index.php/auth/login');
						}
	}

	function addcampoarch(){        
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/add'))
							 {
										$this->load->library('form_validation');    
										$this->data['custom_error'] = '';
										$showForm=0;

										$this->form_validation->set_rules('posi', 'Posición',  'required|numeric');
										$this->form_validation->set_rules('tiporeg', 'Tipo Registro',  'required|numeric');
										$this->form_validation->set_rules('nombrecampo', 'Nombre Campo', 'required|trim|xss_clean|max_length[128]');
										$this->form_validation->set_rules('aling', 'Alineación', 'required|trim|xss_clean|max_length[128]');
										$this->form_validation->set_rules('long', 'Longitud',  'required|numeric');
										$this->form_validation->set_rules('relleno', 'Carácter de Relleno',  'numeric');
										$this->form_validation->set_rules('tipodato', 'Tipo Dato', 'trim|xss_clean|max_length[128]');
										$this->form_validation->set_rules('separator', 'Separador Decimales', 'required');
										$this->form_validation->set_rules('formato', 'Formato',  'trim|xss_clean|max_length[128]');
										$this->form_validation->set_rules('vistacons', 'Vista Consulta', '');
										
										if ($this->form_validation->run() == false)
										{
												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{    
												 
												$data = array(
																'NOMBRE_CAMPO' => set_value('nombrecampo'),
																'COD_TIPOREGISTRO' => set_value('tiporeg'),
																'LONGITUD' => set_value('long'),
																'POSICION' => set_value('posi'),
																'ALINEACION' => set_value('aling'),
																'CARACTER_RELLENO' => set_value('relleno'),
																'TIPO_DATO' => set_value('tipodato'),
																'SEPARADOR_DECIMALES' => set_value('separator'),
																'FORMATO' => $this->input->post('formato'),
																'VISTA_CONSULTA' => $this->input->post('vistacons'),
																'COD_ARCHIVO' => $this->input->post('idcodarchivo')
																);
												 
									if ($this->codegen_model->add('CAMPOSARCHIVO',$data) == TRUE)
									{
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Campo se ha añedido con éxito.</div>');
													redirect(base_url().'index.php/admestructuras/camposarchivo/'.$this->input->post('idcodarchivo').'');
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
										$this->data['result'] = $this->codegen_model->get('ARCHIVOSESTRUCTURA','COD_ARCHIVO, NOMBRE_ARCHIVO ,COD_ESTADO','COD_ARCHIVO = '.$this->uri->segment(3),1,1,true);
										$this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');
										$this->data['tiposreg']  = $this->codegen_model->getSelect('TIPOREGISTRO','COD_TIPOREGISTRO,NOMBRE_TIPO');
										


										$this->template->load($this->template_file, 'admestructuras/admestructuras_add_archivo_ident',$this->data); 
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/admestructuras');
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/edit'))
							 {    
								$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
													redirect(base_url().'index.php/admestructuras');
										}else{
															$this->load->library('form_validation');  
															$this->data['custom_error'] = '';
															$this->form_validation->set_rules('estructura', 'Estructura', 'required|trim|xss_clean|max_length[128]');
															$this->form_validation->set_rules('origenda', 'Origen Datos', 'required|numeric');
															$this->form_validation->set_rules('origendt', 'Tipo Estructura',  'required|numeric|greater_than[0]'); 
															$this->form_validation->set_rules('extensiont', 'Extensión',  'required|numeric|greater_than[0]');  
															$this->form_validation->set_rules('tipocarterac', 'Tipo Cartera',  'required|numeric|greater_than[0]');
															$this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]');  


															if ($this->form_validation->run() == false)
															{
																	 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
																	
															} else
															{                            
																	$data = array(
																				'COD_EXTENSION' => set_value('extensiont'),
																				'COD_ORGIENDATOS' => set_value('origenda'),
																				'COD_TIPOESTRUCTURA' => set_value('origendt'),
																				'COD_TIPOCARTERA' => set_value('tipocarterac'),
																				'COD_ESTADO' => set_value('estado_id')
																				);
																 
														if ($this->codegen_model->edit('ESTRUCTURA',$data,'COD_ESTRUCTURA',$this->input->post('id')) == TRUE)
														{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Estructura se ha editado correctamente.</div>');
																			redirect(base_url().'index.php/admestructuras/');
														}
														else
														{
															$this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';

														}
													}
															$this->data['result'] = $this->codegen_model->get('ESTRUCTURA','COD_ESTRUCTURA,COD_FUNCIONALIDAD, COD_EXTENSION, NOMBRE_ESTRUCTURA, COD_ORGIENDATOS, COD_TIPOESTRUCTURA, COD_TIPOCARTERA, FECHA_CREACION,COD_ESTADO','COD_ESTRUCTURA = '.$this->uri->segment(3),1,1,true);
															$this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');
															$this->data['tipoestructura']  = $this->codegen_model->getSelect('TIPOESTRUCTURA','COD_TIPOESTRUCTURA,NOMBRE_TIPO');
															$this->data['origendatos']  = $this->codegen_model->getSelect('ORIGENDATOS','COD_ORIGENDATOS,NOMBRE_ORIGENDATOS');
															$this->data['extensiones']  = $this->codegen_model->getSelect('EXTENSION_ARCHIVO','COD_EXTENSION,NOMBRE_EXTENSION');
															$this->data['tipocartera']  = $this->codegen_model->getSelect('TIPOCARTERA','COD_TIPOCARTERA,NOMBRE_CARTERA');

																	//add style an js files for inputs selects
																	$this->data['style_sheets']= array(
																					'css/chosen.css' => 'screen'
																			);
																	$this->data['javascripts']= array(
																					'js/chosen.jquery.min.js'
																			);

																	$this->template->load($this->template_file, 'admestructuras/admestructuras_edit', $this->data); 
											}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/admestructuras');
									 }
								
						}else
								{
								redirect(base_url().'index.php/auth/login');
								}
				
		}

		function editarchivo(){    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/edit'))
							 {    
								$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
													redirect(base_url().'index.php/admestructuras');
										}else{
															$this->load->library('form_validation');  
															$this->data['custom_error'] = '';
															$this->form_validation->set_rules('archivo', 'Archivo', 'required|trim|xss_clean|max_length[128]');
															$this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]');  


															if ($this->form_validation->run() == false)
															{
																	 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
																	
															} else
															{                            
																	$data = array(
																				'NOMBRE_ARCHIVO' => set_value('archivo'),
																				'COD_ESTADO' => set_value('estado_id')
																				);
																 
														if ($this->codegen_model->edit('ARCHIVOSESTRUCTURA',$data,'COD_ARCHIVO',$this->input->post('id')) == TRUE)
														{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Estructura se ha editado correctamente.</div>');
																			redirect(base_url().'index.php/admestructuras/');
														}
														else
														{
															$this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';

														}
													}
															$this->data['result'] = $this->codegen_model->get('ARCHIVOSESTRUCTURA','COD_ARCHIVO, NOMBRE_ARCHIVO, COD_ESTADO','COD_ARCHIVO = '.$this->uri->segment(3),1,1,true);
															$this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');

																	//add style an js files for inputs selects
																	$this->data['style_sheets']= array(
																					'css/chosen.css' => 'screen'
																			);
																	$this->data['javascripts']= array(
																					'js/chosen.jquery.min.js'
																			);

																	$this->template->load($this->template_file, 'admestructuras/admestructuras_edit_archivo', $this->data); 
											}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/admestructuras');
									 }
								
						}else
								{
								redirect(base_url().'index.php/auth/login');
								}
				
		}

		function editarcampoarch(){    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/edit'))
							 {    
								$ID =  $this->uri->segment(3);
								$IDArchivo = $this->uri->segment(4);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
													redirect(base_url().'index.php/admestructuras');
										}else{
															$this->load->library('form_validation');  
															$this->data['custom_error'] = '';
															$this->form_validation->set_rules('posi', 'Posición',  'required|numeric');
															$this->form_validation->set_rules('tiporeg', 'Tipo Registro',  'required|numeric');
															$this->form_validation->set_rules('nombrecampo', 'Nombre Campo', 'required|trim|xss_clean|max_length[128]');
															$this->form_validation->set_rules('aling', 'Alineación', 'required|trim|xss_clean|max_length[128]');
															$this->form_validation->set_rules('long', 'Longitud',  'required|numeric');
															$this->form_validation->set_rules('relleno', 'Carácter de Relleno',  'numeric');
															$this->form_validation->set_rules('tipodato', 'Tipo Dato', 'trim|xss_clean|max_length[128]');
															$this->form_validation->set_rules('separator', 'Separador Decimales', 'required');
															$this->form_validation->set_rules('formato', 'Formato',  'trim|xss_clean|max_length[128]');
															$this->form_validation->set_rules('vistacons', 'Vista Consulta', '');
															
															if ($this->form_validation->run() == false)
															{
																	 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

															} else
															{    
																	 
																	$data = array(
																					'NOMBRE_CAMPO' => set_value('nombrecampo'),
																					'COD_TIPOREGISTRO' => set_value('tiporeg'),
																					'LONGITUD' => set_value('long'),
																					'POSICION' => set_value('posi'),
																					'ALINEACION' => set_value('aling'),
																					'CARACTER_RELLENO' => set_value('relleno'),
																					'TIPO_DATO' => set_value('tipodato'),
																					'SEPARADOR_DECIMALES' => set_value('separator'),
																					'FORMATO' => $this->input->post('formato'),
																					'VISTA_CONSULTA' => $this->input->post('vistacons'),
																					'COD_ARCHIVO' => $this->input->post('idcodarchivo')
																					);
																 
																if ($this->codegen_model->edit('CAMPOSARCHIVO',$data,'COD_CAMPOARCHIVO',$this->input->post('idcampo')) == TRUE)
																{
																	$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El campo se ha editado correctamente.</div>');
																					redirect(base_url().'index.php/admestructuras/camposarchivo/'.$IDArchivo.'');
																}
																else
																{
																	$this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';

																}
															}
															$this->data['result'] = $this->codegen_model->get('CAMPOSARCHIVO','COD_CAMPOARCHIVO,COD_ARCHIVO,NOMBRE_CAMPO, COD_TIPOREGISTRO , POSICION, LONGITUD, ALINEACION, CARACTER_RELLENO, TIPO_DATO, SEPARADOR_DECIMALES, FORMATO, VISTA_CONSULTA','COD_CAMPOARCHIVO = '.$this->uri->segment(3),1,1,true);
															$this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');
															$this->data['tiposreg']  = $this->codegen_model->getSelect('TIPOREGISTRO','COD_TIPOREGISTRO,NOMBRE_TIPO');
										

																	//add style an js files for inputs selects
																	$this->data['style_sheets']= array(
																					'css/chosen.css' => 'screen'
																			);
																	$this->data['javascripts']= array(
																					'js/chosen.jquery.min.js'
																			);

																	$this->template->load($this->template_file, 'admestructuras/admestructuras_edit_archivo_campo', $this->data); 
											}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/admestructuras');
									 }
								
						}else
								{
								redirect(base_url().'index.php/auth/login');
								}
				
		}

		function editarcampo(){    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/edit'))
							 {    
								$ID =  $this->uri->segment(3);
								$IDArchivo = $this->uri->segment(4);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
													redirect(base_url().'index.php/admestructuras');
										}else{
															$this->load->library('form_validation');  
															$this->data['custom_error'] = '';
															$this->form_validation->set_rules('posi', 'Posición',  'required|numeric');
															$this->form_validation->set_rules('nombrecampo', 'Nombre Campo', 'required|trim|xss_clean|max_length[128]');
															$this->form_validation->set_rules('desc', 'Descripción', 'required|trim|xss_clean|max_length[128]');
															$this->form_validation->set_rules('long', 'Longitud',  'required|numeric');
															$this->form_validation->set_rules('relleno', 'Carácter de Relleno',  'numeric');
															$this->form_validation->set_rules('formato', 'Formato',  'required');
															$this->form_validation->set_rules('constante', 'Constante', 'required|trim|xss_clean|max_length[128]');
										

															if ($this->form_validation->run() == false)
															{
																	 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
																	
															} else
															{                            
																	$data = array(
																				'NOMBRE_CAMPO' => set_value('nombrecampo'),
																				'POSICION' => set_value('posi'),
																				'DESCRIPCION' => set_value('desc'),
																				'LONGITUD' => set_value('long'),
																				'CARACTER_RELLENO' => set_value('relleno'),
																				'FORMATO' => $this->input->post('formato'),
																				'CONSTANTE' => $this->input->post('constante'),
																				);
																 
														if ($this->codegen_model->edit('CAMPOS_IDENTIFICACION_ARCHIVO',$data,'COD_CAMPOS_IDENT',$this->input->post('idcampo')) == TRUE)
														{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Estructura se ha editado correctamente.</div>');
																			redirect(base_url().'index.php/admestructuras/campos/'.$IDArchivo.'');
														}
														else
														{
															$this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';

														}
													}
															$this->data['result'] = $this->codegen_model->get('CAMPOS_IDENTIFICACION_ARCHIVO','COD_CAMPOS_IDENT, NOMBRE_CAMPO, POSICION, DESCRIPCION, LONGITUD, CARACTER_RELLENO, FORMATO, CONSTANTE, COD_ARCHIVO','COD_CAMPOS_IDENT = '.$this->uri->segment(3),1,1,true);
															

																	//add style an js files for inputs selects
																	$this->data['style_sheets']= array(
																					'css/chosen.css' => 'screen'
																			);
																	$this->data['javascripts']= array(
																					'js/chosen.jquery.min.js'
																			);

																	$this->template->load($this->template_file, 'admestructuras/admestructuras_edit_archivo_campo_ident', $this->data); 
											}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/admestructuras');
									 }
								
						}else
								{
								redirect(base_url().'index.php/auth/login');
								}
				
		}

		function archivo(){    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/edit'))
							 {    
								$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
													redirect(base_url().'index.php/admestructuras');
										}else{
															$this->load->library('form_validation');  
															$this->data['custom_error'] = '';
															$this->form_validation->set_rules('estructura', 'Estructura', 'required|trim|xss_clean|max_length[128]');
															$this->form_validation->set_rules('origenda', 'Origen Datos', 'required|numeric');
															$this->form_validation->set_rules('origendt', 'Tipo Estructura',  'required|numeric|greater_than[0]'); 
															$this->form_validation->set_rules('extensiont', 'Extensión',  'required|numeric|greater_than[0]');  
															$this->form_validation->set_rules('tipocarterac', 'Tipo Cartera',  'required|numeric|greater_than[0]');
															$this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]');  


															if ($this->form_validation->run() == false)
															{
																	 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
																	
															} else
															{                            
																	$data = array(
																				'COD_EXTENSION' => set_value('extensiont'),
																				'COD_ORGIENDATOS' => set_value('origenda'),
																				'COD_TIPOESTRUCTURA' => set_value('origendt'),
																				'COD_TIPOCARTERA' => set_value('tipocarterac'),
																				'COD_ESTADO' => set_value('estado_id')
																				);
																 
														if ($this->codegen_model->edit('ESTRUCTURA',$data,'COD_ESTRUCTURA',$this->input->post('id')) == TRUE)
														{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Estructura se ha editado correctamente.</div>');
																			redirect(base_url().'index.php/admestructuras/');
														}
														else
														{
															$this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';

														}
													}
															$this->data['result'] = $this->codegen_model->get('ESTRUCTURA','COD_ESTRUCTURA,COD_FUNCIONALIDAD, COD_EXTENSION, NOMBRE_ESTRUCTURA, COD_ORGIENDATOS, COD_TIPOESTRUCTURA, COD_TIPOCARTERA, FECHA_CREACION,COD_ESTADO','COD_ESTRUCTURA = '.$this->uri->segment(3),1,1,true);
															$this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');
															$this->data['tipoestructura']  = $this->codegen_model->getSelect('TIPOESTRUCTURA','COD_TIPOESTRUCTURA,NOMBRE_TIPO');
															$this->data['origendatos']  = $this->codegen_model->getSelect('ORIGENDATOS','COD_ORIGENDATOS,NOMBRE_ORIGENDATOS');
															$this->data['extensiones']  = $this->codegen_model->getSelect('EXTENSION_ARCHIVO','COD_EXTENSION,NOMBRE_EXTENSION');
															$this->data['tipocartera']  = $this->codegen_model->getSelect('TIPOCARTERA','COD_TIPOCARTERA,NOMBRE_CARTERA');

																	//add style an js files for inputs selects
																	$this->data['style_sheets']= array(
																				'css/jquery.dataTables_themeroller.css' => 'screen'
																					);
																	$this->data['javascripts']= array(
																				'js/jquery.dataTables.min.js',
																				'js/jquery.dataTables.defaults.js'
																				);

																	$this->template->load($this->template_file, 'admestructuras/admestructuras_list_archivos', $this->data); 
											}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/admestructuras');
									 }
								
						}else
								{
								redirect(base_url().'index.php/auth/login');
								}
				
		}

		function campos(){    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/edit'))
							 {    
								$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
													redirect(base_url().'index.php/admestructuras');
										}else{
															
													}
															$this->data['result'] = $this->codegen_model->get('ARCHIVOSESTRUCTURA','COD_ARCHIVO, NOMBRE_ARCHIVO ,COD_ESTADO','COD_ARCHIVO = '.$this->uri->segment(3),1,1,true);
															$this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');

																	//add style an js files for inputs selects
																	$this->data['style_sheets']= array(
																				'css/jquery.dataTables_themeroller.css' => 'screen'
																					);
																	$this->data['javascripts']= array(
																				'js/jquery.dataTables.min.js',
																				'js/jquery.dataTables.defaults.js'
																				);

																	$this->template->load($this->template_file, 'admestructuras/admestructuras_list_identificacion_campos', $this->data); 
								
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/admestructuras');
									 }
								
						}else
								{
								redirect(base_url().'index.php/auth/login');
								}
				
		}


		function camposarchivo(){    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/edit'))
							 {    
								$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
													redirect(base_url().'index.php/admestructuras');
										}else{
															
													}
															$this->data['result'] = $this->codegen_model->get('ARCHIVOSESTRUCTURA','COD_ARCHIVO, NOMBRE_ARCHIVO ,COD_ESTADO','COD_ARCHIVO = '.$this->uri->segment(3),1,1,true);
															$this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');

																	//add style an js files for inputs selects
																	$this->data['style_sheets']= array(
																				'css/jquery.dataTables_themeroller.css' => 'screen'
																					);
																	$this->data['javascripts']= array(
																				'js/jquery.dataTables.min.js',
																				'js/jquery.dataTables.defaults.js'
																				);

																	$this->template->load($this->template_file, 'admestructuras/admestructuras_list_archivos_campos', $this->data); 
								
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/admestructuras');
									 }
								
						}else
								{
								redirect(base_url().'index.php/auth/login');
								}
				
		}
	
		function delete(){
				 if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/delete'))
							 {
										$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
													redirect(base_url().'index.php/admestructuras');
										}else{
											 $data = array(
                                    				'COD_ESTADO' => '2'

				                            	);
				           				if($this->codegen_model->edit('ESTRUCTURA',$data,'COD_ESTRUCTURA',$ID) == TRUE){
												//$this->codegen_model->delete('TASA_ACUERDO_PAGO','IDTASA',$ID);             
												$this->template->set('title', 'Administrar Estructuras');
												$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Estructura se eliminó correctamente.'.$ID.'</div>');
												redirect(base_url().'index.php/admestructuras/');
										}
									}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/admestructuras');
									 }
					}else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}

		function deletearchivo(){
				 if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/delete'))
							 {
										$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
													redirect(base_url().'index.php/admestructuras');
										}else{
											 $data = array(
                                    				'COD_ESTADO' => '2'

				                            	);
				           				if($this->codegen_model->edit('ARCHIVOSESTRUCTURA',$data,'COD_ARCHIVO',$ID) == TRUE){
												//$this->codegen_model->delete('ARCHIVOSESTRUCTURA','COD_ARCHIVO',$ID);             
												$this->template->set('title', 'Administrar Estructuras');
												$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El archivo se eliminó correctamente.'.$ID.'</div>');
												redirect(base_url().'index.php/admestructuras/');
										}
									}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/admestructuras');
									 }
					}else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}

		function deletecampoident(){
				 if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/delete'))
							 {
										$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
													redirect(base_url().'index.php/admestructuras');
										}else{
											 
				           				if($this->codegen_model->delete('CAMPOS_IDENTIFICACION_ARCHIVO','COD_CAMPOS_IDENT',$ID) == TRUE){
												//$this->codegen_model->delete('ARCHIVOSESTRUCTURA','COD_ARCHIVO',$ID);             
												$this->template->set('title', 'Administrar Estructuras');
												$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Campo se eliminó correctamente.'.$ID.'</div>');
												redirect(base_url().'index.php/admestructuras/');
										}
									}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/admestructuras');
									 }
					}else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}

		function deletecampoarch(){
				 if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/delete'))
							 {
										$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
													redirect(base_url().'index.php/admestructuras');
										}else{
											 
				           				if($this->codegen_model->delete('CAMPOSARCHIVO','COD_CAMPOARCHIVO',$ID) == TRUE){
												//$this->codegen_model->delete('ARCHIVOSESTRUCTURA','COD_ARCHIVO',$ID);             
												$this->template->set('title', 'Administrar Estructuras');
												$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Campo se eliminó correctamente.'.$ID.'</div>');
												redirect(base_url().'index.php/admestructuras/');
										}
									}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/admestructuras');
									 }
					}else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}
 
		 function datatable(){
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/manage'))
							 {
							 
							 if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/edit'))
							 {
								$this->load->library('datatables');
								$this->datatables->select('ESTRUCTURA.COD_ESTRUCTURA,ESTRUCTURA.NOMBRE_ESTRUCTURA, ORG.NOMBRE_ORIGENDATOS, EST.NOMBRE_TIPO, CART.NOMBRE_CARTERA,ESTRUCTURA.FECHA_CREACION,E.NOMBREESTADO');
								$this->datatables->from('ESTRUCTURA'); 
								$this->datatables->join('ESTADOS E','E.IDESTADO = ESTRUCTURA.COD_ESTADO', ' inner ');
								$this->datatables->join('ORIGENDATOS ORG','ORG.COD_ORIGENDATOS = ESTRUCTURA.COD_ORGIENDATOS', ' inner ');
								$this->datatables->join('TIPOESTRUCTURA EST','EST.COD_TIPOESTRUCTURA = ESTRUCTURA.COD_TIPOESTRUCTURA', ' inner ');								
								$this->datatables->join('TIPOCARTERA CART','CART.COD_TIPOCARTERA = ESTRUCTURA.COD_TIPOCARTERA', ' inner ');
								$this->datatables->where('ESTRUCTURA.COD_ESTADO', '1');
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="'.base_url().'index.php/admestructuras/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'ESTRUCTURA.COD_ESTRUCTURA');
								$this->datatables->add_column('archivo', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="'.base_url().'index.php/admestructuras/archivo/$1" class="btn btn-small" title="Archivos"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'ESTRUCTURA.COD_ESTRUCTURA');

							}else{
								$this->load->library('datatables');
								$this->datatables->select('TASA_ACUERDO_PAGO.IDTASA,C.NOMBRECONCEPTO, TASA_ACUERDO_PAGO.VALORTASA,E.NOMBREESTADO');
								$this->datatables->from('TASA_ACUERDO_PAGO'); 
								$this->datatables->join('ESTADOS E','E.IDESTADO = TASA_ACUERDO_PAGO.IDESTADO', ' inner ');
								$this->datatables->join('CONCEPTO C','C.IDCONCEPTO = TASA_ACUERDO_PAGO.IDCONCEPTO', ' inner ');
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'TASA_ACUERDO_PAGO.IDTASA');
							}
								echo $this->datatables->generate();
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/tasaspago');
									 }
						}else
						{
							redirect(base_url().'index.php/auth/login');
						}           
		}

		function datatablearchivos(){
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/manage'))
							 {
							 
							 if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/edit'))
							 {
								$ID =  $this->uri->segment(3);

								$this->load->library('datatables');
								$this->datatables->select('ARCHIVOSESTRUCTURA.COD_ARCHIVO,ARCHIVOSESTRUCTURA.NOMBRE_ARCHIVO, E.NOMBREESTADO');
								$this->datatables->from('ARCHIVOSESTRUCTURA'); 
								$this->datatables->join('ESTADOS E','E.IDESTADO = ARCHIVOSESTRUCTURA.COD_ESTADO', ' inner ');
								$array = array('ARCHIVOSESTRUCTURA.COD_ESTRUCTURA' => $ID,
												'ARCHIVOSESTRUCTURA.COD_ESTADO' => '1');
								$this->datatables->where($array);
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="'.base_url().'index.php/admestructuras/editarchivo/$1" class="btn btn-small" title="Archivos"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'ARCHIVOSESTRUCTURA.COD_ARCHIVO');
								$this->datatables->add_column('identificacion', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="'.base_url().'index.php/admestructuras/campos/$1" class="btn btn-small" title="Campos Identificacion"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'ARCHIVOSESTRUCTURA.COD_ARCHIVO');
								$this->datatables->add_column('campos', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="'.base_url().'index.php/admestructuras/camposarchivo/$1" class="btn btn-small" title="Campos Archivo"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'ARCHIVOSESTRUCTURA.COD_ARCHIVO');

							}else{
								$this->load->library('datatables');
								$this->datatables->select('TASA_ACUERDO_PAGO.IDTASA,C.NOMBRECONCEPTO, TASA_ACUERDO_PAGO.VALORTASA,E.NOMBREESTADO');
								$this->datatables->from('TASA_ACUERDO_PAGO'); 
								$this->datatables->join('ESTADOS E','E.IDESTADO = TASA_ACUERDO_PAGO.IDESTADO', ' inner ');
								$this->datatables->join('CONCEPTO C','C.IDCONCEPTO = TASA_ACUERDO_PAGO.IDCONCEPTO', ' inner ');
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'TASA_ACUERDO_PAGO.IDTASA');
							}
								echo $this->datatables->generate();
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/tasaspago');
									 }
						}else
						{
							redirect(base_url().'index.php/auth/login');
						}           
		}

		function datatablecamposarchivos(){
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/manage'))
							 {
							 
							 if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/edit'))
							 {
								$ID =  $this->uri->segment(3);

								$this->load->library('datatables');
								$this->datatables->select('CA.COD_CAMPOARCHIVO,CA.NOMBRE_CAMPO, T.NOMBRE_TIPO ,CA.POSICION, CA.LONGITUD,  CA.ALINEACION, CA.CARACTER_RELLENO, CA.TIPO_DATO,  CA.SEPARADOR_DECIMALES, CA.FORMATO, CA.VISTA_CONSULTA');
								$this->datatables->from('CAMPOSARCHIVO CA'); 
								$this->datatables->join('TIPOREGISTRO T','T.COD_TIPOREGISTRO = CA.COD_TIPOREGISTRO', ' inner ');
								$this->datatables->where('CA.COD_ARCHIVO', $ID);
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="'.base_url().'index.php/admestructuras/editarcampoarch/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'CA.COD_CAMPOARCHIVO');

							}else{
								$this->load->library('datatables');
								$this->datatables->select('TASA_ACUERDO_PAGO.IDTASA,C.NOMBRECONCEPTO, TASA_ACUERDO_PAGO.VALORTASA,E.NOMBREESTADO');
								$this->datatables->from('TASA_ACUERDO_PAGO'); 
								$this->datatables->join('ESTADOS E','E.IDESTADO = TASA_ACUERDO_PAGO.IDESTADO', ' inner ');
								$this->datatables->join('CONCEPTO C','C.IDCONCEPTO = TASA_ACUERDO_PAGO.IDCONCEPTO', ' inner ');
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'TASA_ACUERDO_PAGO.IDTASA');
							}
								echo $this->datatables->generate();
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/tasaspago');
									 }
						}else
						{
							redirect(base_url().'index.php/auth/login');
						}           
		}


		function datatablecamposidentificacion(){
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/manage'))
							 {
							 
							 if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/edit'))
							 {
								$ID =  $this->uri->segment(3);

								$this->load->library('datatables');
								$this->datatables->select('CA.COD_CAMPOS_IDENT,CA.NOMBRE_CAMPO, CA.POSICION, CA.DESCRIPCION, CA.LONGITUD, CA.CARACTER_RELLENO, CA.FORMATO, CA.CONSTANTE');
								$this->datatables->from('CAMPOS_IDENTIFICACION_ARCHIVO CA'); 
								$this->datatables->where('CA.COD_ARCHIVO', $ID);
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="'.base_url().'index.php/admestructuras/editarcampo/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'CA.COD_CAMPOS_IDENT');

							}else{
								$this->load->library('datatables');
								$this->datatables->select('TASA_ACUERDO_PAGO.IDTASA,C.NOMBRECONCEPTO, TASA_ACUERDO_PAGO.VALORTASA,E.NOMBREESTADO');
								$this->datatables->from('TASA_ACUERDO_PAGO'); 
								$this->datatables->join('ESTADOS E','E.IDESTADO = TASA_ACUERDO_PAGO.IDESTADO', ' inner ');
								$this->datatables->join('CONCEPTO C','C.IDCONCEPTO = TASA_ACUERDO_PAGO.IDCONCEPTO', ' inner ');
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'TASA_ACUERDO_PAGO.IDTASA');
							}
								echo $this->datatables->generate();
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/tasaspago');
									 }
						}else
						{
							redirect(base_url().'index.php/auth/login');
						}           
		}

}


/* End of file tasaspago.php */
/* Location: ./system/application/controllers/tasaspago.php */