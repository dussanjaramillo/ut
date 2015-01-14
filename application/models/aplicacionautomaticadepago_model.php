<?php
/**
 * Aplicacionautomaticadepago (class CI_Model) :)
 *
 * Aplicación automatica de pagos
 *
 * Permite aplicar pagos automaticamente.
 *
 * @author Felipe Camacho [camachogfelipe]
 * @author http://www.cogroupsas.com
 *
 * @package Aplicacionautomaticadepago
 */
class Aplicacionautomaticadepago_model extends CI_Model {

  private $nit;
  private $procedencia;
  private $periodo_inicio;
  private $periodo_fin;
  private $referencia;
  private $deuda;
  private $datos_aplicar;
  private $fiscalizacion;
	private $fecha_pago;

  function __construct() {
    parent::__construct();
  }

  function set_nit($nit) {
    $this->nit = $nit;
  }

  function set_procedencia($procedencia) {
    switch ($procedencia) :
      case "ECOLLECT" : $this->procedencia = "ECOLLECT";
        return true;
        break;
      case "MANUAL" : $this->procedencia = "MANUAL";
        return true;
        break;
      case "RECLASIFICACION" : $this->procedencia = "RECLASIFICACION";
        return true;
        break;
      case "KAKTUS" : $this->procedencia = "KAKTUS";
        return true;
        break;
      case "ASOBANCARIA" : $this->procedencia = "ASOBANCARIA";
        return true;
        break;
      case "JURIDICO"   : $this->procedencia = "JURIDICO";
        return true;
        break;
      case "EMBARGO"   : $this->procedencia = "EMBARGO";
        return true;
        break;
      default : return false;
        break;
    endswitch;
    $this->procedencia = $procedencia;
  }

  function set_periodo_inicio($periodo_inicio) {
    $this->periodo_inicio = $periodo_inicio;
  }

  function set_periodo_fin($periodo_fin) {
    $this->periodo_fin = $periodo_fin;
  }

  function set_referencia($referencia) {

    $this->referencia = $referencia;
  }

  private function set_deuda() {
    $this->deuda = $this->get_obligacion();
  }

  private function get_acuerdo_pago() {
    if (!empty($this->nit)) :
      $this->db->select("(ACUERDOPAGO.ESTADO_ACUERDO, " .
              "PROYECCIONACUERDOPAGO.PROYACUPAG_VALORCUOTA, PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO, " .
              "PROYECCIONACUERDOPAGO.PROYACUPAG_SALDOCAPITAL, " .
              "PROYECCIONACUERDOPAGO.PROYACUPAG_VALORINTERESESMORA");
      $this->db->join("PROYECCIONACUERDOPAGO", "PROYECCIONACUERDOPAGO.NUMERO_RESOLUCION='" . $this->deuda['RESOLUCION'] . "'" .
              " AND PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO=ACUERDOPAGO.NRO_ACUERDOPAGO" .
              " AND PROYECCIONACUERDOPAGO.PROYACUPAG_ESTADO='1'");
      $this->db->where("ACUERDOPAGO.NITEMPRESA", $this->nit);
			$this->db->where("PROYECCIONACUERDOPAGO.PROYACUPAG_ESTADO", "0");
      $dato = $this->db->get("ACUERDOPAGO");
      $dato = $dato->result_array();
      if (!empty($dato)) :
				return $dato;
			else : return NULL;
      endif;
    endif;
  }

  private function get_obligacion() {
    if (!empty($this->nit)) :
			$this->db->select("TO_CHAR( LIQUIDACION.FECHA_LIQUIDACION, 'YYYY-MM-DD') AS  FECHA_LIQUIDACION", FALSE);
			$this->db->select("TO_CHAR( LIQUIDACION.FECHA_VENCIMIENTO, 'YYYY-MM-DD') AS  FECHA_VENCIMIENTO", FALSE);
      $this->db->select("LIQUIDACION.NUM_LIQUIDACION, LIQUIDACION.COD_FISCALIZACION, LIQUIDACION.TOTAL_LIQUIDADO, LIQUIDACION.TOTAL_INTERESES, "
              . "LIQUIDACION.COD_TIPOPROCESO, LIQUIDACION.SALDO_DEUDA, LIQUIDACION.TOTAL_CAPITAL, LIQUIDACION.COD_CONCEPTO, SALDO_CAPITAL, SALDO_INTERES");
      $this->db->where("LIQUIDACION.NUM_LIQUIDACION", $this->referencia);
      $this->db->where("LIQUIDACION.NITEMPRESA", $this->nit);
      $dato = $this->db->get("LIQUIDACION");//echo $this->db->last_query();exit;
      $dato = $dato->row_array();
			//echo "<pre>";print_r($dato);echo "</pre>";exit();
      if (!empty($dato)) :
				$dato['ACUERDO'] = NULL;
        $this->fiscalizacion = $dato['COD_FISCALIZACION'];
        if ($dato['COD_TIPOPROCESO'] == 2 || $dato['COD_TIPOPROCESO'] == 18) :
					$tmpdato = $this->get_obligacion_acuerdo_pago(false);
					if(!is_null($tmpdato)) :
						$dato['ACUERDO'] = $tmpdato;
						$saldo_real = 0;
						foreach($tmpdato as $cuota) :
							$saldo_real += $cuota['VALOR_CUOTA'];
						endforeach;
						$dato['SALDO_DEUDA'] = $saldo_real;
					endif;
        endif;
				//echo "<pre>";print_r($dato);exit("</pre>");
        return $dato;
      else : return $dato = NULL;
      endif;
    endif;
  }

  private function get_obligacion_acuerdo_pago($traer_obligacion = false) {
    if (!empty($this->nit)) :
			if($traer_obligacion == true) $pagos = $this->get_obligacion_paga();
			else $pagos = "1";
      if (!empty($pagos)) :
				$this->db->select("PROYECCIONACUERDOPAGO.PROYACUPAG_SALDO_CUOTA AS VALOR_CUOTA, PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA AS NO_CUOTA, " .
													"PROYECCIONACUERDOPAGO.PROYACUPAG_SALDO_INTCORRIENTE, PROYECCIONACUERDOPAGO.PROYACUPAG_SALDO_INTACUERDO, " .
													"PROYECCIONACUERDOPAGO.PROYACUPAG_SALDOCAPITAL, PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO");
				$this->db->select("TO_CHAR(PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO,'YYYY/MM/DD') AS FECHA_VENCIMIENTO", FALSE);
				$this->db->select("CASE WHEN CEIL(SYSDATE - PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO) < 0 THEN 0 ELSE CEIL(SYSDATE - PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO) END AS DIAS_MORA", FALSE);
				$this->db->join("ACUERDOPAGO", "PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO=ACUERDOPAGO.NRO_ACUERDOPAGO" . 
												" AND ACUERDOPAGO.ESTADOACUERDO='1' AND ACUERDOPAGO.NRO_LIQUIDACION='" . $this->referencia . "'" .
												" AND ACUERDOPAGO.NITEMPRESA='" . $this->nit . "'");
				$this->db->where("PROYECCIONACUERDOPAGO.PROYACUPAG_ESTADO", "0");
				$this->db->order_by("PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA");
				$dato = $this->db->get("PROYECCIONACUERDOPAGO");//echo $this->db->last_query();
				return $dato->result_array();
      endif;
			return NULL;
    endif;
		return NULL;
  }

  private function get_obligacion_paga() {
    if (!empty($this->nit)) :
      $datos = $this->get_obligacion();
      if (!empty($dato) and $dato['SALDO_DEUDA'] > 0) : return $dato['SALDO_DEUDA'];
      else : return $dato = NULL;
      endif;
    endif;
  }

  public function valida_documento($documento) {
    $this->db->select("PAGOSRECIBIDOS.NUM_DOCUMENTO");
		$this->db->where("PAGOSRECIBIDOS.NUM_DOCUMENTO", $documento);
		$dato = $this->db->get("PAGOSRECIBIDOS");
		$dato = $dato->row();
		if (!empty($dato)) : return true;
		else : return FALSE;
		endif;
  }

  private function valida_obligacion_paga() {
    $deuda = $this->get_obligacion_paga();
    if (!is_null($deuda)) :
      return true;
    else : return false;
    endif;
  }

  private function valida_fecha_pago($datos) {
		//echo "<pre>";print_r($datos);exit("</pre>");
    if (!is_null($this->deuda)) :
			//echo "<pre>";print_r($this->deuda);exit("</pre>");
			//echo (is_null($this->deuda['ACUERDO']))?"NULL":"NOT NULL"; exit;
			if(!is_null($this->deuda['ACUERDO'])) :
				$x = 0;
				foreach($this->deuda['ACUERDO'] as $dato) :
					$this->datos_aplicar['dias_mora'][$x] = $this->dias_diferencia($datos['fecha_pago'], $dato['PROYACUPAG_FECHALIMPAGO']);
					$tdm = $this->datos_aplicar['dias_mora'][$x];
					$x++;
					if ($this->datos_aplicar['dias_mora'][$x] > 0) :
						$this->datos_aplicar['DISTRIBUCION_INTERES_MORA'][] = $this->calcular_mora($dato);
					else : $this->datos_aplicar['DISTRIBUCION_INTERES_MORA'][] = 0;
					endif;
				endforeach;
				if(count($this->datos_aplicar['dias_mora'] > 1) and $tdm > 0) :
					return false;
				else :
					return true;
				endif;
			else :
      	$this->datos_aplicar['dias_mora'] = $this->dias_diferencia($datos['fecha_pago'], $this->deuda['FECHA_VENCIMIENTO']);
				if ($this->datos_aplicar['dias_mora'] > 0) :
					$this->datos_aplicar['DISTRIBUCION_INTERES_MORA'] = $this->calcular_mora($datos['fecha_pago'], date("Y-m-d"), $this->deuda['SALDO_DEUDA']);
				else : $this->datos_aplicar['DISTRIBUCION_INTERES_MORA'] = 0;
				endif;
				return true;
			endif;
    else : return false;
    endif;
  }

  function calcular_mora($fecha_inicio, $fecha_fin, $saldo) {
		if(is_array($fecha_inicio)) :
	    $tasas = $this->tasa_interes(implode("-", $fecha_inicio), implode("-", $fecha_fin));
		else :
			$tasas = $this->tasa_interes($fecha_inicio, $fecha_fin);
		endif;
		if(!empty($tasas)) $mora = $this->calculaMora($tasas, $fecha_inicio, $fecha_fin, $saldo);
		else $mora = 0;
		//print_r($tasas);exit;
		return $mora;
  }

  private function tasa_interes($fecha1, $fecha2) {
    $this->db->select("REPLACE(TO_NUMBER(TASA_SUPERINTENDENCIA.TASA_SUPERINTENDENCIA), ',', '.') AS VALOR_TASA", FALSE);
    $this->db->select("TO_NUMBER(TO_CHAR(TASA_SUPERINTENDENCIA.VIGENCIA_DESDE, 'MM')) AS MES_DESDE", FALSE);
    $this->db->select("TO_NUMBER(TO_CHAR(TASA_SUPERINTENDENCIA.VIGENCIA_DESDE, 'YYYY')) AS YEAR", FALSE);
    $this->db->select("TO_NUMBER(TO_CHAR(TASA_SUPERINTENDENCIA.VIGENCIA_HASTA, 'MM')) AS MES_HASTA", FALSE);
    $this->db->where("TASA_SUPERINTENDENCIA.ID_TIPO_TASA", "1");
    $this->db->where("TASA_SUPERINTENDENCIA.VIGENCIA_HASTA>= ", "TO_DATE('" . $fecha1 . "', 'YYYY-MM-DD')", FALSE);
    $this->db->where("TASA_SUPERINTENDENCIA.VIGENCIA_DESDE <= ", "TO_DATE('" . $fecha2 . "', 'YYYY-MM-DD')", FALSE);
    $dato = $this->db->get("TASA_SUPERINTENDENCIA");// echo $this->db->last_query();exit;
		if($dato->num_rows() > 0) :
	    $dato = $dato->result_array();
			return $dato;
		else : return 0;
    endif;
  }

  private function obtenerDiasMes($Month, $Year) {
    if (is_callable("cal_days_in_month"))
      return cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
    else
      return date("d", mktime(0, 0, 0, $Month + 1, 0, $Year));
  }

  private function error($error = NULL) {
    switch ($error) :
      case 1 : return array(FALSE, "300: DATOS VACÍOS. PAGO NO APLICADO");
        break;
      case 2 : return array(FALSE, "301: NO DEFINE PROCEDENCIA DEL PROCESO. PAGO NO APLICADO");
        break;
      case 3 : return array(FALSE, "302: No. DE IDENTIFICACIÓN NO DEFINIDO. PAGO NO APLICADO");
        break;
      case 4 : return array(FALSE, "303: FECHA DE PAGO NO DEFINIDA. PAGO NO APLICADO");
        break;
      case 5 : return array(FALSE, "304: NÚMERO DE REFERENCIA NO DEFINIDO. PAGO NO APLICADO");
        break;
      case 6 : return array(FALSE, "305: VALOR RECIBIDO ESTÁ VACÍO. PAGO NO APLICADO");
        break;
      case 7 : return array(FALSE, "306: FORMA DE PAGO NO DEFINIDA. PAGO NO APLICADO");
        break;
      case 8 : return array(FALSE, "307: PROCEDENCIA DEL PAGO NO APLICA AL SISTEMA. PAGO NO APLICADO");
        break;
      case 9 : return array(FALSE, "308: EL NUMERO DE DOCUMENTO YA FUE APLICADO");
        break;
      case 10 : return array(FALSE, "300: EL NUMERO DE OBLIGACION NO TIENE SALDO PENDIENTE. PAGO NO APLICADO");
        break;
      case 11 : return array(FALSE, "310: NO SE PUEDE APLICAR EL PAGO PORQUE ES UN ACUERDO DE PAGO VENCIDO Y/O ANULADO");
        break;
      case 12 : return array(FALSE, "311: NO SE PUDO APLICAR EL PAGO");
        break;
      default : return array(FALSE, "500: ERROR DESCONOCIDO");
        break;
    endswitch;
  }

  public function aplicar_pago($datos) {
    if (!empty($datos)) :
			switch($datos['COD_SUBCONCEPTO']) :
				case '21'	:	case '17' :	case '19' :	case '59' :	case '62' :	case '77' :	case '102':	case '66' :	case '74' :	
					return $this->AplicarPagoOrdinario($datos);
				case '80' :	case '81' :	case '82' :	case '83' :
					return $this->AplicarPagoCartera($datos, false);
					break;
				case '84' :	case '85' :	case '86' :	case '87' :	case '88' :	case '89' :	case '90' :	case '91' :	case '92' :	case '93' :
					return $this->AplicarPagoCartera($datos, true);
					break;
			endswitch;
		endif;
	}
	
	private function AplicarPagoCartera($datos, $NM = false) {
		if (!empty($datos)) :
      //comenzamos a inicializar las variables
			foreach($datos as $key=>$value) :
				if($key == "SALDO_DEUDA") $this->deuda['SALDO_DEUDA'] = $value;
				elseif($key == "SALDO_CAPITAL") $this->deuda['SALDO_CAPITAL'] = $value;
				elseif($key == "SALDO_INTERES") $this->deuda['SALDO_INTERES'] = $value;
				else $this->datos_aplicar[$key] = $value;
			endforeach;
			$this->referencia = $this->datos_aplicar['NRO_REFERENCIA'];
			//echo "<pre>";print_r($this->datos_aplicar);/*print_r($pago);*/print_r($this->deuda);echo "</pre>";exit;
      $this->datos_aplicar['FECHA_APLICACION'] = date("d/m/Y");
      if ($this->aplica_pago() == true) :
				if($NM == false) $this->actualiza_saldo();
        return true;
      else :
        return $this->error(12);
      endif;
    else : return $this->error(1);
    endif;
  }
	
	private function valida_fecha_pago_reclasificar($datos) {
		//echo "<pre>";print_r($datos);exit("</pre>");
    if (!is_null($this->deuda)) :
			//echo "<pre>";print_r($this->deuda);exit("</pre>");
			//echo (is_null($this->deuda['ACUERDO']))?"NULL":"NOT NULL"; exit;
			if(!is_null($this->deuda['ACUERDO'])) :
				$x = 0;
				foreach($this->deuda['ACUERDO'] as $dato) :
					if ($dato['DIAS_MORA'] > 0) :
						$this->datos_aplicar['DISTRIBUCION_INTERES_MORA'] += $this->calcular_mora(str_replace("/", "-", $dato['FECHA_VENCIMIENTO']), $datos['fecha_pago'], $datos['VALOR_CUOTA']);
					else : $this->datos_aplicar['DISTRIBUCION_INTERES_MORA'] = 0;
					endif;
				endforeach;
				return true;
			else :
      	$this->datos_aplicar['dias_mora'] = $this->dias_diferencia($datos['fecha_pago'], $this->deuda['FECHA_VENCIMIENTO']);
				if ($this->datos_aplicar['dias_mora'] > 0) :
					$this->datos_aplicar['DISTRIBUCION_INTERES_MORA'] = $this->calcular_mora($datos['fecha_pago'], date("Y-m-d"), $this->deuda['SALDO_DEUDA']);
				else : $this->datos_aplicar['DISTRIBUCION_INTERES_MORA'] = 0;
				endif;
				return true;
			endif;
    else : return false;
    endif;
  }

  public function aplicar_reclasificar($datos) {
    if (!empty($datos)) :
			//echo "<pre>";print_r($datos);echo "</pre>";
      //comenzamos a inicializar las variables
      $this->set_nit($datos['nit']);
      $this->set_referencia($datos['iddeuda']);
      $this->set_deuda();
			//echo "<pre>";print_r($this->deuda);echo "</pre>";
      $this->datos_aplicar['APLICADO'] = "1";
      if ($this->deuda['SALDO_DEUDA'] <= '0') :
				return $this->error(10);
      endif;
			$this->db->select("TO_CHAR(FECHA_PAGO, 'YYYY-MM-DD') AS FECHA_PAGO", FALSE);
      $this->db->select("VALOR_PAGADO");
      $this->db->where('COD_PAGO', $datos['idpago']);
      $dat = $this->db->get('PAGOSRECIBIDOS');
      $dat = $dat->row_array();
      $datos['valor_recibido'] = $dat['VALOR_PAGADO'];
      $datos['fecha_pago'] = $dat['FECHA_PAGO'];
			$vfp = $this->valida_fecha_pago_reclasificar($datos);
      if ($vfp == false) return $this->error(11);
			$this->datos_aplicar['FECHA_PAGO'] = $datos['fecha_pago'];
      $this->datos_aplicar['NITEMPRESA'] = $this->nit;
      $this->datos_aplicar['FECHA_APLICACION'] = date("d/m/Y");
			if(!empty($this->deuda['ACUERDO'])) :
				$capital = $intcorriente = $intacuerdo = 0;
				$dis = $datos['valor_recibido'];
				$this->datos_aplicar['DISTRIBUCION_CAPITAL'] = $this->datos_aplicar['DISTRIBUCION_INTERES'] = 0;
				foreach($this->deuda['ACUERDO'] as $value) :
					//echo "dis: ".$dis;
					if($dis > 0) :
						if($value['DIAS_MORA']) :
							$interes_mora = $this->calcular_mora($value['FECHA_VENCIMIENTO'], $datos['fecha_pago'], $value['SALDO_CUOTA']);
						else :
							$interes_mora = 0;
						endif;
						if($dis > $value['VALOR_CUOTA']) :
							$cuota['PROYACUPAG_SALDO_INTACUERDO'] = 
							$cuota['PROYACUPAG_SALDO_INTCORRIENTE'] = 
							$cuota['PROYACUPAG_SALDOCAPITAL'] = 
							$cuota['PROYACUPAG_SALDO_CUOTA'] = 0;
							$cuota['NRO_ACUERDOPAGO'] =  $value['NRO_ACUERDOPAGO'];
							$cuota['CUOTA'] = $value['NO_CUOTA'];
							$cuota["PROYACUPAG_ESTADO"] = 1;
							$this->datos_aplicar['DISTRIBUCION_INTERES'] += $value['PROYACUPAG_SALDO_INTACUERDO'];
							$this->datos_aplicar['DISTRIBUCION_INTERES'] += $value['PROYACUPAG_SALDO_INTCORRIENTE'];
							$this->datos_aplicar['DISTRIBUCION_CAPITAL'] += $value['PROYACUPAG_SALDOCAPITAL'];
							$dis -= $value['VALOR_CUOTA'];
						else :
							//distribucion capital
							//echo " - capital: ".
							$capital = 
							ceil(($dis-$interes_mora)*($value['PROYACUPAG_SALDOCAPITAL']/((
								$value['PROYACUPAG_SALDOCAPITAL']+$value['PROYACUPAG_SALDO_INTACUERDO']+$value['PROYACUPAG_SALDO_INTCORRIENTE']
							))));
							//distribucion interes corriente
							//echo " - int_corriente: ".
							$interes_corriente = 
							floor(($dis-$interes_mora)*($value['PROYACUPAG_SALDO_INTCORRIENTE']/(
								$value['PROYACUPAG_SALDOCAPITAL']+$value['PROYACUPAG_SALDO_INTACUERDO']+$value['PROYACUPAG_SALDO_INTCORRIENTE']
							)));
							//distribucion intereses del acuerdo
							//echo " - int_Acuerdo: ".
							$interes_acuerdo = 
							floor(($dis-$interes_mora)*($value['PROYACUPAG_SALDO_INTACUERDO']/(
								$value['PROYACUPAG_SALDOCAPITAL']+$value['PROYACUPAG_SALDO_INTACUERDO']+$value['PROYACUPAG_SALDO_INTCORRIENTE']
							)));
							$cuota['PROYACUPAG_SALDO_INTACUERDO'] = $value['PROYACUPAG_SALDO_INTACUERDO'] - $interes_acuerdo;
							$cuota['PROYACUPAG_SALDO_INTCORRIENTE'] = $value['PROYACUPAG_SALDO_INTCORRIENTE'] - $interes_corriente;
							$cuota['PROYACUPAG_SALDOCAPITAL'] = $value['PROYACUPAG_SALDOCAPITAL'] - $capital;
							$cuota['PROYACUPAG_SALDO_CUOTA'] = $value['VALOR_CUOTA'] - ($capital+$interes_corriente+$interes_acuerdo);
							$this->datos_aplicar['DISTRIBUCION_INTERES'] += $interes_corriente;
							$this->datos_aplicar['DISTRIBUCION_INTERES'] += $interes_acuerdo;
							$this->datos_aplicar['DISTRIBUCION_CAPITAL'] += $capital;
							$dis -= ($intacuerdo+$intcorriente+$capital);
							$cuota['NRO_ACUERDOPAGO'] =  $value['NRO_ACUERDOPAGO'];
							$cuota['CUOTA'] = $value['NO_CUOTA'];
						endif;
						//echo "<pre>";print_r($cuota);echo "</pre>";
						$this->aplicar_acuerdo($cuota);
					endif;
				endforeach;
			else :
				$this->datos_aplicar['DISTRIBUCION_CAPITAL'] = 
				ceil(($datos['valor_recibido']-$this->datos_aplicar['DISTRIBUCION_INTERES_MORA'])*($this->deuda['TOTAL_CAPITAL']/((
					$this->deuda['TOTAL_CAPITAL']+$this->deuda['TOTAL_INTERESES']
				))));
				
				$this->datos_aplicar['DISTRIBUCION_INTERES'] = 
				floor(($datos['valor_recibido']-$this->datos_aplicar['DISTRIBUCION_INTERES_MORA'])*($this->deuda['TOTAL_INTERESES']/((
					$this->deuda['TOTAL_CAPITAL']+$this->deuda['TOTAL_INTERESES']
				))));
			endif;
			$this->datos_aplicar['COD_CONCEPTO'] = $this->deuda['COD_CONCEPTO'];
			switch($this->deuda['COD_CONCEPTO']) :
				case 1 : $this->datos_aplicar['COD_SUBCONCEPTO'] = 80; break;
				case 2 : $this->datos_aplicar['COD_SUBCONCEPTO'] = 82; break;
				case 3 : $this->datos_aplicar['COD_SUBCONCEPTO'] = 81; break;
				case 5 : $this->datos_aplicar['COD_SUBCONCEPTO'] = 83; break;
			endswitch;
			$this->datos_aplicar['NRO_REFERENCIA'] = $this->deuda['NUM_LIQUIDACION'];
      $this->datos_aplicar['COD_FISCALIZACION'] = $this->deuda['COD_FISCALIZACION'];
      $this->datos_aplicar['COD_PAGO'] = $datos['idpago'];
			// datos onbase
			$this->datos_aplicar['RADICADO_ONBASE'] = $datos['radicado_onbase'];
			$this->datos_aplicar['RESPONSABLE_RECLASIFICACION'] = $datos['responsable'];
			$this->datos_aplicar['FECHA_ONBASE'] = $datos['fecha_onbase'];
			$this->datos_aplicar['FECHA_RECLASIFICACION'] = "SYSDATE";
			$this->datos_aplicar['RECLASIFICADO'] = 1;
			// Fin datos onbase
			//echo "<pre>";print_r($this->datos_aplicar);echo "</pre>";
      /* echo "<pre>";
        print_r($this->deuda);
        print_r($this->datos_aplicar);exit(); */
      unset($this->datos_aplicar['dias_mora']);
      if ($this->aplica_pago() == true) :
				$this->deuda['SALDO_CAPITAL'] = $this->deuda['SALDO_CAPITAL'] - $this->datos_aplicar['DISTRIBUCION_CAPITAL'];
				$this->deuda['SALDO_INTERES'] = $this->deuda['SALDO_INTERES'] - $this->datos_aplicar['DISTRIBUCION_INTERES'];
				$this->deuda['SALDO_DEUDA'] = $this->deuda['SALDO_INTERES']+$this->deuda['SALDO_CAPITAL'];
        return true;
      else :
        return $this->error(11);
      endif;
    else : return $this->error(1);
    endif;
  }
	
	public function AplicarPagoOrdinario($datos) {
    if (!empty($datos)) :
      //comenzamos a inicializar las variables
			foreach($datos as $key=>$value) :
	      $this->datos_aplicar[$key] = $value;
			endforeach;
      $this->datos_aplicar['FECHA_APLICACION'] = date("d/m/Y");
      $this->datos_aplicar['DISTRIBUCION_CAPITAL'] = $datos['VALOR_PAGADO'];
      if ($this->aplica_pago() == true) :
        return true;
      else :
        return $this->error(11);
      endif;
    else : return $this->error(1);
    endif;
  }

  private function set_periodopago($periodo) {
    if (!empty($periodo)) :
      //se presume un formato de fecha d/m/y
      $periodo = implode("-", array_reverse(explode("/", $periodo)));
      //retornamos el formato Y-m
      return date("Y-m", strtotime(str_replace("/", "-", $periodo)));
		else :
			return "";
    endif;
  }

  private function aplica_pago() {
    $this->db->set("FECHA_APLICACION", "TO_DATE('" . $this->datos_aplicar['FECHA_APLICACION'] . "', 'DD/MM/YYYY')", FALSE);
    unset($this->datos_aplicar['FECHA_APLICACION']);
		$this->db->set("FECHA_PAGO", "TO_DATE('" . $this->datos_aplicar['FECHA_PAGO'] . "', 'YYYY-MM-DD')", FALSE);
		unset($this->datos_aplicar['FECHA_PAGO']);
		if(isset($this->datos_aplicar['FECHA_ONBASE'])) :
			$this->db->set("FECHA_ONBASE", "TO_DATE('" . $this->datos_aplicar['FECHA_ONBASE'] . "', 'YYYY-MM-DD')", FALSE);
			unset($this->datos_aplicar['FECHA_ONBASE']);
		endif;
		if(isset($this->datos_aplicar['FECHA_RECLASIFICACION'])) :
			$this->db->set("FECHA_RECLASIFICACION", "SYSDATE", FALSE);
			unset($this->datos_aplicar['FECHA_RECLASIFICACION']);
		endif;
		
		if(isset($this->datos_aplicar['REFERENCIA'])) :
			$this->referencia = $this->datos_aplicar['REFERENCIA'];
			unset($this->datos_aplicar['REFERENCIA']);
		endif;
		if(isset($this->datos_aplicar['FECHA_TRANSACCION'])) :
			$this->db->set("FECHA_TRANSACCION", "TO_DATE('" . $this->datos_aplicar['FECHA_TRANSACCION'] . "', 'YYYY-MM-DD HH24:MI:SS')", FALSE);
			unset($this->datos_aplicar['FECHA_TRANSACCION']);
		endif;
    if (!isset($this->datos_aplicar['COD_PAGO'])) :
			if(isset($this->datos_aplicar['FECHA_INICIO_OBRA'])) :
				$this->db->set("FECHA_INICIO_OBRA", "TO_DATE('" . $this->datos_aplicar['FECHA_INICIO_OBRA'] . "', 'YYYY-MM-DD')", FALSE);
	      unset($this->datos_aplicar['FECHA_INICIO_OBRA']);
			endif;
			if(isset($this->datos_aplicar['FECHA_FIN_OBRA'])) :
				$this->db->set("FECHA_FIN_OBRA", "TO_DATE('" . $this->datos_aplicar['FECHA_FIN_OBRA'] . "', 'YYYY-MM-DD')", FALSE);
	      unset($this->datos_aplicar['FECHA_FIN_OBRA']);
			endif;
      if ($this->db->insert("PAGOSRECIBIDOS", $this->datos_aplicar) == true) :
				$this->deuda['SALDO_DEUDA'] = $this->datos_aplicar['VALOR_ADEUDADO'] - 
																			(
																				$this->datos_aplicar['DISTRIBUCION_CAPITAL'] + 
																			 	$this->datos_aplicar['DISTRIBUCION_INTERES']
																			);
				$this->referencia = $this->datos_aplicar['NRO_REFERENCIA'];
				//echo $this->db->last_query();
        return true;
      else :
        return false;
      endif;
    else :
      $this->db->where("COD_PAGO", $this->datos_aplicar['COD_PAGO']);
      unset($this->datos_aplicar['COD_PAGO']);
      if ($this->db->update("PAGOSRECIBIDOS", $this->datos_aplicar) == true) :
        return true;
      else :
        return false;
      endif;
    endif;
  }
	
	function AplicarNoMisional($datos) {
		if(!empty($datos)) :
			$this->db->set("INTERES_MORA_GEN", ceil($datos['INTERES_MORA_GEN']));
			$this->db->set("SALDO_INTERES_C", ceil($datos['SALDO_INTERES_C']));
			$this->db->set("SALDO_AMORTIZACION", ceil($datos['SALDO_AMORTIZACION']));
			$this->db->set("SALDO_CUOTA", ceil($datos['SALDO_CUOTA']));
			$this->db->where("ID_DEUDA_E", $datos['ID_DEUDA_E']);
			$this->db->where("NO_CUOTA", $datos['NO_CUOTA']);
			$this->db->where_not_in("CONCEPTO", array(12, 13));
			if ($this->db->update("CNM_CUOTAS") == true) :
				require_once APPPATH.'controllers/cnm_liquidacioncuotas.php';
				$cnm = new Cnm_liquidacioncuotas();
				if($datos['COD_SUBCONCEPTO'] == 85) $hipotecario = true;
				else $hipotecario = false;
				$cnm->CnmCalcularCuotas($datos['ID_DEUDA_E'], date("Y-m-d"), 1, $hipotecario, true);
				return true;
			else :
				return false;
			endif;
		endif;
		return true;
	}

  function actualiza_saldo() {
		/*echo $this->deuda['SALDO_DEUDA'];
		echo "-".$this->deuda['SALDO_CAPITAL'];
		echo "-".$this->deuda['SALDO_INTERES'];*/
    $this->db->set("SALDO_DEUDA", ceil($this->deuda['SALDO_CAPITAL'] + $this->deuda['SALDO_INTERES']));
    $this->db->set("SALDO_CAPITAL", $this->deuda['SALDO_CAPITAL']);
    $this->db->set("SALDO_INTERES", $this->deuda['SALDO_INTERES']);
    $this->db->where("NUM_LIQUIDACION", $this->referencia);
    if ($this->db->update("LIQUIDACION") == true) :
			//echo $this->db->last_query(); exit;
      return true;
    else :
      return false;
    endif;
  }
	
	function aplicar_acuerdo($cuota = NULL) {
		if(!is_null($cuota)) :
			$this->db->where("NRO_ACUERDOPAGO", $cuota['NRO_ACUERDOPAGO']);
			unset($cuota['NRO_ACUERDOPAGO']);
			$this->db->where("PROYACUPAG_NUMCUOTA", $cuota['CUOTA']);
			$this->db->set("PROYACUPAG_FECHAPAGO", "SYSDATE", FALSE);
			unset($cuota['CUOTA']);
			if($this->db->update("PROYECCIONACUERDOPAGO", $cuota) == true) return true;
			else return false;
		else :
			return false;
		endif;
	}

  function dias_diferencia($fecha1, $fecha2, $tipo = "DIAS") {
    if (!is_array($fecha1) and !is_array($fecha2)) :
      $fecha1 = str_replace("/", "-", $fecha1);
      $fecha2 = str_replace("/", "-", $fecha2);
      //if (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $fecha2) == true || preg_match("/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/", $fecha1) == true) :
        //$tmp = explode("-", $fecha1);
        //$tmp = implode("-", array_reverse($tmp));
        $tmp = explode("-", date("j-n-Y", strtotime($fecha1)));
        $fecha1 = date("Y-m-d", mktime(0, 0, 0, $tmp[1], $tmp[0], $tmp[2]));
      /*endif;
      if (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $fecha2) == true || preg_match("/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/", $fecha2) == true) :*/
        //$tmp = explode("-", $fecha2);
        //$tmp = implode("-", array_reverse($tmp));
        $tmp = explode("-", date("j-n-Y", strtotime($fecha2)));
        $fecha2 = date("Y-m-d", mktime(0, 0, 0, $tmp[1], $tmp[0], $tmp[2]));
      //endif;
      //echo "<pre>";print_r($fecha1);echo " - ";print_r($fecha2);echo "</pre>";
      //formato fecha Y/m/d
      if (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $fecha1) == true and preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $fecha2) == true) :
        list($anio1, $mes1, $dia1) = explode('-', $fecha1);
        list($anio2, $mes2, $dia2) = explode('-', $fecha2);
        $horas1 = $horas2 = $min1 = $min2 = $seg1 = $seg2 = 0;
      elseif (preg_match("/^[0-9]{1,2}-[0-9]{1,2}-[0-9]{4}$/", $fecha1) == true and
              preg_match("/^[0-9]{1,2}-[0-9]{1,2}-[0-9]{4}$/", $fecha2) == true) : 
        //$fecha1 = explode("-", $fecha1);
        //$fecha2 = explode("-", $fecha2);
        list($dia1, $mes1, $anio1) = explode('-', $fecha1);
        list($dia2, $mes2, $anio2) = explode('-', $fecha2);
        $horas1 = $horas2 = $min1 = $min2 = $seg1 = $seg2 = 0;
      elseif (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}\040[0-9]{2}:[0-9]{2}$/", $fecha1) == true and
              preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}\040[0-9]{2}:[0-9]{2}$/", $fecha2) == true) : 
        $fecha1 = explode(" ", $fecha1);
        $fecha2 = explode(" ", $fecha2);
        list($anio1, $mes1, $dia1) = explode('-', $fecha1[0]);
        list($anio2, $mes2, $dia2) = explode('-', $fecha2[0]);
        list($horas1, $min1) = explode(':', $fecha1[1]);
        list($horas2, $min2) = explode(':', $fecha2[1]);
        $seg1 = "00";
        $seg2 = "00";
      elseif (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}\040[0-9]{2}:[0-9]{2}:[0-9]{2}$/", $fecha1) == true and
              preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}\040[0-9]{2}:[0-9]{2}:[0-9]{2}$/", $fecha2) == true) :
        $fecha1 = explode(" ", $fecha1);
        $fecha2 = explode(" ", $fecha2);
        list($anio1, $mes1, $dia1) = explode('-', $fecha1[0]);
        list($anio2, $mes2, $dia2) = explode('-', $fecha2[0]);
        list($horas1, $min1, $seg1) = explode(':', $fecha1[1]);
        list($horas2, $min2, $seg2) = explode(':', $fecha2[1]);
      elseif (preg_match("/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/", $fecha1) == true and
              preg_match("/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/", $fecha2) == true) :
        $dia1 = $dia2 = date("d");
        $mes1 = $mes2 = date("m");
        $anio1 = $anio2 = date("Y");
        list($horas1, $min1, $seg1) = explode(':', $fecha1);
        list($horas2, $min2, $seg2) = explode(':', $fecha2);
      elseif (preg_match("/^[0-9]{2}:[0-9]{2}$/", $fecha1) == true and
              preg_match("/^[0-9]{2}:[0-9]{2}$/", $fecha2) == true) :
        $dia1 = $dia2 = date("d");
        $mes1 = $mes2 = date("m");
        $anio1 = $anio2 = date("Y");
        list($horas1, $min1) = explode(':', $fecha1);
        list($horas2, $min2) = explode(':', $fecha2);
        $seg1 = 0;
        $seg2 = 0;
      else :
        $mensaje = "Error en formato de fechas";
        exit($mensaje);
      endif;
    else :
      $dia1 = $fecha1[2];
      $mes1 = $fecha1[1];
      $anio1 = $fecha1[0];
      if (!empty($fecha1[3]) and !empty($fecha1[4]) and !empty($fecha1[5])) :
        $horas1 = $fecha1['3'];
        $min1 = $fecha1['4'];
        $seg1 = $fecha1['5'];
      else :
        $horas1 = $min1 = $seg1 = 0;
      endif;
      $dia2 = $fecha2[2];
      $mes2 = $fecha2[1];
      $anio2 = $fecha2[0];
      if (!empty($fecha2[3]) and !empty($fecha2[4]) and !empty($fecha2[5])) :
        $horas2 = $fecha2['3'];
        $min2 = $fecha2['4'];
        $seg2 = $fecha2['5'];
      else :
        $horas2 = $min2 = $seg2 = 0;
      endif;
    endif;
    //echo "<pre>";print_r($fecha2);echo "</pre>";
    $timestamp1 = mktime($horas1, $min1, $seg1, $mes1, $dia1, $anio1);
    $timestamp2 = mktime($horas2, $min2, $seg2, $mes2, $dia2, $anio2);

    //echo "<p>H:".$horas1." m:".$min1." s:".$seg1." M:".$mes1." d:".$dia1." Y:".$anio1."</p>";
    //echo "<p>H:".$horas2." m:".$min2." s:".$seg2." M:".$mes2." d:".$dia2." Y:".$anio2."</p>";

    $segundos_diferencia = $timestamp1 - $timestamp2;
    if ($tipo == "HORAS") :
      $tmp = number_format(($segundos_diferencia / 60 / 60), 2);
      $h = floor(abs($tmp));
      $m = abs($h - abs($tmp));
      $m = ceil($m * 60);
      $resultados['h:m'] = $h . ":" . $m;
      $resultados['entero'] = abs($tmp);
    endif;
    if ($tipo == "DIAS" || empty($tipo)) :
      $resultados = ceil($segundos_diferencia / 86400);
    endif;
    return $resultados;
  }

	function calcular_mora_nomisional($tipo_mora, $codcartera, $tasa_interes, $fecha_inicio, $fecha_fin, $saldo, $dias_mora = 0) {
		$mora = 0;
    if($tipo_mora == 1) :
			$this->db->select("TIPOTASAINTERES.COD_TIPOTASAINTERES, TIPOTASAINTERES.COD_TIPOPERIODO");
			$this->db->from("CNM_CARTERANOMISIONAL");
			$this->db->join("TIPOTASAINTERES", "TIPOTASAINTERES.COD_TIPO_TASAINTERES=CNM_CARTERANOMISIONAL.TIPO_T_F_MORA", "inner");
			$this->db->where("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", $codcartera);
			$dato = $this->db->get();
			if($dato->num_rows() > 0) :
				$dato = $dato->first_row();
				$this->load->helper("liquidaciones");
				switch($dato->COD_TIPOTASAINTERES) :
					case 1 : $mora = convertirTasa_diaria($tasa_interes) * $dias_mora; break;
					case 2 :
						switch($dato->COD_TIPOPERIODO) :
							case 1 : $mora = ($tasa_interes / 360) * $dias_mora; break;
							case 2 : $mora = ($tasa_interes / 180) * $dias_mora; break;
							case 3 : $mora = ($tasa_interes / 90) * $dias_mora; break;
							case 4 : $mora = ($tasa_interes / 30) * $dias_mora; break;
							case 5 : $mora = $tasa_interes * $dias_mora; break;
						endswitch;
				endswitch;
			endif;
		elseif($tipo_mora == 2) :
			$this->db->select("REPLACE(TO_NUMBER(RANGOSTASAHISTORICA.VALOR_TASA), ',', '.') AS VALOR_TASA", FALSE);
			$this->db->select("TO_NUMBER(TO_CHAR(RANGOSTASAHISTORICA.FECHA_INI_VIGENCIA, 'MM')) AS MES_DESDE", FALSE);
			$this->db->select("TO_NUMBER(TO_CHAR(RANGOSTASAHISTORICA.FECHA_INI_VIGENCIA, 'YYYY')) AS YEAR", FALSE);
			$this->db->select("TO_NUMBER(TO_CHAR(RANGOSTASAHISTORICA.FECHA_FIN_VIGENCIA, 'MM')) AS MES_HASTA", FALSE);
			$this->db->join("TIPO_TASA_HISTORICA", "RANGOSTASAHISTORICA.COD_TIPO_TASA_HIST=TIPO_TASA_HISTORICA.COD_TIPO_TASA_HIST", "inner");
			$this->db->join("CNM_CARTERANOMISIONAL", "CNM_CARTERANOMISIONAL.TIPO_T_V_MORA=TIPO_TASA_HISTORICA.COD_TIPO_TASA_HIST", "inner");
			$this->db->where("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", $codcartera);
			$this->db->where("RANGOSTASAHISTORICA.FECHA_FIN_VIGENCIA>=", "TO_DATE('" . $fecha_inicio . "', 'YYYY-MM-DD')", FALSE);
			$this->db->where("RANGOSTASAHISTORICA.FECHA_INI_VIGENCIA<=", "TO_DATE('" . $fecha_fin . "', 'YYYY-MM-DD')", FALSE);
			$dato = $this->db->get("RANGOSTASAHISTORICA");// echo $this->db->last_query();exit;
			$tasas = $dato->result_array();
			if($dato->num_rows() > 0) :
				$mora = $this->calculaMora($tasas, $fecha_inicio, $fecha_fin, $saldo);
			endif;
		endif;
		return ceil($mora);
  }
	
	private function calculaMora($tasas = NULL, $fecha_inicio, $fecha_fin, $saldo = 0) {
		if(!is_null($tasas) and !empty($tasas) and !empty($fecha_inicio) and !empty($fecha_fin)) :
			$fecha_inicio = explode("-", date("j-n-Y", strtotime($fecha_inicio)));
			$fecha_inicio = date("j-n-Y", mktime(0, 0, 0, $fecha_inicio[1], $fecha_inicio[0] + 1, $fecha_inicio[2]));
			$fecha_inicio = explode("-", $fecha_inicio);

			$fecha_fin = explode("-", date("j-n-Y", strtotime($fecha_fin)));
			$fecha_fin = date("j-n-Y", mktime(0, 0, 0, $fecha_fin[1], $fecha_fin[0] + 1, $fecha_fin[2]));
			$fecha_fin = explode("-", $fecha_fin);
			$mora = 0;
			for ($x = $fecha_inicio[2]; $x <= $fecha_fin[2]; ++$x) :
				if ($x == $fecha_inicio[2] and $x < $fecha_fin[2]) :
					for ($y = $fecha_inicio[1]; $y <= 12; ++$y) :
						if ($y == $fecha_inicio[1]) :
							$dias_mora[$x][$y] = $this->dias_diferencia($this->obtenerDiasMes($y, $x) . "-" . $y . "-" . $x, $fecha_inicio[0] . "-" . $y . "-" . $x);
							$dias_mora_fecha[$x][$y] = $this->obtenerDiasMes($y, $x) . "-" . $y . "-" . $x . ", " . $fecha_inicio[0] . "-" . $y . "-" . $x;
						else :
							$dias_mora[$x][$y] = $this->dias_diferencia($this->obtenerDiasMes($y, $x) . "-" . $y . "-" . $x, "1-" . $y . "-" . $x);
							$dias_mora_fecha[$x][$y] = $this->obtenerDiasMes($y, $x) . "-" . $y . "-" . $x . ", 1-" . $y . "-" . $x;
						endif;
						foreach ($tasas as $tasa) :
							if ((intval($tasa['MES_DESDE']) <= $y and intval($tasa['MES_HASTA']) >= $y) and $tasa['YEAR'] == $x) :
								$mora += (((float) str_replace(',', '.', $tasa['VALOR_TASA']) / 100) / 366) * $dias_mora[$x][$y]*$saldo;
							endif;
						endforeach;
					endfor;
				elseif ($x > $fecha_inicio[2] and $x < $fecha_fin[2]) :
					for ($y = 1; $y <= 12; ++$y) :
						$dias_mora[$x][$y] = $this->dias_diferencia($this->obtenerDiasMes($y, $x) . "-" . $y . "-" . $x, "1-" . $y . "-" . $x);
						$dias_mora_fecha[$x][$y] = $this->obtenerDiasMes($y, $x) . "-" . $y . "-" . $x . ", 1-" . $y . "-" . $x;
						foreach ($tasas as $tasa) :
							if ((intval($tasa['MES_DESDE']) <= $y and intval($tasa['MES_HASTA']) >= $y) and $tasa['YEAR'] == $x) :
								$mora += (((float) str_replace(',', '.', $tasa['VALOR_TASA']) / 100) / 366) * $dias_mora[$x][$y]*$saldo;
							endif;
						endforeach;
					endfor;
				elseif ($x > $fecha_inicio[2] and $x == $fecha_fin[2]) :
					for ($y = 1; $y <= $fecha_fin[1]; ++$y) :
						if ($y == $fecha_fin[1]) :
							$dias_mora[$x][$y] = $this->dias_diferencia($fecha_fin[0] . "-" . $y . "-" . $x, "1-" . $y . "-" . $x);
							$dias_mora_fecha[$x][$y] = $fecha_fin[0] . "-" . $y . "-" . $x . ", 1-" . $y . "-" . $x;
						else :
							$dias_mora[$x][$y] = $this->dias_diferencia($this->obtenerDiasMes($y, $x) . "-" . $y . "-" . $x, "1-" . $y . "-" . $x);
							$dias_mora_fecha[$x][$y] = $this->obtenerDiasMes($y, $x) . "-" . $y . "-" . $x . ", 1-" . $y . "-" . $x;
						endif;
						foreach ($tasas as $tasa) :
							if ((intval($tasa['MES_DESDE']) <= $y and intval($tasa['MES_HASTA']) >= $y) and $tasa['YEAR'] == $x) :
								$mora += (((float) str_replace(',', '.', $tasa['VALOR_TASA']) / 100) / 366) * $dias_mora[$x][$y]*$saldo;
							endif;
						endforeach;
					endfor;
				elseif ($x == $fecha_inicio[2] and $x == $fecha_fin[2]) :
					for ($y = $fecha_inicio[1]; $y <= $fecha_fin[1]; ++$y) :
						if ($y == $fecha_inicio[1] and $y < $fecha_fin[1]) :
							$dias_mora[$x][$y] = $this->dias_diferencia($this->obtenerDiasMes($y, $x) . "-" . $y . "-" . $x, $fecha_inicio[0] . "-" . $y . "-" . $x);
							$dias_mora_fecha[$x][$y] = $this->obtenerDiasMes($y, $x) . "-" . $y . "-" . $x . ", " . $fecha_inicio[0] . "-" . $y . "-" . $x;
						elseif ($y > $fecha_inicio[1] and $y < $fecha_fin[1]) :
							$dias_mora[$x][$y] = $this->obtenerDiasMes($y, $x);
							$dias_mora_fecha[$x][$y] = $fecha_fin[0] . "-" . $y . "-" . $x . ", 1-" . $y . "-" . $x;
						elseif ($y == $fecha_fin[1]) :
							$dias_mora[$x][$y] = $this->dias_diferencia($fecha_fin[0] . "-" . $y . "-" . $x, $fecha_inicio[0] . "-" . $y . "-" . $x);
							$dias_mora_fecha[$x][$y] = $fecha_fin[0] . "-" . $y . "-" . $x . ", " . $fecha_inicio[0] . "-" . $y . "-" . $x;
						endif;
						if (!empty($tasas)) :
							foreach ($tasas as $tasa) :
								if ((intval($tasa['MES_DESDE']) <= $y and intval($tasa['MES_HASTA']) >= $y) and $tasa['YEAR'] == $x) :
									//echo "<pre>";print_r($dias_mora);echo "<pre>";
									//echo $x . " - " .$y;
									$mora += (((float) str_replace(',', '.', $tasa['VALOR_TASA']) / 100) / 366) * $dias_mora[$x][$y]*$saldo;
								endif;
							endforeach;
						else :
							$mora += 0;
						endif;
					endfor;
				endif;
			endfor;
			return ceil($mora);
		else :
			return 0;
		endif;
	}
}
