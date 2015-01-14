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

//error_reporting(E_ALL);

class Ecollect_service extends CI_Controller {

  private $nusoap_server;
  private $sesion;

  function __construct() {
    parent::__construct();
    $this->load->library("nu_soap");
    /* $this->ion = new Ion_auth_model();*/
      $this->sesion = new CI_Session();

    // Instanciamos la clase servidor de nusoap
    $this->nusoap_server = new nusoap_server();

    // Creamos el End Point, es decir, el lugar donde la petición cliente va a buscar la estructura del WSDL
    // aunque hay que recordar que nusoap genera dinámicamente dicha estructura XML
    $end_point = base_url() . 'index.php/ecollect_service/index/wsdl?wsdl';
		$ns = 'http://CarteraWDSL';
		//$ns = $end_point;

		// Indicamos cómo se debe formar el WSDL
    $this->nusoap_server->configureWSDL('CarteraWSDL', $ns, $end_point);
    $this->nusoap_server->wsdl->schemaTargetNamespace = $ns;
		//$this->nusoap_server->configureWSDL('CarteraWSDL', 'http://CarteraWDSL', $end_point, 'rpc');

		/*
		*		Function:			RegistrarEmpresa
		*		Description:	Registro de la función de registro de una empresa en el sistema
		*/
    $this->nusoap_server->register(
            'RegistrarEmpresa'
            , array('identidad' => 'xsd:string', 'clave' => 'xsd:string', 'NRO_IDENTIFICACION' => 'xsd:int',
										'COD_DEPARTAMENTO' => 'xsd:string', 'TEL_FIJO' => 'xsd:string', 'RAZON_SOCIAL' => 'xsd:string',
										'COD_TIPODOCUMENTO' => 'xsd:string', 'REPRESENTANTE_LEGAL' => 'xsd:string')
            , array('CODIGO_RETORNO' => 'xsd:string', 'DES_CODIGO' => 'xsd:string')
            , 'http://CarteraWDSL'
            , 'http://CarteraWDSL#RegistrarEmpresa'
            , 'rpc'
            , 'encoded'
            , "Registra una empresa en el sistema con sus datos básicos.
							 NI-Número de Identificación Tributaria
							 CC-Cédula de Ciudadanía
							 CE-Cédula de Extranjería
							 TI-Tarjeta de Identidad
							 RC-Registro Civil
							 PA-Pasaporte"
    );

		/*
		*		Function:			ObtenerCartera
		*		Description:	Registro de la función para obtener la cartera misional de un número de identificación.
		*/

		$this->nusoap_server->wsdl->addComplexType(
			'Reference',
			'complexType',
			'struct',
			'sequence',
			'',
			array('REFERENCE' => array('name' => 'REFERENCE', 'type' => 'tns:StructOfReference', 'maxOccurs' => 'unbounded', 'minOccurs' => '0')
			)
		);

		$this->nusoap_server->wsdl->addComplexType(
			'StructOfReference',
			'complexType',
			'struct',
			'sequence',
			'',
			array(
				'COD_CONCEPTO' => array('name' => 'COD_CONCEPTO', 'type' => 'xsd:string', 'maxOccurs' => '1', 'minOccurs' => '1'),
				'DESC_CONCEPTO' => array('name' => 'DESC_CONCEPTO', 'type' => 'xsd:string', 'maxOccurs' => '1', 'minOccurs' => '1'),
				'SALDO_DEUDA' => array('name' => 'SALDO_DEUDA', 'type' => 'xsd:string', 'maxOccurs' => '1', 'minOccurs' => '1'),
				'NRO_REFERENCIAPAGO' => array('name' => 'NRO_REFERENCIAPAGO', 'type' => 'xsd:string', 'maxOccurs' => '1', 'minOccurs' => '1'),
				'CUOTA' => array('name' => 'CUOTA', 'type' => 'tns:ArrayOfCuota', 'maxOccurs' => 'unbounded', 'minOccurs' => '1')
			)
		);

		$this->nusoap_server->wsdl->addComplexType(
			'ArrayOfCuota',
			'complexType',
			'struct',
			'sequence',
			'',
			array(
				'NRO_CUOTA' => array('name' => 'NRO_CUOTA', 'type' => 'xsd:int', 'maxOccurs' => '1', 'minOccurs' => '1'),
				'FECHA_VENCIMIENTO' => array('name' => 'FECHA_VENCIMIENTO', 'type' => 'xsd:date', 'maxOccurs' => '1', 'minOccurs' => '1'),
				'VALOR_CUOTA' => array('name' => 'VALOR_CUOTA', 'type' => 'xsd:int', 'maxOccurs' => '1', 'minOccurs' => '1')
			)
		);

		$this->nusoap_server->wsdl->addComplexType(
			'ArrayOfReference',
			'complexType',
			'array',
			'all',
			'SOAP-ENC:Array',
			array(),
			array(
				array(
					'ref' => 'SOAP-ENC:arrayType',
					'wsdl:arrayType' => 'tns:StructOfReference[]',
					'maxOccurs' => 'unbounded', 'minOccurs' => '0'
				),
			),
			'tns:StructOfReference'
		);

    $this->nusoap_server->register(
            'ObtenerCartera'
            , array('identidad' => 'xsd:string', 'clave' => 'xsd:string', 'NRO_IDENTIFICACION' => 'xsd:string')
            , array('NRO_IDENTIFICACION' => 'xsd:int', 'CODIGO_RETORNO' => 'xsd:string', 'DES_CODIGO' => 'xsd:string',
										'REFERENCE' => 'tns:ArrayOfReference')
            , 'http://CarteraWDSL'
            , 'http://CarteraWDSL#ObtenerCartera'
            , 'rpc'
            , 'encoded'
            , "Devuelve la cartera asociada a un número de identificación.
							 El formato de la fecha es date: YYYY-MM-DD"
    );

		/*
		*		Function:			NotificarPagoCartera
		*		Description:	Registro de la función para notificar un pago de una cartera existente
		*/

		$this->nusoap_server->wsdl->addComplexType(
			'ArrayOfReferences',
			'complexType',
			'array',
			'',
			'SOAP-ENC:Array',
			array(),
			array(
				array(
					'ref' => 'SOAP-ENC:arrayType',
					'wsdl:arrayType' => 'tns:StructOfReferences[]',
					'maxOccurs' => 'unbounded', 'minOccurs' => '1'
				)
			),
			'tns:StructOfReferences'
		);

		$this->nusoap_server->wsdl->addComplexType(
			'StructOfReferences',
			'complexType',
			'struct',
			'sequence',
			'',
			array(
				'COD_CONCEPTO' => array('name' => 'COD_CONCEPTO', 'type' => 'xsd:string', 'maxOccurs' => '1', 'minOccurs' => '1'),
				'NRO_REFERENCIAPAGO' => array('name' => 'NRO_REFERENCIAPAGO', 'type' => 'xsd:string', 'maxOccurs' => '1', 'minOccurs' => '1'),
				'CUOTA' => array('name' => 'CUOTA', 'type' => 'tns:ArrayOfCuotas', 'maxOccurs' => 'unbounded', 'minOccurs' => '1')
			)
		);

		$this->nusoap_server->wsdl->addComplexType(
			'ArrayOfCuotas',
			'complexType',
			'struct',
			'sequence',
			'',
			array(
				'NRO_CUOTA' => array('name' => 'NRO_CUOTA', 'type' => 'xsd:int', 'maxOccurs' => '1', 'minOccurs' => '1'),
				'VALOR_CUOTA' => array('name' => 'VALOR_CUOTA', 'type' => 'xsd:int', 'maxOccurs' => '1', 'minOccurs' => '1')
			)
		);

    $this->nusoap_server->register(
            'NotificarPagoCartera'
            , array('identidad' => 'xsd:string', 'clave' => 'xsd:string', 'NRO_IDENTIFICACION' => 'xsd:int',
										'REFERENCE' => 'tns:ArrayOfReferences', 'TICKETID' => 'xsd:string', 'ENTIDAD_FINANACIERA' => 'xsd:string',
										'MEDIO_PAGO' => 'xsd:string', 'FECHA_ENTIDADFINANCIERA' => 'xsd:dateTime',
										'NRO_CONF_ENTI_FINANCIERA' => 'xsd:string')
						, array('CODIGO_RETORNO' => 'xsd:string', 'DES_CODIGO' => 'xsd:string')
            , 'http://CarteraWDSL'
            , 'http://CarteraWDSL#NotificarPagoCartera'
            , 'rpc'
            , 'encoded'
            , "Registra en el sistema los pagos de cartera.
							 El formato de la fecha es YYYY/MM/DD HH:mm:ss"
    );

		/*
		*		Function:			NotificarPagoOrdinario
		*		Description:	Registro de la función para registrar el pago ordinario en el sistema.
		*/

    $this->nusoap_server->register(
            'NotificarPagoOrdinario'
            , array('identidad' => 'xsd:string', 'clave' => 'xsd:string', 'REFERENCE' => 'xsd:array',
										'TICKETID' => 'xsd:string', 'ENTIDAD_FINANACIERA' => 'xsd:string', 'MEDIO_PAGO' => 'xsd:string',
										'FECHA_ENTIDADFINANCIERA' => 'xsd:dateTime', 'NRO_CONF_ENTI_FINANCIERA' => 'xsd:string',
										'VALOR_PAGO' => 'xsd:int')
            , array('CODIGO_RETORNO' => 'xsd:string', 'DES_CODIGO' => 'xsd:string')
            , 'http://CarteraWDSL'
            , 'http://CarteraWDSL#NotificarPagoOrdinario'
            , 'rpc'
            , 'encoded'
            , "Notifica los pagos ordinarios en el sistema.
							 El formato de la fecha es YYYY/MM/DD HH:mm:ss
						"
    );
  }

  function index() {
    ob_clean();
    $_SERVER['QUERY_string'] = '';

    if ($this->uri->segment(3) == 'wsdl') {
      $_SERVER['QUERY_string'] = 'wsdl';
    } // endif

    $this->nusoap_server->service(trim(file_get_contents('php://input')));
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
						else :
							$result = array("CODIGO_RETORNO" => "FAIL_SYSTEM", "DES_CODIGO" => 'Falla del sistema - Error registro.');
						endif;
					else :
						$result = array("CODIGO_RETORNO" => "EMPTY_FIELDS", "DES_CODIGO" => 'Existen campos vacios.');
					endif;
				else :
					$result = array("CODIGO_RETORNO" => "FAIL_SYSTEM", "DES_CODIGO" => 'El número de documento ya existe.');
				endif;
			else :
				$result = array("CODIGO_RETORNO" => "FAIL_SYSTEM", "DES_CODIGO" => 'Falla del sistema - Error de validación acceso.');
			endif;
		else :
			$result = array("CODIGO_RETORNO" => "FAIL_SYSTEM", "DES_CODIGO" => 'Falla del sistema - XML no valido.');
		endif;
		return $result;
		//return base64_encode(Ecollect_service::array_to_xml($result, new SimpleXMLElement('<RegistrarEmpresaResult/>'))->asXML());
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
				/*$data = array(
										array('COD_CONCEPTO' => '01',
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
										),
										array('COD_CONCEPTO' => '01',
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
										)
									);*/
				/*$result = array('CODIGO_RETORNO' => "SUCCESS", 'RESULT' => $data, 'NRO_IDENTIFICACION' => '8017804',
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
					$acuerdos = array();
					foreach($dato as $dat) :
						$cuotas = array();
						$interes_mora = $saldo_deuda = 0;
						if($dat['COD_TIPOPROCESO'] == "2" || $dat['COD_TIPOPROCESO'] == "18") :
							if($dat['COD_CONCEPTO'] == 80)	$dat['COD_CONCEPTO'] = 110;
							if($dat['COD_CONCEPTO'] == 81)	$dat['COD_CONCEPTO'] = 111;
							if($dat['COD_CONCEPTO'] == 82)	$dat['COD_CONCEPTO'] = 112;
							if($dat['COD_CONCEPTO'] == 9)		$dat['COD_CONCEPTO'] = 113;
							if($dat['COD_TIPOPROCESO'] == "18") :
								if(!in_array($dat['COD_PROCESO_COACTIVO'])) :
									$acuerdo = $model->obtenerCuotasAcuerdos($NRO_IDENTIFICACION, $dat['COD_PROCESO_COACTIVO'], 18, $acuerdos);
									$dat['NRO_REFERENCIAPAGO'] = $dat['COD_PROCESO_COACTIVO'];
									$acuerdos[] = $dat['COD_PROCESO_COACTIVO'];
								endif;
							else :
								$acuerdo = $model->obtenerCuotasAcuerdos($NRO_IDENTIFICACION, $dat['NRO_REFERENCIAPAGO'], 2);
							endif;
							foreach($acuerdo as $acu) :
								$saldo_deuda += $acu['VALOR_CUOTA'];
								if($acu['DIAS_MORA'] > 0) :
									$cuotas[] = array(
																'NRO_CUOTA' => $acu['NO_CUOTA'],
																'FECHA_VENCIMIENTO' => $acu['FECHA_VENCIMIENTO'],
																'VALOR_CUOTA' => $acu['VALOR_CUOTA']
															);
								elseif(sizeof($cuotas) == 0) :
									$cuotas[] = array(
																'NRO_CUOTA' => $acu['NO_CUOTA'],
																'FECHA_VENCIMIENTO' => $acu['FECHA_VENCIMIENTO'],
																'VALOR_CUOTA' => $acu['VALOR_CUOTA']
															);
								endif;
							endforeach;
						else :
							if($dat['DIAS_MORA'] > 0) :
								$interes_mora = 0;//number_format($apautpago->calcular_mora(str_replace("/", "-", $dat['FECHA_VENCIMIENTO']), $fecha_actual, $dat['SALDO_DEUDA']), 0, ",", "");
							else :
								$interes_mora = 0;
							endif;
							$saldo_deuda = $dat['SALDO_DEUDA'];
							$cuotas[] =	array(
														'NRO_CUOTA' => "",
														'FECHA_VENCIMIENTO' => $dat['FECHA_VENCIMIENTO'],
														'VALOR_CUOTA' => number_format($dat['SALDO_DEUDA'] + $interes_mora, 0, ",", "")
													);
						endif;
						if(!empty($cuotas)) :
							$data[$x] = array('COD_CONCEPTO' => $dat['COD_CONCEPTO'],
																'DESC_CONCEPTO' => $dat['DESC_CONCEPTO'],
																'SALDO_DEUDA' => number_format($saldo_deuda+$interes_mora, 0, ",", ""),
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
					ini_set("MEMORY_LIMIT", "1GB");
					ini_set("MAX_EXECUTION_TIME", "300");
					foreach($datoNM as $dato) :
						//echo "<pre>";print_r($dato);echo "</pre>";exit;
						if(!isset($cuotas[$dato['COD_CARTERA_NOMISIONAL']])) $cuotas[$dato['COD_CARTERA_NOMISIONAL']] = array();
						if($dato['DIAS_MORA'] > 0) :
							$interes_mora = number_format($apautpago->calcular_mora_nomisional(
																							$dato['CALCULO_MORA'], $dato['COD_CARTERA_NOMISIONAL'],
																							$dato['VALOR_T_MORA'], $dato['FECHA_LIM_PAGO'], date("Y-m-d"),
																							$dato['SALDO_CUOTA'], $dato['DIAS_MORA']
																						), 0, ",", "");
							$cuota = number_format($dato['SALDO_CUOTA'] + $interes_mora, 0, ",", "");
							$cuotas[$dato['COD_CARTERA_NOMISIONAL']][] =
								array(
									'NRO_CUOTA' => $dato['NO_CUOTA'],
									'FECHA_VENCIMIENTO' => $dato['FECHA_LIM_PAGO'],
									'VALOR_CUOTA' => number_format($cuota, 0, ",", "")
								);//echo $dato['SALDO_INTERES_C']."\n";
							if(!isset($total_deuda[$dato['COD_CARTERA_NOMISIONAL']])) $total_deuda[$dato['COD_CARTERA_NOMISIONAL']] = 0;
							$total_deuda[$dato['COD_CARTERA_NOMISIONAL']] += $dato['SALDO_INTERES_C'];
							//print_r($cuotas);
						elseif(sizeof($cuotas[$dato['COD_CARTERA_NOMISIONAL']]) == 0) :
							$cuota = number_format($dato['SALDO_CUOTA'], 0, ",", "");
							$cuotas[$dato['COD_CARTERA_NOMISIONAL']][] =
								array(
									'NRO_CUOTA' => $dato['NO_CUOTA'],
									'FECHA_VENCIMIENTO' => $dato['FECHA_LIM_PAGO'],
									'VALOR_CUOTA' => number_format($cuota, 0, ",", "")
								);//echo $dato['SALDO_INTERES_C']."\n";
							if(!isset($total_deuda[$dato['COD_CARTERA_NOMISIONAL']])) $total_deuda[$dato['COD_CARTERA_NOMISIONAL']] = 0;
							$total_deuda[$dato['COD_CARTERA_NOMISIONAL']] += $dato['SALDO_INTERES_C'];
						endif;
					endforeach;
					//echo "<pre>";print_r($cuotas);echo "</pre>";
					//exit;
					$cod = array();
					foreach($datoNM as $dat) :
						if(!in_array($dat['COD_CARTERA_NOMISIONAL'], $cod)) :
							//echo $total_deuda[$dato['COD_CARTERA_NOMISIONAL']]."\n";
							$data[$x] = array('COD_CONCEPTO' => $dat['COD_CONCEPTO_RECAUDO'],
																'DESC_CONCEPTO' => $dat['NOMBRE_CONCEPTO'],
																'SALDO_DEUDA' => number_format($dat['SALDO_DEUDA']+$total_deuda[$dato['COD_CARTERA_NOMISIONAL']], 0, ",", ""),
																'NRO_REFERENCIAPAGO' => $dat['COD_CARTERA_NOMISIONAL'],
																'CUOTA' => $cuotas[$dat['COD_CARTERA_NOMISIONAL']]
													);

							$x++;
							$cod[] = $dat['COD_CARTERA_NOMISIONAL'];
						endif;
					endforeach;
					//echo "<pre>";print_r($data);echo "</pre>";exit;
				endif;
				//Si existe data se envia succes, de lo contrario no records
				if(!empty($data)) :
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
    if($login === TRUE) ://print_r($REFERENCE);exit;
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
						$datos['FECHA_TRANSACCION'] = str_replace("T", " ", substr($FECHA_ENTIDADFINANCIERA, 0, 19));
						$datos['FECHA_PAGO'] = substr($FECHA_ENTIDADFINANCIERA, 0, 10);
						$datos['NUM_DOCUMENTO'] = $NRO_CONF_ENTI_FINANCIERA;
						$datos['COD_FORMAPAGO'] = ($MEDIO_PAGO == 0)?3:4;
						$datos['PROCEDENCIA'] = "ECOLLECT";
						$datos['TICKETID'] = $TICKETID;
						if($model->VerificarTicketId($TICKETID) === FALSE) :
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
									case 81 :
									case 82 :
									case 9	:
										$datos['COD_REGIONAL'] = $model->ObtenerRegionalEmpresa($NRO_IDENTIFICACION);
										$datos['NRO_REFERENCIA'] = $ref['NRO_REFERENCIAPAGO'];
										$fiscalizacion = $model->TraerFiscalizacion($datos['NRO_REFERENCIA']);
										$datos['COD_FISCALIZACION'] = $fiscalizacion['COD_FISCALIZACION'];
										if($ref['COD_CONCEPTO'] == 80)	$datos['COD_SUBCONCEPTO'] = 80;
										if($ref['COD_CONCEPTO'] == 81)	$datos['COD_SUBCONCEPTO'] = 81;
										if($ref['COD_CONCEPTO'] == 82)	$datos['COD_SUBCONCEPTO'] = 82;
										if($ref['COD_CONCEPTO'] == 9)		$datos['COD_SUBCONCEPTO'] = 83;
										$datos['COD_CONCEPTO'] = $fiscalizacion['COD_CONCEPTO'];
										$acuerdo = $fiscalizacion['COD_TIPOPROCESO'];
										//print_r($fiscalizacion);//exit;
										$keys = array_keys($ref['CUOTA']);
										foreach($keys as $key) :
											if(!is_numeric($key)) :
												$tmp = $ref['CUOTA'];
												$ref['CUOTA'] = NULL;
												$ref['CUOTA'][0] = $tmp;
												break;
											endif;
										endforeach;
										if(sizeof($ref['CUOTA']) > 1 and $acuerdo != "2" and $acuerdo != "18") :
											$res = "La obligación no es un acuerdo de pago, y se esta referenciando más de una cuota";
										else :
											//print_r($ref);//exit;
											foreach($ref['CUOTA'] as $cuota) :
												$datos['VALOR_PAGADO'] = $cuota['VALOR_CUOTA'];
												if($acuerdo == "2" || $acuerdo == "18") :
													$datos['NRO_CUOTA'] = $cuota['NRO_CUOTA'];
													$datCuota = $model->TraerDatosCuota($NRO_IDENTIFICACION, $datos['NRO_REFERENCIA'], $cuota['NRO_CUOTA']);
													if(sizeof($datCuota) > 0) :
														if($datCuota['DIAS_MORA'] > 0) :
															$interes_mora = $apautpago->calcular_mora($cuota['FECHA_VENCIMIENTO'], $fecha_pago, $cuota['SALDO_CUOTA']);
														else :
															$interes_mora = 0;
														endif;
														$cuo['NRO_ACUERDOPAGO'] = $datCuota['NRO_ACUERDOPAGO'];
														$dis = $cuota['VALOR_CUOTA'];
														$dis -= $interes_mora;
														$datos['DISTRIBUCION_INTERES_MORA'] = $interes_mora;
														$cuo["PROYACUPAG_ESTADO"] = 0;
														if($dis > 0) :
															$datos['DISTRIBUCION_CAPITAL'] =
															floor(($dis)*($datCuota['CAPITAL']/(($datCuota['CAPITAL']+$datCuota['INTACUERDO']+$datCuota['INTCORRIENTE']))));
															$cuo["PROYACUPAG_SALDOCAPITAL"] = $datCuota['SALDO_CAPITAL'] - $datos['DISTRIBUCION_CAPITAL'];
															$tmpdatos['DISTRIBUCION_INTERES1'] =
															ceil(($dis)*($datCuota['INTCORRIENTE']/(($datCuota['CAPITAL']+$datCuota['INTACUERDO']+$datCuota['INTCORRIENTE']))));
															$cuo["PROYACUPAG_SALDO_INTCORRIENTE"] = $datCuota['SALDO_INTCORRIENTE'] - $tmpdatos['DISTRIBUCION_INTERES1'];
															$tmpdatos['DISTRIBUCION_INTERES2'] =
															ceil(($dis)*($datCuota['INTACUERDO']/(($datCuota['CAPITAL']+$datCuota['INTACUERDO']+$datCuota['INTCORRIENTE']))));
															$cuo["PROYACUPAG_SALDO_INTACUERDO"] = $datCuota['SALDO_INTACUERDO'] - $tmpdatos['DISTRIBUCION_INTERES2'];
															$datos['DISTRIBUCION_INTERES'] = $tmpdatos['DISTRIBUCION_INTERES1'] + $tmpdatos['DISTRIBUCION_INTERES2'];
														else:
															$datos['DISTRIBUCION_INTERES'] = $datos['DISTRIBUCION_CAPITAL'] = 0;
														endif;
														$cuo["PROYACUPAG_SALDO_CUOTA"] =	$cuo["PROYACUPAG_SALDO_INTACUERDO"] +
																																$cuo["PROYACUPAG_SALDO_INTCORRIENTE"] +
																																$cuo["PROYACUPAG_SALDOCAPITAL"];
														$datos['VALOR_PAGADO'] = $cuota['VALOR_CUOTA'];
														//$datos['SALDO_DEUDA'] = $fiscalizacion['SALDO_DEUDA'] - ($datos['VALOR_PAGADO'] - $datos['DISTRIBUCION_INTERES_MORA']);
														$datos['SALDO_CAPITAL'] = $fiscalizacion['SALDO_CAPITAL'] - $datos['DISTRIBUCION_CAPITAL'];
														$datos['SALDO_INTERES'] = $fiscalizacion['SALDO_INTERES'] - $datos['DISTRIBUCION_INTERES'];
														$datos['SALDO_DEUDA'] = $datos['SALDO_CAPITAL'] + $datos['SALDO_INTERES'];
														$datos['NRO_CUOTA'] = $cuo["CUOTA"] = $cuota['NRO_CUOTA'];
														$datos['VALOR_ADEUDADO'] = $fiscalizacion['SALDO_DEUDA'];
														$datos['APLICADO'] = 1;
														if($cuo["PROYACUPAG_SALDO_CUOTA"] <= 0) :
															$cuo["PROYACUPAG_ESTADO"] = 1;
														endif;
														//echo "<pre>";print_r($datos);print_r($cuota);print_r($cuo);echo "</pre>";exit;
														$aplicado = $apautpago->aplicar_pago($datos);
														if(is_array($aplicado)) :
															$res = "EL PAGO DE LA CUOTA NRO. " . $cuo['NRO_CUOTA'] . " DEL ACUERDO NRO. " . $cuo["NRO_ACUERDOPAGO"] . "  DE LA OBLIGACIÓN NRO. " . $datos['NRO_REFERENCIA'] . " presento errores.<br>" .$aplicado[1];
														else :
															$apautpago->aplicar_acuerdo($cuo);
															$res = true;
														endif;
													else :
														$res = "Error en los datos de la cuota a aplicar o esta ya fue aplicada.";
													endif;
												else :
													$pago['valor_aplicar'] = $cuota['VALOR_CUOTA'];
													if(!empty($pago)) :
														$pago['interes_mora'] = 0;
														$datos['DISTRIBUCION_CAPITAL'] = floor(
															($pago['valor_aplicar']-$pago['interes_mora']) *
															($fiscalizacion['TOTAL_CAPITAL']/(($fiscalizacion['TOTAL_CAPITAL']+$fiscalizacion['TOTAL_INTERESES'])))
														);
														$datos['DISTRIBUCION_INTERES'] = ceil(
															($pago['valor_aplicar']-$pago['interes_mora']) *
															($fiscalizacion['TOTAL_INTERESES']/(($fiscalizacion['TOTAL_CAPITAL']+$fiscalizacion['TOTAL_INTERESES'])))
														);
														$datos['DISTRIBUCION_INTERES_MORA'] = $pago['interes_mora'];
														$datos['VALOR_ADEUDADO'] = $fiscalizacion['SALDO_DEUDA'];
														$datos['SALDO_CAPITAL'] = $fiscalizacion['SALDO_CAPITAL'] - $datos['DISTRIBUCION_CAPITAL'];
														$datos['SALDO_INTERES'] = $fiscalizacion['SALDO_INTERES'] - $datos['DISTRIBUCION_INTERES'];
														$datos['SALDO_DEUDA'] = $datos['SALDO_CAPITAL'] + $datos['SALDO_INTERES'];
														//print_r($datos);//exit;
														$res = $apautpago->aplicar_pago($datos);
														if(is_array($res)) :
															$res = "EL PAGO DE LA OBLIGACIÓN NRO. " . $datos['NRO_REFERENCIA'] . " presento errores.<br>" .$aplicado[1];
														else :
															$apautpago->actualiza_saldo();
															$res = true;
														endif;
													else :
														$res = false;
													endif;
												endif;
											endforeach;
										endif;
										break;
									// Pago de acuerdos de pago
									case 110:
									case 111:
									case 112:
									case 113:
										$datos['COD_REGIONAL'] = $model->ObtenerRegionalEmpresa($NRO_IDENTIFICACION);
										$datos['NRO_REFERENCIA'] = $ref['NRO_REFERENCIAPAGO'];
										$fiscalizacion = $model->TraerFiscalizacion($datos['NRO_REFERENCIA']);
										$datos['COD_FISCALIZACION'] = $fiscalizacion['COD_FISCALIZACION'];
										if($ref['COD_CONCEPTO'] == 110)	$datos['COD_SUBCONCEPTO'] = 80;
										if($ref['COD_CONCEPTO'] == 111)	$datos['COD_SUBCONCEPTO'] = 81;
										if($ref['COD_CONCEPTO'] == 112)	$datos['COD_SUBCONCEPTO'] = 82;
										if($ref['COD_CONCEPTO'] == 113)	$datos['COD_SUBCONCEPTO'] = 83;
										$datos['COD_CONCEPTO'] = $fiscalizacion['COD_CONCEPTO'];
										$acuerdo = $fiscalizacion['COD_TIPOPROCESO'];
										//print_r($fiscalizacion);//exit;
										$keys = array_keys($ref['CUOTA']);
										foreach($keys as $key) :
											if(!is_numeric($key)) :
												$tmp = $ref['CUOTA'];
												$ref['CUOTA'] = NULL;
												$ref['CUOTA'][0] = $tmp;
												break;
											endif;
										endforeach;
										if(sizeof($ref['CUOTA']) > 1 and $acuerdo != "2" and $acuerdo != "18") :
											$res = "La obligación no es un acuerdo de pago, y se esta referenciando más de una cuota";
										else :
											//print_r($ref);//exit;
											$saldo_a_aplicar = 0;
											foreach($ref['CUOTA'] as $cuota) :
												$datos['VALOR_PAGADO'] = $cuota['VALOR_CUOTA'];
												$saldo_a_aplicar += $cuota['VALOR_CUOTA'];
												if($acuerdo == "2" || $acuerdo == "18") :
													$datos['NRO_CUOTA'] = $cuota['NRO_CUOTA'];
													$datCuota = $model->TraerDatosCuota($NRO_IDENTIFICACION, $datos['NRO_REFERENCIA'], $cuota['NRO_CUOTA'], $acuerdo);
													if(sizeof($datCuota) > 0) :
														if($datCuota['DIAS_MORA'] > 0) :
															$interes_mora = $apautpago->calcular_mora($cuota['FECHA_VENCIMIENTO'], $fecha_pago, $cuota['SALDO_CUOTA']);
														else :
															$interes_mora = 0;
														endif;
														$cuo['NRO_ACUERDOPAGO'] = $datCuota['NRO_ACUERDOPAGO'];
														$dis = $cuota['VALOR_CUOTA'];
														$dis -= $interes_mora;
														if($dis > $datCuota['VALOR_CUOTA']):
															$saldo_a_aplicar -= ($dis - $datCuota['VALOR_CUOTA']);
															$cuota_paga = $cuota['NRO_CUOTA'];
															$dis = $datCuota['VALOR_CUOTA'];
														endif;
														$datos['DISTRIBUCION_INTERES_MORA'] = $interes_mora;
														$cuo["PROYACUPAG_ESTADO"] = 0;
														if($dis > 0) :
															$datos['DISTRIBUCION_CAPITAL'] =
															floor(($dis)*($datCuota['CAPITAL']/(($datCuota['CAPITAL']+$datCuota['INTACUERDO']+$datCuota['INTCORRIENTE']))));
															$cuo["PROYACUPAG_SALDOCAPITAL"] = $datCuota['SALDO_CAPITAL'] - $datos['DISTRIBUCION_CAPITAL'];
															$tmpdatos['DISTRIBUCION_INTERES1'] =
															floor(($dis)*($datCuota['INTCORRIENTE']/(($datCuota['CAPITAL']+$datCuota['INTACUERDO']+$datCuota['INTCORRIENTE']))));
															$cuo["PROYACUPAG_SALDO_INTCORRIENTE"] = $datCuota['SALDO_INTCORRIENTE'] - $tmpdatos['DISTRIBUCION_INTERES1'];
															$tmpdatos['DISTRIBUCION_INTERES2'] =
															floor(($dis)*($datCuota['INTACUERDO']/(($datCuota['CAPITAL']+$datCuota['INTACUERDO']+$datCuota['INTCORRIENTE']))));
															$cuo["PROYACUPAG_SALDO_INTACUERDO"] = $datCuota['SALDO_INTACUERDO'] - $tmpdatos['DISTRIBUCION_INTERES2'];
															$datos['DISTRIBUCION_INTERES'] = $tmpdatos['DISTRIBUCION_INTERES1'] + $tmpdatos['DISTRIBUCION_INTERES2'];
														else:
															$datos['DISTRIBUCION_INTERES'] = $datos['DISTRIBUCION_CAPITAL'] = 0;
														endif;
														$cuo["PROYACUPAG_SALDO_CUOTA"] =	$cuo["PROYACUPAG_SALDO_INTACUERDO"] +
																																$cuo["PROYACUPAG_SALDO_INTCORRIENTE"] +
																																$cuo["PROYACUPAG_SALDOCAPITAL"];
														$datos['VALOR_PAGADO'] = $cuota['VALOR_CUOTA'];
														//$datos['SALDO_DEUDA'] = $fiscalizacion['SALDO_DEUDA'] - ($datos['VALOR_PAGADO'] - $datos['DISTRIBUCION_INTERES_MORA']);
														$datos['SALDO_CAPITAL'] = $fiscalizacion['SALDO_CAPITAL'] - $datos['DISTRIBUCION_CAPITAL'];
														$datos['SALDO_INTERES'] = $fiscalizacion['SALDO_INTERES'] - $datos['DISTRIBUCION_INTERES'];
														$datos['SALDO_DEUDA'] = $datos['SALDO_CAPITAL'] + $datos['SALDO_INTERES'];
														$datos['NRO_CUOTA'] = $cuo["CUOTA"] = $cuota['NRO_CUOTA'];
														$datos['VALOR_ADEUDADO'] = $fiscalizacion['SALDO_DEUDA'];
														$datos['APLICADO'] = 1;
														if($cuo["PROYACUPAG_SALDO_CUOTA"] <= 0) :
															$cuo["PROYACUPAG_ESTADO"] = 1;
														endif;
														//echo $saldo_a_aplicar;print_r($datos);print_r($cuota);print_r($cuo);exit;
														$aplicado = $apautpago->aplicar_pago($datos);
														if(is_array($aplicado)) :
															$res = "EL PAGO DE LA CUOTA NRO. " . $cuo['NRO_CUOTA'] . " DEL ACUERDO NRO. " . $cuo["NRO_ACUERDOPAGO"] . "  DE LA OBLIGACIÓN NRO. " . $datos['NRO_REFERENCIA'] . " presento errores.<br>" .$aplicado[1];
														else :
															$apautpago->aplicar_acuerdo($cuo);
															$res = true;
														endif;
													else :
														$res = "Error en los datos de la cuota a aplicar o esta ya fue aplicada.";
													endif;
												else :
													$pago['valor_aplicar'] = $cuota['VALOR_CUOTA'];
													if(!empty($pago)) :
														$pago['interes_mora'] = 0;
														$datos['DISTRIBUCION_CAPITAL'] = floor(
															($pago['valor_aplicar']-$pago['interes_mora']) *
															($fiscalizacion['TOTAL_CAPITAL']/(($fiscalizacion['TOTAL_CAPITAL']+$fiscalizacion['TOTAL_INTERESES'])))
														);
														$datos['DISTRIBUCION_INTERES'] = ceil(
															($pago['valor_aplicar']-$pago['interes_mora']) *
															($fiscalizacion['TOTAL_INTERESES']/(($fiscalizacion['TOTAL_CAPITAL']+$fiscalizacion['TOTAL_INTERESES'])))
														);
														$datos['DISTRIBUCION_INTERES_MORA'] = $pago['interes_mora'];
														$datos['VALOR_ADEUDADO'] = $fiscalizacion['SALDO_DEUDA'];
														$datos['SALDO_CAPITAL'] = $fiscalizacion['SALDO_CAPITAL'] - $datos['DISTRIBUCION_CAPITAL'];
														$datos['SALDO_INTERES'] = $fiscalizacion['SALDO_INTERES'] - $datos['DISTRIBUCION_INTERES'];
														$datos['SALDO_DEUDA'] = $datos['SALDO_CAPITAL'] + $datos['SALDO_INTERES'];
														//print_r($datos);//exit;
														$res = $apautpago->aplicar_pago($datos);
														if(is_array($res)) :
															$res = "EL PAGO DE LA OBLIGACIÓN NRO. " . $datos['NRO_REFERENCIA'] . " presento errores.<br>" .$aplicado[1];
														else :
															$apautpago->actualiza_saldo();
															$res = true;
														endif;
													else :
														$res = false;
													endif;
												endif;
											endforeach;
											if($saldo_a_aplicar > 0) :
												Ecollect_service::aplicar_cuotas_misional($NRO_IDENTIFICACION, $datos['NRO_REFERENCIA'], $cuota_paga, $saldo_a_aplicar, $datos);
											endif;
										endif;
									//Pago de cartera NO MISIONAL
									case 12 ://84
									case 11 ://85
									case 17 ://86
									case 30 ://87
									case 14 ://88
									case 15 ://89
									case 32 ://90
									case 19 ://91
									case 23 ://92
									case 25 ://93
										$keys = array_keys($ref['CUOTA']);
										foreach($keys as $key) :
											if(!is_numeric($key)) :
												$tmp = $ref['CUOTA'];
												$ref['CUOTA'] = NULL;
												$ref['CUOTA'][0] = $tmp;
												break;
											endif;
										endforeach;
										$fecha_pago = substr($datos['FECHA_TRANSACCION'], 0, 10);
										//print_r($ref);exit;
										//DATOS PARA APLICAR PAGO
										$text = $datosNm = array();
										foreach($ref['CUOTA'] as $cuota) :
											$datos['COD_REGIONAL'] = "11";//????$model->ObtenerRegionalEmpresa($NRO_IDENTIFICACION);
											$datos['NRO_REFERENCIA'] = $ref['NRO_REFERENCIAPAGO'];
											$DatCuota = $model->ObtenerDataCarteraNoMisional($datos['NRO_REFERENCIA'], $cuota['NRO_CUOTA'], $fecha_pago);
											if($ref['COD_CONCEPTO'] == 12) $datos['COD_SUBCONCEPTO'] = 84;
											if($ref['COD_CONCEPTO'] == 11) $datos['COD_SUBCONCEPTO'] = 85;
											if($ref['COD_CONCEPTO'] == 17) $datos['COD_SUBCONCEPTO'] = 86;
											if($ref['COD_CONCEPTO'] == 30) $datos['COD_SUBCONCEPTO'] = 87;
											if($ref['COD_CONCEPTO'] == 14) $datos['COD_SUBCONCEPTO'] = 88;
											if($ref['COD_CONCEPTO'] == 15) $datos['COD_SUBCONCEPTO'] = 89;
											if($ref['COD_CONCEPTO'] == 32) $datos['COD_SUBCONCEPTO'] = 90;
											if($ref['COD_CONCEPTO'] == 19) $datos['COD_SUBCONCEPTO'] = 91;
											if($ref['COD_CONCEPTO'] == 23) $datos['COD_SUBCONCEPTO'] = 92;
											if($ref['COD_CONCEPTO'] == 25) $datos['COD_SUBCONCEPTO'] = 93;
											$datos['COD_CONCEPTO'] = $DatCuota['CODTIPOCONCEPTO'];
											$datosNm['COD_SUBCONCEPTO'] = $datos['COD_SUBCONCEPTO'];
											//print_r($DatCuota);//exit;
											$datosNm['NO_CUOTA'] = $datos['NRO_CUOTA'] = $cuota['NRO_CUOTA'];
											$datosNm['ID_DEUDA_E'] = $datos['NRO_REFERENCIA'];
											if($DatCuota['DIAS_MORA'] > 0) :
												$interes_mora = $apautpago->calcular_mora_nomisional($DatCuota['CALCULO_MORA'], $ref['NRO_REFERENCIAPAGO'],
																														  $DatCuota['VALOR_T_MORA'], $DatCuota['FECHA_LIM_PAGO'],
																															$fecha_pago, $cuota['VALOR_CUOTA'], $dato['DIAS_MORA']);
											else :
												$interes_mora = 0;
											endif;
											$datosNm['INTERES_MORA_GEN'] = $interes_mora;
											if(($cuota['VALOR_CUOTA']-$interes_mora) > $DatCuota['SALDO_CUOTA']) :
												$saldo_a_aplicar = ($cuota['VALOR_CUOTA']-$interes_mora) - $DatCuota['SALDO_CUOTA'];
												$valor_pagado = ($DatCuota['SALDO_CUOTA']+$interes_mora);
											else :
												$saldo_a_aplicar = 0;
												$valor_pagado = $cuota['VALOR_CUOTA'];
											endif;
											if($saldo_a_aplicar >= $DatCuota['SALDO_INTERES_NO_PAGOS'] and $DatCuota['SALDO_INTERES_NO_PAGOS'] > 0) :
												$datosNm['SALDO_INTERES_NO_PAGOS'] = 0;
												$saldo_a_aplicar -= $DatCuota['SALDO_INTERES_NO_PAGOS'];
												$valor_intereses = $DatCuota['SALDO_INTERES_NO_PAGOS'];
											elseif($DatCuota['SALDO_INTERES_NO_PAGOS'] > 0) :
												$datosNm['SALDO_INTERES_NO_PAGOS'] = $DatCuota['SALDO_INTERES_NO_PAGOS'] - $saldo_a_aplicar;
												$valor_intereses = $datosNm['SALDO_INTERES_NO_PAGOS'];
												$saldo_a_aplicar = 0;
											else :
												$valor_intereses = 0;
											endif;
											$saldo_interes = $DatCuota['SALDO_INTERES_NO_PAGOS'];
											/*echo $DatCuota['SALDO_DEUDA']."\n";
											echo $DatCuota['SALDO_INTERES_NO_PAGOS']."\n";
											echo $saldo_a_aplicar."\n";
											echo $valor_intereses."\n";
											echo $valor_pagado."\n";
											echo $interes_mora."\n";*/
											if(($valor_pagado-$interes_mora) >= $DatCuota['SALDO_INTERES_C'] || $DatCuota['SALDO_INTERES_C'] == 0) :
												$datosNm['SALDO_INTERES_C'] = 0;
											elseif(($valor_pagado-$interes_mora) < $DatCuota['SALDO_INTERES_C']) :
												$datosNm['SALDO_INTERES_C'] = $DatCuota['SALDO_INTERES_C'] - ($cuota['VALOR_CUOTA'] - $interes_mora);
											endif;
											$datosNm['SALDO_AMORTIZACION'] = ($valor_pagado - $interes_mora - $DatCuota['SALDO_INTERES_C']);
											$datosNm['SALDO_AMORTIZACION'] = $DatCuota['SALDO_AMORTIZACION'] - $datosNm['SALDO_AMORTIZACION'];
											$datosNm['SALDO_CUOTA'] = $datosNm['SALDO_AMORTIZACION'] + $datosNm['SALDO_INTERES_C'];
											//$datosNm['COD_SUBCONCEPTO'] = $datos['COD_SUBCONCEPTO'];
											$datos['DISTRIBUCION_CAPITAL'] = ($valor_pagado - $interes_mora - $DatCuota['SALDO_INTERES_C']);
											$datos['DISTRIBUCION_INTERES'] = $DatCuota['SALDO_INTERES_C'] - $datosNm['SALDO_INTERES_C'];
											$datos['DISTRIBUCION_INTERES_MORA'] = $interes_mora;
											$datos['VALOR_PAGADO'] = $cuota['VALOR_CUOTA'];
											$datos['VALOR_ADEUDADO'] = $DatCuota['SALDO_CUOTA'] + $interes_mora;
											$datos['APLICADO'] = 1;
											//print_r($datos);print_r($datosNm);exit;
											$aplicado = $apautpago->aplicar_pago($datos);
											if(is_array($aplicado)) :
												$res = "EL PAGO DE LA CUOTA NRO. " . $DatCuota['NO_CUOTA'] . "  DE LA OBLIGACIÓN NRO. " . $ref['NRO_REFERENCIAPAGO'] . " presento errores.<br>" .$aplicado[1];
												break;
											else :
												$apautpago->AplicarNoMisional($datosNm);
												if($valor_intereses > 0) :
													$model->AplicarInteresNoPago($datosNm['ID_DEUDA_E'], $valor_intereses, $saldo_interes, $datosNm['NO_CUOTA']);
												endif;
												if($saldo_a_aplicar > 0) :
													$model->ActualizarCapitalNoMisional($datosNm['ID_DEUDA_E'], $saldo_a_aplicar);
												endif;
												$res = true;
											endif;
										endforeach;
										break;
									default :
										$res = "El código de concepto no existe en el sistema";
										break;
									//función cartera no misional
								endswitch;
								//print_r($datos);//exit;
							endforeach;
							if($res !== true) :
								$result = array('CODIGO_RETORNO' => "FAIL_SYSTEM",
																'DES_CODIGO' => $res);
							else :
								$result = array('CODIGO_RETORNO' => "SUCCESS",
																'DES_CODIGO' => 'El pago se aplico correctamente.');
							endif;
						else :
							$result = array('CODIGO_RETORNO' => "FAIL_SYSTEM",
															'DES_CODIGO' => 'El TICKETID ya fue usado anteriormente.');
						endif;
					else :
						$result = array('CODIGO_RETORNO' => "FAIL_SYSTEM",
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
				if($model->VerificarTicketId($TICKETID) === FALSE) :
				//echo "Imprimiendo reference<pre>";print_r($REFERENCE);exit("</pre>");
					if(is_array($REFERENCE)) :
						$basicos =	array("COD_SUBCONCEPTO", "NITEMPRESA", "RAZONSOCIAL", "PRIMERAPELLIDO", "TIPODOCUMENTO", "NATURALEZAJURIDICA",
													"DEPARTAMENTO", "CIUDAD", "DIRECCION", "CIUDADDOMICILIO", "LOCALIZACION", "TELEFONO", "EMAIL", "CODIGO_SIIF");
						$conceptos = array(
							"28" => array("REGIONAL", "REGIONAL_SIIF", "MES", "ANIO", "TIPOEMPRESA", "NRO_TRABAJADORES_PERIODO", "IBC", "DISTRIBUCION_INTERES_MORA", "VALOR_PAGO", "21"),
							"39" => array("REGIONAL", "REGIONAL_SIIF", "MES", "ANIO", /*"TIPOEMPRESA", "NRO_TRABAJADORES_PERIODO", "IBC",*/ "VALOR_PAGO", "DISTRIBUCION_INTERES_MORA", "VALOR_TOTAL", "59"),
							"5" => array("RESOLUCION", "FECHA_RESOLUCION", "REGIONAL", "REGIONAL_SIIF", "MES", "ANIO", "DISTRIBUCION_INTERES_MORA", "VALOR_PAGO", "VALOR_TOTAL", "19"),
							"31" => array("REGIONAL", "REGIONAL_SIIF", "NRO_PLANILLA", "FECHA_PAGO", "MES", "ANIO", "OPERADOR", "NRO_TRABAJADORES_PERIODO", "VALOR_PAGO", "DISTRIBUCION_INTERES_MORA", "TOTAL_PAGO", "21"),
							"33" => array("REGIONAL", "REGIONAL_SIIF", "VALOR_PAGADO", "VALOR_TOTAL", "64"),
							"37" => array("REGIONAL", "REGIONAL_SIIF", "VALOR_PAGADO", "VALOR_TOTAL", "69"),
							"34" => array("REGIONAL", "REGIONAL_SIIF", "VALOR_PAGADO", "VALOR_TOTAL", "71"),
							"35" => array("REGIONAL", "REGIONAL_SIIF", "VALOR_PAGADO", "VALOR_TOTAL", "73"),
							"20" => array("REGIONAL", "REGIONAL_SIIF", "VALOR_PAGADO", "VALOR_TOTAL", "75"),
							"21" => array("REGIONAL", "REGIONAL_SIIF", "VALOR_PAGADO", "VALOR_TOTAL", "76"),
							"38" => array("REGIONAL", "REGIONAL_SIIF", "VALOR_PAGADO", "VALOR_TOTAL", "78"),
							"22" => array("REGIONAL", "REGIONAL_SIIF", "ANIO", "MES", "VALOR_PAGADO", "VALOR_TOTAL", "65"),
							"13" => array("REGIONAL", "REGIONAL_SIIF", "TIPO_CARNE", "VALOR_PAGADO", "VALOR_TOTAL", "66"),
							"40" => array("NRO_CONTRATO", "FECHA_CONTRATO", "REGIONAL", "REGIONAL_SIIF", "VALOR_REINTEGRO", "VALOR_PAGO", "VALOR_TOTAL", "67"),
							"27" => array("NRO_CONVENIO", "ANIO_CONVENIO", "PERIODO_RENDIMIENTOS", "REGIONAL", "REGIONAL_SIIF", "VALOR_PAGO", "VALOR_TOTAL", "68"),
							"24" => array("NRO_CONTRATO", "FECHA_CONTRATO", "REGIONAL", "REGIONAL_SIIF", "VALOR_CONTRATO", "VALOR_PAGO", "VALOR_TOTAL", "70"),
							"16" => array("REGIONAL", "REGIONAL_SIIF", "NRO_ORDEN_VIAJE", "ANIO_PERIODO", "VALOR_PAGADO", "VALOR_TOTAL", "74"),
							"41" => array("REGIONAL", "REGIONAL_SIIF", "CONCEPTO_TITULO", "77"),
							"45" => array("REGIONAL", "REGIONAL_SIIF", "CONCEPTO_PAGO", "VALOR_PAGADO", "VALOR_TOTAL", "102"),
							"29" => array("REGIONAL", "REGIONAL_SIIF", "NRO_LICENCIA_CONTRATO_OBRA", "NRO_LICENCIA_CONTRATO_OBRA", "NOMBRE_OBRA", "FECHA_INICIO_OBRA", "FECHA_FIN_OBRA", "CIUDAD_OBRA", "TIPO_FIC", "ANIO_PERIODO", "MES_PERIODO", "COSTO_TOTAL_OBRA_TODO_COSTO", "COSTO_TOTAL_MANO_DE_OBRA", "NRO_TRABAJADORES_PERIODO", "DISTRIBUCION_INTERES_MORA", "VALOR_APORTE", "APORTE_SENA", "VALOR_PAGO", "VALOR_TOTAL", "17"),
							"4390" => array("VALOR_PAGO", "VALOR_TOTAL", "100"),
							"4201" => array("VALOR_PAGO", "VALOR_TOTAL", "94"),
							"4203" => array("VALOR_PAGO", "VALOR_TOTAL", "96"),
							"4202" => array("VALOR_PAGO", "VALOR_TOTAL", "95"),
							"4204" => array("VALOR_PAGO", "VALOR_TOTAL", "97"),
							"4305" => array("VALOR_PAGO", "VALOR_TOTAL", "98"),
							"4345" => array("VALOR_PAGO", "VALOR_TOTAL", "99")
						);
						$campos_tabla = array("NITEMPRESA", "PROCEDENCIA", "COD_PROCEDENCIA", "COD_CONCEPTO", "COD_SUBCONCEPTO", "COD_REGIONAL",
																	"PERIODO_PAGADO", "FECHA_PAGO", "APLICADO", "CONCILIADO", "FECHA_APLICACION", "COD_FORMAPAGO",
																	"NUM_DOCUMENTO", "DISTRIBUCION_CAPITAL", "DISTRIBUCION_INTERES", "DISTRIBUCION_INTERES_MORA",
																	"COD_FISCALIZACION", "COD_PLANILLAUNICA", "NRO_REFERENCIA", "VALOR_ADEUDADO", "NRO_CUOTA", "OPERADOR",
																	"NRO_TRABAJADORES_PERIODO", "NRO_TRABAJADORES_ADICIONAR", "NRO_RESOLUCION_REGULACION", "FECHA_RESOLUCION",
																	"NRO_LICENCIA_CONTRATO_OBRA", "NOMBRE_OBRA", "FECHA_INICIO_OBRA", "FECHA_FIN_OBRA", "CIUDAD_OBRA", "TIPO_FIC",
																	"COSTO_TOTAL_OBRA_TODO_COSTO", "COSTO_TOTAL_MANO_DE_OBRA", "NRO_ORDEN_VIAJE", "NRO_RESOLUCION_MULTA",
																	"TIPO_CARNE", "NRO_CONVENIO", "CONCEPTO_PAGO", "FECHA_TRANSACCION", "COD_ENTIDAD", "VALOR_PAGADO",
																	"REGIONAL_SIIF", "CENTRO_SIIF", "CODIGO_SIIF", "TICKETID");
						foreach($REFERENCE as $key=>$ref) :
							if(isset($basicos[$key])) :
								$datos[$basicos[$key]] = $ref;
								unset($REFERENCE[$key]);
							endif;
						endforeach;
						//print_r($REFERENCE);
						$REFERENCE = array_values($REFERENCE);
						//print_r($REFERENCE);
						//echo "\nCod subconcepto: ".$datos["COD_SUBCONCEPTO"];
						//echo "\n";print_r($REFERENCE);
						foreach($REFERENCE as $key=>$ref) :
							if($conceptos[$datos["COD_SUBCONCEPTO"]][$key] == "NRO_LICENCIA_CONTRATO_OBRA") :
								if(!isset($datos["NRO_LICENCIA_CONTRATO_OBRA"])) :
									$datos[$conceptos[$datos["COD_SUBCONCEPTO"]][$key]] = $ref;
								elseif(empty($datos["NRO_LICENCIA_CONTRATO_OBRA"])) :
									$datos[$conceptos[$datos["COD_SUBCONCEPTO"]][$key]] = $ref;
								endif;
							else :
								$datos[$conceptos[$datos["COD_SUBCONCEPTO"]][$key]] = $ref;
							endif;
							unset($REFERENCE[$key]);
						endforeach;
						//print_r($datos);exit;
						$datos['COD_SUBCONCEPTO'] = $conceptos[$datos["COD_SUBCONCEPTO"]][count($conceptos[$datos["COD_SUBCONCEPTO"]])-1];
						$datos['COD_REGIONAL'] = (isset($datos['REGIONAL_SIIF']))
												 ? $model->ObtenerRegionalSiif($datos['REGIONAL_SIIF'])
												 : $model->ObtenerRegionalEmpresa($datos['NITEMPRESA']);
						$datos['COD_CONCEPTO'] = $model->ObtenerCodConcepto($datos['COD_SUBCONCEPTO']);
						unset($datos['RAZONSOCIAL']);
						unset($datos['PRIMERAPELLIDO']);
						unset($datos['NATURALEZAJURIDICA']);
						unset($datos['DIRECCION']);
						unset($datos['LOCALIZACION']);
						unset($datos['TELEFONO']);
						unset($datos['EMAIL']);
						unset($datos['IBC']);
						unset($datos['TIPOEMPRESA']);
						unset($datos['REGIONAL']);
						unset($datos['CIUDADDOMICILIO']);
						unset($datos['CIUDAD']);
						unset($datos['DEPARTAMENTO']);
						unset($datos['TIPODOCUMENTO']);
						if(isset($datos['MES'])) :
							$datos['PERIODO_PAGADO'] = $datos['ANIO']."-".str_pad($datos['MES'], 2, 0, STR_PAD_LEFT);
							unset($datos['MES']);
						endif;
						unset($datos['ANIO']);
						$datos['FECHA_PAGO'] = substr($FECHA_ENTIDADFINANCIERA, 0, 10);
						$datos['FECHA_TRANSACCION'] = substr(str_replace("T", " ", $FECHA_ENTIDADFINANCIERA), 0, 19);
						$datos['COD_FORMAPAGO'] = $MEDIO_PAGO;
						$datos['VALOR_ADEUDADO'] = $datos['DISTRIBUCION_INTERES'] = $datos['NRO_REFERENCIA'] = NULL;
						$datos['PROCEDENCIA'] = "ECOLLECT";
						$datos['APLICADO'] = 1;
						$datos['NUM_DOCUMENTO'] = $datos['TICKETID'] = $TICKETID;
						$datos['COD_ENTIDAD'] = $model->CodigoBanco(substr($ENTIDAD_FINANACIERA, 0, 4));
						$datos['VALOR_PAGADO'] = $VALOR_PAGO;
						$datos['COD_FORMAPAGO'] = ($datos['COD_FORMAPAGO'] == 0)?3:4;
						//print_r($datos);exit;
						$keys = array_keys($datos);
						//print_r($keys);exit;
						foreach($keys as $key) :
							foreach($campos_tabla as $campo) :
								if($key == $campo) $tmpdatos[$key] = $datos[$key];
							endforeach;
						endforeach;
						$datos = $tmpdatos;
						//print_r($datos);exit;
						$res = $apautpago->AplicarPagoOrdinario($datos);
						if($res !== true) :
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
					$result = array("CODIGO_RETORNO" => "FAIL_SYSTEM", "DES_CODIGO" => 'El TICKETID ya fue usado anteriormente.');
				endif;
			else :
				$result = array("CODIGO_RETORNO" => "FAIL_SYSTEM", "DES_CODIGO" => 'Falla del sistema, error de reference.');
			endif;
		else :
			$result = array("CODIGO_RETORNO" => "FAIL_SYSTEM", "DES_CODIGO" => 'Falla del sistema.');
		endif;
		return $result;
	}

	private function aplicar_cuotas_misional($NRO_IDENTIFICACION, $NRO_REFERENCIA, $cuota_paga, $saldo_a_aplicar, $datos) {
		$CI = & get_instance();
		$CI->load->model("Ecollect_model");
		$CI->load->model("Aplicacionautomaticadepago_model");
		$model = new Ecollect_model();
		$apautpago = new Aplicacionautomaticadepago_model();
		$datCuotas = $model->TraerDatosCuotas($NRO_IDENTIFICACION, $NRO_REFERENCIA, $cuota_paga);
		$fiscalizacion = $model->TraerFiscalizacion($NRO_REFERENCIA);
		//print_r($datCuotas);exit;
		if(sizeof($datCuotas) > 0) :
			foreach($datCuotas as $datCuota) :
				if($saldo_a_aplicar > 0) :
					if($datCuota['DIAS_MORA'] > 0) :
						$interes_mora = $apautpago->calcular_mora($cuota['FECHA_VENCIMIENTO'], $fecha_pago, $cuota['SALDO_CUOTA']);
					else :
						$interes_mora = 0;
					endif;
					$cuo['NRO_ACUERDOPAGO'] = $datCuota['NRO_ACUERDOPAGO'];
					if($saldo_a_aplicar > ($datCuota['VALOR_CUOTA'] + $interes_mora)) :
						$dis = $datCuota['VALOR_CUOTA'] + $interes_mora;
						$saldo_a_aplicar -= $dis;
					elseif($saldo_a_aplicar < $interes_mora) :
						$dis = $saldo_a_aplicar;
						$saldo_a_aplicar = 0;
					else :
						$dis = $saldo_a_aplicar;
						$saldo_a_aplicar = 0;
					endif;
					$cuota['VALOR_CUOTA'] = $dis;
					$saldo_a_aplicar -= $datCuota['VALOR_CUOTA'];
					$dis -= $interes_mora;
					$datos['DISTRIBUCION_INTERES_MORA'] = $interes_mora;
					$cuo["PROYACUPAG_ESTADO"] = 0;
					//echo "\n\n".$dis."\n\n";
					if($dis > 0) :
						$datos['DISTRIBUCION_CAPITAL'] =
						floor(($dis)*($datCuota['CAPITAL']/(($datCuota['CAPITAL']+$datCuota['INTACUERDO']+$datCuota['INTCORRIENTE']))));
						$cuo["PROYACUPAG_SALDOCAPITAL"] = $datCuota['SALDO_CAPITAL'] - $datos['DISTRIBUCION_CAPITAL'];
						$tmpdatos['DISTRIBUCION_INTERES1'] =
						floor(($dis)*($datCuota['INTCORRIENTE']/(($datCuota['CAPITAL']+$datCuota['INTACUERDO']+$datCuota['INTCORRIENTE']))));
						$cuo["PROYACUPAG_SALDO_INTCORRIENTE"] = $datCuota['SALDO_INTCORRIENTE'] - $tmpdatos['DISTRIBUCION_INTERES1'];
						$tmpdatos['DISTRIBUCION_INTERES2'] =
						floor(($dis)*($datCuota['INTACUERDO']/(($datCuota['CAPITAL']+$datCuota['INTACUERDO']+$datCuota['INTCORRIENTE']))));
						$cuo["PROYACUPAG_SALDO_INTACUERDO"] = $datCuota['SALDO_INTACUERDO'] - $tmpdatos['DISTRIBUCION_INTERES2'];
						$datos['DISTRIBUCION_INTERES'] = $tmpdatos['DISTRIBUCION_INTERES1'] + $tmpdatos['DISTRIBUCION_INTERES2'];
					else:
						$datos['DISTRIBUCION_INTERES'] = $datos['DISTRIBUCION_CAPITAL'] = 0;
					endif;
					$cuo["PROYACUPAG_SALDO_CUOTA"] =	$cuo["PROYACUPAG_SALDO_INTACUERDO"] +
																							$cuo["PROYACUPAG_SALDO_INTCORRIENTE"] +
																							$cuo["PROYACUPAG_SALDOCAPITAL"];
					$datos['VALOR_PAGADO'] = $cuota['VALOR_CUOTA'];
					//$datos['SALDO_DEUDA'] = $fiscalizacion['SALDO_DEUDA'] - ($datos['VALOR_PAGADO'] - $datos['DISTRIBUCION_INTERES_MORA']);
					$datos['SALDO_CAPITAL'] = $fiscalizacion['SALDO_CAPITAL'] - $datos['DISTRIBUCION_CAPITAL'];
					$datos['SALDO_INTERES'] = $fiscalizacion['SALDO_INTERES'] - $datos['DISTRIBUCION_INTERES'];
					$datos['SALDO_DEUDA'] = $datos['SALDO_CAPITAL'] + $datos['SALDO_INTERES'];
					$datos['NRO_CUOTA'] = $cuo["CUOTA"] = $datCuota['NO_CUOTA'];
					$datos['VALOR_ADEUDADO'] = $fiscalizacion['SALDO_DEUDA'];
					$datos['APLICADO'] = 1;
					if($cuo["PROYACUPAG_SALDO_CUOTA"] <= 0) :
						$cuo["PROYACUPAG_ESTADO"] = 1;
					endif;
					//print_r($datos);print_r($cuo);exit;
					$apautpago->aplicar_acuerdo($cuo);
				endif;
			endforeach;
		endif;
	}
}

function RegistrarEmpresa($identidad, $clave, $NRO_IDENTIFICACION, $COD_DEPARTAMENTO, $TEL_FIJO, $RAZON_SOCIAL,
													$COD_TIPODOCUMENTO, $REPRESENTANTE_LEGAL) {
	return Ecollect_service::RegistrarEmpresa($identidad, $clave, $NRO_IDENTIFICACION, $COD_DEPARTAMENTO, $TEL_FIJO, $RAZON_SOCIAL,
																						$COD_TIPODOCUMENTO, $REPRESENTANTE_LEGAL);
}

function ObtenerCartera($identidad, $clave, $NRO_IDENTIFICACION) {
	return $data = Ecollect_service::ObtenerCartera($identidad, $clave, $NRO_IDENTIFICACION);
	//echo "<pre>";print_r($data);echo "</pre>";
}

function NotificarPagoCartera($identidad, $clave, $NRO_IDENTIFICACION, $REFERENCE, $TICKETID, $ENTIDAD_FINANACIERA,
															$MEDIO_PAGO, $FECHA_ENTIDADFINANCIERA, $NRO_CONF_ENTI_FINANCIERA) {
	return Ecollect_service::NotificarPagoCartera($identidad, $clave, $NRO_IDENTIFICACION, $REFERENCE, $TICKETID, $ENTIDAD_FINANACIERA,
																								$MEDIO_PAGO, $FECHA_ENTIDADFINANCIERA, $NRO_CONF_ENTI_FINANCIERA);
}

function NotificarPagoOrdinario($identidad, $clave, $REFERENCE, $TICKETID, $ENTIDAD_FINANACIERA, $MEDIO_PAGO,
																$FECHA_ENTIDADFINANCIERA, $NRO_CONF_ENTI_FINANCIERA, $VALOR_PAGO) {
	return Ecollect_service::NotificarPagoOrdinario($identidad, $clave, $REFERENCE, $TICKETID, $ENTIDAD_FINANACIERA, $MEDIO_PAGO,
																									$FECHA_ENTIDADFINANCIERA, $NRO_CONF_ENTI_FINANCIERA, $VALOR_PAGO);
}
