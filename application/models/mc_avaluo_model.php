<?php

class Mc_avaluo_model extends CI_Model {

    var $array_num;

    function __construct() {
        parent::__construct();
    }

    function set_array_num($array_num) {
        $this->array_num = $array_num;
    }

    function set_tipoinmueble($tipoinmueble) {
        $this->tipoinmueble = $tipoinmueble;
    }

    function set_metodo_avaluo($metodoavaluo) {
        $this->$metodoavaluo = $metodoavaluo;
    }

    public function motivos() {
        /* Función que lista los motivos de devolución de una notificación */
        $this->db->select('COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION');
        $this->db->where('IDESTADO', 1);
        $this->db->where('IDESTADO', 1);
        $this->db->where('NATURALEZA', 'N');
        $query = $this->db->get('MOTIVODEVOLUCION');
        //echo $this->db->last_query($acuerdo);   
        return $query->result();
    }

    function gestion_notificacion($post) {
        /* Función que guarda la gestión de las notificaciones */
        $this->db->set("COD_TIPORESPUESTA", $post['cod_siguiente']);
        if (!empty($post['notificacion_efectiva'])):
            $this->db->set('NOTIFICACION_EFECTIVA', "TO_DATE('" . $post['notificacion_efectiva'] . "','DD/MM/YY')", FALSE);
        endif;
        $this->db->where("COD_PROCESO_COACTIVO", $post['id']);
        $this->db->update("MC_AVALUO");
        //    $D = $this->db->last_query();



        if (!empty($post['comentarios'])) {
            $this->db->set("COD_PROCESO_COACTIVO", $post['id']);
            $this->db->set("FECHA_MODIFICACION", FECHA, FALSE);
            $this->db->set("COMENTARIOS", $post['comentarios']);
            $this->db->set("GENERADO_POR", ID_USER);
            $this->db->set("TIPO_DOCUMENTO", $post['tipo_doc']);
            $this->db->insert("MC_TRAZABILIDAD");
        }
        //  echo   "hola ".       $post['cod_siguiente'];die();
        if ($post['generar_oficio'] == TRUE && $post['cod_siguiente'] != 1417):
            $documento = $this->oficios_generados($post);
        endif;

        return TRUE;
    }

    function documento_mc($tipo, $id) {
        $this->db->select("MC_OFICIOS_GENERADOS.COD_OFICIO_MC,MC_OFICIOS_GENERADOS.RUTA_DOCUMENTO_GEN");
        $this->db->where("TIPO_DOCUMENTO ", $tipo);
        $this->db->where("COD_PROCESO_COACTIVO", $id);
        $this->db->order_by('MC_OFICIOS_GENERADOS.COD_OFICIO_MC', 'DESC');
        $dato = $this->db->get("MC_OFICIOS_GENERADOS");
        $data = $this->db->last_query();
        //echo $data;
        if ($dato->num_rows() == 0) {
            return '0';
        } else {
            return $dato->result_array();
        }
    }

    function permiso($id_usuario) {
        $this->db->select("USUARIOS.IDUSUARIO as IDUSUARIO,USUARIOS.COD_REGIONAL, APELLIDOS, NOMBRES,GRUPOS.IDGRUPO");
        $this->db->join("USUARIOS_GRUPOS", "USUARIOS.IDUSUARIO=USUARIOS_GRUPOS.IDUSUARIO");
        $this->db->join("GRUPOS", "USUARIOS_GRUPOS.IDGRUPO=GRUPOS.IDGRUPO");
        $this->db->or_where("(GRUPOS.IDGRUPO", ABOGADO);
        $this->db->or_where("GRUPOS.IDGRUPO", SECRETARIO);
        $this->db->or_where("GRUPOS.IDGRUPO", COORDINADOR . ")", FALSE);
        $this->db->where("USUARIOS.IDUSUARIO", $id_usuario);
        $dato = $this->db->get("USUARIOS");
        return $dato->result_array;
    }

    function abogado($id_usuario) {
        $this->db->select("USUARIOS_GRUPOS.IDUSUARIOGRUPO");
        $this->db->where("USUARIOS_GRUPOS.IDGRUPO", ABOGADO);
        $this->db->where("USUARIOS_GRUPOS.IDUSUARIO", $id_usuario);
        $dato = $this->db->get("USUARIOS_GRUPOS");
        if ($dato->num_rows() == 0)
            return '0';
        return $dato->num_rows();
    }

    function secretario($id_usuario) {
        $this->db->select("USUARIOS_GRUPOS.IDUSUARIOGRUPO");
        $this->db->where("USUARIOS_GRUPOS.IDGRUPO", SECRETARIO);
        $this->db->where("USUARIOS_GRUPOS.IDUSUARIO", $id_usuario);
        $dato = $this->db->get("USUARIOS_GRUPOS");
        if ($dato->num_rows() == 0) {
            return '0';
        } else {
            return $dato->num_rows();
        }
    }

    function coordinador($id_usuario) {
        $this->db->select("USUARIOS_GRUPOS.IDUSUARIOGRUPO");
        $this->db->where("USUARIOS_GRUPOS.IDGRUPO", COORDINADOR);
        $this->db->where("USUARIOS_GRUPOS.IDUSUARIO", $id_usuario);
        $dato = $this->db->get("USUARIOS_GRUPOS");
        if ($dato->num_rows() == 0) {
            return '0';
        } else {
            return $dato->num_rows();
        }
    }

    function datos_gestion($cod_respuesta) {
        $this->db->select("RG.COD_TIPOGESTION, RG.COD_RESPUESTA, RG.NOMBRE_GESTION");
        $this->db->where("RG.COD_RESPUESTA", $cod_respuesta);
        $dato = $this->db->get("RESPUESTAGESTION RG");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    function consulta_mc($reg, $search, $regional) {
        $this->load->library('datatables');
        // $this->db->select('MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR');
        $this->db->select('MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR , MC_MEDIDASCAUTELARES.FECHA_MEDIDAS,MANDAMIENTOPAGO.COD_MANDAMIENTOPAGO,'
                . ' MANDAMIENTOPAGO.FECHA_MANDAMIENTO,MC_MEDIDASCAUTELARES.COD_TIPOGESTION,MC_MEDIDASCAUTELARES.FECHA_GENE_DILIGENCIA_SECUES,'
                . 'FISCALIZACION.COD_FISCALIZACION, FISCALIZACION.COD_ASIGNACION_FISC, '
                . 'ASIGNACIONFISCALIZACION.NIT_EMPRESA,EMPRESA.NOMBRE_EMPRESA,EMPRESA.COD_REGIONAL,EMPRESA.DIRECCION, EMPRESA.TELEFONO_FIJO,REGIONAL.NOMBRE_REGIONAL,'
                . ' REGIONAL.COD_CIUDAD');
        $this->db->join("MANDAMIENTOPAGO", "MANDAMIENTOPAGO.COD_FISCALIZACION=MC_MEDIDASCAUTELARES.COD_FISCALIZACION", 'inner');
        $this->db->join("FISCALIZACION", "MC_MEDIDASCAUTELARES.COD_FISCALIZACION=FISCALIZACION.COD_FISCALIZACION", 'inner');
        $this->db->join("ASIGNACIONFISCALIZACION", "ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC", 'inner');
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=ASIGNACIONFISCALIZACION.NIT_EMPRESA ", 'inner');
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL", 'inner');
        // $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=REGIONAL.COD_CIUDAD", 'inner');
        $this->db->or_where("(MC_MEDIDASCAUTELARES.COD_TIPOGESTION", INICIO1);
        $this->db->or_where("MC_MEDIDASCAUTELARES.COD_TIPOGESTION", INICIO2);
        $this->db->or_where("MC_MEDIDASCAUTELARES.COD_TIPOGESTION", INICIO3 . ")", FALSE);
        $this->db->order_by('MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR', 'ASC');


        $dato = $this->db->get('MC_MEDIDASCAUTELARES');
        $resultado = $this->db->last_query();
        return $dato->result_array();
    }

    function lista_avaluo($reg, $search, $regional) {
        /* Lista todos los avaluos */
        $this->db->select('MA.COD_AVALUO AS PROCESO,MA.COD_TIPORESPUESTA AS COD_RESPUESTA,VW.RESPUESTA AS RESPUESTA,'
                . 'PC.COD_PROCESO_COACTIVO AS COD_PROCESO,PC.ABOGADO AS ABOGADO, PC.COD_PROCESOPJ AS PROCESOPJ,VW.EJECUTADO AS NOMBRE,'
                . 'PC.IDENTIFICACION AS IDENTIFICACION, US.NOMBRES, US.APELLIDOS, VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL, VW.COD_REGIONAL AS COD_REGIONAL');
        $this->db->from('MC_AVALUO MA');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=MA.COD_PROCESO_COACTIVO');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=PC.ABOGADO');
        $where = 'VW.COD_RESPUESTA = MA.COD_TIPORESPUESTA';
        $this->db->where($where);
        $query4 = $this->db->get('');
        $subQuery4 = $this->db->last_query();
        echo $query4;
        $query4 = $query4->result_array();
        return $query4;
    }

    function cabecera($respuesta, $proceso) {
        /* Función que lista los datos básicos del encabezado */
        $this->db->select('');
        $this->db->from('VW_PROCESOS_COACTIVOS VW');
        $this->db->where('VW.COD_RESPUESTA', $respuesta);
        $this->db->where('VW.COD_PROCESO_COACTIVO', $proceso);
        $resultado = $this->db->get();
        $resultado = $resultado->result_array();
        return $resultado[0];
    }

    function get_tipoinmueble() {
        /* Función que lista los tipos de bien */
        $this->db->select(" MC_TIPOINMUEBLE.COD_TIPOINMUEBLE ,  MC_TIPOINMUEBLE.NOMBRE_INMUEBLE ");
        $this->db->where(' MC_TIPOINMUEBLE.ACTIVO', 1, FALSE);
        $dato = $this->db->get("MC_TIPOINMUEBLE");
        return $dato->result_array;
    }

    function get_metodoavaluo() {
        $this->db->select("MC_METODOAVALUO.COD_METODOAVALUO,MC_METODOAVALUO.NOMBRE_METODO_AVALUO");
        $dato = $this->db->get("MC_METODOAVALUO");
        return $dato->result_array();
    }

    function get_tipopropiedad() {
        $this->db->select("MC_TIPOPROPIEDAD.COD_TIPOPROPIEDAD,MC_TIPOPROPIEDAD.NOMBRE_PROPIEDAD");
        $this->db->where('MC_TIPOPROPIEDAD.ACTIVO', 1, FALSE);
        $dato = $this->db->get("MC_TIPOPROPIEDAD");
        return $dato->result_array;
    }

    function get_empresa($id) {
        $this->db->select("EMPRESA.CODEMPRESA, EMPRESA.NOMBRE_EMPRESA, EMPRESA.TELEFONO_FIJO,EMPRESA.DIRECCION, EMPRESA.REPRESENTANTE_LEGAL");
        $this->db->where('EMPRESA.CODEMPRESA', $id);
        $dato = $this->db->get("EMPRESA");
        return $dato->result_array;
    }

    function get_tipoprueba() {
        $this->db->select("MC_TIPOPRUEBA.COD_TIPOPRUEBAS, MC_TIPOPRUEBA.NOMBRE_TIPO_PRUEBAS");
        $dato = $this->db->get("MC_TIPOPRUEBA");
        return $dato->result_array;
    }

    function historico($post) {//Consulta el historico de
        $this->db->select('MC_TRAZABILIDAD.FECHA_MODIFICACION,MC_TRAZABILIDAD.COMENTARIOS,MC_TRAZABILIDAD.GENERADO_POR,'
                . 'USUARIOS.APELLIDOS, USUARIOS.NOMBRES ');
        $this->db->join("USUARIOS", "USUARIOS.IDUSUARIO=MC_TRAZABILIDAD.GENERADO_POR");
        $this->db->where("TIPO_DOCUMENTO", $post['tipo_doc']);
        $this->db->where("COD_PROCESO_COACTIVO", $post['cod_proceso']);
        $this->db->order_by("COD_TRAZABILIDAD", "DESC");
        $dato = $this->db->get('MC_TRAZABILIDAD');

        $datos = $dato->result_array;
        $valor = '';
        if ($datos) {
            foreach ($datos as $consulta) {
                $valor.=$consulta['COMENTARIOS'] . "<br>" . $consulta['FECHA_MODIFICACION'] . "<br>" . $consulta['NOMBRES'] . " " . $consulta['APELLIDOS'] . "<hr>";
            }
        }
        return $valor;
    }

    function consulta_avaluos($post) {

        $query = "SELECT MA.COD_AVALUO,MA.COD_MEDIDACAUTELAR,MA.COD_PROCESO_COACTIVO AS COD_PROCESO,"
                . " MA.UBICACION_BIEN,"
                . " MA.DIRECCION_BIEN,"
                . " MA.LOCALIZACION,MA.ELABORO,MA.PROFESION,MA.LICENCIA_NRO,MA.OBSERVACIONES,"
                . " TB.NOMBRE_TIPO,MA.COD_TIPO_INMUEBLE,PC.COD_PROCESOPJ,VW.NOMBRE_REGIONAL,"
                . " VW.IDENTIFICACION, VW.EJECUTADO "
                . " FROM MC_AVALUO MA, MC_TIPOBIEN TB, VW_PROCESOS_BANDEJA VW,PROCESOS_COACTIVOS PC"
                . " WHERE TB.COD_TIPOBIEN=MA.COD_TIPO_INMUEBLE"
                . " AND MA.COD_TIPORESPUESTA=VW.COD_RESPUESTA"
                . " AND PC.COD_PROCESO_COACTIVO=" . $post['id']
                . " AND MA.COD_PROCESO_COACTIVO=" . $post['id']
                . " AND VW.COD_PROCESO_COACTIVO=" . $post['id'];

        $resultado = $this->db->query($query);
        $resultado = $resultado->result_array();
        //  echo $this->db->last_query();die();
        return $resultado;
    }

    function detalle_avaluo($post) {
//          echo "<pre>";
//        print_r($post);
//        echo "</pre>";die();
        $query = "SELECT MA.COD_AVALUO,MA.COD_MEDIDACAUTELAR,"
                . " MA.UBICACION_BIEN,"
                . " MA.DIRECCION_BIEN,"
                . " MA.LOCALIZACION,MA.ELABORO,MA.COD_AVALUADOR,MA.DIRECCION,MA.FECHA_AVALUO,MA.AREA_TOTAL,MA.PROFESION,MA.LICENCIA_NRO,MA.OBSERVACIONES,"
                . " TB.NOMBRE_TIPO,MA.COD_TIPO_INMUEBLE,PC.COD_PROCESOPJ,VW.NOMBRE_REGIONAL,"
                . " VW.IDENTIFICACION, VW.EJECUTADO "
                . " FROM MC_AVALUO MA, MC_TIPOBIEN TB, VW_PROCESOS_BANDEJA VW,PROCESOS_COACTIVOS PC"
                . " WHERE COD_AVALUO=" . $post['cod_avaluo']
                . " AND TB.COD_TIPOBIEN=MA.COD_TIPO_INMUEBLE"
                . " AND MA.COD_TIPORESPUESTA=VW.COD_RESPUESTA"
                . " AND PC.COD_PROCESO_COACTIVO=" . $post['id']
                . " AND VW.COD_PROCESO_COACTIVO=" . $post['id'];
        //   echo $query;
        $resultado = $this->db->query($query);
        $resultado = $resultado->result_array();
        return $resultado;
    }

    function guarda_registro_avaluo($post) {
        $this->db->trans_begin();
        $this->db->trans_strict(TRUE);

        switch ($post['cod_tipo_bien']):

            case 1://mueble
                if (!empty($post['costo_mueble']))
                    $this->db->set("VALOR_TOTAL_AVALUO", ceil($post['costo_mueble']));
                if (!empty($post['elaboro']))
                    $this->db->set("ELABORO", $post['elaboro']);

                if (!empty($post['profesion']))
                    $this->db->set("PROFESION", $post['profesion']);

                if (!empty($post['cod_avaluador']))
                    $this->db->set("COD_AVALUADOR", $post['cod_avaluador']);

                if (!empty($post['num_licencia']))
                    $this->db->set("LICENCIA_NRO", $post['num_licencia']);

                if (!empty($post['direccion_avaluador']))
                    $this->db->set("DIRECCION", $post['direccion_avaluador']);

                if (!empty($post['fecha']))
                    $this->db->set("FECHA_AVALUO", "TO_DATE('" . $post['fecha'] . "','YY/MM/DD')", FALSE);

                $this->db->where("COD_AVALUO", $post['cod_avaluo'], false);
                $this->db->update("MC_AVALUO");


                //Consulta si hay ya una propiedad para ese avaluo si no esta la inserta en caso contrario la actualiza
                $propiedad = $this->propiedad($post['cod_avaluo']);
                $this->db->set("COD_TIPOINMUEBLE", $post['cod_tipo_bien']);
                if (!empty($post['descripcion_mueble']))
                    $this->db->set("DESCRIPCION_MUEBLE", $post['descripcion_mueble']);
                if (!empty($post['costo_mueble']))
                    $this->db->set("COSTO_TOTAL", ceil($post['costo_mueble']));
                if (!empty($post['observaciones']))
                    $this->db->set("OBSERVACIONES", $post['observaciones']);


                if (empty($propiedad) || $propiedad == 0):
                    $this->db->insert("MC_PROPIEDADESAVALUADAS");
                    //    echo $this->db->last_query();die();
                    $query = $this->db->query("SELECT  MC_Propiedade_cod_propieda_SEQ.CURRVAL FROM dual");
                    $row = $query->row_array();
                    $id = $row['CURRVAL'];
                    //   echo "hola";echo $id;
                    $this->db->set("COD_AVALUO", $post['cod_avaluo']);
                    $this->db->set("COD_PROPIEDAD", $id);
                    $this->db->set("COD_PROCESO_COACTIVO", $post['id'], false);
                    $this->db->insert("MC_AVALUOPROPIEDADES");

                else:
                    $this->db->where("COD_PROPIEDAD", $propiedad['COD_PROPIEDAD']);
                    $this->db->update("MC_PROPIEDADESAVALUADAS");


                //    echo $this->db->last_query();die();
                endif;

                break;
            case 2://INMUEBLE

                if (!empty($post['ubicacion']))
                    $this->db->set("UBICACION_BIEN", $post['ubicacion']);

                if (!empty($post['direccion']))
                    $this->db->set("DIRECCION_BIEN", $post['direccion']);

                if (!empty($post['localizacion']))
                    $this->db->set("LOCALIZACION", $post['localizacion']);

                if (!empty($post['area_total_bien']))
                    $this->db->set("AREA_TOTAL", $post['area_total_bien']);

                if (!empty($post['observaciones']))
                    $this->db->set("OBSERVACIONES", $post['observaciones']);

                if (!empty($post['elaboro']))
                    $this->db->set("ELABORO", $post['elaboro']);

                if (!empty($post['profesion']))
                    $this->db->set("PROFESION", $post['profesion']);

                if (!empty($post['cod_avaluador']))
                    $this->db->set("COD_AVALUADOR", $post['cod_avaluador']);

                if (!empty($post['num_licencia']))
                    $this->db->set("LICENCIA_NRO", $post['num_licencia']);

                if (!empty($post['direccion_avaluador']))
                    $this->db->set("DIRECCION", $post['direccion_avaluador']);

                if (!empty($post['fecha']))
                    $this->db->set("FECHA_AVALUO", "TO_DATE('" . $post['fecha'] . "','DD/MM/YY')", FALSE);

                $this->db->where("COD_AVALUO", $post['cod_avaluo']);
                $this->db->update("MC_AVALUO");

                $propiedad = $this->propiedad($post['cod_avaluo']);

                $this->db->set("COD_TIPOINMUEBLE", $post['cod_tipo_bien']);
                $this->db->set("AREA_TOTAL", $post['area_total_bien']);
                $this->db->set("COSTO_UNITARIO", ceil($post['costo_total']));
                $this->db->set("COSTO_TOTAL", ceil($post['costo_total']));

                if (!empty($post['tipo_propiedad']))
                    $this->db->set("COD_TIPOPROPIEDAD", $post['tipo_propiedad']);

                if (empty($propiedad) || $propiedad == 0):
                    $this->db->insert("MC_PROPIEDADESAVALUADAS");
                    $d = $this->db->last_query();
                    $query = $this->db->query("SELECT  MC_Propiedade_cod_propieda_SEQ.CURRVAL FROM dual");
                    $row = $query->row_array();
                    $id = $row['CURRVAL'];
                    $this->db->set("COD_AVALUO", $post['cod_avaluo']);
                    $this->db->set("COD_PROPIEDAD", $id);
                    $this->db->set("COD_PROCESO_COACTIVO", $post['id']);
                    $this->db->insert("MC_AVALUOPROPIEDADES");
                else:
                    $this->db->where("COD_PROPIEDAD", $propiedad['COD_PROPIEDAD']);
                    $this->db->update("MC_PROPIEDADESAVALUADAS");

                endif;
                break;
            case 3://VEHICULO
                if (!empty($post['observaciones']))
                    $this->db->set("OBSERVACIONES", $post['observaciones']);

                if (!empty($post['elaboro']))
                    $this->db->set("ELABORO", $post['elaboro']);

                if (!empty($post['profesion']))
                    $this->db->set("PROFESION", $post['profesion']);

                if (!empty($post['cod_avaluador']))
                    $this->db->set("COD_AVALUADOR", $post['cod_avaluador']);

                if (!empty($post['num_licencia']))
                    $this->db->set("LICENCIA_NRO", $post['num_licencia']);

                if (!empty($post['direccion_avaluador']))
                    $this->db->set("DIRECCION", $post['direccion_avaluador']);

                if (!empty($post['fecha']))
                    $this->db->set("FECHA_AVALUO", "TO_DATE('" . $post['fecha'] . "','DD/MM/YY')", FALSE);

                $this->db->where("COD_AVALUO", $post['cod_avaluo']);
                $this->db->update("MC_AVALUO");



                $propiedad = $this->propiedad($post['cod_avaluo']);
                $this->db->set("COD_TIPOINMUEBLE", $post['tipo_inmueble'], FALSE);
                $this->db->set("PLACA_VEHICULO", $post['placa']);
                $this->db->set("MARCA_VEHICULO", $post['marca']);
                $this->db->set("NUMERO_CHASIS", $post['numero_chasis']);
                $this->db->set("MODELO_VEHICULO", $post['modelo_vehiculo']);
                $this->db->set("SERVICIO_VEHICULO", $post['servicio_vehiculo']);
                $this->db->set("COLOR_VEHICULO", $post['color_vehiculo']);
                $this->db->set("COD_TIPOVEHICULO", $post['cod_tipovehiculo']);
                $this->db->set("OBSERVACIONES", $post['observaciones']);

                if (empty($propiedad) || $propiedad == 0):
                    $this->db->insert("MC_PROPIEDADESAVALUADAS");
                    $query = $this->db->query("SELECT  MC_Propiedade_cod_propieda_SEQ.CURRVAL FROM dual");
                    $row = $query->row_array();
                    $id = $row['CURRVAL'];
                    $this->db->set("COD_AVALUO", $post['cod_avaluo']);
                    $this->db->set("COD_PROPIEDAD", $id);
                    $this->db->set("COD_PROCESO_COACTIVO", $post['id']);
                    $this->db->insert("MC_AVALUOPROPIEDADES");
                else:

                    $this->db->where("COD_PROPIEDAD", $propiedad['COD_PROPIEDAD']);
                    $this->db->update("MC_PROPIEDADESAVALUADAS");
                endif;
                break;

        endswitch;

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }

        return TRUE;
    }

    function detalle_propiedad($cod_avaluo) {
        $resultado1 = 0;
        $this->db->select("COD_PROPIEDAD");
        $this->db->from("MC_AVALUOPROPIEDADES");
        $this->db->where("COD_AVALUO", $cod_avaluo);
        $resultado = $this->db->get();
        $d = $this->db->last_query();

        if ($resultado->num_rows() > 0):
            $propiedad = $resultado->result_array();
            $propiedad = $propiedad[0];
            $this->db->select("");
            $this->db->from("MC_PROPIEDADESAVALUADAS");
            $this->db->where("COD_PROPIEDAD", $propiedad['COD_PROPIEDAD']);
            $resultado = $this->db->get();
            //
            //
            $d = $this->db->last_query();

            $resultado = $resultado->result_array();
            $resultado1 = $resultado[0];
        endif;

        return $resultado1;
    }

    function guarda_propiedad_inmueble($post) {

        $this->db->set("COD_TIPOINMUEBLE", $post['post']['tipo_inmueble']);
        $this->db->set("COD_TIPOPROPIEDAD", $post['post']['tipo_propiedad']);
        if ($post['post']['tipo_propiedad'] == 1) {
            $this->db->set("AREA_TOTAL", $post['post']['area_total_bien']);
            $this->db->set("COSTO_TOTAL", $post['post']['costototal_total']);
        } else if ($post['post']['tipo_propiedad'] == 2) {
            $this->db->set("AREA_TOTAL", $post['post']['area_total_const']);
            $this->db->set("COSTO_UNITARIO", $post['post']['costounitario_total_const']);
            $this->db->set("COSTO_TOTAL", $post['post']['costototal_total_const']);
        }

        $this->db->set("OBSERVACIONES", $post['post']['observaciones']);
        $this->db->insert("MC_PROPIEDADESAVALUADAS");
        return TRUE;
    }

    function guarda_propiedad_mueble($post) {
        $this->db->set("COD_TIPOINMUEBLE", $post['post']['tipo_inmueble']);
        $this->db->set("DESCRIPCION_MUEBLE", $post['post']['descripcion_mueble']);
        $this->db->set("COSTO_MUEBLE", $post['post']['costo_mueble']);
        $this->db->set("OBSERVACIONES", $post['post']['observaciones']);
        $this->db->insert("MC_PROPIEDADESAVALUADAS");
        return TRUE;
    }

    function guarda_propiedad_vehiculo($post) {
        //print_r($post['post']);die();
        //  $this->db->set("COD_TIPOINMUEBLE", $post['post']['tipo_inmueble']);
        $this->db->set("PLACA_VEHICULO", $post['post']['placa']);
        $this->db->set("MARCA_VEHICULO", $post['post']['marca']);
        $this->db->set("NUMERO_CHASIS", $post['post']['numero_chasis']);
        $this->db->set("MODELO_VEHICULO", $post['post']['modelo_vehiculo']);
        $this->db->set("SERVICIO_VEHICULO", $post['post']['servicio_vehiculo']);
        $this->db->set("COLOR_VEHICULO", $post['post']['color_vehiculo']);
        $this->db->set("COD_TIPOVEHICULO", $post['post']['cod_tipovehiculo']);
        $this->db->set("OBSERVACIONES", $post['post']['observaciones']);
        $this->db->insert("MC_PROPIEDADESAVALUADAS");
        return TRUE;
    }

    function consulta_mcavaluo($regional = FALSE, $estados = FALSE, $id = FALSE, $cod_proceso) {

        $query = "SELECT MA.COD_MEDIDACAUTELAR AS MEDIDA_CAUTELAR,TO_CHAR(MA.NOTIFICACION_EFECTIVA,'DD/MM/YYYY') AS NOTIFICACION_EFECTIVA,MA.COD_PROCESO_COACTIVO AS COD_PROCESO,VW.NOMBRE_REGIONAL,
                MC.FECHA_MEDIDAS,VW.IDENTIFICACION, VW.EJECUTADO,VW.RESPUESTA,VW.IDENTIFICACION,VW.EJECUTADO,VW.TELEFONO,VW.DIRECCION,
                PC.COD_PROCESOPJ,MA.COD_TIPORESPUESTA, LISTAGG (MA.COD_AVALUO,',') WITHIN GROUP (ORDER BY MA.COD_AVALUO) AVALUOS
                FROM MC_AVALUO MA 
                INNER JOIN PROCESOS_COACTIVOS PC ON PC.COD_PROCESO_COACTIVO=MA.COD_PROCESO_COACTIVO
                INNER JOIN VW_PROCESOS_COACTIVOS VW ON VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO 
                INNER JOIN MC_MEDIDASCAUTELARES MC ON MA.COD_MEDIDACAUTELAR=MC.COD_MEDIDACAUTELAR
                WHERE VW.COD_RESPUESTA = MA.COD_TIPORESPUESTA";
        if (!empty($cod_proceso)):
            $query = $query . ' AND PC.COD_PROCESO_COACTIVO=' . $cod_proceso;
        endif;
        $query = $query . " GROUP BY MA.COD_MEDIDACAUTELAR ,MA.COD_PROCESO_COACTIVO ,VW.NOMBRE_REGIONAL,
                MC.FECHA_MEDIDAS,VW.IDENTIFICACION, VW.EJECUTADO,VW.RESPUESTA,PC.COD_PROCESOPJ,MA.COD_TIPORESPUESTA,
                VW.IDENTIFICACION,VW.EJECUTADO,VW.TELEFONO,VW.DIRECCION,MA.NOTIFICACION_EFECTIVA
                 ";
        $resultado = $this->db->query($query);
        $resultado = $resultado->result_array();
        return $resultado;
    }

    function totalData($search, $regional) {
        $this->load->library('datatables');
        // $this->db->select('MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR');
        $this->db->select('MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR, MC_MEDIDASCAUTELARES.FECHA_MEDIDAS,MANDAMIENTOPAGO.COD_MANDAMIENTOPAGO, MANDAMIENTOPAGO.FECHA_MANDAMIENTO,'
                . 'FISCALIZACION.COD_FISCALIZACION, FISCALIZACION.COD_ASIGNACION_FISC, '
                . 'ASIGNACIONFISCALIZACION.NIT_EMPRESA,EMPRESA.NOMBRE_EMPRESA,EMPRESA.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL,'
                . 'REGIONAL.COD_CIUDAD');
        $this->db->join("MANDAMIENTOPAGO", "MANDAMIENTOPAGO.COD_FISCALIZACION=MC_MEDIDASCAUTELARES.COD_FISCALIZACION", 'inner');
        $this->db->join("FISCALIZACION", "MC_MEDIDASCAUTELARES.COD_FISCALIZACION=FISCALIZACION.COD_FISCALIZACION", 'inner');
        $this->db->join("ASIGNACIONFISCALIZACION", "ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC", 'inner');
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=ASIGNACIONFISCALIZACION.NIT_EMPRESA ", 'inner');
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL", 'inner');
        //$this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=REGIONAL.COD_CIUDAD", 'inner');
        $this->db->or_where("(MC_MEDIDASCAUTELARES.COD_TIPOGESTION", INICIO1);
        $this->db->or_where("MC_MEDIDASCAUTELARES.COD_TIPOGESTION", INICIO2);
        $this->db->or_where("MC_MEDIDASCAUTELARES.COD_TIPOGESTION", INICIO3 . ")", FALSE);

        $dato = $this->db->get('MC_MEDIDASCAUTELARES');

        if ($dato->num_rows() == 0) {
            return '0';
        }

        return $dato->num_rows();
    }

    function totalData2() {
        $this->load->library('datatables');
        // $this->db->select('MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR');
        $this->db->select('MC_AVALUO.COD_AVALUO, MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR, MC_MEDIDASCAUTELARES.FECHA_MEDIDAS,MANDAMIENTOPAGO.COD_MANDAMIENTOPAGO, MANDAMIENTOPAGO.FECHA_MANDAMIENTO,'
                . 'FISCALIZACION.COD_FISCALIZACION, FISCALIZACION.COD_ASIGNACION_FISC , '
                . 'ASIGNACIONFISCALIZACION.NIT_EMPRESA,EMPRESA.NOMBRE_EMPRESA,EMPRESA.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL,'
                . 'REGIONAL.COD_CIUDAD');

        $this->db->join("MC_MEDIDASCAUTELARES", "MC_AVALUO.COD_MEDIDACAUTELAR=MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR", 'inner');
        $this->db->join("MANDAMIENTOPAGO", "MANDAMIENTOPAGO.COD_FISCALIZACION=MC_MEDIDASCAUTELARES.COD_FISCALIZACION", 'inner');
        $this->db->join("FISCALIZACION", "MC_MEDIDASCAUTELARES.COD_FISCALIZACION=FISCALIZACION.COD_FISCALIZACION", 'inner');
        $this->db->join("ASIGNACIONFISCALIZACION", "ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC", 'inner');
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=ASIGNACIONFISCALIZACION.NIT_EMPRESA ", 'inner');
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL", 'inner');
        // $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=REGIONAL.COD_CIUDAD", 'inner');
//      $this->db->or_where("(MC_MEDIDASCAUTELARES.COD_TIPOGESTION", INICIO1);
//      $this->db->or_where("MC_MEDIDASCAUTELARES.COD_TIPOGESTION", INICIO2);
//      $this->db->or_where("MC_MEDIDASCAUTELARES.COD_TIPOGESTION", INICIO3 . ")", FALSE);
        $dato = $this->db->get('MC_AVALUO');
        return $dato->result_array;
        if ($dato->num_rows() == 0)
            return '0';

        return $dato->num_rows();
    }

    function soporte_subido($file, $post) {//Guarda el soporte de la recepcion de la notificacion personal en Oficios Generados
        $this->db->set("COD_AVALUO", $post['id']);
        $this->db->set("FECHA_CREACION", FECHA, false);
        $this->db->set("NOMBRE_OFICIO", DOC_SOPORTE);
        $this->db->set("CREADO_POR", ID_USER);
        $this->db->set("TIPO_DOCUMENTO", DOC_SOPORTE_RECEPCION_NOTIFICACION);
        $this->db->set("RUTA_DOCUMENTO_GEN", $file);
        $this->db->set("OBSERVACIONES", $post['descripcion_datos']);
        $this->db->insert("MC_OFICIOS_GENERADOS");
        $this->db->set("COD_TIPORESPUESTA", NOT_PERSONAL_ENVIADA);
        $this->db->where("COD_AVALUO", $post['id']);
        $this->db->update("MC_AVALUO");

        if ($this->db->affected_rows() == '1') :
//            //insert en MC_NOTIFICACIONAVALUO
//            $this->db->set("COD_AVALUO", $post['id'], false);
//            $this->db->set("FECHA_NOTIFICACION", "TO_DATE('" . $post['fecha'] . "','YY/MM/DD')", FALSE);
//            $this->db->set("NOTIFICADO_POR", "Notificacion P");
//            $this->db->set("RADICADO_ONBASE", $post['num_radicado']);
////           // $this->db->set("DOCUMENTO_AVALUO", $post['']);
//            $this->db->set("DOCUMENTO_NOTIFICACION", $post['documento_notificacion']);
//            $this->db->set("FECHA_DOC_NOTIFICACION", "TO_DATE('" . $post['fecha_documento_not'] . "','YY/MM/DD')", FALSE);
//            $this->db->insert("MC_NOTIFICACIONAVALUO");
            return true;
        else:
            return FALSE;
        endif;
    }

    function update_mc_avaluo($post) {

        /* Función que guarda la información de los documentos adjuntos que han sido aprobados y firmados */
        /*    Si existen observaciones las guarda     * */
        if (!empty($post['post']['observaciones'])) {
            $this->db->set("COD_PROCESO_COACTIVO", $post['id']);
            $this->db->set("FECHA_MODIFICACION", FECHA, FALSE);
            $this->db->set("COMENTARIOS", $post['obser']);
            $this->db->set("GENERADO_POR", ID_USER);
            $this->db->set("TIPO_DOCUMENTO", $post['tipo_doc']);
            $this->db->insert("MC_TRAZABILIDAD");
        }
        //  echo "hola"; die();
        $this->oficios_generados($post);
        if ($this->db->affected_rows() == '1') {
            //  echo "hola";
            $this->db->set("COD_TIPORESPUESTA", $post['cod_siguiente']);
            $this->db->where("COD_PROCESO_COACTIVO", $post['id']);
            $this->db->update("MC_AVALUO");
            return TRUE;
        } else {
            return FALSE;
        }


        for ($i = 0; $i < $post['cantidad']; $i++) :

            $cod_remate = $this->mc_remate($post['avaluos'][$i]); //verifica que no exista un registro en mc_remate para ese avaluo
            if ($post['cod_siguiente'] == AUTO_DECLARA_FIRMEZA_AVALUO_F) :
                $this->db->set("COD_AVALUO", $post['avaluos'][$i]);
                $this->db->set("REMATE_DESIERTO", 0, FALSE);
                $this->db->set("COD_RESPUESTA", $post['cod_siguiente']);
                $this->db->set("COD_PROCESO_COACTIVO", $post['id']);
                $this->db->insert("MC_REMATE");
            endif;
        endfor;
    }

    function mc_remate($cod_avaluo) {
        $this->db->select("MC_REMATE.COD_REMATE");
        $this->db->where("MC_REMATE.COD_AVALUO", $cod_avaluo);
        $dato = $this->db->get("MC_REMATE");
        if ($dato->num_rows() == 0) {
            return '0';
        } else {
            return $dato->num_rows();
        }
    }

    //guarda documento
    function guardar_gestion_documento($post) {//cuando el secretario o coodinador rechazan o aprueban el documento del auto
        $this->db->set("COD_PROCESO_COACTIVO", $post['id']);
        $this->db->set("FECHA_MODIFICACION", FECHA, false);
        $this->db->set("COMENTARIOS", $post['observaciones']);
        $this->db->set("GENERADO_POR", ID_USER);
        $this->db->set("TIPO_DOCUMENTO", $post['tipo_doc']);
        $this->db->insert("MC_TRAZABILIDAD");
        for ($i = 0; $i < $post['cantidad']; $i++):
            $this->db->set("COD_TIPORESPUESTA", $post['cod_siguiente']);
            $this->db->where("COD_PROCESO_COACTIVO", $post['id']);
            $this->db->update("MC_AVALUO");
        endfor;
        $this->oficios_generados($post);

        return TRUE;
    }

    function guarda_recibo_honorarios($post) {
        $this->db->set("COD_TIPORESPUESTA", $post['post']['cod_siguiente']);
        $this->db->set('RECIBO_PAGO_HONORARIOS_REC', $post['post']['honorarios']);
        $this->db->where('COD_PROCESO_COACTIVO', $post['post']['id'], FALSE);
        $this->db->update('MC_AVALUO');
        if ($this->db->affected_rows() == '1') {
            echo "hola23";
            return $post['post'];
        }
    }

    function no_objeto($post) {
        for ($i = 0; $i < $post['cantidad']; $i++) :
            $this->db->set("COD_TIPORESPUESTA", $post['cod_siguiente']);
            $this->db->where('COD_AVALUO', $post['avaluos'][$i], FALSE);
            $this->db->update('MC_AVALUO');
        endfor;
        return TRUE;
    }

    function requiere_correccion($post) {
        // echo $post['post']['req_correccion'];die();
        if ($post['post']['req_correccion'] == 1) {
            $requiere = 'S';
            $codigo_siguiente = PRUEBAS_REALIZADAS_REQ_CORRECCION;
        } else if ($post['post']['req_correccion'] == 2) {
            $requiere = 'N';
            $codigo_siguiente = PRUEBAS_REALIZADAS_NO_REQ_CORRECCION;
        }
        for ($i = 0; $i < $post['post']['cantidad']; $i++) :
            $this->db->set("COD_TIPORESPUESTA", $codigo_siguiente);
            $this->db->where("COD_AVALUO", $post['post']['avaluos'][$i]);
            $this->db->update("MC_AVALUO");

        endfor;


        return TRUE;
    }

    function objeto($post) {

        $this->db->set("COD_TIPORESPUESTA", $post['post']['cod_siguiente']);
        $this->db->set("PRESENTA_OBJECION", 'S');
        // $this->db->set("FECHA_PRESENTA_OBJECION",  "to_date( " . $post['post']['fecha']. ",'dd/mm/yyyy HH24:MI:SS')",FALSE);
        $this->db->set("OBSERVACIONES_OBJECION", $post['post']['observaciones']);
        $this->db->set("DECRETA_PRUEBAS_OBJECION", $post['post']['decreta_pruebas']);
        $this->db->where('COD_PROCESO_COACTIVO', $post['post']['cod_proceso'], FALSE);
        $this->db->update('MC_AVALUO');
        $d = $this->db->last_query();

        return TRUE;
    }

    function guardar_mc_avaluo($post) {

        for ($i = 0; $i < $post['cantidad']; $i++):
            $this->db->set("COD_TIPORESPUESTA", $post['cod_siguiente']);
            $this->db->where('COD_PROCESO_COACTIVO', $post['id'], FALSE);
            $this->db->where('COD_AVALUO', $post['avaluos'][$i], FALSE);
            $this->db->update('MC_AVALUO');
        endfor;

        //guarda la información del documento generado
        $this->oficios_generados($post);
        //guarda las observaciones si existen
        if (!empty($post['post']['observaciones'])) {
            $this->db->set("COD_PROCESO_COACTIVO", $post['id']);
            $this->db->set("FECHA_MODIFICACION", FECHA, false);
            $this->db->set("COMENTARIOS", $post['observaciones']);
            $this->db->set("GENERADO_POR", ID_USER);
            $this->db->set("TIPO_DOCUMENTO", $post['tipo_doc']);
            $this->db->insert("MC_TRAZABILIDAD");
        }
        return TRUE;
    }

    function guardar_notificacion_correo($post) {
        $this->db->set("COD_TIPORESPUESTA", $post['post']['cod_siguiente']);
        //$this->db->set("COD_TIPORESPUESTA", 379);
        $this->db->where('COD_PROCESO_COACTIVO', $post['post']['id'], FALSE);
        $this->db->update('MC_AVALUO');
        $this->db->set("COD_AVALUO", $post['post']['id']);
        $this->db->set("FECHA_CREACION", FECHA, false);
        $this->db->set("NOMBRE_OFICIO", DOC_NOTIFICACION_CORREO);
        $this->db->set("CREADO_POR", ID_USER);
        $this->db->set("TIPO_DOCUMENTO", DOC_NOTIFICACIONCORREO);
        $this->db->set("RUTA_DOCUMENTO_GEN", $post['post']['nombre'] . ".txt");
        $this->db->insert("MC_OFICIOS_GENERADOS");
        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }
    }

    function guardar_pruebas($post) {

        $datos = $post['post'];
        ;
        $cantidad = count($post['post']['avaluos']);
        $fecha = 'SYSDATE';
        for ($i = 0; $i < $cantidad; $i++) {
            $this->db->set("COD_AVALUO", $datos['avaluos'][$i], FALSE);
            $this->db->set("COD_TIPOPRUEBA", 1, FALSE);
            //  $this->db->set("COD_TIPOPRUEBA", $datos['tipoprueba'][$i], FALSE);
            if ($datos['nombre_carpeta'][$i]) {
                $this->db->set("RUTA_DOC_PRUEBAS", $datos['nombre_carpeta'][$i]);
            }
            // $this->db->set("OBSERVACIONES", $datos['anotaciones']);
            $this->db->set("CREADA_POR", $post['usuario']);
            $this->db->set("ACTIVA", 1, FALSE);
            $this->db->set("FECHA_PRESENTACION", "to_date( " . $fecha . ",'dd/mm/yyyy HH24:MI:SS')", FALSE);
            $this->db->insert("MC_PRUEBAS");
            //   
        }
        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }
    }

    function rutas_pruebas($post) {
        $this->db->select("MC_PRUEBAS.RUTA_DOC_PRUEBAS, MC_PRUEBAS.COD_PRUEBAS_AVALUO");
        $this->db->where("COD_AVALUO", $post['post']['idproceso']);
        $dato = $this->db->get("MC_PRUEBAS");
        if ($dato->num_rows() == 0) {
            return '0';
        } else {
            return $dato->result_array;
        }
    }

    function oficios_generados($post) {

        $this->db->set("COD_PROCESO_COACTIVO", $post['id']);
        $this->db->set("FECHA_CREACION", FECHA, false);
        $this->db->set("NOMBRE_OFICIO", $post['titulo']);
        define('NOT_PERSONAL_APROBADA', 393);
        switch ($post['cod_siguiente']) {
            case AUTO_AVALUOBIENES_GENERADO://CUANDO EL ABOGADO GENERA EL AUTO
            case AUTO_NOMBRA_PERITO_GENERAD0:
            case NOT_PERSONAL_G:
            case AUTO_APERTURA_PRUEBAS_G;
            case AUTO_CORRECION_AVALUO_G:
            case AUTO_RESUELVE_OBJ_AVALUO_G:
            case AUTO_DECLARA_FIRMEZA_AVALUO_G:
            case NOTIFICACION_CORREO_GENERADA:
            case NOTIFICACION_POR_CORREO_GENERADA:
            case AUTO_DICTAMEN_TRASLADO_GENERADO:
            case NOT_PERSONAL_ENVIADA:
            case NOTIFICACION_POR_CORREO_GENERADA:
                $this->db->set("CREADO_POR", ID_USER);
                $this->db->set("TIPO_DOCUMENTO", $post['tipo_doc']);
                $this->db->set("RUTA_DOCUMENTO_GEN", $post['ruta']);
                $this->db->insert("MC_OFICIOS_GENERADOS");

                if (!empty($post['observaciones'])) {
                    $this->db->set("COD_PROCESO_COACTIVO", $post['id']);
                    $this->db->set("FECHA_MODIFICACION", FECHA, FALSE);
                    $this->db->set("COMENTARIOS", $post['observaciones']);
                    $this->db->set("GENERADO_POR", ID_USER);
                    $this->db->set("TIPO_DOCUMENTO", $post['tipo_doc']);
                    $this->db->insert("MC_TRAZABILIDAD");
                }

                break;
            case AUTO_AVALUOBIENES_PRE_APROBADO://CUANDO EL SECRETARIO PRE-APRUEBA EL AUTO
            case AUTO_NOMBRA_PERITO_PRE_APROBADO:
            case AUTO_APERTURA_PRUEBAS_P:
            case AUTO_CORRECION_AVALUO_P:
            case AUTO_RESUELVE_OBJ_AVALUO_P:
            case AUTO_DECLARA_FIRMEZA_AVALUO_P:
            case NOTIFICACION_CORREO_APROBADA:
            case AUTO_DICTAMEN_TRASLADO_PRE_APROBADO;
            case NOTIFICACION_CORREO_APROBADA:

                $this->db->set("REVISADO_POR", ID_USER);
                $this->db->set("RUTA_DOCUMENTO_GEN", $post['ruta']);
                $this->db->where('COD_OFICIO_MC', $post['cod_oficio'], FALSE);
                $this->db->where("COD_PROCESO_COACTIVO", $post['id']);
                $this->db->update('MC_OFICIOS_GENERADOS');

                break;
            case NOT_PERSONAL_REVISADA_APROBADA:
                $this->db->set("REVISADO_POR", ID_USER);
                // $this->db->set("RUTA_DOCUMENTO_GEN", $post['nombre']);
                $this->db->set("RUTA_DOCUMENTO_GEN", $post['ruta']);
                $this->db->where('COD_OFICIO_MC', $post['cod_oficio'], FALSE);
                $this->db->where("COD_PROCESO_COACTIVO", $post['id']);

                $this->db->update('MC_OFICIOS_GENERADOS');
                break;
//            case AUTO_AVALUOBIENES_RECHAZADO://CUANDO EL SECRETARIO RECHAZA EL AUTO DE AVALUO QUE ORDENA....
            case AUTO_NOMBRA_PERITO_RECHAZADO:
            case AUTO_APERTURA_PRUEBAS_R:
            case AUTO_CORRECION_AVALUO_R:
            case AUTO_RESUELVE_OBJ_AVALUO_R:
            case AUTO_DECLARA_FIRMEZA_AVALUO_R:
            case NOTIFICACION_CORREO_DEVUELTA:
            case AUTO_DICTAMEN_TRASLADO_APROBADO_RECHAZADO;
            case NOTIFICACION_PERSONAL_DEVUELTA:
                $this->db->set("REVISADO_POR", ID_USER);
                $this->db->set("RUTA_DOCUMENTO_GEN", $post['ruta']);
                $this->db->where('COD_OFICIO_MC', $post['cod_oficio'], FALSE);
                $this->db->where("COD_PROCESO_COACTIVO", $post['id']);
                $this->db->update('MC_OFICIOS_GENERADOS');
                break;

            case AUTO_APERTURA_PRUEBAS_APROBADO:
            case AUTO_AVALUOBIENES_APROBADO:
            case AUTO_NOMBRA_PERITO_APROBADO:
            case AUTO_RESUELVE_OBJ_AVALUO_A:
            case AUTO_DECLARA_FIRMEZA_AVALUO_A:
            case NOTIFICACION_CORREO_APROBADA:
            case NOT_PERSONAL_APROBADA:
            case AUTO_DICTAMEN_TRASLADO_APROBADO:
            case NOTIFICACION_POR_CORREO_APROBADA_FIRMADA:

                $this->db->set("APROBADO_POR", ID_USER);
                $this->db->set("RUTA_DOCUMENTO_GEN", $post['ruta']);
                if (!empty($post['post']['observaciones_aprob']))
                    $this->db->set("OBSERVACIONES", $post['observaciones']);
                $this->db->where('COD_OFICIO_MC', $post['cod_oficio'], FALSE);
                $this->db->where("COD_PROCESO_COACTIVO", $post['id']);
                $this->db->update('MC_OFICIOS_GENERADOS');
                break;
            //documentos adjuntos
            case AUTO_APERTURA_PRUEBAS_F:
            case AUTO_AVALUOBIENES_APROBADOFIRMADO:
            case AUTO_RESUELVE_OBJ_AVALUO_F:
            case AUTO_DECLARA_FIRMEZA_AVALUO_F:
            case DOC_AUTO_RESUELVE_OBJECION_AVALUO :
            case AUTO_NOMBRA_PERITO_APROBADOFIRMADO:
            case NOTIFICACION_CORREO_APROBADA_FIRMADA:
            case AUTO_DICTAMEN_TRASLADO_APROBADO_FIRMADO:

                $this->db->set("CREADO_POR", ID_USER);
                // $this->db->set("RUTA_DOCUMENTO_GEN", $post['nombre']);
                $this->db->set("RUTA_DOCUMENTO_GEN", $post['ruta']);
                if (!empty($post['observaciones']))
                    $this->db->set("OBSERVACIONES", $post['observaciones']);

                $this->db->where('COD_OFICIO_MC', $post['cod_oficio'], FALSE);
                $this->db->where("COD_PROCESO_COACTIVO", $post['id']);
                $this->db->update('MC_OFICIOS_GENERADOS');

                break;
            case NOT_PERSONAL_ENVIADA:
                //$this->db->set("RUTA_DOCUMENTO_GEN", $post['nombre']);
                $this->db->set("RUTA_DOCUMENTO_GEN", $post['ruta']);
                $this->db->set("APROBADO_POR", ID_USER);
                $this->db->set("TIPO_DOCUMENTO", $post['tipo_doc']);
                $this->db->insert("MC_OFICIOS_GENERADOS");
                if (!empty($post['onbase']))
                    $this->db->set("NRO_RADICADO", $post['onbase']);
                if (!empty($post['post']['comentarios']))
                    $this->db->set("OBSERVACIONES", $post['comentarios']);
                if (!empty($post['post']['fechanotificacion']))
                    $this->db->set("FECHA_RADICADO", $post['fechanotificacion']);
                break;
            //Guarda la información  del avalúo recibido
            case AVALUO_RECIBIDO:
                $this->db->set("RUTA_DOCUMENTO_GEN", $post['ruta']);
                $this->db->set("APROBADO_POR", ID_USER);
                $this->db->set("TIPO_DOCUMENTO", $post['tipo_doc']);
                $this->db->insert("MC_OFICIOS_GENERADOS");
                break;
                return TRUE;
        }
    }

    function propiedad($cod_avaluo) {
        $propiedad = 0;
        $this->db->select("COD_PROPIEDAD");
        $this->db->from("MC_AVALUOPROPIEDADES");
        $this->db->where("COD_AVALUO", $cod_avaluo);
        $resultado = $this->db->get();
        if ($resultado->num_rows() > 0):
            $propiedad = $resultado->result_array();
            $propiedad = $propiedad[0];
        endif;
        return $propiedad;
    }

    function tipo_vehiculo() {
        $this->db->select("TV.COD_TIPOVEHICULO,TV.NOMBRE_TIPO");
        $this->db->from("MC_TIPOVEHICULO TV");
        $dato = $this->db->get();
        return $dato->result_array;
    }

    function agrega_expediente($cod_respuesta, $nom_doc, $ruta_doc, $num_radicado = FALSE, $fecha_radicado = FALSE, $cod_tipoexp, $subproceso, $id_usuario, $cod_coactivo, $fecha_not_efectiva = FALSE) {

        $this->db->set("COD_PROCESO_COACTIVO", $cod_coactivo);
        $this->db->set("COD_RESPUESTAGESTION", $cod_respuesta);
        $this->db->set("NOMBRE_DOCUMENTO", $nom_doc);
        $this->db->set("RUTA_DOCUMENTO", $ruta_doc);
        $this->db->set("FECHA_DOCUMENTO", 'SYSDATE', FALSE);
        if (!empty($num_radicado))
            $this->db->set("NUMERO_RADICADO", $num_radicado);
        if (!empty($fecha_radicado))
            $this->db->set('FECHA_RADICADO', "TO_DATE('" . $fecha_radicado . "','yyyy/mm/dd')", FALSE);
        if (!empty($fecha_not_efectiva)):
            $this->db->set("FECHA_NOTIFICACION_EFECTIVA", "TO_DATE('" . $fecha_not_efectiva . "','yyyy/mm/dd')", FALSE);
        endif;
        $this->db->set("COD_TIPO_EXPEDIENTE", $cod_tipoexp);
        $this->db->set("SUB_PROCESO", $subproceso);
        $this->db->set("ID_USUARIO", $id_usuario);
        $this->db->insert("EXPEDIENTE");
        if ($this->db->affected_rows() == '1') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function bloqueos($post) {
//      
        $this->db->set('COD_TIPORESPUESTA', $post['cod_siguiente']);
        $this->db->where('COD_PROCESO_COACTIVO',$post['id']);
        $this->db->update('MC_AVALUO');
        return true;
    }

}
