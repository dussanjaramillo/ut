<?php

/**
 * Cnm_liquidacioncuotas (class MY_Controller) :)
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
class Cnm_liquidacioncuotas extends MY_Controller {
	
	private $DatosBase = array();

  function __construct() {
    parent::__construct();
    $this->load->model('cnm_liquidacioncuotas_model');
  }
	
	function cnmPruebas() {
		$fechaActivacion = "01/01/2015";
		$medioPago = 1;
		$DatosBase['VALOR_DEUDA'] = 90000000;
		$DatosBase['CALCULO_CORRIENTE'] = 1;
		$DatosBase['TIPO_T_V_CORRIENTE'] = 8;
		$DatosBase['TIPO_T_F_CORRIENTE'] = 14;
		$DatosBase['COD_FORMAPAGO'] = 1;
		$DatosBase['COD_PLAZO'] = 2;
		$DatosBase['PLAZO_CUOTAS'] = 180;
		$DatosBase['VALOR_CUOTA'] = 950000;
		$DatosBase['VALOR_T_CORRIENTE'] = 8;
		//Hipotecario
		$DatosBase['SALARIO'] = 3950172;
		$DatosBase['TASA_INTERES_CESANTIAS'] = 0;
		$DatosBase['VALOR_CUOTA_APROBADA'] = 950000;
		$DatosBase['FECHA_RETIRO'] = "2015-11-01";
		$DatosBase['GRADIENTE'] = 0.08;
		$DatosBase['TASA_IPC'] = 0.02;
		$DatosBase['FACTOR_TIPO'] = 1.35;
		$DatosBase['RESPUESTA'] = 1;
		
		//echo "<pre>";print_r($DatosBase);echo "</pre>";exit;
		
		//$this->CnmCalcularCuotasVista($DatosBase, $fechaActivación, $medioPago, true, false, false);
		
		$cuotas = $this->CnmCalcularCuotas(431, $fechaActivacion, 1, TRUE);
		echo "<pre>";print_r($cuotas);echo "</pre>";
	}
	
	function CnmCalcularCuotas($cod_cartera = NULL, $fecha_activacion = NULL, $medio_pago = 1, $hipotecario = false, $reliquidacion = false, $refinanciacion = false, $vista = false, $ecollect = false) {
		//echo $cod_cartera." - ".$fecha_activacion." - ".$medio_pago." - ".$hipotecario." - ".$reliquidacion." - ".$refinanciacion;//exit;
		if(!is_null($cod_cartera) and is_numeric($cod_cartera)) :
			$this->DatosBase = $this->cnm_liquidacioncuotas_model->ObtenerDataParaCuota($cod_cartera);
			if($this->DatosBase['VALOR_DEUDA'] > 0) :
				$this->DatosBase['MINCUOTA'] = 1;
				//echo "<pre>";print_r($this->DatosBase);exit("</pre>");
				if($reliquidacion === true and $refinanciacion === true and $this->DatosBase['COD_PLAZO'] == 1):
					if($this->DatosBase['COD_PLAZO'] == 1) :
						return true;
					else :
						return false;
					endif;
				endif;
				if($reliquidacion === true and $refinanciacion === false and $this->DatosBase['COD_PLAZO'] != 1) :
					$this->DatosBase['RELIQUIDACION'] = true;
					$data = $this->cnm_liquidacioncuotas_model->ObtenerCuotaMinima($cod_cartera);
					$this->DatosBase['MINCUOTA'] = $data['MIN_CUOTA'];
					$this->DatosBase['VALOR_DEUDA'] = $data['SALDO_FINAL_CAP'];
					$this->DatosBase['VALOR_CUOTA'] = $this->DatosBase['VALOR_CUOTA_APROBADA'];
					$this->DatosBase['MES_PROYECTADO'] = $data['MES_PROYECTADO'];
					$this->DatosBase['SALDO_INTERES_NO_PAGOS'] = $data['SALDO_INTERES_NO_PAGOS'];
				endif;
				if($refinanciacion !== false and $reliquidacion === false and $this->DatosBase['COD_PLAZO'] != 1) :
					$this->DatosBase['RELIQUIDACION'] = true;
					switch($refinanciacion) :
						case 1 :
							$data = $this->cnm_liquidacioncuotas_model->ObtenerCuotaMinima($cod_cartera);
							$this->DatosBase['MINCUOTA'] = $data['MIN_CUOTA'];
							$this->DatosBase['VALOR_DEUDA'] = $data['SALDO_FINAL_CAP'];
							$this->DatosBase['VALOR_CUOTA'] = $this->DatosBase['VALOR_CUOTA_APROBADA'];
							$this->DatosBase['MES_PROYECTADO'] = $data['MES_PROYECTADO'];
							$this->DatosBase['SALDO_INTERES_NO_PAGOS'] = $data['SALDO_INTERES_NO_PAGOS'];
							break;
						default :
							$pagado = $this->cnm_liquidacioncuotas_model->ObtenerPagos($cod_cartera);
							$this->DatosBase['VALOR_DEUDA'] -= $pagado;
							break;
					endswitch;
				endif;

				if(is_null($this->DatosBase['MINCUOTA']) || empty($this->DatosBase['MINCUOTA'])) :
					$this->DatosBase['MINCUOTA'] = 1;
				endif;
				//$this->DatosBase['VALOR_DEUDA'] = $this->DatosBase['SALDO_DEUDA'];
				//echo "<pre>";print_r($this->DatosBase);echo "</pre>";//exit;
				//echo $fecha_activacion = date("d/m/Y");
				if($this->DatosBase['PLAZO_CUOTAS'] > 1) :
					$this->cnm_liquidacioncuotas_model->BorrarCuotas($cod_cartera, $this->DatosBase['MINCUOTA']);//exit;
				endif;
				//echo "<br>".$medio_pago;
				if($this->DatosBase != false) :
							$this->DatosBase['fecha_activacion'] = (is_null($fecha_activacion) || empty($fecha_activacion))?date("Y-m-d"):date("Y-m-d", strtotime(str_replace("/", "-",$fecha_activacion)));
							if($reliquidacion === true and $refinanciacion === false and $this->DatosBase['COD_PLAZO'] != 1) :
							$this->DatosBase['fecha_activacion']=date("Y-m-d", strtotime($this->DatosBase['MES_PROYECTADO']."-01"));
							endif;
							if($refinanciacion !== false and $reliquidacion === false and $this->DatosBase['COD_PLAZO'] != 1) :
							$this->DatosBase['fecha_activacion']=date("Y-m-d", strtotime($this->DatosBase['MES_PROYECTADO']."-01"));
							endif;
					//echo $this->DatosBase['fecha_activacion'];exit;
					$this->DatosBase['medio_pago'] = $this->DatosBase['COD_FORMAPAGO'];
					//Calculo fijo
					if($this->DatosBase['CALCULO_CORRIENTE'] == 1) :
						//COD_CARTERA_NOMISIONAL, COD_TIPOCARTERA, VALOR_DEUDA, TIPO_T_F_CORRIENTE, VALOR_T_CORRIENTE, PLAZO_CUOTAS, COD_PLAZO
						unset($this->DatosBase['TIPO_T_V_CORRIENTE']);
						$this->DatosBase['VALOR_T_CORRIENTE'] = $this->cnm_liquidacioncuotas_model
																												 ->DefinirTipoTasa(
																														$this->DatosBase['TIPO_T_F_CORRIENTE'], $this->DatosBase['VALOR_T_CORRIENTE']
																													 );
					//Calculo variable
					elseif($this->DatosBase['CALCULO_CORRIENTE'] == 2 and $hipotecario != false) :
						//COD_CARTERA_NOMISIONAL, COD_TIPOCARTERA, VALOR_DEUDA, TIPO_T_V_CORRIENTE, VALOR_T_CORRIENTE, PLAZO_CUOTAS, COD_PLAZO
						unset($this->DatosBase['TIPO_T_F_CORRIENTE']);
						$this->DatosBase['VALOR_T_CORRIENTE'] = $this->cnm_liquidacioncuotas_model
																												 ->TraerValorTasaVariable($cod_cartera);
					endif;
					//echo "<pre>";print_r($this->DatosBase);echo "</pre>";
					//$vista = true;
					if($hipotecario == true) :
						$this->DatosBase = array_merge($this->DatosBase, $this->cnm_liquidacioncuotas_model->ObtenerDataParaCuotaHipotecario($cod_cartera));
						$this->DatosBase['GRADIENTE'] = str_replace(",", ".", $this->DatosBase['GRADIENTE']);
						$this->DatosBase['TASA_IPC'] = str_replace(",", ".", $this->DatosBase['TASA_IPC']);
						$this->DatosBase['FACTOR_TIPO'] = str_replace(",", ".", $this->DatosBase['FACTOR_TIPO']);
						//echo "<pre>";print_r($this->DatosBase);echo "</pre>";//exit;

						$cuotas = $this->calcularCuotasHipotecario($vista);
					else : $cuotas = $this->calcularCuotas($vista);
					endif;
				else : return false;
				endif;
				if(isset($cuotas) and !empty($cuotas)) :
					//echo "<pre>";print_r($cuotas);echo "</pre>";exit;
					/*if($this->DatosBase['COD_PLAZO'] != 1) : $this->db->select(implode(",", array_keys($cuotas[1])));
					else : $this->db->select(implode(",", array_keys($cuotas)));
					endif;*/
					if(is_array($cuotas) and $vista != true) :
						return true;
					elseif(is_array($cuotas) and $vista == true) :
						$response['mensaje']	= $cuotas;
						$response['error']		= true;
						return $this->output->set_content_type('appliation/json')->set_output(json_encode($response));
					elseif(!is_array($cuotas) and $vista != true) :
						return $cuotas;
					else :
						return 301;
					endif;
					//print_r($cuotas);print_r($datos);exit("</pre>");
				else :
					//"Error en calculo de cuotas";
					return 301;
				endif;
			else :
				return false;
			endif;
		else :
			return false;
		endif;
  }
	
	function CnmCalcularCuotasVistaJson() {
		$datos = $this->input->post('datos');
		$error = false;
		$DatosBase['VALOR_DEUDA'] = (isset($datos['VALOR_DEUDA']) and !empty($datos['VALOR_DEUDA']))?str_replace(".", "", $datos['VALOR_DEUDA']):"";
		$DatosBase['CALCULO_CORRIENTE'] = (isset($datos['CALCULO_CORRIENTE']) and !empty($datos['CALCULO_CORRIENTE']))?$datos['CALCULO_CORRIENTE']:"";
		$DatosBase['TIPO_T_V_CORRIENTE'] = (isset($datos['TIPO_T_V_CORRIENTE']) and !empty($datos['TIPO_T_V_CORRIENTE']))?$datos['TIPO_T_V_CORRIENTE']:"";
		$DatosBase['TIPO_T_F_CORRIENTE'] = (isset($datos['TIPO_T_F_CORRIENTE']) and !empty($datos['TIPO_T_F_CORRIENTE']))?$datos['TIPO_T_F_CORRIENTE']:"";
		$DatosBase['COD_PLAZO'] = (isset($datos['COD_PLAZO']) and !empty($datos['COD_PLAZO']))?$datos['COD_PLAZO']:"";
		$DatosBase['PLAZO_CUOTAS'] = (isset($datos['PLAZO_CUOTAS']) and !empty($datos['PLAZO_CUOTAS']))?$datos['PLAZO_CUOTAS']:"";
		$DatosBase['VALOR_CUOTA'] = (isset($datos['VALOR_CUOTA']) and !empty($datos['VALOR_CUOTA']))?str_replace(".", "", $datos['VALOR_CUOTA']):"";
		$DatosBase['VALOR_CUOTA_APROBADA'] = (isset($datos['VALOR_CUOTA_APROBADA']) and !empty($datos['VALOR_CUOTA_APROBADA']))?str_replace(".", "", $datos['VALOR_CUOTA_APROBADA']):"";
		$DatosBase['COD_FORMAPAGO'] = (isset($datos['COD_FORMAPAGO']) and !empty($datos['COD_FORMAPAGO']))?$datos['COD_FORMAPAGO']:"";
		$DatosBase['VALOR_T_CORRIENTE'] = (isset($datos['VALOR_T_CORRIENTE']) and !empty($datos['VALOR_T_CORRIENTE']))?$datos['VALOR_T_CORRIENTE']:"";
		$DatosBase['COD_CARTERA'] = (isset($datos['COD_CARTERA']) and !empty($datos['COD_CARTERA']))?$datos['COD_CARTERA']:"";
		//otros
		$fecha_activacion = $this->input->post('fecha_activacion');
		$fecha_activacion = (!empty($fecha_activacion))?date("Y-m-d", strtotime(str_replace("/", "-",$fecha_activacion))):date("Y-m-d");
		$medio_pago = $this->input->post('medio_pago');
		$medio_pago = (!empty($medio_pago))?$this->input->post('medio_pago'):1;
		$hipotecario = $this->input->post('hipotecario');
		$hipotecario = (!empty($hipotecario))?$this->input->post('hipotecario'):false;
		$reliquidacion = $this->input->post('reliquidacion');
		$reliquidacion = (!empty($reliquidacion))?$this->input->post('reliquidacion'):false;
		$refinanciacion = $this->input->post('refinanciacion');
		$refinanciacion = (!empty($refinanciacion))?$this->input->post('refinanciacion'):false;
		//hipotecario
		if($hipotecario == true) :
			$DatosBase['SALARIO'] = (isset($datos['SALARIO']) and !empty($datos['SALARIO']))?str_replace(".", "", $datos['SALARIO']):"";
			$DatosBase['TASA_INTERES_CESANTIAS'] = (isset($datos['TASA_INTERES_CESANTIAS']) and !empty($datos['TASA_INTERES_CESANTIAS']))?str_replace(",", ".", $datos['TASA_INTERES_CESANTIAS']):"0";
			$DatosBase['GRADIENTE'] = (isset($datos['GRADIENTE']) and !empty($datos['GRADIENTE']))?str_replace(",", ".", $datos['GRADIENTE']):"";
			$DatosBase['TASA_IPC'] = (isset($datos['TASA_IPC']) and !empty($datos['TASA_IPC']))?str_replace(",", ".", $datos['TASA_IPC']):"";
			$DatosBase['FACTOR_TIPO'] = (isset($datos['FACTOR_TIPO']) and !empty($datos['FACTOR_TIPO']))?str_replace(",", ".", $datos['FACTOR_TIPO']):"";
			$DatosBase['FECHA_RETIRO'] = (isset($DatosBase['FECHA_RETIRO']) and !empty($DatosBase['FECHA_RETIRO']))?date("Y-m-d", strtotime(str_replace("/", "-",$DatosBase['FECHA_RETIRO']))):date("Y-m-d");
			$DatosBase['FECHA_RETIRO_ANIO'] = substr($DatosBase['FECHA_RETIRO'], 0, 4);
			$DatosBase['FECHA_RETIRO_MES'] = substr($DatosBase['FECHA_RETIRO'], 6, 7);
			$DatosBase['FECHA_RETIRO_MES'] = (int)$DatosBase['FECHA_RETIRO_MES'];
		endif;
		//echo "hip: ".$hipotecario." - Rel: ".$reliquidacion." - Ref: ".$refinanciacion." - mp: ".$medio_pago." - fa: ".$fecha_activacion." - Error: ".$error;
		//echo "<pre>";print_r($DatosBase);echo "</pre>";//exit;
		return $this->output->set_content_type('appliation/json')->set_output(json_encode($this->CnmCalcularCuotasVista($DatosBase, $fecha_activacion, $medio_pago, $hipotecario, $reliquidacion, $refinanciacion)));
	}
	
	function CnmCalcularCuotasVista($datosCartera = array(), $fecha_activacion = NULL, $medio_pago = 1, $hipotecario = false, $reliquidacion = false, $refinanciacion = false) {
		if ($this->ion_auth->logged_in()) {
			if(sizeof($datosCartera) > 0 and is_array($datosCartera)) :
				$error = FALSE;
				$vista = true;
				$this->DatosBase['VALOR_DEUDA'] = (isset($datosCartera['VALOR_DEUDA']) and !empty($datosCartera['VALOR_DEUDA']))?str_replace(".", "", $datosCartera['VALOR_DEUDA']):"";
				$this->DatosBase['CALCULO_CORRIENTE'] = (isset($datosCartera['CALCULO_CORRIENTE']) and !empty($datosCartera['CALCULO_CORRIENTE']))?$datosCartera['CALCULO_CORRIENTE']:"";
				$this->DatosBase['TIPO_T_V_CORRIENTE'] = (isset($datosCartera['TIPO_T_V_CORRIENTE']) and !empty($datosCartera['TIPO_T_V_CORRIENTE']))?$datosCartera['TIPO_T_V_CORRIENTE']:"";
				$this->DatosBase['TIPO_T_F_CORRIENTE'] = (isset($datosCartera['TIPO_T_F_CORRIENTE']) and !empty($datosCartera['TIPO_T_F_CORRIENTE']))?$datosCartera['TIPO_T_F_CORRIENTE']:"";
				$this->DatosBase['COD_PLAZO'] = (isset($datosCartera['COD_PLAZO']) and !empty($datosCartera['COD_PLAZO']))?$datosCartera['COD_PLAZO']:"";
				$this->DatosBase['PLAZO_CUOTAS'] = (isset($datosCartera['PLAZO_CUOTAS']) and !empty($datosCartera['PLAZO_CUOTAS']))?$datosCartera['PLAZO_CUOTAS']:"";
				$this->DatosBase['VALOR_CUOTA'] = (isset($datosCartera['VALOR_CUOTA']) and !empty($datosCartera['VALOR_CUOTA']))?str_replace(".", "", $datosCartera['VALOR_CUOTA']):"";
				$this->DatosBase['COD_FORMAPAGO'] = (isset($datosCartera['COD_FORMAPAGO']) and !empty($datosCartera['COD_FORMAPAGO']))?$datosCartera['COD_FORMAPAGO']:"";
				$this->DatosBase['VALOR_T_CORRIENTE'] = (isset($datosCartera['VALOR_T_CORRIENTE']) and !empty($datosCartera['VALOR_T_CORRIENTE']))?$datosCartera['VALOR_T_CORRIENTE']:"";
				$this->DatosBase['COD_CARTERA'] = (isset($datosCartera['COD_CARTERA']) and !empty($datosCartera['COD_CARTERA']))?$datosCartera['COD_CARTERA']:NULL;
				$this->DatosBase['VALOR_CUOTA_APROBADA'] = (isset($datosCartera['VALOR_CUOTA_APROBADA']) and !empty($datosCartera['VALOR_CUOTA_APROBADA']))?str_replace(".", "", $datosCartera['VALOR_CUOTA_APROBADA']):"";
				//hipotecario
				if($hipotecario == true) :
					$this->DatosBase['SALARIO'] = (isset($datosCartera['SALARIO']) and !empty($datosCartera['SALARIO']))?str_replace(".", "", $datosCartera['SALARIO']):"";
					$this->DatosBase['TASA_INTERES_CESANTIAS'] = (isset($datosCartera['TASA_INTERES_CESANTIAS']) and !empty($datosCartera['TASA_INTERES_CESANTIAS']))?str_replace(",", ".", $datosCartera['TASA_INTERES_CESANTIAS']):"0";
					$this->DatosBase['GRADIENTE'] = (isset($datosCartera['GRADIENTE']) and !empty($datosCartera['GRADIENTE']))?str_replace(",", ".", $datosCartera['GRADIENTE']):"";
					$this->DatosBase['TASA_IPC'] = (isset($datosCartera['TASA_IPC']) and !empty($datosCartera['TASA_IPC']))?str_replace(",", ".", $datosCartera['TASA_IPC']):"";
					$this->DatosBase['FACTOR_TIPO'] = (isset($datosCartera['FACTOR_TIPO']) and !empty($datosCartera['FACTOR_TIPO']))?str_replace(",", ".", $datosCartera['FACTOR_TIPO']):"";
					$this->DatosBase['FECHA_RETIRO'] = (isset($datosCartera['FECHA_RETIRO']) and !empty($datosCartera['FECHA_RETIRO']))?date("Y-m-d", strtotime(str_replace("/", "-",$datosCartera['FECHA_RETIRO']))):date("Y-m-d");
					$this->DatosBase['FECHA_RETIRO_ANIO'] = substr($this->DatosBase['FECHA_RETIRO'], 0, 4);
					$this->DatosBase['FECHA_RETIRO_MES'] = (int) substr($this->DatosBase['FECHA_RETIRO'], 6, 7);
					$this->DatosBase['FECHA_RETIRO_MES'] = (int) $this->DatosBase['FECHA_RETIRO_MES'];
				endif;
				/*if(isset($datosCartera['RESPUESTA']) and $datosCartera['RESPUESTA'] == 1) :
					echo "<pre>";print_r($this->DatosBase);echo "</pre>";
					echo $error;
				endif;*/
				$error = array();
				foreach($this->DatosBase as $key => $tmp) :
					if(empty($tmp) || is_null($tmp)) :
						$error[] = "El campo ".str_replace("_", " ", $key)." es requerido.";
					endif;
				endforeach;
				$error = implode("\n", $error)."\n";
				$this->DatosBase['COD_CARTERA'] = (isset($datosCartera['COD_CARTERA']))?$datosCartera['COD_CARTERA']:"";
				if(sizeof($error) == 0|| sizeof($error) == 1) :

					if($this->DatosBase['VALOR_DEUDA'] > 0) :
						$this->DatosBase['MINCUOTA'] = 1;
						if($reliquidacion === "true" and $refinanciacion === "true"):
							$response['mensaje']	= "No se puede hacer una reliquidación y una refinanciación simultaneamente.";
							$response['error']		= true;
							return $response;
						endif;
						if($reliquidacion == "true" and $refinanciacion == "false" and !is_null($this->DatosBase['COD_CARTERA'])) :
							$this->DatosBase['RELIQUIDACION'] = true;
							$data = $this->cnm_liquidacioncuotas_model->ObtenerCuotaMinima($this->DatosBase['COD_CARTERA']);
							$this->DatosBase['MINCUOTA'] = $data['MIN_CUOTA'];
							$this->DatosBase['VALOR_DEUDA'] = $data['SALDO_FINAL_CAP'];
							$this->DatosBase['VALOR_CUOTA'] = $this->DatosBase['VALOR_CUOTA_APROBADA'];
							$this->DatosBase['MES_PROYECTADO'] = $data['MES_PROYECTADO'];
							$this->DatosBase['SALDO_INTERES_NO_PAGOS'] = $data['SALDO_INTERES_NO_PAGOS'];
						endif;
						if($refinanciacion != "false" and $reliquidacion == "false" and !is_null($this->DatosBase['COD_CARTERA'])) :
							$this->DatosBase['RELIQUIDACION'] = true;
							switch($refinanciacion) :
								case "true" :
									$data = $this->cnm_liquidacioncuotas_model->ObtenerCuotaMinima($this->DatosBase['COD_CARTERA']);
									$this->DatosBase['MINCUOTA'] = $data['MIN_CUOTA'];
									$this->DatosBase['VALOR_DEUDA'] = $data['SALDO_FINAL_CAP'];
									$this->DatosBase['VALOR_CUOTA'] = $this->DatosBase['VALOR_CUOTA_APROBADA'];
									$this->DatosBase['MES_PROYECTADO'] = $data['MES_PROYECTADO'];
									$this->DatosBase['SALDO_INTERES_NO_PAGOS'] = $data['SALDO_INTERES_NO_PAGOS'];
									//echo "<pre>";print_r($this->DatosBase);echo "</pre>";exit("salio");
									break;
								default :
									$pagado = $this->cnm_liquidacioncuotas_model->ObtenerPagos($this->DatosBase['COD_CARTERA']);
									$this->DatosBase['VALOR_DEUDA'] -= $pagado;
									break;
							endswitch;
						endif;
						//echo "<pre>";print_r($this->DatosBase);echo "</pre>";
						//die();
						if(is_null($this->DatosBase['MINCUOTA']) || empty($this->DatosBase['MINCUOTA'])) :
							$this->DatosBase['MINCUOTA'] = 1;
						endif;
						//echo "<pre>";print_r($this->DatosBase);echo "</pre>";
						//echo "<br>".$medio_pago;Los datos no son suficientes para calcular las cuotas.

						if($this->DatosBase != false) :
							$this->DatosBase['fecha_activacion'] = (is_null($fecha_activacion) || empty($fecha_activacion))?date("Y-m-d"):date("Y-m-d", strtotime(str_replace("/", "-",$fecha_activacion)));
							if($reliquidacion == "true" and $refinanciacion == "false" and !is_null($this->DatosBase['COD_CARTERA'])) :
							$this->DatosBase['fecha_activacion']=date("Y-m-d", strtotime($this->DatosBase['MES_PROYECTADO']."-01"));
							endif;
							if($refinanciacion != "false" and $reliquidacion == "false" and !is_null($this->DatosBase['COD_CARTERA'])) :
							$this->DatosBase['fecha_activacion']=date("Y-m-d", strtotime($this->DatosBase['MES_PROYECTADO']."-01"));
							endif;
							//echo "fecha activacion: ".$this->DatosBase['fecha_activacion']."<br>".
							//die();
							
							//echo $this->DatosBase['fecha_activacion'];exit;
							$this->DatosBase['medio_pago'] = $this->DatosBase['COD_FORMAPAGO'];
							//Calculo fijo
							if($this->DatosBase['CALCULO_CORRIENTE'] == 1) :
								unset($this->DatosBase['TIPO_T_V_CORRIENTE']);
								$this->DatosBase['VALOR_T_CORRIENTE'] = $this->cnm_liquidacioncuotas_model->DefinirTipoTasa(
								$this->DatosBase['TIPO_T_F_CORRIENTE'], $this->DatosBase['VALOR_T_CORRIENTE']);
							//Calculo variable
							elseif($this->DatosBase['CALCULO_CORRIENTE'] == 2 and $hipotecario != false) :
								unset($this->DatosBase['TIPO_T_F_CORRIENTE']);
								$this->DatosBase['VALOR_T_CORRIENTE'] = $this->cnm_liquidacioncuotas_model->TraerValorTasaVariable($cod_cartera);
							endif;
							//echo "<pre>";print_r($this->DatosBase);echo "</pre>";
							
							if($hipotecario == 'true') :
								//echo "<pre>";print_r($this->DatosBase);echo "</pre>";
								/*echo "hipotec".$hipotecario;
								die();
								echo "probando".$vista;
								die();*/
								$cuotas = $this->calcularCuotasHipotecario($vista);
								//echo "<pre>";print_r($cuotas);echo "</pre>";exit;
							else :
							$cuotas = $this->calcularCuotas($vista);
							endif;
						else :
							$response['mensaje']	= "Los datos no son suficientes para calcular la cuota";
							$response['error']		= true;
							return $response;
						endif;
						if(isset($datosCartera['RESPUESTA']) and $datosCartera['RESPUESTA'] == 1) :
							//echo "<pre>";print_r($cuotas);echo "</pre>";exit;
						endif;
						if(isset($cuotas) and !empty($cuotas)) :
							if(is_array($cuotas)) :
								$response['cuota_sugerida'] = $this->DatosBase['CUOTA_SUGERIDA'];
								if(isset($datosCartera['RESPUESTA']) and $datosCartera['RESPUESTA'] == 1) :
									$response['mensaje']	= $cuotas;
									return $response;
								else :
									$response['mensaje']	= true;
									$response['error']		= false;
									return $response;
								endif;
							else :
								$response['mensaje']	= $cuotas;
								$response['cuota_sugerida'] = $this->DatosBase['CUOTA_SUGERIDA'];
								$response['error']		= true;
								return $response;
							endif;
						else :
							//"Error en calculo de cuotas";
							$response['mensaje']	= "Error desconocido";
							$response['cuota_sugerida'] = 0;
							$response['error']		= true;
							return $response;
						endif;
					else :
						$response['mensaje']	= "La deuda debe ser mayor a cero.";
						$response['cuota_sugerida'] = 0;
						$response['error']		= true;
						return $response;
					endif;
				else :
					$response['mensaje']	= "Los datos no son suficientes para calcular las cuotas.\n\n".$error;
					$response['cuota_sugerida'] = 0;
					$response['error']		= true;
					return $response;
				endif;
			else :
				$response['mensaje']	= "Los datos no son suficientes para calcular las cuotas";
				$response['cuota_sugerida'] = 0;
				$response['error']		= true;
				return $response;
			endif;
		} else {
      redirect(base_url() . 'index.php/auth/login');
    }
	}
	
	private function calcularCuotas($vista = false) {
			$capital = $this->DatosBase['VALOR_DEUDA'];
			$tmpcuotas = $cuotas = array();//exit;
			//$this->DatosBase['VALOR_T_CORRIENTE'] = ((pow(1+(30/100),1/12)-1)*100)/100;
			if($this->DatosBase['COD_PLAZO'] == 3) :
				$this->DatosBase['PLAZO_CUOTAS'] = ceil(12 * $this->DatosBase['PLAZO_CUOTAS']);
			endif;
			if(isset($this->DatosBase['RELIQUIDACION']) and $this->DatosBase['RELIQUIDACION'] == true) :
				$cuota = $this->DatosBase['VALOR_CUOTA'];
			elseif(!empty($this->DatosBase['VALOR_T_CORRIENTE'])) :
				$p1 = $this->DatosBase['VALOR_T_CORRIENTE']*pow((1+$this->DatosBase['VALOR_T_CORRIENTE']),$this->DatosBase['PLAZO_CUOTAS']);
				$p2 = pow((1+$this->DatosBase['VALOR_T_CORRIENTE']),$this->DatosBase['PLAZO_CUOTAS'])-1;
				$cuota = $this->DatosBase['VALOR_DEUDA']*($p1/$p2);
			else :
				$cuota = ($this->DatosBase['VALOR_DEUDA'] / $this->DatosBase['PLAZO_CUOTAS']);
				$this->DatosBase['VALOR_T_CORRIENTE'] = 0;
			endif;
			if($vista == true) :
				$this->DatosBase['CUOTA_SUGERIDA'] = $cuota;
				//$cuota = $this->DatosBase['VALOR_CUOTA'];

			endif;
			//echo $cuota; //exit;
			ini_set("MEMORY_LIMIT", "500MB");
			ini_set("MAX_EXECUTION_TIME", "300");
			if($this->DatosBase['COD_PLAZO'] != 1) :
				for($x=$this->DatosBase['MINCUOTA']; $x<=$this->DatosBase['PLAZO_CUOTAS']; ++$x) :
					$tmpcuotas[$x]["capital"]				= $capital;
					$tmpcuotas[$x]["cuota"]					= $cuota;
					$tmpcuotas[$x]["intereses"]			= $capital*$this->DatosBase['VALOR_T_CORRIENTE'];
					$tmpcuotas[$x]["abono_capital"]	= $cuota - $tmpcuotas[$x]['intereses'];
					$tmpcuotas[$x]["capital_saldo"]	= ($capital - $tmpcuotas[$x]['abono_capital']);
					$capital -= $tmpcuotas[$x]['abono_capital'];
					if($tmpcuotas[$x]["capital_saldo"] == 0) :
						$this->DatosBase['PLAZO_CUOTAS'] = $x;
						break;
					endif;
				endfor;
				//echo "<pre>";print_r($tmpcuotas);exit;
				$capital = $cap = $int = 0;
				
				//echo "<br>Fecha de activación: ".$this->DatosBase['fecha_activacion'];
				//echo '<table cellpadding="2" cellspacing="3" border="1">';
				//echo "<tr><td>ID_DEUDA_E</td><td>AMORTIZACION</td><td>SALDO_AMORTIZACION</td><td>SALDO_CUOTA</td><td>VALOR_CUOTA</td><td>CAPITAL</td><td>SALDO_INTERES_C</td><td>MES_PROYECTADO</td><td>FECHA_LIM_PAGO</td><td>NO_CUOTA</td></tr>";
				$capital_aportado = 0;
				for($x=$this->DatosBase['MINCUOTA']; $x<=$this->DatosBase['PLAZO_CUOTAS']; ++$x) :
					$cuotas[$x]["ID_DEUDA_E"]					= (isset($this->DatosBase['COD_CARTERA_NOMISIONAL']))?$this->DatosBase['COD_CARTERA_NOMISIONAL']:"";
					$cuotas[$x]["NO_CUOTA"]						= $x;
					$cuotas[$x]["CONCEPTO"]						= (isset($this->DatosBase['COD_TIPOCARTERA']))?$this->DatosBase['COD_TIPOCARTERA']:"";
					$cuotas[$x]["SALDO_CUOTA"]				= round($tmpcuotas[$x]["cuota"], 0);
					$cuotas[$x]["VALOR_CUOTA"]				= round($tmpcuotas[$x]["cuota"], 0);
					$cuotas[$x]["VALOR_INTERES_C"]		= round($tmpcuotas[$x]["intereses"], 0);
					$cuotas[$x]["SALDO_INTERES_C"]		= round($tmpcuotas[$x]["intereses"], 0);
					$cuotas[$x]["CAPITAL"]						= $this->DatosBase['VALOR_DEUDA'] - $capital_aportado;
					$capital_aportado									+= ($cuotas[$x]["VALOR_CUOTA"] - $cuotas[$x]["SALDO_INTERES_C"]);
					$capital += $cuotas[$x]["CAPITAL"];
					if($x == $this->DatosBase['MINCUOTA'] and (isset($this->DatosBase['RELIQUIDACION']) and $this->DatosBase['RELIQUIDACION'] == true)) :
						$fecha = explode("-", $this->DatosBase['fecha_activacion']);
					elseif($x == $this->DatosBase['MINCUOTA'] and $x > 1) :
						$fecha = $this->DatosBase['MES_PROYECTADO'];
					elseif($x == $this->DatosBase['MINCUOTA'] and $x == 1) :
						$fecha = explode("-", $this->DatosBase['fecha_activacion']);
					else :
						$fecha = explode("-", $cuotas[$x-1]["MES_PROYECTADO"]);
					endif;
					$cuotas[$x]["MES_PROYECTADO"]			= date("Y-m", mktime(0, 0, 0, $fecha[1]+1, 5, $fecha[0]));
					$cuotas[$x]["AMORTIZACION"]				= $cuotas[$x]["VALOR_CUOTA"] - $cuotas[$x]["SALDO_INTERES_C"];
					$cuotas[$x]["SALDO_FINAL_CAP"]		= (abs(floor($tmpcuotas[$x]["capital_saldo"])) > 0 and $x == $this->DatosBase['PLAZO_CUOTAS'])?0:abs(floor($tmpcuotas[$x]["capital_saldo"]));
					$fecha														= explode("-", $cuotas[$x]["MES_PROYECTADO"]);
					$cuotas[$x]["FECHA_LIM_PAGO"]			= date("Y-m-d", mktime(0, 0, 0, $fecha[1]+1, 5, $fecha[0]));
					$cuotas[$x]["SALDO_AMORTIZACION"]	= $cuotas[$x]["AMORTIZACION"];
					$cuotas[$x]["MEDIO_INI_PAGO"]			= $this->DatosBase['medio_pago'];
					if($x == $this->DatosBase['PLAZO_CUOTAS']) :
							$saldo_pendiente									= $this->DatosBase['VALOR_DEUDA'] - $capital;
							$cuotas[$x]["SALDO_CUOTA"]				= 
							$cuotas[$x]["VALOR_CUOTA"]				= $cuotas[$x]["CAPITAL"] + $cuotas[$x]["SALDO_INTERES_C"];
							$cuotas[$x]["AMORTIZACION"] 			= $cuotas[$x]["SALDO_AMORTIZACION"]	= $cuotas[$x]["CAPITAL"];
						//echo $capital . " " . $saldo_pendiente;
					endif;
					$cap += $cuotas[$x]["AMORTIZACION"];
					$int += $cuotas[$x]["SALDO_INTERES_C"];
					
					//echo "<tr><td>".$cuotas[$x]["ID_DEUDA_E"]."</td><td>".$cuotas[$x]["AMORTIZACION"]."</td><td>".$cuotas[$x]["SALDO_AMORTIZACION"]."</td><td>".$cuotas[$x]["SALDO_CUOTA"]."</td><td>".$cuotas[$x]["VALOR_CUOTA"]."</td><td>".$cuotas[$x]["CAPITAL"]."</td><td>".$cuotas[$x]["SALDO_INTERES_C"]."</td><td>".$cuotas[$x]["MES_PROYECTADO"]."</td><td>".$cuotas[$x]["FECHA_LIM_PAGO"]."</td><td>".$cuotas[$x]["NO_CUOTA"]."</td></tr>";
					if($vista != true) :
						$this->db->set("FECHA_LIM_PAGO", "TO_DATE('" . $cuotas[$x]['FECHA_LIM_PAGO'] . "', 'YYYY-MM-DD')", FALSE);
						unset($cuotas[$x]['FECHA_LIM_PAGO']);
						$this->db->insert("CNM_CUOTAS", $cuotas[$x]);
					endif;
					//echo "<br>".$this->db->last_query();
				endfor;
				//echo "</table>"; //exit;
			else :
				$tmpcuotas["capital"]				= $capital;
				$tmpcuotas["cuota"]					= $capital;
				$tmpcuotas["intereses"]			= 0;
				$tmpcuotas["abono_capital"]	= $capital;
				$tmpcuotas["capital_saldo"]	= ($capital - $tmpcuotas['abono_capital']);
				
				$cuotas["ID_DEUDA_E"]					= $this->DatosBase['COD_CARTERA_NOMISIONAL'];
				$cuotas["NO_CUOTA"]						= 1;
				$cuotas["CONCEPTO"]						= $this->DatosBase['COD_TIPOCARTERA'];
				$cuotas["CAPITAL"]						= round($tmpcuotas["capital"], 0);
				$cuotas["SALDO_CUOTA"]				= round($tmpcuotas["cuota"], 0);
				$cuotas["VALOR_CUOTA"]				= round($tmpcuotas["cuota"], 0);
				$fecha												= explode("-", $this->DatosBase['fecha_activacion']);
				$cuotas["MES_PROYECTADO"]			= date("Y-m", mktime(0, 0, 0, $fecha[1], $fecha[2], $fecha[0]));
				$cuotas["VALOR_INTERES_C"]		= round($tmpcuotas["intereses"], 0);
				$cuotas["SALDO_INTERES_C"]		= round($tmpcuotas["intereses"], 0);
				$cuotas["AMORTIZACION"]				= round($tmpcuotas["abono_capital"], 0);
				$cuotas["SALDO_FINAL_CAP"]		= 0;
				$fecha												= explode("-", $this->DatosBase['fecha_activacion']);
				$cuotas["FECHA_LIM_PAGO"]			= date("Y-m-d", mktime(0, 0, 0,  $fecha[1], $fecha[2]+$this->DatosBase['PLAZO_CUOTAS'], $fecha[0]));
				$cuotas["SALDO_AMORTIZACION"]	= round($tmpcuotas["abono_capital"], 0);
				$cuotas["MEDIO_INI_PAGO"]			= $this->DatosBase['medio_pago'];
				if($vista != true) :
					$this->db->where("ID_DEUDA_E", $cuotas[$x]['ID_DEUDA_E']);
					$this->db->where("NO_CUOTA", $cuotas[$x]['NO_CUOTA']);
					$this->db->delete("CNM_CUOTAS");
					$this->db->set("FECHA_LIM_PAGO", "TO_DATE('" . $cuotas['FECHA_LIM_PAGO'] . "', 'YYYY-MM-DD')", FALSE);
					unset($cuotas['FECHA_LIM_PAGO']);
					$this->db->insert("CNM_CUOTAS", $cuotas);
				endif;
			endif;
			//print_r($cuotas);echo $capital."<br>".$cap."<br>".$int;exit;
			unset($tmpcuotas);
			if(is_array($cuotas)) return $cuotas;
			else return "Las cuotas no se pudieron proyectar correctamente";
	}
	
	private function calcularCuotasHipotecario($vista = false) {
			$capital = $this->DatosBase['VALOR_DEUDA'];
			$tmpcuotas = $cuotas = array();
			if($this->DatosBase['COD_PLAZO'] == 3) :
				$this->DatosBase['PLAZO_CUOTAS'] = ceil(12 * $this->DatosBase['PLAZO_CUOTAS']);
			endif;
			$int_no_pagos_mes = $saldo_int_no_pagos_mes = 0;
			if(isset($this->DatosBase['RELIQUIDACION']) and $this->DatosBase['RELIQUIDACION'] === true) :
				$cuota = $this->DatosBase['VALOR_CUOTA'];
				if(empty($this->DatosBase['SALDO_INTERES_NO_PAGOS']) || is_null($this->DatosBase['SALDO_INTERES_NO_PAGOS'])) :
					$this->DatosBase['SALDO_INTERES_NO_PAGOS'] = 0;
				endif;
				$int_no_pagos_mes = $this->DatosBase['SALDO_INTERES_NO_PAGOS'] / $this->DatosBase['PLAZO_CUOTAS'];
				if(is_float($int_no_pagos_mes)) :
					$int_no_pagos_mes = floor($int_no_pagos_mes);
					$saldo_int_no_pagos_mes = $this->DatosBase['SALDO_INTERES_NO_PAGOS'] - ($int_no_pagos_mes * ($this->DatosBase['PLAZO_CUOTAS'] - 1));
				else :
					$saldo_int_no_pagos_mes = 0;
				endif;
			elseif(!empty($this->DatosBase['VALOR_T_CORRIENTE'])) :
				$p1_1 = pow((1+$this->DatosBase['VALOR_T_CORRIENTE']),$this->DatosBase['PLAZO_CUOTAS']);
				$p1_2 = $this->DatosBase['VALOR_T_CORRIENTE']*$p1_1;
				$p1 = $this->DatosBase['VALOR_DEUDA'] * $p1_2;
				$p2_1 = (1+$this->DatosBase['VALOR_T_CORRIENTE']);
				$p2_2 = $this->DatosBase['PLAZO_CUOTAS']-1;
				$p2 = pow($p2_1, $p2_2);
				$cuota = round($p1/$p2, 0);
			else :
				$cuota = round($this->DatosBase['VALOR_DEUDA'] / $this->DatosBase['PLAZO_CUOTAS']);
				$this->DatosBase['VALOR_T_CORRIENTE'] = 0;
			endif;
			if($vista == true) :
				$this->DatosBase['CUOTA_SUGERIDA'] = $cuota;
				$cuota = $this->DatosBase['VALOR_CUOTA'];
			endif;
			//echo "<br>cuota: ".$cuota;
			//echo "<pre>";print_r($this->DatosBase);echo "</pre>";
			//exit;
			
			if($this->DatosBase['COD_PLAZO'] != 1) :
				$NoAnio = NULL; $y = $saldo_interes_no_pago = $capital_aportado = 0;
				ini_set("MEMORY_LIMIT", "500MB");
				ini_set("MAX_EXECUTION_TIME", "300");
				for($x=$this->DatosBase['MINCUOTA']; $x<=$this->DatosBase['PLAZO_CUOTAS']; ++$x) :
					if($x == $this->DatosBase['MINCUOTA'] and (isset($this->DatosBase['RELIQUIDACION']) and $this->DatosBase['RELIQUIDACION'] == true)) :
						$fecha = explode("-", $this->DatosBase['fecha_activacion']);
					elseif($x == $this->DatosBase['MINCUOTA'] and $x > 1) :
						$fecha = explode("-", $this->DatosBase['MES_PROYECTADO']);
					elseif($x == $this->DatosBase['MINCUOTA'] and $x == 1) :
						$fecha = explode("-", $this->DatosBase['fecha_activacion']);
					else :
						$fecha = explode("-", $tmpcuotas[$x-1]["MES_PROYECTADO"]);
					endif;
					$tmpcuotas[$x]["MES_PROYECTADO"]			= date("Y-m", mktime(0, 0, 0, $fecha[1]+1, 5, $fecha[0]));
					$anio																	= date("Y", mktime(0, 0, 0, $fecha[1]+1, 5, $fecha[0]));
					$mes																	= date("n", mktime(0, 0, 0, $fecha[1]+1, 5, $fecha[0]));
					if(is_null($NoAnio)) :
						$NoAnio=$anio;
					else :
						$NoAnio = date("Y", mktime(0, 0, 0, $fecha[1], 5, $fecha[0]));
						if($NoAnio<$anio) $y++;
					endif;
					if($mes == 2 and $anio <= $this->DatosBase['FECHA_RETIRO_ANIO'] and $mes <= $this->DatosBase['FECHA_RETIRO_MES']) :
						$cesantias1_1												= (1+$this->DatosBase['TASA_IPC']);
						$cesantias1_2												= $this->DatosBase['SALARIO'] * $this->DatosBase['FACTOR_TIPO'];
						$cesantias1													= $cesantias1_2 * pow(
																										$cesantias1_1
																										,($y-1)
																									);
						$cesantias2													= (1+$this->DatosBase['TASA_INTERES_CESANTIAS']);
						$cesantias													= round($cesantias1 * $cesantias2, 0);
					else :
						$cesantias													= $cesantias1_1 = $cesantias1_2 = $cesantias1 = $cesantias2 = 0;
					endif;
					$tmpcuotas[$x]['cesantias']						= $cesantias;
					if($x == 1 || $x == $this->DatosBase['MINCUOTA']) :
						$tmpcuotas[$x]["cuota"]							= $this->DatosBase['VALOR_CUOTA_APROBADA'];
					elseif($mes == 1) :
						$tmpcuotas[$x]["cuota"]							= round($tmpcuotas[$x-1]["cuota"] * (1+$this->DatosBase['GRADIENTE']), 0);
					else :
						$tmpcuotas[$x]["cuota"]							= $tmpcuotas[$x-1]["cuota"];
					endif;
					if($x == 1 || $x == $this->DatosBase['MINCUOTA']) :
						$tmpcuotas[$x]["capital"]						= $capital;
					elseif($tmpcuotas[$x-1]["capital"] < $tmpcuotas[$x-1]["capital_saldo"]) :
						$tmpcuotas[$x]["capital"]						= $tmpcuotas[$x-1]["capital"];
					else :
						$tmpcuotas[$x]["capital"]						= $tmpcuotas[$x-1]["capital_saldo"];
					endif;
					if($tmpcuotas[$x]["capital"] > $this->DatosBase['VALOR_DEUDA']) :
						$tmpcuotas[$x]["capital"]						= $this->DatosBase['VALOR_DEUDA'];
					endif;
					$tmpcuotas[$x]["intereses"]						= round($tmpcuotas[$x]["capital"]*$this->DatosBase['VALOR_T_CORRIENTE'], 0);
					$tmpcuotas[$x]["intereses_no_pagado"]	= $tmpcuotas[$x]["cuota"] - $tmpcuotas[$x]["intereses"];
					if($x == 1) :
						$saldo_interes_no_pago = $tmpcuotas[$x]["intereses_no_pagado"];
					elseif($saldo_interes_no_pago < 0) :
						$saldo_interes_no_pago += $tmpcuotas[$x]["intereses_no_pagado"];
					else :
						$saldo_interes_no_pago = $tmpcuotas[$x]["intereses_no_pagado"];
					endif;
					$tmpcuotas[$x]["saldo_intereses_no_pagado"]	= $saldo_interes_no_pago;
					$tmpcuotas[$x]["saldo_intereses_no_pagado"]	= ($tmpcuotas[$x]["saldo_intereses_no_pagado"] > 0) ? 0 : 
																												$tmpcuotas[$x]["saldo_intereses_no_pagado"]	= $saldo_interes_no_pago;
					$tmpcuotas[$x]["abono_capital"]				= $cesantias + $saldo_interes_no_pago;
					$tmpcuotas[$x]["abono_capital"]				= ($tmpcuotas[$x]["abono_capital"] < 0)?0:$tmpcuotas[$x]["abono_capital"];
					$tmpcuotas[$x]["capital_saldo"]				= $tmpcuotas[$x]["capital"] - $tmpcuotas[$x]["abono_capital"];
					if($cesantias > 0) $saldo_interes_no_pago = 0;
					if($tmpcuotas[$x]['capital_saldo'] < 0) :
						$tmpcuotas[$x]["cuota"] = $tmpcuotas[$x]["capital"] + $tmpcuotas[$x]["intereses"];
						$tmpcuotas[$x]['capital_saldo'] = 0;
						$tmpcuotas[$x]["intereses_no_pagado"] = $saldo_interes_no_pago = $tmpcuotas[$x]["intereses"];
						$tmpcuotas[$x]["abono_capital"]				= $tmpcuotas[$x]["capital"];
					endif;
					$capital_aportado += $tmpcuotas[$x]["abono_capital"];
					$tmpcuotas[$x]["capital_aportado"]		= $capital_aportado;
					$capital -= $tmpcuotas[$x]['abono_capital'];
					if($tmpcuotas[$x]['capital_saldo'] == 0) :
						$tc = $x;
						break;
					elseif($x >= $this->DatosBase['PLAZO_CUOTAS'] and $tmpcuotas[$x]['capital_saldo'] > 0) :
						if($vista == true) :
							return "La configuración de la cartera no permite liquidar las cuotas en el plazo proyectado.";
						else :
							//print_r($tmpcuotas);
							return 300;
						endif;
					endif;
				endfor;
				//echo "<pre>";print_r($tmpcuotas);echo "</pre>".$capital_aportado;//exit;
				$capital = $cap = $int = 0;
				
				/*echo "<br>".$this->DatosBase['fecha_activacion'];
				echo '<table cellpadding="2" cellspacing="0" border="2" style="border-collapse: collapse">';
				echo "<td>No</td><td>Mes proyectado</td><td>CAPITAL</td><td>CUOTA</td><td>INTERESES</td><td>SALDO INTERES NO PAGADO</td><td>CESANTIAS</td><td>ABONO CAPITAL</td></tr>";*/
				
				$capital_aportado = 0;
				$generadas = false;
				for($x=$this->DatosBase['MINCUOTA']; $x<=$this->DatosBase['PLAZO_CUOTAS']; ++$x) :
					$cuotas[$x]["ID_DEUDA_E"]							= (isset($this->DatosBase['COD_CARTERA_NOMISIONAL']))?$this->DatosBase['COD_CARTERA_NOMISIONAL']:"";
					$cuotas[$x]["NO_CUOTA"]								= $x;
					$cuotas[$x]["CONCEPTO"]								= (isset($this->DatosBase['COD_TIPOCARTERA']))?$this->DatosBase['COD_TIPOCARTERA']:"";
					$cuotas[$x]["SALDO_CUOTA"]						= round($tmpcuotas[$x]["cuota"], 0);
					$cuotas[$x]["VALOR_CUOTA"]						= round($tmpcuotas[$x]["cuota"], 0);
					$cuotas[$x]["VALOR_INTERES_C"]				= round($tmpcuotas[$x]["intereses"], 0);
					$cuotas[$x]["SALDO_INTERES_C"]				= ($cuotas[$x]["SALDO_CUOTA"] < round($tmpcuotas[$x]["intereses"], 0)) ?
																									$cuotas[$x]["SALDO_CUOTA"]:round($tmpcuotas[$x]["intereses"], 0);
					$cuotas[$x]["VALOR_INTERES_NO_PAGOS"]	= ($tmpcuotas[$x]["saldo_intereses_no_pagado"] <= 0) ?
																									abs(round($tmpcuotas[$x]["saldo_intereses_no_pagado"], 0))+$int_no_pagos_mes:0;
					if($x == $this->DatosBase['PLAZO_CUOTAS'] || $tmpcuotas[$x]['capital_saldo'] == 0) :
						$int_no_pagos_mes = $saldo_int_no_pagos_mes;
					endif;
					//echo "\n".$tmpcuotas[$x]["saldo_intereses_no_pagado"];
					$cuotas[$x]["SALDO_INTERES_NO_PAGOS"]	= ($tmpcuotas[$x]["saldo_intereses_no_pagado"] <= 0) ?
																									abs(round($tmpcuotas[$x]["saldo_intereses_no_pagado"], 0))+$int_no_pagos_mes:0;
					$cuotas[$x]["CAPITAL"]								= $tmpcuotas[$x]["capital"];
					$cuotas[$x]["CESANTIAS"]							= $tmpcuotas[$x]["cesantias"];
					$capital_aportado											+= ($tmpcuotas[$x]["abono_capital"] < 0)?0:$tmpcuotas[$x]['abono_capital'];
					$capital															+= $cuotas[$x]["CAPITAL"];
					$cuotas[$x]["MES_PROYECTADO"]					= $tmpcuotas[$x]["MES_PROYECTADO"];
					$cuotas[$x]["AMORTIZACION"]						= ($tmpcuotas[$x]["abono_capital"] < 0)?0:$tmpcuotas[$x]['abono_capital'];
					$cuotas[$x]["SALDO_FINAL_CAP"]				= $tmpcuotas[$x]["capital_saldo"];
					$fecha																= explode("-", $cuotas[$x]["MES_PROYECTADO"]);
					$cuotas[$x]["FECHA_LIM_PAGO"]					= date("Y-m-d", mktime(0, 0, 0, $fecha[1]+1, 5, $fecha[0]));
					$cuotas[$x]["SALDO_AMORTIZACION"]			= $cuotas[$x]["AMORTIZACION"];
					$cuotas[$x]["MEDIO_INI_PAGO"]					= $this->DatosBase['medio_pago'];
					$cap += ($cuotas[$x]["AMORTIZACION"] + $cuotas[$x]["CESANTIAS"]);
					$int += $cuotas[$x]["SALDO_INTERES_C"];
					
					/*echo
						"<tr>" .
						"<td>".$x."</td>" .
						"<td>".$cuotas[$x]["MES_PROYECTADO"]."</td>" .
						"<td>".number_format($cuotas[$x]["CAPITAL"], 0, ",", ".")."</td>" .
						"<td>".number_format($cuotas[$x]["VALOR_CUOTA"], 0, ",", ".")."</td>" .
						"<td>".number_format($cuotas[$x]["SALDO_INTERES_C"], 0, ",", ".")."</td>" .
						"<td>".number_format($cuotas[$x]["SALDO_INTERES_NO_PAGOS"], 0, ",", ".")."</td>" .
						"<td>".number_format($cuotas[$x]["CESANTIAS"], 0, ",", ".")."</td>" .
						"<td>".number_format($cuotas[$x]["SALDO_AMORTIZACION"], 0, ",", ".")."</td>" .
						"</tr>";$vista = true;*/
					if($vista != true) :
						$this->db->set("FECHA_LIM_PAGO", "TO_DATE('" . $cuotas[$x]['FECHA_LIM_PAGO'] . "', 'YYYY-MM-DD')", FALSE);
						unset($cuotas[$x]['FECHA_LIM_PAGO']);
						$this->db->insert("CNM_CUOTAS", $cuotas[$x]);
						$generadas = true;
					endif;
					if($tmpcuotas[$x]['capital_saldo'] == 0) :
						break;
					endif;
				endfor;
				//echo "</table>";exit;
				if($generadas == true) :
					
					$dbuser = $this->db->username;
					$dbpassword = $this->db->password;
					$dbConnString = $this->db->hostname;
				
					$dbuser = $this->db->username;
					$dbpassword = $this->db->password;
					$dbConnString = $this->db->hostname;
		

					$v_oDataConn = oci_connect($dbuser, $dbpassword, $dbConnString);
					if (!$v_oDataConn) {
							$v_oErroCntr = oci_error();
							trigger_error(htmlentities($v_oErroCntr['message'], ENT_QUOTES), E_USER_ERROR);
					}
				
					//Seguro de incendio
					$query = "BEGIN PKG_CARTERA_NO_MISIONAL.Crea_Cobro_Incendio(".$this->DatosBase['COD_CARTERA_NOMISIONAL'].", null, null, :pio_Mensaje); END;";
					
					$pio_Mensaje = "";
					$v_oStnd_Out = oci_parse($v_oDataConn, $query) or die('Can not parse query');
					//echo "id_deuda ".$this->DatosBase['COD_CARTERA_NOMISIONAL']." porcentaje ".$porcentaje." avaluo ".$avaluo." query ".$query;
					//die();
					oci_bind_by_name($v_oStnd_Out, ":pio_Mensaje", $pio_Mensaje, 32000) or die('Can not bind variable');
					oci_execute($v_oStnd_Out);
					if(empty($pio_Mensaje)) :
						//Seguro de vida
						$query = "BEGIN PKG_CARTERA_NO_MISIONAL.Crea_Cobro_Vida(".$this->DatosBase['COD_CARTERA_NOMISIONAL'].", null, :pio_MensajeVida); END;";
						$pio_MensajeVida = "";
						$v_oStnd_Out = oci_parse($v_oDataConn, $query) or die('Can not parse query');
						oci_bind_by_name($v_oStnd_Out, ":pio_MensajeVida", $pio_MensajeVida, 32000) or die('Can not bind variable');
						oci_execute($v_oStnd_Out);
						oci_close($v_oDataConn);
						if(!empty($pio_MensajeVida)) :
							$this->cnm_liquidacioncuotas_model->BorrarCuotas($this->DatosBase['COD_CARTERA_NOMISIONAL'], $this->DatosBase['MINCUOTA']);
							return $pio_MensajeVida;
						endif;
					else :
						oci_close($v_oDataConn);
						$this->cnm_liquidacioncuotas_model->BorrarCuotas($this->DatosBase['COD_CARTERA_NOMISIONAL'], $this->DatosBase['MINCUOTA']);
						return $pio_Mensaje;
					endif;
				endif;
			else :
				return "El plazo no es el adecuado para calcular este tipo de cartera";
			endif;
			//print_r($cuotas);
			//echo $capital_aportado."<br>".$cap."<br>".$int;
			//exit;
			unset($tmpcuotas);
			return $cuotas;
	}
}