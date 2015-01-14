<?php
class Cnm_tipocartera_model extends CI_Model {

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
	$query = $this->db->query(" UPDATE ".$table." SET ELIMINADO='".$data['ELIMINADO']."' WHERE COD_TIPOCARTERA=".$data['COD_TIPOCARTERA']);
if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;   
	}
	
		function editar ($table,$data){
	$query = $this->db->query(" UPDATE ".$table." SET NOMBRE_CARTERA='".$data['NOMBRE_CARTERA']."' ,FECHA_EDICION=TO_DATE('".$data['FECHA_EDICION']."','dd/mm/yyyy') ,COD_ESTADO='".$data['COD_ESTADO']."' WHERE COD_TIPOCARTERA=".$data['COD_TIPOCARTERA']);
if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;   
	}
	
	
		function selectTipoCartera ($cod_cartera){
$this->db->select('COD_TIPOCARTERA, NOMBRE_CARTERA,COD_ESTADO');

        $this->db->where("COD_TIPOCARTERA", $cod_cartera);
		$dato = $this->db->get("TIPOCARTERA");
		 return $dato->result_array[0];

	}
	
			function verificarExistencia ($nombre_cartera, $cod_cartera){
			
			 $query = $this->db->query(" SELECT COUNT(COD_TIPOCARTERA) AS EXIST FROM TIPOCARTERA WHERE NOMBRE_CARTERA='".$nombre_cartera."' AND  COD_TIPOCARTERA<>".$cod_cartera);
			//echo $this->db->last_query();
		return $query->result_array[0];
		//return $query->result();

	}
	
			function verificarExistenciaNueva ($nombre_cartera){
			
			 $query = $this->db->query(" SELECT COUNT(COD_TIPOCARTERA) AS EXIST FROM TIPOCARTERA WHERE NOMBRE_CARTERA='".$nombre_cartera."' AND ELIMINADO <> 1");
			//echo $this->db->last_query();
		return $query->result_array[0];
		//return $query->result();

	}	
	
	function countCartera ($cod_cartera){
			
		$query = $this->db->query(" SELECT COUNT(COD_TIPOCARTERA) AS EXIST FROM CNM_CARTERANOMISIONAL WHERE COD_TIPOCARTERA='".$cod_cartera."'");
		return $query->result_array[0];
		

	}	
	
       function getSelectTipoCartera($table,$fields){
        $query = $this->db->query(" SELECT ".$fields."  FROM ".$table." WHERE ELIMINADO IS NULL");
        return $query->result();
    }
	
    function getSequence($table,$name){
        $query = $this->db->query("SELECT ".$name."  FROM ".$table." ");
        $row = $query->row_array();
        return $row['NEXTVAL']-1;
        
    }
}