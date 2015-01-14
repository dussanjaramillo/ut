<?php

// Responsable: Leonardo Molina
class Autopruebas_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getDatax($reg, $search) {
        //$this->db->select('*');
        $this->db->select('AJ.DOCUMENTOS_ALEGATOS,AJ.NOMBRE_DOC_FIRMADO,AJ.NOMBRE_DOC_GENERADO,AJ.NUM_AUTOGENERADO, AJ.COD_FISCALIZACION, EM.CODEMPRESA, EM.NOMBRE_EMPRESA, AJ.FECHA_CREACION_AUTO, 
            SEC.SALDO_DEUDA AS ESTADO_VALOR_FINAL, RG.NOMBRE_GESTION, RG.COD_RESPUESTA,
            B.NUM_CITACION, GC.COD_TIPOGESTION ,B.FECHA_RECEPCION,CITACION.TIPOGESTION',FALSE);
        $this->db->from('AUTOSJURIDICOS AJ');
        $this->db->join('(SELECT NUM_AUTOJURIDICO,MAX(CORRESPONDENCIACITACION.FECHA_RECEPCION) FECHA_RECEPCION,MAX(CITACION.NUM_CITACION) NUM_CITACION
FROM CITACION
left JOIN CORRESPONDENCIACITACION ON CORRESPONDENCIACITACION.NUM_CITACION=CITACION.NUM_CITACION
GROUP BY NUM_AUTOJURIDICO) B','B.NUM_AUTOJURIDICO=AJ.NUM_AUTOGENERADO','LEFT',FALSE);
        $this->db->join('CITACION', 'CITACION.NUM_CITACION = B.NUM_CITACION','LEFT');
        $this->db->join('FISCALIZACION F', 'F.COD_FISCALIZACION = AJ.COD_FISCALIZACION');
        $this->db->join('LIQUIDACION SEC', 'SEC.NUM_LIQUIDACION = AJ.SGVA_NRO_CUENTA ');
        $this->db->join('EMPRESA EM', 'EM.CODEMPRESA = SEC.NITEMPRESA');
        $this->db->join('GESTIONCOBRO GC', 'GC.COD_GESTION_COBRO = AJ.COD_GESTIONCOBRO');
        $this->db->join('RESPUESTAGESTION RG', 'RG.COD_RESPUESTA = GC.COD_TIPO_RESPUESTA');
        $this->db->where('COD_TIPO_AUTO', '20');
        $this->db->where("NOT EXISTS(
SELECT 'X' 
FROM RESOLUCION 
WHERE RESOLUCION.COD_FISCALIZACION=SEC.COD_FISCALIZACION
) AND 1=", '1', FALSE);
        $this->db->order_by('AJ.FECHA_CREACION_AUTO', 'desc');
        $this->db->limit(12, $reg);
        // ESTADOAUTO = 3 // APROBADOS
        if ($search) {
            $this->db->like('AJ.NUM_AUTOGENERADO', $search);
            $this->db->or_like('AJ.FECHA_CREACION_AUTO', $search);
            $this->db->or_like('EM.CODEMPRESA', $search);
            $this->db->or_like('EM.NOMBRE_EMPRESA', $search);
            $this->db->or_like('SEC.ESTADO_VALOR_FINAL', $search);
            $this->db->or_like('RG.NOMBRE_GESTION', $search);
        }


        $query = $this->db->get();
        return $query->result();
    }

    function totalData($search) {
        $this->db->select('AJ.NOMBRE_DOC_GENERADO,AJ.NUM_AUTOGENERADO, AJ.COD_FISCALIZACION, EM.CODEMPRESA, EM.NOMBRE_EMPRESA, AJ.FECHA_CREACION_AUTO, 
            SEC.SALDO_DEUDA AS ESTADO_VALOR_FINAL, RG.NOMBRE_GESTION, RG.COD_RESPUESTA,
            B.NUM_CITACION, GC.COD_TIPOGESTION ,B.FECHA_RECEPCION',FALSE);
        $this->db->from('AUTOSJURIDICOS AJ');
        $this->db->join('(SELECT NUM_AUTOJURIDICO,MAX(CORRESPONDENCIACITACION.FECHA_RECEPCION) FECHA_RECEPCION,MAX(CITACION.NUM_CITACION) NUM_CITACION
FROM CITACION
JOIN CORRESPONDENCIACITACION ON CORRESPONDENCIACITACION.NUM_CITACION=CITACION.NUM_CITACION
GROUP BY NUM_AUTOJURIDICO) B','B.NUM_AUTOJURIDICO=AJ.NUM_AUTOGENERADO',FALSE);
        $this->db->join('FISCALIZACION F', 'F.COD_FISCALIZACION = AJ.COD_FISCALIZACION');
        $this->db->join('LIQUIDACION SEC', 'SEC.NUM_LIQUIDACION = AJ.SGVA_NRO_CUENTA ');
        $this->db->join('EMPRESA EM', 'EM.CODEMPRESA = SEC.NITEMPRESA');
        $this->db->join('GESTIONCOBRO GC', 'GC.COD_GESTION_COBRO = AJ.COD_GESTIONCOBRO');
        $this->db->join('RESPUESTAGESTION RG', 'RG.COD_RESPUESTA = GC.COD_TIPO_RESPUESTA');
        $this->db->where('COD_TIPO_AUTO', '20');
        $this->db->where("NOT EXISTS(
SELECT 'X' 
FROM RESOLUCION 
WHERE RESOLUCION.COD_FISCALIZACION=SEC.COD_FISCALIZACION
) AND 1=", '1', FALSE);
        $this->db->order_by('AJ.FECHA_CREACION_AUTO', 'desc');
        // ESTADOAUTO = 3 // APROBADOS
        if ($search) {
            $this->db->like('AJ.NUM_AUTOGENERADO', $search);
            $this->db->or_like('AJ.FECHA_CREACION_AUTO', $search);
            $this->db->or_like('EM.CODEMPRESA', $search);
            $this->db->or_like('EM.NOMBRE_EMPRESA', $search);
            $this->db->or_like('SEC.ESTADO_VALOR_FINAL', $search);
            $this->db->or_like('RG.NOMBRE_GESTION', $search);
        }

        $query = $this->db->get();
        if ($query->num_rows() == 0)
            return '0';
        return $query->num_rows();
    }

    function getAuto($auto) {
        $this->db->select('AJ.*,EM.CODEMPRESA,EM.NOMBRE_EMPRESA,EM.DIRECCION');
        $this->db->from('AUTOSJURIDICOS AJ');
        $this->db->join('LIQUIDACION SEC', 'SEC.NUM_LIQUIDACION = AJ.SGVA_NRO_CUENTA ');
        $this->db->join('EMPRESA EM', 'EM.CODEMPRESA = SEC.NITEMPRESA');
        $this->db->where('AJ.NUM_AUTOGENERADO', $auto);
        $query = $this->db->get();
        return $query->row();
    }

    function getAutoGestion($auto) {
        $this->db->select('AJ.*,RG.*,(SELECT sysdate - AJ.FECHA_PRORROGA FROM dual) AS RESTANTE', FALSE);
        $this->db->from('AUTOSJURIDICOS AJ');
        $this->db->join('GESTIONCOBRO GC', 'GC.COD_GESTION_COBRO = AJ.COD_GESTIONCOBRO');
        $this->db->join('RESPUESTAGESTION RG', 'RG.COD_RESPUESTA = GC.COD_TIPO_RESPUESTA');
        $this->db->where('AJ.NUM_AUTOGENERADO', $auto);
        $query = $this->db->get();
        return $query->row();
    }

    function getAutoAlegatos($auto) {
        $this->db->select('AJ.NUM_AUTOGENERADO,AJ.COD_FISCALIZACION,EM.CODEMPRESA,EM.NOMBRE_EMPRESA,EM.DIRECCION');
        $this->db->from('AUTOSJURIDICOS AJ');
        $this->db->join('SGVA_ESTDO_DE_CUENTA SEC', 'SEC.ESTADO_NRO_ESTADO = AJ.SGVA_NRO_CUENTA');
        $this->db->join('EMPRESA EM', 'EM.CODEMPRESA = SEC.ESTADO_NIT_EMPRESA');
        $this->db->where('AJ.NUM_AUTOGENERADO', $auto);
        $query = $this->db->get();
        return $query->row();
    }

    function getTipoDocumento() {
        $this->db->select('*');
        $this->db->from('TIPODOCUMENTO');
        $query = $this->db->get();
        return $query->result();
    }

    function getDocumentosRespAuto($auto) {
        $this->db->select('RA.NOMBRE_DOCU_RESPUESTA,AJ.COD_FISCALIZACION,AJ.NUM_AUTOGENERADO');
        $this->db->from('RESPUESTA_AUTO RA');
        $this->db->join('AUTOSJURIDICOS AJ', 'AJ.NUM_AUTOGENERADO = RA.NUM_AUTOGENERADO', 'inner');
        $this->db->where('RA.NUM_AUTOGENERADO', $auto);
        $this->db->order_by('RA.NUM_RESPUESTA_AUTO', 'DESC');
        $query = $this->db->get();
        return $query->row();
    }

    function getDocumentosPruebas($auto) {
        $this->db->select('DR.*,AJ.COD_FISCALIZACION');
        $this->db->from('RECURSO R');
        $this->db->join('DOCUMENTOSRECURSO DR', 'DR.NUM_RECURSO = R.NUM_RECURSO', 'inner');
        $this->db->join('AUTOSJURIDICOS AJ', 'AJ.NUM_AUTOGENERADO = R.NUM_AUTOGENERADO', 'inner');
        $this->db->where('R.NUM_AUTOGENERADO', $auto);

        $query = $this->db->get();
        return $query->result();
    }

    function getRecurso($auto) {
        $this->db->select('NUM_RECURSO');
        $this->db->from('RECURSO');
        $this->db->where('NUM_AUTOGENERADO', $auto);
        $query = $this->db->get();
        return $query->row();
    }

    function getEmpresa($nit) {

        $this->db->select('E.CODEMPRESA,E.NOMBRE_EMPRESA,E.DIRECCION,D.NOM_DEPARTAMENTO');
        $this->db->from('EMPRESA E');
        $this->db->join('DEPARTAMENTO D', 'D.COD_DEPARTAMENTO = E.COD_DEPARTAMENTO', 'INNER');
        $this->db->where('E.CODEMPRESA', $nit);

        $query = $this->db->get();
        return $query->row();
    }

    function deleteFile($data) {
        $this->db->where('NOMBRE_DOCUMENTO', $data);
        $query = $this->db->delete('DOCUMENTOSRECURSO');
        return $query;
    }

    function add_recurso($data) {
        $this->db->set('FECHA_RECURSO', 'sysdate', false);
        $this->db->insert('RECURSO', $data);

        $this->db->select('NUM_RECURSO');
        $this->db->from('RECURSO');
        $this->db->where($data);
        $this->db->order_by('FECHA_RECURSO', 'desc');
        $dato = $this->db->get();
        $numrecurso = $dato->result_array[0];
        return $numrecurso;
    }

    function add_docPruebas($data) {
        $this->db->set('FECHA_CARGA', 'sysdate', false);
        $this->db->insert('DOCUMENTOSRECURSO', $data);
    }

    function add_respAuto($data, $dataf) {
        //$this->db->set('FECHA_RESPUESTA','SYSDATE',false);
        $this->db->set('FECHA_RESPUESTA', "to_date('" . $dataf['FECHA_RESPUESTA'] . "','dd/mm/yyyy HH24:MI:SS')", false);
        $query = $this->db->insert('RESPUESTA_AUTO', $data);
        return $query;
    }
    function desbloquear($post) {
            $cod_siguiente = "37";
        $codgest=trazar($cod_siguiente, "55", $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
        $post['post']['cod_resolucion']=$post['post']['id'];
        $this->trazabilidad4($post['post'],$codgest['COD_GESTION_COBRO']);
    }
    function gestion_citacion($id) {
        $this->db->select('CITACION.TIPOGESTION');
        $this->db->join("AUTOSJURIDICOS", "AUTOSJURIDICOS.NUM_AUTOGENERADO=CITACION.NUM_AUTOJURIDICO ");
        $this->db->where('CITACION.NUM_AUTOJURIDICO', $id);
        $this->db->order_by("CITACION.TIPOGESTION", "DESC");
        $dato = $this->db->get('CITACION');
        return $datos = $dato->result_array;
    }
    function siguiente_codigo_del_bolqueo($post) {

        $tipogestion = $this->gestion_citacion($post['post']['id']);
        if ($tipogestion[0]['TIPOGESTION'] == '33') {
                $cod_siguiente = "35";
                $cod_respuesta = "52";
        } else if ($tipogestion[0]['TIPOGESTION'] == '34') {
            $cod_siguiente = "35";
            $cod_respuesta = "52";
        } else if ($tipogestion[0]['TIPOGESTION'] == '411') {
            $cod_siguiente = "414";
            $cod_respuesta = "52";
        } else {
            $cod_siguiente = "37";
            $cod_respuesta = "55";
        }
        $post['post']['cod_resolucion']=$post['post']['id'];
        $codgest=trazar($cod_siguiente, $cod_respuesta, $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
        $this->trazabilidad4($post['post'],$codgest['COD_GESTION_COBRO']);
    }
    function update_Auto($data, $auto) {
        if ($data['SOLICITUD_PRUEBAS'])
            $this->db->set('FECHA_SOLICITUD_PRUEBAS', 'SYSDATE', false);
        if ($data['TRASLADO_ALEGATO'])
            $this->db->set('FECHA_TRASLADO_ALEGATO', 'SYSDATE', false);
        $this->db->set("FECHA_GESTION", 'SYSDATE', FALSE);
        $this->db->where('NUM_AUTOGENERADO', $auto);
        $query = $this->db->update('AUTOSJURIDICOS', $data);

        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La operaciÃ³n se ha realizado con Ã©xito.</div>');
        return $query;
    }

    function updateStateAuto($autoCargos, $codGestion) {
        $this->db->set("COD_GESTIONCOBRO", $codGestion);
        $this->db->set("FECHA_GESTION", 'SYSDATE', FALSE);
        $this->db->where('NUM_AUTOGENERADO', $autoCargos);
        $query = $this->db->update('AUTOSJURIDICOS');
        return $query;
    }

    function updateAlegatoAuto($data, $autoCargos, $numrecurso, $codGestion) {
        $this->db->set("FECHA_GESTION", 'SYSDATE', FALSE);
        $this->db->set('COD_GESTIONCOBRO', $codGestion);
        $this->db->where('NUM_AUTOGENERADO', $autoCargos);
        $this->db->update('AUTOSJURIDICOS');

        $this->db->set('FECHA_CARGA', 'SYSDATE', FALSE);
        $this->db->where('NUM_RECURSO', $numrecurso);
        $query = $this->db->update('DOCUMENTOSRECURSO', $data);


        return $query;
    }

    function updateProrrogaAuto($data, $autoCargos) {
        $this->db->set("FECHA_GESTION", 'SYSDATE', FALSE);
        $this->db->set("FECHA_PRORROGA", 'SYSDATE', FALSE);
        $this->db->where('NUM_AUTOGENERADO', $autoCargos);
        $query = $this->db->update('AUTOSJURIDICOS', $data);
        return $query;
    }

    function updateAPruebaFirm($autoCargos, $codGestion, $data) {

        $this->db->select('NUM_AUTOGENERADO,COD_GESTIONCOBRO');
        $this->db->from('AUTOSJURIDICOS');
        $this->db->where('NUM_AUTOGENERADO', $autoCargos);
        $query = $this->db->get();
        $this->db->set("FECHA_GESTION", 'SYSDATE', FALSE);
        $this->db->set("FECHA_DOC_FIRMADO", 'SYSDATE', FALSE);
        $this->db->set("COD_GESTIONCOBRO", $codGestion);
        $this->db->where('COD_TIPO_AUTO', '22');
        $this->db->where('COD_GESTIONCOBRO', $query->row()->COD_GESTIONCOBRO);
        $query2 = $this->db->update('AUTOSJURIDICOS', $data);

        $this->updateStateAuto($autoCargos, $codGestion);

        return $query2;
    }

    function saveAuto($cod_tipo_auto, $cod_fiscalizacion, $cod_estadoauto, $cod_tipo_proceso, $creado_por, $asignado_a, $cod_gestioncobro, $nombreDoc
    ) {

        $this->db->set("COD_TIPO_AUTO", $cod_tipo_auto);
        $this->db->set("COD_FISCALIZACION", $cod_fiscalizacion);
        $this->db->set("COD_ESTADOAUTO", $cod_estadoauto);
        $this->db->set("COD_TIPO_PROCESO", $cod_tipo_proceso);
        $this->db->set("CREADO_POR", $creado_por);
        $this->db->set("ASIGNADO_A", $asignado_a);
        $this->db->set("FECHA_GESTION", 'SYSDATE', FALSE);
        $this->db->set("FECHA_CREACION_AUTO", 'SYSDATE', false);
        $this->db->set("COD_GESTIONCOBRO", $cod_gestioncobro);
        $this->db->set("NOMBRE_DOC_GENERADO", $nombreDoc);
        $query = $this->db->insert("AUTOSJURIDICOS");

        return $query;
    }

    function trazabilidad($post, $codgest) {
        $this->db->set("COD_GESTIONCOBRO", $codgest);
        $this->db->set("COMENTARIOS", 'COMENTARIOS ||' . "'" . $post['obser'] . "'", FALSE);
        $this->db->where("NUM_AUTOGENERADO", $post['num_auto']);
        $this->db->update("AUTOSJURIDICOS");
    }
    function trazabilidad7($post, $codgest) {
        $this->db->set("COD_GESTIONCOBRO", $codgest);
        $this->db->set("DOCUMENTO_RADICADO", 'DOCUMENTO_RADICADO ||' . "'" . $post['obser'] . "'", FALSE);
        $this->db->where("NUM_AUTOGENERADO", $post['num_auto']);
        $this->db->update("AUTOSJURIDICOS");
    }
    function trazabilidad8($post, $codgest) {
        $this->db->set("COD_GESTIONCOBRO", $codgest);
        $this->db->set("OBSERVACIONES_ALEGATOS", 'OBSERVACIONES_ALEGATOS ||' . "'" . $post['obser'] . "'", FALSE);
        $this->db->where("NUM_AUTOGENERADO", $post['num_auto']);
        $this->db->update("AUTOSJURIDICOS");
    }

    function trazabilidad2($post, $codgest, $nombreDoc) {
        if ($nombreDoc != "") {
            $this->db->set("NOMBRE_DOC_GENERADO", $nombreDoc);
        }
        $this->db->set("COD_GESTIONCOBRO", $codgest);
        $this->db->where("NUM_AUTOGENERADO", $post['num_auto']);
        $this->db->update("AUTOSJURIDICOS");
    }
    function trazabilidad6($post, $codgest, $nombreDoc) {
        if ($nombreDoc != "") {
            $this->db->set("NOMBRE_DOC_FIRMADO", $nombreDoc);
        }
        $this->db->set("COD_GESTIONCOBRO", $codgest);
        $this->db->where("NUM_AUTOGENERADO", $post['num_auto']);
        $this->db->update("AUTOSJURIDICOS");
    }
    function trazabilidad9($post, $codgest, $nombreDoc) {
        if ($nombreDoc != "") {
            $this->db->set("DOCUMENTOS_ALEGATOS", $nombreDoc);
        }
        $this->db->set("COD_GESTIONCOBRO", $codgest);
        $this->db->where("NUM_AUTOGENERADO", $post['num_auto']);
        $this->db->update("AUTOSJURIDICOS");
    }

    function plantilla($id) {
        $this->db->select('ARCHIVO_PLANTILLA');
        $this->db->where('CODPLANTILLA', $id);
        $dato = $this->db->get('PLANTILLA');
        return $datos5 = $dato->result_array['0']['ARCHIVO_PLANTILLA'];
    }
    function correspondencia_citacion($file, $post) {

        $this->db->set('NUM_CITACION', $post['post']['num_citacion']);
        $this->db->set('CITACION_CONCEPTO', $post['post']['gestionar']);
        if ($post['post']['gestionar'] == 1) {
//            $this->db->set('FECHA_RECEPCION', $post['post']['fecha_recep']);
            $this->db->set('FECHA_RECEPCION', "to_date('" . $post['post']['fecha_recep'] . "','DD/MM/RR')", false);
            if ($post['post']['cod_estado'] == "412") {
                $cod_siguiente = "413";
                $respuesta = "37";
            } else {
                $cod_siguiente = "84";
                $respuesta = "37";
            }
        } else {
//            $this->db->set('FECHA_DEVOLUCION', $post['post']['fecha_actual']);
            $this->db->set('FECHA_DEVOLUCION', "to_date('" . $post['post']['fecha_actual'] . "','DD/MM/RR')", false);
            $this->db->set('COD_MOTIVODEVOLUCION', $post['post']['razon']);
            if ($post['post']['razon'] == "3" || $post['post']['razon'] == "8" || $post['post']['razon'] == "9") {
                if ($post['post']['cod_estado'] == "412") {
                    $cod_siguiente = "410";
                    $respuesta = "40";
                } else {
                    $cod_siguiente = "29";
                    $respuesta = "40";
                }
            } else {
                if ($post['post']['cod_estado'] == "412") {
                    $cod_siguiente = "414";
                    $respuesta = "40";
                } else {
                    $cod_siguiente = "310"; //310
                    $respuesta = "884";
                }
            }
        }
        $this->db->set('RUTA_COLILLA', $file['upload_data']['file_name']);
        $this->db->set('NOMBRE_RECEPTOR', $post['post']['nombre_recep']);
        $this->db->set('NUM_COLILLA', $post['post']['colilla']);
        $this->db->insert('CORRESPONDENCIACITACION');

        $codgest=trazar($cod_siguiente, $respuesta, $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
        $this->trazabilidad4($post['post'],$codgest['COD_GESTION_COBRO']);
    }
    function correspondencia_citacion_gestion($post) {

        $this->db->set('DOCUMENTO_GENERADO', $post['post']['nombre']);
        $this->db->where('NUM_CITACION', $post['post']['num_citacion']);
        $this->db->update('CORRESPONDENCIACITACION');

        switch ($post['post']['notificacion']) {
            case "38":
                $resultado = "59";
                break;
            case "39":
                $resultado = "61";
                break;
            case "40":
                $resultado = "63";
                break;
        }

        $codgest=trazar($post['post']['notificacion'], $resultado, $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
        $this->trazabilidad4($post['post'],$codgest['COD_GESTION_COBRO']);
    }
    function subir_acta_archivo($post) {
        $data = array(
            'NOMBRE_DOCUMENTO_FIRMADO' => $post['file']['upload_data']['file_name'],
            'DOCUMENTO_GENERADO' => $post['post']['nombre_archivo']
        );
        $this->db->where('NUM_CITACION', $post['post']['num_citacion']);
        $this->db->update('CORRESPONDENCIACITACION', $data);

        if ($this->db->affected_rows() == '0') {
            $this->db->set("NUM_CITACION", $post['post']['num_citacion']);
            $this->db->set("FECHA_RECEPCION", FECHA, FALSE);
            $this->db->set("NOMBRE_DOCUMENTO_FIRMADO", $post['file']['upload_data']['file_name']);
            $this->db->set("DOCUMENTO_GENERADO", $post['post']['nombre_archivo']);
            $this->db->insert('CORRESPONDENCIACITACION');
        }
        $post['post']['cod_resolucion']=$post['post']['id'];
        $codgest=trazar("55", "91", $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
        $this->trazabilidad4($post['post'],$codgest['COD_GESTION_COBRO']);
    }
    function consultar($post) {
        $this->db->select('EMPRESA.CORREOELECTRONICO,GC.COD_TIPOGESTION COD_ESTADO,MUNICIPIO.NOMBREMUNICIPIO,LIQUIDACION.COD_CONCEPTO COD_CPTO_FISCALIZACION,AUTOSJURIDICOS.NUM_AUTOGENERADO COD_RESOLUCION,LIQUIDACION.NITEMPRESA,'
                . 'CEDULA_COORDINADOR,AUTORIZA_NOTIFIC_EMAIL,REGIONAL.COD_REGIONAL,AUTOSJURIDICOS.NUM_RESOLUCION NUMERO_RESOLUCION,'
                . 'LIQUIDACION.FECHA_RESOLUCION FECHA_CREACION,EMPRESA.FAX,REGIONAL.NOMBRE_REGIONAL,REGIONAL.DIRECCION_REGIONAL,EMPRESA.DIRECCION,'
                . 'EMPRESA.REPRESENTANTE_LEGAL,EMPRESA.NOMBRE_EMPRESA,USUARIOS.NOMBRES COORDINADOR_REGIONAL,EMPRESA.TELEFONO_FIJO,REGIONAL.COD_CIUDAD,'
                . '(SELECT MAX(NUM_CITACION) FROM CITACION WHERE NUM_AUTOJURIDICO=AUTOSJURIDICOS.NUM_AUTOGENERADO) NUM_CITACION,'
                . '(SELECT MAX(NUM_CITACION) FROM CITACION WHERE NUM_AUTOJURIDICO=AUTOSJURIDICOS.NUM_AUTOGENERADO) NUMERO_CITACION'
                . '');
        $this->db->join('LIQUIDACION', 'LIQUIDACION.COD_FISCALIZACION=AUTOSJURIDICOS.COD_FISCALIZACION');
        $this->db->join('CONCEPTOSFISCALIZACION', 'CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO');
        $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA');
        $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL');
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO", "LEFT");
        $this->db->join('GESTIONCOBRO GC', 'GC.COD_GESTION_COBRO = AUTOSJURIDICOS.COD_GESTIONCOBRO');
        $this->db->join('USUARIOS', 'USUARIOS.IDUSUARIO=REGIONAL.CEDULA_COORDINADOR');
        $this->db->where('AUTOSJURIDICOS.NUM_AUTOGENERADO', $post);
        $datos = $this->db->get('AUTOSJURIDICOS');
        return $datos->result_array;
    }

    function guardar_citacion($post, $cod_cita) {

        $this->db->set("NUM_AUTOJURIDICO", $post['post']['cod_resolucion']);
        $this->db->set("COD_TIPO_CITACION", $cod_cita);
        $this->db->set("FECHA_ENVIO_CITACION", FECHA, false);
        $this->db->set("ELABORO", $post['user']->IDUSUARIO);
        $this->db->set("DOCUMENTO_GENERADO", $post['post']['nombre_archivo']);
        $this->db->set("REVISO", $post['user']->IDUSUARIO);
        $this->db->set("PROYECTO", $post['user']->IDUSUARIO);
        $this->db->set("COORDINADOR", $post['post']['cod_coordinador']);
        $this->db->set("TIPOGESTION", $post['post']['radio']);
        if (!empty($post['post']['correo']))
            $this->db->set("CORREO_ENVIO", $post['post']['correo']);
        $this->db->set("FAX", $post['post']['fax']);
        $this->db->set("DIRECCION_ENVIO", $post['post']['domicilio']);
        $this->db->insert('CITACION');
    }

    function trazabilidad3($post, $codgest, $nombreDoc) {
        $this->db->set("NIS", $post['nombre_archivo']);
        $this->db->set("FECHA_RADICADO", $post['fecha']);
        $this->db->set("NOMBRE_DOC_GENERADO", $nombreDoc);
        $this->db->set("COD_GESTIONCOBRO", $codgest);
        $this->db->where("NUM_AUTOGENERADO", $post['id']);
        $this->db->update("AUTOSJURIDICOS");
    }
    function motivodevolucion() {
        $this->db->select('NOMBRE_MOTIVO,COD_MOTIVODEVOLUCION');
        $dato = $this->db->get('MOTIVODEVCORRESPONDENCIA');
        return $dato->result_array;
    }
    function documentacion_resolcuion($id) {
        $this->db->select("NOMBRE_DOC_GENERADO RUTA_DOCUMENTO_FIRMADO,NIS NUMERO_RESOLUCION,'Auto de Cargos' NOMBRE_ARCHIVO_RESOLUCION" , FALSE);
        $this->db->where('NUM_AUTOGENERADO', $id);
        $dato = $this->db->get('AUTOSJURIDICOS');
        return $datos = $dato->result_array;
    }
    function documentacion_citacion($id) {
//        citacion
        $this->db->select('DOCUMENTO_GENERADO,TIPOGESTION.TIPOGESTION');
        $this->db->join('TIPOGESTION', 'TIPOGESTION.COD_GESTION=CITACION.TIPOGESTION');
        $this->db->where('NUM_AUTOJURIDICO', $id);
        $dato = $this->db->get('CITACION');
        return $datos2 = $dato->result_array;
    }
    
    function trazabilidad4($post, $codgest) {
        $this->db->set("COD_GESTIONCOBRO", $codgest);
        $this->db->where("NUM_AUTOGENERADO", $post['cod_resolucion']);
        $this->db->update("AUTOSJURIDICOS");
    }
    function trazabilidad5($post, $codgest,$nombreDoc) {
        $this->db->set("NOMBRE_DOC_FIRMADO", $nombreDoc);
        $this->db->set("COD_GESTIONCOBRO", $codgest);
        $this->db->where("NUM_AUTOGENERADO", $post['cod_resolucion']);
        $this->db->update("AUTOSJURIDICOS");
    }
    function correspondencia($id) {
        $this->db->select('CORRESPONDENCIACITACION.NOMBRE_RECEPTOR,CORRESPONDENCIACITACION.NUM_COLILLA,CORRESPONDENCIACITACION.RUTA_COLILLA,
CORRESPONDENCIACITACION.FECHA_DEVOLUCION,CORRESPONDENCIACITACION.FECHA_RECEPCION');
        $this->db->join('CITACION', 'CITACION.NUM_CITACION=CORRESPONDENCIACITACION.NUM_CITACION');
        $this->db->join('AUTOSJURIDICOS', 'AUTOSJURIDICOS.NUM_AUTOGENERADO=CITACION.NUM_AUTOJURIDICO');
        $this->db->where('AUTOSJURIDICOS.NUM_AUTOGENERADO', $id);
        $dato = $this->db->get('CORRESPONDENCIACITACION');
        return $datos3 = $dato->result_array;
    }

    function historico_obser($post) {
        $this->db->select("COMENTARIOS");
        $this->db->where('NUM_AUTOGENERADO', $post['num_auto']);
        $query = $this->db->get('AUTOSJURIDICOS');
        $query = $query->result_array[0]['COMENTARIOS'];
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
    function historico_obser2($post) {
        $this->db->select("DOCUMENTO_RADICADO COMENTARIOS");
        $this->db->where('NUM_AUTOGENERADO', $post['num_auto']);
        $query = $this->db->get('AUTOSJURIDICOS');
        $query = $query->result_array[0]['COMENTARIOS'];
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
    function historico_obser3($post) {
        $this->db->select("OBSERVACIONES_ALEGATOS COMENTARIOS");
        $this->db->where('NUM_AUTOGENERADO', $post['num_auto']);
        $query = $this->db->get('AUTOSJURIDICOS');
        $query = $query->result_array[0]['COMENTARIOS'];
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
    function citacion($id) {
        $this->db->select('TIPOGESTION');
        $this->db->where("NUM_CITACION", $id);
        $dato = $this->db->get("CITACION");
        $datos = $dato->result_array[0]['TIPOGESTION'];
        return $datos ;
    }
    function update_citacion($post) {
        $data = array(
            'DOCUMENTO_RECIBIDO' => $post['file']['upload_data']['file_name']
        );
        $this->db->where('NUM_CITACION', $post['post']['num_citacion']);
        $this->db->update('CITACION', $data);
    }

}
