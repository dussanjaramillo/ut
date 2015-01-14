<?php

class Cnm_seguros extends MY_Controller {

		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('cnm_seguros_model','',TRUE); 
		
				$this->data['javascripts'] = array(

            'js/tinymce/tinymce.jquery.min.js',
			'js/jquery.dataTables.min.js',
        	'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/validateForm.js',
        	'js/ajaxfileupload.js'
        	
                    );
                $this->data['style_sheets']= array(

                        'css/jquery.dataTables_themeroller.css' => 'screen',
				        'css/validationEngine.jquery.css' => 'screen'
                        
                );
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
if(!empty($flag)){
	redirect(base_url().'index.php/cnm_seguros/detalle/'.$this->input->post('tipo_seguro_id')); 

		}
else{

								}									
								$this->template->set('title', 'Tasas de Seguros');
								$this->data['message']=$this->session->flashdata('message');
								
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen'
												);
								$this->data['javascripts']= array(
														'js/jquery.dataTables.min.js',
												); 
								$flag=$this->input->post('vista_flag');
										if(!empty($flag)){
											
										$this->tipo_seguro_id=$this->input->post('tipo_seguro_id');	
								if($this->tipo_seguro_id==12)
								{
									
									
									
								}

				}				
								$this->data['seguro']  = $this->cnm_seguros_model->selectSeguro();
								$this->template->load($this->template_file, 'cnm_seguros/cnm_seguros_list', $this->data);
								
								
								
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
											$this->id_concepto=$this->input->post('id_concepto');	
											$this->ini_vigencia=$this->input->post('ini_vigencia');	


								$carteraexist=$this->cnm_seguros_model->verificarExistenciaNueva($this->id_concepto, $this->ini_vigencia);
								if($carteraexist['EXIST']==0){	

										$this->form_validation->set_rules('concepto', 'Concepto', 'required|trim|xss_clean|max_length[128]');
												if ($this->form_validation->run() == false)
										{
												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{
												
												
											
																						
											$this->cnm_seguros_model->updateSeguro('TASAS_SEGUROS',$this->input->post('ini_vigencia'),$this->id_concepto);

												$date = array(
															'FECHA_INI_VIGENCIA' => $this->input->post('ini_vigencia'),
												);
												$data = array(
																'ID_ASEGURADORA' => $this->input->post('id_aseguradora'),
																'ASEGURADORA' => $this->input->post('nombre_aseguradora'),
																'VALOR_TASA' => $this->input->post('tasa'),
																'CONCEPTO' => $this->input->post('id_concepto'),
																																
												);

									if ($this->cnm_seguros_model->add('TASAS_SEGUROS',$data,$date) == TRUE)
									{
											
										if ($this->cnm_seguros_model->borrarCuotasSeg($this->input->post('id_concepto')) == TRUE)
									{
										$id_deudas=$this->cnm_seguros_model->seleccionarDeudasActivas();
										
										foreach ($id_deudas->result_array as $value) {
					$dbuser = $this->db->username;
					$dbpassword = $this->db->password;
					$dbConnString = $this->db->hostname;
		

					$v_oDataConn = oci_connect($dbuser, $dbpassword, $dbConnString);
					if (!$v_oDataConn) {
							$v_oErroCntr = oci_error();
							trigger_error(htmlentities($v_oErroCntr['message'], ENT_QUOTES), E_USER_ERROR);
					}
					if($this->input->post('id_concepto')==12){
					//Seguro de incendio
					$query = "BEGIN PKG_CARTERA_NO_MISIONAL.Crea_Cobro_Incendio(".$value["COD_CARTERA_NOMISIONAL"].", null, null, :pio_Mensaje); END;";
					
					$pio_Mensaje = "";
					$v_oStnd_Out = oci_parse($v_oDataConn, $query) or die('Can not parse query');
					//echo "id_deuda ".$this->DatosBase['COD_CARTERA_NOMISIONAL']." porcentaje ".$porcentaje." avaluo ".$avaluo." query ".$query;
					//die();
					oci_bind_by_name($v_oStnd_Out, ":pio_Mensaje", $pio_Mensaje, 32000) or die('Can not bind variable');
					oci_execute($v_oStnd_Out);
					if(!empty($pio_Mensaje))
					{
					oci_close($v_oDataConn);	
					echo $pio_Mensaje;
					die();
					}
					}else{
					//Seguro de vida
					$query = "BEGIN PKG_CARTERA_NO_MISIONAL.Crea_Cobro_Vida(".$value["COD_CARTERA_NOMISIONAL"].", null, :pio_MensajeVida); END;";
					$pio_MensajeVida = "";
					$v_oStnd_Out = oci_parse($v_oDataConn, $query) or die('Can not parse query');
					oci_bind_by_name($v_oStnd_Out, ":pio_MensajeVida", $pio_MensajeVida, 32000) or die('Can not bind variable');
					oci_execute($v_oStnd_Out);
					if(!empty($pio_MensajeVida))
					{oci_close($v_oDataConn);
					echo $pio_MensajeVida;
					die();
					}
					}
					oci_close($v_oDataConn);

										
										}
										

										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Seguro se agregó y se liquido en las deudas de crédito hipotecario con éxito.</div>');
													redirect(base_url().'index.php/cnm_seguros');
									}
									else
									{
										$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
									}	
										
										
										
									}
									else
									{
										$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

									}
								}

							}
else{
	$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ya existe una tasa mas actualizada.</div>');
													redirect(base_url().'index.php/');
	
}		

				}
$this->data['concepto']=$this->input->post('tipo_cartera');
$this->data['concepto_nombre']=$this->input->post('nombre_cartera');

										$this->load->view('cnm_seguros/cnm_seguros_add', $this->data);
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/');
									 }
							 }
						else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}


	function detalle($cod_concepto){
					if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_tipo_tasa_interes/add'))
							 {
	$this->data['id_concepto'] = $cod_concepto;	
	$this->data['segurodet'] = $this->cnm_seguros_model->selectDetalleSeguro($this->data['id_concepto'])->result_array();
	
	/*var_dump($this->data['seguro']);
	die();*/
	
	$this->template->load($this->template_file, 'cnm_seguros/cnm_seguros_detalle', $this->data);
											
											
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