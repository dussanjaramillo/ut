<?php

/**
 * Cnm_calcularmoras (class MY_Controller) :)
 *
 * Liquidación de intereses de mora en las cuotas de la cartera no misional
 *
 * Permite calcular los intereses de moras en las cuotas de la cartera No Misional según su configuración.
 *
 * @author Felipe Camacho [camachogfelipe]
 * @author http://www.cogroupsas.com
 *
 * @package Cnm_calcularmoras
 */
class Cnm_calcularmoras extends MY_Controller {
	
	private $apautpago;

	function __construct() {
    parent::__construct();
		$this->load->model("Aplicacionautomaticadepago_model");
		$this->apautpago = new Aplicacionautomaticadepago_model();
  }
	
	private function obtenerCarteraNoMisional() {
		$fecha_actual = date("Y-m-d");
		$this->db->select("TO_CHAR(CNM_CUOTAS.FECHA_LIM_PAGO, 'YYYY-MM-DD') AS FECHA_LIM_PAGO", FALSE);
		$this->db->select("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_CUOTAS.NO_CUOTA, CNM_CUOTAS.SALDO_CUOTA, CNM_CUOTAS.SALDO_AMORTIZACION, " .
											"CNM_CARTERANOMISIONAL.CALCULO_MORA, CNM_CARTERANOMISIONAL.VALOR_T_MORA, CNM_CARTERANOMISIONAL.TIPO_T_F_MORA, " .
											"CNM_CARTERANOMISIONAL.TIPO_T_V_MORA");
		$this->db->select("DECODE(ABS(CEIL(TO_DATE('".$fecha_actual."', 'YYYY-MM-DD') - CNM_CUOTAS.FECHA_LIM_PAGO)), 0, 0, " .
											"DECODE(CEIL(TO_DATE('".$fecha_actual."', 'YYYY-MM-DD') - CNM_CUOTAS.FECHA_LIM_PAGO) / ". 
											"ABS(CEIL(TO_DATE('".$fecha_actual."', 'YYYY-MM-DD') - CNM_CUOTAS.FECHA_LIM_PAGO)), -1, 0, " . 
											"CEIL(TO_DATE('".$fecha_actual."', 'YYYY-MM-DD') - CNM_CUOTAS.FECHA_LIM_PAGO))) AS DIAS_MORA", FALSE);
		$this->db->join("CNM_CARTERANOMISIONAL", "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E", "inner");
		$this->db->where("CNM_CUOTAS.SALDO_CUOTA >", 0);
		$this->db->where("CNM_CUOTAS.ANULADO", "N");
		$this->db->where("CNM_CARTERANOMISIONAL.SALDO_DEUDA >", 0);
		$this->db->where("CNM_CUOTAS.FECHA_LIM_PAGO<=", "SYSDATE", FALSE);
		$this->db->where_not_in("CNM_CUOTAS.CONCEPTO", array(9, 12, 13));
		$this->db->order_by("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", "ASC");
		$this->db->order_by("CNM_CUOTAS.NO_CUOTA", "ASC");
		$dato = $this->db->get("CNM_CUOTAS");//echo $this->db->last_query();exit;
		$dato = $dato->result_array();
		if (!empty($dato)) :
			return $dato;
		else : return $dato = NULL;
		endif;
	}
	
	function CalcularMoras() {
		$datoNM = $this->obtenerCarteraNoMisional();
		//echo "<pre>";print_r($datoNM);echo "</pre>";exit;
		foreach($datoNM as $dato) :
			//echo "<pre>";print_r($dato);echo "</pre>";exit;
			if($dato['DIAS_MORA'] > 0) :
				//echo "<br>".$dato['COD_CARTERA_NOMISIONAL']." - ".$dato['NO_CUOTA']." - ".
				$interes_mora = round($this->apautpago->calcular_mora_nomisional(
																				$dato['CALCULO_MORA'], $dato['COD_CARTERA_NOMISIONAL'], 
																				$dato['VALOR_T_MORA'], $dato['FECHA_LIM_PAGO'], date("Y-m-d"), 
																				$dato['SALDO_AMORTIZACION'], $dato['DIAS_MORA']
																			), 0);
				$this->db->set("INTERES_MORA_GEN", $interes_mora);
				$this->db->where("ID_DEUDA_E", $dato['COD_CARTERA_NOMISIONAL']);
				$this->db->where("NO_CUOTA", $dato['NO_CUOTA']);
				$this->db->where_not_in("CONCEPTO", array(12, 13));
				$this->db->update("CNM_CUOTAS");
				//echo $this->db->last_query();
			endif;
		endforeach;
	}
}