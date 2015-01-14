<?php

class Identificacion_reclasificacion_pagos_model extends CI_Model {

  private $nit;
  private $nitorg;
  private $regional;
  private $concepto;
  private $subconcepto;
  private $estado;
  private $periodo;
  private $periodo_inicio;
  private $periodo_fin;
  private $documento;
  private $fecha_pago;
  private $valor;
  private $iddeuda;
  private $idpagos;

  function __construct() {
    parent::__construct();
  }

  function set_nit($nit) {
    $this->nit = $nit;
  }
  
  function set_nitorg($nit) {
    $this->nitorg = $nit;
  }

  function set_regional($regional) {
    $this->regional = $regional;
  }
  
  function set_concepto($concepto) {
    $this->concepto = $concepto;
  }
  
  function set_subconcepto($subconcepto) {
    $this->subconcepto = $subconcepto;
  }
  
  function set_estado($estado) {
    $this->estado = $estado;
  }
  
  function set_produccion($produccion) {
    $this->produccion = $produccion;
  }
  
  function set_periodo_inicio($periodo_inicio) {
    $this->periodo_inicio = $periodo_inicio;
  }
  
  function set_periodo_fin($periodo_fin) {
    $this->periodo_fin = $periodo_fin;
  }
  
  function set_documento($documento) {
    $this->documento = $documento;
  }
  
  function set_fecha_pago($fecha_pago) {
    $this->fecha_pago = $fecha_pago;
  }
  
  function set_valor($valor) {
    $this->valor = $valor;
  }

  function buscarnits() {
    $this->db->select('CODEMPRESA');
    $this->db->select('UPPER(RAZON_SOCIAL) AS RAZON_SOCIAL', FALSE);
    if (!empty($this->nit) and empty($this->razon)) {
      $this->db->like('CODEMPRESA', $this->nit);
    }
    if(!empty($this->nitorg)) :
      $this->db->where_not_in("CODEMPRESA", $this->nitorg);
    endif;
    $datos = $this->db->get('EMPRESA');
    $datos = $datos->result_array();
    if(!empty($datos)) :
      $tmp = NULL;
      foreach($datos as $nit) :
        $tmp[] = array("value" => $nit['CODEMPRESA'], "label" => $nit['CODEMPRESA']." :: ".$nit['RAZON_SOCIAL']);
      endforeach;
      $datos = $tmp;
    endif;
    return $datos;
  }

  public function get_empresa() {
    if (!empty($this->nit)) :
      $this->db->select("EMPRESA.CODEMPRESA, EMPRESA.RAZON_SOCIAL, EMPRESA.DIRECCION, EMPRESA.TELEFONO_FIJO, EMPRESA.TELEFONO_CELULAR, REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL");
      $this->db->join("REGIONAL", "EMPRESA.COD_REGIONAL=REGIONAL.COD_REGIONAL");
      $this->db->where("EMPRESA.CODEMPRESA", $this->nit);
      $dato = $this->db->get("EMPRESA");
      return $dato = $dato->row_array();
    endif;
  }

  public function get_bancos() {
    $this->db->select("BANCO.IDBANCO, BANCO.NOMBREBANCO");
    $this->db->where("BANCO.IDESTADO", 1);
    $dato = $this->db->get("BANCO");
    return $dato->result_array();
  }

  public function get_conceptos() {
    if(!empty($this->concepto)) $dato = $this->db->order_by("NOMBRE_CONCEPTO", "ASC")->get_where("CONCEPTORECAUDO", array("CODTIPOCONCEPTO" => "3"));
    else $dato = $this->db->order_by("NOMBRE_CONCEPTO", "ASC")->get("CONCEPTORECAUDO");
    return $dato->result_array();
  }
  
  public function get_conceptosfiscalizacion() {
    if(!empty($this->concepto)) $this->db->where("COD_CPTO_FISCALIZACION", $this->concepto);
    $dato = $this->db->order_by("NOMBRE_CONCEPTO", "ASC")->get("CONCEPTOSFISCALIZACION");
    return $dato->result_array();
  }

  public function get_regionales() {
    $this->db->select("REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL");
    $dato = $this->db->get("REGIONAL");
    return $dato->result_array();
  }
  
  public function get_formaspago() {
    $this->db->select("FORMAPAGO.COD_FORMAPAGO, FORMAPAGO.NOMBE_FORMAPAGO");
    $this->db->where("FORMAPAGO.COD_ESTADO", 1);
    $dato = $this->db->get("FORMAPAGO");
    return $dato->result_array();
  }
  
  public function get_obligaciones() {
    if (!empty($this->nit)) :
      $this->db->select("LIQUIDACION.NUM_LIQUIDACION, LIQUIDACION.FECHA_INICIO, LIQUIDACION.FECHA_FIN, LIQUIDACION.TOTAL_LIQUIDADO, "
                      . "CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO, CONCEPTORECAUDO.NOMBRE_CONCEPTO");
      if(!empty($this->concepto)) $this->db->where("LIQUIDACION.COD_CONCEPTO", $this->concepto);
      if(!empty($this->subconcepto)) $this->db->where("LIQUIDACION.COD_SUBCONCEPTO", $this->subconcepto);
      $this->db->where("LIQUIDACION.NITEMPRESA", $this->nit);
      $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
      $this->db->join("CONCEPTORECAUDO", "CONCEPTORECAUDO.COD_CONCEPTO_RECAUDO=LIQUIDACION.COD_CONCEPTO");
      $this->db->order_by("LIQUIDACION.NUM_LIQUIDACION", "ASC");
      $this->db->where("LIQUIDACION.SALDO_DEUDA > ", 0);
      $dato = $this->db->get("LIQUIDACION");
      $dato = $dato->result_array();
      if(!empty($dato)) : return $dato;
      else : return $dato = NULL;
      endif;
    endif;
  }
  
  public function get_ingresos(){
    if (!empty($this->nit)) :
      $this->db->select("PAGOSRECIBIDOS.COD_PAGO, PAGOSRECIBIDOS.PROCEDENCIA, PAGOSRECIBIDOS.VALOR_PAGADO, PAGOSRECIBIDOS.PERIODO_PAGADO, "
                      . "PAGOSRECIBIDOS.FECHA_PAGO, PAGOSRECIBIDOS.APLICADO, "
                      . "CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO AS NOMBRE_CONCEPTO, CONCEPTORECAUDO.NOMBRE_CONCEPTO AS NOMBRE_SUBCONCEPTO, "
                      . "REGIONAL.NOMBRE_REGIONAL");
      if(!empty($this->documento)) :
        $this->db->where("APLICACION_PAGOS.NUM_DOCUMENTO", $this->documento);
        $this->db->join("APLICACION_PAGOS", "APLICACION_PAGOS.COD_APLICACIONPAGOS=PAGOSRECIBIDOS.COD_APLICACIONPAGOS");
      endif;
      if(!empty($this->fecha_pago))
        $this->db->where("TRUNC(PAGOSRECIBIDOS.FECHA_PAGO)", " TO_DATE('".$this->fecha_pago."', 'DD/MM/RRRR')", FALSE);
      if(!empty($this->estado)) :
        if($this->estado == 0) :
          $this->db->where("PAGOSRECIBIDOS.APLICADO IS NULL", NULL);
          $this->db->or_where("PAGOSRECIBIDOS.APLICADO", $this->estado);
        else :
          $this->db->where("PAGOSRECIBIDOS.APLICADO", $this->estado);
        endif;
      endif;
      if(!empty($this->regional)) $this->db->where("PAGOSRECIBIDOS.COD_REGIONAL", $this->regional);
      if(!empty($this->valor)) $this->db->where("PAGOSRECIBIDOS.VALOR_PAGADO", $this->valor);
      $this->db->where("PAGOSRECIBIDOS.NITEMPRESA", $this->nit);
      $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=PAGOSRECIBIDOS.COD_CONCEPTO");
      $this->db->join("CONCEPTORECAUDO", "CONCEPTORECAUDO.COD_CONCEPTO_RECAUDO=PAGOSRECIBIDOS.COD_SUBCONCEPTO");
      $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=PAGOSRECIBIDOS.COD_REGIONAL");
      $this->db->order_by("PAGOSRECIBIDOS.FECHA_PAGO", "ASC");
      if(!empty($this->periodo_inicio))
        $this->db->where("PAGOSRECIBIDOS.FECHA_PAGO>=", " TO_DATE('".$this->periodo_inicio."', 'DD/MM/RR')", FALSE);
      if(!empty($this->periodo_fin))
        $this->db->where("PAGOSRECIBIDOS.FECHA_PAGO<=", " TO_DATE('".$this->periodo_fin."', 'DD/MM/RR')", FALSE);
      $dato = $this->db->get("PAGOSRECIBIDOS");//echo $this->db->last_query();
      $dato = $dato->result_array();
      if(!empty($dato)) : return $dato;
      else : return $dato = NULL;
      endif;
    endif;
  }
  
  private function mes($mes) {
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
}