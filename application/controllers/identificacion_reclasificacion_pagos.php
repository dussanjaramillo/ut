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
class Identificacion_reclasificacion_pagos extends MY_Controller {

  function __construct() {

    parent::__construct();
    $this->load->library('form_validation');
    $this->load->helper(array('form', 'url', 'codegen_helper'));
    $this->load->model('codegen_model', '', TRUE);
    $this->load->model('identificacion_reclasificacion_pagos_model');
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
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarpagos/manage')) {
        //template data
        $this->template->set('title', 'Identificar y Reclasificar Pagos');
        $this->data['title'] = 'RECLASIFICACIÓN INGRESOS';
        $this->data['stitle'] = '';
        $this->data['regionales'] = $this->identificacion_reclasificacion_pagos_model->get_regionales();
        $this->data['conceptos'] = $this->identificacion_reclasificacion_pagos_model->get_conceptosfiscalizacion();
        $this->data['message'] = $this->session->flashdata('message');
        $this->template->load($this->template_file, 'identificacionreclasificacionpagos/home', $this->data);
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
   * @return json con los resultados de busqueda
   * @param string $identificación, $razon
   */
  function buscar() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarpagos/buscar')) {
        $nit = $this->input->post("nit");
        $regional = $this->input->post('regional');
        $concepto = $this->input->post('concepto');
        $concepto2 = $this->input->post('concepto2');
        $periodo_inicio = $this->data['periodo_inicio'] = $this->input->post('periodo_inicio');
        $periodo_fin = $this->data['periodo_fin'] = $this->input->post('periodo_fin');
        $resolucion = $this->input->post('resolucion');
        if (empty($nit) || empty($periodo_inicio) || empty($periodo_fin)) {
          redirect(base_url() . 'index.php/identificacion_reclasificacion_pagos');
        } else {
          if(!empty($nit)) $this->identificacion_reclasificacion_pagos_model->set_nit($nit);
          if(!empty($regional)) $this->identificacion_reclasificacion_pagos_model->set_regional($regional);
          if(!empty($concepto) and $concepto != "TODOS") $this->identificacion_reclasificacion_pagos_model->set_concepto($concepto);
          if(!empty($concepto2) and $concepto2 != "TODOS") $this->identificacion_reclasificacion_pagos_model->set_produccion($concepto2);
          $this->identificacion_reclasificacion_pagos_model->set_periodo_inicio($periodo_inicio);
          $this->identificacion_reclasificacion_pagos_model->set_periodo_fin($periodo_fin);          
          $this->data['empresa'] = $this->identificacion_reclasificacion_pagos_model->get_empresa();
          $this->data['obligaciones'] = $this->identificacion_reclasificacion_pagos_model->get_obligaciones();
          if(!empty($this->data['obligaciones'])) :
            $this->identificacion_reclasificacion_pagos_model->set_estado(0);
            $this->data['pagos'] = $this->identificacion_reclasificacion_pagos_model->get_ingresos();
            $this->data['conceptos'] = $this->identificacion_reclasificacion_pagos_model->get_conceptosfiscalizacion();
            $this->data['regionales'] = $this->identificacion_reclasificacion_pagos_model->get_regionales();
            $this->data['message'] = $this->session->flashdata('message');
          else :
            $this->data['message'] = "Este número de identificación no tiene obligaciones pendientes.";
          endif;
          $this->template->set('title', 'RECLASIFICACION INGRESOS');
          $this->data['title'] = 'RECLASIFICACIÓN INGRESOS';
          $this->template->load($this->template_file, 'identificacionreclasificacionpagos/resultadobusqueda', $this->data);
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
          $this->identificacion_reclasificacion_pagos_model->set_nit($nit);
          if(!empty($nitorg)) $this->identificacion_reclasificacion_pagos_model->set_nitorg($nitorg);
          return $this->output->set_content_type('application/json')->set_output(json_encode($this->identificacion_reclasificacion_pagos_model->buscarnits()));
        }
      /*} else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
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
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('index.php/identificacion_reclasificacion_pagos')) {
        $nit = $this->input->post('nit');
        $concepto = $this->input->post('concepto');
        if (empty($concepto) || empty($nit)) {
          echo '<option value="">Seleccione el periodo</option>'."\n";
        } else {
          $this->identificacion_reclasificacion_pagos_model->set_concepto($concepto);
          $this->identificacion_reclasificacion_pagos_model->set_nit($nit);
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
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('index.php/identificacion_reclasificacion_pagos')) {
        $nit = $this->input->post('nit');
        $nitorg = $this->input->post('nitorg');
        if( empty($nit) ) :
          $datos = array('status' => 'error', 'data' => 'Los numeros de Identificación estan vacíos');
          return $this->output->set_content_type('application/json')->set_output(json_encode($datos));
        elseif($nit == $nitorg) :
          $datos = array('status' => 'error', 'data' => 'Los numeros de Identificación son iguales');
          return $this->output->set_content_type('application/json')->set_output(json_encode($datos));
        else :
          $this->identificacion_reclasificacion_pagos_model->set_nit($nit);
          $documento = $this->input->post('documento');
          if(!empty($documento))
            $this->identificacion_reclasificacion_pagos_model->set_documento($documento);
          $fecha_pago = $this->input->post('fecha_pago');
          if(!empty($fecha_pago))
            $this->identificacion_reclasificacion_pagos_model->set_fecha_pago($fecha_pago);
          $estado = $this->input->post('estado');
          if(!empty($estado))
            $this->identificacion_reclasificacion_pagos_model->set_estado($estado);
          $regional = $this->input->post('regional');
          if(!empty($regional))
            $this->identificacion_reclasificacion_pagos_model->set_regional($regional);
          $valor = $this->input->post('valor');
          if(!empty($valor))
            $this->identificacion_reclasificacion_pagos_model->set_valor($valor);
          $datos = $this->identificacion_reclasificacion_pagos_model->get_ingresos();
          $json = array();
          if(is_null($datos)) :
            $json = array("status" => "error", "data" => "No hay pagos relacionados con los datos solicitados.");
          else :
            $tmp = $tmp1 = $cols = array();
            foreach($datos as $dato) :
              $cols = array_keys($dato);
              foreach($dato as $key=>$dat) :
                if($key == "COD_PAGO") :
                  $tmp1[] = '<input type="radio" name="pago[]" class="pago" data-value=" ' . $dat['VALOR_PAGADO'] . ' " value=" ' . $dat . ' " />';
                else :
                  $tmp1[] = $dat;
                endif;
              endforeach;
              $tmp[] = $tmp1;
            endforeach;
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
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('index.php/identificacion_reclasificacion_pagos')) {
        $post = $this->input->post();
        $idpagos = explode(",", $post['idpago']);
        $errores = array();
        foreach($idpagos as $idpago) :
          $post['idpago'] = $idpago;
          $this->load->model("aplicacionautomaticadepago_model");
          $aplica = new Aplicacionautomaticadepago_model();
          $aplicado = $aplica->aplicar_reclasificar($post);
          if(is_array($aplicado)) :
            $errores[] = "El pago No. ".$idpago." - ".$aplicado[1];
          else :
            $errores[] = "El pago No. ".$idpago." fue aplicado automaticamente";
          endif;
        endforeach;
        $this->data['error']['status'] = false;
        $this->data['error']['text'] = implode("<br>", $errores);
        $this->template->set('title', 'Identificar y Reclasificar Pagos');
        $this->data['title'] = 'RECLASIFICACIÓN INGRESOS';
        $this->data['stitle'] = '';
        $this->data['regionales'] = $this->identificacion_reclasificacion_pagos_model->get_regionales();
        $this->data['conceptos'] = $this->identificacion_reclasificacion_pagos_model->get_conceptosfiscalizacion();
        $this->data['message'] = $this->session->flashdata('message');
        $this->template->load($this->template_file, 'identificacionreclasificacionpagos/home', $this->data);
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