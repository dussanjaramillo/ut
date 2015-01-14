<?php

class Ecollect extends MY_Controller {
		
		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');		
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('codegen_model','',TRUE);

	}	
	
	function index(){
		$this->manage();
	}

	function manage(){
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('ecollect/manage'))
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
								$this->template->load($this->template_file, 'ecollect/ecollect_list',$this->data); 
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('ecollect/add'))
							 {
										$this->load->library('form_validation');    
										$this->data['custom_error'] = '';
										$this->form_validation->set_rules('nit', 'NIT / Cédula', 'required|numeric');     
										if ($this->form_validation->run() == false)
										{
										  $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{    
										  $this->data['result'] = $this->codegen_model->get('CONTACTOEMPRESA','NOMBRE_CONTACTO','NIT_EMPRESA = '.$this->input->post('nit'),1,1,true);
		                                  if ($this->data['result']) {
		                                  	   $this->data['custom_error'] = 'bien';
                                          



		                                  	$this->template->load($this->template_file, 'ecollect/ecollect_add', $this->data);
		                                  } else {
		                                  	$this->data['custom_error'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>El nit ingresado no se encuentra registrado, por favor verifique e intente de nuevo</div>';
		                                  }
								        }
										
															
										$this->template->load($this->template_file, 'ecollect/ecollect_add', $this->data);
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/ecollect');
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('ecollect/edit'))
							 {    
								$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
													redirect(base_url().'index.php/ecollect');
										}else{
															$this->load->library('form_validation');  
													$this->data['custom_error'] = '';
													$this->form_validation->set_rules('valortasa', 'Valor Tasa', 'required|numeric');  
															$this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]');  
															if ($this->form_validation->run() == false)
															{
																	 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
																	
															} else
															{                            
																	$data = array(
																					'VALORTASA' => $this->input->post('valortasa'),
																					'IDESTADO' => $this->input->post('estado_id')
																	);
																 
														if ($this->codegen_model->edit('ECOLLECT',$data,'IDTASA',$this->input->post('id')) == TRUE)
														{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La tasa de pago se ha editado correctamente.</div>');
																			redirect(base_url().'index.php/ecollect/');
														}
														else
														{
															$this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';

														}
													}
															$this->data['result'] = $this->codegen_model->get('ECOLLECT','IDTASA,IDCONCEPTO,VALORTASA,IDESTADO','IDTASA = '.$this->uri->segment(3),1,1,true);
															$this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');
															$this->data['conceptos']  = $this->codegen_model->getSelect('CONCEPTO','IDCONCEPTO,NOMBRECONCEPTO');
																	
																	//add style an js files for inputs selects
																	$this->data['style_sheets']= array(
																					'css/chosen.css' => 'screen'
																			);
																	$this->data['javascripts']= array(
																					'js/chosen.jquery.min.js'
																			);

																	$this->template->load($this->template_file, 'ecollect/ecollect_edit', $this->data); 
											}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/ecollect');
									 }
								
						}else
								{
								redirect(base_url().'index.php/auth/login');
								}
				
		}
	
		function delete(){
				 if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('ecollect/delete'))
							 {
										$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
													redirect(base_url().'index.php/ecollect');
										}else{
											 $data = array(
                                    				'IDESTADO' => '2'

				                            	);
				           				if($this->codegen_model->edit('ECOLLECT',$data,'IDTASA',$ID) == TRUE){
												//$this->codegen_model->delete('ECOLLECT','IDTASA',$ID);             
												$this->template->set('title', 'ecollect');
												$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La tasa de pado se eliminó correctamente.'.$ID.'</div>');
												redirect(base_url().'index.php/ecollect/');
										}
									}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/ecollect');
									 }
					}else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}
 
		 function datatable (){
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('ecollect/manage'))
							 {
							 
							 if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('ecollect/edit'))
							 {
								$this->load->library('datatables');
								$this->datatables->select('ECOLLECT.COD_TRANSACCION,ECOLLECT.COD_LOG_ECOLLECT,ECOLLECT.CONSECUTIVO_DET,ECOLLECT.NRO_IDENTIFICACION');
								$this->datatables->from('ECOLLECT'); 
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="'.base_url().'index.php/ecollect/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
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
													redirect(base_url().'index.php/ecollect');
									 }
						}else
						{
							redirect(base_url().'index.php/auth/login');
						}           
		}
}


/* End of file ecollect.php */
/* Location: ./system/application/controllers/ecollect.php */