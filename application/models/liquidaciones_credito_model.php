<?php
/**
* Archivo para ala administración de los modelos necesarios para las liquidaciones en el proceso administrativo
*
* @package    Cartera
* @subpackage Models
* @author     jdussan
* @location   ./application/models/liquidaciones_model.php
* @last-modified  12/05/2014
*/

class Liquidaciones_credito_model extends CI_Model
{
    function __construct()
        {
            parent::__construct();
        }

    function consultarCodRespuestaProceso($codigoProceso)
        /**
         * Función que recibe el código de proceso a consultar y retorna el código de respuesta actual del proceso.
         *
         * @param integer $codigoProceso
         * @return string $codigoRespuesta
         * @return boolean FALSE - error
         */
    {
        $this -> db -> select('COD_RESPUESTA');
        $this -> db -> from('PROCESOS_COACTIVOS');
        $this -> db -> where('COD_PROCESO_COACTIVO', $codigoProceso);
        $resultado = $this -> db ->get();
        //#####BUGGER PARA LA CONSULTA ######
        //$resultado = $this -> db -> last_query();
        //echo $resultado; die();
        //#####BUGGER PARA LA CONSULTA ######
        if ($resultado -> num_rows() > 0):
            $codigoRespuesta = $resultado -> row_array();
            return $codigoRespuesta;
        else:
            return FALSE;
        endif;
    }

    function consultarProceso($codigoProceso, $codigoRespuesta)
        /**
         * Función que recibe el código de proceso y el codigo de respueta del proceso  a consultar y retorna la información y liquidaciones asociadas al proceso.
         *
         * @param integer $codigoProceso
         * @param integer $codigoRespuesta
         * @return string $codigoRespuesta
         * @return boolean FALSE - error
         */
    {
        $this -> db -> select('IDENTIFICACION, EJECUTADO, REPRESENTANTE, COD_CPTO_FISCALIZACION, CONCEPTO, NO_EXPEDIENTE, ABOGADO, NUM_LIQUIDACION, PROCESO, RESPUESTA, DIRECCION, CORREO_ELECTRONICO, SALDO_DEUDA, SALDO_CAPITAL, SALDO_INTERES, COD_EXPEDIENTE_JURIDICA');
        $this -> db -> from('VW_PROCESOS_COACTIVOS');
        $this -> db -> where('COD_PROCESO_COACTIVO', $codigoProceso);
        $this -> db -> where('COD_RESPUESTA', $codigoRespuesta);
        $resultado = $this -> db ->get();
        //#####BUGGER PARA LA CONSULTA ######
        //$resultado = $this -> db -> last_query();
        //echo $resultado; die();
        //#####BUGGER PARA LA CONSULTA ######
        if ($resultado):
            $tmp = NULL;
            foreach($resultado -> result_array() as $fiscalizacion):
                $tmp[] = $fiscalizacion;
            endforeach;
            $proceso = $tmp;
        else:
            $proceso = FALSE;
        endif;
        return $proceso;
    }

    function getInfoUsuario($usuario)
    {
        /**
         * Función que retorna el nombre del abogado del proceso.
         * Esta función no debe retornar error, pues no se pueden logear usuarios no identificados en la DB
         *
         * @param integer $usuario
         * @return string $nombre
         */
        $this -> db -> select("NOMBRES || ' ' || APELLIDOS AS ABOGADO");
        $this -> db -> from ('USUARIOS');
        $this -> db -> where('IDUSUARIO',$usuario);
        $resultado = $this -> db -> get();
        if ($resultado -> num_rows() > 0):
            $nombre = $resultado -> row_array();
            return $nombre;
        endif;
    }

    function consultarCodigoFiscalizacion($codigoFiscalizacion, $idusuario)
    {
    /**
    * Función que devuelve la información asociada al codigo de fiscalización, necesaria para el proceso de liquidación y que se muestra en las cabeceras de las liquidaciones. Se asocia el id de usuario que lanza la consulta para cooroborar su permiso al proceso.
    * Solo funcional si se lanza la consulta a través de un codigo de fiscalización existente
    *
    * @param integer $codigoFiscalizacion
    * @param integer $idusuario
    * @return array $fiscalizacion
    * @return boolean false - error
    */
        $this -> db -> select('F.COD_FISCALIZACION, F.COD_ASIGNACION_FISC, F.COD_CONCEPTO, CF.NOMBRE_CONCEPTO,TG.TIPOGESTION, AF.ASIGNADO_POR, AF.ASIGNADO_A, AF.NIT_EMPRESA, EMP.NOMBRE_EMPRESA, REG.NOMBRE_REGIONAL, EMP.CIIU, CIIU.DESCRIPCION');
        $this -> db -> select('to_char("F"."PERIODO_INICIAL",'."'DD/MM/YYYY') AS PERIODO_INICIAL", FALSE);
        $this -> db -> select('to_char("F"."PERIODO_FINAL",'."'DD/MM/YYYY') AS PERIODO_FINAL", FALSE);
        $this -> db -> from('13FISCALIZACION "F"');
        $this -> db -> join('13TIPOGESTION "TG"', 'F.COD_TIPOGESTION = TG.COD_GESTION');
        $this -> db -> join('13CONCEPTOSFISCALIZACION "CF"', 'F.COD_CONCEPTO = CF.COD_CPTO_FISCALIZACION');
        $this -> db -> join('13ASIGNACIONFISCALIZACION "AF"' , 'F.COD_ASIGNACION_FISC = AF.COD_ASIGNACIONFISCALIZACION');
        $this -> db -> join('13EMPRESA "EMP"', 'AF.NIT_EMPRESA = EMP.CODEMPRESA');
        $this -> db -> join('13CIIU', 'EMP.CIIU = CIIU.CLASE');
        $this -> db -> join('13REGIONAL "REG"', 'EMP.COD_REGIONAL = REG.COD_REGIONAL');
        $condicion = 'F.COD_FISCALIZACION =' . $codigoFiscalizacion . 'AND (AF.ASIGNADO_POR = ' . $idusuario . ' OR AF.ASIGNADO_A = ' . $idusuario . ')';
        $this -> db -> where($condicion);
        $resultado = $this -> db ->get();
        //#####BUGGER PARA LA CONSULTA ######
        // $resultado = $this -> db -> last_query();
        // echo $resultado; die();
        //#####BUGGER PARA LA CONSULTA ######
        if ($resultado -> num_rows() > 0):
            $fiscalizacion = $resultado -> row_array();
            return $fiscalizacion;
        else:
            return FALSE;
        endif;

    }

    //CONSULTA DE CABECERAS PARA MULTAS DEL MINISTERIO
    function getCabecerasMultasMinisterio($codigoMulta)
    {
    /**
    * Funcion que devuelve la información asociada a la multa de ministerio consultada a través del código asociado
    * Solo funcional si se lanza la consulta a través de un codigo de multa existente
    *
    * @param integer $codigoMulta
    * @return array $empresa
    * @return boolean false - error
    */
        $this -> db -> select('MUL.COD_MULTAMINISTERIO, EMP.CODEMPRESA, EMP.NOMBRE_EMPRESA, REG.NOMBRE_REGIONAL, MUL.NRO_RESOLUCION, MUL.VALOR, MUL.RESPONSABLE, TIP.TIPOGESTION');
        $this -> db -> select('to_char("MUL"."FECHA_CREACION", '."'DD/MM/YYYY') AS FECHA_CREACION", FALSE);
        // RETIRO POR CAMBIO APROBADO EN BUG #119
        // $this -> db -> select('to_char("m"."PERIODO_INICIAL", '."'DD/MM/YYYY') AS PERIODO_INICIAL", FALSE);
        // $this -> db -> select('to_char("m"."PERIODO_FINAL", '."'DD/MM/YYYY') AS PERIODO_FINAL", FALSE);
        // RETIRO POR CAMBIO APROBADO EN BUG #119
        $this -> db -> select('to_char("MUL"."FECHA_EJECUTORIA", '."'DD/MM/YYYY') AS FECHA_EJECUTORIA", FALSE);
        $this -> db -> from('13MULTASMINISTERIO "MUL"');
        $this -> db -> join('13EMPRESA "EMP"', 'MUL.NIT_EMPRESA = EMP.CODEMPRESA');
        $this -> db -> join('13REGIONAL REG', 'EMP.COD_REGIONAL = REG.COD_REGIONAL');
        $this -> db -> join('13TIPOGESTION TIP', 'MUL.COD_GESTION_COBRO = TIP.COD_GESTION');
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

    //CARGAR ENCABEZADO MULTA MINISTERIO
    function loadMultasMinisterio($maestro, $detalle)
    {
    /**
    * Función que almacena en DB los registros generados como maestro - detalle de las liquidaciones de una multa de ministerio
    * Inicia la transacción de la inserción en el encabezado, consulta el ID de la secuencia y se lo asigna al detalle.
    *
    * @param array $maestro
    * @param array $detalle
    * @return string $last_query
    * @return string $last_query - error
    */
        $this->db->trans_begin();
        $this->db->trans_strict(TRUE);

        //insertar encabezado intereses
        $this -> db -> set('COD_MULTAMINISTERIO', $maestro['codigo_multa'], FALSE);
        $this -> db -> set('VALOR_CAPITAL', $maestro['valor_multa'], FALSE);
        $this -> db -> set ('FECHA_ELABORACION', "TO_DATE('". $maestro['fecha_elaboracion'] ."','DD/MM/YY')", FALSE);
        $this -> db -> set ('FECHA_EJECUTORIA', "TO_DATE('". $maestro['fecha_ejecutoria'] ."','DD/MM/YY')", FALSE);
        $this -> db -> set ('FECHA_LIQUIDACION', "TO_DATE('". $maestro['fecha_liquidacion'] ."','DD/MM/YY')", FALSE);
        $this -> db -> set('TOTAL_DIAS_MORA', $maestro['total_dias_mora'], FALSE);
        $this -> db -> set('TOTAL_CAPITAL', $maestro['total_capital'], FALSE);
        $this -> db -> set('TOTAL_INTERESES', $maestro['total_interes'], FALSE);
        $this -> db -> set('VALOR_TOTAL', $maestro['total_valor'],FALSE);
        $this -> db -> insert('13INTERES_MULTAMIN_ENC');

        //Adquirir cod_interes_multamin_enc
            $query = $this->db->query("SELECT Interes_Multa_cod_Interes__SEQ.CURRVAL FROM dual");
                $row = $query->row_array();
                $id = $row['CURRVAL'];


                //insertar detalles mes a mes
                foreach ($detalle as $linea):
                    foreach($linea as $value):
                        $this -> db -> set ('COD_INTERES_MULTAMIN', $id, FALSE);
                        $this -> db -> set ('MES', $linea['mes']);
                        $this -> db -> set ('ANNO', $linea['anno']);
                        $this -> db -> set ('VALOR_CAPITAL', number_format($linea['valorCapital'], 2, '.', ''), FALSE );
                        $this -> db -> set ('VALOR_INTERESES', number_format($linea['valorInteres'], 2, '.', ''), FALSE);
                        $this -> db -> set ('VALOR_TOTAL', number_format($linea['valorTotal'], 2, '.', ''), FALSE);
                    endforeach;
                    $this -> db ->insert('13INTERESES_MULTAMIN_DET');
                endforeach;

                //verificacion de la transacción
                if ($this -> db -> trans_status() === FALSE) :
                        $this -> db ->trans_rollback();
                        return $this -> db -> last_query();
                else:
                        $this -> db -> trans_commit();
                        return $this -> db -> last_query();
            endif;
    }

    //CONSULTA TIPOS DE CARTA REMISIÓN
    function getCombinacionTipoAportante()
    {
    /**
    * Función que consulta los tipos de combinación de correspondencia para aportantes
    * Esta función provee los tipos para mostrar las opciones en pantalla y si no se han cargado aún por parametrización devuelve error
    *
    * @return array $tipos
    * @return string $tipos - error
    */
        $this -> db -> select('COD_COMB_TIP_APORTANTE, DESCRIPCION_COMB_TIPO');
        $this -> db -> order_by('COD_COMB_TIP_APORTANTE','ASC');

        $resultado = $this -> db -> get('13COMBINACION_TIPO_APORTANTE');

        if($resultado -> num_rows() > 0):
            $tipos = $resultado -> result_array();
            return $tipos;
        else:
            $tipos = 'Consulta sin datos en los tipos de combinación';
        endif;
    }

    //CONSULTA RESPUESTA PARA CARTA REMISIÓN
    function getCombinacionRespuesta($codigoCombinacion)
    {
    /**
    * Función que retorna la respuesta según el codigo de la combinación seleccionado por el usuario
    * Esta función provee la respuesta en HTML almacendao en la DB
    *
    * @param integer $codigoCombinacion
    * @return array $respuesta
    * @return boolean False - error
    */
        $this -> db -> select('COD_COMB_TIP_APORTANTE, TEXTO_COMBINATORIO');
        $this -> db -> from('13COMBINACION_TIPO_APORTANTE');
        $this -> db -> where('COD_COMB_TIP_APORTANTE',$codigoCombinacion);
        $resultado = $this -> db -> get();
        if ($resultado -> num_rows() > 0):
                $respuesta = $resultado -> row_array();
            return $respuesta;
        else:
            return FALSE;
            endif;
    }

    //CAPTURAR LA FECHA DE VISITA SEGUN CODIGO DE GESTIÓN
    function getFechaVisita($codigoGestion)
    {
    /**
    * Función que retorna la fecha en la cual fue visitada la empresa para la generación de la comunicación de aportante
    * Si la empresa consultada no tiene fecha de visita devuelve error
    *
    * @param integer $codigoGestion
    * @return array $fecha
    * @return boolean False - error
    */
        $this -> db -> select('INF.COD_GESTION_COBRO');
        $this -> db -> select('to_char("NOTI"."FECHA_VISITA", '."'DD/MM/YYYY') AS FECHA_VISITA", FALSE);
        $this -> db ->from ('13INFORMEVISITA "INF"');
        $this -> db ->join('13NOTIFICACIONVISITA "NOTI"', 'INF.COD_NOTIFICACION_VISITA = NOTI.COD_NOTIFICACION_VISITA');
        $this -> db -> where('INF.COD_GESTION_COBRO',$codigoGestion);
        $resultado = $this -> db -> get();
        if ($resultado -> num_rows() > 0):
                $fecha = $resultado -> row_array();
            return $fecha;
        else:
            return FALSE;
            endif;
    }

    //CONSULTA DE USUARIOS


    //CONSULTA DE EMPRESA
    function getEmpresa($nitEmpresa)
    {
    /**
    * Función que retorna la información de la empresa consultada  por nit
    * Si la empresa consultada no se encuentra registrada en la DB, reporta un error lógico
    *
    * @param integer $nitEmpresa
    * @return array $empresa
    * @return boolean False - error
    */
        $this -> db -> select ('EMP.CODEMPRESA, EMP.NOMBRE_EMPRESA, EMP.DIRECCION, EMP.TELEFONO_FIJO, EMP.REPRESENTANTE_LEGAL, EMP.RAZON_SOCIAL, EMP.COD_REGIONAL, EMP.COD_PAIS, EMP.COD_DEPARTAMENTO, EMP.COD_MUNICIPIO, MUN.CODMUNICIPIO, MUN.NOMBREMUNICIPIO, DEP.COD_DEPARTAMENTO, DEP.NOM_DEPARTAMENTO');
        $this -> db -> from('13EMPRESA "EMP"');
        $this  -> db -> join('13MUNICIPIO "MUN"','EMP.COD_MUNICIPIO = MUN.CODMUNICIPIO');
        $this  -> db -> join('13DEPARTAMENTO "DEP"','EMP.COD_DEPARTAMENTO = DEP.COD_DEPARTAMENTO');
        // $this  -> db -> join('13PAIS "PAI"','EMP.COD_PAIS = PAI.CODPAIS');
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

    //CONSULTA DE CABECERAS PARA SOPORTES DE LIQUIDACION
    function getCabecerasSoportesLiquidacion($codigoConcepto)
    {
    /**
    * Función que retorna la información asociada al soporte de liquidación consultado por el codigo de gestión
    * Si el código de concepto no tiene liquidaciones asociadas no deberia reportar datos (PENDIENTE)
    *
    * @param integer $codigoConcepto
    * @return array $empresa
    * @return boolean False - error
    */
        $this -> db -> select('L.NUM_LIQUIDACION, L.COD_CONCEPTO, L.NITEMPRESA, L.COD_FISCALIZACION, E.NOMBRE_EMPRESA, C.NOMBRE_CONCEPTO');
        $this -> db -> from('13LIQUIDACION L');
        $this -> db -> join('13EMPRESA E', 'L.NITEMPRESA = E.CODEMPRESA');
        $this -> db -> join('13CONCEPTOSFISCALIZACION C','L.COD_CONCEPTO = C.COD_CPTO_FISCALIZACION');
        $this -> db -> where('L.COD_FISCALIZACION',$codigoConcepto);

        $resultado = $this->db->get();

        if ($resultado -> num_rows() > 0):
                        $empresa = $resultado -> row_array();
            return $empresa;
                endif;
    }

    //CARGAR REGISTRO SOPORTE DE LIQUIDACIÓN
    function loadSoportesLiquidacion($liquidacion, $nis, $fecha, $radicado, $archivo, $fiscalizador)
    {
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
        $this -> db -> set ('sl.NUM_LIQUIDACION', $liquidacion);
        $this -> db -> set ('sl.NRO_RADICADO', $radicado);
        $this -> db -> set ('sl.FECHA_RADICADO', "TO_DATE('". $fecha."','DD/MM/YY')", FALSE);
        $this -> db -> set ('sl.NOMBRE_ARCHIVO', $archivo);
        $this -> db -> set ('sl.NOMBRE_RADICADOR', $fiscalizador);
        $this -> db -> set ('sl.NIS', $nis);
        return $this -> db-> insert ('13SOPORTE_LIQUIDACION "sl"');
    }

    //CONSULTA TASA PARAMETRIZADA :: Multas y FIC
    function getTasaParametrizada()
    {
    /**
    * Función que retorna la información de la tasa parametrizada para los calculos de multas de ministerio y FIC
    *
    * @return array $tasaInteres
    * @return boolean False - error
    */
        $this -> db -> select ('VALOR_TASA');
        $this -> db -> from ('13TASA_PARAMETRIZADO');
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

    //CONSULTA TASA DE INTERES ACTUAL DE LA SUPERFINANCIERA
    function getTasaInteresSF_actual()
    {
    /**
    * Función que retorna la información de la ultima tasa de la superintendencia almacenada en la DB
    *
    * @return array $tasaInteres
    * @return boolean False - error
    */
        $resultado = $this -> db -> query('
        SELECT * FROM (
            SELECT s.ID_TASA_SUPERINTENDENCIA, s.TASA_SUPERINTENDENCIA, s.VIGENCIA_DESDE, s.VIGENCIA_HASTA, s.FECHACREACION
            FROM 13TASA_SUPERINTENDENCIA s
            ORDER BY s.FECHACREACION DESC
        )
        WHERE ROWNUM = 1');
        if ($resultado -> num_rows > 0):
            $tasaInteres = $resultado -> row_array();
            return $tasaInteres;
        endif;
    }

    //CONSULTA TASA DE INTERES DE LA SUPERFINANCIERA POR MES
    function getTasaInteresSF_mes($mes, $anno)
    {
    /**
    * Función que retorna la información de la tasa de la superintendencia para el mes y año consultado
    * Si el mes y año consultados no concuerdan con una tasa registra reporta error de tasa
    *
    * @param integer $mes
    * @param integer $anno
    * @return integer $tasa
    * @return boolean False - error
    */
        $this -> db -> select ('TASA_SUPERINTENDENCIA');
        $this -> db -> from ('13TASA_SUPERINTENDENCIA');
        $this -> db -> where('VIGENCIA_DESDE >=',"to_date('". $mes . '/'. $anno."',"."'MM/YYYY')", FALSE);
        $this -> db -> order_by('VIGENCIA_DESDE', 'ASC');
        $resultado = $this -> db ->get();
        if ($resultado -> num_rows() > 0):
            $tasa = $resultado -> row_array();
            return $tasa;
        else:
            return FALSE;
        endif;
    }

    //CONSULTA APORTES PAGADOS
    function getAporte_mes($mes, $anno, $nit, $concepto)
    {
    /**
    * Función que retorna si la empresa consultada con el nit, tiene aportes regulares por el concepto enviado en el period selecionao (mes /año)
    * Si la empresa consultada no tiene aportes en el periodo retorna un 0
    *
    * @param integer $nitEmpresa
    * @param integer $concepto
    * @param integer $mes
    * @param integer $anno
    * @return integer $cuota
    */
        $data =     $mes.'/'.$anno.': '.$nit;
        $data = 0;
        return $data;
    }

    function getLiquidacionMultaMinisterio($codigoMulta)
    {
    /**
    * Funcion que devuelve la información asociada a la multa consultada
    * Solo funcional si se lanza la consulta a través de un codigo de multa existente
    *
    * @param integer $codigoMulta
    * @return array $liquidacion
    * @return boolean false - error
    */
        $this -> db -> select('IME.COD_INTERES_MULTA_MIN, IME.COD_MULTAMINISTERIO, IME.TOTAL_CAPITAL, IME.TOTAL_INTERESES, IME.VALOR_TOTAL, MM.NIT_EMPRESA, EMP.NOMBRE_EMPRESA');
        $this -> db -> select('to_char("IME"."FECHA_LIQUIDACION", '."'DD/MM/YYYY') AS FECHA_LIQUIDACION", FALSE);
        $this -> db -> from('13INTERES_MULTAMIN_ENC "IME"');
        $this -> db -> join('13MULTASMINISTERIO "MM"', 'IME.COD_MULTAMINISTERIO = MM.COD_MULTAMINISTERIO');
        $this -> db -> join('13EMPRESA "EMP"', 'MM.NIT_EMPRESA = EMP.CODEMPRESA');
        $this -> db -> where('IME.COD_MULTAMINISTERIO', $codigoMulta, FALSE);
        $this -> db -> order_by('IME.FECHA_LIQUIDACION' , 'DESC');
        $resultado = $this->db->get();
        if ($resultado -> num_rows() > 0):
            $liquidacion = $resultado -> row_array();
            return $liquidacion;
        else:
            return FALSE;
        endif;
    }

    function getSalarioMinimoVigente($anno)
    {
    /**
    * Funcion que devuelve el Salario Mínimo Legal Vigente para el año parametrizado
    *
    * @param integer $anno
    * @return array $smlv
    * @return boolean false - error
    */
        $this -> db -> select('SALARIO_MINIMO');
        $this -> db -> from('13HISTORICOSALARIOMINIMO_UVT');
        $this -> db -> where('ANNO', $anno, FALSE);
        $resultado = $this->db->get();
        if ($resultado -> num_rows() > 0):
            $smlv = $resultado -> row_array();
            return $smlv;
        else:
            return FALSE;
        endif;
    }
}

/* End of file liquidaciones_model.php */
