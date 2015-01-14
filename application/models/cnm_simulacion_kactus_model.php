<?php
class Cnm_simulacion_kactus_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    
    function get($table,$fields,$where='',$perpage=0,$start=0,$one=false,$array='array'){
        
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->limit($perpage,$start);
        if($where){
        $this->db->where($where);
        }
        
        $query = $this->db->get();
        
        $result =  !$one  ? $query->result($array) : $query->row() ;
        return $result;
    }

    function getoption($table,$where,$id){

        $this->db->where($where,$id);
        $result=$this->db->get($table);
        return $result;
               
    }
	    function add($table,$data,$date=''){
        if ($date!='') {
           foreach ($data as $key => $value) {
                     $this->db->set($key, $value);
                 }
            foreach ($date as $keyf => $valuef) {
                     $this->db->set($keyf,"to_date('".$valuef."','dd/mm/yyyy')",false);
                 }         

            $this->db->insert($table);
        }else{
            $this->db->insert($table, $data);
        }
        if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
		
		return FALSE;       
    }

	function delete ($table,$data){
	//echo $data['COD_TIPO_TASA_HIST'];
	//die();
	$query = $this->db->query(" UPDATE ".$table." SET ELIMINADO='".$data['ELIMINADO']."' WHERE COD_RANGOTASA='".$data['COD_RANGOTASA']."'");
if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;   
	}
	
		function consultaCuotas ($mesproy){
	$query = $this->db->query("INSERT INTO CNM_CUOTAS_KACTUS(Id_Deuda_K, No_Cuota, Concepto, Valor_Pagado, Fecha_Pago, Cod_regional)
	SELECT ID_DEUDA_E, NO_CUOTA, CONCEPTO, SALDO_CUOTA, SYSDATE, '11' FROM CNM_CUOTAS WHERE MES_PROYECTADO='".$mesproy."' AND SALDO_CUOTA>0 AND CONCEPTO=8");
		
	if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;  
}
}