<?php
// Responsable: Leonardo Molina
class Recibirpruebastransalegatos_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    function getDatax($reg,$search) {
        //$this->db->select('*');
        $this->db->select('AJ.NUM_AUTOGENERADO,EM.CODEMPRESA,EM.NOMBRE_EMPRESA,AJ.FECHA_CREACION_AUTO,LQ.SALDO_DEUDA,LQ.TOTAL_CAPITAL,EA.NOMBRE_ESTADO');
        $this->db->from('AUTOSJURIDICOS AJ');
        $this->db->join('LIQUIDACION LQ','LQ.COD_FISCALIZACION = AJ.COD_FISCALIZACION');
        $this->db->join('EMPRESA EM', 'EM.CODEMPRESA = LQ.NITEMPRESA');
        $this->db->join('ESTADOAUTO EA', 'EA.COD_ESTADOAUTO = AJ.COD_ESTADOAUTO');
        $this->db->limit(10,$reg);
        // ESTADOAUTO = 3 // APROBADOS
        $this->db->like('AJ.NUM_AUTOGENERADO',$search);
        $this->db->or_like('AJ.FECHA_CREACION_AUTO', $search);
        $this->db->or_like('EM.CODEMPRESA', $search);
        $this->db->or_like('EM.NOMBRE_EMPRESA', $search);
        $this->db->or_like('LQ.SALDO_DEUDA', $search);
        $this->db->or_like('LQ.TOTAL_CAPITAL', $search);
        $this->db->or_like('EA.NOMBRE_ESTADO', $search);
        
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            return $query->result();
        }   
    }
    
    function totalData($search){
        $this->db->select('AJ.NUM_AUTOGENERADO,EM.CODEMPRESA,EM.NOMBRE_EMPRESA,AJ.FECHA_CREACION_AUTO,LQ.SALDO_DEUDA,LQ.TOTAL_CAPITAL,EA.NOMBRE_ESTADO');
        $this->db->from('AUTOSJURIDICOS AJ');
        $this->db->join('LIQUIDACION LQ','LQ.COD_FISCALIZACION = AJ.COD_FISCALIZACION');
        $this->db->join('EMPRESA EM', 'EM.CODEMPRESA = LQ.NITEMPRESA');
        $this->db->join('ESTADOAUTO EA', 'EA.COD_ESTADOAUTO = AJ.COD_ESTADOAUTO');
        // ESTADOAUTO = 3 // APROBADOS
        $this->db->like('AJ.NUM_AUTOGENERADO',$search);
        $this->db->or_like('AJ.FECHA_CREACION_AUTO', $search);
        $this->db->or_like('EM.CODEMPRESA', $search);
        $this->db->or_like('EM.NOMBRE_EMPRESA', $search);
        $this->db->or_like('LQ.SALDO_DEUDA', $search);
        $this->db->or_like('LQ.TOTAL_CAPITAL', $search);
        $this->db->or_like('EA.NOMBRE_ESTADO', $search);
        $query = $this->db->get();
        if ($query->num_rows() == 0)
            return '0';
        return $query->num_rows();
    }
    
    
}