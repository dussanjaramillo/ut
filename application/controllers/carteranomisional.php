<?php

class Carteranomisional extends MY_Controller {
		
		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');		
		$this->load->helper(array('form','url','codegen_helper','template_helper','traza_fecha_helper'));
		$this->load->model('carteranomisional_model','',TRUE);
				 $this->load->file(APPPATH . "controllers/cnm_liquidacioncuotas.php", true);
		 
		 
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
	        
								         
								$this->template->set('title', 'Cartera No Misional');
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen',
														'css/validationEngine.jquery.css' => 'screen'
												);
								
								 $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Administración de Cartera No Misional</div>');
								$this->data['message']=$this->session->flashdata('message');
								$this->template->load($this->template_file, 'carteranomisional/carteranomisional_cargue',$this->data); 
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
															
																												
															

														$this->data['message']=$this->session->flashdata('message');				
										$this->template->set('title', 'Registro Cartera No Misional');


										$this->data['tipos']  = $this->carteranomisional_model->getSelectTipoCarteraCrear('TIPOCARTERA','COD_TIPOCARTERA, NOMBRE_CARTERA');
										$this->template->load($this->template_file, 'carteranomisional/carteranomisional_add', $this->data);
								
							 
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


		function carga_archivo_cart(){

		if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/add'))
							 {

										$flag=$this->input->post('vista_flag');
										if(!empty($flag)){
											
											$this->data['message']="";
											$adjuntos= $this->input->post('documentos');
											$id_cartera= $this->input->post('id_cartera_form');
											
											switch ($this->input->post('tipo_cartera')) {
												case '1':
	
											
											
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CALAMIDAD_DOMESTICA',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Calamidad Doméstica guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/add');
									
									}
												
													
													break;
													
										case '2':

																				
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_CONVENIOS',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Convenios guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/add');
									
									}
												
													
													break;
													
													
								case '3':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_EDUCATIVO_SANCION',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Educativo Sanción guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/add');
									
									}
												
													
													break;
													
													
													case '4':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_PREST_EDUCATIVO',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Prestamo Educativo guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/add');
									
									}
												
													
													break;
													
													
													case '5':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_EXCEDENTE_SERVICIO_MEDICO',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Excedente Servicio Médico guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/add');
									
									}
												
													
													break;
													
													
													case '6':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_RESPON_BIENES',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Excedente Servicio Médico guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/add');
									
									}
												
													
													break;
													
														
								case '7':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_RESPON_FONDOS',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Responsabilidad Fondos guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/add');
									
									}
												
													
													break;
													
									case '8':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_PRESTAMO_HIPOTEC',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Credito Hipotecario guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/add');
									
									}
												
													
													break;				
																	
									case '10':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_DOBLE_MESADA',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Doble Mesada Pensional guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/add');
									
									}
												
													
													break;				
												
													
													
									case '11':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_PRESTAMO_AHORRO',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Prestamo Ahorro guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/add');
									
									}
												
													
													break;				
																	
												
												default:
													$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_OTRAS_CARTERAS',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/add');
									
									}
												
													
													break;
													
											
											}

										}

				$datos=($this->session->userdata('lolwut'));
				$this->data['message']=$this->session->flashdata('message');

				$this->data['id_cartera'] = $datos["id_cartera"];	
				$this->data['tipo_cartera'] = $datos["tipo_cartera"];	
				if(isset($datos["agregar"])){
					$this->data['agregar'] = $datos["agregar"];	
				}
				else{
					$this->data['agregar'] = '0';	
				}
				
										$this->template->set('title', 'Archivos Cartera No Misional');


										$this->template->load($this->template_file, 'carteranomisional/carteranomisional_carga_archivo_cart', $this->data);
								
							 
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

		function carga_archivo_coact(){

						if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/add'))
							 {

										$flag=$this->input->post('vista_flag');
										if(!empty($flag)){
											
											$this->data['message']="";

											$adjuntos= $this->input->post('documentos');
											$id_cartera= $this->input->post('id_cartera_form');
											$cod_recepcion= $this->input->post('cod_recepcion');
											switch ($this->input->post('tipo_cartera')) {
												case '1':
	
											
											
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CALAMIDAD_DOMESTICA',$data) == TRUE)
									{
									$this->carteranomisional_model->reenvioTitulos($id_cartera,$cod_recepcion,$adjuntos);
									
									}
												
													
													break;
													
										case '2':

																				
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_CONVENIOS',$data) == TRUE)
									{
									$this->carteranomisional_model->reenvioTitulos($id_cartera,$cod_recepcion,$adjuntos);
									
									}
												
													
													break;
													
													
								case '3':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_EDUCATIVO_SANCION',$data) == TRUE)
									{
									$this->carteranomisional_model->reenvioTitulos($id_cartera,$cod_recepcion,$adjuntos);
									
									}
												
													
													break;
													
													
													case '4':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_PREST_EDUCATIVO',$data) == TRUE)
									{
									$this->carteranomisional_model->reenvioTitulos($id_cartera,$cod_recepcion,$adjuntos);
									
									}
												
													
													break;
													
													
													case '5':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_EXCEDENTE_SERVICIO_MEDICO',$data) == TRUE)
									{
									$this->carteranomisional_model->reenvioTitulos($id_cartera,$cod_recepcion,$adjuntos);
									
									}
												
													
													break;
													
													
													case '6':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_RESPON_BIENES',$data) == TRUE)
									{
															$this->carteranomisional_model->reenvioTitulos($id_cartera,$cod_recepcion,$adjuntos);
									
									}
												
													
													break;
													
														
								case '7':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_RESPON_FONDOS',$data) == TRUE)
									{
															$this->carteranomisional_model->reenvioTitulos($id_cartera,$cod_recepcion,$adjuntos);
									
									}
												
													
													break;
													
									case '8':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_PRESTAMO_HIPOTEC',$data) == TRUE)
									{
									$this->carteranomisional_model->reenvioTitulos($id_cartera,$cod_recepcion,$adjuntos);
									
									}
												
													
													break;				
									
									case '9':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_CUOTA_PARTE',$data) == TRUE)
									{
									$this->carteranomisional_model->reenvioTitulos($id_cartera,$cod_recepcion,$adjuntos);
									
									}
												
													
													break;		
																	
									case '10':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_DOBLE_MESADA',$data) == TRUE)
									{
									$this->carteranomisional_model->reenvioTitulos($id_cartera,$cod_recepcion,$adjuntos);
									
									}
												
													
													break;				
												
													
													
									case '11':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_PRESTAMO_AHORRO',$data) == TRUE)
									{
									$this->carteranomisional_model->reenvioTitulos($id_cartera,$cod_recepcion,$adjuntos);
									
									}
												
													
													break;				
																	
												
												default:
													$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_OTRAS_CARTERAS',$data) == TRUE)
									{
										$this->carteranomisional_model->reenvioTitulos($id_cartera,$cod_recepcion,$adjuntos);
									}
												
													
													break;
													
											
											}



										}


				$this->data['id_cartera'] = $this->input->post('atcoactivo');	
				$this->data['tipo_cartera'] = $this->input->post('attipo');
				switch ($this->data['tipo_cartera']) {
					case 1:
					$this->data['tabla_cartera'] = 'CNM_CALAMIDAD_DOMESTICA';		
						break;
					case 2:
					$this->data['tabla_cartera'] = 'CNM_CARTERA_CONVENIOS';	
						break;
					case 3:
					$this->data['tabla_cartera'] = 'CNM_CARTERA_EDUCATIVO_SANCION';	
						break;
					case 4:
					$this->data['tabla_cartera'] = 'CNM_CARTERA_PREST_EDUCATIVO';	
						break;
					case 5:
					$this->data['tabla_cartera'] = 'CNM_EXCEDENTE_SERVICIO_MEDICO';	
						break;
					case 6:
					$this->data['tabla_cartera'] = 'CNM_CARTERA_RESPON_BIENES';	
						break;
					case 7:
					$this->data['tabla_cartera'] = 'CNM_CARTERA_RESPON_FONDOS';	
						break;
					case 8:
					$this->data['tabla_cartera'] = 'CNM_CARTERA_PRESTAMO_HIPOTEC';	
						break;
					case 9:
					$this->data['tabla_cartera'] = 'CNM_CARTERA_CUOTA_PARTE';	
						break;
					case 10:
					$this->data['tabla_cartera'] = 'CNM_CARTERA_DOBLE_MESADA';	
						break;
					case 11:
					$this->data['tabla_cartera'] = 'CNM_PRESTAMO_AHORRO';	
						break;
						
					default:
					$this->data['tabla_cartera'] = 'CNM_CARTERA_OTRAS_CARTERAS';	
						break;
				}
				$this->data['cod_recepcion']= $this->input->post('cod_recepcion');
				$consultaadj=$this->carteranomisional_model->consultaadjuntos($this->data['tabla_cartera'],$this->data['id_cartera'])->result_array[0]["ADJUNTOS"];
				//var_dump($consultaadj);
				//die();
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
				
						//var_dump($this->data['lista_adjuntos'])	;
						//die();						
										$this->template->set('title', 'Archivos Cartera No Misional');


										$this->load->view('carteranomisional/carteranomisional_carga_archivo_coact', $this->data);
								
							 
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

    function observaciones() {
    	$this->data['idcartera'] =$this->input->post('atcoactivo');
		$this->data['tipocartera'] =$this->input->post('attipo');
        $this->data['cod_recepcion'] = $this->carteranomisional_model->cod_recepcion($this->data['idcartera']);
		$this->data['titulos'] = $this->carteranomisional_model->titulos($this->data['cod_recepcion'][0]['COD_RECEPCIONTITULO']);
		$this->data['observaciones'] = $this->carteranomisional_model->observaciones($this->data['cod_recepcion'][0]['COD_RECEPCIONTITULO']);
        $this->load->view('carteranomisional/carteranomisional_observaciones', $this->data);
    }

	function enviar_correcciones() {
        $this->data['post'] = $this->input->post();
        $this->carteranomisional_model->enviar_correcciones($this->data['post']);
		$this->carteranomisional_model->updateEstadoCoac('CNM_CARTERANOMISIONAL', $this->data['post']['idcartera']);
    }
		function carga_archivo_edit(){


    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/add'))
							 {

										$flag=$this->input->post('vista_flag');
										if(!empty($flag)){
											
											$this->data['message']="";

											$adjuntos= $this->input->post('documentos');
											$id_cartera= $this->input->post('id_cartera_form');
										
											switch ($this->input->post('tipo_cartera')) {
												case '1':
	
											
											
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CALAMIDAD_DOMESTICA',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Calamidad Doméstica guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/consulta');
									
									}
												
													
													break;
													
										case '2':

																				
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_CONVENIOS',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Convenios guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/consulta');
									
									}
												
													
													break;
													
													
								case '3':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_EDUCATIVO_SANCION',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Educativo Sanción guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/consulta');
									
									}
												
													
													break;
													
													
													case '4':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_PREST_EDUCATIVO',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Prestamo Educativo guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/consulta');
									
									}
												
													
													break;
													
													
													case '5':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_EXCEDENTE_SERVICIO_MEDICO',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Excedente Servicio Médico guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/consulta');
									
									}
												
													
													break;
													
													
													case '6':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_RESPON_BIENES',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Excedente Servicio Médico guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/consulta');
									
									}
												
													
													break;
													
														
								case '7':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_RESPON_FONDOS',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Responsabilidad Fondos guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/consulta');
									
									}
												
													
													break;
													
									case '8':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_PRESTAMO_HIPOTEC',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Credito Hipotecario guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/consulta');
									
									}
												
													
													break;				
																	
									case '10':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_DOBLE_MESADA',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Doble Mesada Pensional guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/consulta');
									
									}
												
													
													break;				
												
													
													
									case '11':

										
											
												$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_PRESTAMO_AHORRO',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Prestamo Ahorro guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/consulta');
									
									}
												
													
													break;				
																	
												
												default:
													$data = array(
																'CODIGO_CARTERA' => 'COD_CARTERA',
																'COD_CARTERA' => $id_cartera,
																'ADJUNTOS' => $adjuntos,
																													
												);

									if ($this->carteranomisional_model->updateadjuntos('CNM_CARTERA_OTRAS_CARTERAS',$data) == TRUE)
									{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera guardada exitosamente.</div>');
				        				redirect(base_url().'index.php/carteranomisional/consulta');
									
									}
												
													
													break;
													
											
											}

										}

				$datos=($this->session->userdata('lolwut'));
				$this->data['message']=$this->session->flashdata('message');
				$this->data['id_cartera'] = $datos["id_deuda"];	
				$this->data['tipo_cartera'] = $datos["tipo_cartera"];
				$this->data['tabla_cartera'] = $datos["tabla_cartera"];	
				$consultaadj=$this->carteranomisional_model->consultaadjuntos($this->data['tabla_cartera'],$this->data['id_cartera'])->result_array[0]["ADJUNTOS"];
				//var_dump($consultaadj);
				//die();
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
				
						//var_dump($this->data['lista_adjuntos'])	;
						//die();						
										$this->template->set('title', 'Archivos Cartera No Misional');


										$this->template->load($this->template_file, 'carteranomisional/carteranomisional_carga_archivo_edit', $this->data);
								
							 
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
		
		public function doUploadFile($cod_calamidad) {
		//$cod_calamidad=	$this->input->post('id_calamidad');

$nombre_carpeta = './uploads/carteranomisional/'.$cod_calamidad;
$nombre_subcarpeta = './uploads/carteranomisional/'.$cod_calamidad.'/documentoscartera';

if(!is_dir($nombre_carpeta)){ 
@mkdir($nombre_carpeta, 0777); 
}else{ 

}  
if(!is_dir($nombre_subcarpeta)){ 
@mkdir($nombre_subcarpeta, 0777); 
}else{ 

}
        $status = '';
        $message = '';
        $background = '';
        $file_element_name = 'userFile';


 

   
            if ($status != 'error') {
                $config['upload_path'] = './uploads/carteranomisional/'.$cod_calamidad.'/documentoscartera';
                $config['allowed_types'] = 'png|jpg|gif|pdf';
                $config['max_size'] = '10000';
                $config['overwrite'] = TRUE;
				 $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload($file_element_name)) {
return false;
                } else {
     $data = $this->upload->data();

                }
                @unlink($_FILES[$file_element_name]);
            }
        
        $json_encode = json_encode(array('nombre' => $data["file_name"], 'status' => $status, 'background' => $background));
        echo $json_encode;
    }
		
		function consultacarteraexist(){


    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/add'))
							 {

															
										$this->template->set('title', 'Registro Cartera No Misional');
										$this->data['style_sheets']= array(
											
														'css/jquery.dataTables_themeroller.css' => 'screen'
												);

										$this->load->view('carteranomisional/carteranomisional_consultacarteraexist', $this->data);
								
							 
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


		 function novedades(){


    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/novedades'))
							 {
										
										$this->data['message']=$this->session->flashdata('message');
															
										$this->template->set('title', 'Generación Novedades Cartera No Misional');

										$this->template->load($this->template_file, 'carteranomisional/carteranomisional_novedades', $this->data);
								
                        
							 
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
		
		function novedades_confirmacion(){


    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/novedades'))
							 {
										
										$this->data['message']=$this->session->flashdata('message');
															
										$this->template->set('title', 'Generación Novedades Cartera No Misional');


										$this->load->view('carteranomisional/carteranomisional_novedades_confirmacion', $this->data);
								
                        
							 
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

function novedad_correcta(){
	if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/novedades'))
							 {
										
										$this->data['message']=$this->session->flashdata('message');
															
										$this->template->set('title', 'Generación Novedades Cartera No Misional');

										$this->load->view('carteranomisional/carteranomisional_novedad_correcta', $this->data);
								
                        
							 
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

		 function certificaciones(){

				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/certificaciones'))
							 {
										
										$this->data['message']=$this->session->flashdata('message');
															
										$this->template->set('title', 'Generación Certificaciones Cartera No Misional');
												
										$this->data['tipos']  = $this->carteranomisional_model->getSelectTipoCartera('TIPOCARTERA','COD_TIPOCARTERA, NOMBRE_CARTERA');
										$this->template->load($this->template_file, 'carteranomisional/carteranomisional_certificaciones', $this->data);
								
                        
							 
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

		 function get_tipo_certificacion($id_cartera){
if($id_cartera==8){
$tipo_certificacion[1]="CERTIFICADO DE ESTADO DE CUENTA A LA FECHA";
$tipo_certificacion[2]="CERTIFICADO DE ESTADO DE CUENTA ANUAL";
$tipo_certificacion[3]="CERTIFICADO PAZ Y SALVO SALDADA";
$tipo_certificacion[4]="CERTIFICADO PAZ Y SALVO PARA CANCELACION DE HIPOTECA";
print_r(json_encode($tipo_certificacion));
}
else {
$tipo_certificacion[1]="CERTIFICADO DE ESTADO DE CUENTA A LA FECHA";
$tipo_certificacion[2]="CERTIFICADO DE ESTADO DE CUENTA ANUAL";
print_r(json_encode($tipo_certificacion));	
}

		}
 
 
 		 function tipo_certificacion(){

				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/certificaciones'))
							 {
										
										$this->data['message']=$this->session->flashdata('message');
										$this->tipo_cartera = $this->input->post('cartera');
										$this->tipo_certificacion = $this->input->post('certificacion');
										
										switch($this->tipo_certificacion){
										case '1':		
										$urlplantilla="./uploads/plantillas/certificaciones/estado_cuenta_fecha.txt";					
										break;
										case '2':		
										$urlplantilla="./uploads/plantillas/certificaciones/estado_cuenta_anual.txt";					
										break;
										case '3':		
										$urlplantilla="./uploads/plantillas/certificaciones/paz_salvo_saldada.txt";					
										break;
										case '4':		
										$urlplantilla="./uploads/plantillas/certificaciones/paz_salvo_cancelacion_hipoteca.txt";					
										break;
										default:
										break;
										
										
										}	

										$array_tags                                 = array();

			
			        $this->data['docreemplazado']=template_tags($urlplantilla, $array_tags);			
												

										$this->template->set('title', 'Generación Certificaciones Cartera No Misional');

										$this->load->view('carteranomisional/carteranomisional_tipo_certificacion', $this->data);
								
                        
							 
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
 
 
		 function consulta (){


    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/list'))
							 {
										
										$this->data['message']=$this->session->flashdata('message');
															
										$this->template->set('title', 'Consulta Cartera No Misional');
										$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen',
												);

										$this->data['tipos']  = $this->carteranomisional_model->getSelectTipoCartera('TIPOCARTERA','COD_TIPOCARTERA, NOMBRE_CARTERA');
										$this->data['estados']  = $this->carteranomisional_model->getSelectEstados('ESTADOCARTERA','COD_EST_CARTERA, DESC_EST_CARTERA');
										$this->template->load($this->template_file, 'carteranomisional/carteranomisional_list', $this->data);
								
                        
							 
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


function datos_consulta_detalle(){


    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
							 {

															
										$this->template->set('title', 'Consulta Cartera No Misional');

										$this->load->view('carteranomisional/carteranomisional_datos_consulta_detalle', $this->data);
								
							 
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

function gestiones(){
	
$this->atgestion = $this->input->post('atgestion');
	

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
               {
               	$this->data['atarchivos']=$this->input->post('atarchivos');
				 $this->data['id_cnm']=$this->input->post('atgestion');
				$this->data['atdoc']=$this->input->post('atdoc');
				$this->data['attipoc']=$this->input->post('attipoc');
				
                //template data
                $this->template->set('title', 'Cartera No Misional');
                $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->data['message']=$this->session->flashdata('message');
				
				
                $this->load->view('carteranomisional/carteranomisional_gestiones',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}		

function acuerdo_pago(){
	
	

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
               {
                //template data
                $this->template->set('title', 'Cartera No Misional');
                $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->data['message']=$this->session->flashdata('message');
				
				
                $this->template->load($this->template_file,'carteranomisional/carteranomisional_acuerdo_pagos',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}		

function refinanciacion(){
	

if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/add'))
							 {
					$this->template->set('title', 'Refinanciación Cartera');
												
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
						$calc_corriente= '1';
						switch($calc_corriente)
						{
						case '1':
						$tipo_t_f_corriente= $this->input->post('reliq_aplica_tasa_c');
						$tipo_t_v_corriente= "";
						$valor_t_corriente= $this->input->post('reliq_porcent_tasa_c');
							break;
							
						case '2':
						$tipo_t_f_corriente= "";
						$tipo_t_v_corriente= $this->input->post('reliq_aplica_tasa_c');
						$valor_t_corriente= "";
							break;
							
						default:
						$tipo_t_f_corriente= "";
						$tipo_t_v_corriente= "";
						$valor_t_corriente= "";
						break;		
						}
						

													
													

						$mod_tasa_mora='S';		
						$calc_mora= $this->input->post('reliq_combo_tipo_tasa_mora');
						switch($calc_mora)
						{case '1':
						$tipo_t_f_mora= $this->input->post('reliq_aplica_tasa_m');
						$tipo_t_v_mora= "";
						$valor_t_mora= $this->input->post('reliq_porcent_tasa_m');
							break;
							
						case '2':
						$tipo_t_f_mora= "";
						$tipo_t_v_mora= $this->input->post('reliq_aplica_tasa_m');
						$valor_t_mora= "";
							break;
							
						default:
						$tipo_t_f_mora= "";
						$tipo_t_v_mora= "";
						$valor_t_mora= "";

						break;		
						}
						
						
								
$id_deuda = $this->input->post('id_deuda');
												$datecnm = array(
												'FECHA_INI_GEST_ACT' => $this->input->post('fecha_refin'),
																);		
												$datacnm = array(
																'CALCULO_CORRIENTE' => $calc_corriente,
																'TIPO_T_F_CORRIENTE' => $tipo_t_f_corriente,
																'TIPO_T_V_CORRIENTE' => $tipo_t_v_corriente,
																'VALOR_T_CORRIENTE' => $valor_t_corriente,		
																'CALCULO_MORA' => $calc_mora,
																'TIPO_T_F_MORA' => $tipo_t_f_mora,
																'TIPO_T_V_MORA' => $tipo_t_v_mora,
																'VALOR_T_MORA' => $valor_t_mora,
																'PLAZO_CUOTAS' => $this->input->post('refin_plazo'),
																'COD_PLAZO' => $this->input->post('refin_plazo_id'),
																'TIPO_MODIFICACION' => 'RF',	
																'VALOR_CUOTA_APROBADA' => str_replace(".", "", $this->input->post('nueva_cuota')),			
																													
												);
						
								

												//if($this->input->post('cod_tipo_cartera')==8){
																						if($this->input->post('cod_tipo_cartera')==8){

											
												$data = array(
																'MOD_TASA_CORRIENTE' => $mod_tasa_corr,
																'MOD_TASA_MORA' => $mod_tasa_mora,
																'VALOR_CUOTA_APROBADA' => str_replace(".", "", $this->input->post('nueva_cuota')),
																'FECHA_RETIRO' =>  $this->input->post('nueva_f_r'),
																);
													$tabla_cart='CNM_CARTERA_PRESTAMO_HIPOTEC';
													$this->carteranomisional_model->updateCartera($tabla_cart,$data,'',$id_deuda,"COD_CARTERA"); 	
									
												if ($this->carteranomisional_model->updateCartera('CNM_CARTERANOMISIONAL',$datacnm,$datecnm,$id_deuda,"COD_CARTERA_NOMISIONAL") == TRUE)			
									{




									
						$fechaact=$this->carteranomisional_model->detalleFechaAct($tabla_cart, $id_deuda);
$ultimo_periodo=$this->input->post('ultimo_pago');
												if(!empty($ultimo_periodo))
					{$ultimo_periodo=1;
					}
												else{$ultimo_periodo=2;}
						$this->cnm_liquidacioncuotas = new Cnm_liquidacioncuotas();
                if($this->cnm_liquidacioncuotas->CnmCalcularCuotas($id_deuda,  $fechaact["FECHA_ACTIVACION"], $fechaact["FORMA_PAGO"],true,false,$ultimo_periodo)==true)		
				{
											trazarProcesoJuridico('551', '1365', '', '', $id_deuda, '', '', "Sin Comentarios Juridico", '','');
				}
				else {
				}
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Cartera ha sido reliquidada con éxito.</div>');
			                         	$this->session->set_userdata('lolwut',$datos);			
													redirect(base_url().'index.php/carteranomisional/consulta/');	
									

									}
									else
									{
									
					$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

									}
																						}
													
												else{

													$tabla_cart='CNM_PRESTAMO_AHORRO';
																		if ($this->carteranomisional_model->updateCartera('CNM_CARTERANOMISIONAL',$datacnm,$datecnm,$id_deuda,"COD_CARTERA_NOMISIONAL") == TRUE)			
									{




									
						$fechaact=$this->carteranomisional_model->detalleFechaAct($tabla_cart, $id_deuda);
$ultimo_periodo=$this->input->post('ultimo_pago');
												if(!empty($ultimo_periodo))
					{$ultimo_periodo=1;
					}
												else{$ultimo_periodo=2;}
						$this->cnm_liquidacioncuotas = new Cnm_liquidacioncuotas();
                if($this->cnm_liquidacioncuotas->CnmCalcularCuotas($id_deuda,  $fechaact["FECHA_ACTIVACION"], $fechaact["FORMA_PAGO"],false,false,$ultimo_periodo)==true)		
				{
											trazarProcesoJuridico('551', '1365', '', '', $id_deuda, '', '', "Sin Comentarios Juridico", '','');
				}
				else {
				}
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Cartera ha sido reliquidada con éxito.</div>');
			                         	$this->session->set_userdata('lolwut',$datos);			
													redirect(base_url().'index.php/carteranomisional/consulta/');	
									

									}
									else
									{
									
					$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

									}
																										
												}
											
												

									
									
								}


										}


$this->data['estado']  = $this->carteranomisional_model->selectTipoEstado();
$this->data['forma_pago']  = $this->carteranomisional_model->selectFormaPago();
$this->data['duracion']  = $this->carteranomisional_model->selectPlazo();
$this->data['id_cnm']=$this->input->post('id_cnm_refi');
				$this->data['id_tipo']=$this->input->post('tipo_cartera_rf');
				
$this->data['detalleCart']  = $this->carteranomisional_model->detalleCarterasEdit($this->data['id_cnm'],$this->data['id_tipo'])->result_array[0];
//var_dump($this->data['detalleCart']);
//die();
$int_no_pago_cuotas_canceladas  = $this->carteranomisional_model->selectIntNoPago($this->data['id_cnm']);
if(!empty($int_no_pago_cuotas_canceladas))
{
$this->data['int_no_pago']=	$int_no_pago_cuotas_canceladas[0]["SALDO_INTERES_NO_PAGOS"];
}
else{
$this->data['int_no_pago']=0;	
}
//echo($int_no_pago_cuotas_canceladas[0]["SALDO_INTERES_NO_PAGOS"]);
//die();
$this->data['cuotas_restantes']  = $this->carteranomisional_model->selectCuotasRestantes($this->data['id_cnm']);
$this->data['tipoCartera']  = $this->carteranomisional_model->tipoCartera($this->data['id_tipo']);
$this->data['motivo']  = $this->carteranomisional_model->selectTipoMotivo('2');
$this->data['estado']  = $this->carteranomisional_model->selectTipoEstado();
$this->data['forma_pago']  = $this->carteranomisional_model->selectFormaPago();
$this->data['duracion']  = $this->carteranomisional_model->selectPlazo();
$this->data['acuerdos']  = $this->carteranomisional_model->selectAcuerdo($this->data['id_tipo']);

if(!empty($this->data['acuerdos']->result_array[0]["COD_ACUERDOLEY"])){
$cod_ac_modalidad= $this->data['acuerdos']->result_array[0]["COD_ACUERDOLEY"];
}
else{
$cod_ac_modalidad= "";	
}

$this->data['acuerdo_prestamo']  = $cod_ac_modalidad;
$this->data['modalidad']  = $this->carteranomisional_model->selectModalidad($cod_ac_modalidad);
$this->data['tipotasa']  = $this->carteranomisional_model->selectTipoTasaCombo();
$this->data['tipotasaesp']  = $this->carteranomisional_model->selectTasaEspCombo();
$this->data['tipotasaespC']  = $this->carteranomisional_model->selectTasaEspComboEdit($this->data['detalleCart']["CALCULO_CORRIENTE"]);
$this->data['tipotasaespV']  = $this->carteranomisional_model->selectTasaEspComboEdit($this->data['detalleCart']["CALCULO_MORA"]);
				
                 $this->template->load($this->template_file,'carteranomisional/carteranomisional_refinanciacion',$this->data); 
               

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
	function reliquidacion(){    
			
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/add'))
							 {
					$this->template->set('title', 'Reliquidación Cartera');
												
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
						$calc_corriente= '1';
						switch($calc_corriente)
						{
						case '1':
						$tipo_t_f_corriente= $this->input->post('reliq_aplica_tasa_c');
						$tipo_t_v_corriente= "";
						$valor_t_corriente= $this->input->post('reliq_porcent_tasa_c');
							break;
							
						case '2':
						$tipo_t_f_corriente= "";
						$tipo_t_v_corriente= $this->input->post('reliq_aplica_tasa_c');
						$valor_t_corriente= "";
							break;
							
						default:
						$tipo_t_f_corriente= "";
						$tipo_t_v_corriente= "";
						$valor_t_corriente= "";
						break;		
						}
						

													
													

						$mod_tasa_mora='S';		
						$calc_mora= $this->input->post('reliq_combo_tipo_tasa_mora');
						switch($calc_mora)
						{case '1':
						$tipo_t_f_mora= $this->input->post('reliq_aplica_tasa_m');
						$tipo_t_v_mora= "";
						$valor_t_mora= $this->input->post('reliq_porcent_tasa_m');
							break;
							
						case '2':
						$tipo_t_f_mora= "";
						$tipo_t_v_mora= $this->input->post('reliq_aplica_tasa_m');
						$valor_t_mora= "";
							break;
							
						default:
						$tipo_t_f_mora= "";
						$tipo_t_v_mora= "";
						$valor_t_mora= "";

						break;		
						}
						
						
								
$id_deuda = $this->input->post('id_deuda');

												$datecnm = array(
												'FECHA_INI_GEST_ACT' => $this->input->post('fecha_reliq'),
																);	
												$datacnm = array(
																'CALCULO_CORRIENTE' => $calc_corriente,
																'TIPO_T_F_CORRIENTE' => $tipo_t_f_corriente,
																'TIPO_T_V_CORRIENTE' => $tipo_t_v_corriente,
																'VALOR_T_CORRIENTE' => $valor_t_corriente,		
																'CALCULO_MORA' => $calc_mora,
																'TIPO_T_F_MORA' => $tipo_t_f_mora,
																'TIPO_T_V_MORA' => $tipo_t_v_mora,
																'VALOR_T_MORA' => $valor_t_mora,
																'TIPO_MODIFICACION' => 'RL',
																'VALOR_CUOTA_APROBADA' => str_replace(".", "", $this->input->post('nueva_cuota')),																		
												);
												
									if ($this->carteranomisional_model->updateCartera('CNM_CARTERANOMISIONAL',$datacnm,$datecnm,$id_deuda,"COD_CARTERA_NOMISIONAL") == TRUE)			
									{
										if($this->input->post('cod_tipo_cartera')==8){

												$data = array(
																'MOD_TASA_CORRIENTE' => $mod_tasa_corr,
																'MOD_TASA_MORA' => $mod_tasa_mora,
																'VALOR_CUOTA_APROBADA' => str_replace(".", "", $this->input->post('nueva_cuota')),
																'FECHA_RETIRO' =>  $this->input->post('nueva_f_r'),
																);
													$tabla_cart='CNM_CARTERA_PRESTAMO_HIPOTEC';
								if ($this->carteranomisional_model->updateCartera($tabla_cart,$data,'',$id_deuda,"COD_CARTERA") == TRUE)		
									{
									
						$fechaact=$this->carteranomisional_model->detalleFechaAct($tabla_cart, $id_deuda);

						$this->cnm_liquidacioncuotas = new Cnm_liquidacioncuotas();
                if($this->cnm_liquidacioncuotas->CnmCalcularCuotas($id_deuda,  $fechaact["FECHA_ACTIVACION"], $fechaact["FORMA_PAGO"],true,true)==true)		
				{ 
						trazarProcesoJuridico('550', '1364', '', '', $id_deuda, '', '', "Sin Comentarios Juridico", '','');
				}
				else {
				}
						$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Cartera ha sido reliquidada con éxito.</div>');
                     	$this->session->set_userdata('lolwut',$datos);			
						redirect(base_url().'index.php/carteranomisional/consulta/');	
		
									}
else {
	$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
	
}										

}
													
												else{
													
													$data = array(
																'MOD_TASA_CORRIENTE' => $mod_tasa_corr,
																'MOD_TASA_MORA' => $mod_tasa_mora,
																																																									
																								);
													$tabla_cart='CNM_PRESTAMO_AHORRO';
																		
			if ($this->carteranomisional_model->updateCartera($tabla_cart,$data,'',$id_deuda,"COD_CARTERA") == TRUE)		
									{
									
						$fechaact=$this->carteranomisional_model->detalleFechaAct($tabla_cart, $id_deuda);

						$this->cnm_liquidacioncuotas = new Cnm_liquidacioncuotas();
                if($this->cnm_liquidacioncuotas->CnmCalcularCuotas($id_deuda,  $fechaact["FECHA_ACTIVACION"], $fechaact["FORMA_PAGO"],false,true)==true)		
				{ 
						trazarProcesoJuridico('550', '1364', '', '', $id_deuda, '', '', "Sin Comentarios Juridico", '','');
				}
				else {
				}
						$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Cartera ha sido reliquidada con éxito.</div>');
                     	$this->session->set_userdata('lolwut',$datos);			
						redirect(base_url().'index.php/carteranomisional/consulta/');	
		
									}
else {
	$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
	
}						
																										
												}


									}
									else
									{
									
					$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

									}
								}


										}

$this->data['id_cnm']=$this->input->post('id_cnm_reli');
$this->data['id_tipo']=$this->input->post('tipo_cartera_rl');
				
$this->data['detalleCart']  = $this->carteranomisional_model->detalleCarterasEdit($this->data['id_cnm'],$this->data['id_tipo'])->result_array[0];
//var_dump($this->data['detalleCart']);
//die();
$int_no_pago_cuotas_canceladas  = $this->carteranomisional_model->selectIntNoPago($this->data['id_cnm']);
if(!empty($int_no_pago_cuotas_canceladas))
{
$this->data['int_no_pago']=	$int_no_pago_cuotas_canceladas[0]["SALDO_INTERES_NO_PAGOS"];
}
else{
$this->data['int_no_pago']=0;	
}
$this->data['cuotas_restantes']  = $this->carteranomisional_model->selectCuotasRestantes($this->data['id_cnm']);
$this->data['tipoCartera']  = $this->carteranomisional_model->tipoCartera($this->data['id_tipo']);
$this->data['motivo']  = $this->carteranomisional_model->selectTipoMotivo('1');
$this->data['estado']  = $this->carteranomisional_model->selectTipoEstado();
$this->data['forma_pago']  = $this->carteranomisional_model->selectFormaPago();
$this->data['duracion']  = $this->carteranomisional_model->selectPlazo();
$this->data['acuerdos']  = $this->carteranomisional_model->selectAcuerdo($this->data['id_tipo']);

if(!empty($this->data['acuerdos']->result_array[0]["COD_ACUERDOLEY"])){
$cod_ac_modalidad= $this->data['acuerdos']->result_array[0]["COD_ACUERDOLEY"];
}
else{
$cod_ac_modalidad= "";	
}

$this->data['acuerdo_prestamo']  = $cod_ac_modalidad;
$this->data['modalidad']  = $this->carteranomisional_model->selectModalidad($cod_ac_modalidad);
$this->data['tipotasa']  = $this->carteranomisional_model->selectTipoTasaCombo();
$this->data['tipotasaesp']  = $this->carteranomisional_model->selectTasaEspCombo();
$this->data['tipotasaespC']  = $this->carteranomisional_model->selectTasaEspComboEdit($this->data['detalleCart']["CALCULO_CORRIENTE"]);
$this->data['tipotasaespV']  = $this->carteranomisional_model->selectTasaEspComboEdit($this->data['detalleCart']["CALCULO_MORA"]);
				
                $this->template->load($this->template_file,'carteranomisional/carteranomisional_reliquidacion',$this->data); 
               

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


function recurso(){
	

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
               {
                //template data
                $this->template->set('title', 'Cartera No Misional');
                $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->data['message']=$this->session->flashdata('message');
				
				
                $this->template->load($this->template_file,'carteranomisional/carteranomisional_recurso',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}

function gen_notificacion(){
	

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
               {
                //template data
                $this->template->set('title', 'Cartera No Misional');
                $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->data['message']=$this->session->flashdata('message');
				$urlplantilla="./uploads/plantillas/no_misional.txt";
				$array_tags                                 = array();
			
			        $this->data['docreemplazado']=template_tags($urlplantilla, $array_tags);
				
                $this->template->load($this->template_file,'carteranomisional/carteranomisional_notificacion',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}


function enviar_correo_empleado(){

date_default_timezone_set("America/Bogota");



	
		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consultar'))
               {
			
                    	
               		$this->data['custom_error'] = '';
                    $showForm=0;
					
		   
               //template data
                $this->template->set('title', 'Consulta Cartera No Misional');
                $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->data['message']=$this->session->flashdata('message');
				

				
                $this->load->view('carteranomisional/carteranomisional_enviar_correo_empleado',$this->data); 
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
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consultar'))
               {
               		$this->load->library('form_validation');    
	       			
	       			$this->form_validation->set_rules('para', 'para', 'required');
        			
                    if ($this->form_validation->run() == false)
                    {
                         $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                    } else
                    {
					$archivo=$this->input->post('adjunto_id');
					

					if(!empty($archivo))
					
					{
						$file = $this->do_upload_mail();
						$adjunto=$file['upload_data']['file_name'];	
								
								if(empty($adjunto)){				   
						  $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>El tipo de archivo es invalido, extensiones permitidas(gif|jpg|png|pdf) intentelo nuevamente</div>');
                        
		 				redirect(base_url().'index.php/carteranomisional/consulta');
					
					}
					
					}
					else{
						$adjunto="";
					}
					
					
						$config = Array( 
					  	'protocol' => 'smtp', 
					 	'smtp_host' => 'ssl://smtp.googlemail.com', 
				  		'smtp_port' => '465', 
					 	'smtp_user' => 'carterasena@gmail.com', 
					 	'smtp_pass' => '7demarzo',
					 	'mailtype' =>'html',
			            'starttls' => true,
						'newline'  => "\r\n",
			            'wordwrap' => true    ); 
						
				 $this->email->initialize($config);		
						 $this->load->library('email'); 
						 

						 $this->email->from('carterasena@gmail.com', '');
						 $this->email->to($this->input->post('para'));
						 $this->email->cc($this->input->post('cc')); 
						 $this->email->bcc($this->input->post('cco')); 
						 $this->email->subject($this->input->post('asunto')); 
						 $this->email->message($this->input->post('descripcion'));
						if(!empty($archivo))
					
					{
						$this->email->attach('./uploads/cnm/docemail/'.$file['upload_data']['file_name']);
	
					}

						 
						 if (!$this->email->send()) {
						   
						  $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>El email no se ha podido enviar verifique que el correo sea valido e intentelo nuevamente</div>');
                        
		 				redirect(base_url().'index.php/carteranomisional/consulta');
						
						 }
						 else {

        				
                         
                         
            			  $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El correo se envio correctamente</div>');
                        
		 				redirect(base_url().'index.php/carteranomisional/consulta');	

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
function do_upload_mail() {

	
date_default_timezone_set("America/Bogota");
	
$nombre_carpeta = './uploads/cnm/';
$nombre_subcarpeta = './uploads/cnm/docemail';

if(!is_dir($nombre_carpeta)){ 
@mkdir($nombre_carpeta, 0777); 
}else{ 

}  
if(!is_dir($nombre_subcarpeta)){ 
@mkdir($nombre_subcarpeta, 0777); 
}else{ 

}

    $config['upload_path'] = './uploads/cnm/docemail';
	
    $config['allowed_types'] = 'gif|jpg|png|pdf';
    $config['max_size'] = '10000';

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload()) {
      return $error = array('error' => $this->upload->display_errors());
    } else {
      return $data = array('upload_data' => $this->upload->data());
    }
  }


function traslado_vivienda(){
	

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
               {
                //template data
                $this->template->set('title', 'Cartera No Misional');
                $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->data['message']=$this->session->flashdata('message');
				
				
                $this->template->load($this->template_file,'carteranomisional/carteranomisional_traslado_vivienda',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}


function pagos_seguros(){
	

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
               {
                //template data
                $this->template->set('title', 'Cartera No Misional');
                $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->data['message']=$this->session->flashdata('message');
				
				
                $this->template->load($this->template_file,'carteranomisional/carteranomisional_pagos_seguros',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}


function cobro_beneficiario(){
				
if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/add'))
							 {
					$this->template->set('title', 'Cobro a Beneficiarios');
												
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
						$calc_corriente= $this->input->post('reliq_combo_tipo_tasa_corriente');
						switch($calc_corriente)
						{case '1':
						$tipo_t_f_corriente= $this->input->post('reliq_aplica_tasa_c');
						$tipo_t_v_corriente= "";
						$valor_t_corriente= $this->input->post('reliq_porcent_tasa_c');
							break;
							
						case '2':
						$tipo_t_f_corriente= "";
						$tipo_t_v_corriente= $this->input->post('reliq_aplica_tasa_c');
						$valor_t_corriente= "";
							break;
							
						default:
						$tipo_t_f_corriente= "";
						$tipo_t_v_corriente= "";
						$valor_t_corriente= "";

						break;		
						}
						

													
													

						$mod_tasa_mora='S';		
						$calc_mora= $this->input->post('reliq_combo_tipo_tasa_mora');
						switch($calc_mora)
						{case '1':
						$tipo_t_f_mora= $this->input->post('reliq_aplica_tasa_m');
						$tipo_t_v_mora= "";
						$valor_t_mora= $this->input->post('reliq_porcent_tasa_m');
							break;
							
						case '2':
						$tipo_t_f_mora= "";
						$tipo_t_v_mora= $this->input->post('reliq_aplica_tasa_m');
						$valor_t_mora= "";
							break;
							
						default:
						$tipo_t_f_mora= "";
						$tipo_t_v_mora= "";
						$valor_t_mora= "";

						break;		
						}
						
						
								
$id_deuda = $this->input->post('id_deuda');

												
															$datacnm = array(
																'CALCULO_CORRIENTE' => $calc_corriente,
																'TIPO_T_F_CORRIENTE' => $tipo_t_f_corriente,
																'TIPO_T_V_CORRIENTE' => $tipo_t_v_corriente,
																'VALOR_T_CORRIENTE' => $valor_t_corriente,		
																'CALCULO_MORA' => $calc_mora,
																'TIPO_T_F_MORA' => $tipo_t_f_mora,
																'TIPO_T_V_MORA' => $tipo_t_v_mora,
																'VALOR_T_MORA' => $valor_t_mora,		
																													
												);
												
									if ($this->carteranomisional_model->updateCartera('CNM_CARTERANOMISIONAL',$datacnm,'',$id_deuda,"COD_CARTERA_NOMISIONAL") == TRUE)			
									{

										if($this->input->post('cod_tipo_cartera')==8){

												$data = array(
																'MOD_TASA_CORRIENTE' => $mod_tasa_corr,
																'MOD_TASA_MORA' => $mod_tasa_mora,
																'VALOR_CUOTA_APROBADA' => str_replace(".", "", $this->input->post('nueva_cuota')),
	
																													
												);
													$tabla_cart='CNM_CARTERA_PRESTAMO_HIPOTEC';}
													
												else{
													
													$data = array(
																'MOD_TASA_CORRIENTE' => $mod_tasa_corr,
																'MOD_TASA_MORA' => $mod_tasa_mora,
																																																									
																								);
													$tabla_cart='CNM_PRESTAMO_AHORRO';
																										
												}

								if ($this->carteranomisional_model->updateCartera($tabla_cart,$data,'',$id_deuda,"COD_CARTERA") == TRUE)		
									{
									
						$fechaact=$this->carteranomisional_model->detalleFechaAct($tabla_cart, $id_deuda);

						$this->cnm_liquidacioncuotas = new Cnm_liquidacioncuotas();
                if($this->cnm_liquidacioncuotas->CnmCalcularCuotas($id_deuda,  $fechaact["FECHA_ACTIVACION"], $fechaact["FORMA_PAGO"],false,true)==true)		
				{ 
						$this->traza_fecha_helper->trazarProcesoJuridico('550', '1364', '', '', $id_deuda, '', '', "Sin Comentarios Juridico", '','');
				}
				else {
				}
						$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Cartera ha sido reliquidada con éxito.</div>');
                     	$this->session->set_userdata('lolwut',$datos);			
						redirect(base_url().'index.php/carteranomisional/consulta/');	
		
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

												
												

$this->data['id_cnm']=$this->input->post('id_cnm_cobro');
//echo $this->data['id_cnm_cobro'];
//die();
				
$this->data['detalleCart']  = $this->carteranomisional_model->detalleCarterasDobleM($this->data['id_cnm'])->result_array[0];
$this->data['beneficiarios']  = $this->carteranomisional_model->detalleBeneficiarios($this->data['id_cnm']);

//var_dump($this->data['beneficiarios']);
//die();

				
                  $this->template->load($this->template_file,'carteranomisional/carteranomisional_cobro_beneficiario',$this->data); 
              

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


function reporte_cnm(){
	

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/reportes'))
               {
                //template data
                $this->template->set('title', 'Reporte Cartera No Misional');
                $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );

                $this->data['message']=$this->session->flashdata('message');
				
				
                $this->template->load($this->template_file,'carteranomisional/carteranomisional_reporte_cnm',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}

function reporte_general_cartera(){
	

	
		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/reportes'))
               {
                //template data
                $this->template->set('title', 'Reporte Cartera No Misional');
           /*     $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );
*/
$this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
                    'js/ZeroClipboard.js'
                );
                $this->data['message']=$this->session->flashdata('message');
				
				$this->data['tipos']  = $this->carteranomisional_model->getSelectTipoCartera('TIPOCARTERA','COD_TIPOCARTERA, NOMBRE_CARTERA');
				$this->template->load($this->template_file,'carteranomisional/carteranomisional_reporte_general_cartera',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}
function reporte_general_cartera_detalle(){
	
	

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/reportes'))
               {
               	
				
				
				
                //template data
                $this->template->set('title', 'Reporte Cartera No Misional');
           /*     $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );*/
				$this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
                    'js/ZeroClipboard.js'
                );

                $this->data['message']=$this->session->flashdata('message');
				
				$this->load->view('carteranomisional/carteranomisional_reporte_general_cartera_detalle',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}

function reporte_general_ingresos(){
	

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/reportes'))
               {
                //template data
                $this->template->set('title', 'Reporte Cartera No Misional');
               $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
                    'js/ZeroClipboard.js'
                );

                $this->data['message']=$this->session->flashdata('message');
				
				
                $this->template->load($this->template_file,'carteranomisional/carteranomisional_reporte_general_ingresos',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}

function reporte_general_ingresos_detalle(){
	

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/reportes'))
               {
                //template data
                $this->template->set('title', 'Reporte Cartera No Misional');
               $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
                    'js/ZeroClipboard.js'
                );
                $this->data['message']=$this->session->flashdata('message');
				
				
                $this->load->view('carteranomisional/carteranomisional_reporte_general_ingresos_detalle',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}

function reporte_liquidacion_cartera(){
	

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/reportes'))
               {
                //template data
                $this->template->set('title', 'Reporte Cartera No Misional');
                              $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
                    'js/ZeroClipboard.js'
                );

                $this->data['message']=$this->session->flashdata('message');
				
				$this->data['tipos']  = $this->carteranomisional_model->getSelectTipoCartera('TIPOCARTERA','COD_TIPOCARTERA, NOMBRE_CARTERA');
				
                $this->template->load($this->template_file,'carteranomisional/carteranomisional_reporte_liquidacion_cartera',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}

function reporte_liquidacion_cartera_detalle(){
	

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/reportes'))
               {
                //template data
                $this->template->set('title', 'Reporte Cartera No Misional');
                               $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
                    'js/ZeroClipboard.js'
                );

                $this->data['message']=$this->session->flashdata('message');
				
				$this->load->view('carteranomisional/carteranomisional_reporte_liquidacion_cartera_detalle',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}

function reporte_informe_morosos(){
	

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/reportes'))
               {
                //template data
                $this->template->set('title', 'Reporte Cartera No Misional');
               $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
                    'js/ZeroClipboard.js'
                );

                $this->data['message']=$this->session->flashdata('message');
				
				
                $this->template->load($this->template_file,'carteranomisional/carteranomisional_reporte_informe_morosos',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}

function reporte_informe_morosos_detalle(){
	

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/reportes'))
               {
                //template data
                $this->template->set('title', 'Reporte Cartera No Misional');
               $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
                    'js/ZeroClipboard.js'
                );
                $this->data['message']=$this->session->flashdata('message');
				
				
                $this->load->view('carteranomisional/carteranomisional_reporte_informe_morosos_detalle',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}


function reporte_historico_pagos(){
	

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/reportes'))
               {
                //template data
                $this->template->set('title', 'Reporte Cartera No Misional');
               $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
                    'js/ZeroClipboard.js'
                );
                $this->data['message']=$this->session->flashdata('message');
				
				$this->data['tipos']  = $this->carteranomisional_model->getSelectTipoCartera('TIPOCARTERA','COD_TIPOCARTERA, NOMBRE_CARTERA');
				
                $this->template->load($this->template_file,'carteranomisional/carteranomisional_reporte_historico_pagos',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}

function reporte_historico_pagos_detalle(){
	

		 if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/reportes'))
               {
                //template data
                $this->template->set('title', 'Reporte Cartera No Misional');
               $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
                    'js/ZeroClipboard.js'
                );

                $this->data['message']=$this->session->flashdata('message');
				
				
                $this->load->view('carteranomisional/carteranomisional_reporte_historico_pagos_detalle',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}


function visualizar_proyeccion(){
			if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
							 {

															
										$this->template->set('title', 'Consulta Cartera No Misional');
										
										$datos=$this->input->post('datos');
										$fecha_activacion=$this->input->post('fecha_activacion');
										$medio_pago=$this->input->post('medio_pago');
										$hipotecario=$this->input->post('hipotecario');
										$reliquidacion=$this->input->post('reliquidacion');
										$refinanciacion=$this->input->post('refinanciacion');
										//echo $hipotecario;
										//die();
										
										$this->cnm_liquidacioncuotas = new Cnm_liquidacioncuotas();
										
										$this->data['proyeccion'] = $this->cnm_liquidacioncuotas->CnmCalcularCuotasVista($datos, $fecha_activacion, $medio_pago, $hipotecario, $reliquidacion, $refinanciacion);
										/*echo "<pre>";
										print_r($this->data['proyeccion']['mensaje']);
										echo "</pre>";
										die();*/
										if($hipotecario=='true'){	
										$this->load->view('carteranomisional/carteranomisional_visualizar_proyeccion', $this->data);
										}
										else{
											$this->load->view('carteranomisional/carteranomisional_visualizar_proyeccion_ahorro', $this->data);
										}
										
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
										
										$id_deuda=$this->input->post('id_deuda');

										
										$this->data['detalle_pagos']  = $this->carteranomisional_model->detallePagos($id_deuda);
										//var_dump($this->data['detalle_pagos']);
										//die();	
										$this->load->view('carteranomisional/carteranomisional_visualizar_pagos', $this->data);
								
							 
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

function ver_pagos_seguros(){
			if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
							 {			
										$this->template->set('title', 'Consulta Cartera No Misional');
										$id_deuda=$this->input->post('id_cartera');
										$concepto=$this->input->post('concepto');
										$this->data['detalle_pagos']  = $this->carteranomisional_model->detallePagosSeguros($id_deuda, $concepto);
										//var_dump($this->data['detalle_pagos']);
										//die();	
										$this->load->view('carteranomisional/carteranomisional_visualizar_pagos_seguros', $this->data);
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

function visualizar_pagos_hipotecario(){


    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
							 {

															
										$this->template->set('title', 'Consulta Cartera No Misional');
										
										$id_deuda=$this->input->post('id_deuda');

										
										$this->data['detalle_pagos']  = $this->carteranomisional_model->detallePagosHipotecario($id_deuda);
										//var_dump($this->data['detalle_pagos']);
										//die();	
										$this->load->view('carteranomisional/carteranomisional_visualizar_pagos_hipotecario', $this->data);
								
							 
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



function visualizar_pagos_ecollect(){


    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
							 {

															
										$this->template->set('title', 'Consulta Cartera No Misional');
										
										$id_deuda=$this->input->post('id_deuda');

										
										$this->data['detalle_pagos']  = $this->carteranomisional_model->detallePagos($id_deuda);
										//var_dump($this->data['detalle_pagos']);
										//die();	
										$this->load->view('carteranomisional/carteranomisional_visualizar_pagos', $this->data);
								
							 
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

function cobro_coactivo(){
		if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
               {
                //template data
                $this->template->set('title', 'Cartera No Misional');
                $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );
				
				
				
				$atdoc_coact=$this->input->post('atdoc_coact');
				$id_cartera=$this->input->post('id_cnm_coact');
				$archivos_coact=$this->input->post('archivos_coact');
				
				$this->carteranomisional_model->recepcionTituloNM($id_cartera, $atdoc_coact, $archivos_coact);
				
				$this->carteranomisional_model->updateEstadoCoac('CNM_CARTERANOMISIONAL', $id_cartera);
				
				$this->data['message']='<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Agregada a Cobro Coactivo.</div>';
                $this->data['tipos']  = $this->carteranomisional_model->getSelectTipoCartera('TIPOCARTERA','COD_TIPOCARTERA, NOMBRE_CARTERA');
				$this->data['estados']  = $this->carteranomisional_model->getSelectEstados('ESTADOCARTERA','COD_EST_CARTERA, DESC_EST_CARTERA');
				$this->template->load($this->template_file, 'carteranomisional/carteranomisional_list', $this->data);
				
				}else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}	

function cobro_judicial(){
		if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
               {
                //template data
                $this->template->set('title', 'Cartera No Misional');
                $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );
				
				
				
				$atdoc_coact=$this->input->post('atdoc_judicial');
				$id_cartera=$this->input->post('id_cnm_judicial');
				$archivos_coact=$this->input->post('archivos_judicial');
				
				$this->carteranomisional_model->updateEstadoJudicial('CNM_CARTERANOMISIONAL', $id_cartera);
				
				$this->data['message']='<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera Agregada a Cobro Judicial.</div>';
                $this->data['tipos']  = $this->carteranomisional_model->getSelectTipoCartera('TIPOCARTERA','COD_TIPOCARTERA, NOMBRE_CARTERA');
				$this->data['estados']  = $this->carteranomisional_model->getSelectEstados('ESTADOCARTERA','COD_EST_CARTERA, DESC_EST_CARTERA');
				$this->template->load($this->template_file, 'carteranomisional/carteranomisional_list', $this->data);
				
				}else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}	

function documentos(){
		if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
               {
                //template data
                $this->template->set('title', 'Cartera No Misional');
                $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen'
                        );
				
				
				
				$id_cartera=$this->input->post('id_deuda_docs');
				$tipo_cartera=$this->input->post('tipo_cartera_docs');

				$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Agregar Documentos Cartera.</div>');
                $datos = array('id_cartera'=>$id_cartera, 'tipo_cartera'=>$tipo_cartera, 'agregar'=>'1');

 				$this->session->set_userdata('lolwut',$datos);			
				redirect(base_url().'index.php/carteranomisional/carga_archivo_cart/');
			}else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
          
		}	

}


