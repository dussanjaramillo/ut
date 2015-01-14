<?php
class Fiscalizacion extends MY_Controller {
		
		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');	
		$this->load->library('tcpdf/tcpdf.php');
		$this->load->library('tcpdf/Plantilla_PDF_Fiscalizacion.php');	
		$this->load->helper(array('form','url','codegen_helper','template_helper'));
		$this->load->model('fiscalizacion_model','',TRUE);
		
		 
		 
		 $this->data['javascripts'] = array(
            
            'js/tinymce/tinymce.jquery.min.js',
			'js/jquery.dataTables.min.js',
        	'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/validateForm.js'        	
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
	        
								         
										 
								$usuario= $this->ion_auth->user()->row()->IDUSUARIO;		 
								        

                       
                    $this->data['empresas']  = $this->fiscalizacion_model->selectEmpresaFisc($usuario);

                // var_dump( $this->data['empresas']);
										// die();
        
								
								//template data
								$this->template->set('title', 'Fiscalizacion');
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen',
														'css/validationEngine.jquery.css' => 'screen'
												);
								
								
								$this->data['message']=$this->session->flashdata('message');
								$this->template->load($this->template_file, 'fiscalizacion/fiscalizacion_list',$this->data); 
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

$this->cod_asign = $this->input->post('cod_asign');
$this->nit = $this->input->post('nit');    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/add'))
							 {
																 	                    	
																 	                    $this->load->library('form_validation');    
            		$this->data['custom_error'] = '';
                    $showForm=0;
					$finicio=$this->input->post('periodo_inicio');
					$ffin=$this->input->post('periodo_fin');
					$flag=$this->input->post('vista_flag');
if(!empty($flag)){
					
        			$this->form_validation->set_rules('periodo_inicio', 'periodo_inicio', 'required');
        			$this->form_validation->set_rules('periodo_fin', 'periodo_fin', 'required'); 
            			
					
                    if ($this->form_validation->run() == false)
                    {
                         $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                    } else
                    {
                   $finiciorep=str_replace('/', '-', $finicio); 
				   	$ffinrep=str_replace('/', '-', $ffin); 
					if(strtotime($finiciorep)< strtotime($ffinrep) ){
						if(strtotime($finiciorep)< strtotime(date("d-m-Y"))||strtotime($ffinrep)< strtotime(date("d-m-Y")) ){
						
					$concepto=$this->input->post('concepto_id');
        			$fechasFiscalizacion=$this->fiscalizacion_model->selectFechaFisc($concepto,$this->input->post('cod_asign'));
					$indicadorcrucefechas=0;					
					foreach ($fechasFiscalizacion as $data) {
					
					$start_date = $data->PERIODO_INI;
						//echo $start_date;
					$end_date = $data->PERIODO_FIN;
						//echo $end_date;
						
					$fecha_inicio_fiscalizacion = $this->input->post('periodo_inicio');
					$explode_fecha_ini=explode("/", $fecha_inicio_fiscalizacion);
					$fecha_inicio_fiscalizacion_rango=$explode_fecha_ini[0].'-'.$explode_fecha_ini[1].'-'.$explode_fecha_ini[2];
						//echo $fecha_inicio_fiscalizacion_rango;
					$fecha_fin_fiscalizacion = $this->input->post('periodo_fin');
					$explode_fecha_fin=explode("/", $fecha_fin_fiscalizacion);
					$fecha_fin_fiscalizacion_rango=$explode_fecha_fin[0].'-'.$explode_fecha_fin[1].'-'.$explode_fecha_fin[2];
					
						//echo $fecha_fin_fiscalizacion_rango;
					
					if ($this->check_in_range($start_date, $end_date, $fecha_inicio_fiscalizacion_rango)||$this->check_in_range($start_date, $end_date, $fecha_fin_fiscalizacion_rango)) {
					   $indicadorcrucefechas=1;
					   // echo "no puede agregar esta fiscalización";
						//no puede agregar esta fiscalización
					} else {
					    //echo "si puede agregar esta fiscalización";
						//si puede agregar esta fiscalización
					}
					
			}

					    if($indicadorcrucefechas==0){
                        $date = array( 
                                'PERIODO_INICIAL' => $this->input->post('periodo_inicio'),
                                'PERIODO_FINAL' => $this->input->post('periodo_fin')
                        );
                        $data = array(
                                                                
                                'COD_ASIGNACION_FISC' => $this->input->post('cod_asign'),
                                'COD_CONCEPTO' => $this->input->post('concepto_id'),
								'COD_TIPOGESTION' => '1'
                        );
                         
            			if ($this->fiscalizacion_model->addFiscalizacion('FISCALIZACION',$data,$date) == TRUE)
										
								{
										
								$nit= $this->fiscalizacion_model->selectNitxCodasign($this->input->post('cod_asign'));
								$idfiscalizacion=$this->fiscalizacion_model->selectIdFiscConsec('FISCALIZACION',$data,$date);
								
								/*if(mb_strlen($idfiscalizacion['COD_REGIONAL'])==1)
								{
									$codreg="0".$idfiscalizacion['COD_REGIONAL'];
								}
									else{
										$codreg=$idfiscalizacion['COD_REGIONAL'];
									}
									
								switch (mb_strlen($idfiscalizacion['IDENTIFICADOR'])){
								case 1:
									$idfisc="0000".$idfiscalizacion['IDENTIFICADOR'];
								break;	
									
								case 2:
									$idfisc="000".$idfiscalizacion['IDENTIFICADOR'];
								break;	
									
								case 3:
									$idfisc="00".$idfiscalizacion['IDENTIFICADOR'];
								break;	
									
								case 4:
									$idfisc="0".$idfiscalizacion['IDENTIFICADOR'];
								break;	
									
									default:
									$idfisc=$idfiscalizacion['IDENTIFICADOR'];	
									break;
								
								}
								
								if(mb_strlen($idfiscalizacion['COD_CONCEPTO'])==1)
								{
									$codconcept="0".$idfiscalizacion['COD_CONCEPTO'];
								}
									else{
										$codconcept=$idfiscalizacion['COD_CONCEPTO'];
									}
								$nro_expediente=$codreg.$idfisc.$codconcept.$idfiscalizacion['COD_FISCALIZACION'];
						  		
								//$this->fiscalizacion_model->updateGestionActualConsec('FISCALIZACION',$idfiscalizacion ['COD_FISCALIZACION'],$nro_expediente);	
								*/
						  		$dategestion = array( 
								'FECHA_CONTACTO' =>  date("d/m/Y  H:i"),
                        );
                        $datagestion = array(
                        
								'COMENTARIOS' => 'Fiscalización Creada',
	                            'COD_TIPO_RESPUESTA' => '1',
	                            'COD_TIPOGESTION' => '1',
                                'NIT_EMPRESA' => $nit ['NIT_EMPRESA'],
								'COD_FISCALIZACION_EMPRESA' => $idfiscalizacion['COD_FISCALIZACION'],
								 'COD_USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO
								
                        );				
                        				
                        		
                        
                        $this->fiscalizacion_model->addgestion('GESTIONCOBRO',$datagestion,$dategestion);
            			$idgestioncobro=$this->fiscalizacion_model->selectIdGestion('GESTIONCOBRO',$datagestion,$dategestion);

						
						$gestactual=$this->fiscalizacion_model->updateGestionActual('FISCALIZACION',$idgestioncobro['COD_GESTION_COBRO'],$idfiscalizacion['COD_FISCALIZACION'],'1');		

						$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Fiscalización se agregó con éxito.</div>');
                          
                         $datos = array('nit'=>$this->nit,'cod_asign'=>$this->cod_asign);
						
            			 $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');	
            			}
            			else
            			{
            				$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

            			}

}
            			else{
            				$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Esta empresa ya tiene una Fiscalizacion Creada para este periodo por este mismo Concepto. La Fiscalización no se ha agregado</div>');
                           $datos = array('nit'=>$this->nit,'cod_asign'=>$this->cod_asign);
						
            			 $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');
							
            			}

}else{
	//echo strtotime($finiciorep);

	$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>La Fecha de Inicio o la Fecha Fin es posterior a la Fecha Actual ó es la misma. La Fiscalización no se ha agregado</div>');
                           $datos = array('nit'=>$this->nit,'cod_asign'=>$this->cod_asign);
						
            			 $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');
								
	
}
}else{
	$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>La Fecha de Inicio esta despues de la Fecha Fin ó es la misma. La Fiscalización no se ha agregado</div>');
                           $datos = array('nit'=>$this->nit,'cod_asign'=>$this->cod_asign);
						
            			 $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');
								
	
}


						
            		}
        		} 


				//add style an js files for inputs selects

										$this->data['inicio']  = $finicio;
										$this->data['fin']  = $ffin;
										$this->data['conceptos']  = $this->fiscalizacion_model->getSelectConceptos('CONCEPTOSFISCALIZACION','COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO');
										$this->data['cod_asign']  = $this->cod_asign; 
										$this->data['nit']  = $this->nit; 
										$this->template->load($this->template_file, 'fiscalizacion/fiscalizacion_add', $this->data);
								
						
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



 
		 function datatable (){

				$this->nit_consulta = $this->input->post('cod_nit');	
				$this->cod_asignacion_fisc = $this->input->post('cod_asignacion_fisc');
			
		        $this->data['style_sheets']= array(
                        'css/jquery.dataTables_themeroller.css' => 'screen'
                );

						
				$this->nit = $this->input->post('nit');
				$this->load->library('datatables');
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable'))
							 {
if(!empty($this->cod_asignacion_fisc)){	
							 	$this->load->library('datatables');	
								$this->load->model('fiscalizacion_model');	
							 	$this->data['validacion']=$this->fiscalizacion_model->selectAsignado($this->cod_asignacion_fisc);
								
								$this->data['funcion']=$this->fiscalizacion_model->datatable1($this->nit_consulta, $this->cod_asignacion_fisc);
								$this->data['empresa']=$this->fiscalizacion_model->selectEmpresa($this->nit_consulta);
								$this->data['cod_asign_fisc']=$this->cod_asignacion_fisc;
								//var_dump($this->data['funcion']);
								
								//$this->template->load($this->template_file, 'fiscalizacion/fiscalizacion_validar_sop', $this->data);
								$this -> data['style_sheets']= array('css/jquery.dataTables_themeroller.css' => 'screen');
								$this->template->load($this->template_file, 'fiscalizacion/datatable',$this->data);
							 }
								else{
									
									redirect(base_url().'index.php/fiscalizacion/');
								}

								//echo $this->datatables->generate();
									
                   }else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/fiscalizacion');
									 }
						}else
						{
							redirect(base_url().'index.php/auth/login');
						}          
		}

function datatabler(){
		 		
				
		 	$datos=($this->session->flashdata('lolwut'));
			$this->data['message']=$this->session->flashdata('message');

				$this->nit_consulta = $datos["nit"];	
				$this->cod_asignacion_fisc = $datos["cod_asign"];

		        $this->data['style_sheets']= array(
                        'css/jquery.dataTables_themeroller.css' => 'screen'
                );

							
				$this->nit = $this->input->post('nit');
				$this->load->library('datatables');
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
							 {
							
							
							 	$this->load->library('datatables');	
								$this->load->model('fiscalizacion_model');	
							 	$this->data['validacion']=$this->fiscalizacion_model->selectAsignado($this->cod_asignacion_fisc);
								
							 	$this->data['funcion']=$this->fiscalizacion_model->datatable1($this->nit_consulta, $this->cod_asignacion_fisc);
								$this->data['empresa']=$this->fiscalizacion_model->selectEmpresa($this->nit_consulta);
								$this->data['cod_asign_fisc']=$this->cod_asignacion_fisc;
								//var_dump($this->data['funcion']);
								
								//$this->template->load($this->template_file, 'fiscalizacion/fiscalizacion_validar_sop', $this->data);
								$this -> data['style_sheets']= array('css/jquery.dataTables_themeroller.css' => 'screen');
								$this->template->load($this->template_file, 'fiscalizacion/datatable',$this->data);
								
								//$this->load->library('datatables');
								
																			
						
                   }else {
										/*$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/fiscalizacion');*/ 
									 }
						}else
						{
							//redirect(base_url().'index.php/auth/login');
						}          
		}

function dataTableEmpresas (){
			
 $this->datagestion['style_sheets']= array(
                        'css/jquery.dataTables_themeroller.css' => 'screen'
                );

                
                
$this->nit_emp = $this->input->post('idemp');


				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/manage'))
							 {
							
							 
							 	$this->load->library('datatables');	
								$this->load->model('fiscalizacion_model');	
							 	$this->dataempresa['infoempresa']=$this->fiscalizacion_model->selectEmpresaFiscporNit($this->nit_emp);
								
								
								//$this->template->load($this->template_file, 'fiscalizacion/fiscalizacion_validar_sop', $this->data);
								$this -> dataempresa['style_sheets']= array('css/jquery.dataTables_themeroller.css' => 'screen');
								$this->load->view('fiscalizacion/fiscalizacion_empresas',$this->dataempresa);
															
							 							


																											 

								//echo $this->datatables->generate();
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/fiscalizacion');
									 }
						}else
						{
							redirect(base_url().'index.php/auth/login');
						}           
		}

function registro_onbase (){
		date_default_timezone_set("America/Bogota");
 $this->data['style_sheets']= array(
                        'css/jquery.dataTables_themeroller.css' => 'screen'
                );

                
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
							 {
							$this->cod_gestion = $this->input->post('cod_gestion_cobro');		
							
		 	                    $this->load->library('form_validation');    
            		$this->data['custom_error'] = '';
                    $showForm=0;
					//$cod_asign=$this->fiscalizacion_model->selectCodasignxCodFisc($this->cod_fisc);
						
$flag=$this->input->post('vista_flag');
if(!empty($flag)){
      			$this->form_validation->set_rules('registro_id', 'Registro', 'required'); 
				$this->form_validation->set_rules('fecha_id', 'Fecha', 'required'); 
					
                    if ($this->form_validation->run() == false)
                    {
                    	
						
                         $this->dataemail['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                    } else
                    {
                    	$this->cod_gestion = $this->input->post('vista_flag');	
						
						
                        	$dategestionemail = array( 
								'FECHA_RADICADO' =>  $this->input->post('fecha_id')
                        );
                        $datagestionemail= array(
                        
								'NRO_RADICADO_ONBASE' => $this->input->post('registro_id'),
	                            'COD_GESTION_COBRO' => $this->cod_gestion
                        );				

                         
            			if ($this->fiscalizacion_model->updateGestion('GESTIONCOBRO',$datagestionemail,$dategestionemail) == TRUE)
										
								{
									$fiscnit=$this->fiscalizacion_model->selectcod_fiscnit($this->cod_gestion);	
							$this->fiscalizacion_model->updateTipoGestionActual('FISCALIZACION',"",$fiscnit["COD_FISCALIZACION_EMPRESA"],'509');
									$cod_asign=$this->fiscalizacion_model->selectCodasignxCodFisc($fiscnit["COD_FISCALIZACION_EMPRESA"]);
											
						$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Registro OnBase se agregó con éxito.</div>');

                         $datos = array('nit'=>$fiscnit["NIT_EMPRESA"],'cod_asign'=>$cod_asign["COD_ASIGNACION_FISC"]);
						
            			 $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');		

            			}
            			else
            			{
            				$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

            			}
            		} 
            	}
else{
										
										$this->data['custom_error'] = '';
										//add style an js files for inputs selects
										$this->data['cod_gestion']  = $this->cod_gestion; 
										//$this->data['fecha']  = date("d/m/Y H:i"); 
										$this->load->view('fiscalizacion/fiscalizacion_registro_onbase',$this->data);
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

		 function dataTableGestion (){
			
 $this->datagestion['style_sheets']= array(
                        'css/jquery.dataTables_themeroller.css' => 'screen'
                );

                
                
$this->consultar_gestion = $this->input->post('id');


				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
							 {

							 	$this->load->library('datatables');	
								$this->load->model('fiscalizacion_model');	
							 	$this->datagestion['funcion']=$this->fiscalizacion_model->datatablegestion($this->consultar_gestion);
								$this->datagestion['cod_fisc']=$this->consultar_gestion;
								
								$this->datagestion['validacion']=$this->fiscalizacion_model->selectAsignadoxCodfisc($this->consultar_gestion);

								//var_dump($this->datagestion['liquidacion']);
								//die();
								
								//$this->template->load($this->template_file, 'fiscalizacion/fiscalizacion_validar_sop', $this->data);
								$this -> datagestion['style_sheets']= array('css/jquery.dataTables_themeroller.css' => 'screen');
								$this->load->view('fiscalizacion/datatablegestion',$this->datagestion);

								//echo $this->datatables->generate();
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/fiscalizacion');
									 }
						}else
						{
							redirect(base_url().'index.php/auth/login');
						}           
		}


function ingresar_llamada(){
					
$this->cod_fisc = $this->input->post('cod_fisc');
$this->nit = $this->input->post('nit');

	date_default_timezone_set("America/Bogota");
	
				$id=$this->ion_auth->user()->row()->IDUSUARIO;
		    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
							 {
																 	                    $this->load->library('form_validation');    
            		$this->data['custom_error'] = '';
                    $showForm=0;
					$cod_asign=$this->fiscalizacion_model->selectCodasignxCodFisc($this->cod_fisc);
						
$flag=$this->input->post('vista_flag');
if(!empty($flag)){
      			
      			$this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean|max_length[200]'); 

					
                    if ($this->form_validation->run() == false)
                    {
                    	
						
                         $this->dataemail['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                    } else
                    {
                    	
                        	$dategestionemail = array( 
								'FECHA_CONTACTO' =>  date("d/m/Y  H:i")
                        );
                        $datagestionemail= array(
                        
								'COMENTARIOS' => base64_encode($this->input->post('descripcion')),
	                            'COD_TIPO_RESPUESTA' => '2',
	                            'COD_TIPOGESTION' => '2',
                                'NIT_EMPRESA' => $this->nit,
								'COD_FISCALIZACION_EMPRESA' => $this->cod_fisc,
								'COD_USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO
                        );				

                         
            			if ($this->fiscalizacion_model->addgestion('GESTIONCOBRO',$datagestionemail,$dategestionemail) == TRUE)
										
								{
									
							$idgestioncobro=$this->fiscalizacion_model->selectIdGestion('GESTIONCOBRO',$datagestionemail,$dategestionemail);		
                        	$gestactual=$this->fiscalizacion_model->updateTipoGestionActual('FISCALIZACION',$idgestioncobro['COD_GESTION_COBRO'],$this->input->post('cod_fisc'),'2');
                        	
							$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La llamada se agregó con éxito.</div>');

                         $datos = array('nit'=>$this->nit,'cod_asign'=>$cod_asign["COD_ASIGNACION_FISC"]);
						
            			 $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');		

            			}
            			else
            			{
            				$this->dataemail['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

            			}
            		} 
            	}
else{
										
										$this->dataemail['custom_error'] = '';
										$this->dataemail['cod_fisc']  = $this->cod_fisc;
										$this->dataemail['nit']  = $this->nit;
										$this->dataemail['fecha']  = date("d/m/Y H:i"); 
										$this->load->view('fiscalizacion/fiscalizacion_ingresar_llamada', $this->dataemail);
}								
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
		
		function ingresar_nueva_gestion(){
					
$this->cod_fisc = $this->input->post('cod_fisc');
$this->nit = $this->input->post('nit');

	date_default_timezone_set("America/Bogota");
	
				$id=$this->ion_auth->user()->row()->IDUSUARIO;
		    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
							 {
																 	                    $this->load->library('form_validation');    
            		$this->data['custom_error'] = '';
                    $showForm=0;
					$cod_asign=$this->fiscalizacion_model->selectCodasignxCodFisc($this->cod_fisc);
						
$flag=$this->input->post('vista_flag');
if(!empty($flag)){
      			
      			$this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean|max_length[200]'); 

					
                    if ($this->form_validation->run() == false)
                    {
                    	
						
                         $this->dataemail['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                    } else
                    {
                    	
                        	$dategestionemail = array( 
								'FECHA_CONTACTO' =>  date("d/m/Y  H:i")
                        );
                        $datagestionemail= array(
                        
								'COMENTARIOS' => base64_encode($this->input->post('descripcion')),
	                            'COD_TIPO_RESPUESTA' => '1031',
	                            'COD_TIPOGESTION' => '2',
                                'NIT_EMPRESA' => $this->nit,
								'COD_FISCALIZACION_EMPRESA' => $this->cod_fisc,
								'COD_USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO
                        );				

                         
            			if ($this->fiscalizacion_model->addgestion('GESTIONCOBRO',$datagestionemail,$dategestionemail) == TRUE)
										
								{
									
							$idgestioncobro=$this->fiscalizacion_model->selectIdGestion('GESTIONCOBRO',$datagestionemail,$dategestionemail);		
                        	$gestactual=$this->fiscalizacion_model->updateTipoGestionActual('FISCALIZACION',$idgestioncobro['COD_GESTION_COBRO'],$this->input->post('cod_fisc'),'2');
                        	
							$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La gestión se agregó con éxito.</div>');

                         $datos = array('nit'=>$this->nit,'cod_asign'=>$cod_asign["COD_ASIGNACION_FISC"]);
						
            			 $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');		

            			}
            			else
            			{
            				$this->dataemail['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

            			}
            		} 
            	}
else{
										
										$this->dataemail['custom_error'] = '';
										$this->dataemail['cod_fisc']  = $this->cod_fisc;
										$this->dataemail['nit']  = $this->nit;
										$this->dataemail['fecha']  = date("d/m/Y H:i"); 
										$this->load->view('fiscalizacion/fiscalizacion_ingresar_gestion', $this->dataemail);
}								
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
		

		
function anulacion(){
					
$this->cod_fisc = $this->input->post('cod_fisc');
$this->nit = $this->input->post('nit');

	date_default_timezone_set("America/Bogota");
	
				$id=$this->ion_auth->user()->row()->IDUSUARIO;
		    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
							 {
																 	                    $this->load->library('form_validation');    
            		$this->data['custom_error'] = '';
                    $showForm=0;
					$cod_asign=$this->fiscalizacion_model->selectCodasignxCodFisc($this->cod_fisc);
						
$flag=$this->input->post('vista_flag');
if(!empty($flag)){
      			
      			$this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean|max_length[200]'); 

					
                    if ($this->form_validation->run() == false)
                    {
                    	
						
                         $this->dataemail['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                    } else
                    {
                    	
                        	$dategestionemail = array( 
								'FECHA_CONTACTO' =>  date("d/m/Y  H:i")
                        );
                        $datagestionemail= array(
                        
								'COMENTARIOS' => base64_encode($this->input->post('descripcion')),
	                            'COD_TIPO_RESPUESTA' => '880',
	                            'COD_TIPOGESTION' => '309',
                                'NIT_EMPRESA' => $this->nit,
								'COD_FISCALIZACION_EMPRESA' => $this->cod_fisc,
								'COD_USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO
                        );				

                         
            			if ($this->fiscalizacion_model->addgestion('GESTIONCOBRO',$datagestionemail,$dategestionemail) == TRUE)
										
								{
									
							$idgestioncobro=$this->fiscalizacion_model->selectIdGestion('GESTIONCOBRO',$datagestionemail,$dategestionemail);		
                        	$gestactual=$this->fiscalizacion_model->updateGestionActual('FISCALIZACION',$idgestioncobro['COD_GESTION_COBRO'],$this->input->post('cod_fisc'),'309');
                        	
							$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La anulación se realizó con éxito.</div>');

                         $datos = array('nit'=>$this->nit,'cod_asign'=>$cod_asign["COD_ASIGNACION_FISC"]);
						
            			 $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');		

            			}
            			else
            			{
            				$this->dataemail['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

            			}
            		} 
            	}
else{
										
										$this->dataemail['custom_error'] = '';
										$this->dataemail['cod_fisc']  = $this->cod_fisc;
										$this->dataemail['nit']  = $this->nit;
										$this->dataemail['fecha']  = date("d/m/Y H:i"); 
										$this->load->view('fiscalizacion/fiscalizacion_anulacion', $this->dataemail);
}								
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
		
		
function notificacion_personal(){
					
$this->cod_fisc = $this->input->post('cod_fisc');
$this->nit = $this->input->post('nit');

	date_default_timezone_set("America/Bogota");
	
				$id=$this->ion_auth->user()->row()->IDUSUARIO;
		    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
							 {
																 	                    $this->load->library('form_validation');    
            		$this->data['custom_error'] = '';
                    $showForm=0;
					$cod_asign=$this->fiscalizacion_model->selectCodasignxCodFisc($this->cod_fisc);
						
$flag=$this->input->post('vista_flag');
if(!empty($flag)){
      			$file = $this->do_upload_com_personal($this->cod_fisc, $this->input->post('nit'), $cod_asign["COD_ASIGNACION_FISC"]);
      			$this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean|max_length[200]'); 

					
                    if ($this->form_validation->run() == false)
                    {
                    	
						
                         $this->dataemail['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                    } else
                    {
                    	
                        	$dategestionemail = array( 
								'FECHA_CONTACTO' =>  date("d/m/Y  H:i")
                        );
                        $datagestionemail= array(
                        
								'COMENTARIOS' => base64_encode($this->input->post('descripcion')),
	                            'COD_TIPO_RESPUESTA' => '652',
	                            'COD_TIPOGESTION' => '2',
                                'NIT_EMPRESA' => $this->nit,
								'COD_FISCALIZACION_EMPRESA' => $this->cod_fisc,
								'COD_USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO,
								'DOCUMENTO' => 'uploads/fiscalizaciones/'.$this->cod_fisc.'/comunicacionpersonal/'.$file['upload_data']['file_name']
                        );				

                         
            			if ($this->fiscalizacion_model->addgestion('GESTIONCOBRO',$datagestionemail,$dategestionemail) == TRUE)
										
								{
									
							$idgestioncobro=$this->fiscalizacion_model->selectIdGestion('GESTIONCOBRO',$datagestionemail,$dategestionemail);		
                        	$gestactual=$this->fiscalizacion_model->updateTipoGestionActual('FISCALIZACION',$idgestioncobro['COD_GESTION_COBRO'],$this->input->post('cod_fisc'),'2');
                        	
							$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Información de la Comunicación Personal se agregó con éxito.</div>');

                         $datos = array('nit'=>$this->nit,'cod_asign'=>$cod_asign["COD_ASIGNACION_FISC"]);
						
            			 $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');		

            			}
            			else
            			{
            				$this->dataemail['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

            			}
            		} 
            	}
else{
										
										$this->dataemail['custom_error'] = '';
										$this->dataemail['cod_fisc']  = $this->cod_fisc;
										$this->dataemail['nit']  = $this->nit;
										$this->dataemail['fecha']  = date("d/m/Y H:i"); 
										$this->load->view('fiscalizacion/fiscalizacion_notificacion_personal', $this->dataemail);
}								
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

function do_upload_com_personal ($cod_fisc, $nit, $codasign) {

	
date_default_timezone_set("America/Bogota");
	
$nombre_carpeta = './uploads/fiscalizaciones/'.$cod_fisc;
$nombre_subcarpeta = './uploads/fiscalizaciones/'.$cod_fisc.'/comunicacionpersonal';

if(!is_dir($nombre_carpeta)){ 
@mkdir($nombre_carpeta, 0777); 
}else{ 

}  
if(!is_dir($nombre_subcarpeta)){ 
@mkdir($nombre_subcarpeta, 0777); 
}else{ 

}

    $config['upload_path'] = './uploads/fiscalizaciones/'.$cod_fisc.'/comunicacionpersonal';
	
    $config['allowed_types'] = 'pdf';
    $config['max_size'] = '5000';

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload()) {
      					$datos = array('nit'=>$nit,'cod_asign'=>$codasign);
						
						$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Tamaño de archivo excedido del limite permitido 5Mb, la gestión no ha sido registrada intente nuevamente.</div>');
               
            			 $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');
    } else {
      return $data = array('upload_data' => $this->upload->data());
    }
  }

function ver_archivo() {
$this->cod_gestion_cobro = $this->input->post('archivo_gest');
 

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
               {

                    	
						
				$this->data['archivo']  = $this->fiscalizacion_model->selectArchivo($this->cod_gestion_cobro);		 
              
               //template data
                $this->template->set('title', 'Fiscalización');
                $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->data['message']=$this->session->flashdata('message');
				
				
				
                $this->load->view('fiscalizacion/fiscalizacion_ver_archivo',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }

}

function detalle_asistencia_sena(){


	date_default_timezone_set("America/Bogota");
	
				$id=$this->ion_auth->user()->row()->IDUSUARIO;
		    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
							 {
																 	                    $this->load->library('form_validation');    
            		$this->data['custom_error'] = '';
                    $showForm=0;
					
						
$flag=$this->input->post('vista_flag');
if(!empty($flag)){
	
	$this->asistencia= $this->input->post('asiste_id');

      			$this->cod_fisc = $this->input->post('cod_fisc');
				$this->nit = $this->input->post('nit');
      			$this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean|max_length[200]'); 
				$cod_asign=$this->fiscalizacion_model->selectCodasignxCodFisc($this->cod_fisc);
					
                    if ($this->form_validation->run() == false)
                    {
                    	
						
                         $this->dataemail['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                    } else
                    {
                    	
                        	$dategestionemail = array( 
								'FECHA_CONTACTO' =>  date("d/m/Y  H:i")
                        );
						if($this->asistencia==0){
                        $datagestionemail= array(
                        
								'COMENTARIOS' => $this->input->post('descripcion'),
	                            
	                            'COD_TIPO_RESPUESTA' => '674',
	                            'COD_TIPOGESTION' => '248',
	                            'NIT_EMPRESA' => $this->nit,
								'COD_FISCALIZACION_EMPRESA' => $this->cod_fisc,
								'COD_USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO
                        );				
 }
						else
							{
						  $datagestionemail= array(
                        
								'COMENTARIOS' => $this->input->post('descripcion'),
	                            
	                            'COD_TIPO_RESPUESTA' => '651',
	                            'COD_TIPOGESTION' => '248',
	                            'NIT_EMPRESA' => $this->nit,
								'COD_FISCALIZACION_EMPRESA' => $this->cod_fisc,
								'COD_USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO
                        );					
								
							}
                         
            			if ($this->fiscalizacion_model->addgestion('GESTIONCOBRO',$datagestionemail,$dategestionemail) == TRUE)
										
								{
									
							$idgestioncobro=$this->fiscalizacion_model->selectIdGestion('GESTIONCOBRO',$datagestionemail,$dategestionemail);		
                        	$gestactual=$this->fiscalizacion_model->updateGestionActual('FISCALIZACION',$idgestioncobro['COD_GESTION_COBRO'],$this->cod_fisc,'248');
						

                        	
							$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Información de la Notificación se agregó con éxito.</div>');

                         $datos = array('nit'=>$this->nit,'cod_asign'=>$cod_asign["COD_ASIGNACION_FISC"]);
						
            			 $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');		

            			}
            			else
            			{
            				$this->dataemail['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

            			}
            		} 
            	}
else{
										
										$this->cod_gestion = $this->input->post('cod_gestion');					
										$cod_fisc_nit=$this->fiscalizacion_model->selectcod_fiscnit($this->cod_gestion);
										$this->dataemail['custom_error'] = '';
										$this->dataemail['cod_fisc']  = $cod_fisc_nit['COD_FISCALIZACION_EMPRESA'];
										$this->dataemail['nit']  = $cod_fisc_nit['NIT_EMPRESA'];
										$this->dataemail['fecha']  = date("d/m/Y H:i"); 
										$this->load->view('fiscalizacion/fiscalizacion_detalle_asistencia_sena', $this->dataemail);
}								
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







function validar_sop(){
					
$this->cod_fisc = $this->input->post('cod_fisc');
$this->nit = $this->input->post('nit');
$this->cod_concepto = $this->input->post('cod_concepto');	
$empresa=$this->fiscalizacion_model->selectEmpresa($this->nit);
					
$cod_asign=$this->fiscalizacion_model->selectCodasignxCodFisc($this->cod_fisc);
					
	date_default_timezone_set("America/Bogota");
	
				$id=$this->data['user'] = $this->ion_auth->user()->row();

		    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/validar_sop'))
							 {
					$this->data['custom_error'] = '';
					
					$flag=$this->input->post('vista_flag');

if(!empty($flag)){

                    $this->load->library('form_validation');    
            		
                    $showForm=0;

        			$this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean|max_length[200]'); 
            		
                    if ($this->form_validation->run() == false)
                    {
                         $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                    } else
                    {
                    	$this->load->helper('traza_fecha_helper');
						                    	
						$file = $this->do_upload($this->input->post('cod_fisc'), $this->input->post('nit'), $cod_asign["COD_ASIGNACION_FISC"]);

						
                        	$dategestion = array( 
								'FECHA_CONTACTO' =>  date("d/m/Y H:i"),
                        );
                        $datagestion = array(
                        
								  
                        		'COMENTARIOS' => base64_encode($this->input->post('descripcion')),
	                            'COD_TIPO_RESPUESTA' => '12',
	                            'COD_TIPOGESTION' => '10',
                                'NIT_EMPRESA' => $this->input->post('nit'),
								'COD_FISCALIZACION_EMPRESA' => $this->input->post('cod_fisc'),
								'COD_USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO
                        );				
                        				
                        	$this->fiscalizacion_model->addgestion('GESTIONCOBRO',$datagestion,$dategestion);	
                        	$idgestioncobro=$this->fiscalizacion_model->selectIdGestion('GESTIONCOBRO',$datagestion,$dategestion);		
                        	
                        	$gestactual=$this->fiscalizacion_model->updateGestionActual('FISCALIZACION',$idgestioncobro['COD_GESTION_COBRO'],$this->input->post('cod_fisc'),'10');	
							    
                        $date = array( 
								'FECHA_DOCUMENTO' =>  date("d/m/Y H:i"),
                        );
                        $data = array(
                        
								'NOMBRE_ARCHIVO' => $file['upload_data']['file_name'],   
                        		'COMENTARIOS' => base64_encode($this->input->post('descripcion')),
	                            'APROBADO' => '1',
                                'COD_GESTION_COBRO' => $idgestioncobro['COD_GESTION_COBRO'],
								'COD_USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO
                        );
                         
            			if ($this->fiscalizacion_model->addsoportevalidacion('SOPORTESGESTIONCOBRO',$data,$date) == TRUE)
										
								{
									enviarcorreosena('tecnicocarterasena@gmail.com',"Por favor ingresar a la aplicacion y realizar la verificación del soporte de pago pendiente para la 
                        			fiscalización # ".$this->input->post('cod_fisc')." correspondiente a la empresa ". $empresa['NOMBRE_EMPRESA']." identificada con nit ". $empresa['CODEMPRESA'],"Verificación Nuevo Soporte de Pago","","","");		
                        	
            			  		$this->fiscalizacion_model->selectEmpresa($this->nit);
								
								$this->data['funcion']=$this->fiscalizacion_model->datatable1($this->nit, $cod_asign["COD_ASIGNACION_FISC"]);
								$this->data['empresa']=$this->fiscalizacion_model->selectEmpresa($this->nit);
								$this->data['cod_asign_fisc']=$cod_asign["COD_ASIGNACION_FISC"];	
							
							$datos = array('nit'=>$this->nit,'cod_asign'=>$cod_asign["COD_ASIGNACION_FISC"]);
						
            			  $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Soporte Agregado Correctamente</div>');
                
            			  $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');
								
						  
            			}
            			else
            			{
            				$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

            			}
            		} 
    		}
										$this->data['conceptos']  = $this->fiscalizacion_model->getSelectConceptos('CONCEPTOSFISCALIZACION','COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO');
										
										$this->data['cod_fisc']  = $this->cod_fisc;
										
										$this->data['nit']  = $this->nit;
										$this->data['cod_concepto']  = $this->cod_concepto;


										
										$this->data['fecha']  = date("d/m/Y"); 
										$this->data['hora']  = date("H:i"); 
										$this->template->load($this->template_file, 'fiscalizacion/fiscalizacion_validar_sop',$this->data);

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




function do_upload($cod_fisc, $nit, $codasign) {

	
date_default_timezone_set("America/Bogota");
	
$nombre_carpeta = './uploads/fiscalizaciones/'.$cod_fisc;
$nombre_subcarpeta = './uploads/fiscalizaciones/'.$cod_fisc.'/validarsoportespagos';

if(!is_dir($nombre_carpeta)){ 
@mkdir($nombre_carpeta, 0777); 
}else{ 

}  
if(!is_dir($nombre_subcarpeta)){ 
@mkdir($nombre_subcarpeta, 0777); 
}else{ 

}

    $config['upload_path'] = './uploads/fiscalizaciones/'.$cod_fisc.'/validarsoportespagos';
	
    $config['allowed_types'] = 'pdf';
    $config['max_size'] = '5000';

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload()) {
							$datos = array('nit'=>$nit,'cod_asign'=>$codasign);
						
							$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Tamaño de archivo excedido del limite permitido 5Mb, la gestión no ha sido registrada intente nuevamente.</div>');
               
            			  $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');
	  
    } else {
      return $data = array('upload_data' => $this->upload->data());
    }
  }


function consultarAsignacionFisc(){
	echo $_POST["parametro"];
	
	die();
	
}

function novedades(){
	
$this->cod_fisc = $this->input->post('cod_fisc');
$this->nit = $this->input->post('nit');
$this->cod_concepto = $this->input->post('cod_concepto');	
	

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
               {
                //template data
                $this->template->set('title', 'Fiscalización');
                $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->data['message']=$this->session->flashdata('message');
				$this->data['cod_fisc']  = $this->cod_fisc;
												$this->data['liquidacion']=$this->fiscalizacion_model->selectCountLiq($this->cod_fisc);							
								
								if($this->data['liquidacion']['LIQUIDACION']>0)
								{
								$this->data['liquidaciondet']=$this->fiscalizacion_model->selectDetLiq($this->cod_fisc);							
								}
								else{$this->data['liquidaciondet']['LIQUIDACION']="";
									$this->data['liquidaciondet']['FECHA_RESOLUCION']="";
}
				$this->data['nit']  = $this->nit;
				$this->data['cod_concepto']  = $this->cod_concepto;
				$count_auto=0;
				$cod_gest=0;
				$email_autorizado='';
				$this->data['aprobacion_envio_email']  = $this->fiscalizacion_model->selectAprobacionEmail($this->cod_fisc);
				if(!empty($this->data['aprobacion_envio_email'])){
				foreach ($this->data['aprobacion_envio_email']->result_array as $data) {
					if($data['AUTORIZA']=='S'){
						$count_auto=$count_auto+1;
					if($cod_gest<$data ["COD_GESTION_COBRO"])
					{
						$email_autorizado=$data["EMAIL_AUTORIZADO"];	
						$cod_gest= $data ["COD_GESTION_COBRO"];
					}	
					}
					
				}
				}
				//var_dump($this->data['aprobacion_envio_email']);
				//die();
				$this->data['count_aprobacion_envio_email']  = $count_auto;		
				$this->data['email_aprobado']  = $email_autorizado;		
				 
				$this->data['visitas_programadas']  = $this->fiscalizacion_model->selectCountVisitas($this->cod_fisc);		 
				$this->data['informes_realizados']  = $this->fiscalizacion_model->selectCountInformes($this->cod_fisc);		 
				$this->data['visitas_presencia']  = $this->fiscalizacion_model->selectCountVisitasPresencia($this->cod_fisc);		 
				$this->data['informe_presencia']  = $this->fiscalizacion_model->selectCountInformePresencia($this->cod_fisc);		 
				
				//var_dump($this->data['visitas_programadas']);
				//die();
                $this->load->view('fiscalizacion/fiscalizacion_novedades',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}


function aprobacion_soporte(){
$this->cod_gestion = $this->input->post('cod_gestion');

					
		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/aprobacion_soporte'))
               {
               	
				
				
                //template data
                $this->template->set('title', 'Fiscalización');
                $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->data['message']=$this->session->flashdata('message');
				$this->data['aprobacion']=$this->fiscalizacion_model->selectAprobacion($this->cod_gestion);
				
				
				$explodefecha=explode(" ", $this->data['aprobacion']['FECHA']);
				$this->data['fecha']=$explodefecha[0];
				$this->data['hora']=$explodefecha[1];
				$this->data['conceptos']  = $this->fiscalizacion_model->getSelectConceptos('CONCEPTOSFISCALIZACION','COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO');
				$explodefechacarpeta=explode("/", $this->data['fecha']);
				$this->data['fecha_carpeta']=$explodefechacarpeta[0].$explodefechacarpeta[1].$explodefechacarpeta[2];
				
				
                $this->load->view('fiscalizacion/fiscalizacion_aprobacion_soporte',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}

function respuesta_aprobacion_soporte(){
$this->cod_gestion = $this->input->post('cod_gestion_cobro');
$this->cod_soporte_gestion = $this->input->post('cod_soporte_gestion_cobro');
date_default_timezone_set("America/Bogota");
	
					
		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/aprobacion_soporte'))
               {
			$this->form_validation->set_rules('descripcion_resp_sop', 'Descripción', 'required|trim|xss_clean|max_length[200]'); 
               
               if ($this->form_validation->run() == false)
                    {
                         $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                    } else
                    {
													
									$cod_fisc_nit=$this->fiscalizacion_model->selectcod_fiscnit($this->input->post('cod_gestion_cobro'));				
											
                    	$cod_asign=$this->fiscalizacion_model->selectCodasignxCodFisc($cod_fisc_nit['COD_FISCALIZACION_EMPRESA']);
						$nit_nueva_gestion=$cod_fisc_nit['NIT_EMPRESA'];
						$cod_fisc_nueva_gestion=$cod_fisc_nit['COD_FISCALIZACION_EMPRESA'];
						
						
												$daterespuesta = array( 
								'FECHA_CONTACTO' =>  date("d/m/Y H:i"),
                        );
                        $datarespuesta = array(
                        
								  
                        		'COMENTARIOS' => base64_encode($this->input->post('descripcion_resp_sop')),
	                            'COD_TIPO_RESPUESTA' => $this->input->post('respuesta_id'),
	                            'COD_TIPOGESTION' => '11',
                                'NIT_EMPRESA' => $nit_nueva_gestion,
								'COD_FISCALIZACION_EMPRESA' => $cod_fisc_nueva_gestion,
								'COD_USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO
                        );				
                        				
                        	$this->fiscalizacion_model->addgestion('GESTIONCOBRO',$datarespuesta,$daterespuesta);	
						
						$idgestioncobro=$this->fiscalizacion_model->selectIdGestion('GESTIONCOBRO',$datarespuesta,$daterespuesta);
						$gestactual=$this->fiscalizacion_model->updateGestionActual('FISCALIZACION',$idgestioncobro['COD_GESTION_COBRO'],$cod_fisc_nueva_gestion,'11');	
						//
						
						
						$fechaupdate=$this->input->post('fecha')." ".$this->input->post('hora');
						
                        $date = array( 
                                'FECHA_APROBACION' => $fechaupdate
                        );
                        $data = array(
                                                                
                                'COMENTARIOS_APROBACION' => base64_encode($this->input->post('descripcion_resp_sop')),
                                'COD_SOPORTE_GESTION' => $this->input->post('cod_soporte_gestion'),
                                'COD_GESTIONCOBRO_RESP' =>$idgestioncobro['COD_GESTION_COBRO']
                        );
                         
                         
            			if ($this->fiscalizacion_model->updateRespuestaAprobacion('SOPORTESGESTIONCOBRO',$data,$date) == TRUE)
										
								{
            			  $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La respuesta de validación se agregó con éxito.</div>');

                         $datos = array('nit'=>$cod_fisc_nit['NIT_EMPRESA'],'cod_asign'=>$cod_asign["COD_ASIGNACION_FISC"]);
						
            			 $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');
            			}
            			else
            			{
            				$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

            			}
            		} 
               
               
               //template data
                $this->template->set('title', 'Fiscalización');
                $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );
 
                $this->data['message']=$this->session->flashdata('message');
				
				$this->data['respuestas']  = $this->fiscalizacion_model->selectRespuesta();

				$this->data['cod_gestion']  = $this->cod_gestion; 
				$this->data['cod_soporte_gestion']  = $this->cod_soporte_gestion; 
				$this->data['fecha']  = date("d/m/Y"); 
				$this->data['hora']  = date("H:i"); 
				//$this->data['conceptos']  = $this->fiscalizacion_model->getSelectConceptos('CONCEPTOSFISCALIZACION','COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO');
				
				
                $this->load->view('fiscalizacion/fiscalizacion_respuesta_aprobacion_soporte',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}

function ver_soporte_pago_ingreso() {
	

$this->cod_gestion_cobro = $this->input->post('sop_p');

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
               {

                    	
						
				$this->datainf['informe']  = $this->fiscalizacion_model->selectSopPagoIngreso($this->cod_gestion_cobro);		 
				//var_dump($this->datacomsena['comunicacion']);
               
               //template data
                $this->template->set('title', 'Fiscalización');
                $this->datainf['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->datainf['message']=$this->session->flashdata('message');
				
				
				
                $this->load->view('fiscalizacion/fiscalizacion_ver_soporte_pago',$this->datainf); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }

}

function ver_soporte_pago() {
	

$this->cod_gestion_cobro = $this->input->post('sop_p');

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
               {

                    	
						
				$this->datainf['informe']  = $this->fiscalizacion_model->selectSopPago($this->cod_gestion_cobro);		 
				//var_dump($this->datacomsena['comunicacion']);
               
               //template data
                $this->template->set('title', 'Fiscalización');
                $this->datainf['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->datainf['message']=$this->session->flashdata('message');
				
				
				
                $this->load->view('fiscalizacion/fiscalizacion_ver_soporte_pago',$this->datainf); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }

}

/*
function elaborar_comunicacion_oficial(){

$this->cod_fisc = $this->input->post('cod_fisc');
$this->nit = $this->input->post('nit');
$this->cod_concepto = $this->input->post('cod_concepto');	
					
		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('gestion/manage'))
               {
			$this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean|max_length[200]'); 
               
               if ($this->form_validation->run() == false)
                    {
                         $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                    } else
                    {
                    	
	
						
						
						$fechaupdate=$this->input->post('fecha')." ".$this->input->post('hora');
						
                        $date = array( 
                                'FECHA_APROBACION' => $fechaupdate
                        );
                        $data = array(
                                                                
                                'COMENTARIOS_APROBACION' => $this->input->post('descripcion'),
                                'COD_SOPORTE_GESTION' => $this->input->post('cod_soporte_gestion'),
                        );
                         
                         
            			if ($this->fiscalizacion_model->updateRespuestaAprobacion('SOPORTESGESTIONCOBRO',$data,$date) == TRUE)
										
								{
            			  $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La gestión se agregó con éxito.</div>');
                          redirect(base_url().'index.php/fiscalizacion/datatable');
            			}
            			else
            			{
            				$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

            			}
            		} 
               
               
               //template data
                $this->template->set('title', 'Fiscalización');
                $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->data['message']=$this->session->flashdata('message');
				
				$this->data['respuestas']  = $this->fiscalizacion_model->selectRespuesta();

				$this->data['cod_gestion']  = $this->cod_gestion; 
				$this->data['cod_soporte_gestion']  = $this->cod_soporte_gestion; 
				$this->data['fecha']  = date("d/m/Y"); 
				$this->data['hora']  = date("H:i"); 
				//$this->data['conceptos']  = $this->fiscalizacion_model->getSelectConceptos('CONCEPTOSFISCALIZACION','COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO');
				
				
                $this->load->view('fiscalizacion/fiscalizacion_respuesta_aprobacion_soporte',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}
*/


function enviar_correo_empresario(){

date_default_timezone_set("America/Bogota");

$this->cod_fisc = $this->input->post('cod_fisc');
$this->nit = $this->input->post('nit');
$this->cod_concepto = $this->input->post('cod_concepto');	
$this->email = $this->input->post('email');

	
		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
               {
			
                    	
               		$this->data['custom_error'] = '';
                    $showForm=0;
					
					
					
               


			   
               //template data
                $this->template->set('title', 'Fiscalización');
                $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->data['message']=$this->session->flashdata('message');
				
				$this->data['email']  = base64_encode($this->email);

				$this->data['cod_fisc']  = $this->cod_fisc; 
				$this->data['nit']  = $this->nit; 
				$this->data['cod_concepto']  = $this->cod_concepto; 
				$cod_asign=$this->fiscalizacion_model->selectCodasignxCodFisc($this->cod_fisc);
				$this->data['cod_asignacion_fisc']=$cod_asign["COD_ASIGNACION_FISC"];
				//$this->data['conceptos']  = $this->fiscalizacion_model->getSelect('CONCEPTOSFISCALIZACION','COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO');
				
				
                $this->load->view('fiscalizacion/fiscalizacion_enviar_correo_empresario',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}

function enviar_email_dest()
{
					
	//$archivo=$this->do_upload_mail();
								 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
               {
               		$cod_asign=$this->fiscalizacion_model->selectCodasignxCodFisc($this->input->post('cod_fisc'));
               	
       				$this->load->library('form_validation');    
	       			
	       			$this->form_validation->set_rules('asunto', 'asunto', 'required');
        			
                    if ($this->form_validation->run() == false)
                    {
                         $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                    } else
                    {
					$archivo=$this->input->post('adjunto_id');
					

					if(!empty($archivo))
					
					{
						$file = $this->do_upload_mail($this->input->post('cod_fisc'),$this->input->post('nit'),$this->input->post('cod_asignacion_fisc2'));
						$adjunto=$file['upload_data']['file_name'];	
								
								if(empty($adjunto)){				   
						  $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>El tipo de archivo es invalido, extensiones permitidas(pdf) intentelo nuevamente</div>');
						 						  
                         $datos = array('nit'=>$this->input->post('nit'),'cod_asign'=>$cod_asign["COD_ASIGNACION_FISC"]);
						
            			 $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');
					
					}
					
					}
					else{
						$adjunto="";
					}
					
					
						$config = Array( 
					  	'protocol' => 'smtp', 
					 	'smtp_host' => 'ssl://smtp.googlemail.com', 
				  		'smtp_port' => '465', 
					 	'smtp_user' => 'carterasena2@gmail.com', 
					 	'smtp_pass' => '7demarzo',
					 	'mailtype' =>'html',
			            'starttls' => true,
						'newline'  => "\r\n",
			            'wordwrap' => true    ); 
						
				 $this->email->initialize($config);		
						 $this->load->library('email'); 
						 
							$para=base64_decode($this->input->post('para'));
							
						$nombres=$this->ion_auth->user()->row()->NOMBRES;
						$apellidos=$this->ion_auth->user()->row()->APELLIDOS;
						$regional=$this->fiscalizacion_model->selectNombreRegional($this->ion_auth->user()->row()->COD_REGIONAL);
						
						$remitente="<br><br><br><br>"
						.$nombres
						." "
						.$apellidos
						."<br>"
						.$regional
						;
						 $this->email->from('carterasena@gmail.com', '');
						 $this->email->to($para);
						 $this->email->cc($this->input->post('cc')); 
						 $this->email->bcc($this->input->post('cco')); 
						 $this->email->subject($this->input->post('asunto')); 
						 $this->email->message($this->input->post('descripcion')."<br><br><br>"
						 .$nombres
						 ." "
						 .$apellidos
						 ."<br>"
						 .$regional['NOMBRE_REGIONAL']);
						if(!empty($archivo))
					
					{
						$this->email->attach('./uploads/fiscalizaciones/'.$this->input->post('cod_fisc').'/docemail/'.$file['upload_data']['file_name']);
						}

						 
						 if (!$this->email->send()) {
						   
						  $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>El email no se ha podido enviar verifique que el correo sea valido e intentelo nuevamente</div>');
						 						  
                         $datos = array('nit'=>$this->input->post('nit'),'cod_asign'=>$cod_asign["COD_ASIGNACION_FISC"]);
						
            			 $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');	
						
						 }
						 else {

        				$dategestion = array( 
								'FECHA_CONTACTO' =>  date("d/m/Y  H:i"),
                        );
                        $datagestion = array(
                        
								  
                        		'COMENTARIOS' => 'e-mail Enviado',
	                            'COD_TIPO_RESPUESTA' => '3',
	                            'COD_TIPOGESTION' => '2',
                                'NIT_EMPRESA' => $this->input->post('nit'),
								'COD_FISCALIZACION_EMPRESA' => $this->input->post('cod_fisc'),
								'COD_USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO
                        );				
                        				
                        	$this->fiscalizacion_model->addgestion('GESTIONCOBRO',$datagestion,$dategestion);	
						$idgestioncobro=$this->fiscalizacion_model->selectIdGestion('GESTIONCOBRO',$datagestion,$dategestion);	
						//$gestactual=$this->fiscalizacion_model->updateGestionActual('FISCALIZACION',$idgestioncobro['COD_GESTION_COBRO'],$this->input->post('cod_fisc'),'2');
						

						
						
                        $date = array( 
                                'FECHA_ENVIO' => date("d/m/Y"),
                        );
                        $data = array(
                                                                
                                'ASUNTO' => $this->input->post('asunto'),
                                 'DIRECCION_DESTINO' => $para,
                                  'MENSAJE' => $this->input->post('descripcion'),
                                   'CC' => $this->input->post('cc'),
                                'CCO' => $this->input->post('cco'),
                                'NOMBRE_ADJUNTO' => $adjunto,
		                          'COD_PROCESO' => '1',
                                'NRO_INTENTOFALLIDO' => '0',
                                'COD_INTERNO_PROCESO' => $this->input->post('cod_fisc'),
                                'COD_GESTION_COBRO' => $idgestioncobro['COD_GESTION_COBRO']

                        );
                         
                         
            			if ($this->fiscalizacion_model->add('CORREOELECTRONICO',$data,$date) == TRUE)
										
								{
            			  $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El correo se envio correctamente</div>');

						 $datos = array('nit'=>$this->input->post('nit'),'cod_asign'=>$cod_asign["COD_ASIGNACION_FISC"]);
						
            			 $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');	
            			}
            			else
            			{
            				$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

            			}
            		}
             
             }







}else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
	
	
}
function do_upload_mail($cod_fisc, $nit, $codasign) {

date_default_timezone_set("America/Bogota");
	
$nombre_carpeta = './uploads/fiscalizaciones/'.$cod_fisc;
$nombre_subcarpeta = './uploads/fiscalizaciones/'.$cod_fisc.'/docemail';

if(!is_dir($nombre_carpeta)){ 
@mkdir($nombre_carpeta, 0777); 
}else{ 

}  
if(!is_dir($nombre_subcarpeta)){ 
@mkdir($nombre_subcarpeta, 0777); 
}else{ 

}

    $config['upload_path'] = './uploads/fiscalizaciones/'.$cod_fisc.'/docemail';
	
    $config['allowed_types'] = 'pdf';
    $config['max_size'] = '5000';

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload()) {
     						$datos = array('nit'=>$nit,'cod_asign'=>$codasign);
						
							$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Tamaño de archivo excedido del limite permitido 5Mb, el correo no ha sido enviado, intente nuevamente.</div>');
               
            			  $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');
    } else {
      return $data = array('upload_data' => $this->upload->data());
    }
  }

	
	
	function check_in_range($start_date, $end_date, $evaluame) {
	//echo"  fecha in antes stro ".($start_date);
    $start_ts = strtotime($start_date);
	//echo"  fecha in stro ".($start_ts);

		//echo"  fecha fin antes stro ".($end_date);
    $end_ts = strtotime($end_date);
		//echo"  fecha fin stro ".($end_ts);
		//echo"  fecha eval antes stro ".($evaluame);
    $user_ts = strtotime($evaluame);
	//echo"  fecha eval stro ".($user_ts);
		//die();
    return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}


	function ver_correo() {
$this->cod_gestion_cobro = $this->input->post('cod_gestion_cobro');


	
					
		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
               {

                    	
						
				$this->datacorreo['email']  = $this->fiscalizacion_model->selectEmail($this->cod_gestion_cobro);		 

               
               //template data
                $this->template->set('title', 'Fiscalización');
                $this->datacorreo['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->datacorreo['message']=$this->session->flashdata('message');
				
				
				
                $this->load->view('fiscalizacion/fiscalizacion_ver_correo',$this->datacorreo); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }

}



function ingresar_autorizacion_email(){
					
$this->cod_fisc = $this->input->post('cod_fisc');
//echo $this->cod_fisc;
//die();
$this->nit = $this->input->post('nit');
$this->cod_concepto = $this->input->post('cod_concepto');	

	date_default_timezone_set("America/Bogota");
	
				$id=$this->ion_auth->user()->row()->IDUSUARIO;
		    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
							 {
																 	                    $this->load->library('form_validation');    
            		$this->data['custom_error'] = '';
                    $showForm=0;
					$cod_asign=$this->fiscalizacion_model->selectCodasignxCodFisc($this->cod_fisc);
						//var_dump($cod_asign);
						//die();
$flag=$this->input->post('vista_flag');
if(!empty($flag)){
      			
      			$this->form_validation->set_rules('contacto_id', 'Contacto', 'required'); 
            	//$this->form_validation->set_rules('email_id', 'Email', 'required|valid_email');
            		
            		
					
					
                    if ($this->form_validation->run() == false)
                    {
                    	
						//echo $this->input->post('email_id');
						
						//die();
                         $this->dataemail['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                    } else
                    {
                    	$file = $this->do_upload_autorizacion_email($this->input->post('cod_fisc'), $this->input->post('nit'), $cod_asign["COD_ASIGNACION_FISC"]);
                    	
						if($this->input->post('autoriza_id')=='accept'){$check_auto_email='S';}
						else{$check_auto_email='N';}

						
						
                        	$dategestionemail = array( 
								'FECHA_CONTACTO' =>  date("d/m/Y  H:i")
                        );
                        $datagestionemail= array(
                        
								'COMENTARIOS' => 'Autorización Registrada',
	                            'COD_TIPO_RESPUESTA' => '9',
	                            'COD_TIPOGESTION' => '7',
                                'NIT_EMPRESA' => $this->input->post('nit'),
								'COD_FISCALIZACION_EMPRESA' => $this->input->post('cod_fisc'),
								'COD_USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO
                        );				
                        				
                        	$this->fiscalizacion_model->addgestion('GESTIONCOBRO',$datagestionemail,$dategestionemail);		
                        		
                        	$idgestioncobro=$this->fiscalizacion_model->selectIdGestion('GESTIONCOBRO',$datagestionemail,$dategestionemail);		
                        	$gestactual=$this->fiscalizacion_model->updateTipoGestionActual('FISCALIZACION',$idgestioncobro['COD_GESTION_COBRO'],$this->input->post('cod_fisc'),'7');
                        	
                        	//var_dump($idgestioncobro["COD_GESTION_COBRO"]);
							//die();
							    
                        $date = array( 
								'FECHA_AUTORIZACION' =>  date("d/m/Y H:i"),
                        );
                        $data = array(
                        
								'DOCUMENTO_AUTORIZACION' => $file['upload_data']['file_name'],   
                        		'EMAIL_AUTORIZADO' => $this->input->post('email_id'),
	                            'AUTORIZA' => $check_auto_email,
                                'NOMBRE_CONTACTO' => base64_encode($this->input->post('contacto_id')),
								'NITEMPRESA' => $this->input->post('nit'),
								'COD_FISCALIZACION' => $this->input->post('cod_fisc'),
								'COD_GESTION' => $idgestioncobro["COD_GESTION_COBRO"]
                        );
                         
            			if ($this->fiscalizacion_model->autorizacionemail('AUTORIZACION_NOTI_EMAIL',$data,$date) == TRUE)
										
								{
									
							            			  $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La autorización se agregó con éxito.</div>');

                         $datos = array('nit'=>$this->nit,'cod_asign'=>$cod_asign["COD_ASIGNACION_FISC"]);
						
            			 $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');		

            			}
            			else
            			{
            				$this->dataemail['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

            			}
            		}


 
            	}
else{
										
										$this->dataemail['custom_error'] = '';
										$this->dataemail['cod_fisc']  = $this->cod_fisc;
										$this->dataemail['nit']  = $this->nit;
										$this->dataemail['cod_concepto']  = $this->cod_concepto;
										$this->dataemail['fecha']  = date("d/m/Y"); 
										$this->load->view('fiscalizacion/fiscalizacion_ingresar_aut_email', $this->dataemail);
}								
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


function do_upload_autorizacion_email($cod_fisc, $nit, $codasign) {

	
date_default_timezone_set("America/Bogota");


$nombre_carpeta = './uploads/fiscalizaciones/'.$cod_fisc;
$nombre_subcarpeta = './uploads/fiscalizaciones/'.$cod_fisc.'/autorizacionenvioemail';

if(!is_dir($nombre_carpeta)){ 
@mkdir($nombre_carpeta, 0777); 
}else{ 

}  
if(!is_dir($nombre_subcarpeta)){ 
@mkdir($nombre_subcarpeta, 0777); 
}else{ 

}

    $config['upload_path'] = './uploads/fiscalizaciones/'.$cod_fisc.'/autorizacionenvioemail';
    $config['allowed_types'] = 'pdf';
    $config['max_size'] = '5000';

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload()) {
      							$datos = array('nit'=>$nit,'cod_asign'=>$codasign);
						
							$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Tamaño de archivo excedido del limite permitido 5Mb, la gestión no ha sido registrada intente nuevamente.</div>');
               
            			  $this->session->set_flashdata('lolwut',$datos);
                        
		 				redirect(base_url().'index.php/fiscalizacion/datatabler');
    } else {
      return $data = array('upload_data' => $this->upload->data());
    }
  }


function ver_autorizacion_noti() {
$this->cod_gestion_cobro = $this->input->post('cod_gestion_cobro');

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
               {

                    	
						
				$this->dataautorizacion['autorizacion']  = $this->fiscalizacion_model->selectAutorizacionNoti($this->cod_gestion_cobro);		 

               
               //template data
                $this->template->set('title', 'Fiscalización');
                $this->dataautorizacion['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->dataautorizacion['message']=$this->session->flashdata('message');
				
				
				
                $this->load->view('fiscalizacion/fiscalizacion_ver_autorizacion_noti',$this->dataautorizacion); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }

}

//para guardar archivo plantilla

function cargar_plantilla() {

		//$this->template->load($this->template_file, 'fiscalizacion/plantilla',$this->data);

    }

function guardar_archivo() {
        $post = $this->input->post();
		print_r($post);
        $this->data['post'] = $post;
        $ar = fopen("uploads/plantillas/cert_nm_4.txt", "w+") or die();
        fputs($ar, $post['informacion']);
        fclose($ar);
    }

function programar_visita() {
	
$this->cod_fisc = $this->input->post('cod_fisc');
$this->nit = $this->input->post('nit');
$this->cod_concepto = $this->input->post('cod_concepto');	
	
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
							 {
																 	                   
            		$this->data['custom_error'] = '';
                    $showForm=0;
					
							    
                      	$dategestionprogrvisita = array( 
								'FECHA_CONTACTO' =>  date("d/m/Y H:i")
                        );
                        $datagestionprogrvisita= array(
                        
								'COMENTARIOS' => 'Realizar Programación de Visita',
	                            'COD_TIPO_RESPUESTA' => '4',
	                            'COD_TIPOGESTION' => '2',
                                'NIT_EMPRESA' => $this->nit,
								'COD_FISCALIZACION_EMPRESA' => $this->cod_fisc,
								'COD_USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO
                        );				
                        				
                        	
                         
            			if ($this->fiscalizacion_model->addgestion('GESTIONCOBRO',$datagestionprogrvisita,$dategestionprogrvisita) == TRUE)
										
								{
										$idgestioncobro=$this->fiscalizacion_model->selectIdGestion('GESTIONCOBRO',$datagestionprogrvisita,$dategestionprogrvisita);
									$gestactual=$this->fiscalizacion_model->updateGestionActual('FISCALIZACION',$idgestioncobro['COD_GESTION_COBRO'],$this->cod_fisc,'2');
						
									
							echo "Gestión Agregada Correctamente";
            			  //$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Gestión Agregada Correctamente</div>');
                          //redirect(base_url().'index.php/fiscalizacion');
            			}
            			else
            			{
            				echo "La Gestión no se ha podido agregar";
            				//$this->dataemail['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

            			}
            		
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

function consulta_generar_documento_visita_ofi() {
	

$this->cod_gestion = $this->input->post('cod_gestion_programar_visita');

//echo $this->cod_fisc;
//die();

//$this->nit = $this->input->post('nit');
//$this->cod_concepto = $this->input->post('cod_concepto');	
	
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/consulta_generar_documento_visita_ofi'))
							 {
																 	                   
            		$this->data['custom_error'] = '';
                    $showForm=0;
					$cod_fisc_nit=$this->fiscalizacion_model->selectcod_fiscnit($this->cod_gestion);
					$cod_asign=$this->fiscalizacion_model->selectCodasignxCodFisc($cod_fisc_nit["COD_FISCALIZACION_EMPRESA"]);
					
					
					
					$this->data['empresa']=$this->fiscalizacion_model->selectEmpresa( $cod_fisc_nit["NIT_EMPRESA"]);
					$this->data['nit']=$cod_fisc_nit["NIT_EMPRESA"];

											
					$this->data['cod_fisc']=$cod_fisc_nit["COD_FISCALIZACION_EMPRESA"];
					$this->data['cod_asignacion_fisc']=$cod_asign["COD_ASIGNACION_FISC"];
					$this->data['cod_gestion']=$this->cod_gestion;
					$this->data['visitas_programadas']  = $this->fiscalizacion_model->selectCountVisitas($cod_fisc_nit["COD_FISCALIZACION_EMPRESA"]);		 
					
					//var_dump($cod_fisc_nit);		
					//die();
		
							    
				$this->template->load($this->template_file, 'fiscalizacion/fiscalizacion_ingr_datos_comunicacion_oficial',$this->data);
                        				
                       	
                         

            		
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

function generar_documento_visita_ofi(){
			
	
		
		date_default_timezone_set("America/Bogota");
		


//$this->nit = $this->input->post('nit');
//$this->cod_concepto = $this->input->post('cod_concepto');	
	
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/consulta_generar_documento_visita_ofi'))
							 {
										
										
					$this->cod_gestion = $this->input->post('cod_gestion');
					$this->fecha_visita = $this->input->post('fecha_visita');
					
					
					$fecha_visita_com_explo=explode("/", $this->fecha_visita);
					
					$meses = array('01' => 'enero','02' => 'febrero','03' => 'marzo','04' => 'abril','05' => 'mayo','06' => 'junio','07' => 'julio','08' => 'agosto','09' => 'septiembre','10' => 'octubre','11' => 'noviembre','12' => 'diciembre');
					$fecha_visita_com = $fecha_visita_com_explo[0]." del mes ".$meses[$fecha_visita_com_explo[1]]." del año ".$fecha_visita_com_explo[2];
					
					$fechacomunicado= date("d/m/Y");
					$fechacomunicado_explode= explode("/", $fechacomunicado);
					$fechacomunicado_visita_ofi = $fechacomunicado_explode[0]." de ".$meses[$fechacomunicado_explode[1]]." de ".$fechacomunicado_explode[2];
					
					
					$this->hora_visita = $this->input->post('hora_inicio');
					
														 	                   
            		$this->data['custom_error'] = '';
                    $showForm=0;
					$cod_fisc_nit=$this->fiscalizacion_model->selectcod_fiscnit($this->cod_gestion);
					
					$visitas  = $this->fiscalizacion_model->selectCountVisitas($cod_fisc_nit["COD_FISCALIZACION_EMPRESA"]);	
				
					$this->data['empresa']=$this->fiscalizacion_model->selectEmpresa( $cod_fisc_nit["NIT_EMPRESA"]);
					$this->data['nit']=$cod_fisc_nit["NIT_EMPRESA"];
	
										if($visitas['VISITAS']==2){
			        $datosprimvisita  = $this->fiscalizacion_model->select_datos_visita($cod_fisc_nit["COD_FISCALIZACION_EMPRESA"]);	
					//var_dump($datosprimvisita);
					//die();
					$nrocom=$datosprimvisita['NRO_RADICADO_ONBASE'];
					$fecha_primera_visita_explode= explode(" ", $datosprimvisita['HORA_INICIO']);
					$hora_primera_visita=$fecha_primera_visita_explode[1];
					
					$fecha_primera_visita=explode("/", $fecha_primera_visita_explode[0]);
					
					$fecha_visita_p = $fecha_primera_visita[0]." del mes ".$meses[$fecha_primera_visita[1]]." del año ".$fecha_primera_visita[2];
											
											
											
				}else{
					$hora_primera_visita="";
					$fecha_visita_p="";
					$nrocom="";
				}
					
					$urlplantilla="./uploads/plantillas/cu_004.txt";
					$urlplantilla2="./uploads/plantillas/cu_004_2.txt";
					$this->data['filas']=file($urlplantilla);
					$DatosCom1=$this->fiscalizacion_model->selectDatosComOfi($this->cod_gestion);
					$DatosCom2=$this->fiscalizacion_model->selectDatosComOfi2($this->cod_gestion);
					//var_dump($DatosCom2);
					//die();
					
					$cordinador= $DatosCom1['NOMBRES']." ".$DatosCom1['APELLIDOS'];
					$fiscalizador= $DatosCom2['NOMBRES']." ".$DatosCom2['APELLIDOS'];
					
					$array_tags                                 = array();
					$array_tags['cod_regional']                = $DatosCom1['COD_REGIONAL'];
			        $array_tags['ciudad_comunicado']                = $DatosCom2['CIUDAD_REGIONAL'];
					$array_tags['fecha_visita']                 	= $fecha_visita_com;
					$array_tags['fecha_primera_visita']                 	= $fecha_visita_p;
					$array_tags['hora_primera_visita']                 	= $hora_primera_visita;
					$array_tags['nro_com']                 	= $nrocom;
					$array_tags['hora_visita']                 		= $this->hora_visita;
			        $array_tags['nombre_coordinador']               = $cordinador;
					$array_tags['ciudad_empresa']               = $DatosCom1['CIUDAD_EMPRESA'];
					$array_tags['nombre_representante']               = $DatosCom2['REPRESENTANTE_LEGAL']; 
					$array_tags['nombre_empresa']               = $DatosCom2['NOMBRE_EMPRESA']; 
					$array_tags['nit_empresa']               = $DatosCom2['CODEMPRESA']; 
					$array_tags['dir_empresa']               = $DatosCom2['DIRECCION']; 
					$array_tags['nombre_fiscalizador']       = $fiscalizador; 
					$array_tags['correo_fisc']               = $DatosCom2['EMAIL']; 
					$array_tags['tel_fisc']               = $DatosCom2['TELEFONO'];
					$array_tags['ext_fisc']               = $DatosCom2['EXTENSION'];
					$array_tags['nombre_regional']               = $DatosCom2['NOMBRE_REGIONAL'];
					$array_tags['fecha_comunicado']               = $fechacomunicado_visita_ofi;
				
					if($visitas['VISITAS']==1){
			        $this->data['docreemplazado']=template_tags($urlplantilla, $array_tags);
			        }
					else{
						$this->data['docreemplazado']=template_tags($urlplantilla2, $array_tags);
					}
						
					$this->data['cod_fisc']=$cod_fisc_nit["COD_FISCALIZACION_EMPRESA"];
					$this->data['visitas_programadas']  = $this->fiscalizacion_model->selectCountVisitas($cod_fisc_nit["COD_FISCALIZACION_EMPRESA"]);		 

				
		
							    
				$this->load->view('fiscalizacion/fiscalizacion_comunicacion_oficial',$this->data);
                        				
                       	
                         

            		
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



function guardar_comunicacion_oficial_visita() {
        
		
		date_default_timezone_set("America/Bogota");
		$fechacomunicado= date("d/m/Y");
		$fechaexplode = explode("/", $fechacomunicado);
		$fechanombre= $fechaexplode[0].$fechaexplode[1].$fechaexplode[2];
        $post = $this->input->post();
        print_r($post);
        $this->data['post'] = $post;
        
        $nombre_carpeta = './uploads/fiscalizaciones/'.$post['cod_fisc'];
$nombre_subcarpeta = './uploads/fiscalizaciones/'.$post['cod_fisc'].'/comunicacionoficialvisita';

if(!is_dir($nombre_carpeta)){ 
@mkdir($nombre_carpeta, 0777); 
}else{ 

}  
if(!is_dir($nombre_subcarpeta)){ 
@mkdir($nombre_subcarpeta, 0777); 
}else{ }  
		
		 $html = $this->input->post('informacion');
        $nombre_pdf = $fechanombre;
		$visita=$this->input->post('visitas');
		ob_clean();
        $pdf = new Plantilla_PDF_Fiscalizacion(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(30);
        $pdf->SetFooterMargin(30);
		$pdf->SetLeftMargin(30);
		$pdf->SetRightMargin(30);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 8, '', true);
        $pdf->setFooterData('sena');

		$pdf->regional=$this->fiscalizacion_model->infoRegional($this->ion_auth->user()->row()->COD_REGIONAL);
	    $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output("uploads/fiscalizaciones/".$post['cod_fisc']."/comunicacionoficialvisita/".$visita.$nombre_pdf.'.pdf', 'F');

		
		$dategestiongenedoc = array( 
								'FECHA_CONTACTO' =>  date("d/m/Y  H:i"),
                        );
                        $datagestiongenedoc= array(
                        
								'COMENTARIOS' => 'Documento generado',
	                            'COD_TIPO_RESPUESTA' => '6',
	                            'COD_TIPOGESTION' => '4',
                                'NIT_EMPRESA' => $post['nit'],
								'COD_FISCALIZACION_EMPRESA' => $post['cod_fisc'],
								'COD_USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO
                        );				
                        				
                        	
                         $this->fiscalizacion_model->addgestion('GESTIONCOBRO',$datagestiongenedoc,$dategestiongenedoc);
            			$idgestioncobro=$this->fiscalizacion_model->selectIdGestion('GESTIONCOBRO',$datagestiongenedoc,$dategestiongenedoc);
						$gestactual=$this->fiscalizacion_model->updateGestionActual('FISCALIZACION',$idgestioncobro['COD_GESTION_COBRO'],$post['cod_fisc'],'4');
						
								$datenotivisita = array( 
								'FECHA_COMUNICADO' =>  date("d/m/Y"),
								'FECHA_VISITA' =>  $post['fecha_visita'],
								'HORA_VISITA_INICIO' =>  $post['fecha_visita']." ".$post['hora_inicio'],
								'HORA_VISITA_FIN' =>  $post['fecha_visita']." ".$post['hora_fin']
                        );
                       $datanotivisita = array(
                        
								'NOMBRE_ARCHIVO_NOTIFICACION' => $visita.$fechanombre.".pdf",
	                            'COD_GESTION_COBRO' => $idgestioncobro['COD_GESTION_COBRO']
                        );	
						
						
						
						if ($this->fiscalizacion_model->addnotivisita('NOTIFICACIONVISITA',$datanotivisita,$datenotivisita) == TRUE)
										
								{
							echo "Documento Generado Correctamente";

            			}
            			else
            			{
            				echo "El documento no se ha podido Generar";

            			}
		
    }


function ver_notificacion_visita() {
$this->cod_gestion_cobro = $this->input->post('notivisita');

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
               {

                    	
						
				$this->datanotificacion['notificacion']  = $this->fiscalizacion_model->selectNotiVisita($this->cod_gestion_cobro);		 

               
               //template data
                $this->template->set('title', 'Fiscalización');
                $this->datanotificacion['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->datanotificacion['message']=$this->session->flashdata('message');
				
				
				
                $this->load->view('fiscalizacion/fiscalizacion_ver_notificacion_visita',$this->datanotificacion); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }

}

function consulta_comunicacion_presentarse_sena() {
	

$this->cod_fisc = $this->input->post('cod_fisca_presentarse_sena');
$this->nit = $this->input->post('nit_presentarse_sena');
	
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/consulta_comunicacion_presentarse_sena'))
							 {
																 	                   
            		$this->data['custom_error'] = '';
                    $showForm=0;
					
					$cod_asign=$this->fiscalizacion_model->selectCodasignxCodFisc($this->cod_fisc);
					
					
					
					$this->data['empresa']=$this->fiscalizacion_model->selectEmpresa( $this->nit);
					$this->data['nit']=$this->nit ;

											
					$this->data['cod_fisc']=$this->cod_fisc;
					$this->data['cod_asignacion_fisc']=$cod_asign["COD_ASIGNACION_FISC"];

					
					//var_dump($cod_fisc_nit);		
					//die();
		
							    
				$this->template->load($this->template_file, 'fiscalizacion/fiscalizacion_ingr_datos_comunicacion_presentarse_sena',$this->data);
                        				
                       	
                         

            		
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

function comunicacion_presentarse_sena() {
	
	
$this->cod_fisc = $this->input->post('cod_fisc');
$this->nit = $this->input->post('nit');


//$this->nit = $this->input->post('nit');
//$this->cod_concepto = $this->input->post('cod_concepto');	
	
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/consulta_comunicacion_presentarse_sena'))
							 {
																 	                   
            		$this->data['custom_error'] = '';
                    $showForm=0;
					
					$this->data['nit']=$this->nit;

					
											
					$this->data['cod_fisc']=$this->cod_fisc;
					$this->data['motivos']  = $this->fiscalizacion_model->getSelect('MOTIVOCITACION','COD_MOTIVO_CITACION,NOMBRE_MOTIVO');
					

					$urlplantilla="./uploads/plantillas/cu_008_1.txt";
					$urlplantilla2="./uploads/plantillas/cu_008_2.txt";
					
					$datos1  = $this->fiscalizacion_model->selectDatosComPrSena($this->cod_fisc);
					$datos2  = $this->fiscalizacion_model->selectDatosComPrSena2($this->cod_fisc);
					
					$meses = array('01' => 'enero','02' => 'febrero','03' => 'marzo','04' => 'abril','05' => 'mayo','06' => 'junio','07' => 'julio','08' => 'agosto','09' => 'septiembre','10' => 'octubre','11' => 'noviembre','12' => 'diciembre');
					
					$fechacomunicado= $this->input->post('fecha_visita');
					if(!empty($fechacomunicado)){
					$fechacomunicado_explode= explode("/", $fechacomunicado);
					$fechacomunicado_pr_ante_sena = $fechacomunicado_explode[0]." de ".$meses[$fechacomunicado_explode[1]]." de ".$fechacomunicado_explode[2];
					}
					
					$this->hora_visita = $this->input->post('hora_inicio');
					
					$coordinador= $datos1['NOMBRES']." ".$datos1['APELLIDOS'];
					$fiscalizador= $datos2['NOMBRES']." ".$datos2['APELLIDOS'];
					
					$array_tags                                 = array();
			        $array_tags['ciudad_comunicado']            = $datos2['CIUDAD_REGIONAL'];
					if(!empty($fechacomunicado)){
					$array_tags['fecha_visita']                 = $fechacomunicado_pr_ante_sena;

					}
					else{
					$array_tags['fecha_visita']                 = "";	
					}
					$array_tags['hora_visita']                 	= $this->hora_visita;
			        $array_tags['nombre_coordinador']           = $coordinador;
					$array_tags['ciudad_empresa']               = $datos1['CIUDAD_EMPRESA'];
					$array_tags['nombre_representante']             = $datos2['REPRESENTANTE_LEGAL']; 
					$array_tags['nombre_empresa']               = $datos2['NOMBRE_EMPRESA']; 
					$array_tags['nit_empresa']               = $datos2['CODEMPRESA']; 
					$array_tags['dir_empresa']               = $datos2['DIRECCION']; 
					$array_tags['nombre_fiscalizador']       = $fiscalizador; 
					$array_tags['correo_fisc']               = $datos2['EMAIL']; 
					$array_tags['tel_fisc']              	 = $datos2['TELEFONO'];
					$array_tags['ext_fisc']               	 = $datos2['EXTENSION'];
					$array_tags['nombre_regional']           = $datos2['NOMBRE_REGIONAL'];
					$array_tags['cod_regional']           = $datos2['COD_REGIONAL'];
			        
							
					
					$this->data['filas']=template_tags($urlplantilla, $array_tags);
						
					
					$this->data['filas2']=template_tags($urlplantilla2, $array_tags);	
						
					$this->data['visitas_programadas']  = $this->fiscalizacion_model->selectCountVisitasPresencia($this->cod_fisc);
		
		
							    
				$this->load->view('fiscalizacion/fiscalizacion_comunicacion_presentarse_sena',$this->data);
                        				
                       	
                         

            		
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




function guardar_comunicacion_presentarse_sena() {
        
		
		date_default_timezone_set("America/Bogota");
		$fechacomunicado= date("d/m/Y");
		$fechaexplode = explode("/", $fechacomunicado);
		$fechanombre= $fechaexplode[0].$fechaexplode[1].$fechaexplode[2];
        $post = $this->input->post();
        print_r($post);
        $this->data['post'] = $post;
        
        $nombre_carpeta = './uploads/fiscalizaciones/'.$post['cod_fisc'];
$nombre_subcarpeta = './uploads/fiscalizaciones/'.$post['cod_fisc'].'/comunicacionpresentarsesena';

if(!is_dir($nombre_carpeta)){ 
@mkdir($nombre_carpeta, 0777); 
}else{ 

}  
if(!is_dir($nombre_subcarpeta)){ 
@mkdir($nombre_subcarpeta, 0777); 
}else{ }  
		
		
		$html = $this->input->post('informacion');
        $nombre_pdf = $fechanombre;
		$visita=$this->input->post('visitas')+1;
		ob_clean();
        $pdf = new Plantilla_PDF_Fiscalizacion(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(30);
        $pdf->SetFooterMargin(30);
		$pdf->SetLeftMargin(30);
		$pdf->SetRightMargin(30);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 8, '', true);
        $pdf->setFooterData('sena');

		$pdf->regional=$this->fiscalizacion_model->infoRegional($this->ion_auth->user()->row()->COD_REGIONAL);
	    $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output("uploads/fiscalizaciones/".$post['cod_fisc']."/comunicacionpresentarsesena/".$visita.$nombre_pdf.'.pdf', 'F');
		
		//verificar a partir de aca
		$dategestiongenedoc = array( 
								'FECHA_CONTACTO' =>  date("d/m/Y  H:i"),
                        );
                        $datagestiongenedoc= array(
                        
								'COMENTARIOS' => 'Comunicación generada',
	                            'COD_TIPO_RESPUESTA' => '10',
	                            'COD_TIPOGESTION' => '8',
                                'NIT_EMPRESA' => $post['nit'],
								'COD_FISCALIZACION_EMPRESA' => $post['cod_fisc'],
								'COD_USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO
                        );				
                        				
                        	
                        $this->fiscalizacion_model->addgestion('GESTIONCOBRO',$datagestiongenedoc,$dategestiongenedoc);
            			$idgestioncobro=$this->fiscalizacion_model->selectIdGestion('GESTIONCOBRO',$datagestiongenedoc,$dategestiongenedoc);
						$gestactual=$this->fiscalizacion_model->updateGestionActual('FISCALIZACION',$idgestioncobro['COD_GESTION_COBRO'],$post['cod_fisc'],'8');
						$fiscalizador=$this->fiscalizacion_model->selectFiscalizador($post['cod_fisc']);
						$coordinador=$this->fiscalizacion_model->selectCoordinador($post['nit']);
												//echo($coordinador['IDUSUARIO']);
						//die();

                       $datacomunicacionsena = array(
                        
								'COD_MOTIVO_CITACION' => $post['cod_motivo'],
	                            'COD_GESTION_COBRO' => $idgestioncobro['COD_GESTION_COBRO'],
	                            'NIT_EMPRESA' => $post['nit'],
	                            'PROYECTO' => $fiscalizador['ASIGNADO_A'],
	                            'COORDINADOR' => $coordinador['IDUSUARIO'],
	                            'NOMBRE_DOCUMENTO' => $visita.$fechanombre.".pdf"
                        );	
						
						
						
						if ($this->fiscalizacion_model->addcomunicacionsena('CITACIONEMPRESA',$datacomunicacionsena) == TRUE)
										
								{
							echo "Documento Generado Correctamente";

            			}
            			else
            			{
            				echo "El documento no se ha podido Generar";

            			}
		
    }

function ver_comunicacion_sena() {
	

$this->cod_gestion_cobro = $this->input->post('com_sena');

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
               {

                    	
						
				$this->datacomsena['comunicacion']  = $this->fiscalizacion_model->selectComSena($this->cod_gestion_cobro);		 
				//var_dump($this->datacomsena['comunicacion']);
               
               //template data
                $this->template->set('title', 'Fiscalización');
                $this->datacomsena['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->datacomsena['message']=$this->session->flashdata('message');
				
				
				
                $this->load->view('fiscalizacion/fiscalizacion_ver_comunicacion_sena',$this->datacomsena); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }

}

function agregar_documentos_informe_visita() {
	
$this->cod_gestion = $this->input->post('cod_gestion_elaborar_informe_visita');

	
//$this->cod_concepto = $this->input->post('cod_concepto');	
	
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/agregar_documentos_informe_visita'))
							 {
																 	                   
            		$this->data['custom_error'] = '';
                    $showForm=0;
					$cod_fisc_nit=$this->fiscalizacion_model->selectcod_fiscnit($this->cod_gestion);
					
					$cod_asign=$this->fiscalizacion_model->selectCodasignxCodFisc($cod_fisc_nit["COD_FISCALIZACION_EMPRESA"]);
					
	
					$this->data['cod_asignacion_fisc']=$cod_asign["COD_ASIGNACION_FISC"];

					$this->data['empresa']=$this->fiscalizacion_model->selectEmpresa( $cod_fisc_nit["NIT_EMPRESA"]);
					$this->data['nit']=$cod_fisc_nit["NIT_EMPRESA"];
					$this->data['cod_fisc']=$cod_fisc_nit["COD_FISCALIZACION_EMPRESA"];
					$this->data['cod_gestion']=$this->cod_gestion;

				$this->template->load($this->template_file, 'fiscalizacion/fiscalizacion_agregar_documentos_informe_visita',$this->data);
                        				
                       	
                         

            		
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

function elaborar_informe_visita() {
	
$this->cod_gestion = $this->input->post('cod_gestion');
$this->documentos = $this->input->post('doc');
$this->observaciones = $this->input->post('observaciones');	
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/agregar_documentos_informe_visita'))
							 {
																 	                   
            		$this->data['custom_error'] = '';
                    $showForm=0;
					
					$cod_fisc_nit=$this->fiscalizacion_model->selectcod_fiscnit($this->cod_gestion);
					
					$this->data['nit']=$cod_fisc_nit["NIT_EMPRESA"];
					$this->data['cod_fisc']=$cod_fisc_nit["COD_FISCALIZACION_EMPRESA"];
					$this->data['cod_gestion']=$this->cod_gestion;
					$this->data['docs']=$this->documentos;
					
					$meses = array('01' => 'enero','02' => 'febrero','03' => 'marzo','04' => 'abril','05' => 'mayo','06' => 'junio','07' => 'julio','08' => 'agosto','09' => 'septiembre','10' => 'octubre','11' => 'noviembre','12' => 'diciembre');
					
					$fechainforme= date("d/m/Y");
					$fechainforme_explode= explode("/", $fechainforme);
					$fechainforme_visita = $fechainforme_explode[0]." de ".$meses[$fechainforme_explode[1]]." de ".$fechainforme_explode[2];
					
					$datosInforme_visita= $this->fiscalizacion_model->selectInformeV($cod_fisc_nit["COD_FISCALIZACION_EMPRESA"]);;
					$nombrefiscalizador= $datosInforme_visita['NOMBRES']." ".$datosInforme_visita['APELLIDOS'];

					
					$urlplantilla="./uploads/plantillas/cu_006.txt";
					
					$valor="";
					if($this->documentos!=""){
					foreach ($this->documentos as $key => $value) {
					$valor.= "- ".$value."<br>";
					}
				}
					
					$array_tags                                 = array();
			        $array_tags['fecha_informe']                = $fechainforme_visita;
					$array_tags['observaciones_informe']                = $this->observaciones;
					$array_tags['documentos_informe']                = $valor;
					$array_tags['nombre_fiscalizador']                = $nombrefiscalizador;
					$array_tags['nit_empresa']                = $datosInforme_visita['CODEMPRESA'];
					$array_tags['actividad_empresa']                = $datosInforme_visita['ACTIVIDADECONOMICA'];
					$array_tags['representante_empresa']                = $datosInforme_visita['REPRESENTANTE_LEGAL'];
					$array_tags['ciiu_empresa']                = $datosInforme_visita['CIIU'];
					$array_tags['direccion_empresa']                = $datosInforme_visita['DIRECCION'];
					$array_tags['fax_empresa']                = $datosInforme_visita['FAX'];
					$array_tags['ciudad_empresa']                = $datosInforme_visita['CIUDAD_EMPRESA'];
					$array_tags['email_empresa']                = $datosInforme_visita['CORREOELECTRONICO'];
					$array_tags['telefono_1']                = $datosInforme_visita['TELEFONO_FIJO'];
					$array_tags['telefono_2']                = $datosInforme_visita['TELEFONO_CELULAR'];
					$array_tags['firma_fiscalizador']                = $nombrefiscalizador;
					$array_tags['telefono_fiscalizador']                = $datosInforme_visita['TELEFONO'];
					$array_tags['ext_fiscalizador']                = $datosInforme_visita['EXTENSION'];
					$array_tags['nombre_empresa']                = $datosInforme_visita['NOMBRE_EMPRESA'];
			        $array_tags['ruta_imagen']=base_url('img/sena.jpg');
			        
			        $this->data['informereemplazado']=template_tags($urlplantilla, $array_tags);
					
							
						
		
					
					//var_dump($cod_fisc_nit);		
					//die();
		
							    
				$this->load->view('fiscalizacion/fiscalizacion_elaborar_informe_visita',$this->data);
                        				
                       	
                         

            		
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

function guardar_informe_visita() {
        
		
		date_default_timezone_set("America/Bogota");
		$fechacomunicado= date("d/m/Y");
		$fechaexplode = explode("/", $fechacomunicado);
		$fechanombre= $fechaexplode[0].$fechaexplode[1].$fechaexplode[2];
        $post = $this->input->post();
        $this->data['post'] = $post;
        
        $nombre_carpeta = './uploads/fiscalizaciones/'.$post['cod_fisc'];
		$nombre_subcarpeta = './uploads/fiscalizaciones/'.$post['cod_fisc'].'/informevisita';

if(!is_dir($nombre_carpeta)){ 
@mkdir($nombre_carpeta, 0777); 
}else{ 

}  
if(!is_dir($nombre_subcarpeta)){ 
@mkdir($nombre_subcarpeta, 0777); 
}else{ }  
		
		
        $ar = fopen("uploads/fiscalizaciones/".$post['cod_fisc']."/informevisita/".$fechanombre.".html", "w+") or die();
        

        $content_file = str_replace('../../img/sena.jpg', base_url('img/sena.jpg'), $post['informacion']);
        fputs($ar, $content_file);
        fclose($ar);
		

		$dategestiongenedoc = array( 
								'FECHA_CONTACTO' =>  date("d/m/Y  H:i"),
                        );
                        $datagestiongenedoc= array(
                        
								'COMENTARIOS' => 'Informe de visita generada',
	                            'COD_TIPO_RESPUESTA' => '7',
	                            'COD_TIPOGESTION' => '5',
                                'NIT_EMPRESA' => $post['nit'],
								'COD_FISCALIZACION_EMPRESA' => $post['cod_fisc'],
								'COD_USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO
                        );				
                        				
                        	
                        $this->fiscalizacion_model->addgestion('GESTIONCOBRO',$datagestiongenedoc,$dategestiongenedoc);
            			$idgestioncobro=$this->fiscalizacion_model->selectIdGestion('GESTIONCOBRO',$datagestiongenedoc,$dategestiongenedoc);
						$gestactual=$this->fiscalizacion_model->updateGestionActual('FISCALIZACION',$idgestioncobro['COD_GESTION_COBRO'],$post['cod_fisc'],'5');
						$idnotivisita=$this->fiscalizacion_model->selectIdNotiVisita($post['cod_gestion']);



                       $data = array(
                        
								'COD_NOTIFICACION_VISITA' => $idnotivisita['COD_NOTIFICACION_VISITA'],
	                            'COD_GESTION_COBRO' => $idgestioncobro['COD_GESTION_COBRO'],
	                            'FECHA_DOCUMENTO' =>  date("d/m/Y"),
	                            'NOMBRE_DOCUMENTO' => $fechanombre.".html"
                        );	
						
						$this->fiscalizacion_model->addinformevisita($data);
						
						
            			  //$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Informe de Visita Agregado Correctamente</div>');
                          //redirect('http://localhost/prototiposena1/index.php/fiscalizacion');
            			
            				//$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

            			
		
    }

function ver_informe_visita() {
	

$this->cod_gestion_cobro = $this->input->post('com_sena');

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
               {

                    	
						
				$this->datainf['informe']  = $this->fiscalizacion_model->selectInfVisita($this->cod_gestion_cobro);		 
				//var_dump($this->datacomsena['comunicacion']);
               
               //template data
                $this->template->set('title', 'Fiscalización');
                $this->datainf['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->datainf['message']=$this->session->flashdata('message');
				
				
				
                $this->load->view('fiscalizacion/fiscalizacion_ver_informe_visita',$this->datainf); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }

}

function agregar_documentos_acta_via_gubernativa() {
	
	
$this->cod_fisc = $this->input->post('cod_fisca_acta_via_guber');

$this->nit = $this->input->post('nit_acta_via_guber');
//$this->cod_concepto = $this->input->post('cod_concepto');	
	
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/agregar_documentos_acta_via_gubernativa'))
							 {
																 	                   
            		$this->data['custom_error'] = '';
                    $showForm=0;

					$this->data['empresa']=$this->fiscalizacion_model->selectEmpresa( $this->nit);
					$this->data['nit']=$this->nit;
					$this->data['cod_fisc']=$this->cod_fisc;
					$cod_asign=$this->fiscalizacion_model->selectCodasignxCodFisc($this->cod_fisc);
			
					$this->data['cod_asignacion_fisc']=$cod_asign["COD_ASIGNACION_FISC"];
					

					
						
					
					//var_dump($cod_fisc_nit);		
					//die();
		
							    
				$this->template->load($this->template_file, 'fiscalizacion/fiscalizacion_agregar_documentos_acta_via_gubernativa',$this->data);
                        				
                       	
                         

            		
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


    
    
function generar_acta_via_gubernativa() {
	
	$this->documentos = $this->input->post('doc');
	
$this->cod_fisc = $this->input->post('cod_fisc');

$this->nit = $this->input->post('nit');

//$this->cod_concepto = $this->input->post('cod_concepto');	
	
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/agregar_documentos_acta_via_gubernativa'))
							 {
					$this->expediente = $this->input->post('exp');
					$this->folio = $this->input->post('folio');
																 	                   
            		$this->data['custom_error'] = '';
                    $showForm=0;

					$this->data['empresa']=$this->fiscalizacion_model->selectEmpresa( $this->nit);
					$this->data['nit']=$this->nit;
					$this->data['cod_fisc']=$this->cod_fisc;
					
					
					$meses = array('01' => 'enero','02' => 'febrero','03' => 'marzo','04' => 'abril','05' => 'mayo','06' => 'junio','07' => 'julio','08' => 'agosto','09' => 'septiembre','10' => 'octubre','11' => 'noviembre','12' => 'diciembre');
					
					$fechainforme= date("d/m/Y");
					$fechainforme_explode= explode("/", $fechainforme);
					$fechainforme_visita = $fechainforme_explode[0]." días del mes de ".$meses[$fechainforme_explode[1]]." de ".$fechainforme_explode[2];
					
					$urlplantilla="./uploads/plantillas/cu_007.txt";
					
					$valor="";
					if($this->documentos!=""){
					foreach ($this->documentos as $key => $value) {
					$valor.= "- ".$value."<br>";
					}
				}
						$array_tags                                 = array();
					$array_tags['documentos_informe']                = $valor;
					$array_tags['fecha_acta']                		= $fechainforme_visita;
			        $array_tags['expediente_acta']                		= $this->expediente;
			        $array_tags['folio_acta']                		= $this->folio;
					$array_tags['nombre_fiscalizador']                		= $this->ion_auth->user()->row()->NOMBRES." ".$this->ion_auth->user()->row()->APELLIDOS;
					$array_tags['doc_fiscalizador']                		= $this->ion_auth->user()->row()->IDUSUARIO;
			        $this->data['informereemplazado']=template_tags($urlplantilla, $array_tags);
							    
				$this->load->view('fiscalizacion/fiscalizacion_generar_acta_via_gubernativa',$this->data);
                        				
                       	
                         

            		
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



function guardar_acta_gubernativa() {
        
		
		date_default_timezone_set("America/Bogota");
		$fechacomunicado= date("d/m/Y");
		$fechaexplode = explode("/", $fechacomunicado);
		$fechanombre= $fechaexplode[0].$fechaexplode[1].$fechaexplode[2];
        $post = $this->input->post();
        print_r($post);
        $this->data['post'] = $post;
        
        $nombre_carpeta = './uploads/fiscalizaciones/'.$post['cod_fisc'];
		$nombre_subcarpeta = './uploads/fiscalizaciones/'.$post['cod_fisc'].'/actagubernativa';

if(!is_dir($nombre_carpeta)){ 
@mkdir($nombre_carpeta, 0777); 
}else{ }  
if(!is_dir($nombre_subcarpeta)){ 
@mkdir($nombre_subcarpeta, 0777); 
}else{ }  
		
		
		$html = $this->input->post('informacion');
        $nombre_pdf = $fechanombre;
		ob_clean();
        $pdf = new Plantilla_PDF_Fiscalizacion(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(30);
        $pdf->SetFooterMargin(30);
		$pdf->SetLeftMargin(30);
		$pdf->SetRightMargin(30);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 8, '', true);
        $pdf->setFooterData('sena');

		$pdf->regional=$this->fiscalizacion_model->infoRegional($this->ion_auth->user()->row()->COD_REGIONAL);
	    $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output("uploads/fiscalizaciones/".$post['cod_fisc']."/actagubernativa/".$nombre_pdf.'.pdf', 'F');
		

		
		//verificar a partir de aca
		$dategestiongenedoc = array( 
								'FECHA_CONTACTO' =>  date("d/m/Y  H:i"),
                        );
                        $datagestiongenedoc= array(
                        
								'COMENTARIOS' => 'Acta via Gubernativa generada',
	                            'COD_TIPO_RESPUESTA' => '11',
	                            'COD_TIPOGESTION' => '9',
                                'NIT_EMPRESA' => $post['nit'],
								'COD_FISCALIZACION_EMPRESA' => $post['cod_fisc'],
								'COD_USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO
                        );				
                        				
                        	
                        $this->fiscalizacion_model->addgestion('GESTIONCOBRO',$datagestiongenedoc,$dategestiongenedoc);
            			$idgestioncobro=$this->fiscalizacion_model->selectIdGestion('GESTIONCOBRO',$datagestiongenedoc,$dategestiongenedoc);
            			$gestactual=$this->fiscalizacion_model->updateGestionActualActaG('FISCALIZACION',$idgestioncobro['COD_GESTION_COBRO'],$post['cod_fisc'],'9');
						$idinformevisita=$this->fiscalizacion_model->selectIdInfVisita('INFORMEVISITA', $post['cod_fisc']);
						
						$dateactasena = array(
                        
								'FECHA_ENVIO_ACTA' => date("d/m/Y"),

                        );	
						
                       $dataactasena = array(
                        
								'COD_INFORME_VISITA' => $idinformevisita['COD_INFORME_VISITA'],
	                            'COD_GESTION_COBRO' => $idgestioncobro['COD_GESTION_COBRO'],
	                            'DOCUMENTOS_SOPORTE' => $nombre_pdf.".pdf"
                        );	
						
						
						
						$this->fiscalizacion_model->addactagubernativa('PROCESOGUBERNATIVO',$dateactasena,$dataactasena);
										

		
    }


function ver_comunicacion_acta_gub() {
	
$this->cod_gestion_cobro = $this->input->post('acta_gub_sena');

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/datatable') || $this->ion_auth->in_menu('fiscalizacion/datatabler'))
               {

                    	
						
				$this->datainf['informe']  = $this->fiscalizacion_model->selectActaGub($this->cod_gestion_cobro);		 
				//var_dump($this->datacomsena['comunicacion']);
               
               //template data
                $this->template->set('title', 'Fiscalización');
                $this->datainf['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->datainf['message']=$this->session->flashdata('message');
				
				
				
                $this->load->view('fiscalizacion/fiscalizacion_ver_acta_via_gub',$this->datainf); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }

}
function ingresar_filtro_agenda() {
	
	
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/ingresar_filtro_agenda'))
							 {
							 	

																 	                   
            		$this->data['custom_error'] = '';
                    $showForm=0;

$this->data['idcargo']=$this->ion_auth->user()->row()->IDCARGO;
if($this->ion_auth->user()->row()->IDCARGO=='3'){
	$this->data['fiscalizadores']=$this->fiscalizacion_model->selectListarFiscalizadores($this->ion_auth->user()->row()->COD_REGIONAL,'5');

	
		}
else{
	
	$this->data['fiscalizadores']=$this->ion_auth->user()->row()->NOMBREUSUARIO;
		
}						
						$this->load->library('datatables');			

                   	$this -> data['style_sheets']= array('css/jquery.dataTables_themeroller.css' => 'screen');		     				
                   $this->template->load($this->template_file, 'fiscalizacion/fiscalizacion_filtro_agenda',$this->data);    	
                         

            		
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



function ver_agenda() {

	
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/ingresar_filtro_agenda'))
							 {
							 	
$fecha_inicio=$this->input->post('fecha_inicio');
$fecha_fin=$this->input->post('fecha_final');
$id_usuario=$this->input->post('fiscalizador_id');

																 	                   
            		$this->data['custom_error'] = '';
                    $showForm=0;
					


return $this->output->set_content_type('application/json')->set_output(json_encode($this->fiscalizacion_model->selectAgenda($fecha_inicio,$fecha_fin,$id_usuario)));
							    
//				$this->load->view('fiscalizacion/fiscalizacion_agenda',$this->data);
                        				
                       	
                         

            		
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

function vista_agenda() {
	$this->load->library('datatables');				
	$this -> data['style_sheets']= array('css/jquery.dataTables_themeroller.css' => 'screen');	
	
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/ingresar_filtro_agenda'))
							 {
							 	
$fecha_inicio=$this->input->post('fecha_inicio');
$fecha_fin=$this->input->post('fecha_final');
$id_usuario=$this->input->post('fiscalizador_id');

											 	                   
            		$this->data['agenda']=$this->fiscalizacion_model->selectVerAgenda($fecha_inicio,$fecha_fin,$id_usuario);
				//var_dump($this->data['agenda']);
				//die();
	$this->load->library('datatables');				
	$this -> data['style_sheets']= array('css/jquery.dataTables_themeroller.css' => 'screen');						    
$this->load->view('fiscalizacion/fiscalizacion_agenda',$this->data);
                        				
                       	
                         

            		
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

function ingresar_detalle_empresa_fiscalizar() {
	
	
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/ingresar_detalle_empresa_fiscalizar'))
							 {
							 	

																 	                   
            		$this->data['custom_error'] = '';
                    $showForm=0;

				
								

                        				
$this->template->load($this->template_file, 'fiscalizacion/fiscalizacion_empresa_fiscalizar',$this->data);    	
                         

            		
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

function detalle_empresa_fiscalizar(){
	
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/detalle_empresa_fiscalizar'))
							 {
	        
								         
										 
					$this->nit=$this->input->post('cod_nit');				 


                   
                    $this->data['empresa']  = $this->fiscalizacion_model->selectEmpresa($this->nit);
					$this->data['conceptos']  = $this->fiscalizacion_model->getSelectConceptosConsulta('CONCEPTOSFISCALIZACION','COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO');
        
								
								//template data
								$this->template->set('title', 'Fiscalizacion');
								
								
								$this->data['message']=$this->session->flashdata('message');
								$this->template->load($this->template_file, 'fiscalizacion/fiscalizacion_detalle_empresa_fisc',$this->data); 
							 }else {
								$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
											redirect(base_url().'index.php/inicio');
							 } 

						}else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}

function datapagosempresa(){
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/detalle_empresa_fiscalizar'))
							 {
 
					$this->concepto_id = str_replace("/", "-", $this->input->post('concepto_id'));				 
					$this->fecha_inicio = str_replace("/", "-", $this->input->post('fecha_inicio'));	
					$this->fecha_final = str_replace("/", "-", $this->input->post('fecha_final'));
					$this->cod_nit = str_replace("/", "-", $this->input->post('cod_nit'));
						

								//template data
								$this->template->set('title', 'Fiscalizacion');
								 $this->data['empresas']  = $this->fiscalizacion_model->selectEmpresa($this->cod_nit);
								
								$this->data['message']=$this->session->flashdata('message');
								
								$this->data['concepto']=$this->concepto_id;
								$datospago=$this->fiscalizacion_model->datapagosaplicados($this->concepto_id, $this->fecha_inicio, $this->fecha_final, $this->cod_nit);
								
								$pagoim=array();
								$pagoanio=array();
								$pagoanioim=array();
									
								$pago=array();
								foreach ($datospago->result_array as $data) {
								$fechap=explode("-", $data['FECHA']);
								$año=$fechap[0];
								$añomes=$fechap[0].$fechap[1];	

								if(!empty($pago[$data['COD_CONCEPTO'].$añomes])){			
								$pago[$data['COD_CONCEPTO'].$añomes]=	$pago[$data['COD_CONCEPTO'].$añomes]+$data['DISTRIBUCION_CAPITAL']+$data['DISTRIBUCION_INTERES'];
								}
								else
									{
								$pago[$data['COD_CONCEPTO'].$añomes]=	$data['DISTRIBUCION_CAPITAL']+$data['DISTRIBUCION_INTERES'];
									}
									
								if(!empty($pagoim[$data['COD_CONCEPTO'].$añomes])){			
								$pagoim[$data['COD_CONCEPTO'].$añomes]=	$pagoim[$data['COD_CONCEPTO'].$añomes]+$data['DISTRIBUCION_INTERES_MORA'];
								
								}
								else
									{
								$pagoim[$data['COD_CONCEPTO'].$añomes]=	$data['DISTRIBUCION_INTERES_MORA'];
								
									}
										
									if(!empty($pagoanio[$data['COD_CONCEPTO'].$año])){			
								$pagoanio[$data['COD_CONCEPTO'].$año]=	$pagoanio[$data['COD_CONCEPTO'].$año]+$data['DISTRIBUCION_CAPITAL']+$data['DISTRIBUCION_INTERES'];
								}
								else
									{
								$pagoanio[$data['COD_CONCEPTO'].$año]=	$data['DISTRIBUCION_CAPITAL']+$data['DISTRIBUCION_INTERES'];
									}

								
									if(!empty($pagoanioim[$data['COD_CONCEPTO'].$año])){			
								$pagoanioim[$data['COD_CONCEPTO'].$año]=	$pagoanio[$data['COD_CONCEPTO'].$año]+$data['DISTRIBUCION_INTERES_MORA'];
								}
								else
									{
								$pagoanioim[$data['COD_CONCEPTO'].$año]=	$data['DISTRIBUCION_INTERES_MORA'];
									}

								} 

								
								
								
								$this->data['pago']=$pago;
								$this->data['pagoanio']=$pagoanio;
								$this->data['pagoim']=$pagoim;
								$this->data['pagoanioim']=$pagoanioim;
							//var_dump($pago);
							//die();
								
		 $tmp = explode("-", $this->fecha_inicio);
          $this->data['year']['inicio'] = $tmp[2];
          $tmp = explode("-", $this->fecha_final);
          $this->data['year']['fin'] = $tmp[2];
          
          $tmp = explode("-", $this->fecha_inicio);
          $tmp2 = explode("-", $this->fecha_final);
		  $inicio="1";
		  //var_dump($tmp2);
		  //die();
          for($x=$this->data['year']['inicio']; $x<=$this->data['year']['fin']; ++$x) :
            if($x == $this->data['year']['inicio'] and $x < $this->data['year']['fin']) :
              $this->data['mes'][$x]['mesinicio'] = $tmp[1];
              $this->data['mes'][$x]['mesfin'] = 12;
            elseif($x < $this->data['year']['fin']) :
              $this->data['mes'][$x]['mesinicio'] = 1;
              $this->data['mes'][$x]['mesfin'] = 12;
			elseif($x == $this->data['year']['fin'] and $this->data['year']['inicio'] == $this->data['year']['fin']) :
              $this->data['mes'][$x]['mesinicio'] = $tmp[1];
              $this->data['mes'][$x]['mesfin'] = $tmp2[1];              
            elseif($x == $this->data['year']['fin']) :
              $this->data['mes'][$x]['mesinicio'] = $inicio;
              $this->data['mes'][$x]['mesfin'] = $tmp2[1];              
            endif;             
			//var_dump($this->data['mes']); 
          endfor;
								
								
								$this->load->view('fiscalizacion/fiscalizacion_detalle_pagos',$this->data); 
							 }else {
								$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
											redirect(base_url().'index.php/inicio');
							 } 

						}else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}

  private function mes($mes) {
    switch($mes) :
      case "01" : return "Ene"; break;
      case "02" : return "Feb"; break;
      case "03" : return "Mar"; break;
      case "04" : return "Abr"; break;
      case "05" : return "May"; break;
      case "06" : return "Jun"; break;
      case "07" : return "Jul"; break;
      case "08" : return "Ago"; break;
      case "09" : return "Sep"; break;
      case "10" : return "Oct"; break;
      case "11" : return "Nov"; break;
      case "12" : return "Dic"; break;
    endswitch;
  }
  
  public function mes2($mes) {
    switch($mes) :
      case "01" : return "Enero"; break;
      case "02" : return "Febrero"; break;
      case "03" : return "Marzo"; break;
      case "04" : return "Abril"; break;
      case "05" : return "Mayo"; break;
      case "06" : return "Junio"; break;
      case "07" : return "Julio"; break;
      case "08" : return "Agosto"; break;
      case "09" : return "Septiembre"; break;
      case "10" : return "Octubre"; break;
      case "11" : return "Noviembre"; break;
      case "12" : return "Diciembre"; break;
    endswitch;
  }

function detalle_liquidaciones(){
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/detalle_liquidaciones'))
							 {
	        
								         
										 
					$nit=$this->input->post('cod_nit');				 


                       
                    $this->data['empresa']  = $this->fiscalizacion_model->selectEmpresa($nit);
					$this->data['conceptos']  = $this->fiscalizacion_model->getSelectConceptosConsulta('CONCEPTOSFISCALIZACION','COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO');
        
								
								//template data
								$this->template->set('title', 'Fiscalizacion');
								
								
								$this->data['message']=$this->session->flashdata('message');
								$this->template->load($this->template_file, 'fiscalizacion/fiscalizacion_detalle_liquidaciones_empresa_fisc',$this->data); 
							 }else {
								$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
											redirect(base_url().'index.php/inicio');
							 } 

						}else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}

function dataliquidacion(){
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/detalle_liquidaciones'))
							 {
 
					$this->concepto_id = $this->input->post('concepto_id');				 
					$this->tipo_id = $this->input->post('tipo_id');		
					$this->fecha_inicio = $this->input->post('fecha_inicio');		
					$this->fecha_final = $this->input->post('fecha_final');	
					$this->cod_nit = $this->input->post('cod_nit');	

								//template data
								$this->template->set('title', 'Fiscalizacion');
								
								
								$this->data['message']=$this->session->flashdata('message');
								
								$this->data['datosliq']=$this->fiscalizacion_model->dataliquidacion($this->concepto_id, 0, $this->fecha_inicio, $this->fecha_final, $this->cod_nit);
								$this->data['datosres']=$this->fiscalizacion_model->dataliquidacion($this->concepto_id, 1, $this->fecha_inicio, $this->fecha_final, $this->cod_nit);
								

								$this->data['concepto']=$this->concepto_id;
								$this->data['tipo']=$this->tipo_id;

								
								$this->load->view('fiscalizacion/fiscalizacion_liquidaciones_resoluciones',$this->data); 
							 }else {
								$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
											redirect(base_url().'index.php/inicio');
							 } 

						}else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}



  public function exportarxl() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/detalle_empresa_fiscalizar')) {
        	
header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=ficheroExcel.xls");
header("Pragma: no-cache");
header("Expires: 0");
echo utf8_decode($_POST['datos_envio']);
	  
	  } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
  
  
   function traernits() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/manage')) {
        $nit = $this->input->get('term');
        if (empty($nit)) {
          redirect(base_url() . 'index.php/fiscalizacion');
        } else {
          $this->fiscalizacion_model->set_nit($nit);
          return $this->output->set_content_type('application/json')->set_output(json_encode($this->fiscalizacion_model->buscarnits()));
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  } 



function pdf() {
	
        $html = $this->input->post('html');
        $nombre_pdf = $this->input->post('nombre');
		$mod = $this->input->post('mod');
        ob_clean();
        $pdf = new Plantilla_PDF_Fiscalizacion(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(30);
        $pdf->SetFooterMargin(30);
		$pdf->SetLeftMargin(30);
		$pdf->SetRightMargin(30);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 8, '', true);
        $pdf->setFooterData('sena');

		$pdf->regional=$this->fiscalizacion_model->infoRegional($this->ion_auth->user()->row()->COD_REGIONAL);
	    $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($nombre_pdf . '.pdf', $mod);
        exit();
    }

}



