<?php

class Cnm_ac_componentes extends MY_Controller {

		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('cnm_ac_componentes_model','',TRUE); 
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
								$this->template->set('title', 'Componentes');
								$this->data['message']=$this->session->flashdata('message');
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen'
												);
								$this->data['javascripts']= array(
														'js/jquery.dataTables.min.js',
														'js/jquery.dataTables.defaults.js'
												); 
								$this->componente=$this->input->post('cod_ac_componente');
								$this->data['tipo_tasa']  = $this->cnm_ac_componentes_model->select_tipo_tasa();
								
								foreach ($this->data['tipo_tasa']->result_array as $data) {
								$this->data['tipo_tasa_resp'] [$data["COD_TIPO_TASAINTERES"]]=$data["NOMBRE_TASAINTERES"];
									}	
								
								$this->data['tipo_tasa_h']  = $this->cnm_ac_componentes_model->select_tipo_tasa_h();
								
								
								foreach ($this->data['tipo_tasa_h']->result_array as $data) {
								$this->data['tipo_tasa_h_resp'] [$data["COD_TIPO_TASA_HIST"]]=$data["NOMBRE_TIPOTASA"];
									}	
								
								
								$this->data['tipos']  = $this->cnm_ac_componentes_model->selectComponentes($this->componente);
								$this->data['componente'] = $this->componente;
								$this->data['infoac']  = $this->cnm_ac_componentes_model->selectInfoAcuerdo($this->componente);
								//var_dump($this->data['infoac']);
								//die();	
																
								$this->template->load($this->template_file, 'cnm_ac_componentes/cnm_ac_componentes_list', $this->data);
								
								
								
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
											$this->cod_Ac=$this->input->post('cod_ac_comp');
													
								
											
											
										$this->load->library('form_validation'); //por seguridad SIEMPRE! realizar validaciones del lado del servidor para todos los campos en los formularios y siempre usar validaciones del core de codeigniter
										$this->data['custom_error'] = ''; 
										
										$flag=$this->input->post('vista_flag');
										if(!empty($flag)){
											$this->comp_esp_id=$this->input->post('comp_esp_id');	
										$this->comp_aplica_id=$this->input->post('comp_aplica_id');	
										$this->f_interes_id=$this->input->post('f_interes_id');	
										$this->calculo_id=$this->input->post('calculo_id');	
										$this->tipo_tasa_id=$this->input->post('tipo_tasa_id');	


								$carteraexist=$this->cnm_ac_componentes_model->verificarExistenciaNueva($this->cod_Ac, $this->comp_esp_id, $this->comp_aplica_id, $this->f_interes_id, $this->calculo_id, $this->tipo_tasa_id);
							
								if($carteraexist['EXIST']==0){	
											
											
										$this->form_validation->set_rules('comp_esp_id', 'Nombre', 'required|trim|xss_clean|max_length[128]');
												if ($this->form_validation->run() == false)
										{
												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{
												$date = array(
												
												);
												$data = array(
																'COD_ACUERDOLEY' => $this->input->post('cod_ac_comp'),
																'APLICAR_A' => $this->comp_aplica_id,
																'FORMULA_INTERES' => $this->f_interes_id,
																'CALCULO' => $this->calculo_id,
																'TIPO_TASA' => $this->tipo_tasa_id,
																'VALOR' => $this->input->post('valor'),
																'PUNTOS_ADICIONALES' => $this->input->post('puntos_add'),
																'COD_COMPONENTE_ESP' => $this->comp_esp_id,
																																
												);

									if ($this->cnm_ac_componentes_model->add('NM_COMPONENTE',$data,$date) == TRUE)
									{
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Componente se agregó con éxito.</div>');
													redirect(base_url().'index.php/cnm_acuerdo_ley/');
									}
									else
									{
										$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

									}
								}

							}
else{
	$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ya existe un componente del mismo tipo para este acuerdo de ley</div>');
													redirect(base_url().'index.php/cnm_acuerdo_ley/');
	
}		

				}

										
										$this->data['componente']  = $this->cod_Ac;	
										
										$this->data['infoac']  = $this->cnm_ac_componentes_model->selectInfoAcuerdo($this->cod_Ac);

										$this->data['comp_esp']  = $this->cnm_ac_componentes_model->selectComponenteEsp();
										$this->data['comp_aplic']  = $this->cnm_ac_componentes_model->selectAplicaa();
										$this->data['f_interes']  = $this->cnm_ac_componentes_model->select_f_interes();
										$this->data['calculo']  = $this->cnm_ac_componentes_model->select_calculo();
										$this->data['tipo_tasa'] = $this->cnm_ac_componentes_model->select_tipo_tasa();
												
										$this->template->load($this->template_file, 'cnm_ac_componentes/cnm_ac_componentes_add', $this->data);
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


		 function get_tipo_tasa($calculo_id){
if($calculo_id==1){
$tipo_tasa = $this->cnm_ac_componentes_model->select_tipo_tasa();

foreach ($tipo_tasa->result_array as $data) {
	$tipo_tasa_resp[$data["COD_TIPO_TASAINTERES"]]=$data["NOMBRE_TASAINTERES"];
}	

print_r(json_encode($tipo_tasa_resp));
}
elseif($calculo_id==2) {
$tipo_tasa = $this->cnm_ac_componentes_model->select_tipo_tasa_h();

foreach ($tipo_tasa->result_array as $data) {
	$tipo_tasa_resp[$data["COD_TIPO_TASA_HIST"]]=$data["NOMBRE_TIPOTASA"];
}	

print_r(json_encode($tipo_tasa_resp));

}
else{
	
	$tipo_tasa_resp[""]="";
print_r(json_encode($tipo_tasa_resp));	
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
										$this->cod_componente=$this->input->post('cod_componente');
																					
											 $data = array(
                                    				'ELIMINADO' => '1',
													'COD_COMPONENTE' => $this->cod_componente
				                            	);
				           				$this->cnm_ac_componentes_model->delete('NM_COMPONENTE',$data);


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