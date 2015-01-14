<?php

// Responsable: Leonardo Molina
class Ejecutoriaactoadmin_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getDatax($reg, $search, $lenght = 10, $resticcion, $state) {
        $this->db->select('RS.COD_RESOLUCION');
        $this->db->from('RESOLUCION RS');
        $this->db->join('GESTIONCOBRO GC', 'GC.COD_GESTION_COBRO = RS.COD_GESTION_COBRO', 'LEFT');
        $this->db->where('GC.COD_TIPO_RESPUESTA', '86');
        $this->db->where($resticcion, ID_USER);
//        $this->db->where('GC.COD_TIPO_RESPUESTA','159');
        $query1 = $this->db->get();
        $multas = $query1->result();
        $i = 0;
        foreach ($multas as $row) {
            $nmult[$i] = $row->COD_RESOLUCION;
            $i++;
        }

        $this->db->select('NVL(RV.ESTADO_REVOCATORIA ,NULL) as REVOCATORIA2', false);
        $this->db->select(
                'RS.COD_RESOLUCION,RS.NUMERO_RESOLUCION,RS.REVISO,RS.DETALLE_COBRO_COACTIVO,RS.NITEMPRESA,RS.FECHA_CREACION,EM.NOMBRE_EMPRESA,'
                . 'TG.TIPOGESTION,CF.NOMBRE_CONCEPTO,GC.COD_TIPO_RESPUESTA,RS.COD_FISCALIZACION,'
                . 'RV.COD_REVOCATORIA,EJ.COD_EJECUTORIA  as EJECUTORIA,'
                . 'RV.COD_REVOCATORIA as REVOCATORIA, RV.RESPUESTA_REVOCATORIA as RES_REVOCATORIA,'
                . 'CF.COD_CPTO_FISCALIZACION,RS.COD_ESTADO,RS.NUMERO_CITACION,CT.FECHA_ENVIO_CITACION,'
                . 'RV.DOC_RESPUESTA_FIRMADO', true);
        $this->db->from('RESOLUCION RS');
        $this->db->join('EMPRESA EM', 'EM.CODEMPRESA = RS.NITEMPRESA', ' inner');
        $this->db->join("LIQUIDACION", "LIQUIDACION.COD_FISCALIZACION=RS.COD_FISCALIZACION AND LIQUIDACION.SALDO_DEUDA>0 AND LIQUIDACION.BLOQUEADA='0' AND LIQUIDACION.EN_FIRME='S'");
        $this->db->join('TIPOGESTION TG', 'TG.COD_GESTION = RS.COD_ESTADO', ' inner');
        $this->db->join('CONCEPTOSFISCALIZACION CF', 'CF.COD_CPTO_FISCALIZACION = RS.COD_CPTO_FISCALIZACION', ' inner');
        $this->db->join('GESTIONCOBRO GC', 'GC.COD_GESTION_COBRO = RS.COD_GESTION_COBRO', 'LEFT');
        $this->db->join('EJECUTORIA EJ', 'EJ.NUM_DOCUMENTO = RS.NUMERO_RESOLUCION AND EJ.ESTADO=0', 'LEFT');
        $this->db->join('CITACION CT', 'RS.NUMERO_CITACION = CT.NUM_CITACION', 'LEFT');
        $this->db->join('REVOCATORIA RV', 'RS.COD_RESOLUCION = RV.COD_RESOLUCION', 'LEFT');
        $this->db->where_in('RS.COD_ESTADO', $state);
        $this->db->where('CF.COD_CPTO_FISCALIZACION <>', '5');
        $this->db->where($resticcion, ID_USER);
        //$this->db->where_not_in('RS.COD_RESOLUCION',$nmult);
        $this->db->order_by('RS.FECHA_CREACION', 'desc');
        if ($search) {
            $array = array(
                'RS.NUMERO_RESOLUCION' => $search,
                'RS.NITEMPRESA' => $search,
                'EM.NOMBRE_EMPRESA' => $search,
                'TG.TIPOGESTION' => $search,
                'RS.FECHA_CREACION' => $search
            );
            $this->db->or_like($array);
        }
        $this->db->limit($lenght + 1, $reg);
        $query = $this->db->get();
        return $query->result();
    }
    function devolucion_coactivo(){
        $this->db->select('LIQUIDACION.NITEMPRESA,RESOLUCION.NUMERO_RESOLUCION,RECEPCIONTITULOS.COD_RECEPCIONTITULO,EMPRESA.NOMBRE_EMPRESA,RESPUESTAGESTION.NOMBRE_GESTION,
RECEPCIONTITULOS.COD_TIPORESPUESTA');
        $this->db->join('RESOLUCION','RESOLUCION.COD_FISCALIZACION=RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA');
        $this->db->join('LIQUIDACION','LIQUIDACION.COD_FISCALIZACION=RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA');
        $this->db->join('EMPRESA','LIQUIDACION.NITEMPRESA=EMPRESA.CODEMPRESA');
        $this->db->join('RESPUESTAGESTION','RESPUESTAGESTION.COD_RESPUESTA=RECEPCIONTITULOS.COD_TIPORESPUESTA');
        $this->db->where('RESOLUCION.ABOGADO',ID_USER);
        $this->db->where('RECEPCIONTITULOS.COD_TIPORESPUESTA',1114);
        $datos =$this->db->get('RECEPCIONTITULOS');
        return $datos = $datos->result_array;
    }
    function enviar_correcciones($post){
        $this->db->set('OBSERVACIONES', $post['informacion']);
        $this->db->where('COD_RECEPCIONTITULO',$post['id'] );
        $this->db->set('COD_TIPORESPUESTA',1112 );
        $this->db->update('RECEPCIONTITULOS');
    }
    function titulos($id){
        $this->db->select('NOMBRE_DOCUMENTO,RUTA_ARCHIVO');
        $this->db->where('COD_RECEPCIONTITULO',$id);
        $datos =$this->db->get('TITULOS');
        return $datos = $datos->result_array;
    }
    function observaciones($id){
        $this->db->select('OBSERVACIONES');
        $this->db->where('COD_RECEPCIONTITULO',$id);
        $datos =$this->db->get('RECEPCIONTITULOS');
        return $datos = $datos->result_array;
    }

    function totalData($search, $resticcion, $state) {
        $this->db->select('RS.COD_RESOLUCION');
        $this->db->from('RESOLUCION RS');
        $this->db->join('GESTIONCOBRO GC', 'GC.COD_GESTION_COBRO = RS.COD_GESTION_COBRO', 'LEFT');
        $this->db->where('GC.COD_TIPO_RESPUESTA', '159');
        $this->db->where($resticcion, ID_USER);
        $query1 = $this->db->get();
        $multas = $query1->result();
        $i = 0;
        foreach ($multas as $row) {
            $nmult[$i] = $row->COD_RESOLUCION;
            $i++;
        }
        $this->db->select('RS.COD_RESOLUCION,RS.NUMERO_RESOLUCION,RS.NITEMPRESA,RS.FECHA_CREACION,EM.NOMBRE_EMPRESA,'
                . 'TG.TIPOGESTION,CF.NOMBRE_CONCEPTO,GC.COD_TIPO_RESPUESTA,RS.COD_FISCALIZACION,'
                . 'RV.COD_REVOCATORIA,RV.ESTADO_REVOCATORIA as REVOCATORIA2, RV.COD_REVOCATORIA as REVOCATORIA, '
                . 'RV.RESPUESTA_REVOCATORIA as RES_REVOCATORIA,'
                . 'RV.DOC_RESPUESTA_FIRMADO', true);
        $this->db->from('RESOLUCION RS');
        $this->db->join('EMPRESA EM', 'EM.CODEMPRESA = RS.NITEMPRESA', ' inner');
        $this->db->join("LIQUIDACION", "LIQUIDACION.COD_FISCALIZACION=RS.COD_FISCALIZACION AND LIQUIDACION.SALDO_DEUDA >0 AND LIQUIDACION.BLOQUEADA='0' AND LIQUIDACION.EN_FIRME='S'");
        $this->db->join('TIPOGESTION TG', 'TG.COD_GESTION = RS.COD_ESTADO', ' inner');
        $this->db->join('CONCEPTOSFISCALIZACION CF', 'CF.COD_CPTO_FISCALIZACION = RS.COD_CPTO_FISCALIZACION', ' inner');
        $this->db->join('EJECUTORIA EJ', 'EJ.NUM_DOCUMENTO = RS.NUMERO_RESOLUCION AND EJ.ESTADO=0', 'LEFT');
        $this->db->join('GESTIONCOBRO GC', 'GC.COD_GESTION_COBRO = RS.COD_GESTION_COBRO', 'LEFT');
        $this->db->join('REVOCATORIA RV', 'RS.COD_RESOLUCION = RV.COD_RESOLUCION', 'LEFT');
        $this->db->where_in('RS.COD_ESTADO', $state);
        $this->db->where($resticcion, ID_USER);
        $this->db->where('CF.COD_CPTO_FISCALIZACION <>', '5');
        if (count($multas) > 0)
            $this->db->where_not_in('RS.COD_RESOLUCION', $nmult);
        if ($search) {
            $array = array(
                'RS.NUMERO_RESOLUCION' => $search,
                'RS.NITEMPRESA' => $search,
                'EM.NOMBRE_EMPRESA' => $search,
                'TG.TIPOGESTION' => $search,
                'RS.FECHA_CREACION' => $search
            );
            $this->db->or_like($array);
        }

        $query = $this->db->get();
        if ($query->num_rows() == 0)
            return '0';
        $info = $query->num_rows();
//        echo "<pre>";
//        print_r($info);
//        echo "</pre>";
        return $info;
    }

    ////// funciones para traer datos 

    function getResolucion($cod) {
        $this->db->select('RS.NUMERO_RESOLUCION,RS.COD_ESTADO,RS.COD_CPTO_FISCALIZACION,RS.FECHA_ACTUAL,CT.FECHA_ENVIO_CITACION,'
                . 'RS.VALOR_LETRAS,LIQUIDACION.SALDO_DEUDA VALOR_TOTAL,RS.COD_FISCALIZACION,TG.TIPOGESTION,TG.COD_GESTION,CF.NOMBRE_CONCEPTO,'
                . 'TG2.TIPOGESTION as CITACION,TG2.COD_GESTION AS CGESTION,RS.NUM_RECURSO,EM.*');
        $this->db->from('RESOLUCION RS');
        $this->db->join('EMPRESA EM', 'EM.CODEMPRESA = RS.NITEMPRESA', ' inner');
        $this->db->join('TIPOGESTION TG', 'TG.COD_GESTION = RS.COD_ESTADO', ' inner');
        $this->db->join('CONCEPTOSFISCALIZACION CF', 'CF.COD_CPTO_FISCALIZACION = RS.COD_CPTO_FISCALIZACION', ' inner');
        $this->db->join('CITACION CT', 'CT.NUM_CITACION = RS.NUMERO_CITACION', 'inner');
        $this->db->join('TIPOGESTION TG2', 'TG2.COD_GESTION = CT.TIPOGESTION', 'inner');
        $this->db->join('LIQUIDACION', 'LIQUIDACION.COD_FISCALIZACION=RS.COD_FISCALIZACION', 'inner');
        $this->db->where('RS.NUMERO_RESOLUCION', $cod);
        $this->db->where('LIQUIDACION.BLOQUEADA', 0);
        $query = $this->db->get();
        return $query->row();
    }
    function num_fisca($id) {
        $this->db->select("count(RESOLUCION.COD_FISCALIZACION) CANTIDAD");
        $this->db->join('RESOLUCION','RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION');
        $this->db->where('RESOLUCION.NUMERO_RESOLUCION',$id);
        $this->db->group_by('RESOLUCION.COD_FISCALIZACION');
        $dato=$this->db->get('LIQUIDACION');
        return $datos = $dato->result_array;
    }

    function getResolucionMulta($cod) {
        $this->db->select('RS.NUMERO_RESOLUCION,RS.COD_CPTO_FISCALIZACION,RS.FECHA_ACTUAL,RS.VALOR_LETRAS,RS.VALOR_TOTAL,RS.COD_FISCALIZACION,EM.*');
        $this->db->from('RESOLUCION RS');
        $this->db->join('EMPRESA EM', 'EM.CODEMPRESA = RS.NITEMPRESA', ' inner');
        $this->db->join('CONCEPTOSFISCALIZACION CF', 'CF.COD_CPTO_FISCALIZACION = RS.COD_CPTO_FISCALIZACION', ' inner');
        $this->db->where('RS.NUMERO_RESOLUCION', $cod);
        $this->db->where('RS.COD_CPTO_FISCALIZACION', 5); // 5 = Multas
        $query = $this->db->get();
        return $query->row();
    }

    function getResolucionGestion($cod) {
        $this->db->select('RG.COD_RESPUESTA,RG.NOMBRE_GESTION');
        $this->db->from('RESOLUCION RS');
        $this->db->join('GESTIONCOBRO GC', 'GC.COD_GESTION_COBRO = RS.COD_GESTION_COBRO');
        $this->db->join('RESPUESTAGESTION RG', 'RG.COD_RESPUESTA = GC.COD_TIPO_RESPUESTA');
        $this->db->where('RS.NUMERO_RESOLUCION', $cod);
        $query = $this->db->get();
        return $query->row();
    }

    function getResolucionUnica($cod) {
        $this->db->select('RS.COD_RESOLUCION');
        $this->db->from('RESOLUCION RS');
        $this->db->where('RS.NUMERO_RESOLUCION', $cod);
        $query = $this->db->get();
        return $query->row()->COD_RESOLUCION;
    }

    function getEjecutoria($cod) {
        $this->db->select('*');
        $this->db->from('EJECUTORIA');
        $this->db->where('COD_FISCALIZACION', $cod);
        $this->db->where('COD_GESTION_COBRO >=', 127);
        $this->db->where('COD_GESTION_COBRO <=', 133);
        $this->db->order_by('COD_EJECUTORIA', 'desc');
        $query = $this->db->get();
        return $query->row();
    }

    function getRevocatoria($cod) {
        $this->db->select('RV.COD_REVOCATORIA,RV.DOC_REVOCATORIA,RV.DOC_RESPUESTA,RV.DOC_RESPUESTA_FIRMADO,RV.RESPUESTA_REVOCATORIA,'
                . 'RS.NUMERO_RESOLUCION,RS.COD_FISCALIZACION,RS.NITEMPRESA,RS.COD_CPTO_FISCALIZACION,RS.COD_RESOLUCION');
        $this->db->from('REVOCATORIA RV');
        $this->db->join('RESOLUCION RS', 'RS.COD_RESOLUCION = RV.COD_RESOLUCION');
        $this->db->where('RS.NUMERO_RESOLUCION', $cod);
        $query = $this->db->get();
        return $query->row();
    }

    function addEjecutoria($data, $cod_estado, $cod_fiscalizacion) {
        if ($cod_estado == 416) {
            $this->db->set('ESTADO', '1');
            $this->db->where('COD_FISCALIZACION', $cod_fiscalizacion);
            $query = $this->db->update('EJECUTORIA');
        }
        $this->db->set('FECHA_EJECUTORIA', 'sysdate', false);
        $query = $this->db->insert('EJECUTORIA', $data);
        $this->commit();
        return ($query);
    }

    function addRevocatoria($data) {
        $this->db->set('FECHA_REVOCATORIA', 'SYSDATE', false);
        $this->db->set('FECHA_GESTION', 'SYSDATE', FALSE);
        $query = $this->db->insert('REVOCATORIA', $data);
        $this->commit();
        return ($query);
    }

    function updateRevocatoria($cod, $data) {
        $this->db->set('FECHA_GESTION', 'SYSDATE', FALSE);
        $this->db->where('COD_REVOCATORIA', $cod);
        $query = $this->db->update('REVOCATORIA', $data);
        $this->commit();
        return ($query);
    }
    function updateliquidacion2($fisc) {
        $this->db->set('FECHA_EJECUTORIA', 'SYSDATE', FALSE);
        $this->db->where('COD_FISCALIZACION', $fisc);
        $query = $this->db->update('LIQUIDACION');
        $this->commit();
        return ($query);
    }

    function updateEstadoResolucion($res, $estado) {
        $data = array('COD_ESTADO' => $estado);
        $this->db->where('NUMERO_RESOLUCION', $res);
        $query = $this->db->update('RESOLUCION', $data);
        $this->commit();
        return ($query);
    }

    function updateEstadoResolucion2($res, $estado) {
        $data = array('COD_ESTADO' => $estado);
        $this->db->where('COD_RESOLUCION', $res);
        $query = $this->db->update('RESOLUCION', $data);
        $this->commit();
        return ($query);
    }

    function updateEstadoResolucion3($res, $data) {
        $this->db->where('COD_RESOLUCION', $res);
        $query = $this->db->update('RESOLUCION', $data);
        $this->commit();
        return ($query);
    }

    function updateGestionResolucion($res, $gestion) {
        $data = array('COD_GESTION_COBRO' => $gestion);
        $this->db->set('FECHA_GESTION', 'SYSDATE', FALSE);
        $this->db->where('NUMERO_RESOLUCION', $res);
        $query = $this->db->update('RESOLUCION', $data);
        $this->commit();
        return ($query);
    }

    function updateLiquidacionEjecutoria($cod_fisc, $cod_prc) {
        $this->db->set('COD_TIPOPROCESO', $cod_prc);
        $this->db->set('FECHA_EJECUTORIA', 'SYSDATE', FALSE);
        $this->db->where('COD_FISCALIZACION', $cod_fisc);
        $query = $this->db->update('LIQUIDACION');
        $this->commit();
        return ($query);
    }

    function pre_revocatoria($post) {
        $this->db->select('DOC_RESPUESTA');
        $this->db->where('COD_REVOCATORIA', $post['id']);
        $dato = $this->db->get('REVOCATORIA');
        return $dato->result_array;
    }

    function devolucion($post) {
        $this->db->set('COD_REVOCATORIA', $post['id']);
        $this->db->set('RESPONSABLE', ID_USER);
        $this->db->set("COMENTARIOS", $post['informacion']);
        $this->db->insert('GESTIONRESOLUCION');
        

        $this->db->set("ESTADO_REVOCATORIA", 2);
        $this->db->where('COD_REVOCATORIA', $post['id']);
        $this->db->update('REVOCATORIA');

        $this->db->set("COD_ESTADO", 399);
        $this->db->where('COD_RESOLUCION', $post['id_resolucion']);
        $this->db->update('RESOLUCION');
        $this->commit();
    }

    function aprobar($post) {
        $this->db->set('COD_REVOCATORIA', $post['id']);
        $this->db->set('RESPONSABLE', ID_USER);
        $this->db->set("COMENTARIOS", "REVOCATORIA APROBADA");
        $this->db->insert('GESTIONRESOLUCION');

        $this->db->set("ESTADO_REVOCATORIA", 1);
        $this->db->where('COD_REVOCATORIA', $post['id']);
        $this->db->update('REVOCATORIA');

        $this->db->set("COD_ESTADO", 75);
        $this->db->where('COD_RESOLUCION', $post['id_resolucion']);
        $this->db->update('RESOLUCION');
        $this->commit();
    }

    function citacion($post) {
        $this->db->set("COD_ESTADO", 410);
        $this->db->where('COD_RESOLUCION', $post['id_resolucion']);
        $this->db->update('RESOLUCION');
        $this->commit();
    }

    function pre_aprobar($post, $nom_doc) {
        $this->db->set('COD_REVOCATORIA', $post['id']);
        $this->db->set('RESPONSABLE', ID_USER);
        $this->db->set("COMENTARIOS", "ENVIO REVOCATORIA CON CORRECCIONES");
        $this->db->insert('GESTIONRESOLUCION');

        $this->db->set("ESTADO_REVOCATORIA", 0);
        $this->db->set("DOC_RESPUESTA", $nom_doc);
        $this->db->where('COD_REVOCATORIA', $post['id']);
        $this->db->update('REVOCATORIA');

        $this->db->set("COD_ESTADO", 398);
        $this->db->where('COD_RESOLUCION', $post['id_resolucion']);
        $this->db->update('RESOLUCION');
        $this->commit();
    }

    function comentario($id) {
        //obtener lo valores de los comentarios de la resolucion guardada
        $this->db->select('COMENTARIOS,FECHA_MODIFICACION');
        $this->db->where("COD_REVOCATORIA", $id);
        $this->db->order_by("FECHA_MODIFICACION", "DESC");
        $dato = $this->db->get("GESTIONRESOLUCION");
        $datos = $dato->result_array;
        $comentarios = "";
        for ($i = 0; $i < count($datos); $i++) {
            $comentarios.=$datos[$i]['COMENTARIOS'] . "<br> Fecha: " . $datos[$i]['FECHA_MODIFICACION'] . " <hr> ";
        }
        return $comentarios;
    }

    // paso la informacion de revocatoria a acuerdo de pago o juridico
    function envio($post) {
        $this->db->set("COD_ESTADO", $post['gestion']);
        $this->db->where('COD_RESOLUCION', $post['id']);
        $this->db->update('RESOLUCION');
        $this->commit();
    }
    function multas($multa){
        $this->db->set('NUMERO_COMUNICACION','');
        $this->db->where('COD_MULTAMINISTERIO',$multa);
        $this->db->update('MULTASMINISTERIO');
    }

    // generacion del documento que se genera para el paso a jutidico
    function guardar_paso_juridico($post, $nombreDoc) {
        $this->db->set("COD_REVOCATORIA", $post['cod_revocatoria']);
        $this->db->set('ARCHIVO', $nombreDoc);
        $this->db->set('IDUSUARIO', ID_USER);
        $this->db->set('FECHA_CREACION', 'SYSDATE', false);
        $this->db->insert('REMISION_JURIDICA');

        $this->db->set("COD_ESTADO", 418);
        $this->db->where('COD_RESOLUCION', $post['id']);
        $this->db->update('RESOLUCION');
        $this->commit();
    }

    function subir_archivo($post, $file, $user, $codgest) {

        $this->db->set("COD_ESTADO", 419);
        $this->db->set("ABOGADO", ID_USER);
        $this->db->set("DOCUMENTO_COBRO_COACTIVO", $file['upload_data']['file_name']);
        $this->db->where('COD_RESOLUCION', $post['id']);
        $this->db->update('RESOLUCION');


        $this->db->trans_begin();

        $this->db->set("COD_FISCALIZACION_EMPRESA", $post['cod_fis']);
        $this->db->set('NIT_EMPRESA', $post['nit']);
        $this->db->set('FECHA_RECEPCION', 'SYSDATE', false);
        $this->db->set('FECHA_CONSULTAONBASE', 'SYSDATE', false);
        $this->db->set('COD_TIPORESPUESTA', 1112);
        $this->db->insert('RECEPCIONTITULOS');

        $query = $this->db->query("SELECT RecepcionTitu_cod_recepcio_SEQ.CURRVAL FROM dual");
        $row = $query->row_array();
        $id = $row['CURRVAL'];
        @$datos = base64_decode($post['documentos']);
        @$datos = explode("|||", $datos);
        @$rutas = explode("///", $datos[0]);
        @$inputs = explode("///", $datos[1]);
//        print_r($inputs);
        for ($i = 0; $i < count($rutas) - 1; $i++) {
            $this->db->set("COD_RECEPCIONTITULO", $id);
            $this->db->set('NOMBRE_DOCUMENTO', utf8_encode($inputs[$i]));
            $this->db->set('RUTA_ARCHIVO', utf8_encode($rutas[$i]));
            $this->db->set('FECHA_CARGA', 'SYSDATE', FALSE);
            $this->db->insert('TITULOS');
        }
        $this->commit();
    }
    
    function commit(){
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    } 

    function subir_archivo2($post, $file, $user) {

        $this->db->set('FECHA_GESTION', 'SYSDATE', FALSE);
        $this->db->set('DOC_GENERADO', $file['upload_data']['file_name']);
        $this->db->where('COD_REVOCATORIA', $post['cod_revocatoria']);
        $query = $this->db->update('REVOCATORIA');

        $this->db->set("COD_ESTADO", 425);
        $this->db->where('COD_RESOLUCION', $post['id']);
        $this->db->update('RESOLUCION');
        $this->commit();
    }

    function plantilla($id) {
        $this->db->select('ARCHIVO_PLANTILLA');
        $this->db->where('CODPLANTILLA', $id);
        $dato = $this->db->get('PLANTILLA');
        return $datos5 = $dato->result_array[0]['ARCHIVO_PLANTILLA'];
    }

    function guardar_llamadas($post) {
        $this->db->set("OBSERVACIONES", "CONCAT(OBSERVACIONES,'" . $post['informacion'] . "')", false);
        $this->db->where('COD_RESOLUCION', $post['id']);
        $query = $this->db->update('RESOLUCION');
        $this->commit();
    }

    function historico_llamadas($post) {
        $this->db->select("OBSERVACIONES");
        $this->db->where('COD_RESOLUCION', $post['id']);
        $query = $this->db->get('RESOLUCION');
        $query = $query->result_array[0]['OBSERVACIONES'];
        $datos = "";
        if (!empty($query)) {
            $query2 = explode("////", $query);
            for ($i = 1; $i < count($query2); $i++) {
                $query3 = explode("///", $query2[$i]);
//                for($i=0;$i<count($query2);$i++){
                $datos .=$query3[0] . "<br>" . $query3[1] . "<hr>";
//                }
            }
        }
        return $datos;
    }

    function datos_resolucion($id) {
        $this->db->select('RUTA_DOCUMENTO_FIRMADO,FECHA_CREACION');
        $this->db->where('COD_RESOLUCION', $id);
        $datos = $this->db->get('RESOLUCION');
        $datos = $datos->result_array[0];
        $input = "";
        $tabla = '<p><table border="0" width="100%"><tr style="background: #5bb75b;">'
                . '<td>RESOLUCION</td>'
                . '<td>' . $datos['FECHA_CREACION'] . '</td>'
                . '<td><a  target="_blank" href="' . base_url() . RUTA_DES . $id . '/' . $datos['RUTA_DOCUMENTO_FIRMADO'] . '">ver documento</a></td>'
                . '</tr>'
                . '</table><p>';
        $input.="<input type='hidden' name='rutas[]' value='" . base_url() . RUTA_DES . $id . '/' . $datos['RUTA_DOCUMENTO_FIRMADO'] . "'>";
        $input.="<input type='hidden' name='nombre[]' value='resolucion'>";
        $datos = array();
        $datos['input'] = $input;
        $datos['tabla'] = $tabla;
        return $datos;
    }

    function datos_citacion($id) {
        $this->db->select('CI.DOCUMENTO_RECIBIDO,CI.FECHA_ENVIO_CITACION,CORR.FECHA_RECEPCION,CORR.FECHA_DEVOLUCION,CORR.RUTA_COLILLA,TIPOGESTION.TIPOGESTION');
        $this->db->join('CORRESPONDENCIACITACION CORR', 'CORR.NUM_CITACION=CI.NUM_CITACION');
        $this->db->join('TIPOGESTION', 'TIPOGESTION.COD_GESTION=CI.TIPOGESTION');
        $this->db->where('CI.COD_RESOLUCION', $id);
        $datos = $this->db->get('CITACION CI');
        $datos = $datos->result_array;
        $tabla = '<table border="0" width="100%">'
                . '<tr style="background: #5bb75b;color: #fff">'
                . '<td>FECHA ENVIO CITACI&Oacute;N</td>'
                . '<td>DOCUMENTO ENVIADO</td>'
                . '<td>FECHA RECEPCI&Oacute;N</td>'
                . '<td>FECHA DEVOLUCI&Oacute;N</td>'
                . '<td>TIPOGESTION</td>'
                . '<td>COLILLA</td>'
                . '</tr>';
        $i = 0;
        $input = "";
        foreach ($datos as $values) {
            if ($i % 2 == 0)
                $tabla.= '<tr style="background: #CCC">';
            else
                $tabla.= '<tr>';
            $tabla.= '<td>' . $values['FECHA_ENVIO_CITACION'] . '</td>'
                    . '<td><a  target="_blank" href="' . base_url() . RUTA_DES . $id . '/' . $values['DOCUMENTO_RECIBIDO'] . '">ver documento</a></td>'
                    . '<td>' . $values['FECHA_RECEPCION'] . '</td>'
                    . '<td>' . $values['FECHA_DEVOLUCION'] . '</td>'
                    . '<td><a  target="_blank" href="' . base_url() . RUTA_DES . $id . '/' . $values['RUTA_COLILLA'] . '">ver documento</a></td>'
                    . '<td>' . $values['TIPOGESTION'] . '</td>'
                    . '</tr>';
            $input.="<input type='hidden' name='rutas[]' value='" . base_url() . RUTA_DES . $id . '/' . $values['DOCUMENTO_RECIBIDO'] . "'>";
            $input.="<input type='hidden' name='nombre[]' value='" . $values['TIPOGESTION'] . "'>";
            $i++;
        }
        $tabla.='</table><p>';

        $datos = array();
        $datos['input'] = $input;
        $datos['tabla'] = $tabla;
        return $datos;
    }

    function datos_recurso($id) {
        $this->db->select('RECURSO.FECHA_RECURSO,DOCUMENTOSRECURSO.NOMBRE_DOCUMENTO');
        $this->db->join('RECURSO', 'RECURSO.COD_RESOLUCION=RE.COD_RESOLUCION');
        $this->db->join('DOCUMENTOSRECURSO', 'DOCUMENTOSRECURSO.NUM_RECURSO=RECURSO.NUM_RECURSO');
        $this->db->where('RE.COD_RESOLUCION', $id);
        $datos = $this->db->get('RESOLUCION RE');
        $datos = $datos->result_array;
        $tabla = '<table border="0" width="100%">'
                . '<tr style="background: #5bb75b;color: #fff">'
                . '<td>FECHA DEL RECURSO</td>'
                . '<td>DOCUMENTO PRESENTADO</td>'
                . '</tr>';
        $i = 0;
        $input = "";
        foreach ($datos as $values) {
            if ($i % 2 == 0)
                $tabla.= '<tr style="background: #CCC">';
            else
                $tabla.= '<tr>';
            $tabla.= '<td>' . $values['FECHA_RECURSO'] . '</td>'
                    . '<td><a  target="_blank" href="' . base_url() . RUTA_DES . $id . '/' . $values['NOMBRE_DOCUMENTO'] . '">ver documento</a></td>'
                    . '</tr>';
            $input.="<input type='hidden' name='rutas[]' value='" . base_url() . RUTA_DES . $id . '/' . $values['NOMBRE_DOCUMENTO'] . "'>";
            $input.="<input type='hidden' name='nombre[]' value='Recursos'>";
            $i++;
        }
        $tabla.='</table><p>';
        $datos = array();
        $datos['input'] = $input;
        $datos['tabla'] = $tabla;
        return $datos;
    }

    function datos_ejecutoria($cod_fis) {
        $this->db->select('DOCUMENTO_EJECUTORIA,FECHA_EJECUTORIA');
        $this->db->where('COD_FISCALIZACION', $cod_fis);
        $datos = $this->db->get('EJECUTORIA');
        $datos = $datos->result_array;
        $tabla = '<table border="0" width="100%">'
                . '<tr style="background: #5bb75b;color: #fff">'
                . '<td>FECHA DE LA EJECUTORIA</td>'
                . '<td>DOCUMENTO</td>'
                . '</tr>';
        $i = 0;
        $input = "";
        foreach ($datos as $values) {
            if ($i % 2 == 0)
                $tabla.= '<tr style="background: #CCC">';
            else
                $tabla.= '<tr>';
            $tabla.= '<td>' . $values['FECHA_EJECUTORIA'] . '</td>'
                    . '<td><a  target="_blank" href="' . base_url() . '/uploads/fiscalizaciones/' . $cod_fis . '/ejecutoriaactoadmin/' . $values['DOCUMENTO_EJECUTORIA'] . '">ver documento</a></td>'
                    . '</tr>';
            $input.="<input type='hidden' name='rutas[]' value='" . base_url() . '/uploads/fiscalizaciones/' . $cod_fis . '/ejecutoriaactoadmin/' . $values['DOCUMENTO_EJECUTORIA'] . "'>";
            $input.="<input type='hidden' name='nombre[]' value='Ejecutoria'>";
            $i++;
        }
        $tabla.='</table><p>';
        $datos = array();
        $datos['input'] = $input;
        $datos['tabla'] = $tabla;
        return $datos;
    }

    function datos_revocatoria($id, $cod_fis) {
        $this->db->select('FECHA_REVOCATORIA,DOC_RESPUESTA_FIRMADO');
        $this->db->where('COD_RESOLUCION', $id);
        $datos = $this->db->get('REVOCATORIA');
        $datos = $datos->result_array;
        $tabla = '<table border="0" width="100%">'
                . '<tr style="background: #5bb75b;color: #fff">'
                . '<td>FECHA DE LA REVOCATORIA</td>'
                . '<td>DOCUMENTO</td>'
                . '</tr>';
        $i = 0;
        $input = "";
        foreach ($datos as $values) {
            if ($i % 2 == 0)
                $tabla.= '<tr style="background: #CCC">';
            else
                $tabla.= '<tr>';
            $tabla.= '<td>' . $values['FECHA_REVOCATORIA'] . '</td>'
                    . '<td><a  target="_blank" href="' . base_url() . '/uploads/fiscalizaciones/' . $cod_fis . '/ejecutoriaactoadmin/' . $values['DOC_RESPUESTA_FIRMADO'] . '">ver documento</a></td>'
                    . '</tr>';
            $input.="<input type='hidden' name='rutas[]' value='" . base_url() . '/uploads/fiscalizaciones/' . $cod_fis . '/ejecutoriaactoadmin/' . $values['DOC_RESPUESTA_FIRMADO'] . "'>";
            $input.="<input type='hidden' name='nombre[]' value='Revocatoria'>";
            $i++;
        }
        $tabla.='</table><p>';
        $datos = array();
        $datos['input'] = $input;
        $datos['tabla'] = $tabla;
        return $datos;
    }
    function datos_liquidacion($id, $cod_fis) {
        $this->db->select('SOPORTE_LIQUIDACION.FECHA_RADICADO,SOPORTE_LIQUIDACION.NOMBRE_ARCHIVO');
        $this->db->join('SOPORTE_LIQUIDACION' , 'SOPORTE_LIQUIDACION.NUM_LIQUIDACION=LIQUIDACION.NUM_LIQUIDACION');
        $this->db->where('COD_FISCALIZACION', $id);
        $datos = $this->db->get('LIQUIDACION');
        $datos = $datos->result_array;
        $tabla = '<table border="0" width="100%">'
                . '<tr style="background: #5bb75b;color: #fff">'
                . '<td>FECHA DE LA LIQUIDACION CTÓ</td>'
                . '<td>DOCUMENTO</td>'
                . '</tr>';
        $i = 0;
        $input = "";
        foreach ($datos as $values) {
            if ($i % 2 == 0)
                $tabla.= '<tr style="background: #CCC">';
            else
                $tabla.= '<tr>';
            $tabla.= '<td>' . $values['FECHA_RADICADO'] . '</td>'
                    . '<td><a  target="_blank" href="' . base_url() . '/uploads/fiscalizaciones/' . $cod_fis . '/liquidaciones/' . $values['NOMBRE_ARCHIVO'] . '">ver documento</a></td>'
                    . '</tr>';
            $input.="<input type='hidden' name='rutas[]' value='" . base_url() . '/uploads/fiscalizaciones/' . $cod_fis . '/liquidaciones/' . $values['NOMBRE_ARCHIVO'] . "'>";
            $input.="<input type='hidden' name='nombre[]' value='LIQUIDACION CTO'>";
            $i++;
        }
        $tabla.='</table><p>';
        $datos = array();
        $datos['input'] = $input;
        $datos['tabla'] = $tabla;
        return $datos;
    }
    function informacion_plantilla_coactivo($id){

        $this->db->select("REGIONAL.COD_REGIONAL,MUNICIPIO.NOMBREMUNICIPIO AS CIUDAD,EMPRESA.REPRESENTANTE_LEGAL,
            CF.NOMBRE_CONCEPTO,LIQUIDACION.SALDO_DEUDA,EMPRESA.NOMBRE_EMPRESA,EMPRESA.CODEMPRESA,RESOLUCION.NUMERO_RESOLUCION,
            RESOLUCION.FECHA_CREACION,EMPRESA.DIRECCION,REGIONAL.DIRECCION_REGIONAL,LIQUIDACION.SALDO_DEUDA,TELEFONO_REGIONAL,
CITACION.FECHA_ENVIO_CITACION, EJECUTORIA.FECHA_EJECUTORIA,REGIONAL.NOMBRE_REGIONAL,'' COORDINADOR_REGIONAL",false);

        $this->db->join('EMPRESA','EMPRESA.CODEMPRESA=RESOLUCION.NITEMPRESA');
        $this->db->join('REGIONAL','REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL');
        $this->db->join('CITACION','CITACION.NUM_CITACION=RESOLUCION.NUMERO_CITACION','left');
        $this->db->join('EJECUTORIA','EJECUTORIA.NUM_DOCUMENTO=RESOLUCION.NUMERO_RESOLUCION','left');
        $this->db->join("LIQUIDACION", "LIQUIDACION.COD_FISCALIZACION=RESOLUCION.COD_FISCALIZACION AND LIQUIDACION.SALDO_DEUDA<>0");
        $this->db->join('CONCEPTOSFISCALIZACION CF', 'CF.COD_CPTO_FISCALIZACION = RESOLUCION.COD_CPTO_FISCALIZACION', ' inner');
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO", "LEFT");
        $this->db->where('RESOLUCION.COD_RESOLUCION',$id);
        $dato = $this->db->get('RESOLUCION');
        return @$dato->result_array[0];
    }
    function updateliquidacion($cod_fis){
        $this->db->set("COD_TIPOPROCESO", "2");
        $this->db->where('COD_FISCALIZACION', $cod_fis);
        $query = $this->db->update('LIQUIDACION');
    }

}
