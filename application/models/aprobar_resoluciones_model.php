<?php

class Aprobar_resoluciones_model extends CI_Model {

    var $director;
    var $nit;
    var $array_num;
    var $set_abogado;
    var $municipio;
    var $iduser;

    function __construct() {
        parent::__construct();
    }

    function set_director($director) {
        $this->director = $director;
    }

    function set_iduser($id) {
        $this->iduser = $id;
    }

    function set_permiso($permiso) {
        $this->permiso = $permiso;
    }

    function set_array_num($array_num) {
        $this->array_num = $array_num;
    }

    function set_nit($nit) {
        $this->nit = $nit;
    }

    function set_abogado($abogado) {
        $this->abogado = $abogado;
    }

    function set_municipio($municipio) {
        $this->municipio = $municipio;
    }

    function tipodocumento() {
        $this->db->select('CODTIPODOCUMENTO,NOMBRETIPODOC');
        $dato = $this->db->get('TIPODOCUMENTO');
        return $datos = $dato->result_array;
    }

    function motivodevolucion() {
        $this->db->select('NOMBRE_MOTIVO,COD_MOTIVODEVOLUCION');
        $dato = $this->db->get('MOTIVODEVCORRESPONDENCIA');
        return $dato->result_array;
    }

    function guardar_citacion_favorabilidad($post) {

        $data = array(
            'FAVORABILIDAD' => "2",
        );
        $this->db->where('NUM_RECURSO', $post['post']['num_recurso']);
        $this->db->update('RESOLUCIONRECURSO', $data);

        $this->db->set("COD_RESOLUCION", $post['consulta'][0]['COD_RESOLUCION']);
        $this->db->set("COD_TIPO_CITACION", "1");
        $this->db->set("COD_PLANTILLA_CITACION", "9");
        $this->db->set("FECHA_ENVIO_CITACION", FECHA, false);
        $this->db->set("ELABORO", $post['user']->IDUSUARIO);
        $this->db->set("DOCUMENTO_GENERADO", $post['post']['nombre']);
        $this->db->set("REVISO", $post['user']->IDUSUARIO);
        $this->db->set("PROYECTO", $post['user']->IDUSUARIO);
        $this->db->set("COORDINADOR", $post['consulta'][0]['COORDINADOR']);
        if ($post['consulta'][0]['AUTORIZA_NOTIFIC_EMAIL'] == "s" || $post['consulta'][0]['AUTORIZA_NOTIFIC_EMAIL'] == "S")
            $this->db->set("CORREO_ENVIO", $post['consulta'][0]['CORREOELECTRONICO']);
        $this->db->set("FAX", $post['consulta'][0]['FAX']);
        $this->db->set("DIRECCION_ENVIO", $post['consulta'][0]['DIRECCION']);
        $this->db->insert('CITACION');
        $this->actualizar_estado("54", $post['consulta'][0]['COD_RESOLUCION'], $post['user']->IDUSUARIO);

        //
        $this->db->select('NUM_CITACION');
        $this->db->where("COD_RESOLUCION", $post['cod_resolucion']);
        $this->db->where("FECHA_ENVIO_CITACION", "to_date('" . date("d/m/Y") . "','DD/MM/RR')", false);
        $dato = $this->db->get('CITACION');
        $datos = $dato->result_array;

        $data = array(
            'NUMERO_CITACION' => $datos[0]['NUM_CITACION']
        );
        $this->db->where('COD_RESOLUCION', $post['cod_resolucion']);
        $this->db->update('RESOLUCION', $data);
    }

    function actualizar_estado($cod, $id, $id_user) {
        $data = array(
            'COD_ESTADO' => $cod
        );
        $this->db->where('COD_RESOLUCION', $id);
        $this->db->update('RESOLUCION', $data);
        $this->gestion_resolucion($id, $id_user);
    }

    function citacion_pendientes($id) {
//        $this->db->select('MAX(NUM_CITACION) as MAS',false);
//        $this->db->where('COD_RESOLUCION', $id);
//        $dato = $this->db->get('CITACION');

        $this->db->select('RESOLUCION.NUMERO_CITACION,CITACION.FECHA_ENVIO_CITACION,CITACION.TIPOGESTION');
        $this->db->join("CITACION", "CITACION.NUM_CITACION=RESOLUCION.NUMERO_CITACION");
        $this->db->where('RESOLUCION.COD_RESOLUCION', $id);
//        $this->db->where('CITACION.NUM_CITACION', $dato->result_array[0]['MAS']);
        $dato = $this->db->get('RESOLUCION');
        return $dato->result_array;
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
                    $cod_siguiente = "662";
                    $respuesta = "1422";
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

        //$post['cod_fiscalizacion'];

        $post['post']['id'] = $post['post']['cod_resolucion'];
        $this->resolucion_actualizar($post, $cod_siguiente);
        $comentario = "RECEPCIÓN DE LA CITACIÓN";
        trazar($cod_siguiente, $respuesta, $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
        $this->detalle_resolcion($post, $comentario);
    }

    function correspondencia_citacion_gestion($post) {

        $this->db->set('DOCUMENTO_GENERADO', $post['post']['nombre']);
        $this->db->where('NUM_CITACION', $post['post']['num_citacion']);
        $this->db->update('CORRESPONDENCIACITACION');

        //$post['cod_fiscalizacion'];

        $data = array(
            'COD_ESTADO' => $post['post']['notificacion']
        );
        $this->db->where('COD_RESOLUCION', $post['post']['cod_resolucion']);
        $this->db->update('RESOLUCION', $data);


        $this->db->select('TIPOGESTION');
        $this->db->where('COD_GESTION', $post['post']['notificacion']);
        $dato = $this->db->get('TIPOGESTION');
        $datos = $dato->result_array;

        $this->db->set('COD_RESOLUCION', $post['post']['cod_resolucion']);
        $this->db->set('RESPONSABLE', $post['user']->IDUSUARIO);
        $this->db->set("COMENTARIOS", $datos[0]['TIPOGESTION']);
        $this->db->insert('GESTIONRESOLUCION');

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

        trazar($post['post']['notificacion'], $resultado, $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
    }

    function subir_acta_recurso_archivo($post) {
//        echo "<pre>";
//        print_r($post);
//        echo "</pre>";
        $data = array(
            'NOMBRE_DOCU_FIRMADO' => $post['file']['upload_data']['file_name']
        );
        $this->db->where('NUM_RECURSO', $post['recurso'][0]['NUM_RECURSO']);
        $this->db->update('RESOLUCIONRECURSO', $data);

        $data = array(
            'NUMERO_RESOLUCION' => $post['post']['num_resolu'],
            'FECHA_CREACION' => $post['post']['nom_resolu'],
            'COD_ESTADO' => "29",
        );
        $this->db->where('COD_RESOLUCION', $post['post']['id_resolucion']);
        $this->db->update('RESOLUCION', $data);

        $this->db->set("COD_RESOLUCION", $post['post']['id_resolucion']);
        $this->db->set("COMENTARIOS", "ADJUNTO RESOLICION CON RECURSO");
        $this->db->set("RESPONSABLE", $post['user']->IDUSUARIO);
        $this->db->insert('GESTIONRESOLUCION');
    }

    function update_citacion($post) {
        $data = array(
            'COD_ESTADO' => $post['post']['cod_siguiente'],
        );
        $this->db->where('COD_RESOLUCION', $post['post']['id']);
        $this->db->update('RESOLUCION', $data);

        $data = array(
            'DOCUMENTO_RECIBIDO' => $post['file']['upload_data']['file_name']
        );
        $this->db->where('NUM_CITACION', $post['post']['num_citacion']);
        $this->db->update('CITACION', $data);

        $this->db->set("COD_RESOLUCION", $post['post']['id']);
        $this->db->set("COMENTARIOS", "DOCUMENTO CITACION GUARDADO");
        $this->db->set("RESPONSABLE", $post['user']->IDUSUARIO);
        $this->db->insert('GESTIONRESOLUCION');
        trazar($post['post']['cod_siguiente'], $post['post']['cod_respuesta'], $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
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

        $data = array(
            'COD_ESTADO' => "30",
        );
        $this->db->where('COD_RESOLUCION', $post['post']['id']);
        $this->db->update('RESOLUCION', $data);

        $this->db->set("COD_RESOLUCION", $post['post']['id']);
        $this->db->set("COMENTARIOS", "ACTA NOTIFICACION FIRMADA");
        $this->db->set("RESPONSABLE", $post['user']->IDUSUARIO);
        $this->db->insert('GESTIONRESOLUCION');
        trazar("30", "41", $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
    }

    function info_liquidacion($id) {//funcion para obtener los datos de la liquidacion por empresa
        $this->db->select("TO_CHAR(FECHA_INICIO, 'DD/MM/YYYY') AS FECHA_INICIO", FALSE);
        $this->db->select("TO_CHAR(FECHA_FIN, 'DD/MM/YYYY') AS FECHA_FIN", FALSE);
        $this->db->select("TO_CHAR(FECHA_LIQUIDACION, 'DD/MM/YYYY') AS FECHA_LIQUIDACION", FALSE);
        $this->db->select("SALDO_DEUDA,NOMBRE_CONCEPTO as NOMBRECONCEPTO,NUM_LIQUIDACION,COD_CONCEPTO,"
                . "NITEMPRESA,TOTAL_LIQUIDADO,TOTAL_INTERESES,COD_TASA_INTERES,COD_FISCALIZACION");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->where("LIQUIDACION.BLOQUEADA", "0");
        $this->db->where("LIQUIDACION.EN_FIRME", "S");
        $this->db->where("COD_FISCALIZACION", $id);
        $datos = $this->db->get('LIQUIDACION');
        if (!empty($datos->result_array)):
            return $datos->result_array[0];
        else : return NULL;
        endif;
    }

    function consultar($id = NULL) {// funcion para consultar todos los datos de la resolucion por usuario
        $this->db->select("MUNICIPIO.NOMBREMUNICIPIO,RESOLUCION.COD_RESOLUCION,RESOLUCION.NUMERO_RESOLUCION,RESOLUCION.COD_ESTADO,RESOLUCION.NUM_RECURSO,RESOLUCION.NOMBRE_ARCHIVO_RESOLUCION,RESOLUCION.FECHA_CREACION,RESOLUCION.COD_REGIONAL,"
                . "NOMBRE_EMPLEADOR,RESOLUCION.COD_FISCALIZACION,RESOLUCION.NITEMPRESA,RESOLUCION.NUM_LIQUIDACION,RESOLUCION.FECHA_LIQUIDACION,RESOLUCION.PERIODO_INICIAL,RESOLUCION.PERIODO_FINAL,RESOLUCION.VALOR_TOTAL,RESOLUCION.VALOR_LETRAS,RESOLUCION.ELABORO,"
                . "RESOLUCION.REVISO, RESOLUCION.ABOGADO,RESOLUCION.COORDINADOR, RESOLUCION.DIRECTOR_REGIONAL,FECHA_ACTUAL,NUM_TRASLADO_ALEGATO,NUMERO_CITACION,OBSERVACIONES,COD_PLANTILLA,"
                . "APROBADA,FECHA_APROBACION,OBSERVACIONES_APROBACION,RESPONSABLE,DOCUMENTO_FIRMADO,RUTA_DOCUMENTO_FIRMADO,CUOTA,NUM_RESOLUCION_CUOTA,"
                . "FECHA_EJECUTORIA_CUOTA,FECHA_EXPEDICION_CUOTA,DECISION_RESO_CONTRATO,TEXTO_RESOLUCION,RESOLUCION.COD_CPTO_FISCALIZACION,"
                . "NOMBRE_CONCEPTO,REGIONAL.COD_CIUDAD,REGIONAL.DIRECCION_REGIONAL,REGIONAL.NOMBRE_REGIONAL,USUARIOS.APELLIDOS,USUARIOS.NOMBRES,"
                . "TIPOGESTION.TIPOGESTION,EMPRESA.AUTORIZA_NOTIFIC_EMAIL,EMPRESA.FAX,EMPRESA.DIRECCION,EMPRESA.TELEFONO_FIJO,"
                . "EMPRESA.DIRECCION,EMPRESA.NOMBRE_EMPRESA,EMPRESA.REPRESENTANTE_LEGAL,EMPRESA.CORREOELECTRONICO,REGIONAL.CEDULA_COORDINADOR_RELACIONES,"
                . "USER1.NOMBRES || ' ' || USER1.APELLIDOS COORDINADOR_REGIONAL,''  NOMBRE_DIRECTOR", false);
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=RESOLUCION.COD_CPTO_FISCALIZACION");
        $this->db->join("USUARIOS", "USUARIOS.IDUSUARIO=RESOLUCION.ABOGADO");
        $this->db->join("LIQUIDACION", "LIQUIDACION.COD_FISCALIZACION=RESOLUCION.COD_FISCALIZACION AND LIQUIDACION.SALDO_DEUDA>0");
        $this->db->join("FISCALIZACION", "FISCALIZACION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("TIPOGESTION", "TIPOGESTION.COD_GESTION=RESOLUCION.COD_ESTADO");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=RESOLUCION.COD_REGIONAL");
        $this->db->join("USUARIOS USER1", 'REGIONAL.CEDULA_COORDINADOR_RELACIONES=USER1.IDUSUARIO', false);
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=RESOLUCION.NITEMPRESA");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO", "LEFT");
        $this->db->where("LIQUIDACION.BLOQUEADA", "0");
        $this->db->where("LIQUIDACION.EN_FIRME", "S");
        $this->db->where("FISCALIZACION.COD_TIPOGESTION <>", "309");
        if (!empty($id)) {
            $this->db->where("COD_RESOLUCION", $id);
        } else {
            if ($this->permiso == 1)
                $this->db->where("RESOLUCION.DIRECTOR_REGIONAL", $this->iduser);
            if ($this->permiso == 2)
                $this->db->where("RESOLUCION.ABOGADO", $this->iduser);
            if ($this->permiso == 3)
                $this->db->where("RESOLUCION.COORDINADOR", $this->iduser);
            $num = $this->array_num;
            $this->db->where_in("RESOLUCION.COD_ESTADO", $num, FALSE);
        }

        $dato = $this->db->get("RESOLUCION");
        $this->set_nit((!empty($dato->result_array[0]['NITEMPRESA'])) ? $dato->result_array[0]['NITEMPRESA'] : "");
        $this->set_municipio((!empty($dato->result_array[0]['COD_CIUDAD'])) ? $dato->result_array[0]['COD_CIUDAD'] : "");
        return $datos = $dato->result_array;
    }

    function num_fisca($id) {
        $this->db->select("count(RESOLUCION.COD_FISCALIZACION) CANTIDAD");
        $this->db->join('RESOLUCION','RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION');
        $this->db->where('RESOLUCION.COD_RESOLUCION',$id);
        $this->db->group_by('RESOLUCION.COD_FISCALIZACION');
        $dato=$this->db->get('LIQUIDACION');
        return $datos = $dato->result_array;
    }

    function solucion_recursos_conculta($id = NULL) {// funcion para consultar todos los datos de la resolucion por usuario
        $this->db->select("RESOLUCION.COD_RESOLUCION,RESOLUCION.NUMERO_RESOLUCION,RESOLUCION.COD_ESTADO,RESOLUCION.NOMBRE_ARCHIVO_RESOLUCION,RESOLUCION.FECHA_CREACION,RESOLUCION.COD_REGIONAL,"
                . "NOMBRE_EMPLEADOR,RESOLUCION.NITEMPRESA,RESOLUCION.COD_FISCALIZACION,RESOLUCION.NUM_LIQUIDACION,RESOLUCION.FECHA_LIQUIDACION,RESOLUCION.PERIODO_INICIAL,RESOLUCION.PERIODO_FINAL,RESOLUCION.VALOR_TOTAL,RESOLUCION.VALOR_LETRAS,RESOLUCION.ELABORO,"
                . "RESOLUCION.NUM_RECURSO,RESOLUCION.REVISO, RESOLUCION.ABOGADO,RESOLUCION.COORDINADOR,RESOLUCION.DIRECTOR_REGIONAL,FECHA_ACTUAL,NUM_TRASLADO_ALEGATO,NUMERO_CITACION,OBSERVACIONES,COD_PLANTILLA,"
                . "APROBADA,FECHA_APROBACION,OBSERVACIONES_APROBACION,RESPONSABLE,DOCUMENTO_FIRMADO,RUTA_DOCUMENTO_FIRMADO,CUOTA,NUM_RESOLUCION_CUOTA,"
                . "FECHA_EJECUTORIA_CUOTA,FECHA_EXPEDICION_CUOTA,DECISION_RESO_CONTRATO,TEXTO_RESOLUCION,RESOLUCION.COD_CPTO_FISCALIZACION,"
                . "NOMBRE_CONCEPTO,REGIONAL.COD_CIUDAD,REGIONAL.DIRECCION_REGIONAL,REGIONAL.NOMBRE_REGIONAL,USUARIOS.APELLIDOS,USUARIOS.NOMBRES,"
                . "TIPOGESTION.TIPOGESTION,EMPRESA.AUTORIZA_NOTIFIC_EMAIL,EMPRESA.FAX,EMPRESA.DIRECCION,EMPRESA.TELEFONO_FIJO,EMPRESA.DIRECCION,EMPRESA.NOMBRE_EMPRESA,EMPRESA.REPRESENTANTE_LEGAL,EMPRESA.CORREOELECTRONICO,REGIONAL.CEDULA_COORDINADOR_RELACIONES,REGIONAL.COORDINADOR_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=RESOLUCION.COD_CPTO_FISCALIZACION");
        $this->db->join("USUARIOS", "USUARIOS.IDUSUARIO=RESOLUCION.ABOGADO");
        $this->db->join("TIPOGESTION", "TIPOGESTION.COD_GESTION=RESOLUCION.COD_ESTADO");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=RESOLUCION.COD_REGIONAL");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=RESOLUCION.NITEMPRESA");
        $this->db->join("CITACION", "CITACION.NUM_CITACION=RESOLUCION.NUMERO_CITACION");
        if (!empty($id)) {
            $this->db->where("COD_RESOLUCION", $id);
        } else {
            $this->db->where("(RESOLUCION.DIRECTOR_REGIONAL", $this->iduser);
            $this->db->or_where("RESOLUCION.ABOGADO", $this->iduser);
            $this->db->or_where("RESOLUCION.COORDINADOR", $this->iduser . ")", false);
            $num = $this->array_num;
            $this->db->where_in("RESOLUCION.COD_ESTADO", $num, FALSE);
        }

        $dato = $this->db->get("RESOLUCION");
        $this->set_nit((!empty($dato->result_array[0]['NITEMPRESA'])) ? $dato->result_array[0]['NITEMPRESA'] : "");
        $this->set_municipio((!empty($dato->result_array[0]['COD_CIUDAD'])) ? $dato->result_array[0]['COD_CIUDAD'] : "");
        return $datos = $dato->result_array;
    }

    function conceptosfiscalizacion($concepto) {
        $this->db->select("COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO");
        $this->db->where("COD_CPTO_FISCALIZACION", $concepto);
        $dato = $this->db->get("CONCEPTOSFISCALIZACION");
        return $datos = $dato->result_array[0];
    }

    function municipio($id) { //funcion para optener el municipio de la empresa
        $this->db->select("NOMBREMUNICIPIO");
        $this->db->where("CODMUNICIPIO", (!empty($id)) ? $id : $this->cod_municipio);
        $dato = $this->db->get("MUNICIPIO");
        if (!empty($dato->result_array[0])):
            return $dato->result_array[0];
        endif;
    }

    function confirmar_resolucion($id) {
        $this->db->select("FECHA_CREACION,NUMERO_RESOLUCION");
        $this->db->where("NUMERO_RESOLUCION", $id);
        $dato = $this->db->get("RESOLUCION");
        $dato = $dato->row();
        return (!empty($dato)) ? $dato : "";
    }

    function proyecto($id) {
        $this->db->select("APELLIDOS,NOMBRES");
        $this->db->where("IDUSUARIO", $id);
        $dato = $this->db->get("USUARIOS");
        return $dato->result_array[0];
    }

    function guardar_modificacion($post, $id_user) {
        $data = array(
            'COD_ESTADO' => "23",
        );
        $this->db->where('COD_RESOLUCION', $post['id']);
        $this->db->update('RESOLUCION', $data);

        $this->db->set("COD_RESOLUCION", $post['id']);
        $this->db->set("COMENTARIOS", "SE REALIZARON CORRECCIONES EN LA RESOLUCION");
        $this->db->set("RESPONSABLE", $id_user->IDUSUARIO);
        $this->db->insert('GESTIONRESOLUCION');
        trazar("23", "33", $post['cod_fis'], $post['nit'], $cambiarGestionActual = 'S', '-1', $comentarios = "SE REALIZARON CORRECCIONES EN LA RESOLUCION");
        return $this->comentario($post['id']);
    }

    function mostrar_coordinador($id) {
        $this->db->select('CEDULA_COORDINADOR_RELACIONES as CEDULA');
        $this->db->where("CEDULA_COORDINADOR_RELACIONES", $id);
        $dato = $this->db->get("REGIONAL");
        if (!empty($dato->result_array[0]))
            return $dato->result_array[0];
        else {
            $dato->result_array[0]['CEDULA'] = "";
            return $dato->result_array[0];
        }
    }

    function mostrar_director($id) {
        $this->db->select('CEDULA_DIRECTOR as CEDULA');
        $this->db->where("CEDULA_DIRECTOR", $id);
        $dato = $this->db->get("REGIONAL");
        if (!empty($dato->result_array[0]))
            return $dato->result_array[0];
        else {
            $dato->result_array[0]['CEDULA'] = "";
            return $dato->result_array[0];
        }
    }

    function mostrar_abogados($id) {
        $this->db->select("USUARIOS.IDUSUARIO as CEDULA");
        $this->db->join("USUARIOS_GRUPOS", "USUARIOS.IDUSUARIO=USUARIOS_GRUPOS.IDUSUARIO");
        $this->db->join("GRUPOS", "USUARIOS_GRUPOS.IDGRUPO=GRUPOS.IDGRUPO");
        $this->db->where("GRUPOS.IDGRUPO", "44");
        $this->db->where("USUARIOS.IDUSUARIO", $id);
        $dato = $this->db->get("USUARIOS");
        if (!empty($dato->result_array[0]))
            return $dato->result_array[0];
        else {
            $dato->result_array[0]['CEDULA'] = "";
            return $dato->result_array[0];
        }
    }

    function comentario($id) {
        //obtener lo valores de los comentarios de la resolucion guardada
        $this->db->select('COMENTARIOS,FECHA_MODIFICACION');
        $this->db->where("COD_RESOLUCION", $id);
        $this->db->order_by("FECHA_MODIFICACION", "DESC");
        $dato = $this->db->get("GESTIONRESOLUCION");
        $datos = $dato->result_array;
        $comentarios = "";
        for ($i = 0; $i < count($datos); $i++) {
            $comentarios.=$datos[$i]['COMENTARIOS'] . "<br> Fecha: " . $datos[$i]['FECHA_MODIFICACION'] . " <hr> ";
        }
        return $comentarios;
    }

    function resolucion_guardar($post, $id_user) {

        $data = array(
            'COD_ESTADO' => $post['estado']
        );
        $this->db->where('COD_RESOLUCION', $post['id']);
        $this->db->update('RESOLUCION', $data);

        $this->db->set("COD_RESOLUCION", $post['id']);
        $this->db->set("COMENTARIOS", $post['correcciones']);
        $this->db->set("RESPONSABLE", $id_user->IDUSUARIO);
        $this->db->insert('GESTIONRESOLUCION');
        trazar($post['estado'], $post['respuesta'], $post['cod_fis'], $post['nit'], $cambiarGestionActual = 'S', '-1', $post['correcciones']);
        return $this->comentario($post['id']);
    }

    function subir_archivo($post, $file, $id_user) {
        $this->db->set("RUTA_DOCUMENTO_FIRMADO", $file['upload_data']['file_name']);
        $this->db->set("COD_ESTADO", CITACION1);
        $this->db->set("NUMERO_RESOLUCION", $post['num_resolu']);
        $this->db->set("NOMBRE_ARCHIVO_RESOLUCION", "Resolucion " . $post['num_resolu']);
        $this->db->set("FECHA_CREACION", "TO_DATE('" . $post['nom_resolu'] . "','DD/MM/RR HH24:mi:ss')", FALSE);

        $this->db->where('COD_RESOLUCION', $post['id']);
        $this->db->update('RESOLUCION');
        trazar(CITACION1, "40", $post['cod_fis'], $post['nit'], $cambiarGestionActual = 'S', $comentarios = "");

        $this->db->set('FECHA_RESOLUCION', FECHA, false);
        $this->db->set('COD_TIPOPROCESO', '3');
        $this->db->where("COD_FISCALIZACION", $post['cod_fis']);
        $this->db->update('LIQUIDACION');

        $this->db->set('COD_TIPOGESTION', CITACION1);
        $this->db->set('FIN_FISCALIZACION', 'S');
        $this->db->where("COD_FISCALIZACION", $post['cod_fis']);
        $this->db->update('FISCALIZACION');



        return $this->gestion_resolucion2($post['id'], $id_user->IDUSUARIO);
    }

    function gestion_resolucion2($id_resolu, $id_user) {
        $this->db->set('COD_RESOLUCION', $id_resolu);
        $this->db->set('RESPONSABLE', $id_user);
        $this->db->set("COMENTARIOS", "RESOLUCION FIRMADA");
        $this->db->insert('GESTIONRESOLUCION');
    }

    function guardar_citacion($post) {
        if ($post['post']['radio'] == "33") {
            $cod_cita = "1";
            $cod_siguiente = "313";
//            $cod_siguiente = "36";
            $cod_respuesta = "1421";
        } else if ($post['post']['radio'] == "34") {
            $cod_cita = "2";
//            $cod_siguiente = "84";
            $cod_siguiente = "313";
            $cod_respuesta = "35";
        } else if ($post['post']['radio'] == "35") {
            $cod_cita = "3";
//            $cod_siguiente = "30";
            $cod_siguiente = "313";
            $cod_respuesta = "31";
        } else if ($post['post']['radio'] == "310") {
            $cod_cita = "3";
//            $cod_siguiente = "30";
            $cod_siguiente = "560";
            $cod_respuesta = "1378";
        } else if ($post['post']['radio'] == "311") {
            $cod_cita = "3";
            $cod_siguiente = "561";
            $cod_respuesta = "1379";
        } else if ($post['post']['radio'] == "411") {
            $cod_cita = "3";
            $cod_siguiente = "411";
            $cod_respuesta = "1083";
        } else if ($post['post']['radio'] == "414") {
            $cod_cita = "3";
            $cod_siguiente = "415";
            $cod_respuesta = "884";
        } else if ($post['post']['radio'] == "415") {
            $cod_cita = "3";
            $cod_siguiente = "416";
            $cod_respuesta = "884";
        }
        $this->db->set("COD_RESOLUCION", $post['post']['cod_resolucion']);
        $this->db->set("COD_TIPO_CITACION", $cod_cita);
        $this->db->set("COD_PLANTILLA_CITACION", $post['post']['cod_planilla_citacion']);
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
//        echo "<pre>";
//        print_r($post);
        $this->db->insert('CITACION');

        $this->db->select('MAX(NUM_CITACION) as MAS', false);
        $this->db->where('COD_RESOLUCION', $post['post']['cod_resolucion']);
        $dato = $this->db->get('CITACION');

//        $this->db->select('NUM_CITACION');
//        $this->db->where("COD_RESOLUCION", $post['post']['cod_resolucion']);
//        $this->db->where("FECHA_ENVIO_CITACION", "to_date('" . date("d/m/Y") . "','DD/MM/RR')", false);
//        $dato = $this->db->get("CITACION");

        $this->db->set("NUMERO_CITACION", $dato->result_array[0]['MAS']);
        $this->db->set("FECHA_ACTUAL", FECHA, false);
        $this->db->where('COD_RESOLUCION', $post['post']['cod_resolucion']);
        $this->db->update('RESOLUCION');
        trazar($cod_siguiente, $cod_respuesta, $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
        $this->actualizar_estado($cod_siguiente, $post['post']['cod_resolucion'], $post['user']->IDUSUARIO);
    }

    function id_recurso($id) {
        $this->db->select('NUM_RECURSO');
        $this->db->where("COD_RESOLUCION", $id);
        $dato = $this->db->get("RESOLUCION");
        return $datos = $dato->result_array;
    }

    function citacion($id) {
        $this->db->select('TIPOGESTION');
        $this->db->where("NUM_CITACION", $id);
        $dato = $this->db->get("CITACION");
        return $datos = $dato->result_array[0]['TIPOGESTION'];
    }

    function ver_resolucion_recurso($id) {
        $this->db->select('RESOLUCIONRECURSO.NOM_DOCU_RESOL_RECURSO,RESOLUCIONRECURSO.NUM_RECURSO');
        $this->db->join('RESOLUCION', 'RESOLUCION.NUM_RECURSO=RESOLUCIONRECURSO.NUM_RECURSO');
        $this->db->where("RESOLUCION.COD_RESOLUCION", $id);
        $dato = $this->db->get("RESOLUCIONRECURSO");
        return $datos = $dato->result_array[0];
    }

    function update_resolucion_hija($post) {
        $data = array(
            'COD_ESTADO' => $post['post']['cod_siguiente']
        );
        $this->db->where('COD_RESOLUCION', $post['post']['id']);
        $this->db->update('RESOLUCION', $data);

        $this->db->set('COD_RESOLUCION', $post['post']['id']);
        $this->db->set('RESPONSABLE', $post['user']->IDUSUARIO);
        $this->db->set("COMENTARIOS", $post['post']['contenido']);
        $this->db->set("GESTION", "1");
        $this->db->insert('GESTIONRESOLUCION');

        trazar("48", "79", $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', '-1', $post['post']['contenido']);
    }

    function guardar_recurso($post) {
        $this->db->set('NUM_RECURSO', $post['recurso'][0]['NUM_RECURSO']);
        $this->db->set('NOM_DOCU_RESOL_RECURSO', $post['post']['nombre_archivo']);
        $this->db->set("FECHA_DOCUMENTO", FECHA, false);
        $this->db->set("FECHA_RECIBO_DOCU_FIRMADO", FECHA, false);
        $this->db->set("GENERADO_POR", $post['user']->IDUSUARIO);
        $this->db->insert('RESOLUCIONRECURSO');


        $data = array(
            'COD_ESTADO' => '314',
            'NUM_LIQUIDACION' => $post['post']['num_liquidacion'],
            'VALOR_TOTAL' => $post['post']['nuevo_valor'],
            'VALOR_LETRAS' => $post['post']['letras']
        );
        $this->db->where('COD_RESOLUCION', $post['post']['id']);
        $this->db->update('RESOLUCION', $data);

        $this->db->set('COD_RESOLUCION', $post['post']['id']);
        $this->db->set('RESPONSABLE', $post['user']->IDUSUARIO);
        $this->db->set("COMENTARIOS", "PRESENTAR RECURSO");
        $this->db->insert('GESTIONRESOLUCION');
    }

    function update_recurso($post) {


        $this->db->where('NUM_RECURSO', $post['recurso'][0]['NUM_RECURSO']);
        $this->db->set('NOM_DOCU_RESOL_RECURSO', $post['post']['nombre_archivo']);
        $this->db->set("FECHA_DOCUMENTO", FECHA, false);
        $this->db->set("FECHA_RECIBO_DOCU_FIRMADO", FECHA, false);
        $this->db->set("GENERADO_POR", $post['user']->IDUSUARIO);
        $this->db->update('RESOLUCIONRECURSO');

        if ($this->db->affected_rows() == '0') {
            $this->db->set('NUM_RECURSO', $post['recurso'][0]['NUM_RECURSO']);
            $this->db->set('NOM_DOCU_RESOL_RECURSO', $post['post']['nombre_archivo']);
            $this->db->set("FECHA_DOCUMENTO", FECHA, false);
            $this->db->set("FECHA_RECIBO_DOCU_FIRMADO", FECHA, false);
            $this->db->set("GENERADO_POR", $post['user']->IDUSUARIO);
            $this->db->insert('RESOLUCIONRECURSO');
        }

        $data = array(
            'COD_ESTADO' => '314'
        );
        $this->db->where('COD_RESOLUCION', $post['post']['id']);
        $this->db->update('RESOLUCION', $data);

        $this->db->set('COD_RESOLUCION', $post['post']['id']);
        $this->db->set('RESPONSABLE', $post['user']->IDUSUARIO);
        $this->db->set("COMENTARIOS", "PRESENTAR RECURSO");
        $this->db->insert('GESTIONRESOLUCION');
    }

    function gestion_resolucion($id_resolu, $id_user) {
        $this->db->set('COD_RESOLUCION', $id_resolu);
        $this->db->set('RESPONSABLE', $id_user);
        $this->db->set("COMENTARIOS", "CITACION RESOLUCION");
        $this->db->insert('GESTIONRESOLUCION');
    }

    function Recurso_ejecutoria($post) {
        $respu = trazar("51", "86", $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
        $data = array(
            'COD_ESTADO' => '51',
            'COD_GESTION_COBRO' => $respu['COD_GESTION_COBRO']
        );
        $this->db->where('COD_RESOLUCION', $post['post']['id']);
        $this->db->update('RESOLUCION', $data);

        $this->db->set('COD_RESOLUCION', $post['post']['id']);
        $this->db->set('RESPONSABLE', $post['user']->IDUSUARIO);
        $this->db->set("COMENTARIOS", "REALIZAR EJECUTORIA");
        $this->db->insert('GESTIONRESOLUCION');
        trazar("51", "86", $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
    }

    function Recurso_resolucion($post) {

        $this->db->set("NUM_RADICADO", $post['post']['num_radicado']);
        $this->db->set("COD_RESOLUCION", $post['post']['id_resolucion']);
        $this->db->set("TIPO_DOCUMENTO", $post['post']['documento']);
        $this->db->set("NUM_DOCUMENTO", $post['post']['num_iden']);
        $this->db->set("FECHA_RECURSO", FECHA, false);
        $this->db->set("NOMBRE_PERSONA_PRESENTA", $post['post']['nombre']);
        $this->db->set("OBSERVACIONES", $post['post']['nis']);
        $this->db->insert('RECURSO');



        $this->db->select('NUM_RECURSO');
        $this->db->where('FECHA_RECURSO', FECHA, false);
        $this->db->where('NUM_RADICADO', $post['post']['num_radicado']);
        $this->db->where('COD_RESOLUCION', $post['post']['id_resolucion']);
        $dato = $this->db->get('RECURSO');
        $datos = $dato->result_array;



        foreach ($post['post']['docuemtos'] as $docuemtos) {
            $this->db->set("NUM_RECURSO", $datos[0]['NUM_RECURSO']);
            $this->db->set("NOMBRE_DOCUMENTO", $docuemtos);
            $this->db->set("FECHA_CARGA", FECHA, false);
            $this->db->insert('DOCUMENTOSRECURSO');
        }


        $this->db->set("COD_RESOLUCION", $post['post']['id_resolucion']);
        $this->db->set("COMENTARIOS", "RESOLUCION CON RECURSOS");
        $this->db->set("RESPONSABLE", $post['user']->IDUSUARIO);
        $this->db->insert('GESTIONRESOLUCION');
//        RESOLUCIONRECURSO
        $data = array(
            'COD_ESTADO' => "47",
            'NUM_RECURSO' => $datos[0]['NUM_RECURSO']
        );
        $this->db->where('COD_RESOLUCION', $post['post']['id_resolucion']);
        $this->db->update('RESOLUCION', $data);
        trazar('47', "78", $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
    }

    function desbloquear($post) {
        if ($post['post']['gestion'] == 413)
            $cod_siguiente = "416";
        else
            $cod_siguiente = "37";
        $this->resolucion_actualizar($post, $cod_siguiente);
        $comentario = "DESBLOQUEO PORQUE EL CLIENTE SE PRESENTO";
        $this->detalle_resolcion($post, $comentario);
        trazar($cod_siguiente, "55", $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
    }

    function devolver_a_citacion($post) {
        $data = array(
            'COD_ESTADO' => "29",
        );
        $this->db->where('COD_RESOLUCION', $post['post']['id']);
        $this->db->update('RESOLUCION', $data);

        $data = array(
            'FAVORABILIDAD' => "1",
        );
        $this->db->where('NUM_RECURSO', $post['post']['num_recurso']);
        $this->db->update('RESOLUCIONRECURSO', $data);

        $this->db->set("COD_RESOLUCION", $post['post']['id']);
        $this->db->set("COMENTARIOS", "DEVOLUCION POR FAVORABILIDAD AL SENA");
        $this->db->set("RESPONSABLE", $post['user']->IDUSUARIO);
        $this->db->insert('GESTIONRESOLUCION');
    }

    function gestion_citacion($id) {
        $this->db->select('CITACION.TIPOGESTION');
        $this->db->join("RESOLUCION", "RESOLUCION.NUMERO_CITACION=CITACION.NUM_CITACION");
        $this->db->where('CITACION.COD_RESOLUCION', $id);
        $this->db->order_by("CITACION.TIPOGESTION", "DESC");
        $dato = $this->db->get('CITACION');
        return $datos = $dato->result_array;
    }

    function siguiente_codigo_del_bolqueo($post) {

        $tipogestion = $this->gestion_citacion($post['post']['id']);
        if ($tipogestion[0]['TIPOGESTION'] == '33') {
            if ($post['consulta'][0]['AUTORIZA_NOTIFIC_EMAIL'] == "s" || $post['consulta'][0]['AUTORIZA_NOTIFIC_EMAIL'] == "S") {
                $cod_siguiente = "34";
                $cod_respuesta = "49";
            } else {
                $cod_siguiente = "35";
                $cod_respuesta = "52";
            }
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
        $this->resolucion_actualizar($post, $cod_siguiente);
        $comentario = "BLOQUEO PORQUE EL CLIENTE NO SE PRESENTO";
        $this->detalle_resolcion($post, $comentario);
        trazar($cod_siguiente, $cod_respuesta, $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
    }

    function resolucion_actualizar($post, $cod) {
        $this->db->set("COD_ESTADO", $cod);
        $this->db->set("FECHA_ACTUAL", FECHA, false);
        $this->db->where('COD_RESOLUCION', $post['post']['id']);
        $this->db->update('RESOLUCION');
    }

    function detalle_resolcion($post, $comentario) {
        $this->db->set("COD_RESOLUCION", $post['post']['id']);
        $this->db->set("COMENTARIOS", $comentario);
        $this->db->set("RESPONSABLE", $post['user']->IDUSUARIO);
        $this->db->insert('GESTIONRESOLUCION');
    }

    function documentacion_resolcuion($id) {
//        resolcuion citacion RECURSO DOCUMENTOSRECURSO H
        $this->db->select('RUTA_DOCUMENTO_FIRMADO,NUMERO_RESOLUCION,NOMBRE_ARCHIVO_RESOLUCION');
        $this->db->where('COD_RESOLUCION', $id);
        $dato = $this->db->get('RESOLUCION');
        return $datos = $dato->result_array;
    }

    function documentacion_citacion($id) {
//        citacion
        $this->db->select('DOCUMENTO_GENERADO,TIPOGESTION.TIPOGESTION');
        $this->db->join('TIPOGESTION', 'TIPOGESTION.COD_GESTION=CITACION.TIPOGESTION');
        $this->db->where('COD_RESOLUCION', $id);
        $dato = $this->db->get('CITACION');
        return $datos2 = $dato->result_array;
    }

    function documentacion_recurso($id) {
//        RECURSO
        $this->db->select('NUM_RECURSO');
        $this->db->where('COD_RESOLUCION', $id);
        $dato = $this->db->get('RECURSO');
        return $datos3 = $dato->result_array;
    }

    function correspondencia($id) {
        $this->db->select('CORRESPONDENCIACITACION.NOMBRE_RECEPTOR,CORRESPONDENCIACITACION.NUM_COLILLA,CORRESPONDENCIACITACION.RUTA_COLILLA,
CORRESPONDENCIACITACION.FECHA_DEVOLUCION,CORRESPONDENCIACITACION.FECHA_RECEPCION');
        $this->db->join('CITACION', 'CITACION.NUM_CITACION=CORRESPONDENCIACITACION.NUM_CITACION');
        $this->db->join('RESOLUCION', 'RESOLUCION.COD_RESOLUCION=CITACION.COD_RESOLUCION');
        $this->db->where('RESOLUCION.COD_RESOLUCION', $id);
        $dato = $this->db->get('CORRESPONDENCIACITACION');
        return $datos3 = $dato->result_array;
    }

    function documentacion_DOCUMENTOSRECURSO($id) {
        //DOCUMENTOSRECURSO
        $this->db->select('NOMBRE_DOCUMENTO');
        $this->db->where('NUM_RECURSO', $id);
        $dato = $this->db->get('DOCUMENTOSRECURSO');
        return $datos4 = $dato->result_array;
    }

    function documentacion_RESOLUCIONRECURSO($id) {
        //RESOLUCIONRECURSO
        $this->db->select('NOM_DOCU_RESOL_RECURSO,NOMBRE_DOCU_FIRMADO');
        $this->db->where('NUM_RECURSO', $id);
        $dato = $this->db->get('RESOLUCIONRECURSO');
        return $datos5 = $dato->result_array;
    }

    function plantilla($id) {
        $this->db->select('ARCHIVO_PLANTILLA');
        $this->db->where('CODPLANTILLA', $id);
        $dato = $this->db->get('PLANTILLA');
        return $datos5 = $dato->result_array['0']['ARCHIVO_PLANTILLA'];
    }

    function detalle_aportes($id_liquidacion) {
        $this->db->select('LAP_D.ANO,LAP_D.VALORSUELDOS,LAP_D.VALORSOBRESUELDOS,LAP_D.SALARIOINTEGRAL,LAP_D.SALARIOESPECIE,LAP_D.SUPERNUMERARIOS,
            LAP_D.JORNALES,LAP_D.AUXILIOTRANSPORTE,LAP_D.HORASEXTRAS,LAP_D.DOMINICALES_FESTIVOS,LAP_D.RECARGONOCTURNO,LAP_D.VIATICOS,
            LAP_D.BONIFICACIONES,LAP_D.COMISIONES,LAP_D.POR_SOBREVENTAS,LAP_D.VACACIONES,LAP_D.TRAB_DOMICILIO,LAP_D.PRIMA_TEC_SALARIAL,
            LAP_D.AUXILIO_ALIMENTACION,LAP_D.PRIMA_SERVICIO,LAP_D.PRIMA_LOCALIZACION,LAP_D.PRIMA_VIVIENDA,LAP_D.GAST_REPRESENTACION,
            LAP_D.PRIMA_ANTIGUEDAD,LAP_D.PRIMA_EXTRALEGALES,LAP_D.PRIMA_VACACIONES,LAP_D.PRIMA_NAVIDAD,LAP_D.CONTRATOS_AGRICOLAS,
            LAP_D.REMU_SOCIOS_INDUSTRIALES,LAP_D.HORA_CATEDRA,LAP_D.OTROS_PAGOS');
        $this->db->join('LIQ_APORTESPARAFISCALES LAP', 'LAP.CODLIQUIDACIONAPORTES_P=L.NUM_LIQUIDACION');
        $this->db->join('LIQ_APORTESPARAFISCALES_DET LAP_D', 'LAP_D.CODLIQUIDACIONAPORTES_P=LAP.CODLIQUIDACIONAPORTES_P');
        $this->db->where('LAP_D.CODLIQUIDACIONAPORTES_P', $id_liquidacion);
        $dato = $this->db->get('LIQUIDACION L');
        return $datos5 = $dato->result_array;
    }

    function detalle_fic($id_liquidacion) {
        $this->db->select('L.NUM_LIQUIDACION,LFP.VLR_CONTRATO_TODOCOSTO,LFP.VLR_CONTRATO_MANO_OBRA,
            LFP.PAGOS_FIC_DESCONTAR,LFP.COD_LIQ_PRESUNTIVA,LFN.ANO,LFN.NRO_TRABAJADORES,LFN.TOTAL_ANO,LFN.MESCOBRO');
        $this->db->join('LIQUIDACION_FIC LF', 'LF.CODLIQUIDACIONFIC=L.NUM_LIQUIDACION');
        $this->db->join('LIQ_FIC_NORMATIVA LFN', 'LFN.CODLIQUIDACIONFIC=L.NUM_LIQUIDACION', 'LEFT');
        $this->db->join('LIQ_FIC_PRESUNTIVA LFP', 'LFP.CODLIQUIDACIONFIC=L.NUM_LIQUIDACION', 'LEFT');
        $this->db->where('L.NUM_LIQUIDACION', $id_liquidacion);
        $dato = $this->db->get('LIQUIDACION L');
        return $datos5 = $dato->result_array;
    }

    function recurso_reliquidacion() {
        $post = $this->input->post();
        $this->db->select('NUM_LIQUIDACION,COD_CONCEPTO,NITEMPRESA,FECHA_INICIO,FECHA_FIN,FECHA_LIQUIDACION,TOTAL_LIQUIDADO,TOTAL_INTERESES,
COD_TASA_INTERES,BLOQUEADA,FECHA_VENCIMIENTO,SALDO_DEUDA,COD_TIPOPROCESO,FECHA_RESOLUCION,FECHA_EJECUTORIA,COD_FISCALIZACION,COD_CARTERANOMISIONAL,
EN_FIRME,TOTAL_CAPITAL,SALDO_CAPITAL,SALDO_INTERES');
        $this->db->where('COD_FISCALIZACION', $post['cod_fis']);
        $this->db->where('BLOQUEADA', 0);
        $dato = $this->db->get('LIQUIDACION');
        $datos5 = $dato->result_array;

//        print_r($datos5);
        $this->db->set('BLOQUEADA', '1');
        $this->db->where('COD_FISCALIZACION', $post['cod_fis']);
        $this->db->update('LIQUIDACION');
        
        $this->db->set('REVISO', '1');
        $this->db->where('COD_FISCALIZACION', $post['cod_fis']);
        $this->db->update('RESOLUCION');


        $SQL = "INSERT INTO LIQUIDACION (NUM_LIQUIDACION,COD_CONCEPTO,NITEMPRESA,FECHA_INICIO,FECHA_FIN,FECHA_LIQUIDACION,TOTAL_LIQUIDADO,TOTAL_INTERESES,
COD_TASA_INTERES,BLOQUEADA,FECHA_VENCIMIENTO,SALDO_DEUDA,COD_TIPOPROCESO,FECHA_RESOLUCION,FECHA_EJECUTORIA,COD_FISCALIZACION,COD_CARTERANOMISIONAL,
EN_FIRME,TOTAL_CAPITAL,SALDO_CAPITAL,SALDO_INTERES) VALUES ('" . $datos5[0]["NUM_LIQUIDACION"] . "1','" . $datos5[0]["COD_CONCEPTO"] . "','" . $datos5[0]["NITEMPRESA"]
        . "','" . $datos5[0]["FECHA_INICIO"] . "','" . $datos5[0]["FECHA_FIN"] . "','" . $datos5[0]["FECHA_LIQUIDACION"] . "','" . $datos5[0]["TOTAL_LIQUIDADO"]
        . "','" . $datos5[0]["TOTAL_INTERESES"]
        . "','" . $datos5[0]["COD_TASA_INTERES"] . "','" . $datos5[0]["BLOQUEADA"] . "','" . $datos5[0]["FECHA_VENCIMIENTO"] . "','" . $datos5[0]["SALDO_DEUDA"]
        . "','5','" . $datos5[0]["FECHA_RESOLUCION"] . "','" . $datos5[0]["FECHA_EJECUTORIA"] . "','" . $datos5[0]["COD_FISCALIZACION"]
        . "','" . $datos5[0]["COD_CARTERANOMISIONAL"] . "','N','" . $datos5[0]["TOTAL_CAPITAL"] . "','" . $datos5[0]["SALDO_CAPITAL"]
        . "','" . $datos5[0]["SALDO_INTERES"] . "')";
        $this->db->query($SQL);
    }

}

?>