<?php

class Procinfoposdeudores extends MY_Controller {
		
		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');		
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('codegen_model','',TRUE);
		$this->load->model('procinfoposdeudores_model');

	}	
	
	function index(){
		$this->manage();
	}

	function manage(){
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procinfoposdeudores/manage'))
							 {
								//template data
								$this->template->set('title', 'Extraer información de E-Collect');
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen'
												);
								$this->data['javascripts']= array(
														'js/jquery.dataTables.min.js',
														'js/jquery.dataTables.defaults.js'
												);
								$this->data['message']=$this->session->flashdata('message');
								$this->template->load($this->template_file, 'procinfoposdeudores/procinfoposdeudores_list',$this->data); 
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
				   if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procinfoposdeudores/add'))
				   {   
				       $this->data['custom_error'] = ''; 
				      
				       

					   $this->template->load($this->template_file, 'procinfoposdeudores/procinfoposdeudores_add', $this->data);
				   }else {
					   $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					   redirect(base_url().'index.php/procinfoposdeudores');
			        }
				}
				else
				{
					redirect(base_url().'index.php/auth/login');
				}
		}	
	
		function cargue(){        
				if ($this->ion_auth->logged_in())
			    {
				   if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procinfoposdeudores/add'))
				   {   
				       
				       $this->data['custom_error'] = '';  
				       $this->data['registros'] = ''; 								
					 
					   $path = "uploads/subirarchivoconfecamaras/";
                       
                       // |:::::Crea el directorio si no existe
                       if(!is_dir($path)) 
                       {
                          mkdir($path,0777,TRUE);
                       }

                       foreach (array_combine($_FILES['uploads']['name'],  $_FILES['uploads']['tmp_name']) as $file => $filet) {

						
						 			if (file_exists($path . $file)){

										
										$this->data['custom_error'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>No se pudo  cargar el archivo, '.$file. ' Este archivo ya existe</div>';
									}else{
											$extension = $this->obtenerExtensionFichero($file);

											if ($extension=='txt' or $extension=='TXT') {
												
												
												// |:::::subir el archivo al servidor
												move_uploaded_file($filet, $path . $file);
												
												// |:::::loop through the csv file and insert into database 
												$this->load->database(); 
												$handle = fopen($filet,"r");	
											    
											   
												 while (($data = fgetcsv($handle,0,"|")) !== FALSE)
												{
											        
											            mysql_query("INSERT INTO BAS_COMFECAMARAS (CODIGO_CAMARA,
																									NIT,
																									RAZON_SOCIAL,
																									COD_CLASE_IDENTIFICACION,
																									NUMERO_IDENTIFICACION,
																									DIG_VERIFICACION,
																									COD_MUN_COMERCIAL,
																									DIRECCION_COMERCIAL,
																									TELEFONO_COMERCIAL,
																									COD_MUNICIPIO_FISCAL,
																									DIRECCION_FISCAL,
																									TELEFONO_FISCAL,
																									EMAIL,
																									COD_ADMON_DIAN,
																									ULTIMO_ANNO_RENOVADO,
																									FECHA_MATRICULA,
																									COD_ORGANIZACION_JURIDICA,
																									CODIGO_TIPO_SOCIEDAD,
																									CODIGO_CATEGORIA_MATRICULA,
																									CODIGO_ESTADO_MATRICULA,
																									CIIU,
																									CAPITAL_SOCIAL,
																									ACTIVOS,
																									EMPLEADOS,
																									INDICADOR_BENEFICIO_CAMARA,
																									FECHA_RENOVACION,
																									REPRESENTANTE_LEGAL,
																									TIPO_IDENTIFICACION_RL,
																									NUMERO_IDENTIFICACION_RL,
																									FECHA_INICIO_ACT_ECONOMICA,
																									CANTIDAD_ESTABLECIMIENTOS,
																									FECHA_CANCELACION,
																									VENTAS_NETAS_ING_OPER,
																									INGRESOS_NO_OPERACIONALES) 
															VALUES 
											                ( 
											                    '".addslashes($data[0])."', 
											                    '".addslashes($data[1])."', 
											                    '".addslashes($data[2])."', 
											                    '".addslashes($data[3])."', 
											                    '".addslashes($data[4])."', 
											                    '".addslashes($data[5])."', 
											                    '".addslashes($data[6])."', 
											                    '".addslashes($data[7])."', 
											                    '".addslashes($data[8])."', 
											                    '".addslashes($data[9])."',
											                    '".addslashes($data[10])."',
											                    '".addslashes($data[11])."', 
											                    '".addslashes($data[12])."', 
											                    '".addslashes($data[13])."', 
											                    '".addslashes($data[14])."', 
											                    '".addslashes($data[15])."', 
											                    '".addslashes($data[16])."', 
											                    '".addslashes($data[17])."', 
											                    '".addslashes($data[18])."', 
											                    '".addslashes($data[19])."', 
											                    '".addslashes($data[20])."',
											                    '".addslashes($data[21])."',
											                    '".addslashes($data[22])."', 
											                    '".addslashes($data[23])."', 
											                    '".addslashes($data[24])."',
											                    '".addslashes($data[25])."',
											                    '".addslashes($data[26])."', 
											                    '".addslashes($data[27])."', 
											                    '".addslashes($data[28])."', 
											                    '".addslashes($data[29])."', 
											                    '".addslashes($data[30])."', 
											                    '".addslashes($data[31])."', 
											                    '".addslashes($data[32])."', 
											                    '".addslashes($data[33])."' 
											                     
											                ) 
											            "); 
											       
											        $i = 0;
												    foreach($data as $row) {
												 
												         //echo "Campo $i: $row<br />";
												        echo "'$data[$i]',<br />";
												        // Muestra todos los campos de la fila actual
												        $i++ ;
												 
												    }
												 
												    echo "<br /><br />";

											    }// |:::::Fin while
												
												/*//Query que deberia importar los datos del archivo confecamaras directamente con LOAD DATA INFILE		
												$this->load->database(); 
												$this->db->query("LOAD DATA INFILE ".trim($filet)." REPLACE INTO TABLE BAS_COMFECAMARAS
																			FIELDS TERMINATED BY '|' 
																			LINES TERMINATED BY '\\n' 
																			IGNORE 1 LINES 
																			   (CODIGO_CAMARA,
																				NIT,
																				RAZON_SOCIAL,
																				COD_CLASE_IDENTIFICACION,
																				NUMERO_IDENTIFICACION,
																				DIG_VERIFICACION,
																				COD_MUN_COMERCIAL,
																				DIRECCION_COMERCIAL,
																				TELEFONO_COMERCIAL,
																				COD_MUNICIPIO_FISCAL,
																				DIRECCION_FISCAL,
																				TELEFONO_FISCAL,
																				EMAIL,
																				COD_ADMON_DIAN,
																				ULTIMO_ANNO_RENOVADO,
																				FECHA_MATRICULA,
																				COD_ORGANIZACION_JURIDICA,
																				CODIGO_TIPO_SOCIEDAD,
																				CODIGO_CATEGORIA_MATRICULA,
																				CODIGO_ESTADO_MATRICULA,
																				CIIU,
																				CAPITAL_SOCIAL,
																				ACTIVOS,
																				EMPLEADOS,
																				INDICADOR_BENEFICIO_CAMARA,
																				FECHA_RENOVACION,
																				REPRESENTANTE_LEGAL,
																				TIPO_IDENTIFICACION_RL,
																				NUMERO_IDENTIFICACION_RL,
																				FECHA_INICIO_ACT_ECONOMICA,
																				CANTIDAD_ESTABLECIMIENTOS,
																				FECHA_CANCELACION,
																				VENTAS_NETAS_ING_OPER,
																				INGRESOS_NO_OPERACIONALES)" 
																		 );*/
													
        
												//$query = $this->db->query("LOAD DATA INFILE ? REPLACE INTO TABLE item FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\\n' (id, product_id, @dummy, @dummy, item_code, @dummy, @retail_price, @dummy, item_status, @dummy) SET retail_price = substring(@retail_price, 2, 100)",array('/tmp/capelite_item.csv'));
       
											
											    //if ($query) {
										            //$data['message'] = "All items imported successfully.";
										        //} else {
										            //$data['message'] = "Import failed.";
										       // }
												$this->data['registros'] = count(file($filet)) - 1;
												//$this->posiblesDeudores();
												//$string = file_get_contents($path . $file);
												$this->data['custom_error'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El archivo: '.$file. ' Se ha cargado correctamente</div>';
											}else{
												$this->data['custom_error'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>No se pudo  cargar el archivo, '.$file. ' El formato valido es txt</div>'; 
											}
										}
						}

                      
                      
                    
					   $this->template->load($this->template_file, 'procinfoposdeudores/procinfoposdeudores_result', $this->data);

				   }else {
					   $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					   redirect(base_url().'index.php/procinfoposdeudores');
			        }
				}
				else
				{
					redirect(base_url().'index.php/auth/login');
				}
		}



	function obtenerExtensionFichero($str) 
	{
	        return end(explode(".", $str));
			
	}

	function posiblesDeudores(){
		/*Segun lo solicitado las validaciones se deben hacer segun la informacion del SGVA y las tablas o tabla donde se suben los pagos de planilla unica
		* Existen tres tipos de condiciones para catalogar una empresa para ser fiscalizada:
		*		1. empresa evasora: Enero == null,
									Febrero == null, 
									Marzo == null, 
									Abril == null, 
									Mayo == null, 
									Junio == null, 
									Julio == null, 
									Agosto == null, 
									Septiembre == null, 
									Octubre == null, 
									Noviembre == null, 
									Diciembre == null
									Posiblemente la validacion de todos los periodos pueda hacerse de la forma if(enero == null && febrero == null && etc);

				2. Empresa Morosa: se define porque tiene periodos sin pagos registrados. Posiblemente puede hacerse de la forma  if(enero == null || febrero == null || etc);

				3. Empresa Elusora: se define porque tiene pagos mayores en unos periodos que en otros. posiblemente puede hacerse de la forma if(enero > febrero || enero > marzo || enero > etc);

		 Observaciones a tener en cuenta: - no se evaluaran periodos que esten inactivos segun el caso de uso RQ06_CU011_Activar e Inactivar periodos,
		 									por tanto, primero se entrara a evaluar si existen periodos inactivos o bloqueados para cada empresa.

		 Datos de entrada necesarios: - NIT de la empresa -> dato principal de las validaciones, debera venir desde la tabla BAS_COMFECAMARAS -> NIT, del sistema SGVA y del caso de uso RQ07_CU003_Aplicar Pagos automáticos
		 							  - Periodos de pago ->
		 							  - Valor de pago en ese periodo
		 							  - Periodos incativos -> PERIODOSINACTIVOS -> COD_EMPRESA
																				   ANNO
																		     	   PERIODO

		 Datos de salida para identificar las empresas como posible deudora: - Nit
		 																	 - Razon social
		 																	 - Regional

		*/
		
		// |:::::Consultar BD
		
		// |:::::Consulta que contiene solo los nit que aparecen con periodos inactivos
		$comfecamaras_periodosinactivos = $this->procinfoposdeudores_model->nitConPeriodosInactivos;

		$periodos = $this->codegen_model->getSelect('PERIODOSINACTIVOS','COD_EMPRESA');//::::: nit y conjunto de datos que proviene de la tabla PERIODOSINACTIVOS
		
		$tipo_deudor = 0;
		
		// |::::: buscar que nits coinciden entre comfecamaras y periodo_inactivo.
		$num = 0;
			
		foreach($$comfecamaras_periodosinactivos as $key => $value) {
		    	    
		    foreach($periodos as $key2 => $value2) {
		    		
		    		// |:::::Evalua si los NIT provenientes de Confecamaras ($value) estan registrados con periodos inactivos ($value2)
			        if($value == $value2)
			        {
			            $num++;
			            $nit = $value;
			            
			            // |:::::Contiene todos los periodos registrados con pagos asociados al NIT
			            $periodos_pila = $this->periodosPila($nit);

			            // |:::::Contiene todos lo periodos inactivos asociados al NIT
			            $periodos_inactivos = $this->periodosInactivos($nit);
			            
			            
			            

			        }else{
			        	
			        	//:::::en caso de que no tenga periodos inactivos
			            $comfecamaras_pila = $this->procinfoposdeudores_model->nitConPagosEnPila;

			            foreach ($comfecamaras_pila as $key => $value) {
			            	$nit = $value;

			            	// |:::::Contiene todos los periodos registrados con pagos asociados al NIT
				            $periodos_pila = $this->periodosPila($nit);

				            foreach ($periodos_pila as $mes => $valor) {
				            			
				            	if ($mes['enero'] == null && $mes['febrero'] == null && $mes['marzo'] == null && $mes['abril'] == null && $mes['mayo'] == null && $mes['junio'] == null && $mes['julio'] == null && $mes['agosto'] == null && $mes['septiembre'] == null && $mes['octubre'] == null && $mes['noviembre'] == null && $mes['diciembre'] == null) {
				            				
				            		// |:::::1. Empresa Evasora
				            		$tipo_deudor = 1;

				            	}else{

				            		if ($mes['enero'] == null || $mes['febrero'] == null || $mes['marzo'] == null || $mes['abril'] == null || $mes['mayo'] == null || $mes['junio'] == null || $mes['julio'] == null || $mes['agosto'] == null || $mes['septiembre'] == null || $mes['octubre'] == null || $mes['noviembre'] == null || $mes['diciembre'] == null) {
				            					
				            			// |:::::2. Empresa Morosa
				            			$tipo_deudor = 2;
				            				
				            		}else{

				            			if (condition) {
				            						
				            				// |:::::3. Empresa Elusora
				            				$tipo_deudor = 3;
				            			}
				            		}
				            	}
				            }
				            // |:::::validaciones luego de identificar los periodos que aparecen en pila
			            }
			        }

			    $this->identificarEmpresa($tipo_deudor,$nit);  
			}
		}
		if($num >  0){
			echo "Existen ".$num." coincidencias";
		}else{
			echo "No existen coincidencias";

		}

	}

	function periodosPila($nit){
		
		// |:::::contiene los pagos realizados registrados en planilla unica relacionados al NIT que se esta evaluando
		$pila = $this->codegen_model->getSelect('PLANILLAUNICA_DET','PERIODO_DE_PAGO,VALOR_TOTAL_NOMINA','WHERE IDENTIFICACION_APORTANTE = '.$nit);

				 		    
			foreach ($pila as $key => $value) {

			  	if ($key->PERIODO_DE_PAGO == 'enero' || $key->PERIODO_DE_PAGO == 'Enero' || $key->PERIODO_DE_PAGO == 'ENERO') {
			        $periodo = array('enero' => $key->VALOR_TOTAL_NOMINA);
			    }else{
			        $periodo = array('enero' => null);
			            				
			        }
			            			
			        if ($key->PERIODO_DE_PAGO == 'febrero' || $key->PERIODO_DE_PAGO == 'Febrero' || $key->PERIODO_DE_PAGO == 'FEBRERO') {
			            $periodo = array('febrero' => $key->VALOR_TOTAL_NOMINA);
			        }else{
			            $periodo = array('febrero' => null);
			            				
			            }

			            if ($key->PERIODO_DE_PAGO == 'marzo' || $key->PERIODO_DE_PAGO == 'Marzo' || $key->PERIODO_DE_PAGO == 'MARZO') {
			            	$periodo = array('marzo' => $key->VALOR_TOTAL_NOMINA);
				        }else{
				        	$periodo = array('marzo' => null);
				            				
				        	}

				           	if ($key->PERIODO_DE_PAGO == 'abril' || $key->PERIODO_DE_PAGO == 'Abril' || $key->PERIODO_DE_PAGO == 'ABRIL') {
					        	$periodo = array('abril' => $key->VALOR_TOTAL_NOMINA);
					        }else{
					        	$periodo = array('abril' => null);
					            				
					        	}

					            if ($key->PERIODO_DE_PAGO == 'mayo' || $key->PERIODO_DE_PAGO == 'Mayo' || $key->PERIODO_DE_PAGO == 'MAYO') {
						        	$periodo = array('mayo' => $key->VALOR_TOTAL_NOMINA);
						        }else{
						        	$periodo = array('mayo' => null);
						            				
						        	}

						        	if ($key->PERIODO_DE_PAGO == 'junio' || $key->PERIODO_DE_PAGO == 'Junio' || $key->PERIODO_DE_PAGO == 'JUNIO') {
							        	$periodo = array('junio' => $key->VALOR_TOTAL_NOMINA);
							        }else{
							        	$periodo = array('junio' => null);
							            				
							        	}

							        	if ($key->PERIODO_DE_PAGO == 'julio' || $key->PERIODO_DE_PAGO == 'Julio' || $key->PERIODO_DE_PAGO == 'JULIO') {
								    		$periodo = array('julio' => $key->VALOR_TOTAL_NOMINA);
								    	}else{
								    		$periodo = array('julio' => null);
								            				
								    		}

								            if ($key->PERIODO_DE_PAGO == 'agosto' || $key->PERIODO_DE_PAGO == 'Agosto' || $key->PERIODO_DE_PAGO == 'AGOSTO') {
									        	$periodo = array('agosto' => $key->VALOR_TOTAL_NOMINA);
									        }else{
									        	$periodo = array('agosto' => null);
									            				
									        	}

									        	if ($key->PERIODO_DE_PAGO == 'septiembre' || $key->PERIODO_DE_PAGO == 'Septiembre' || $key->PERIODO_DE_PAGO == 'SEPTIEMBRE') {
										    		$periodo = array('septiembre' => $key->VALOR_TOTAL_NOMINA);
										    	}else{
										    		$periodo = array('septiembre' => null);
										           				
										    		}

										    		if ($key->PERIODO_DE_PAGO == 'octubre' || $key->PERIODO_DE_PAGO == 'Octubre' || $key->PERIODO_DE_PAGO == 'OCTUBRE') {
											    		$periodo = array('octubre' => $key->VALOR_TOTAL_NOMINA);
											    	}else{
											    		$periodo = array('octubre' => null);
											            				
											        	}

											        	if ($key->PERIODO_DE_PAGO == 'noviembre' || $key->PERIODO_DE_PAGO == 'Noviembre' || $key->PERIODO_DE_PAGO == 'NOVIEMBRE') {
												    		$periodo = array('noviembre' => $key->VALOR_TOTAL_NOMINA);
												    	}else{
												    		$periodo = array('noviembre' => null);
												            				
												    		}

												    		if ($key->PERIODO_DE_PAGO == 'diciembre' || $key->PERIODO_DE_PAGO == 'Diciembre' || $key->PERIODO_DE_PAGO == 'DICIEMBRE') {
																$periodo = array('diciembre' => $key->VALOR_TOTAL_NOMINA);
															}else{
																$periodo = array('diciembre' => null);
													            				
																}
			}// |:::::Fin de la busqueda en PILA
			            		
			return $periodo;
	}

	function periodosInactivos($nit){

		// |:::::Consulta los periodos inactivos registrados a la empresa segun su NIT
		$periodo_inactivo = $this->codegen_model->getSelect('PERIODOSINACTIVOS','PERIODO','WHERE COD_EMPRESA = '.$nit);//contiene los periodos inactivos relacionados al nit

		foreach ($periodo_inactivo as $key => $value) {
			
			if ($key->PERIODO == '01') {
			        $periodo = array('enero' => TRUE);
			    }else{
			        $periodo = array('enero' => FALSE);
			            				
			        }
			            			
			        if ($key->PERIODO == '02') {
			            $periodo = array('febrero' => TRUE);
			        }else{
			            $periodo = array('febrero' => FALSE);
			            				
			            }

			            if ($key->PERIODO == '03') {
			            	$periodo = array('marzo' => TRUE);
				        }else{
				        	$periodo = array('marzo' => FALSE);
				            				
				        	}

				           	if ($key->PERIODO == '04') {
					        	$periodo = array('abril' => TRUE);
					        }else{
					        	$periodo = array('abril' => FALSE);
					            				
					        	}

					            if ($key->PERIODO == '05') {
						        	$periodo = array('mayo' => TRUE);
						        }else{
						        	$periodo = array('mayo' => FALSE);
						            				
						        	}

						        	if ($key->PERIODO == '06') {
							        	$periodo = array('junio' => TRUE);
							        }else{
							        	$periodo = array('junio' => FALSE);
							            				
							        	}

							        	if ($key->PERIODO == '07') {
								    		$periodo = array('julio' => TRUE);
								    	}else{
								    		$periodo = array('julio' => FALSE);
								            				
								    		}

								            if ($key->PERIODO == '08') {
									        	$periodo = array('agosto' => TRUE);
									        }else{
									        	$periodo = array('agosto' => FALSE);
									            				
									        	}

									        	if ($key->PERIODO == '09') {
										    		$periodo = array('septiembre' => TRUE);
										    	}else{
										    		$periodo = array('septiembre' => FALSE);
										           				
										    		}

										    		if ($key->PERIODO == '10') {
											    		$periodo = array('octubre' => TRUE);
											    	}else{
											    		$periodo = array('octubre' => FALSE);
											            				
											        	}

											        	if ($key->PERIODO == '11') {
												    		$periodo = array('noviembre' => TRUE);
												    	}else{
												    		$periodo = array('noviembre' => FALSE);
												            				
												    		}

												    		if ($key->PERIODO == '12') {
																$periodo = array('diciembre' => TRUE);
															}else{
																$periodo = array('diciembre' => FALSE);
													            				
																}
		}
		return $periodo;
	}

	function identificarEmpresa($tipo_deudor, $nit){

		switch ($tipo_deudor) {
			
			case 1:
				// |:::::datos que se cargaran en la tabla EMPRESASEVASORAS
				$dateevasoras = array(
									'FECHA_PROCESAMIENTO' => date("d/m/Y")

				);

				$dataevasoras = array(
									'COD_EMP_EVASORA' => $nit,
									'COD_EMPRESA' => $nit
																			
				);

				// |:::::datos que se cargaran en la tabla EMPRESASEVASORAS_DET
				$datadetalle = array(
									'COD_EMP_EVASORA' => $nit,
									'OBSERVACIONES' => 'EMPRESA IDENTIFICADA COMO EVASORA'
				);

				$this->codegen_model->add('EMPRESASEVASORAS',$dataevasoras,$dateevasoras);
				$this->codegen_model->add('EMPRESASEVASORAS_DET',$datadetalle);
				break;

			case 2:
				// |:::::datos que se cargaran en la tabla EMPRESASEVASORAS
				$dateevasoras = array(
									'FECHA_PROCESAMIENTO' => date("d/m/Y")

				);

				$dataevasoras = array(
									'COD_EMP_EVASORA' => $nit,
									'COD_EMPRESA' => $nit
																			
				);

				// |:::::datos que se cargaran en la tabla EMPRESASEVASORAS_DET
				$datadetalle = array(
									'COD_EMP_EVASORA' => $nit,
									'OBSERVACIONES' => 'EMPRESA IDENTIFICADA COMO MOROSA'
				);

				$this->codegen_model->add('EMPRESASEVASORAS',$dataevasoras,$dateevasoras);
				$this->codegen_model->add('EMPRESASEVASORAS_DET',$datadetalle);
				break;

			case 3:
				// |:::::datos que se cargaran en la tabla EMPRESASEVASORAS
				$dateevasoras = array(
									'FECHA_PROCESAMIENTO' => date("d/m/Y")

				);

				$dataevasoras = array(
									'COD_EMP_EVASORA' => $nit,
									'COD_EMPRESA' => $nit
																			
				);

				// |:::::datos que se cargaran en la tabla EMPRESASEVASORAS_DET
				$datadetalle = array(
									'COD_EMP_EVASORA' => $nit,
									'OBSERVACIONES' => 'EMPRESA IDENTIFICADA COMO ELUSORA'
				);

				$this->codegen_model->add('EMPRESASEVASORAS',$dataevasoras,$dateevasoras);
				$this->codegen_model->add('EMPRESASEVASORAS_DET',$datadetalle);
				break;
			
			//default:
			//	# code...
			//	break;
		}
	}

	function pruebas(){

		$periodo_inactivo = $this->codegen_model->getSelect('PERIODOSINACTIVOS','PERIODO','WHERE COD_EMPRESA = '.$nit);//contiene los periodos inactivos relacionados al nit
			            
			            // |:::::identificar a que periodo corresponde
			            foreach ($periodo_inactivo as $key => $periodo) {
			            	
			            	//:::::validacion si el periodo bloqueado corresponde a Enero
			            	if ($periodo == '01') {
			            		
			            		foreach ($periodos_pila as $mes => $valor) {
			            			
			            			if ($mes['febrero'] == null && $mes['marzo'] == null && $mes['abril'] == null && $mes['mayo'] == null && $mes['junio'] == null && $mes['julio'] == null && $mes['agosto'] == null && $mes['septiembre'] == null && $mes['octubre'] == null && $mes['noviembre'] == null && $mes['diciembre'] == null) {
			            				
			            				// |:::::1. Empresa Evasora
			            				$tipo_deudor = 1;

			            			}else{

			            				if ($mes['febrero'] == null || $mes['marzo'] == null || $mes['abril'] == null || $mes['mayo'] == null || $mes['junio'] == null || $mes['julio'] == null || $mes['agosto'] == null || $mes['septiembre'] == null || $mes['octubre'] == null || $mes['noviembre'] == null || $mes['diciembre'] == null) {
			            					
			            					// |:::::2. Empresa Morosa
			            					$tipo_deudor = 2;
			            				
			            				}else{

			            					if (condition) {
			            						
			            						// |:::::3. Empresa Elusora
			            						$tipo_deudor = 3;
			            					}
			            				}
			            			}
			            		}
			            		// |:::::validaciones luego de identificar los periodos que aparecen en pila
			            	
			            	// |::::: fin validacion del periodo de Enero	
			            	}else{

			            		//:::::validacion si el periodo bloqueado corresponde a Febrero
			            		if ($periodo == '02') {
			            		
				            		foreach ($periodos_pila as $mes => $valor) {
				            			
				            			if ($mes['enero'] == null && $mes['marzo'] == null && $mes['abril'] == null && $mes['mayo'] == null && $mes['junio'] == null && $mes['julio'] == null && $mes['agosto'] == null && $mes['septiembre'] == null && $mes['octubre'] == null && $mes['noviembre'] == null && $mes['diciembre'] == null) {
				            				
				            				// |:::::1. Empresa Evasora
				            				$tipo_deudor = 1;

				            			}else{

				            				if ($mes['enero'] == null || $mes['marzo'] == null || $mes['abril'] == null || $mes['mayo'] == null || $mes['junio'] == null || $mes['julio'] == null || $mes['agosto'] == null || $mes['septiembre'] == null || $mes['octubre'] == null || $mes['noviembre'] == null || $mes['diciembre'] == null) {
				            					
				            					// |:::::2. Empresa Morosa
				            					$tipo_deudor = 2;
				            				
				            				}else{

				            					if (condition) {
				            						
				            						// |:::::3. Empresa Elusora
				            						$tipo_deudor = 3;
				            					}
				            				}
				            			}
				            		}
				            		// |:::::validaciones luego de identificar los periodos que aparecen en pila
				            	
				            	// |::::: fin validacion del periodo de Febrero	
				            	}else{

				            		//:::::validacion si el periodo bloqueado corresponde a Marzo
				            		if ($periodo == '03') {
				            		
					            		foreach ($periodos_pila as $mes => $valor) {
					            			
					            			if ($mes['enero'] == null && $mes['febrero'] == null && $mes['abril'] == null && $mes['mayo'] == null && $mes['junio'] == null && $mes['julio'] == null && $mes['agosto'] == null && $mes['septiembre'] == null && $mes['octubre'] == null && $mes['noviembre'] == null && $mes['diciembre'] == null) {
					            				
					            				// |:::::1. Empresa Evasora
					            				$tipo_deudor = 1;

					            			}else{

					            				if ($mes['enero'] == null || $mes['febrero'] == null || $mes['abril'] == null || $mes['mayo'] == null || $mes['junio'] == null || $mes['julio'] == null || $mes['agosto'] == null || $mes['septiembre'] == null || $mes['octubre'] == null || $mes['noviembre'] == null || $mes['diciembre'] == null) {
					            					
					            					// |:::::2. Empresa Morosa
					            					$tipo_deudor = 2;
					            				
					            				}else{

					            					if (condition) {
					            						
					            						// |:::::3. Empresa Elusora
					            						$tipo_deudor = 3;
					            					}
					            				}
					            			}
					            		}
					            		// |:::::validaciones luego de identificar los periodos que aparecen en pila
					            	
					            	// |::::: fin validacion del periodo de Marzo	
					            	}else{

					            		//:::::validacion si el periodo bloqueado corresponde a Abril
					            		if ($periodo == '04') {
					            		
						            		foreach ($periodos_pila as $mes => $valor) {
						            			
						            			if ($mes['enero'] == null && $mes['febrero'] == null && $mes['marzo'] == null && $mes['mayo'] == null && $mes['junio'] == null && $mes['julio'] == null && $mes['agosto'] == null && $mes['septiembre'] == null && $mes['octubre'] == null && $mes['noviembre'] == null && $mes['diciembre'] == null) {
						            				
						            				// |:::::1. Empresa Evasora
						            				$tipo_deudor = 1;

						            			}else{

						            				if ($mes['enero'] == null || $mes['febrero'] == null || $mes['marzo'] == null || $mes['mayo'] == null || $mes['junio'] == null || $mes['julio'] == null || $mes['agosto'] == null || $mes['septiembre'] == null || $mes['octubre'] == null || $mes['noviembre'] == null || $mes['diciembre'] == null) {
						            					
						            					// |:::::2. Empresa Morosa
						            					$tipo_deudor = 2;
						            				
						            				}else{

						            					if (condition) {
						            						
						            						// |:::::3. Empresa Elusora
						            						$tipo_deudor = 3;
						            					}
						            				}
						            			}
						            		}
						            		// |:::::validaciones luego de identificar los periodos que aparecen en pila
						            	
						            	// |::::: fin validacion del periodo de Marzo	
						            	}
					            	}

			            	}
			            }// |:::::Fin foreach $periodo_inactivo
	}


	
 
		 function datatable (){
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargarpagosplantillaunica/manage'))
							 {
							 
							 if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargarpagosplantillaunica/edit'))
							 {
								$this->load->library('datatables');
								$this->datatables->select('ECOLLECT.COD_TRANSACCION,ECOLLECT.COD_LOG_ECOLLECT,ECOLLECT.CONSECUTIVO_DET,ECOLLECT.NRO_IDENTIFICACION');
								$this->datatables->from('ECOLLECT'); 
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																			<div class="btn-group">
																				<a href="'.base_url().'index.php/cargarpagosplantillaunica/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
																			</div>
																		</div>', 'ECOLLECT.COD_TRANSACCION');
							}else{
								$this->load->library('datatables');
								$this->datatables->select('ECOLLECT.COD_TRANSACCION, ECOLLECT.COD_LOG_ECOLLECT,ECOLLECT.CONSECUTIVO_DET,ECOLLECT.NRO_IDENTIFICACION');
								$this->datatables->from('ECOLLECT'); 
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																			<div class="btn-group">
																				<a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
																			</div>
																		</div>', 'ECOLLECT.COD_TRANSACCION');
							}
								echo $this->datatables->generate();
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/cargarpagosplantillaunica');
									 }
						}else
						{
							redirect(base_url().'index.php/auth/login');
						}           
		}
}
}
