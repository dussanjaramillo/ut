<?php

class Cnm_acuerdo_ley extends MY_Controller {

		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('cnm_acuerdo_ley_model','',TRUE); 
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
								$this->template->set('title', 'Acuerdo de Ley');
								$this->data['message']=$this->session->flashdata('message');
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen'
												);
								$this->data['javascripts']= array(
														'js/jquery.dataTables.min.js',
														'js/jquery.dataTables.defaults.js'
												); 
												
								$this->data['tipos']  = $this->cnm_acuerdo_ley_model->selectAcuerdoLey();
								//var_dump($this->data['tipos']);
								//die();	
																
								$this->template->load($this->template_file, 'cnm_acuerdo_ley/cnm_acuerdo_ley_list', $this->data);
								
								
								
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
											
										$this->nombre=mb_strtoupper($this->input->post('nombre'));		
											$this->fecha=$this->input->post('fecha_ini');
											$this->tipo_cartera_id=	$this->input->post('tipo_cartera_id');
											//echo $this->fecha;
											//die();
								$carteraexist=$this->cnm_acuerdo_ley_model->verificarExistenciaNueva($this->tipo_cartera_id, $this->fecha);
								
								if($carteraexist['EXIST']==0){	
											
											
										$this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
										$this->form_validation->set_rules('fecha_ini', 'Fecha Vigencia', 'required');
												if ($this->form_validation->run() == false)
										{
												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{
												$date = array(
																'FECHA_INI_VIGENCIA' => $this->input->post('fecha_ini'),
																'PERIODO_INCREMENTO' => $this->input->post('periodo_inc'),
												);
												$data = array(
																'NOMBRE_ACUERDO' => mb_strtoupper($this->input->post('nombre')),
																'COD_TIPOCARTERA' => $this->input->post('tipo_cartera_id'),
																'TIPO_VINCULACION' => $this->input->post('tipo_vinc'),
																'PLAZO_MAX_REFINANC' => $this->input->post('plazo_max_ref'),
																'MONTO_MAXIMO' => str_replace(".", "", $this->input->post('monto_max')),
																'PLAZO_MAXIMO' => $this->input->post('plazo_max'),
																'MANEJO_GRACIA' => $this->input->post('man_gracia'),
																'DURACION_GRACIA' => $this->input->post('select_duracion'),
																'PERIODO_GRACIA' => $this->input->post('dur'),
																'INCREMENTO_CUOTA' => $this->input->post('incr_cuota'),
																'PORCENT_INCREMENTO' => $this->input->post('porcent_inc'),
																'ABONO_CESANTIAS' => $this->input->post('abono_ces'),

																
												);

									if ($this->cnm_acuerdo_ley_model->add('NM_ACUERDOLEY',$data,$date) == TRUE)
									{
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo de Ley se ha creado con éxito.</div>');
													redirect(base_url().'index.php/cnm_acuerdo_ley/');
									}
									else
									{
										$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

									}
								}

							}
else{
	$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ya existe un acuerdo de ley en una fecha posterior para el concepto seleccionado no se puede agregar un nuevo acuerdo</div>');
													redirect(base_url().'index.php/cnm_acuerdo_ley/');
	
}		

				}

										$this->data['tipocart']  = $this->cnm_acuerdo_ley_model->selectTipoCartera();
										
										//var_dump($this->data['tipoPeriodo']);
										//die();		
												
										$this->template->load($this->template_file, 'cnm_acuerdo_ley/cnm_acuerdo_ley_add', $this->data);
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/cnm_acuerdo_ley');
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_tipo_tasa_interes/add'))
							 {
										$this->load->library('form_validation'); //por seguridad SIEMPRE! realizar validaciones del lado del servidor para todos los campos en los formularios y siempre usar validaciones del core de codeigniter
										$this->data['custom_error'] = ''; 
										
										$flag=$this->input->post('vista_flag');
										if(!empty($flag)){
											
										$this->nombre=$this->input->post('nombre');	
											$this->fecha=$this->input->post('fecha_ini');
											$this->tipo_cartera_id=	$this->input->post('tipo_cartera_id');
											//echo $this->fecha;
											//die();
								$carteraexist=$this->cnm_acuerdo_ley_model->verificarExistenciaNueva($this->tipo_cartera_id, $this->fecha);
								
								if($carteraexist['EXIST']==0){	
											
											
										$this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
												if ($this->form_validation->run() == false)
										{
												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{
												$date = array(
																'FECHA_INI_VIGENCIA' => $this->input->post('fecha_ini'),
												);
												$data = array(
																'NOMBRE_ACUERDO' => mb_strtoupper($this->input->post('nombre')),
																'COD_TIPOCARTERA' => $this->input->post('tipo_cartera_id'),
																'TIPO_VINCULACION' => $this->input->post('tipo_vinc'),
																'TIPO_CUOTA' => $this->input->post('tipo_cuota'),
																'PORC_REFINANCIACION' => $this->input->post('porc_ref'),
																'PLAZO_MAX_REFINANC' => $this->input->post('plazo_max_ref'),
																'FORMA_APLI_PAGO' => $this->input->post('forma_ap_pago'),
																'MONTO_MAXIMO' => $this->input->post('monto_max'),
																'PLAZO_MAXIMO' => $this->input->post('plazo_max'),
																'MANEJO_GRACIA' => $this->input->post('man_gracia'),
																'DURACION_GRACIA' => $this->input->post('dur'),
																'INCREMENTO_CUOTA' => $this->input->post('incr_cuota'),
																'PERIODO_INCREMENTO' => $this->input->post('periodo_inc'),
																'ABONO_CESANTIAS' => $this->input->post('abono_ces'),
																'PORCE_ABONO_CESANT' => $this->input->post('porcent_abono_ces'),
																'ABONO_PRIMAS' => $this->input->post('abono_prim'),
																'PORCEN_ABONA_PRIMA' => $this->input->post('porcent_abono_prim'),
																
																
												);

									if ($this->cnm_acuerdo_ley_model->update('NM_ACUERDOLEY',$data,$date) == TRUE)
									{
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo de Ley se ha creado con éxito.</div>');
													redirect(base_url().'index.php/cnm_acuerdo_ley/');
									}
									else
									{
										$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

									}
								}

							}
else{
	$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ya existe un acuerdo de ley en una fecha posterior para el concepto seleccionado no se puede agregar un nuevo acuerdo</div>');
													redirect(base_url().'index.php/cnm_acuerdo_ley/');
	
}		

				}
										$cod_acuerdo=$this->input->post('cod_acuerdo');

												
										$this->data['tipocart']  = $this->cnm_acuerdo_ley_model->selectTipoCartera();
										
										$this->data['acuerdo']  = $this->cnm_acuerdo_ley_model->selectAcuerdoEsp($cod_acuerdo);	

										
										$this->template->load($this->template_file, 'cnm_acuerdo_ley/cnm_acuerdo_ley_edit', $this->data);
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/cnm_acuerdo_ley');
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