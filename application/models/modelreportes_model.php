<?php
class Modelreportes_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    function regional(){
      $this->db->select("COD_REGIONAL,NOMBRE_REGIONAL");
      return $this->db->get('REGIONAL');
    }
    function fiscalizador(){
        
        $this->db->select("IDUSUARIO,NOMBREUSUARIO");
        $this->db->join('CARGOS','CARGOS.IDCARGO=USUARIOS.IDCARGO');
        $this->db->where('USUARIOS.IDCARGO', 5);
        return $this->db->get('USUARIOS');
    }
    function ciudad(){
      $this->db->select("CODMUNICIPIO,NOMBREMUNICIPIO");
      return $this->db->get('MUNICIPIO'); 
    }
}  
    