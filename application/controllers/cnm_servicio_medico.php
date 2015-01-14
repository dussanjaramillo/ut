<?php

class Cnm_servicio_medico extends MY_Controller {
		
		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');		
		$this->load->helper(array('form','url','codegen_helper','template_helper','traza_fecha_helper'));
		$this->load->model('cnm_servicio_medico_model','',TRUE);
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/manage'))
							 {
	        					
								$this->template->set('title', 'Cargue Excedente de Servicios Medicos');
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
								
								$this->data['tipos']  = $this->cnm_servicio_medico_model->getSelectTipoCartera('TIPOCARTERA','COD_TIPOCARTERA, NOMBRE_CARTERA');
								
								$this->template->load($this->template_file, 'cnm_servicio_medico/cnm_servicio_medico_cargue',$this->data); 			 
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
					$this->template->set('title', 'Asignación de Cartera de Servicio Médico');
										//add style an js files for inputs selects
										
										$this -> data['cod_servicio_medico']=$this->input->post('cod_servicio_medico');
										
										$this->template->load($this->template_file, 'cnm_servicio_medico/cnm_servicio_medico_add', $this->data);
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/fiscalizacion');
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
          redirect(base_url() . 'index.php/carteranomisional/add');
        } else {
          $this->cnm_servicio_medico_model->set_nit($nit);
          return $this->output->set_content_type('application/json')->set_output(json_encode($this->cnm_servicio_medico_model->buscarnits()));
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
		   
		    function verificacion_orden($orden){

$data_emp = $this->cnm_servicio_medico_model->select_data_orden($orden);
//var_dump($data_emp);
//die();
print_r(json_encode($data_emp));

		} 		


   		 function verificacion_cedula($id){

$data_emp = $this->cnm_servicio_medico_model->select_data_cedula($id);
//var_dump($data_emp);
//die();
print_r(json_encode($data_emp));

		} 		
		
		function asignacion_res(){
    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/add'))
							 {

															
										$this->template->set('title', 'Asignación de Cartera de Servicio Médico');

										$cedula=$this->input->post('cedula');
										$orden=$this->input->post('orden');
										$this->load->library('datatables');	
										$this -> data['style_sheets']= array('css/jquery.dataTables_themeroller.css' => 'screen');
										if(!empty($orden))
										{	
											$this->data['cargas']=$this->cnm_servicio_medico_model->carguexorden($orden);
											$empleado=$this->data['cargas']->result_array();
											$infoemp=$this->cnm_servicio_medico_model->infoempleado($empleado[0]["COD_DEUDOR"])->result_array();
										
										}
										else {

										$this->data['cargas']=$this->cnm_servicio_medico_model->carguexcedula($cedula);
										$infoemp=$this->cnm_servicio_medico_model->infoempleado($cedula)->result_array();

										}

										$this->data['empleado']=$infoemp[0];
										$this -> data['cod_servicio_medico']=$this->input->post('cod_servicio_medico2');
	
										$this->load->view('cnm_servicio_medico/cnm_servicio_medico_asignacion_res', $this->data);
								
							 
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
		

function creacion(){
    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/add'))
							 {
							$this->template->set('title', 'Creación de Cartera de Servicio Médico');

																					
										$flag=$this->input->post('vista_flag');
										if(!empty($flag)){

											
										$this->form_validation->set_rules('cedula', 'Cedula', 'required');

												if ($this->form_validation->run() == false)
										{

												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{
date_default_timezone_set("America/Bogota");
												$tasa_corriente=$this->input->post('t_i_c');
												if(!empty($tasa_corriente))
					{	$mod_tasa_corr='S';
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
						
						
					}
												else
													{
						$mod_tasa_corr='N';								
						$calc_corriente= "";
						$tipo_t_f_corriente= "";
						$tipo_t_v_corriente= "";
						$valor_t_corriente= "";
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
												
												
												$id_deuda = $this->input->post('id_deuda');
												
												$datecnm = array(
												'FECHA_CREACION' =>  date("d/m/Y H:i:s"),
												);

												$datacnm = array(
																'COD_TIPOCARTERA' => '5',
																'COD_PROCESO_REALIZADO' => '1',
																'COD_REGIONAL' => $this->ion_auth->user()->row()->COD_REGIONAL,
																'COD_EMPLEADO' => $this->input->post('cedula'),
																'COD_ESTADO' => $this->input->post('tipo_estado_id'),				
																'COD_TIPO_ACUERDO' => $this->input->post('tipo_acuerdo_id'),	
																'COD_FORMAPAGO' => $this->input->post('forma_pago_id'),	
																'SALDO_DEUDA' => $this->input->post('valor_reintegro'),	
																'PLAZO_CUOTAS' => $this->input->post('plazo'),
																'COD_PLAZO' => $this->input->post('plazo_id'),	
																
																'CALCULO_CORRIENTE' => $calc_corriente,
																'TIPO_T_F_CORRIENTE' => $tipo_t_f_corriente,
																'TIPO_T_V_CORRIENTE' => $tipo_t_v_corriente,
																'VALOR_T_CORRIENTE' => $valor_t_corriente,		
																'CALCULO_MORA' => $calc_mora,
																'TIPO_T_F_MORA' => $tipo_t_f_mora,
																'TIPO_T_V_MORA' => $tipo_t_v_mora,
																'VALOR_T_MORA' => $valor_t_mora,														
												);
												
									if ($this->cnm_servicio_medico_model->updateCartera('CNM_CARTERANOMISIONAL',$datacnm,$datecnm,$id_deuda,"COD_CARTERA_NOMISIONAL") == TRUE)			
									{
										$existben=$this->cnm_servicio_medico_model->verificarExistenciaBeneficiario($this->input->post('id_beneficiario'));
										if($existben["EXIST"]==0)
										{
																					
												$dateben = array(

												);

												$databen = array(
																'COD_BENEFICIARIO' => $this->input->post('id_beneficiario'),
																'COD_EMPLEADO' => $this->input->post('cedula'),			
																'NOMBRES_BENEFICIARIO' => $this->input->post('n_beneficiario'),	
																'APELLIDOS_BENEFICIARIO' => $this->input->post('a_beneficiario'),	
																													
												);
												$this->cnm_servicio_medico_model->add('CNM_BENEFICIARIOS_SERVICIO_MED',$databen,$dateben);
										
										}

										
										$existcont=$this->cnm_servicio_medico_model->verificarExistenciaContratista($this->input->post('id_contratista'));
										if($existcont["EXIST"]==0)
										{
												$datecon = array(

												);

												$datacon = array(
																'COD_CONTRATISTA' => $this->input->post('id_contratista'),
																'NOMBRE_CONTRATISTA' => $this->input->post('n_contratista'),			
																													
												);
												$this->cnm_servicio_medico_model->add('CONTRATISTA',$datacon,$datecon);
										
										}


												$date = array(
												'FECHA_ACTIVACION' =>  $this->input->post('fecha_activacion'),
												'FECHA_CREACION' =>  date("d/m/Y H:i:s"),
												'FECHA_PAGO' =>  $this->input->post('fecha_pago'),
												'FECHA_RESOLUCION' =>  $this->input->post('fecha_resolucion'),
												'FECHA_ORDEN' =>  $this->input->post('fecha_orden'),
												'VIGENCIA' =>  $this->input->post('vigencia'),
												);

												$data = array(
																'NUM_RESOLUCION' => $this->input->post('numero_resolucion'),
																'RECIBO_PAGO' => $this->input->post('recibo_pago'),			
																'ORDEN' => $this->input->post('numero_orden'),	
																'LETRA' => $this->input->post('letra'),	
																'COD_CONTRATISTA' => $this->input->post('id_contratista'),
																'COD_BENEFICIARIO' => $this->input->post('id_beneficiario'),
																'MOD_TASA_CORRIENTE' => $mod_tasa_corr,
																'MOD_TASA_MORA' => $mod_tasa_mora,													
												);
									if ($this->cnm_servicio_medico_model->updateCartera('CNM_EXCEDENTE_SERVICIO_MEDICO',$data,$date,$id_deuda,"COD_CARTERA") == TRUE)
									{
										$this->cnm_servicio_medico_model->updateCreado($this->input->post('numero_orden'));
										trazarProcesoJuridico('546', '1357', '', '', $id_deuda, '', '', '', '','');
					
										
										if($this->input->post('tipo_estado_id')=='2')
										{
				$this->cnm_liquidacioncuotas = new Cnm_liquidacioncuotas();
                if($this->cnm_liquidacioncuotas->CnmCalcularCuotas($id_deuda, $this->input->post('fecha_activacion'), $this->input->post('forma_pago_id'))==true)		
				{	trazarProcesoJuridico('547', '1358', '', '', $id_deuda, '', '', '', '','');
					trazarProcesoJuridico('548', '1359', '', '', $id_deuda, '', '', '', '','');
				}
				else {
				}
																			
										}
										else{		
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Cartera Excedente Servicio Médico ha sido editada con éxito.</div>');
				                         $datos = array('id_deuda'=>$id_deuda, 'tipo_cartera'=>'5', 'tabla_cartera'=>'CNM_EXCEDENTE_SERVICIO_MEDICO');
										 
            			 				$this->session->set_userdata('lolwut',$datos);			
													redirect(base_url().'index.php/carteranomisional/carga_archivo_edit/');
}
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Cartera Excedente Servicio Médico ha sido editada con éxito.</div>');
				                         $datos = array('id_deuda'=>$id_deuda, 'tipo_cartera'=>'5', 'tabla_cartera'=>'CNM_EXCEDENTE_SERVICIO_MEDICO');
										 
            			 				$this->session->set_userdata('lolwut',$datos);			
													redirect(base_url().'index.php/carteranomisional/carga_archivo_edit/');
													
									
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

$id_cartera=5;												
$this->data['detalleCart']  = $this->cnm_servicio_medico_model->detalleCarterasEdit($id_cartera_esp)->result_array[0];
//var_dump($this->data['detalleCart']);
//die();


$this->data['tipoCartera']  = $this->cnm_servicio_medico_model->tipoCartera($id_cartera);
$this->data['estado']  = $this->cnm_servicio_medico_model->selectTipoEstado();
$this->data['forma_pago']  = $this->cnm_servicio_medico_model->selectFormaPago();
$this->data['duracion']  = $this->cnm_servicio_medico_model->selectPlazo();
$this->data['acuerdos']  = $this->cnm_servicio_medico_model->selectAcuerdo($id_cartera);
if(!empty($this->data['acuerdos']->result_array[0]["COD_ACUERDOLEY"])){
$cod_ac_modalidad= $this->data['acuerdos']->result_array[0]["COD_ACUERDOLEY"];
}
else{
$cod_ac_modalidad= "";	
}
$this->data['acuerdo_prestamo']  = $cod_ac_modalidad;
$this->data['tipotasa']  = $this->cnm_servicio_medico_model->selectTipoTasaCombo();
$this->data['tipotasaesp']  = $this->cnm_servicio_medico_model->selectTasaEspCombo();
$this->data['tipotasaespC']  = $this->cnm_servicio_medico_model->selectTasaEspComboEdit($this->data['detalleCart']["CALCULO_CORRIENTE"]);
$this->data['tipotasaespV']  = $this->cnm_servicio_medico_model->selectTasaEspComboEdit($this->data['detalleCart']["CALCULO_MORA"]);										
										//$cod_carga = $this->input->post('id_carga');
										//$this->data['datos_c']  = $this->cnm_servicio_medico_model->selectDatosc($cod_carga)->result_array[0];			

										$this->template->load($this->template_file, 'cnm_servicio_medico/cnm_servicio_medico_edit', $this->data);
								
										
							 
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
		

	function edit(){    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/edit'))
							 {    
								$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
													redirect(base_url().'index.php/fiscalizacion');
										}else{
															$this->load->library('form_validation');  
													$this->data['custom_error'] = '';
													$this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');  
															$this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]');  
															if ($this->form_validation->run() == false)
															{
																	 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
																	
															} else
															{                            
																	$data = array(
																					'NOMBREBANCO' => $this->input->post('nombre'),
																					'IDESTADO' => $this->input->post('estado_id')
																	);
																 
														if ($this->fiscalizacion_model->edit('BANCO',$data,'IDBANCO',$this->input->post('id')) == TRUE)
														{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El banco se ha editado correctamente.</div>');
																			redirect(base_url().'index.php/fiscalizacion/');
														}
														else
														{
															$this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';

														}
													}
															$this->data['result'] = $this->fiscalizacion_model->get('PERIODO_INICIAL','PERIODO_FINAL','NOMBRE_CONCEPTO','NOMBRE_ESTADO','NOMBREUSUARIO','NRO_EXPEDIENTE','NRO_EXPEDIENTE = '.$this->uri->segment(3),1,1,true);
															$this->data['estados']  = $this->fiscalizacion_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');
																	
																	$this->template->load($this->template_file, 'fiscalizacion/fiscalizacion_edit', $this->data); 
											}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/fiscalizacion');
									 }
								
						}else
								{
								redirect(base_url().'index.php/auth/login');
								}
				
		}
	
		function delete(){
			
			
				 if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/delete'))
							 {
										$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
													redirect(base_url().'index.php/fiscalizacion');
										}else{
											 $data = array(
                                    				'IDESTADO' => '2'

				                            	);
				           				if($this->fiscalizacion_model->edit('BANCO',$data,'IDBANCO',$ID) == TRUE){
												//$this->codegen_model->delete('BANCO','IDBANCO',$ID);             
												$this->template->set('title', 'Fiscalizacion');
												$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El banco se eliminó correctamente.'.$ID.'</div>');
												redirect(base_url().'index.php/fiscalizacion/');
										}
									}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/fiscalizacion');
									 }
					}else
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
										
										$this->data['carteras']  = $this->cnm_servicio_medico_model->detalleCarteras($cedula, $id_cartera, $estado, $id_deuda);
									

										
										$this->load->view('cnm_servicio_medico/cnm_servicio_medico_datos_consulta', $this->data);
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

		 function detalle(){
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
							 {
					$this->template->set('title', 'Consulta Cartera No Misional');
										//add style an js files for inputs selects

										
										$id_cartera=$this->input->post('id_cartera');

										$this->data['carterasini']  = $this->cnm_servicio_medico_model->detalleprevioSMed($id_cartera)->result_array[0];
																											
										$this->data['carteras']  = $this->cnm_servicio_medico_model->detalleSMed($id_cartera, $this->data['carterasini']['MOD_TASA_CORRIENTE'], $this->data['carterasini']['MOD_TASA_MORA'], $this->data['carterasini']['CALCULO_CORRIENTE'], $this->data['carterasini']['CALCULO_MORA'])->result_array[0];
										//&var_dump($this->data['carteras']);
										//die();	
										//	var_dump($this->data['carteras']);
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
										
										
										$this->template->load($this->template_file, 'cnm_servicio_medico/cnm_servicio_medico_list', $this->data);
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
										$this->load->view('cnm_servicio_medico/cnm_servicio_medico_visualizar_pagos', $this->data);
								
							 
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
//cargue
    function subir_archivo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/add')) {
                $this->template->set('title', 'Asignación de Cartera de Servicio Médico');

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
                                $this->cargaarchivo($file, $filet);
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

                $this->template->load($this->template_file, 'cnm_servicio_medico/cnm_servicio_medico_result', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/fiscalizacion');
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

    private function cargaarchivo($file, $filet){
//abre el archivo temporal para su lectura
        $leer = fopen($filet, "r") or exit("Unable to open file!");
        while (!feof($leer)) {
            $linea = trim(fgets($leer));
            $estructura = explode(",", $linea);
            if (sizeof($estructura) > 3) {
                if (is_numeric($estructura[11]) && is_numeric($estructura[12]) && is_numeric($estructura[13])) {
                    $errores = 0;
                } else {
                    array_push($this->data['errores'], 'error de estructura linea ' . $this->data['numlineas']);
                }
            } else {
                $estructura = explode(";", $linea);
                if (is_numeric($estructura[11]) && is_numeric($estructura[12]) && is_numeric($estructura[13])) {
                    $errores = 0;
                } else {
                    array_push($this->data['errores'], 'error de estructura linea ' . $this->data['numlineas']);
                }
            }
            
            $f = $this->cnm_servicio_medico_model->guardarfila($estructura, $file);
            if ($f == FALSE) {
                array_push($this->data['errores'], 'error en la carga de linea ' . $this->data['numlineas']);
            }
            $this->data['numlineas'] ++;
									
        }
        fclose($leer);

    }

    function obtenerExtensionFichero($str) {
        return end(explode(".", $str));
    }

}
		