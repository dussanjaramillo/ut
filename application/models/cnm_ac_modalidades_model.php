<?php
class Cnm_ac_modalidades_model extends CI_Model {

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
                     $this->db->set($keyf,"to_date('".$valuef."','dd/mm/yyyy hh24:mi')",false);
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
	
		    function update($table,$data,$date){

	$query = $this->db->query(" UPDATE ".$table." SET ELIMINADO='".$data['ELIMINADO']."' WHERE COD_TIPO_TASAINTERES=".$data['COD_TIPO_TASAINTERES']);
if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;       
    }

	function delete ($table,$data){
	$query = $this->db->query(" UPDATE ".$table." SET ELIMINADO='".$data['ELIMINADO']."' WHERE COD_MODALIDAD=".$data['COD_MODALIDAD']);
if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;   
	}
	
	
			function selectModalidades ($cod_acuerdo){
$this->db->select('CNM_MODALIDADES.COD_MODALIDAD, CNM_MODALIDADES.NOMBRE_MODALIDAD');
 $this->db->where("CNM_MODALIDADES.COD_ACUERDO", $cod_acuerdo);
 $this->db->where("ELIMINADO IS NULL", NULL, FALSE); 
	$dato = $this->db->get("CNM_MODALIDADES");
		 return $dato;
	}
	
				
			function verificarExistenciaNueva ($nombre, $cod_acuerdo){

			 $query = $this->db->query(" SELECT COUNT(COD_MODALIDAD) AS EXIST FROM CNM_MODALIDADES WHERE COD_ACUERDO='".$cod_acuerdo."' AND NOMBRE_MODALIDAD='".$nombre."' AND ELIMINADO IS NULL");

			//echo $this->db->last_query();
		return $query->result_array[0];
		//return $query->result();

	}		
		
    
	
    function getSequence($table,$name){
        $query = $this->db->query("SELECT ".$name."  FROM ".$table." ");
        $row = $query->row_array();
        return $row['NEXTVAL']-1;
        
    }
}