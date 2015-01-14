<?php
if ( ! defined('BASEPATH')) exit('No se permite el acceso directo a las p&aacute;ginas de este sitio.');

class Kactus_client extends MY_Controller {

  private $soapclient;

  function __construct() {
    parent::__construct();
    $this->load->library('form_validation');
	$this->load->model('carteranomisional_model','',TRUE);
    $this->load->file("application/libraries/nusoap/nusoap.php", false);
  }

  function index() {
    ob_clean();
	  
	  $proxyhost = 'proxy2.sena.edu.co';
      $proxyport = '80';
	  
    $this->soapclient = new nusoap_client('http://10.104.180.6/wsc/ws_cartera_kact.asmx?WSDL', 'wsdl');
	

		//$this->soapclient->debug();

    $err = $this->soapclient->getError();
    if ($err) {
      echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
    } // endif

		/* Prueba de registro de empresa */
	//$periodo='2014-08';	
	$periodo=date("Y-m");
	
	$fechapas=date("Ym");
	//$fechapas='201408';
	$novedades=$this->carteranomisional_model->getNovedadesCNM($periodo);	

	$res=0;
	foreach ($novedades->result_array as $data) {
	$parts = array(		'IDDEUDA' => $data['IDDEUDA'], 
						'NRO_CTA' => $data['NRO_CTA'], 
						'COD_EMPL' => $data['CEDULA'], 
						'VLR_CTA' => $data['VLR_CTA'], 
						'FEC_MES' => $fechapas, 
						'COD_CONC' => $data['CONCEPTO_HOMOLOGADO'],  
						'CodAc' => '5i5t3m4_c4KT0c'
					   );	
	$call = 'Cartera_Novedades';
    $result[$res][$data['IDDEUDA']] = $this->soapclient->call($call, $parts);
	$res++;
	}

$this->_manage_response($result, $this->soapclient->fault, $this->soapclient->getError());	
	return;
		// Gestionamos la respuesta
  }

  /*
   * Acci�n predeterminada para las pruebas del webservices cliente
   */
  
    function actualizar_empleados() {
    ob_clean();
	  
	  $proxyhost = 'proxy2.sena.edu.co';
      $proxyport = '80';
	  
    $this->soapclient = new nusoap_client('http://10.104.180.6/wsc/ws_cartera_kact.asmx?WSDL', 'wsdl');
	

		//$this->soapclient->debug();

    $err = $this->soapclient->getError();
    if ($err) {
      echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
    } // endif


	$parts = array(		'CodAc' => '5i5t3m4_c4KT0c'
					   );	
	$call = 'Consulta_Cambios';
	$empleadosini= array();
	$empleados=$this->carteranomisional_model->getEmpleadosCNM();
	foreach ($empleados->result_array() as $row)
{
	array_push($empleadosini, $row['IDENTIFICACION']);
}
	$result= $this->soapclient->call($call, $parts);
	$empleadoswebs=$result['Consulta_CambiosResult']["Consulta_Cambio"];
	/*echo "<pre>";
	var_dump($empleadoswebs);
	echo "</pre>";*/
	//var_dump(htmlentities($empleadoswebs));
	//die();
	
	foreach ($empleadoswebs as $empleadows) {
												$data = array(
																'IDENTIFICACION' => htmlentities($empleadows['CEDULA']),
																'NOMBRES' => htmlentities($empleadows['NOMBRES']),
																'APELLIDOS' => htmlentities($empleadows['APELLIDOS']),
																'DIRECCION' => htmlentities($empleadows['DIR_RESI']),			
																'TELEFONO' => htmlentities($empleadows['TEL_RESI']),	
																'CORREO_ELECTRONICO' => htmlentities($empleadows['EEE_MAIL']),	
																'DEPENDENCIA' => htmlentities($empleadows['CENTRO']),
																'COD_ESTADO_E' => htmlentities($empleadows['IND_ACTI']),
																'COD_REGIONAL' => htmlentities($empleadows['REGIONAL']),
												);	
		if(in_array($empleadows['CEDULA'], $empleadosini)){
			
			//echo 	$empleadows['CEDULA']." existe";
			if ($this->carteranomisional_model->update('CNM_EMPLEADO',$data,$empleadows['CEDULA'],'IDENTIFICACION') == TRUE)
			{
				echo "Cedula: ".$empleadows['CEDULA']." Actualizada.</br>" ;
			}
			else
			{
				echo "Cedula: ".$empleadows['CEDULA']." No se pudo actualizar.</br>";
			}
		}
		else{
			//echo 	$empleadows['CEDULA']." no existe";
			if ($this->carteranomisional_model->add('CNM_EMPLEADO',$data) == TRUE)
			{
				echo "Cedula: ".$empleadows['CEDULA']." Creada.</br>";
				array_push($empleadosini, $empleadows['CEDULA']);
			}
			else
			{
				echo "Cedula: ".$empleadows['CEDULA']." No se pudo crear.</br>";
			}
		}
	}
$this->_manage_response($result, $this->soapclient->fault, $this->soapclient->getError());	
	return;
		// Gestionamos la respuesta
  }
  
      function actualizar_descuentos(){
      ob_clean();
	  
	  $proxyhost = 'proxy2.sena.edu.co';
	  $proxyport = '80';
	  
    $this->soapclient = new nusoap_client('http://10.104.180.6/wsc/ws_cartera_kact.asmx?WSDL', 'wsdl');
	

		//$this->soapclient->debug();

    $err = $this->soapclient->getError();
    if ($err) {
      echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
    } // endif

$fechapas=date("Ym");
	$parts = array(	
	'Fec_Mes' => $fechapas,
	'CodAc' => '5i5t3m4_c4KT0c'
					   );	
	$call = 'Consulta_Novedades';

	$result= $this->soapclient->call($call, $parts);
	$novedadesdesc=$result['Consulta_NovedadesResult']["Consulta_Cart"];
	
	$fecha=date("d/m/Y");
	//falta
	
	
	foreach ($novedadesdesc as $novedadws) {
	$concepto=$this->carteranomisional_model->getConceptoCNM($novedadws['COD_CONC']);;
	//echo($concepto[0]["CONCEPTO_HOMOLOGADO"]); 
	//die();
		
														$date = array(
																'FECHA_PAGO' => $fecha,
												);
		
												$data = array(
																'ID_DEUDA_K' => $novedadws['IDDEUDA'],
																'NO_CUOTA' => $novedadws['NRO_CTA'],
																'CONCEPTO' => $concepto[0]["CONCEPTO_HOMOLOGADO"],
																'VALOR_PAGADO' => $novedadws['VLR_DCTO'],
																'COD_REGIONAL' => $novedadws['REGIONAL'],
												);	

			if ($this->carteranomisional_model->addNovedadVal('CNM_CUOTAS_KACTUS',$data,$date) == TRUE)
			{
				echo "Deuda: ".$novedadws['IDDEUDA']." Actualizada.</br>" ;
			}
			else
			{
				echo "Deuda: ".$novedadws['IDDEUDA']." No se pudo actualizar.</br>";
			}

	}
	

$this->_manage_response($result, $this->soapclient->fault, $this->soapclient->getError());	
	return;
		// Gestionamos la respuesta
  }
  
  
  function update_empleado($cedula) {
  		
  		
		
  	$this->carteranomisional_model->updateEmpleadosCNM($cedula);
	}
  
  
  function crear_empleado($cedula) {
  		
		
  	$this->carteranomisional_model->addEmpleadosCNM($cedula);
	}

  private function _manage_response($result, $is_fault, $is_error) {
    // Fallas
    if ($is_fault) {
      echo '<h2>Falla:</h2><pre>';
      print_r($result);
      echo '</pre>';
      echo '<h2>Request</h2><pre>' . htmlspecialchars($this->soapclient->request, ENT_QUOTES) . '</pre>';
      echo '<h2>Response</h2><pre>' . htmlspecialchars($this->soapclient->response, ENT_QUOTES) . '</pre>';
      echo '<h2>Debug</h2><pre>' . htmlspecialchars($this->soapclient->debug_str, ENT_QUOTES) . '</pre>';
    } else {
      // Errores
      if ($is_error) {
        // Imprimir los detalles del error
        echo '<h2>Error:</h2><pre>' . $is_error . '</pre>';
        echo '<h2>Request</h2><pre>' . htmlspecialchars($this->soapclient->request, ENT_QUOTES) . '</pre>';
        echo '<h2>Response</h2><pre>' . htmlspecialchars($this->soapclient->response, ENT_QUOTES) . '</pre>';
        echo '<h2>Debug</h2><pre>' . htmlspecialchars($this->soapclient->debug_str, ENT_QUOTES) . '</pre>';
      } else {
        // ¡Que felicidad, desplegamos el resultado!
				echo "<pre>";print_r($result);echo "</pre>";exit();
        ob_clean();
        header ("Content-Type:text/xml");
        echo base64_decode($result);
      }
    }
    return;
  }
	
	/* Funci�n para retornar los datos en un xml */
  function array_to_xml(array $arr, SimpleXMLElement $xml) {
    foreach ($arr as $k => $v) {
      is_array($v) ? Ecollect_service::array_to_xml($v, $xml->addChild($k)) : $xml->addChild($k, $v);
    }
    return $xml;
  }

// end function
}