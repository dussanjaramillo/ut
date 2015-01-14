<?php

class Cnm_tipocartera extends MY_Controller {

		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('cnm_tipocartera_model','',TRUE); 
	}

	function index(){
		$this->manage();
	}

	function manage(){ 
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_tipocartera/add'))
							 {
$flag=$this->input->post('vista_flag');
if(!empty($flag)){}
else{
	$this->session->set_flashdata('message', '');
								}									
								$this->template->set('title', 'Tipo Cartera');
								$this->data['message']=$this->session->flashdata('message');
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen'
												);
								$this->data['javascripts']= array(
														'js/jquery.dataTables.min.js',
												); 
								$this->data['tipos']  = $this->cnm_tipocartera_model->getSelectTipoCartera('TIPOCARTERA','COD_TIPOCARTERA, NOMBRE_CARTERA, COD_ESTADO, FECHA_CREACION, FECHA_EDICION');
								//var_dump($this->data['tipos']);
								//die();
								
								$this->template->load($this->template_file, 'cnm_tipocartera/cnm_tipocartera_list', $this->data);
								
								
								
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_tipocartera/add'))
							 {
										$this->load->library('form_validation'); //por seguridad SIEMPRE! realizar validaciones del lado del servidor para todos los campos en los formularios y siempre usar validaciones del core de codeigniter
										$this->data['custom_error'] = ''; 
										
										$flag=$this->input->post('vista_flag');
										if(!empty($flag)){
											
										$this->nombre=$this->input->post('nombre');	
								$carteraexist=$this->cnm_tipocartera_model->verificarExistenciaNueva($this->nombre);
								
								if($carteraexist['EXIST']==0){	
											
											
										$this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
												if ($this->form_validation->run() == false)
										{
												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{
												$date = array(
																'FECHA_CREACION' => date("d/m/Y")
												);
												$data = array(
																'COD_ESTADO' => $this->input->post('tipo_id'),
																'NOMBRE_CARTERA' => mb_strtoupper($this->input->post('nombre')),
																'TIPO' => 'OTRAS'
												);

									if ($this->cnm_tipocartera_model->add('TIPOCARTERA',$data,$date) == TRUE)
									{
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Tipo de Cartera se ha creado con éxito.</div>');
													redirect(base_url().'index.php/cnm_tipocartera/');
									}
									else
									{
										$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

									}
								}

							}
else{
	$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>La cartera ya se encuentra registrada.</div>');
													redirect(base_url().'index.php/cnm_tipocartera/');
	
}		

				}

										$this->template->load($this->template_file, 'cnm_tipocartera/cnm_tipocartera_add', $this->data);
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/cnm_tipocartera');
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_tipocartera/add'))
							 {
							 	
								$this->cod_cartera=$this->input->post('cod_cartera');	
										
										$this->data['custom_error'] = ''; 
										$flag=$this->input->post('vista_flag');
if(!empty($flag)){	
										
								$this->nombre=$this->input->post('nombre');	
								$carteraexist=$this->cnm_tipocartera_model->verificarExistencia($this->nombre, $this->cod_cartera);
								
								if($carteraexist['EXIST']==0){
																			
										$this->load->library('form_validation'); //por seguridad SIEMPRE! realizar validaciones del lado del servidor para todos los campos en los formularios y siempre usar validaciones del core de codeigniter
										
										$this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
												if ($this->form_validation->run() == false)
										{
												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{
																								
												
												$data = array(
																'COD_TIPOCARTERA' => $this->input->post('cod_cartera'),
																'NOMBRE_CARTERA' => mb_strtoupper($this->nombre),
																'COD_ESTADO' => $this->input->post('tipo_id'),
																'FECHA_EDICION' => date("d/m/Y")
												);
									
									
									if ($this->cnm_tipocartera_model->editar('TIPOCARTERA',$data) == TRUE)
									{
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Tipo de Cartera se ha actualizado con éxito.</div>');
													redirect(base_url().'index.php/cnm_tipocartera/');
									}
									else
									{
										$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

									}
								}
										
							}
							
else{
	$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>El Tipo de Cartera Ya existe no se puede actualizar.</div>');
													redirect(base_url().'index.php/cnm_tipocartera/');
	
}
										
					}
						
else{										
										//var_dump($this->data['datos_edit']);
										//die();
										$cartera=$this->cnm_tipocartera_model->countCartera($this->cod_cartera);
										if($cartera["EXIST"]>0)
										{
										$this->load->view('cnm_tipocartera/cnm_tipocartera_noedit');
										}
										else{
										$this->data['datos_edit']=$this->cnm_tipocartera_model->selectTipoCartera($this->cod_cartera);
										$this->load->view('cnm_tipocartera/cnm_tipocartera_edit', $this->data);
										}
										}

								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/cnm_tipocartera');
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_tipocartera/delete'))
							 {
										$this->cod_tipo_cartera=$this->input->post('cod_tipo_cartera');
									$cartera=$this->cnm_tipocartera_model->countCartera($this->cod_tipo_cartera);
										if($cartera["EXIST"]>0)
										{
										$data = array(
                                    				'ELIMINDADO' => '1',
													'COD_TIPOCARTERA' => $this->cod_tipo_cartera
				                            	);
				           				$this->cnm_tipocartera_model->delete('TIPOCDARTERA',$data);
										}
										else{
										$data = array(
                                    				'ELIMINADO' => '1',
													'COD_TIPOCARTERA' => $this->cod_tipo_cartera
				                            	);
				           				$this->cnm_tipocartera_model->delete('TIPOCARTERA',$data);
										}											
											 
											 
											 $data = array(
                                    				'ELIMINADO' => '1',
													'COD_TIPOCARTERA' => $this->cod_tipo_cartera
				                            	);
				           				$this->cnm_tipocartera_model->delete('TIPOCARTERA',$data);



								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/fiscalizacion');
									 }
					}else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}



}