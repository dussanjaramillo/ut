<?php
/**
 * Webservice pagos electrónicos (class CI_Model) :)
 *
 * Ecollect Webservice
 *
 * Procesa todas las peticiones de la base de datos.
 *
 * @author Felipe Camacho [camachogfelipe]
 * @author http://www.cogroupsas.com
 *
 * @package Ecollect WebService
 */
 
class Ecollect_model extends CI_Model {
	
	function __construct() {
    parent::__construct();
  }
	
	function obtenerCarteraMisional($NRO_IDENTIFICACION) {
		if(!empty($NRO_IDENTIFICACION)) :
			//$fecha_actual = date("Y-m-d");
			$this->db->select("TO_CHAR(LIQUIDACION.FECHA_VENCIMIENTO, 'YYYY-MM-DD') AS FECHA_VENCIMIENTO", FALSE);
			$this->db->select("CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO AS DESC_CONCEPTO, "
										. "LIQUIDACION.COD_TIPOPROCESO, TIPOPROCESO.TIPO_PROCESO AS DESC_PROCESO, LIQUIDACION.SALDO_DEUDA, "
										. "LIQUIDACION.NUM_LIQUIDACION AS NRO_REFERENCIAPAGO, LIQUIDACION.TOTAL_LIQUIDADO, LIQUIDACION.COD_PROCESO_COACTIVO");
			$this->db->select("CASE WHEN CEIL(SYSDATE - FECHA_VENCIMIENTO) < 0 THEN 0 ELSE CEIL(SYSDATE - FECHA_VENCIMIENTO) END AS DIAS_MORA", FALSE);
			$this->db->select("DECODE(LIQUIDACION.COD_CONCEPTO, 1, 80, 2, 82, 3, 81, 5, 9) AS COD_CONCEPTO", FALSE);
			$this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
			$this->db->join("TIPOPROCESO", "TIPOPROCESO.COD_TIPO_PROCESO=LIQUIDACION.COD_TIPOPROCESO");
			$this->db->where("LIQUIDACION.NITEMPRESA", "$NRO_IDENTIFICACION");
			$this->db->where("LIQUIDACION.SALDO_DEUDA >", "0");
			$this->db->where("LIQUIDACION.EN_FIRME", "S");
			$this->db->where("LIQUIDACION.BLOQUEADA", 0);
			$this->db->order_by("LIQUIDACION.FECHA_INICIO", "ASC");
			$this->db->order_by("LIQUIDACION.FECHA_VENCIMIENTO", "ASC");
			//$this->db->where("LIQUIDACION.FECHA_VENCIMIENTO >= ", "TO_DATE('".$fecha_actual."', 'YYYY-MM-DD')", FALSE);
			$dato = $this->db->get("LIQUIDACION");//echo $this->db->last_Query();
			return $dato->result_array();
		else :
			return FALSE;
		endif;
	}
	
	function obtenerCarteraNoMisional($NRO_IDENTIFICACION) {
		if(!empty($NRO_IDENTIFICACION)) :
			$fecha_actual = date("Y-m-d");
			$this->db->select("TO_CHAR(CNM_CUOTAS.FECHA_LIM_PAGO, 'YYYY-MM-DD') AS FECHA_LIM_PAGO", FALSE);
			$this->db->select("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_CUOTAS.NO_CUOTA, CNM_CUOTAS.SALDO_CUOTA, CNM_CUOTAS.SALDO_AMORTIZACION, " .
												"CONCEPTORECAUDO.CODIGO_SENA AS COD_CONCEPTO_RECAUDO, CONCEPTORECAUDO.NOMBRE_CONCEPTO, CNM_CARTERANOMISIONAL.CALCULO_MORA, CNM_CUOTAS.SALDO_INTERES_C, " .
												"CNM_CARTERANOMISIONAL.VALOR_T_MORA, CNM_CARTERANOMISIONAL.TIPO_T_F_MORA, CNM_CARTERANOMISIONAL.TIPO_T_V_MORA, " .
												"CNM_CARTERANOMISIONAL.SALDO_DEUDA, CNM_CUOTAS.SALDO_AMORTIZACION");
			$this->db->select("DECODE(ABS(CEIL(TO_DATE('".$fecha_actual."', 'YYYY-MM-DD') - CNM_CUOTAS.FECHA_LIM_PAGO)), 0, 0, " .
												"DECODE(CEIL(TO_DATE('".$fecha_actual."', 'YYYY-MM-DD') - CNM_CUOTAS.FECHA_LIM_PAGO) / ". 
												"ABS(CEIL(TO_DATE('".$fecha_actual."', 'YYYY-MM-DD') - CNM_CUOTAS.FECHA_LIM_PAGO)), -1, 0, " . 
												"CEIL(TO_DATE('".$fecha_actual."', 'YYYY-MM-DD') - CNM_CUOTAS.FECHA_LIM_PAGO))) AS DIAS_MORA", FALSE);
			$this->db->join("CNM_CARTERANOMISIONAL", "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E AND " .
											"(CNM_CARTERANOMISIONAL.COD_EMPLEADO='".$NRO_IDENTIFICACION."' OR " .
											"CNM_CARTERANOMISIONAL.COD_EMPRESA='".$NRO_IDENTIFICACION."')", "inner");
			$this->db->join("CONCEPTO_HOMOLOGACION","CONCEPTO_HOMOLOGACION.CONCEPTO_HOMOLOGADO=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA" .
											" AND CONCEPTO_HOMOLOGACION.TIPO_FUENTE='3' AND CONCEPTO_HOMOLOGACION.ESTADO='A'", 'inner');
			$this->db->join("CONCEPTORECAUDO", "CONCEPTORECAUDO.COD_CONCEPTO_RECAUDO=CONCEPTO_HOMOLOGACION.COD_CONCEPTO_RECAUDO", "inner");
			$this->db->where("CNM_CUOTAS.SALDO_CUOTA >", 0);
			$this->db->where("CNM_CUOTAS.ANULADO", "N");
			$this->db->where("CNM_CARTERANOMISIONAL.SALDO_DEUDA >", 0);
			$this->db->where_not_in("CNM_CUOTAS.CONCEPTO", array(12, 13));
			$this->db->order_by("CNM_CUOTAS.FECHA_LIM_PAGO", "ASC");
			$this->db->order_by("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", "ASC");
			$this->db->order_by("CNM_CUOTAS.NO_CUOTA", "ASC");
      $dato = $this->db->get("CNM_CUOTAS");//echo $this->db->last_query();exit;
      $dato = $dato->result_array();
      if (!empty($dato)) :
        return $dato;
      else : return $dato = NULL;
      endif;
		else :
			return FALSE;
		endif;
	}
	
	function ObtenerDataCarteraNoMisional($cod_cartera = NULL, $cuota = NULL, $fecha = NULL) {
		if(!is_null($cod_cartera) and !is_null($cuota)) :
			if(is_null($fecha)) $fecha = date("Y-m-d");
			$this->db->select("CNM_CUOTAS.NO_CUOTA, CNM_CUOTAS.SALDO_CUOTA, CNM_CUOTAS.MES_PROYECTADO, CNM_CUOTAS.SALDO_INTERES_NO_PAGOS, " .
												"CNM_CUOTAS.SALDO_AMORTIZACION, CNM_CUOTAS.SALDO_INTERES_C, CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, " .
												"CNM_CARTERANOMISIONAL.CALCULO_MORA, CNM_CARTERANOMISIONAL.VALOR_T_MORA, CNM_CARTERANOMISIONAL.TIPO_T_F_MORA, " .
												"CNM_CARTERANOMISIONAL.TIPO_T_V_MORA, CNM_CARTERANOMISIONAL.SALDO_DEUDA, CONCEPTORECAUDO.CODTIPOCONCEPTO");
			$this->db->select("DECODE(CEIL(TO_DATE('".$fecha."', 'YYYY-MM-DD') - CNM_CUOTAS.FECHA_LIM_PAGO) / ". 
												"ABS(CEIL(TO_DATE('".$fecha."', 'YYYY-MM-DD') - CNM_CUOTAS.FECHA_LIM_PAGO)), -1, 0, " . 
												"CEIL(TO_DATE('".$fecha."', 'YYYY-MM-DD') - CNM_CUOTAS.FECHA_LIM_PAGO)) AS DIAS_MORA", FALSE);
			$this->db->select("TO_CHAR(CNM_CUOTAS.FECHA_LIM_PAGO, 'YYYY-MM-DD') AS FECHA_LIM_PAGO", FALSE);
			$this->db->join("CNM_CARTERANOMISIONAL", "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E", "inner");
			$this->db->join("CONCEPTO_HOMOLOGACION","CONCEPTO_HOMOLOGACION.CONCEPTO_HOMOLOGADO=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA" .
											" AND CONCEPTO_HOMOLOGACION.TIPO_FUENTE='3' AND CONCEPTO_HOMOLOGACION.ESTADO='A'", 'inner');
			$this->db->join("CONCEPTORECAUDO", "CONCEPTORECAUDO.COD_CONCEPTO_RECAUDO=CONCEPTO_HOMOLOGACION.COD_CONCEPTO_RECAUDO", "inner");
			$this->db->where("CNM_CUOTAS.SALDO_CUOTA >", 0);
			$this->db->where("CNM_CUOTAS.ANULADO", "N");
			$this->db->where("CNM_CUOTAS.ID_DEUDA_E", "$cod_cartera");
			$this->db->where("CNM_CUOTAS.NO_CUOTA", "$cuota");
			$this->db->where_not_in("CNM_CUOTAS.CONCEPTO", array(12, 13));
			$this->db->order_by("CNM_CUOTAS.FECHA_LIM_PAGO", "ASC");
      $dato = $this->db->get("CNM_CUOTAS");//echo $this->db->last_query();exit;
      $dato = $dato->row_array();
      if (!empty($dato)) :
        return $dato;
      else : return $dato = NULL;
      endif;
		else :
			return NULL;
		endif;
	}
	
	function obtenerCuotasAcuerdos($NRO_IDENTIFICACION, $NRO_REFERENCIAPAGO, $COD, $ACUERDOS = NULL) {
		if(!empty($NRO_IDENTIFICACION) and !empty($NRO_REFERENCIAPAGO)) :
			$this->db->select("PROYECCIONACUERDOPAGO.PROYACUPAG_SALDO_CUOTA AS VALOR_CUOTA, PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA AS NO_CUOTA");
			$this->db->select("TO_CHAR(PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO, 'YYYY-MM-DD') AS FECHA_VENCIMIENTO", FALSE);
			$this->db->select("CASE WHEN CEIL(SYSDATE - PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO) < 0 THEN 0 ELSE CEIL(SYSDATE - PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO) END AS DIAS_MORA", FALSE);
			if($COD == 2) :
				$acuerdo = " AND ACUERDOPAGO.NRO_LIQUIDACION='" . $NRO_REFERENCIAPAGO . "'";
			elseif($COD == 18) :
				$acuerdo = " AND ACUERDOPAGO.COD_PROCESO_COACTIVO='" . $NRO_REFERENCIAPAGO . "'";
				$acuerdo .= " AND ACUERDOPAGO.COD_PROCESO_COACTIVO NOT IN (" . implode(",", $ACUERDOS) . ")";
			endif;
			$this->db->join("ACUERDOPAGO", "PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO=ACUERDOPAGO.NRO_ACUERDOPAGO" . 
											" AND ACUERDOPAGO.ESTADOACUERDO='1'" . $acuerdo .
											" AND ACUERDOPAGO.NITEMPRESA='" . $NRO_IDENTIFICACION . "'");
			$this->db->where("PROYECCIONACUERDOPAGO.PROYACUPAG_ESTADO", "0");
			$this->db->order_by("PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA");
			$dato = $this->db->get("PROYECCIONACUERDOPAGO");
			return $dato->result_array();
		else :
			return FALSE;
		endif;
	}
	
	function obtenerConcepto($cod) {
		if(!empty($cod) and is_numeric($cod)) :
			$data = $this->db->select("CODTIPOCONCEPTO")->where("COD_CONCEPTO_RECAUDO", $cod)->get("CONCEPTORECAUDO")->row();
			return $data->CODTIPOCONCEPTO;
		endif;
	}
	
	function ObtenerRegionalEmpresa($NRO_IDENTIFICACION) {
		if(!empty($NRO_IDENTIFICACION)) :
			$this->db->select("COD_REGIONAL");
			$this->db->from("EMPRESA");
			$this->db->where("CODEMPRESA", $NRO_IDENTIFICACION);
			$data = $this->db->get();//echo $this->db->last_query();
			$data = $data->row_array();//print_r($data);exit;
			if(!empty($data)) :
				return $data['COD_REGIONAL'];
			else :
				return 1;
			endif;
		else : return 1;
		endif;
	}
	
	function ObtenerRegionalSiif($siif, $depto = false) {
		if(!empty($siif)) :
			if(preg_match("/^[0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{3}-[0-9]{6}/", $siif)) :
				$siif = substr($siif, 0, -7);
			endif;
			if($depto == false) $this->db->select("COD_REGIONAL");
			if($depto == true) $this->db->select("COD_DEPARTAMENTO");
			$this->db->from("REGIONAL");
			$this->db->where("COD_SIIF", $siif);
			$data = $this->db->get();
			$data = $data->row_array();
			if(!empty($data)) :
				if($depto == false) : return $data['COD_REGIONAL'];
				elseif($depto == true) : return $data['COD_DEPARTAMENTO'];
				endif;
			else :
				return 1;
			endif;
		else : return 1;
		endif;
	}
	
	function CodigoBanco($codigo) {
		if(!empty($codigo)) :
			$this->db->select("IDBANCO");
			$this->db->from("BANCO");
			$this->db->where("COD_TRANSFERENCIA", $codigo);
			$data = $this->db->get();
			$data = $data->row_array();
			if(!empty($data)) return $data['IDBANCO'];
			else return false;
		endif;
	}
	
	function TraerFiscalizacion($codigo) {
		if(!empty($codigo)) :
			$this->db->select("TOTAL_CAPITAL, TOTAL_INTERESES, SALDO_DEUDA, SALDO_CAPITAL, SALDO_INTERES, COD_TIPOPROCESO, COD_FISCALIZACION, COD_CONCEPTO, COD_PROCESO_COACTIVO");
			$this->db->from("LIQUIDACION");
			$this->db->where("NUM_LIQUIDACION", "$codigo");
			$this->db->or_where("COD_PROCESO_COACTIVO", "$codigo");
			$data = $this->db->get();//echo $this->db->last_query();
			$data = $data->row_array();
			if(!empty($data)) :
				return $data;
			else : return false;
			endif;
		endif;
	}
	
	function TraerDatosCuota($NRO_IDENTIFICACION, $NRO_REFERENCIAPAGO, $NRO_CUOTA) {
		if(!empty($NRO_IDENTIFICACION) and !empty($NRO_REFERENCIAPAGO)) :
			$this->db->select("PROYECCIONACUERDOPAGO.PROYACUPAG_SALDO_CUOTA AS VALOR_CUOTA, PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA AS NO_CUOTA, ACUERDOPAGO.NRO_ACUERDOPAGO, PROYECCIONACUERDOPAGO.PROYACUPAG_SALDO_INTACUERDO AS SALDO_INTACUERDO, PROYECCIONACUERDOPAGO.PROYACUPAG_SALDO_INTCORRIENTE AS SALDO_INTCORRIENTE, PROYECCIONACUERDOPAGO.PROYACUPAG_SALDOCAPITAL AS SALDO_CAPITAL, PROYECCIONACUERDOPAGO.PROYACUPAG_CAPITAL_CUOTA AS CAPITAL, PROYECCIONACUERDOPAGO.PROYACUPAG_INTCORRIENTE_CUOTA AS INTCORRIENTE, PROYECCIONACUERDOPAGO.PROYACUPAG_INTACUERDO AS INTACUERDO");
			$this->db->select("TO_CHAR(PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO,'YYYY-MM-DD') AS FECHA_VENCIMIENTO", FALSE);
			$this->db->select("CASE WHEN CEIL(SYSDATE - PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO) < 0 THEN 0 ELSE CEIL(SYSDATE - PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO) END AS DIAS_MORA", FALSE);
			$this->db->join("ACUERDOPAGO", "PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO=ACUERDOPAGO.NRO_ACUERDOPAGO" . 
											" AND ACUERDOPAGO.ESTADOACUERDO='1' AND ACUERDOPAGO.NRO_LIQUIDACION='" . $NRO_REFERENCIAPAGO . "'" .
											" AND ACUERDOPAGO.NITEMPRESA='" . $NRO_IDENTIFICACION . "'");
			$this->db->where("PROYECCIONACUERDOPAGO.PROYACUPAG_ESTADO", "0");
			$this->db->where("PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA", "$NRO_CUOTA");
			$this->db->order_by("PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA");
			$dato = $this->db->get("PROYECCIONACUERDOPAGO");//echo $this->db->last_query();
			return $dato->row_array();
		else :
			return FALSE;
		endif;
	}
	
	function TraerDatosCuotas($NRO_IDENTIFICACION, $NRO_REFERENCIAPAGO, $NRO_CUOTA) {
		if(!empty($NRO_IDENTIFICACION) and !empty($NRO_REFERENCIAPAGO)) :
			$this->db->select("PROYECCIONACUERDOPAGO.PROYACUPAG_SALDO_CUOTA AS VALOR_CUOTA, PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA AS NO_CUOTA, ACUERDOPAGO.NRO_ACUERDOPAGO, PROYECCIONACUERDOPAGO.PROYACUPAG_SALDO_INTACUERDO AS SALDO_INTACUERDO, PROYECCIONACUERDOPAGO.PROYACUPAG_SALDO_INTCORRIENTE AS SALDO_INTCORRIENTE, PROYECCIONACUERDOPAGO.PROYACUPAG_SALDOCAPITAL AS SALDO_CAPITAL, PROYECCIONACUERDOPAGO.PROYACUPAG_CAPITAL_CUOTA AS CAPITAL, PROYECCIONACUERDOPAGO.PROYACUPAG_INTCORRIENTE_CUOTA AS INTCORRIENTE, PROYECCIONACUERDOPAGO.PROYACUPAG_INTACUERDO AS INTACUERDO");
			$this->db->select("TO_CHAR(PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO,'YYYY-MM-DD') AS FECHA_VENCIMIENTO", FALSE);
			$this->db->select("CASE WHEN CEIL(SYSDATE - PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO) < 0 THEN 0 ELSE CEIL(SYSDATE - PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO) END AS DIAS_MORA", FALSE);
			$this->db->join("ACUERDOPAGO", "PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO=ACUERDOPAGO.NRO_ACUERDOPAGO" . 
											" AND ACUERDOPAGO.ESTADOACUERDO='1' AND ACUERDOPAGO.NRO_LIQUIDACION='" . $NRO_REFERENCIAPAGO . "'" .
											" AND ACUERDOPAGO.NITEMPRESA='" . $NRO_IDENTIFICACION . "'");
			$this->db->where("PROYECCIONACUERDOPAGO.PROYACUPAG_ESTADO", "0");
			$this->db->where("PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA>", "$NRO_CUOTA", FALSE);
			$this->db->order_by("PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA");
			$dato = $this->db->get("PROYECCIONACUERDOPAGO");//echo $this->db->last_query();
			return $dato->result_array();
		else :
			return FALSE;
		endif;
	}
	
	function ObtenerCodConcepto($cod) {
		if(!empty($cod)) :
			$this->db->select("CODTIPOCONCEPTO");
			$this->db->from("CONCEPTORECAUDO");
			$this->db->where("COD_CONCEPTO_RECAUDO", "$cod");
			$dato = $this->db->get();
			$cod = $dato->row_array();
			return $cod['CODTIPOCONCEPTO'];
		else :
			return false;
		endif;
	}
	
	function VerificarTicketId($id) {
		if(!empty($id)) :
			$this->db->select("TICKETID");
			$this->db->from("PAGOSRECIBIDOS");
			$this->db->where("TICKETID", "$id");
			$dato = $this->db->get();//echo $this->db->last_query();echo $dato->num_rows();
			if($dato->num_rows() > 0) return TRUE;
			else return FALSE;
		else :
			return FALSE;
		endif;
	}
	
	function AplicarInteresNoPago($id_deuda = NULL, $valor_interes = 0, $saldo_interes = 0, $no_cuota = 1) {
		if(!is_null($id_deuda) and $valor_interes > 0 and $saldo_interes > 0 and $no_cuota > 1) :
			if($valor_interes == $saldo_interes) :
				$this->db->set("SALDO_INTERES_NO_PAGOS", 0);
				$this->db->where("ID_DEUDA_E", $id_deuda);
				$this->db->where("NO_CUOTA<", $no_cuota, FALSE);
				$this->db->where_not_in("CONCEPTO", array(12, 13));
				$this->db->update("CNM_CUOTAS");
			else :
				$this->db->select("NO_CUOTA, SALDO_INTERES_NO_PAGOS");
				$this->db->where("ID_DEUDA_E", $id_deuda);
				$this->db->where("NO_CUOTA<", $no_cuota, FALSE);
				$this->db->where_not_in("CONCEPTO", array(12, 13));
				$cuotas = $this->db->get();//echo $this->db->last_query();echo $dato->num_rows();
				if($cuotas->num_rows() > 0) :
					$cuotas = $cuotas->result_array();
					foreach($cuotas as $cuota) :
						if($valor_interes >= $cuota['SALDO_INTERES_NO_PAGOS']) :
							$this->db->set("SALDO_INTERES_NO_PAGOS", 0);
							$valor_interes -= $cuota['SALDO_INTERES_NO_PAGOS'];
						elseif($valor_interes > 0 and $valor_interes < $cuota['SALDO_INTERES_NO_PAGOS']) :
							$interes = $cuota['SALDO_INTERES_NO_PAGOS'] - $valor_interes;
							$this->db->set("SALDO_INTERES_NO_PAGOS", $interes);
							$valor_interes = 0;
						endif;
						$this->db->where("ID_DEUDA_E", $id_deuda);
						$this->db->where("NO_CUOTA", $cuota['NO_CUOTA']);
						$this->db->where_not_in("CONCEPTO", array(12, 13));
						$this->db->update("CNM_CUOTAS");
					endforeach;
				endif;
			endif;
		endif;
	}
	
	function ActualizarCapitalNoMisional($id_deuda = NULL, $saldo_a_aplicar = 0) {
		if(!is_null($id_deuda) and $saldo_a_aplicar > 0) :
			$this->db->select("SALDO_DEUDA, COD_TIPOCARTERA");
			$this->db->where("COD_CARTERA_NOMISIONAL", $id_deuda);
			$capital = $this->db->get("CNM_CARTERANOMISIONAL");//echo $this->db->last_query();echo $dato->num_rows();
			if($capital->num_rows() > 0) :
				$capital = $capital->row_array();
				$capital_saldo = $capital['SALDO_DEUDA'] - $saldo_a_aplicar;
				$this->db->set("SALDO_DEUDA", $capital_saldo);
				$this->db->where("COD_CARTERA_NOMISIONAL", $id_deuda);
				$this->db->update("CNM_CARTERANOMISIONAL");
				if($capital['COD_TIPOCARTERA'] == 8) $hipotecario = true;
				else $hipotecario = false;
				require_once APPPATH.'controllers/cnm_liquidacioncuotas.php';
				$cnm = new Cnm_liquidacioncuotas();
				$cnm->CnmCalcularCuotas($id_deuda, date("Y-m-d"), 1, $hipotecario, true);
			endif;
		endif;
	}
}