<?php

class Checklist_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    function listar($concepto='', $descripcion=''){
        $this->db->from("MAESTRO_CHECKLIST MA, TIPOCONCEPTO TC");
        $this->db->where("TC.COD_TIPOCONCEPTO = MA.ID_CONCEPTO");
        if($concepto != '') $this->db->where("MA.ID_CONCEPTO", $concepto);
        if($descripcion != '') $this->db->where("MA.DESCRIPCION", $descripcion);
        $consulta = $this->db->get();
        return $consulta;
    }
    
    function getconceptos(){
        $this->db->where("ACTIVO", 1);
        $consulta = $this->db->get("CONCEPTO_CHECKLIST");
        return $consulta;
    }
    
    function guardar($concepto, $descripcion, $texto, $orden, $activo){
        $this->db->set('ID_CONCEPTO', $concepto);
        $this->db->set('DESCRIPCION', $descripcion);
        $this->db->set('TEXTO', $texto);
        $this->db->set('ORDEN', $orden);
        $this->db->set('ACTIVO', $activo);
        $this->db->where('ID_CONCEPTO', $concepto);
        $this->db->where('DESCRIPCION', $descripcion);
        $consulta = $this->db->update('MAESTRO_CHECKLIST');
        return $consulta;
    }
    
    function guardar2($concepto, $descripcion, $texto, $orden, $activo){
        $this->db->set('ID_CONCEPTO', $concepto);
        $this->db->set('DESCRIPCION', $descripcion);
        $this->db->set('TEXTO', $texto);
        $this->db->set('ORDEN', $orden);
        $this->db->set('ACTIVO', $activo);
        $consulta = $this->db->insert('MAESTRO_CHECKLIST');
        return $consulta;
    }   
}