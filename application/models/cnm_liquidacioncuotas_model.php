<?php
/**
 * Cnm_liquidacioncuotas (class CI_Model) :)
 *
 * Liquidación de cuotas de la cartera no misional
 *
 * Permite calcular las cuotas de la cartera No Misional según su configuración.
 *
 * @author Felipe Camacho [camachogfelipe]
 * @author http://www.cogroupsas.com
 *
 * @package Cnm_liquidacioncuotas
 */
class Cnm_liquidacioncuotas_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }
	
	function ObtenerDataParaCuota($cod_cartera = NULL) {
		if (!is_null($cod_cartera) and is_numeric($cod_cartera)) :
			$this->db->select("COD_CARTERA_NOMISIONAL, COD_TIPOCARTERA, VALOR_DEUDA, CALCULO_CORRIENTE, TIPO_T_F_CORRIENTE, TIPO_T_V_CORRIENTE, " .
												"VALOR_T_CORRIENTE, PLAZO_CUOTAS, COD_PLAZO, COD_TIPOCARTERA, COD_FORMAPAGO, CALCULO_SEGURO, TIPO_T_F_SEGURO, " .
												"TIPO_T_V_SEGURO, VALOR_T_SEGURO, INCREMENTO_CUOTA, FECHA_INCREMENTO, SALDO_DEUDA, VALOR_CUOTA_APROBADA");
			$this->db->where("COD_CARTERA_NOMISIONAL", $cod_cartera);
      $dato = $this->db->get("CNM_CARTERANOMISIONAL");//echo $this->db->last_query();//exit;
      $dato = $dato->row_array();
      if (!empty($dato)) :
        return $dato;
      else : return false;
      endif;
		else : return false;
    endif;
	}
	
	function ObtenerDataParaCuotaHipotecario($cod_cartera = NULL) {
		if (!is_null($cod_cartera) and is_numeric($cod_cartera)) :
			$this->db->select("SALARIO, FACTOR_TIPO, TASA_IPC, TASA_INTERES_CESANTIAS, VALOR_CUOTA_APROBADA, GRADIENTE");
			$this->db->select("TO_CHAR(FECHA_RETIRO, 'YYYY') AS FECHA_RETIRO_ANIO", FALSE);
			$this->db->select("TO_NUMBER(TO_CHAR(FECHA_RETIRO, 'MM')) AS FECHA_RETIRO_MES", FALSE);
			$this->db->where("COD_CARTERA", $cod_cartera);
      $dato = $this->db->get("CNM_CARTERA_PRESTAMO_HIPOTEC");//echo $this->db->last_query();exit;
      $dato = $dato->row_array();
      if (!empty($dato)) :
        return $dato;
      else : return false;
      endif;
		else : return false;
    endif;
	}
	
	function TraerValorTasaVariable($cod_cartera) {
		$this->db->select("REPLACE(TO_NUMBER(RANGOSTASAHISTORICA.VALOR_TASA), ',', '.') AS VALOR_TASA", FALSE);
		$this->db->select("TO_NUMBER(TO_CHAR(RANGOSTASAHISTORICA.FECHA_INI_VIGENCIA, 'MM')) AS MES_DESDE", FALSE);
		$this->db->select("TO_NUMBER(TO_CHAR(RANGOSTASAHISTORICA.FECHA_INI_VIGENCIA, 'YYYY')) AS YEAR", FALSE);
		$this->db->select("TO_NUMBER(TO_CHAR(RANGOSTASAHISTORICA.FECHA_FIN_VIGENCIA, 'MM')) AS MES_HASTA", FALSE);
		$this->db->join("TIPO_TASA_HISTORICA", "RANGOSTASAHISTORICA.COD_TIPO_TASA_HIST=TIPO_TASA_HISTORICA.COD_TIPO_TASA_HIST", "inner");
		$this->db->join("CNM_CARTERANOMISIONAL", "CNM_CARTERANOMISIONAL.TIPO_T_V_CORRIENTE=TIPO_TASA_HISTORICA.COD_TIPO_TASA_HIST", "inner");
		$this->db->where("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", $cod_cartera);
		$this->db->where("RANGOSTASAHISTORICA.FECHA_FIN_VIGENCIA>=", "TO_CHAR(SYSDATE)", FALSE);
		$this->db->where("RANGOSTASAHISTORICA.FECHA_INI_VIGENCIA<=", "TO_CHAR(SYSDATE)", FALSE);
		$dato = $this->db->get("RANGOSTASAHISTORICA");// echo $this->db->last_query();exit;
		$tasas = $dato->row_array();
		if($dato->num_rows() > 0) :
			$this->load->helper("liquidaciones");
			return ((pow(1+($tasas['VALOR_TASA']/100),1/12)-1)*100)/100;
		endif;
	}
	
	function DefinirTipoTasa($tipo, $tasa_interes) {
		//echo $tipo." - ".$tasa_interes;
		$this->db->select("TIPOTASAINTERES.COD_TIPOTASAINTERES, TIPOTASAINTERES.COD_TIPOPERIODO");
		$this->db->from("TIPOTASAINTERES");
		$this->db->where("TIPOTASAINTERES.COD_TIPO_TASAINTERES", $tipo);
		$dat = $this->db->get();
		$dato = $dat->row_array();//print_r($dato);exit;
		if($dat->num_rows() > 0) :
			switch($dato['COD_TIPOTASAINTERES']) :
				case 1 : $tasa = ((pow(1+($tasa_interes/100),1/12)-1)*100)/100; break;
				case 2 :
					switch($dato['COD_TIPOPERIODO']) :
						case 1 : $tasa = ($tasa_interes / 360); break;
						case 2 : $tasa = ($tasa_interes / 180); break;
						case 3 : $tasa = ($tasa_interes / 90); break;
						case 4 : $tasa = ($tasa_interes / 30); break;
						case 5 : $tasa = $tasa_interes; break;
					endswitch;
					break;
				default: return 0; break;
			endswitch;
		endif;
		//echo $tasa."<br>";exit;
		return $tasa;
	}
	
	function ObtenerCuotaMinima($cod_cartera = NULL) {
		if(!is_null($cod_cartera)) :
			/*"SELECT NO_CUOTA AS MIN_CUOTA, SALDO_FINAL_CAP, VALOR_CUOTA 
												FROM CNM_CUOTAS 
												INNER JOIN (SELECT	MIN(NO_CUOTA) AS MIN_CUOTA FROM CNM_CUOTAS WHERE ID_DEUDA_E=$cod_cartera AND SALDO_CUOTA>'0' AND 
												ANULADO='N') ON (MIN_CUOTA = NO_CUOTA) WHERE ID_DEUDA_E=$cod_cartera"*/
			
			//Obtenemos la cuota mínima desde donde se va a volver a proyectar
			$mincuota = $this->db->query("
			SELECT MIN(NO_CUOTA) AS MIN_CUOTA
										FROM SENA.CNM_CUOTAS 
										WHERE (ID_DEUDA_E = $cod_cartera) 
										AND (SALDO_CUOTA = VALOR_CUOTA) 
										AND (TO_CHAR(FECHA_LIM_PAGO, 'YYYYMM') <> TO_CHAR(SYSDATE, 'YYYYMM')) 
										AND CONCEPTO NOT IN (12, 13) 
										AND CESANTIAS_APLICADAS<>1
			", FALSE);
			$mincuota = $mincuota->row_array();//echo $this->db->last_query();
			$result['MIN_CUOTA'] = $mincuota['MIN_CUOTA'];
			//Obtenemos el saldo de los intereses no pagos
			$intnopagos = $this->db->query("SELECT SALDO_INTERES_NO_PAGOS, SALDO_AMORTIZACION 
											FROM CNM_CUOTAS WHERE (ID_DEUDA_E=$cod_cartera) AND 
											(NO_CUOTA = '".$result['MIN_CUOTA']."'-1) 
											AND CONCEPTO NOT IN (12, 13)");
			$intnopagos = $intnopagos->row_array();//echo $this->db->last_query();
			$result['SALDO_INTERES_NO_PAGOS'] = $intnopagos['SALDO_INTERES_NO_PAGOS'];
			if(is_null($result['SALDO_INTERES_NO_PAGOS']) || empty($result['SALDO_INTERES_NO_PAGOS']))
				$result['SALDO_INTERES_NO_PAGOS'] = 0;
				
			//obtenemos el capital a proyectar
			$capital = $this->db->query("SELECT SALDO_DEUDA AS SALDO_FINAL_CAP FROM CNM_CARTERANOMISIONAL WHERE COD_CARTERA_NOMISIONAL=$cod_cartera", FALSE);
			$capital = $capital->row_array();//echo $this->db->last_query();
			$result['SALDO_FINAL_CAP'] = $capital['SALDO_FINAL_CAP']-$intnopagos['SALDO_AMORTIZACION'];
			//Obtenemos el minimo mes proyectado
			$mes = $this->db->query("SELECT MES_PROYECTADO 
									FROM CNM_CUOTAS WHERE (ID_DEUDA_E=$cod_cartera) AND 
									(NO_CUOTA = '".$result['MIN_CUOTA']."'-1) 
									AND CONCEPTO NOT IN (12, 13)");
			$mes = $mes->row_array();//echo $this->db->last_query();
			$result['MES_PROYECTADO'] = $mes['MES_PROYECTADO'];
			
			//Obtenemos el valor de la cuota calculada
			$cuota = $this->db->query("SELECT MIN(VALOR_CUOTA) AS VALOR_CUOTA 
										FROM CNM_CUOTAS WHERE (ID_DEUDA_E=$cod_cartera) AND 
										(NO_CUOTA = '".$result['MIN_CUOTA']."') 
										AND CONCEPTO NOT IN (12, 13)");
			$cuota = $cuota->row_array();//echo $this->db->last_query();
			$result['VALOR_CUOTA'] = $cuota['VALOR_CUOTA'];
			//Devolvemos el resultado
			return $result;
		else :
			return false;
		endif;
	}
	
	function ObtenerPagos($cod_cartera = NULL) {
		if(!is_null($cod_cartera)) :
			$this->db->select("SUM(VALOR_CUOTA - SALDO_CUOTA) AS PAGADO");
			$this->db->where("ID_DEUDA_E", $cod_cartera);
			$pagado = $this->db->get("CNM_CUOTAS");
			$pagado = $pagado->row_array();
			return $pagado['PAGADO'];
		endif;
		return 0;
	}
	
	function BorrarCuotas($cod_cartera = NULL, $min_cuota = NULL) {
		if(!is_null($cod_cartera)) :
			if(!is_null($min_cuota) and is_numeric($min_cuota) and $min_cuota > 1) :
				$this->db->where("NO_CUOTA >=", $min_cuota);
			endif;
			$this->db->where("ID_DEUDA_E", $cod_cartera);
			$this->db->delete("CNM_CUOTAS");//echo $this->db->last_query();
		endif;
	}
}

?>