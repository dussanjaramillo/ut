<?php
/**
 * Reclasificacion_pagos (class MY_Controller) :)
 *
 * Reclasificación de pagos
 *
 * Permite reclasificar pagos manualmente.
 * Depende de aplicación automatica de pago para aplicar el pago.
 *
 * @author Felipe Camacho [camachogfelipe]
 * @author http://www.cogroupsas.com
 *
 * @package Reclasificacion_pagos
 */
class Reclasificacion_pagos extends MY_Controller {

  function __construct() {

    parent::__construct();
    $this->load->library('form_validation');
    $this->load->helper(array('form', 'url', 'codegen_helper'));
    $this->load->model('codegen_model', '', TRUE);
    $this->load->model('reclasificacion_pagos_model');
    $this->load->model('consultarpagos_model');
    $this->data['javascripts'] = array(
        'js/jquery.dataTables.min.js',
        'js/jquery.dataTables.defaults.js',
        'js/jquery.validationEngine-es.js',
        'js/jquery.validationEngine.js',
        'js/validateForm.js'
    );
    $this->data['style_sheets'] = array(
        'css/jquery.dataTables_themeroller.css' => 'screen',
        'css/validationEngine.jquery.css' => 'screen'
    );
  }

  function index() {
    $this->manage();
  }
  /**
   * Funcion que muestra el primer formulario para determinar los filtros de busqueda de los pagos
   *
   * @return none
   * @param none
   */
  function manage() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('reclasificacion_pagos/index')) {
        //template data
        $this->template->set('title', 'Identificar y Reclasificar Pagos');
        $this->data['title'] = 'RECLASIFICACIÓN INGRESOS';
        $this->data['stitle'] = '';
        $this->data['regionales'] = $this->reclasificacion_pagos_model->get_regionales();
        $this->data['conceptos'] = $this->reclasificacion_pagos_model->get_conceptosfiscalizacion();
        $this->data['message'] = $this->session->flashdata('message');
        $this->template->load($this->template_file, 'reclasificacionpagos/home', $this->data);
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
  /**
   * Funcion que busca por nit o por raz贸n social a una empresa pasada en el formulario 2 por post
   *
   * @return json con los resultados de busqueda
   * @param string $identificaci贸n, $razon
   */
  function buscar() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('reclasificacion_pagos/buscar')) {
        $nit = $this->input->post("nit");
        $concepto = $this->input->post('concepto');
				if($concepto == "-1") $concepto = NULL;
        $resolucion = $this->input->post('resolucion');
				$periodo_inicio = $this->data['periodo_inicio'] = $this->input->post('periodo_inicio');
				$periodo_fin = $this->data['periodo_fin'] = $this->input->post('periodo_fin');
        if (empty($nit) || empty($periodo_inicio) || empty($periodo_fin)) {
          redirect(base_url() . 'index.php/reclasificacion_pagos');
        } else {
          if(!empty($nit)) $this->reclasificacion_pagos_model->set_nit($nit);
          if(!empty($concepto) and $concepto != "TODOS") $this->reclasificacion_pagos_model->set_concepto($concepto);
          $this->reclasificacion_pagos_model->set_periodo_inicio($periodo_inicio);
          $this->reclasificacion_pagos_model->set_periodo_fin($periodo_fin);          
          $this->data['empresa'] = $this->reclasificacion_pagos_model->get_empresa();
          $this->data['obligaciones'] = $this->reclasificacion_pagos_model->get_obligaciones();
          if(!empty($this->data['obligaciones'])) :
            $this->reclasificacion_pagos_model->set_estado(0);
						$conceptop = $this->input->post('conceptop');
						if($conceptop == "-1") $conceptop = NULL;
						$concepto2 = $this->input->post('concepto2');
						if($concepto2 == "-1") $concepto2 = NULL;
						$regional = $this->input->post('regional');
						if(!empty($conceptop) and $conceptop != "TODOS") $this->reclasificacion_pagos_model->set_concepto($conceptop);
						if(!empty($concepto2) and $concepto2 != "TODOS") $this->reclasificacion_pagos_model->set_subconcepto($concepto2);
						if(!empty($regional)) $this->reclasificacion_pagos_model->set_regional($regional);
            $this->data['pagos'] = $this->reclasificacion_pagos_model->get_ingresos();
            $this->data['conceptos'] = $this->reclasificacion_pagos_model->get_conceptosfiscalizacion();
            $this->data['regionales'] = $this->reclasificacion_pagos_model->get_regionales();
            $this->data['message'] = $this->session->flashdata('message');
          else :
            $this->data['message'] = "Este número de identificación no tiene obligaciones pendientes.";
          endif;
          $this->template->set('title', 'RECLASIFICACIÓN INGRESOS');
          $this->data['title'] = 'RECLASIFICACIÓN INGRESOS';
          $this->template->load($this->template_file, 'reclasificacionpagos/resultadobusqueda', $this->data);
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
   * Funcion que trae los nits del campo autocompletar
   *
   * @return json con los resultados
   * @param string $idConcepto
   */
  function traernits() {
    if ($this->ion_auth->logged_in()) {
      //if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarpagos/traernits')) {
        $nit = $this->input->get('term');
        $nitorg = $this->input->get('nit');
        if (empty($nit)) {
          return false;
        } else {
          $this->reclasificacion_pagos_model->set_nit($nit);
          if(!empty($nitorg)) $this->reclasificacion_pagos_model->set_nitorg($nitorg);
          return $this->output->set_content_type('application/json')->set_output(json_encode($this->reclasificacion_pagos_model->buscarnits()));
        }
      /*} else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta 谩rea.</div>');
        redirect(base_url() . 'index.php/inicio');
      }*/
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
  /**
   * Funcion que trae los periodos de un nit
   *
   * @return json con los resultados
   * @param string $idConcepto
   */
  function traerperiodos() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('reclasificacion_pagos/traerperiodos')) {
        $nit = $this->input->post('nit');
        $concepto = $this->input->post('concepto');
        if (empty($concepto) || empty($nit)) {
          echo '<option value="">Seleccione el periodo</option>'."\n";
        } else {
          $this->reclasificacion_pagos_model->set_concepto($concepto);
          $this->reclasificacion_pagos_model->set_nit($nit);
          $result['datos'] = $this->consultarpagos_model->get_conceptos();
          if(!empty($result)) :
            echo '<option value="">Seleccione el sub concepto...</option>'."\n";
            foreach($result['datos'] as $concepto) :
              echo '<option value="'.$concepto['COD_CONCEPTO_RECAUDO'].'">'.$concepto['NOMBRE_CONCEPTO'].'</option>'."\n";
            endforeach;
            echo '<option value="TODOS">TODOS</option>'."\n";
          endif;
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
   * Funcion que busca pagos de otros contribuyentes
   *
   * @return pagos_model
   * @param int $nit
   */
  
  public function otroscontribuyentes() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('reclasificacion_pagos/otroscontribuyentes')) {
        $nit = $this->input->post('nit');
        $nitorg = $this->input->post('nitorg');
        if( empty($nit) ) :
          $datos = array('status' => 'error', 'data' => 'Los numeros de Identificación estan vacíos');
          return $this->output->set_content_type('application/json')->set_output(json_encode($datos));
        elseif($nit == $nitorg) :
          $datos = array('status' => 'error', 'data' => 'Los numeros de Identificación son iguales');
          return $this->output->set_content_type('application/json')->set_output(json_encode($datos));
        else :
          $this->reclasificacion_pagos_model->set_nit($nit);
          $documento = $this->input->post('documento');
          if(!empty($documento))
            $this->reclasificacion_pagos_model->set_documento($documento);
          $fecha_pago = $this->input->post('fecha_pago');
          if(!empty($fecha_pago))
            $this->reclasificacion_pagos_model->set_fecha_pago($fecha_pago);
          $estado = $this->input->post('estado');
          if(!empty($estado))
            $this->reclasificacion_pagos_model->set_estado($estado);
          $regional = $this->input->post('regional');
          if(!empty($regional))
            $this->reclasificacion_pagos_model->set_regional($regional);
          $valor = $this->input->post('valor');
          if(!empty($valor))
            $this->reclasificacion_pagos_model->set_valor($valor);
          $datos = $this->reclasificacion_pagos_model->get_ingresos();
          $json = array();
          if(is_null($datos)) :
            $json = array("status" => "error", "data" => "No hay pagos relacionados con los datos solicitados.");
          else :
            $tmp = $tmp1 = $cols = array();
            foreach($datos as $dato) :
              $cols = array_keys($dato);//print_r($dato);
							$tmp1 = NULL;
              foreach($dato as $key=>$dat) :
                if($key == "COD_PAGO") :
									$tmp1[] = '<input type="checkbox" name="pago[]" class="pago" data-value="' . $dato['VALOR_PAGADO'] . '" data-id="' . $dato['COD_PAGO'] . '" />';
                else :
                  $tmp1[] = $dat;
                endif;
              endforeach;
              $tmp[] = $tmp1;
            endforeach;//print_r($tmp);
            $json = array("status" => true, "aaData" => $tmp, "cols" => $cols);
          endif;
          return $this->output->set_content_type('application/json')->set_output(json_encode($json));
        endif;
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
  
  /**
   * Funcion que transforma a letras el mes pasado por parametro
   *
   * @return string mes
   * @param int $mes
   */
  public function reclasificar() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('reclasificacion_pagos/reclasificar')) {
        $post = $this->input->post();
				//echo "<pre>";print_r($post);echo "</pre>";
        $idpagos = explode(",", $post['idpago']);
        $errores = array();
				$this->load->model("aplicacionautomaticadepago_model");
				$aplica = new Aplicacionautomaticadepago_model();
        foreach($idpagos as $idpago) :
          $post['idpago'] = $idpago;
          $aplicado = $aplica->aplicar_reclasificar($post);
					//echo "<pre>";print_r($post);echo "</pre>";exit;
          if(is_array($aplicado)) :
            $errores[] = "El pago No. ".$idpago." - ".$aplicado[1];
          else :
            $errores[] = "El pago No. ".$idpago." fue reclasificado exitosamente.";
          endif;
        endforeach;//exit;
        $this->data['error']['status'] = false;
        $this->data['error']['text'] = implode("<br>", $errores);
        $this->template->set('title', 'Identificar y Reclasificar Pagos');
        $this->data['title'] = 'RECLASIFICACIÓN INGRESOS';
        $this->data['stitle'] = '';
        $this->data['regionales'] = $this->reclasificacion_pagos_model->get_regionales();
        $this->data['conceptos'] = $this->reclasificacion_pagos_model->get_conceptosfiscalizacion();
        $this->data['message'] = $this->session->flashdata('message');
        $this->template->load($this->template_file, 'reclasificacionpagos/home', $this->data);
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
  /**
   * Funcion que transforma a letras el mes pasado por parametro
   *
   * @return string mes
   * @param int $mes
   */
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
  
  public function mes2($mes) {
    switch($mes) :
      case "01" : return "Enero"; break;
      case "02" : return "Febrero"; break;
      case "03" : return "Marzo"; break;
      case "04" : return "Abril"; break;
      case "05" : return "Mayo"; break;
      case "06" : return "Junio"; break;
      case "07" : return "Julio"; break;
      case "08" : return "Agosto"; break;
      case "09" : return "Septiembre"; break;
      case "10" : return "Octubre"; break;
      case "11" : return "Noviembre"; break;
      case "12" : return "Diciembre"; break;
    endswitch;
  }
}

/* End of file consultapagos.php */
/* Location: ./system/application/controllers/consultapagos.php */