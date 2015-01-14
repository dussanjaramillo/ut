<?php
/**
 * Devolucion_pagos_model (class CI_Model) :)
 *
 * Devoluci�n de pagos
 *
 * Permite hacer las consultas para la devoluci�n de pagos
 *
 * @author Felipe Camacho [camachogfelipe]
 * @author http://www.cogroupsas.com
 *
 * @package Devolucion
 */
class Devolucion_pagos_model extends CI_Model {

  private $nit;
  private $referencia;
	private $concepto;

  function __construct() {
    parent::__construct();
  }

  function SetNit($nit) {
    $this->nit = $nit;
  }

  function SetReferencia($referencia) {
    $this->referencia = $referencia;
  }
	
	function SetConcepto($concepto) {
    $this->concepto = $concepto;
  }
	
	function GetEmpresa() {
		if(!empty($this->nit)) :
			$this->db->select("CODEMPRESA, NOMBRE_EMPRESA");
			$this->db->from("VW_PERSONAS");
			$this->db->where("CODEMPRESA", $this->nit);
			$dato = $this->db->get();
			if($dato->num_rows() > 0) :
				return $dato->row_array();
			endif;
		else : return false;
		endif;
	}
	
	function GetPagos() {
		if(!empty($this->referencia)) :
                    $dato = $this->db->query("SELECT PAGOSRECIBIDOS.COD_PAGO, PAGOSRECIBIDOS.VALOR_PAGADO, SUM(DEVOLUCION_PAGOS.VALOR_DEVUELTO) AS VALOR_DEVUELTO 
                        FROM PAGOSRECIBIDOS LEFT JOIN DEVOLUCION_PAGOS ON DEVOLUCION_PAGOS.COD_PAGOSRECIBIDOS=PAGOSRECIBIDOS.COD_PAGO 
                        WHERE COD_SUBCONCEPTO = '$this->concepto' 
                        AND PROCEDENCIA IN ('MANUAL', 'ECOLLECT') 
                        AND NOT EXISTS ( SELECT 'X' FROM DEVOLUCION_PAGOS WHERE COD_PAGOSRECIBIDOS = PAGOSRECIBIDOS.COD_PAGO ) 
                        AND NITEMPRESA = '$this->nit' 
                        GROUP BY PAGOSRECIBIDOS.COD_PAGO, PAGOSRECIBIDOS.VALOR_PAGADO");
//			$this->db->select("PAGOSRECIBIDOS.COD_PAGO, PAGOSRECIBIDOS.VALOR_PAGADO, SUM(DEVOLUCION_PAGOS.VALOR_DEVUELTO) AS VALOR_DEVUELTO");
//			$this->db->from("PAGOSRECIBIDOS");
//			$this->db->join("DEVOLUCION_PAGOS", "DEVOLUCION_PAGOS.COD_PAGOSRECIBIDOS=PAGOSRECIBIDOS.COD_PAGO", "LEFT");
//			$this->db->where("COD_SUBCONCEPTO", "$this->concepto");
//			$this->db->where_in("PROCEDENCIA", array('MANUAL', 'ECOLLECT'));
//			$this->db->where("NOT EXISTS (
//							SELECT	'X'
//							FROM		DEVOLUCION_PAGOS 
//							WHERE		COD_PAGOSRECIBIDOS = PAGOSRECIBIDOS.COD_PAGO
//						)",FALSE);
			/*$this->db->where("(NOT EXISTS (
							SELECT	'X'
							FROM		DEVOLUCION_PAGOS 
							WHERE		ESTADO <> 1
                      AND COD_PAGOSRECIBIDOS = PAGOSRECIBIDOS.COD_PAGO
						) OR 
						EXISTS (
							SELECT	'X'
							FROM		DEVOLUCION_PAGOS
							WHERE		COD_PAGOSRECIBIDOS = PAGOSRECIBIDOS.COD_PAGO
							HAVING SUM(VALOR_DEVUELTO) < PAGOSRECIBIDOS.VALOR_PAGADO
						))");*/
//			$this->db->where("NITEMPRESA", "$this->nit");
//			$this->db->group_by(array("PAGOSRECIBIDOS.COD_PAGO", "PAGOSRECIBIDOS.VALOR_PAGADO"));
//			$dato = $this->db->get();//exit($this->db->last_query());
			//echo "<pre>";print_r($dato);exit("</pre>");
			if($dato->num_rows() >0) :
				return $dato->result_array();
			else :
				return false;
			endif;
		endif;
	}
	
	function GuardarPagos($datos, $cod) {
		if(!empty($datos)) :
			if($this->db->insert_batch('DEVOLUCION_PAGOS', $datos)) :
				$this->db->set("COD_RESPUESTA", 1354);
				$this->db->where("COD_DEVOLUCION", $cod);
				$this->db->update("SOLICITUDDEVOLUCION");
				return true;
			else : return false;
			endif;
		endif;
	}
}
