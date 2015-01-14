<?php

class Cnm_ac_variables extends MY_Controller {

		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('cnm_ac_variables_model','',TRUE); 
	}

	function index(){
		$this->manage();
	}

	function manage(){ 
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_tipo_tasa_interes/list'))
							 {
							 	$cod_componente=$this->input->post('cod_acuerdo');
								
$flag=$this->input->post('vista_flag');
if(!empty($flag)){}
else{
	$this->session->set_flashdata('message', '');
								}									
								$this->template->set('title', 'Componentes');
								$this->data['message']=$this->session->flashdata('message');
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen'
												);
								$this->data['javascripts']= array(
														'js/jquery.dataTables.min.js',
														'js/jquery.dataTables.defaults.js'
												); 
												

								$this->data['variable']  = $this->cnm_ac_variables_model->selectVariables($cod_componente);
								$this->data['cod_componente']  = $cod_componente;
																
								$this->load->view('cnm_ac_variables/cnm_ac_variables_list', $this->data);
								
								
								
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
											$this->cod_Comp=$this->input->post('cod_ac_var');
											
										
										$this->load->library('form_validation'); //por seguridad SIEMPRE! realizar validaciones del lado del servidor para todos los campos en los formularios y siempre usar validaciones del core de codeigniter
										$this->data['custom_error'] = ''; 
										
										$flag=$this->input->post('vista_flag');
										if(!empty($flag)){
											$this->var_id=$this->input->post('var_esp_id');	



								$carteraexist=$this->cnm_ac_variables_model->verificarExistenciaNueva($this->cod_Comp, $this->var_id);
							//echo $carteraexist['EXIST'];
							//die();
								if($carteraexist['EXIST']==0){	
											
											
										$this->form_validation->set_rules('maximo', 'Máximo', 'required|trim|xss_clean|max_length[128]');
										$this->form_validation->set_rules('minimo', 'Minimo', 'required|trim|xss_clean|max_length[128]');
												if ($this->form_validation->run() == false)
										{
												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{
												$date = array(
												'FECHA_CREACION' =>  date("d/m/Y  H:i"),
												);
												$data = array(
																'MINIMO' => $this->input->post('minimo'),
																'MAXIMO' => $this->input->post('maximo'),
																'COD_VARIABLE_ESP' => $this->var_id,
																'COD_COMPONENTE' => $this->cod_Comp,																																
												);

									if ($this->cnm_ac_variables_model->add('NM_VARIABLE',$data,$date) == TRUE)
									{
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La variable se agregó con éxito.</div>');
													redirect(base_url().'index.php/cnm_acuerdo_ley/');
									}
									else
									{
										$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

									}
								}

							}
else{
	$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ya existe una variable del mismo tipo para este componente</div>');
													redirect(base_url().'index.php/cnm_acuerdo_ley/');
	
}		

				}
										$this->data['style_sheets']= array(
														'css/chosen.css' => 'screen'
												);
										$this->data['javascripts']= array(
														'js/chosen.jquery.min.js'
												);
										$this->data['cod_Comp']  = $this->cod_Comp;
										$this->data['var_esp']  = $this->cnm_ac_variables_model->selectVarEsp();
										//var_dump($this->data['var_esp']);		
												//die();
										$this->template->load($this->template_file, 'cnm_ac_variables/cnm_ac_variables_add', $this->data);
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
										//echo $cod_acuerdo;
										//die();
										$this->data['style_sheets']= array(
														'css/chosen.css' => 'screen'
												);
										$this->data['javascripts']= array(
														'js/chosen.jquery.min.js'
												);
												
										$this->data['tipocart']  = $this->cnm_acuerdo_ley_model->selectTipoCartera();
										
										//var_dump($this->data['tipoPeriodo']);
										//die();		
										$this->data['acuerdo']  = $this->cnm_acuerdo_ley_model->selectAcuerdoEsp($cod_acuerdo);	
										//echo($this->data['acuerdo'] ["NOMBRE_ACUERDO"]);
										//die();
										
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
										$this->cod_var2=$this->input->post('cod_var');
												
												$data = array(
                                    				'ELIMINADO' => '1',
													'COD_VARIABLE' => $this->cod_var2
				                            	);
				           				$this->cnm_ac_variables_model->delete('NM_VARIABLE',$data);


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