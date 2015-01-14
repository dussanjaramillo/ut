<?php
if ( ! defined('BASEPATH')) exit('No se permite el acceso directo a las p&aacute;ginas de este sitio.');

/**
 * ecollect_service (class MY_Controller) :)
 *
 * Ecollect Web Service
 *
 * Permite gestionar toda las transacciones de comunicación con ECOLLECT.
 *
 * @author Felipe Camacho [camachogfelipe]
 * @author http://www.cogroupsas.com
 *
 * @package Ecollect_client
 */
class Ecollect_client extends MY_Controller {

  private $soapclient;

  function __construct() {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->file("application/libraries/nusoap/nusoap.php", false);
  }

  function index() {
    ob_clean();
    $this->soapclient = new nusoap_client(base_url('index.php/ecollect_service?wsdl'), 'wsdl');
		//$this->soapclient->debug();

    $err = $this->soapclient->getError();
    if ($err) {
      echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
    } // endif

		/* Prueba de registro de empresa */
		$call = 'ObtenerCartera';
		
		$data[] = array('COD_CONCEPTO' => '1',
										'NRO_REFERENCIAPAGO' => '1179951894113',
										'CUOTA' =>	array(
																	array(
																		'NRO_CUOTA' => '',
																		'VALOR_CUOTA' => '400000'
																	)
																)
										);
									 
		$parts = array('identidad' => 'felipe@cogroupsas.com', 'clave' => 'Sena0702', 'NRO_IDENTIFICACION' => '830507528');/*, 
									 'REFERENCE' => $data, 'TICKETID' => '1234567890', 'ENTIDAD_FINANACIERA' => '1', 'MEDIO_PAGO' => '0', 
									 'FECHA_ENTIDADFINANCIERA' => '2014-07-21 12:48:20', 'NRO_CONF_ENTI_FINANCIERA' => '9876543210');									 
		/*ob_clean();
    header ("Content-Type:text/xml");*/
		//echo "<pre>";print_r($parts);exit();
		
    $result = $this->soapclient->call($call, $parts);
		// Gestionamos la respuesta
    $this->_manage_response($result, $this->soapclient->fault, $this->soapclient->getError());
  }

  /*
   * Acción predeterminada para las pruebas del webservices cliente
   */

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
        // !Que felicidad, desplegamos el resultado!
				echo "<pre>";print_r($result);echo "</pre>";exit();
        ob_clean();
        header ("Content-Type:text/xml");
        echo base64_decode($result);
      }
    }
    return;
  }
	
	/* Función para retornar los datos en un xml */
  function array_to_xml(array $arr, SimpleXMLElement $xml) {
    foreach ($arr as $k => $v) {
      is_array($v) ? Ecollect_service::array_to_xml($v, $xml->addChild($k)) : $xml->addChild($k, $v);
    }
    return $xml;
  }

// end function
}