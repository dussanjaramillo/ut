<?php

class Cnm_prestamo_hipotecario extends MY_Controller {
		
		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');		
		$this->load->helper(array('form','url','codegen_helper','template_helper','traza_fecha_helper'));
		$this->load->model('cnm_prestamo_hipotecario_model','',TRUE);
		 $this->load->file(APPPATH . "controllers/cnm_liquidacioncuotas.php", true);
		 
		 $this->data['javascripts'] = array(
            
            'js/tinymce/tinymce.jquery.min.js',
			'js/jquery.dataTables.min.js',
        	'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/validateForm.js',
        	
                    );
                $this->data['style_sheets']= array(
                        'css/jquery.dataTables_themeroller.css' => 'screen',
				        'css/validationEngine.jquery.css' => 'screen'
                        
                );
                
	}	


	
	
	
	
	var $nit;
	var $razon_social;
		

	
	function index(){
		$this->manage();
	}

	function manage(){
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/manage'))
							 {
	        
								         
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/add'))
							 {
					$this->template->set('title', 'Registro Cartera Crédito Hipotecario');

												
												$flag=$this->input->post('vista_flag');
										if(!empty($flag)){

											
										$this->form_validation->set_rules('cedula', 'Cedula', 'required');

												if ($this->form_validation->run() == false)
										{

												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{
date_default_timezone_set("America/Bogota");
					
						$mod_tasa_corr='S';
						$calc_corriente= $this->input->post('combo_tipo_tasa_corriente');
						switch($calc_corriente)
						{case '1':
						$tipo_t_f_corriente= $this->input->post('aplica_tasa_c');
						$tipo_t_v_corriente= "";
						$valor_t_corriente= $this->input->post('porcent_tasa_c');
							break;
							
						case '2':
						$tipo_t_f_corriente= "";
						$tipo_t_v_corriente= $this->input->post('aplica_tasa_c');
						$valor_t_corriente= "";
							break;
							
						default:
						$tipo_t_f_corriente= "";
						$tipo_t_v_corriente= "";
						$valor_t_corriente= "";

						break;		
						}
						

													
													
								$tasa_mora=$this->input->post('t_i_m');
												if(!empty($tasa_mora))
					{
						$mod_tasa_mora='S';		
						$calc_mora= $this->input->post('combo_tipo_tasa_mora');
						switch($calc_mora)
						{case '1':
						$tipo_t_f_mora= $this->input->post('aplica_tasa_m');
						$tipo_t_v_mora= "";
						$valor_t_mora= $this->input->post('porcent_tasa_m');
							break;
							
						case '2':
						$tipo_t_f_mora= "";
						$tipo_t_v_mora= $this->input->post('aplica_tasa_m');
						$valor_t_mora= "";
							break;
							
						default:
						$tipo_t_f_mora= "";
						$tipo_t_v_mora= "";
						$valor_t_mora= "";

						break;		
						}
						
						
					}
												else
													{
						$mod_tasa_mora='N';									
						$calc_mora= "";
						$tipo_t_f_mora= "";
						$tipo_t_v_mora= "";
						$valor_t_mora= "";
													}					

$tasa_seg=$this->input->post('t_i_s');
												if(!empty($tasa_seg))
					{
						$calc_seg=$this->input->post('tipo_tasa_seg');
						$tasa_f_s="";
						$tasa_v_s="";
						$mod_tasa_seg='S';		

						switch($calc_seg)
						{case '1':
						$tasa_f_s= $this->input->post('tasa_esp_seg');
						$tasa_v_s= "";
						$valor_t_seg= $this->input->post('valor_tasa_seg');
							break;
							
						case '2':
						$tasa_f_s= "";
						$tasa_v_s= $this->input->post('tasa_esp_seg');
						$valor_t_seg= "";
							break;
							
						default:
						$tasa_f_s= "";
						$tasa_v_s= "";
						$valor_t_seg= "";

						break;		
						}
						
						
					}
												else
													{
						$mod_tasa_seg='N';									
						$calc_seg= "";
						$tasa_f_s= "";
						$tasa_v_s= "";
						$valor_t_seg= "";
													}				




												
																								$datecnm = array(
												'FECHA_CREACION' =>  date("d/m/Y H:i:s"),
												'FECHA_INCREMENTO' => $this->input->post('fecha_incremento'),
												);

												$datacnm = array(
																'COD_TIPOCARTERA' => '8',
																'COD_PROCESO_REALIZADO' => '1',
																'COD_REGIONAL' => $this->ion_auth->user()->row()->COD_REGIONAL,
																'COD_EMPLEADO' => $this->input->post('cedula'),
																'COD_ESTADO' => $this->input->post('tipo_estado_id'),			
																'COD_TIPO_ACUERDO' => $this->input->post('tipo_acuerdo_id'),	
																'VALOR_DEUDA' => str_replace(".", "", $this->input->post('valor_deuda')),
																'SALDO_DEUDA' => str_replace(".", "", $this->input->post('valor_deuda')),
																'COD_FORMAPAGO' => $this->input->post('forma_pago_id'),	
																'CALCULO_CORRIENTE' => $calc_corriente,
																'TIPO_T_F_CORRIENTE' => $tipo_t_f_corriente,
																'TIPO_T_V_CORRIENTE' => $tipo_t_v_corriente,
																'VALOR_T_CORRIENTE' => $valor_t_corriente,		
																'CALCULO_MORA' => $calc_mora,
																'TIPO_T_F_MORA' => $tipo_t_f_mora,
																'TIPO_T_V_MORA' => $tipo_t_v_mora,
																'VALOR_T_MORA' => $valor_t_mora,		
																'CALCULO_SEGURO' => $calc_seg,	
																'TIPO_T_F_SEGURO' => $tasa_f_s,	
																'TIPO_T_V_SEGURO' => $tasa_v_s,	
																'VALOR_T_SEGURO' => $valor_t_seg,
																'INCREMENTO_CUOTA' => $this->input->post('select_incremento'),
																'PLAZO_CUOTAS' => $this->input->post('plazo'),
																'COD_PLAZO' => $this->input->post('plazo_id'),
																													
												);
												
												
									if ($this->cnm_prestamo_hipotecario_model->add('CNM_CARTERANOMISIONAL',$datacnm,$datecnm) == TRUE)
									{
										$id_cartera=$this->cnm_prestamo_hipotecario_model->getLastInserted('CNM_CARTERANOMISIONAL','COD_CARTERA_NOMISIONAL');


												$date = array(
												'FECHA_CREACION' =>  date("d/m/Y H:i:s"),
												'FECHA_SOLICITUD' =>  $this->input->post('fecha_solicitud'),
												'FECHA_ESCRITURA' =>  $this->input->post('fecha_escritura'),
												'FECHA_ACTIVACION' =>  $this->input->post('fecha_activacion'),
												'FECHA_RETIRO' =>  $this->input->post('fecha_retiro'),
												);

												$data = array(
																'NUMERO_ESCRITURA' => $this->input->post('numero_resolucion'),
																'COD_MODALIDAD' => $this->input->post('tipo_modalidad_id'),
																'NUMERO_ESCRITURA' => $this->input->post('numero_escritura'),
																'PLAZO_CUOTAS' => $this->input->post('plazo'),			
																'FORMA_PAGO' => $this->input->post('forma_pago_id'),	
																'ID_CODEUDOR' => $this->input->post('id_codeudor'),	
																'NOMBRE_CODEUDOR' => $this->input->post('nombre_codeudor'),	
																'COD_CARTERA' => $id_cartera,
																'ID_GARANTIA' => $this->input->post('id_garantia'),
																'DESC_GARANTIA' => $this->input->post('desc_garantia'),
																'NUM_POLIZA' => $this->input->post('n_poliza'),
																'ASEGURADORA' => $this->input->post('aseguradora'),
																'MOD_TASA_CORRIENTE' => $mod_tasa_corr,
																'MOD_TASA_MORA' => $mod_tasa_mora,
																'MOD_TASA_SEGURO' => $mod_tasa_seg,
																'SALARIO' => str_replace(".", "", $this->input->post('salario')),
																'FACTOR_TIPO' => str_replace(",", ".", $this->input->post('factor_tipo')),
																'TASA_IPC' => str_replace(",", ".", $this->input->post('tasa_ipc')),
																'TASA_INTERES_CESANTIAS' =>  str_replace(",", ".", $this->input->post('tasa_cesantia')),
																'VALOR_CUOTA_APROBADA' => str_replace(".", "", $this->input->post('valor_cuota_aprob')),
																'GRADIENTE' => str_replace(",", ".", $this->input->post('gradiente')),
																	
												);
										
									if ($this->cnm_prestamo_hipotecario_model->add('CNM_CARTERA_PRESTAMO_HIPOTEC',$data,$date) == TRUE)
									{
											trazarProcesoJuridico('545', '1356', '', '', $id_cartera, '', '', 'Cartera Creada', '','');
										
											
											
				if($this->input->post('tipo_estado_id')=='2')
							{			
				$this->cnm_liquidacioncuotas = new Cnm_liquidacioncuotas();
                if($this->cnm_liquidacioncuotas->CnmCalcularCuotas($id_cartera, $this->input->post('fecha_activacion'), $this->input->post('forma_pago_id'), true)===true)		
				{		trazarProcesoJuridico('547', '1358', '', '', $id_cartera, '', '', '', '','');
					trazarProcesoJuridico('548', '1359', '', '', $id_cartera, '', '', '', '','');
					$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Nueva Cartera Credito Hipotecario se creó con éxito.</div>');
				}
				else {
					$datacnmact = array(
																'COD_ESTADO' => 1,			
												);
												
									$this->cnm_prestamo_hipotecario_model->updateCartera('CNM_CARTERANOMISIONAL',$datacnmact,'',$id_cartera,"COD_CARTERA_NOMISIONAL");			
					$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Cartera Credito Hipotecario se creó, pero no se liquidó por error en los datos.</div>');
					
				}
																			
										}
										else{
											
				                         $datos = array('id_cartera'=>$id_cartera, 'tipo_cartera'=>'8');
	           			 				$this->session->set_userdata('lolwut',$datos);			
										redirect(base_url().'index.php/carteranomisional/carga_archivo_cart/');
																								

													}
										 $datos = array('id_cartera'=>$id_cartera, 'tipo_cartera'=>'8');
	           			 				$this->session->set_userdata('lolwut',$datos);			
										redirect(base_url().'index.php/carteranomisional/carga_archivo_cart/');											
										
									
									}
else {
	$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
	
}
									}
									else
									{
									
					$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

									}
								}


										}

												
												
$id_cartera=$this->input->post('cod_prestamo_hip');
$this->data['tipoCartera']  = $this->cnm_prestamo_hipotecario_model->tipoCartera($id_cartera);
$this->data['estado']  = $this->cnm_prestamo_hipotecario_model->selectTipoEstado();
$this->data['forma_pago']  = $this->cnm_prestamo_hipotecario_model->selectFormaPago();
$this->data['duracion']  = $this->cnm_prestamo_hipotecario_model->selectPlazo();
$this->data['acuerdos']  = $this->cnm_prestamo_hipotecario_model->selectAcuerdo($id_cartera);
if(!empty($this->data['acuerdos']->result_array[0]["COD_ACUERDOLEY"])){
$cod_ac_modalidad= $this->data['acuerdos']->result_array[0]["COD_ACUERDOLEY"];
}
else{
$cod_ac_modalidad= "";	
}

$this->data['acuerdo_prestamo']  = $cod_ac_modalidad;
$this->data['modalidad']  = $this->cnm_prestamo_hipotecario_model->selectModalidad($cod_ac_modalidad);
$this->data['tipotasa']  = $this->cnm_prestamo_hipotecario_model->selectTipoTasaCombo();
$this->data['tipotasaesp']  = $this->cnm_prestamo_hipotecario_model->selectTasaEspCombo();
$this->data['incremento']  = $this->cnm_prestamo_hipotecario_model->selectPeriodoLiq();
										
										
										$this->template->load($this->template_file, 'cnm_prestamo_hipotecario/cnm_prestamo_hipotecario_add', $this->data);
									}
								else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/carteranomisional');
									 }
							 }
						else
						{
							redirect(base_url().'index.php/auth/login');
						}
    
								
		}	

function traercedula() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/manage')) {
        $nit = $this->input->get('term');
        if (empty($nit)) {
          redirect(base_url() . 'index.php/cnm_prestamo_hipotecario/add');
        } else {
          $this->cnm_prestamo_hipotecario_model->set_nit($nit);
          return $this->output->set_content_type('application/json')->set_output(json_encode($this->cnm_prestamo_hipotecario_model->buscarnits()));
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
   
   
   		 function get_datos($id){

$data_emp = $this->cnm_prestamo_hipotecario_model->select_data_emp($id);
//var_dump($data_emp);
//die();
print_r(json_encode($data_emp));

		} 		


	function edit(){    
			
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/add'))
							 {
					$this->template->set('title', 'Registro Cartera Crédito Ahorro');
												
												$flag=$this->input->post('vista_flag');
										if(!empty($flag)){
									
										$this->form_validation->set_rules('cedula', 'Cedula', 'required');

												if ($this->form_validation->run() == false)
										{
												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
										} else
										{
date_default_timezone_set("America/Bogota");

						$mod_tasa_corr='S';
						$calc_corriente= $this->input->post('combo_tipo_tasa_corriente');
						switch($calc_corriente)
						{case '1':
						$tipo_t_f_corriente= $this->input->post('aplica_tasa_c');
						$tipo_t_v_corriente= "";
						$valor_t_corriente= $this->input->post('porcent_tasa_c');
							break;
							
						case '2':
						$tipo_t_f_corriente= "";
						$tipo_t_v_corriente= $this->input->post('aplica_tasa_c');
						$valor_t_corriente= "";
							break;
							
						default:
						$tipo_t_f_corriente= "";
						$tipo_t_v_corriente= "";
						$valor_t_corriente= "";

						break;		
						}
						

													
													
								$tasa_mora=$this->input->post('t_i_m');
												if(!empty($tasa_mora))
					{
						$mod_tasa_mora='S';		
						$calc_mora= $this->input->post('combo_tipo_tasa_mora');
						switch($calc_mora)
						{case '1':
						$tipo_t_f_mora= $this->input->post('aplica_tasa_m');
						$tipo_t_v_mora= "";
						$valor_t_mora= $this->input->post('porcent_tasa_m');
							break;
							
						case '2':
						$tipo_t_f_mora= "";
						$tipo_t_v_mora= $this->input->post('aplica_tasa_m');
						$valor_t_mora= "";
							break;
							
						default:
						$tipo_t_f_mora= "";
						$tipo_t_v_mora= "";
						$valor_t_mora= "";

						break;		
						}
						
						
					}
												else
													{
						$mod_tasa_mora='N';									
						$calc_mora= "";
						$tipo_t_f_mora= "";
						$tipo_t_v_mora= "";
						$valor_t_mora= "";
													}					

$tasa_seg=$this->input->post('t_i_s');
												if(!empty($tasa_seg))
					{
						$calc_seg=$this->input->post('tipo_tasa_seg');
						$tasa_f_s="";
						$tasa_v_s="";
						$mod_tasa_seg='S';		

						switch($calc_seg)
						{case '1':
						$tasa_f_s= $this->input->post('tasa_esp_seg');
						$tasa_v_s= "";
						$valor_t_seg= $this->input->post('valor_tasa_seg');
							break;
							
						case '2':
						$tasa_f_s= "";
						$tasa_v_s= $this->input->post('tasa_esp_seg');
						$valor_t_seg= "";
							break;
							
						default:
						$tasa_f_s= "";
						$tasa_v_s= "";
						$valor_t_seg= "";

						break;		
						}
						
						
					}
												else
													{
						$mod_tasa_seg='N';									
						$calc_seg= "";
						$tasa_f_s= "";
						$tasa_v_s= "";
						$valor_t_seg= "";
													}							


$id_deuda = $this->input->post('id_deuda');

												
																								$datecnm = array(
												'FECHA_CREACION' =>  date("d/m/Y H:i:s"),
												'FECHA_INCREMENTO' => $this->input->post('fecha_incremento'),
												);

												$datacnm = array(
																'COD_TIPOCARTERA' => '8',
																'COD_PROCESO_REALIZADO' => '1',
																'COD_REGIONAL' => $this->ion_auth->user()->row()->COD_REGIONAL,
																'COD_EMPLEADO' => $this->input->post('cedula'),
																'COD_ESTADO' => $this->input->post('tipo_estado_id'),			
																'COD_TIPO_ACUERDO' => $this->input->post('tipo_acuerdo_id'),	
																'VALOR_DEUDA' => str_replace(".", "", $this->input->post('valor_deuda')),
																'SALDO_DEUDA' => str_replace(".", "", $this->input->post('valor_deuda')),	
																'COD_FORMAPAGO' => $this->input->post('forma_pago_id'),	
																'CALCULO_CORRIENTE' => $calc_corriente,
																'TIPO_T_F_CORRIENTE' => $tipo_t_f_corriente,
																'TIPO_T_V_CORRIENTE' => $tipo_t_v_corriente,
																'VALOR_T_CORRIENTE' => $valor_t_corriente,		
																'CALCULO_MORA' => $calc_mora,
																'TIPO_T_F_MORA' => $tipo_t_f_mora,
																'TIPO_T_V_MORA' => $tipo_t_v_mora,
																'VALOR_T_MORA' => $valor_t_mora,		
																'CALCULO_SEGURO' => $calc_seg,	
																'TIPO_T_F_SEGURO' => $tasa_f_s,	
																'TIPO_T_V_SEGURO' => $tasa_v_s,	
																'VALOR_T_SEGURO' => $valor_t_seg,
																'INCREMENTO_CUOTA' => $this->input->post('select_incremento'),
																'PLAZO_CUOTAS' => $this->input->post('plazo'),
																'COD_PLAZO' => $this->input->post('plazo_id'),
																													
												);
												
									if ($this->cnm_prestamo_hipotecario_model->updateCartera('CNM_CARTERANOMISIONAL',$datacnm,$datecnm,$id_deuda,"COD_CARTERA_NOMISIONAL") == TRUE)			
									{



												$date = array(
												'FECHA_CREACION' =>  date("d/m/Y H:i:s"),
												'FECHA_SOLICITUD' =>  $this->input->post('fecha_solicitud'),
												'FECHA_ESCRITURA' =>  $this->input->post('fecha_escritura'),
												'FECHA_ACTIVACION' =>  $this->input->post('fecha_activacion'),
												'FECHA_RETIRO' =>  $this->input->post('fecha_retiro'),
												);

												$data = array(
																'NUMERO_ESCRITURA' => $this->input->post('numero_resolucion'),
																'COD_MODALIDAD' => $this->input->post('tipo_modalidad_id'),
																'NUMERO_ESCRITURA' => $this->input->post('numero_escritura'),
																'PLAZO_CUOTAS' => $this->input->post('plazo'),			
																'FORMA_PAGO' => $this->input->post('forma_pago_id'),	
																'ID_CODEUDOR' => $this->input->post('id_codeudor'),	
																'NOMBRE_CODEUDOR' => $this->input->post('nombre_codeudor'),	
																'ID_GARANTIA' => $this->input->post('id_garantia'),
																'DESC_GARANTIA' => $this->input->post('desc_garantia'),
																'NUM_POLIZA' => $this->input->post('n_poliza'),
																'ASEGURADORA' => $this->input->post('aseguradora'),
																'MOD_TASA_CORRIENTE' => $mod_tasa_corr,
																'MOD_TASA_MORA' => $mod_tasa_mora,
																'MOD_TASA_SEGURO' => $mod_tasa_seg,
																'SALARIO' => str_replace(".", "", $this->input->post('salario')),
																'FACTOR_TIPO' => str_replace(",", ".", $this->input->post('factor_tipo')),
																'TASA_IPC' => str_replace(",", ".", $this->input->post('tasa_ipc')),
																'TASA_INTERES_CESANTIAS' => str_replace(",", ".", $this->input->post('tasa_cesantia')),
																'VALOR_CUOTA_APROBADA' => str_replace(".", "", $this->input->post('valor_cuota_aprob')),
																'GRADIENTE' => str_replace(",", ".", $this->input->post('gradiente')),
																													
												);
								if ($this->cnm_prestamo_hipotecario_model->updateCartera('CNM_CARTERA_PRESTAMO_HIPOTEC',$data,$date,$id_deuda,"COD_CARTERA") == TRUE)		
									{
									trazarProcesoJuridico('546', '1357', '', '', $id_deuda, '', '', '', '','');
																				
																									if($this->input->post('tipo_estado_id')=='2')
										{
				$this->cnm_liquidacioncuotas = new Cnm_liquidacioncuotas();
                if($this->cnm_liquidacioncuotas->CnmCalcularCuotas($id_deuda, $this->input->post('fecha_activacion'), $this->input->post('forma_pago_id'), true)===true)		
				{
						trazarProcesoJuridico('547', '1358', '', '', $id_deuda, '', '', '', '','');
					trazarProcesoJuridico('548', '1359', '', '', $id_deuda, '', '', '', '','');
					$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Nueva Cartera Credito Hipotecario se creó con éxito.</div>');
				}
				else {
					$datacnmact = array(
																'COD_ESTADO' => 1,			
												);
												
									$this->cnm_prestamo_hipotecario_model->updateCartera('CNM_CARTERANOMISIONAL',$datacnmact,'',$id_deuda,"COD_CARTERA_NOMISIONAL");			
					$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Cartera Credito Hipotecario se creó, pero no se liquidó por error en los datos.</div>');
					
				}
																			
										}
										else{
											
				                         $datos = array('id_cartera'=>$id_deuda, 'tipo_cartera'=>'8');
	           			 				$this->session->set_userdata('lolwut',$datos);			
										redirect(base_url().'index.php/carteranomisional/carga_archivo_cart/');
																								

													}
										 $datos = array('id_cartera'=>$id_deuda, 'tipo_cartera'=>'8');
	           			 				$this->session->set_userdata('lolwut',$datos);			
										redirect(base_url().'index.php/carteranomisional/carga_archivo_cart/');											
										
						


									
									}
else {
	$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
	
}
									}
									else
									{
									
					$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

									}
								}


										}

												
												

$id_cartera_esp=$this->input->post('id_cartera_edit');
//echo $id_cartera_esp;
//die();
$id_cartera=8;
$this->data['detalleCart']  = $this->cnm_prestamo_hipotecario_model->detalleCarterasEdit($id_cartera_esp)->result_array[0];
$this->data['tipoCartera']  = $this->cnm_prestamo_hipotecario_model->tipoCartera($id_cartera);

$this->data['estado']  = $this->cnm_prestamo_hipotecario_model->selectTipoEstado();
$this->data['forma_pago']  = $this->cnm_prestamo_hipotecario_model->selectFormaPago();
$this->data['duracion']  = $this->cnm_prestamo_hipotecario_model->selectPlazo();
$this->data['acuerdos']  = $this->cnm_prestamo_hipotecario_model->selectAcuerdo($id_cartera);

if(!empty($this->data['acuerdos']->result_array[0]["COD_ACUERDOLEY"])){
$cod_ac_modalidad= $this->data['acuerdos']->result_array[0]["COD_ACUERDOLEY"];
}
else{
$cod_ac_modalidad= "";	
}

$this->data['acuerdo_prestamo']  = $cod_ac_modalidad;
$this->data['modalidad']  = $this->cnm_prestamo_hipotecario_model->selectModalidad($cod_ac_modalidad);
$this->data['tipotasa']  = $this->cnm_prestamo_hipotecario_model->selectTipoTasaCombo();
$this->data['tipotasaesp']  = $this->cnm_prestamo_hipotecario_model->selectTasaEspCombo();
$this->data['tipotasaespC']  = $this->cnm_prestamo_hipotecario_model->selectTasaEspComboEdit($this->data['detalleCart']["CALCULO_CORRIENTE"]);
$this->data['tipotasaespV']  = $this->cnm_prestamo_hipotecario_model->selectTasaEspComboEdit($this->data['detalleCart']["CALCULO_MORA"]);
$this->data['incremento']  = $this->cnm_prestamo_hipotecario_model->selectPeriodoLiq();										
	$this->template->load($this->template_file, 'cnm_prestamo_hipotecario/cnm_prestamo_hipotecario_edit', $this->data);

									}
								else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/carteranomisional');
									 }
							 }
						else
						{
							redirect(base_url().'index.php/auth/login');
						}   
				
				
		}
	
		function detalle(){


    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
							 {
					$this->template->set('title', 'Consulta Cartera No Misional');

										$id_cartera=$this->input->post('id_cartera');
										//echo $id_cartera;
										//die();
										$this->data['carterasini']  = $this->cnm_prestamo_hipotecario_model->detalleprevioPHipotecario($id_cartera)->result_array[0];
										//var_dump($this->data['carterasini']);			
										//die();	
										$this->data['carteras']  = $this->cnm_prestamo_hipotecario_model->detallePHipotecario($id_cartera, $this->data['carterasini']['COD_MODALIDAD'], $this->data['carterasini']['MOD_TASA_CORRIENTE'], $this->data['carterasini']['MOD_TASA_MORA'], $this->data['carterasini']['CALCULO_CORRIENTE'], $this->data['carterasini']['CALCULO_MORA'], $this->data['carterasini']['MOD_TASA_SEGURO'], $this->data['carterasini']['CALCULO_SEGURO'])->result_array[0];
										//var_dump($this->data['carteras']);
										//die();
										$consultaadj=$this->data['carteras']['ADJUNTOS'];
										
										
if(!empty($consultaadj)){
				$adjuntoconsulta = strpos($consultaadj, "::");

if ($adjuntoconsulta === false) {
	$this->data['lista_adjuntos'][0]=$consultaadj;	
} else {$cadena="";
	$stringadj=explode('::', $consultaadj);
	$this->data['lista_adjuntos']=$stringadj;
}	
				
				}
				else{
						
			$this->data['lista_adjuntos']="";		
				}
										$this->template->load($this->template_file, 'cnm_prestamo_hipotecario/cnm_prestamo_hipotecario_list', $this->data);
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/carteranomisional');
									 }
							 }
						else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}
 
		
function lista(){


    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
							 {
					$this->template->set('title', 'Consulta Cartera No Misional');
										//add style an js files for inputs selects
										$this->load->library('datatables');	
										$this->data['style_sheets']= array(
									                    'css/jquery.dataTables_themeroller.css' => 'screen'
                
												);
										
										$id_cartera=$this->input->post('cartera_id');
										$cedula=$this->input->post('cedula');
										$estado=$this->input->post('estado');
										$id_deuda=$this->input->post('id_deuda');
										$this->data['carteras']  = $this->cnm_prestamo_hipotecario_model->detalleCarteras($cedula, $id_cartera, $estado, $id_deuda);
										//var_dump($this->data['carteras']);
										//die();

										
										$this->load->view('cnm_prestamo_hipotecario/cnm_prestamo_hipotecario_datos_consulta', $this->data);
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/carteranomisional');
									 }
							 }
						else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}		

function visualizar_pagos(){


    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
							 {

															
										$this->template->set('title', 'Consulta Cartera No Misional');


										$this->load->view('cnm_prestamo_hipotecario/cnm_prestamo_hipotecario_visualizar_pagos', $this->data);
								
							 
							 }else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/carteranomisional');
									 }
							 }
						else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}		
		
function cargue_cesantias(){
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/manage'))
							 {
	        					
								$this->template->set('title', 'Cargue Cesantias');
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen',
														'css/validationEngine.jquery.css' => 'screen'
												);
								
								
					$this->data['custom_error'] = '';
                    $showForm=0;

						
$flag=$this->input->post('vista_flag');
if(!empty($flag)){
      			
      			$this->form_validation->set_rules('vista_flag', '', 'required'); 
					
					
                    if ($this->form_validation->run() == false)
                    {
                    	
						
                         $this->dataemail['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                    } else
                    {
							$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cargue para excedentes de servicios medicos realizado correctamente</div>');

		 				redirect(base_url().'index.php/carteranomisional/manage');	
							 
							 
							 
							 	} 
				}
				
								$this->session->set_flashdata('message', '');
								$this->data['message']=$this->session->flashdata('message');
								
								$this->template->load($this->template_file, 'cnm_prestamo_hipotecario/cnm_prestamo_hipotecario_cesantias',$this->data); 			 
	}else {
								$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
											redirect(base_url().'index.php/inicio');
							 } 

						}else
						{
							redirect(base_url().'index.php/auth/login');
						}									
								
							
						
					
				
			}

function cargue_avaluos(){
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/manage'))
							 {
	        					
								$this->template->set('title', 'Cargue Avaluos de Vivienda');
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen',
														'css/validationEngine.jquery.css' => 'screen'
												);
								
								
					$this->data['custom_error'] = '';
                    $showForm=0;

						
$flag=$this->input->post('vista_flag');
if(!empty($flag)){
      			
      			$this->form_validation->set_rules('vista_flag', '', 'required'); 
					
					
                    if ($this->form_validation->run() == false)
                    {
                    	
						
                         $this->dataemail['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                    } else
                    {
							$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cargue para excedentes de servicios medicos realizado correctamente</div>');

		 				redirect(base_url().'index.php/carteranomisional/manage');	
							 
							 
							 
							 	} 
				}
				
								$this->session->set_flashdata('message', '');
								$this->data['message']=$this->session->flashdata('message');
								
								$this->template->load($this->template_file, 'cnm_prestamo_hipotecario/cnm_prestamo_hipotecario_aval',$this->data); 			 
	}else {
								$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
											redirect(base_url().'index.php/inicio');
							 } 

						}else
						{
							redirect(base_url().'index.php/auth/login');
						}									
								
							
						
					
				
			}

//cargue
    function subir_archivo_avaluo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_prestamo_hipotecario/subir_archivo_avaluo')) {
                $this->template->set('title', 'Actualización de Avaluos');

                $this->data['dateini'] = date('Y-m-d H:i:s');
                $this->data['errores'] = array();
                $this->data['numlineas'] = 0;
//                $this->data['negativos'] = array();
//                $this->data['positivos'] = array();

                if (is_dir("uploads/cargarnomisional/temporal")) {
                    $this->deleteDir("uploads/cargarnomisional/temporal", '1');
                }

                $directorios = $this->directorios();
                $path = "uploads/cargarnomisional/temporal/";
                if ($directorios) {
                    foreach (array_combine($_FILES['userfile']['name'], $_FILES['userfile']['tmp_name']) as $file => $filet) {
                        if ($file == '') {
                            array_push($this->data['errores'], $file . " , No se selecciono ningun archivo. ");
                        } else {
                            $this->data['numlineas'] = 0;
                            $extension = trim($this->obtenerExtensionFichero($file));
                            if ($extension == 'txt' || $extension == 'TXT' || $extension == 'csv' || $extension == 'CSV') {
                                $this->cargaarchivo_avaluo($file, $filet);
                            } else {
                                array_push($this->data['errores'], 'El archivo no es tipo texto');
                            }
                        }
                    }
                }
//
//                if (sizeof($this->data['errores']) > 0) {
//                    $this->crearfichero(0, $file, $filet);
//                    $this->data['negativos'] ++;
//                    $this->data['archivo'] = "errores/" . $file;
//                    $this->data['numlineas'] = 0;
//                } else {
//                    $this->crearfichero(1, $file, $filet);
//                    $this->data['positivos'] ++;
//                    $this->data['archivo'] = "generados/" . $file;
//                }

                $this->template->load($this->template_file, 'cnm_prestamo_hipotecario/cnm_prestamo_hipotecario_result', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

//cargue
    function subir_archivo_cesantias() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_prestamo_hipotecario/subir_archivo_cesantias')) {
                $this->template->set('title', 'Actualización de Avaluos');

                $this->data['dateini'] = date('Y-m-d H:i:s');
                $this->data['errores'] = array();
                $this->data['numlineas'] = 0;
//                $this->data['negativos'] = array();
//                $this->data['positivos'] = array();

                if (is_dir("uploads/cargarnomisional/temporal")) {
                    $this->deleteDir("uploads/cargarnomisional/temporal", '1');
                }

                $directorios = $this->directorios();
                $path = "uploads/cargarnomisional/temporal/";
                if ($directorios) {
                    foreach (array_combine($_FILES['userfile']['name'], $_FILES['userfile']['tmp_name']) as $file => $filet) {
                        if ($file == '') {
                            array_push($this->data['errores'], $file . " , No se selecciono ningun archivo. ");
                        } else {
                            $this->data['numlineas'] = 0;
                            $extension = trim($this->obtenerExtensionFichero($file));
                            if ($extension == 'txt' || $extension == 'TXT' || $extension == 'csv' || $extension == 'CSV') {
                                $this->cargaarchivo_cesantias($file, $filet);
                            } else {
                                array_push($this->data['errores'], 'El archivo no es tipo texto');
                            }
                        }
                    }
                }


                $this->template->load($this->template_file, 'cnm_prestamo_hipotecario/cnm_prestamo_hipotecario_result', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function deleteDir($path, $tipo = 0) {
        if (!is_dir($path)) {
            throw new InvalidArgumentException("$path is not a directory");
        }
        if (substr($path, strlen($path) - 1, 1) != '/') {
            $path .= '/';
        }
        $dotfiles = glob($path . '.*', GLOB_MARK);
        $files = glob($path . '*', GLOB_MARK);
        $files = array_merge($files, $dotfiles);
        foreach ($files as $file) {
            if (basename($file) == '.' || basename($file) == '..') {
                continue;
            } else if (is_dir($file)) {
                self::deleteDir($file, '1');
            } else {
                unlink($file);
            }
        }
        if ($tipo == 1) {
            rmdir($path);
        }
    }

    private function directorios() {
        $errores = 0;
        $paths = array();
        $paths[] = "uploads/cargarnomisional/";
        $paths[] = "uploads/cargarnomisional/archivos";
        $paths[] = "uploads/cargarnomisional/errores";
        $paths[] = "uploads/cargarnomisional/generados";
        foreach ($paths as $path) {
            if (!is_dir($path)) {
                if (!mkdir($path, 0777, TRUE)) {
                    $errores++;
                }
            }
        }
        if ($errores > 0)
            return false;
        else
            return true;
    }

    private function cargaarchivo_avaluo($file, $filet){
//abre el archivo temporal para su lectura
        $leer = fopen($filet, "r") or exit("Unable to open file!");
        while (!feof($leer)) {
            $linea = trim(fgets($leer));
            $estructura = explode(",", $linea);
            if (sizeof($estructura) > 1) {
                if (is_numeric($estructura[1])) {
                    $errores = 0;
                } else {
                    array_push($this->data['errores'], 'error de estructura linea ' . $this->data['numlineas']);
                }
            } else {
                $estructura = explode(";", $linea);
                if (is_numeric($estructura[1])) {
                    $errores = 0;
                } else {
                    array_push($this->data['errores'], 'error de estructura linea ' . $this->data['numlineas']);
                }
            }
            
            $f = $this->cnm_prestamo_hipotecario_model->guardarfila_avaluo($estructura, $file);
            if ($f == FALSE) {
                array_push($this->data['errores'], 'error en la carga de linea ' . $this->data['numlineas']);
            }
			if ($f!=FALSE){
            $this->data['numlineas'] ++;
			$dbuser = $this->db->username;
			$dbpassword = $this->db->password;
			$dbConnString = $this->db->hostname;
			
			$v_oDataConn = oci_connect($dbuser, $dbpassword, $dbConnString);
					if (!$v_oDataConn) {
							$v_oErroCntr = oci_error();
							trigger_error(htmlentities($v_oErroCntr['message'], ENT_QUOTES), E_USER_ERROR);
					}
					
			//Seguro de incendio
			$query = "BEGIN PKG_CARTERA_NO_MISIONAL.Crea_Cobro_Incendio(".$f.", null, null, :pio_Mensaje); END;";
			
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
			oci_close($v_oDataConn);
			
			}					
        }
        fclose($leer);

    }


    private function cargaarchivo_cesantias($file, $filet){
//abre el archivo temporal para su lectura
        $leer = fopen($filet, "r") or exit("Unable to open file!");
        while (!feof($leer)) {
            $linea = trim(fgets($leer));
            $estructura = explode(",", $linea);
            if (sizeof($estructura) > 3) {
                if (is_numeric($estructura[1])) {
                    $errores = 0;
                } else {
                    array_push($this->data['errores'], 'error de estructura linea ' . $this->data['numlineas']);
                }
            } else {
                $estructura = explode(";", $linea);
                if (is_numeric($estructura[1])) {
                    $errores = 0;
                } else {
                    array_push($this->data['errores'], 'error de estructura linea ' . $this->data['numlineas']);
                }
            }
            
            $f = $this->cnm_prestamo_hipotecario_model->guardarfila_cesantias($estructura, $file);
            if ($f == FALSE) {
                array_push($this->data['errores'], 'error en la carga de linea ' . $this->data['numlineas']);
            }
				$fechaact=$this->cnm_prestamo_hipotecario_model->detalleFechaAct($estructura[0]);

				$this->cnm_liquidacioncuotas = new Cnm_liquidacioncuotas();
                if($this->cnm_liquidacioncuotas->CnmCalcularCuotas($estructura[0],  $fechaact["FECHA_ACTIVACION"], $fechaact["FORMA_PAGO"],true,true)==true)		
				{ 
						trazarProcesoJuridico('550', '1364', '', '', $estructura[0], '', '', "Cesantias Aplicadas", '','');
				}
				else {
				}
            $this->data['numlineas'] ++;
									
        }
        fclose($leer);

    }

    function obtenerExtensionFichero($str) {
        return end(explode(".", $str));
    }


}


