<?php

class Devoluciones_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function actualizar_devolucion($data, $devolucion, $proceso) {
        $this->db->trans_start();
        $this->db->where('COD_DEVOLUCION', $devolucion);
        $this->db->where('COD_DEVOLUCION', $devolucion);
        if ($proceso == 'comunicacion') {
            $this->db->set('VERIFICA_SOPORTES', $data['VERIFICA_SOPORTES']);
            $this->db->set('COD_RESPUESTA', $data['COD_RESPUESTA']);
        }
        if ($proceso == 'certificacion' || $proceso == 'respuesta') {
            $this->db->set('COD_RESPUESTA', $data['COD_RESPUESTA']);
            $this->db->set('ASIGNADO', $data['ASIGNADO']);
        }
        if ($proceso == 'respuesta_empresario' || $proceso == 'respuesta' || $proceso == 'adicionales' || $proceso == 'adicionales_recibidos_general' || $proceso == 'adicionales_recibidos' || $proceso == 'adicionales_general' || $proceso == 'recurso' || $proceso == 'respuesta_general' || $proceso == 'recurso_general') {
            $this->db->set('COD_RESPUESTA', $data['COD_RESPUESTA']);
        }

        $this->db->update('SOLICITUDDEVOLUCION');
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() >= 0) {
            $this->db->trans_complete();
            return TRUE;
        }
        return FALSE;
    }

    function actualizar_documentos($data, $devolucion) {
        $this->db->trans_start();
        $this->db->where('COD_DEVOLUCION', $devolucion);
        $this->db->where('ESTADO', 1);
        $this->db->set('NOMBRE_DOCUMENTO', $data['NOMBRE_DOCUMENTO']);
        $this->db->set('REVISADO_POR', $data['REVISADO_POR']);
        $this->db->set('COMENTADO_POR', $data['COMENTADO_POR']);
        $this->db->set('COMENTARIO', $data['COMENTARIO']);
        $this->db->set('COD_RESPUESTA', $data['COD_RESPUESTA']);
        $this->db->set('ASIGNADO', $data['ASIGNADO']);
        $this->db->set('ESTADO', $data['ESTADO']);
        $this->db->set('APROBADO', $data['APROBADO']);
        $this->db->update('NOTIFICACIONDEVOLUCION');
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() >= 0) {
            $this->db->trans_complete();
            return TRUE;
        }
        return FALSE;
    }

    public function get_comentarios_proceso($cod_devolucion) {
        if (!empty($cod_devolucion)) :
            $dato = $this->db->query("SELECT ND.COMENTARIO, TO_CHAR(ND.FECHA_CREACION, 'YYYY/MM/DD HH:MM:SS') AS FECHA_CREACION, 
            ND.COMENTADO_POR, U.NOMBRES, U.APELLIDOS FROM NOTIFICACIONDEVOLUCION ND JOIN USUARIOS U ON U.IDUSUARIO = ND.COMENTADO_POR 
            WHERE ND.COD_DEVOLUCION = '$cod_devolucion' AND ESTADO IN (1,2) ORDER BY ND.COD_NOTIFICACION_DEVOLUCION DESC ");
            if ($dato->num_rows() > 0) {
                return $dato->result_array();
            }
        endif;
    }

    /*
     * SALARIO MINIMO VIGENTE
     */

    public function get_salariominimo() {
        $dato = $this->db->query("SELECT SALARIO_VIGENTE
                FROM SALARIO
                WHERE TO_CHAR(VIGENCIA_DESDE, 'YYYY') = TO_CHAR(SYSDATE, 'YYYY')");
        if ($dato->num_rows() > 0) {
            return $dato->result_array();
        }
    }

    function get_fechadevolucion($cod_devolucion) {
        $this->db->select('to_char("ND"."FECHA_CREACION", ' . "'YYYY-MM-DD') AS FECHA_CREACION", FALSE);
        $this->db->from('NOTIFICACIONDEVOLUCION ND');
        $this->db->where('ND.COD_DEVOLUCION', $cod_devolucion);
        $resultado = $this->db->get();
        if ($resultado->num_rows() > 0):
            $empresa = $resultado->row_array();
            return $empresa;
        endif;
    }

    function actEstadoDocumentos($data, $devolucion) {
        $this->db->trans_start();
        $this->db->where('COD_DEVOLUCION', $devolucion);
        $this->db->where('ESTADO', 1);
        $this->db->set('ESTADO', $data['ESTADO']);
        $this->db->update('NOTIFICACIONDEVOLUCION');
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() >= 0) {
            $this->db->trans_complete();
            return TRUE;
        }
        return FALSE;
    }

    function adicionar_solicitud($data, $base_de_datos, $data2) {
        if ($data2['FECHA_RADICADO'])
            $this->db->set('FECHA_RADICADO', "to_date('" . $data2['FECHA_RADICADO'] . "','dd/mm/yyyy HH24:MI:SS')", false);
        else {
            $this->db->set('FECHA_RADICACION', "to_date('" . $data2['FECHA_RADICACION'] . "','dd/mm/yyyy HH24:MI:SS')", false);
            $this->db->set('FECHA_REGISTRO', "to_date('" . $data2['FECHA_REGISTRO'] . "','dd/mm/yyyy HH24:MI:SS')", false);
        }
        $query = $this->db->insert($base_de_datos, $data);
        return $query;
    }

    function guardar_aproba($apro, $comen, $codigo) {
        $arreglo = array('APROBADO' => $apro, 'OBSERVACIONES' => $comen, 'USUARIO_APROBO' => $this->ion_auth->user()->row()->IDUSUARIO);
        $this->db->set('FECHA_APROBACION', 'SYSDATE', FALSE);
        $this->db->where('COD_DEVOLUCION', $codigo);
        $query = $this->db->update('SOLICITUDDEVOLUCION', $arreglo);
        return $query;
    }

    function modificar_planilla($nit, $datos, $datos1 = '') {
        $this->db->where('COD_PLANILLAUNICA', $nit);
        if ($datos) {
            $query = $this->db->update('PLANILLAUNICA_ENC', $datos);
            return $query;
        }
        if ($datos1 != '') {
            $query1 = $this->db->update('PLANILLAUNICA_DET', $datos1);
            return $query1;
        }
    }

    function guardar_reembolso($dato, $dato1) {
        $arreglo = array('DATOS_REEMBOLSO' => $dato);
        $this->db->where('COD_DEVOLUCION', $dato1);
        $query = $this->db->update('SOLICITUDDEVOLUCION', $arreglo);
        return $query;
    }

    function guardar_enviocontabilidad($dato, $dato1, $fecha) {
        $arreglo = array('ENVIADO_CONTABILIDAD' => 's', 'OBS_ENVIO_CONTABILIDAD' => $dato);
        $this->db->set('FECHA_ENVIO_CONTABILIDAD', 'SYSDATE', FALSE);
        // $this->db->set('FECHA_ENVIO_CONTABILIDAD', "to_date('". $fecha ."','dd/mm/yyyy HH24:MI:SS')", false);
        $this->db->where('COD_DEVOLUCION', $dato1);
        $query = $this->db->update('SOLICITUDDEVOLUCION', $arreglo);
        return $query;
    }

    function guardar_enviocorreo($dato, $dato1, $fecha) {
        $arreglo = array('ENVIADO_CORREO' => 's', 'OBS_ENVIO_CORREO' => $dato);
        $this->db->set('FECHA_ENVIO_CORREO', 'SYSDATE', FALSE);
        $this->db->where('COD_DEVOLUCION', $dato1);
        $query = $this->db->update('SOLICITUDDEVOLUCION', $arreglo);
        return $query;
    }

    function guardar_calculos($ibc_incorrecto, $valor_incorrecto, $ibc_real, $valor_correcto, $diferencia, $codigo_planilla) {
        $arreglo = array('ENVIADO_CONTABILIDAD' => 's', 'OBS_ENVIO_CONTABILIDAD' => $dato, 'FECHA_ENVIO_CONTABILIDAD' => $fecha);
        $this->db->where('COD_DEVOLUCION', $dato1);
        $query = $this->db->update('SOLICITUDDEVOLUCION', $arreglo);
        return $query;
    }

    function consultar_motivoEspecifico($naturaleza) {
        $this->db->select('MO.*');
        $this->db->from('MOTIVODEVOLUCIONDINERO MO');
        $this->db->where('MO.NATURALEZA', $naturaleza);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    function consultar_motivo() {
        $this->db->select('MO.*');
        $this->db->from('MOTIVODEVOLUCIONDINERO MO');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    function consultar_usuarios() {
        $this->db->select('US.*');
        $this->db->from('USUARIOS US');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    function consultar_empresa($nit) {
        $dato = $this->db->query("
                    SELECT CODEMPRESA, UPPER(RAZON_SOCIAL) AS RAZON_SOCIAL
                    FROM EMPRESA E, PLANILLAUNICA_ENC PUE
                    WHERE PUE.N_INDENT_APORTANTE = E.CODEMPRESA
                    AND CODEMPRESA LIKE '%$nit%' OR RAZON_SOCIAL LIKE '%$nit%'
                    UNION(
                        SELECT TO_CHAR(COD_ENTIDAD), UPPER(RAZON_SOCIAL)
                        FROM CNM_EMPRESA
                        WHERE COD_ENTIDAD LIKE '%$nit%' OR RAZON_SOCIAL LIKE '%$nit%'
                        UNION(
                            SELECT TO_CHAR(IDENTIFICACION), NOMBRES || '' || APELLIDOS
                            FROM CNM_EMPLEADO
                            WHERE IDENTIFICACION LIKE '%$nit%' OR NOMBRES LIKE '%$nit%' OR APELLIDOS LIKE '%$nit%'
                        )
                 )
                ");
        return $dato->result_array();
    }

    function guardarDevolucion($data) {
        $nit = $data['NIT'];
        $this->db->set('FECHA_RADICACION', "TO_DATE('" . $data['FECHA_RADICACION'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        unset($data['FECHA_RADICACION']);
        $this->db->insert("SOLICITUDDEVOLUCION", $data);
        //print_r($this->db->last_query());die();
        /*
         * RETORNAR EL COD_DEVOLUCION INSERTADO
         */
        $this->db->select("R.COD_DEVOLUCION");
        $this->db->where("R.NIT", $data['NIT']);
        $this->db->where("R.FECHA_REGISTRO", "(SELECT MAX(R.FECHA_REGISTRO) FROM SOLICITUDDEVOLUCION R WHERE R.NIT = '$nit' )", FALSE);
        $dato = $this->db->get("SOLICITUDDEVOLUCION R");
        $dato = $dato->result_array();
        return $dato[0]['COD_DEVOLUCION'];
    }

    function guardar_doc($data) {
        $this->db->trans_start();
        $this->db->set('COD_DEVOLUCION', $data['COD_DEVOLUCION']);
        $this->db->set('NOMBRE_DOCUMENTO', $data['NOMBRE_DOCUMENTO']);
        $this->db->set('CREADO_POR', $data['CREADO_POR']);
        $this->db->set('REVISADO_POR', $data['REVISADO_POR']);
        $this->db->set('COMENTADO_POR', $data['COMENTADO_POR']);
        $this->db->set('COMENTARIO', $data['COMENTARIO']);
        $this->db->set('ASIGNADO', $data['ASIGNADO']);
        $this->db->set('COD_RESPUESTA', $data['COD_RESPUESTA']);
        $this->db->set('ESTADO', $data['ESTADO']);
        $this->db->set('APROBADO', $data['APROBADO']);
        $this->db->insert('NOTIFICACIONDEVOLUCION');
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    function getDatax($reg, $search, $lenght = 10, $para, $datos, $datos1, $apro, $apro_con) {
        $this->db->select('SD.*,MD.MOTIVO_DEVOLUCION,CD.NOMBRE_CONCEPTO,EM.*');
        $this->db->from('SOLICITUDDEVOLUCION SD');
        $this->db->join('MOTIVODEVOLUCION MD', 'MD.COD_MOTIVO_DEVOLUCION=SD.COD_MOTIVO_DEVOLUCION', 'inner');
        $this->db->join('CONCEPTOSDEVOLUCION CD', 'CD.COD_CONCEPTO =SD.COD_CONCEPTO', 'inner');
        $this->db->join('EMPRESA EM', 'EM.CODEMPRESA =SD.NIT', 'inner');
        if ($datos1 == "" && $datos != "") {
            $this->db->where('SD.' . $para, $datos);
        } else {
            $this->db->where('SD.FECHA_RADICACION >=', $datos1);
            $this->db->where('SD.FECHA_RADICACION <=', $datos);
        }
        if ($apro == "s") {
            $this->db->where('SD.APROBADO', 'n');
        }
        if ($apro_con == "s") {
            $this->db->where('SD.ENVIADO_CONTABILIDAD', 'n');
        }

        $this->db->limit($lenght, $reg);
        $query = $this->db->get();
        return $query->result();
    }

    function totalData($search, $para, $datos, $datos1, $apro, $apro_con) {
        $this->db->select('SD.*,MD.MOTIVO_DEVOLUCION,CD.NOMBRE_CONCEPTO,EM.*');
        $this->db->from('SOLICITUDDEVOLUCION SD');
        $this->db->join('MOTIVODEVOLUCION MD', 'MD.COD_MOTIVO_DEVOLUCION=SD.COD_MOTIVO_DEVOLUCION', 'inner');
        $this->db->join('CONCEPTOSDEVOLUCION CD', 'CD.COD_CONCEPTO =SD.COD_CONCEPTO', 'inner');
        $this->db->join('EMPRESA EM', 'EM.CODEMPRESA =SD.NIT', 'inner');
        if ($datos1 == "" && $datos != "") {
            $this->db->where('SD.' . $para, $datos);
        } else {
            $this->db->where('SD.FECHA_RADICACION >=', $datos1);
            $this->db->where('SD.FECHA_RADICACION <=', $datos);
        }
        if ($apro == "s") {
            $this->db->where('SD.APROBADO', 'n');
        }
        if ($apro_con == "s") {
            $this->db->where('SD.ENVIADO_CONTABILIDAD', 'n');
        }

        $query = $this->db->get();
        if ($query->num_rows() == 0)
            return '0';
        return $query->num_rows();
    }

    function getDatax1($reg, $search, $lenght = 10, $codigo, $des, $has) {
        $this->db->select('PU.*,PUE.*');
        $this->db->from('PLANILLAUNICA_ENC PU');
        $this->db->join('PLANILLAUNICA_DET PUE', 'PUE.COD_PLANILLAUNICA =PU.COD_PLANILLAUNICA', 'inner');
        $this->db->where('PU.N_INDENT_APORTANTE', $codigo);
        $this->db->where("TO_DATE(PU.PERIDO_PAGO, 'yyyy-mm') BETWEEN TO_DATE('" . $des . "', 'yyyy-mm') AND TO_DATE('" . $has . "', 'yyyy-mm')", NULL, FALSE);
        $query = $this->db->get();
        //print_r($this->db->last_query($query));die();
        return $query->result();
    }

    function totalData1($search, $codigo, $des, $has) {
        $this->db->select('PU.*,PUE.*');
        $this->db->from('PLANILLAUNICA_ENC PU');
        $this->db->join('PLANILLAUNICA_DET PUE', 'PUE.COD_PLANILLAUNICA =PU.COD_PLANILLAUNICA', 'inner');
        $this->db->where('PU.N_INDENT_APORTANTE', $codigo);
        $this->db->where("TO_DATE(PU.PERIDO_PAGO, 'yyyy-mm') BETWEEN TO_DATE('" . $des . "', 'yyyy-mm') AND TO_DATE('" . $has . "', 'yyyy-mm')", NULL, FALSE);
        $query = $this->db->get();
        if ($query->num_rows() == 0)
            return '0';
        return $query->num_rows();
    }

    function consultar_conceptoEspecifico($naturaleza) {
        $this->db->select('CD.*');
        $this->db->from('CONCEPTOSDEVOLUCION CD');
        $this->db->where('CD.NATURALEZA', $naturaleza);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    function consultar_concepto() {
        $this->db->select('CO.*');
        $this->db->from('CONCEPTOSDEVOLUCION CO');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    function consultar_cargos() {
        $this->db->select('CA.*');
        $this->db->from('CARGOS CA');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    function traer_cod($nit_ingresado) {
        $this->db->select_max('SD.COD_DEVOLUCION');
        $this->db->from('SOLICITUDDEVOLUCION SD');
        $this->db->where('SD.NIT', $nit_ingresado);
        $query = $this->db->get();
        return $query->row();
    }

    function datos_planilla($nit_ingresado) {
        $this->db->select('PU.*,PUD.*');
        $this->db->from('PLANILLAUNICA_ENC PU');
        $this->db->join('PLANILLAUNICA_DET PUD', 'PUD.COD_PLANILLAUNICA=PU.COD_PLANILLAUNICA', 'inner');
        $this->db->where('PU.N_RADICACION', $nit_ingresado);
        $query = $this->db->get();
        return $query->row();
    }

    function contabilidad($codigo) {
        $this->db->select('SD.ENVIADO_CONTABILIDAD');
        $this->db->from('SOLICITUDDEVOLUCION SD');
        $this->db->where('SD.COD_DEVOLUCION', $codigo);
        $query = $this->db->get();
        return $query->row();
    }

    function buscar($id_user) {

        $dato = $this->db->query("SELECT SD.*, MD.NOMBRE_MOTIVO, CD.NOMBRE_CONCEPTO, EM.CODEMPRESA AS NIT, EM.RAZON_SOCIAL AS NOMBRE_EMPRESA
             FROM SOLICITUDDEVOLUCION SD 
             INNER JOIN MOTIVODEVOLUCIONDINERO MD ON MD.COD_MOT_DEVOLUCION_DINERO=SD.COD_MOTIVO_DEVOLUCION 
             INNER JOIN CONCEPTOSDEVOLUCION CD ON CD.COD_CONCEPTO =SD.COD_CONCEPTO 
             INNER JOIN RESPUESTAGESTION RG ON RG.COD_RESPUESTA = SD.COD_RESPUESTA 
             INNER JOIN EMPRESA EM ON EM.CODEMPRESA =  SD.NIT 
             WHERE SD.ASIGNADO = '$id_user' 
             AND NVL(RG.URLGESTION, '***') = '***'             
                UNION(
                        SELECT SD.*, MD.NOMBRE_MOTIVO, CD.NOMBRE_CONCEPTO,  TO_CHAR(EM.COD_ENTIDAD), UPPER(EM.RAZON_SOCIAL)
                        FROM SOLICITUDDEVOLUCION SD 
                        INNER JOIN MOTIVODEVOLUCIONDINERO MD ON MD.COD_MOT_DEVOLUCION_DINERO=SD.COD_MOTIVO_DEVOLUCION 
                        INNER JOIN CONCEPTOSDEVOLUCION CD ON CD.COD_CONCEPTO =SD.COD_CONCEPTO 
                        INNER JOIN RESPUESTAGESTION RG ON RG.COD_RESPUESTA = SD.COD_RESPUESTA 
                        INNER JOIN CNM_EMPRESA EM ON EM.COD_ENTIDAD =  SD.NIT 
                        WHERE SD.ASIGNADO = '$id_user' 
                        AND NVL(RG.URLGESTION, '***') = '***'  
                        UNION(
                                SELECT SD.*, MD.NOMBRE_MOTIVO, CD.NOMBRE_CONCEPTO,  TO_CHAR(EM.IDENTIFICACION), EM.NOMBRES || '' || EM.APELLIDOS
                                FROM SOLICITUDDEVOLUCION SD 
                                INNER JOIN MOTIVODEVOLUCIONDINERO MD ON MD.COD_MOT_DEVOLUCION_DINERO=SD.COD_MOTIVO_DEVOLUCION 
                                INNER JOIN CONCEPTOSDEVOLUCION CD ON CD.COD_CONCEPTO =SD.COD_CONCEPTO 
                                INNER JOIN RESPUESTAGESTION RG ON RG.COD_RESPUESTA = SD.COD_RESPUESTA 
                                INNER JOIN CNM_EMPLEADO EM ON EM.IDENTIFICACION =  SD.NIT 
                                WHERE SD.ASIGNADO = '$id_user' 
                                AND NVL(RG.URLGESTION, '***') = '***'  
                        )
                )");
        return $dato->result_array();
    }

    function buscar_planilla_devolucion($nit_devolucion) {
        $this->db->select('PU.*,PUE.*');
        $this->db->from('PLANILLAUNICA_ENC PU');
        $this->db->join('PLANILLAUNICA_DET PUE', 'PUE.COD_PLANILLAUNICA =PU.COD_PLANILLAUNICA', 'inner');
        $this->db->where('PU.N_INDENT_APORTANTE', $nit_devolucion);
        $query = $this->db->get();
        return $query;
    }

    function buscar_planilla_devolucion1($nit_devolucion, $codigo_planilla) {
        $this->db->select('PU.*,PUE.*');
        $this->db->from('PLANILLAUNICA_ENC PU');
        $this->db->join('PLANILLAUNICA_DET PUE', 'PUE.COD_PLANILLAUNICA =PU.COD_PLANILLAUNICA', 'inner');
        //  $this->db->join('EMPRESA EM','EM.CODEMPRESA =PU.N_INDENT_APORTANTE','inner');
        $this->db->where('PU.N_INDENT_APORTANTE', $nit_devolucion);
        $this->db->where('PU.COD_PLANILLAUNICA', $codigo_planilla);
        $query = $this->db->get();
        return $query->row();
    }

    function buscar_planilla($codigo, $des, $has) {
        $this->db->select('PU.*,PUE.*');
        $this->db->from('PLANILLAUNICA_ENC PU');
        $this->db->join('PLANILLAUNICA_DET PUE', 'PUE.COD_PLANILLAUNICA =PU.COD_PLANILLAUNICA', 'inner');
        $this->db->where('PU.N_INDENT_APORTANTE', $codigo);
        $this->db->where("TO_DATE(PU.PERIDO_PAGO, 'yyyy-mm') BETWEEN TO_DATE('" . $des . "', 'yyyy-mm') AND TO_DATE('" . $has . "', 'yyyy-mm')", NULL, FALSE);
        $query = $this->db->get();
        return $query;
    }

    function consultar_devoluciones($codigo) {
        $this->db->select('SD.*,EM.*');
        $this->db->from('SOLICITUDDEVOLUCION SD');
        $this->db->join('EMPRESA EM', 'EM.CODEMPRESA =SD.NIT', 'inner');
        $this->db->where('SD.COD_DEVOLUCION', $codigo);
        $query = $this->db->get();
        return $query->row();
    }

    function consultar_docs($codigo) {
        $this->db->select('COD_DEVOLUCION,NOMBRE_DOCUMENTO,FECHA_CREACION,CREADO_POR,REVISADO_POR,COMENTADO_POR,COMENTARIO,COD_RESPUESTA,ASIGNADO,ESTADO,APROBADO');
        $this->db->from('NOTIFICACIONDEVOLUCION');
        $this->db->where('COD_DEVOLUCION', $codigo);
        $this->db->where('ESTADO', 1);
        $this->db->order_by('FECHA_CREACION', 'desc');
        $query = $this->db->get();
        return $query->row();
    }

    function buscar_detalle($dato) {
        $this->db->select('SD.*,MD.*,CD.*,EM.*');
        $this->db->from('SOLICITUDDEVOLUCION SD');
        $this->db->join('MOTIVODEVOLUCIONDINERO MD', 'MD.COD_MOT_DEVOLUCION_DINERO=SD.COD_MOTIVO_DEVOLUCION', 'inner');
        $this->db->join('CONCEPTOSDEVOLUCION CD', 'CD.COD_CONCEPTO =SD.COD_CONCEPTO', 'inner');
        $this->db->join('EMPRESA EM', 'EM.CODEMPRESA =SD.NIT', 'inner');
        $this->db->where('SD.COD_DEVOLUCION', $dato);

        $query = $this->db->get();

        return $query->row();
    }

    function traer_nombre($cedula) {
        $this->db->select('US.*');
        $this->db->from('USUARIOS US');
        $this->db->where('US.IDUSUARIO', $cedula);
        $query = $this->db->get();
        return $query->row();
    }

    function lisUsuarios($fields, $regional, $cargo, $order = '') {
        $this->db->select('USUARIOS.' . $fields, 'NOMBREGRUPO');
        $this->db->join("USUARIOS_GRUPOS", "USUARIOS.IDUSUARIO=USUARIOS_GRUPOS.IDUSUARIO");
        $this->db->join("GRUPOS", "USUARIOS_GRUPOS.IDGRUPO=GRUPOS.IDGRUPO");
        $this->db->where('COD_REGIONAL', $regional);
        //$this->db->where('IDCARGO', $cargo);
        //$this->db->where('(NOMBREGRUPO = \'Coordinador Relaciones Corporativas\' OR NOMBREGRUPO = \'tecnico_profesional_cartera\')');
        $this->db->order_by($order);
        $query = $this->db->get("USUARIOS");
        //print_r($this->db->last_query($query));die();
        return $query->result();
    }

    function lisUsuarios2($fields, $regional, $cargo, $order = '') {
        $this->db->select('USUARIOS.' . $fields, 'NOMBREGRUPO');
        $this->db->join("USUARIOS_GRUPOS", "USUARIOS.IDUSUARIO=USUARIOS_GRUPOS.IDUSUARIO");
        $this->db->join("GRUPOS", "USUARIOS_GRUPOS.IDGRUPO=GRUPOS.IDGRUPO");
        $this->db->where('COD_REGIONAL', $regional);
        $this->db->where('IDCARGO', $cargo);
        //$this->db->where('(NOMBREGRUPO = \'Coordinador Relaciones Corporativas\' OR NOMBREGRUPO = \'tecnico_profesional_cartera\')');
        $this->db->order_by($order);
        $query = $this->db->get("USUARIOS");
        //print_r($this->db->last_query($query));die();
        return $query->result();
    }

    function permiso() {
        $this->db->select("USUARIOS.IDUSUARIO as IDUSUARIO, APELLIDOS, NOMBRES,GRUPOS.IDGRUPO,USUARIOS.COD_REGIONAL");
        $this->db->join("USUARIOS_GRUPOS", "USUARIOS.IDUSUARIO=USUARIOS_GRUPOS.IDUSUARIO");
        $this->db->join("GRUPOS", "USUARIOS_GRUPOS.IDGRUPO=GRUPOS.IDGRUPO");
        $this->db->where("USUARIOS.IDUSUARIO", ID_USER);
        $dato = $this->db->get("USUARIOS");
        //var_dump($this->db->last_query($dato));die;
        return $dato->result_array;
    }

    function verificar_permiso($id_usuario) {//S
        $this->db->select("UG.IDGRUPO");
        $this->db->where("UG.IDUSUARIO", $id_usuario);
        $dato = $this->db->get("USUARIOS_GRUPOS UG");
        if ($dato->num_rows() > 0) {
            return $dato->result_array;
        } else {
            return false;
        }
    }

    public function get_estadosgestion($cod_usuario, $grupo) {
        $cadena = "";
        if ($grupo == LIDER_DEVOLUCION) {
            $cadena = "SD.LIDER_DEVOLUCIONES = '$cod_usuario' AND";
        }
        $dato = $this->db->query("
             SELECT CD.COD_CONCEPTO_RECAUDO, RG.COD_RESPUESTA, SD.NIT, SD.COD_DEVOLUCION, E.CODEMPRESA, E.RAZON_SOCIAL, MD.NOMBRE_MOTIVO, CD.NOMBRE_CONCEPTO, RG.NOMBRE_GESTION, RG.URLGESTION, RG.IDGRUPO 
             FROM SOLICITUDDEVOLUCION SD 
             JOIN EMPRESA E ON E.CODEMPRESA = SD.NIT 
             JOIN MOTIVODEVOLUCIONDINERO MD ON MD.COD_MOT_DEVOLUCION_DINERO = SD.COD_MOTIVO_DEVOLUCION 
             JOIN RESPUESTAGESTION RG ON RG.COD_RESPUESTA = SD.COD_RESPUESTA 
             JOIN TIPOGESTION TG ON TG.COD_GESTION = RG.COD_TIPOGESTION 
             JOIN CONCEPTOSDEVOLUCION CD ON CD.COD_CONCEPTO = SD.COD_CONCEPTO 
             WHERE $cadena RG.IDGRUPO = '$grupo' AND
                 NVL(RG.URLGESTION, '***') <> '***'
                 UNION(
                        SELECT CD.COD_CONCEPTO_RECAUDO, RG.COD_RESPUESTA, SD.NIT, SD.COD_DEVOLUCION, TO_CHAR(E.COD_ENTIDAD), UPPER(E.RAZON_SOCIAL), MD.NOMBRE_MOTIVO, CD.NOMBRE_CONCEPTO, RG.NOMBRE_GESTION, RG.URLGESTION, RG.IDGRUPO 
                        FROM SOLICITUDDEVOLUCION SD 
                        JOIN CNM_EMPRESA E ON E.COD_ENTIDAD =  SD.NIT 
                        JOIN MOTIVODEVOLUCIONDINERO MD ON MD.COD_MOT_DEVOLUCION_DINERO = SD.COD_MOTIVO_DEVOLUCION 
                        JOIN RESPUESTAGESTION RG ON RG.COD_RESPUESTA = SD.COD_RESPUESTA 
                        JOIN TIPOGESTION TG ON TG.COD_GESTION = RG.COD_TIPOGESTION 
                        JOIN CONCEPTOSDEVOLUCION CD ON CD.COD_CONCEPTO = SD.COD_CONCEPTO 
                        WHERE $cadena RG.IDGRUPO = '$grupo' AND
                            NVL(RG.URLGESTION, '***') <> '***'
                            UNION(
                                SELECT CD.COD_CONCEPTO_RECAUDO, RG.COD_RESPUESTA, SD.NIT, SD.COD_DEVOLUCION, TO_CHAR(E.IDENTIFICACION), E.NOMBRES || '' || E.APELLIDOS, MD.NOMBRE_MOTIVO, CD.NOMBRE_CONCEPTO, RG.NOMBRE_GESTION, RG.URLGESTION, RG.IDGRUPO 
                                FROM SOLICITUDDEVOLUCION SD 
                                JOIN CNM_EMPLEADO E ON E.IDENTIFICACION =  SD.NIT 
                                JOIN MOTIVODEVOLUCIONDINERO MD ON MD.COD_MOT_DEVOLUCION_DINERO = SD.COD_MOTIVO_DEVOLUCION 
                                JOIN RESPUESTAGESTION RG ON RG.COD_RESPUESTA = SD.COD_RESPUESTA 
                                JOIN TIPOGESTION TG ON TG.COD_GESTION = RG.COD_TIPOGESTION 
                                JOIN CONCEPTOSDEVOLUCION CD ON CD.COD_CONCEPTO = SD.COD_CONCEPTO 
                                WHERE $cadena RG.IDGRUPO = '$grupo' AND
                                NVL(RG.URLGESTION, '***') <> '***'
                            )
                 )
                 ");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result();
            return $dato;
        } else {
            return false;
        }
    }

    public function update_devolucion($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_DEVOLUCION", $datos['COD_DEVOLUCION']);
            unset($datos['COD_DEVOLUCION']);
            $this->db->update("SOLICITUDDEVOLUCION", $datos);
            if ($this->db->affected_rows() >= 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        endif;
    }

    function get_usuariosgrupo($grupo) {
        $this->db->select("U.IDUSUARIO,U.NOMBRES,U.APELLIDOS");
        $this->db->join("USUARIOS_GRUPOS UG", "UG.IDUSUARIO = U.IDUSUARIO");
        $this->db->join("GRUPOS G", "G.IDGRUPO = UG.IDGRUPO");
        $this->db->where("UG.IDGRUPO", $grupo);
        $dato = $this->db->get("USUARIOS U");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

    function get_regionalorigen($cod_devolucion) {
        $this->db->select("REGIONAL_DEVOLUCION");
        $this->db->where("REGIONAL_DEVOLUCION", 1);
        $this->db->where("COD_DEVOLUCION", $cod_devolucion);
        $dato = $this->db->get("SOLICITUDDEVOLUCION");
        if ($dato->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_documentosoporte($cod_devolucion) {
        $this->db->select("SD.NIT, ND.NOMBRE_DOCUMENTO");
        $this->db->join("NOTIFICACIONDEVOLUCION ND", "ND.COD_DEVOLUCION = SD.COD_DEVOLUCION");
        $this->db->where("SD.COD_DEVOLUCION", $cod_devolucion);
        $this->db->order_by('ND.COD_NOTIFICACION_DEVOLUCION', 'desc');
        $dato = $this->db->get("SOLICITUDDEVOLUCION SD");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        } else {
            return false;
        }
    }

    function get_planillas($cod_devolucion) {
        $dato = $this->db->query("SELECT SD.NRO_RADICACION, SD.FECHA_RADICACION, PU.COD_PLANILLAUNICA, PU.PERIDO_PAGO, PU.FECHA__PAGO, TO_NUMBER(RT.TOTAL_APORTES) TOTAL_APORTES, TO_NUMBER(RT.INGRESO) AS IBC "
                . "FROM SOLICITUDDEVOLUCION SD "
                . "JOIN PLANILLAUNICA_ENC PU ON PU.N_INDENT_APORTANTE = SD.NIT "
                . "JOIN REGISTROTIPO3 RT ON RT.COD_CAMPO = PU.COD_PLANILLAUNICA "
                . "WHERE SD.COD_DEVOLUCION = '$cod_devolucion'"
                . "AND NOT EXISTS(
                    SELECT 'X'
                    FROM DEVOLUCION_PLANILLAS B
                    WHERE PU.COD_PLANILLAUNICA = B.COD_PLANILLAUNICA
                  )");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        } else {
            return false;
        }
    }

    function get_valorplanilla($cod_planilla) {
        $dato = $this->db->query("SELECT TO_NUMBER(RT.TOTAL_APORTES) TOTAL_APORTES, TO_NUMBER(RT.INGRESO) AS IBC "
                . "FROM SOLICITUDDEVOLUCION SD JOIN PLANILLAUNICA_ENC PU ON PU.N_INDENT_APORTANTE = SD.NIT JOIN REGISTROTIPO3 RT ON RT.COD_CAMPO = PU.COD_PLANILLAUNICA "
                . "WHERE PU.COD_PLANILLAUNICA = '$cod_planilla'");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result();
            return $dato[0];
        } else {
            return false;
        }
    }

    function get_totalaportes($cod_planilla) {
        $this->db->select("RT.TOTAL_APORTES");
        $this->db->where("RT.COD_CAMPO", $cod_planilla);
        $dato = $this->db->get("REGISTROTIPO3 RT");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result();
            return $dato[0];
        } else {
            return false;
        }
    }

    function get_detalleplanilla($cod_planilla) {
        $this->db->select("PUD.TARIFA,PUE.N_INDENT_APORTANTE AS NIT,PUE.NOM_APORTANTE AS NOMBRE, PUD.N_IDENT_COTIZ AS CEDULA, TO_NUMBER(PUD.ING_BASE_COTIZ) AS IBC", FALSE);
        $this->db->join("PLANILLAUNICA_DET PUD", "PUD.COD_PLANILLAUNICA = PUE.COD_PLANILLAUNICA");
        $this->db->where("PUE.COD_PLANILLAUNICA", $cod_planilla);
        $this->db->group_by("PUD.TARIFA,PUE.N_INDENT_APORTANTE, PUE.NOM_APORTANTE, PUD.N_IDENT_COTIZ,PUD.ING_BASE_COTIZ");
        $dato = $this->db->get("PLANILLAUNICA_ENC PUE");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result();
            return $dato[0];
        } else {
            return false;
        }
    }

    function get_detallepersona($cod_persona) {
        $this->db->select("PUD.COD_PLANILLAUNICA, PUD.N_IDENT_COTIZ, PUD.PRIMER_APELLIDO, PUD.SEGUN_APELLIDO, PUD.PRIMER_NOMBRE, PUD.SEGUN_NOMBRE, TO_NUMBER(PUD.ING_BASE_COTIZ) AS ING_BASE_COTIZ, TO_NUMBER(PUD.APORTE_OBLIG) APORTE_OBLIG");
        $this->db->where("PUD.N_IDENT_COTIZ", $cod_persona);
        $dato = $this->db->get("PLANILLAUNICA_DET PUD");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result();
            return $dato[0];
        } else {
            return false;
        }
    }

    function get_infoplanilla($cod_planilla) {
        $this->db->select("PUE.N_INDENT_APORTANTE AS NIT, NOM_APORTANTE AS NOMBRE");
        $this->db->join("PLANILLAUNICA_DET PUD", "PUD.COD_PLANILLAUNICA = PUE.COD_PLANILLAUNICA");
        $this->db->where("PUE.COD_PLANILLAUNICA", $cod_planilla);
        $dato = $this->db->get("PLANILLAUNICA_ENC PUE");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result();
            return $dato[0];
        } else {
            return false;
        }
    }

    function get_personasplanilla($cod_planilla) {
        $dato = $this->db->query("SELECT PUD.COD_PLANILLAUNICA, PUD.N_IDENT_COTIZ, PUD.PRIMER_APELLIDO, PUD.SEGUN_APELLIDO, PUD.PRIMER_NOMBRE, PUD.SEGUN_NOMBRE, TO_NUMBER(PUD.ING_BASE_COTIZ) AS ING_BASE_COTIZ, TO_NUMBER(PUD.APORTE_OBLIG) APORTE_OBLIG "
                . "FROM PLANILLAUNICA_DET PUD "
                . "WHERE PUD.COD_PLANILLAUNICA = '$cod_planilla'"
                . "AND  NOT EXISTS(
                                SELECT 'X'
                                FROM DEVOLUCION_DET B
                                WHERE ((PUD.COD_PLANILLAUNICA = B.COD_PLANILLAUNICA)
                                      AND (PUD.N_IDENT_COTIZ = B.ID_EMPLEADO))
                              )");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result();
            return $dato;
        } else {
            return false;
        }
    }

    function get_conceptodevolucion($cod_devolucion) {
        $this->db->select("CD.COD_CONCEPTO, CD.NOMBRE_CONCEPTO");
        $this->db->join("CONCEPTOSDEVOLUCION CD", "CD.COD_CONCEPTO = SD.COD_CONCEPTO");
        $this->db->where("SD.COD_DEVOLUCION", $cod_devolucion);
        $dato = $this->db->get("SOLICITUDDEVOLUCION SD");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result();
            return $dato[0];
        } else {
            return false;
        }
    }

    /*
     * INSERTAR SOPORTE DE CADA DEVOLUCION DE PLANTILLA
     */

    public function insertar_devolucionplantilla($datos = NULL) {
        if (!empty($datos)) :
            $this->db->insert("DEVOLUCION_PLANILLAS", $datos);
        endif;
    }

    /*
     * INSERTAR DATOS ORIGINALES ANTES DE MODIFICAR LA PLANILLA
     */

    public function insertar_detallesdevolucion($datos = NULL) {
        if (!empty($datos)) :
            $this->db->insert("DEVOLUCION_DET", $datos);
        endif;
    }

    /*
     * MODIFICACION DEL VALOR TOTAL DEL APORTE
     */

    public function actualizacion_encabezadoplanilla($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_CAMPO", $datos['COD_CAMPO']);
            unset($datos['COD_CAMPO']);
            $this->db->update("REGISTROTIPO3", $datos);
        endif;
    }

    /*
     * MODIFICACION DEL IBC DEL APORTANTE
     */

    public function actualizacion_ibcaportante($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_PLANILLAUNICA", $datos['COD_PLANILLAUNICA']);
            unset($datos['COD_PLANILLAUNICA']);
            $this->db->where("N_IDENT_COTIZ", $datos['N_IDENT_COTIZ']);
            unset($datos['N_IDENT_COTIZ']);
            $this->db->update("PLANILLAUNICA_DET", $datos);
        endif;
    }

    /*
     * DEVOLUCIONES PARA APROBAR ESTADO 1237
     */

    function get_devolucionesaprobar($cod_devolucion) {
        $dato = $this->db->query("SELECT DP.COD_DEVOLUCION_PLANILLA AS COD_APROBACION, ENC.N_INDENT_APORTANTE, ENC.NOM_APORTANTE, DP.COD_PLANILLAUNICA, ENC.PERIDO_PAGO, DP.VALOR_INCORRECTO, SD.FECHA_RADICACION, DP.VALOR_INCORRECTO - DP.VALOR_CORRECTO AS VALOR_DEVOLUCION, CD.NOMBRE_CONCEPTO
                FROM SOLICITUDDEVOLUCION SD, PLANILLAUNICA_ENC ENC, PLANILLAUNICA_DET DET, DEVOLUCION_PLANILLAS DP, CONCEPTOSDEVOLUCION CD
                WHERE SD.COD_DEVOLUCION = DP.COD_DEVOLUCION
                AND DP.COD_PLANILLAUNICA = ENC.COD_PLANILLAUNICA
                AND dp.Cod_PlanillaUnica = Det.Cod_PlanillaUnica
                AND SD.COD_RESPUESTA = 1237
                AND SD.COD_DEVOLUCION = $cod_devolucion
                AND SD.COD_CONCEPTO = CD.COD_CONCEPTO
                GROUP BY DP.COD_DEVOLUCION_PLANILLA, ENC.N_INDENT_APORTANTE, ENC.NOM_APORTANTE, DP.COD_PLANILLAUNICA, ENC.PERIDO_PAGO, SD.FECHA_RADICACION,DP.VALOR_INCORRECTO, DP.VALOR_INCORRECTO - DP.VALOR_CORRECTO,CD.NOMBRE_CONCEPTO
                 UNION(
                SELECT DD.COD_DEVOLUCIONDET, DET.N_IDENT_COTIZ, DET.PRIMER_NOMBRE || ' ' ||DET.SEGUN_NOMBRE || ' ' || DET.PRIMER_APELLIDO || ' ' || SEGUN_APELLIDO AS NOM_APORTANTE, DD.COD_PLANILLAUNICA, ENC.PERIDO_PAGO, TO_NUMBER(DET.APORTE_OBLIG), SD.FECHA_RADICACION, DD.TOTAL_DEVOLUCION, CD.NOMBRE_CONCEPTO
                FROM SOLICITUDDEVOLUCION SD, PLANILLAUNICA_ENC ENC, PLANILLAUNICA_DET DET, DEVOLUCION_DET DD, CONCEPTOSDEVOLUCION CD
                WHERE SD.COD_DEVOLUCION = DD.COD_DEVOLUCION
                AND DD.COD_PLANILLAUNICA = DET.COD_PLANILLAUNICA
                AND DD.ID_EMPLEADO = DET.N_IDENT_COTIZ
                AND DD.PAGO_ERRADO = TO_NUMBER(DET.APORTE_OBLIG)
                AND ENC.COD_PLANILLAUNICA =DD.COD_PLANILLAUNICA
                AND SD.COD_RESPUESTA = 1237
                AND SD.COD_DEVOLUCION = $cod_devolucion
                AND SD.COD_CONCEPTO = CD.COD_CONCEPTO
                GROUP BY DD.COD_DEVOLUCIONDET, DET.N_IDENT_COTIZ, DET.PRIMER_NOMBRE, DET.SEGUN_NOMBRE, DET.PRIMER_APELLIDO, SEGUN_APELLIDO , DD.COD_PLANILLAUNICA, ENC.PERIDO_PAGO, DET.APORTE_OBLIG, SD.FECHA_RADICACION, DD.TOTAL_DEVOLUCION, CD.NOMBRE_CONCEPTO
                )");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result();
            return $dato;
        } else {
            return false;
        }
    }

    function get_aprobarotrosconceptos($cod_devolucion) {
        $this->db->select("DP.COD_PAGO_DEVUELTO, DP.COD_DEVOLUCION, PR.VALOR_PAGADO, PR.FECHA_TRANSACCION, DP.VALOR_DEVUELTO, DP.FECHA_DEVOLUCION");
        $this->db->join("SOLICITUDDEVOLUCION SD", "SD.COD_DEVOLUCION = DP.COD_DEVOLUCION");
        $this->db->join("PAGOSRECIBIDOS PR", "PR.COD_PAGO = DP.COD_PAGOSRECIBIDOS");
        $this->db->where("DP.COD_DEVOLUCION", $cod_devolucion);
        $this->db->where("DP.ESTADO", 0);
        $dato = $this->db->get("DEVOLUCION_PAGOS DP");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result();
            return $dato;
        } else {
            return false;
        }
    }

    /*
     * MODIFICAR APROBACION
     */

    public function actualizacion_aprobacionplanillas($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_DEVOLUCION_PLANILLA", $datos['COD_DEVOLUCION_PLANILLA']);
            unset($datos['COD_DEVOLUCION_PLANILLA']);
            $this->db->update("DEVOLUCION_PLANILLAS", $datos);
        endif;
    }

    public function actualizacion_aprobacionterceros($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_DEVOLUCIONDET", $datos['COD_DEVOLUCIONDET']);
            unset($datos['COD_DEVOLUCIONDET']);
            $this->db->update("DEVOLUCION_DET", $datos);
        endif;
    }

    /*
     * INSERTAR DEVOLUCIONES APROBADAS
     */

    public function insertar_devolucionplanilla($datos = NULL) {
        if (!empty($datos)) :
            $this->db->insert("DEVOLUCIONES_APROBADAS", $datos);
        endif;
    }

    /*
     * MODIFICACION DEL IBC DEL APORTANTE
     */

    public function actualizacion_devolucionotrosaportes($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_PAGO_DEVUELTO", $datos['COD_PAGO_DEVUELTO']);
            unset($datos['COD_PAGO_DEVUELTO']);
            $this->db->update("DEVOLUCION_PAGOS", $datos);
        endif;
    }

    public function get_trazagestion($cod_respuesta) {
        $this->db->select("RG.COD_TIPOGESTION, RG.COD_RESPUESTA");
        $this->db->where("RG.COD_RESPUESTA", $cod_respuesta);
        $dato = $this->db->get("RESPUESTAGESTION RG");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    /**
     * Obtiene el ultimo codigo de la solicitud de devolucion insertado 
     * @access public
     * @param 
     * @return integer
     * @autor German E. Perez H 20141024
     */
    public function get_ultimo_codigo_dev() {
        $query = $this->db->query("SELECT SolicitudDevo_cod_devoluci_SEQ.CURRVAL FROM Dual");
        $row = $query->row_array();
        $id = $row['CURRVAL'];
        return $id;
    }

}
