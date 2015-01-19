<?php

class Mcinvestigacion_model extends CI_Model {

    var $cod_municipio;
    var $array_num;

    function __construct() {
        parent::__construct();
    }

    function set_cod_municipio($cod_municipio) {
        $this->cod_municipio = $cod_municipio;
    }

    function set_array_num($array_num) {
        $this->array_num = $array_num;
    }

    function permiso() {
        $this->db->select("USUARIOS.IDUSUARIO as IDUSUARIO, APELLIDOS, NOMBRES,GRUPOS.IDGRUPO");
        $this->db->join("USUARIOS_GRUPOS", "USUARIOS.IDUSUARIO=USUARIOS_GRUPOS.IDUSUARIO");
        $this->db->join("GRUPOS", "USUARIOS_GRUPOS.IDGRUPO=GRUPOS.IDGRUPO");
        $this->db->or_where("(GRUPOS.IDGRUPO", ABOGADO);
        $this->db->or_where("GRUPOS.IDGRUPO", SECRETARIO);
        $this->db->or_where("GRUPOS.IDGRUPO", COORDINADOR . ")", FALSE);
        $this->db->where("USUARIOS.IDUSUARIO", ID_USER);
        $dato = $this->db->get("USUARIOS");
        return $dato->result_array;
    }

    function COBROPERSUASIVO($id = NULL, $id_doc = NULL, $id_COD = NULL) {
//        echo $id;
        $this->db->select("VW.REPRESENTANTE AS REPRESENTANTE_LEGAL, VW.RESPUESTA AS RESPUESTA,VW.CONCEPTO AS NOMBRE_CONCEPTO,VW.DIRECCION,MUNICIPIO.CODMUNICIPIO COD_MUNICIPIO,MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR AS PROCESO,MC_MEDIDASCAUTELARES.COD_RESPUESTAGESTION AS COD_RESPUESTA,VW.RESPUESTA AS RESPUESTA,"
                . 'PC.COD_PROCESO_COACTIVO AS COD_PROCESO,PC.ABOGADO AS ABOGADO, PC.COD_PROCESOPJ AS PROCESOPJ,VW.EJECUTADO AS NOMBRE_EMPRESA,'
                . 'PC.IDENTIFICACION AS CODEMPRESA, US.NOMBRES, US.APELLIDOS, VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL, VW.COD_REGIONAL AS COD_REGIONAL,MC_MEDIDASCAUTELARES.COD_RESPUESTAGESTION,'
                . "MC_MEDIDASCAUTELARES.COD_PROCESO_COACTIVO COD_FISCALIZACION,MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR,RESPUESTAGESTION.NOMBRE_GESTION AS TIPOGESTION,"
                . "REST.NOMBRE_GESTION AS TIPOGESTION2,MC_MEDIDASCAUTELARES.COD_RESPUESTAGESTION_BIENES,MUNICIPIO.NOMBREMUNICIPIO", FALSE);
//        $this->db->from('MC_MEDIDASCAUTELARES MC');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=MC_MEDIDASCAUTELARES.COD_PROCESO_COACTIVO');
        $this->db->join('RESPUESTAGESTION', 'RESPUESTAGESTION.COD_RESPUESTA=MC_MEDIDASCAUTELARES.COD_RESPUESTAGESTION');
        $this->db->join('RESPUESTAGESTION REST', 'REST.COD_RESPUESTA=MC_MEDIDASCAUTELARES.COD_RESPUESTAGESTION_BIENES', 'LEFT');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=PC.ABOGADO');
        $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL=VW.COD_REGIONAL');
        $this->db->join('MUNICIPIO', 'MUNICIPIO.CODMUNICIPIO=REGIONAL.COD_CIUDAD AND MUNICIPIO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO', FALSE);
        /**/
        //  $where = 'VW.COD_RESPUESTA = CP.COD_TIPO_RESPUESTA AND CP.COD_TIPO_RESPUESTA NOT IN (204,196)';
        $where = 'VW.COD_RESPUESTA = MC_MEDIDASCAUTELARES.COD_RESPUESTAGESTION';
        $this->db->where($where);

        if (!empty($id_doc)) {
            $this->db->select('MC_OFICIOS_GENERADOS.RUTA_DOCUMENTO_GEN');
            $this->db->join("MC_OFICIOS_GENERADOS", "MC_OFICIOS_GENERADOS.COD_MEDIDACAUTELAR=MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR"
                    . " and MC_OFICIOS_GENERADOS.ESTADO=0 AND MC_OFICIOS_GENERADOS.TIPO_DOCUMENTO='" . $id_doc . "'", "LEFT");
        }
        if (!empty($id))
            $this->db->where("MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR", $id);
        else if (!empty($id_COD))
            $this->db->where("MC_MEDIDASCAUTELARES.COD_PROCESO_COACTIVO", $id_COD);
        else {
            $num = $this->array_num;
            $this->db->where_in("(MC_MEDIDASCAUTELARES.COD_RESPUESTAGESTION", $num, FALSE);
            $datos = "";
            for ($i = 0; $i < count($num); $i++) {
                $datos.=$num[$i] . ",";
            }
            $this->db->or_where("MC_MEDIDASCAUTELARES.COD_RESPUESTAGESTION_BIENES in (", substr($datos, 0, -1) . "))", FALSE);
        }
        $this->db->where("MC_MEDIDASCAUTELARES.BLOQUEO", "0");
//        $this->db->where("EMPRESA.COD_REGIONAL", COD_REGIONAL);
        $dato = $this->db->get("MC_MEDIDASCAUTELARES");
       // echo $data=$this->db->last_query();
        return $dato->result_array;
    }

    function COBROPERSUASIVO_PRELACION($id = NULL) {

        $this->db->select("MC_MEDIDASCAUTELARES.OBSERVACIONES_REVISION,MC_MEDIDASCAUTELARES.RUTA_DOCUMENTO_MC,MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR,MC_MEDIDASCAUTELARES.COD_RESPUESTAGESTION,MC_MEDIDASCAUTELARES.COD_FISCALIZACION,"
                . "RESPUESTAGESTION.NOMBRE_GESTION as TIPOGESTION,"
                . "MC_MEDIDASPRELACION.COD_CONCURRENCIA,"
                . "MC_MEDIDASPRELACION.COD_MEDIDAPRELACION,"
                . "MC_MEDIDASPRELACION.COD_TIPOGESTION as TIPO,"
                . "MC_MEDIDASPRELACION.FECHA"
                . "");
        $this->db->join("MC_MEDIDASPRELACION", "MC_MEDIDASPRELACION.COD_MEDIDACAUTELAR=MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR");
        $this->db->join("RESPUESTAGESTION", "RESPUESTAGESTION.COD_RESPUESTA=MC_MEDIDASPRELACION.COD_TIPOGESTION");
        $num = $this->array_num;
        $this->db->where_in("COD_RESPUESTAGESTION", $num, FALSE);
        if (!empty($id))
            $this->db->where("MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR", $id);
        $this->db->where("MC_MEDIDASCAUTELARES.BLOQUEO", "0");
        $this->db->where("MC_MEDIDASPRELACION.ACTIVO", "0");
        $dato = $this->db->get("MC_MEDIDASCAUTELARES");
        return $dato->result_array;
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

    function municipio2($id) { //funcion para optener el municipio de la empresa
        if (!empty($this->cod_municipio)) :
            $this->db->select("NOMBREMUNICIPIO");
            $this->db->where("CODMUNICIPIO", $id);
            $dato = $this->db->get("MUNICIPIO");
            if (!empty($dato->result_array[0])):
                return $dato->result_array[0];
            endif;
        endif;
    }

    function secretario() {
        $this->db->select("USUARIOS.IDUSUARIO as IDUSUARIO, APELLIDOS, NOMBRES");
        $this->db->join("REGIONAL", "REGIONAL.CEDULA_SECRETARIO=USUARIOS.IDUSUARIO");
        $this->db->where("USUARIOS.COD_REGIONAL", COD_REGIONAL);
//        $this->db->where("USUARIOS.IDUSUARIO", $user);
        $dato = $this->db->get("USUARIOS");
        return $dato->result_array;
    }

    function guardar_Mc_medidas_cautelarias($post) {
        $this->db->set("COD_PROCESO_COACTIVO", $post['consulta'][0]['COD_FISCALIZACION']);
        switch ($post['post']['cod_siguiente']) {
            case ENVIAR_MODIFICACIONES:
                $no_info = INICIO_SECRETARIO;
                break;
            case OFICIO_ENVIAR_MODIFICACIONES:
                $no_info = OFICIO_SECRETARIO;
                break;
            case OFICIO_ENVIAR_MODIFICACIONES2:
                $no_info = OFICIO_SECRETARIO2;
                break;
            case AUTO_ENVIAR_MODIFICACIONES:
                $no_info = AUTO_INICIO_SECRETARIO;
                break;
            case FRACCIONAMIENTO_ENVIAR_MODIFICACIONES:
                $no_info = FRACCIONAMIENTO_INICIO_SECRETARIO;
                break;
            case OFICIO_BIENES_ENVIAR_MODIFICACIONES:
                $no_info = OFICIO_BIENES_INICIO_SECRETARIO;
                break;
            default :
                $no_info = $post['post']['cod_siguiente'];
        }
        $post['post']['cod_siguiente2'] = $no_info;
        if ($post['post']['cod_siguiente2'] == OFICIO_BIENES_INICIO_SECRETARIO ||
                $post['post']['cod_siguiente2'] == OFICIO_BIENES_INICIO_COORDINADOR ||
                $post['post']['cod_siguiente2'] == OFICIO_BIENES_DEVOLUCION ||
                $post['post']['cod_siguiente2'] == OFICIO_BIENES_ENVIAR_MODIFICACIONES
        )
            $this->db->set("COD_RESPUESTAGESTION_BIENES", $no_info);
        else
            $this->db->set("COD_RESPUESTAGESTION", $no_info);

        $this->db->set("FECHA_MEDIDAS", FECHA, false);
        $this->db->set("NOMBRE_DOCUMENTO_MC", "Oficio Orden de Investigacion y Envargo de Dinero");
        $this->db->set("RUTA_DOCUMENTO_MC", $post['post']['nombre'] . ".txt");
        $this->db->set("GENARADO_POR", ID_USER);
        $this->db->set("REVISADO_POR", $post['post']['secretario']);
        $this->db->set("FECHA_REVISION", FECHA, false);
        $this->db->where("COD_MEDIDACAUTELAR", $post['post']['id']);
        $this->db->update("MC_MEDIDASCAUTELARES");

        $this->db->select("COD_MEDIDACAUTELAR");
        $this->db->where("COD_PROCESO_COACTIVO", $post['consulta'][0]['COD_FISCALIZACION']);
        $dato = $this->db->get("MC_MEDIDASCAUTELARES");

        $post['post']['id_mc'] = $dato->result_array[0]['COD_MEDIDACAUTELAR'];

        if (!empty($post['post']['obser'])) {
            $this->db->set("COD_MEDIDACAUTELAR", $post['post']['id']);
            $this->db->set("FECHA_MODIFICACION", FECHA, false);
            $this->db->set("COMENTARIOS", $post['post']['obser']);
            $this->db->set("GENERADO_POR", ID_USER);
            $this->db->set("TIPO_DOCUMENTO", $post['post']['tipo_doc']);
            $this->db->insert("MC_TRAZABILIDAD");
        }

        $this->oficios_generados($post);
    }

    function guardar_Mc_medidas_Prelacion($post) {
//        echo $post['post']['cod_siguiente_prelacion']."**";
        switch ($post['post']['cod_siguiente_prelacion']) {
            case MUEBLES_ENVIAR_MODIFICACIONES:
                $no_info = MUEBLES_SECRETARIO;
                break;
            case MUEBLES_COM_SECUESTRO_ENVIAR_MODIFICACIONES:
                $no_info = MUEBLES_COM_SECUESTRO_SECRETARIO;
                break;
            case MUEBLES_DES_SECUESTRO_ENVIAR_MODIFICACIONES:
                $no_info = MUEBLES_DES_SECUESTRO_SECRETARIO;
                break;
            case MUEBLES_RESPUESTA_ENVIAR_MODIFICACIONES:
                $no_info = MUEBLES_RESPUESTA_SECRETARIO;
                break;
            case MUEBLES_COMISORIO_ENVIAR_MODIFICACIONES:
                $no_info = MUEBLES_COMISORIO_SECRETARIO;
                break;
            case MUEBLES_FECHA_ENVIAR_MODIFICACIONES:
                $no_info = MUEBLES_FECHA_SECRETARIO;
                break;
            case MUEBLES_DILIGENCIA_ENVIAR_MODIFICACIONES:
                $no_info = MUEBLES_DILIGENCIA_SECRETARIO;
                break;
            case MUEBLES_ORDEN_ENVIAR_MODIFICACIONES:
                $no_info = MUEBLES_ORDEN_SECRETARIO;
                break;
            case INMUEBLES_ORDEN_ENVIAR_MODIFICACIONES:
                $no_info = INMUEBLES_ORDEN_SECRETARIO;
                break;
            case VEHICULO_ORDEN_ENVIAR_MODIFICACIONES:
                $no_info = VEHICULO_ORDEN_SECRETARIO;
                break;
            case MUEBLES_PROYECTAR_AUTO_ENVIAR_MODIFICACIONES:
                $no_info = MUEBLES_PROYECTAR_AUTO_SECRETARIO;
                break;
            case MUEBLES_PROYECTAR_RESPUESTA_ENVIAR_MODIFICACIONES:
                $no_info = MUEBLES_PROYECTAR_RESPUESTA_SECRETARIO;
                break;
            case INMUEBLES_PROYECTAR_AUTO_ENVIAR_MODIFICACIONES:
                $no_info = INMUEBLES_PROYECTAR_AUTO_SECRETARIO;
                break;
            case VEHICULO_PROYECTAR_AUTO_ENVIAR_MODIFICACIONES:
                $no_info = VEHICULO_PROYECTAR_AUTO_SECRETARIO;
                break;
            case INMUEBLES_PROYECTAR_RESPUESTA_ENVIAR_MODIFICACIONES:
                $no_info = INMUEBLES_PROYECTAR_RESPUESTA_SECRETARIO;
                break;
            case INMUEBLES_ENVIAR_MODIFICACIONES:
                $no_info = INMUEBLES_SECRETARIO;
                break;
            case INMUEBLES_EMBARGO_ENVIAR_MODIFICACIONES:
                $no_info = INMUEBLES_EMBARGO_SECRETARIO;
                break;
            case INMUEBLES_FECHA_ENVIAR_MODIFICACIONES:
                $no_info = INMUEBLES_FECHA_SECRETARIO;
                break;
            case INMUEBLES_SECUESTRO_ENVIAR_MODIFICACIONES:
                $no_info = INMUEBLES_SECUESTRO_SECRETARIO;
                break;
            case INMUEBLES_AUTO_SECUESTRO_ENVIAR_MODIFICACIONES:
                $no_info = INMUEBLES_AUTO_SECUESTRO_SECRETARIO;
                break;
            case INMUEBLES_COMISION_SECUESTRO_ENVIAR_MODIFICACIONES:
                $no_info = INMUEBLES_COMISION_SECUESTRO_SECRETARIO;
                break;
            case INMUEBLES_DOCUMENTO_SECUESTRO_ENVIAR_MODIFICACIONES:
                $no_info = INMUEBLES_DOCUMENTO_SECUESTRO_SECRETARIO;
                break;
            case INMUEBLES_COMISORIO_SECUESTRO_ENVIAR_MODIFICACIONES:
                $no_info = INMUEBLES_COMISORIO_SECUESTRO_SECRETARIO;
                break;
            case INMUEBLES_DESP_COM_SECUESTRO_ENVIAR_MODIFICACIONES:
                $no_info = INMUEBLES_DESP_COM_SECUESTRO_SECRETARIO;
                break;
            case VEHICULO_ENVIAR_MODIFICACIONES:
                $no_info = VEHICULO_SECRETARIO;
                break;
            case VEHICULO_EMBARGO_ENVIAR_MODIFICACIONES:
                $no_info = VEHICULO_EMBARGO_SECRETARIO;
                break;
            case VEHICULO_SECUESTRO_ENVIAR_MODIFICACIONES:
                $no_info = VEHICULO_SECUESTRO_SECRETARIO;
                break;
            case VEHICULO_COMISION_ENVIAR_MODIFICACIONES:
                $no_info = VEHICULO_COMISION_SECRETARIO;
                break;
            case VEHICULO_DESPACHO_ENVIAR_MODIFICACIONES:
                $no_info = VEHICULO_DESPACHO_SECRETARIO;
                break;
            case VEHICULO_FECHA_ENVIAR_MODIFICACIONES:
                $no_info = VEHICULO_FECHA_SECRETARIO;
                break;
            case VEHICULO_DILIGENCIA_ENVIAR_MODIFICACIONES:
                $no_info = VEHICULO_DILIGENCIA_SECRETARIO;
                break;
            case VEHICULO_EMBARGO_OFICIO_ENVIAR_MODIFICACIONES:
                $no_info = VEHICULO_EMBARGO_OFICIO_INICIO;
                break;
            case VEHICULO_OPOSICION_ENVIAR_MODIFICACIONES:
                $no_info = VEHICULO_OPOSICION_SECRETARIO;
                break;
            default :
                $no_info = $post['post']['cod_siguiente_prelacion'];
        }

        $this->db->set("FECHA_MEDIDAS", FECHA, false);
        $this->db->set("NOMBRE_DOCUMENTO_MC", "Oficio Orden de Investigacion y Envargo de Dinero");
        $this->db->set("RUTA_DOCUMENTO_MC", $post['post']['nombre'] . ".txt");
        $this->db->set("FECHA_REVISION", FECHA, false);
        $this->db->where("COD_MEDIDACAUTELAR", $post['post']['id']);
        $this->db->update("MC_MEDIDASCAUTELARES");
        $this->db->set("COD_TIPOGESTION", $no_info);
        $this->db->set("FECHA", FECHA, false);
        if ($post['bloqueo'] == 0)
            $this->db->where("COD_MEDIDAPRELACION", $post['post']['id_prelacion']);
        else {
            $this->db->where("COD_MEDIDACAUTELAR", $post['post']['id']);
            $this->db->where("COD_CONCURRENCIA", $post['bloqueo']);
        }
        $this->db->update("MC_MEDIDASPRELACION");
//        echo $post['post']['cod_siguiente'] = $post['post']['cod_siguiente_prelacion'];
        $post['post']['cod_siguiente'] = $no_info;
//        echo "entro";
//        echo $no_info;

        if (!empty($post['post']['obser'])) {
            $this->db->set("COD_MEDIDACAUTELAR", $post['post']['id']);
            $this->db->set("FECHA_MODIFICACION", FECHA, false);
            $this->db->set("COMENTARIOS", $post['post']['obser']);
            $this->db->set("GENERADO_POR", ID_USER);
            $this->db->set("TIPO_DOCUMENTO", $post['post']['tipo_doc']);
            $this->db->insert("MC_TRAZABILIDAD");
        }
        $this->oficios_generados($post);
    }

    function documento($post) {
        $this->db->select("RUTA_DOCUMENTO_GEN AS RUTA_DOCUMENTO_MC");
        $this->db->where("COD_MEDIDACAUTELAR", $post['post']['id']);
        $this->db->where("TIPO_DOCUMENTO", $post['post']['tipo_doc']);
        $this->db->where("ESTADO", 0);
        $this->db->order_by("FECHA_CREACION", "DESC");
        $dato = $this->db->get("MC_OFICIOS_GENERADOS");
        return $dato->result_array;
    }

    function update_Mc_medidas_cautelarias($post) {
        $this->db->set("APROBADO_POR", ID_USER);
        $this->db->set("RUTA_DOCUMENTO_MC", $post['post']['nombre'] . ".txt");
        switch ($post['post']['cod_siguiente']) {
            case ENVIAR_MODIFICACIONES:
                $no_info = INICIO_SECRETARIO;
                break;
            case OFICIO_ENVIAR_MODIFICACIONES:
                $no_info = OFICIO_SECRETARIO;
                break;
            case OFICIO_ENVIAR_MODIFICACIONES2:
                $no_info = OFICIO_SECRETARIO2;
                break;
            case AUTO_ENVIAR_MODIFICACIONES:
                $no_info = AUTO_INICIO_SECRETARIO;
                break;
            case FRACCIONAMIENTO_ENVIAR_MODIFICACIONES:
                $no_info = FRACCIONAMIENTO_INICIO_SECRETARIO;
                break;
            case OFICIO_BIENES_ENVIAR_MODIFICACIONES:
                $no_info = OFICIO_BIENES_INICIO_SECRETARIO;
                break;
            default :
                $no_info = $post['post']['cod_siguiente'];
        }
        $post['post']['cod_siguiente2'] = $no_info;
        if ($post['post']['cod_siguiente2'] == OFICIO_BIENES_INICIO_SECRETARIO ||
                $post['post']['cod_siguiente2'] == OFICIO_BIENES_INICIO_COORDINADOR ||
                $post['post']['cod_siguiente2'] == OFICIO_BIENES_DEVOLUCION ||
                $post['post']['cod_siguiente2'] == OFICIO_BIENES_ENVIAR_MODIFICACIONES ||
                $post['post']['cod_siguiente2'] == OFICIO_BIENES_SUBIR_ARCHIVO
        )
            $this->db->set("COD_RESPUESTAGESTION_BIENES", $no_info);
        else
            $this->db->set("COD_RESPUESTAGESTION", $no_info);

        $this->db->set("FECHA_APROBACION", FECHA, false);
        $this->db->where("COD_MEDIDACAUTELAR", $post['post']['id']);
        $this->db->update("MC_MEDIDASCAUTELARES");
        $this->oficios_generados($post);
    }

    function oficios_generados($post) {
        $this->db->set("COD_MEDIDACAUTELAR", $post['post']['id']);
        $this->db->set("RUTA_DOCUMENTO_GEN", $post['post']['nombre'] . ".txt");
        $this->db->set("FECHA_CREACION", FECHA, false);
        $this->db->set("NOMBRE_OFICIO", $post['post']['titulo']);
//        echo $post['post']['cod_siguiente'];
        switch ($post['post']['cod_siguiente']) {
            case INICIO_SECRETARIO:
            case OFICIO_SECRETARIO:
            case OFICIO_SECRETARIO2:
            // Auto Medidas Cautelares
            case AUTO_INICIO_SECRETARIO:
            // Fraccionamiento de titulo
            case FRACCIONAMIENTO_INICIO_SECRETARIO:
            // Generar oficio bienes
            case OFICIO_BIENES_INICIO_SECRETARIO:
            // bienes y servicio 1 auto
            case MUEBLES_SECRETARIO:
            case MUEBLES_COM_SECUESTRO_SECRETARIO:
            case MUEBLES_DES_SECUESTRO_SECRETARIO:
            case MUEBLES_RESPUESTA_SECRETARIO:
            case MUEBLES_COMISORIO_SECRETARIO:
            case MUEBLES_FECHA_SECRETARIO:
            case MUEBLES_PROYECTAR_AUTO_SECRETARIO:
            case MUEBLES_PROYECTAR_RESPUESTA_SECRETARIO:
            case INMUEBLES_PROYECTAR_AUTO_SECRETARIO:
            case VEHICULO_PROYECTAR_AUTO_SECRETARIO:
            case INMUEBLES_PROYECTAR_RESPUESTA_SECRETARIO:
            case MUEBLES_DILIGENCIA_SECRETARIO:
            case MUEBLES_ORDEN_SECRETARIO:
            case INMUEBLES_ORDEN_SECRETARIO:
            case VEHICULO_ORDEN_SECRETARIO:
            case INMUEBLES_SECRETARIO:
            case INMUEBLES_EMBARGO_SECRETARIO:
            case INMUEBLES_FECHA_SECRETARIO:
            case INMUEBLES_SECUESTRO_SECRETARIO:
            case INMUEBLES_AUTO_SECUESTRO_SECRETARIO:
            case INMUEBLES_COMISION_SECUESTRO_SECRETARIO:
            case INMUEBLES_DESP_COM_SECUESTRO_SECRETARIO:
            case INMUEBLES_DOCUMENTO_SECUESTRO_SECRETARIO:
            case INMUEBLES_COMISORIO_SECUESTRO_SECRETARIO:
            case VEHICULO_SECRETARIO:
            case VEHICULO_EMBARGO_SECRETARIO:
            case VEHICULO_SECUESTRO_SECRETARIO:
            case VEHICULO_DESPACHO_SECRETARIO:
            case VEHICULO_COMISION_SECRETARIO:
            case VEHICULO_INCORPORANDO_SECRETARIO:
            case VEHICULO_FECHA_SECRETARIO:
            case VEHICULO_DILIGENCIA_SECRETARIO:
            case VEHICULO_OPOSICION_SECRETARIO:
            case VEHICULO_EMBARGO_OFICIO_SECRETARIO:
            //_______________________________________________________
            case ENVIAR_MODIFICACIONES:
            case INICIO_COORDINADOR:
            case DEVOLUCION:
            case SUBIR_ARCHIVO:

            case OFICIO_ENVIAR_MODIFICACIONES:
            case OFICIO_COORDINADOR:
            case OFICIO_DEVOLUCION:
            case OFICIO_SUBIR_ARCHIVO:

            case OFICIO_ENVIAR_MODIFICACIONES2:
            case OFICIO_COORDINADOR2:
            case OFICIO_DEVOLUCION2:
            case OFICIO_SUBIR_ARCHIVO2:

            case AUTO_ENVIAR_MODIFICACIONES:
            case AUTO_INICIO_COORDINADOR:
            case AUTO_DEVOLUCION:
            case AUTO_SUBIR_ARCHIVO:

            case FRACCIONAMIENTO_ENVIAR_MODIFICACIONES:
            case FRACCIONAMIENTO_INICIO_COORDINADOR:
            case FRACCIONAMIENTO_DEVOLUCION:
            case FRACCIONAMIENTO_SUBIR_ARCHIVO:

            case OFICIO_BIENES_ENVIAR_MODIFICACIONES:
            case OFICIO_BIENES_INICIO_COORDINADOR:
            case OFICIO_BIENES_DEVOLUCION:
            case OFICIO_BIENES_SUBIR_ARCHIVO:

            case MUEBLES_ENVIAR_MODIFICACIONES:
            case MUEBLES_COORDINARO:
            case MUEBLES_DEVOLUCION:
            case MUEBLES_SUBIR_ARCHIVO:

            case MUEBLES_COM_SECUESTRO_ENVIAR_MODIFICACIONES:
            case MUEBLES_COM_SECUESTRO_COORDINARO:
            case MUEBLES_COM_SECUESTRO_DEVOLUCION:
            case MUEBLES_COM_SECUESTRO_SUBIR_ARCHIVO:

            case MUEBLES_DES_SECUESTRO_ENVIAR_MODIFICACIONES:
            case MUEBLES_DES_SECUESTRO_COORDINARO:
            case MUEBLES_DES_SECUESTRO_DEVOLUCION:
            case MUEBLES_DES_SECUESTRO_SUBIR_ARCHIVO:

            case MUEBLES_RESPUESTA_ENVIAR_MODIFICACIONES:
            case MUEBLES_RESPUESTA_COORDINARO:
            case MUEBLES_RESPUESTA_DEVOLUCION:
            case MUEBLES_RESPUESTA_SUBIR_ARCHIVO:

            case MUEBLES_COMISORIO_ENVIAR_MODIFICACIONES:
            case MUEBLES_COMISORIO_COORDINARO:
            case MUEBLES_COMISORIO_DEVOLUCION:
            case MUEBLES_COMISORIO_SUBIR_ARCHIVO:

            case MUEBLES_FECHA_ENVIAR_MODIFICACIONES:
            case MUEBLES_FECHA_COORDINARO:
            case MUEBLES_FECHA_DEVOLUCION:
            case MUEBLES_FECHA_SUBIR_ARCHIVO:

            case MUEBLES_DILIGENCIA_ENVIAR_MODIFICACIONES:
            case MUEBLES_DILIGENCIA_COORDINARO:
            case MUEBLES_DILIGENCIA_DEVOLUCION:
            case MUEBLES_DILIGENCIA_SUBIR_ARCHIVO:

            case MUEBLES_ORDEN_ENVIAR_MODIFICACIONES:
            case MUEBLES_ORDEN_COORDINARO:
            case MUEBLES_ORDEN_DEVOLUCION:
            case MUEBLES_ORDEN_SUBIR_ARCHIVO:

            case INMUEBLES_ORDEN_ENVIAR_MODIFICACIONES:
            case INMUEBLES_ORDEN_COORDINARO:
            case INMUEBLES_ORDEN_DEVOLUCION:
            case INMUEBLES_ORDEN_SUBIR_ARCHIVO:

            case VEHICULO_ORDEN_ENVIAR_MODIFICACIONES:
            case VEHICULO_ORDEN_COORDINARO:
            case VEHICULO_ORDEN_DEVOLUCION:
            case VEHICULO_ORDEN_SUBIR_ARCHIVO:

            case MUEBLES_PROYECTAR_AUTO_ENVIAR_MODIFICACIONES:
            case MUEBLES_PROYECTAR_AUTO_COORDINARO:
            case MUEBLES_PROYECTAR_AUTO_DEVOLUCION:
            case MUEBLES_PROYECTAR_AUTO_SUBIR_ARCHIVO:

            case MUEBLES_PROYECTAR_RESPUESTA_ENVIAR_MODIFICACIONES:
            case MUEBLES_PROYECTAR_RESPUESTA_COORDINARO:
            case MUEBLES_PROYECTAR_RESPUESTA_DEVOLUCION:
            case MUEBLES_PROYECTAR_RESPUESTA_SUBIR_ARCHIVO:

            case INMUEBLES_PROYECTAR_AUTO_ENVIAR_MODIFICACIONES:
            case INMUEBLES_PROYECTAR_AUTO_COORDINARO:
            case INMUEBLES_PROYECTAR_AUTO_DEVOLUCION:
            case INMUEBLES_PROYECTAR_AUTO_SUBIR_ARCHIVO:

            case VEHICULO_PROYECTAR_AUTO_ENVIAR_MODIFICACIONES:
            case VEHICULO_PROYECTAR_AUTO_COORDINARO:
            case VEHICULO_PROYECTAR_AUTO_DEVOLUCION:
            case VEHICULO_PROYECTAR_AUTO_SUBIR_ARCHIVO:

            case INMUEBLES_PROYECTAR_RESPUESTA_ENVIAR_MODIFICACIONES:
            case INMUEBLES_PROYECTAR_RESPUESTA_COORDINARO:
            case INMUEBLES_PROYECTAR_RESPUESTA_DEVOLUCION:
            case INMUEBLES_PROYECTAR_RESPUESTA_SUBIR_ARCHIVO:

            case INMUEBLES_ENVIAR_MODIFICACIONES:
            case INMUEBLES_COORDINARO:
            case INMUEBLES_DEVOLUCION:
            case INMUEBLES_SUBIR_ARCHIVO:

            case INMUEBLES_EMBARGO_ENVIAR_MODIFICACIONES:
            case INMUEBLES_EMBARGO_COORDINARO:
            case INMUEBLES_EMBARGO_DEVOLUCION:
            case INMUEBLES_EMBARGO_SUBIR_ARCHIVO:

            case INMUEBLES_FECHA_ENVIAR_MODIFICACIONES:
            case INMUEBLES_FECHA_COORDINARO:
            case INMUEBLES_FECHA_DEVOLUCION:
            case INMUEBLES_FECHA_SUBIR_ARCHIVO:

            case INMUEBLES_SECUESTRO_ENVIAR_MODIFICACIONES:
            case INMUEBLES_SECUESTRO_COORDINARO:
            case INMUEBLES_SECUESTRO_DEVOLUCION:
            case INMUEBLES_SECUESTRO_SUBIR_ARCHIVO:

            case INMUEBLES_AUTO_SECUESTRO_ENVIAR_MODIFICACIONES:
            case INMUEBLES_AUTO_SECUESTRO_COORDINARO:
            case INMUEBLES_AUTO_SECUESTRO_DEVOLUCION:
            case INMUEBLES_AUTO_SECUESTRO_SUBIR_ARCHIVO:

            case INMUEBLES_COMISION_SECUESTRO_ENVIAR_MODIFICACIONES:
            case INMUEBLES_COMISION_SECUESTRO_COORDINARO:
            case INMUEBLES_COMISION_SECUESTRO_DEVOLUCION:
            case INMUEBLES_COMISION_SECUESTRO_SUBIR_ARCHIVO:

            case INMUEBLES_DESP_COM_SECUESTRO_ENVIAR_MODIFICACIONES:
            case INMUEBLES_DESP_COM_SECUESTRO_COORDINARO:
            case INMUEBLES_DESP_COM_SECUESTRO_DEVOLUCION:
            case INMUEBLES_DESP_COM_SECUESTRO_SUBIR_ARCHIVO:

            case INMUEBLES_DOCUMENTO_SECUESTRO_ENVIAR_MODIFICACIONES:
            case INMUEBLES_DOCUMENTO_SECUESTRO_COORDINARO:
            case INMUEBLES_DOCUMENTO_SECUESTRO_DEVOLUCION:
            case INMUEBLES_DOCUMENTO_SECUESTRO_SUBIR_ARCHIVO:

            case INMUEBLES_COMISORIO_SECUESTRO_ENVIAR_MODIFICACIONES:
            case INMUEBLES_COMISORIO_SECUESTRO_COORDINARO:
            case INMUEBLES_COMISORIO_SECUESTRO_DEVOLUCION:
            case INMUEBLES_COMISORIO_SECUESTRO_SUBIR_ARCHIVO:

            case VEHICULO_ENVIAR_MODIFICACIONES:
            case VEHICULO_COORDINARO:
            case VEHICULO_DEVOLUCION:
            case VEHICULO_SUBIR_ARCHIVO:

            case VEHICULO_EMBARGO_ENVIAR_MODIFICACIONES:
            case VEHICULO_EMBARGO_COORDINARO:
            case VEHICULO_EMBARGO_DEVOLUCION:
            case VEHICULO_EMBARGO_SUBIR_ARCHIVO:
            case VEHICULO_EMBARGO_ENTREGA_SUBIR_ARCHIVO:

            case VEHICULO_SECUESTRO_ENVIAR_MODIFICACIONES:
            case VEHICULO_SECUESTRO_COORDINARO:
            case VEHICULO_SECUESTRO_DEVOLUCION:
            case VEHICULO_SECUESTRO_SUBIR_ARCHIVO:

            case VEHICULO_COMISION_ENVIAR_MODIFICACIONES:
            case VEHICULO_COMISION_COORDINARO:
            case VEHICULO_COMISION_DEVOLUCION:
            case VEHICULO_COMISION_SUBIR_ARCHIVO:

            case VEHICULO_DESPACHO_ENVIAR_MODIFICACIONES:
            case VEHICULO_DESPACHO_COORDINARO:
            case VEHICULO_DESPACHO_DEVOLUCION:
            case VEHICULO_DESPACHO_SUBIR_ARCHIVO:

            case VEHICULO_INCORPORANDO_ENVIAR_MODIFICACIONES:
            case VEHICULO_INCORPORANDO_COORDINARO:
            case VEHICULO_INCORPORANDO_DEVOLUCION:
            case VEHICULO_INCORPORANDO_SUBIR_ARCHIVO:

            case VEHICULO_FECHA_ENVIAR_MODIFICACIONES:
            case VEHICULO_FECHA_COORDINARO:
            case VEHICULO_FECHA_DEVOLUCION:
            case VEHICULO_FECHA_SUBIR_ARCHIVO:

            case VEHICULO_DILIGENCIA_ENVIAR_MODIFICACIONES:
            case VEHICULO_DILIGENCIA_COORDINARO:
            case VEHICULO_DILIGENCIA_DEVOLUCION:
            case VEHICULO_DILIGENCIA_SUBIR_ARCHIVO:

            case VEHICULO_EMBARGO_OFICIO_ENVIAR_MODIFICACIONES:
            case VEHICULO_EMBARGO_OFICIO_COORDINARO:
            case VEHICULO_EMBARGO_OFICIO_DEVOLUCION:
            case VEHICULO_EMBARGO_OFICIO_SUBIR_ARCHIVO:

            case VEHICULO_OPOSICION_ENVIAR_MODIFICACIONES:
            case VEHICULO_OPOSICION_COORDINARO:
            case VEHICULO_OPOSICION_DEVOLUCION:
            case VEHICULO_OPOSICION_SUBIR_ARCHIVO:

                $this->guardar_datos_temporales($post);
                break;
        }
        if (isset($post['post']['cod_siguiente2'])) {
            $gestion = $this->tipogestion($post['post']['cod_siguiente2']);
//            $id_traza = trazar($gestion, $post['post']['cod_siguiente2'], $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
            trazarProcesoJuridico($gestion, $post['post']['cod_siguiente2'], '', $post['post']['cod_fis'], '', '', $comentarios = "", ID_USER);
        } else {
            $gestion = $this->tipogestion($post['post']['cod_siguiente']);
            trazarProcesoJuridico($gestion, $post['post']['cod_siguiente'], '', $post['post']['cod_fis'], '', '', $comentarios = "", ID_USER);
//            trazar($gestion, $post['post']['cod_siguiente'], $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
        }
    }

    function view_documentos($id) {
        $this->db->select('RUTA_DOCUMENTO_GEN,FECHA_CREACION,NOMBRE_OFICIO');
        $this->db->where('COD_MEDIDACAUTELAR', $id);
        $dato = $this->db->get('MC_OFICIOS_GENERADOS');
        return $dato->result_array;
    }

    function view_medida_cautelar($post) {
        $this->db->select('COD_MEDIDACAUTELAR');
        $this->db->where('COD_PROCESO_COACTIVO', $post['post']['id_mc']);
        $dato = $this->db->get('MC_MEDIDASCAUTELARES');
        return $dato->result_array;
    }

    function subir_documento_doc2($post, $file_name) {
        $this->db->set("COD_MEDIDACAUTELAR", $post['post']['id_mc']);
        $this->db->set("NOMBRE_OFICIO", $post['post']['titulo']);
        $this->db->set("RUTA_DOCUMENTO_GEN", $file_name);
        $this->db->set("FECHA_CREACION", $post['post']['fecha_radicado']);
        $this->db->set("REVISADO_POR", ID_USER);
        $this->db->set("NRO_RADICADO", $post['post']['radicado']);
        $this->db->set("ESTADO", 1);
        $this->db->where("TIPO_DOCUMENTO", $post['post']['tipo']);
        $this->db->where("ESTADO", 0);
        $this->db->update("MC_OFICIOS_GENERADOS");

        if ($this->db->affected_rows() == '0') {
            $this->db->set("COD_MEDIDACAUTELAR", $post['post']['id']);
            $this->db->set("NOMBRE_OFICIO", $post['post']['titulo']);
            $this->db->set("RUTA_DOCUMENTO_GEN", $file_name);
            $this->db->set("FECHA_CREACION", $post['post']['fecha_radicado']);
            $this->db->set("REVISADO_POR", ID_USER);
            $this->db->set("NRO_RADICADO", $post['post']['radicado']);
            $this->db->set("TIPO_DOCUMENTO", $post['post']['tipo']);
            $this->db->set("ESTADO", 1);
            $this->db->insert("MC_OFICIOS_GENERADOS");
        }
    }

    function subir_documento_doc1($post) {
//        echo "<pre>";
//        print_r($post['post']);
//        echo "</pre>";
        $this->db->set("APROBADO_POR", ID_USER);
        $this->db->set("RUTA_DOCUMENTO_MC", $post['file']['upload_data']['file_name']);
        $this->db->set("FECHA_APROBACION", FECHA, false);
        $this->db->set("NOMBRE_DOCUMENTO_MC", "");
        $this->db->set("RUTA_DOCUMENTO_MC", "");
        $this->db->where("COD_MEDIDACAUTELAR", $post['post']['id']);

        if (!empty($this->data['post']['cod_siguiente_prelacion'])) {
            $this->db->update("MC_MEDIDASCAUTELARES");

            $gestion = $this->tipogestion($post['post']['cod_siguiente_prelacion']);
//            $id_traza = trazar($gestion, $post['post']['cod_siguiente_prelacion'], $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
            trazarProcesoJuridico($gestion, $post['post']['cod_siguiente_prelacion'], '', $post['post']['cod_fis'], '', '', $comentarios = "", ID_USER);
            $this->db->set("COD_TIPOGESTION", $post['post']['cod_siguiente_prelacion']);
            $this->db->set("FECHA", FECHA, false);
            $this->db->where("COD_MEDIDAPRELACION", $post['post']['id_prelacion']);
            $this->db->update("MC_MEDIDASPRELACION");
        } else {
//            echo  $post['post']['tipo'];
            if (APROVACION_BIENES_GENERALES == $post['post']['cod_siguiente']) {
                $this->db->set("COD_RESPUESTAGESTION_BIENES", $post['post']['cod_siguiente']);
            } else if ($post['post']['tipo'] < 2 || $post['post']['tipo'] == 55) {
                $this->db->set("COD_RESPUESTAGESTION", $post['post']['cod_siguiente']);
            } else if ($post['post']['tipo'] == 2) {
                $this->db->set("COD_RESPUESTAGESTION", $post['post']['cod_siguiente']);
                $this->db->set("COD_RESPUESTAGESTION_BIENES", APROVACION_BIENES_GENERALES);
            } else {
                $this->db->set("COD_RESPUESTAGESTION_BIENES", $post['post']['cod_siguiente']);
            }
            $this->db->update("MC_MEDIDASCAUTELARES");

            $gestion = $this->tipogestion($post['post']['cod_siguiente']);
//            $id_traza = trazar($gestion, $post['post']['cod_siguiente'], $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
            trazarProcesoJuridico($gestion, $post['post']['cod_siguiente'], '', $post['post']['cod_fis'], '', '', $comentarios = "", ID_USER);
        }




        if ($post['post']['cod_siguiente'] == VIKY_AVALUO) {
            $post['post']['traza'] = $id_traza['COD_GESTION_COBRO'];
            $post['post']['dato'] = $post['post']['cod_siguiente'];
            $this->envio_avaluo($post);
        }
    }

    function MC_TRAZABILIDAD($post) {
        $this->db->select('MC_TRAZABILIDAD.FECHA_MODIFICACION,MC_TRAZABILIDAD.COMENTARIOS,MC_TRAZABILIDAD.GENERADO_POR,'
                . 'USUARIOS.APELLIDOS, USUARIOS.NOMBRES');
        $this->db->join("USUARIOS", "USUARIOS.IDUSUARIO=MC_TRAZABILIDAD.GENERADO_POR");
        $this->db->where("TIPO_DOCUMENTO", $post['post']['tipo_doc']);
        $this->db->where("COD_MEDIDACAUTELAR", $post['post']['id']);
        $this->db->order_by("MC_TRAZABILIDAD.FECHA_MODIFICACION", 'desc');
        $dato = $this->db->get('MC_TRAZABILIDAD');
        $datos = $dato->result_array;
        $valor = "";
        foreach ($datos as $consulta) {
            $valor.=$consulta['COMENTARIOS'] . "<br>" . $consulta['FECHA_MODIFICACION'] . "<br>" . $consulta['NOMBRES'] . " " . $consulta['APELLIDOS'] . "<hr>";
        }
        return $valor;
    }

    function guardar_trazabilidad($post) {
        if ($post['post']['devol'] == OFICIO_BIENES_DEVOLUCION)
            $this->db->set("COD_RESPUESTAGESTION_BIENES", $post['post']['devol']);
        else
            $this->db->set("COD_RESPUESTAGESTION", $post['post']['devol']);

        $this->db->where("COD_MEDIDACAUTELAR", $post['post']['id']);
        $this->db->update("MC_MEDIDASCAUTELARES");

        $this->db->set("COD_MEDIDACAUTELAR", $post['post']['id']);
        $this->db->set("FECHA_MODIFICACION", FECHA, false);
        $this->db->set("COMENTARIOS", $post['post']['infor']);
        $this->db->set("GENERADO_POR", ID_USER);
        $this->db->set("TIPO_DOCUMENTO", $post['post']['tipo_doc']);
        $this->db->insert("MC_TRAZABILIDAD");
        $gestion = $this->tipogestion($post['post']['devol']);
//        $id_traza = trazar($gestion, $post['post']['devol'], $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
        trazarProcesoJuridico($gestion, $post['post']['devol'], '', $post['post']['cod_fis'], '', '', $comentarios = "", ID_USER);
    }

    function guardar_trazabilidad_prelacion($post) {
        $this->db->set("COD_MEDIDACAUTELAR", $post['post']['id']);
        $this->db->set("FECHA_MODIFICACION", FECHA, false);
        $this->db->set("COMENTARIOS", $post['post']['infor']);
        $this->db->set("GENERADO_POR", ID_USER);
        $this->db->set("TIPO_DOCUMENTO", $post['post']['tipo_doc']);
        $this->db->insert("MC_TRAZABILIDAD");

        $this->db->set("COD_TIPOGESTION", $post['post']['devol']);
        $this->db->set("FECHA", FECHA, false);
        if ($post['bloqueo'] == 0)
            $this->db->where("COD_MEDIDAPRELACION", $post['post']['id_prelacion']);
        else {
            $this->db->where("COD_MEDIDACAUTELAR", $post['post']['id']);
            $this->db->where("COD_CONCURRENCIA", $post['bloqueo']);
        }
        $this->db->update("MC_MEDIDASPRELACION");
        $gestion = $this->tipogestion($post['post']['devol']);
//        $id_traza = trazar($gestion, $post['post']['devol'], $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
        trazarProcesoJuridico($gestion, $post['post']['devol'], '', $post['post']['cod_fis'], '', '', $comentarios = "", ID_USER);
    }

    function guardar_documentos_bancarios($id, $name) {
        $this->db->set("COD_MEDIDACAUTELAR", $id);
        $name = str_replace(" ", "_", $name);
        $this->db->set("NOMBRE_OFICIO_EMBARGO", $name);
        //nelson falta colocar el id del usuario pero se va a usar para colocar valor
        $this->db->insert("MC_EMBARGOBIENES");
    }

    function delete_documentos_bancarios($id) {
        $this->db->where('COD_EMBARGO_DINEROS', $id);
        $this->db->delete("MC_EMBARGOBIENES");
    }

    function delete_documentos_mc($id) {
        $this->db->where('COD_OFICIO_MC', $id);
        $this->db->delete("MC_OFICIOS_GENERADOS");
    }

    function select_documentos_bancarios($id) {
        $this->db->select("COD_EMBARGO_DINEROS,COD_MEDIDACAUTELAR,NOMBRE_OFICIO_EMBARGO");
        $this->db->where('COD_MEDIDACAUTELAR', $this->data['post']['id']);
        $dato = $this->db->get("MC_EMBARGOBIENES");
        $datos = $dato->result_array;


//        echo $this->db->last_query();
//        die();
        $informacion = "";
        $i = 1;
        foreach ($datos as $dato) {
            $dato['COD_EMBARGO_DINEROS'] = '"' . $dato['COD_EMBARGO_DINEROS'] . '","' . $dato['COD_MEDIDACAUTELAR'] . '"';

            if ($i % 2 == 0)
                $informacion.="<tr>";
            else
                $informacion.='<tr style="background-color: #CCC">';

            $informacion.=""
                    . "<td><a href='" . base_url() . RUTA_DES . $id . "/" . $dato['NOMBRE_OFICIO_EMBARGO'] . "' target='_blank'>" . $dato['NOMBRE_OFICIO_EMBARGO'] . "</a></td>"
                    . "<td><a href='javascript:' onclick='eliminar(" . $dato['COD_EMBARGO_DINEROS'] . ")'><i class='fa fa-trash-o' title='Eliminar'></i>
</a></td>"
                    . "</tr>";
            $i++;
        }
        return $informacion;
    }

    function select_documentos_mc($id, $t_doc) {
        $this->db->select("COD_OFICIO_MC,COD_MEDIDACAUTELAR,RUTA_DOCUMENTO_GEN,NRO_RADICADO,FECHA_CREACION");
        $this->db->where('ESTADO', 1);
        $this->db->where('COD_MEDIDACAUTELAR', $id);
        $this->db->where('TIPO_DOCUMENTO', $t_doc);
        $dato = $this->db->get("MC_OFICIOS_GENERADOS");
        $datos = $dato->result_array;


//        echo $this->db->last_query();
//        die();
        $informacion = "";
        $i = 1;
        foreach ($datos as $dato) {

            if ($i % 2 == 0)
                $informacion.="<tr>";
            else
                $informacion.='<tr style="background-color: #CCC">';

            $informacion.=""
                    . "<td><a href='" . base_url() . RUTA_DES . $id . "/" . $dato['RUTA_DOCUMENTO_GEN'] . "' target='_blank'>" . $dato['RUTA_DOCUMENTO_GEN'] . "</a></td>"
                    . "<td>" . $dato['NRO_RADICADO'] . "</td>"
                    . "<td>" . $dato['FECHA_CREACION'] . "</td>"
                    . "<td><a href='javascript:' onclick='eliminar(" . $dato['COD_OFICIO_MC'] . ")'><i class='fa fa-trash-o' title='Eliminar'></i>
</a></td>"
                    . "</tr>";
            $i++;
        }
        return $informacion;
    }

    function count_documentos_bancarios($id) {
        $this->db->select("count(COD_MEDIDACAUTELAR) as TOTAL");
        $this->db->where('COD_MEDIDACAUTELAR', $id);
        $dato = $this->db->get("MC_EMBARGOBIENES");
        $datos = $dato->result_array;
        return $datos;
    }

    function resumen_documentos_bancarios($post) {
        for ($i = 0; $i < count($post['post']['banco']); $i++) {
//            $this->db->set("NOMBRE_OFICIO_EMBARGO", $post['post']['banco'][]);
            $this->db->set("FECHA_GENERACION_DOC", FECHA, false);
            $this->db->set("ELABORADO_POR", ID_USER);
            $this->db->set("COD_BANCO", $post['post']['banco'][$i]);
            $this->db->set("VALOR", $post['post']['valor'][$i]);
            $this->db->set("OBSERVACIONES", $post['post']['observaciones'][$i]);
            $this->db->where("COD_EMBARGO_DINEROS", $post['post']['cod'][$i]);
            $this->db->update("MC_EMBARGOBIENES");
        }
        $this->db->set("OBSERVACIONES_REVISION", $post['post']['t']);
        $this->documentos_bancarios($post);
    }

    function MC_EMBARGOBIENES($post) {
        $this->db->select("MC_EMBARGOBIENES.COD_BANCO,MC_EMBARGOBIENES.OBSERVACIONES,MC_EMBARGOBIENES.COD_EMBARGO_DINEROS,MC_EMBARGOBIENES.NOMBRE_OFICIO_EMBARGO,"
                . "MC_EMBARGOBIENES.VALOR,MC_EMBARGOBIENES.COD_BANCO,BANCO.NOMBREBANCO");
        $this->db->join("BANCO", "BANCO.IDBANCO=MC_EMBARGOBIENES.COD_BANCO", "LEFT");
        $this->db->where("MC_EMBARGOBIENES.COD_MEDIDACAUTELAR", $post['post']['id']);
        //nelson falta colocar el id del usuario pero se va a usar para colocar valor
        $dato = $this->db->get("MC_EMBARGOBIENES");
        return $dato->result_array;
    }

    function eliminar_documentos_bancarios($id, $name) {
        $this->db->where("COD_MEDIDACAUTELAR", $id);
        $this->db->where("NOMBRE_OFICIO_EMBARGO", $name);
        //nelson falta colocar el id del usuario pero se va a usar para colocar valor
        $this->db->delete("MC_EMBARGOBIENES");
    }

    function documentos_bancarios($post) {
        $this->db->set("APROBADO_POR", ID_USER);
        $this->db->set("COD_RESPUESTAGESTION", $post['post']['cod_siguiente']);
        $this->db->where("COD_MEDIDACAUTELAR", $post['post']['id']);
        $this->db->update("MC_MEDIDASCAUTELARES");

        $gestion = $this->tipogestion($post['post']['cod_siguiente']);
//        $id_traza = trazar($gestion, $post['post']['cod_siguiente'], $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
        trazarProcesoJuridico($gestion, $post['post']['cod_siguiente'], '', $post['post']['cod_fis'], '', '', $comentarios = "", ID_USER);
    }

    function subir_documentos_nuevos($post) {
        $this->db->set("COD_RESPUESTAGESTION", $post['post']['cod_siguiente']);
        $this->db->where("COD_MEDIDACAUTELAR", $post['post']['id']);
        $this->db->update("MC_MEDIDASCAUTELARES");
    }

    function bancos() {
        $this->db->select("IDBANCO,NOMBREBANCO");
        $dato = $this->db->get("BANCO");
        return $dato->result_array;
    }

    function MC_TIPO_PRIORIDAD() {
        $this->db->select("COD_PRIORIDAD,NOMBRE_PRIORIDAD");
        $this->db->where("ACTIVO", '0');
        $dato = $this->db->get("MC_TIPO_PRIORIDAD");
        return $dato->result_array;
    }

    function accion_de($post) {
        if (!empty($post['post']['dato'])) {
            switch ($post['post']['dato']) {
                case 1:
                    $no_info = MUEBLE_COMISIONAR1;
                    $gestion = "144";
                    break;
                case 2:
                    $no_info = INMUEBLES_INICIO;
                    $gestion = "139";
                    break;
                case 3:
                    $no_info = VEHICULO_INICIO;
                    $gestion = "140";
                    break;
            }
        }
        $this->db->select('COD_MEDIDAPRELACION');
        $this->db->where('COD_MEDIDACAUTELAR', $post['post']['id']);
        $this->db->where('COD_CONCURRENCIA', $post['post']['dato']);
        $dato = $this->db->get('MC_MEDIDASPRELACION');

        $this->db->set('ACTIVO', '0');
        $this->db->where('COD_MEDIDACAUTELAR', $post['post']['id']);
        $this->db->where('COD_CONCURRENCIA', $post['post']['dato']);
        $this->db->update('MC_MEDIDASPRELACION');
        if (!empty($dato->result_array[0])) {
            $datos = $dato->result_array[0];
        } else {
            $this->db->set("COD_MEDIDACAUTELAR", $post['post']['id']);
            $this->db->set("COD_CONCURRENCIA", $post['post']['dato']);
            $this->db->set("COD_TIPOGESTION", $no_info);
            $this->db->set("FECHA", FECHA, false);
            $this->db->insert('MC_MEDIDASPRELACION');

            $this->db->select('COD_MEDIDAPRELACION');
            $this->db->where('COD_MEDIDACAUTELAR', $post['post']['id']);
            $this->db->where('COD_CONCURRENCIA', $post['post']['dato']);
            $dato = $this->db->get('MC_MEDIDASPRELACION');
            $datos = $dato->result_array[0];
        }
        $cantidad = count($post['post']['mueble']);

        $this->db->where("COD_MEDIDAPRELACION", $datos['COD_MEDIDAPRELACION']);
        $this->db->where("COD_TIPOINMUEBLE", $post['post']['dato']);
        $this->db->delete('MC_PRELACIONTITULO');

        for ($i = 0; $i < $cantidad; $i++) {
            $this->db->set("COD_PRIORIDAD", $post['post']['id_prioridad'][$i]);
            $this->db->set("COD_BANCO", $post['post']['id_banco'][$i]);
            $this->db->set("COD_TIPOINMUEBLE", $post['post']['dato']);
            $this->db->set("CREADO_POR", ID_USER);
            $this->db->set("FECHA_CREACION", FECHA, false);
            $this->db->set("VALOR", $post['post']['valor'][$i]);
            $this->db->set("OBSERVACIONES", $post['post']['observacion'][$i]);
            $this->db->set("NUM_MATRICULA", $post['post']['mueble'][$i]);
            $this->db->set("COD_MEDIDAPRELACION", $datos['COD_MEDIDAPRELACION']);
            $this->db->insert('MC_PRELACIONTITULO');
        }
        $id_traza = trazarProcesoJuridico($gestion, $no_info, '', $post['cod_fis'], '', '', $comentarios = "", ID_USER);
    }

    function bloquear_vehiculos($post) {
        $this->db->set("ACTIVO", '1');
        $this->db->set("FECHA", FECHA, false);
        $this->db->where("COD_MEDIDAPRELACION", $post['id_prelacion']);
        $this->db->update('MC_MEDIDASPRELACION');
        $id_traza = trazarProcesoJuridico('553', '1368', '', $post['cod_fis'], '', '', $comentarios = "", ID_USER);
    }

    function avance($post) {
//        echo "<pre>";
//        print_r($post['post']);
//        echo "</pre>";
        $this->db->set("COD_TIPOGESTION", $post['post']['dato']);
        $this->db->set("FECHA", FECHA, false);
        $this->db->where("COD_MEDIDAPRELACION", $post['post']['id_prelacion']);
        $this->db->update('MC_MEDIDASPRELACION');
        $gestion = $this->tipogestion($post['post']['dato']);
//        $id_traza = trazar($gestion, $post['post']['dato'], $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
        $id_traza = trazarProcesoJuridico($gestion, $post['post']['dato'], '', $post['post']['cod_fis'], '', '', $comentarios = "", ID_USER);
        $post['post']['traza'] = $id_traza['COD_GESTION_COBRO'];
        $this->envio_avaluo($post);
    }

    function envio_avaluo($post) {
        if (
                $post['post']['dato'] == VIKY_OPOSICION ||
                $post['post']['dato'] == VIKY_FAVORABLE) {

            $this->db->select('MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR,MC_MEDIDASCAUTELARES.COD_PROCESO_COACTIVO,MC_MEDIDASPRELACION.COD_CONCURRENCIA as TIPO_INMUEBLE,
 MC_PRELACIONTITULO.COD_CONCURRENCIA AS CONCURRENCIA');
            $this->db->join('MC_MEDIDASPRELACION', ' MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR=MC_MEDIDASPRELACION.COD_MEDIDACAUTELAR');
            $this->db->join('MC_PRELACIONTITULO', ' MC_PRELACIONTITULO.COD_MEDIDAPRELACION=MC_MEDIDASPRELACION.COD_MEDIDAPRELACION');
            $this->db->where("MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR", $post['post']['id']);
            $dato = $this->db->get('MC_MEDIDASCAUTELARES');
            $datos = $dato->result_array;
            $cantidad = count($datos);
//            print_r($id_traza);
            for ($i = 0; $i < $cantidad; $i++) {
                $this->db->set('COD_PROCESO_COACTIVO', $datos[$i]['COD_PROCESO_COACTIVO']);
                $this->db->set('COD_TIPO_INMUEBLE', $datos[$i]['TIPO_INMUEBLE']);
                $this->db->set('COD_MEDIDACAUTELAR', $datos[$i]['COD_MEDIDACAUTELAR']);
                $this->db->set('COD_CONCURRENCIA', $datos[$i]['CONCURRENCIA']);
                $this->db->set('COD_TIPORESPUESTA', $post['post']['dato']);
                $this->db->set('COD_GESTION_COBRO', 536);
//                $this->db->set('COD_GESTION_COBRO', $post['post']['traza']);
                $this->db->insert('MC_AVALUO');
            }
        }
    }

    function ofice($post) {
        $this->db->set("TIPO_DOCUMENTO", '99');
        $this->db->where("COD_MEDIDACAUTELAR", $post['post']['id']);
        $this->db->where("TIPO_DOCUMENTO", $post['post']['tipo_doc']);
        $this->db->update("MC_OFICIOS_GENERADOS");
    }

    function reiniciar_proceso($post) {
//        $this->db->set("BLOQUEO", '1');
        $this->db->set("COD_RESPUESTAGESTION_BIENES", OFICIO_BIENES);
        $this->db->where("COD_FISCALIZACION", $post['post']['cod_fis']);
        $this->db->update("MC_MEDIDASCAUTELARES");
    }

    function tipogestion($id) {
        $this->db->select("COD_TIPOGESTION");
        $this->db->where("COD_RESPUESTA", $id);
        $dato = $this->db->get('RESPUESTAGESTION');
        $datos = $dato->result_array[0]['COD_TIPOGESTION'];
        return $datos;
    }

    function guardar_datos_temporales($post) {
        $this->db->where("COD_MEDIDACAUTELAR", $post['post']['id']);
        $this->db->set("RUTA_DOCUMENTO_GEN", $post['post']['nombre'] . ".txt");
        $this->db->set("FECHA_CREACION", FECHA, false);
        $this->db->set("NOMBRE_OFICIO", $post['post']['titulo']);
        $this->db->set("CREADO_POR", ID_USER);
        $this->db->where("TIPO_DOCUMENTO", $post['post']['tipo_doc']);
        $this->db->update("MC_OFICIOS_GENERADOS");

        if ($this->db->affected_rows() == '0') {
            $this->db->set("COD_MEDIDACAUTELAR", $post['post']['id']);
            $this->db->set("RUTA_DOCUMENTO_GEN", $post['post']['nombre'] . ".txt");
            $this->db->set("FECHA_CREACION", FECHA, false);
            $this->db->set("NOMBRE_OFICIO", $post['post']['titulo']);
            $this->db->set("CREADO_POR", ID_USER);
            $this->db->set("TIPO_DOCUMENTO", $post['post']['tipo_doc']);
            $this->db->insert("MC_OFICIOS_GENERADOS");
        }
    }

    function informacion_prelacion($post) {
        $this->db->select("MC_PRELACIONTITULO.NUM_MATRICULA,MC_PRELACIONTITULO.OBSERVACIONES");
        $this->db->join('MC_PRELACIONTITULO', 'MC_PRELACIONTITULO.COD_MEDIDAPRELACION=MC_MEDIDASPRELACION.COD_MEDIDAPRELACION');
        $this->db->where("MC_MEDIDASPRELACION.COD_MEDIDACAUTELAR", $post['id']);
        $this->db->where("MC_MEDIDASPRELACION.COD_CONCURRENCIA", $post['dato']);
        $dato = $this->db->get('MC_MEDIDASPRELACION');
        $datos = $dato->result_array;
        $html = "";
        $i = 3;
        foreach ($datos as $value) {
            $html.='<tr id="trr' . $i . '" align="center">'
                    . '<td><input class="infor_table" type="text" style="width: 100px;" value="' . $value['NUM_MATRICULA'] . '" maxlength="32" name="mueble[]"></td>'
                    . '<td></td>'
                    . '<td><input class="infor_table" type="text" value="' . $value['OBSERVACIONES'] . '" name="observacion[]"></td>'
                    . '<td></td>'
                    . '<td></td>'
                    . '<td><button id="del" class="eliminar btn btn-primary" onclick="eliminar_col(' . $i . ')" type="button">Eliminar</button></td>'
                    . "</tr>";
            $i++;
        }
        return $html;
    }

}

?>
