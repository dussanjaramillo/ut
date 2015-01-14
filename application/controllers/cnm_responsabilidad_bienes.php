<?php

class Cnm_responsabilidad_bienes extends MY_Controller {
		
		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');		
		$this->load->helper(array('form','url','codegen_helper','template_helper','traza_fecha_helper'));
		$this->load->model('cnm_responsabilidad_bienes_model','',TRUE);
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
	        					
								$this->template->set('title', 'Cargue Activos Dados de Baja');
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
							$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cargue para activos dados de baja realizado correctamente</div>');

		 				redirect(base_url().'index.php/carteranomisional/manage');	
							 
							 
							 
							 	} 
				}
				
								$this->session->set_flashdata('message', '');
								$this->data['message']=$this->session->flashdata('message');
								
								$this->data['tipos']  = $this->cnm_responsabilidad_bienes_model->getSelectTipoCartera('TIPOCARTERA','COD_TIPOCARTERA, NOMBRE_CARTERA');
								
								$this->template->load($this->template_file, 'cnm_responsabilidad_bienes/cnm_responsabilidad_bienes_cargue',$this->data); 			 
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
					$this->template->set('title', 'Asignación de Responsabilidad de Bienes');


										$this -> data['cod_activos']=$this->input->post('cod_activos');
										$this->template->load($this->template_file, 'cnm_responsabilidad_bienes/cnm_responsabilidad_bienes_add', $this->data);
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
		
		
				   function traercedula() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/manage')) {
        $nit = $this->input->get('term');
        if (empty($nit)) {
          redirect(base_url() . 'index.php/carteranomisional/add');
        } else {
          $this->cnm_responsabilidad_bienes_model->set_nit($nit);
          return $this->output->set_content_type('application/json')->set_output(json_encode($this->cnm_responsabilidad_bienes_model->buscarnits()));
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
				   
				   
				      		 function verificacion_activo($id_activo){

$data_emp = $this->cnm_responsabilidad_bienes_model->select_data_activo($id_activo);
//var_dump($data_emp);
//die();
print_r(json_encode($data_emp));

		} 		


   		 function verificacion_cedula($id){

$data_emp = $this->cnm_responsabilidad_bienes_model->select_data_cedula($id);
//var_dump($data_emp);
//die();
print_r(json_encode($data_emp));

		} 		

				   
				   
				   		function asignacion_res(){
    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/add'))
							 {

															
										$this->template->set('title', 'Asignación de Cartera de Responsabilidad Bienes');
										$cedula=$this->input->post('cedula');
										$orden=$this->input->post('orden');
										$this->load->library('datatables');	
										$this -> data['style_sheets']= array('css/jquery.dataTables_themeroller.css' => 'screen');
										if(!empty($orden))
										{	
											$this->data['cargas']=$this->cnm_responsabilidad_bienes_model->carguexorden($orden);
											$empleado=$this->data['cargas']->result_array();
											$infoemp=$this->cnm_responsabilidad_bienes_model->infoempleado($empleado[0]["NUM_IDENTIFICACION"])->result_array();
										
										}
										else {

										$this->data['cargas']=$this->cnm_responsabilidad_bienes_model->carguexcedula($cedula);
										$infoemp=$this->cnm_responsabilidad_bienes_model->infoempleado($cedula)->result_array();

										}
										$this->data['empleado']=$infoemp[0];

										$this -> data['cod_activos']=$this->input->post('cod_activos2');
										$this->load->view('cnm_responsabilidad_bienes/cnm_responsabilidad_bienes_asignacion_res', $this->data);
								
							 
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
							$this->template->set('title', 'Edición de Cartera Responsabilidad de Bienes');
								
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
																'COD_TIPOCARTERA' => '6',
																'COD_PROCESO_REALIZADO' => '1',
																'COD_REGIONAL' => $this->ion_auth->user()->row()->COD_REGIONAL,
																'COD_EMPLEADO' => $this->input->post('cedula'),
																'COD_ESTADO' => $this->input->post('tipo_estado_id'),			
																'COD_TIPO_ACUERDO' => $this->input->post('tipo_acuerdo_id'),	
																'VALOR_DEUDA' => $this->input->post('valor_deducible'),	
																'COD_FORMAPAGO' => $this->input->post('forma_pago_id'),	
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
												
									if ($this->cnm_responsabilidad_bienes_model->updateCartera('CNM_CARTERANOMISIONAL',$datacnm,$datecnm,$id_deuda,"COD_CARTERA_NOMISIONAL") == TRUE)					
									{

										$existbien=$this->cnm_responsabilidad_bienes_model->verificarExistenciaBien($this->input->post('id_bien'));
										if($existbien["EXIST"]==0)
										{
							
												$datebien = array(
																'FECHA_CREACION' =>  date("d/m/Y H:i:s"),
												);

												$databien = array(
																'COD_TIPOACTIVO' => $this->input->post('id_bien'),
																'NOMBRE_TIPOACTIVO' => $this->input->post('desc_bien'),	
																'COD_REGIONAL' => $this->input->post('regional_bien'),
																'PLACA_INVENTARIO' => $this->input->post('placa_inv'),	
																'VALOR_COMPRA' => $this->input->post('valor_compra'),

																													
												);
												$this->cnm_responsabilidad_bienes_model->add('CM_TIPOACTIVO',$databien,$datebien);
										
										}


												$date = array(
												'FECHA_CREACION' =>  date("d/m/Y H:i:s"),
												'FECHA_BAJA' =>  $this->input->post('fecha_baja'),
												'FECHA_RESOLUCION' =>  $this->input->post('fecha_resolucion'),
												'FECHA_ACTIVACION' =>  $this->input->post('fecha_activacion'),

												);

												$data = array(
																'COD_TIPO_ACTIVO' => $this->input->post('id_bien'),
																'NUM_RESOLUCION' => $this->input->post('numero_resolucion'),			
																'MOTIVO_DADO_BAJA' => $this->input->post('motivo_baja'),	
																'FORMA_PAGO' => $this->input->post('forma_pago_id'),	
																'ID_CODEUDOR' => $this->input->post('id_codeudor'),
																'NOMBRE_CODEUDOR' => $this->input->post('nombre_codeudor'),
																'MOD_TASA_CORRIENTE' => $mod_tasa_corr,
																'MOD_TASA_MORA' => $mod_tasa_mora,		
																													
												);
									if ($this->cnm_responsabilidad_bienes_model->updateCartera('CNM_CARTERA_RESPON_BIENES',$data,$date,$id_deuda,"COD_CARTERA") == TRUE)
									{
										trazarProcesoJuridico('546', '1357', '', '', $id_deuda, '', '', '', '','');
					
										$this->cnm_responsabilidad_bienes_model->updateCreado($this->input->post('id_bien'));
													
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
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Cartera Responsabilidad de Bienes ha sido editada con éxito.</div>');
				                         $datos = array('id_deuda'=>$id_deuda, 'tipo_cartera'=>'6', 'tabla_cartera'=>'CNM_CARTERA_RESPON_BIENES');
										 
            			 				$this->session->set_userdata('lolwut',$datos);			
													redirect(base_url().'index.php/carteranomisional/carga_archivo_edit/');
}
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Cartera Responsabilidad de Bienes ha sido editada con éxito.</div>');
				                         $datos = array('id_deuda'=>$id_deuda, 'tipo_cartera'=>'6', 'tabla_cartera'=>'CNM_CARTERA_RESPON_BIENES');
										 
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
//echo $id_cartera_esp;
//die();
$id_cartera=6;	
$this->data['detalleCart']  = $this->cnm_responsabilidad_bienes_model->detalleCarterasEdit($id_cartera_esp)->result_array[0];

$this->data['tipoCartera']  = $this->cnm_responsabilidad_bienes_model->tipoCartera($id_cartera);
$this->data['estado']  = $this->cnm_responsabilidad_bienes_model->selectTipoEstado();
$this->data['forma_pago']  = $this->cnm_responsabilidad_bienes_model->selectFormaPago();
$this->data['duracion']  = $this->cnm_responsabilidad_bienes_model->selectPlazo();
$this->data['acuerdos']  = $this->cnm_responsabilidad_bienes_model->selectAcuerdo($id_cartera);
if(!empty($this->data['acuerdos']->result_array[0]["COD_ACUERDOLEY"])){
$cod_ac_modalidad= $this->data['acuerdos']->result_array[0]["COD_ACUERDOLEY"];
}
else{
$cod_ac_modalidad= "";	
}
$this->data['acuerdo_prestamo']  = $cod_ac_modalidad;
$this->data['tipotasa']  = $this->cnm_responsabilidad_bienes_model->selectTipoTasaCombo();
$this->data['tipotasaesp']  = $this->cnm_responsabilidad_bienes_model->selectTasaEspCombo();
$this->data['tipotasaespC']  = $this->cnm_responsabilidad_bienes_model->selectTasaEspComboEdit($this->data['detalleCart']["CALCULO_CORRIENTE"]);
$this->data['tipotasaespV']  = $this->cnm_responsabilidad_bienes_model->selectTasaEspComboEdit($this->data['detalleCart']["CALCULO_MORA"]);	
										
										
										$this->template->load($this->template_file, 'cnm_responsabilidad_bienes/cnm_responsabilidad_bienes_edit', $this->data);
								
										
							 
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
		
		function add_activos(){


    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/add'))
							 {

															
										$this->template->set('title', 'Asignación de Responsabilidad de Bienes');

										$this->load->view('cnm_responsabilidad_bienes/cnm_responsabilidad_bienes_add_activos', $this->data);
								
							 
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
										
										$this->data['carteras']  = $this->cnm_responsabilidad_bienes_model->detalleCarteras($cedula, $id_cartera, $estado, $id_deuda);
									

										
										$this->load->view('cnm_responsabilidad_bienes/cnm_responsabilidad_bienes_datos_consulta', $this->data);
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
										$this->data['carterasini']  = $this->cnm_responsabilidad_bienes_model->detalleprevioRespB($id_cartera)->result_array[0];
										//var_dump($this->data['carterasini']);
										//die();																	
										$this->data['carteras']  = $this->cnm_responsabilidad_bienes_model->detalleRespB($id_cartera, $this->data['carterasini']['MOD_TASA_CORRIENTE'], $this->data['carterasini']['MOD_TASA_MORA'], $this->data['carterasini']['CALCULO_CORRIENTE'], $this->data['carterasini']['CALCULO_MORA'])->result_array[0];
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
										
										
										$this->template->load($this->template_file, 'cnm_responsabilidad_bienes/cnm_responsabilidad_bienes_list', $this->data);
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

										$this->load->view('cnm_responsabilidad_bienes/cnm_responsabilidad_bienes_visualizar_pagos', $this->data);
								
							 
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
                $this->template->set('title', 'AsignaciÃ³n de Cartera Responsabilidad de Bienes');
				
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
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Ã¡rea.</div>');
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

    private function cargaarchivo($file, $filet) {
		//abre el archivo temporal para su lectura
        $leer = fopen($filet, "r") or exit("Unable to open file!");
        while (!feof($leer)) {
            $linea = trim(fgets($leer));
            $estructura = explode(",", $linea);
            if (sizeof($estructura) > 3) {
                if (is_numeric($estructura[8]) && is_numeric($estructura[9]) && is_numeric($estructura[10])) {
                    $errores = 0;
                } else {
                    array_push($this->data['errores'], 'error de estructura linea ' . $this->data['numlineas']);
                }
            } else {
                $estructura = explode(";", $linea);
                if (is_numeric($estructura[8]) && is_numeric($estructura[9]) && is_numeric($estructura[10])) {
                    $errores = 0;
                } else {
                    array_push($this->data['errores'], 'error de estructura linea ' . $this->data['numlineas']);
                }
            }
            
                $f = $this->cnm_responsabilidad_bienes_model->guardarfila($estructura, $file);


	        if ($f == FALSE) {
                array_push($this->data['errores'], 'error en la carga de linea ' . $this->data['numlineas']);
            }
            $this->data['numlineas'] ++;
        }
        fclose($leer);
//        if (sizeof($this->data['errores']) == 0) {
//            $estructura = $this->validaestructura($file);
//        }
//        if (sizeof($this->data['errores']) == 0) {
//            $registros = $this->validaregistros($file);
//        }
//            if (sizeof($this->data['errores']) == 0) {
//                $planillas = $this->validaplanillas($file);
//            }
    }
    
    function obtenerExtensionFichero($str) {
        return end(explode(".", $str));
    }

}


