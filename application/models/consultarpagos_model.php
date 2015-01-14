<?php
/**
 * Consultarpagos (class CI_Model) :)
 *
 * Consultar pagos
 *
 * Permite consultar pagos realizados.
 *
 * @author Felipe Camacho [camachogfelipe]
 * @author http://www.cogroupsas.com
 *
 * @package Consultarpagos
 */

class Consultarpagos_model extends CI_Model {

  private $nit;
  private $regional;
  private $concepto;
  private $subconcepto;
  private $produccion;
  private $periodo_inicio;
  private $periodo_fin;
  private $resolucion;
  private $liquidacion;
  private $estadopago;

  function __construct() {
    parent::__construct();
  }

  function set_nit($nit) {
    $this->nit = $nit;
  }

  function set_regional($regional) {
    $this->razon = $regional;
  }
  
  function set_concepto($concepto) {
    $this->concepto = $concepto;
  }
	
	function set_subconcepto($concepto) {
    $this->subconcepto = $concepto;
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
  
  function set_resolucion($resolucion) {
    $this->resolucion = $resolucion;
  }
  
  function set_liquidacion($liquidacion) {
    $this->liquidacion = $liquidacion;
  }
  
  function set_estado($estado) {
    $this->estado = $estado;
  }
	
	function buscarnits() {
		$datos = NULL;
		if (!empty($this->nit)) {
			$query = "SELECT			TO_CHAR(CNM_EMPLEADO.IDENTIFICACION) AS CODEMPRESA, 
														CNM_EMPLEADO.NOMBRES || ' ' || CNM_EMPLEADO.APELLIDOS AS RAZON_SOCIAL, CNM_EMPLEADO.COD_REGIONAL 
								FROM				CNM_EMPLEADO WHERE (TO_CHAR(CNM_EMPLEADO.IDENTIFICACION) LIKE '%".mb_strtoupper($this->nit). "%') OR 
														(UPPER(CNM_EMPLEADO.NOMBRES || ' ' || CNM_EMPLEADO.APELLIDOS) LIKE '%".mb_strtoupper($this->nit)."%')
								UNION(
									SELECT		CNM_EMPRESA.COD_ENTIDAD AS CODEMPRESA, CNM_EMPRESA.RAZON_SOCIAL, CNM_EMPRESA.COD_REGIONAL 
									FROM			CNM_EMPRESA 
									WHERE			(CNM_EMPRESA.COD_ENTIDAD LIKE '%" . mb_strtoupper($this->nit) . "%') OR 
														(UPPER(CNM_EMPRESA.RAZON_SOCIAL) LIKE '%" . mb_strtoupper($this->nit) . "%') 
									UNION(
										SELECT	EMPRESA.CODEMPRESA, EMPRESA.RAZON_SOCIAL, EMPRESA.COD_REGIONAL 
										FROM		EMPRESA 
										WHERE		(EMPRESA.CODEMPRESA LIKE '%" . mb_strtoupper($this->nit) . "%') OR 
														(UPPER(EMPRESA.RAZON_SOCIAL) LIKE '%" . mb_strtoupper($this->nit) . "%') 
									)
								)
								ORDER BY 1, 2 ASC";
			$query = $this->db->query($query);
			//echo "query: ".$this->db->last_query();exit();
			$datos = $query->result_array;
    }
    if(!empty($datos)) :
      $tmp = NULL;
      foreach($datos as $nit) :
        $tmp[] = array("value" => $nit['CODEMPRESA'], 
											 "label" => $nit['CODEMPRESA']." :: ".$nit['RAZON_SOCIAL'], 
											 "razon" => $nit['RAZON_SOCIAL'],
											 "regional" => $nit['COD_REGIONAL'], 
											 "nit" => (is_numeric($nit['CODEMPRESA']))?number_format($nit['CODEMPRESA'], 0, ".", "."):$nit['CODEMPRESA']
											);
      endforeach;
      $datos = $tmp;
    endif;
    return $datos;
  }

  public function get_empresa() {
    if (!empty($this->nit)) :
      $this->db->where("CODEMPRESA", $this->nit);
      $dato = $this->db->get("VW_PERSONAS");
      $dato = $dato->row_array();
      return $dato;
    endif;
  }

  public function get_bancos() {
    $this->db->select("BANCO.IDBANCO, BANCO.NOMBREBANCO");
    $this->db->where("BANCO.IDESTADO", 1);
    $dato = $this->db->get("BANCO");
    return $dato->result_array();
  }

  public function get_conceptos() {
    if(!empty($this->concepto)) $dato = $this->db->order_by("NOMBRE_CONCEPTO", "ASC")->get_where("CONCEPTORECAUDO", array("CODTIPOCONCEPTO" => $this->concepto));
    else $dato = $this->db->order_by("NOMBRE_CONCEPTO", "ASC")->get("CONCEPTORECAUDO");
    return $dato->result_array();
  }
  
  public function get_conceptosfiscalizacion() {
    if(!empty($this->concepto)) $this->db->where("COD_CPTO_FISCALIZACION", $this->concepto);
    $dato = $this->db->order_by("NOMBRE_CONCEPTO", "ASC")->get("CONCEPTOSFISCALIZACION");
    return $dato->result_array();
  }
	
	public function ObtenerConceptos() {
		if(!empty($this->concepto)) $this->db->where("COD_TIPOCONCEPTO", $this->concepto);
		$dato = $this->db->order_by("NOMBRE_TIPO", "ASC")->get("TIPOCONCEPTO");
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
  
  public function get_pagos() {
    if (!empty($this->nit)) :
		$this->db->select("TO_CHAR(PAGOSRECIBIDOS.FECHA_PAGO, 'DD/MM/YYYY') AS FECHA_PAGO", FALSE);
		$this->db->select("TO_CHAR(PAGOSRECIBIDOS.FECHA_APLICACION, 'DD/MM/YYYY') AS FECHA_APLICACION", FALSE);
		$this->db->select("PAGOSRECIBIDOS.NRO_REFERENCIA AS NUM_LIQUIDACION, "
                      . "PAGOSRECIBIDOS.VALOR_PAGADO AS VALOR_RECIBIDO, PAGOSRECIBIDOS.NUM_DOCUMENTO, PAGOSRECIBIDOS.COD_CONCEPTO, "
                      . "PAGOSRECIBIDOS.PROCEDENCIA, PAGOSRECIBIDOS.DISTRIBUCION_CAPITAL, PAGOSRECIBIDOS.DISTRIBUCION_INTERES, "
                      . "PAGOSRECIBIDOS.DISTRIBUCION_INTERES_MORA, FORMAPAGO.NOMBE_FORMAPAGO, PAGOSRECIBIDOS.NRO_CUOTA, "
											. "PAGOSRECIBIDOS.COD_PROCEDENCIA, TIPOCONCEPTO.NOMBRE_TIPO, CONCEPTORECAUDO.NOMBRE_CONCEPTO");
      $this->db->where("PAGOSRECIBIDOS.NITEMPRESA", $this->nit);
      if(!empty($this->liquidacion)) :
        $this->db->where("LIQUIDACION.NUM_LIQUIDACION", $this->liquidacion);
      else :
        if(!empty($this->concepto)) $this->db->where("PAGOSRECIBIDOS.COD_CONCEPTO", $this->concepto);
				if(!empty($this->subconcepto)) $this->db->where("PAGOSRECIBIDOS.COD_SUBCONCEPTO", $this->subconcepto);
        if(!empty($this->periodo_inicio) and !empty($this->periodo_fin))
					$this->db->where("PAGOSRECIBIDOS.FECHA_PAGO BETWEEN '".str_replace("/", "-", $this->periodo_inicio)."' AND '".str_replace("/", "-", $this->periodo_fin)."'");
        if(!empty($this->regional)) $this->db->where("PAGOSRECIBIDOS.COD_REGIONAL", $this->regional);
      endif;
      $this->db->order_by("PAGOSRECIBIDOS.FECHA_PAGO", "ASC");
	  $this->db->order_by("PAGOSRECIBIDOS.NUM_DOCUMENTO", "ASC");
	  $this->db->order_by("PAGOSRECIBIDOS.COD_PAGO", "ASC");
      $this->db->join("TIPOCONCEPTO", "TIPOCONCEPTO.COD_TIPOCONCEPTO=PAGOSRECIBIDOS.COD_CONCEPTO");
			$this->db->join("CONCEPTORECAUDO", "CONCEPTORECAUDO.COD_CONCEPTO_RECAUDO=PAGOSRECIBIDOS.COD_SUBCONCEPTO");
      $this->db->join("FORMAPAGO", "PAGOSRECIBIDOS.COD_FORMAPAGO=FORMAPAGO.COD_FORMAPAGO");
      $dato = $this->db->get("PAGOSRECIBIDOS");// echo $this->db->last_query();exit;
      $dato = $dato->result_array();
      if (!empty($dato)) : return $dato;
      else : return $dato = NULL;
      endif;
    endif;
  }
  
  function get_resolucion() {
    if(!empty($this->resolucion)) :
      $this->db->select("RESOLUCION.NUMERO_RESOLUCION, RESOLUCION.NUM_LIQUIDACION, RESOLUCION.FECHA_LIQUIDACION, RESOLUCION.PERIODO_INICIAL, "
                      . "RESOLUCION.PERIODO_FINAL, "
                      . "LIQUIDACION.NUM_LIQUIDACION, LIQUIDACION.TOTAL_LIQUIDADO, LIQUIDACION.TOTAL_INTERESES, LIQUIDACION.FECHA_LIQUIDACION, "
                      . "LIQUIDACION.COD_CONCEPTO, "
                      . "PAGOSRECIBIDOS.VALOR_PAGADO, PAGOSRECIBIDOS.COD_FISCALIZACION"
                      . "CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO");
      $this->db->where("RESOLUCION.NUMERO_RESOLUCION", $this->resolucion);
      $this->db->join("LIQUIDACION", "LIQUIDACION.NUM_LIQUIDACION=RESOLUCION.NUM_LIQUIDACION");
      $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
      $this->db->join("PAGOSRECIBIDOS", "PAGOSRECIBIDOS.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION AND PAGOSRECIBIDOS.NITEMPRESA=LIQUIDACION.NITEMPRESA");
      $this->db->order_by("LIQUIDACION.FECHA_LIQUIDACION", "ASC");
      $this->db->where("PAGOSRECIBIDOS.FECHA_PAGO >=", " TO_DATE('".$this->periodo_inicio."', 'DD/MM/RR')", FALSE);
      $this->db->where("PAGOSRECIBIDOS.FECHA_PAGO <=", " TO_DATE('".$this->periodo_fin."', 'DD/MM/RR')", FALSE);
      $dato = $this->db->get("RESOLUCION");
      return $dato->result_array();
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
?>