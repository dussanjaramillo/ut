<?php

class Plantillas_model extends CI_Model {
    
    function plantillas($id=NULL){
        $this->db->select('CODPLANTILLA,NOMBRE_PLANTILLA,TEXTO,ACTIVO,TIPO_PLANTILLA,ARCHIVO_PLANTILLA');
        if(!empty($id))
        $this->db->where('CODPLANTILLA', $id);
        $dato = $this->db->get('PLANTILLA');
        return $datos = $dato->result_array;
    }
    function guardar($post){
        $this->db->set('NOMBRE_PLANTILLA', $post['post']['nombre2']);
        $this->db->set('ARCHIVO_PLANTILLA', $post['post']['nombre'].".txt");
        $this->db->set('TEXTO', '1');
        $this->db->insert('PLANTILLA');
    }
    function modificar($post){
        $this->db->set('NOMBRE_PLANTILLA', $post['post']['nombre2']);
        $this->db->set('ARCHIVO_PLANTILLA', $post['post']['nombre'].".txt");
        $this->db->where('CODPLANTILLA', $post['post']['id']);
        $this->db->update('PLANTILLA');
    }
}