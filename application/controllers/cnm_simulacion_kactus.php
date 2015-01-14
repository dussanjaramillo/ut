<?php

class Cnm_simulacion_kactus extends MY_Controller {

		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('cnm_simulacion_kactus_model','',TRUE); 
	}

	function index(){
		$this->manage();
	}

	function manage(){ 
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_rango_tipo_tasa_interes_historica/list'))
							 {

													$this->template->set('title', 'Simulación Kactus');

												
												$flag=$this->input->post('vista_flag');
										if(!empty($flag)){
											$mes= $this->input->post('mes');
											$anio= $this->input->post('anio');
											$mes_proy= $anio."-".$mes;
											
										if ($this->cnm_simulacion_kactus_model->consultaCuotas($mes_proy)== TRUE)
										{
											$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Los descuentos han sido aplicados con éxito.</div>');
											redirect(base_url().'index.php/reporteador/r_kactus');
										}

										}								
								$this->template->load($this->template_file, 'cnm_simulacion_kactus/cnm_simulacion_kactus_list', $this->data);
								
								
								
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cnm_tipo_tasa_interes/add'))
							 {
										$cod_tasa_hist=$this->input->post('cod_tasa_historica');	
										
										$fecha_ini=$this->input->post('fecha_inicial');	
										$fecha_fin=$this->input->post('fecha_final');	
										$valor_tasa=$this->input->post('valor_tasa');
										$this->load->library('form_validation'); //por seguridad SIEMPRE! realizar validaciones del lado del servidor para todos los campos en los formularios y siempre usar validaciones del core de codeigniter
										$this->data['custom_error'] = ''; 
										
										$flag=$this->input->post('vista_flag');
										if(!empty($flag)){
											
								$carteraexist=$this->cnm_rango_tipo_tasa_interes_historica_model->verificarExistencia($fecha_ini,$fecha_fin, $cod_tasa_hist);

								if($carteraexist['EXIST']==0){	
											
										        			$this->form_validation->set_rules('fecha_inicial', 'Fecha Inicio', 'required');
        													$this->form_validation->set_rules('fecha_final', 'Fecha Fin', 'required'); 		
        													if ($this->form_validation->run() == false)
										{
												$this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

										} else
										{
												
												$date = array(
															'FECHA_INI_VIGENCIA' => $fecha_ini,
															'FECHA_FIN_VIGENCIA' => $fecha_fin,
												);
												$data = array(

																'COD_TIPO_TASA_HIST' => $cod_tasa_hist,
																'VALOR_TASA' => $valor_tasa,
																
												);

									if ($this->cnm_rango_tipo_tasa_interes_historica_model->add('RANGOSTASAHISTORICA',$data,$date) == TRUE)
									{
										$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El rango se ha añadido con éxito.</div>');
													redirect(base_url().'index.php/cnm_tipo_tasa_interes_historic/');
									}
									else
									{
										$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';

									}
								}

							}
else{
	$this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>El Rango o alguna de las fechas ya se encuentran registrados para esta tasa, no es posible registrarlo nuevamente.</div>');
													redirect(base_url().'index.php/cnm_tipo_tasa_interes_historic/');
	
}		

				}
										$this->data['style_sheets']= array(
														'css/chosen.css' => 'screen'
												);
										$this->data['javascripts']= array(
														'js/chosen.jquery.min.js'
												);

										
										$this->data['informacion']  = $this->cnm_rango_tipo_tasa_interes_historica_model->selectInformacion($cod_tasa_hist);
										$this->data['cod_tasa_hist']  = $cod_tasa_hist;
										$this->load->view('cnm_rango_tipo_tasa_interes_historica/cnm_rango_tipo_tasa_interes_historica_add', $this->data);
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


	



}