<?php

class mandamientopago_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array') {

        $this->db->select($fields);
        $this->db->from($table);
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }

        $query = $this->db->get();

        $result = !$one ? $query->result($array) : $query->row();
        return $result;
    }

    function getoption($table, $where, $id) {

        $this->db->where($where, $id);
        $result = $this->db->get($table);
        return $result;
    }

    function addAviso($data) {

        $this->db->trans_start();
        $this->db->set('COD_AVISONOTIFICACION', $data['COD_AVISONOTIFICACION']);
        $this->db->set('COD_TIPONOTIFICACION', $data['COD_TIPONOTIFICACION']);
        $this->db->set('FECHA_NOTIFICACION', "to_date('" . $data['FECHA_NOTIFICACION'] . "','dd/mm/yyyy')", false);
        $this->db->set('COD_ESTADO', $data['COD_ESTADO']);
        $this->db->set('OBSERVACIONES', $data['OBSERVACIONES']);
        $this->db->set('COD_MANDAMIENTOPAGO', $data['COD_MANDAMIENTOPAGO']);
        $this->db->set('NOMBRE_DOC_CARGADO', $data['NOMBRE_DOC_CARGADO']);
        $this->db->set('EXCEPCION', $data['EXCEPCION']);
        $this->db->set('PLANTILLA', $data['PLANTILLA']);
        $this->db->set('RECURSO', $data['RECURSO']);
        $this->db->set('ESTADO_NOTIFICACION', $data['ESTADO_NOTIFICACION']);
        $this->db->insert('AVISONOTIFICACION');
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() > 0) {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    function addExcepciones($data) {

        $this->db->trans_start();
        $this->db->set('COD_EXCEPCION', $data['COD_EXCEPCION']);
        $this->db->set('COD_MANDAMIENTO', $data['COD_MANDAMIENTO']);
        $this->db->set('FECHA_EXCEPCION', "to_date('" . $data['FECHA_EXCEPCION'] . "','dd/mm/yyyy')", false);
        $this->db->set('PAGO_REALIZADO', $data['PAGO_REALIZADO']);
        if ($data['PRESENTA_EXCEPCIONES'])
            $this->db->set('PRESENTA_EXCEPCIONES', $data['PRESENTA_EXCEPCIONES']);
        $this->db->set('OBSERVACIONES', $data['OBSERVACIONES']);
        $this->db->insert('EXCEPCIONESNOTIFICACION');
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    function addRecursos($data) {

        $this->db->trans_start();
        $this->db->set('COD_RECURSO', $data['COD_RECURSO']);
        $this->db->set('COD_MANDAMIENTO', $data['COD_MANDAMIENTO']);
        $this->db->set('FECHA_RECURSO', "to_date('" . $data['FECHA_RECURSO'] . "','dd/mm/yyyy')", false);
        $this->db->set('PRESENTA_RECURSOS', $data['PRESENTA_RECURSOS']);
        $this->db->set('OBSERVACIONES', $data['OBSERVACIONES']);
        $this->db->insert('RECURSOS_NOTIFICACION');
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    function addMandamiento($idmandamiento, $data) {

        $this->db->trans_start();
        $this->db->where('COD_MANDAMIENTOPAGO', $idmandamiento);
        $this->db->set('FECHA_MODIFICA_MANDAMIENTO', "to_date('" . $data['FECHA_MODIFICA_MANDAMIENTO'] . "','dd/mm/yyyy')", false);
        $this->db->set('COMENTARIOS', $data['COMENTARIOS']);
        $this->db->set('APROBADO', $data['APROBADO']);
        $this->db->set('REVISADO', $data['REVISADO']);
        $this->db->set('ASIGNADO_A', $data['ASIGNADO_A']);
        $this->db->set('ESTADO', $data['ESTADO']);
        $this->db->set('PLANTILLA', $data['PLANTILLA']);
        $this->db->set('EXCEPCION', $data['EXCEPCION']);
        $this->db->set('COD_GESTIONCOBRO', $data['COD_GESTIONCOBRO']);
        $this->db->update('MANDAMIENTOPAGO');
        /* Se actualiza cada titulo seleccionado */


        foreach ($data['TITULOS'] as $titulo):
            $this->db->set('COD_MANDAMIENTOPAGO', $idmandamiento);
            $this->db->where('COD_RECEPCIONTITULO', $titulo);
            $this->db->update('ACUMULACION_COACTIVA');
        endforeach;

        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    function addMandamientoAdelante($table, $data) {

        $this->db->trans_start();
        $this->db->set('COD_MANDAMIENTO_ADELANTE', $data['COD_MANDAMIENTO_ADELANTE']);
        $this->db->set('COD_PROCESO_COACTIVO', $data['COD_PROCESO_COACTIVO']);
        $this->db->set('FECHA_MANDAMIENTO_ADELANTE', "to_date('" . $data['FECHA_MANDAMIENTO_ADELANTE'] . "','dd/mm/yyyy')", false);
        $this->db->set('CREADO_POR', $data['CREADO_POR']);
        $this->db->set('COMENTARIOS', $data['COMENTARIOS']);
        $this->db->set('APROBADO', $data['APROBADO']);
        $this->db->set('REVISADO', $data['REVISADO']);
        $this->db->set('ASIGNADO_A', $data['ASIGNADO_A']);
        $this->db->set('ESTADO', $data['ESTADO']);
        $this->db->set('PLANTILLA', $data['PLANTILLA']);
        $this->db->set('COD_GESTIONCOBRO', $data['COD_GESTIONCOBRO']);
        $this->db->set('COD_MANDAMIENTOPAGO', $data['COD_MANDAMIENTOPAGO']);
        $this->db->insert($table);
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    function addResolucion($table, $data) {

        $this->db->trans_start();
        $this->db->set('COD_RESOLUCION', $data['COD_RESOLUCION']);
        $this->db->set('COD_TIPOEXCEPCION', $data['COD_TIPOEXCEPCION']);
        $this->db->set('COMENTARIOS', $data['COMENTARIOS']);
        $this->db->set('COD_ESTADO', $data['COD_ESTADO']);
        $this->db->set('PLANTILLA', $data['PLANTILLA']);
        $this->db->set('COD_MANDAMIENTOPAGO', $data['COD_MANDAMIENTOPAGO']);
        $this->db->set('COD_PROCESO_COACTIVO', $data['COD_PROCESO_COACTIVO']);
        $this->db->set('APROBADO', $data['APROBADO']);
        $this->db->set('REVISADO', $data['REVISADO']);
        $this->db->set('ASIGNADO_A', $data['ASIGNADO_A']);
        $this->db->set('FECHA_EXCEPCION', "to_date('" . $data['FECHA_EXCEPCION'] . "','dd/mm/yyyy')", false);
        $this->db->set('CREADO_POR', $data['CREADO_POR']);
        $this->db->set('COD_GESTIONCOBRO', $data['COD_GESTIONCOBRO']);
        $this->db->insert($table);
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    function addResolucionRecurso($table, $data) {
        $this->db->trans_start();
        $this->db->set('COD_RESOLUCION', $data['COD_RESOLUCION']);
        $this->db->set('COD_ESTADO', $data['COD_ESTADO']);
        $this->db->set('PLANTILLA', $data['PLANTILLA']);
        $this->db->set('COD_PROCESO_COACTIVO', $data['COD_PROCESO_COACTIVO']);
        $this->db->set('APROBADO', $data['APROBADO']);
        $this->db->set('REVISADO', $data['REVISADO']);
        $this->db->set('CREADO_POR', $data['CREADO_POR']);
        $this->db->set('COD_GESTIONCOBRO', $data['COD_GESTIONCOBRO']);
        $this->db->set('COMENTARIOS', $data['COMENTARIOS']);
        $this->db->set('COD_MANDAMIENTOPAGO', $data['COD_MANDAMIENTOPAGO']);
        $this->db->set('FECHA_RECURSO', "to_date('" . $data['FECHA_RECURSO'] . "','dd/mm/yyyy')", false);
        $this->db->insert($table);
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    function add($table, $data, $date = '') {
        if ($date != '') {
            foreach ($data as $key => $value) {
                $this->db->set($key, $value);
            }
            foreach ($date as $keyf => $valuef) {
                $this->db->set($keyf, "to_date('" . $valuef . "','dd/mm/yyyy')", false);
            }
            $this->db->insert($table);
        } else {
            $this->db->insert($table, $data);
        }
        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }
        return FALSE;
    }

    function edit($table, $data, $fieldID, $ID) {
        $this->db->where($fieldID, $ID);
        $this->db->update($table, $data);
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() >= 0) {
            return TRUE;
        }
        return FALSE;
        var_dump($this->db->affected_rows());
    }

    function delete($table, $fieldID, $ID) {
        $this->db->where($fieldID, $ID);
        $this->db->delete($table);
        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }
        return FALSE;
    }

    function editAvi($data, $id) {
        $this->db->trans_start();
        $this->db->where('COD_AVISONOTIFICACION', $id);
        $this->db->set('COD_TIPONOTIFICACION', $data['COD_TIPONOTIFICACION']);
        $this->db->set('NUM_RADICADO_ONBASE', $data['NUM_RADICADO_ONBASE']);
        if ($data['FECHA_ONBASE'] == ''):
            $date = date('d/m/Y');
            $this->db->set('FECHA_ONBASE', "to_date('" . $date . "','dd/mm/yyyy')", false);
        else:
            $this->db->set('FECHA_ONBASE', "to_date('" . $data['FECHA_ONBASE'] . "','dd/mm/yyyy')", false);
        endif;
        $this->db->set('FECHA_MODIFICA_NOTIFICACION', "to_date('" . $data['FECHA_MODIFICA_NOTIFICACION'] . "','dd/mm/yyyy')", false);
        $this->db->set('COD_ESTADO', $data['COD_ESTADO']);
        $this->db->set('OBSERVACIONES', $data['OBSERVACIONES']);
        $this->db->set('DOC_COLILLA', $data['DOC_COLILLA']);
        $this->db->set('DOC_FIRMADO', $data['DOC_FIRMADO']);
        $this->db->set('NOMBRE_DOC_CARGADO', $data['NOMBRE_DOC_CARGADO']);
        $this->db->set('NOMBRE_COL_CARGADO', $data['NOMBRE_COL_CARGADO']);
        $this->db->set('COD_MOTIVODEVOLUCION', $data['COD_MOTIVODEVOLUCION']);
        $this->db->set('EXCEPCION', $data['EXCEPCION']);
        $this->db->set('PLANTILLA', $data['PLANTILLA']);
        $this->db->set('RECURSO', $data['RECURSO']);
        $this->db->set('COD_COMUNICACION', $data['COD_COMUNICACION']);
        $this->db->set('ESTADO_NOTIFICACION', $data['ESTADO_NOTIFICACION']);
        $this->db->update('AVISONOTIFICACION');
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() >= 0) {
            return TRUE;
        }
        return FALSE;
    }

    function editManda($data, $id) {
        $this->db->trans_start();
        $this->db->where('COD_MANDAMIENTOPAGO', $id);
        $this->db->set('FECHA_MODIFICA_MANDAMIENTO', "to_date('" . $data['FECHA_MODIFICA_MANDAMIENTO'] . "','dd/mm/yyyy')", false);
        $this->db->set('COMENTARIOS', $data['COMENTARIOS']);
        $this->db->set('APROBADO', $data['APROBADO']);
        $this->db->set('REVISADO', $data['REVISADO']);
        $this->db->set('ESTADO', $data['ESTADO']);
        $this->db->set('PLANTILLA', $data['PLANTILLA']);
        $this->db->set('NUM_RESOLUCION', $data['NUM_RESOLUCION']);
        $this->db->set('FECHA_RESOLUCION', "to_date('" . $data['FECHA_RESOLUCION'] . "','dd/mm/yyyy')", false);
        $this->db->set('COD_GESTIONCOBRO', $data['COD_GESTIONCOBRO']);
        $this->db->update('MANDAMIENTOPAGO');
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    
    function editMandamientoRecurso($data,$id){

        $this->db->trans_start();
        $this->db->where('COD_MANDAMIENTOPAGO', $id);
        $this->db->set('ESTADO', $data['ESTADO']);
        $this->db->set('TIPO_RECURSO',$data['TIPO_RECURSO']);
        $this->db->set('COD_GESTIONCOBRO', $data['COD_GESTIONCOBRO']);
        $this->db->update('MANDAMIENTOPAGO');
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    function editMandamiento($data, $id) {
        $this->db->trans_start();
        $this->db->where('COD_MANDAMIENTOPAGO', $id);
        $this->db->set('ESTADO', $data['ESTADO']);
        $this->db->set('COD_GESTIONCOBRO', $data['COD_GESTIONCOBRO']);
        $this->db->update('MANDAMIENTOPAGO');
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    function editMandamientoExc($data, $id) {
        $this->db->trans_start();
        $this->db->where('COD_MANDAMIENTOPAGO', $id);
        $this->db->set('ESTADO', $data['ESTADO']);
        $this->db->set('EXCEPCION', $data['EXCEPCION']);
        $this->db->set('COD_GESTIONCOBRO', $data['COD_GESTIONCOBRO']);
        $this->db->update('MANDAMIENTOPAGO');
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    function editMandamientoTipoExc($data, $id) {
        //var_dump($data);die;
        $this->db->trans_start();
        $this->db->where('COD_MANDAMIENTOPAGO', $id);
        $this->db->set('ESTADO', $data['ESTADO']);
        $this->db->set('EXCEPCION', $data['EXCEPCION']);
        $this->db->set('TIPO_EXCEPCION', $data['TIPO_EXCEPCION']);
        $this->db->set('COD_GESTIONCOBRO', $data['COD_GESTIONCOBRO']);
        $this->db->update('MANDAMIENTOPAGO');
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    function editMandaCautelar($data, $id) {
        $this->db->trans_start();
        $this->db->where('COD_MANDAMIENTOPAGO', $id);
        $this->db->set('FECHA_MEDIDA_CAUTERLAR', "to_date('" . $data['FECHA_MEDIDA_CAUTERLAR'] . "','dd/mm/yyyy')", false);
        $this->db->set('COMENTARIOS', $data['COMENTARIOS']);
        $this->db->set('OBSERVACIONES_MEDIDAS', $data['OBSERVACIONES_MEDIDAS']);
        $this->db->set('ESTADO', $data['ESTADO']);
        $this->db->set('COD_GESTIONCOBRO', $data['COD_GESTIONCOBRO']);
        $this->db->update('MANDAMIENTOPAGO');
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    function editExcepciones($table, $data, $fieldId, $id) {
        $this->db->trans_start();
        $this->db->where($fieldId, $id);
        $this->db->set('PRESENTA_EXCEPCIONES', $data['PRESENTA_EXCEPCIONES']);
        $this->db->set('FECHA_ONBASE', "to_date('" . $data['FECHA_ONBASE'] . "','dd/mm/yyyy')", false);
        $this->db->set('NOM_DOCUMENTO', $data['NOM_DOCUMENTO']);
        $this->db->set('RUTA_ARCHIVO', $data['RUTA_ARCHIVO']);
        $this->db->set('NUM_FOLIOS', $data['NUM_FOLIOS']);
        $this->db->set('PRESENTA_EXCEPCIONES', $data['PRESENTA_EXCEPCIONES']);
        $this->db->set('OBSERVACIONES', $data['OBSERVACIONES']);
        $this->db->update($table);
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }


    function editRecursos($data, $fieldId, $id) {

        $this->db->trans_start();
        $this->db->where('COD_MANDAMIENTO', $id);
        $this->db->set('FECHA_ONBASE', "to_date('" . $data['FECHA_ONBASE'] . "','dd/mm/yyyy')", false);
        $this->db->set('NOM_DOCUMENTO', $data['NOM_DOCUMENTO']);
        $this->db->set('RUTA_ARCHIVO', $data['RUTA_ARCHIVO']);
        $this->db->set('NUM_FOLIOS', $data['NUM_FOLIOS']);
        $this->db->set('OBSERVACIONES', $data['OBSERVACIONES']);
        $this->db->update('RECURSOS_NOTIFICACION');
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }
    }

    function editResolucion($data, $id) {

        $this->db->trans_start();
        $this->db->where('COD_MANDAMIENTOPAGO', $id);
        $this->db->set('COMENTARIOS', $data['COMENTARIOS']);
        $this->db->set('COD_ESTADO', $data['COD_ESTADO']);
        $this->db->set('PLANTILLA', $data['PLANTILLA']);
        $this->db->set('APROBADO', $data['APROBADO']);
        $this->db->set('REVISADO', $data['REVISADO']);
        $this->db->set('ASIGNADO_A', $data['ASIGNADO_A']);
        if ($data['FECHA_RESOLUCION'] == ''):
            $date = date('d/m/Y');
        else:
            $date = $data['FECHA_RESOLUCION'];
        endif;
        $this->db->set('FECHA_RESOLUCION', "to_date('" . $date . "','dd/mm/yyyy')", false);
        $this->db->set('NUM_RESOLUCION', $data['NUM_RESOLUCION']);
        $this->db->set('COD_GESTIONCOBRO', $data['COD_GESTIONCOBRO']);
        $this->db->update('RESOLUCIONEXCEPCION');
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() > 0) {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    function editResolExcep($data, $id) {

        $this->db->trans_start();
        $this->db->where('COD_MANDAMIENTOPAGO', $id);
        $this->db->set('COMENTARIOS', $data['COMENTARIOS']);
        $this->db->set('COD_ESTADO', $data['COD_ESTADO']);
        $this->db->set('PLANTILLA', $data['PLANTILLA']);
        $this->db->set('APROBADO', $data['APROBADO']);
        $this->db->set('REVISADO', $data['REVISADO']);
        $this->db->set('ASIGNADO_A', $data['ASIGNADO_A']);
        $this->db->set('FECHA_RESOLUCION', "to_date('" . $data['FECHA_RESOLUCION'] . "','dd/mm/yyyy')", false);
        $this->db->set('NUM_RESOLUCION', $data['NUM_RESOLUCION']);
        $this->db->set('COD_GESTIONCOBRO', $data['COD_GESTIONCOBRO']);
        $this->db->update('RESOLUCIONRECURSOMANDAMIENTO');
        if ($this->db->affected_rows() > 0) {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    function editMandaAd($data, $id) {

        $this->db->trans_start();
        $this->db->where('COD_MANDAMIENTOPAGO', $id);
        $this->db->set('FECHA_MODIFICA_ADELANTE', "to_date('" . $data['FECHA_MODIFICA_ADELANTE'] . "','dd/mm/yyyy')", false);
        $this->db->set('COMENTARIOS', $data['COMENTARIOS']);
        $this->db->set('APROBADO', $data['APROBADO']);
        $this->db->set('REVISADO', $data['REVISADO']);
        //$this->db->set('ASIGNADO_A', $data['ASIGNADO_A']);
        $this->db->set('ESTADO', $data['ESTADO']);
        $this->db->set('PLANTILLA', $data['PLANTILLA']);
        $this->db->set('COD_GESTIONCOBRO', $data['COD_GESTIONCOBRO']);
        $query = $this->db->update('MANDAMIENTO_ADELANTE');
        //print_r($this->db->last_query($query));die();
        if ($this->db->affected_rows() >= 0) {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    function count($table) {
        return $this->db->count_all($table);
    }

    function max($table, $field) {
        $this->db->select_max($field);
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->row_array(); //return the row as an associative array
        }
        return FALSE;
    }

    function getSelect($table, $fields, $where = '', $order = '') {
        $sql = "SELECT " . $fields . "  FROM " . $table . " ";
        if ($where != '')
            $sql .= "WHERE " . $where . " ";
        if ($order != '')
            $sql .= "ORDER BY " . $order . " ";
        $query = $this->db->query($sql);
        //print_r($this->db->last_query($query));die();
        return $query->result();
    }

    function getMandamiento($id) {
        $array = array();
        $this->db->select('COD_MANDAMIENTOPAGO,COD_FISCALIZACION,FECHA_MANDAMIENTO,CREADO_POR,COMENTARIOS,APROBADO,REVISADO,ASIGNADO_A,PLANTILLA,EXCEPCION,COD_GESTIONCOBRO');
        $this->db->from("MANDAMIENTOPAGO");
        $this->db->where('COD_MANDAMIENTOPAGO', $id);
        //$this->db->where('ACTIVO', '1');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }
    }

    function getRecursoDoc($id) {
        $array = array();
        $this->db->select('COD_MANDAMIENTO,NOM_DOCUMENTO,NUM_FOLIOS');
        $this->db->from("RECURSOS_NOTIFICACION");
        $this->db->where('COD_MANDAMIENTO', $id);
        //$this->db->where('ACTIVO', '1');
        $query = $this->db->get();
        //var_dump($this->db->last_query($query));die;

        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }
    }

    function getPagoDoc($id) {
        $array = array();
        $this->db->select('COD_MANDAMIENTO,NOM_DOCUMENTO,NUM_FOLIOS');
        $this->db->from("EXCEPCIONESNOTIFICACION");
        $this->db->where('COD_MANDAMIENTO', $id);
        //$this->db->where('ACTIVO', '1');
        $query = $this->db->get();
        //var_dump($this->db->last_query($query));die;

        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }
    }

    function getRecursoNotifi($id) {
        $array = array();
        $this->db->select('COD_RECURSO,FECHA_RECURSO,PRESENTA_RECURSOS,OBSERVACIONES,COD_MANDAMIENTO,NUM_FOLIOS,RUTA_ARCHIVO,NOM_DOCUMENTO,FECHA_ONBASE');
        $this->db->from("RECURSOS_NOTIFICACION");
        $this->db->where('COD_MANDAMIENTO', $id);
        //$this->db->where('ACTIVO', '1');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }
    }

    function getRecurso($id) {
        $array = array();
        $this->db->select('COD_MANDAMIENTOPAGO,COD_FISCALIZACION,FECHA_RECURSO,CREADO_POR,COMENTARIOS,APROBADO,REVISADO,ASIGNADO_A,PLANTILLA,COD_GESTIONCOBRO');
        $this->db->from("RESOLUCIONRECURSOMANDAMIENTO");
        $this->db->where('COD_MANDAMIENTOPAGO', $id);
        //$this->db->where('ACTIVO', '1');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }
    }

    function getRecursoExcep($id) {
        $array = array();
        $this->db->select('COD_RESOLUCION,COMENTARIOS,COD_ESTADO,PLANTILLA,COD_MANDAMIENTOPAGO,COD_FISCALIZACION,APROBADO,REVISADO,ASIGNADO_A,CREADO_POR');
        $this->db->from("RESOLUCIONEXCEPCION");
        $this->db->where('COD_MANDAMIENTOPAGO', $id);
        //$this->db->where('ACTIVO', '1');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }
    }

    function getRecursoResol($id) {
        $this->db->select('COD_ESTADO,PLANTILLA,COD_MANDAMIENTOPAGO,COD_FISCALIZACION,APROBADO,REVISADO,ASIGNADO_A,CREADO_POR,FECHA_RECURSO,COD_TIPORECURSO,FECHA_RESOLUCION,NUM_RESOLUCION');
        $this->db->where("COD_MANDAMIENTOPAGO", $id);
        $dato = $this->db->get("RESOLUCIONRECURSOMANDAMIENTO");
        //var_dump($dato);
        return $dato->result_array;
    }

    function getRespuesta($idGestion) {
        $array = array();
        $this->db->select('NOMBRE_GESTION,COD_TIPOGESTION,COD_RESPUESTA');
        $this->db->from("RESPUESTAGESTION");
        $this->db->where('COD_RESPUESTA', $idGestion);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }
    }

    function getResolucionExcep($id) {
        $this->db->select("COD_RESOLUCION, COD_ESTADO, PLANTILLA,FECHA_RESOLUCION,NUM_RESOLUCION,CREADO_POR,COD_MANDAMIENTOPAGO");
        $this->db->where("COD_MANDAMIENTOPAGO", $id);
        $dato = $this->db->get("RESOLUCIONEXCEPCION");
        //var_dump($dato);
        return $dato->result_array;
    }

    function getEmpresa($nit) {
        $this->db->select("NOMBRE_EMPRESA, CODEMPRESA, REPRESENTANTE_LEGAL,TELEFONO_FIJO,ACTIVO,DIRECCION");
        $this->db->where("CODEMPRESA", $nit);
        $dato = $this->db->get("EMPRESA");
        //var_dump($dato);
        return $dato->result_array;
    }

    function getMandamientoAdelante($id) {
        $array = array();
        $this->db->select('COD_MANDAMIENTOPAGO,COD_MANDAMIENTO_ADELANTE,COD_FISCALIZACION,COD_GESTIONCOBRO,FECHA_MANDAMIENTO_ADELANTE,CREADO_POR,COMENTARIOS,APROBADO,REVISADO,ASIGNADO_A,PLANTILLA');
        $this->db->from('MANDAMIENTO_ADELANTE');
        $this->db->where('COD_MANDAMIENTOPAGO', $id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }
    }

    function getTitulos($cod_coactivo) {
        $this->db->select('COD_ACUMULACION_COACTIVA, COD_RECEPCIONTITULO, COD_ESTADO_TITULO, COD_PROCESO_COACTIVO');
        $this->db->where('COD_PROCESO_COACTIVO', $cod_coactivo);
        $query = $this->db->get('ACUMULACION_COACTIVA');

        if ($query->num_rows() > 0) {
            return $query->result_array;
        }
    }

    function getTitulosProceso($cod_coactivo) {

        $this->db->select("VW.SALDO_DEUDA, VW.SALDO_CAPITAL,VW.NO_EXPEDIENTE, VW.SALDO_INTERES,VW.COD_EXPEDIENTE_JURIDICA, VW.CONCEPTO");
        $this->db->from('PROCESOS_COACTIVOS PC');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO AND VW.COD_RESPUESTA=PC.COD_RESPUESTA');
        $this->db->where('PC.COD_PROCESO_COACTIVO', $cod_coactivo);

        $query = $this->db->get();
//        echo $this->db->last_query();
//        die();
        if ($query->num_rows() > 0) {
            $query = $query->result_array();
            return $query;
        }
    }

    function getTitulosMandamiento($id_mandamiento, $cod_coactivo) {

        $this->db->select("VW.SALDO_DEUDA, VW.SALDO_CAPITAL, VW.SALDO_INTERES,VW.COD_EXPEDIENTE_JURIDICA, VW.CONCEPTO");
        $this->db->from('PROCESOS_COACTIVOS PC');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO AND VW.COD_RESPUESTA=PC.COD_RESPUESTA');
        $this->db->join('ACUMULACION_COACTIVA AC', 'PC.COD_PROCESO_COACTIVO=AC.COD_PROCESO_COACTIVO');
        $this->db->where('PC.COD_PROCESO_COACTIVO', $cod_coactivo);
        $this->db->where('AC.COD_MANDAMIENTOPAGO', $id_mandamiento);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $query = $query->result_array();
            return $query;
        }
    }

    function getMedidaCautelar($id) {
        $array = array();
        $this->db->select('MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR,MC_MEDIDASCAUTELARES.COD_PROCESO_COACTIVO,MC_MEDIDASCAUTELARES.COD_RESPUESTAGESTION,MC_MEDIDASCAUTELARES.BLOQUEO,
        MC_PRELACIONTITULO.OBSERVACIONES,MC_PRELACIONTITULO.CREADO_POR');
        $this->db->from("MC_MEDIDASCAUTELARES");
        $this->db->join('MC_MEDIDASPRELACION', 'MC_MEDIDASPRELACION.COD_MEDIDACAUTELAR = MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR', 'LEFT');
        $this->db->join('MC_PRELACIONTITULO', 'MC_PRELACIONTITULO.COD_MEDIDAPRELACION = MC_MEDIDASPRELACION.COD_MEDIDAPRELACION', 'LEFT');
        $this->db->where('MC_MEDIDASCAUTELARES.COD_PROCESO_COACTIVO', $id);
        $this->db->where('MC_MEDIDASCAUTELARES.BLOQUEO', 1);
        $query = $this->db->get();
        //print_r($this->db->last_query($query));die();
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }
    }

    function getNotifica($id, $tipo, $excep, $rec, $estado) {
        $array = array();
        $this->db->select('COD_AVISONOTIFICACION,OBSERVACIONES,PLANTILLA,COD_ESTADO,DOC_COLILLA,NUM_RADICADO_ONBASE,FECHA_ONBASE,COD_MOTIVODEVOLUCION,COD_COMUNICACION,FECHA_NOTIFICACION,COD_MANDAMIENTOPAGO,NOMBRE_DOC_CARGADO,ESTADO_NOTIFICACION,DOC_FIRMADO,RECURSO,EXCEPCION');
        $this->db->from("AVISONOTIFICACION");
        $this->db->where('COD_MANDAMIENTOPAGO', $id);
        $this->db->where('COD_TIPONOTIFICACION', $tipo);
        $this->db->where('ESTADO_NOTIFICACION', $estado);
        $this->db->where('EXCEPCION', $excep);
        $this->db->where('RECURSO', $rec);
        $query = $this->db->get();
        //print_r($this->db->last_query($query));die();
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }
    }

    function getNotificaCautelares($id, $tipo) {
        $array = array();
        $this->db->select('COD_AVISONOTIFICACION,OBSERVACIONES,PLANTILLA,COD_ESTADO,DOC_COLILLA,NUM_RADICADO_ONBASE,FECHA_ONBASE,COD_MOTIVODEVOLUCION,COD_COMUNICACION,FECHA_NOTIFICACION,COD_MANDAMIENTOPAGO');
        $this->db->from("AVISONOTIFICACION");
        $this->db->where('COD_MANDAMIENTOPAGO', $id);
        $this->db->where('COD_TIPONOTIFICACION', $tipo);
        //$this->db->where('ACTIVO', '1');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }
    }

    function getAdelante($id, $notificacion) {
        $array = array();
        $this->db->select('M.COD_GESTIONCOBRO,M.COD_MANDAMIENTOPAGO,M.COD_FISCALIZACION,M.FECHA_MANDAMIENTO,M.CREADO_POR,M.ASIGNADO_A,
        A.NOMBRES AS ASIGNADO,B.NOMBRES AS CREADO,M.ESTADO,M.PLANTILLA,M.APROBADO,A.COD_TIPONOTIFICACION        
        ');
        $this->db->join('AVISONOTIFICACION A', 'A.COD_MANDAMIENTOPAGO = M.COD_MANDAMIENTOPAGO');
        $this->db->join('USUARIOS A', 'A.IDUSUARIO = M.ASIGNADO_A');
        $this->db->join('USUARIOS B', 'B.IDUSUARIO = M.CREADO_POR');
        $this->db->where('A.COD_TIPONOTIFICACION', $notificacion);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }
    }

    function lisUsuarios($fields, $regional, $cargo, $order = '') {
        $this->db->select($fields);
        $this->db->where('COD_REGIONAL', $regional);
        $this->db->where('IDCARGO', $cargo);
        $this->db->order_by($order);
        $query = $this->db->get("USUARIOS");
        //print_r($this->db->last_query($query));die();
        return $query->result();
    }

    function lisUsuariosecre($fields, $regional, $cargo1, $cargo2, $order = '') {
        $this->db->select($fields);
        $this->db->where('COD_REGIONAL', $regional);
        $this->db->where('(IDCARGO = ' . $cargo1 . ' OR IDCARGO = ' . $cargo2 . ')');
        $this->db->order_by($order);
        $query = $this->db->get("USUARIOS");
        //print_r($this->db->last_query($query));die();
        return $query->result();
    }

    function getNitsMandamiento($cod_proceso) {
        $this->db->select('MP.COD_MANDAMIENTOPAGO AS PROCESO,TO_NUMBER(MP.ESTADO) AS COD_RESPUESTA,VW.RESPUESTA AS RESPUESTA,'
                . 'PC.COD_PROCESO_COACTIVO AS COD_PROCESO,PC.ABOGADO AS ABOGADO, PC.COD_PROCESOPJ AS PROCESOPJ,VW.EJECUTADO AS NOMBRE,'
                . 'PC.IDENTIFICACION AS IDENTIFICACION, US.NOMBRES, US.APELLIDOS, VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,'
                . ' VW.COD_REGIONAL AS COD_REGIONAL,MP.ESTADO,MP.ASIGNADO_A,MP.TIPO_EXCEPCION,MP.TIPO_RECURSO,MP.FECHA_MANDAMIENTO,MP.APLICA_MEDIDA_CAUTELAR,
            MP.COD_MANDAMIENTOPAGO,VW.DIRECCION AS DIRECCION,RG.URLGESTION,MP.PLANTILLA,MP.APROBADO');
        $this->db->from('MANDAMIENTOPAGO MP');
        $this->db->join('RESPUESTAGESTION RG', 'RG.COD_RESPUESTA = MP.ESTADO');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=MP.COD_PROCESO_COACTIVO');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=PC.ABOGADO');
        $where = 'VW.COD_RESPUESTA = MP.ESTADO';
        $this->db->where($where);
        $this->db->where('PC.COD_PROCESO_COACTIVO', $cod_proceso);
        $query = $this->db->get('');
//        $this->db->select('A.NIT_EMPRESA,E.NOMBRE_EMPRESA,E.COD_REGIONAL,F.COD_ABOGADO,MANDAMIENTOPAGO.ESTADO,
//        MANDAMIENTOPAGO.ASIGNADO_A,MANDAMIENTOPAGO.COD_MANDAMIENTOPAGO,MANDAMIENTOPAGO.COD_FISCALIZACION,
//        E.DIRECCION,MANDAMIENTOPAGO.TIPO_EXCEPCION, MANDAMIENTOPAGO.TIPO_RECURSO, MANDAMIENTOPAGO.FECHA_MANDAMIENTO,E.DIRECCION,
//        MANDAMIENTOPAGO.APLICA_MEDIDA_CAUTELAR,RG.URLGESTION,MANDAMIENTOPAGO.COD_PROCESO_COACTIVO');
        //$this->db->where('F.COD_ABOGADO',$user);        
//        $this->db->join('RESPUESTAGESTION RG', 'RG.COD_RESPUESTA = MANDAMIENTOPAGO.ESTADO');
//        $this->db->join('FISCALIZACION F', 'F.COD_FISCALIZACION = MANDAMIENTOPAGO.COD_FISCALIZACION');
//        $this->db->join('ASIGNACIONFISCALIZACION A', 'F.COD_ASIGNACION_FISC = A.COD_ASIGNACIONFISCALIZACION');                
//        $this->db->join('EMPRESA E', 'E.CODEMPRESA = A.NIT_EMPRESA');
//        $this->db->where('MANDAMIENTOPAGO.COD_PROCESO_COACTIVO',$cod_proceso);       
        //$this->db->where('MANDAMIENTOPAGO.COD_FISCALIZACION',$fiscalizacion);       
        //$this->db->where('E.COD_REGIONAL',$regional);       
        //$this->db->where('MANDAMIENTOPAGO.ESTADO',204);  
        //$this->db->where('MANDAMIENTOPAGO.ASIGNADO_A',$user);
//        $this->db->order_by('E.NOMBRE_EMPRESA', 'ASC');        
//        $query = $this->db->get("MANDAMIENTOPAGO");        
        //print_r($this->db->last_query($query));die();
        if ($query->num_rows() > 0) {
            //return $query;   
            return $query->result_array;
        }
    }

    function getNitsMandamientoAbogado($user) {
        $this->db->select('A.NIT_EMPRESA,E.NOMBRE_EMPRESA,E.COD_REGIONAL,F.COD_ABOGADO,M.ESTADO,
        M.ASIGNADO_A,M.COD_MANDAMIENTOPAGO,M.COD_FISCALIZACION,M.PLANTILLA,
        M.APROBADO,M.TIPO_EXCEPCION, M.TIPO_RECURSO, M.FECHA_MANDAMIENTO');
        //$this->db->where('F.COD_ABOGADO',$user);        
        $this->db->join('FISCALIZACION F', 'F.COD_FISCALIZACION = M.COD_FISCALIZACION');
        $this->db->join('ASIGNACIONFISCALIZACION A', 'F.COD_ASIGNACION_FISC = A.COD_ASIGNACIONFISCALIZACION');
        $this->db->join('EMPRESA E', 'E.CODEMPRESA = A.NIT_EMPRESA');
        $this->db->where('(M.ESTADO = \'207\' OR M.ESTADO = \'208\' OR M.ESTADO = \'209\' OR M.ESTADO = \'211\' 
        OR M.ESTADO = \'212\' OR M.ESTADO = \'213\' OR M.ESTADO = \'214\' OR M.ESTADO = \'215\' OR M.ESTADO = \'217\' 
        OR M.ESTADO = \'218\' OR M.ESTADO = \'219\' OR M.ESTADO = \'221\' OR M.ESTADO = \'222\' OR M.ESTADO = \'223\' 
        OR M.ESTADO = \'225\' OR M.ESTADO = \'226\' OR M.ESTADO = \'227\' OR M.ESTADO = \'229\' OR M.ESTADO = \'233\' 
        OR M.ESTADO = \'235\' OR M.ESTADO = \'236\' OR M.ESTADO = \'237\' OR M.ESTADO = \'239\' OR M.ESTADO = \'240\' 
        OR M.ESTADO = \'241\' OR M.ESTADO = \'243\' OR M.ESTADO = \'244\' OR M.ESTADO = \'245\' OR M.ESTADO = \'248\' 
        OR M.ESTADO = \'249\' OR M.ESTADO = \'250\' OR M.ESTADO = \'251\' OR M.ESTADO = \'254\' OR M.ESTADO = \'255\' 
        OR M.ESTADO = \'256\' OR M.ESTADO = \'257\' OR M.ESTADO = \'258\' OR M.ESTADO = \'259\' OR M.ESTADO = \'261\' 
        OR M.ESTADO = \'263\' OR M.ESTADO = \'266\' OR M.ESTADO = \'267\' OR M.ESTADO = \'270\' OR M.ESTADO = \'271\' 
        OR M.ESTADO = \'273\' OR M.ESTADO = \'274\' OR M.ESTADO = \'275\' OR M.ESTADO = \'662\' OR M.ESTADO = \'741\' 
        OR M.ESTADO = \'742\' OR M.ESTADO = \'743\' OR M.ESTADO = \'744\' OR M.ESTADO = \'950\' OR M.ESTADO = \'951\' 
        OR M.ESTADO = \'952\' OR M.ESTADO = \'958\' OR M.ESTADO = \'959\' OR M.ESTADO = \'1020\' OR M.ESTADO = \'1021\' 
        OR M.ESTADO = \'1022\' OR M.ESTADO = \'1032\' OR M.ESTADO = \'1033\' OR M.ESTADO = \'1038\' OR M.ESTADO = \'1039\' 
        OR M.ESTADO = \'1058\' OR M.ESTADO = \'1070\' OR M.ESTADO = \'1071\' OR M.ESTADO = \'1080\' OR M.ESTADO = \'1081\' 
        OR M.ESTADO = \'1085\' OR M.ESTADO = \'1086\' OR M.ESTADO = \'1087\' OR M.ESTADO = \'1088\' OR M.ESTADO = \'1089\'
        OR M.ESTADO = \'1321\' OR M.ESTADO = \'1322\')');
        $this->db->where('M.ASIGNADO_A', $user);
        $this->db->order_by('E.NOMBRE_EMPRESA', 'ASC');
        $query = $this->db->get("MANDAMIENTOPAGO M");
//        $nEstadoMandamientoPago = 21;
//        $codTipoRespuesta = 204 ;
//        $this->db->select('COBROPERSUASIVO.COD_COBRO_PERSUASIVO,T.COD_RECEPCIONTITULO,COBROPERSUASIVO.COD_FISCALIZACION,
//        COBROPERSUASIVO.NIT_EMPRESA,E.NOMBRE_EMPRESA,E.DIRECCION,M.ESTADO,M.COD_MANDAMIENTOPAGO,M.COD_GESTIONCOBRO,
//        M.PLANTILLA,M.ASIGNADO_A,M.TIPO_EXCEPCION,M.APROBADO,M.TIPO_RECURSO,M.FECHA_MANDAMIENTO');
//        $this->db->where('COD_ESTADO_PROCESO',$nEstadoMandamientoPago);
//        $this->db->where('COD_TIPO_RESPUESTA',$codTipoRespuesta);
//        $this->db->where('(M.ESTADO = \'207\' OR M.ESTADO = \'208\' OR M.ESTADO = \'209\' OR M.ESTADO = \'211\' 
//        OR M.ESTADO = \'212\' OR M.ESTADO = \'213\' OR M.ESTADO = \'214\' OR M.ESTADO = \'215\' OR M.ESTADO = \'217\' 
//        OR M.ESTADO = \'218\' OR M.ESTADO = \'219\' OR M.ESTADO = \'221\' OR M.ESTADO = \'222\' OR M.ESTADO = \'223\' 
//        OR M.ESTADO = \'225\' OR M.ESTADO = \'226\' OR M.ESTADO = \'227\' OR M.ESTADO = \'229\' OR M.ESTADO = \'233\' 
//        OR M.ESTADO = \'235\' OR M.ESTADO = \'236\' OR M.ESTADO = \'237\' OR M.ESTADO = \'239\' OR M.ESTADO = \'240\' 
//        OR M.ESTADO = \'241\' OR M.ESTADO = \'243\' OR M.ESTADO = \'244\' OR M.ESTADO = \'245\' OR M.ESTADO = \'248\' 
//        OR M.ESTADO = \'249\' OR M.ESTADO = \'250\' OR M.ESTADO = \'251\' OR M.ESTADO = \'254\' OR M.ESTADO = \'255\' 
//        OR M.ESTADO = \'256\' OR M.ESTADO = \'257\' OR M.ESTADO = \'258\' OR M.ESTADO = \'259\' OR M.ESTADO = \'261\' 
//        OR M.ESTADO = \'263\' OR M.ESTADO = \'266\' OR M.ESTADO = \'267\' OR M.ESTADO = \'270\' OR M.ESTADO = \'271\' 
//        OR M.ESTADO = \'273\' OR M.ESTADO = \'274\' OR M.ESTADO = \'275\' OR M.ESTADO = \'662\' OR M.ESTADO = \'741\' 
//        OR M.ESTADO = \'742\' OR M.ESTADO = \'743\' OR M.ESTADO = \'744\' OR M.ESTADO = \'950\' OR M.ESTADO = \'951\' 
//        OR M.ESTADO = \'952\' OR M.ESTADO = \'958\' OR M.ESTADO = \'959\' OR M.ESTADO = \'1020\' OR M.ESTADO = \'1021\' 
//        OR M.ESTADO = \'1022\' OR M.ESTADO = \'1032\' OR M.ESTADO = \'1033\' OR M.ESTADO = \'1038\' OR M.ESTADO = \'1039\' 
//        OR M.ESTADO = \'1058\' OR M.ESTADO = \'1070\' OR M.ESTADO = \'1071\' OR M.ESTADO = \'1080\' OR M.ESTADO = \'1081\' 
//        OR M.ESTADO = \'1085\' OR M.ESTADO = \'1086\' OR M.ESTADO = \'1087\' OR M.ESTADO = \'1088\' OR M.ESTADO = \'1089\')');
//        $this->db->where('ASIGNADO_A',$user);
//        $this->db->join('RECEPCIONTITULOS T', 'T.COD_RECEPCIONTITULO = COBROPERSUASIVO.COD_RECEPCIONTITULO');
//        $this->db->join('EMPRESA E', 'E.CODEMPRESA = COBROPERSUASIVO.NIT_EMPRESA');   
//        $this->db->join('FISCALIZACION F', 'F.COD_FISCALIZACION = COBROPERSUASIVO.COD_FISCALIZACION');
//        $this->db->join('MANDAMIENTOPAGO M', 'M.COD_FISCALIZACION = F.COD_FISCALIZACION');
//        $this->db->order_by('E.NOMBRE_EMPRESA', 'ASC');
//        $query = $this->db->get("COBROPERSUASIVO");
        if ($query->num_rows() > 0) {
            return $query;
        }
    }

    function getNitsMandamientoSecretario($user) {
        $this->db->select('A.NIT_EMPRESA,E.NOMBRE_EMPRESA,E.COD_REGIONAL,F.COD_ABOGADO,M.ESTADO,
        M.ASIGNADO_A,M.COD_MANDAMIENTOPAGO,M.COD_FISCALIZACION,M.PLANTILLA,
        M.APROBADO,M.TIPO_EXCEPCION, M.TIPO_RECURSO, M.FECHA_MANDAMIENTO');
        //$this->db->where('F.COD_ABOGADO',$user);        
        $this->db->join('FISCALIZACION F', 'F.COD_FISCALIZACION = M.COD_FISCALIZACION');
        $this->db->join('ASIGNACIONFISCALIZACION A', 'F.COD_ASIGNACION_FISC = A.COD_ASIGNACIONFISCALIZACION');
        $this->db->join('EMPRESA E', 'E.CODEMPRESA = A.NIT_EMPRESA');
        $this->db->where('(M.ESTADO = \'205\' OR M.ESTADO = \'228\' OR M.ESTADO = \'230\' OR M.ESTADO = \'246\' 
        OR M.ESTADO = \'220\' OR M.ESTADO = \'210\' OR M.ESTADO = \'216\' OR M.ESTADO = \'224\' OR M.ESTADO = \'238\' 
        OR M.ESTADO = \'242\' OR M.ESTADO = \'252\' OR M.ESTADO = \'254\' OR M.ESTADO = \'260\' OR M.ESTADO = \'264\' 
        OR M.ESTADO = \'268\' OR M.ESTADO = \'272\' OR M.ESTADO = \'659\' OR M.ESTADO = \'1018\')');
        $this->db->where('M.ASIGNADO_A', $user);
        $this->db->order_by('E.NOMBRE_EMPRESA', 'ASC');
        $query = $this->db->get("MANDAMIENTOPAGO M");
//        $this->db->select('COBROPERSUASIVO.COD_COBRO_PERSUASIVO,T.COD_RECEPCIONTITULO,COBROPERSUASIVO.COD_FISCALIZACION,
//        COBROPERSUASIVO.NIT_EMPRESA,E.NOMBRE_EMPRESA,E.DIRECCION,M.ESTADO,M.COD_MANDAMIENTOPAGO,M.COD_GESTIONCOBRO,
//        M.PLANTILLA,M.ASIGNADO_A,M.TIPO_EXCEPCION,M.APROBADO,M.TIPO_RECURSO,M.FECHA_MANDAMIENTO');
//        $this->db->where('COD_ESTADO_PROCESO',$nEstadoMandamientoPago);
//        $this->db->where('COD_TIPO_RESPUESTA',$codTipoRespuesta);
//        $this->db->where('ASIGNADO_A',$user);
//        $this->db->where('(M.ESTADO = \'205\' OR M.ESTADO = \'228\' OR M.ESTADO = \'230\' OR M.ESTADO = \'246\' 
//        OR M.ESTADO = \'220\' OR M.ESTADO = \'210\' OR M.ESTADO = \'216\' OR M.ESTADO = \'224\' OR M.ESTADO = \'238\' 
//        OR M.ESTADO = \'242\' OR M.ESTADO = \'252\' OR M.ESTADO = \'254\' OR M.ESTADO = \'260\' OR M.ESTADO = \'264\' 
//        OR M.ESTADO = \'268\' OR M.ESTADO = \'272\' OR M.ESTADO = \'659\' OR M.ESTADO = \'1018\')');
//        $this->db->join('RECEPCIONTITULOS T', 'T.COD_RECEPCIONTITULO = COBROPERSUASIVO.COD_RECEPCIONTITULO');
//        $this->db->join('EMPRESA E', 'E.CODEMPRESA = COBROPERSUASIVO.NIT_EMPRESA');   
//        $this->db->join('FISCALIZACION F', 'F.COD_FISCALIZACION = COBROPERSUASIVO.COD_FISCALIZACION');
//        $this->db->join('MANDAMIENTOPAGO M', 'M.COD_FISCALIZACION = F.COD_FISCALIZACION');
//        $this->db->order_by('E.NOMBRE_EMPRESA', 'ASC');
//        $query = $this->db->get("COBROPERSUASIVO");
        if ($query->num_rows() > 0) {
            return $query;
        }
    }

    function getDocumentos($cod_manda) {
        $this->db->select('NOMBRE_DOC_CARGADO,COD_TIPONOTIFICACION,COD_MANDAMIENTOPAGO,DOC_FIRMADO,ESTADO_NOTIFICACION,
        DOC_FIRMADO,RECURSO,EXCEPCION,FECHA_MODIFICA_NOTIFICACION');
        $this->db->where('COD_MANDAMIENTOPAGO', $cod_manda);
        $this->db->where('ESTADO_NOTIFICACION', 'ACTIVO');
        $this->db->where('(COD_ESTADO =\'6\' OR COD_ESTADO =\'7\')');
        $this->db->order_by('FECHA_MODIFICA_NOTIFICACION', 'ASC');
        $query = $this->db->get("AVISONOTIFICACION");
        //var_dump($this->db->last_query($query));die;
        return $query;
    }

    function getNitsMandamientoCoordinador($user) {
        $this->db->select('A.NIT_EMPRESA,E.NOMBRE_EMPRESA,E.COD_REGIONAL,F.COD_ABOGADO,M.ESTADO,
        M.ASIGNADO_A,M.COD_MANDAMIENTOPAGO,M.COD_FISCALIZACION,M.PLANTILLA,
        M.APROBADO,M.TIPO_EXCEPCION, M.TIPO_RECURSO, M.FECHA_MANDAMIENTO');
        //$this->db->where('F.COD_ABOGADO',$user);        
        $this->db->join('FISCALIZACION F', 'F.COD_FISCALIZACION = M.COD_FISCALIZACION');
        $this->db->join('ASIGNACIONFISCALIZACION A', 'F.COD_ASIGNACION_FISC = A.COD_ASIGNACIONFISCALIZACION');
        $this->db->join('EMPRESA E', 'E.CODEMPRESA = A.NIT_EMPRESA');
        $this->db->where('(M.ESTADO = \'206\' OR M.ESTADO = \'246\' OR M.ESTADO = \'238\' OR M.ESTADO = \'239\' 
        OR M.ESTADO = \'240\' OR M.ESTADO = \'241\' OR M.ESTADO = \'247\' OR M.ESTADO = \'252\' OR M.ESTADO = \'253\' 
        OR M.ESTADO = \'255\' OR M.ESTADO = \'265\' OR M.ESTADO = \'1019\')');
        $this->db->where('M.ASIGNADO_A', $user);
        $this->db->order_by('E.NOMBRE_EMPRESA', 'ASC');
        $query = $this->db->get("MANDAMIENTOPAGO M");
//        $nEstadoMandamientoPago = 21;
//        $codTipoRespuesta = 204 ;
//        $this->db->select('COBROPERSUASIVO.COD_COBRO_PERSUASIVO,T.COD_RECEPCIONTITULO,COBROPERSUASIVO.COD_FISCALIZACION,
//        COBROPERSUASIVO.NIT_EMPRESA,E.NOMBRE_EMPRESA,E.DIRECCION,M.ESTADO,M.COD_MANDAMIENTOPAGO,M.COD_GESTIONCOBRO,
//        M.PLANTILLA,M.ASIGNADO_A,M.TIPO_EXCEPCION,M.APROBADO,M.TIPO_RECURSO,M.FECHA_MANDAMIENTO');
//        $this->db->where('COD_ESTADO_PROCESO',$nEstadoMandamientoPago);
//        $this->db->where('COD_TIPO_RESPUESTA',$codTipoRespuesta);
//        $this->db->where('ASIGNADO_A',$user);
//        $this->db->where('(M.ESTADO = \'206\' OR M.ESTADO = \'246\' OR M.ESTADO = \'238\' OR M.ESTADO = \'239\' 
//        OR M.ESTADO = \'240\' OR M.ESTADO = \'241\' OR M.ESTADO = \'247\' OR M.ESTADO = \'252\' OR M.ESTADO = \'253\' 
//        OR M.ESTADO = \'255\' OR M.ESTADO = \'265\' OR M.ESTADO = \'1019\')');
//        $this->db->join('RECEPCIONTITULOS T', 'T.COD_RECEPCIONTITULO = COBROPERSUASIVO.COD_RECEPCIONTITULO');
//        $this->db->join('EMPRESA E', 'E.CODEMPRESA = COBROPERSUASIVO.NIT_EMPRESA');   
//        $this->db->join('FISCALIZACION F', 'F.COD_FISCALIZACION = COBROPERSUASIVO.COD_FISCALIZACION');
//        $this->db->join('MANDAMIENTOPAGO M', 'M.COD_FISCALIZACION = F.COD_FISCALIZACION');
//        $this->db->order_by('E.NOMBRE_EMPRESA', 'ASC');
//        $this->db->order_by('E.NOMBRE_EMPRESA', 'ASC');
//        $query = $this->db->get("COBROPERSUASIVO");
        if ($query->num_rows() > 0) {
            return $query;
        }
    }

    function getDataTableAdelante($sTipoProceso = '', $nNumProceso = '', $sCreado = '', $sAsignado = '', $sFecha = '', $nEstado = '') {

        $this->db->select('MA.COD_GESTIONCOBRO,MA.COD_MANDAMIENTOPAGO,MA.COD_FISCALIZACION,MA.FECHA_MANDAMIENTO_ADELANTE,
        MA.CREADO_POR,MA.ASIGNADO_A,A.NOMBRES AS ASIGNADO,B.NOMBRES AS CREADO,MA.ESTADO,MA.PLANTILLA,MA.APROBADO,MA.REVISADO,
        RT.NIT_EMPRESA');
        if (!empty($nNumProceso))
            $this->db->where('MA.COD_FISCALIZACION', $nNumProceso);
        if (!empty($sCreado))
            $this->db->where('MA.CREADO_POR', $sCreado);
        if (!empty($sAsignado))
            $this->db->where('MA.ASIGNADO_A', $sAsignado);
        if (!empty($sFecha))
            $this->db->where('MA.FECHA_MANDAMIENTO_ADELANTE', $sFecha);
        $this->db->join('USUARIOS A', 'A.IDUSUARIO = MA.ASIGNADO_A');
        $this->db->join('USUARIOS B', 'B.IDUSUARIO = MA.CREADO_POR');
        $this->db->join('RECEPCIONTITULOS RT', 'RT.COD_FISCALIZACION_EMPRESA = MA.COD_FISCALIZACION');
        $this->db->order_by('MA.COD_MANDAMIENTOPAGO', 'DESC');
        return $this->db->get("MANDAMIENTO_ADELANTE MA");
    }

    function totalData($sSearch) {

        $this->db->select('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,CREADO,ACTIVO');
        $this->db->from('USUARIOS');
        $this->db->where('ACTIVO', '1');
        $this->db->like('USUARIOS.NOMBRES', $sSearch, 'both');
        $query = $this->db->get();

        if ($query->num_rows() == 0)
            return '0';
        else
            return $query->num_rows();
    }

    function getSequence($table, $name) {
        $query = $this->db->query("SELECT " . $name . "  FROM " . $table . " ");
        $row = $query->row_array();
        return @$row['NEXTVAL'] - 1;
    }

    function permiso() {
        $this->db->select("USUARIOS.IDUSUARIO as IDUSUARIO, APELLIDOS, NOMBRES,GRUPOS.IDGRUPO,USUARIOS.COD_REGIONAL");
        $this->db->join("USUARIOS_GRUPOS", "USUARIOS.IDUSUARIO=USUARIOS_GRUPOS.IDUSUARIO");
        $this->db->join("GRUPOS", "USUARIOS_GRUPOS.IDGRUPO=GRUPOS.IDGRUPO");
        $this->db->or_where("(GRUPOS.IDGRUPO", ABOGADO);
        $this->db->or_where("GRUPOS.IDGRUPO", SECRETARIO);
        $this->db->or_where("GRUPOS.IDGRUPO", ADMIN);
        $this->db->or_where("GRUPOS.IDGRUPO", COORDINADOR . ")", FALSE);
        $this->db->where("USUARIOS.IDUSUARIO", ID_USER);
        $dato = $this->db->get("USUARIOS");
        //var_dump($this->db->last_query($dato));die;
        return $dato->result_array;
    }

    function verificaMedida($fiscalizacion, $mandamiento, $datos) {
        $noMedidas = 1322;
        $this->db->select("COD_MEDIDACAUTELAR");
        $this->db->from("MC_MEDIDASCAUTELARES");
        $this->db->where("MC_MEDIDASCAUTELARES.COD_PROCESO_COACTIVO", $fiscalizacion);
        $resultado = $this->db->get();

        if ($resultado->num_rows() == 0) {
            $this->db->set('COD_PROCESO_COACTIVO', $datos['COD_PROCESO_COACTIVO']);
            $this->db->set('COD_GESTIONCOBRO', $datos['COD_GESTIONCOBRO']);
            $this->db->set('FECHA_MEDIDAS', "to_date('" . $data['FECHA_MEDIDAS'] . "','dd/mm/yyyy')", false);
            $this->db->set('COD_RESPUESTAGESTION', $noMedidas);
            $query = $this->db->insert('MC_MEDIDASCAUTELARES');
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
     function agrega_expediente($cod_respuesta, $nom_doc, $ruta_doc, $num_radicado = FALSE, $fecha_radicado = FALSE, $cod_tipoexp, $subproceso, $id_usuario, $cod_coactivo, $fecha_not_efectiva = FALSE,$num_resolucion,$fecha_resolucion) {

        $this->db->set("COD_PROCESO_COACTIVO", $cod_coactivo);
        $this->db->set("COD_RESPUESTAGESTION", $cod_respuesta);
        $this->db->set("NOMBRE_DOCUMENTO", $nom_doc);
        $this->db->set("RUTA_DOCUMENTO", $ruta_doc);
        $this->db->set("FECHA_DOCUMENTO", 'SYSDATE', FALSE);
        if (!empty($num_radicado))
            $this->db->set("NUMERO_RADICADO", $num_radicado);
        if (!empty($num_resolucion))
            $this->db->set("NUMERO_RESOLUCION", $num_resolucion);
        if (!empty($fecha_resolucion))
            $this->db->set("FECHA_RESOLUCION", "TO_DATE('" . $fecha_resolucion . "','yyyy/mm/dd')", FALSE);
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
    function Resolución($ID){
        $this->db->select('MP.NUM_RESOLUCION, MP.FECHA_RESOLUCION,PC.COD_PROCESOPJ, PC.FECHA_AVOCA,VW.CONCEPTO');
        $this->db->from('MANDAMIENTOPAGO MP');
        $this->db->join('PROCESOS_COACTIVOS PC','MP.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO');
        $this->db->join('VW_PROCESOS_COACTIVOS VW','VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO AND VW.COD_RESPUESTA=PC.COD_RESPUESTA');
        $this->db->where('COD_MANDAMIENTOPAGO',$ID);
        $resultado=$this->db->get();
        $resultado=$resultado->result_array();
        return $resultado;
    }

}
