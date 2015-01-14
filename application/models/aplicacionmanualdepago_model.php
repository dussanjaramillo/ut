<?php
/**
 * Aplicacionmanualdepago (class CI_Model) :)
 *
 * Aplicación manual de pagos
 *
 * Permite aplicar pagos manualmente.
 * Depende de Apicación automatica de pagos para que el pago quede aplicado.
 *
 * @author Felipe Camacho [camachogfelipe]
 * @author http://www.cogroupsas.com
 *
 * @package Aplicacionmanualdepago
 */
class Aplicacionmanualdepago_model extends CI_Model {

  private $nit;
  private $razon;
  private $concepto;
	private $subconcepto;
	private $fechaPago;

  function __construct() {
    parent::__construct();
  }

  function SetNit($nit) {
    $this->nit = $nit;
  }

  function SetRazon($razon) {
    $this->razon = mb_strtolower($razon);
  }

  function SetConcepto($concepto) {
    $this->concepto = $concepto;
  }
	
	function SetSubConcepto($sconcepto) {
    $this->subconcepto = $sconcepto;
  }
	
	function SetFechaPago($fecha) {
    $this->fechaPago = $fecha;
  }

  function buscar() {
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

  public function ObtenerEmpresa() {
    if (!empty($this->nit)) :
      $this->db->select("EMPRESA.CODEMPRESA, EMPRESA.RAZON_SOCIAL, REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL");
      $this->db->join("REGIONAL", "EMPRESA.COD_REGIONAL=REGIONAL.COD_REGIONAL");
      $this->db->where("EMPRESA.CODEMPRESA", $this->nit);
      $dato = $this->db->get("EMPRESA");
			if($dato->num_rows() > 0) :
				$dato = $dato->result_array();
				return $dato[0];
			else : return NULL;
			endif;
    endif;
  }

  public function ObtenerBancos() {
    $this->db->select("IDBANCO, NOMBREBANCO");
    $this->db->where("IDESTADO", 1);
		$this->db->order_by("NOMBREBANCO", "asc");
    $dato = $this->db->get("BANCO");
    return $dato->result_array();
  }

  public function ObtenerSubconceptos() {
		if(!empty($this->concepto)) :
			$this->db->where("CODTIPOCONCEPTO", $this->concepto);
		endif;
    $dato = $this->db->order_by("NOMBRE_CONCEPTO", "ASC")->get("CONCEPTORECAUDO");//echo $this->db->last_query();
    return $dato->result_array();
  }

  public function ObtenerConceptosFiscalizacion() {
    if (!empty($this->concepto))
      $this->db->where("COD_CPTO_FISCALIZACION", $this->concepto);
    $dato = $this->db->order_by("NOMBRE_CONCEPTO", "ASC")->get("CONCEPTOSFISCALIZACION");
    return $dato->result_array();
  }
	
	public function ObtenerConceptos() {
    $dato = $this->db->order_by("NOMBRE_TIPO", "ASC")->get("TIPOCONCEPTO");
    return $dato->result_array();
  }

  public function ObtenerRegionales() {
    $this->db->select("REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL");
    $dato = $this->db->get("REGIONAL");
    return $dato->result_array();
  }

  public function ObtenerFormasPago() {
    $this->db->select("FORMAPAGO.COD_FORMAPAGO, FORMAPAGO.NOMBE_FORMAPAGO");
    $this->db->where("FORMAPAGO.COD_ESTADO", 1);
		$this->db->where_in("FORMAPAGO.COD_FORMAPAGO", array(1, 2));
    $dato = $this->db->get("FORMAPAGO");
    return $dato->result_array();
  }

  public function ObtenerPagosPendientes() {
    if (!empty($this->nit) and !empty($this->concepto) and !empty($this->subconcepto)) :
			$this->db->select("NUM_LIQUIDACION, TOTAL_LIQUIDADO, SALDO_DEUDA, CEIL(SYSDATE - FECHA_VENCIMIENTO) AS DIAS_MORA, " .
												"TOTAL_INTERESES, TOTAL_CAPITAL, COD_FISCALIZACION, SALDO_CAPITAL, SALDO_INTERES, COD_TIPOPROCESO AS PROCESO, " .
												"COD_PROCESO_COACTIVO");
			$this->db->select("DECODE(LIQUIDACION.COD_TIPOPROCESO, '2', 'ACUERDO', '18', 'ACUERDO', 'NO') AS COD_TIPOPROCESO", FALSE);
			$this->db->select("TO_CHAR(LIQUIDACION.FECHA_INICIO, 'YYYY-MM-DD') AS FECHA_INICIO", FALSE);
			$this->db->select("TO_CHAR(LIQUIDACION.FECHA_FIN, 'YYYY-MM-DD') AS FECHA_FIN", FALSE);
			$this->db->select("TO_CHAR(LIQUIDACION.FECHA_VENCIMIENTO, 'YYYY-MM-DD') AS FECHA_VENCIMIENTO", FALSE);
			$this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
			$this->db->where("LIQUIDACION.COD_CONCEPTO", $this->concepto);
			$this->db->where("LIQUIDACION.NITEMPRESA", $this->nit);
			$this->db->where("LIQUIDACION.SALDO_DEUDA >", 0);
			$this->db->where("LIQUIDACION.BLOQUEADA", 0);
			$this->db->where("LIQUIDACION.EN_FIRME", "S");
			$this->db->order_by("LIQUIDACION.FECHA_INICIO", "ASC");
			$this->db->order_by("LIQUIDACION.FECHA_FIN", "ASC");
			$this->db->order_by("LIQUIDACION.NUM_LIQUIDACION", "ASC");
      $dato = $this->db->get("LIQUIDACION");//echo $this->db->last_query();exit;
      $dato = $dato->result_array();
      if (!empty($dato)) :
				$acuerdos = array();
				foreach($dato as $key=>$cartera) :
					if($cartera['COD_TIPOPROCESO'] == "ACUERDO") :
						if($cartera['PROCESO'] == 2) :
							$dato[$key]['CUOTAS'] = $this->ObtenerCuotasAcuerdos($this->nit, $cartera['NUM_LIQUIDACION'], $this->fechaPago, $cartera['PROCESO']);
						else :
							if(!in_array($cartera['COD_PROCESO_COACTIVO'], $acuerdos)) :
								$dato[$key]['CUOTAS'] = $this->ObtenerCuotasAcuerdos($this->nit, $cartera['COD_PROCESO_COACTIVO'], $this->fechaPago, $cartera['PROCESO'], $acuerdos);
								$acuerdos[] = $cartera['COD_PROCESO_COACTIVO'];
							endif;
						endif;
					endif;
				endforeach;
        return $dato;
      else : return $dato = NULL;
      endif;
    endif;
  }
	
	private function ObtenerCuotasAcuerdos($NRO_IDENTIFICACION, $NRO_REFERENCIAPAGO, $FECHA_PAGO, $COD, $ACUERDOS = NULL) {
		if(!empty($NRO_IDENTIFICACION) and !empty($NRO_REFERENCIAPAGO)) :
			$this->db->select("PROYECCIONACUERDOPAGO.PROYACUPAG_SALDOCAPITAL AS SALDO_CAPITAL, " .
												"PROYECCIONACUERDOPAGO.PROYACUPAG_SALDO_INTCORRIENTE AS SALDO_INTCORRIENTE, " .
												"PROYECCIONACUERDOPAGO.PROYACUPAG_SALDO_INTACUERDO AS SALDO_INTACUERDO, " .
												"PROYECCIONACUERDOPAGO.PROYACUPAG_SALDO_CUOTA AS SALDO_CUOTA, " .
												"PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA AS NO_CUOTA, " .
												"PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO");
			$this->db->select("CASE WHEN CEIL(SYSDATE - PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO) < 0 THEN 0 ELSE CEIL(SYSDATE - PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO) END AS DIAS_MORA", FALSE);
			$this->db->select("TO_CHAR(PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO,'YYYY-MM-DD') AS FECHA_VENCIMIENTO", FALSE);
			if($COD == 2) :
				$acuerdo = " AND ACUERDOPAGO.NRO_LIQUIDACION='" . $NRO_REFERENCIAPAGO . "'";
			elseif($COD == 18) :
				$acuerdo = " AND ACUERDOPAGO.COD_PROCESO_COACTIVO='" . $NRO_REFERENCIAPAGO . "'";
				$acuerdo .= " AND ACUERDOPAGO.COD_PROCESO_COACTIVO NOT IN (" . implode(",", $ACUERDOS) . ")";
			endif;
			$this->db->join("ACUERDOPAGO", "PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO=ACUERDOPAGO.NRO_ACUERDOPAGO" . 
											" AND ACUERDOPAGO.ESTADOACUERDO='1' " . $acuerdo .
											" AND ACUERDOPAGO.NITEMPRESA='" . $NRO_IDENTIFICACION . "'");
			$this->db->where("PROYECCIONACUERDOPAGO.PROYACUPAG_ESTADO", "0");
			$this->db->order_by("PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA");
			$dato = $this->db->get("PROYECCIONACUERDOPAGO");//echo $this->db->last_query();exit;
			return $dato->result_array();
		else :
			return FALSE;
		endif;
	}
	
	function ObtenerPagosPendientesNoMisional() {
		if (!empty($this->nit) and !empty($this->concepto) and !empty($this->subconcepto)) :
			$this->db->select("CNM_CUOTAS.NO_CUOTA, CNM_CUOTAS.SALDO_CUOTA, CNM_CUOTAS.MES_PROYECTADO, CNM_CUOTAS.SALDO_AMORTIZACION, " .
												"CNM_CUOTAS.SALDO_AMORTIZACION, CNM_CUOTAS.SALDO_INTERES_C, CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, " .
												"CNM_CARTERANOMISIONAL.CALCULO_MORA, CNM_CARTERANOMISIONAL.VALOR_T_MORA, CNM_CARTERANOMISIONAL.TIPO_T_F_MORA, " .
												"CNM_CARTERANOMISIONAL.TIPO_T_V_MORA");
			$this->db->select("DECODE(CEIL(TO_DATE('".$this->concepto."', 'YYYY-MM-DD') - CNM_CUOTAS.FECHA_LIM_PAGO) / ". 
												"ABS(CEIL(TO_DATE('".$this->concepto."', 'YYYY-MM-DD') - CNM_CUOTAS.FECHA_LIM_PAGO)), -1, 0, " . 
												"CEIL(TO_DATE('".$this->concepto."', 'YYYY-MM-DD') - CNM_CUOTAS.FECHA_LIM_PAGO)) AS DIAS_MORA", FALSE);
			$this->db->select("TO_CHAR(CNM_CUOTAS.FECHA_LIM_PAGO, 'YYYY-MM-DD') AS FECHA_LIM_PAGO", FALSE);
			$this->db->join("CNM_CARTERANOMISIONAL", "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E AND " .
											"(CNM_CARTERANOMISIONAL.COD_EMPLEADO='".$this->nit."' OR " .
											"CNM_CARTERANOMISIONAL.COD_EMPRESA='".$this->nit."')", "inner");
			$this->db->join("CONCEPTO_HOMOLOGACION","CONCEPTO_HOMOLOGACION.CONCEPTO_HOMOLOGADO=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA" .
											" AND CONCEPTO_HOMOLOGACION.TIPO_FUENTE='3' AND CONCEPTO_HOMOLOGACION.ESTADO='A' AND " .
											"CONCEPTO_HOMOLOGACION.COD_CONCEPTO_RECAUDO='" . $this->subconcepto . "' ", 'inner');
			$this->db->where("CNM_CUOTAS.SALDO_CUOTA >", 0);
			$this->db->where("CNM_CUOTAS.ANULADO", "N");
			$this->db->where_not_in("CNM_CUOTAS.CONCEPTO", array(12, 13));
			$this->db->order_by("CNM_CUOTAS.FECHA_LIM_PAGO", "ASC");
      $dato = $this->db->get("CNM_CUOTAS");//echo $this->db->last_query();exit;
      $dato = $dato->result_array();
      if (!empty($dato)) :
        return $dato;
      else : return $dato = NULL;
      endif;
    endif;
	}

  public function ValidaPago($datos) {
    if (!empty($datos)) :
      $this->db->select("OTROSPAGOS_DET.NRO_DOCUMENTO");
      $this->db->join("OTROSPAGOS_DET", "OTROSPAGOS.COD_TRANSACCION=OTROSPAGOS_DET.COD_TRANSACCION AND OTROSPAGOS_DET.NRO_DOCUMENTO='" . $datos['NRO_DOCUMENTO'] . "'");
      $this->db->where("OTROSPAGOS.NIT_EMPRESA", $datos['NIT_EMPRESA']);
			if($datos['subconcepto'] != "17" and $datos['subconcepto'] != "19" and $datos['subconcepto'] != "21") :
	      $this->db->where("OTROSPAGOS.NRO_REFERENCIA", $datos['NRO_REFERENCIA']);
			endif;
      $dato = $this->db->get("OTROSPAGOS");
      $dato = $dato->row();
      if (!empty($dato->NRO_DOCUMENTO)) : return true;
      else : return false;
      endif;
    else : return false;
    endif;
  }

  public function AplicarPago($datos = NULL, $datos2 = NULL) {
    if (!empty($datos)) :
      $this->db->set("FECHA_PAGO", "TO_DATE('" . $datos['FECHA_PAGO'] . "', 'DD/MM/YYYY')", FALSE);
      $this->db->set("FECHA_TRANSACCION", "TO_DATE('" . $datos['FECHA_PAGO'] . "', 'DD/MM/YYYY')", FALSE);
      unset($datos['FECHA_PAGO']);
      unset($datos['FECHA_TRANSACCION']);
			if($datos['subconcepto'] == "17" || $datos['subconcepto'] == "19" || $datos['subconcepto'] == "21") :
				$datos['NRO_REFERENCIA'] = 0;
				$datos['VALOR_ADEUDADO'] = 0;
				$datos['APLICADO'] = 1;
			endif;
			unset($datos['subconcepto']);

      // COMIENZO DE LA TRANSACCION
      $this->db->trans_begin();

      $this->db->insert("OTROSPAGOS", $datos);

      //Adquirimos codigo otrospagos
      $query = $this->db->query("SELECT OtrosPagos_cod_transaccion_SEQ.CURRVAL FROM dual");
      $row = $query->row_array();
      $id = $row['CURRVAL'];

      $datos2['COD_TRANSACCION'] = $id;

      $this->db->insert("OTROSPAGOS_DET", $datos2);

      if ($this->db->trans_status() === FALSE) :
        $this->db->trans_rollback();
        return false;
      else :
        $this->db->trans_commit();
        return $id;
      endif;
    else : return false;
    endif;
  }

  private function mes($mes) {
    switch ($mes) :
      case "01" : return "Ene";
        break;
      case "02" : return "Feb";
        break;
      case "03" : return "Mar";
        break;
      case "04" : return "Abr";
        break;
      case "05" : return "May";
        break;
      case "06" : return "Jun";
        break;
      case "07" : return "Jul";
        break;
      case "08" : return "Ago";
        break;
      case "09" : return "Sep";
        break;
      case "10" : return "Oct";
        break;
      case "11" : return "Nov";
        break;
      case "12" : return "Dic";
        break;
    endswitch;
  }

  public function ValidarExtracto($datos) {
    if (!empty($datos)) :
      $dato = $this->db->get_where("EXTRACTOS", $datos);
      $dato = $dato->result_array();
      if (!empty($dato)) :
        $datos['datos'] = true;
        $datos['id'] = $dato[0]['COD_EXTRACTO'];
        return $datos;
      else : return array("datos" => false);
      endif;
    endif;
  }

  public function extracto($datos) {
    if (!empty($datos)) :
      if (isset($datos['COD_EXTRACTO'])) :
        $this->db->where("COD_EXTRACTO", $datos['COD_EXTRACTO']);
        unset($datos['COD_EXTRACTO']);
        $this->db->update("EXTRACTOS", $datos);
      else : $this->db->insert("EXTRACTOS", $datos);
      endif;

    endif;
  }

	public function ObtenerOperador() {
		$this->db->select("COD_TIPO_OPERADOR, NOMBRE_OPERADOR || ' - ' || ENTIDAD_RECAUDO AS NOMBRE_OPERADOR");
    $dato = $this->db->get("TIPO_OPERADOR_PILA");
    return $dato->result_array();
	}
	
	public function TrearCuentasBancarias($idBanco = NULL) {
		if(!is_null($idBanco)) :
			$this->db->select("NUMERO_CUENTA, UPPER(DESCRIPCION) AS DESCRIPCION");
			$this->db->where("IDBANCO", $idBanco);
			$this->db->where("ACTIVO", 1);
			$this->db->order_by("NUMERO_CUENTA");
			$dato = $this->db->get("MAESTRO_CUENTAS_BANCARIAS");
			$dato = $dato->result_array();
      if (!empty($dato)) :
        return $dato;
      else : return false;
      endif;
		endif;
	}
}

?>