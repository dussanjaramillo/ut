<?php

/**
 * Aplicacionmanualdepago (class MY_Controller) :)
 *
 * Aplicación manual de pagos
 *
 * Permite aplicar pagos realizados mediante trasferencias, cajas de compensación y bancos.
 *
 * @author Felipe Camacho [camachogfelipe]
 * @author http://www.cogroupsas.com
 *
 * @package Aplicacionmanualdepago
 */
class Aplicacionmanualdepago extends MY_Controller {

  function __construct() {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->helper(array('form', 'url', 'codegen_helper'));
    $this->load->model('codegen_model', '', TRUE);
    $this->load->model('aplicacionmanualdepago_model');
    $this->data['javascripts'] = array(
        'js/jquery.validationEngine-es.js',
        'js/jquery.validationEngine.js',
        'js/validateForm.js',
    );
    $this->data['style_sheets'] = array(
        'css/validationEngine.jquery.css' => 'screen'
    );
  }

  function index() {
    $this->manage();
  }
  /**
   * Funcion que muestra el primer formulario para determinar cual es el flujo a seguir en el sistema
   *
   * @return none
   * @param none
   */
  function manage() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicacionmanualdepago/manage')) {
        //template data
        $this->template->set('title', 'Aplicación Manual de Pagos');
        $this->data['title'] = 'Aplicación Manual de Pagos';
        $this->data['stitle'] = 'Por favor seleccione la opción deseada';
        $this->data['message'] = $this->session->flashdata('message');
        $this->template->load($this->template_file, 'aplicacionmanualdepago/aplicacionmanualdepago_list', $this->data);
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
  /**
   * Funcion que recibe la opción del primer formulario para determinar si el flujo es estractos o es aplicaión de pago
   *
   * @return none
   * @param none
   */
  function seleccionar_form() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicacionmanualdepago/manage')) {
        $form = $this->input->post('opcion');
        if (!empty($form)) {
          if ($form == "cargar_extractos") {
            //template data
            $this->template->set('title', 'Aplicación Manual de Pagos - Cargar Extractos');
            $this->data['title'] = "CARGUE EXTRACTOS";
            $this->data['stitle'] = 'Fecha Actual ' . date("d/m/Y");
            $this->data['message'] = $this->session->flashdata('message');
            $this->data['bancos'] = $this->aplicacionmanualdepago_model->ObtenerBancos();
            $this->data['regionales'] = $this->aplicacionmanualdepago_model->ObtenerRegionales();
            $this->template->load($this->template_file, 'aplicacionmanualdepago/aplicacionmanualdepago_extractohome', $this->data);
          } elseif ($form == 'AplicarPagos_manual') {
            //template data
						$this->aplicarpago();
            /*$this->template->set('title', 'Aplicación de Pagos');
            $this->data['title'] = 'APLICACIÓN DE PAGOS';
            $this->data['stitle'] = 'Búsqueda de entidad para Aplicación Manual de Pagos';
            $this->data['message'] = $this->session->flashdata('message');
            $this->template->load($this->template_file, 'aplicacionmanualdepago/aplicacionmanualdepago_home', $this->data);*/
          }
        } else {
          redirect(base_url() . 'index.php/aplicacionmanualdepago');
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
	
	/**
   * Funcion que carga las subcartera de la tabla conceptosrecaudo
   *
   * @return json con los resultados de Búsqueda
   * @param $concepto
   */
  function subcarteras() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicacionmanualdepago/manage')) {
        $concepto = $this->input->post('concepto');
        if (empty($concepto)) {
          redirect(base_url() . 'index.php/aplicacionmanualdepago');
        } else {
          $this->aplicacionmanualdepago_model->SetConcepto($concepto);
          return $this->output->set_content_type('appliation/json')->set_output(json_encode($this->aplicacionmanualdepago_model->ObtenerSubconceptos()));
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
	
	/**
   * Funcion que carga las subcartera de la tabla conceptosrecaudo
   *
   * @return json con los resultados de Búsqueda
   * @param $concepto
   */
  function periodo() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicacionmanualdepago/manage')) {
        $concepto = $this->input->post('nit');
        if (empty($concepto)) {
          redirect(base_url() . 'index.php/aplicacionmanualdepago');
        } else {
					$periodo = array(array("VALOR" => date("Y-m"), "DATO" => date("Y")."-".$this->mes(date("m"))));
          return $this->output->set_content_type('appliation/json')->set_output(json_encode($periodo));
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  /**
   * Funcion que busca por nit o por razón social a una empresa pasada en el formulario 2 por post
   *
   * @return json con los resultados de Búsqueda
   * @param string $identificación, $razon
   */
  function buscar() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicacionmanualdepago/manage')) {
        $identificacion = $this->input->get('term');
        if (empty($identificacion)) {
          redirect(base_url() . 'index.php/aplicacionmanualdepago');
        } else {
          $this->aplicacionmanualdepago_model->SetNit($identificacion);
          return $this->output->set_content_type('appliation/json')->set_output(json_encode($this->aplicacionmanualdepago_model->buscar()));
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }

  /**
   * Funcion que carga el formulario para insertar los datos del pago a aplicar
   *
   * @return empty
   * @param string $nit
   */
  function aplicarpago() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicacionmanualdepago/aplicarpago')) {
        //$nit = $this->input->post('idEmpresa');
        /*if (empty($nit)) {
          redirect(base_url() . 'index.php/aplicacionmanualdepago');
        } else {*/
          //template data
          $this->template->set('title', 'Aplicación Manual de Pagos');
          $this->data['title'] = 'APLICACIÓN MANUAL DE PAGOS';
          $this->data['stitle'] = 'Por favor llene los campos para aplicar el pago manualmente';
          $this->data['message'] = $this->session->flashdata('message');
          //$this->aplicacionmanualdepago_model->SetNit($nit);
          //$this->data['empresa'] = $this->aplicacionmanualdepago_model->ObtenerEmpresa();
          $this->data['bancos'] = $this->aplicacionmanualdepago_model->ObtenerBancos();
          $this->data['conceptos'] = $this->aplicacionmanualdepago_model->ObtenerConceptos();
          $this->data['regionales'] = $this->aplicacionmanualdepago_model->ObtenerRegionales();
          $this->data['formaspago'] = $this->aplicacionmanualdepago_model->ObtenerFormasPago();
          $this->template->load($this->template_file, 'aplicacionmanualdepago/aplicarpago', $this->data);
        //}
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
			echo "sin logueo";
      //redirect(base_url() . 'index.php/auth/login');
    }
  }

  /**
   * Funcion que trae los pagos de acuerdo al concepto seleccionado en aplicarpago
   *
   * @return json con los resultados
   * @param string $idConcepto post
   */
  function traerpagos() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicacionmanualdepago/traerpagos')) {
        $concepto = $this->input->post('concepto');
        $nit = $this->input->post("nit");
        $op = $this->input->post("op");
        if (empty($concepto) and empty($nit) and !empty($op)) {
          redirect(base_url() . 'index.php/aplicacionmanualdepago');
        } else {
          $this->aplicacionmanualdepago_model->SetNit($nit);
          $this->aplicacionmanualdepago_model->SetConcepto($concepto);
          $this->aplicacionmanualdepago_model->SetRazon($op);
          $result['datos'] = $this->aplicacionmanualdepago_model->ObtenerPagosPendientes();
          if(!empty($result['datos'])) :
            $result['tipo'] = 2;
          else :
            $result['tipo'] = 1;
          endif;            
          return $this->output->set_content_type('application/json')->set_output(json_encode($result));
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
  /**
   * Funcion que valida si el periodo del extracto existe
   *
   * @return true or false
   * @param string $banco, $regional, $periodo
   */
  function validarperiodo() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicacionmanualdepago/traerpagos')) {
        $this->form_validation->set_rules('banco', 'Banco', 'required');
        $this->form_validation->set_rules('regional', 'Regional', 'required');
        $this->form_validation->set_rules('periodo', 'Periodo de pago', 'required');
        if($this->form_validation->run() == FALSE) {
          return $this->output->set_content_type('application/json')->set_output(json_encode(array("datos" => "error")));
        }
        else {
          $datos['COD_ENTIDAD'] = $this->input->post('banco');
          $datos['COD_REGIONAL'] = $this->input->post('regional');
          $datos['PERIODO_EXTRACTO'] = $this->input->post('periodo');
          $dato = $this->aplicacionmanualdepago_model->ValidarExtracto($datos);
          return $this->output->set_content_type('application/json')->set_output(json_encode($dato));
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
  /**
   * Funcion general de codeigniter para subir archivos, en este caso solo imágenes
   *
   * @return none
   * @param string $imagen
   */
  function addpago(){
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicacionmanualdepago/traerpagos')) {
        $this->form_validation->set_rules("fecha_pago", "Fecha de pago", "required");
        $this->form_validation->set_rules("regional", "Fecha de pago", "required");
        $concepto = $this->form_validation->set_rules("concepto", "Concepto", "required");
				$sconcepto = $this->form_validation->set_rules("subconcepto", "Subconcepto", "required");
        $this->form_validation->set_rules("banco_recaudo", "Banco de recaudo", "required");
        $this->form_validation->set_rules("valor_recibido", "Valor recibido", "required");
        $this->form_validation->set_rules("documento", "Fecha de pago", "required");
        $this->form_validation->set_rules("forma_pago", "Forma de pago", "required");
        $nit = $this->input->post("nit");
        $this->form_validation->set_rules("fecha_actual", "Fecha de pago", "required");
        
        if($this->form_validation->run() == TRUE) {
					set_time_limit(120);
					ini_set('memory_limit', '256M');
          //template data
          $this->template->set('title', 'Aplicación Manual de Pagos');
          $this->data['title'] = 'APLICACIÓN MANUAL DE PAGOS';
          $this->data['stitle'] = '';
          $this->data['message'] = $this->session->flashdata('message');
          $this->aplicacionmanualdepago_model->SetNit($nit);
          $this->data['empresa'] = $this->aplicacionmanualdepago_model->ObtenerEmpresa();
          $this->data['bancos'] = $this->aplicacionmanualdepago_model->ObtenerBancos();
          $this->data['conceptos'] = $this->aplicacionmanualdepago_model->ObtenerConceptos();
          $this->data['regionales'] = $this->aplicacionmanualdepago_model->ObtenerRegionales();
          $this->data['formaspago'] = $this->aplicacionmanualdepago_model->ObtenerFormasPago();
          //DATOS TABLA PAGOSRECIBIDOS
          $datos['NITEMPRESA'] = $this->input->post('nit');
          $datos['FECHA_TRANSACCION'] = $this->input->post('fecha_actual');
					$datos['FECHA_TRANSACCION'] = date("Y-m-d h:m:i", strtotime($datos['FECHA_TRANSACCION']));
          $datos['FECHA_PAGO'] = $this->input->post('fecha_pago');
          $datos['COD_REGIONAL'] = $this->input->post('regional');
					$datos['COD_CONCEPTO'] = $this->input->post('concepto');
					$datos['COD_SUBCONCEPTO'] = $this->input->post('subconcepto');
          $datos['COD_ENTIDAD'] = $this->input->post('banco_recaudo');
          $datos['VALOR_PAGADO'] = $this->input->post('valor_recibido');
          $datos['NUM_DOCUMENTO'] = $this->input->post('documento');
          $datos['COD_FORMAPAGO'] = $this->input->post('forma_pago');
					$datos['PROCEDENCIA'] = "MANUAL";
					$this->load->model("aplicacionautomaticadepago_model");
					$aplica = new Aplicacionautomaticadepago_model();
					if($aplica->valida_documento($datos['NUM_DOCUMENTO']) == false) :
						$ejecutar = true;
						switch($datos['COD_SUBCONCEPTO']) :
							case '21' :
								$datos['PERIODO_PAGADO'] = $this->input->post('fecha_periodo');
								$datos['OPERADOR'] = $this->input->post('operador');
								$datos['APLICADO'] = 1;
								break;
							case '17' :
							case '19' :
							case '59' :
								$datos['PERIODO_PAGADO'] = $this->input->post('fecha_periodo');
								$datos['DISTRIBUCION_INTERES_MORA'] = $this->input->post('intereses_mora');
								$datos['APLICADO'] = 1;
								break;
							case '62' :
								$datos['PERIODO_PAGADO'] = $this->input->post('fecha_periodo');
								$datos['PERIODO_PAGADO'] = substr($datos['PERIODO_PAGADO'], 0, -3);
								$datos['NRO_LICENCIA_CONTRATO'] = $this->input->post('nro_contrato');
								$datos['TIPO_FIC'] = $this->input->post('tipo_fic');
								$datos['DISTRIBUCION_INTERES_MORA'] = $this->input->post('intereses_mora');
								$datos['NRO_TRABAJADORES_PERIODO'] = $this->input->post('nro_trabajadores');
								$datos['NRO_TRABAJADORES_ADICIONAR'] = $this->input->post('nro_trabajadores_adicion');
								$datos['NOMBRE_OBRA'] = $this->input->post('nombre_obra');
								$datos['CIUDAD_OBRA'] = $this->input->post('ciudad_obra');
								$datos['FECHA_INICIO_OBRA'] = $this->input->post('fecha_inicio');
								$datos['FECHA_FIN_OBRA'] = $this->input->post('fecha_fin');
								$datos['COSTO_TOTAL_OBRA_TODO_COSTO'] = $this->input->post('valor_todo_costo');
								$datos['COSTO_TOTAL_MANO_DE_OBRA'] = $this->input->post('valor_mano_obra');
								$datos['APLICADO'] = 1;
								break;
							case '77' :
							case '102':
								$datos['CONCEPTO_PAGO'] = $this->input->post('concepto_pago');
								$datos['APLICADO'] = 1;
								break;
							case '66' :
								$datos['TIPO_CARNE'] = $this->input->post('tipo_carne');
								$datos['APLICADO'] = 1;
								break;
							case '74' :
								$datos['PERIODO_PAGADO'] = $this->input->post('anio');
								$datos['NRO_ORDEN_VIAJE'] = $this->input->post('numero_orden_viaje');
								$datos['APLICADO'] = 1;
								break;
							//Pago de cartera MISIONAL
							case '80' :
							case '81' :
							case '82' :
							case '83' :
								$datos1 = $this->input->post();
								//DATOS PARA APLICAR PAGO
								//echo "<pre>";print_r($datos);print_r($datos1);echo "</pre>";
								$text = array();
								foreach ($datos1['deuda'] as $k=>$pago) :
									$datos['NRO_REFERENCIA'] = $k;
									$datos['COD_FISCALIZACION'] = $pago['cod_fiscalizacion'];
									$datos['SALDO_DEUDA'] = $pago['saldo_deuda'];
									//Si es un acuerdo de pago entra en el if
									if(isset($pago['cuota'])) :
										$cuota = NULL;
										foreach($pago['cuota'] as $key=>$value) :
											$cuota['NRO_ACUERDOPAGO'] = $value['acuerdo'];
											$dis = $value['saldo_aplicado'];
											$dis -= $value['interes_mora'];
											$datos['DISTRIBUCION_INTERES_MORA'] = $value['interes_mora'];
											$cuota["PROYACUPAG_ESTADO"] = 0;
											if($dis > 0) :
												$datos['DISTRIBUCION_CAPITAL'] = 
												ceil(($dis)*($value['capital']/(($value['capital']+$value['intacuerdo']+$value['intcorriente']))));
												$cuota["PROYACUPAG_SALDOCAPITAL"] = $value['capital'] - $datos['DISTRIBUCION_CAPITAL'];
												$tmpdatos['DISTRIBUCION_INTERES1'] = 
												floor(($dis)*($value['intcorriente']/(($value['capital']+$value['intacuerdo']+$value['intcorriente']))));
												$cuota["PROYACUPAG_SALDO_INTCORRIENTE"] = $value['intcorriente'] - $tmpdatos['DISTRIBUCION_INTERES1'];
												$tmpdatos['DISTRIBUCION_INTERES2'] = 
												floor(($dis)*($value['intacuerdo']/(($value['capital']+$value['intacuerdo']+$value['intcorriente']))));
												$cuota["PROYACUPAG_SALDO_INTACUERDO"] = $value['intacuerdo'] - $tmpdatos['DISTRIBUCION_INTERES2'];
												$datos['DISTRIBUCION_INTERES'] = $tmpdatos['DISTRIBUCION_INTERES1'] + $tmpdatos['DISTRIBUCION_INTERES2'];
											else:
												$datos['DISTRIBUCION_INTERES'] = $datos['DISTRIBUCION_CAPITAL'] = 0;
											endif;
											$cuota["PROYACUPAG_SALDO_CUOTA"] =	$cuota["PROYACUPAG_SALDO_INTACUERDO"] + 
																													$cuota["PROYACUPAG_SALDO_INTCORRIENTE"] + 
																													$cuota["PROYACUPAG_SALDOCAPITAL"];
											$datos['VALOR_PAGADO'] = $value['saldo_aplicado'];
											$datos['SALDO_DEUDA'] -= ($datos['VALOR_PAGADO'] - $datos['DISTRIBUCION_INTERES_MORA']);
											$datos['SALDO_CAPITAL'] = $pago['saldo_capital'] - $datos['DISTRIBUCION_CAPITAL'];
											$datos['SALDO_INTERES'] = $pago['saldo_interes'] - $datos['DISTRIBUCION_INTERES'];
											$datos['NRO_CUOTA'] = $cuota["CUOTA"] = $key;
											$datos['VALOR_ADEUDADO'] = $pago['adeudado'];
											$datos['APLICADO'] = 1;
											if($cuota["PROYACUPAG_SALDO_CUOTA"] <= 0) :
												$cuota["PROYACUPAG_ESTADO"] = 1;
											endif;
											//echo "<pre>";print_r($datos);print_r($pago);print_r($cuota);echo "</pre>";exit;
											$aplicado = $aplica->aplicar_pago($datos);
											if(is_array($aplicado)) :
												$class = "error";
												$text[] = "EL PAGO DE LA CUOTA NRO. " . $key . " DEL ACUERDO NRO. " . $cuota["NRO_ACUERDOPAGO"] . "  DE LA OBLIGACIÓN NRO. " . $k . " presento errores.<br>" .$aplicado[1];
											else :
												$aplica->aplicar_acuerdo($cuota);
												$class = "info";
												$text[] = "EL PAGO DE LA CUOTA NRO. " . $key . " DEL ACUERDO NRO. " . $cuota["NRO_ACUERDOPAGO"] . " DE LA OBLIGACIÓN NRO. " . $k . " SE REGISTRO CORRECTAMENTE<BR>EL PAGO FUE APLICADO AUTOMATICAMENTE.";
											endif;
										endforeach;
									else :
										if($pago['valor_aplicar'] == $pago['adeudado']) :
											$datos['DISTRIBUCION_CAPITAL'] = $pago['saldo_capital'];
											$datos['DISTRIBUCION_INTERES'] = $pago['saldo_interes'];
											$datos['DISTRIBUCION_INTERES_MORA'] = $pago['interes_mora'];
										elseif($pago['valor_aplicar'] <= $pago['interes_mora']) :
											$datos['DISTRIBUCION_CAPITAL'] = $datos['DISTRIBUCION_INTERES'] = 0;
											$datos['DISTRIBUCION_INTERES_MORA'] = $pago['interes_mora'];
										else:
											$datos['DISTRIBUCION_CAPITAL'] = 
											ceil(($pago['valor_aplicar']-$pago['interes_mora'])*($pago['capital']/(($pago['capital']+$pago['interes']))));
											$datos['DISTRIBUCION_INTERES'] = 
											floor(($pago['valor_aplicar']-$pago['interes_mora'])*($pago['interes']/(($pago['capital']+$pago['interes']))));
											$datos['DISTRIBUCION_INTERES_MORA'] = $pago['interes_mora'];
										endif;
										$datos['VALOR_PAGADO'] = $datos['DISTRIBUCION_CAPITAL'] + $datos['DISTRIBUCION_INTERES'] + $datos['DISTRIBUCION_INTERES_MORA'];
										$datos['SALDO_CAPITAL'] = $pago['saldo_capital'] - $datos['DISTRIBUCION_CAPITAL'];
										$datos['SALDO_INTERES'] = $pago['saldo_interes'] - $datos['DISTRIBUCION_INTERES'];
										$datos['VALOR_ADEUDADO'] = $pago['adeudado'];
										$datos['SALDO_DEUDA'] -= ($datos['VALOR_PAGADO'] - $pago['interes_mora']);
										$datos['APLICADO'] = 1;
										//echo "<pre>";print_r($pago);echo "</pre>";
										//echo "<pre>";print_r($datos);echo "</pre>";exit;
										$aplicado = $aplica->aplicar_pago($datos);
										if(is_array($aplicado)) :
											$class = "error";
											$text[] = "EL PAGO DE LA OBLIGACIÓN NRO. " . $k . " presento errores.<br>" .$aplicado[1];
										else :
											//$aplica->actualiza_saldo();
											$class = "info";
											$text[] = "EL PAGO DE LA OBLIGACIÓN NRO. " . $k . " SE REGISTRO CORRECTAMENTE<BR>EL PAGO FUE APLICADO AUTOMATICAMENTE.";
										endif;
									endif;
								endforeach;
								$text = implode("<br><br>", $text);//exit($text);
								$ejecutar = false;
								//echo "<pre>";print_r($datos1);echo "</pre>";
								//exit;
								break;
							//Pago de cartera NO MISIONAL
							case '84' :
							case '85' :
							case '86' :
							case '87' :
							case '88' :
							case '89' :
							case '90' :
							case '91' :
							case '92' :
							case '93' :
								$datos1 = $this->input->post();
								//DATOS PARA APLICAR PAGO
								//echo "<pre>";print_r($datos1);echo "</pre>";exit;
								$text = $datosNm = array();
								foreach ($datos1['deuda'] as $k=>$pago) :
									$datos['NRO_REFERENCIA'] = $k;
									foreach($pago as $key=>$value) :
										$datosNm['NO_CUOTA'] = $datos['NRO_CUOTA'] = $key;
										$datosNm['ID_DEUDA_E'] = $k;
										$datosNm['INTERES_MORA_GEN'] = $value['interes_mora'];
										if(($value['saldo_aplicado']-$value['interes_mora']) > $value['interes'] || $value['interes'] == 0) :
											$datosNm['SALDO_INTERES_C'] = 0;
										elseif(($value['saldo_aplicado']-$value['interes_mora']) < $value['interes']) :
											$datosNm['SALDO_INTERES_C'] = $value['interes'] - ($value['saldo_aplicado'] - $value['interes_mora']);
										endif;
										$datosNm['SALDO_AMORTIZACION'] = ($value['saldo_aplicado'] - $value['interes_mora'] - $value['interes']);
										$datosNm['SALDO_AMORTIZACION'] = $value['capital'] - $datosNm['SALDO_AMORTIZACION'];
										$datosNm['SALDO_CUOTA'] = $datosNm['SALDO_AMORTIZACION'] + $datosNm['SALDO_INTERES_C'];
										$datosNm['COD_SUBCONCEPTO'] = $datos['COD_SUBCONCEPTO'];
										$datos['DISTRIBUCION_CAPITAL'] = ($value['saldo_aplicado'] - $value['interes_mora'] - $value['interes']);
										$datos['DISTRIBUCION_INTERES'] = $value['interes'] - $datosNm['SALDO_INTERES_C'];
										$datos['DISTRIBUCION_INTERES_MORA'] = $value['interes_mora'];
										$datos['VALOR_PAGADO'] = $datos['DISTRIBUCION_CAPITAL'] + $datos['DISTRIBUCION_INTERES'] + $datos['DISTRIBUCION_INTERES_MORA'];
										$datos['VALOR_ADEUDADO'] = $value['adeudado'];
										$datos['APLICADO'] = 1;
										//echo "<pre>";print_r($datos);print_r($datosNm);echo "</pre>";
										$aplicado = $aplica->aplicar_pago($datos);
										if(is_array($aplicado)) :
											$class = "error";
											$text[] = "EL PAGO DE LA CUOTA NRO. " . $key . "  DE LA OBLIGACIÓN NRO. " . $k . " presento errores.<br>" .$aplicado[1];
										else :
											$aplica->AplicarNoMisional($datosNm);
											$class = "info";
											$text[] = "EL PAGO DE LA CUOTA NRO. " . $key . "  DE LA OBLIGACIÓN NRO. " . $k . " SE REGISTRO CORRECTAMENTE<BR>EL PAGO FUE APLICADO AUTOMATICAMENTE.";
										endif;
									endforeach;
								endforeach;
								$text = implode("<br><br>", $text);
								$ejecutar = false;
								//echo "<pre>";print_r($datos);print_r($$datosNm);print_r($datos1);echo "</pre>";
								//exit;
								break;
							default:
								break;
						endswitch;
						/*echo "<pre>";
						print_r($datos);
						$datos2 = $this->input->post();
						print_r($datos2);
						echo "</pre>";
						exit();*/
						//DATOS PARA APLICAR PAGO
						//echo "<pre>";print_r($text);exit("</pre>");
					else :
						$ejecutar = false;
						$class = "error";
						$text = "El Número de documento ya fue aplicado.";
					endif;
					if($ejecutar == true) :
						$aplicado = $aplica->aplicar_pago($datos);
						if(is_array($aplicado)) :
							$class = "error";
							$text = $aplicado[1];
						else :
							$class = "info";
							$text = "EL PAGO SE REGISTRO CORRECTAMENTE<BR>EL PAGO FUE APLICADO AUTOMATICAMENTE.";
						endif;
					endif;
					if (defined('ENVIRONMENT')) :
						switch (ENVIRONMENT) :
							case 'development':
								$this->session->set_flashdata('message', '<div class="alert alert-'.$class.'"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$text.'</div>');
        				redirect(base_url() . 'index.php/aplicacionmanualdepago/manage');
								break;
							case 'testing':
							case 'production':
							default:
								$this->session->set_flashdata('message', '<div class="alert alert-'.$class.'"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$text.'</div>');
        				redirect(base_url() . 'index.php/aplicacionmanualdepago/manage');
								break;
						endswitch;
					endif;
        }
        else {
					$text = validate_errors();
					$class = "error";
					$this->session->set_flashdata('message', '<div class="alert alert-'.$class.'"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$text.'</div>');
        	redirect(base_url() . 'index.php/aplicacionmanualdepago/manage');
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
  /**
   * Funcion general de codeigniter para subir archivos, en este caso solo imágenes
   *
   * @return none
   * @param string $imagen
   */
  function subir_extracto() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicacionmanualdepago/traerpagos')) {
        $this->form_validation->set_rules('banco_extracto', 'Banco', 'required');
        $this->form_validation->set_rules('regional', 'Regional', 'required');
        $this->form_validation->set_rules('periodo_pago', 'Periodo de pago', 'required');
        $this->form_validation->set_rules('cant_registros', 'Cantidad de registros', 'required|integer');
        $this->form_validation->set_rules('total_extracto', 'Total extracto', 'required|regex_match[/^-?[0-9]*([,\.][0-9]*)?$/]');
        $file = $this->do_upload();
        if(isset($file['error']) || $this->form_validation->run() == FALSE) {
          $this->template->set('title', 'Aplicación de Pagos');
          $this->data['title'] = 'CARGUE EXTRACTOS';
          $this->data['stitle'] = 'Fecha Actual: '.date("d-m-Y");
          $this->data['class'] = 'error';
          $this->data['message'] = $this->session->flashdata('message');
          $this->data['mensaje'] = validation_errors();
          $this->data['error'] = true;
          $this->data['file_error'] = $file;
          $this->template->load($this->template_file, 'aplicacionmanualdepago/respuestaextracto', $this->data);
        }
        else {
          $datos['COD_ENTIDAD'] = $this->input->post('banco_extracto');
          $datos['COD_REGIONAL'] = $this->input->post('regional');
          $datos['PERIODO_EXTRACTO'] = $this->input->post('periodo_pago');
          $datos['CANT_REGISTROS'] = $this->input->post('cant_registros');
          $datos['TOTAL_EXTRACTO'] = number_format($this->input->post('total_extracto'), 0, ",", "");
					$datos['NUMERO_CUENTA'] = $this->input->post('cuenta_banco');
          $datos['NOMBRE_ARCHIVO'] = $file['upload_data']['file_name'];
          $replace = $this->input->post('replace_periodo');
          $extracto_id = $this->input->post('extracto_id');
          if($replace == 1 and !empty($extracto_id)) :
            $datos['COD_EXTRACTO'] = $extracto_id;
          endif;
          $this->aplicacionmanualdepago_model->extracto($datos);
          $this->template->set('title', 'Aplicación de Pagos');
          $this->data['title'] = 'CARGUE EXTRACTOS';
          $this->data['class'] = 'success';
          $this->data['stitle'] = 'Fecha Actual: '.date("d-m-Y");
          $this->data['mensaje'] = "Se ha adicionado exitosamente el extracto de la entidad, y la información del mismo";
          $this->data['error'] = false;
          $this->data['file'] = $file;
          $this->data['message'] = $this->session->flashdata('message');
          $this->template->load($this->template_file, 'aplicacionmanualdepago/respuestaextracto', $this->data);
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
  /**
   * Funcion general de codeigniter para subir archivos, en este caso solo imágenes
   *
   * @return none
   * @param string $imagen
   */
  private function do_upload() {
    $config['upload_path'] = './uploads/aplicacionmanualdepagoextractos/';
    $config['allowed_types'] = 'gif|jpg|png';
    $config['max_size'] = '2048';
    $config['encrypt_name'] = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload()) {
      return $error = array('error' => $this->upload->display_errors());
    } else {
      return $data = array('upload_data' => $this->upload->data());
    }
  }
  
  public function mes($mes) {
    switch($mes) :
      case "01" : return "Ene"; break;
      case "02" : return "Feb"; break;
      case "03" : return "Mar"; break;
      case "04" : return "Abr"; break;
      case "05" : return "May"; break;
      case "06" : return "Jun"; break;
      case "07" : return "Jul"; break;
      case "08" : return "Ago"; break;
      case "09" : return "Sep"; break;
      case "10" : return "Oct"; break;
      case "11" : return "Nov"; break;
      case "12" : return "Dic"; break;
    endswitch;
  }
	
	function cargar_template() {
		if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicacionmanualdepago/cargar_template')) {
				//template data
				$concepto = $this->input->post("concepto");
				$sconcepto = $this->input->post("subconcepto");
				$nit = $this->input->post("nit");
				if(empty($concepto) and empty($sconcepto)) :
					$error = "Por favor seleccione un concepto y un subconcepto";
				elseif(!empty($concepto) and empty($sconcepto)) :
					$error = "Por favor seleccione un subconcepto";	
				endif;
				if(isset($error)) :
					echo '<div class="alert alert-danger">' .
							 '<button type="button" class="close" data-dismiss="alert">&times;</button>' .
							 $error .
							 '</div>';
				else :
					switch($sconcepto) :
						case '21' :
							$this->data['titulo'] = "MISIONAL";
							$this->data['operador'] = $this->aplicacionmanualdepago_model->ObtenerOperador();
							$this->load->view('aplicacionmanualdepago/pagos/AporteOrdinarioPila', $this->data);
							break;
						case '17' :
						case '19' :
						case '59' :
							$this->data['titulo'] = "MISIONAL";
							$this->load->view('aplicacionmanualdepago/pagos/AporteOrdinarioBase', $this->data);
							break;
						case '62' :
							$this->load->view('aplicacionmanualdepago/pagos/AporteOrdinarioFicPresuntivo', $this->data);
							break;
						case '77' :
						case '102':
							$this->load->view('aplicacionmanualdepago/pagos/TitulosJudicialesPagosEspeciales', $this->data);
							break;
						case '66' :
							$this->load->view('aplicacionmanualdepago/pagos/Carne', $this->data);
							break;
						case '74' :
							$this->data['titulo'] = "MISIONAL";
							$this->load->view('aplicacionmanualdepago/pagos/ReintegroViaticos', $this->data);
							break;
						case '80' :
						case '81' :
						case '82' :
						case '83' :
							$this->aplicacionmanualdepago_model->SetConcepto($concepto);
							$this->aplicacionmanualdepago_model->SetSubConcepto($sconcepto);
							$this->aplicacionmanualdepago_model->SetNit($nit);
							$this->data['valor_pagado'] = $this->input->post("valor_pago");
							$this->data['fecha_pago'] = $this->input->post("fecha_pago");
							$this->load->model("aplicacionautomaticadepago_model", "", TRUE);
							$this->aplicacionmanualdepago_model->SetFechaPago($this->data['fecha_pago']);
							$this->data['cartera'] = $this->aplicacionmanualdepago_model->ObtenerPagosPendientes();
							//echo "<pre>";print_r($this->data['cartera']);exit("<pre>");
							$this->data['titulo'] = "MISIONAL";
							$this->load->view('aplicacionmanualdepago/pagos/Cartera', $this->data);
							break;
						case '84' :
						case '85' :
						case '86' :
						case '87' :
						case '88' :
						case '89' :
						case '90' :
						case '91' :
						case '92' :
						case '93' :
							$this->data['valor_pagado'] = $this->input->post("valor_pago");
							$this->data['fecha_pago'] = $this->input->post("fecha_pago");
							$this->aplicacionmanualdepago_model->SetConcepto($this->data['fecha_pago']);
							$this->aplicacionmanualdepago_model->SetSubConcepto($sconcepto);
							$this->aplicacionmanualdepago_model->SetNit($nit);
							$this->load->model("aplicacionautomaticadepago_model", "", TRUE);
							$this->data['cartera'] = $this->aplicacionmanualdepago_model->ObtenerPagosPendientesNoMisional();
							//echo "<pre>";print_r($this->data['cartera']);exit("<pre>");
							$this->data['titulo'] = "NO MISIONAL";
							$this->load->view('aplicacionmanualdepago/pagos/CarteraNoMisional', $this->data);
							break;
						default:
							break;
					endswitch;
        endif;
			} else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
	}
	
	public function TraerCuentasBancarias() {
		if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicacionmanualdepago/manage')) {
        $idbanco = $this->input->post('banco');
        if (empty($idbanco)) {
          return false;//redirect(base_url() . 'index.php/aplicacionmanualdepago');
        } else {
          return $this->output->set_content_type('appliation/json')->set_output(json_encode($this->aplicacionmanualdepago_model->TrearCuentasBancarias($idbanco)));
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
	}
}

/* End of file aplicaciondepago.php */
/* Location: ./system/application/controllers/aplicaciondepago.php */