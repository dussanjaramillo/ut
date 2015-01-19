<?php

class Carteracobro_model extends CI_Model {

    var $nit;
    var $NUM_LIQUIDACION;
    var $cordinador;
    var $cod_municipio;

    function __construct() {
        parent::__construct();
    }

    function set_nit($nit) {
        $this->nit = $nit;
    }

    function set_num_liquidacion($NUM_LIQUIDACION) {
        $this->NUM_LIQUIDACION = $NUM_LIQUIDACION;
    }

    function set_cordinador($cordinador) {
        $this->cordinador = $cordinador;
    }

    function set_cod_municipio($cod_municipio) {
        $this->cod_municipio = $cod_municipio;
    }

    function iniciar($id) {
        $this->db->select("LIQUIDACION.NITEMPRESA as NIT_EMPRESA,"
                . "LIQUIDACION.NUM_LIQUIDACION,"
                . "LIQUIDACION.COD_FISCALIZACION, LIQUIDACION.SALDO_DEUDA AS ESTADO_VALOR_FINAL,"
                . "EMPRESA.RESOLUCION RESOLUCION_CUOTA, '' RESOLUCION_FECHA_RESOLUCION,'' RESOLUCION_FECHA_EJECUTORIA,"
                . "'' ID_ESTADO", FALSE);
        $this->db->join("EMPRESA", "LIQUIDACION.NITEMPRESA=EMPRESA.CODEMPRESA");
//        $this->db->join("LIQUIDACION", "LIQUIDACION.COD_FISCALIZACION=GESTIONCOBRO.COD_FISCALIZACION_EMPRESA");
//        $this->db->where("TO_DATE('" . date("d/m/Y") . "', 'DD/MM/RR') >", "LIQUIDACION.FECHA_VENCIMIENTO ", false);
//        $this->db->where("GESTIONCOBRO.COD_GESTION_COBRO", $id);
        $this->db->where("LIQUIDACION.COD_FISCALIZACION", $id);
        $dato = $this->db->get("LIQUIDACION");
        if (!empty($dato->result_array[0])) {
            return $dato->result_array[0];
        }
    }
    function buscar_nuevas_resoluciones() {
        $this->db->select("LIQUIDACION.NITEMPRESA as NIT_EMPRESA,LIQUIDACION.FECHA_LIQUIDACION,LIQUIDACION.COD_CONCEPTO,EMPRESA.COD_REGIONAL,"
                . "LIQUIDACION.NUM_LIQUIDACION,"
                . "LIQUIDACION.COD_FISCALIZACION, EMPRESA.NOMBRE_EMPRESA,"
                . "(LIQUIDACION.TOTAL_LIQUIDADO) AS DEUDA1, LIQUIDACION.SALDO_DEUDA AS DEUDA2, CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO ");
        $this->db->join("FISCALIZACION", "FISCALIZACION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA = LIQUIDACION.NITEMPRESA");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
                $this->db->join("(SELECT GESTIONCOBRO.COD_FISCALIZACION_EMPRESA,GESTIONCOBRO.COD_TIPOGESTION FROM GESTIONCOBRO
WHERE GESTIONCOBRO.COD_TIPOGESTION=9
GROUP BY GESTIONCOBRO.COD_FISCALIZACION_EMPRESA,GESTIONCOBRO.COD_TIPOGESTION ) B", "B.COD_FISCALIZACION_EMPRESA=LIQUIDACION.COD_FISCALIZACION");
         $this->db->where("LIQUIDACION.COD_FISCALIZACION  NOT IN (
             SELECT DISTINCT COD_FISCALIZACION FROM RESOLUCION WHERE COD_FISCALIZACION IS NOT NULL)", NULL, FALSE);
//         $this->db->where("LIQUIDACION.TOTAL_LIQUIDADO >"," LIQUIDACION.SALDO_DEUDA ",false);
         $this->db->where("LIQUIDACION.SALDO_DEUDA >"," 0 ",false);
         $this->db->where("LIQUIDACION.BLOQUEADA","0");
         $this->db->where("LIQUIDACION.EN_FIRME","S");
         $this->db->where("FISCALIZACION.COD_TIPOGESTION <>","309");
         $this->db->where("EMPRESA.COD_REGIONAL",REGIONAL_ALTUAL);
         $num=array();
         $num[]="1";
         $num[]="2";
         $this->db->where_in("CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION",$num,FALSE);
        $dato = $this->db->get("LIQUIDACION");

        return $dato->result_array;
    }
    function guardar_abogado($post){
        $this->db->set('NUM_LIQUIDACION',$post['post']['num_liquidacion']);
        $this->db->set('NITEMPRESA',$post['post']['nit']);
        $this->db->set('COD_FISCALIZACION',$post['post']['cod_fis']);
        $this->db->set('FECHA_GESTION', FECHA, false);
        $this->db->set('ABOGADO',$post['post']['abogado']);
        $this->db->set('COD_CPTO_FISCALIZACION',$post['post']['concepto']);
        $this->db->set('COD_REGIONAL',$post['post']['regional']);
        $this->db->set('COD_ESTADO',"21");
        $this->db->insert('RESOLUCION');
        trazar("558", "1376", $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
    }

    function sgva($id) {
        $this->db->select("SGVA_ESTDO_DE_CUENTA.ESTADO_NIT_EMPRESA as NIT_EMPRESA,"
                . "SGVA_ESTDO_DE_CUENTA.ID_ESTADO as NUM_LIQUIDACION,"
                . "AUTOSJURIDICOS.COD_FISCALIZACION,"
                . "SGVA_RESOLUCIONES.RESOLUCION_FECHA_EJECUTORIA,"
                . "SGVA_RESOLUCIONES.RESOLUCION_CUOTA,"
                . "SGVA_RESOLUCIONES.RESOLUCION_FECHA_RESOLUCION,"
                . "SGVA_ESTDO_DE_CUENTA.ESTADO_VALOR_FINAL,"
                . "SGVA_ESTDO_DE_CUENTA.ID_ESTADO");
        $this->db->join("AUTOSJURIDICOS", "AUTOSJURIDICOS.SGVA_NRO_CUENTA=SGVA_ESTDO_DE_CUENTA.ESTADO_NRO_ESTADO");
        $this->db->join("SGVA_RESOLUCIONES", "SGVA_RESOLUCIONES.ID_ESTADO=SGVA_ESTDO_DE_CUENTA.ID_ESTADO","LEFT");
        $this->db->where("AUTOSJURIDICOS.COD_FISCALIZACION", $id);
        $this->db->order_by("SGVA_RESOLUCIONES.RESOLUCION_FECHA_RESOLUCION","DESC");
        $dato = $this->db->get("SGVA_ESTDO_DE_CUENTA");
        if (!empty($dato->result_array[0])) {
            return $dato->result_array[0];
        }
    }

    function empresa($nit = NULL) {  //funcion para traer datos de la empresa
        $this->nit = (!empty($nit)) ? $nit : $this->nit;
        if (!empty($this->nit)) :
            $this->db->select("MUNICIPIO.NOMBREMUNICIPIO,REGIONAL.CEDULA_DIRECTOR,REGIONAL.CEDULA_COORDINADOR_RELACIONES,REGIONAL.EMAIL_REGIONAL,USER1.NOMBRES || ' ' || USER1.APELLIDOS COORDINADOR_REGIONAL,"
                    . "EMPRESA.CODEMPRESA,EMPRESA.CUOTA_APRENDIZ,EMPRESA.NOMBRE_EMPRESA,EMPRESA.RAZON_SOCIAL,EMPRESA.COD_MUNICIPIO, "
                    . "EMPRESA.REPRESENTANTE_LEGAL,USER2.NOMBRES || ' ' || USER2.APELLIDOS DIRECTOR_REGIONAL,REGIONAL.CEDULA_DIRECTOR,REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL",false);
            $this->db->join("REGIONAL", "EMPRESA.COD_REGIONAL=REGIONAL.COD_REGIONAL");
            $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO", "LEFT");
            $this->db->join("USUARIOS USER1", 'REGIONAL.CEDULA_COORDINADOR_RELACIONES=USER1.IDUSUARIO',false);
            $this->db->join("USUARIOS USER2", 'REGIONAL.CEDULA_DIRECTOR=USER2.IDUSUARIO',false);
            $this->db->where("EMPRESA.CODEMPRESA", $this->nit);
            $dato = $this->db->get("EMPRESA");
            $this->set_cordinador($dato->result_array[0]['CEDULA_COORDINADOR_RELACIONES']);
            return $dato->result_array[0];
        endif;
    }

    function municipio() { //funcion para optener el municipio de la empresa
        if (!empty($this->cod_municipio)) :
            $this->db->select("NOMBREMUNICIPIO");
            $this->db->where("CODMUNICIPIO", $this->cod_municipio);
            $dato = $this->db->get("MUNICIPIO");
            if (!empty($dato->result_array[0])):
                return $dato->result_array[0];
            endif;
        endif;
    }

    function cordinador($id = NULL) { // funcion para obtener el coordinador de la regional
        $this->db->select("REGIONAL.COD_REGIONAL,USER1.IDUSUARIO CEDULA_COORDINADOR_RELACIONES, CEDULA_DIRECTOR,
USER1.NOMBRES || ' ' || USER1.APELLIDOS COORDINADOR_REGIONAL ",false);
        $this->db->join("USUARIOS USER1", 'REGIONAL.CEDULA_COORDINADOR_RELACIONES=USER1.IDUSUARIO',false);
        $this->db->where("REGIONAL.COD_REGIONAL", REGIONAL_ALTUAL);
        $dato = $this->db->get("REGIONAL");
        return $dato->result_array;
    }

    function info_liquidacion() {//funcion para obtener los datos de la liquidacion por empresa
        $this->db->select("SALDO_DEUDA,LIQUIDACION.FECHA_LIQUIDACION,NOMBRE_CONCEPTO as NOMBRECONCEPTO,NUM_LIQUIDACION,COD_CONCEPTO,NITEMPRESA,FECHA_INICIO,FECHA_FIN,FECHA_LIQUIDACION,TOTAL_LIQUIDADO,TOTAL_INTERESES,COD_TASA_INTERES,COD_FISCALIZACION");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->where("NUM_LIQUIDACION", $this->NUM_LIQUIDACION);
        $this->db->where("NITEMPRESA", $this->nit);
        $datos = $this->db->get('LIQUIDACION');
        if (!empty($datos->result_array)):
            return $datos->result_array[0];
        else : return NULL;
        endif;
    }

    function resolucion() {
        $this->db->select("NUMERO_RESOLUCION,COD_TIPORESOLUCION,NOMBRE_ARCHIVO_RESOLUCION,FECHA_CREACION,COD_REGIONAL,NOMBRE_EMPLEADOR,"
                . "NITEMPRESA,NUM_LIQUIDACION,FECHA_LIQUIDACION,PERIODO_INICIAL,PERIODO_FINAL,VALOR_TOTAL,VALOR_LETRAS,ELABORO,REVISO,ABOGADO,"
                . "COORDINADOR,DIRECTOR_REGIONAL,FECHA_ACTUAL,NUM_TRASLADO_ALEGATO,NUMERO_CITACION,OBSERVACIONES,COD_PLANTILLA,"
                . "COD_ESTADO,APROBADA,FECHA_APROBACION");
        $this->db->where("CODMUNICIPIO", $this->cod_municipio);
        return $dato = $this->db->get("MUNICIPIO");
    }

    function abogados($id) {
        $this->db->select("USUARIOS.IDUSUARIO,USUARIOS.APELLIDOS,USUARIOS.NOMBRES");
        $this->db->join("USUARIOS_GRUPOS", "USUARIOS.IDUSUARIO=USUARIOS_GRUPOS.IDUSUARIO");
        $this->db->join("GRUPOS", "USUARIOS_GRUPOS.IDGRUPO=GRUPOS.IDGRUPO");
        $this->db->where("GRUPOS.IDGRUPO", "44");
        $this->db->where("USUARIOS.COD_REGIONAL", $id);
        $dato = $this->db->get("USUARIOS");
        return $dato->result_array;
    }

    function reviso() {
        $this->db->select("APELLIDOS,NOMBRES");
        $dato = $this->db->get("USUARIOS");
        return $dato->result_array;
    }

    function guardar_resolucion($informacion, $id_user) {

    }

    function resolucion_guardar_contrato($informacion, $id_user) {//guarda la informacion de la resolucion del contrato de aprendizaje
        $this->db->set('COD_REGIONAL', $informacion['id_regional']);
        $this->db->set('NITEMPRESA', $informacion['nit']);
        $this->db->set('CUOTA', $informacion['cuota']);
//        $this->db->set('NUM_LIQUIDACION', $informacion['num_liquidacion']);

        $this->db->set('FECHA_CREACION', FECHA, false);
        $this->db->set('FECHA_ACTUAL', FECHA, false);
        if (!empty($informacion['fechaExpedicion']))
            $this->db->set('FECHA_EXPEDICION_CUOTA', "TO_DATE('" . $informacion['fechaExpedicion'] . "', 'DD/MM/RR')", false);
        $this->db->set('NUM_RESOLUCION_CUOTA', $informacion['nResolucionCuota']);
        if (!empty($informacion['fecha_ejecutoria']))
            $this->db->set('FECHA_EJECUTORIA_CUOTA', "TO_DATE('" . $informacion['fecha_ejecutoria'] . "', 'DD/MM/RR')", false);
//        $this->db->set('TEXTO_RESOLUCION', $texto_informacion);
//        $this->db->set('REVISO', $informacion['reviso']);
        $this->db->set('ELABORO', ID_USER);
        $this->db->set('ABOGADO', ID_USER);
        $this->db->set('COORDINADOR', str_replace(" ", "", $informacion['reviso']));
        $this->db->set('NOMBRE_EMPLEADOR', $informacion['rozon_social']);
        $this->db->set('DECISION_RESO_CONTRATO', $informacion['decision']);
        $this->db->set('VALOR_LETRAS', $informacion['vletras']);
        $this->db->set('VALOR_TOTAL', $informacion['vresolucion']);
        $this->db->set('NUM_LIQUIDACION', $informacion['num_proceso']);
        $this->db->set('DIRECTOR_REGIONAL', str_replace(" ", "", $informacion['id_director_regional']));
        $this->db->set('COD_FISCALIZACION', $informacion['cod_fis']);
        $this->db->set('COD_CPTO_FISCALIZACION', '3');
        $this->db->set("COD_ESTADO", '23');
        $this->db->insert('RESOLUCION');
//        $id_resolucion=$this->db->last_id();
//        gestion_resolucion($id_resolucion,$id_user->IDUSUARIO);

        $this->db->select('COD_RESOLUCION');
        $this->db->where("NITEMPRESA", $informacion['nit']);
        $this->db->where("ELABORO", $id_user->IDUSUARIO);
//        $this->db->where("TEXTO_RESOLUCION", $texto_informacion);
        $dato = $this->db->get("RESOLUCION");
        $id_resolucion = $dato->result_array[0];
        $this->gestion_resolucion($id_resolucion['COD_RESOLUCION'], $id_user->IDUSUARIO);
        trazar("21", "30", $informacion['cod_fis'], $informacion['nit'], $cambiarGestionActual = 'S', $comentarios = "");
        return $id_resolucion['COD_RESOLUCION'];
    }

    function resolucion_guardar_aportes_fic($informacion, $id_user) { //guarda la resolucion de aportes y fic
        if (!empty($informacion['fechacreacion']))
            $this->db->set('FECHA_CREACION', "TO_DATE('" . $informacion['fechacreacion'] . "', 'DD/MM/RR')", false);
        if (!empty($informacion['fecha_actual']))
            $this->db->set('FECHA_ACTUAL', "TO_DATE('" . $informacion['fecha_actual'] . "', 'DD/MM/RR')", false);
        if (!empty($informacion['fecha_liquidacion']))
            $this->db->set('FECHA_LIQUIDACION', "TO_DATE('" . $informacion['fecha_liquidacion'] . "', 'DD/MM/RR')", false);

        $this->db->set('VALOR_TOTAL', $informacion['valor_pesos2']);
        $this->db->set('COD_REGIONAL', $informacion['id_regional']);
        $this->db->set('NOMBRE_EMPLEADOR', $informacion['nombreempleador']);
        $this->db->set('NITEMPRESA', $informacion['nit']);
        $this->db->set('NUM_LIQUIDACION', $informacion['liquidacion']);
        $this->db->set('PERIODO_INICIAL', $informacion['periodo_desde']);
        $this->db->set('PERIODO_FINAL', $informacion['periodo_hasta']);
        $this->db->set('VALOR_LETRAS', $informacion['valor_letras']);
        $this->db->set('ELABORO', $id_user->IDUSUARIO);
//        $this->db->set('REVISO', $informacion['reviso']);
        $this->db->set('ABOGADO', $id_user->IDUSUARIO);
        $this->db->set('COORDINADOR', $informacion['id_coordinador']);
        $this->db->set('DIRECTOR_REGIONAL', $informacion['id_director_regional']);
        $this->db->set('COD_CPTO_FISCALIZACION', $informacion['id_concepto']);
        $this->db->set("COD_ESTADO", '23');
        $this->db->set('COD_FISCALIZACION', $informacion['cod_fis']);
        if($informacion['id_resolucion']==""){
        $this->db->insert('RESOLUCION');
        }else{
            $this->db->where('COD_RESOLUCION',$informacion['id_resolucion']);
            $this->db->update('RESOLUCION');
        }
        //obtener el ultimo id de la tabla resolucion con los mismos datos ingresados
        $this->db->select('COD_RESOLUCION');
        $this->db->where("NITEMPRESA", $informacion['nit']);
        $this->db->where("ELABORO", $id_user->IDUSUARIO);
        $this->db->where("VALOR_TOTAL", $informacion['valor_pesos2']);
        $this->db->where("NOMBRE_EMPLEADOR", $informacion['nombreempleador']);
        $dato = $this->db->get("RESOLUCION");
        $id_resolucion = $dato->result_array[0];
        $this->gestion_resolucion($id_resolucion['COD_RESOLUCION'], $id_user->IDUSUARIO);
        trazar("21", "30", $informacion['cod_fis'], $informacion['nit'], $cambiarGestionActual = 'S', $comentarios = "");



        return $id_resolucion['COD_RESOLUCION'];
    }

    function gestion_resolucion($id_resolu, $id_user) {
        $this->db->set('COD_RESOLUCION', $id_resolu);
        $this->db->set('RESPONSABLE', $id_user);
        $this->db->set("COMENTARIOS", "CREACION RESOLUCION");
        $this->db->insert('GESTIONRESOLUCION');
    }
    function plantilla($id){
        $this->db->select('ARCHIVO_PLANTILLA');
        $this->db->where('CODPLANTILLA', $id);
        $dato = $this->db->get('PLANTILLA');
        return $datos5 = $dato->result_array['0']['ARCHIVO_PLANTILLA'];
    }
    function detalle_aportes($id_liquidacion){
        $this->db->select("TO_CHAR(VISITA.FECHA_DOCUMENTO, 'DD') AS DIA_V, Mes_Letras(FECHA_DOCUMENTO) AS MES_V, TO_CHAR(VISITA.FECHA_DOCUMENTO, 'YYYY') AS ANO_V,
            U.NOMBRES || U.APELLIDOS AS FISCALIZADOR, F.PERIODO_INICIAL, F.PERIODO_FINAL,
            LAP_D.ANO,LAP_D.VALORSUELDOS,LAP_D.VALORSOBRESUELDOS,LAP_D.SALARIOINTEGRAL,LAP_D.SALARIOESPECIE,LAP_D.SUPERNUMERARIOS,
            LAP_D.JORNALES,LAP_D.AUXILIOTRANSPORTE,LAP_D.HORASEXTRAS,LAP_D.DOMINICALES_FESTIVOS,LAP_D.RECARGONOCTURNO,LAP_D.VIATICOS,
            LAP_D.BONIFICACIONES,LAP_D.COMISIONES,LAP_D.POR_SOBREVENTAS,LAP_D.VACACIONES,LAP_D.TRAB_DOMICILIO,LAP_D.PRIMA_TEC_SALARIAL,
            LAP_D.AUXILIO_ALIMENTACION,LAP_D.PRIMA_SERVICIO,LAP_D.PRIMA_LOCALIZACION,LAP_D.PRIMA_VIVIENDA,LAP_D.GAST_REPRESENTACION,
            LAP_D.PRIMA_ANTIGUEDAD,LAP_D.PRIMA_EXTRALEGALES,LAP_D.PRIMA_VACACIONES,LAP_D.PRIMA_NAVIDAD,LAP_D.CONTRATOS_AGRICOLAS,
            LAP_D.REMU_SOCIOS_INDUSTRIALES,LAP_D.HORA_CATEDRA,LAP_D.OTROS_PAGOS", FALSE);
        $this->db->join('LIQ_APORTESPARAFISCALES LAP','LAP.CODLIQUIDACIONAPORTES_P=L.NUM_LIQUIDACION');
        $this->db->join('LIQ_APORTESPARAFISCALES_DET LAP_D','LAP_D.CODLIQUIDACIONAPORTES_P=LAP.CODLIQUIDACIONAPORTES_P');
        $this->db->join('FISCALIZACION F','F.COD_FISCALIZACION = L.COD_FISCALIZACION');
        $this->db->join('ASIGNACIONFISCALIZACION AF','AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC');
        $this->db->join('USUARIOS U','U.IDUSUARIO = AF.ASIGNADO_A');
         $this->db->join('GESTIONCOBRO GC','GC.COD_FISCALIZACION_EMPRESA = F.COD_FISCALIZACION');
         $this->db->join('INFORMEVISITA VISITA','VISITA.COD_GESTION_COBRO = GC.COD_GESTION_COBRO');
        $this->db->where('LAP_D.CODLIQUIDACIONAPORTES_P', $id_liquidacion);
        $dato = $this->db->get('LIQUIDACION L');
        return $datos5 = $dato->result_array;
    }
    function detalle_fic($id_liquidacion){
        $this->db->select('L.NUM_LIQUIDACION,LFP.VLR_CONTRATO_TODOCOSTO,LFP.VLR_CONTRATO_MANO_OBRA,
            LFP.PAGOS_FIC_DESCONTAR,LFP.COD_LIQ_PRESUNTIVA,LFN.ANO,LFN.NRO_TRABAJADORES,LFN.TOTAL_ANO,LFN.MESCOBRO');
        $this->db->join('LIQUIDACION_FIC LF','LF.CODLIQUIDACIONFIC=L.NUM_LIQUIDACION');
        $this->db->join('LIQ_FIC_NORMATIVA LFN','LFN.CODLIQUIDACIONFIC=L.NUM_LIQUIDACION','LEFT');
        $this->db->join('LIQ_FIC_PRESUNTIVA LFP','LFP.CODLIQUIDACIONFIC=L.NUM_LIQUIDACION','LEFT');
        $this->db->where('L.NUM_LIQUIDACION', $id_liquidacion);
        $dato = $this->db->get('LIQUIDACION L');
        return $datos5 = $dato->result_array;
    }

}

?>
