<?php

class Cnm_cuota_parte extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'template_helper','traza_fecha_helper'));
        $this->load->model('cnm_cuota_parte_model', '', TRUE);


        $this->data['javascripts'] = array(
            'js/tinymce/tinymce.jquery.min.js',
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/validateForm.js',
        );
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen'
        );
    }

    function index() {
        $this->manage();
    }

    function manage() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/manage')) {

                $this->template->set('title', 'Cargue Cuotas Partes Pensional');
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/validationEngine.jquery.css' => 'screen'
                );


                $this->data['custom_error'] = '';
                $showForm = 0;


                $flag = $this->input->post('vista_flag');
                if (!empty($flag)) {

                    $this->form_validation->set_rules('vista_flag', '', 'required');


                    if ($this->form_validation->run() == false) {


                        $this->dataemail['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cargue Cuotas Partes Pensional realizado correctamente</div>');

                        redirect(base_url() . 'index.php/carteranomisional/manage');
                    }
                }

                $this->session->set_flashdata('message', '');
                $this->data['message'] = $this->session->flashdata('message');

                $this->data['tipos'] = $this->cnm_cuota_parte_model->getSelectTipoCartera('TIPOCARTERA', 'COD_TIPOCARTERA, NOMBRE_CARTERA');

                $this->template->load($this->template_file, 'cnm_cuota_parte/cnm_cuota_parte_cargue', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function add() {



        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/add')) {
                $this->template->set('title', 'Cartera Cuotas Partes');

                $this->template->load($this->template_file, 'cnm_cuota_parte/cnm_cuota_parte_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/carteranomisional');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function edit() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/edit')) {
                $ID = $this->uri->segment(3);
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/fiscalizacion');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
                    $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');
                    if ($this->form_validation->run() == false) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $data = array(
                            'NOMBREBANCO' => $this->input->post('nombre'),
                            'IDESTADO' => $this->input->post('estado_id')
                        );

                        if ($this->fiscalizacion_model->edit('BANCO', $data, 'IDBANCO', $this->input->post('id')) == TRUE) {
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El banco se ha editado correctamente.</div>');
                            redirect(base_url() . 'index.php/fiscalizacion/');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                        }
                    }
                    $this->data['result'] = $this->fiscalizacion_model->get('PERIODO_INICIAL', 'PERIODO_FINAL', 'NOMBRE_CONCEPTO', 'NOMBRE_ESTADO', 'NOMBREUSUARIO', 'NRO_EXPEDIENTE', 'NRO_EXPEDIENTE = ' . $this->uri->segment(3), 1, 1, true);
                    $this->data['estados'] = $this->fiscalizacion_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');

                    $this->template->load($this->template_file, 'fiscalizacion/fiscalizacion_edit', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/fiscalizacion');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function delete() {


        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('fiscalizacion/delete')) {
                $ID = $this->uri->segment(3);
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
                    redirect(base_url() . 'index.php/fiscalizacion');
                } else {
                    $data = array(
                        'IDESTADO' => '2'
                    );
                    if ($this->fiscalizacion_model->edit('BANCO', $data, 'IDBANCO', $ID) == TRUE) {
                        //$this->codegen_model->delete('BANCO','IDBANCO',$ID);             
                        $this->template->set('title', 'Fiscalizacion');
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El banco se eliminó correctamente.' . $ID . '</div>');
                        redirect(base_url() . 'index.php/fiscalizacion/');
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/fiscalizacion');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

 function lista(){


    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta'))
							 {
					$this->template->set('title', 'Consulta Cartera No Misional');
										//add style an js files for inputs selects
										$this->load->library('datatables');	
										$this->data['style_sheets']= array(
									                    'css/jquery.dataTables_themeroller.css' => 'screen'
                
												);
										
										$nit=$this->input->post('cedula');
										$id_cartera=$this->input->post('cartera_id');
										$estado=$this->input->post('estado');										
										$id_deuda=$this->input->post('id_deuda');
										$this->data['carteras']  = $this->cnm_cuota_parte_model->detalleCarteras($nit, $id_cartera, $estado, $id_deuda);
										//var_dump($this->data['carteras']);
										//die();

										
										$this->load->view('cnm_cuota_parte/cnm_cuota_parte_datos_consulta', $this->data);
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

    function detalle() {
            if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta')) {
                $this->template->set('title', 'Consulta Cartera No Misional');

				$id_cartera=$this->input->post('id_cartera');
				//echo $id_cartera;
				//die();
				$this->data['carteras']  = $this->cnm_cuota_parte_model->detalleCuotaP($id_cartera)->result_array[0];
				$this->data['pensionados']  = $this->cnm_cuota_parte_model->consultaPensionados($id_cartera);
				//var_dump($this->data['pensionados']);
				//die();
				$consultaadj=$this->data['carteras']['ADJUNTOS'];
										
										
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
					
                $this->template->load($this->template_file, 'cnm_cuota_parte/cnm_cuota_parte_list', $this->data);
            
			
			} else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/carteranomisional');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function visualizar_pagos() {



        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/consulta')) {


                $this->template->set('title', 'Consulta Cartera No Misional');

                $this->load->view('cnm_cuota_parte/cnm_cuota_parte_visualizar_pagos', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/carteranomisional');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    function subir_archivo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('carteranomisional/add')) {
                $this->template->set('title', 'Asignación de Cartera Cuota Parte');

                $this->data['dateini'] = date('Y-m-d H:i:s');
                $this->data['errores'] = array();
                $this->data['numlineas'] = 0;
//                $this->data['negativos'] = array();
//                $this->data['positivos'] = array();

                if (is_dir("uploads/cargarnomisional/temporal")) {
                    $this->deleteDir("uploads/cargarnomisional/temporal", '1');
                }

                $directorios = $this->directorios();
                $path = "uploads/cargarnomisional/temporal/";
                if ($directorios) {
                    foreach (array_combine($_FILES['userfile']['name'], $_FILES['userfile']['tmp_name']) as $file => $filet) {
                        if ($file == '') {
                            array_push($this->data['errores'], $file . " , No se selecciono ningun archivo. ");
                        } else {
                            $this->data['numlineas'] = 0;
                            $extension = trim($this->obtenerExtensionFichero($file));
                            if ($extension == 'txt' || $extension == 'TXT' || $extension == 'csv' || $extension == 'CSV') {
                                $this->cargaarchivo($file, $filet);
                            } else {
                                array_push($this->data['errores'], 'El archivo no es tipo texto');
                            }
                        }
                    }
                }
//
//                if (sizeof($this->data['errores']) > 0) {
//                    $this->crearfichero(0, $file, $filet);
//                    $this->data['negativos'] ++;
//                    $this->data['archivo'] = "errores/" . $file;
//                    $this->data['numlineas'] = 0;
//                } else {
//                    $this->crearfichero(1, $file, $filet);
//                    $this->data['positivos'] ++;
//                    $this->data['archivo'] = "generados/" . $file;
//                }

                $this->template->load($this->template_file, 'cnm_cuota_parte/cnm_cuota_parte_result', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/fiscalizacion');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function deleteDir($path, $tipo = 0) {
        if (!is_dir($path)) {
            throw new InvalidArgumentException("$path is not a directory");
        }
        if (substr($path, strlen($path) - 1, 1) != '/') {
            $path .= '/';
        }
        $dotfiles = glob($path . '.*', GLOB_MARK);
        $files = glob($path . '*', GLOB_MARK);
        $files = array_merge($files, $dotfiles);
        foreach ($files as $file) {
            if (basename($file) == '.' || basename($file) == '..') {
                continue;
            } else if (is_dir($file)) {
                self::deleteDir($file, '1');
            } else {
                unlink($file);
            }
        }
        if ($tipo == 1) {
            rmdir($path);
        }
    }

    private function directorios() {
        $errores = 0;
        $paths = array();
        $paths[] = "uploads/cargarnomisional/";
        $paths[] = "uploads/cargarnomisional/archivos";
        $paths[] = "uploads/cargarnomisional/errores";
        $paths[] = "uploads/cargarnomisional/generados";
        foreach ($paths as $path) {
            if (!is_dir($path)) {
                if (!mkdir($path, 0777, TRUE)) {
                    $errores++;
                }
            }
        }
        if ($errores > 0)
            return false;
        else
            return true;
    }

    private function cargaarchivo($file, $filet) {
        $cod_regional = $this->ion_auth->user()->row()->COD_REGIONAL;
//abre el archivo temporal para su lectura
        $leer = fopen($filet, "r") or exit("Unable to open file!");
        while (!feof($leer)) {
            $linea = trim(fgets($leer));
            $estructura = explode(",", $linea);
            if (sizeof($estructura) > 3) {
                if (is_numeric($estructura[5]) && is_numeric($estructura[6]) && is_numeric($estructura[7])) {
                    $errores = 0;
                } else {
                    array_push($this->data['errores'], 'error de estructura linea ' . $this->data['numlineas']);
                }
            } else {
                $estructura = explode(";", $linea);
                if (is_numeric($estructura[5]) && is_numeric($estructura[6]) && is_numeric($estructura[7])) {
                    $errores = 0;
                } else {
                    array_push($this->data['errores'], 'error de estructura linea ' . $this->data['numlineas']);
                }
            }
			if (sizeof($estructura) > 3) {
				
			}
            $f = $this->cnm_cuota_parte_model->guardarfila($estructura, $file);

            if ($f == FALSE) {
                array_push($this->data['errores'], 'error en la carga de linea ' . $this->data['numlineas']);
            }
            
            $this->data['numlineas'] ++;
        }
        fclose($leer);
//        if (sizeof($this->data['errores']) == 0) {
//            $estructura = $this->validaestructura($file);
//        }
//        if (sizeof($this->data['errores']) == 0) {
//            $registros = $this->validaregistros($file);
//        }
//            if (sizeof($this->data['errores']) == 0) {
//                $planillas = $this->validaplanillas($file);
//            }
    }
    
    function obtenerExtensionFichero($str) {
        return end(explode(".", $str));
    }

}
