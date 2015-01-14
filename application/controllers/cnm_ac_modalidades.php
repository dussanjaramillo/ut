<?php

class Cnm_ac_modalidades extends MY_Controller {

		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('cnm_ac_modalidades_model','',TRUE); 
	}

	function index(){
		$this->manage();
	}

	function manage(){ 
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_tipo_tasa_interes/list'))
							 {
							 	$cod_acuerdo=$this->input->post('cod_acuerdo');
								
$flag=$this->input->post('vista_flag');
if(!empty($flag)){}
else{
	$this->session->set_flashdata('message', '');
								}									
								$this->template->set('title', 'Modalidades');
								$this->data['message']=$this->session->flashdata('message');
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen'
												);
								$this->data['javascripts']= array(
														'js/jquery.dataTables.min.js',
														'js/jquery.dataTables.defaults.js'
												); 
												

								$this->data['modalidades']  = $this->cnm_ac_modalidades_model->selectModalidades($cod_acuerdo);
								$this->data['cod_acuerdo']  = $cod_acuerdo;
					
								$this->load->view('cnm_ac_modalidades/cnm_ac_modalidades_list', $this->data);
								
								
								
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_tipo_tasa_interes/add'))
							 {
											$this->cod_acuerdo=$this->input->post('cod_ac_mod');
											
										
										$this->load->library('form_validation'); //por seguridad SIEMPRE! realizar validaciones del lado del servidor para todos los campos en los formularios y siempre usar validaciones del core de codeigniter
										$this->data['custom_error'] = ''; 
										
										$flag=$this->input->post('vista_flag');
										if(!empty($flag)){
											$this->nombre=mb_strtoupper($this->input->post('componente'));	
										

				
								$carteraexist=$this->cnm_ac_modalidades_model->verificarExistenciaNueva($this->nombre, $this->cod_acuerdo);
							//echo $carteraexist['EXIST'];
							//die();
								if($carteraexist['EXIST']==0){	
											
										$this->form_validation->set_rules('componente', 'Nombre', 'required|trim|xss_clean|max_length[128]');
											if ($this->form_validation->run() == false)
										{
												 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{
												$date = array(

												);
												$data = array(
																'COD_ACUERDO' => $this->cod_acuerdo,
																'NOMBRE_MODALIDAD' => $this->nombre,
												);

									if ($this->cnm_ac_modalidades_model->add('CNM_MODALIDADES',$data,$date) == TRUE)
									{
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Modalidad se agregó con éxito.</div>');
													redirect(base_url().'index.php/cnm_acuerdo_ley/');
									}
									else
									{
										$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

									}
								}

							}
else{
	$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ya existe una modalidad con el mismo nombre para este Acuerdo de Ley</div>');
													redirect(base_url().'index.php/cnm_acuerdo_ley/');
	
}		

				}

										$this->data['cod_acuerdo']  = $this->cod_acuerdo;
										//var_dump($this->data['var_esp']);		
												//die();
										$this->template->load($this->template_file, 'cnm_ac_modalidades/cnm_ac_modalidades_add', $this->data);
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/cnm_acuerdo_ley');
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_tipo_tasa_interes/delete'))
							 {
										$this->cod_mod=$this->input->post('cod_mod');
												
												$data = array(
                                    				'ELIMINADO' => '1',
													'COD_MODALIDAD' => $this->cod_mod
				                            	);
				           				$this->cnm_ac_modalidades_model->delete('CNM_MODALIDADES',$data);


								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/bancos');
									 }
					}else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}



}