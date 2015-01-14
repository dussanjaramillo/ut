<?php

class Cronacuerdopago_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

//---------------------------------------------------------------------------------  
// Cancelacion acuerdo de Pago
//---------------------------------------------------------------------------------      
    function incumplimientocuotainicial() {

        $fechainicial = date('d-m-Y');

        $this->db->select('NRO_ACUERDOPAGO');
        $this->db->where('PROYACUPAG_NUMCUOTA', 0);
        $this->db->where('PROYACUPAG_ESTADO', 0);
        $this->db->where('PROYACUPAG_FECHALIMPAGO < ', "TO_DATE('" . $fechainicial . "','DD/MM/RR')", false);
        $incumplimiento = $this->db->get('PROYECCIONACUERDOPAGO');
        return $incumplimiento->result_array();
    }

    function actualizaincumplimiento($data) {
//      se cancela el acuerdo de pago 
        $cancelado = array('CANCELADO' => 1);
        $this->db->where($data);
        $this->db->update('ACUERDOPAGO', $cancelado);
    }

//---------------------------------------------------------------------------------    
//    Acuerdos ya pagos totalmente
//---------------------------------------------------------------------------------      

    function acuerdopagototal() {
//        1 CANCELADO
//        2 PAGO TOTAL 

        $datos = $this->db->query('SELECT NRO_ACUERDOPAGO FROM ACUERDOPAGO WHERE CANCELADO IS NULL');
        $contador = count($datos->result_array());

        if ($contador > 0) {
            $this->db->distinct('PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO');
            $this->db->select('PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO');
            $this->db->join('ACUERDOPAGO', 'ACUERDOPAGO.NRO_ACUERDOPAGO = PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO ');
            $this->db->where("ACUERDOPAGO.NRO_ACUERDOPAGO  IN (SELECT NRO_ACUERDOPAGO FROM ACUERDOPAGO WHERE CANCELADO IS NULL)");
            $this->db->where('PROYECCIONACUERDOPAGO.PROYACUPAG_ESTADO', 1);
            $this->db->where('PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO NOT IN (SELECT DISTINCT NRO_ACUERDOPAGO FROM PROYECCIONACUERDOPAGO WHERE PROYACUPAG_ESTADO IS NULL)');
            $this->db->order_by('PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO');
            $acuerdo = $this->db->get('PROYECCIONACUERDOPAGO');

            return $acuerdo->result_array();
        } else {
            return "";
        }
    }

    function actualizaacuerdospagototal($acuerdo) {

        $data = array('CANCELADO' => 2);
        $this->db->where('NRO_ACUERDOPAGO', $acuerdo);
        $this->db->update('ACUERDOPAGO', $data);
    }

    function terminacionmc() {
//------------------------------------------------------------------------------
//**************************Terminacion para medidas cautelares*****************        
//------------------------------------------------------------------------------        
//* Se consulta despues de que se ejecute el acuerdo 60 días y que no tengan recurso
        
        $this->db->select('LIQUIDACION.COD_FISCALIZACION,LIQUIDACION.NITEMPRESA');
        $this->db->join('LIQUIDACION','LIQUIDACION.NUM_LIQUIDACION = ACUERDOPAGO.NRO_LIQUIDACION');
        $this->db->where('ATRASOACUERDO', 2);
        $this->db->where('JURIDICO',2);
        $datos = $this->db->get('ACUERDOPAGO');
        
        return $datos->result_array();
    }
// ----------------------------------------------------------------------------------------
    function enviamc($mc){
        $this->db->insert_batch('MC_MEDIDASCAUTELARES',$mc);
    }
    
}
