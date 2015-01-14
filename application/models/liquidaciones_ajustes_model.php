<?php
/**
* Archivo para ala administración de los modelos necesarios para las liquidaciones en el proceso administrativo
*
* @packageCartera
* @subpackage Models
* @author jdussan
* @location./application/models/liquidaciones_ajustes_model.php
* @last-modified 04/02/2014
*/

class Liquidaciones_ajustes_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

    function consultarLiquidaciones()
        /**
         * Función que develve las liquidaciones disponibles en la DB
         * @return array $datos
         */
    {
        $this -> db -> select('NUM_LIQUIDACION');
        $this -> db -> from('LIQUIDACION');
        $this -> db -> where('EN_FIRME', 'S');
        $datos = $this -> db -> get();
        //#####BUGGER PARA LA CONSULTA ######
        //$resultado = $this -> db -> last_query();
        //echo $resultado; die();
        //#####BUGGER PARA LA CONSULTA ######
        if ($this -> db -> trans_status() === FALSE) :

            $this -> db -> trans_rollback();
            return $datos = FALSE;

        else:

            $this -> db -> trans_commit();
            $datos = $datos -> result_array();

            if(!empty($datos)):

                $tmp = NULL;

                foreach($datos as $registro):

                    $tmp[] = array("liquidacion" => $registro['NUM_LIQUIDACION']);

                endforeach;

                return $datos = $tmp;

            else:

                return $datos = FALSE;

            endif;

        endif;

    }

    /*--- VERSIÓN PREVIA ---*/

	//CONSULTA CODIGO FISCALIZACIÓN
	function consultarCodigoFiscalizacion($codigoFiscalizacion, $idusuario)
	/**
	* Función que devuelve la información asociada al codigo de fiscalización, necesaria para el proceso de liquidación. Se asocia el id de usuario que lanza la consulta para comproborar su permiso al proceso.
	* Solo funcional si se lanza la consulta a través de un codigo de fiscalización existente
	*
	* @param integer $codigoFiscalizacion
	* @param integer $idusuario
	* @return array $fiscalizacion
	* @return boolean false - error
	*/
	{
		$this -> db -> select('F.COD_FISCALIZACION, F.COD_ASIGNACION_FISC, F.COD_CONCEPTO, CF.NOMBRE_CONCEPTO,TG.TIPOGESTION, AF.ASIGNADO_POR, AF.ASIGNADO_A, AF.NIT_EMPRESA, EMP.RAZON_SOCIAL, REG.NOMBRE_REGIONAL, EMP.CIIU, CIIU.DESCRIPCION, LIQ.EN_FIRME');
		$this -> db -> select('to_char("F"."PERIODO_INICIAL",'."'DD/MM/YYYY') AS PERIODO_INICIAL", FALSE);
		$this -> db -> select('to_char("F"."PERIODO_FINAL",'."'DD/MM/YYYY') AS PERIODO_FINAL", FALSE);
		$this -> db -> from('FISCALIZACION "F"');
		$this -> db -> join('TIPOGESTION "TG"', 'F.COD_TIPOGESTION = TG.COD_GESTION', 'left');
		$this -> db -> join('CONCEPTOSFISCALIZACION "CF"', 'F.COD_CONCEPTO = CF.COD_CPTO_FISCALIZACION', 'left');
		$this -> db -> join('ASIGNACIONFISCALIZACION "AF"' , 'F.COD_ASIGNACION_FISC = AF.COD_ASIGNACIONFISCALIZACION', 'left');
		$this -> db -> join('EMPRESA "EMP"', 'AF.NIT_EMPRESA = EMP.CODEMPRESA', 'left');
		$this -> db -> join('CIIU', 'EMP.CIIU = CIIU.CLASE', 'left');
		$this -> db -> join('REGIONAL "REG"', 'EMP.COD_REGIONAL = REG.COD_REGIONAL', 'left');
		$this -> db -> join('LIQUIDACION "LIQ"', 'F.COD_FISCALIZACION = LIQ.COD_FISCALIZACION', 'left');
		$condicion = "F.COD_FISCALIZACION ='" . $codigoFiscalizacion . "' AND (AF.ASIGNADO_POR = " . $idusuario . " OR AF.ASIGNADO_A = " . $idusuario . ")";
		$this -> db -> where($condicion);
		$resultado = $this -> db ->get();
		//#####BUGGER PARA LA CONSULTA ######
		  //$resultado = $this -> db -> last_query();
		  //echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		if ($resultado -> num_rows() > 0):
			$fiscalizacion = $resultado -> row_array();
			return $fiscalizacion;
		else:
			return FALSE;
		endif;
	}

	function consultarFiscalizaciones($nit, $razonSocial, $representante, $expediente, $concepto, $usuario)
	/**
	* Función que devuelve la información asociada a unos de los parametros de $nit, $razonSocial, $representante en la tbala fiscalización, por ende puede traer multiples fiscalizaciones que pueden estar asociadas al usuario.
	* Si se indican los datos de filtro: $expediente, $concepto, los resultados se pueden reducir considerablemente.
	*
	* @param integer $nit
	* @param integer $razonSocial
	* @param integer $representante
	* @param integer $expediente
	* @param integer $concepto
	* @param integer $usuario
	* @return array $fiscalizacion
	* @return boolean false - error
	*/
	{
		// $this -> db -> select('FISC.COD_FISCALIZACION, FISC.COD_CONCEPTO, FISC.COD_TIPOGESTION, ASIG.ASIGNADO_A, ASIG.ASIGNADO_POR, EMP.CODEMPRESA, EMP.NOMBRE_EMPRESA, EMP.REPRESENTANTE_LEGAL, CON.NOMBRE_CONCEPTO, TIP.TIPOGESTION');
		$this -> db -> select('FISC.COD_ASIGNACION_FISC, FISC.COD_FISCALIZACION, EMP.CODEMPRESA, EMP.RAZON_SOCIAL, CON.NOMBRE_CONCEPTO, TIP.TIPOGESTION');
		$this -> db -> select('to_char("FISC"."PERIODO_INICIAL",'."'DD/MM/YYYY') AS PERIODO_INICIAL", FALSE);
		$this -> db -> select('to_char("FISC"."PERIODO_FINAL",'."'DD/MM/YYYY') AS PERIODO_FINAL", FALSE);
		$this -> db -> from('FISCALIZACION "FISC"');
		$this -> db -> from('ASIGNACIONFISCALIZACION "ASIG"');
		$this -> db -> from('EMPRESA "EMP"');
		$this -> db -> from('CONCEPTOSFISCALIZACION "CON"');
		$this -> db -> from('TIPOGESTION "TIP"');
		$condicion = "FISC.COD_TIPOGESTION not in (309, 440) and FISC.COD_CONCEPTO not in (3,5) and FISC.CODIGO_PJ is NULL ";
		if($nit != ''):
			$condicion .= "and ( (EMP.CODEMPRESA = '" . $nit . "'";
			if($razonSocial == '' && $representante == '' ):
				$condicion .= ")";
			endif;
		endif;
		if($razonSocial != ''):
			if($nit != ''):
				$condicion .= " and EMP.RAZON_SOCIAL = '" . $razonSocial . "')";
			else:
				$condicion .= "and ( (EMP.RAZON_SOCIAL = '" . $razonSocial . "'";
				if($representante == '' ):
					$condicion .= ")";
				endif;
			endif;
		endif;
		if($representante != ''):
			if($nit != '' || $razonSocial != ''):
				$condicion .= " and EMP.REPRESENTANTE_LEGAL = '" . $representante . "')";
			else:
				$condicion .= "and ((EMP.REPRESENTANTE_LEGAL = '" . $representante . "'";
				if($nit == '' && $razonSocial == '' ):
					$condicion .= ")";
				endif;
			endif;
		endif;

		if($razonSocial != '' || $nit != '' || $representante != ''):
			$condicion .= ")";
		endif;
		$condicion .= " and ( FISC.COD_ASIGNACION_FISC = ASIG.COD_ASIGNACIONFISCALIZACION and ASIG.NIT_EMPRESA = EMP.CODEMPRESA and (ASIG.ASIGNADO_A = '" . $usuario . "' or ASIG.ASIGNADO_POR = '" . $usuario . "') and FISC.COD_CONCEPTO = CON.COD_CPTO_FISCALIZACION and FISC.COD_TIPOGESTION = TIP.COD_GESTION";
		if($expediente != ''):
			$condicion .= " and FISC.COD_FISCALIZACION = '" . $expediente . "'";
		endif;
		if($concepto != 'null'):
			$condicion .= " and FISC.COD_CONCEPTO = " . $concepto;
		endif;
		$condicion .= ")";

		$this -> db -> where($condicion);
		$resultado = $this -> db -> get();
		//#####BUGGER PARA LA CONSULTA ######
		 //$resultado = $this -> db -> last_query();
		 //echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		if ($resultado):
			$tmp = NULL;
			foreach($resultado -> result_array() as $fiscalizacion):
			$tmp[] = $fiscalizacion;
			endforeach;
			$datos = $tmp;
		else:
			$datos = FALSE;
		endif;
		return $datos;
	}

	//CONSULTA DE CABECERAS PARA LIQUIDACIONES
	function consultarCabeceraLiquidacion($codigoFiscalizacion, $idusuario)
	/**
	* Función que devuelve la información asociada al codigo de fiscalización, necesaria para el proceso de liquidación y que se muestra en las cabeceras de las liquidaciones. Se asocia el id de usuario que lanza la consulta para comproborar su permiso de acceso al proceso.
	* Solo funcional si se lanza la consulta a través de un codigo de fiscalización existente
	*
	* @param integer $codigoFiscalizacion
	* @param integer $idusuario
	* @return array $fiscalizacion
	* @return boolean false - error
	*/
	{
		$this -> db -> select('F.COD_FISCALIZACION, F.COD_ASIGNACION_FISC, F.COD_CONCEPTO, F.NRO_EXPEDIENTE, CF.NOMBRE_CONCEPTO, TG.TIPOGESTION, AF.ASIGNADO_POR, AF.ASIGNADO_A, AF.NIT_EMPRESA, EMP.RAZON_SOCIAL, REG.NOMBRE_REGIONAL, EMP.CIIU, CIIU.DESCRIPCION, EMP.DIRECCION, EMP.TELEFONO_FIJO, EMP.FAX, EMP.EMAILAUTORIZADO, EMP.EMPRESA_NUEVA, EMP.REPRESENTANTE_LEGAL, EMP.COD_REPRESENTANTELEGAL, MUN.NOMBREMUNICIPIO, EMP.NOM_CAJACOMPENSACION, EMP.RESOLUCION,EMP.NUM_EMPLEADOS,EMP.NRO_ESCRITURAPUBLICA, EMP.NOTARIA');
		$this -> db -> select('to_char("F"."PERIODO_INICIAL",'."'DD/MM/YYYY') AS PERIODO_INICIAL", FALSE);
		$this -> db -> select('to_char("F"."PERIODO_FINAL",'."'DD/MM/YYYY') AS PERIODO_FINAL", FALSE);
		$this -> db -> from('FISCALIZACION "F"');
		$this -> db -> from('TIPOGESTION "TG"');
		$this -> db -> from('CONCEPTOSFISCALIZACION "CF"');
		$this -> db -> from('ASIGNACIONFISCALIZACION "AF"');
		$this -> db -> from('EMPRESA "EMP"');
		$this -> db -> from('CIIU');
		$this -> db -> from('REGIONAL "REG"');
		$this -> db -> from('DEPARTAMENTO "DEP"');
		$this -> db -> from('MUNICIPIO "MUN"');
        //$this -> db -> from('LIQUIDACION "LIQ"');
		$condicion = "(F.COD_TIPOGESTION not in (309, 440) AND F.CODIGO_PJ is NULL 	AND F.COD_FISCALIZACION ='" . $codigoFiscalizacion . "' AND (AF.ASIGNADO_POR = " . $idusuario . " OR AF.ASIGNADO_A = " . $idusuario . ")) AND (F.COD_TIPOGESTION = TG.COD_GESTION 	AND F.COD_CONCEPTO = CF.COD_CPTO_FISCALIZACION 	AND F.COD_ASIGNACION_FISC = AF.COD_ASIGNACIONFISCALIZACION AND AF.NIT_EMPRESA = EMP.CODEMPRESA AND EMP.CIIU = CIIU.CLASE AND EMP.COD_REGIONAL = REG.COD_REGIONAL AND EMP.COD_DEPARTAMENTO = DEP.COD_DEPARTAMENTO AND EMP.COD_MUNICIPIO = MUN.CODMUNICIPIO AND DEP.COD_DEPARTAMENTO = MUN.COD_DEPARTAMENTO)";
		$this -> db -> where($condicion);
		$resultado = $this -> db ->get();
		//#####BUGGER PARA LA CONSULTA ######
		  //$resultado = $this -> db -> last_query();
		  //echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		if ($resultado -> num_rows() > 0):
			$fiscalizacion = $resultado -> row_array();
			return $fiscalizacion;
		else:
			return FALSE;
		endif;
	}

	//CONSULTA PARA DETERMINAR SI LA FISCALIZACIÓN YA TIENE LIQUIDACION APORTES
	function consultarLiquidacionAportes($codigoLiquidacion)
	/**
	* Función que retorna todos los registros disponibles de una liquidación en la tabla detalle para aportes parafizcales
	* @param string $codigoLiquidacion
	* @return array $detalles
	* @return boolean false - error
	*/
	{
		$resultado = $this -> db -> get_where('LIQ_APORTESPARAFISCALES_DET',  "CODLIQUIDACIONAPORTES_P = '" . $codigoLiquidacion . "'");
		// $resultado = $this -> db -> get_where('LIQ_APORTESPARAFISCALES_DET',  "CODLIQUIDACIONAPORTES_P = '1179951894110'");
		//#####BUGGER PARA LA CONSULTA ######
		 //$resultado = $this -> db -> last_query();
		 //echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		$datos = $resultado -> result_array();
		if(!empty($datos)):
			$tmp = NULL;
			foreach($datos as $detalle):
				$tmp[] = $detalle;
			endforeach;
			$datos = $tmp;
		else:
			$datos = FALSE;
		endif;
		return $datos;
	}


    //CONSULTA PARA DETERMINAR SI LA FISCALIZACIÓN YA TIENE LIQUIDACION APORTES
    function consultarLiquidacionBloqueada($codigoLiquidacion)
        /**
         * Función que retorna todos los registros disponibles de una fiscalización en la tabla liquidación
         * @param string $codigoFiscalización
         * @return array $resultado
         * @return boolean false - error
         */
    {

        $this->db->trans_begin();
        $this->db->trans_strict(TRUE);
        $this -> db -> select('NUM_LIQUIDACION');
        $this -> db -> from('LIQUIDACION');
        $condicion = "COD_FISCALIZACION = '" . $codigoLiquidacion . "' ORDER BY NUM_LIQUIDACION DESC";
        $this -> db -> where($condicion);
        $datos = $this -> db -> get();
        //#####BUGGER PARA LA CONSULTA ######
        //$datos = $this -> db -> last_query();
        //echo $datos; die();
        //#####BUGGER PARA LA CONSULTA ######
        $registros = $datos -> num_rows();

        if ($this -> db -> trans_status() === FALSE) :

            $this -> db -> trans_rollback();
            return $datos = FALSE;

        else:

            $this -> db -> trans_commit();
            $datos = $datos -> row_array();
            if($registros > 1):

                return $resultado = $datos;

            else:

                return $datos = FALSE;

            endif;

        endif;

    }

	//CONSULTA PARA INFORMACIÓN DE DOCUMENTACIÓN EN LIQUIDACION APORTES
	function consultarLiquidacionAportesDetalle($codigoLiquidacion)
	/**
	* Función que retorna todos la información de documentación disponibles de una liquidación en la tabla maestra
	* @param string $codigoLiquidacion
	* @return array $detalles
	* @return boolean false - error
	*/
	{
		$this -> db -> select('OBSERVACIONES, DOCU_APORTADA');
		$this -> db -> from('LIQ_APORTESPARAFISCALES');
		$this -> db -> where('CODLIQUIDACIONAPORTES_P',  $codigoLiquidacion);
		$resultado = $this -> db -> get();
		//#####BUGGER PARA LA CONSULTA ######
		// $resultado = $this -> db -> last_query();
		// echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		if ($resultado -> num_rows() > 0):
			$detalles = $resultado -> row_array();
			return $detalles;
		else:
			return FALSE;
		endif;
	}

	function consultarLiquidacionFic($codigoLiquidacion)
	/**
	* Función que retorna si existe una liquidación Fic  en el maestro de liquidaciones
	* @param string $codigoLiquidacion
	* @return array $datos
	* @return boolean false - error
	*/
	{
		$resultado = $this -> db -> get_where('LIQUIDACION_FIC',  "NRO_EXPEDIENTE = '" . $codigoLiquidacion . "'");
		//#####BUGGER PARA LA CONSULTA ######
		// $resultado = $this -> db -> last_query();
		// echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		$datos = $resultado -> result_array();
		if(!empty($datos)):
			return $datos;
		else:
			return $datos = FALSE;
		endif;
	}

	function consultarLiquidacionMulta($codigoLiquidacion)
	/**
	* Funcion que retorna si existe una liquidación en la tabla maestra de Multas de Ministerio
	* @param string $codigoLiquidacion
	* @return array $datos
	* @return boolean false - error
	*/
	{
		$resultado = $this -> db -> get_where('INTERES_MULTAMIN_ENC',  "COD_INTERES_MULTA_MIN = '" . $codigoLiquidacion . "'");
		//#####BUGGER PARA LA CONSULTA ######
		// $resultado = $this -> db -> last_query();
		// echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		$datos = $resultado -> result_array();
		if(!empty($datos)):
			return $datos;
		else:
			return $datos = FALSE;
		endif;
	}

       	 function cargarLiquidacionFic($liquidacion,  $liquidacion_previa)
       	 /**
       	 * Función que inserta valores en la tabla maestra y detalle de liquidacion FIC
       	 * Responde si la transacción fue exitosa, de no serlo realiza un rollback sobre la tabla.
       	 *
       	 * @param array $liquidacion
       	 * @param int $liquidacion_previa
       	 * @return boolean true - exito
       	 * @return string last_query - error
       	 */
        	{
        		$presuntiva=$liquidacion['detalle_presuntiva'];
                	$this->db->trans_begin();
		$this->db->trans_strict(TRUE);
		$this -> db -> set ('CODEMPRESA', $liquidacion['nitEmpresa']);
		$this -> db -> set ('CODTIPOLIQUIDACION', $liquidacion['tipoLiquidacion'], FALSE);
	               $this -> db -> set ('FECHALIQUIDACION', "TO_DATE('". $liquidacion['fechaLiquidacion'] ."','DD/MM/YYYY')", FALSE);
	               $this -> db -> set ('VALORINVERSION', $liquidacion['valorInversion'], FALSE);
	               $this -> db -> set ('GASTOSFINANCIACION', $liquidacion['gastosFinanciacion'], FALSE);
	               $this -> db -> set ('VALORLOTE', $liquidacion['valorLote'], FALSE);
	               $this -> db -> set ('INDEM_TERCEROS', $liquidacion['indenmizacion'], FALSE);
	               $this -> db -> set ('PERI_INICIAL', "TO_DATE('".$liquidacion['periodoInicial']."','DD/MM/YYYY')", FALSE);
	               $this -> db -> set ('PERI_FINAL', "TO_DATE('".$liquidacion['periodoFinal']."','DD/MM/YYYY')", FALSE);
	               $this -> db -> set ('VALOR_FIC', $liquidacion['valorFic'], FALSE);
	               $this -> db -> set ('INTERESES_FIC', $liquidacion['interesesFic'], FALSE);
	               $this -> db -> set ('VALOR_TOTAL_FIC', $liquidacion['valorTotalFic'], FALSE);
	               $this -> db -> set ('COD_FUNCIONARIO', $liquidacion['codFuncionario'], FALSE);
	               // $this -> db -> set ('CODLIQUIDACIONAPORTES_P', 1, FALSE);
	               $this -> db -> set ('COD_FISCALIZACION', $liquidacion['fiscalizacion'], FALSE);
	               $this -> db -> set ('NRO_EXPEDIENTE',$liquidacion['id']);

	             if($liquidacion_previa == 0):
				$this -> db -> set ('CODLIQUIDACIONFIC', $liquidacion['id']);
				$resultado = $this -> db -> insert('LIQUIDACION_FIC');
			else:
				$this->db->where('CODLIQUIDACIONFIC', $liquidacion['id']);
				$resultado = $this->db->update('LIQUIDACION_FIC');
			endif;


	                        $datos_fecha_inicial = explode("/", $liquidacion['periodoInicial']);
	                        $anno_fecha_inicial = (int) $datos_fecha_inicial[2];
	                        $datos_fecha_final = explode("/", $liquidacion['periodoFinal']);
	                        $anno_fecha_final = (int) $datos_fecha_final[2];
	                        //detalle por año
	                        $normativa=$liquidacion['detalle_normativa'];
	                        $presuntiva=$liquidacion['detalle_presuntiva'];
	                        for($i=$anno_fecha_inicial; $i<=$anno_fecha_final; $i++):
	        		//insertar detalle normativa
	                                        $this -> db -> set ('ANO',$i, FALSE);
	                                        $this -> db -> set ('NRO_TRABAJADORES', $normativa['empleados']['empleados_'.$i], FALSE);
	                                        $this -> db -> set ('TOTAL_ANO', number_format($normativa['valor_anno']['valor_total_'.$i], 2, '.', ''), FALSE );
	        				$this -> db -> set ('MESCOBRO',1, FALSE);
	                                        $this -> db -> set ('CODLIQUIDACIONFIC',$liquidacion['id']);
	                                        $resultado = $this -> db -> insert('LIQ_FIC_NORMATIVA');
	                             //insertar detalle presuntiva
	        			$this -> db -> set ('COD_TIPOLIQ_PRESUNTIVA', 1, FALSE);
	                                        	$this -> db -> set ('VLR_CONTRATO_TODOCOSTO', number_format($presuntiva['ValorContratoCosto_'.$i], 2, '.', ''), FALSE );
	        			$this -> db -> set ('VLR_CONTRATO_MANO_OBRA', number_format($presuntiva['ValorContratoObra_'.$i], 2, '.', ''), FALSE);
	        			$this -> db -> set ('PAGOS_FIC_DESCONTAR', number_format(0, 2, '.', ''), FALSE);
	                                        	$this -> db -> set ('COD_LIQ_PRESUNTIVA',$liquidacion['id']);
	                                        	$this -> db -> set ('CODLIQUIDACIONFIC',$liquidacion['id']);
	        			$resultado=$this -> db -> insert('LIQ_FIC_PRESUNTIVA');
	                      endfor;
			//verificacion de la transacción
		if ($this -> db -> trans_status() === FALSE) :
			$this -> db ->trans_rollback();
			return $this -> db -> last_query();
		else:
			$this -> db -> trans_commit();
			return TRUE;
		endif;
	}

	function cargarLiquidacion($liquidacion,  $liquidacion_previa)
	/**
	* Función que inserta valores en la tabla liquidacion
	* Responde si la transacción fue exitosa, de no serlo realiza un rollback sobre la tabla.
	*
	* @param array $liquidacion
	* @param int $liquidacion_previa
	* @return boolean true - exito
	* @return string last_query - error
	*/
	{
		$this->db->trans_begin();
		$this->db->trans_strict(TRUE);
		$this -> db -> set ('COD_CONCEPTO', $liquidacion['codigoConcepto'], FALSE);
		$this -> db -> set ('NITEMPRESA', $liquidacion['nitEmpresa']);
		$this -> db -> set ('FECHA_INICIO', "TO_DATE('". $liquidacion['fechaInicio'] ."','DD/MM/YYYY')", FALSE);
		$this -> db -> set ('FECHA_FIN', "TO_DATE('". $liquidacion['fechaFin'] ."','DD/MM/YYYY')", FALSE);
		$this -> db -> set ('FECHA_LIQUIDACION', "TO_DATE('". $liquidacion['fechaLiquidacion'] ."','DD/MM/YYYY')", FALSE);
		$this -> db -> set ('FECHA_VENCIMIENTO', "TO_DATE('". $liquidacion['fechaVencimiento'] ."','DD/MM/YYYY')", FALSE);
		$this -> db -> set ('TOTAL_LIQUIDADO', $liquidacion['totalLiquidado'], FALSE);
		$this -> db -> set ('TOTAL_INTERESES', $liquidacion['totalInteres'], FALSE);
		$this -> db -> set ('SALDO_DEUDA', $liquidacion['saldoDeuda'], FALSE);
		$this -> db -> set ('TOTAL_CAPITAL', $liquidacion['totalCapital'], FALSE);
		$this -> db -> set ('COD_TIPOPROCESO', $liquidacion['tipoProceso'], FALSE);
		$this -> db -> set ('COD_FISCALIZACION', $liquidacion['codigoFiscalizacion']);
        $this -> db -> set ('SALDO_CAPITAL', $liquidacion['totalCapital'], FALSE);
        $this -> db -> set ('SALDO_INTERES', $liquidacion['totalInteres'], FALSE);

        if ($liquidacion['codigoConcepto'] == '5'):
            $this -> db -> set ('FECHA_RESOLUCION', "TO_DATE('". $liquidacion['fechaLiquidacion'] ."','DD/MM/YYYY')", FALSE);
            $this -> db -> set ('FECHA_EJECUTORIA', "TO_DATE('". $liquidacion['fechaLiquidacion'] ."','DD/MM/YYYY')", FALSE);
        endif;

		if ($liquidacion['codigoConcepto'] == '5' || $liquidacion['codigoConcepto'] == '3' ):
			$this -> db -> set ('EN_FIRME', 'S');
		endif;
		if ($liquidacion_previa == 0):
			$this -> db -> set ('NUM_LIQUIDACION', $liquidacion['numeroLiquidacion']);
			$resultado = $this -> db -> insert('LIQUIDACION');
		else:
			$this->db->where('NUM_LIQUIDACION', $liquidacion['numeroLiquidacion']);
			$resultado = $this->db->update('LIQUIDACION');
		endif;
		//#####BUGGER PARA LA CONSULTA ######
		// $resultado = $this -> db -> last_query();
		// echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		//verificacion de la transacción
		if ($this -> db -> trans_status() === FALSE) :
			$this -> db -> trans_rollback();
			return $this -> db -> last_query();
		else:
			$this -> db -> trans_commit();
			return TRUE;
		endif;
	}

	function consultarLiquidacion($codigoLiquidacion)
	/**
	* Función que retorna valores en la tabla liquidacion de un numero de liquidación específico
	* Responde si la transacción fue exitosa, de no serlo realiza un rollback sobre la tabla.
	*
	* @param array $liquidacion
	* @param int $liquidacion_previa
	* @return boolean true - exito
	* @return boolean false - error
	*/
	{
		$this->db->trans_begin();
		$this->db->trans_strict(TRUE);
		$this -> db -> select('LIQUIDACION.COD_CONCEPTO, LIQUIDACION.NITEMPRESA, LIQUIDACION.NUM_LIQUIDACION, LIQUIDACION.COD_FISCALIZACION, CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO, EMPRESA.RAZON_SOCIAL, LIQUIDACION.TOTAL_CAPITAL, LIQUIDACION.TOTAL_INTERESES, LIQUIDACION.TOTAL_LIQUIDADO');
		$this -> db -> select('to_char("LIQUIDACION"."FECHA_VENCIMIENTO", '."'DD/MM/YYYY') AS FECHA_VENCIMIENTO", FALSE);
		$this -> db -> from('LIQUIDACION');
		$this -> db -> from('CONCEPTOSFISCALIZACION');
		$this -> db -> from('EMPRESA');
		$this -> db -> where( 'LIQUIDACION.NUM_LIQUIDACION' , $codigoLiquidacion );
		$this -> db -> where( 'LIQUIDACION.COD_CONCEPTO = CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION' );
		$this -> db -> where( 'LIQUIDACION.NITEMPRESA = EMPRESA.CODEMPRESA' );
		$resultado = $this->db->get();
		//#####BUGGER PARA LA CONSULTA ######
		// $resultado = $this -> db -> last_query();
		// echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		if ($resultado -> num_rows() >= 0):
			$liquidacion = $resultado -> row_array();
			return $liquidacion;
		else:
			return FALSE;
		endif;
	}

	function cargarLiquidacionAporte($liquidacion,  $liquidacion_previa)
	/**
	* Función que inserta valores en la tabla maestro y detalle de liquidacion de aportes
	* Responde si la transacción fue exitosa, de no serlo realiza un rollback sobre la tabla.
	*
	* @param array $liquidacion
	* @param int $liquidacion_previa
	* @return boolean true - exito
	* @return string last_query - error
	*/
	{
		$this->db->trans_begin();
		$this->db->trans_strict(TRUE);
		$this -> db -> set ('CODEMPRESA', $liquidacion['nitEmpresa']);
		$this -> db -> set ('FECHALIQUIDACION', "TO_DATE('". $liquidacion['fechaLiquidacion'] ."','DD/MM/YYYY')", FALSE);
		$this -> db -> set ('TOTAL_SENA', $liquidacion['totalCapital'], FALSE);
		$this -> db -> set ('TOTALINTERESES', $liquidacion['totalInteres'], FALSE);
		$this -> db -> set ('TOTAL_APORTES', $liquidacion['totalAportes'], FALSE);
		$this -> db -> set ('PAG_APORTES_DESCONTAR', $liquidacion['pagoAportes'], FALSE);
		$this -> db -> set ('INTERESES', $liquidacion['intereses'], FALSE);
		$this -> db -> set ('TOTAL_DEUDA', $liquidacion['saldoDeuda'], FALSE);
		$this -> db -> set ('ENTIDAD_PUBLICA', $liquidacion['entidadPublica']);
		$this -> db -> set ('COD_FISCALIZACION', $liquidacion['codigoFiscalizacion']);
		if($liquidacion_previa == 0):
			$this -> db -> set ('CODLIQUIDACIONAPORTES_P', $liquidacion['numeroLiquidacion']);
			$resultado = $this -> db -> insert('LIQ_APORTESPARAFISCALES');
		else:
			$this->db->where('CODLIQUIDACIONAPORTES_P', $liquidacion['numeroLiquidacion']);
			$resultado = $this->db->update('LIQ_APORTESPARAFISCALES');
		endif;

		//verificacion de la transacción
		if ($this -> db -> trans_status() === FALSE) :
			$this -> db ->trans_rollback();
			return $this -> db -> last_query();
		else:
			$this -> db -> trans_commit();
			return TRUE;
		endif;
	}

	function actualizarLiquidacionAporte($adjuntos)
	/**
	* Función que actualiza los datos de observaciones y documentación en la tabla de liquidaciones aportes
	* Responde si la transacción fue exitosa, de no serlo realiza un rollback sobre la tabla.
	*
	* @param array $adjuntos
	* @return boolean true - exito
	* @return string last_query - error
	*/
	{
		$this->db->trans_begin();
		$this->db->trans_strict(TRUE);
		$this -> db -> set ('OBSERVACIONES', $adjuntos['observaciones']);
		$this -> db -> set ('DOCU_APORTADA', $adjuntos['documentacion']);
		$this->db->where('CODLIQUIDACIONAPORTES_P', $adjuntos['liquidacion']);
		$resultado = $this->db->update('LIQ_APORTESPARAFISCALES');
		//verificacion de la transacción
		if ($this -> db -> trans_status() === FALSE) :
			$this -> db ->trans_rollback();
			return $this -> db -> last_query();
		else:
			$this -> db -> trans_commit();
			return TRUE;
		endif;
	}

	function cargarLiquidacionAporteDetalle($liquidacion,  $liquidacion_previa)
	/**
	* Función que inserta los valores anuales sobre la tabla de detalle para liquidaciones parafiscales.
	* Responde si la transacción fue exitosa, de no serlo realiza un rollback sobre la tabla.
	*
	* @param int $ano
	* @param int $sueldos
	* @param int $valorsobresueldos
	* @param int $salariointegral
	* @param int $salarioespecie
	* @param int $supernumerarios
	* @param int $jornales
	* @param int $auxiliotransporte
	* @param int $horasextras
	* @param int $dominicales_festivos
	* @param int recargonocturno
	* @param int $viaticos
	* @param int $bonificaciones
	* @param int $comisiones
	* @param int $por_sobreventas
	* @param int $vacaciones
	* @param int $trab_domicilio
	* @param int $prima_tec_salarial
	* @param int $auxilio_alimentacion
	* @param int $prima_servicio
	* @param int $prima_localizacion
	* @param int $prima_vivienda
	* @param int $gast_representacion
	* @param int $prima_antiguedad
	* @param int $prima_extralegales
	* @param int $prima_vacaciones
	* @param int $prima_navidad
	* @param int $contratos_agricolas
	* @param int $remu_socios_industriales
	* @param int $hora_catedra
	* @param int $otros_pagos,
	* @param int $subcontrato,
	* @param string $codliquidacionaportes_p
	* @param int $liquidacion_previa
	* @return boolean true - exito
	* @return string last_query - error
	*/
	{
		$this -> db -> trans_begin();
		$this -> db -> trans_strict(TRUE);
		$this -> db -> set ('VALORSUELDOS', $liquidacion['sueldos'], FALSE);
		$this -> db -> set ('VALORSOBRESUELDOS', $liquidacion['sobresueldos'], FALSE);
		$this -> db -> set ('SALARIOINTEGRAL', $liquidacion['salarioIntegral'], FALSE);
		$this -> db -> set ('SALARIOESPECIE', $liquidacion['salarioEspecie'], FALSE);
		$this -> db -> set ('SUPERNUMERARIOS', $liquidacion['supernumerarios'], FALSE);
		$this -> db -> set ('JORNALES', $liquidacion['jornales'], FALSE);
		$this -> db -> set ('AUXILIOTRANSPORTE', $liquidacion['auxilioTransporte'], FALSE);
		$this -> db -> set ('HORASEXTRAS', $liquidacion['horasExtras'], FALSE);
		$this -> db -> set ('DOMINICALES_FESTIVOS', $liquidacion['dominicales'], FALSE);
		$this -> db -> set ('RECARGONOCTURNO', $liquidacion['recargoNocturno'], FALSE);
		$this -> db -> set ('VIATICOS', $liquidacion['viaticos'], FALSE);
		$this -> db -> set ('BONIFICACIONES', $liquidacion['bonificacionesHabituales'], FALSE);
		$this -> db -> set ('COMISIONES', $liquidacion['comisiones'], FALSE);
		$this -> db -> set ('POR_SOBREVENTAS', $liquidacion['porcentajeVentas'], FALSE);
		$this -> db -> set ('VACACIONES', $liquidacion['vacaciones'], FALSE);
		$this -> db -> set ('TRAB_DOMICILIO', $liquidacion['trabajoDomicilio'], FALSE);
		$this -> db -> set ('PRIMA_TEC_SALARIAL', $liquidacion['primaTecnicaSalarial'], FALSE);
		$this -> db -> set ('AUXILIO_ALIMENTACION', $liquidacion['auxilioAlimentacion'], FALSE);
		$this -> db -> set ('PRIMA_SERVICIO', $liquidacion['primaServicios'], FALSE);
		$this -> db -> set ('PRIMA_LOCALIZACION', $liquidacion['primaLocalizacion'], FALSE);
		$this -> db -> set ('PRIMA_VIVIENDA', $liquidacion['primaVivienda'], FALSE);
		$this -> db -> set ('GAST_REPRESENTACION', $liquidacion['gastosRepresentacion'], FALSE);
		$this -> db -> set ('PRIMA_ANTIGUEDAD', $liquidacion['primaAntiguedad'], FALSE);
		$this -> db -> set ('PRIMA_EXTRALEGALES', $liquidacion['primaProductividad'], FALSE);
		$this -> db -> set ('PRIMA_VACACIONES', $liquidacion['primaVacaciones'], FALSE);
		$this -> db -> set ('PRIMA_NAVIDAD', $liquidacion['primaNavidad'], FALSE);
		$this -> db -> set ('CONTRATOS_AGRICOLAS', $liquidacion['contratosAgricolas'], FALSE);
		$this -> db -> set ('REMU_SOCIOS_INDUSTRIALES', $liquidacion['remuneracionSocios'], FALSE);
		$this -> db -> set ('HORA_CATEDRA', $liquidacion['horaCatedra'], FALSE);
		$this -> db -> set ('OTROS_PAGOS', $liquidacion['otrosPagos'], FALSE);
		$this -> db -> set ('SUBCONTRATO', $liquidacion['subcontratos'], FALSE);
		if($liquidacion_previa == 0):
			$this -> db -> set ('ANO', $liquidacion['ano'], FALSE);
			$this -> db -> set ('CODLIQUIDACIONAPORTES_P', $liquidacion['liquidacion']);
			$resultado = $this -> db -> insert('LIQ_APORTESPARAFISCALES_DET');
		else:
			$this -> db -> where ('ANO', $liquidacion['ano'], FALSE);
			$this -> db -> where('CODLIQUIDACIONAPORTES_P', $liquidacion['liquidacion']);
			$resultado = $this -> db -> update('LIQ_APORTESPARAFISCALES_DET');
		endif;

		//#####BUGGER PARA LA CONSULTA ######
		// $resultado = $this -> db -> last_query();
		// echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		//verificacion de la transacción
		if ($this -> db -> trans_status() === FALSE) :
			$this -> db ->trans_rollback();
			return $this -> db -> last_query();
		else:
			$this -> db -> trans_commit();
			return TRUE;
		endif;
	 }

	function getCabecerasMultasMinisterio($codigoMulta)
	/**
	* Funcion que devuelve la información asociada a la multa de ministerio consultada a través del código asociado
	* Solo funcional si se lanza la consulta a través de un codigo de multa existente
	*
	* @param integer $codigoMulta
	* @return array $empresa
	* @return boolean false - error
	*/
	{
		$this -> db -> select('MUL.COD_MULTAMINISTERIO, EMP.CODEMPRESA, EMP.RAZON_SOCIAL, REG.NOMBRE_REGIONAL, MUL.NRO_RESOLUCION, MUL.VALOR, MUL.RESPONSABLE, TIP.TIPOGESTION');
		$this -> db -> select('to_char("MUL"."FECHA_CREACION", '."'DD/MM/YYYY') AS FECHA_CREACION", FALSE);
		$this -> db -> select('to_char("MUL"."FECHA_EJECUTORIA", '."'DD/MM/YYYY') AS FECHA_EJECUTORIA", FALSE);
		$this -> db -> from('MULTASMINISTERIO "MUL"');
		$this -> db -> from('EMPRESA "EMP"');
		$this -> db -> from('REGIONAL "REG"');
		$this -> db -> from('GESTIONCOBRO "GES"');
		$this -> db -> from('TIPOGESTION "TIP"');
		$this -> db -> where('MUL.NIT_EMPRESA = EMP.CODEMPRESA');
		$this -> db -> where('EMP.COD_REGIONAL = REG.COD_REGIONAL');
		$this -> db -> where('MUL.COD_GESTION_COBRO = GES.COD_GESTION_COBRO');
		$this -> db -> where('GES.COD_TIPOGESTION = TIP.COD_GESTION');
		$this -> db -> where('MUL.COD_MULTAMINISTERIO', $codigoMulta);
		$resultado = $this->db->get();
		//#####BUGGER PARA LA CONSULTA ######
		// $resultado = $this -> db -> last_query();
		// echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		if ($resultado -> num_rows() >= 0):
			$empresa = $resultado -> row_array();
			return $empresa;
		else:
			return FALSE;
		endif;
	}

	function cargarMultaMinisterio($maestro, $detalle, $liquidacion_previa)
	/**
	* Función que almacena en DB los registros generados como maestro - detalle de las liquidaciones de una multa de ministerio
	* Inicia la transacción de la inserción en el encabezado, consulta el ID de la secuencia y se lo asigna al detalle.
	*
	* @param array $maestro
	* @param array $detalle
	* @param int $liquidacion_previa
	* @return boolean true
	* @return string $last_query - error
	*/
	{
		$this->db->trans_begin();
		$this->db->trans_strict(TRUE);
		//insertar encabezado intereses
		$this -> db -> set('COD_MULTAMINISTERIO', $maestro['codigo_multa']);
		$this -> db -> set('VALOR_CAPITAL', $maestro['valor_multa'], FALSE);
		$this -> db -> set ('FECHA_ELABORACION', "TO_DATE('". $maestro['fecha_elaboracion'] ."','DD/MM/YY')", FALSE);
		$this -> db -> set ('FECHA_EJECUTORIA', "TO_DATE('". $maestro['fecha_ejecutoria'] ."','DD/MM/YY')", FALSE);
		$this -> db -> set ('FECHA_LIQUIDACION', "TO_DATE('". $maestro['fecha_liquidacion'] ."','DD/MM/YY')", FALSE);
		$this -> db -> set('TOTAL_DIAS_MORA', $maestro['total_dias_mora'], FALSE);
		$this -> db -> set('TOTAL_CAPITAL', $maestro['total_capital'], FALSE);
		$this -> db -> set('TOTAL_INTERESES', $maestro['total_interes'], FALSE);
		$this -> db -> set('VALOR_TOTAL', $maestro['total_valor'],FALSE);

		if($liquidacion_previa == 0):
			$this -> db -> set ('COD_INTERES_MULTA_MIN', $maestro['codigo_multa']);
			$this -> db -> insert('INTERES_MULTAMIN_ENC');
		else:
			$this -> db -> set ('COD_INTERES_MULTA_MIN', $maestro['codigo_multa']);
			$this -> db -> where('COD_INTERES_MULTA_MIN', $maestro['codigo_multa']);
			$this -> db -> update('INTERES_MULTAMIN_ENC');
		endif;

		//Adquirir cod_interes_multamin_enc
		$id = $maestro['codigo_multa'];

		//insertar detalles mes a mes
		foreach ($detalle as $linea):
			$this -> db -> set ('VALOR_CAPITAL', number_format($linea['valorCapital'], 2, '.', ''), FALSE );
			$this -> db -> set ('VALOR_INTERESES', number_format($linea['valorInteres'], 2, '.', ''), FALSE);
			$this -> db -> set ('VALOR_TOTAL', number_format($linea['valorTotal'], 2, '.', ''), FALSE);
			// foreach($linea as $value):
			// endforeach;
			if($liquidacion_previa == 0):
				$this -> db -> set ('MES', $linea['mes']);
				$this -> db -> set ('ANNO', $linea['anno']);
				$this -> db -> set ('COD_INTERES_MULTAMIN', $id);
				$this -> db -> insert('INTERESES_MULTAMIN_DET');
			else:
				$this -> db -> select ('COD_INTERES_MULTAMIN, MES, ANNO');
				$this -> db -> from ('INTERESES_MULTAMIN_DET');
				$this -> db -> where ('COD_INTERES_MULTAMIN', $id);
				$this -> db -> where ('MES', $linea['mes']);
				$this -> db -> where ('ANNO', $linea['anno']);
				$resultado = $this -> db -> get();
				if ($resultado -> num_rows() > 0):
					$this -> db -> where ('COD_INTERES_MULTAMIN', $id);
					$this -> db -> where ('MES', $linea['mes']);
					$this -> db -> where ('ANNO', $linea['anno']);
					$this -> db -> update('INTERESES_MULTAMIN_DET');
				else:
					$this -> db -> set ('MES', $linea['mes']);
					$this -> db -> set ('ANNO', $linea['anno']);
					$this -> db -> set ('COD_INTERES_MULTAMIN', $id);
					$this -> db -> insert('INTERESES_MULTAMIN_DET');
				endif;
				//#####BUGGER PARA LA CONSULTA ######
				// $resultado = $this -> db -> last_query();
				// echo $resultado; die();
				//#####BUGGER PARA LA CONSULTA ######
			endif;
		endforeach;
		//verificacion de la transacción
		if ($this -> db -> trans_status() === FALSE) :
			$this -> db ->trans_rollback();
			return $this -> db -> last_query();
		else:
			$this -> db -> trans_commit();
			return TRUE;
		endif;
	}

	function getCombinacionTipoAportante()
	/**
	* Función que consulta los tipos de combinación de correspondencia para aportantes
	* Esta función provee los tipos para mostrar las opciones en pantalla y si no se han cargado aún por parametrización devuelve error
	*
	* @return array $tipos
	* @return string $tipos - error
	*/
	{
		$this -> db -> select('COD_COMB_TIP_APORTANTE, DESCRIPCION_COMB_TIPO');
		$this -> db -> order_by('COD_COMB_TIP_APORTANTE','ASC');
		$resultado = $this -> db -> get('COMBINACION_TIPO_APORTANTE');

		if($resultado -> num_rows() > 0):
			$tipos = $resultado -> result_array();
			return $tipos;
		else:
			$tipos = 'Consulta sin datos en los tipos de combinación';
		endif;
	}

	function getCombinacionRespuesta($codigoCombinacion)
	/**
	* Función que retorna la respuesta según el codigo de la combinación seleccionado por el usuario
	* Esta función provee la respuesta en HTML almacendao en la DB
	*
	* @param integer $codigoCombinacion
	* @return array $respuesta
	* @return boolean False - error
	*/
	{
		$this -> db -> select('COD_COMB_TIP_APORTANTE, TEXTO_COMBINATORIO');
		$this -> db -> from('COMBINACION_TIPO_APORTANTE');
		$this -> db -> where('COD_COMB_TIP_APORTANTE',$codigoCombinacion);
		$resultado = $this -> db -> get();
		if ($resultado -> num_rows() > 0):
		$respuesta = $resultado -> row_array();
			return $respuesta;
		else:
			return FALSE;
		endif;
	}

	function getFechaVisita($codigoGestion)
	/**
	* Función que retorna la fecha en la cual fue visitada la empresa para la generación de la comunicación de aportante
	* Si la empresa consultada no tiene fecha de visita devuelve error
	*
	* @param integer $codigoGestion
	* @return array $fecha
	* @return boolean False - error
	*/
	{
		$this -> db -> select('INF.COD_GESTION_COBRO');
		$this->db->select('to_char("INF"."FECHA_DOCUMENTO", ' . "'DD/MM/YYYY') AS FECHA_DOCUMENTO", FALSE);
		$this -> db ->from ('INFORMEVISITA "INF"');
		$this->db->join('GESTIONCOBRO "GC"', 'GC.COD_GESTION_COBRO = INF.COD_GESTION_COBRO');
		$this->db->where('GC.COD_FISCALIZACION_EMPRESA', $codigoGestion);
		$resultado = $this -> db -> get();
		//#####BUGGER PARA LA CONSULTA ######
		// $resultado = $this -> db -> last_query();
		// echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		if ($resultado -> num_rows() > 0):
			$fecha = $resultado -> row_array();
			return $fecha;
		else:
			return FALSE;
		endif;
	}

	function getInfoUsuarios($usuario)
	/**
	* Función que retorna la información asociada al usuario logeado.
	* Esta función no debe retornar error, pues no se pueden logear usuarios no identificados en la DB
	*
	* @param integer $usuario
	* @return array $usuario
	* @return boolean False - error
	*/
	{
		$this -> db ->select('USU.IDUSUARIO, USU.COD_REGIONAL, REG.COD_REGIONAL, REG.NOMBRE_REGIONAL, REG.CEDULA_DIRECTOR, fn_Nombre_Usuario(CEDULA_DIRECTOR) AS Nombre_Director, REG.CEDULA_COORDINADOR_RELACIONES, fn_Nombre_Usuario(CEDULA_COORDINADOR_RELACIONES) AS NOMBRE_COORDINADOR_RELACIONES, REG.COD_CIUDAD, MUN.NOMBREMUNICIPIO');
		$this -> db -> from ('USUARIOS "USU"');
		$this -> db -> from ('REGIONAL "REG"');
		$this -> db -> from ('MUNICIPIO "MUN"');
		$this -> db -> where('USU.COD_REGIONAL = REG.COD_REGIONAL');
		$this -> db -> where('REG.COD_REGIONAL = MUN.CODMUNICIPIO');
		$this -> db -> where('USU.IDUSUARIO',$usuario);
		$resultado = $this -> db -> get();
		//#####BUGGER PARA LA CONSULTA ######
		// $resultado = $this -> db -> last_query();
		// echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		if ($resultado -> num_rows() > 0):
	 		$usuario = $resultado -> row_array();
			return $usuario;
		else:
			return FALSE;
		endif;
	}

	function getEmpresa($nitEmpresa)
	/**
	* Función que retorna la información de la empresa consultadapor nit
	* Si la empresa consultada no se encuentra registrada en la DB, reporta un error lógico
	*
	* @param integer $nitEmpresa
	* @return array $empresa
	* @return boolean False - error
	*/
	{
		$this -> db -> select ('EMP.CODEMPRESA, EMP.RAZON_SOCIAL, EMP.DIRECCION, EMP.TELEFONO_FIJO, EMP.REPRESENTANTE_LEGAL, EMP.RAZON_SOCIAL, EMP.COD_REGIONAL, EMP.COD_PAIS, EMP.COD_DEPARTAMENTO, EMP.COD_MUNICIPIO, MUN.CODMUNICIPIO, MUN.NOMBREMUNICIPIO, DEP.COD_DEPARTAMENTO, DEP.NOM_DEPARTAMENTO');
		$this -> db -> from('EMPRESA "EMP"');
		$this-> db -> join('MUNICIPIO "MUN"','EMP.COD_MUNICIPIO = MUN.CODMUNICIPIO');
		$this-> db -> join('DEPARTAMENTO "DEP"','EMP.COD_DEPARTAMENTO = DEP.COD_DEPARTAMENTO');
		// $this-> db -> join('PAIS "PAI"','EMP.COD_PAIS = PAI.CODPAIS');
		$this -> db -> where ('CODEMPRESA', $nitEmpresa);
		$this -> db -> where ('EMP.COD_DEPARTAMENTO = MUN.COD_DEPARTAMENTO');
		$resultado = $this -> db -> get();
		if ($resultado -> num_rows() > 0):
		$empresa = $resultado -> row_array();
			return $empresa;
		else:
			return FALSE;
		endif;
	}

	function getCabecerasSoportesLiquidacion($codigoConcepto)
	/**
	* Función que retorna la información asociada al soporte de liquidación consultado por el codigo de gestión
	* Si el código de concepto no tiene liquidaciones asociadas no deberia reportar datos (PENDIENTE)
	*
	* @param integer $codigoConcepto
	* @return array $empresa
	* @return boolean False - error
	*/
	{
		$this -> db -> select('L.NUM_LIQUIDACION, L.COD_CONCEPTO, L.NITEMPRESA, L.COD_FISCALIZACION, E.RAZON_SOCIAL, C.NOMBRE_CONCEPTO');
		$this->db->select('to_char("L"."FECHA_LIQUIDACION", ' . "'DD/MM/YYYY') AS FECHA_LIQUIDACION", FALSE);
		$this -> db -> from('LIQUIDACION L');
		$this -> db -> join('EMPRESA E', 'L.NITEMPRESA = E.CODEMPRESA');
		$this -> db -> join('CONCEPTOSFISCALIZACION C','L.COD_CONCEPTO = C.COD_CPTO_FISCALIZACION');
		$this -> db -> where('L.COD_FISCALIZACION',$codigoConcepto);
		$resultado = $this->db->get();
		if ($resultado -> num_rows() > 0):
			$empresa = $resultado -> row_array();
			return $empresa;
		endif;
	}

	function loadSoportesLiquidacion($liquidacion, $nis, $fecha, $radicado, $archivo, $fiscalizador)
	/**
	* Función para cargar los soportes de liquidación en la DB y en el repo de uploads
	*
	* @param string $liquidacion
	* @param string $nis
	* @param string $fecha
	* @param string $radicado
	* @param string $archivo
	* @param string $fiscalizador
	* @return boolean $empresa
	*/
	{
		$this -> db -> set ('sl.NUM_LIQUIDACION', $liquidacion);
		$this -> db -> set ('sl.NRO_RADICADO', $radicado);
		$this -> db -> set ('sl.FECHA_RADICADO', "TO_DATE('". $fecha."','DD/MM/YYYY')", FALSE);
		$this -> db -> set ('sl.NOMBRE_ARCHIVO', $archivo);
		$this -> db -> set ('sl.NOMBRE_RADICADOR', $fiscalizador);
		$this -> db -> set ('sl.NIS', $nis);
		$resultado =  $this -> db-> insert ('SOPORTE_LIQUIDACION "sl"');
		//#####BUGGER PARA LA CONSULTA ######
		// $resultado = $this -> db -> last_query();
		// echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		return $resultado;
	}

	function getTasaParametrizada()
	/**
	* Función que retorna la información de la tasa parametrizada para los calculos de multas de ministerio y FIC
	*
	* @return array $tasaInteres
	* @return boolean False - error
	*/
	{
		$this -> db -> select ('VALOR_TASA');
		$this -> db -> from ('TASA_PARAMETRIZADO');
		$this -> db -> where('IDTASA = 3'); // Basado en el parametro creado ¡cuidado en la migración!
		$this -> db -> where('IDESTADO = 1');
		$resultado = $this -> db ->get();
		if ($resultado -> num_rows() > 0):
			$tasa = $resultado -> row_array();
			return $tasa;
		else:
			return FALSE;
		endif;
	}

	function getTasaInteresSF_actual()
	/**
	* Función que retorna la información de la ultima tasa de la superintendencia almacenada en la DB
	*
	* @return array $tasaInteres
	* @return boolean False - error
	*/
	{
		$resultado = $this -> db -> query('
		SELECT * FROM (
			SELECT s.ID_TASA_SUPERINTENDENCIA, s.TASA_SUPERINTENDENCIA, s.VIGENCIA_DESDE, s.VIGENCIA_HASTA, s.FECHACREACION
			FROM TASA_SUPERINTENDENCIA s
			ORDER BY s.FECHACREACION DESC
		)
		WHERE ROWNUM = 1');
		if ($resultado -> num_rows > 0):
			$tasaInteres = $resultado -> row_array();
			return $tasaInteres;
		endif;
	}

	function getTasaInteresSF_mes($mes, $anno)
	/**
	* Función que retorna la información de la tasa de la superintendencia para el mes y año consultado
	* Si el mes y año consultados no concuerdan con una tasa registra reporta error de tasa
	*
	* @param integer $mes
	* @param integer $anno
	* @return integer $tasa
	* @return boolean False - error
	*/
	{
		$this -> db -> select ('TASA_SUPERINTENDENCIA');
		$this -> db -> from ('TASA_SUPERINTENDENCIA');
		$this -> db -> where('VIGENCIA_DESDE >=',"to_date('". $mes . '/'. $anno."',"."'MM/YYYY')", FALSE);
		$this -> db -> where('ID_TIPO_TASA', 1);
		$this -> db -> order_by('VIGENCIA_DESDE', 'ASC');
		$resultado = $this -> db ->get();
		if ($resultado -> num_rows() > 0):
			$tasa = $resultado -> row_array();
			return $tasa;
		else:
			return FALSE;
		endif;
	}

	function getAporte_mes($nitEmpresa, $concepto, $subconcepto, $periodo)
	/**
	* Función que retorna si la empresa consultada con el nit, tiene aportes regulares por el concepto y subconcepto de pago, enviado en el periodo selecionado (yyyy-mm)
	* Si la empresa consultada no tiene aportes en el periodo retorna un 0
	*
	* @param varchar $nitEmpresa
	* @param integer $concepto
	* @param integer $subconcepto
	* @param varchar $periodo
	* @return integer $cuota
	* @return boolean false - error
	*/
	{
		$cuota = 0;
		$this -> db -> select('VALOR_PAGADO, PERIODO_PAGADO');
		$this -> db -> from('PAGOSRECIBIDOS');
		$this -> db -> where('COD_CONCEPTO', $concepto, FALSE);
		$this -> db -> where('COD_SUBCONCEPTO', $subconcepto, FALSE);
		$this -> db -> where('NITEMPRESA', $nitEmpresa);
		$this -> db -> where('PERIODO_PAGADO', $periodo);
		$resultado = $this -> db -> get();
		//#####BUGGER PARA LA CONSULTA ######
    		// $resultado = $this -> db -> last_query();
    		// echo $resultado; die();
    		//#####BUGGER PARA LA CONSULTA ######
		if ($resultado -> num_rows() > 0):
    			$cuota = $resultado -> row_array();
    			return $cuota;
    		else:
    			return FALSE;
    		endif;
	}

	function getAporte_Fic($nitEmpresa, $concepto, $subconcepto, $periodo, $tipoFic, $contrato=FALSE)
	/**
   	* Función que retorna si la empresa consultada con el nit, tiene aportes por Fic Presuntivo Anuales
   	* Si la empresa consultada no tiene aportes en el periodo retorna un 0
  	*
  	* @param varchar $nitEmpresa
  	* @param integer $concepto
  	* @param integer $subconcepto
  	* @param varchar $periodo
   	* @return integer $cuota
	*/
	{

		$cuota = 0;
		$this -> db -> select('VALOR_PAGADO, PERIODO_PAGADO');
		$this -> db -> from('PAGOSRECIBIDOS');
		$this -> db -> where('COD_CONCEPTO', $concepto, FALSE);
		$this -> db -> where('COD_SUBCONCEPTO', $subconcepto, FALSE);
		$this -> db -> where('NITEMPRESA', $nitEmpresa);
		$this -> db -> where('PERIODO_PAGADO', $periodo);
                $this -> db -> where('PERIODO_PAGADO', $tipoFic);
                if(!empty($contrato)){
                $this -> db -> where('NRO_LICENCIA_CONTRATO', $contrato);}

		$resultado = $this -> db -> get();
		//#####BUGGER PARA LA CONSULTA ######
		// $resultado = $this -> db -> last_query();
		// echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		if ($resultado -> num_rows() > 0):
			$cuota = $resultado -> row_array();
			return $cuota;
		else:
			return FALSE;
		endif;
	}

	function getLiquidacionMultaMinisterio($codigoMulta)
	/**
	* Funcion que devuelve la información asociada a la multa consultada
	* Solo funcional si se lanza la consulta a través de un codigo de multa existente
	*
	* @param integer $codigoMulta
	* @return array $liquidacion
	* @return boolean false - error
	*/
	{
		$this -> db -> select('IME.COD_INTERES_MULTA_MIN, IME.COD_MULTAMINISTERIO, IME.TOTAL_CAPITAL, IME.TOTAL_INTERESES, IME.VALOR_TOTAL, MM.NIT_EMPRESA, EMP.RAZON_SOCIAL');
		$this -> db -> select('to_char("IME"."FECHA_LIQUIDACION", '."'DD/MM/YYYY') AS FECHA_LIQUIDACION", FALSE);
		$this -> db -> from('INTERES_MULTAMIN_ENC "IME"');
		$this -> db -> join('MULTASMINISTERIO "MM"', 'IME.COD_MULTAMINISTERIO = MM.COD_MULTAMINISTERIO');
		$this -> db -> join('EMPRESA "EMP"', 'MM.NIT_EMPRESA = EMP.CODEMPRESA');
		$this -> db -> where('IME.COD_MULTAMINISTERIO', $codigoMulta, FALSE);
		$this -> db -> order_by('IME.FECHA_LIQUIDACION' , 'DESC');
		$resultado = $this->db->get();
		//#####BUGGER PARA LA CONSULTA ######
		 // $resultado = $this -> db -> last_query();
		 // echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		if ($resultado -> num_rows() > 0):
			$liquidacion = $resultado -> row_array();
			return $liquidacion;
		else:
			return FALSE;
		endif;
	}

	// FUNCIONES DE SIMULACÓN SGVA---------------------------------------------------------

	function buscarEmpresaSgva($nit)
	/**
	* Función que devuelve la información de la empresa en SGVA
	*
	* @param string $nit
	* @return array $datos
	* @return boolean false - error
	*/
	{
		$this -> db -> select('CODEMPRESA, RAZON_SOCIAL, REPRESENTANTE_LEGAL, COD_REGIONAL');
		$this -> db -> from('EMPRESA');
		$this -> db -> where('CODEMPRESA', $nit);
		$resultado = $this -> db -> get();
		//#####BUGGER PARA LA CONSULTA ######
		 // $resultado = $this -> db -> last_query();
		 // echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		if ($resultado -> num_rows() > 0):
			$empresa = $resultado -> row_array();
			return $empresa;
		else:
			return FALSE;
		endif;
	}

	function buscarRegulacionesSgva($nit, $fechaInicio, $fechaFin)
	/**
	* Función que devuelve la información de contratos de la empresa en SGVA
	*
	* @param string $nit
	* @param string $fechaInicio
	* @param string $fechaFin
	* @return array $datos
	* @return boolean false - error
	*/
	{
		$this -> db -> select('NUMERORESOLUCION, TRABAJADORES, CUOTA,');
		$this -> db -> select('to_char("FECHARESOLUCION", '."'DD/MM/YYYY') AS FECHARESOLUCION", FALSE);
		$this -> db -> select('to_char("EJECUTORIA", '."'DD/MM/YYYY') AS EJECUTORIA", FALSE);
		$this -> db -> select('to_char("FECHAINICIAL", '."'DD/MM/YYYY') AS FECHAINICIAL", FALSE);
		$this -> db -> select('to_char("FECHAFINAL", '."'DD/MM/YYYY') AS FECHAFINAL", FALSE);
		$this -> db -> from('REGULACIONES');
		$condicion = "TRUNC(FECHAINICIAL) >= TRUNC(TO_DATE('" . $fechaInicio . "', 'DD/MM/YYYY')) AND TRUNC(FECHAFINAL) <= TRUNC(TO_DATE('" . $fechaFin . "', 'DD/MM/YYYY')) AND CODEMPRESA = '" . $nit . "'";
		$this -> db -> where($condicion);
		$this -> db -> order_by('NUMERORESOLUCION', 'ASC');
		$datos = $this -> db -> get();
		//#####BUGGER PARA LA CONSULTA ######
		 // $datos = $this -> db -> last_query();
		 // echo $datos; die();
		//#####BUGGER PARA LA CONSULTA ######
		$datos = $datos -> result_array();
		if(!empty($datos)):
			$tmp = NULL;
			foreach($datos as $regulacion):
			$tmp[] = array("NUMERORESOLUCION" => $regulacion['NUMERORESOLUCION'], "TRABAJADORES" => $regulacion['TRABAJADORES'], "CUOTA" => $regulacion['CUOTA'], "FECHARESOLUCION" => $regulacion['FECHARESOLUCION'], "EJECUTORIA" => $regulacion['EJECUTORIA'], "FECHAINICIAL" => $regulacion['FECHAINICIAL'], "FECHAFINAL" => $regulacion['FECHAFINAL']);
			endforeach;
			$datos = $tmp;
		else:
			$datos = FALSE;
		endif;
		return $datos;
	}

	function buscarContratosSgva($nit, $fechaInicio, $fechaFin)
	/**
	* Función que devuelve la información de contratos de la empresa en SGVA
	*
	* @param string $nit
	* @param string $fechaInicio
	* @param string $fechaFin
	* @return array $datos
	* @return boolean false - error
	*/
	{
		$this -> db -> select('TIPOCONRATO, APRENDIZ, DOCUMENTO, TOTALDIAS');
		$this -> db -> select('to_char("ACUERDO", '."'DD/MM/YYYY') AS ACUERDO", FALSE);
		$this -> db -> select('to_char("FECHAINICIAL", '."'DD/MM/YYYY') AS FECHAINICIAL", FALSE);
		$this -> db -> select('to_char("FECHAFINAL", '."'DD/MM/YYYY') AS FECHAFINAL", FALSE);
		$this -> db -> from('CONTRATOS');
		$condicion = "TRUNC(FECHAINICIAL) >= TRUNC(TO_DATE('" . $fechaInicio . "', 'DD/MM/YYYY')) AND TRUNC(FECHAFINAL) <= TRUNC(TO_DATE('" . $fechaFin . "', 'DD/MM/YYYY')) AND CODEMPRESA = '" . $nit . "'";
		$this -> db -> where($condicion);
		$this -> db -> order_by('APRENDIZ', 'ASC');
		$datos = $this -> db -> get();
		//#####BUGGER PARA LA CONSULTA ######
		 // $datos = $this -> db -> last_query();
		 // echo $datos; die();
		//#####BUGGER PARA LA CONSULTA ######
		$datos = $datos -> result_array();
		if(!empty($datos)):
			$tmp = NULL;
			foreach($datos as $contrato):
			$tmp[] = array("TIPOCONTRATO" => $contrato['TIPOCONRATO'], "APRENDIZ" => $contrato['APRENDIZ'], "DOCUMENTO" => $contrato['DOCUMENTO'], "TOTALDIAS" => $contrato['TOTALDIAS'], "ACUERDO" => $contrato['ACUERDO'], "FECHAINICIAL" => $contrato['FECHAINICIAL'], "FECHAFINAL" => $contrato['FECHAFINAL']);
			endforeach;
			$datos = $tmp;
		else:
			$datos = FALSE;
		endif;
		return $datos;
	}

	function buscarMonetizacionSgva($nit, $fechaInicio, $fechaFin)
	/**
	* Función que devuelve la información de monetización de la empresa en SGVA
	*
	* @param string $nit
	* @param string $fechaInicio
	* @param string $fechaFin
	* @return array $datos
	* @return boolean false - error
	*/
	{
		$this -> db -> select('PERIODOPAGO, TRANSACCION, PAGONETO, INTERESES');
		$this -> db -> select('to_char("FECHAPAGO", '."'DD/MM/YYYY') AS FECHAPAGO", FALSE);
		$this -> db -> from('MONETIZACION');
		$condicion = "TO_DATE(PERIODOPAGO, 'MM-YYYY') BETWEEN TO_DATE('". $fechaInicio . "', 'MM-YYYY') AND TO_DATE('" . $fechaFin . "', 'MM-YYYY') AND CODEMPRESA = '" . $nit . "'";
		$this -> db -> where($condicion);
		$this -> db -> order_by('FECHAPAGO', 'ASC');
		$datos = $this -> db -> get();
		//#####BUGGER PARA LA CONSULTA ######
		 // $datos = $this -> db -> last_query();
		 // echo $datos; die();
		//#####BUGGER PARA LA CONSULTA ######
		$datos = $datos -> result_array();
		if(!empty($datos)):
			$tmp = NULL;
			foreach($datos as $monetizacion):
			$tmp[] = array("PERIODOPAGO" => $monetizacion['PERIODOPAGO'], "FECHAPAGO" => $monetizacion['FECHAPAGO'], "TRANSACCION" => $monetizacion['TRANSACCION'], "PAGONETO" => $monetizacion['PAGONETO'], "INTERESES" => $monetizacion['INTERESES']);
			endforeach;
			$datos = $tmp;
		else:
			$datos = FALSE;
		endif;
		return $datos;
	}

	function buscarResumenSgva($periodo, $nit)
	/**
	* Función que devuelve la información de los resumenes por periodos de las  empresa de SGVA
	*
	* @param string $periodo
	* @param string $nit
	* @return array $datos
	* @return boolean false - error
	*/
	{
		$this -> db -> select('DIASASIGNADOS,DIASCUMPLIDOS, MONETIZACION');
		$this -> db -> from('RESUMEN');
		$this -> db -> where('CODEMPRESA', $nit);
		$this -> db -> where('ANNO', $periodo);
		$resultado = $this -> db -> get();
		//#####BUGGER PARA LA CONSULTA ######
		 // $resultado = $this -> db -> last_query();
		 // echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		if ($resultado -> num_rows() > 0):
			$empresa = $resultado -> row_array();
			return $empresa;
		else:
			return FALSE;
		endif;
	}

	function cargarFiscalizacionSgva($codigoAsignacion, $codigoConcepto, $codigoTipoGestion, $periodoInicial, $periodoFinal)
	/**
	* Función que inserta valores en la tabla fiscalizacón
	* Responde si la transacción fue exitosa, de no serlo realiza un rollback sobre la tabla.
	*
	* @param string $codigoAsignacion
	* @param string $codigoConcepto
	* @param string $periodoInicial
	* @param string $periodoFinal
	* @return boolean true - exito
	* @return string false - error
	*/
	{
		$this->db->trans_begin();
		$this->db->trans_strict(TRUE);
		$this -> db -> set ('COD_ASIGNACION_FISC', $codigoAsignacion, FALSE);
		$this -> db -> set ('COD_CONCEPTO', $codigoConcepto, FALSE);
		$this -> db -> set ('COD_TIPOGESTION', $codigoTipoGestion, FALSE);
		$this -> db -> set ('PERIODO_INICIAL', "TO_DATE('". $periodoInicial ."','DD/MM/YYYY')", FALSE);
		$this -> db -> set ('PERIODO_FINAL', "TO_DATE('". $periodoFinal ."','DD/MM/YYYY')", FALSE);
		$resultado = $this -> db -> insert('FISCALIZACION');
		//#####BUGGER PARA LA CONSULTA ######
		// $resultado = $this -> db -> last_query();
		// echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		//verificacion de la transacción

        if ($this -> db -> trans_status() === FALSE):

            $this -> db -> trans_rollback();
            return $datos = FALSE;

        else:

			$this -> db -> trans_commit();
			return $datos = TRUE;

		endif;
	}

	function consultarFiscalizacionSgva($codigoAsignacion, $codigoConcepto)
	/**
	* Funcion que devuelve la información de una fiscalización creada por el proceso de liquidación de contratos de aprendizaje
	*
	* @param string $codigoAsignacion
    * @param string $codigoConcepto
    * @return $fiscalizacion
	* @return boolean false - error
	*/
	{
        $this->db->trans_begin();
        $this->db->trans_strict(TRUE);
        $this -> db -> select('NRO_EXPEDIENTE, COD_FISCALIZACION');
		$this -> db -> from('FISCALIZACION');
		$this -> db -> where('COD_ASIGNACION_FISC', $codigoAsignacion, FALSE);
		$this -> db -> where('COD_CONCEPTO', $codigoConcepto, FALSE);
		$resultado = $this->db->get();
        //#####BUGGER PARA LA CONSULTA ######
         //$resultado = $this -> db -> last_query();
         //echo $resultado; die();
        //#####BUGGER PARA LA CONSULTA ######
        //verificacion de la transacción

        if ($this -> db -> trans_status() === FALSE):

            $this -> db -> trans_rollback();
            return $datos = FALSE;

        else:

            $this -> db -> trans_commit();

            if ($resultado -> num_rows() > 0):
                $fiscalizacion = $resultado -> row_array();
                return $fiscalizacion;

            else:

                return $fiscalizacion = FALSE;

            endif;

        endif;

	}

	function getSalarioMinimoVigente($anno)
	/**
	* Funcion que devuelve el Salario Mínimo Legal Vigente para el año parametrizado
	*
	* @param integer $anno
	* @return array $smlv
	* @return boolean false - error
	*/
	{

		$this -> db -> select('SALARIO_MINIMO');
		$this -> db -> from('HISTORICOSALARIOMINIMO_UVT');
		$this -> db -> where('ANNO', $anno, FALSE);
		$resultado = $this->db->get();
		if ($resultado -> num_rows() > 0):
			$smlv = $resultado -> row_array();
			return $smlv;
		else:
			return FALSE;
		endif;
	}

	function getSMLV_rango($anno_inicial, $anno_final)
	/**
	* Funcion que devuelve el Salario Mínimo Legal Vigente para un rango de fechas
	*
	* @param integer $anno
	* @return array $smlv
	* @return boolean false - error
	*/
	{
		$this -> db -> select(' ANNO, SALARIO_MINIMO');
		$this -> db -> from('HISTORICOSALARIOMINIMO_UVT');
		$where = "ANNO >= ". $anno_inicial . " and ANNO <= ". $anno_final;
		$this -> db -> where($where);
		$resultado = $this->db->get();
		//#####BUGGER PARA LA CONSULTA ######
	 	// $resultado = $this -> db -> last_query();
	 	// echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######
		if ($resultado -> num_rows() > 0):
			$smlv = $resultado -> row_array();
			return $smlv;
		else:
			return FALSE;
		endif;
	}

	// FUNCIONES DE AUTOCOMPLETAR---------------------------------------------------------

	function buscarConceptos()
	/**
	* Función que devuelve los conceptos de fiscalización para los formularios de consulta.
	*
	* @param string $nit
	* @return array $datos
	* @return boolean false - error
	*/
	{
		$this -> db -> select('COD_CPTO_FISCALIZACION, NOMBRE_CONCEPTO');
		$condicion = 'COD_CPTO_FISCALIZACION not in (3, 5)';
		$this -> db -> where($condicion);
		$this -> db -> order_by('NOMBRE_CONCEPTO', 'ASC');
		$datos = $this -> db -> get('CONCEPTOSFISCALIZACION');
		//#####BUGGER PARA LA CONSULTA ######
		 // $resultado = $this -> db -> last_query();
		 // echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######

        if ($this -> db -> trans_status() === FALSE) :

            $this -> db -> trans_rollback();
            return $datos = FALSE;

        else:

            $this -> db -> trans_commit();
            $datos = $datos -> result_array();

            if(!empty($datos)):

                $tmp = NULL;

                foreach($datos as $nit):

                    $tmp[] = array("value" => $nit['COD_CPTO_FISCALIZACION'], "label" => $nit['NOMBRE_CONCEPTO']);

                endforeach;

                return $datos = $tmp;

            else:

                return $datos = FALSE;

            endif;

        endif;

	}

	function buscarNits($nit, $regional)
	/**
	* Función que devuelve nits para los formularios de consulta. Condiciona la busqueda a la regional asociada al usuario y solo muestra los primeros 500 resultados como límite
	*
	* @param string $nit
	* @param string $regional
	* @return array $datos
	* @return boolean false - error
	*/
	{
		$this -> db -> select('CODEMPRESA, RAZON_SOCIAL');
		$this -> db -> where('COD_REGIONAL', $regional);
		if (!empty($nit)):
			$this -> db -> like('CODEMPRESA', $nit, 'after');
		endif;
		$this -> db -> order_by('CODEMPRESA', 'ASC');
		$this -> db -> limit(500);
		$datos = $this -> db -> get('EMPRESA');
		//#####BUGGER PARA LA CONSULTA ######
		 // $resultado = $this -> db -> last_query();
		 // echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######

        if ($this -> db -> trans_status() === FALSE) :

            $this -> db -> trans_rollback();
            return $datos = FALSE;

        else:

            $this -> db -> trans_commit();
            $datos = $datos -> result_array();

            if(!empty($datos)):

			    $tmp = NULL;

			    foreach($datos as $nit):

			        $tmp[] = array("value" => $nit['CODEMPRESA'], "label" => $nit['CODEMPRESA']." :: ".$nit['RAZON_SOCIAL']);

			    endforeach;

                return $datos = $tmp;

            else:

                return $datos = FALSE;

            endif;

        endif;

	}

	function buscarRazonSocial($nombre, $regional)
	/**
	* Función que devuelve razon social para los formularios de consulta. Limita a las razones sociales registradasen la regional del usuario y solo muestra los primeros 500 resultados como límite
	*
	* @param string $nombre
	* @param string $regional
	* @return array $datos
	* @return boolean false - error
	*/
	{
		$this -> db -> select('CODEMPRESA, RAZON_SOCIAL');
		$this -> db -> where('COD_REGIONAL', $regional);
		if (!empty($nombre)):
			$this -> db -> like('RAZON_SOCIAL', $nombre, 'after');
		endif;
		$this -> db -> order_by('RAZON_SOCIAL', 'ASC');
		$this -> db -> limit(500);
		$datos = $this -> db -> get('EMPRESA');
		//#####BUGGER PARA LA CONSULTA ######
		  //$resultado = $this -> db -> last_query();
		  //echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######

        if ($this -> db -> trans_status() === FALSE) :

            $this -> db -> trans_rollback();
            return $datos = FALSE;

        else:

            $this -> db -> trans_commit();
            $datos = $datos -> result_array();

            if(!empty($datos)):

			    $tmp = NULL;

			    foreach($datos as $nombre):

			        $tmp[] = array("value" => $nombre['RAZON_SOCIAL'], "label" => $nombre['CODEMPRESA']." :: ".$nombre['RAZON_SOCIAL']);

                endforeach;

                return $datos = $tmp;

            else:

                return $datos = FALSE;

            endif;

        endif;
	}

	function buscarRepresentante($nombre, $regional)
	/**
	* Función que devuelve representantes para los formularios de consulta. Solo muestra las empresas registradas en la regional del usuario y los primeros 500 resultados como límite
	*
	* @param string $nombre
	* @param string $regional
	* @return array $datos
	* @return boolean false - error
	*/
	{
		$this -> db -> select('CODEMPRESA, RAZON_SOCIAL, REPRESENTANTE_LEGAL');
		$this -> db -> where('COD_REGIONAL', $regional);
		if (!empty($nombre)):
			$this -> db -> like('REPRESENTANTE_LEGAL', $nombre, 'after');
		endif;
		$this -> db -> order_by('REPRESENTANTE_LEGAL', 'ASC');
		$this -> db -> limit(500);
		$datos = $this -> db -> get('EMPRESA');
		//#####BUGGER PARA LA CONSULTA ######
		 // $resultado = $this -> db -> last_query();
		 // echo $resultado; die();
		//#####BUGGER PARA LA CONSULTA ######

        if ($this -> db -> trans_status() === FALSE) :

            $this -> db -> trans_rollback();
            return $datos = FALSE;

        else:

            $this -> db -> trans_commit();
            $datos = $datos -> result_array();

		    if(!empty($datos)):

			    $tmp = NULL;

			    foreach($datos as $nombre):

				    $tmp[] = array("value" => $nombre['REPRESENTANTE_LEGAL'], "label" => $nombre['REPRESENTANTE_LEGAL']." :: ".$nombre['RAZON_SOCIAL']);

			    endforeach;

			    return $datos = $tmp;

            else:

                return $datos = FALSE;

            endif;

        endif;
	}

	// FINAL FUNCIONES DE AUTOCOMPLETAR---------------------------------------------------------

	function getLiquidaciones($cod_fiscalizacion = null)
	/**
	* Función que devuelve las liquidaciones asociadas al usuario
	*
	* @param string $cod_fiscalizacion
	* @return obj $dato
	* @return array $dato - error
	*/
	{
		$this -> db -> select('E.CODEMPRESA, E.RAZON_SOCIAL, F.COD_CONCEPTO, CF.NOMBRE_CONCEPTO, F.COD_FISCALIZACION, L.NUM_LIQUIDACION');
		$this -> db -> join('FISCALIZACION F', 'F.COD_FISCALIZACION = L.COD_FISCALIZACION');
		$this -> db -> join('ASIGNACIONFISCALIZACION AF', 'AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC');
		$this -> db -> join('EMPRESA E', 'E.CODEMPRESA = AF.NIT_EMPRESA');
		$this -> db -> join('CONCEPTOSFISCALIZACION CF', 'CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO');

		if ($cod_fiscalizacion != NULL):

			$this -> db -> where('F.COD_FISCALIZACION', $cod_fiscalizacion);
			$this -> db -> where('L.EN_FIRME', 'N');
			$this -> db -> where('AF.ASIGNADO_A', COD_USUARIO);
			$this -> db -> or_where('AF.ASIGNADO_POR', COD_USUARIO);
			$this -> db -> group_by('E.CODEMPRESA, E.RAZON_SOCIAL, F.COD_CONCEPTO,CF.NOMBRE_CONCEPTO, F.COD_FISCALIZACION, L.NUM_LIQUIDACION');
			$this -> db -> where('NOT EXISTS (SELECT * FROM SOPORTE_LIQUIDACION WHERE Soporte_Liquidacion.Num_liquidacion = L.Num_Liquidacion)', '', FALSE);
			$this -> db -> where('F.COD_TIPOGESTION not in (309, 440) and F.COD_CONCEPTO not in (3,5) and F.CODIGO_PJ is NULL ');
			$dato = $this -> db -> get("LIQUIDACION L");
			//#####BUGGER PARA LA CONSULTA ######
			 // $resultado = $this -> db -> last_query();
			 // echo $resultado; die();
			//#####BUGGER PARA LA CONSULTA ######
			$dato = $dato -> result_array();

			if (!empty($dato)) :

				return $dato[0];

			endif;

		else:

			$this -> db -> where('L.EN_FIRME', 'N');
			$this -> db -> where('AF.ASIGNADO_A', COD_USUARIO);
			$this -> db -> or_where('AF.ASIGNADO_POR', COD_USUARIO);
			$this -> db -> group_by('E.CODEMPRESA, E.RAZON_SOCIAL, F.COD_CONCEPTO,CF.NOMBRE_CONCEPTO, F.COD_FISCALIZACION, L.NUM_LIQUIDACION');
			$this -> db -> where('NOT EXISTS (SELECT * FROM SOPORTE_LIQUIDACION WHERE Soporte_Liquidacion.Num_liquidacion = L.Num_Liquidacion)', '', FALSE);
			$this -> db -> where('F.COD_TIPOGESTION not in (309, 440) and F.COD_CONCEPTO not in (3,5) and F.CODIGO_PJ is NULL ');
			$dato = $this -> db -> get("LIQUIDACION L");
			//#####BUGGER PARA LA CONSULTA ######
			 // $resultado = $this -> db -> last_query();
			 // echo $resultado; die();
			//#####BUGGER PARA LA CONSULTA ######
			if ($dato->num_rows() > 0):

				return $dato -> result();

			endif;

		endif;
	}

	function getArchivosSubidos($num_liquidacion)
	/**
	* Función que retorna la información de los archivos cargados en la legalización de la liquidación
	*
 	* @param string $num_liquidacion
	* @return obj $dato
	* @return  boolean false - error
  	*/
	{
		$this->db->select('NRO_RADICADO, FECHA_RADICADO, NOMBRE_ARCHIVO, NIS,COD_SOPORTE_LIQUIDACION');
		$this->db->where('NUM_LIQUIDACION', $num_liquidacion);
		$dato = $this->db->get("SOPORTE_LIQUIDACION");
		$dato = $dato->result_array();
		if (!empty($dato)) :
			return $dato;
		else:
			return FALSE;
		endif;
	}

	public function eliminar_soporte($cod_soporte)
	/**
	* Función que elimina los datos de los soportes cargados en la legalización de la liquidación
	*
 	* @param string $cod_soporte
  	*/
	{
		if (!empty($cod_soporte)) :
		$this->db->where('COD_SOPORTE_LIQUIDACION', $cod_soporte);
		$this->db->delete('SOPORTE_LIQUIDACION');
		endif;
	}

	public function actualizacion_liquidacion($datos)
	/**
	* Función que actualiza los datos de los soportes cargados en la legalización de la liquidación
	*
 	* @param string $cod_soporte
  	*/
	{
		if (!empty($datos)) :

			$this -> db -> where("NUM_LIQUIDACION", $datos['NUM_LIQUIDACION']);
			unset($datos['NUM_LIQUIDACION']);
			$resultado = $this -> db -> update("LIQUIDACION", $datos);
			//#####BUGGER PARA LA CONSULTA ######
			 // $resultado = $this -> db -> last_query();
			 // echo $resultado; die();
			//#####BUGGER PARA LA CONSULTA ######

		endif;
	}

	public function consultarLiquidacion_acuerdoPago($liquidacion)
	/**
	* Función que devuelve la información necesaria para el recalculo de la liquidación hasta el momento de la generación de un acuerdo de pago
	* Solo funcional en liquidación en firme y recalcula capital e intereses de forma independiente
	*
	* @param string $liquidacion
	*/
	{
		$this -> db -> select('to_char("FECHA_LIQUIDACION", '."'DD/MM/YYYY') AS FECHA_LIQUIDACION", FALSE);
		$this -> db -> select('COD_CONCEPTO, TOTAL_LIQUIDADO, SALDO_INTERES, SALDO_CAPITAL, SALDO_DEUDA');
		$this -> db -> from('LIQUIDACION');
		$condicion = "NUM_LIQUIDACION = '" . $liquidacion . "' AND EN_FIRME = 'S'";
		$this -> db -> where($condicion);
		$datos = $this -> db -> get();
		//#####BUGGER PARA LA CONSULTA ######
		 // $datos = $this -> db -> last_query();
		 // echo $datos; die();
		//#####BUGGER PARA LA CONSULTA ######
		$datos = $datos -> row_array();
		if(!empty($datos)):

			return $datos;

		else:

			return FALSE;

		endif;

	}

    public function consultarPagosPeriodo($nit, $periodo, $concepto, $subconcepto)
        /**
         * Función que devuelve la información de los pagos por el concepto de monetización dentro de un año específico
         * Solo funcional en la consulta estados de cuenta de contrato de aprendizaje
         *
         * @param string $nit
         * @param string $periodo
         * @param string $concepto
         * @param string $subconcepto
         * @return array $resultado
         */
    {
        $this->db->trans_begin();
        $this->db->trans_strict(TRUE);
        $this -> db -> select('sum(VALOR_PAGADO) as MONETIZACION ');
        $this -> db -> from('PAGOSRECIBIDOS');
        $condicion = "NITEMPRESA = '" . $nit . "' AND COD_CONCEPTO = " . $concepto . " AND COD_SUBCONCEPTO = " . $subconcepto . " AND substr(PERIODO_PAGADO, 1, 4) = '" . $periodo . "'";
        $this -> db -> where($condicion);
        $datos = $this -> db -> get();
        //#####BUGGER PARA LA CONSULTA ######
        //$datos = $this -> db -> last_query();
        //echo $datos; die();
        //#####BUGGER PARA LA CONSULTA ######

        if ($this -> db -> trans_status() === FALSE) :

            $this -> db -> trans_rollback();
            return $datos = FALSE;

        else:

            $this -> db -> trans_commit();
            $datos = $datos -> row_array();
            if(empty($datos['MONETIZACION'])):

                return $datos = 0;

            else:

                return $datos;

            endif;

        endif;


    }

}
/* End of file liquidaciones_model.php */