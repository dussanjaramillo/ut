<?php
/**
 * Ecollect_service (class CI_Controller) :)
 *
 * Clase para uso del webservice de Ecollect
 *
 * Permite gestionar toda las transacciones de comunicación con ECOLLECT.
 * Depende de aplicación automatica de pago para aplicar el pago.
 *
 * @author Felipe Camacho [@camachogfelipe]
 * @author http://www.cogroupsas.com
 *
 * @Empresa contratante: Turrisystem
 *
 * @package Ecollect_service
 */
if (!defined('BASEPATH'))
  exit('No se permite el acceso directo a las p&aacute;ginas de este sitio.');

error_reporting(0);

class Ecollect_service extends CI_Controller {

  private $nusoap_server;
  private $sesion;

  function __construct() {
    parent::__construct();
  }

  function index() {
    ob_clean();
    $_SERVER['QUERY_string'] = '';

    if ($this->uri->segment(3) == 'wsdl') {
      $_SERVER['QUERY_string'] = 'wsdl';
    } // endif
  }
	
	/* Función para generar el cuerpo del mensaje */
	private function GenerarXML($array = NULL) {
		if(!is_null($array)) :
			$response = '';
			foreach($array as $key => $item) :
				if(is_array($item)) :
					$response .= '<'.$key.'>'.$this->GenerarXML($item).'</'.$key.'>';
				else :
					$response .= '<'.$key.'>'.$item.'</'.$key.'>';
				endif;
			endforeach;
			return $response;
		else :
			return "";
		endif;
	}
	
	/* Función para retornar los datos en un xml */
  function array_to_xml(array $arr, SimpleXMLElement $xml) {
    foreach ($arr as $k => $v) {
      is_array($v) ? Ecollect_service::array_to_xml($v, $xml->addChild($k)) : $xml->addChild($k, $v);
    }
    return $xml;
  }
  
  /* Función para validar el acceso del usuario ecollect.
   * Siempre en todos las funciones se deben enviar estos porque en cada función se valida el acceso.
	 */
  function login($identidad, $clave) {
    if (!empty($identidad) and !empty($clave)) :
      $remember = 0;
      $data = array();

      $CI = & get_instance();
      $CI->load->model("Ion_auth_model");
      if ($CI->Ion_auth_model->login($identidad, $clave, $remember)) :
        return TRUE;
      else :
				return false;
      endif;
    else :
      return false;
    endif;
    return false;
  }

  /* Función para onbtener los datos de la empresa de acuerdo al número de identificación. */
  function ObtenerEmpresa($NRO_IDENTIFICACION) {
		$CI = & get_instance();
		$CI->load->model("Ion_auth_model");
		if(!is_null($NRO_IDENTIFICACION) and !empty($NRO_IDENTIFICACION)) :
			$CI = & get_instance();
			$CI->db->select("EMPRESA.CODEMPRESA AS NRO_IDENTIFICACION, EMPRESA.RAZON_SOCIAL, EMPRESA.NOMBRE_EMPRESA, "
										. "EMPRESA.CORREOELECTRONICO AS EMAIL");
			$CI->db->where("EMPRESA.CODEMPRESA", "$NRO_IDENTIFICACION");
			$dato = $CI->db->get("EMPRESA");
			$dato = $dato->row_array();
			return $dato;
		else :
			$result = array("error" => "Números de identificación vacíos");
		endif;
		return $result;
  }

	/* Función para registrar la empresa. */
	function RegistrarEmpresa($identidad, $clave, $NRO_IDENTIFICACION, $COD_DEPARTAMENTO, $TEL_FIJO, $RAZON_SOCIAL,
														$COD_TIPODOCUMENTO, $REPRESENTANTE_LEGAL) {
		if(!is_null($identidad) and !is_null($clave)) :
			$login = Ecollect_service::login($identidad, $clave);
			//$login = TRUE;
			if($login === TRUE) :
				switch($COD_TIPODOCUMENTO) :
					case 'NI'	: $COD_TIPODOCUMENTO = 2; break;
					case 'CC'	: $COD_TIPODOCUMENTO = 1; break;
					case 'CE'	: $COD_TIPODOCUMENTO = 4; break;
					case 'TI'	: $COD_TIPODOCUMENTO = 5; break;
					case 'RC'	: $COD_TIPODOCUMENTO = 3; break;
					case 'PA'	: $COD_TIPODOCUMENTO = 6; break;
				endswitch;
				$CI = & get_instance();
				$CI->load->model("Ecollect_model");
				$model = new Ecollect_model();
				$validar = Ecollect_service::ObtenerEmpresa($NRO_IDENTIFICACION);
				if(empty($validar)) :
					$COD_REGIONAL = $model->ObtenerRegionalSiif($COD_DEPARTAMENTO);
					$COD_DEPARTAMENTO = $model->ObtenerRegionalSiif($COD_DEPARTAMENTO, true);
					$enterprise = array('CODEMPRESA' => $NRO_IDENTIFICACION, 'COD_DEPARTAMENTO' => $COD_DEPARTAMENTO, 
															'COD_REGIONAL' => $COD_REGIONAL, 'TELEFONO_FIJO' => $TEL_FIJO, 
															'RAZON_SOCIAL' => mb_strtoupper($RAZON_SOCIAL), 
															'COD_TIPODOCUMENTO' => $COD_TIPODOCUMENTO, 
															'REPRESENTANTE_LEGAL' => mb_strtoupper($REPRESENTANTE_LEGAL), 
															'NOMBRE_EMPRESA' => mb_strtoupper($RAZON_SOCIAL));//print_r($enterprise);exit;
					$validate = true;
					foreach($enterprise as $val) :
						if(empty($val) || is_null($val)) :
							$validate = false;
							break;
						endif;
					endforeach;
					if($validate == true) :
						$CI->load->model("Ion_auth_model");
						if ($CI->db->insert("EMPRESA", $enterprise) == true) :
							$result = array("CODIGO_RETORNO" => "SUCCESS", "DES_CODIGO" => 'Registro de la empresa exitoso.');
							//$result = $this->GenerarXML($result);
						else :
							$result = array("CODIGO_RETORNO" => "FAIL_SYSTEM", "DES_CODIGO" => 'Falla del sistema - Error registro.');
							//$result = $this->GenerarXML($result);
						endif;
					else :
						$result = array("CODIGO_RETORNO" => "EMPTY_FIELDS", "DES_CODIGO" => 'Existen campos vacios.');
						//$result = $this->GenerarXML($result);
					endif;
				else :
					$result = array("CODIGO_RETORNO" => "FAIL_SYSTEM", "DES_CODIGO" => 'El n&uacute;mero de documento ya existe.');
					//$result = $this->GenerarXML($result);
				endif;
			else :
				$result = array("CODIGO_RETORNO" => "FAIL_SYSTEM", "DES_CODIGO" => 'Falla del sistema - Error de validación acceso.');
				//$result = $this->GenerarXML($result);
			endif;
		else :
			$result = array("CODIGO_RETORNO" => "FAIL_SYSTEM", "DES_CODIGO" => 'Falla del sistema - XML no valido.');
			//$result = $this->GenerarXML($result);
		endif;
		return $result;
		//return Ecollect_service::array_to_xml($result, new SimpleXMLElement('<RegistrarEmpresaResult/>'))->asXML();
  }

	/* Función que obtiene la cartera de un número de documento */
	function ObtenerCartera($identidad, $clave, $NRO_IDENTIFICACION) {
		$login = Ecollect_service::login($identidad, $clave);
		//$login = TRUE;
    if($login === TRUE) :
      $CI = & get_instance();
      if(!is_null($NRO_IDENTIFICACION) and !empty($NRO_IDENTIFICACION)) :
				$fecha_actual = date("Y-m-d");
				$data = array();
				/* Ejemplo prueba */
				/*$data[] = array('COD_CONCEPTO' => '01',
												'DESC_CONCEPTO' => 'PRUEBA_CONCEPTO1',
												'SALDO_DEUDA' => '1000000',
												'NRO_REFERENCIAPAGO' => '123456',
												'CUOTA' =>	array(
																			array(
																				'NRO_CUOTA' => '0',
																				'FECHA_VENCIMIENTO' => '2014-07-17',
																				'VALOR_CUOTA' => '100000'
																			),
																			array(
																				'NRO_CUOTA' => '1',
																				'FECHA_VENCIMIENTO' => '2014-07-17',
																				'VALOR_CUOTA' => '500000'
																			)
																		)
												);
				$result = array('CODIGO_RETORNO' => "SUCCESS", 'RESULT' => $data, 'NRO_IDENTIFICACION' => '8017804', 
												'DES_CODIGO' => 'Se encontraron ' . count($data) . ' resultados');*/
				/* Fin ejemplo prueba */
	      $CI->load->model("Ecollect_model");
				$model = new Ecollect_model();
				$dato = $model->obtenerCarteraMisional($NRO_IDENTIFICACION);
				//echo "<pre>";print_r($dato);exit("</pre>");
				$datoNM = $model->obtenerCarteraNoMisional($NRO_IDENTIFICACION);
				//echo "<pre>";print_r($datoNM);exit("</pre>");
				$x = 0;
				$CI->load->model("Aplicacionautomaticadepago_model");
				$apautpago = new Aplicacionautomaticadepago_model();
				// Recorremos la data de cartera misional
				if (!empty($dato)) :
					foreach($dato as $dat) :
						$cuotas = array();
						$interes_mora = 0;
						if($dat['COD_TIPOPROCESO'] == "2" || $dat['COD_TIPOPROCESO'] == "18") :
							$acuerdo = $model->obtenerCuotasAcuerdos($NRO_IDENTIFICACION, $dat['NRO_REFERENCIAPAGO']);
							foreach($acuerdo as $acu) :
								$cuotas[] = array(
															'NRO_CUOTA' => $acu['NO_CUOTA'],
															'FECHA_VENCIMIENTO' => $acu['FECHA_VENCIMIENTO'],
															'VALOR_CUOTA' => $acu['VALOR_CUOTA']
														);
							endforeach;
						else :
							if($dat['DIAS_MORA'] > 0) :
								$interes_mora = number_format($apautpago->calcular_mora(str_replace("/", "-", $dat['FECHA_VENCIMIENTO']), $fecha_actual, $dat['SALDO_DEUDA']), 0, ",", "");
							else :
								$interes_mora = 0;
							endif;
							$cuotas[] =	array(
														'NRO_CUOTA' => "",
														'FECHA_VENCIMIENTO' => $dat['FECHA_VENCIMIENTO'],
														'VALOR_CUOTA' => number_format($dat['SALDO_DEUDA'] + $interes_mora, 0, ",", "")
													);
						endif;
						if(!empty($cuotas)) :
							$data[$x] = array('COD_CONCEPTO' => $dat['COD_CONCEPTO'],
																'DESC_CONCEPTO' => $dat['DESC_CONCEPTO'],
																'SALDO_DEUDA' => number_format($dat['SALDO_DEUDA']+$interes_mora, 0, ",", ""),
																'NRO_REFERENCIAPAGO' => $dat['NRO_REFERENCIAPAGO'],
																'CUOTA' => $cuotas
													);
							
							$x++;
						endif;
					endforeach;
				endif;
				// Recorremos la data de cartera no misional
				if (!empty($datoNM)) :
					$cuotas = $total_deuda = array();
					foreach($datoNM as $dato) :
						//echo "<pre>";print_r($dato);echo "</pre>";
						if($dato['DIAS_MORA'] > 0) :
							$interes_mora = number_format($apautpago->calcular_mora_nomisional(
																							$dato['CALCULO_MORA'], $dato['COD_CARTERA_NOMISIONAL'], 
																							$dato['VALOR_T_MORA'], $dato['FECHA_LIM_PAGO'], date("Y-m-d"), 
																							$dato['SALDO_CUOTA']
																						), 0, ",", "");
						else :
							$interes_mora = 0;
						endif;
						$cuota = number_format($dato['SALDO_CUOTA'] + $interes_mora, 0, ",", "");
						if(!isset($cuotas[$dato['COD_CARTERA_NOMISIONAL']])) $cuotas[$dato['COD_CARTERA_NOMISIONAL']] = array();
						$cuotas[$dato['COD_CARTERA_NOMISIONAL']][] = 
							array(
								'NRO_CUOTA' => $dato['NO_CUOTA'],
								'FECHA_VENCIMIENTO' => $dato['FECHA_LIM_PAGO'],
								'VALOR_CUOTA' => number_format($cuota, 0, ",", "")
							);
						//echo "<pre>";print_r($cuotas);echo "</pre>";
						if(!isset($total_deuda[$dato['COD_CARTERA_NOMISIONAL']])) $total_deuda[$dato['COD_CARTERA_NOMISIONAL']] = 0;
						$total_deuda[$dato['COD_CARTERA_NOMISIONAL']] += $cuota;
					endforeach;
					//echo "<pre>";print_r($cuotas);echo "</pre>";
					//exit;
					foreach($datoNM as $dat) :
						$data[$x] = array('COD_CONCEPTO' => $dat['COD_CONCEPTO_RECAUDO'],
															'DESC_CONCEPTO' => $dat['NOMBRE_CONCEPTO'],
															'SALDO_DEUDA' => number_format($total_deuda[$dato['COD_CARTERA_NOMISIONAL']], 0, ",", ""),
															'NRO_REFERENCIAPAGO' => $dat['COD_CARTERA_NOMISIONAL'],
															'CUOTA' => $cuotas[$dat['COD_CARTERA_NOMISIONAL']]
												);
						
						$x++;
					endforeach;
				endif;
				//Si existe data se envia succes, de lo contrario no records
				if(!empty($data)) :
					//$data = Ecollect_service::array_to_xml($data, new SimpleXMLElement('<REFERENCE/>'))->asXML();
					/*if(sizeof($data) == 1) :
						$data = $data[0];
					endif;*/
					$result = array('CODIGO_RETORNO' => "SUCCESS", 'REFERENCE' => $data, 'NRO_IDENTIFICACION' => $NRO_IDENTIFICACION, 
												'DES_CODIGO' => 'Se encontraron ' . count($data) . ' resultados');
				else :
					$result = array('CODIGO_RETORNO' => "NO_RECORDS", 'NRO_IDENTIFICACION' => $NRO_IDENTIFICACION, 
													'DES_CODIGO' => 'No hay una cartera asociada al Número de documento.');
				endif;
			else :
				$result = array("CODIGO_RETORNO" => "FAIL_SYSTEM", "DES_CODIGO" => 'Falla del sistema.');
			endif;
		else :
			$result = array("CODIGO_RETORNO" => "FAIL_SYSTEM", "DES_CODIGO" => 'Falla del sistema.');
		endif;
		return $result;
	}
	
	/* Función que procesa el pago de una cartera*/
	function NotificarPagoCartera($identidad, $clave, $NRO_IDENTIFICACION, $REFERENCE, $TICKETID, $ENTIDAD_FINANACIERA, 
																$MEDIO_PAGO, $FECHA_ENTIDADFINANCIERA, $NRO_CONF_ENTI_FINANCIERA) {
		$login = Ecollect_service::login($identidad, $clave);
		//$login = TRUE;
    if($login === TRUE) :
      if(!is_null($REFERENCE) and !empty($REFERENCE)) :
				$CI = & get_instance();
				$CI->load->model("Ecollect_model");
				$CI->load->model("Aplicacionautomaticadepago_model");
				$model = new Ecollect_model();
				$apautpago = new Aplicacionautomaticadepago_model();
				//print_r($REFERENCE);exit;
				if(is_array($REFERENCE)) :
					$datos['COD_ENTIDAD'] = $model->CodigoBanco(substr($ENTIDAD_FINANACIERA, 0, 4));
					if($datos['COD_ENTIDAD'] != false) :
						$datos['NITEMPRESA'] = $NRO_IDENTIFICACION;
						$datos['FECHA_TRANSACCION'] = str_replace("T", " ", substr($FECHA_ENTIDADFINANCIERA, 0, -6));
						$datos['FECHA_PAGO'] = substr($FECHA_ENTIDADFINANCIERA, 0, 10);
						$datos['NUM_DOCUMENTO'] = $NRO_CONF_ENTI_FINANCIERA;
						$datos['COD_FORMAPAGO'] = ($MEDIO_PAGO == 0)?3:4;
						$datos['PROCEDENCIA'] = "ECOLLECT";
						$datos['TICKETID'] = $TICKETID;
						//print_r($REFERENCE);//exit;
						$keys = array_keys($REFERENCE);
						foreach($keys as $key) :
							if(!is_numeric($key)) :
								$tmp = $REFERENCE;
								$REFERENCE = NULL;
								$REFERENCE[0] = $tmp;
								break;
							endif;
						endforeach;
						//print_r($REFERENCE);//exit;
						foreach($REFERENCE as $ref) :
							//print_r($ref);//exit;
							//$acuerdo = explode("::", $ref['COD_CONCEPTO']);
							//print_r($acuerdo);//exit;
							//if($acuerdo[0] >= 1 and $acuerdo[0] <= 10 and count($acuerdo) > 1) :
							switch($ref['COD_CONCEPTO']) :
								//Proceso de cartera misional
								//DATOS PARA APLICAR PAGO
								//$datos['COD_CONCEPTO'] = $acuerdo[0];
								case 80 :
								case 82 :
								case 81 :
								case 83 :
									$datos['COD_REGIONAL'] = $model->ObtenerRegionalEmpresa($NRO_IDENTIFICACION);
									$datos['NRO_REFERENCIA'] = $ref['NRO_REFERENCIAPAGO'];
									$datos['COD_FISCALIZACION'] = $model->TraerFiscalizacion($datos['NRO_REFERENCIA']);
									$keys = array_keys($ref['CUOTA']);
									foreach($keys as $key) :
										if(!is_numeric($key)) :
											$tmp = $ref['CUOTA'];
											$ref['CUOTA'] = NULL;
											$ref['CUOTA'][0] = $tmp;
											break;
										endif;
									endforeach;
									foreach($ref['CUOTA'] as $cuota) :
										$datos['VALOR_PAGADO'] = $cuota['VALOR_CUOTA'];
										if($acuerdo[1] == "2" || $acuerdo[1] == "18") :
											$datos['NRO_CUOTA'] = $cuota['NRO_CUOTA'];
											$res = false;//$model->registrarPagoCuotas($datos);
										else :
											$pago = $model->TraerFiscalizacion($datos['NRO_REFERENCIA'], false);
											$pago['valor_aplicar'] = $cuota['VALOR_CUOTA'];
											if(!empty($pago)) :
												$pago['interes_mora'] = 0;
												$datos['DISTRIBUCION_CAPITAL'] = ceil(
													($pago['valor_aplicar']-$pago['interes_mora']) * 
													($pago['TOTAL_CAPITAL']/(($pago['TOTAL_CAPITAL']+$pago['TOTAL_INTERESES'])-$pago['interes_mora']))
												);
												$datos['DISTRIBUCION_INTERES'] = floor(
													($pago['valor_aplicar']-$pago['interes_mora']) * 
													($pago['TOTAL_INTERESES']/(($pago['TOTAL_CAPITAL']+$pago['TOTAL_INTERESES'])-$pago['interes_mora']))
												);
												$datos['DISTRIBUCION_INTERES_MORA'] = $pago['interes_mora'];
												$datos['VALOR_ADEUDADO'] = $pago['SALDO_DEUDA'];
												$res = true;
											else :
												$res = false;
											endif;
										endif;
										if($res != false) :
											$res = $apautpago->aplicar_pago($datos);
										endif;
									endforeach;
									break;
								default :
								//función cartera no misional
							endswitch;
							//print_r($datos);//exit;
						endforeach;
						if($res != true) :
							$result = array('CODIGO_RETORNO' => "FAIL_SYSTEM", 
															'DES_CODIGO' => $res);
						else :
							$result = array('CODIGO_RETORNO' => "SUCCESS", 
															'DES_CODIGO' => 'El pago se aplico correctamente.');
						endif;
					else :
						$result = array('CODIGO_RETORNO' => "SUCCESS", 
															'DES_CODIGO' => 'El código de banco no existe.');
					endif;
				else :
					$result = array('CODIGO_RETORNO' => "SUCCESS", 
													'DES_CODIGO' => 'La información suministrada es incorrecta.');
				endif;
			else :
				$result = array("CODIGO_RETORNO" => "FAIL_SYSTEM", "DES_CODIGO" => 'Falla del sistema.');
			endif;
		else :
			$result = array("CODIGO_RETORNO" => "FAIL_SYSTEM", "DES_CODIGO" => 'Falla del sistema.');
		endif;
		return $result;
	}
	
	/*Función que procesa los datos para realizar un pago ordinario*/
	function NotificarPagoOrdinario($identidad, $clave, $REFERENCE = array(), $TICKETID, $ENTIDAD_FINANACIERA, $MEDIO_PAGO, 
																	$FECHA_ENTIDADFINANCIERA, $NRO_CONF_ENTI_FINANCIERA, $VALOR_PAGO) {
		$login = Ecollect_service::login($identidad, $clave);
		$login = TRUE;
    if($login === TRUE) :
			//echo "<pre>";print_r($REFERENCE);exit("</pre>");
      if(!is_null($REFERENCE) and !empty($REFERENCE)) :
				$CI = & get_instance();
				$CI->load->model("Ecollect_model");
				$CI->load->model("Aplicacionautomaticadepago_model");
				$model = new Ecollect_model();
				$apautpago = new Aplicacionautomaticadepago_model();
				//echo "Imprimiendo reference<pre>";print_r($REFERENCE);exit("</pre>");
				if(is_array($REFERENCE)) :
					$basicos =	array("COD_SUBCONCEPTO", "NITEMPRESA", "RAZONSOCIAL", "PRIMERAPELLIDO", "TIPODOCUMENTO", "NATURALEZAJURIDICA", 
												"DEPARTAMENTO", "CIUDAD", "DIRECCION", "CIUDADDOMICILIO", "LOCALIZACION", "TELEFONO", "EMAIL", "CODIGOSIIF");
					$conceptos = array(
						"28" => array("REGIONAL", "REGIONALSIIF", "MES", "ANIO", "TIPOEMPRESA", "NRO_TRABAJADORES", "IBC", "DISTRIBUCION_INTERES_MORA", "VALOR_PAGADO", "59"),
						"5" => array("RESOLUCION", "FECHA_RESOLUCION", "REGIONAL", "REGIONALSIIF", "MES", "ANIO", "DISTRIBUCION_INTERES_MORA", "VALOR_PAGO", "VALOR_TOTAL", "19"), 
						"31" => array("REGIONAL", "REGIONALSIIF", "NRO_PLANILLA", "FECHA_PAGO", "MES", "ANIO", "OPERADOR", "NRO_TRABAJADORES", "VALOR_PAGO", "DISTRIBUCION_INTERES_MORA", "TOTAL_PAGO", "21"),
						"33" => array("REGIONAL", "REGIONALSIIF", "VALOR_PAGO", "VALOR_TOTAL", "64"),
						"37" => array("REGIONAL", "REGIONALSIIF", "VALOR_PAGO", "VALOR_TOTAL", "69"),
						"34" => array("REGIONAL", "REGIONALSIIF", "VALOR_PAGO", "VALOR_TOTAL", "71"),
						"35" => array("REGIONAL", "REGIONALSIIF", "VALOR_PAGO", "VALOR_TOTAL", "73"),
						"20" => array("REGIONAL", "REGIONALSIIF", "VALOR_PAGO", "VALOR_TOTAL", "75"),
						"21" => array("REGIONAL", "REGIONALSIIF", "VALOR_PAGO", "VALOR_TOTAL", "76"),
						"38" => array("REGIONAL", "REGIONALSIIF", "VALOR_PAGO", "VALOR_TOTAL", "78"),
						"22" => array("REGIONAL", "REGIONALSIIF", "ANIO", "MES", "VALOR_PAGO", "VALOR_TOTAL", "65"),
						"13" => array("REGIONAL", "REGIONALSIIF", "TIPO_CARNE", "VALOR_PAGO", "VALOR_TOTAL", "66"),
						"40" => array("NRO_CONTRATO", "FECHA_CONTRATO", "REGIONAL", "REGIONALSIIF", "VALOR_REINTEGRO", "VALOR_PAGO", "VALOR_TOTAL", "67"),
						"27" => array("NRO_CONVENIO", "ANIO_CONVENIO", "PERIODO_RENDIMIENTOS", "REGIONAL", "REGIONALSIIF", "VALOR_PAGO", "VALOR_TOTAL", "68"),
						"24" => array("NRO_CONTRATO", "FECHA_CONTRATO", "REGIONAL", "REGIONALSIIF", "VALOR_CONTRATO", "VALOR_PAGO", "VALOR_TOTAL", "70"),
						"16" => array("REGIONAL", "REGIONALSIIF", "NRO_ORDEN_VIAJE", "ANIO_PERIODO", "VALOR_PAGO", "VALOR_TOTAL", "74"),
						"41" => array("REGIONAL", "REGIONALSIIF", "CONCEPTO_TITULO", "77"),
						"45" => array("REGIONAL", "REGIONALSIIF", "CONCEPTO_PAGO", "VALOR_PAGO", "VALOR_TOTAL", "102"),
						"29" => array("REGIONAL", "REGIONALSIIF", "NRO_LICENCIA_CONTRATO_OBRA", "NOMBRE_OBRA", "FECHA_INICIO_OBRA", "FECHA_FIN_OBRA", "CIUDAD_OBRA", "TIPO_FIC", "ANIO_PERIODO", "MES_PERIODO", "COSTO_TOTAL_OBRA", "COSTO_MANO_OBRA", "NRO_TRABAJADORES", "DISTRIBUCION_INTERES_MORA", "VALOR_APORTE", "APORTE_SENA", "VALOR_PAGO", "VALOR_TOTAL", "17"),
						"439002" => array("CENTRO", "CODIGO_SIIF", "VALOR_PAGO", "VALOR_TOTAL", "100"),
						"420104" => array("CENTRO", "CODIGO_SIIF", "VALOR_PAGO", "VALOR_TOTAL", "94"),
						"420302" => array("CENTRO", "CODIGO_SIIF", "VALOR_PAGO", "VALOR_TOTAL", "96"),
						"420205" => array("CENTRO", "CODIGO_SIIF", "VALOR_PAGO", "VALOR_TOTAL", "95"),
						"420401" => array("CENTRO", "CODIGO_SIIF", "VALOR_PAGO", "VALOR_TOTAL", "97"),
						"43052701" => array("CENTRO", "CODIGO_SIIF", "VALOR_PAGO", "VALOR_TOTAL", "98"),
						"434505" => array("CENTRO", "CODIGO_SIIF", "VALOR_PAGO", "VALOR_TOTAL", "99")
					);
					
					foreach($REFERENCE as $key=>$ref) :
						if(isset($basicos[$key])) :
							$datos[$basicos[$key]] = $ref;
							unset($REFERENCE[$key]);
						endif;
					endforeach;
					$REFERENCE = array_values($REFERENCE);
					//echo "<pre>";print_r($REFERENCE);exit("<pre>");
					//echo "Cod subconcepto: ".$datos["COD_SUBCONCEPTO"];
					//echo "<pre>";print_r($REFERENCE);echo "<pre>";
					foreach($REFERENCE as $key=>$ref) :
						$datos[$conceptos[$datos["COD_SUBCONCEPTO"]][$key]] = $ref;
						unset($REFERENCE[$key]);
					endforeach;
					$datos['COD_SUBCONCEPTO'] = $conceptos[$datos["COD_SUBCONCEPTO"]][count($conceptos[$datos["COD_SUBCONCEPTO"]])-1];
					//echo "<pre>";print_r($datos);exit("<pre>");
					$res = true;
					if($res != true) :
						$result = array('CODIGO_RETORNO' => "FAIL_SYSTEM", 
														'DES_CODIGO' => $res);
					else :
						//$data = print_r($REFERENCE, false);
						$result = array('CODIGO_RETORNO' => "SUCCESS", 
														'DES_CODIGO' => 'El pago se aplico correctamente.'/*.$data*/);
					endif;
				else :
					$result = array('CODIGO_RETORNO' => "SUCCESS", 
													'DES_CODIGO' => 'La información suministrada es incorrecta.');
				endif;
			else :
				$result = array("CODIGO_RETORNO" => "FAIL_SYSTEM", "DES_CODIGO" => 'Falla del sistema, error de reference.');
			endif;
		else :
			$result = array("CODIGO_RETORNO" => "FAIL_SYSTEM", "DES_CODIGO" => 'Falla del sistema.');
		endif;
		return $result;
	}
}

$server = new SoapServer(NULL, array("uri"=>"urn:webservices"));
 
// Asignamos la Clase
$server->setClass('Ecollect_service');
 
// Atendemos las peticiones
$data = file_get_contents('php://input');
$server->handle($data);
