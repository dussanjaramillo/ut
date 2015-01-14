<?php

/**
 * Modelo 
 */
class Procinfoposdeudores_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function nitConPeriodosInactivos(){
        $this->db->select('COMFE.NIT');
        $this->db->from('COMFECAMARAS COMFE'); 
        $this->db->join('PERIODOSINACTIVOS P','P.COD_EMPRESA = COMFE.NIT', 'inner');
        
        $consulta = $this->db->get();
        return $consulta->result();
    }

    function nitConPagosEnPila(){
        $this->db->select('COMFE.NIT');
        $this->db->from('COMFECAMARAS COMFE'); 
        $this->db->join('PLANILLAUNICA_DET P','P.N_IDENT_APORTANTE = COMFE.NIT', 'inner');
        
        $consulta = $this->db->get();
        return $consulta->result();
    }

}
