<?php

class Bandejaunificada_model Extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    function ListadoTerminacion($cod_coactivo) {

        $array = array();
        if ($total == false) :
            $this->db->select('AUTOSJURIDICOS.NUM_AUTOGENERADO, AUTOSJURIDICOS.COD_TIPO_PROCESO, USUARIO_CREADOR.IDUSUARIO ID_CREADOR, 
												 USUARIO_CREADOR.NOMBREUSUARIO NOMBRE_CREADOR, USUARIO_ASIGNADO.IDUSUARIO ID_ASIGNADO, 
												 USUARIO_ASIGNADO.NOMBREUSUARIO NOMBRE_ASIGNADO, AUTOSJURIDICOS.FECHA_CREACION_AUTO, TIPOGESTION.TIPOGESTION, 
												 RESPUESTAGESTION.NOMBRE_GESTION, PROCESOS_COACTIVOS.ABOGADO AS COD_ABOGADO, PROCESOS_COACTIVOS.IDENTIFICACION AS NIT_EMPRESA,
												 VW_PROCESOS_COACTIVOS.EJECUTADO AS NOMBRE_EMPRESA, PROCESOS_COACTIVOS.COD_PROCESOPJ, VW_PROCESOS_COACTIVOS.CONCEPTO,VW_PROCESOS_COACTIVOS.NOMBRE_REGIONAL');
        elseif ($total == true) :
            $this->db->select('COUNT(AUTOSJURIDICOS.NUM_AUTOGENERADO) numero');
        endif;
        $this->db->from('AUTOSJURIDICOS');
        $this->db->join('PROCESOS_COACTIVOS', 'AUTOSJURIDICOS.COD_PROCESO_COACTIVO = PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO', 'inner');
        $this->db->join('USUARIOS USUARIO_CREADOR', 'USUARIO_CREADOR.IDUSUARIO = AUTOSJURIDICOS.CREADO_POR', 'inner');
        $this->db->join('USUARIOS USUARIO_ASIGNADO', 'USUARIO_ASIGNADO.IDUSUARIO = AUTOSJURIDICOS.ASIGNADO_A', 'inner');
        $gestion = "GESTIONCOBRO.COD_GESTION_COBRO = AUTOSJURIDICOS.COD_GESTIONCOBRO AND GESTIONCOBRO.COD_TIPO_RESPUESTA != '1138'";
        $this->db->join('GESTIONCOBRO', $gestion, 'inner');
        $this->db->join('TIPOGESTION', 'TIPOGESTION.COD_GESTION = GESTIONCOBRO.COD_TIPOGESTION', 'inner');
        $this->db->join('RESPUESTAGESTION', 'RESPUESTAGESTION.COD_RESPUESTA = GESTIONCOBRO.COD_TIPO_RESPUESTA', 'inner');
        $this->db->join('VW_PROCESOS_COACTIVOS', 'AUTOSJURIDICOS.COD_PROCESO_COACTIVO = VW_PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO ', 'inner');
        $this->db->where('AUTOSJURIDICOS.COD_TIPO_PROCESO', 1);
        $this->db->where('AUTOSJURIDICOS.COD_TIPO_AUTO', 1);



        if ($this->session->userdata['id_secretario'] == $this->idusuario) :
            $this->db->where('AUTOSJURIDICOS.ASIGNADO_A', $this->idusuario);
        elseif ($this->session->userdata['id_coordinador'] == $this->idusuario) :
            $this->db->where('AUTOSJURIDICOS.ASIGNADO_A', $this->idusuario);
        else :
            $this->db->where('PROCESOS_COACTIVOS.ABOGADO', $this->idusuario);
        endif;
        $resultado = $this->db->get('');
//        $resultado_final = $this->db->last_query();
//        echo $resultado_final;
//        $resultado = $resultado->result_array();
        return $resultado;
    }

 function titulos_coactivo($cod_coactivo) {
        $this->db->select('VW.NO_EXPEDIENTE');
        $this->db->from('VW_PROCESOS_COACTIVOS VW');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_RESPUESTA=VW.COD_RESPUESTA');
        $this->db->where('VW.COD_PROCESO_COACTIVO', $cod_coactivo);
        $this->db->join('RECEPCIONTITULOS RT', 'RT.COD_RECEPCIONTITULO=VW.NO_EXPEDIENTE');
        $this->db->where('RT.CERRADO', 0);
        $where = 'VW.SALDO_DEUDA >0';
        $this->db->where($where);
        $this->db->group_by('VW.NO_EXPEDIENTE, VW.NUM_LIQUIDACION');
        $resultado = $this->db->get();
        $resultado = $resultado->result_array();
        return $resultado;
    }


    function consulta_responsable($respuesta) {
        $this->db->select('URLGESTION, IDCARGO');
        $this->db->from('RESPUESTAGESTION');
        $this->db->where('COD_RESPUESTA', $respuesta);
        $datos = $this->db->get();
        $query = $this->db->last_query();
        // echo $query;
        $datos = $datos->result_array();
        return $datos;
    }

    function consulta_secretario($regional) {
        $this->db->select('US.APELLIDOS,US.IDUSUARIO, US.NOMBRES,UG.IDGRUPO');
        $this->db->from('USUARIOS US');
        $this->db->join('USUARIOS_GRUPOS UG', 'UG.IDUSUARIO=US.IDUSUARIO');
        $this->db->where('UG.IDGRUPO', 41);
        $this->db->where('US.COD_REGIONAL', $regional);
        $datos = $this->db->get();
        $datos = $datos->result_array();
        return $datos[0];
    }

    function consulta_coordinador($regional) {
        $this->db->select('US.APELLIDOS,US.IDUSUARIO, US.NOMBRES,UG.IDGRUPO');
        $this->db->from('USUARIOS US');
        $this->db->join('USUARIOS_GRUPOS UG', 'UG.IDUSUARIO=US.IDUSUARIO');
        $this->db->where('UG.IDGRUPO', 42);
        $this->db->where('US.COD_REGIONAL', $regional);
        $datos = $this->db->get();
        $datos = $datos->result_array();
        return $datos[0];
    }

    function abogado($id) {
        $this->db->select('APELLIDOS, NOMBRES');
        $this->db->from('USUARIOS');
        $this->db->where('IDUSUARIO', $id);
        $query = $this->db->last_query();
        //echo $query;
        $abogado = $this->db->get();
        $abogado = $abogado->result_array();
        return $abogado[0];
    }

    function consulta_regional($nit) {
        $this->db->select('COD_REGIONAL');
        $this->db->from('EMPRESA');
        $this->db->where('CODEMPRESA', $nit);
        $regional = $this->db->get();
        $regional = $regional->result_array();
        return $regional[0];
    }

    function Procesos($cod_regional, $usuario, $cod_coactivo, $titulo) {
        
           /* Acercamiento Persuasivo $subQuery2 */
        /* Medidas Cautelares investigacion  $subQuery3 */
        /* Medidas Cautelares Avaluo $subQuery4 */
        /* Mandamoento Pago $subQuery5 */
        /* Terminación de proceso $subQuery6 */
        /* Medidas Cautelares Remate */
        /* Procesos Coactivos  $subQuery7 */
        /* Traslado Judicial $subQuery8 */
        /* Resolución Prescripción  $subQuery9 */
        $regional = ' AND VW.COD_REGIONAL=' . $cod_regional . '';
        if ($this->ion_auth->is_admin()):
            $regional = '';
        endif;
        //  echo "-" . $regional;
        $this->db->select('CP.COD_COBRO_PERSUASIVO AS PROCESO,'
                . 'TO_CHAR(CP.COD_TIPO_RESPUESTA) AS COD_RESPUESTA,'
                . 'VW.RESPUESTA AS RESPUESTA,'
                . 'PC.COD_PROCESO_COACTIVO AS COD_PROCESO,'
                . 'PC.ABOGADO AS ABOGADO, '
                . 'PC.COD_PROCESOPJ AS PROCESOPJ,'
                . 'VW.EJECUTADO AS NOMBRE,'
                . 'PC.IDENTIFICACION AS IDENTIFICACION, '
                . 'US.NOMBRES, US.APELLIDOS, '
                . 'VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL, '
                . 'VW.COD_REGIONAL AS COD_REGIONAL,VW.CONCEPTO,'
                . 'VW.FECHA_COACTIVO AS FECHA_RECEPCION,VW.SALDO_DEUDA,VW.SALDO_CAPITAL,VW.SALDO_INTERES,VW.NO_EXPEDIENTE,'
                . 'VW.COD_EXPEDIENTE_JURIDICA');

        $this->db->from('COBROPERSUASIVO CP');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=CP.COD_PROCESO_COACTIVO');
        /**/
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=PC.ABOGADO');
        /**/
        $where = 'VW.COD_RESPUESTA = CP.COD_TIPO_RESPUESTA AND CP.COD_TIPO_RESPUESTA NOT IN (204,196,193,201) AND VW.SALDO_DEUDA!=0 AND PC.AUTO_CIERRE IS NULL' . $regional;
        $this->db->where($where);
        $query2 = $this->db->get('');
        $subQuery2 = $this->db->last_query();
        $query2 = $query2->result_array();
        //MEDIDAS CAUTELARES

        $this->db->select("MC.COD_MEDIDACAUTELAR AS PROCESO,MP.COD_TIPOGESTION || '*?*' || MC.COD_RESPUESTAGESTION AS COD_RESPUESTA, RG.NOMBRE_GESTION  || '*?*' || VW.RESPUESTA AS RESPUESTA, PC.COD_PROCESO_COACTIVO AS COD_PROCESO,PC.ABOGADO AS ABOGADO, PC.COD_PROCESOPJ AS PROCESOPJ,VW.EJECUTADO AS NOMBRE,PC.IDENTIFICACION AS IDENTIFICACION, US.NOMBRES, US.APELLIDOS, VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL, VW.COD_REGIONAL AS COD_REGIONAL,VW.CONCEPTO,VW.FECHA_COACTIVO AS FECHA_RECEPCION,VW.SALDO_DEUDA,VW.SALDO_CAPITAL,VW.SALDO_INTERES,VW.NO_EXPEDIENTE,VW.COD_EXPEDIENTE_JURIDICA");
        $this->db->from('MC_MEDIDASCAUTELARES MC');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=MC.COD_PROCESO_COACTIVO');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=PC.ABOGADO');
        $this->db->join('MC_MEDIDASPRELACION MP', 'MP.COD_MEDIDACAUTELAR=MC.COD_MEDIDACAUTELAR ', 'LEFT');
        $this->db->join('RESPUESTAGESTION RG', 'RG.COD_RESPUESTA= MP.COD_TIPOGESTION OR RG.COD_RESPUESTA=MC.COD_RESPUESTAGESTION');

        $where = 'VW.COD_RESPUESTA = MC.COD_RESPUESTAGESTION OR  VW.COD_RESPUESTA=MP.COD_TIPOGESTION AND MP.COD_TIPOGESTION NOT IN (378,617,1011)  '
                . 'AND VW.SALDO_DEUDA!=0 AND PC.AUTO_CIERRE IS NULL' . $regional;

        $this->db->where($where);
        $query3 = $this->db->get('');
        $subQuery3 = $this->db->last_query();
        // echo $subQuery3;
//        $query3 = $query3->result_array();
        //   return $query3;
        //   
        //Mc_Avaluo
        $this->db->select('MA.COD_AVALUO AS PROCESO,TO_CHAR(MA.COD_TIPORESPUESTA) AS COD_RESPUESTA,VW.RESPUESTA AS RESPUESTA,'
                . 'PC.COD_PROCESO_COACTIVO AS COD_PROCESO,PC.ABOGADO AS ABOGADO, PC.COD_PROCESOPJ AS PROCESOPJ,VW.EJECUTADO AS NOMBRE,'
                . 'PC.IDENTIFICACION AS IDENTIFICACION, US.NOMBRES, US.APELLIDOS, VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,'
                . ' VW.COD_REGIONAL AS COD_REGIONAL,VW.CONCEPTO,VW.FECHA_COACTIVO AS FECHA_RECEPCION,VW.SALDO_DEUDA,'
                . 'VW.SALDO_CAPITAL,VW.SALDO_INTERES,VW.NO_EXPEDIENTE,VW.COD_EXPEDIENTE_JURIDICA');
        $this->db->from('MC_AVALUO MA');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=MA.COD_PROCESO_COACTIVO');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=PC.ABOGADO');
        $where = 'VW.COD_RESPUESTA = MA.COD_TIPORESPUESTA AND VW.SALDO_DEUDA!=0 AND PC.AUTO_CIERRE IS NULL' . $regional;
        $this->db->where($where);
        $query4 = $this->db->get('');
        $subQuery4 = $this->db->last_query();
        $query4 = $query4->result_array();
//        echo $subQuery4;
//MC_remate
//Mandamiento

        $this->db->select('MP.COD_MANDAMIENTOPAGO AS PROCESO,TO_CHAR(MP.ESTADO) AS COD_RESPUESTA,VW.RESPUESTA AS RESPUESTA,'
                . 'PC.COD_PROCESO_COACTIVO AS COD_PROCESO,PC.ABOGADO AS ABOGADO, PC.COD_PROCESOPJ AS PROCESOPJ,VW.EJECUTADO AS NOMBRE,'
                . 'PC.IDENTIFICACION AS IDENTIFICACION, US.NOMBRES, US.APELLIDOS, VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,'
                . ' VW.COD_REGIONAL AS COD_REGIONAL, VW.CONCEPTO,VW.FECHA_COACTIVO AS FECHA_RECEPCION,VW.SALDO_DEUDA,'
                . 'VW.SALDO_CAPITAL,VW.SALDO_INTERES,VW.NO_EXPEDIENTE,VW.COD_EXPEDIENTE_JURIDICA');
        $this->db->from('MANDAMIENTOPAGO MP');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=MP.COD_PROCESO_COACTIVO');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=PC.ABOGADO');
        $where = 'VW.COD_RESPUESTA = MP.ESTADO AND VW.SALDO_DEUDA!=0 AND PC.AUTO_CIERRE IS NULL' . $regional;
        $this->db->where($where);
        $query5 = $this->db->get('');
        $query5 = $query5->result_array();
        $subQuery5 = $this->db->last_query();
        $subQuery5;
        /* Terminación de proceso */
        $this->db->select('AJ.NUM_AUTOGENERADO AS PROCESO, TO_CHAR(GC.COD_TIPO_RESPUESTA) AS COD_RESPUESTA,VW.RESPUESTA AS RESPUESTA,'
                . 'PC.COD_PROCESO_COACTIVO AS COD_PROCESO,PC.ABOGADO AS ABOGADO, PC.COD_PROCESOPJ AS PROCESOPJ,VW.EJECUTADO AS NOMBRE,'
                . 'PC.IDENTIFICACION AS IDENTIFICACION, US.NOMBRES, US.APELLIDOS, VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,'
                . ' VW.COD_REGIONAL AS COD_REGIONAL, VW.CONCEPTO,VW.FECHA_COACTIVO AS FECHA_RECEPCION,'
                . 'VW.SALDO_DEUDA,VW.SALDO_CAPITAL,VW.SALDO_INTERES,VW.NO_EXPEDIENTE,VW.COD_EXPEDIENTE_JURIDICA'); /* AJ.AUTOSJURIDICOS AS PROCESO,GC.COD_TIPO_RESPUES */
        $this->db->from('AUTOSJURIDICOS AJ');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=AJ.COD_PROCESO_COACTIVO');
        $this->db->join('TRAZAPROCJUDICIAL GC', 'GC.COD_TRAZAPROCJUDICIAL=AJ.COD_GESTIONCOBRO');
        $this->db->join('RESPUESTAGESTION RES', 'RES.COD_RESPUESTA=GC.COD_TIPO_RESPUESTA');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=PC.ABOGADO');
        $where = 'VW.COD_RESPUESTA = GC.COD_TIPO_RESPUESTA AND PC.AUTO_CIERRE IS NULL ' . $regional;
        $this->db->where('AJ.COD_TIPO_AUTO', 1);
        $this->db->where('AJ.COD_TIPO_PROCESO', 1);
        $this->db->where($where);
        $query6 = $this->db->get('');
        $subQuery6 = $this->db->last_query();
        echo "<br>";
        echo $subQuery6;echo "<br>";
        $query6 = $query6->result_array();

        $cod_respuesta = '( 170 )';

        if (!empty($titulo)):
            $where_titulo = 'AND ( RT.COD_RECEPCIONTITULO = ' . $titulo . ')';
        else:
            $titulo = '';
        endif;

        $secretario = FALSE;
        $coordinador = FALSE;
        if ($usuario == ID_SECRETARIO || $usuario == ID_COORDINADOR):
            $abogado = FALSE;
        else://El usuario es abogado
            $abogado = TRUE;
        endif;

        if ($abogado == TRUE):
            // $abogado_titulos = ' AND RT.COD_ABOGADO=' . $usuario;
            // $abogado_procesos = ' WHERE  PR.ABOGADO=' . $usuario;
            $abogado_titulos = '';
            $where_abogado = '';

        else:
            $abogado_titulos = '';
            $where_abogado = '';
        endif;
        $where_coactivo = '';
        if (!empty($cod_coactivo)):
            if ($where_abogado != ''):
                $where_coactivo = 'AND ( PR.COD_PROCESO= ' . $cod_coactivo . ')';
            else:
                $where_coactivo = 'WHERE ( PR.COD_PROCESO = ' . $cod_coactivo . ')';
            endif;
        endif;
        $where_proceso = $where_abogado . " " . $where_coactivo;
        //echo "<br>","------".$where;
//        $regional = ' '; 
        $regional = ' AND (REG.COD_REGIONAL=' . $cod_regional . ')';
        if ($this->ion_auth->is_admin()):
            $regional = '';
        endif;
        $query1 = "
            SELECT DISTINCT 
            RT.COD_RECEPCIONTITULO AS PROCESO,
            RT.COD_TIPORESPUESTA AS COD_RESPUESTA,
            RG.NOMBRE_GESTION AS RESPUESTA, 
            TO_NUMBER(F.COD_FISCALIZACION) AS COD_PROCESO,
            TO_CHAR(F.COD_FISCALIZACION) AS PROCESOPJ,
            RT.COD_ABOGADO AS ABOGADO,
            '' AS NOMBRES,
            '' AS APELLIDOS,
            E.RAZON_SOCIAL AS NOMBRE, 
            E.CODEMPRESA AS IDENTIFICACION,
            REG.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,
            REG.COD_REGIONAL AS COD_REGIONAL,
            RG.URLGESTION AS URL,
            RG.IDCARGO AS CARGO,
            CF.NOMBRE_CONCEPTO AS CONCEPTO,
            RT.FECHA_CONSULTAONBASE,VR.SALDO_DEUDA,VR.SALDO_CAPITAL,VR.SALDO_INTERES
            FROM
            
            RECEPCIONTITULOS RT,
            FISCALIZACION F, ASIGNACIONFISCALIZACION AF, EMPRESA E, CONCEPTOSFISCALIZACION CF, RESPUESTAGESTION RG, TIPOGESTION TG,
            TIPOPROCESO TP , REGIONAL REG, VW_RECEPCIONTITULOS VR
            
            WHERE 
            
            (CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO) AND (F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA) 
            AND (AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC) AND (E.CODEMPRESA = AF.NIT_EMPRESA) AND
            (TG.COD_GESTION = RG.COD_TIPOGESTION) AND
            (TG.CODPROCESO = TP.COD_TIPO_PROCESO) AND 
            (RG.COD_RESPUESTA=RT.COD_TIPORESPUESTA) 
             AND (E.COD_REGIONAL=REG.COD_REGIONAL) 
             AND (VR.SALDO_DEUDA > 0)
             " . $titulo . "
             AND (RT.COD_TIPORESPUESTA NOT IN (1325,623,1114,1123,178,1367))
                " . $regional . "
                    " . $abogado_titulos . "
                        AND (RT.COD_RECEPCIONTITULO=VR.NO_EXPEDIENTE)
            UNION( SELECT DISTINCT 
            RT.COD_RECEPCIONTITULO AS PROCESO,
            RT.COD_TIPORESPUESTA AS COD_RESPUESTA,
              RG.NOMBRE_GESTION AS RESPUESTA, 
            NM.COD_CARTERA_NOMISIONAL AS COD_PROCESO,
           TO_CHAR( NM.COD_CARTERA_NOMISIONAL) AS PROCESOPJ,
            RT.COD_ABOGADO AS ABOGADO,
            'NOMBRE' AS NOMBRES,
            'APELLIDO' AS APELLIDOS,
            E.RAZON_SOCIAL AS NOMBRE,
              E.COD_ENTIDAD  AS IDENTIFICACION, 
            REG.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,
            REG.COD_REGIONAL AS COD_REGIONAL,
            RG.URLGESTION AS URL,
            RG.IDCARGO AS CARGO,
            TC.NOMBRE_CARTERA AS CONCEPTO,
             RT.FECHA_CONSULTAONBASE,VR.SALDO_DEUDA,VR.SALDO_CAPITAL,VR.SALDO_INTERES
            FROM 
            RECEPCIONTITULOS RT, CNM_CARTERANOMISIONAL NM, CNM_EMPRESA E, TIPOCARTERA TC, RESPUESTAGESTION RG,
            TIPOGESTION TG, TIPOPROCESO TP, REGIONAL REG,VW_RECEPCIONTITULOS VR
            WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL) AND
           (E.COD_ENTIDAD = NM.COD_EMPRESA) AND (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA) AND (NM.COD_EMPRESA = E.COD_ENTIDAD) AND 
           (TG.COD_GESTION = RG.COD_TIPOGESTION) AND
           
            (TG.CODPROCESO = TP.COD_TIPO_PROCESO) 
            AND (RG.COD_RESPUESTA=RT.COD_TIPORESPUESTA) 
           AND (RT.COD_RECEPCIONTITULO=VR.NO_EXPEDIENTE) AND
            (E.COD_REGIONAL=REG.COD_REGIONAL) 
            AND (VR.SALDO_DEUDA > 0)
             " . $titulo . "
             AND (RT.COD_TIPORESPUESTA NOT IN (1325,623,1114,1123,178,1367))
                " . $regional . "
                      " . $abogado_titulos . " 
             UNION( 
             SELECT DISTINCT 
             RT.COD_RECEPCIONTITULO AS PROCESO,
             RT.COD_TIPORESPUESTA AS COD_RESPUESTA,
             RG.NOMBRE_GESTION AS RESPUESTA, 
             NM.COD_CARTERA_NOMISIONAL AS COD_PROCESO,
             TO_CHAR( NM.COD_CARTERA_NOMISIONAL) AS PROCESOPJ,
            RT.COD_ABOGADO AS ABOGADO,
            'NOMBRE' AS NOMBRES,
            'APELLIDO' AS APELLIDOS,
             E.NOMBRES || E.APELLIDOS AS NOMBRE , 
             TO_CHAR(E.IDENTIFICACION) AS IDENTIFICACION,
             REG.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,
             REG.COD_REGIONAL AS COD_REGIONAL,
             RG.URLGESTION AS URL,
             RG.IDCARGO AS CARGO,
             TC.NOMBRE_CARTERA AS CONCEPTO,
              RT.FECHA_CONSULTAONBASE,VR.SALDO_DEUDA,VR.SALDO_CAPITAL,VR.SALDO_INTERES
             
             FROM 
             RECEPCIONTITULOS RT, CNM_CARTERANOMISIONAL NM, CNM_EMPLEADO E, TIPOCARTERA TC,
             RESPUESTAGESTION RG, TIPOGESTION TG, TIPOPROCESO TP, REGIONAL REG,VW_RECEPCIONTITULOS VR
             
             WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL) 
             AND (E.IDENTIFICACION = NM.COD_EMPLEADO) AND
             (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA) AND 
             (TG.COD_GESTION = RG.COD_TIPOGESTION) AND
             
             (TG.CODPROCESO = TP.COD_TIPO_PROCESO)
           AND (RT.COD_RECEPCIONTITULO=VR.NO_EXPEDIENTE)          AND
             (RG.COD_RESPUESTA=RT.COD_TIPORESPUESTA) 
             
             AND  (E.COD_REGIONAL=REG.COD_REGIONAL) 
               AND (VR.SALDO_DEUDA > 0)
             " . $titulo . "
                   AND (RT.COD_TIPORESPUESTA NOT IN (1325,623,1114,1123,178,1367))
                    " . $regional . "
                          " . $abogado_titulos . "  
            ) ) "
        ;
        //echo $query1;
        $regional = ' AND VW.COD_REGIONAL=' . $cod_regional . '';
        if ($this->ion_auth->is_admin()):
            $regional = '';
        endif;
//echo $query1;
        /* procesos coactivos */
        $this->db->select('PC.COD_PROCESO_COACTIVO AS PROCESO,TO_CHAR(PC.COD_RESPUESTA) AS COD_RESPUESTA,VW.RESPUESTA AS RESPUESTA,'
                . 'PC.COD_PROCESO_COACTIVO AS COD_PROCESO,PC.ABOGADO AS ABOGADO, PC.COD_PROCESOPJ AS PROCESOPJ,VW.EJECUTADO AS NOMBRE,'
                . 'PC.IDENTIFICACION AS IDENTIFICACION, US.NOMBRES, US.APELLIDOS, VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,'
                . ' VW.COD_REGIONAL AS COD_REGIONAL,VW.CONCEPTO,VW.FECHA_COACTIVO AS FECHA_RECEPCION,VW.SALDO_DEUDA,'
                . 'VW.SALDO_CAPITAL,VW.SALDO_INTERES,VW.NO_EXPEDIENTE,VW.COD_EXPEDIENTE_JURIDICA');
        $this->db->from('PROCESOS_COACTIVOS PC');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=PC.ABOGADO');
        $where = 'VW.COD_RESPUESTA = PC.COD_RESPUESTA AND PC.COD_RESPUESTA NOT IN (1123,1114,1175)  AND VW.SALDO_DEUDA!=0 AND PC.AUTO_CIERRE IS NULL' . $regional;
        $this->db->where($where);
        $query7 = $this->db->get('');
        $query7 = $query7->result_array();
        $subQuery7 = $this->db->last_query();
        //echo $subQuery7;
        //TRASLADO DE PROCESO JUDICIAL

        $this->db->select('TJ.COD_TRASLADO AS PROCESO,TO_CHAR(TJ.COD_RESPUESTA) AS COD_RESPUESTA,VW.RESPUESTA AS RESPUESTA,'
                . 'PC.COD_PROCESO_COACTIVO AS COD_PROCESO,PC.ABOGADO AS ABOGADO, PC.COD_PROCESOPJ AS PROCESOPJ,VW.EJECUTADO AS NOMBRE,'
                . 'PC.IDENTIFICACION AS IDENTIFICACION, US.NOMBRES, US.APELLIDOS, VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,'
                . ' VW.COD_REGIONAL AS COD_REGIONAL, VW.CONCEPTO,VW.FECHA_COACTIVO AS FECHA_RECEPCION,VW.SALDO_DEUDA,'
                . 'VW.SALDO_CAPITAL,VW.SALDO_INTERES,VW.NO_EXPEDIENTE,VW.COD_EXPEDIENTE_JURIDICA');
        $this->db->from('TRASLADO_JUDICIAL TJ');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=TJ.COD_PROCESO_COACTIVO');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=PC.ABOGADO');
        $where = 'VW.COD_RESPUESTA = TJ.COD_RESPUESTA AND TJ.COD_RESPUESTA NOT IN(1124) AND  VW.SALDO_DEUDA!=0 AND PC.AUTO_CIERRE IS NULL' . $regional;
        $this->db->where($where);
        $query8 = $this->db->get('');
        $query8 = $query8->result_array();
        $subQuery8 = $this->db->last_query();
        $subQuery8;




        /* Documentos Liquidación de Credito */

        $this->db->select('AJ.NUM_AUTOGENERADO AS PROCESO, TO_CHAR(GC.COD_TIPO_RESPUESTA) AS COD_RESPUESTA,VW.RESPUESTA AS RESPUESTA,'
                . 'PC.COD_PROCESO_COACTIVO AS COD_PROCESO,PC.ABOGADO AS ABOGADO, PC.COD_PROCESOPJ AS PROCESOPJ,VW.EJECUTADO AS NOMBRE,'
                . 'PC.IDENTIFICACION AS IDENTIFICACION, US.NOMBRES, US.APELLIDOS, VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,'
                . ' VW.COD_REGIONAL AS COD_REGIONAL,VW.CONCEPTO,VW.FECHA_COACTIVO AS FECHA_RECEPCION,VW.SALDO_DEUDA,'
                . 'VW.SALDO_CAPITAL,VW.SALDO_INTERES,VW.NO_EXPEDIENTE,VW.COD_EXPEDIENTE_JURIDICA'); /* AJ.AUTOSJURIDICOS AS PROCESO,GC.COD_TIPO_RESPUES */
        $this->db->from('AUTOSJURIDICOS AJ');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=AJ.COD_PROCESO_COACTIVO');
        $this->db->join('TRAZAPROCJUDICIAL GC', 'GC.COD_TRAZAPROCJUDICIAL=AJ.COD_GESTIONCOBRO');
        // $this->db->join('RESPUESTAGESTION RES', 'RES.COD_RESPUESTA=GC.COD_TIPO_RESPUESTA');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=PC.ABOGADO');
        $where = "VW.COD_RESPUESTA = GC.COD_TIPO_RESPUESTA  AND VW.SALDO_DEUDA != 0   AND EXISTS(
        SELECT 'X'
        FROM AUTOSJURIDICOS LA
        WHERE LA.COD_PROCESO_COACTIVO = AJ.COD_PROCESO_COACTIVO
              AND (LA.COD_TIPO_AUTO IN (3, 24) )
              AND (LA.COD_TIPO_PROCESO = 14)
        HAVING MAX(LA.NUM_AUTOGENERADO) = AJ.NUM_AUTOGENERADO
      )" . $regional;
        $this->db->where_in('AJ.COD_TIPO_AUTO', array(3, 24));
        $this->db->where('AJ.COD_TIPO_PROCESO', 14);
        $this->db->where($where);
        $query10 = $this->db->get('');
        $subQuery10 = $this->db->last_query();
        $query10 = $query10->result_array();
        echo $subQuery10;
        //RESOLUCION_PRESCRIPCION
        $this->db->select('RP.COD_PRESCRIPCION AS PROCESO,TO_CHAR(RP.COD_RESPUESTA) AS COD_RESPUESTA,VW.RESPUESTA AS RESPUESTA,'
                . 'PC.COD_PROCESO_COACTIVO AS COD_PROCESO,PC.ABOGADO AS ABOGADO, PC.COD_PROCESOPJ AS PROCESOPJ,VW.EJECUTADO AS NOMBRE,'
                . 'PC.IDENTIFICACION AS IDENTIFICACION, US.NOMBRES, US.APELLIDOS, VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,'
                . ' VW.COD_REGIONAL AS COD_REGIONAL,VW.CONCEPTO,VW.FECHA_COACTIVO AS FECHA_RECEPCION,VW.SALDO_DEUDA,'
                . 'VW.SALDO_CAPITAL,VW.SALDO_INTERES,VW.NO_EXPEDIENTE,VW.COD_EXPEDIENTE_JURIDICA');
        $this->db->from('RESOLUCION_PRESCRIPCION RP');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=RP.COD_PROCESO_COACTIVO', 'inner');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=RP.COD_PROCESO_COACTIVO', 'inner');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=PC.ABOGADO', 'inner');
        $where = 'VW.COD_RESPUESTA = RP.COD_RESPUESTA  AND VW.SALDO_DEUDA!=0 AND PC.AUTO_CIERRE IS NULL' . $regional;
        $this->db->where($where);
        $query9 = $this->db->get('');
        $query9 = $query9->result_array();
        $subQuery9 = $this->db->last_query();
        // echo $subQuery9; 
        //Acuerdo de Pago
        $this->db->select('AP.NRO_ACUERDOPAGO AS PROCESO, TO_CHAR(AP.COD_RESPUESTA) AS COD_RESPUESTA, VW.RESPUESTA AS RESPUESTA,'
                . 'PC.COD_PROCESO_COACTIVO AS COD_PROCESO,PC.ABOGADO AS ABOGADO, PC.COD_PROCESOPJ AS PROCESOPJ,VW.EJECUTADO AS NOMBRE,'
                . 'PC.IDENTIFICACION AS IDENTIFICACION, US.NOMBRES, US.APELLIDOS, VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,'
                . ' VW.COD_REGIONAL AS COD_REGIONAL,VW.CONCEPTO,VW.FECHA_COACTIVO AS FECHA_RECEPCION,VW.SALDO_DEUDA,'
                . 'VW.SALDO_CAPITAL,VW.SALDO_INTERES,VW.NO_EXPEDIENTE,VW.COD_EXPEDIENTE_JURIDICA'); /* AJ.AUTOSJURIDICOS AS PROCESO,GC.COD_TIPO_RESPUES */
        $this->db->from('ACUERDOPAGO AP');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=AP.COD_PROCESO_COACTIVO');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=PC.ABOGADO');
        $where2 = 'VW.COD_RESPUESTA = AP.COD_RESPUESTA AND AP.JURIDICO=1 AND VW.SALDO_DEUDA!=0 AND PC.AUTO_CIERRE IS NULL AND AP.COD_RESPUESTA!=1272' . $regional;
        $this->db->where($where2);
        $this->db->where('AP.JURIDICO', 1);
        $query11 = $this->db->get('');
        $subQuery11 = $this->db->last_query();
        // $query11 = $query11->result_array();
        //  echo $subQuery11 ; 
        //die();
        //REMATE
        $this->db->select('MR.COD_REMATE AS PROCESO,TO_CHAR(MR.COD_RESPUESTA) AS COD_RESPUESTA,VW.RESPUESTA AS RESPUESTA,'
                . 'PC.COD_PROCESO_COACTIVO AS COD_PROCESO,PC.ABOGADO AS ABOGADO, PC.COD_PROCESOPJ AS PROCESOPJ,VW.EJECUTADO AS NOMBRE,'
                . 'PC.IDENTIFICACION AS IDENTIFICACION, US.NOMBRES, US.APELLIDOS, VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,'
                . ' VW.COD_REGIONAL AS COD_REGIONAL,VW.CONCEPTO,VW.FECHA_COACTIVO AS FECHA_RECEPCION,VW.SALDO_DEUDA,VW.SALDO_CAPITAL,'
                . 'VW.SALDO_INTERES,VW.NO_EXPEDIENTE,VW.COD_EXPEDIENTE_JURIDICA');
        $this->db->from('MC_REMATE MR');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=MR.COD_PROCESO_COACTIVO', 'inner');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=MR.COD_PROCESO_COACTIVO', 'inner');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=PC.ABOGADO', 'inner');
        $where = 'VW.COD_RESPUESTA = MR.COD_RESPUESTA AND VW.SALDO_DEUDA!=0 AND PC.AUTO_CIERRE IS NULL' . $regional;
        $this->db->where($where);
        $query12 = $this->db->get('');
        $query12 = $query12->result_array();
        $subQuery12 = $this->db->last_query();
        //echo $subQuery12;
        //NULIDAD
        $this->db->select('N.COD_NULIDAD AS PROCESO,TO_CHAR(N.COD_RESPUESTA) AS COD_RESPUESTA,VW.RESPUESTA AS RESPUESTA,'
                . 'PC.COD_PROCESO_COACTIVO AS COD_PROCESO,PC.ABOGADO AS ABOGADO, PC.COD_PROCESOPJ AS PROCESOPJ,VW.EJECUTADO AS NOMBRE,'
                . 'PC.IDENTIFICACION AS IDENTIFICACION, US.NOMBRES, US.APELLIDOS, VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,'
                . ' VW.COD_REGIONAL AS COD_REGIONAL,VW.CONCEPTO,VW.FECHA_COACTIVO AS FECHA_RECEPCION,'
                . 'VW.SALDO_DEUDA,VW.SALDO_CAPITAL,VW.SALDO_INTERES,VW.NO_EXPEDIENTE,VW.COD_EXPEDIENTE_JURIDICA');
        $this->db->from('NULIDAD N');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=N.COD_PROCESO_COACTIVO', 'inner');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=N.COD_PROCESO_COACTIVO', 'inner');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=PC.ABOGADO', 'inner');
        $where = 'VW.COD_RESPUESTA = N.COD_RESPUESTA AND VW.SALDO_DEUDA!=0 AND PC.AUTO_CIERRE IS NULL';
        $this->db->where($where);
        $query13 = $this->db->get('');
        $query13 = $query13->result_array();
        $subQuery13 = $this->db->last_query();
        //   echo $subQuery13;

        /* REMISIBILIDAD */
        $this->db->select('REM.COD_REMISIBILIDAD AS PROCESO,TO_CHAR(REM.COD_TIPORESPUESTA) AS COD_RESPUESTA,VW.RESPUESTA AS RESPUESTA,'
                . 'PC.COD_PROCESO_COACTIVO AS COD_PROCESO,PC.ABOGADO AS ABOGADO, PC.COD_PROCESOPJ AS PROCESOPJ,VW.EJECUTADO AS NOMBRE,'
                . 'PC.IDENTIFICACION AS IDENTIFICACION, US.NOMBRES, US.APELLIDOS, VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,'
                . ' VW.COD_REGIONAL AS COD_REGIONAL,VW.CONCEPTO,VW.FECHA_COACTIVO AS FECHA_RECEPCION,'
                . 'VW.SALDO_DEUDA,VW.SALDO_CAPITAL,VW.SALDO_INTERES,VW.NO_EXPEDIENTE,VW.COD_EXPEDIENTE_JURIDICA');
        $this->db->from('REM_REMISIBILIDAD REM');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=REM.COD_PROCESO_COACTIVO', 'inner');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=REM.COD_PROCESO_COACTIVO', 'inner');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=PC.ABOGADO', 'inner');
        $where = 'VW.COD_RESPUESTA = REM.COD_TIPORESPUESTA AND REM.COD_TIPORESPUESTA IN (1116, 1117,1118, 1119,1121 ,1472) AND VW.SALDO_DEUDA!=0 AND PC.AUTO_CIERRE IS NULL' . $regional;
        $this->db->where($where);
        $query14 = $this->db->get('');
        $query14 = $query14->result_array();
        $subQuery14 = $this->db->last_query();

        $querys = "($subQuery2   UNION $subQuery3  UNION $subQuery4 UNION $subQuery5 UNION $subQuery6 UNION $subQuery7 UNION $subQuery8  UNION $subQuery9 UNION  $subQuery10 UNION $subQuery12 UNION $subQuery11  UNION $subQuery13 UNION $subQuery14)";
        //$querys = "($subQuery3)";
        ////$querys = "($subQuery2  )";
        // $querys = "($subQuery10)";

        $qry_final = "SELECT PR.COD_PROCESO,PR.IDENTIFICACION,PR.NOMBRE,PR.NOMBRE_REGIONAL,PR.COD_REGIONAL,PR.NOMBRES,PR.APELLIDOS,
            PR.PROCESOPJ,PR.ABOGADO,
             LISTAGG (PR.NO_EXPEDIENTE,'?*') WITHIN GROUP (ORDER BY PR.NO_EXPEDIENTE) \"NUMEROS EXPEDIENTES\",
             LISTAGG (PR.SALDO_DEUDA,'?*') WITHIN GROUP (ORDER BY PR.SALDO_DEUDA) \"SALDOS DEUDAS\",
               LISTAGG (PR.SALDO_CAPITAL,'?*') WITHIN GROUP (ORDER BY PR.SALDO_CAPITAL) \"SALDOS CAPITAL\",
                 LISTAGG (PR.SALDO_INTERES,'?*') WITHIN GROUP (ORDER BY PR.SALDO_INTERES) \"SALDOS INTERESES\",
            LISTAGG (PR.FECHA_RECEPCION,'?*') WITHIN GROUP (ORDER BY PR.FECHA_RECEPCION) \"FECHAS RECEPCION\",
            LISTAGG (PR.CONCEPTO,'?*') WITHIN GROUP (ORDER BY PR.CONCEPTO) \"CONCEPTOS UNIDOS\",
        LISTAGG (PR.RESPUESTA,'*?*') WITHIN GROUP (ORDER BY PR.COD_RESPUESTA) \"RESPUESTAS UNIDAS\",
        LISTAGG (PR.COD_EXPEDIENTE_JURIDICA,'?*') WITHIN GROUP (ORDER BY PR.COD_EXPEDIENTE_JURIDICA) \"FISCALIZACIONES\",
        LISTAGG (PR.COD_RESPUESTA,'*?*') WITHIN GROUP (ORDER BY PR.COD_RESPUESTA) \"CODIGOS RESPUESTAS\"
        
        FROM " . $querys . " PR " . $where_proceso . " 
        GROUP BY PR.COD_PROCESO,PR.IDENTIFICACION,PR.NOMBRE,PR.NOMBRE_REGIONAL,PR.COD_REGIONAL,
        PR.NOMBRES,PR.APELLIDOS,PR.PROCESOPJ,PR.ABOGADO";
     //   echo "<br>";
        //echo $qry_final;
//        $qry_final2="SELECT MAX(FECHA),COD_PROCESO, IDENTIFICACION,NOMBRE,NOMBRE_REGIONAL,COD_REGIONAL,NOMBRES,APELLIDOS, "
//                . "PROCESOPJ,ABOGADO,NUMEROS EXPEDIENTES,SALDOS DEUDAS,SALDOS CAPITAL"
//                . " FROM ( ".$qry_final." ) PR, TRAZAPROCJUDICIAL TR WHERE TR.COD_JURIDICO=PR.COD_PROCESO  GROUP BY PR.COD_PROCESO"
//                . "ORDER BY 1 DESC ";  
//        echo  $qry_final2;
        $querys = $this->db->query($qry_final);
        $resultado = $querys->result_array();
        $query1 = $this->db->query($query1);
        $query1 = $query1->result_array();
        $resultado_final = array('titulos' => $query1, 'procesos' => $resultado);
        if (!empty($cod_coactivo)):
            $resultado_final = array('titulos' => $query1 = array(), 'procesos' => $resultado);
        endif;

        if (!empty($titulo)):
            $resultado_final = array('titulos' => $query1, 'procesos' => $resultado = array());
        endif;

        return $resultado_final;

       
    }

    function procesos_coactivos($regional, $usuario, $cod_coactivo, $titulo) {
       // echo $regional;
 /*         * Para listar los procesos que se encuentran en Recepción de titulos se consulta la vista VW_RECEPCIONTITULOS la cual permite consultar los datos básicos
         * $subQuery1. Para listar los procesos coactivos se consulta la vista VW_PROCESOS_COACTIVOS la cual permite consultar los datos básicos
         * @param integer $regional
         * @param integer $idusuario
         * @param integer $cod_coactivo
         * @param integer $titulo
         * @return array $resultado
         */

        $cod_respuesta = '( 170 )';

        if (!empty($titulo)):
            $where_titulo = 'AND ( RT.COD_RECEPCIONTITULO = ' . $titulo . ')';
        else:
            $titulo = '';
        endif;

        $secretario = FALSE;
        $coordinador = FALSE;
        if ($usuario == ID_SECRETARIO || $usuario == ID_COORDINADOR):
            $abogado = FALSE;
        else://El usuario es abogado
            $abogado = TRUE;
        endif;

        if ($abogado == TRUE):
             $abogado_titulos = ' AND RC.COD_ABOGADO=' . $usuario;
             $abogado_procesos = ' WHERE  PR.ABOGADO=' . $usuario;
//            $abogado_titulos = '';
//            $where_abogado = '';

        else:
            $abogado_titulos = '';
           $abogado_procesos = '';
        endif;
        $where_coactivo = '';
        if (!empty($cod_coactivo)):
            if ($abogado_procesos != ''):
                $where_coactivo = ' AND ( PR.COD_PROCESO= ' . $cod_coactivo . ')';
            else:
                $where_coactivo = ' WHERE ( PR.COD_PROCESO = ' . $cod_coactivo . ')';
            endif;
        endif;
        $where_proceso = $abogado_procesos . " " . $where_coactivo;
        //echo "<br>","------". $where_proceso;die();
       
        $regional = ' AND VW.COD_REGIONAL =' . $regional  ;
        //echo $regional
        if ($this->ion_auth->is_admin()):
            $regional = '';
        endif;
       //echo $regional;
        // $regional = ' ';
        $this->db->select("RC.COD_RECEPCIONTITULO AS COD_PROCESO,
                           VW.IDENTIFICACION AS IDENTIFICACION,
                           VW.EJECUTADO AS NOMBRE, 
                           VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,
                           VW.COD_REGIONAL AS COD_REGIONAL,
                           US.NOMBRES,
                           US.APELLIDOS,
                           RC.COD_FISCALIZACION_EMPRESA || '' || RC.COD_CARTERA_NOMISIONAL AS PROCESOPJ,
                           RC.COD_ABOGADO AS ABOGADO,
                           VW.ULTIMA_ACTUACION,
                           TO_CHAR(VW.COD_CPTO_FISCALIZACION) AS CPTO,
                           TO_CHAR(VW.NO_EXPEDIENTE) AS NUMEROS_EXPEDIENTES,
                           TO_CHAR(VW.SALDO_DEUDA) AS SALDOS_DEUDAS ,
                           TO_CHAR(VW.SALDO_CAPITAL) AS SALDOS_CAPITAL,
                           TO_CHAR(VW.SALDO_INTERES) AS SALDOS_INTERESES,
                           TO_CHAR(VW.FECHA_COACTIVO) AS FECHAS_RECEPCION,
                           TO_CHAR(VW.CONCEPTO) AS CONCEPTOS_UNIDOS,
                           TO_CHAR(RG.NOMBRE_GESTION) AS RESPUESTAS_UNIDAS, 
                           TO_CHAR(RC.COD_FISCALIZACION_EMPRESA) || '' || TO_CHAR(RC.COD_CARTERA_NOMISIONAL) AS FISCALIZACIONES,
                           TO_CHAR(RG.COD_RESPUESTA) AS CODIGOS_RESPUESTAS
                           
                           ");
        $this->db->from('RECEPCIONTITULOS RC');
        $this->db->join('VW_RECEPCIONTITULOS VW', 'VW.NO_EXPEDIENTE=RC.COD_RECEPCIONTITULO', 'inner');
        $this->db->join('RESPUESTAGESTION RG', 'RG.COD_RESPUESTA=RC.COD_TIPORESPUESTA', 'inner');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=RC.COD_ABOGADO', 'left');
        $where = 'RC.COD_TIPORESPUESTA NOT IN (1325,623,1114,1123,178,1367) '. $regional. $abogado_titulos;
        $this->db->where($where);
        $query1 = $this->db->get('');
        $subQuery1 = $this->db->last_query();
         // $resultado = $this->db->get();
        $resultado = $query1->result_array();
        
        
        /*Consulto todos los procesos coactivos*/
        
        $no_int='(1114,1472,1124,1367,1175,1123)';
        $qry_vista="  SELECT 
    DISTINCT 
      VW1.PROCESO, 
      VW1.COD_RESPUESTA, 
      RG.NOMBRE_GESTION AS RESPUESTA, 
      VW1.COD_PROCESO_COACTIVO, 
      VW.ABOGADO AS ABOGADO, 
      VW.COD_PROCESOPJ AS PROCESOPJ, 
      VW.EJECUTADO AS NOMBRE, 
      VW.IDENTIFICACION AS IDENTIFICACION, 
      US.NOMBRES, 
      US.APELLIDOS, 
      VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL, 
      VW.COD_REGIONAL AS COD_REGIONAL, 
      VW.CONCEPTO, VW.FECHA_COACTIVO AS FECHA_RECEPCION, 
      VW.SALDO_DEUDA, 
      VW.SALDO_CAPITAL, 
      VW.SALDO_INTERES, 
      VW.NO_EXPEDIENTE, 
      VW.COD_EXPEDIENTE_JURIDICA, 
      VW.ULTIMA_ACTUACION,
      TO_CHAR(VW.COD_CPTO_FISCALIZACION) AS CPTO
      
  FROM VW_BANDEJA_01 VW1 
        INNER JOIN VW_PROCESOS_COACTIVOS_0002 VW ON VW.COD_PROCESO_COACTIVO=VW1.COD_PROCESO_COACTIVO 
        INNER JOIN USUARIOS US ON US.IDUSUARIO=VW.ABOGADO 
        INNER JOIN RESPUESTAGESTION RG ON RG.COD_RESPUESTA = VW1.COD_TIPO_RESPUESTA";

        
        $qry_procesos = "SELECT PR.COD_PROCESO_COACTIVO AS COD_PROCESO,PR.IDENTIFICACION,PR.NOMBRE,PR.NOMBRE_REGIONAL,PR.COD_REGIONAL,PR.NOMBRES,PR.APELLIDOS,
                    PR.PROCESOPJ,PR.ABOGADO,PR.ULTIMA_ACTUACION,PR.CPTO,
                    LISTAGG (PR.NO_EXPEDIENTE,'?*') WITHIN GROUP (ORDER BY PR.NO_EXPEDIENTE) \"NUMEROS EXPEDIENTES\",
                    LISTAGG (PR.SALDO_DEUDA,'?*') WITHIN GROUP (ORDER BY PR.SALDO_DEUDA) \"SALDOS DEUDAS\",
                    LISTAGG (PR.SALDO_CAPITAL,'?*') WITHIN GROUP (ORDER BY PR.SALDO_CAPITAL) \"SALDOS CAPITAL\",
                    LISTAGG (PR.SALDO_INTERES,'?*') WITHIN GROUP (ORDER BY PR.SALDO_INTERES) \"SALDOS INTERESES\",
                    LISTAGG (PR.FECHA_RECEPCION,'?*') WITHIN GROUP (ORDER BY PR.FECHA_RECEPCION) \"FECHAS_RECEPCION\",
                    LISTAGG (PR.CONCEPTO,'?*') WITHIN GROUP (ORDER BY PR.CONCEPTO) \"CONCEPTOS UNIDOS\",
                    LISTAGG (PR.RESPUESTA,'*?*') WITHIN GROUP (ORDER BY PR.COD_RESPUESTA) \"RESPUESTAS_UNIDAS\",
                    LISTAGG (PR.COD_EXPEDIENTE_JURIDICA,'?*') WITHIN GROUP (ORDER BY PR.COD_EXPEDIENTE_JURIDICA) \"FISCALIZACIONES\",
                    LISTAGG (PR.COD_RESPUESTA,'*?*') WITHIN GROUP (ORDER BY PR.COD_RESPUESTA) \"CODIGOS_RESPUESTAS\"
                    FROM (" . $qry_vista . " AND  VW1.COD_TIPO_RESPUESTA NOT IN ".$no_int.$regional." ) PR " . $where_proceso .  " 
                    GROUP BY PR.COD_PROCESO_COACTIVO,PR.IDENTIFICACION,PR.NOMBRE,PR.NOMBRE_REGIONAL,PR.COD_REGIONAL,
                    PR.NOMBRES,PR.APELLIDOS,PR.PROCESOPJ,PR.ABOGADO, PR.ULTIMA_ACTUACION,PR.CPTO";
        

        $qry_final="(  $subQuery1 UNION $qry_procesos ) ORDER BY ULTIMA_ACTUACION DESC NULLS LAST";
        $querys = $this->db->query($qry_final);
        $resultado = $querys->result_array;
      //  echo  $qry_final;
//        echo  $subQuery1; 
////       echo "<pre>";
//        var_dump($resultado);echo "</pre>";
//        die();
       return $resultado;
     
    }

    function cabecera($respuesta, $proceso) {

        $this->db->select('');
        $this->db->from('VW_PROCESOS_COACTIVOS VW');
        $this->db->where('VW.COD_RESPUESTA', $respuesta);
        $this->db->where('VW.COD_PROCESO_COACTIVO', $proceso);
        $resultado = $this->db->get();
        $resultado = $resultado->result_array();
        return $resultado[0];
    }

    function Liquidaciones($cod_coactivo) {
        /* Metodo que permite consultar las liquidaciones de un proceso coactivo */
    }

    function funcionarios($regional) {
        $this->db->select('');
        $this->db->from('REGIONAL RG');
        $this->db->where('RG.COD_REGIONAL', $regional);
        $regional = $this->db->get('');
        $regional = $regional->result_array();

        //Consulto los id
    }

    function mandamiento($cod_coactivo) {
        $respuesta = 0;
        $this->db->select("MP.COD_MANDAMIENTOPAGO, MP.ESTADO");
        $this->db->from("MANDAMIENTOPAGO MP");
        $this->db->where("MP.COD_PROCESO_COACTIVO=", $cod_coactivo, FALSE);
        $resultado = $this->db->get('');
        $resultado = $resultado->result_array();
        if ($resultado):
            if ($resultado[0]['ESTADO'] == 204):
                $respuesta = TRUE;
            endif;
        endif;
        return $respuesta;
    }

    function medidas($cod_coactivo) {
        $respuesta = 0;
        $this->db->select("MC.COD_MEDIDACAUTELAR, MC.COD_RESPUESTAGESTION");
        $this->db->from("MC_MEDIDASCAUTELARES MC");
        $this->db->where("MC.COD_PROCESO_COACTIVO=", $cod_coactivo, FALSE);
        $resultado = $this->db->get('');
        $resultado = $resultado->result_array();
        //   print_r( $resultado);
        if ($resultado):
            if ($resultado[0]['COD_RESPUESTAGESTION'] == 204):
                $respuesta = TRUE;
            endif;
        endif;
        return $respuesta;
    }

    function acercamiento($cod_coactivo) {
        $respuesta = 0;
        $this->db->select("CP.COD_TIPO_RESPUESTA");
        $this->db->from("COBROPERSUASIVO CP");
        $this->db->where("CP.COD_PROCESO_COACTIVO=", $cod_coactivo, FALSE);
        $resultado = $this->db->get('');
        $resultado = $resultado->result_array();
        if ($resultado):
            if ($resultado[0]['COD_TIPO_RESPUESTA'] == 184):
                $respuesta = TRUE;
            endif;
        endif;
        return $respuesta;
    }

    function detalle_remate($cod_coactivo) {
        $this->db->select('MR.COD_AVALUO,MR.COD_REMATE AS PROCESO,TO_NUMBER(MR.COD_RESPUESTA) AS COD_RESPUESTA,VW.RESPUESTA AS RESPUESTA,'
                . 'PC.COD_PROCESO_COACTIVO AS COD_PROCESO,PC.ABOGADO AS ABOGADO, PC.COD_PROCESOPJ AS PROCESOPJ,VW.EJECUTADO AS NOMBRE,'
                . 'PC.IDENTIFICACION AS IDENTIFICACION,VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,'
                . ' VW.COD_REGIONAL AS COD_REGIONAL, GR.PARAMETRO,MR.COD_PROCESO_COACTIVO');
        $this->db->from('MC_REMATE MR');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=MR.COD_PROCESO_COACTIVO', 'inner');
        $this->db->join('VW_PROCESOS_BANDEJA VW', 'VW.COD_PROCESO_COACTIVO=MR.COD_PROCESO_COACTIVO', 'inner');
        //  $this->db->join('USUARIOS US', 'US.IDUSUARIO=PC.ABOGADO', 'inner');
        $this->db->join('MC_GESTIONREMATE GR', 'GR.COD_RESPUESTA=MR.COD_RESPUESTA', 'LEFT');
        $where = 'VW.COD_RESPUESTA = MR.COD_RESPUESTA AND MR.COD_PROCESO_COACTIVO=' . $cod_coactivo;
        $this->db->where($where);

        $query12 = $this->db->get('');
        $query12 = $query12->result_array();
        //echo  $subQuery12 = $this->db->last_query();die();
        return $query12;
    }

    function CrearFacilidadPago($datos) {
        $this->db->select("NRO_ACUERDOPAGO");
        $this->db->from("ACUERDOPAGO");
        $this->db->where("ACUERDOPAGO.COD_PROCESO_COACTIVO=", $datos['cod_proceso'], FALSE);
        $resultado1 = $this->db->get();

        // echo $resultado1->num_rows();
        if ($resultado1->num_rows() == 0):


            $this->db->set("NITEMPRESA", $datos['nit']);
            $this->db->set("COD_RESPUESTA", $datos['tipo_respuesta'], FALSE);
            $this->db->set("USUARIO_GENERA", ID_USUARIO, FALSE);
            $this->db->set("COD_CONCEPTO_COBRO", $datos['cod_concepto'], FALSE);
            $this->db->set("FECHA_CREACION", 'SYSDATE', FALSE);
            $this->db->set("COD_REGIONAL", $datos['cod_regional'], FALSE);
            $this->db->set("ESTADOACUERDO", 1);
            $this->db->set("JURIDICO", 1);
            $this->db->set("COD_PROCESO_COACTIVO", $datos['cod_proceso'], FALSE);
            $this->db->insert("ACUERDOPAGO");

        endif;

        if ($this->db->affected_rows() == '1'):
            $this->db->set('ACUERDO_PAGO', 0);
            $this->db->where('COD_PROCESO_COACTIVO', $cod_coactivo);
            $this->db->update('PROCESOS_COACTIVOS');

            /* Actualizo cada liquidación  para cada titulo del proceso coactivo */
          foreach($titulos_facilidad as $liquidacion):

                    $this->db->set('COD_PROCESO_COACTIVO',$datos['cod_proceso'], FALSE);
                    $this->db->set('COD_TIPOPROCESO',18);
                    $this->db->where('NUM_LIQUIDACION',$liquidacion);
                    $this->db->update('LIQUIDACION');
              
                endforeach; 
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function AutoTerminacionTitulo($datos) {
        /* Función que valida si un titulo tiene un auto de terminación de proceso creado */
        $resultado = 0;
        $this->db->select('AJ.NUM_AUTOGENERADO');
        $this->db->from('AUTOSJURIDICOS AJ');
        $this->db->join('RECEPCIONTITULOS RT', 'RT.NUM_AUTOGENERADO=AJ.NUM_AUTOGENERADO', 'inner');
        $this->db->where('AJ.COD_PROCESO_COACTIVO', $datos['COD_PROCESO']);
        $this->db->where('RT.COD_RECEPCIONTITULO', $datos['TITULO']);
        $this->db->where('RT.CERRADO', 0);
        $query = $this->db->get();
        if ($query->num_rows() > 0):
            $resultado = $resultado->result_array();
            $resultado = $resultado[0];
        endif;
        print_r($resultado);
        return $resultado;
    }

    function ActualizaProcesoCoactivo($cod_coactivo) {
        /** Función que permite actualizar un proceso coactivo cuando se ha enviado a terminación del proceso */
        $this->db->set('AUTO_CIERRE', 0);
        $this->db->where('COD_PROCESO_COACTIVO', $cod_coactivo);
        $this->db->update('PROCESOS_COACTIVOS');
        if ($this->db->affected_rows() == '1'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }
    function regional($regional){
        $this->db->select('REG.CEDULA_COORDINADOR, REG.CEDULA_SECRETARIO, USUARIO_COORDINADOR.NOMBREUSUARIO NOMBRE_COORDINADOR, USUARIO_SECRETARIO.NOMBREUSUARIO NOMBRE_SECRETARIO');
        $this->db->from('REGIONAL REG');
        $this->db->join('USUARIOS USUARIO_COORDINADOR', 'USUARIO_COORDINADOR.IDUSUARIO =REG.CEDULA_COORDINADOR', 'inner');
        $this->db->join('USUARIOS USUARIO_SECRETARIO', 'USUARIO_SECRETARIO.IDUSUARIO =REG.CEDULA_SECRETARIO', 'inner');
        $this->db->where('REG.COD_REGIONAL',$regional,FALSE);
        $resultado=$this->db->get();
        $resultado=$resultado->result_array();
        return $resultado[0];
        
    }
    
    

}

?>