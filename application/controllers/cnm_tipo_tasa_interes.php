<?php

class Cnm_tipo_tasa_interes extends MY_Controller {

		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('cnm_tipo_tasa_interes_model','',TRUE); 
	}

	function index(){
		$this->manage();
	}

	function manage(){ 
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_tipo_tasa_interes/list'))
							 {
$flag=$this->input->post('vista_flag');
if(!empty($flag)){}
else{
	$this->session->set_flashdata('message', '');
								}									
								$this->template->set('title', 'Tipo Tasa Interes');
								$this->data['message']=$this->session->flashdata('message');
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen'
												);
								$this->data['javascripts']= array(
														'js/jquery.dataTables.min.js',
												); 
												
								$this->data['tipos']  = $this->cnm_tipo_tasa_interes_model->selectTipoTasaInteres();
								//var_dump($this->data['tipos']);
								//die();	
																
								$this->template->load($this->template_file, 'cnm_tipo_tasa_interes/cnm_tipo_tasa_interes_list', $this->data);
								
								
								
							 }else { //usar siempre bootstrap 2.3 para mensajes, formulario y estilos en general, como alternativa que también se puede usar está jquery ui
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_tipo_tasa_interes/add'))
							 {
										$this->load->library('form_validation'); //por seguridad SIEMPRE! realizar validaciones del lado del servidor para todos los campos en los formularios y siempre usar validaciones del core de codeigniter
										$this->data['custom_error'] = ''; 
										
										$flag=$this->input->post('vista_flag');
										if(!empty($flag)){
											
										$this->nombre=$this->input->post('nombre');	
								$carteraexist=$this->cnm_tipo_tasa_interes_model->verificarExistenciaNueva($this->nombre);

								if($carteraexist['EXIST']==0){	
											
											
										$this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
												if ($this->form_validation->run() == false)
										{
												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{
												$date = array(
															
												);
												$data = array(
																'COD_TIPOTASAINTERES' => $this->input->post('tipo_id'),
																'COD_TIPOPERIODO' => $this->input->post('periodo_id'),
																'COD_FORMAPAGO_INTERESES' => $this->input->post('forma_pago_id'),
																'NOMBRE_TASAINTERES' => mb_strtoupper($this->input->post('nombre')),
																
												);

									if ($this->cnm_tipo_tasa_interes_model->add('TIPOTASAINTERES',$data,$date) == TRUE)
									{
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Tipo de Tasa de Interés se ha creado con éxito.</div>');
													redirect(base_url().'index.php/cnm_tipo_tasa_interes/');
									}
									else
									{
										$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

									}
								}

							}
else{
	$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>El Tipo de Tasa de Interés ya se encuentra registrado.</div>');
													redirect(base_url().'index.php/cnm_tipo_tasa_interes/');
	
}		

				}

												
										$this->data['formaPago']  = $this->cnm_tipo_tasa_interes_model->selectFormaPago();
										
										$this->data['periodoLiq']  = $this->cnm_tipo_tasa_interes_model->selectPeriodoLiq();
										
										$this->data['tipoPeriodo']  = $this->cnm_tipo_tasa_interes_model->selectTipoPeriodo();
										
										$this->data['nombre']  = "E.A.V.";
										
										
										//var_dump($this->data['tipoPeriodo']);
										//die();		
												
										$this->template->load($this->template_file, 'cnm_tipo_tasa_interes/cnm_tipo_tasa_interes_add', $this->data);
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/cnm_tipocartera');
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_tipocartera/add'))
							 {
							 	
								$this->cod_tipo_tasa=$this->input->post('cod_tipo_tasa');	
										
										$this->data['custom_error'] = ''; 
										$flag=$this->input->post('vista_flag');
if(!empty($flag)){	
										
								$this->nombre=$this->input->post('nombre');	
								$carteraexist=$this->cnm_tipocartera_model->verificarExistencia($this->nombre, $this->cod_cartera);
								
								if($carteraexist['EXIST']==0){
																			
										$this->load->library('form_validation'); //por seguridad SIEMPRE! realizar validaciones del lado del servidor para todos los campos en los formularios y siempre usar validaciones del core de codeigniter
										
										$this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
												if ($this->form_validation->run() == false)
										{
												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{
												
												$data = array(
																'COD_TIPOCARTERA' => $this->input->post('cod_cartera'),
																'NOMBRE_CARTERA' => mb_strtoupper($this->nombre),
																'COD_ESTADO' => $this->input->post('tipo_id')
												);
									
									
									if ($this->cnm_tipocartera_model->editar('TIPOCARTERA',$data) == TRUE)
									{
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Tipo de Cartera se ha actualizado con éxito.</div>');
													redirect(base_url().'index.php/cnm_tipocartera/');
									}
									else
									{
										$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

									}
								}
										
							}
							
else{
	$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>El Tipo de Cartera Ya existe no se puede actualizar.</div>');
													redirect(base_url().'index.php/cnm_tipocartera/');
	
}
										
					}
						
else{										

										$this->data['datos_edit']=$this->cnm_tipo_tasa_interes_model->selectTipoTasaEd($this->cod_tipo_tasa);
										var_dump($this->data['datos_edit']);
										die();
										
										$this->load->view('cnm_tipocartera/cnm_tipocartera_edit', $this->data);
										}

								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/cnm_tipocartera');
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_tipo_tasa_interes/delete'))
							 {
										$this->cod_tipo_tasa=$this->input->post('cod_tipo_tasa');
																					
											 $data = array(
                                    				'ELIMINADO' => '1',
													'COD_TIPO_TASAINTERES' => $this->cod_tipo_tasa
				                            	);
				           				$this->cnm_tipo_tasa_interes_model->delete('TIPOTASAINTERES',$data);


								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/bancos');
									 }
					}else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}



}