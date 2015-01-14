<?php

class Cnm_rango_tipo_tasa_interes_historica extends MY_Controller {

		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('cnm_rango_tipo_tasa_interes_historica_model','',TRUE); 
	}

	function index(){
		$this->manage();
	}

	function manage(){ 
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_rango_tipo_tasa_interes_historica/list'))
							 {
$cod_tasa_hist=$this->input->post('cod_tasa_hist');	
//echo $cod_tasa_hist;
//die();						 	
$flag=$this->input->post('vista_flag');
if(!empty($flag)){}
else{
	$this->session->set_flashdata('message', '');
								}									
								$this->template->set('title', 'Rango Tipo Tasa Interes Historica');
								$this->data['message']=$this->session->flashdata('message');
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen'
												);
								$this->data['javascripts']= array(
														'js/jquery.dataTables.min.js',
														'js/jquery.dataTables.defaults.js'
												); 
												
								$this->data['tipos']  = $this->cnm_rango_tipo_tasa_interes_historica_model->selectRangoTasaInteres($cod_tasa_hist);
								$this->data['cod_tasa_hist']  = $cod_tasa_hist;
								$this->data['informacion']  = $this->cnm_rango_tipo_tasa_interes_historica_model->selectInformacion($cod_tasa_hist);
								//var_dump($this->data['informacion'] );
								//die();	
																
								$this->template->load($this->template_file, 'cnm_rango_tipo_tasa_interes_historica/cnm_rango_tipo_tasa_interes_historica_list', $this->data);
								
								
								
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
										$cod_tasa_hist=$this->input->post('cod_tasa_historica');	
										
										$fecha_ini=$this->input->post('fecha_inicial');	
										$fecha_fin=$this->input->post('fecha_final');	
										$valor_tasa=$this->input->post('valor_tasa');
										$this->load->library('form_validation'); //por seguridad SIEMPRE! realizar validaciones del lado del servidor para todos los campos en los formularios y siempre usar validaciones del core de codeigniter
										$this->data['custom_error'] = ''; 
										
										$flag=$this->input->post('vista_flag');
										if(!empty($flag)){
											
								$carteraexist=$this->cnm_rango_tipo_tasa_interes_historica_model->verificarExistencia($fecha_ini,$fecha_fin, $cod_tasa_hist);

								if($carteraexist['EXIST']==0){	
											
										        			$this->form_validation->set_rules('valor_tasa', 'Valor Tasa', 'required');

        													if ($this->form_validation->run() == false)
										{
												$this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{
												
												$date = array(
															'FECHA_INI_VIGENCIA' => $fecha_ini,
															'FECHA_FIN_VIGENCIA' => $fecha_fin,
												);
												$data = array(

																'COD_TIPO_TASA_HIST' => $cod_tasa_hist,
																'VALOR_TASA' => $valor_tasa,
																
												);

									if ($this->cnm_rango_tipo_tasa_interes_historica_model->add('RANGOSTASAHISTORICA',$data,$date) == TRUE)
									{
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El rango se ha añadido con éxito.</div>');
													redirect(base_url().'index.php/cnm_tipo_tasa_interes_historic/');
									}
									else
									{
										$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

									}
								}

							}
else{
	$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>El Rango o alguna de las fechas ya se encuentran registrados para esta tasa, no es posible registrarlo nuevamente.</div>');
													redirect(base_url().'index.php/cnm_tipo_tasa_interes_historic/');
	
}		

				}
										$this->data['style_sheets']= array(
														'css/chosen.css' => 'screen'
												);
										$this->data['javascripts']= array(
														'js/chosen.jquery.min.js'
												);

										
										$this->data['informacion']  = $this->cnm_rango_tipo_tasa_interes_historica_model->selectInformacion($cod_tasa_hist);
										$this->data['cod_tasa_hist']  = $cod_tasa_hist;
										$this->load->view('cnm_rango_tipo_tasa_interes_historica/cnm_rango_tipo_tasa_interes_historica_add', $this->data);
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
										
										
										
										//add style an js files for inputs selects
										$this->data['style_sheets']= array(
														'css/chosen.css' => 'screen'
												);
										$this->data['javascripts']= array(
														'js/chosen.jquery.min.js'
												);
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
										$this->cod_rangotasa=$this->input->post('cod_rango_tasa');
										
											 
											 $data = array(
                                    				'ELIMINADO' => '1',
													'COD_RANGOTASA' => $this->cod_rangotasa
				                            	);
				           				$this->cnm_rango_tipo_tasa_interes_historica_model->delete('RANGOSTASAHISTORICA',$data);


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