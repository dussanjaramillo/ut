<?php

class Tipostasainteres extends MY_Controller {
		
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipostasainteres/manage'))
							 {
								//template data
								$this->template->set('title', 'Administrar Tipo Tasa Interés');
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen'
												);
								$this->data['javascripts']= array(
														'js/jquery.dataTables.min.js',
														'js/jquery.dataTables.defaults.js'
												);
								$this->data['message']=$this->session->flashdata('message');
								$this->template->load($this->template_file, 'tipostasainteres/tipostasainteres_list',$this->data); 
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipostasainteres/add'))
							 {
										$this->load->library('form_validation');    
										$this->data['custom_error'] = '';
										$showForm=0;
										$this->form_validation->set_rules('nombretasa', 'Nombre Tasa', 'required|trim|xss_clean|max_length[128]');
										$this->form_validation->set_rules('tipo', 'Tipo tasa', 'required|trim|xss_clean|max_length[128]');
										$this->form_validation->set_rules('tasaremunera', 'Tasa en que remunera', 'required|numeric');
										$this->form_validation->set_rules('liquidainteres', 'Periodo Liquidación', 'required|numeric');
										$this->form_validation->set_rules('pagointeres', 'Pago Interés', 'required|numeric');

										if ($this->form_validation->run() == false)
										{
												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{    
												
												$data = array(
																'NOMBRE_TASAINTERES' => set_value('nombretasa'),
																'TIPO' => set_value('tipo'),
																'COD_TIPOPERIODO' => set_value('tasaremunera'),
																'COD_PER_LIQUIDACION' => set_value('liquidainteres'),
																'COD_FORMAPAGO_INTERESES' => set_value('pagointeres')
												);
												 
									if ($this->codegen_model->add('TIPOTASAINTERES',$data) == TRUE)
									{
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La tasa de interés se ha creado con éxito.</div>');
													redirect(base_url().'index.php/tipostasainteres/');
									}
									else
									{
										$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

									}
								}
										//add style an js files for inputs selects
										$this->data['style_sheets']= array(
														'css/chosen.css' => 'screen'
												);
										$this->data['javascripts']= array(
														'js/chosen.jquery.min.js'
												);
										
										$this->data['periodotasa']  = $this->codegen_model->getSelect('TIPOPERIODOTASA','COD_TIPOPERIODOTASA,NOMBRE_TIPOPERIODO');
										$this->data['periodoliquidacion']  = $this->codegen_model->getSelect('PERIODOLIQUIDACION','COD_PERIDOLIQUIDACION,NOMBRE_PERIODOLIQUIDACION');
										$this->data['pagointeres']  = $this->codegen_model->getSelect('FORMAPAGOINTERESES','COD_FORMA_PAGO_INTERESES,NOMBRE_FORMAPAGO');
															
										$this->template->load($this->template_file, 'tipostasainteres/tipostasainteres_add', $this->data);
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/tipostasainteres');
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipostasainteres/edit'))
							 {    
								$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
													redirect(base_url().'index.php/tipostasainteres');
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
																 
														if ($this->codegen_model->edit('TASA_ACUERDO_PAGO',$data,'IDTASA',$this->input->post('id')) == TRUE)
														{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La tasa de pago se ha editado correctamente.</div>');
																			redirect(base_url().'index.php/tipostasainteres/');
														}
														else
														{
															$this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';

														}
													}
															$this->data['result'] = $this->codegen_model->get('TIPOTASAINTERES','COD_TIPO_TASAINTERES,NOMBRE_TASAINTERES,TIPO,COD_TIPOPERIODO,COD_FORMAPAGO_INTERESES,COD_PER_LIQUIDACION','COD_TIPO_TASAINTERES = '.$this->uri->segment(3),1,1,true);
															$this->data['periodotasa']  = $this->codegen_model->getSelect('TIPOPERIODOTASA','COD_TIPOPERIODOTASA,NOMBRE_TIPOPERIODO');
															$this->data['periodoliquidacion']  = $this->codegen_model->getSelect('PERIODOLIQUIDACION','COD_PERIDOLIQUIDACION,NOMBRE_PERIODOLIQUIDACION');
															$this->data['pagointeres']  = $this->codegen_model->getSelect('FORMAPAGOINTERESES','COD_FORMA_PAGO_INTERESES,NOMBRE_FORMAPAGO');
												
																	//add style an js files for inputs selects
																	$this->data['style_sheets']= array(
																					'css/chosen.css' => 'screen'
																			);
																	$this->data['javascripts']= array(
																					'js/chosen.jquery.min.js'
																			);

																	$this->template->load($this->template_file, 'tipostasainteres/tipostasainteres_edit', $this->data); 
											}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/tipostasainteres');
									 }
								
						}else
								{
								redirect(base_url().'index.php/auth/login');
								}
				
		}
	
		function delete(){
				 if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipostasainteres/delete'))
							 {
										$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
													redirect(base_url().'index.php/tipostasainteres');
										}else{
											 
				           				if($this->codegen_model->delete('TIPOTASAINTERES','COD_TIPO_TASAINTERES',$ID) == TRUE){
												//$this->codegen_model->delete('TIPOTASAINTERES','COD_TIPO_TASAINTERES',$ID);             
												$this->template->set('title', 'tipostasainteres');
												$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El tipo tasa de interés se eliminó correctamente.'.$ID.'</div>');
												redirect(base_url().'index.php/tipostasainteres/');
										}
									}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/tipostasainteres');
									 }
					}else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}
 
		 function datatable (){
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipostasainteres/manage'))
							 {
							 
							 if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipostasainteres/edit'))
							 {
								$this->load->library('datatables');
								$this->datatables->select('TIPOTASAINTERES.COD_TIPO_TASAINTERES, TIPOTASAINTERES.NOMBRE_TASAINTERES, TIPOTASAINTERES.TIPO, TP.NOMBRE_TIPOPERIODO, FP.NOMBRE_FORMAPAGO, PL.NOMBRE_PERIODOLIQUIDACION');
								$this->datatables->from('TIPOTASAINTERES'); 
								$this->datatables->join('TIPOPERIODOTASA TP','TP.COD_TIPOPERIODOTASA = TIPOTASAINTERES.COD_TIPOPERIODO', ' inner ');
								$this->datatables->join('FORMAPAGOINTERESES FP','FP.COD_FORMA_PAGO_INTERESES = TIPOTASAINTERES.COD_FORMAPAGO_INTERESES', ' inner ');
								$this->datatables->join('PERIODOLIQUIDACION PL','PL.COD_PERIDOLIQUIDACION = TIPOTASAINTERES.COD_PER_LIQUIDACION', ' inner ');
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="'.base_url().'index.php/tipostasainteres/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'TIPOTASAINTERES.COD_TIPO_TASAINTERES');
							}else{
								$this->datatables->select('TIPOTASAINTERES.COD_TIPO_TASAINTERES, TIPOTASAINTERES.NOMBRE_TASAINTERES, TIPOTASAINTERES.TIPO, TP.NOMBRE_TIPOPERIODO, FP.NOMBRE_FORMAPAGO, PL.NOMBRE_PERIODOLIQUIDACION');
								$this->datatables->from('TIPOTASAINTERES'); 
								$this->datatables->join('TIPOPERIODOTASA TP','TP.COD_TIPOPERIODOTASA = TIPOTASAINTERES.COD_TIPOPERIODO', ' inner ');
								$this->datatables->join('FORMAPAGOINTERESES FP','FP.COD_FORMA_PAGO_INTERESES = TIPOTASAINTERES.COD_FORMAPAGO_INTERESES', ' inner ');
								$this->datatables->join('PERIODOLIQUIDACION PL','PL.COD_PERIDOLIQUIDACION = TIPOTASAINTERES.COD_PER_LIQUIDACION', ' inner ');
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'TIPOTASAINTERES.COD_TIPO_TASAINTERES');
							}
								echo $this->datatables->generate();
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/tipostasainteres');
									 }
						}else
						{
							redirect(base_url().'index.php/auth/login');
						}           
		}
}


/* End of file tipostasainteres.php */
/* Location: ./system/application/controllers/tipostasainteres.php */