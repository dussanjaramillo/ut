<?php

class Asignacionsemiautomatica extends MY_Controller {

		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('codegen_model','',TRUE);
		$this->load->model('asigna_model');
                $this->load->file(APPPATH . "controllers/sgva_client.php", true);

	}

	function index(){
		$this->manage();
	}

	function manage(){
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('asignacionsemiautomatica/manage'))
							 {
								
								$this->load->library('form_validation'); 
				                $this->data['custom_error'] = '';
				                
				                				                
				                $this->form_validation->set_rules('ciudad', 'Ciudad', 'trim|xss_clean|numeric'); 
				                $this->form_validation->set_rules('ciu', 'CIIU', 'trim|xss_clean|numeric');
				                $this->form_validation->set_rules('tipoempresa_id', 'Tipo de Empresa', 'trim|xss_clean|numeric');


				                
				                if ($this->form_validation->run() == false) {
				                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
				                    
				                } else {
				                
				                    $ciudad = $this->input->post('ciudad');
				                    $ciiu = $this->input->post('ciu');
				                    $empresa = $this->input->post('tipoempresa_id');
				                    $regional = $this->session->userdata('regional');
				                    
				                    
				                    if ($this->asignar($ciudad, $ciiu, $empresa, $regional) == TRUE) {
				                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El proceso ha concluido con éxito.</div>');
				                        redirect(base_url() . 'index.php/asignacionsemiautomatica');
				                    } else {
				                        $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
				                        
				                    }
				                }

								//template data
								$this->template->set('title', 'Asignacion de Fiscalizadores');
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen',
														'css/chosen.css' => 'screen'
												);
								$this->data['javascripts']= array(
														'js/jquery.dataTables.min.js',
														'js/jquery.dataTables.defaults.js',
														'js/jquery.dataTables.columnFilter.js',
														'js/chosen.jquery.min.js'
												);
								$this->data['tiposempresa']  = $this->codegen_model->getSelect('TIPOEMPRESA','CODTIPOEMPRESA,NOM_TIPO_EMP');
								
								$this->data['municipios'] = $this->codegen_model->getMunicipio($this->session->userdata('regional'));
								$this->data['ciu'] = $this->codegen_model->getSelect('CIIU', 'CLASE,DESCRIPCION');
								$this->data['message'] = $this->session->flashdata('message');
								
								$this->template->load($this->template_file, 'asignacionsemiautomatica/asignacionsemiautomatica_list',$this->data);
							 }else {
								$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
											redirect(base_url().'index.php/inicio');
							 }

						}else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}


	function getData(){
            if ($this->ion_auth->logged_in()) {
            	
                $lenght = $this->input->post('iDisplayLength');
                $data['registros'] = $this->asigna_model->getDatax($this->input->post('ciu'), $this->input->post('ciudad'), $this->input->post('tipoempresa_id'), $this->session->userdata('regional'), $this->input->post('iDisplayStart'), $this->input->post('sSearch'),$lenght);  
                define('AJAX_REQUEST', 1);//truco para que en nginx no muestre el debug
                $TOTAL = $this->asigna_model->totalData($this->input->post('ciu'), $this->input->post('ciudad'), $this->input->post('tipoempresa_id'), $this->session->userdata('regional'), $this->input->post('sSearch'));  
                echo json_encode(array('aaData'=>$data['registros'],  
                    'iTotalRecords'=>$TOTAL,  
                    'iTotalDisplayRecords'=>$TOTAL,
                    'sEcho'=>$this->input->post('sEcho')));
            } else {
                redirect(base_url() . 'index.php/auth/login');
            }
        }

	function asignar($ciudad, $ciiu, $empresa, $regional)
    {

        // |:::::Query's segun los filtros ingresados
    	$query_fiscalizadores = $this->db->query("SELECT IDUSUARIO
    										FROM USUARIOS 
    										WHERE FISCALIZADOR = 'S' AND COD_REGIONAL ='$regional' AND ACTIVO = 1");
    	
    	$query = "SELECT EV.COD_EMPRESA
                           FROM EMPRESASEVASORAS EV
	                       INNER JOIN EMPRESA E ON e.codempresa = ev.COD_EMPRESA 
	                       INNER JOIN municipio M ON M.CODMUNICIPIO = E.COD_MUNICIPIO AND M.COD_DEPARTAMENTO = E.COD_REGIONAL
	                       INNER JOIN TIPOEMPRESA T ON T.CODTIPOEMPRESA = E.COD_TIPOEMPRESA
	                       WHERE EV.COD_EMPRESA NOT IN (SELECT NIT_EMPRESA FROM ASIGNACIONFISCALIZACION)
	                       AND ev.fiscalizada IS NULL
	                       AND M.COD_DEPARTAMENTO = '$regional'";
	    if ($ciiu > 0) {
	    	$query.= " AND e.ciiu = '$ciiu'";
	    }
	    if ($ciudad > 0) {
	    	$query.= " AND E.COD_MUNICIPIO = '$ciudad'";
	    }
	    if ($empresa > 0) {
	    	$query.= " AND E.COD_TIPOEMPRESA = '$empresa'";
	    }
	    

    	$query_empresas =  $this->db->query($query);
    	// |:::::Guarda en un arreglo las query
    	$fiscalizadores = $query_fiscalizadores->result_array();
    	$empresas = $query_empresas->result_array();

    	if (count($empresas) > 0 && count($fiscalizadores) > 0) {
    		
    	
    		$i = 0;
			while ($i <= count($empresas)){

				if ($i == count($empresas)) {
						break;
					}
					
				for ($j = 0; $j < count($fiscalizadores); $j++){
						
						if ($i == count($empresas)) {
							break;
						}	
											
						//echo "llegue a la data";						
						// |:::::Data que sera ingresada en la tabla ASIGNACIONFISCALIZACION						
						$date = array(
                           				'FECHA_ASIGNACION' => date("d/m/Y")
                        );
						$data_asignacion = array(
										'NIT_EMPRESA' => $empresas[$i]['COD_EMPRESA'],
										'ASIGNADO_A' => $fiscalizadores[$j]['IDUSUARIO'],
										'ASIGNADO_POR' => $this->session->userdata('user_id'),
										'COMENTARIOS_ASIGNACION' => "ASIGNACIÓN AUTOMATICA"

						);


						// |:::::Data que servira para actualizar el campo FISCALIZADA de la tabla EMPRESASEVASORAS, con el fin de evitar que se liste
						// |:::::en el datatable de esta lista	 			
						$data_fiscalizada = array(
										'FISCALIZADA' => 1
										
						);
						//echo "llegue a la condicion de DB";
						$this->codegen_model->add('ASIGNACIONFISCALIZACION', $data_asignacion, $date);
                                                 $this->sgva_client = new sgva_client();
                                                 $resultado = $this->sgva_client->AsignarFiscalizador($data_asignacion);
                                              
						$this->codegen_model->edit('EMPRESASEVASORAS', $data_fiscalizada, 'COD_EMPRESA', $empresas[$i]['COD_EMPRESA']);
					$i++;
					
				}
			}

			return true;
    	} else {

    		return false;
    	}
    }
		
	function datatable (){
				if ($this->ion_auth->logged_in())
					 {
							if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('asignacionsemiautomatica/manage'))
							 {
							 
							if ($this->ion_auth->is_admin() || $this->session->userdata('regional') == 999)
							{	
								$this->load->library('datatables');
								$this->datatables->select("EMPRESASEVASORAS.COD_EMP_EVASORA,
															E.RAZON_SOCIAL,
															M.NOMBREMUNICIPIO,
															T.NOM_TIPO_EMP,
															E.CIIU");
								
								$this->datatables->from('EMPRESASEVASORAS');
								$this->datatables->join('EMPRESA E','E.CODEMPRESA = EMPRESASEVASORAS.COD_EMP_EVASORA', 'inner');
								$this->datatables->join('MUNICIPIO M','M.CODMUNICIPIO = E.COD_MUNICIPIO AND M.COD_DEPARTAMENTO = E.COD_REGIONAL ', 'inner');
								$this->datatables->join('TIPOEMPRESA T','T.CODTIPOEMPRESA = E.COD_TIPOEMPRESA', 'left');
								$this->datatables->where('EMPRESASEVASORAS.FISCALIZADA', null);

								
								
							
								
								
							}else{

								
									
								$this->load->library('datatables');
								$this->datatables->select("EMPRESASEVASORAS.COD_EMP_EVASORA,
															E.RAZON_SOCIAL,
															M.NOMBREMUNICIPIO,
															T.NOM_TIPO_EMP,
															E.CIIU");
								
								$this->datatables->from('EMPRESASEVASORAS');
								$this->datatables->join('EMPRESA E','E.CODEMPRESA = EMPRESASEVASORAS.COD_EMP_EVASORA', 'inner');
								$this->datatables->join('MUNICIPIO M','M.CODMUNICIPIO = E.COD_MUNICIPIO AND M.COD_DEPARTAMENTO = E.COD_REGIONAL ', 'inner');
								$this->datatables->join('TIPOEMPRESA T','T.CODTIPOEMPRESA = E.COD_TIPOEMPRESA', 'left');
								$this->datatables->where('EMPRESASEVASORAS.FISCALIZADA', null);
								$this->datatables->where('E.COD_REGIONAL', $this->session->userdata('regional'));
								
									
							}
								echo $this->datatables->generate();
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/conempresasprocobro');
									 }
						}else
						{
							redirect(base_url().'index.php/auth/login');
						}           
		}
}
