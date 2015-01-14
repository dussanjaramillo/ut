<?php
/**
* Archivo para la administracion de los procesos de ajustes de valores de la liquidacion, bien via Cron Job o vía implementación de método
*
* @packageCartera
* @subpackage Controllers
* @author jdussan
* @location./application/controllers/liquidaciones_ajustes.php
* @last-modified 04/12/2014
*/

class Liquidaciones_ajustes extends MY_Controller
{
	function __construct()
	{
		parent :: __construct();
		//$this -> load -> library(array('form_validation', 'tcpdf/tcpdf.php'));
		//$this -> load -> helper(array('form', 'url', 'codegen_helper', 'date', 'template', 'liquidaciones', 'template_helper', 'traza_fecha_helper'));
        $this -> load -> helper(array('liquidaciones'));
        //$this->load->file(APPPATH . "controllers/sgva_client.php", true);
		$this -> load -> model('liquidaciones_ajustes_model','',TRUE);
		//$this -> data['javascripts'] = array('js/tinymce/tinymce.jquery.min.js', 'js/jquery.dataTables.min.js');
		//$this -> data['style_sheets']= array('css/jquery.dataTables_themeroller.css' => 'screen');
		//$this -> data['user'] = $this -> ion_auth -> user() -> row();
		//define("COD_USUARIO", $this -> data['user'] -> IDUSUARIO);
		//define("REGIONAL_USUARIO", $this -> data['user'] -> COD_REGIONAL);
	}

	function index()
	{
		$this -> manage();
	}

    function manage()
    {
        $liquidaciones = $this -> liquidaciones_ajustes_model -> consultarLiquidaciones();
        foreach($liquidaciones as $registro):
            $this -> data['liquidacion'] = recalcularLiquidacion_acuerdoPago($registro['liquidacion']);
        endforeach;
        //print_r($liquidaciones); die();
        //$this -> data['liquidacion'] = recalcularLiquidacion_acuerdoPago($contratoAprendizaje);
        $this->output->enable_profiler(TRUE);
        $this -> template -> set('title', 'Liquidación Acuerdo de Pago');
        $this -> data['titulo'] = 'Liquidación Acuerdo de Pago';
        $this -> template -> load($this -> template_file, 'liquidaciones/liquidaciones_base',$this ->  data);

    }


	/*--- VERSIÓN PREVIA ---*/

	//GESTION POR CONCEPTO SOLO APLICABLE A APORTES Y FIC
	function administrar()
	/**
   	* Función que muestra el formulario para administrar las liquidaciones  para  Aportes y FIC.
   	* @return array $data;
	*/
	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

				if ($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_menu('liquidaciones/administrar')):

					$codigoFiscalizacion = $this -> input -> post('codigoFiscalizacion');
					$codigoFiscalizacion = (string)$codigoFiscalizacion;
					$usuario = $this -> ion_auth -> user() -> row();
					$idusuario = $usuario -> IDUSUARIO;
					$idusuario = (integer)$idusuario;

					if($codigoFiscalizacion == ''):
						throw new Exception('<strong>El código de finalización: <i>'. $codigoFiscalizacion .'</i> no es un dato valido</strong>.');
					endif;

					$fiscalizacion = $this -> liquidaciones_model ->  consultarCabeceraLiquidacion($codigoFiscalizacion,$idusuario);

					if($fiscalizacion == FALSE):
						throw new Exception('<strong>El código de gestión: <i>'. $codigoFiscalizacion .'</i> no cuenta con datos asociados o el expediente no esta asociado a su cuenta</strong>.');
					else:
						switch($fiscalizacion['COD_CONCEPTO']):
							case 1:
								$this -> crearFormularioAportes($fiscalizacion);
								break;
							case 2:
								$this -> getFormFic($fiscalizacion);
								break;
							default:
								throw new Exception('<strong>El código de gestión: <i>'. $codigoGestion .'</i> tiene un concepto no asociado a un método de liquidación</strong>.');
								break;
						endswitch;
					endif;

				else:
					$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					redirect(site_url().'/inicio');
				endif;

			else:
				redirect(site_url().'/auth/login');
			endif;
		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Administrar Liquidaciones';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="aleradt">&times;</button>Ha ocurrido un problema de ejecución: ' . $e -> getMessage() .'</div>';
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
	}

	//MOSTRAR FORMULARIO PARA CONSULTAR FISCALIZACIONES Y PROCEDER A LIQUIDAR
	function liquidar()
	/**
   	* Función que muestra el formulario para consultar las fiscalizaciones activas y generar una liquidación de dicha fiscalización. Hasta la fecha de pruebas no ha requerido recibir variables. Solicita los campos nit o razon social o represntante legal y permite filtrar si se tiene el número de expediente y concepto a consultar
   	* @return array $data;
	*/
	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

				if ($this -> ion_auth -> is_admin() ||  $this -> ion_auth -> in_menu('liquidaciones/liquidar')):

					$this -> data['conceptos'] = $this -> liquidaciones_model -> buscarConceptos();
					$this -> template -> set('title', 'Consultar Fiscalizaciones');
					$this -> template -> load($this -> template_file, 'liquidaciones/consultar',$this ->  data);

				else:

					$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					redirect(site_url().'/inicio');

				endif;

			else:

				redirect(site_url().'/auth/login');

			endif;
		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Crear LIquidaciones';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e -> getMessage() .'</div>';
			$this -> template -> set('title', 'Errores en liquidación');
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
	}

	//MOSTRAR TRAZA DE EXPEDIENTE PARA LIQUIDAR
	function mostrarFiscalizaciones()
	/**
   	* Función que muestra el resultado de la consulta de fiscalizaciones asociadas a los datos registrados en liquidar()
	* Esta función invoca la vista consultar_respuesta que llama a administrar para los casos de aportes, fic y contratos de aprendizaje. Las multas de ministerio no pueden consultarse por este medio, pues no tienen expediente creado desde fiscalización y no se verifican por la traza. Los contratos de  aprendizaje (SGVA) deben consultarse tras este método
   	* @return array $data;
	*/
	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

				if ($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_menu('liquidaciones/mostrarFiscalizaciones')):

					$this -> form_validation -> set_error_delimiters('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>');
					$this -> form_validation -> set_rules('nit', 'Nit Empresa', 'trim|min_length[4]|max_length[20]|xss_clean');
					$this -> form_validation -> set_rules('razonSocial', 'Razón Social', 'trim|min_length[4]|max_length[255]|xss_clean');
					$this -> form_validation -> set_rules('representante', 'Representante Legal', 'trim|min_length[4]|max_length[255]|xss_clean');
					$this -> form_validation -> set_rules('expediente', 'Número de Expediente', 'trim|min_length[22]|max_length[22]|xss_clean');
					$this -> form_validation -> set_rules('concepto', 'Concepto', 'trim|required|xss_clean');
					$nit = $this -> input -> post('nit');
					$razonSocial = $this -> input -> post('razonSocial');
					$representante = $this -> input -> post('representante');
					$expediente = $this -> input -> post('expediente');
					$concepto = $this -> input -> post('concepto');
					$usuario = $this -> ion_auth -> user() -> row();
					$idusuario = $usuario -> IDUSUARIO;
					$idusuario = (string)$idusuario;

					if ($this -> form_validation -> run() == FALSE):

						$this -> data['conceptos'] = $this -> liquidaciones_model -> buscarConceptos();
						$this -> template -> set('title', 'Consultar Fiscalizaciones');
						$this -> template -> load($this -> template_file, 'liquidaciones/consultar');

					else:

						$fiscalizaciones = $this -> liquidaciones_model -> consultarFiscalizaciones($nit, $razonSocial, $representante, $expediente, $concepto, $idusuario);

						if($fiscalizaciones == FALSE):

							throw new Exception('<strong>Los criterios de busqueda: <i>' . $nit . ' ' . $razonSocial . ' ' . $representante . '</i> no cuentan con fiscalizaciones o no se le han asignado a ' . $usuario -> NOMBRES . ' ' . $usuario -> APELLIDOS . '</strong>.');

						else:

							$this -> data['fiscalizacion'] = $fiscalizaciones;

						endif;

						$this -> template -> set('title', 'Fiscalizaciones Asociadas');
						$this -> template -> load($this -> template_file, 'liquidaciones/consultar_respuesta',$this ->  data);

					endif;

				else:

					$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					redirect(site_url().'/inicio');

				endif;

			else:

				redirect(site_url().'/auth/login');

			endif;
		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Consultar Fiscalizaciones';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e -> getMessage() .'</div>';
			$this -> template -> set('title', 'Errores en liquidación');
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
	}

	//MOSTRAR TRAZA DE EXPEDIENTE PARA LIQUIDAR
	function consultarLiquidacion()
	/**
   	* Función que muestra el resultado de la consulta de expediente relaizada en liquidar(). Solo aplica para Aportes y FIC
	* Las multas de ministerio no pueden consultarse por este medio, pues no tienen expediente generado en fiscalizacion
   	* @return array $data;
	*/
	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

				if ($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_menu('liquidaciones/consultarLiquidacion')):

						$expediente = $this -> security -> xss_clean((string)$this -> uri -> segment(3));
						$usuario = $this -> ion_auth -> user() -> row();
						$idusuario = $usuario -> IDUSUARIO;

						if($expediente == ''):

							throw new Exception('<strong>El código de fiscalización: <i>'. $expediente .'</i> no es un dato valido</strong>.');

						else:

							$resultado = $this -> liquidaciones_model ->  consultarCodigoFiscalizacion($expediente,$idusuario);

							if($resultado == FALSE):

								throw new Exception('<strong>El código de fiscalización: <i>'. $expediente .'</i> no corresponde a una fiscalización valida o no se le ha asignado</strong>.');

							else:

								$this -> data['fiscalizacion'] = $resultado;

							endif;

						endif;

						$this -> template -> set('title', 'Generar Liquidaciones');
						$this -> template -> load($this -> template_file, 'liquidaciones/consultar_liquidacion',$this ->  data);

				else:

					$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					redirect(site_url().'/inicio');

				endif;

			else:

				redirect(site_url().'/auth/login');

			endif;
		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Consultar Liquidaciones';
			$this -> template -> set('title', 'Errores en liquidación');
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
	}

	//BASE PARA CONSULTA
	function getManage()
	{
		$multaMinisterio = '0000000000869';
		$contratoAprendizaje = '0000000000589';
		$fic = '0000000000867';
		$aportes = '0000000000868';
		$this -> data['liquidacion'] = recalcularLiquidacion_acuerdoPago($contratoAprendizaje);
		$this->output->enable_profiler(TRUE);
		$this -> template -> set('title', 'Liquidación Acuerdo de Pago');
		$this -> data['titulo'] = 'Liquidación Acuerdo de Pago';
		$this -> template -> load($this -> template_file, 'liquidaciones/liquidaciones_base',$this ->  data);

	}

	//MOSTRAR FORMULARIO PARA MULTAS DEL MINISTERIO
	function getFormMultasMinisterio()
	/**
   	* Función que muestra el formulario para la generación de liquidaciones por el concepto de multas de ministerio.
	* No se vincula desde el método administrar(), se invoca directamente desde el método de ejecutoria de multas de ministerio
   	* @return array $data;
	*/
	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

				if ($this -> ion_auth -> is_admin() || $this -> ion_auth ->  in_menu('liquidaciones/getFormMultasMinisterio')):

					$this -> data['message']=$this -> session -> flashdata('message');
					$codigoMulta = (string)$this -> input -> post('cod_multaministerio');
					$codigoFiscalizacion = (string)$this -> input -> post('cod_fis');
					$existe = $this -> liquidaciones_model -> consultarLiquidacionMulta($codigoMulta);

					if($existe != null):

						$this -> data['previa'] = 1;

					endif;

					if ($codigoMulta != '' && $codigoFiscalizacion != ''):

						$datestring = "%d/%m/%Y";
						$fecha_actual = mdate($datestring);
						$this -> data['fecha'] = $fecha_actual;
						$this -> data['codigoMulta'] = $codigoMulta;
						$this -> data['codigoFiscalizacion'] = $codigoFiscalizacion;
						$this -> data['empresa'] = $this -> liquidaciones_model -> getCabecerasMultasMinisterio($codigoMulta);

						if ($this -> data['empresa'] !== FALSE):

							$this -> template -> set('title', 'Multas Ministerio Trabajo');
							$this -> template -> load($this -> template_file, 'liquidaciones/interesmultasmin_form',$this -> data);

						else:

							throw new Exception('<strong>La multa consultada no existe. Código Multa: '.$codigoMulta.'</strong>.');

						endif;

					else:

						throw new Exception('<strong>Datos insuficientes para mostrar una liquidación.</strong>');

					endif;

				else:

					$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					redirect(site_url().'/inicio');

				endif;

			else:

				redirect(site_url().'/auth/login');

			endif;
		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Intereses Resolución Multas Ministerio del Trabajo';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e -> getMessage() .'</div>';
			$this -> template -> set('title', 'Errores en liquidación');
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
	}

	//CALCULAR INTERESES MULTAS MINISTERIO
	function calcularInteresMultasMinisterio()
	/**
   	* Función que calcula los interes generados por multas de ministerio a partir de los datos provistos en la ejecutoria de la multa y la fecha de calculo de la liquidación por este concepto.
	* Se vincula desde el método getFormMultasMinisterio()
   	* @return array $data;
	*/
	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

				if ($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_menu('liquidaciones/calcularInteresMultasMinisterio')):

					$maestro = array();
					$detalle = array();
					$maestro ['codigo_multa'] = $this -> input -> post('codigoMulta');
					$maestro ['valor_multa'] = $this -> input -> post('valorMulta');
					$maestro ['fecha_elaboracion'] = $this -> input -> post('fechaElaboracion');
					$maestro ['fecha_liquidacion'] = $this -> input -> post('fechaLiquidacion');
					$maestro ['fecha_ejecutoria'] = $this -> input -> post('fechaEjecutoria');
					$nit = $this -> input -> post('nit');
					$codigoFiscalizacion = $this -> input -> post('codigoFiscalizacion'); //capturado desde la vista
					//CALCULAR LIQUIDACION MULTAS MINISTERIO
					$cod_respuesta_calculada = '792';
					$cod_tipoGestion_calculada = 271;
					$comentarios_calculada = 'Liquidación de Multa de Ministerio calculada';
					//RECALCULAR LIQUIDACION MULTAS MINISTERIO
					$cod_respuesta_recalculada = '790';
					$cod_tipoGestion_recalculada = 271;
					$comentarios_recalculada = 'Liquidación de Multa de Ministerio re-calculada';
					//LIQUIDACIÓN EN FIRME MULTAS MINISTERIO
					$cod_respuesta = '791';
					$cod_tipoGestion = 271;
					$comentarios_enFIrme = 'Liquidación de Multa de Ministerio en Firme';
					$existe = $this -> liquidaciones_model -> consultarLiquidacionMulta($maestro ['codigo_multa']);
					$liquidacion_previa = 0;

					if($existe != null):

						$liquidacion_previa = 1;

					endif;

					$tasa_interes_datos = $this -> liquidaciones_model -> getTasaParametrizada();
					$tasa_interes = (integer)$tasa_interes_datos['VALOR_TASA'];
					$tasa_interes_diaria = convertirTasaSimple_diaria($tasa_interes);
					$datos_fecha_liquidacion = explode("/",$maestro ['fecha_liquidacion']);
					$dia_fecha_liquidacion = (int)$datos_fecha_liquidacion[0];
					$mes_fecha_liquidacion = (int)$datos_fecha_liquidacion[1];
					$anno_fecha_liquidacion = (int)$datos_fecha_liquidacion[2];
					$datos_fecha_ejecutoria = explode("/",$maestro ['fecha_ejecutoria']);
					$dia_fecha_ejecutoria = (int)$datos_fecha_ejecutoria[0];
					$mes_fecha_ejecutoria = (int)$datos_fecha_ejecutoria[1];
					$anno_fecha_ejecutoria = (int)$datos_fecha_ejecutoria[2];
					$diferencia_annos = $anno_fecha_liquidacion - $anno_fecha_ejecutoria;
					$valor_multa = $maestro ['valor_multa'];
					$total_dias_mora = 0;
					$total_capital = 0;
					$total_interes = 0;
					$total_valor = 0;
					$info ='<table id="intereses" class="table table-striped table-bordered"><thead><tr><th>Mes</th><th>Año</th><th>Días en Mora</th><th>Capital</th><th>Intereses</th><th>Total</th></tr></thead><tbody id="resumen"><!--Cargado por respuesta de/liquidaciones/calcularInteresMultasMinisterio--> ';

					if($diferencia_annos < 0):

						throw new Exception('<strong>La fecha de liquidación: <i>'. $datos_fecha_liquidacion .'</i> es anterior a la fecha ejecutoria: <i>'. $datos_fecha_ejecutoria .'</i></strong>.');

					endif;

					if($diferencia_annos == 0):

						$diferencia_meses = $mes_fecha_liquidacion - $mes_fecha_ejecutoria;

						if($diferencia_meses < 0): //control error de meses

							throw new Exception('<strong>La fecha de liquidación: <i>'. $datos_fecha_liquidacion .'</i> es anterior a la fecha ejecutoria: <i>'. $datos_fecha_ejecutoria .'</i></strong>.');

						endif;

						if($diferencia_meses == 0):

							$diferencia_dias = $dia_fecha_liquidacion - $dia_fecha_ejecutoria;

							if($diferencia_dias < 0): //control error de dias

								throw new Exception('<strong>La fecha de liquidación: <i>'. $datos_fecha_liquidacion .'</i> es anterior a la fecha ejecutoria: <i>'. $datos_fecha_ejecutoria .'</i></strong>.');

							endif;

							if($diferencia_dias == 0): //control error de dias

								throw new Exception('<strong>La fecha de liquidación: <i>'. $datos_fecha_liquidacion .'</i> es igual a la fecha ejecutoria: <i>'. $datos_fecha_ejecutoria .',</i> por lo cual no se pueden calcular intereses</strong>.');

							endif;

							if($diferencia_dias > 0):

								$datos = calcularDias($mes_fecha_ejecutoria, $anno_fecha_ejecutoria, $diferencia_dias, $valor_multa, $tasa_interes_diaria);
								$info.= "<tr><td>". $datos['mes'] . "</td><td>" . $datos['anno'] . "</td><td>" . $datos['diaMora'] . "</td><td>" . $datos['capital'] . "</td><td>" . $datos['intereses'] . "</td><td>" . $datos['total'] ."</td></tr>";
								$total_dias_mora += $datos['diaMora'];
								$total_capital = $datos['c_valor'];
								$total_interes += $datos['c_intereses_cuota'];
								$total_valor += $datos['c_total_cuota'];
								$linea = array('mes'=>$datos['mes'],'anno' =>$datos['anno'], 'valorCapital' =>$datos['c_valor'], 'valorInteres' =>  $datos['c_intereses_cuota'], 'valorTotal' =>$datos['c_total_cuota'] );
								array_push($detalle, $linea);

							endif;

						endif;

						if($diferencia_meses > 0):

							$mes = (int)$mes_fecha_liquidacion;

							for($i = $diferencia_meses; $i >= 0; $i--):

								if($i == $diferencia_meses):
									$mes_fecha_liquidacion = $mes_fecha_liquidacion+0;
									$diferencia_dias = $dia_fecha_liquidacion;
									$datos = calcularDias($mes_fecha_liquidacion, $anno_fecha_liquidacion, $diferencia_dias, $valor_multa, $tasa_interes_diaria);
									$info.= "<tr><td>". $datos['mes'] . "</td><td>" . $datos['anno'] . "</td><td>" . $datos['diaMora'] . "</td><td>" . $datos['capital'] . "</td><td>" . $datos['intereses'] . "</td><td>" . $datos['total'] ."</td></tr>";
									$total_dias_mora += $datos['diaMora'];
									$total_capital += $datos['c_valor'];
									$total_interes += $datos['c_intereses_cuota'];
									$total_valor += $datos['c_total_cuota'];
									$linea = array('mes'=>$datos['mes'],'anno' =>$datos['anno'], 'valorCapital' =>$datos['c_valor'], 'valorInteres' =>  $datos['c_intereses_cuota'], 'valorTotal' =>$datos['c_total_cuota'] );
									array_push($detalle, $linea);

								elseif($i == 0):

									$mes_fecha_ejecutoria = $mes_fecha_ejecutoria+0;
									$diferencia_dias = cuentaDias($mes_fecha_ejecutoria,$anno_fecha_ejecutoria) - $dia_fecha_ejecutoria;
									$datos = calcularDias($mes_fecha_ejecutoria, $anno_fecha_ejecutoria, $diferencia_dias, $valor_multa, $tasa_interes_diaria);
									$info.= "<tr><td>". $datos['mes'] . "</td><td>" . $datos['anno'] . "</td><td>" . $datos['diaMora'] . "</td><td>" . $datos['capital'] . "</td><td>" . $datos['intereses'] . "</td><td>" . $datos['total'] ."</td></tr>";
									$total_dias_mora += $datos['diaMora'];
									$total_capital += $datos['c_valor'];
									$total_interes += $datos['c_intereses_cuota'];
									$total_valor += $datos['c_total_cuota'];
									$linea = array('mes'=>$datos['mes'],'anno' =>$datos['anno'], 'valorCapital' =>$datos['c_valor'], 'valorInteres' =>  $datos['c_intereses_cuota'], 'valorTotal' =>$datos['c_total_cuota'] );
									array_push($detalle, $linea);

								else:

									$mes --;
									$datos = calcularMeses($mes, $anno_fecha_liquidacion, $valor_multa, $tasa_interes_diaria);
									$info.= "<tr><td>". $datos['mes'] . "</td><td>" . $datos['anno'] . "</td><td>" . $datos['diaMora'] . "</td><td>" . $datos['capital'] . "</td><td>" . $datos['intereses'] . "</td><td>" . $datos['total'] ."</td></tr>";
									$total_dias_mora += $datos['diaMora'];
									$total_capital += $datos['c_valor'];
									$total_interes += $datos['c_intereses_cuota'];
									$total_valor += $datos['c_total_cuota'];
									$linea = array('mes'=>$datos['mes'],'anno' =>$datos['anno'], 'valorCapital' =>$datos['c_valor'], 'valorInteres' =>  $datos['c_intereses_cuota'], 'valorTotal' =>$datos['c_total_cuota'] );
									array_push($detalle, $linea);
								endif;

							endfor;

						endif;

					endif;

					if($diferencia_annos > 0):

						$anno = (int)$anno_fecha_liquidacion;

						for($j = $diferencia_annos; $j >= 0; $j--):

							if($anno_fecha_ejecutoria == $anno_fecha_liquidacion - $j):

								$mes_fecha_liquidacion = (int)$mes_fecha_liquidacion;

								for($i = $mes_fecha_liquidacion; $i > 0; $i--):

									if($i == $mes_fecha_liquidacion):

										$diferencia_dias = $dia_fecha_liquidacion;
										$datos = calcularDias($mes_fecha_liquidacion, $anno_fecha_liquidacion, $diferencia_dias, $valor_multa, $tasa_interes_diaria);
										$info.= "<tr><td>". $datos['mes'] . "</td><td>" . $datos['anno'] . "</td><td>" . $datos['diaMora'] . "</td><td>" . $datos['capital'] . "</td><td>" . $datos['intereses'] . "</td><td>" . $datos['total'] ."</td></tr>";
										$total_dias_mora += $datos['diaMora'];
										$total_capital += $datos['c_valor'];
										$total_interes += $datos['c_intereses_cuota'];
										$total_valor += $datos['c_total_cuota'];
										$linea = array('mes'=>$datos['mes'],'anno' =>$datos['anno'], 'valorCapital' =>$datos['c_valor'], 'valorInteres' =>  $datos['c_intereses_cuota'], 'valorTotal' =>$datos['c_total_cuota'] );
										array_push($detalle, $linea);

									else:

										$mes = $i;
										$datos = calcularMeses($mes, $anno_fecha_liquidacion, $valor_multa, $tasa_interes_diaria);
										$info.= "<tr><td>". $datos['mes'] . "</td><td>" . $datos['anno'] . "</td><td>" . $datos['diaMora'] . "</td><td>" . $datos['capital'] . "</td><td>" . $datos['intereses'] . "</td><td>" . $datos['total'] ."</td></tr>";
										$total_dias_mora += $datos['diaMora'];
										$total_capital += $datos['c_valor'];
										$total_interes += $datos['c_intereses_cuota'];
										$total_valor += $datos['c_total_cuota'];
										$linea = array('mes'=>$datos['mes'],'anno' =>$datos['anno'], 'valorCapital' =>$datos['c_valor'], 'valorInteres' =>  $datos['c_intereses_cuota'], 'valorTotal' =>$datos['c_total_cuota'] );
										array_push($detalle, $linea);
									endif;

								endfor;

							elseif($j == 0):

								$mes_fecha_ejecutoria = (int)$mes_fecha_ejecutoria;

								for($i = 12; $i >= $mes_fecha_ejecutoria; $i--):

									if($i == $mes_fecha_ejecutoria):

										$diferencia_dias = cuentaDias($mes_fecha_ejecutoria,$anno_fecha_ejecutoria)  - $dia_fecha_ejecutoria;
										$datos = calcularDias($mes_fecha_ejecutoria, $anno_fecha_ejecutoria, $diferencia_dias, $valor_multa, $tasa_interes_diaria);
										$info.= "<tr><td>". $datos['mes'] . "</td><td>" . $datos['anno'] . "</td><td>" . $datos['diaMora'] . "</td><td>" . $datos['capital'] . "</td><td>" . $datos['intereses'] . "</td><td>" . $datos['total'] ."</td></tr>";
										$total_dias_mora += $datos['diaMora'];
										$total_capital += $datos['c_valor'];
										$total_interes += $datos['c_intereses_cuota'];
										$total_valor += $datos['c_total_cuota'];
										$linea = array('mes'=>$datos['mes'],'anno' =>$datos['anno'], 'valorCapital' =>$datos['c_valor'], 'valorInteres' =>  $datos['c_intereses_cuota'], 'valorTotal' =>$datos['c_total_cuota'] );
										array_push($detalle, $linea);

									else:

										$mes = $i;
										$datos = calcularMeses($mes, $anno_fecha_ejecutoria, $valor_multa, $tasa_interes_diaria);
										$info.= "<tr><td>". $datos['mes'] . "</td><td>" . $datos['anno'] . "</td><td>" . $datos['diaMora'] . "</td><td>" . $datos['capital'] . "</td><td>" . $datos['intereses'] . "</td><td>" . $datos['total'] ."</td></tr>";
										$total_dias_mora += $datos['diaMora'];
										$total_capital += $datos['c_valor'];
										$total_interes += $datos['c_intereses_cuota'];
										$total_valor += $datos['c_total_cuota'];
										$linea = array('mes'=>$datos['mes'],'anno' =>$datos['anno'], 'valorCapital' =>$datos['c_valor'], 'valorInteres' =>  $datos['c_intereses_cuota'], 'valorTotal' =>$datos['c_total_cuota'] );
										array_push($detalle, $linea);

									endif;

								endfor;

							elseif($j >= 1):

								$anno --;

								for($i = 12; $i > 0; $i--):

									$mes = $i;
									$datos = calcularMeses($mes, $anno, $valor_multa, $tasa_interes_diaria);
									$info.= "<tr><td>". $datos['mes'] . "</td><td>" . $datos['anno'] . "</td><td>" . $datos['diaMora'] . "</td><td>" . $datos['capital'] . "</td><td>" . $datos['intereses'] . "</td><td>" . $datos['total'] ."</td></tr>";
									$total_dias_mora += $datos['diaMora'];
									$total_capital += $datos['c_valor'];
									$total_interes += $datos['c_intereses_cuota'];
									$total_valor += $datos['c_total_cuota'];
									$linea = array('mes'=>$datos['mes'],'anno' =>$datos['anno'], 'valorCapital' =>$datos['c_valor'], 'valorInteres' =>  $datos['c_intereses_cuota'], 'valorTotal' =>$datos['c_total_cuota'] );
									array_push($detalle, $linea);
								endfor;

							endif;

						endfor;

					endif;

					$total_capital = $valor_multa;
					$total_valor = $valor_multa + $total_interes;
					$maestro ['total_dias_mora'] = $total_dias_mora;
					$maestro ['total_capital'] = $total_capital;
					$maestro ['total_interes'] = $total_interes;
					$maestro ['total_valor'] = $total_valor;
					$info .= '</tbody></table><div id="paginador" class="text-right"></div>';
					$info.= '<br><br><div id="informe_total" style="width:50%; margin-left:auto; margin-right:auto;"><table id="reporteIntereses" class="table table-bordered"><tr class="success"><th>TOTAL DIAS EN MORA</th><td style="background-color:#FFF;">'. $total_dias_mora .'</td></tr><tr class="success"><th>CAPITAL</th><td style="background-color:#FFF;">'. "$".number_format($total_capital, 0, '.', '.') .'</td></tr><tr class="success"><th>TOTAL INTERESES</th><td style="background-color:#FFF;">'. "$".number_format($total_interes, 0, '.', '.') .'</td></tr><tr class="success"><th>VALOR TOTAL</th><td style="background-color:#FFF;">'. "$".number_format($total_valor, 0, '.', '.') .'</td></tr></table></div>';
					$resultado = $this -> liquidaciones_model ->  cargarMultaMinisterio($maestro, $detalle, $liquidacion_previa);

					if($liquidacion_previa == 0):

						trazar($cod_tipoGestion_calculada, $cod_respuesta_calculada, $codigoFiscalizacion, $nit, $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_calculada);

					else:

						trazar($cod_tipoGestion_recalculada, $cod_respuesta_recalculada, $codigoFiscalizacion, $nit, $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_recalculada);

					endif;

					if($resultado === TRUE):

						echo $info;
						$insertar_maestro_liquidacion = array(
							'numeroLiquidacion' => $maestro['codigo_multa'] ,
							'codigoConcepto' => 5 ,
							'nitEmpresa' => $nit ,
							'fechaInicio' => $maestro['fecha_elaboracion'] ,
							'fechaFin' => $maestro['fecha_ejecutoria'] ,
							'fechaLiquidacion' => date('d-m-Y') ,
							'totalLiquidado' => $maestro['total_valor'] ,
							'totalCapital' => $maestro['total_capital'] ,
							'totalInteres' => $maestro['total_interes'],
							'fechaVencimiento' => $maestro['fecha_liquidacion'] ,
							'saldoDeuda' => $maestro['total_valor'] ,
							'tipoProceso' => 5 ,
							'codigoFiscalizacion' => $codigoFiscalizacion );
						$cargar_liquidacion = $this  -> liquidaciones_model -> cargarLiquidacion($insertar_maestro_liquidacion,  $liquidacion_previa);

						if($cargar_liquidacion === TRUE):

							trazar($cod_tipoGestion, $cod_respuesta, $codigoFiscalizacion, $nit, $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_enFIrme);

						else:

							throw new Exception('<strong><i>No se han almacenado datos en Maestro de Liquidaciones</i></strong>.');

						endif;

					else:

						throw new Exception('<strong><i>No se han almacenado datos en Maestro de Multas de Ministerio</i></strong>.');

					endif;

				else:

					$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					redirect(site_url().'/inicio');

				endif;

			else:

				redirect(site_url().'/auth/login');

			endif;
		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Intereses por mora en multas Ministerio del Trabajo';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e -> getMessage() .'</div>';
			$this -> template -> set('title', 'Errores en liquidación');
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
	}

	//MOSTRAR FORMULARIO PARA LIQUIDACIÓN DE APORTE
	function crearFormularioAportes($fiscalizacion)
	/**
   	* Función que muestra el formulario para registrar las bases anuales de los aportes parafiscales de la empresa en fiscalización
   	* Captura valores por parametros desde el metodo administrar()
   	*
   	* @param array $fiscalizacion
   	* @return array $data;
	*/
	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

				if ($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Fiscalizadores') || $this -> ion_auth -> in_menu('liquidaciones/crearFormularioAportes')):

                    $bloqueada = $this -> liquidaciones_model -> consultarLiquidacionBloqueada($fiscalizacion['COD_FISCALIZACION']);

                    if($bloqueada === FALSE):

                        $existe = $this -> liquidaciones_model -> consultarLiquidacionAportes($fiscalizacion['NRO_EXPEDIENTE']);
                        $liquidacion_previa = 0;

                        if($existe !== FALSE):

                            foreach ($existe as $anno):

                                $this -> data['serie_' . $anno['ANO']] = $anno;

                            endforeach;

                            $liquidacion_previa = 1;

                        endif;

                    else:

                        $liquidacion_previa = 1;
                        $this -> data['liquidacion_bloqueada'] = $bloqueada['NUM_LIQUIDACION'];

                    endif;

					$this -> data['previa'] = $liquidacion_previa;
					$maestro ['fecha_inicial'] = $fiscalizacion['PERIODO_INICIAL'];
					$maestro ['fecha_final'] = $fiscalizacion['PERIODO_FINAL'];
					$datos_fecha_inicial = explode("/",$maestro ['fecha_inicial']);
					$dia_fecha_inicial = (int)$datos_fecha_inicial[0];
					$mes_fecha_inicial = (int)$datos_fecha_inicial[1];
					$anno_fecha_inicial = (int)$datos_fecha_inicial[2];
					$datos_fecha_final = explode("/",$maestro ['fecha_final']);
					$dia_fecha_final = (int)$datos_fecha_final[0];
					$mes_fecha_final = (int)$datos_fecha_final[1];
					$anno_fecha_final = (int)$datos_fecha_final[2];
					$diferencia_annos = $anno_fecha_final - $anno_fecha_inicial;
					$diferencia_meses = $mes_fecha_final - $mes_fecha_inicial;
					$diferencia_dias = $dia_fecha_final - $dia_fecha_inicial;
					$datestring = "%d/%m/%Y";
					$fecha_actual = mdate($datestring);
					$this -> template -> set('title', 'Liquidación Aportes Parafiscales');
					$this -> data['message']=$this -> session -> flashdata('message');

					if ($diferencia_annos < 0 || ($diferencia_annos == 0  && $diferencia_meses < 0) || ($diferencia_annos == 0  && $diferencia_meses == 0 && $diferencia_dias < 0)):

						throw new Exception('<strong>La fecha de finalización de la fiscalización  : <i>'. $maestro ['fecha_final']  .'</i> es anterior a la fecha de inicio de la fiscalización: <i>'. $maestro ['fecha_inicial']  .'</i></strong>.');

					elseif ($diferencia_annos > 6):

						throw new Exception('<strong>La fecha de finalización de la fiscalización  : <i>'. $maestro ['fecha_final']  .'</i> se encuntra a más de 60 meses a la fecha de inicio de la fiscalización: <i>'. $maestro ['fecha_inicial']  .'</i> por lo cual excede el límite de fiscalización</strong>.');

					elseif ($maestro ['fecha_inicial'] == $maestro ['fecha_final']):

						throw new Exception('<strong>La fecha de finalización de la fiscalización  : <i>'. $maestro ['fecha_final']  .'</i> es igual a la fecha de inicio de la fiscalización: <i>'. $maestro ['fecha_inicial']  .'</i> por lo cual no se pueden generar calculos</strong>.');

					else:

						$this -> data['fecha'] = $fecha_actual;
						$this -> data['annoInicial'] = $anno_fecha_final;
						$this -> data['annos'] = $diferencia_annos;
						$this -> data['fiscalizacion'] = $fiscalizacion;
						$this -> template -> set('title', 'Liquidaciones Aportes');
						$this -> template -> load($this -> template_file, 'liquidaciones/liquidacion_aportes',$this ->  data);

					endif;

				else:

					$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					redirect(site_url().'/inicio');

				endif;

			else:

				redirect(site_url().'/auth/login');

			endif;
		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Liquidación Aportes Parafiscales';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e -> getMessage() .'</div>';
			$this -> template -> set('title', 'Errores en liquidación');
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
	}

	//CALCULAR LIQUIDACIÓN DE APORTES
	function calcularLiquidacionAportes()
	/**
   	* Funcion que muestra el resultado de la liquidación de aportes parafiscales, calculados anualmente y se cargan en la vista de respuesta
   	* Captura valores por variables post de getFormAportes()
   	*
   	* @param string $fechaInicial
   	* @param string $fechaFinal
   	* @param string $entidadesPublicas
   	* @param string $nit
   	* @param string $fechaLiquidacion
   	* @return float $interes_compuesto
	*/
	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

				if ($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_menu('liquidaciones/calcularLiquidacionAportes')):

					ini_set('max_execution_time', '7200');
					$this -> data['message']=$this -> session -> flashdata('message');
					$entidad = $this -> security -> xss_clean((string)$this -> input -> post('entidades'));
					$publica = $this -> security -> xss_clean((string)$this -> input -> post('entidadesPublicas'));
					$debuger = $this -> security -> xss_clean((string)$this -> input -> post('debuger'));
					$fiscalizacion = unserialize($this -> security -> xss_clean($this -> input -> post('fiscalizacion')));

                    $liquidacion_bloqueada = $this -> security -> xss_clean((string)$this -> input -> post('liquidacion_bloqueada'));

                    if($liquidacion_bloqueada > 0):

                        $fiscalizacion['NRO_EXPEDIENTE'] = $liquidacion_bloqueada;

                    endif;

					$this -> data['fiscalizacion'] = $fiscalizacion;
					$liquidacion_previa = $this -> security -> xss_clean($this -> input -> post('liquidacion_previa'));
					$maestro ['fecha_inicial'] = $fiscalizacion['PERIODO_INICIAL'];
					$maestro ['fecha_final'] = $fiscalizacion['PERIODO_FINAL'];
					$maestro ['entidadesPublicas'] = $publica;
					$maestro ['nit'] = $fiscalizacion['NIT_EMPRESA'];
					$maestro ['fechaLiquidacion'] = $this -> security -> xss_clean($this -> input -> post('fechaLiquidacion'));



					if ($entidad == '' || $publica == '' || $fiscalizacion == '' || $liquidacion_previa == '' || $maestro ['fechaLiquidacion']  == '' ):

						throw new Exception('<strong>Se han ingresado datos insuficientes para el calculo de una liquidación</strong>.');

					endif;

					//CALCULAR LIQUIDACION APORTES PARAFISCALES
					$cod_respuesta_calculada = '110';
					$cod_tipoGestion_calculada = 69;
					$comentarios_calculada = 'Liquidación de Aportes Parafiscales calculada';
					//RECALCULAR LIQUIDACION APORTES PARAFISCALES
					$cod_respuesta_recalculada = '111';
					$cod_tipoGestion_recalculada = 69;
					$comentarios_recalculada = 'Liquidación de Aportes Parafiscales re-calculada';

					if($entidad != NULL && $entidad == 'EPU' && $publica == 'C'):

						$entidad = 0.5;
						$entidadPublica = 'S';

					else:

						$entidad = 2.0;
						$entidadPublica = 'N';

					endif;

					if($debuger == 'S'):

						$this -> data['debuger'] = $debuger;

					endif;

					$datos_fecha_liquidacion = explode("/", $maestro ['fechaLiquidacion']);
					$dia_fecha_liquidacion = (int)$datos_fecha_liquidacion[0];
					$mes_fecha_liquidacion = (int)$datos_fecha_liquidacion[1];
					$anno_fecha_liquidacion = (int)$datos_fecha_liquidacion[2];
					$datos_fecha_inicial = explode("/", $maestro ['fecha_inicial']);
					$dia_fecha_inicial = (int)$datos_fecha_inicial[0];
					$mes_fecha_inicial = (int)$datos_fecha_inicial[1];
					$anno_fecha_inicial = (int)$datos_fecha_inicial[2];
					$datos_fecha_final = explode("/", $maestro ['fecha_final']);
					$dia_fecha_final = (int)$datos_fecha_final[0];
					$mes_fecha_final = (int)$datos_fecha_final[1];
					$anno_fecha_final = (int)$datos_fecha_final[2];
					$insertar_detalle = array();
					$detalle = array();
					$diferencia_annos = $anno_fecha_final - $anno_fecha_inicial;
					$diferencia_meses = $mes_fecha_final - $mes_fecha_inicial;
					$diferencia_dias = $dia_fecha_final - $dia_fecha_inicial;

					if($diferencia_annos == 0):

						if($diferencia_meses == 0):

							if($diferencia_dias == 0):

								throw new Exception('<strong>No existe dias para calcular en un periodo de fiscalizacion desde ' . $maestro ['fecha_inicial'] . ' al ' . $maestro ['fecha_final'] . '</strong>.');

							endif;

						endif;

					endif;

					if($anno_fecha_final == $anno_fecha_liquidacion):

						if($mes_fecha_final == $mes_fecha_liquidacion):

							if($dia_fecha_final == $dia_fecha_liquidacion):

								throw new Exception('<strong>No existen dias para calcular intereses desde ' . $maestro ['fecha_final'] . ' al ' . $maestro ['fechaLiquidacion'] . '</strong>.');

							elseif($dia_fecha_liquidacion < $dia_fecha_final):

								throw new Exception('<strong>La fecha de liquidación ' . $maestro ['fechaLiquidacion'] . ' es anterior al final del periodo de fiscalización ' . $maestro ['fecha_final'] . '</strong>.');

							endif;

						elseif($mes_fecha_liquidacion < $mes_fecha_final):

							throw new Exception('<strong>La fecha de liquidación ' . $maestro ['fechaLiquidacion'] . ' es anterior al final del periodo de fiscalización ' . $maestro ['fecha_final'] . '</strong>.');

						endif;

					elseif($anno_fecha_liquidacion < $anno_fecha_final):

						throw new Exception('<strong>La fecha de liquidación ' . $maestro ['fechaLiquidacion'] . ' es anterior al final del periodo de fiscalización ' . $maestro ['fecha_final'] . '</strong>.');

					endif;

					$acumuladorDeuda = 0;
					$acumuladorAportes = 0;
					$acumuladorInteres = 0;
					$calculos = array();

					for($fila = $anno_fecha_final; $fila >= $anno_fecha_inicial; $fila--):

						$base = 0;

						if($fila == $anno_fecha_final):

							$base += (int)str_replace(".","",$this -> input -> post('sueldos_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('sobresueldos_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('salarioIntegral_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('salarioEspecie_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('supernumerarios_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('viaticos_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('auxilioTransporte_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('horasExtras_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('dominicales_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('recargoNocturno_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('jornales_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('comisiones_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('bonificacionesHabituales_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('porcentajeVentas_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('vacaciones_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('trabajoDomicilio_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('subcontratos_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaTecnicaSalarial_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('auxilioAlimentacion_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaServicios_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaLocalizacion_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaVivienda_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('gastosRepresentacion_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaAntiguedad_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaProductividad_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaVacaciones_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaNavidad_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('contratosAgricolas_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('remuneracionSocios_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('horaCatedra_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('otrosPagos_'.$fila));

							if ($base <=0):

								throw new Exception('<strong>Se han ingresado datos insuficientes o errados en el año : <i>'. $fila  .'</i></strong>.');

							endif;

							$detalle_fila = array('ano' => $fila,
								'sueldos' => (int)str_replace(".","",$this -> input -> post('sueldos_'.$fila)),
								'sobresueldos' => (int)str_replace(".","",$this -> input -> post('sobresueldos_'.$fila)),
								'salarioIntegral' => (int)str_replace(".","",$this -> input -> post('salarioIntegral_'.$fila)),
								'salarioEspecie' => (int)str_replace(".","",$this -> input -> post('salarioEspecie_'.$fila)),
								'supernumerarios' => (int)str_replace(".","",$this -> input -> post('supernumerarios_'.$fila)),
								'jornales' => (int)str_replace(".","",$this -> input -> post('jornales_'.$fila)),
								'auxilioTransporte' => (int)str_replace(".","",$this -> input -> post('auxilioTransporte_'.$fila)),
								'horasExtras' => (int)str_replace(".","",$this -> input -> post('horasExtras_'.$fila)),
								'dominicales' => (int)str_replace(".","",$this -> input -> post('dominicales_'.$fila)),
								'recargoNocturno' => (int)str_replace(".","",$this -> input -> post('recargoNocturno_'.$fila)),
								'viaticos' => (int)str_replace(".","",$this -> input -> post('viaticos_'.$fila)),
								'bonificacionesHabituales' => (int)str_replace(".","",$this -> input -> post('bonificacionesHabituales_'.$fila)),
								'comisiones' => (int)str_replace(".","",$this -> input -> post('comisiones_'.$fila)),
								'porcentajeVentas' => (int)str_replace(".","",$this -> input -> post('porcentajeVentas_'.$fila)),
								'vacaciones' => (int)str_replace(".","",$this -> input -> post('vacaciones_'.$fila)),
								'trabajoDomicilio' => (int)str_replace(".","",$this -> input -> post('trabajoDomicilio_'.$fila)),
								'primaTecnicaSalarial' => (int)str_replace(".","",$this -> input -> post('primaTecnicaSalarial_'.$fila)),
								'auxilioAlimentacion' => (int)str_replace(".","",$this -> input -> post('auxilioAlimentacion_'.$fila)),
								'primaServicios' => (int)str_replace(".","",$this -> input -> post('primaServicios_'.$fila)),
								'primaLocalizacion' => (int)str_replace(".","",$this -> input -> post('primaLocalizacion_'.$fila)),
								'primaVivienda' => (int)str_replace(".","",$this -> input -> post('primaVivienda_'.$fila)),
								'gastosRepresentacion' => (int)str_replace(".","",$this -> input -> post('gastosRepresentacion_'.$fila)),
								'primaAntiguedad' => (int)str_replace(".","",$this -> input -> post('primaAntiguedad_'.$fila)),
								'primaProductividad' => (int)str_replace(".","",$this -> input -> post('primaProductividad_'.$fila)),
								'primaVacaciones' => (int)str_replace(".","",$this -> input -> post('primaVacaciones_'.$fila)),
								'primaNavidad' => (int)str_replace(".","",$this -> input -> post('primaNavidad_'.$fila)),
								'contratosAgricolas' => (int)str_replace(".","",$this -> input -> post('contratosAgricolas_'.$fila)),
								'remuneracionSocios' => (int)str_replace(".","",$this -> input -> post('remuneracionSocios_'.$fila)),
								'horaCatedra' => (int)str_replace(".","",$this -> input -> post('horaCatedra_'.$fila)),
								'otrosPagos' => (int)str_replace(".","",$this -> input -> post('otrosPagos_'.$fila)),
								'subcontratos' => (int)str_replace(".","",$this -> input -> post('subcontratos_'.$fila)),
								'liquidacion' => $fiscalizacion['NRO_EXPEDIENTE']);
							array_push($insertar_detalle, $detalle_fila);
							$base_anual = $base * ($entidad/100);
							$aporteMensualAcumulado = 0;

							if($diferencia_annos == 0):

								$intereses = 0;
								$tasaMensual = $base_anual/($diferencia_meses + 1);

								for($fila_int = $mes_fecha_final; $fila_int >= $mes_fecha_inicial; $fila_int --):

									if($fila_int < 10):

										$periodo = $fila.'-0'.$fila_int;

									else:

										$periodo = $fila.'-'.$fila_int;

									endif;

									$aporteMensual = $this -> liquidaciones_model -> getAporte_mes($maestro['nit'], 1, 21, $periodo);
									$pagoOportuno = 0;
									$capital = $tasaMensual - $aporteMensual['VALOR_PAGADO'];
									$aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];

									if ($capital <= 0 ):

										$intereses = 0;

									else:

										$intereses += calcularMesesCorrientes($maestro ['fechaLiquidacion'], $capital, $fila_int, $fila);
										$linea = calcularInteresesAportesDebug($maestro ['fechaLiquidacion'], $capital, $fila_int, $fila, $aporteMensual['VALOR_PAGADO']);
										$calculos = array_merge($calculos, $linea);


									endif;

								endfor;

							else:

								$intereses = 0;
								$tasaMensual = $base_anual/$mes_fecha_final;

								for($fila_int = $mes_fecha_final; $fila_int > 0; $fila_int --):

									if($fila_int < 10):

										$periodo = $fila.'-0'.$fila_int;

									else:

										$periodo = $fila.'-'.$fila_int;

									endif;

									$aporteMensual = $this -> liquidaciones_model -> getAporte_mes($maestro['nit'], 1, 21, $periodo);
									$pagoOportuno = 0;
									$deuda = $tasaMensual - $aporteMensual['VALOR_PAGADO'];
									$aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];

									if ($deuda <= 0 ):

										$intereses = 0;

									else:

										$intereses += calcularMesesCorrientes($maestro ['fechaLiquidacion'], $deuda, $fila_int, $fila);
										$linea = calcularInteresesAportesDebug($maestro ['fechaLiquidacion'], $deuda, $fila_int, $fila, $aporteMensual['VALOR_PAGADO']);
										$calculos = array_merge($calculos, $linea);

									endif;

								endfor;


							endif;

							$informacion = array('aportes_'.$fila => $aporteMensualAcumulado, 'deuda_'.$fila => $base_anual, 'intereses_'.$fila => $intereses, 'base_'.$fila => $base);
							$detalle = array_merge($detalle, $informacion);
							$acumuladorDeuda += $base_anual;
							$acumuladorAportes += $aporteMensualAcumulado;
							$acumuladorInteres += $intereses;

						elseif ($fila == $anno_fecha_inicial):
							$base += (int)str_replace(".","",$this -> input -> post('sueldos_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('sobresueldos_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('salarioIntegral_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('salarioEspecie_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('supernumerarios_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('viaticos_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('auxilioTransporte_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('horasExtras_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('dominicales_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('recargoNocturno_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('jornales_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('comisiones_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('bonificacionesHabituales_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('porcentajeVentas_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('vacaciones_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('trabajoDomicilio_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('subcontratos_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaTecnicaSalarial_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('auxilioAlimentacion_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaServicios_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaLocalizacion_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaVivienda_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('gastosRepresentacion_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaAntiguedad_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaProductividad_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaVacaciones_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaNavidad_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('contratosAgricolas_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('remuneracionSocios_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('horaCatedra_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('otrosPagos_'.$fila));

							if ($base <= 0):

								throw new Exception('<strong>Se han ingresado datos insuficientes o errados en el año : <i>'. $fila  .'</i></strong>.');

							endif;

							$detalle_fila = array('ano' => $fila,
								'sueldos' => (int)str_replace(".","",$this -> input -> post('sueldos_'.$fila)),
								'sobresueldos' => (int)str_replace(".","",$this -> input -> post('sobresueldos_'.$fila)),
								'salarioIntegral' => (int)str_replace(".","",$this -> input -> post('salarioIntegral_'.$fila)),
								'salarioEspecie' => (int)str_replace(".","",$this -> input -> post('salarioEspecie_'.$fila)),
								'supernumerarios' => (int)str_replace(".","",$this -> input -> post('supernumerarios_'.$fila)),
								'jornales' => (int)str_replace(".","",$this -> input -> post('jornales_'.$fila)),
								'auxilioTransporte' => (int)str_replace(".","",$this -> input -> post('auxilioTransporte_'.$fila)),
								'horasExtras' => (int)str_replace(".","",$this -> input -> post('horasExtras_'.$fila)),
								'dominicales' => (int)str_replace(".","",$this -> input -> post('dominicales_'.$fila)),
								'recargoNocturno' => (int)str_replace(".","",$this -> input -> post('recargoNocturno_'.$fila)),
								'viaticos' => (int)str_replace(".","",$this -> input -> post('viaticos_'.$fila)),
								'bonificacionesHabituales' => (int)str_replace(".","",$this -> input -> post('bonificacionesHabituales_'.$fila)),
								'comisiones' => (int)str_replace(".","",$this -> input -> post('comisiones_'.$fila)),
								'porcentajeVentas' => (int)str_replace(".","",$this -> input -> post('porcentajeVentas_'.$fila)),
								'vacaciones' => (int)str_replace(".","",$this -> input -> post('vacaciones_'.$fila)),
								'trabajoDomicilio' => (int)str_replace(".","",$this -> input -> post('trabajoDomicilio_'.$fila)),
								'primaTecnicaSalarial' => (int)str_replace(".","",$this -> input -> post('primaTecnicaSalarial_'.$fila)),
								'auxilioAlimentacion' => (int)str_replace(".","",$this -> input -> post('auxilioAlimentacion_'.$fila)),
								'primaServicios' => (int)str_replace(".","",$this -> input -> post('primaServicios_'.$fila)),
								'primaLocalizacion' => (int)str_replace(".","",$this -> input -> post('primaLocalizacion_'.$fila)),
								'primaVivienda' => (int)str_replace(".","",$this -> input -> post('primaVivienda_'.$fila)),
								'gastosRepresentacion' => (int)str_replace(".","",$this -> input -> post('gastosRepresentacion_'.$fila)),
								'primaAntiguedad' => (int)str_replace(".","",$this -> input -> post('primaAntiguedad_'.$fila)),
								'primaProductividad' => (int)str_replace(".","",$this -> input -> post('primaProductividad_'.$fila)),
								'primaVacaciones' => (int)str_replace(".","",$this -> input -> post('primaVacaciones_'.$fila)),
								'primaNavidad' => (int)str_replace(".","",$this -> input -> post('primaNavidad_'.$fila)),
								'contratosAgricolas' => (int)str_replace(".","",$this -> input -> post('contratosAgricolas_'.$fila)),
								'remuneracionSocios' => (int)str_replace(".","",$this -> input -> post('remuneracionSocios_'.$fila)),
								'horaCatedra' => (int)str_replace(".","",$this -> input -> post('horaCatedra_'.$fila)),
								'otrosPagos' => (int)str_replace(".","",$this -> input -> post('otrosPagos_'.$fila)),
								'subcontratos' => (int)str_replace(".","",$this -> input -> post('subcontratos_'.$fila)),
								'liquidacion' => $fiscalizacion['NRO_EXPEDIENTE']);
							array_push($insertar_detalle, $detalle_fila);
							$base_anual = $base * ($entidad/100);
							$aporteMensualAcumulado = 0;

							if($diferencia_annos == 0):

								$intereses = 0;
								$tasaMensual = $base_anual/($diferencia_meses + 1);

								for($fila_int = $mes_fecha_final; $fila_int >= $mes_fecha_inicial; $fila_int --):

									if($fila_int < 10):

										$periodo = $fila.'-0'.$fila_int;

									else:

										$periodo = $fila.'-'.$fila_int;

									endif;

									$aporteMensual = $this -> liquidaciones_model -> getAporte_mes($maestro['nit'], 1, 21, $periodo);
									$pagoOportuno = 0;
									$capital = $tasaMensual - $aporteMensual['VALOR_PAGADO'];
									$aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];

									if ($capital <= 0 ):

										$intereses = 0;

									else:

										$intereses += calcularMesesCorrientes($maestro ['fechaLiquidacion'], $capital, $fila_int, $fila);
										$linea = calcularInteresesAportesDebug($maestro ['fechaLiquidacion'], $capital, $fila_int, $fila, $aporteMensual['VALOR_PAGADO']);
										$calculos = array_merge($calculos, $linea);


									endif;

								endfor;

							else:

								$intereses = 0;
								$tasaMensual = $base_anual/((12 - $mes_fecha_inicial)+1);

								for($fila_int = 12; $fila_int >= $mes_fecha_inicial; $fila_int --):

									if($fila_int < 10):

										$periodo = $fila.'-0'.$fila_int;

									else:

										$periodo = $fila.'-'.$fila_int;

									endif;

									$aporteMensual = $this -> liquidaciones_model -> getAporte_mes($maestro['nit'], 1, 21, $periodo);
									$pagoOportuno = 0;
									$deuda = $tasaMensual - $aporteMensual['VALOR_PAGADO'];
									$aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];

									if ($deuda <= 0 ):

										$intereses = 0;

									else:

										$intereses += calcularMesesCorrientes($maestro ['fechaLiquidacion'], $deuda, $fila_int, $fila);
										$linea = calcularInteresesAportesDebug($maestro ['fechaLiquidacion'], $deuda, $fila_int, $fila, $aporteMensual['VALOR_PAGADO']);
										$calculos = array_merge($calculos, $linea);

									endif;

								endfor;


							endif;

							$informacion = array('aportes_'.$fila => $aporteMensualAcumulado, 'deuda_'.$fila => $base_anual, 'intereses_'.$fila => $intereses, 'base_'.$fila => $base);
							$detalle = array_merge($detalle, $informacion);
							$acumuladorDeuda += $base_anual;
							$acumuladorAportes += $aporteMensualAcumulado;
							$acumuladorInteres += $intereses;

						else:
							$base += (int)str_replace(".","",$this -> input -> post('sueldos_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('sobresueldos_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('salarioIntegral_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('salarioEspecie_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('supernumerarios_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('viaticos_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('auxilioTransporte_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('horasExtras_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('dominicales_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('recargoNocturno_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('jornales_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('comisiones_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('bonificacionesHabituales_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('porcentajeVentas_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('vacaciones_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('trabajoDomicilio_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('subcontratos_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaTecnicaSalarial_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('auxilioAlimentacion_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaServicios_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaLocalizacion_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaVivienda_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('gastosRepresentacion_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaAntiguedad_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaProductividad_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaVacaciones_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('primaNavidad_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('contratosAgricolas_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('remuneracionSocios_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('horaCatedra_'.$fila));
							$base += (int)str_replace(".","",$this -> input -> post('otrosPagos_'.$fila));

							if ($base <=0):

								throw new Exception('<strong>Se han ingresado datos insuficientes o errados en el año : <i>'. $fila  .'</i></strong>.');

							endif;

							$detalle_fila = array('ano' => $fila,
								'sueldos' => (int)str_replace(".","",$this -> input -> post('sueldos_'.$fila)),
								'sobresueldos' => (int)str_replace(".","",$this -> input -> post('sobresueldos_'.$fila)),
								'salarioIntegral' => (int)str_replace(".","",$this -> input -> post('salarioIntegral_'.$fila)),
								'salarioEspecie' => (int)str_replace(".","",$this -> input -> post('salarioEspecie_'.$fila)),
								'supernumerarios' => (int)str_replace(".","",$this -> input -> post('supernumerarios_'.$fila)),
								'jornales' => (int)str_replace(".","",$this -> input -> post('jornales_'.$fila)),
								'auxilioTransporte' => (int)str_replace(".","",$this -> input -> post('auxilioTransporte_'.$fila)),
								'horasExtras' => (int)str_replace(".","",$this -> input -> post('horasExtras_'.$fila)),
								'dominicales' => (int)str_replace(".","",$this -> input -> post('dominicales_'.$fila)),
								'recargoNocturno' => (int)str_replace(".","",$this -> input -> post('recargoNocturno_'.$fila)),
								'viaticos' => (int)str_replace(".","",$this -> input -> post('viaticos_'.$fila)),
								'bonificacionesHabituales' => (int)str_replace(".","",$this -> input -> post('bonificacionesHabituales_'.$fila)),
								'comisiones' => (int)str_replace(".","",$this -> input -> post('comisiones_'.$fila)),
								'porcentajeVentas' => (int)str_replace(".","",$this -> input -> post('porcentajeVentas_'.$fila)),
								'vacaciones' => (int)str_replace(".","",$this -> input -> post('vacaciones_'.$fila)),
								'trabajoDomicilio' => (int)str_replace(".","",$this -> input -> post('trabajoDomicilio_'.$fila)),
								'primaTecnicaSalarial' => (int)str_replace(".","",$this -> input -> post('primaTecnicaSalarial_'.$fila)),
								'auxilioAlimentacion' => (int)str_replace(".","",$this -> input -> post('auxilioAlimentacion_'.$fila)),
								'primaServicios' => (int)str_replace(".","",$this -> input -> post('primaServicios_'.$fila)),
								'primaLocalizacion' => (int)str_replace(".","",$this -> input -> post('primaLocalizacion_'.$fila)),
								'primaVivienda' => (int)str_replace(".","",$this -> input -> post('primaVivienda_'.$fila)),
								'gastosRepresentacion' => (int)str_replace(".","",$this -> input -> post('gastosRepresentacion_'.$fila)),
								'primaAntiguedad' => (int)str_replace(".","",$this -> input -> post('primaAntiguedad_'.$fila)),
								'primaProductividad' => (int)str_replace(".","",$this -> input -> post('primaProductividad_'.$fila)),
								'primaVacaciones' => (int)str_replace(".","",$this -> input -> post('primaVacaciones_'.$fila)),
								'primaNavidad' => (int)str_replace(".","",$this -> input -> post('primaNavidad_'.$fila)),
								'contratosAgricolas' => (int)str_replace(".","",$this -> input -> post('contratosAgricolas_'.$fila)),
								'remuneracionSocios' => (int)str_replace(".","",$this -> input -> post('remuneracionSocios_'.$fila)),
								'horaCatedra' => (int)str_replace(".","",$this -> input -> post('horaCatedra_'.$fila)),
								'otrosPagos' => (int)str_replace(".","",$this -> input -> post('otrosPagos_'.$fila)),
								'subcontratos' => (int)str_replace(".","",$this -> input -> post('subcontratos_'.$fila)),
								'liquidacion' => $fiscalizacion['NRO_EXPEDIENTE']);
							array_push($insertar_detalle, $detalle_fila);
							$base_anual = $base * ($entidad/100);
							$aporteMensualAcumulado = 0;

							if($diferencia_annos == 0):

								$intereses = 0;
								$tasaMensual = $base_anual/($diferencia_meses + 1);

								for($fila_int = $mes_fecha_final; $fila_int >= $mes_fecha_inicial; $fila_int --):

									if($fila_int < 10):

										$periodo = $fila.'-0'.$fila_int;

									else:

										$periodo = $fila.'-'.$fila_int;

									endif;

									$aporteMensual = $this -> liquidaciones_model -> getAporte_mes($maestro['nit'], 1, 21, $periodo);
									$pagoOportuno = 0;
									$capital = $tasaMensual - $aporteMensual['VALOR_PAGADO'];
									$aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];

									if ($capital <= 0 ):

										$intereses = 0;

									else:

										$intereses += calcularMesesCorrientes($maestro ['fechaLiquidacion'], $capital, $fila_int, $fila);
										$linea = calcularInteresesAportesDebug($maestro ['fechaLiquidacion'], $capital, $fila_int, $fila, $aporteMensual['VALOR_PAGADO']);
										$calculos = array_merge($calculos, $linea);


									endif;

								endfor;

							else:

								$intereses = 0;
								$tasaMensual = $base_anual/12;

								for($fila_int = 12; $fila_int >= 1; $fila_int --):

									if($fila_int < 10):

										$periodo = $fila.'-0'.$fila_int;

									else:

										$periodo = $fila.'-'.$fila_int;

									endif;

									$aporteMensual = $this -> liquidaciones_model -> getAporte_mes($maestro['nit'], 1, 21, $periodo);
									$pagoOportuno = 0;
									$deuda = $tasaMensual - $aporteMensual['VALOR_PAGADO'];
									$aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];

									if ($deuda <= 0 ):

										$intereses = 0;

									else:

										$intereses += calcularMesesCorrientes($maestro ['fechaLiquidacion'], $deuda, $fila_int, $fila);
										$linea = calcularInteresesAportesDebug($maestro ['fechaLiquidacion'], $deuda, $fila_int, $fila, $aporteMensual['VALOR_PAGADO']);
										$calculos = array_merge($calculos, $linea);

									endif;

								endfor;


							endif;

							$informacion = array('aportes_'.$fila => $aporteMensualAcumulado, 'deuda_'.$fila => $base_anual, 'intereses_'.$fila => $intereses, 'base_'.$fila => $base);
							$detalle = array_merge($detalle, $informacion);
							$acumuladorDeuda += $base_anual;
							$acumuladorAportes += $aporteMensualAcumulado;
							$acumuladorInteres += $intereses;

						endif;

					endfor;

					$data = $detalle;
					$this -> data['calculos'] = $calculos;
					$this -> data['detalle'] = $data;
					$this -> data['maestro'] = $maestro;
					$this -> data['documentacion'] = $this -> liquidaciones_model -> consultarLiquidacionAportesDetalle($fiscalizacion['NRO_EXPEDIENTE']);
					$insertar_maestro_liquidacion = array(
						'numeroLiquidacion' => $fiscalizacion['NRO_EXPEDIENTE'] ,
						'codigoConcepto' => $fiscalizacion['COD_CONCEPTO'] ,
						'nitEmpresa' => $fiscalizacion['NIT_EMPRESA'] ,
						'fechaInicio' => $fiscalizacion['PERIODO_INICIAL'] ,
						'fechaFin' => $fiscalizacion['PERIODO_FINAL'] ,
						'fechaLiquidacion' => date('d-m-Y') ,
						'totalLiquidado' => ($acumuladorDeuda + $acumuladorInteres) ,
						'totalCapital' => $acumuladorDeuda ,
						'totalInteres' => $acumuladorInteres,
						'fechaVencimiento' => $maestro['fechaLiquidacion'] ,
						'saldoDeuda' => ($acumuladorDeuda + $acumuladorInteres) ,
						'tipoProceso' => 5 ,
						'codigoFiscalizacion' => $fiscalizacion['COD_FISCALIZACION'] );
					$insertar_maestro_liquidacion_aportes = array(
						'numeroLiquidacion' => $fiscalizacion['NRO_EXPEDIENTE'] ,
						'nitEmpresa' => $fiscalizacion['NIT_EMPRESA'] ,
						'fechaLiquidacion' => $maestro['fechaLiquidacion'] ,
						'totalCapital' => $acumuladorDeuda ,
						'totalInteres' => $acumuladorInteres,
						'totalAportes' => $acumuladorAportes,
						'pagoAportes' => $acumuladorAportes,
						'intereses' => $acumuladorInteres,
						'saldoDeuda' => ($acumuladorDeuda + $acumuladorInteres) ,
						'entidadPublica' =>  $entidadPublica,
						'codigoFiscalizacion' => $fiscalizacion['COD_FISCALIZACION']
						);
					$insertar_detalle_liquidacion_aportes = $insertar_detalle;
                    //var_dump($insertar_detalle); die();
					$cargar_liquidacion = $this  -> liquidaciones_model -> cargarLiquidacion($insertar_maestro_liquidacion,  $liquidacion_previa);

					if($cargar_liquidacion === TRUE):

                        if($liquidacion_bloqueada > 0):

                            $liquidacion_previa = 0;

                        endif;

						$cargar_liquidacion_aportes = $this -> liquidaciones_model -> cargarLiquidacionAporte($insertar_maestro_liquidacion_aportes,  $liquidacion_previa);

						if($cargar_liquidacion_aportes === TRUE):

							if($liquidacion_previa == 0):

								trazar($cod_tipoGestion_calculada, $cod_respuesta_calculada, $fiscalizacion['COD_FISCALIZACION'], $fiscalizacion['NIT_EMPRESA'], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_calculada);

							else:

								trazar($cod_tipoGestion_recalculada, $cod_respuesta_recalculada, $fiscalizacion['COD_FISCALIZACION'], $fiscalizacion['NIT_EMPRESA'], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_recalculada);

							endif;

							foreach($insertar_detalle_liquidacion_aportes as $liquidacion):

								$cargar_liquidacion_aportes_detalle = $this -> liquidaciones_model -> cargarLiquidacionAporteDetalle($liquidacion,  $liquidacion_previa);

								if($cargar_liquidacion_aportes_detalle === TRUE):

								else:

									throw new Exception('<strong>No ha sido posible cargar la liquidacion. Error: '. $cargar_liquidacion);

								endif;

							endforeach;

						else:

							throw new Exception('<strong>No ha sido posible cargar la liquidacion. Error: '. $cargar_liquidacion);

						endif;

					else:

						throw new Exception('<strong>No ha sido posible cargar la liquidacion. Error: '. $cargar_liquidacion);

					endif;

					// $diferencia_annos = $anno_fecha_final - $anno_fecha_inicial;
					$anno_inicial = $anno_fecha_final;

					if ($diferencia_annos < 0):

						throw new Exception('<strong>La fecha de finalización de la fiscalización  : <i>'. $maestro ['fecha_final']  .'</i> es anterior a la fecha de inicio de la fiscalización: <i>'. $maestro ['fecha_inicial']  .'</i></strong>.');

					else:

						$this -> data['annoInicial'] =$anno_inicial;
						$this -> data['annos'] = $diferencia_annos;

					endif;

					$this -> template -> set('title', 'Liquidaciones Aportes');
					$this -> template -> load($this -> template_file, 'liquidaciones/liquidacion_aportes_respuesta',$this ->  data);

				else:

					$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					redirect(site_url().'/inicio');

				endif;
			else:

				redirect(site_url().'/auth/login');

			endif;
		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Liquidación Aportes Parafiscales';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e -> getMessage() .'</div>';
			$this -> template -> set('title', 'Errores en liquidación');
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
	}

		//MOSTRAR FORMULARIO PARA LIQUIDACIÓN DE FIC
		function getFormFic($codigoGestion)
		/**
	   	* Función que muestra el formulario para registrar las liquidaciones presuntivas y normativas de la empresa en fiscalización
	   	* Captura valores por parametros desde el metodo administrar()
	   	*
	   	* @param array $codigoGestion
	   	* @return array $data;
		*/
		{
			try
			{
				if ($this -> ion_auth -> logged_in()): //verificación de acceso autorizado
					if ($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_menu('liquidaciones/getFormFic')): //verificación de perfil

						$this -> data['codigoGestion'] = $codigoGestion;
						$this -> data['fecha'] = getFechaActual();

						$fechaInicial = explode('/',$codigoGestion['PERIODO_INICIAL']);
						$this -> data['annoInicial'] = (integer)$fechaInicial[2];
						$fechaFinal = explode('/',$codigoGestion['PERIODO_FINAL']);
						$this -> data['annoFinal'] = (integer)$fechaFinal[2];

						$smlv = array();
						for ($indicador = (integer)$fechaFinal[2]; $indicador >= (integer)$fechaInicial[2]; $indicador--):
								$salario = $this -> liquidaciones_model -> getSalarioMinimoVigente($indicador);
								$smlv['smlv_'.$indicador] = $salario['SALARIO_MINIMO'];
						endfor;

						$this -> data['smlv'] = $smlv;

						$this -> template -> set('title', 'Liquidaciones FIC');
						$this -> template->load($this->template_file, 'liquidaciones/fic_view',$this-> data);

					else:

						$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
						redirect(site_url().'/inicio');

					endif;

				else:

					redirect(site_url().'/auth/login');

				endif;
			}
			catch (Exception $e)
			{
				$this -> data['titulo'] = 'Liquidación Fondo de Industria y Comercio (FIC)';
				$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e->getMessage() .'</div>';
				$this -> template -> set('title', 'Errores en liquidación');
				$this -> template -> load($this->template_file, 'liquidaciones/error',$this-> data);
			}
		}


	    function loadFormFic()
	    /**
	   	* Función que muestra el formulario para registrar las liquidaciones presuntivas y normativas de la empresa en fiscalización
	   	* Captura valores por parametros desde el metodo administrar()
	   	*
	   	* @param array $codigoGestion
	   	* @return array $data;
		*/
		{
			try
			{
				if ($this -> ion_auth -> logged_in()): //verificación de acceso autorizado

					if ($this -> ion_auth -> is_admin()  || $this -> ion_auth -> in_menu('liquidaciones/loadFormFic')): //verificación de perfil

				    	$liquidacion = $this -> security -> xss_clean($_POST);
				    	$maestro = $this -> security -> xss_clean(unserialize($this -> input -> post('maestro')));
                        /*var_dump($liquidacion);
                        var_dump($maestro);
                        die();*/
                        if($_POST['debuger'] == 'S'):
                            $this -> data['debuger'] = 'S';
                        endif;
				    	$this -> data['liquidacion'] = $liquidacion;
				    	$this -> data['maestro'] = $maestro;
				    	$this -> data['message'] = ' ';

				    	//LIQUIDACIÓN FIC CALCULADA
					    $cod_respuesta = '116';
					    $cod_tipoGestion = 71;
					    $comentarios_fic_calculada = 'Liquidación FIC Generada';
					    //LIQUIDACIÓN FIC RE-CALCULADA
					    $cod_respuesta_recalculada = '117';
					    $cod_tipoGestion_recalculada = 71;
					    $comentarios_fic_recalculada = 'Liquidación FIC Recalculada';

				        $codigoFiscalizacion = $maestro['COD_FISCALIZACION'];
				        $usuario = $this->ion_auth->user()->row();
				        $idusuario = $usuario->IDUSUARIO;
				        $this->data['dato_usuario']=$usuario->NOMBRES." ".$usuario->APELLIDOS;

				        $this->data['cabecera'] = $this->liquidaciones_model->consultarCabeceraLiquidacion($codigoFiscalizacion, $idusuario);
						$datos_fecha_inicial = explode("/",$maestro ['PERIODO_INICIAL']);
						$dia_fecha_inicial = (int)$datos_fecha_inicial[0];
						$mes_fecha_inicial = (int)$datos_fecha_inicial[1];
						$anno_fecha_inicial = (int)$datos_fecha_inicial[2];

						//separación de fechas periodo final
						$datos_fecha_final = explode("/",$maestro ['PERIODO_FINAL']);
						$dia_fecha_final = (int)$datos_fecha_final[0];
						$mes_fecha_final = (int)$datos_fecha_final[1];
						$anno_fecha_final = (int)$datos_fecha_final[2];
	                    $fechaLiquidacion = $liquidacion['fechaLiquidacion'];

	                    $detalle = array();
                        $calculos = array();
	                    $FicNormativo = $this -> FicNormativo($liquidacion, $maestro);
                        $calculos = array_merge($calculos, $FicNormativo['calculos']);
	                    $this -> data ['FicNormativo'] = $FicNormativo;
	                    //Contratos Fic Mano de Obra/Todo Costo
	                    $cantidadContr = count($liquidacion['contratos_costo']);
	                    $cantidadContrObra = count($liquidacion['contratos_obra']);
	                    if ($cantidadContr > 1)://ContratosCosto
	                        $ContratoCosto = $this -> ContratoCosto($cantidadContr, $liquidacion, $maestro);
                            $calculos = array_merge($calculos, $ContratoCosto['calculos']);
	                        $this -> data['FicCostoContrato'] = $ContratoCosto['detalle'];
	                    endif;
	                    if ($cantidadContrObra > 1):
	                        $ContratoObra = $this -> ContratoObra($cantidadContrObra , $liquidacion, $maestro);
                            $calculos = array_merge($calculos, $ContratoObra['calculos']);
	                    endif;
	                    $ValorAnual = $this->ValorAnual($liquidacion, $maestro);
	                    $resultado = array();
	                    $InteresesNormativo =0;
	                    $InteresesCosto     =0;
	                    $InteresesObra      =0;
	                    $InteresesAnual     =0;
	                    $Intereses          =0;
	                    $AportesNormativo   =0;
	                    $AportesAnual       =0;
	                    $AportesPresuntivo  =0;
	                    $Aportes            =0;
	                    $TotalNormativo     =0;
	                    $TotalAnual         =0;
	                    $TotalPresuntivo    =0;
	                    $TotalFic           =0;
	                    $cant               =0;
	                    $TotalFicCosto      =0;
	                    $TotalFicObra       =0;
	                    $InteresesFic       =0;
	                    $TotalNeto          =0;
	                    $ValorTotalFic      =0;
	                    $ValorTotalAportes  =0;
	                    $ValorTotalIntereses=0;
	                    $SaldoDeuda         =0;
	                    $BaseCosto          =0;
	                    $BaseObra           =0;
	                    $interesesAnualCosto=0;
	                    $interesesAnualObra =0;
	                    $AporteAnualPres    =0;
	                    setlocale(LC_TIME, 'spanish');
	                    for ($fila = $anno_fecha_inicial; $fila <= $anno_fecha_final; $fila++):
	                        //Obtener nombre de los meses de acuerdo al año
	                        if (($anno_fecha_final - $anno_fecha_inicial) == 0)://si es el mismo año;
	                            if ($mes_fecha_inicial == $mes_fecha_final)://si es el miso
	                                $periodo = strtoupper(strftime("%B", mktime(0, 0, 0, $mes_fecha_inicial, 1, 2000)));
	                            else://Cuando es diferente mes
	                                $periodo = strtoupper(strftime("%B", mktime(0, 0, 0, $mes_fecha_inicial, 1, 2000)) . " - " . strftime("%B", mktime(0, 0, 0, $mes_fecha_final, 1, 2000)));
	                            endif;
	                        else://otros años
	                            if ($fila == $anno_fecha_inicial): //primer año
	                                $periodo = strtoupper(substr(strftime("%B", mktime(0, 0, 0, $mes_fecha_final, 1, 2000)), 0, -3)) . " - DIC";
	                            elseif ($fila == $anno_fecha_final): //ultimo año
	                                 $periodo = 'ENE - ' . strtoupper(substr(strftime("%B", mktime(0, 0, 0, $mes_fecha_inicial, 1, 2000)), 0, -3));
	                            else://años intermedios
	                                $periodo = 'ENE-DIC';
	                            endif;
	                        endif;
	                        $cant +=$cant+1;
	                        $liquidacion[$fila."_TotalContratoObra"]=preg_replace( "/[\.]/", "",  $liquidacion[$fila."_TotalContratoObra"]);
	                        $liquidacion["totalCosto_".$fila]=preg_replace( "/[\.]/", "",  $liquidacion["totalCosto_".$fila]);
	                        $liquidacion["totalObra_".$fila]=preg_replace( "/[\.]/", "",  $liquidacion["totalObra_".$fila]);
	                        if (isset($ContratoCosto))://Valida si existe al menos un contrato
	                             if (array_key_exists('tasamensual_' . $fila, $ContratoCosto))://Valida si existe contrato para ese año
	                                $valor_contrato_costo = $ContratoCosto['tasamensual_' . $fila];
	                                $intereses_contrato_costo=$ContratoCosto['intereses_' . $fila];
	                                $aportes_contrato_costo=$ContratoCosto['aportes_' . $fila];
	                                $valorContrato=$ContratoCosto['aportes_' . $fila];
	                             else:
	                            $valor_contrato_costo = 0;
	                            $intereses_contrato_costo=0;
	                            $aportes_contrato_costo=0;
	                             $valorContrato=0;
	                        endif;
	                        else:
	                            $valor_contrato_costo = 0;
	                            $intereses_contrato_costo=0;
	                            $aportes_contrato_costo=0;
	                        endif;

	//
	//                        echo "<pre>";
	//                        print_r($ValorAnual);
	//                        echo "</pre>";
	                        if ($ValorAnual['baseCosto_' . $fila]):

	                            $valor_anual_costo = $ValorAnual['tasamensualcosto_' . $fila];//Lo que debio haber pagado
	                            $BaseCosto=$ValorAnual['baseCosto_' . $fila];
	                             //Consulto el pago anual
	                            //verifico si hay deuda
	                            if($ValorAnual['tasamensualcosto_2014']>0):
	                                $c_periodo=$fila."-01";
	                                $AporteAnualPres= $this -> liquidaciones_model -> getAporte_Fic($maestro['NIT_EMPRESA'], 2, 62, $c_periodo,'ANUAL');
	                                $vAnual=($ValorAnual['baseCosto_' . $fila]*0.0025);
	                                $valorDeuda=$vAnual-$AporteAnualPres['VALOR_PAGADO'];
	                                //si no hay pago calculo intereses
	                                if($valorDeuda>0):
	                                    $interesesAnualCosto =calcularMesesCorrientes($liquidacion['fechaLiquidacion'], $valorDeuda, 01, $fila);
                                        $linea = calcularInteresesFicDebug($liquidacion ['fechaLiquidacion'], $valorDeuda, 01, $fila, $AporteAnualPres);
                                        $calculos = array_merge($calculos, $linea);

                                    endif;
	                            endif;
	                        else:
	                            $valor_anual_costo = 0;
	                            $BaseCosto=0;
	                        endif;
	                        $contratoCosto=$liquidacion[$fila."_TotalContratoCosto"]+$BaseCosto;
	                        if ($ValorAnual['baseObra_' . $fila]):
	                            $valor_anual_obra =$ValorAnual['tasamensualobra_' . $fila];
	                            $BaseObra=$ValorAnual['baseObra_' . $fila];
	                               //Consulto el pago anual
	                            //verifico si hay deuda
	                            if($ValorAnual['tasamensualobra_2014']>0):
	                                $c_periodo=$fila."-01";
	                                $AporteAnualPres= $this -> liquidaciones_model -> getAporte_Fic($maestro['NIT_EMPRESA'], 2, 62, $c_periodo,'ANUAL');
	                                $vAnual=($ValorAnual['baseObra_' . $fila]*0.01);
	                                $valorDeuda=$vAnual-$AporteAnualPres['VALOR_PAGADO'];
	                                //si no hay pago calculo intereses
	                                if($valorDeuda>0):
	                                    $interesesAnualObra =calcularMesesCorrientes($liquidacion['fechaLiquidacion'], $valorDeuda, 01, $fila);
                                        $linea = calcularInteresesFicDebug($liquidacion ['fechaLiquidacion'], $valorDeuda, 01, $fila, $AporteAnualPres);
                                        $calculos = array_merge($calculos, $linea);
	                                endif;
	                            endif;
	                        else:
	                            $valor_anual_obra = 0;
	                            $BaseObra=0;
	                        endif;
	                        if (isset($ContratoObra))://Valida si existe al menos un contrato
	                            if (array_key_exists('tasamensual_' . $fila, $ContratoObra))://Valida si existe contrato para ese año
	                                $valor_contrato_obra = $ContratoObra['tasamensual_' . $fila];//Valor Tasa Acumulada para ese año
	                                $interes_contrato_obra=$ContratoObra['intereses_' . $fila];
	                                $aportes_contrato_obra=$ContratoObra['aportes_'.$fila];
	                             else:
	                            $valor_contrato_obra = 0;
	                            $interes_contrato_obra=0;
	                            $aportes_contrato_obra=0;
	                        endif;
	                        else:
	                            $valor_contrato_obra = 0;
	                            $interes_contrato_obra=0;
	                            $aportes_contrato_obra=0;
	                        endif;

	                          $contratoObra= $liquidacion[$fila."_TotalContratoObra"]+ $BaseObra;
	                        //Suma de Intereses por año

	                         $InteresesNormativo = $FicNormativo['detalle_fic']['intereses_'.$fila];
	//                         echo "<pre>";
	//                         print_r($ValorAnual);
	//                         echo "</pre>";
	                         $InteresesAnual= $interesesAnualCosto+$interesesAnualObra;
	                         $InteresesPresuntivo= ($InteresesAnual+$interes_contrato_obra+ $intereses_contrato_costo);
	                         $Intereses=($InteresesNormativo + $InteresesPresuntivo);
	                         $InteresesFic+=$Intereses;
	                        //Suma Aportes por año
	                         $AportesNormativo += $FicNormativo['detalle_fic']['aportes_'.$fila];
	                         $AportesAnual+= $ValorAnual['aportes_'.$fila]+ $AporteAnualPres;
	                         $AportesPresuntivo= ($AportesAnual+$aportes_contrato_obra+ $aportes_contrato_costo);
	                         $Aportes=($AportesNormativo + $AportesPresuntivo);
	                         //Valor Total Fic por año
	                         $TotalNormativo=$FicNormativo['detalle_fic']['tasamensual_'.$fila];
	                         $TotalAnual=$ValorAnual['tasamensualobra_' . $fila]+$ValorAnual['tasamensualcosto_' . $fila];
	                         $TotalPresuntivo=($TotalAnual+$liquidacion["totalObra_".$fila]+ $liquidacion["totalCosto_".$fila]);
	                         //Aportes Fic 0.25% costo

	                         $TotalFicCosto=$ValorAnual['tasamensualcosto_' . $fila]+$liquidacion["totalCosto_".$fila];
	                         //Aportes Fic 1% obra
	                         $TotalFicObra=$ValorAnual['tasamensualobra_' . $fila]+$liquidacion["totalObra_".$fila];
	                         $TotalFic= $TotalNormativo+$TotalPresuntivo;
	                         //Totales
	                         $ValorTotalFic+=$TotalFic;
	                         $ValorTotalAportes+=$Aportes;
	                         $TotalNeto=$ValorTotalFic-$ValorTotalAportes;
	                         $ValorTotalIntereses+=$Intereses;
	                         $informacion = array('periodo_'.$fila=>$periodo,'ValorFicCosto_'.$fila=>$TotalFicCosto,'ValorFicObra_'.$fila=>$TotalFicObra,'ValorContratoCosto_' . $fila => $contratoCosto, 'ValorContratoObra_' . $fila => $contratoObra,'Intereses_'.$fila=>$Intereses, 'Aportes_'.$fila=>$Aportes,'TotalFic_'.$fila=>$TotalFic,);
	                         $resultado = array_merge($informacion, $resultado);
	                         endfor;

	                         $SaldoDeuda+= $TotalNeto+$ValorTotalIntereses;
	                    $this->data['resultado'] = $resultado;
	                    $this->data['cantidad']=$cant;
	                    $this->data['empresa']=$this->liquidaciones_model->getEmpresa($maestro['NIT_EMPRESA']);
	                    $this->data['info_usuario']= $this -> liquidaciones_model -> getInfoUsuarios($idusuario);

	                    //Guardar liquidación Fic

	                    $ValorTotalFic=$TotalFic+$Intereses;
	                    $liquidacion_fic=array('nitEmpresa'=>$maestro['NIT_EMPRESA'],'tipoLiquidacion'=>1,'fechaLiquidacion'=>$liquidacion['fechaLiquidacion']
	                    ,'valorInversion'=>$liquidacion['valorInversion'],'gastosFinanciacion'=>$liquidacion['gastosFinanciacion'],'valorLote'=>$liquidacion['valorLote']
	                    ,'indenmizacion'=>$liquidacion['indenmizacion'],'periodoInicial'=>$maestro ['PERIODO_INICIAL'],'periodoFinal'=>$maestro ['PERIODO_FINAL']
	                    ,'valorFic'=>$TotalFic,'interesesFic'=>$InteresesFic, 'valorTotalFic'=>$ValorTotalFic,'codFuncionario'=>$idusuario,'fiscalizacion'=>$maestro['COD_FISCALIZACION']
	                    ,'detalle_presuntiva'=> $resultado,'detalle_normativa'=>$FicNormativo,'maestro'=>$maestro,'id'=>$maestro['NRO_EXPEDIENTE']);
	                    $liquidacion_previa=$this->liquidaciones_model->consultarLiquidacionFic($maestro['NRO_EXPEDIENTE']);
	                    $datestring = "%d/%m/%Y";
	                    $fecha_actual = mdate($datestring);
	                    $this -> data['fecha'] = $fecha_actual;
	                  $cargarfic=$this->liquidaciones_model->CargarLiquidacionFic($liquidacion_fic,$liquidacion_previa);

	               if($cargarfic == TRUE):

			if($liquidacion_previa == 0):

				trazar($cod_tipoGestion, $cod_respuesta, $maestro['COD_FISCALIZACION'], $maestro['NIT_EMPRESA'], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_fic_calculada);

			else:

				trazar($cod_tipoGestion_recalculada, $cod_respuesta_recalculada, $maestro['COD_FISCALIZACION'], $maestro['NIT_EMPRESA'], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_fic_recalculada);

			endif;

		endif;

	                       $datos_liquidacion=array('codigoConcepto'=>2,'nitEmpresa'=>$maestro['NIT_EMPRESA'],'fechaInicio'=>$maestro['PERIODO_INICIAL']
	                       ,'fechaFin'=>$maestro['PERIODO_FINAL'],'fechaLiquidacion'=>$fecha_actual,'fechaVencimiento'=>$liquidacion['fechaLiquidacion']
	                       ,'totalLiquidado'=>$SaldoDeuda,'totalInteres'=>$ValorTotalIntereses,'saldoDeuda'=>$SaldoDeuda,'totalCapital'=>$TotalNeto
	                       ,'tipoProceso'=>5,'codigoFiscalizacion'=>$maestro['COD_FISCALIZACION'],'numeroLiquidacion'=>$maestro['NRO_EXPEDIENTE']);
                        $cargarLiquidacion=$this->liquidaciones_model->cargarLiquidacion($datos_liquidacion,  $liquidacion_previa);


                        $datos_cabecera = $this -> liquidaciones_model -> consultarCabeceraLiquidacion($maestro['COD_FISCALIZACION'], $idusuario);
	                    $this -> data['datos_cabecera']=$datos_cabecera;
                        $this -> data['calculos'] = $calculos;

	                   $this -> template -> set('title', 'Liquidación Fondo Nacional de Formación Profesional de la Industria de la Construcción (FIC)');
	                   $this -> template -> load($this->template_file, 'liquidaciones/generar_liquidacion_fic', $this->data);
	                else:
	                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
	                    redirect(site_url() . '/inicio');
	                endif;
	            else:
	                redirect(site_url() . '/auth/login');
	            endif;
	        } catch (Exception $e) {
	            $this->data['titulo'] = 'Liquidación Fondo Nacional de Formación Profesional de la Industria de la Construcción (FIC)';
	            $this->data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecuciÃ³n : ' . $e->getMessage() . '</div>';
	            $this->template->set('title', 'Errores en liquidación');
	            $this->template->load($this->template_file, 'liquidaciones/error', $this->data);
	        }
	    }

	private function cantidad_meses($contr_anno_inicial,$contr_anno_final,$contr_mes_inicial,$contr_mes_final)
	{

	                    $meses_inicio=0;
	                    $meses_fin=0;
	                    $otros_meses=0;
	                       for ($j = $contr_anno_inicial; $j <= $contr_anno_final; $j++){
	                         if ($j == $contr_anno_inicial){//primer año
	                             for ($i = $contr_mes_inicial; $i<= 12; $i++){
	                                 $meses_inicio =$meses_inicio + 1;
	                                   }
	                         }
	                         else if ($j == $contr_anno_final){//ultimo año
	                               for ($i = 1; $i <= $contr_mes_final; $i++){
	                               $meses_fin = $meses_fin  + 1;
	                           }
	                         }
	                         else{//otros años
	                              for ($i = 1; $i <= 12; $i++){
	                               $otros_meses = $otros_meses+1;
	                                 }
	                       }
	                     }
	                    $meses=$meses_inicio+$meses_fin+$otros_meses;
	                  return $meses;
	}
	         /***
	         //Funcion para calcular Fic Presuntivo Anual para Todo Costo y Mano de Obra
	         ***/
	private function ValorAnual($liquidacion,$maestro)
	{
	                                        $datos_fecha_inicial = explode("/",$maestro ['PERIODO_INICIAL']);
						$dia_fecha_inicial = (int)$datos_fecha_inicial[0];
						$mes_fecha_inicial = (int)$datos_fecha_inicial[1];
						$anno_fecha_inicial = (int)$datos_fecha_inicial[2];

						//separación de fechas periodo final
						$datos_fecha_final = explode("/",$maestro ['PERIODO_FINAL']);
						$dia_fecha_final = (int)$datos_fecha_final[0];
						$mes_fecha_final = (int)$datos_fecha_final[1];
						$anno_fecha_final = (int)$datos_fecha_final[2];
	                                        $fechaLiquidacion=$liquidacion['fechaLiquidacion'];
						$detalle = array();
	                                        $intereses=0;
	                                        for($fila = $anno_fecha_final; $fila >= $anno_fecha_inicial; $fila--):
	                                        $liquidacion[$fila."_val_costo"]=preg_replace( "/[\.]/", "",   $liquidacion[$fila."_val_costo"]);
	                                        $liquidacion[$fila."_val_obra"]=preg_replace( "/[\.]/", "",   $liquidacion[$fila."_val_obra"]);
	                                        $valor_anual_costo  =   $liquidacion[$fila."_val_costo"];
	                                        $valor_anual_obra   =    $liquidacion[$fila."_val_obra"];
	                                        if($valor_anual_costo!=0 ||  $valor_anual_obra!=0):
							if($fila == $anno_fecha_final)://ultimo Año
	                                                    /***VALOR ANUAL*******/
	                                                            $meses_fin = 0;
	                                                            for ($i = 0; $i < $mes_fecha_final; $i++):  //Calculo cuantos meses corresponden al último año
	                                                                $meses_fin +=1;
	                                                            endfor;
	                                                            $MensualPresCosto = $valor_anual_costo / $meses_fin;
	                                                            $tasaMensualPresCosto = $MensualPresCosto * 0.0025;
	                                                            $MensualPresObra = $valor_anual_obra / $meses_fin;
	                                                            $tasaMensualPresObra = $MensualPresObra * 0.01;
	                                                            $aporteMensualAcumulado=0;
	                                                            $deudaAcumulada=0;
	                                                            $TotalIntereses=0;
	                                                            $TasaMensualAcumulada=0;
	                                                            $TasaMensualAcumuladaObra=0;
	                                                            $TasaMensualAcumuladaCosto=0;
	                                                            $TotalPresCosto=0;
	                                                            $TotalPresObra=0;
	                                                            $mes_siguiente=$meses_fin+1;
								for($fila_int = $mes_fecha_final; $fila_int >0; $fila_int --)://mes a mes
	                                                           	if($fila_int < 10):
										$periodo = $fila.'-0'.$fila_int;
									else:
										$periodo = $fila.'-'.$fila_int;
									endif;
	                                                                    $tipoFic="ANUAL";
	                                                                    $AporteMensualPres= $this -> liquidaciones_model -> getAporte_Fic($maestro['NIT_EMPRESA'], 2, 62, $periodo,$tipoFic);
	                                                                    $deuda = ($tasaMensualPresCosto+$tasaMensualPresObra) - $AporteMensualPres ['VALOR_PAGADO'];
	                                                                    $TasaMensualAcumulada+=($tasaMensualPresCosto+$tasaMensualPresObra);
	                                                                    $aporteMensualAcumulado  +=  $AporteMensualPres['VALOR_PAGADO'];
	                                                                    $deuda=$tasaMensualPresCosto+$tasaMensualPresObra;
	                                                                    $TasaMensualAcumuladaObra+=$tasaMensualPresObra;
	                                                                    $TasaMensualAcumuladaCosto+=$tasaMensualPresCosto;
	                                                                    $TotalPresObra+= $MensualPresObra;
	                                                                    $TotalPresCosto+= $MensualPresCosto;
	                                                                    $deudaAcumulada+=$deuda;
	                                                                    $TotalIntereses+=$intereses;
	                                                         endfor;

	                                                         $informacion = array('baseCosto_'.$fila=> $TotalPresCosto,'baseObra_'.$fila=>$TotalPresObra,'tasamensualobra_'.$fila=>$TasaMensualAcumuladaObra,'tasamensualcosto_'.$fila=>$TasaMensualAcumuladaCosto,'tasamensual_'.$fila=>$TasaMensualAcumulada,'aportes_'.$fila => $aporteMensualAcumulado , 'deuda_'.$fila =>$deudaAcumulada,'intereses_'.$fila => $TotalIntereses);
	                                                         $detalle = array_merge($detalle, $informacion);
	//                                                     /********************Otros Años***********************/
	                                                      elseif($fila != $anno_fecha_inicial && $fila != $anno_fecha_final): //Otros Años
	                                                      $MensualPresCosto = $valor_anual_costo /12;
	                                                      $tasaMensualPresCosto = $MensualPresCosto * 0.0025;
	                                                      $MensualPresObra = $valor_anual_obra / 12;
	                                                      $tasaMensualPresObra = $MensualPresObra * 0.01;
	                                                             $aporteMensualAcumulado=0;
	                                                             $deudaAcumulada=0;
	                                                             $TotalIntereses=0;
	                                                             $mes=0;
	                                                             $TasaMensualAcumulada=0;
	                                                             $TasaMensualAcumuladaObra=0;
	                                                             $TasaMensualAcumuladaCosto=0;
	                                                      $TotalPresCosto=0;
	                                                      $TotalPresObra=0;
								for($fila_int = 12; $fila_int >0; $fila_int--)://mes a mes
	                                                                    if($fila_int < 10):
	                                                                            $periodo = $fila.'-0'.$fila_int;
	                                                                    else:
	                                                                            $periodo = $fila.'-'.$fila_int;
	                                                                    endif;
	                                                                $tipoFic="ANUAL";
	                                                                $AporteMensualPres= $this -> liquidaciones_model ->getAporte_Fic($maestro['NIT_EMPRESA'], 2, 62, $periodo,$tipoFic);
	                                                                $deuda = ($tasaMensualPresCosto+$tasaMensualPresObra) - $AporteMensualPres ['VALOR_PAGADO'];
	                                                                $TasaMensualAcumulada+=$tasaMensualPresCosto+$tasaMensualPresObra;
	                                                                $aporteMensualAcumulado  +=  $AporteMensualPres['VALOR_PAGADO'];
	                                                                $TasaMensualAcumuladaObra+=$tasaMensualPresObra;
	                                                                $TasaMensualAcumuladaCosto+=$tasaMensualPresCosto;
	                                                                $TotalPresCosto+=$MensualPresCosto;
	                                                                $TotalPresObra+= $MensualPresObra;
	                                                                    $mes++; //Calculo Intereses
	                                                                    $deudaAcumulada+=$deuda;
	                                                                    $TotalIntereses+=$intereses;
	                                                         endfor;
	                                                         $informacion = array('baseCosto_'.$fila=> $TotalPresCosto,'baseObra_'.$fila=>$TotalPresObra,'tasamensualobra_'.$fila=>$TasaMensualAcumuladaObra,'tasamensualcosto_'.$fila=>$TasaMensualAcumuladaCosto,'tasamensual_'.$fila=>$TasaMensualAcumulada,'aportes_'.$fila => $aporteMensualAcumulado , 'deuda_'.$fila =>$deudaAcumulada,'intereses_'.$fila => $TotalIntereses);
	                                                         $detalle = array_merge($detalle, $informacion);
	                                                      elseif ($fila == $anno_fecha_inicial): //primer Año
	                                                             $meses_fin = 0;
	                                                             for ($i = 0; $i < $mes_fecha_inicial; $i++):   //Calculo cuantos meses corresponden al primer año
	                                                                 $meses_fin +=1;
	                                                             endfor;
	                                                             $MensualPresCosto = $valor_anual_costo / $meses_fin;
	                                                             $tasaMensualPresCosto = $MensualPresCosto * 0.0025;
	                                                             $MensualPresObra = $valor_anual_obra / $meses_fin;
	                                                             $tasaMensualPresObra = $MensualPresObra * 0.01;
	                                                      $aporteMensualAcumulado = 0;
	                                                             $deudaAcumulada=0;
	                                                             $TotalIntereses=0;
	                                                      $mes = 0;
	                                                      $TasaMensualAcumulada=0;
	                                                      $TasaMensualAcumuladaObra=0;
	                                                      $TasaMensualAcumuladaCosto=0;
	                                                             $TotalPresCosto=0;
	                                                             $TotalPresObra=0;
	                                                              $tipoFic="ANUAL";
	                                                             for($fila_int=0; $fila_int<$mes_fecha_inicial; $fila_int ++)://mes a mes
									if($fila_int < 10):
										$periodo = $fila.'-0'.$fila_int;
									else:
										$periodo = $fila.'-'.$fila_int;
									endif;
	                                                                $AporteMensualPres= $this -> liquidaciones_model -> getAporte_Fic($maestro['NIT_EMPRESA'], 2, 62, $periodo,$tipoFic);
	                                                                $deuda = ($tasaMensualPresCosto+$tasaMensualPresObra) - $AporteMensualPres ['VALOR_PAGADO'];
	                                                                $TasaMensualAcumulada+=$tasaMensualPresCosto+$tasaMensualPresObra;
	                                                                $aporteMensualAcumulado  +=  $AporteMensualPres['VALOR_PAGADO'];
	                                                                $TasaMensualAcumuladaObra+=$tasaMensualPresObra;
	                                                                $TasaMensualAcumuladaCosto+=$tasaMensualPresCosto;
	                                                                $TotalPresCosto+=$MensualPresCosto;
	                                                                $TotalPresObra+= $MensualPresObra;
	                                                                $mes++; //Calculo Intereses
	                                                                    if ($deuda <= 0 )://se encontro aporte
	                                                                            $intereses = 0;
	                                                                    else:
	                                                                            $intereses = $calculo_mensual = calcularMesesCorrientes($fechaLiquidacion, $deuda, $fila_int, $fila);
                                                                            $linea = calcularInteresesFicDebug($fechaLiquidacion, $deuda, $fila_int, $fila, $AporteAnualPres);
                                                                            $calculos = array_merge($calculos, $linea);
	                                                                    endif;
	                                                                    $deudaAcumulada+=$deuda;
	                                                                    $TotalIntereses+=$intereses;
	                                                         endfor;
	                                                         $informacion = array('baseCosto_'.$fila=> $TotalPresCosto,'baseObra_'.$fila=>$TotalPresObra,'tasamensualobra_'.$fila=>$TasaMensualAcumuladaObra,'tasamensualcosto_'.$fila=>$TasaMensualAcumuladaCosto,'tasamensual_'.$fila=>$TasaMensualAcumulada,'aportes_'.$fila => $aporteMensualAcumulado , 'deuda_'.$fila =>$deudaAcumulada,'intereses_'.$fila  => $TotalIntereses);
	                                                         $detalle = array_merge($detalle, $informacion);
							endif;
	                                                else:
	                                               $informacion = array('baseCosto_'.$fila=> 0,'baseObra_'.$fila=>0,'tasamensualobra_'.$fila=>0,'tasamensualcosto_'.$fila=>0,'tasamensual_'.$fila=>0,'aportes_'.$fila => 0, 'deuda_'.$fila =>0,'intereses_'.$fila  => 0);
	                                                         $detalle = array_merge($detalle, $informacion);
							endif;
	endfor;

	return ($detalle);
	}

	private function ContratoCosto($cantidadContr,$liquidacion,$maestro)
	{
	      $fechaLiquidacion=$liquidacion['fechaLiquidacion'];
	      $detalle = array();
	      $SumValorContrato=0;
	      $Valor_Contratos=array();
        $calculos = array();

	      for ($contrato = 1; $contrato < $cantidadContr; $contrato++):
	            $contr_fecha_inicial = explode("/", $liquidacion['fecha_inicio_costo'][$contrato]);
	            $contr_dia_inicial = (int) $contr_fecha_inicial[0];
	            $contr_mes_inicial = (int) $contr_fecha_inicial[1];
	            $contr_anno_inicial = (int) $contr_fecha_inicial[2];
	            //Periodo Final
	            $contr_fecha_final = explode("/", $liquidacion['fecha_fin_costo'][$contrato]);
	            $contr_dia_final = (int) $contr_fecha_final[0];
	            $contr_mes_final = (int) $contr_fecha_final[1];
	            $contr_anno_final = (int) $contr_fecha_final[2];
	            $tipoFic = 'CONTRATO';
	            $aporteMensualAcumulado = 0;
	            $SumValorContrato=$liquidacion['valor_contr_costo'][$contrato];
	            //Recorro todos los años del periodo del contrato
	            for ($c_anno = $contr_anno_final; $c_anno >= $contr_anno_inicial; $c_anno--):
	                //Realizo el calculo para el primer año
	                 $TasaMensualAcumulada=0;
	                if (($contr_anno_inicial - $contr_anno_inicial ) == 0):
	                    //Cuando el contrato corresponde a un mismo año
	                    if (($contr_mes_final - $contr_mes_inicial) == 0):   //Cuando corresponde al mismo mes
	                        if (($contr_dia_final - $contr_dia_inicial) < 31):
	                            $tasaMensualPresAcumulado = 0;
	                            $TotalIntereses = 0;
	                            $deudaAcumulada = 0;
	                            $valor_mensual = $liquidacion['valor_contr_costo'][$contrato];
	                            $tasaMensual = $valor_mensual * 0.0025;
	                            if ($contr_mes_final < 10):
	                                $periodo = $contr_anno_final . '-0' . $contr_mes_final;
	                            else:
	                                $periodo = $contr_anno_final . '-' . $contr_mes_final;
	                            endif;
	                            $pagoOportuno = 0;
	                            $aporteMensual = $this->liquidaciones_model->getAporte_Fic($maestro['NIT_EMPRESA'], 2, 62, $periodo, $tipoFic,$liquidacion['contrato']);
	                            echo $aporteMensual;
	                            $deuda = $tasaMensual - $aporteMensual['VALOR_PAGADO'];
	                            $TasaMensualAcumulada += $tasaMensual;
	                            $aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];
	                            $deudaAcumulada+=$deuda;
	                            if ($deuda <= 0)://se encontro aporte
	                                $intereses = 0;
	                            else:
	                                $intereses = $calculo_mensual = calcularMesesCorrientes($fechaLiquidacion, $deuda, $contr_mes_final, $c_anno);
                                    $linea = calcularInteresesFicDebug($fechaLiquidacion, $deuda, $contr_mes_final, $c_anno, $aporteMensual);
                                    $calculos = array_merge($calculos, $linea);
	                            endif;
	                            $TotalIntereses+=$intereses;
	                        endif;
	                        $informacion = array('tasamensual_'.$c_anno=>$TasaMensualAcumulada,'aportes_' . $c_anno => $aporteMensualAcumulado, 'deuda_' . $c_anno => $deudaAcumulada, 'intereses_' . $c_anno => $TotalIntereses);
	                        $detalle = array_merge($detalle, $informacion);
	                    elseif (($contr_mes_final - $contr_mes_inicial) > 0)://Cuando es un mes diferente
	                        $valor_mensual = $liquidacion['valor_contr_costo'][$contrato] / (($contr_mes_final - $contr_mes_inicial) + 1);
	                        $deudaAcumulada = 0;
	                        $TotalIntereses = 0;
	                        for ($i = $contr_mes_inicial; $i <= $contr_mes_final; $i++):
	                            $tasaMensual = $valor_mensual * 0.0025;
	                            if ($contr_mes_final < 10):
	                                $periodo = $contr_anno_inicial . '-0' . $i;
	                            else:
	                                $periodo = $contr_anno_final . '-' . $i;
	                            endif;
	                            $aporteMensual = $this->liquidaciones_model->getAporte_Fic($maestro['NIT_EMPRESA'], 2, 62, $periodo, $tipoFic,$liquidacion['contrato']);
	                            $pagoOportuno = 0;
	                            $deuda =  $tasaMensual  - $aporteMensual['VALOR_PAGADO'];
	                            $TasaMensualAcumulada+= $tasaMensual;
	                            $aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];
	                            $deudaAcumulada+=$deuda;
	                            if ($deuda <= 0)://se encontro aporte
	                                $intereses = 0;
	                            else:
	                                $intereses = 0;
	//
	                                $intereses =  calcularMesesCorrientes($fechaLiquidacion, $deuda, $i, $c_anno);
                                    $linea = calcularInteresesFicDebug($fechaLiquidacion, $deuda, $i, $c_anno, $aporteMensual);
                                    $calculos = array_merge($calculos, $linea);
	                            endif;
	                            $TotalIntereses+=$intereses;
	                        endfor;
	                        $informacion = array('tasamensual_'.$c_anno=>$TasaMensualAcumulada,'aportes_' . $c_anno => $aporteMensualAcumulado, 'deuda_' . $c_anno => $deudaAcumulada, 'intereses_' . $c_anno => $TotalIntereses);
	                        $detalle = array_merge($detalle, $informacion);
	                    endif;
	                endif;
	                if ($contr_anno_final - $contr_anno_inicial > 0) ://Cuando el contrato corresponde a varios años
	                    $meses=$this->cantidad_meses($contr_anno_inicial,$contr_anno_final,$contr_mes_inicial,$contr_mes_final);
	                    $valor_mensual = $liquidacion['valor_contr_costo'][$contrato] / ($meses);
	                    $tasaMensualPresAcumulado = 0;
	                    $TotalIntereses = 0;
	                    $deudaAcumulada = 0;
	                    $aporteMensualAcumulado = 0;
	                   // $TasaMensualAcumulada=0;
	                    if ($c_anno == $contr_anno_inicial)://primer año

	                        for ($i = $contr_mes_inicial; $i <= 12; $i++):
	                            $tasaMensual = $valor_mensual * 0.0025;
	                            if ($contr_mes_final < 10):
	                                $periodo = $contr_anno_inicial . '-0' . $i;
	                            else:
	                                $periodo = $contr_anno_final . '-' . $i;
	                            endif;
	                            $aporteMensual = $this->liquidaciones_model->getAporte_Fic($maestro['NIT_EMPRESA'], 2, 62, $periodo, $tipoFic,$liquidacion['contrato']);
	                            $pagoOportuno = 0;
	                            $deuda =  $tasaMensual  - $aporteMensual['VALOR_PAGADO'];
	                            $TasaMensualAcumulada+=$tasaMensual;
	                            $aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];
	                            $deudaAcumulada+=$deuda;
	                            if ($deuda <= 0)://se encontro aporte
	                                $intereses = 0;
	                            else:
	                                $intereses =calcularMesesCorrientes($fechaLiquidacion, $deuda, $i, $c_anno);
                                    $linea = calcularInteresesFicDebug($fechaLiquidacion, $deuda, $i, $c_anno, $aporteMensual);
                                    $calculos = array_merge($calculos, $linea);
	                            endif;
	                            $TotalIntereses+=$intereses;
	                        endfor;
	                        $informacion = array('tasamensual_'.$c_anno=>$TasaMensualAcumulada,'aportes_' . $c_anno => $aporteMensualAcumulado, 'deuda_' . $c_anno => $deudaAcumulada, 'intereses_' . $c_anno => $TotalIntereses);
	                        $detalle = array_merge($detalle, $informacion);
	                    endif;
	                    if (($c_anno != $contr_anno_inicial) && ($c_anno != $contr_anno_final))://Otros años
	                        $TotalIntereses = 0;
	                        $deudaAcumulada = 0;
	                        $aporteMensualAcumulado = 0;
	                        for ($i = 1; $i <= 12; $i++):
	                            $tasaMensual = $valor_mensual * 0.0025;
	                            if ($contr_mes_final < 10):
	                                $periodo = $contr_anno_inicial . '-0' . $i;
	                            else:
	                                $periodo = $contr_anno_final . '-' . $i;
	                            endif;
	                            $aporteMensual = $this->liquidaciones_model->getAporte_Fic($maestro['NIT_EMPRESA'], 2, 62, $periodo, $tipoFic,$liquidacion['contrato']);
	                            $pagoOportuno = 0;
	                            $deuda =  $tasaMensual  - $aporteMensual['VALOR_PAGADO'];
	                            $TasaMensualAcumulada += $tasaMensual;
	                            $aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];
	                            $deudaAcumulada+=$deuda;
	                            if ($deuda <= 0)://se encontro aporte
	                                $intereses = 0;
	                            else:
	                                $intereses = $calculo_mensual = calcularMesesCorrientes($fechaLiquidacion, $deuda, $i, $c_anno);
                                    $linea = calcularInteresesFicDebug($fechaLiquidacion, $deuda, $i, $c_anno, $aporteMensual);
                                    $calculos = array_merge($calculos, $linea);
	                            endif;
	                            $TotalIntereses+=$intereses;
	                        endfor;
	                        $informacion = array('tasamensual_'.$c_anno=>$TasaMensualAcumulada,'aportes_' . $c_anno => $aporteMensualAcumulado, 'deuda_' . $c_anno => $deudaAcumulada, 'intereses_' . $c_anno => $TotalIntereses);
	                        $detalle = array_merge($detalle, $informacion);
	                    endif;
	                    if ($c_anno == $contr_anno_final)://ultimo año
	                        $TotalIntereses = 0;
	                        $deudaAcumulada = 0;
	                        $aporteMensualAcumulado = 0;
	                        $TasaMensualAcumulada=0;
	                        for ($i = 1; $i <= $contr_mes_final; $i++):
	                            $tasaMensual = $valor_mensual * 0.0025;
	                            if ($contr_mes_final < 10):
	                                $periodo = $contr_anno_inicial . '-0' . $i;
	                            else:
	                                $periodo = $contr_anno_final . '-' . $i;
	                            endif;
	                            $aporteMensual = $this->liquidaciones_model->getAporte_Fic($maestro['NIT_EMPRESA'], 2, 62, $periodo, $tipoFic,$liquidacion['contrato']);
	                            $pagoOportuno = 0;
	                            $deuda =  $tasaMensual  - $aporteMensual['VALOR_PAGADO'];
	                            $TasaMensualAcumulada+= $tasaMensual;
	                            $aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];
	                            $deudaAcumulada+=$deuda;
	                            if ($deuda <= 0)://se encontro aporte
	                                $intereses = 0;
	                            else:
	                                $intereses = $calculo_mensual = calcularMesesCorrientes($fechaLiquidacion, $deuda, $i, $c_anno);
                                    $linea = calcularInteresesFicDebug($fechaLiquidacion, $deuda, $i, $c_anno, $aporteMensual);
                                    $calculos = array_merge($calculos, $linea);
	                            endif;
	                            $TotalIntereses+=$intereses;
	                        endfor;
	                        $informacion = array('tasamensual_'.$c_anno=>$TasaMensualAcumulada,'aportes_' . $c_anno => $aporteMensualAcumulado, 'deuda_' . $c_anno => $deudaAcumulada, 'intereses_' . $c_anno => $TotalIntereses);
	                        $detalle = array_merge($detalle, $informacion);
	                    endif;
	                endif;
	            endfor;
	             $Valor_Contratos=array('valor_contratos_'.$c_anno=>$SumValorContrato);
	       endfor;
    $detalle = array_merge($detalle,$Valor_Contratos);
        $detalle_calculos = array('detalle' => $detalle, 'calculos' => $calculos);
    return $detalle_calculos;
    }

    private function FicNormativo($liquidacion, $maestro)
    {
        //Recorro los años para la fecha de cada contrato
        //Periodo Inicial
        $datos_fecha_inicial = explode("/",$maestro ['PERIODO_INICIAL']);
        $dia_fecha_inicial = (int)$datos_fecha_inicial[0];
        $mes_fecha_inicial = (int)$datos_fecha_inicial[1];
        $anno_fecha_inicial = (int)$datos_fecha_inicial[2];
        //separación de fechas periodo final
        $datos_fecha_final = explode("/",$maestro ['PERIODO_FINAL']);
        $dia_fecha_final = (int)$datos_fecha_final[0];
        $mes_fecha_final = (int)$datos_fecha_final[1];
        $anno_fecha_final = (int)$datos_fecha_final[2];
        $fechaLiquidacion=$liquidacion['fechaLiquidacion'];
        $detalle = array();
        $cant_empl = 0;
        $empleados = array();
        $totales_anuales = array();
        $calculos = array();
	                                          for($fila = $anno_fecha_final; $fila >= $anno_fecha_inicial; $fila--):
	                                              //Calculo Cantidad de Empleados
	                                              for($emp_mes=1; $emp_mes<=12; $emp_mes++):
	                                                   $cant_empl_mes= $liquidacion[$fila."_".$emp_mes];
	                                                   $cant_empl= $cant_empl+$cant_empl_mes;
	                                              endfor;
	                                                 $info=array('empleados_'.$fila=>$cant_empl);//Cantidad de empleados del año
	                                                 $empleados=  array_merge($empleados, $info);//Almacena las cantidades de empleados por cada año
	                                                 $cant_empl=0;
	                                                 $liquidaciontotal=  preg_replace( "/[\.]/", "", $liquidacion['total_'.$fila]);
	                                                 $valor_fila=array('valor_total_'.$fila=>$liquidaciontotal);//Total por año
	                                                 $totales_anuales=array_merge($totales_anuales,$valor_fila);//Almacena totales por cada año
	                                                 if($fila == $anno_fecha_final)://ultimo Año
	                $tasaMensualPresAcumulado = 0; //
	                $TotalIntereses = 0; //total de intereses para ese año
	                $deudaAcumulada = 0; //total de deuda para el año
	                                                     $TasaMensualAcumulada=0;
	                                                     $aporteMensualAcumulado = 0;
								for($fila_int = $mes_fecha_final; $fila_int >0; $fila_int --)://mes a mes
	                                                           	if($fila_int < 10):
										$periodo = $fila.'-0'.$fila_int;
									else:
										$periodo = $fila.'-'.$fila_int;
									endif;

								    $mes = 0;
	                                                            $tasaMensual = $this -> input -> post($fila."_".$fila_int."_data");//Tasa mensual normativa
	                                                            $tasaMensual = preg_replace( "/[\.]/", "", $tasaMensual );

	                                                            if($tasaMensual):
	                                                                $aporteMensual = $this -> liquidaciones_model -> getAporte_mes($maestro['NIT_EMPRESA'], 2, 17, $periodo);
	                                                                $pagoOportuno = 0;
	                                                                $deuda =  $tasaMensual  - $aporteMensual['VALOR_PAGADO'];
	                                                                $TasaMensualAcumulada+=$tasaMensual;
	                                                                $deudaAcumulada+=$deuda;
	                                                                $aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];
	                                                                $mes++;
	                                                                    if ($deuda <= 0 )://se encontro aporte
	                                                                            $intereses = 0;
	                                                                    else:
                                                                            $intereses = $calculo_mensual = calcularMesesCorrientes($fechaLiquidacion, $deuda, $fila_int, $fila);
                                                                            $linea = calcularInteresesFicDebug($fechaLiquidacion, $deuda, $fila_int, $fila, $aporteMensual);
                                                                            $calculos = array_merge($calculos, $linea);
	                                                                    endif;
	                                                                 $TotalIntereses+=$intereses;
	                                                             endif;
	                                                         endfor;
	                                                         $informacion = array('tasamensual_'.$fila=>$TasaMensualAcumulada,'aportes_'.$fila => $aporteMensualAcumulado, 'deuda_'.$fila=>$deudaAcumulada,'intereses_'.$fila => $TotalIntereses);
								 $detalle = array_merge($detalle, $informacion);
	                                                        elseif ($fila == $anno_fecha_inicial): //primer Año
	                                                         $TotalIntereses=0;
	                                                         $deudaAcumulada=0;
	                                                         $tasaMensualPresAcumulado=0;
	                                                         $TasaMensualAcumulada=0;
	                                                             $intereses = 0;
	                                                              $aporteMensualAcumulado = 0;
								    $mes = 0;
	                                                             for($fila_int = 12; $fila_int >= $mes_fecha_inicial; $fila_int --)://mes a mes
									if($fila_int < 10):
										$periodo = $fila.'-0'.$fila_int;
									else:
										$periodo = $fila.'-'.$fila_int;
									endif;
	                                                                $tasaMensual = $this -> input -> post($fila."_".$fila_int."_data");
	                                                                  $tasaMensual = preg_replace( "/[\.]/", "", $tasaMensual );
	                                                                        $aporteMensual = $this -> liquidaciones_model -> getAporte_mes($maestro['NIT_EMPRESA'], 2, 17, $periodo);
	                                                                        $pagoOportuno = 0; //$this -> liquidaciones_model -> getPagoPuntual();
	                                                                        $deuda = $tasaMensual - $aporteMensual['VALOR_PAGADO'];
	                                                                        $TasaMensualAcumulada+=$tasaMensual;
	                                                                        $deudaAcumulada+=$deuda;
	                                                                        $aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];
	                                                                        $mes++;
	                                                                        if ($deuda <= 0 )://se encontro aporte
	                                                                        $intereses = 0;
	                                                                        else:
	                                                                            $intereses =  $calculo_mensual = calcularMesesCorrientes($fechaLiquidacion, $deuda, $fila_int, $fila);
                                                                                $linea = calcularInteresesFicDebug($fechaLiquidacion, $deuda, $fila_int, $fila, $aporteMensual);
                                                                                $calculos = array_merge($calculos, $linea);
	                                                                        endif;
	                                                                        $TotalIntereses+=$intereses;
								endfor;
	                                                         $informacion = array('tasamensual_'.$fila=>$TasaMensualAcumulada,'aportes_'.$fila => $aporteMensualAcumulado, 'deuda_'.$fila=>$deudaAcumulada,'intereses_'.$fila => $TotalIntereses);
								$detalle = array_merge($detalle, $informacion);
	//                                                     /********************Otros Años***********************/
	                                                        else: //Otros Años
	                                                      $aporteMensualAcumulado = 0;
	                                                      $mes = 0;
	                                                      $intereses = 0;
	                                                        $tasaMensualPresAcumulado=0;
	                                                        $TotalIntereses=0;
	                                                        $deudaAcumulada=0;
	                                                        $TasaMensualAcumulada=0;
								for($fila_int = 12; $fila_int >0; $fila_int--)://mes a mes
									if($fila_int < 10):
										$periodo = $fila.'-0'.$fila_int;
									else:
										$periodo = $fila.'-'.$fila_int;
									endif;
	                                                                $fechaLiquidacion=$liquidacion['fechaLiquidacion'];
	                                                                $tasaMensual = $this -> input -> post($fila."_".$fila_int."_data");
	                                                                  $tasaMensual = preg_replace( "/[\.]/", "", $tasaMensual );
	                                                                 if($tasaMensual):
	                                                                        $aporteMensual = $this -> liquidaciones_model -> getAporte_mes($maestro['NIT_EMPRESA'], 2, 17, $periodo);
	                                                                        $pagoOportuno = 0; //$this -> liquidaciones_model -> getPagoPuntual();
	                                                                        $deuda = $tasaMensual - $aporteMensual['VALOR_PAGADO'];
	                                                                        $TasaMensualAcumulada+=$tasaMensual ;
	                                                                        $deudaAcumulada+=$deuda;
	                                                                        $aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];
	                                                                        $mes++;
	                                                                       if ($deuda <= 0 )://se encontro aporte
	                                                                        $intereses = 0;
	                                                                        else:
	                                                                            $intereses =  $calculo_mensual = calcularMesesCorrientes($fechaLiquidacion, $deuda, $fila_int, $fila);
                                                                                $linea = calcularInteresesFicDebug($fechaLiquidacion, $deuda, $fila_int, $fila, $aporteMensual);
                                                                                $calculos = array_merge($calculos, $linea);
	                                                                        endif;
	                                                                       $TotalIntereses+=$intereses;
	                                                                    endif;
								endfor;
								     $informacion = array('tasamensual_'.$fila=>$TasaMensualAcumulada,'aportes_'.$fila => $aporteMensualAcumulado, 'deuda_'.$fila=>$deudaAcumulada,'intereses_'.$fila => $TotalIntereses);
								     $detalle = array_merge($detalle, $informacion);
							endif;
	                                        endfor;
    $informacionFic = array('empleados' => $empleados,'valor_anno' => $totales_anuales,'detalle_fic' => $detalle, 'calculos' => $calculos);
    return ($informacionFic);
    }

	private function ContratoObra($cantidadContr,$liquidacion,$maestro)
	{
	      $fechaLiquidacion=$liquidacion['fechaLiquidacion'];
	      $detalle = array();
        $calculos = array();
	      $SumValorContrato=0;
	      $Valor_Contratos=array();
	      for ($contrato=1; $contrato<$cantidadContr; $contrato++):
	            $contr_fecha_inicial = explode("/", $liquidacion['fecha_inicio_obra'][$contrato]);
	            $contr_dia_inicial = (int) $contr_fecha_inicial[0];
	            $contr_mes_inicial = (int) $contr_fecha_inicial[1];
	            $contr_anno_inicial = (int) $contr_fecha_inicial[2];
	            //Periodo Final
	            $contr_fecha_final = explode("/", $liquidacion['fecha_fin_obra'][$contrato]);
	            $contr_dia_final = (int) $contr_fecha_final[0];
	            $contr_mes_final = (int) $contr_fecha_final[1];
	            $contr_anno_final = (int) $contr_fecha_final[2];
	            $tipoFic = 'CONTRATO';
	            $aporteMensualAcumulado = 0;
	            $SumValorContrato=$liquidacion['valor_contr_obra'][$contrato];
	           // echo  $SumValorContrato; die();
	            //Recorro todos los años del periodo del contrato
	            for ($c_anno = $contr_anno_final; $c_anno >= $contr_anno_inicial; $c_anno--):
	                //Realizo el calculo para el primer año
	                 $TasaMensualAcumulada=0;
	                if (($contr_anno_inicial - $contr_anno_inicial ) == 0):
	                    //Cuando el contrato corresponde a un mismo año
	                    if (($contr_mes_final - $contr_mes_inicial) == 0):   //Cuando corresponde al mismo mes
	                        if (($contr_dia_final - $contr_dia_inicial) < 31):
	                            $tasaMensualPresAcumulado = 0;
	                            $TotalIntereses = 0;
	                            $deudaAcumulada = 0;
	                            $valor_mensual = $liquidacion['valor_contr_obra'][$contrato];
	                            $tasaMensual = $valor_mensual * 0.0025;
	                            if ($contr_mes_final < 10):
	                                $periodo = $contr_anno_final . '-0' . $contr_mes_final;
	                            else:
	                                $periodo = $contr_anno_final . '-' . $contr_mes_final;
	                            endif;
	                            $pagoOportuno = 0;
	                            $aporteMensual = $this->liquidaciones_model->getAporte_Fic($maestro['NIT_EMPRESA'], 2, 62, $periodo, $tipoFic,$liquidacion['contrato']);
	                            $deuda = $tasaMensual - $aporteMensual['VALOR_PAGADO'];
	                            $TasaMensualAcumulada+=$tasaMensual;
	                            $aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];
	                            $deudaAcumulada+=$deuda;
	                            if ($deuda <= 0 )://se encontro aporte
	                                $intereses = 0;
	                            else:
	                                $intereses = $calculo_mensual = calcularMesesCorrientes($fechaLiquidacion, $deuda, $contr_mes_final, $c_anno);
                                    $linea = calcularInteresesFicDebug($fechaLiquidacion, $deuda, $contr_mes_final, $c_anno, $aporteMensual);
                                    $calculos = array_merge($calculos, $linea);
	                            endif;
	                            $TotalIntereses+=$intereses;
	                        endif;
	                        $informacion = array('tasamensual_'.$c_anno=>$TasaMensualAcumulada,'aportes_' . $c_anno => $aporteMensualAcumulado, 'deuda_' . $c_anno => $deudaAcumulada, 'intereses_' . $c_anno => $TotalIntereses);
	                        $detalle = array_merge($detalle, $informacion);
	                        elseif (($contr_mes_final - $contr_mes_inicial) > 0)://Cuando es un mes diferente
	                        $valor_mensual = $liquidacion['valor_contr_obra'][$contrato] / (($contr_mes_final - $contr_mes_inicial) + 1);
	                        $deudaAcumulada = 0;
	                        $TotalIntereses = 0;
	                      //  $TasaMensualAcumulada=0;
	                        for ($i = $contr_mes_inicial; $i <= $contr_mes_final; $i++):
	                            $tasaMensual = $valor_mensual * 0.0025;
	                            if ($contr_mes_final < 10):
	                                $periodo = $contr_anno_inicial . '-0' . $i;
	                            else:
	                                $periodo = $contr_anno_final . '-' . $i;
	                            endif;
	                            $aporteMensual = $this->liquidaciones_model->getAporte_Fic($maestro['NIT_EMPRESA'], 2, 62, $periodo, $tipoFic,$liquidacion['contratoObra']);
	                            $pagoOportuno = 0;
	                            $deuda =  $tasaMensual  - $aporteMensual['VALOR_PAGADO'];
	                            $TasaMensualAcumulada+=$tasaMensual;
	                            $aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];
	                            $deudaAcumulada+=$deuda;
	                            if ($deuda <= 0)://se encontro aporte
	                                                                        $intereses = 0;
	                                                                        else:
                                                                                $intereses = $calculo_mensual = calcularMesesCorrientes($fechaLiquidacion, $deuda, $i, $c_anno);
                                                                                $linea = calcularInteresesFicDebug($fechaLiquidacion, $deuda, $i, $c_anno, $aporteMensual);
                                                                                $calculos = array_merge($calculos, $linea);
	                            endif;
	                            $TotalIntereses+=$intereses;
	                        endfor;
	                        $informacion = array('tasamensual_'.$c_anno=>$TasaMensualAcumulada,'aportes_' . $c_anno => $aporteMensualAcumulado, 'deuda_' . $c_anno => $deudaAcumulada, 'intereses_' . $c_anno => $TotalIntereses);
	                        $detalle = array_merge($detalle, $informacion);
	                                                                        endif;
	                                                                    endif;
	                if ($contr_anno_final - $contr_anno_inicial > 0) ://Cuando el contrato corresponde a varios años
	                    $meses=$this->cantidad_meses($contr_anno_inicial,$contr_anno_final,$contr_mes_inicial,$contr_mes_final);
	                    $valor_mensual = $liquidacion['valor_contr_obra'][$contrato] / ($meses);
	                    $tasaMensualPresAcumulado = 0;
	                    $TotalIntereses = 0;
	                    $deudaAcumulada = 0;
	                    $aporteMensualAcumulado = 0;
	                   // $TasaMensualAcumulada=0;
	                    if ($c_anno == $contr_anno_inicial)://primer año
	                        for ($i = $contr_mes_inicial; $i <= 12; $i++):
	                            $tasaMensual = $valor_mensual * 0.0025;
	                            if ($contr_mes_final < 10):
	                                $periodo = $contr_anno_inicial . '-0' . $i;
	                            else:
	                                $periodo = $contr_anno_final . '-' . $i;
	                            endif;
	                            $aporteMensual = $this->liquidaciones_model->getAporte_Fic($maestro['NIT_EMPRESA'], 2, 62, $periodo, $tipoFic,$liquidacion['contratoObra']);
	                            $pagoOportuno = 0;
	                            $deuda =  $tasaMensual  - $aporteMensual['VALOR_PAGADO'];
	                            $TasaMensualAcumulada+=$tasaMensual;
	                            $aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];
	                            $deudaAcumulada+=$deuda;
	                            //echo $deuda;die();
	                            if ($deuda <= 0)://se encontro aporte
	                                $intereses = 0;
	                            else:
	                                $intereses = $calculo_mensual = calcularMesesCorrientes($fechaLiquidacion, $deuda, $i, $c_anno);
                                    $linea = calcularInteresesFicDebug($fechaLiquidacion, $deuda, $i, $c_anno, $aporteMensual);
                                    $calculos = array_merge($calculos, $linea);
	                            endif;
	                            $TotalIntereses+=$intereses;
	                        endfor;
	                        $informacion = array('tasamensual_'.$c_anno=>$TasaMensualAcumulada,'aportes_' . $c_anno => $aporteMensualAcumulado, 'deuda_' . $c_anno => $deudaAcumulada, 'intereses_' . $c_anno => $TotalIntereses);
								  $detalle = array_merge($detalle, $informacion);
	                         // echo "<pre>";print_r($detalle);echo "</pre>";
							endif;
	                    if (($c_anno != $contr_anno_inicial) && ($c_anno != $contr_anno_final))://Otros años
	                        $TotalIntereses = 0;
	                        $deudaAcumulada = 0;
	                        $aporteMensualAcumulado = 0;
	                       // $TasaMensualAcumulada=0;
	                        for ($i = 1; $i <= 12; $i++):
	                            $tasaMensual = $valor_mensual * 0.0025;
	                            if ($contr_mes_final < 10):
	                                $periodo = $contr_anno_inicial . '-0' . $i;
	                            else:
	                                $periodo = $contr_anno_final . '-' . $i;
	                            endif;
	                            $aporteMensual = $this->liquidaciones_model->getAporte_Fic($maestro['NIT_EMPRESA'], 2, 62, $periodo, $tipoFic,$liquidacion['contratoObra']);
	                            $pagoOportuno = 0;
	                            $deuda =  $tasaMensual  - $aporteMensual['VALOR_PAGADO'];
	                            $TasaMensualAcumulada+=$tasaMensual;
	                            $aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];
	                            $deudaAcumulada+=$deuda;
	                            if ($deuda <= 0)://se encontro aporte
	                                $intereses = 0;
	    			else:
                        $intereses = $calculo_mensual = calcularMesesCorrientes($fechaLiquidacion, $deuda, $i, $c_anno);
                        $linea = calcularInteresesFicDebug($fechaLiquidacion, $deuda, $i, $c_anno, $aporteMensual);
                        $calculos = array_merge($calculos, $linea);
					endif;
	                            $TotalIntereses+=$intereses;
	                        endfor;
	                        $informacion = array('tasamensual_'.$c_anno=>$TasaMensualAcumulada,'aportes_' . $c_anno => $aporteMensualAcumulado, 'deuda_' . $c_anno => $deudaAcumulada, 'intereses_' . $c_anno => $TotalIntereses);
	                        $detalle = array_merge($detalle, $informacion);
				endif;
	                    if ($c_anno == $contr_anno_final)://ultimo año
	                        $TotalIntereses = 0;
	                        $deudaAcumulada = 0;
	                        $aporteMensualAcumulado = 0;
	                        $TasaMensualAcumulada=0;
	                        for ($i = 1; $i <= $contr_mes_final; $i++):
	                            $tasaMensual = $valor_mensual * 0.0025;
	                            if ($contr_mes_final < 10):
	                                $periodo = $contr_anno_inicial . '-0' . $i;
	                            else:
	                                $periodo = $contr_anno_final . '-' . $i;
	                            endif;
	                            $aporteMensual = $this->liquidaciones_model->getAporte_Fic($maestro['NIT_EMPRESA'], 2, 62, $periodo, $tipoFic,$liquidacion['contratoObra']);
	                            $pagoOportuno = 0;
	                            $deuda =  $tasaMensual  - $aporteMensual['VALOR_PAGADO'];
	                            $TasaMensualAcumulada+=$tasaMensual;
	                            $aporteMensualAcumulado += $aporteMensual['VALOR_PAGADO'];
	                            $deudaAcumulada+=$deuda;
	                            if ($deuda <= 0)://se encontro aporte
	                                $intereses = 0;
	                            else:
	                                $intereses = $calculo_mensual = calcularMesesCorrientes($fechaLiquidacion, $deuda, $i, $c_anno);
                                    $linea = calcularInteresesFicDebug($fechaLiquidacion, $deuda, $i, $c_anno, $aporteMensual);
                                    $calculos = array_merge($calculos, $linea);
	                            endif;
	                            $TotalIntereses+=$intereses;
	                        endfor;
	                        $informacion = array('tasamensual_'.$c_anno=>$TasaMensualAcumulada,'aportes_' . $c_anno => $aporteMensualAcumulado, 'deuda_' . $c_anno => $deudaAcumulada, 'intereses_' . $c_anno => $TotalIntereses);
	                        $detalle = array_merge($detalle, $informacion);
	                    endif;
	                endif;
	            endfor;

	             $Valor_Contratos=array('valor_contratos_'.$c_anno=>$SumValorContrato);
	       endfor;
    $detalle = array_merge($detalle,$Valor_Contratos);
    $detalle_calculos = array('detalle' => $detalle, 'calculos' => $calculos);
    return $detalle_calculos;
	}

	    function valorgrilla(){
	        if ($this->ion_auth->logged_in()){
	            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/valorgrilla')){
	                $this->data['post'] = $this->input->post();
	                $cantmes=0;
	                $cantdia=0;
	                //Cantidad de filas adicionadas
	                $this->data['cantidad'] = $this->input->post('cantidad');
	                //Valor Total Ingresado en la Fila
	                $this->data['valor'] = $this->input->post('valor');
	                //Diferencia entre año inicial y final
	                $this->data['total_anio'] = $this->input->post('total_anio');
	                //Diferencia entre los meses
	                $this->data['total_mes'] = $this->input->post('total_mes');
	                //Mes inicial
	                $this->data['mesini'] = $this->input->post('mesini');
	                //Mes Final
	                $this->data['mesfin'] = $this->input->post('mesfin');
	                //Total de dias
	                $this->data['total_day'] = $this->input->post('total_day');
	                //Fecha inicio
	                $this->data['fechainicio'] = $this->input->post('fechainicio');
	                //Fecha Final
	                $this->data['fechafinal'] = $this->input->post('fechafinal');



	                $this->load->view('liquidaciones/matrizFic', $this->data);
	            }else {
			$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
			redirect(base_url().'index.php/mandamientopago');
	            }
	        }else{
	            redirect(base_url().'index.php/auth/login');
		}
	    }

	//MOSTRAR FORMULARIO PARA LIQUIDACIÓN DE SGVA
	function getFormSgva()
	/**
   	* Función que muestra el formulario para consultar estados de cuenta del SGVA
   	* Captura valores por parametros desde la vista consultar_respuesta
   	*
   	* @param array $codigoGestion
   	* @return array $data;
	*/
	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

				if ($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_menu('liquidaciones/getFormSgva')):

					if ($this -> uri -> segment(3) === FALSE || $this -> uri -> segment(4) === FALSE):

						throw new Exception('<strong>No cuenta con los suficientes parametros para generar un estado de cuenta</strong>.');

					else:

						$nit = $this -> security -> xss_clean((string)$this -> uri -> segment(3));
						$this -> data['nit'] = $nit;
						$codigoAsignacion = $this -> security -> xss_clean((string)$this -> uri -> segment(4));
						$this -> data['codigoAsignacion'] = $codigoAsignacion;
						$fiscalizacion = $this -> liquidaciones_model -> consultarFiscalizacionSgva($codigoAsignacion, 3);

						if($fiscalizacion !== FALSE ):

							throw new Exception('<strong>Ya existe un estado de cuenta en cobro para  el NIT:  ' . $nit . ' con el codigo de Fiscalización:  ' . $fiscalizacion['COD_FISCALIZACION'] . '</strong>');

						endif;

					endif;
					/*$empresa = $this -> liquidaciones_model -> buscarEmpresaSgva($nit);

					if($empresa === FALSE):

						throw new Exception('<strong>No cuenta con los suficientes parametros para generar un estado de cuenta para el NIT: </strong>.' . $nit);

					endif;*/

                    $this -> sgva_client = new sgva_client();
                    $estadoCuenta = $this -> sgva_client -> ObtenerIdEstadoCuenta($nit);

                    if($estadoCuenta == 0):

                        throw new Exception('<strong>No existe un estado de cuenta para el NIT: </strong>.' . $nit);

                    endif;

                    $info = $this -> sgva_client -> sgva_cartera_query_2($estadoCuenta);
                    $concepto = (string)3;
                    $subconcepto = (string)19;

                    if($info != NULL):

                        $fechasInicio = explode('/', $info['estado_fecha_inicio']);
                        $annoInicio = explode(' ',$fechasInicio[2]);
                        $this -> data['annoInicio'] = (int)$annoInicio[0];
                        $fechasFinal = explode('/', $info['estado_fecha_fin']);
                        $annoFinal = explode(' ',$fechasFinal[2]);
                        $this -> data['annoFinal'] = (int)$annoFinal[0];
                        if($annoInicio[0] == $annoFinal[0]):

                            ${'resumen_'.$annoInicio[0]} = $this -> sgva_client -> sgva_cartera_query_9($estadoCuenta,$annoInicio[0]);
                            $this -> data['resumen_' . $annoInicio[0]] = ${'resumen_' . $annoInicio[0]};
                            $this -> data['smlv_' . $annoInicio[0]] = $this -> liquidaciones_model -> getSalarioMinimoVigente($annoInicio[0]);
                            $this -> data['monetizacion_' . $annoInicio[0]] = $this -> liquidaciones_model -> consultarPagosPeriodo($info['estado_nit_empresa'], $annoInicio[0], $concepto, $subconcepto);

                        elseif($annoFinal[0] > $annoInicio[0]):

                            for($anno = $annoInicio[0]; $anno <= $annoFinal[0]; $anno++):

                                ${'resumen_'.$anno} = $this -> sgva_client -> sgva_cartera_query_9($estadoCuenta,$anno);
                                $this -> data['resumen_' . $anno] = ${'resumen_' . $anno};
                                $this -> data['smlv_' . $anno] = $this -> liquidaciones_model -> getSalarioMinimoVigente($anno);
                                $this -> data['monetizacion_' . $anno] = $this -> liquidaciones_model -> consultarPagosPeriodo($info['estado_nit_empresa'], $anno, $concepto, $subconcepto);

                            endfor;

                        endif;
                        $regulaciones = $this -> sgva_client -> sgva_cartera_query_3($estadoCuenta);
                        $contratos = $this -> sgva_client -> sgva_cartera_query_6a($estadoCuenta,$nit);
                        $contratos2 = $this -> sgva_client -> sgva_cartera_query_6b($estadoCuenta,$nit);
                        $monetizacion = $this -> sgva_client -> sgva_cartera_query_7($estadoCuenta);

                        $this -> data['info'] = $info;
                        $this -> data['regulaciones'] = $regulaciones;
                        $this -> data['contratos'] = $contratos;
                        $this -> data['contratos2'] = $contratos2;
                        $this -> data['monetizacion'] = $monetizacion;

                    else:

                        throw new Exception('<strong>No existe información para el estado de cuenta: </strong>.' . $estadoCuenta);

                    endif;



                    /*$this -> data['empresa'] = $empresa;
					$datestring = "%d/%m/%Y";
					$fecha_final = mdate($datestring);
					$this -> data['fechaFinal'] = $fecha_final;
					$datestring2 = "%d/%m/%Y";
					$fecha_inicial = mdate($datestring2, mktime(0, 0, 0, date("m"),date("d"), date("Y")-2));
					$this -> data['fechaInicial'] = $fecha_inicial;

					$regulaciones = $this -> liquidaciones_model -> buscarRegulacionesSgva($nit, $fecha_inicial, $fecha_final);

					if($regulaciones === FALSE):

						$this -> data['regulaciones'] = 0;

					else:

						$this -> data['regulaciones'] = $regulaciones;

					endif;

					$contratos = $this -> liquidaciones_model -> buscarContratosSgva($nit, $fecha_inicial, $fecha_final);

					if($contratos === FALSE):

						$this -> data['contratos'] = 0;

					else:

						$this -> data['contratos'] = $contratos;

					endif;

					$datestring3 = "%m/%Y";
					$fecha_final_monetizacion = mdate($datestring3);
					$datestring4 = "%m/%Y";
					$fecha_inicial_monetizacion = mdate($datestring4, mktime(0, 0, 0, date("m"),date("d"), date("Y")-2));
					$monetizacion = $this -> liquidaciones_model -> buscarMonetizacionSgva($nit, $fecha_inicial_monetizacion, $fecha_final_monetizacion);

					if($monetizacion === FALSE):

						$this -> data['monetizacion'] = 0;

					else:

						$this -> data['monetizacion'] = $monetizacion;

					endif;

					$this -> data['anno_inicial'] = $anno_inicial = 2012;
					$this -> data['anno_final'] = $anno_final = 2014;

					for($periodo = $anno_inicial; $periodo <= $anno_final; $periodo++):

						$this -> data['smlv_' . $periodo] = $this -> liquidaciones_model -> getSalarioMinimoVigente($periodo);
						$this -> data['resumen_' . $periodo] = $this -> liquidaciones_model -> buscarResumenSgva($periodo, $nit);

					endfor;*/

					$this -> template -> set('title', 'Estado de Cuenta SGVA');
					$this -> template -> load($this -> template_file, 'liquidaciones/liquidacionsgva_form',$this ->  data);

				else:

					$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');

					redirect(site_url().'/inicio');
				endif;

			else:

				redirect(site_url().'/auth/login');

			endif;
		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Liquidación Contratos de Aprendizaje';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e -> getMessage() .'</div>';
			$this -> template -> set('title', 'Errores en liquidación');
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
	}

	function getFormSgva_exito()
	/**
   	* Función que muestra el formulario para consultar estados de cuenta del SGVA
   	* Captura valores por parametros desde la vista consultar_respuesta
   	*
   	* @return array $data;
	*/
	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

				if ($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_menu('liquidaciones/getFormSgva_exito')):

					$codigoAsignacion = $this -> security -> xss_clean((int)$this -> input -> post('codigoAsignacion'));
					$periodoInicial = $this -> security -> xss_clean((string)$this -> input -> post('periodoInicial'));
					$periodoFinal = $this -> security -> xss_clean((string)$this -> input -> post('periodoFinal'));
					$nit = $this -> security -> xss_clean((string)$this -> input -> post('nit'));
					$total = $this -> security -> xss_clean((int)$this -> input -> post('total'));
					$codigoConcepto = 3;
					$codigoTipoGestion = 1;

					//CREAR FISCALIZACIÓN  CONTRATO DE APRENDIZAJE
					$cod_respuesta_fiscalizacion = '1';
					$cod_tipoGestion_fiscalizacion = 1;
					$comentarios_fiscalizacion = 'Fiscalización Contrato de Aprendizaje Generada';
					//CALCULAR LIQUIDACION CONTRATOS DE APRENDIZAJE
					$cod_respuesta_calculada = '797';
					$cod_tipoGestion_calculada = 274;
					$comentarios_calculada = 'Liquidación Contrato de Aprendizaje Generada';
					//LIQUIDACION CONTRATOS DE APRENDIZAJE EN FIRME
					$cod_respuesta_firme = '796';
					$cod_tipoGestion_firme = 274;
					$comentarios_firme = 'Liquidacion  Contrato de Aprendizaje Legalizada';

					$resultado = $this -> liquidaciones_model -> cargarFiscalizacionSgva($codigoAsignacion, $codigoConcepto, $codigoTipoGestion, $periodoInicial, $periodoFinal);

					if($resultado === FALSE):

                        throw new Exception('<strong>Se presentó un problema generando la fiscalización por el concepto de Contratos de Aprendizaje para la asignación: </strong>.' . $codigoAsignacion);

                    else:

						$fiscalizacion = $this -> liquidaciones_model -> consultarFiscalizacionSgva($codigoAsignacion, $codigoConcepto);
						trazar($cod_tipoGestion_fiscalizacion, $cod_respuesta_fiscalizacion, $fiscalizacion['COD_FISCALIZACION'], $nit, $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_fiscalizacion);
						$liquidacion_previa = 0;
						$liquidacion =  array(
							'numeroLiquidacion' => $fiscalizacion['NRO_EXPEDIENTE'] ,
							'codigoConcepto' => '3' ,
							'nitEmpresa' => $nit ,
							'fechaInicio' => $periodoInicial ,
							'fechaFin' => $periodoFinal ,
							'fechaLiquidacion' => date('d-m-Y') ,
							'totalLiquidado' => $total ,
							'totalCapital' => $total ,
							'totalInteres' => '0',
							'fechaVencimiento' => date('d-m-Y'),
							'saldoDeuda' => $total ,
							'tipoProceso' => 5 ,
							'codigoFiscalizacion' => $fiscalizacion['COD_FISCALIZACION'] );
						$estadoCuenta = $this -> liquidaciones_model -> cargarLiquidacion($liquidacion,  $liquidacion_previa);

						if($estadoCuenta):

							trazar($cod_tipoGestion_calculada, $cod_respuesta_calculada, $fiscalizacion['COD_FISCALIZACION'], $nit, $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_calculada);
							trazar($cod_tipoGestion_firme, $cod_respuesta_firme, $fiscalizacion['COD_FISCALIZACION'], $nit, $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_firme);

						endif;

					endif;

					$this -> data['liquidacion'] = $fiscalizacion['NRO_EXPEDIENTE'];
					$this -> data['expediente'] = $fiscalizacion['COD_FISCALIZACION'];
					$this -> template -> set('title', 'Estado de Cuenta SGVA');
					$this -> template -> load($this -> template_file, 'liquidaciones/liquidacionsgva_exito',$this ->  data);

				else:

					$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');

					redirect(site_url().'/inicio');
				endif;

			else:

				redirect(site_url().'/auth/login');

			endif;
		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Liquidación Aportes Parafiscales';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e -> getMessage() .'</div>';
			$this -> template -> set('title', 'Errores en liquidación');
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
	}

	function getFormRemision()
	/**
   	* Función que muestra la lista de fiscalizaciones disponible para emitir la carta de presentación de la liquidación
   	*  Muestra las fiscalizaciones asociadas al usuario logueado
   	*
   	* @return array $data;
	*/
	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

				if ($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_menu('liquidaciones/getFormRemision')):

					$this -> template -> set('title', 'Carta Remisión Liquidación');
					$this -> data['message']=$this -> session -> flashdata('message');
					$codigoGestion = $this -> input -> post('cod_gestion_liquidacion');
					$this -> data['codigo'] = $codigoGestion;
					$tipos = $this -> liquidaciones_model  -> getCombinacionTipoAportante();
					$this -> data['tipos'] = $tipos;
					$this -> template -> load($this -> template_file, 'liquidaciones/liquidacioncarta_form',$this ->  data);

				else:

					$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					redirect(site_url().'/inicio');

				endif;

			else:

				redirect(site_url().'/auth/login');

			endif;
		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Carta Remisión Liquidación';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e -> getMessage() .'</div>';
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
	}

	function loadRemision()
	/**
   	* Función que muestra la carta de presentación de la liquidación
   	* Captura valores por formulario de codigo de fiscalizacion y la opción seleccionada por el usuario para la combiación de correspondencia
   	*
   	* @param array $codigoGestion
   	* @return array $data;
	*/
	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

				if ($this -> ion_auth -> is_admin() ||  $this -> ion_auth -> in_menu('liquidaciones/loadRemision')):

					$this -> template -> set('title', 'Carta Remisión Liquidación');
					$this -> data['message']=$this -> session -> flashdata('message');
					$codigoGestion = $this -> security -> xss_clean($this -> input -> post('cod_gestion_liquidacion'));
					$codigoOpcion = $this -> security -> xss_clean($this -> input -> post('opcion'));

					//CARTA DE PRESENTACIÓN CREADA
					$cod_respuesta_carta = '793';
					$cod_tipoGestion_carta = 273;
					$comentarios_carta = 'Carta de Presentación de Liquidación Generada';

                    				$fiscalizacion = $this -> liquidaciones_model -> consultarCodigoFiscalizacion($codigoGestion, COD_USUARIO);

					if($fiscalizacion === FALSE):

						throw new Exception('<strong>El código de fiscalización: <i>'. $codigoGestion .'</i> no cuenta con datos asociados</strong>.');

					endif;

					$usuario = $this -> ion_auth -> user() -> row();

					if($usuario === FALSE):

						throw new Exception('<strong>No se reportan usuarios logeados</strong>.');

					endif;

					$usuario_consulta = $usuario -> IDUSUARIO;
					$usuario_nombre = array('NOMBRE' => $usuario  -> NOMBRES,  'APELLIDOS' => $usuario -> APELLIDOS);
					$info_usuario = $this -> liquidaciones_model -> getInfoUsuarios($usuario_consulta);//consultar ciudad y coordinador usuario logeado

					if($info_usuario === FALSE):

						throw new Exception('<strong>No se encuentran datos asociado al usuario ID:' . $usuario_consulta . ' </strong>.');

					endif;

					$info_empresa = $this -> liquidaciones_model  -> getEmpresa($fiscalizacion['NIT_EMPRESA']);//consultar información de la empresa

					if($info_empresa === FALSE):

						throw new Exception('<strong>No se encuentran datos asociado a la empresa NIT:' . $fiscalizacion['NIT_EMPRESA'] . ' </strong>.');
					endif;

					$info_liquidacion = $this -> liquidaciones_model -> getCabecerasSoportesLiquidacion($codigoGestion);

					if (sizeof($info_liquidacion) === 0):
						throw new Exception('<strong>No se puede realizar la carta por que no existe una liquidacion realizada a la empresa:' . $fiscalizacion['NIT_EMPRESA'] . ' </strong>.');
					endif;

					$info_fecha = $this -> liquidaciones_model  -> getFechaVisita($codigoGestion);//consultar fecha de visita

					if ($info_fecha === false):

						throw new Exception('<strong>No se puede realizar la carta por que no se ha generado una visita a la empresa:' . $fiscalizacion['NIT_EMPRESA'] . ' </strong>.');
					endif;

					$meses = array('01' => 'enero','02' => 'febrero','03' => 'marzo','04' => 'abril','05' => 'mayo','06' => 'junio','07' => 'julio','08' => 'agosto','09' => 'septiembre','10' => 'octubre','11' => 'noviembre','12' => 'diciembre');
					$fechacomunicado = $info_fecha['FECHA_DOCUMENTO'];
					$fechacomunicado_explode= explode("/", $fechacomunicado);
					$fecha = array('MES' => $meses[$fechacomunicado_explode[1]], "DIA" =>  $fechacomunicado_explode[0]);
					$info_respuesta = $this -> liquidaciones_model -> getCombinacionRespuesta($codigoOpcion);//consultar respuesta para la combinación

					if($info_respuesta === FALSE):

						throw new Exception('<strong>No se encuentran datos asociado al código de opción :' . $codigoOpcion . ' </strong>.');
					endif;

					$carga = array_merge($fiscalizacion, $usuario_nombre, $info_usuario, $info_empresa, $info_respuesta, $fecha); //unificación de datos
					$datos = array(
						'fecha_liquidacion' => $info_liquidacion['FECHA_LIQUIDACION'],
						'num_liquidacion' => $info_liquidacion['NUM_LIQUIDACION'],
						'mm' => $fecha['MES'],
						'dd' => $fecha['DIA'],
						'ciudad' => $carga['NOMBREMUNICIPIO'],
						'trato' => 'Sr(a)',
						'nombre' => $carga['REPRESENTANTE_LEGAL'],
						'nombreEmpresa' => $carga['RAZON_SOCIAL'],
						'nit' => $carga['NIT_EMPRESA'],
						'direccion' => $carga['DIRECCION'],
						'ciudad' => $carga['NOMBREMUNICIPIO'],
						'concepto' => $carga['NOMBRE_CONCEPTO'],
						'respuesta' => $carga['TEXTO_COMBINATORIO'],
						'fiscalizador' => $carga['NOMBRE'] . " " . $carga['APELLIDOS'],
						'coordinador' => $carga['NOMBRE_COORDINADOR_RELACIONES'],
						'diaVisita' => $carga['DIA'],
						'mesVisita' => $carga['MES']
					);
					$template = template_tags('uploads/plantillas/rq_003_cu_005.txt', $datos);
					trazar($cod_tipoGestion_carta, $cod_respuesta_carta, $fiscalizacion['COD_FISCALIZACION'], $carga['NIT_EMPRESA'], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_carta);
					$this -> data['plantilla'] = $template;
					$this -> template -> load($this -> template_file, 'liquidaciones/liquidacioncarta_respuesta',$this ->  data);
				else:

					$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					redirect(site_url().'/inicio');

				endif;

			else:

				redirect(site_url().'/auth/login');

			endif;
		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Carta Remisión Liquidación';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e -> getMessage() .'</div>';
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
	}

	//MOSTRAR FORMULARIO  PARA SOPORTE DE LIQUIDACIÓN
	function getFormSoporteLiquidacion()
	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

				if ($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_menu('liquidaciones/getFormSoporteLiquidacion')):

					$this -> data['message']= $this -> session -> flashdata('message');
                   				 $codigo_fiscalizacion = $this -> input -> post('cod_gestion_liquidacion');
                   				 $empresa = $this -> liquidaciones_model -> getLiquidaciones($codigo_fiscalizacion);

					if ($empresa == NULL):

						throw new Exception('El codigo de Fiscalización ya ha dejado en firme su liquidación, o no se ha liquidado. <strong><em>Código Fiscalización : ' . $codigo_fiscalizacion . '</em></strong>');

					else:

						$this -> data['empresa'] =  $empresa;
						$this -> data['codigoFiscalizacion'] = $codigo_fiscalizacion;
						$datestring = "%d/%m/%Y";
						$fecha_actual = mdate($datestring);
						$this -> data['fecha'] = $fecha_actual;

					endif;

					$this -> template -> set('title', 'Legalización de Liquidación');
					$this -> template ->  load($this -> template_file, 'liquidaciones/legalizacionliquidacion_form', $this -> data);

				else:

					$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					redirect(site_url().'/inicio');

				endif;

			else:

				redirect(site_url().'/auth/login');

			endif;

		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Legalización de Liquidación';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e -> getMessage() .'</div>';
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
	}

	//GRABAR SOPORTE DE LIQUIDACIÓN Y CARGUE DE ARCHIVO
	function loadSoporteLiquidacion()
	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

				if ($this -> ion_auth -> is_admin() ||  $this -> ion_auth -> in_menu('liquidaciones/loadSoporteLiquidacion')):

					//LIQUIDACIÓN EN FIRME APORTES PARAFISCALES
					$cod_respuesta_aportes = '626';
					$cod_tipoGestion_aportes = 69;
					$comentarios_aportes_enFIrme = 'Liquidación de Aportes Parafiscales en Firme';
					//LIQUIDACIÓN EN FIRME FIC
					$cod_respuesta_fic = '794';
					$cod_tipoGestion_fic = 71;
					$comentarios_fic_enFIrme = 'Liquidación de FIC en Firme';
					//LIQUIDACIÓN EN FIRME
					$cod_respuesta = '119';
					$cod_tipoGestion = 73;
					$comentarios_enFIrme = 'Liquidación en Firme';


					$this -> data['message'] = $this -> session -> flashdata('message');
					$concepto = $this -> security -> xss_clean($this -> input -> post('codigoConcepto'));
					$liquidacion = $this -> security -> xss_clean($this -> input -> post('numeroLiquidacion'));
					$nis = $this -> security -> xss_clean($this -> input -> post('nis'));
					$nis = mb_strtoupper($nis, 'UTF-8');
					$fecha = $this -> security -> xss_clean($this -> input -> post('fechaRadicado'));
					$radicado = $this -> security -> xss_clean($this -> input -> post('numeroRadicado'));
					$codigoFiscalizacion = $this -> security -> xss_clean($this -> input -> post('codigoFiscalizacion'));
					$usuario = $this -> ion_auth -> user() -> row();
					$nombres = mb_strtoupper(limpiar_cadena((string)$usuario -> NOMBRES), 'UTF-8');
					$apellidos = mb_strtoupper(limpiar_cadena((string)$usuario -> APELLIDOS), 'UTF-8');
					$fiscalizador = $nombres . " " . $apellidos;
					$idusuario = $usuario -> IDUSUARIO;

					if ($concepto == "" || $liquidacion == "" || $fecha == "" || $codigoFiscalizacion == ""):

						throw new Exception('No se han recibido los datos suficientes para proceder con su solicitud.');

					else:

						$tam_archivo = (int)$_FILES['userfile']['size'];
						if($tam_archivo > 5242880):
							throw new Exception('El archivo <strong>'. $_FILES['userfile']['name'] . '</strong>, ha superado el limite de tamaño para adjuntos de 5Mb. Tamaño archivo: ' . number_format((float)(($tam_archivo/1024)/1024), 3,',','.') . 'Mb');
						endif;
						$resultado = $this -> do_upload($codigoFiscalizacion);
						$archivo = $resultado['upload_data']['file_name'];
						$carga_previa = $this -> liquidaciones_model -> getArchivosSubidos($liquidacion);

						if($carga_previa > 0):

							$this -> data['archivos'] = $carga_previa;
							$this -> data['fiscalizacion'] = $codigoFiscalizacion;
							$this -> data['liquidacion'] = $liquidacion;
							// $this -> data['respuesta'] = $carga;
							$this  -> template -> set('title', 'Legalización de Liquidación');
							$this -> template -> load($this -> template_file, 'liquidaciones/legalizacionliquidacion_exito', $this -> data);


						else:
							$traza = $this -> liquidaciones_model -> consultarCabeceraLiquidacion($codigoFiscalizacion, $idusuario);
							$carga = $this -> liquidaciones_model -> loadSoportesLiquidacion($liquidacion, $nis, $fecha, $radicado, $archivo, $fiscalizador);

							if($concepto == 1):

								trazar($cod_tipoGestion_aportes, $cod_respuesta_aportes, $codigoFiscalizacion, $traza["NIT_EMPRESA"], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_aportes_enFIrme);

							elseif($concepto == 2):


								trazar($cod_tipoGestion_fic, $cod_respuesta_fic, $codigoFiscalizacion, $traza["NIT_EMPRESA"], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_fic_enFIrme);

							endif;

							if ($carga != NULL):

								$datos['NUM_LIQUIDACION'] = $liquidacion;
								$datos['EN_FIRME'] = 'S';
								$this -> liquidaciones_model -> actualizacion_liquidacion($datos);
								trazar($cod_respuesta, $cod_tipoGestion, $codigoFiscalizacion, $traza["NIT_EMPRESA"], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_enFIrme);
								$this -> data['archivos'] = $this -> liquidaciones_model -> getArchivosSubidos($liquidacion);
								$this -> data['fiscalizacion'] = $codigoFiscalizacion;
								$this -> data['liquidacion'] = $liquidacion;
								$this -> data['respuesta'] = $carga;
								$this  -> template -> set('title', 'Legalización de Liquidación');
								$this -> template -> load($this -> template_file, 'liquidaciones/legalizacionliquidacion_exito', $this -> data);

							else:

								throw new Exception('Ha ocurrido un problema guardando los datos en la base de datos.');

							endif;

						endif;

					endif;

				else:

					$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					redirect(site_url() . '/inicio');

				endif;

			else:

				redirect(site_url() . '/auth/login');

			endif;

		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Legalización de Liquidación';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e -> getMessage() . '</div>';
			$this -> template -> load($this -> template_file, 'liquidaciones/error', $this -> data);
		}
	}

	//MOSTRAR FORMULARIO  PARA SOPORTE DE LIQUIDACIÓN
	function comprobante()
	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

				if ($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_menu('liquidaciones/comprobante')):

					$this -> template -> set('title', 'Comprobante de Liquidación');
					$this -> data['message']=$this -> session -> flashdata('message');

					if ($this -> uri -> segment(3) === FALSE ):

						throw new Exception('<strong>No cuenta con los suficientes parametros para generar un comprobante</strong>.');

					else:

						$codigoMulta = $this -> uri -> segment(3);

					endif;

					$liquidacion = $this -> liquidaciones_model -> getLiquidacionMultaMinisterio($codigoMulta);
					$this -> data['liquidacion'] = $liquidacion;
					$datestring = "%d/%m/%Y";
					$fecha_actual = mdate($datestring);
					$this -> data['fecha'] = $fecha_actual;
					$this -> template -> load($this -> template_file, 'liquidaciones/comprobante',$this ->  data);

				else:

					$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					redirect(site_url().'/inicio');

				endif;

			else:

				redirect(site_url().'/auth/login');

			endif;

		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Legalización de Liquidación';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e -> getMessage() .'</div>';
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
	}

	//MOSTRAR FORMULARIO  PARA SOPORTE DE LIQUIDACIÓN
	function comprobanteAlterno()
	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

				if ($this -> ion_auth -> is_admin() ||  $this -> ion_auth -> in_menu('liquidaciones/comprobanteAlterno')):

					$this -> data['message']=$this -> session -> flashdata('message');

					if ($this -> uri -> segment(3) === FALSE ):

						throw new Exception('<strong>No cuenta con los suficientes parametros para generar un comprobante</strong>.');

					else:

						$codigoLiquidacion = $this -> security -> xss_clean((string)$this -> uri -> segment(3));

					endif;

					$liquidacion = $this -> liquidaciones_model -> consultarLiquidacion($codigoLiquidacion);
					$this -> data['liquidacion'] = $liquidacion;
					$datestring = "%d/%m/%Y";
					$fecha_actual = mdate($datestring);

					//COMPROBANTE APORTES
					$cod_respuesta_aportes = '113';
					$cod_tipoGestion_aportes = 70;
					$comentarios_aportes_enFIrme = 'Comprobante Pago Generado Aportes Parafiscales';
					//COMPROBANTE FIC
					$cod_respuesta_fic = '112';
					$cod_tipoGestion_fic = 70;
					$comentarios_fic_enFIrme = 'Comprobante Pago Generado FIC';
					//COMPROBANTE CONTRATOS DE APRENDIZAJE
					$cod_respuesta_contratos = '114';
					$cod_tipoGestion_contratos = 70;
					$comentarios_contratos_enFIrme = 'Comprobante Pago Generado Contrato Aprendizaje';
					//COMPROBANTE MULTAS MINISTERIO
					$cod_respuesta_multas = '115';
					$cod_tipoGestion_multas = 70;
					$comentarios_multas_enFIrme = 'Comprobante Pago Generado Multas Ministerio';


					if($liquidacion["COD_CONCEPTO"] == 1):

						trazar($cod_tipoGestion_aportes, $cod_respuesta_aportes, $liquidacion["COD_FISCALIZACION"], $liquidacion["NITEMPRESA"], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_aportes_enFIrme);

					elseif($liquidacion["COD_CONCEPTO"] == 2):

						trazar($cod_tipoGestion_fic, $cod_respuesta_fic, $liquidacion["COD_FISCALIZACION"], $liquidacion["NITEMPRESA"], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_fic_enFIrme);

					elseif($liquidacion["COD_CONCEPTO"] == 3):

						trazar($cod_tipoGestion_contratos, $cod_respuesta_contratos, $liquidacion["COD_FISCALIZACION"], $liquidacion["NITEMPRESA"], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_contratos_enFIrme);

					elseif($liquidacion["COD_CONCEPTO"] == 5):

						trazar($cod_tipoGestion_multas, $cod_respuesta_multas, $liquidacion["COD_FISCALIZACION"], $liquidacion["NITEMPRESA"], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_multas_enFIrme);

					endif;

					$this -> data['fecha'] = $fecha_actual;
					$this -> template -> set('title', 'Comprobante de Liquidación');
					$this -> template -> load($this -> template_file, 'liquidaciones/comprobanteAlterno',$this ->  data);

				else:

					$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					redirect(site_url().'/inicio');

				endif;

			else:

				redirect(site_url().'/auth/login');

			endif;
		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Legalización de Liquidación';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e -> getMessage() .'</div>';
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
	}

	//MOSTRAR FORMULARIO  PARA SOPORTE DE LIQUIDACIÓN
	function crearLiquidacionAportes()
	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

				if ($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_menu('liquidaciones/crearLiquidacionAportes')):

					$this -> template -> set('title', 'Resumen de Liquidación');
					$this -> data['message']=$this -> session -> flashdata('message');
					$maestro  = unserialize($this -> input -> post('maestro'));
					$detalle  = unserialize($this -> input -> post('informacion'));
					$fiscalizacion  = unserialize($this -> input -> post('fiscalizacion'));
					$observaciones  = $this -> input -> post('observaciones');
					$documentacion  = $this -> input -> post('documentacion');
					$adjuntos = array('observaciones' => $observaciones, 'documentacion' => $documentacion, 'liquidacion' => $fiscalizacion['NRO_EXPEDIENTE']);
					$existe = $this -> liquidaciones_model  -> consultarLiquidacionAportes($fiscalizacion['NRO_EXPEDIENTE']);
					$liquidacion_previa = 0;

					if($existe != null):

						foreach ($existe as $anno):

							$this -> data['serie_' . $anno['ANO']] = $anno;

						endforeach;

						$liquidacion_previa = 1;

					endif;

					$usuario = $this -> ion_auth -> user() -> row();
					$nombres = strtoupper((string)$usuario -> NOMBRES);
					$apellidos = strtoupper((string)$usuario -> APELLIDOS);
					$fiscalizador = $nombres . " " . $apellidos;
					$idusuario = $usuario -> IDUSUARIO;
					$resultado = $this -> liquidaciones_model -> actualizarLiquidacionAporte($adjuntos);
					$informacion_usuario = $this -> liquidaciones_model -> getInfoUsuarios($idusuario);

					$this -> data['maestro'] = $maestro;
					$this -> data['detalle'] = $detalle;
					$this -> data['fiscalizacion'] = $fiscalizacion;
					$this -> data['fiscalizador'] = $fiscalizador;
					$this -> data['informacion_usuario'] = $informacion_usuario;
					$this -> data['observaciones'] = $observaciones;
					$this -> data['documentacion'] = $documentacion;
					$this -> template -> load($this -> template_file, 'liquidaciones/generar_liquidacion',$this ->  data);

				else:

					$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					redirect(site_url().'/inicio');

				endif;

			else:

				redirect(site_url().'/auth/login');

			endif;

		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Legalización de Liquidación';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e -> getMessage() .'</div>';
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
	}

	 //FUNCIÓN CARGUE DE ARCHIVO EN SERVER
 	private function do_upload($codigoFiscalizacion)
	{

		$nombre_carpeta = './uploads/fiscalizaciones/'.$codigoFiscalizacion.'/liquidaciones/';

		if (!file_exists($nombre_carpeta)):

			if (!mkdir($nombre_carpeta, 0777, true)):

				die('Fallo al crear las carpetas...');

			endif;

		endif;

		$config['upload_path'] = $nombre_carpeta;
		$config['allowed_types'] = '*';
		$config['max_size'] = '2048';
		$this -> load -> library('upload', $config);
		$files = $_FILES;

		if (sizeof($files) == 0):

			return false;

		 endif;

		if (!$this -> upload -> do_upload()):

			return $error = array('error' => $this -> upload -> display_errors());

		else :

			return $data = array('upload_data' => $this -> upload -> data());

		endif;
	}

  	// FUNCIONES DE AUTOCOMPLETAR---------------------------------------------------------

  	function consultarNit()
	/**
   	* Función que consulta los nits para la opción de autocompletar
  	*
  	* @param string $nit
   	* @return array $datos
   	* @return boolean false - error
	*/
  	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

		    		if ($this -> ion_auth -> is_admin() ||  $this -> ion_auth -> in_menu('liquidaciones/consultarNit')):

			        	$nit = $this -> input -> get('term');

			        		if (empty($nit)):

			          			redirect(site_url() . '/liquidaciones/consultar');

			        		else:

				          		$usuario = $this -> ion_auth -> user() -> row();
				          		$regional = $usuario -> COD_REGIONAL;
				          		$this -> liquidaciones_model -> buscarNits($nit, $regional);
				          		return $this -> output -> set_content_type('application/json') -> set_output(json_encode($this  -> liquidaciones_model -> buscarNits($nit, $regional)));

			        		endif;

			      	else:

			      		$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
			        		redirect(site_url() . '/inicio');

			      	endif;

			else:

		    		redirect(site_url() . '/auth/login');

			endif;

		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Legalización de Liquidación';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e -> getMessage() .'</div>';
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
  	}

	function consultarRazonSocial()
	/**
   	* Función que consulta las razones sociales para la opción de autocompletar
  	*
  	* @param string $razonSocial
   	* @return array $datos
   	* @return boolean false - error
	*/
  	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

			    	if ($this -> ion_auth -> is_admin() ||  $this -> ion_auth -> in_menu('liquidaciones/consultarRazonSocial')):

			        		$razonSocial = strtoupper($this -> input -> get('term'));

				        	if (empty($razonSocial)):

				          			redirect(site_url() . '/liquidaciones/consultar');

				        	else:

				          		$usuario = $this -> ion_auth -> user() -> row();
				          		$regional = $usuario -> COD_REGIONAL;
				          		$this -> liquidaciones_model -> buscarRazonSocial($razonSocial, $regional);
				          		return $this -> output -> set_content_type('application/json') -> set_output(json_encode($this  -> liquidaciones_model -> buscarRazonSocial($razonSocial, $regional)));

				        	endif;

			      	else:

			      		$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
			        		redirect(site_url() . '/inicio');

			      	endif;

			else:

		    		redirect(site_url() . '/auth/login');

			endif;

		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Legalización de Liquidación';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e -> getMessage() .'</div>';
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
  	}

  	function consultarRepresentante()
	/**
   	* Función que consulta los representantes para la opción de autocompletar
  	*
  	* @param string $representante
   	* @return array $datos
   	* @return boolean false - error
	*/
  	{
		try
		{
			if ($this -> ion_auth -> logged_in()):

			    	if ($this -> ion_auth -> is_admin() ||  $this -> ion_auth -> in_menu('liquidaciones/consultarRepresentante')):

			        		$representante = strtoupper($this -> input -> get('term'));

			        		if (empty($representante)):

			          			redirect(site_url() . '/liquidaciones/consultar');

			        		else:

				          		$usuario = $this -> ion_auth -> user() -> row();
				          		$regional = $usuario -> COD_REGIONAL;
				          		$this -> liquidaciones_model -> buscarRepresentante($representante, $regional);
				          		return $this -> output -> set_content_type('application/json') -> set_output(json_encode($this  -> liquidaciones_model -> buscarRepresentante($representante, $regional)));

			        		endif;

			      	else:

			      		$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					redirect(site_url() . '/inicio');

			      	endif;

			else:

			    	redirect(site_url() . '/auth/login');

			endif;

		}
		catch (Exception $e)
		{
			$this -> data['titulo'] = 'Legalización de Liquidación';
			$this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e -> getMessage() .'</div>';
			$this -> template -> load($this -> template_file, 'liquidaciones/error',$this ->  data);
		}
  	}

	function pdf()
	{
		$html = utf8_encode(base64_decode($this -> input -> post('html')));
		$nombre_pdf = $this -> input -> post('nombre');
		$titulo = $this -> input -> post('titulo');
		$tipo = $this -> input -> post('tipo');
		$data[0] = $tipo;
		$data[1] = $titulo;
		createPdfTemplateOuput($nombre_pdf, $html, false, $data);
		exit();
	}

	function legalizarLiquidacion()
	{
		if ($this -> ion_auth -> logged_in()):

			if ($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_menu('liquidaciones/legalizarLiquidacion')):

				$this -> data['ruta'] = 'index.php/liquidaciones/getFormSoporteLiquidacion';
				$this -> data['titulo'] = 'Legalizar Liquidaciones';
				$this -> data['liquidaciones_seleccionadas'] = $this -> liquidaciones_model -> getLiquidaciones();
				$this -> template -> set('title', 'Legalizar Liquidación');
				$this -> template -> load($this -> template_file, 'liquidaciones/gestionliquidacion_list', $this -> data);

			else:

				$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
				redirect(base_url() . 'index.php/inicio');

			endif;

		else:

			redirect(base_url() . 'index.php/auth/login');

		endif;
	}

	function Gestion_EliminarSoporte()
	{
	if ($this -> ion_auth -> logged_in()):

		if ($this -> ion_auth -> is_admin() || $this -> input -> post('cod_soporte')):

				$cod_soporte = $this -> security -> xss_clean($this -> input -> post('cod_soporte'));
				$liquidacion = $this -> security -> xss_clean($this -> input -> post('num_liquidacion'));
				$cod_fiscalizacion = $this -> security -> xss_clean($this -> input -> post('cod_fiscalizacion'));
				$this -> liquidaciones_model -> eliminar_soporte($cod_soporte);
				$datos['NUM_LIQUIDACION'] = $liquidacion;
				$datos['EN_FIRME'] = 'N';
				$this -> liquidaciones_model -> actualizacion_liquidacion($datos);
				$this -> data['archivos'] = $this -> liquidaciones_model -> getArchivosSubidos($liquidacion);
				$this -> data['fiscalizacion'] = $cod_fiscalizacion;
				$this -> data['liquidacion'] = $liquidacion;
				$usuario = $this -> ion_auth -> user() -> row();
				$idusuario = $usuario -> IDUSUARIO;
				//RETIRO DE SOPORTE
				$cod_respuesta = '1320';
				$cod_tipoGestion = 73;
				$comentarios_enFirme = 'Soporte LIquidación Eliminado';
				$traza = $this -> liquidaciones_model -> consultarCabeceraLiquidacion($cod_fiscalizacion, $idusuario);
				trazar($cod_tipoGestion, $cod_respuesta, $cod_fiscalizacion, $traza["NIT_EMPRESA"], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios_enFirme);

				if (sizeof($this -> data['archivos']) == 0):

					$empresa = $this -> liquidaciones_model -> getLiquidaciones($cod_fiscalizacion);

					if ($empresa == NULL):

						throw new Exception('El codigo de Fiscalización no existe. <strong><em>Código Fiscalización : ' . $codigo_fiscalizacion . '</em></strong>');
					else:

						$this -> data['empresa'] = $empresa;
						$this -> data['codigoFiscalizacion'] = $cod_fiscalizacion;
						$datestring = "%d/%m/%Y";
						$fecha_actual = mdate($datestring);
						$this -> data['fecha'] = $fecha_actual;

					endif;

					$this -> template -> load($this -> template_file, 'liquidaciones/legalizacionliquidacion_form', $this -> data);

				else:

					$this -> template -> load($this -> template_file, 'liquidaciones/legalizacionliquidacion_exito', $this -> data);

				endif;

			else:

				$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
				redirect(base_url() . 'index.php/inicio');

			endif;

		else:

			redirect(base_url() . 'index.php/auth/login');

		endif;
	}

	function getLiquidacion()
	{
		if ($this -> ion_auth -> logged_in()):

			if ($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_menu('liquidaciones/getLiquidacion')):

				$this -> template -> set('title', 'Fiscalizaciones Disponibles');
				$this -> data['ruta'] = 'index.php/liquidaciones/getFormRemision';
				$this -> data['titulo'] = 'Carta Remisión de Liquidación';
				$this -> data['liquidaciones_seleccionadas'] = $this -> liquidaciones_model -> getLiquidaciones();
				$this -> template -> load($this -> template_file, 'liquidaciones/gestionliquidacion_list', $this -> data);

			else:

				$this -> session -> set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
				redirect(base_url() . 'index.php/inicio');

			endif;
		else:

			redirect(base_url() . 'index.php/auth/login');

		endif;
	}
}
/* End of file liquidaciones.php */