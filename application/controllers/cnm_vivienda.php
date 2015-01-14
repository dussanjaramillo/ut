<?php

class Fiscalizacion extends MY_Controller {
		
		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');		
		$this->load->helper(array('form','url','codegen_helper','template_helper'));
		$this->load->model('carteranomisional_model','',TRUE);
		 
		 
		 $this->data['javascripts'] = array(
            
            'js/tinymce/tinymce.jquery.min.js',
			'js/jquery.dataTables.min.js',
        	'js/jquery.dataTables.defaults.js',
        	'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/validateForm.js',
        	'js/chosen.jquery.min.js'
        	
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
    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/add'))
							 {
																 	                    $this->load->library('form_validation');    
            		$this->data['custom_error'] = '';
                    $showForm=0;
					
					
					
        			$this->form_validation->set_rules('periodo_inicio', 'periodo_inicio', 'required');
        			$this->form_validation->set_rules('periodo_fin', 'periodo_fin', 'required'); 
            		$this->form_validation->set_rules('concepto_id', 'concepto_id',  'required|numeric|greater_than[0]');    
 									
					
                    if ($this->form_validation->run() == false)
                    {
                         $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                    } else
                    {
                    	
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
								$idfiscalizacion=$this->fiscalizacion_model->selectIdFisc('FISCALIZACION',$data,$date);
								
						  		$dategestion = array( 
								'FECHA_CONTACTO' =>  date("d/m/Y  H:i"),
                        );
                        $datagestion= array(
                        
								'COMENTARIOS' => 'Fiscalización Creada',
	                            'COD_TIPO_RESPUESTA' => '1',
	                            'COD_TIPOGESTION' => '1',
                                'NIT_EMPRESA' => $nit ['NIT_EMPRESA'],
								'COD_FISCALIZACION_EMPRESA' => $idfiscalizacion ['COD_FISCALIZACION'],
								 'COD_USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO
								
                        );				
                        				
                        	
                        $this->fiscalizacion_model->addgestion('GESTIONCOBRO',$datagestion,$dategestion);
            			$idgestioncobro=$this->fiscalizacion_model->selectIdGestion('GESTIONCOBRO',$datagestion,$dategestion);

						
						$gestactual=$this->fiscalizacion_model->updateGestionActual('FISCALIZACION',$idgestioncobro['COD_GESTION_COBRO'],$idfiscalizacion ['COD_FISCALIZACION'],'1');		

						//die();			
            			  $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Fiscalización se agregó con éxito.</div>');
                          redirect(base_url().'index.php/fiscalizacion/');
            			}
            			else
            			{
            				$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

            			}

}
            			else{
            				$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Esta empresa ya tiene una Fiscalizacion Creada para este periodo por este mismo Concepto. La Fiscalización no se ha agregado</div>');
                          redirect(base_url().'index.php/fiscalizacion/');
							
            			}


						
            		} 
										//add style an js files for inputs selects
										$this->data['style_sheets']= array(
														'css/chosen.css' => 'screen'
												);

										$this->data['conceptos']  = $this->fiscalizacion_model->getSelect('CONCEPTOSFISCALIZACION','COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO');
										$this->data['cod_asign']  = $this->cod_asign; 
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
																	
																	//add style an js files for inputs selects
																	$this->data['style_sheets']= array(
																					'css/chosen.css' => 'screen'
																			);

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
				$this->razon_social = mb_strtolower($this->input->post('razon_social'));	
				$this->load->library('datatables');
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/manage'))
							 {
							
							 if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/edit'))
							 {
							 	$this->load->library('datatables');	
								$this->load->model('fiscalizacion_model');	
							 	$this->data['funcion']=$this->fiscalizacion_model->datatable1($this->nit_consulta, $this->cod_asignacion_fisc);
								$this->data['empresa']=$this->fiscalizacion_model->selectEmpresa($this->nit_consulta);
								$this->data['cod_asign_fisc']=$this->cod_asignacion_fisc;
								//var_dump($this->data['funcion']);
								
								//$this->template->load($this->template_file, 'fiscalizacion/fiscalizacion_validar_sop', $this->data);
								$this -> data['style_sheets']= array('css/jquery.dataTables_themeroller.css' => 'screen');
								$this->template->load($this->template_file, 'fiscalizacion/datatable',$this->data);
								
								//$this->load->library('datatables');
								
																			
							}else{}
								//echo $this->datatables->generate();
									
                   }else {
										/*$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/fiscalizacion');*/ 
									 }
						}else
						{
							//redirect(base_url().'index.php/auth/login');
						}          
		}




}


