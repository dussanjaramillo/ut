<?php
class Cnm_tipo_tasa_interes_historica_model extends CI_Model {

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
                     $this->db->set($keyf,"TO_DATE('".$valuef."','dd/mm/yyyy')",false);
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
	$query = $this->db->query(" UPDATE ".$table." SET ELIMINADO='".$data['ELIMINADO']."' WHERE COD_TIPO_TASA_HIST='".$data['COD_TIPO_TASA_HIST']."'");
if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;   
	}
	
		function editar ($table,$data){
	$query = $this->db->query(" UPDATE ".$table." SET NOMBRE_CARTERA='".$data['NOMBRE_CARTERA']."' ,COD_ESTADO='".$data['COD_ESTADO']."' WHERE COD_TIPOCARTERA=".$data['COD_TIPOCARTERA']);
if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;   
	}
	
	
				function selectTipoTasa (){
$this->db->select('TIPOTASAINTERES.COD_TIPO_TASAINTERES, TIPOTASAINTERES.NOMBRE_TASAINTERES');
        $this->db->where("ELIMINADO IS NULL", NULL, FALSE); 
		$dato = $this->db->get("TIPOTASAINTERES");
		 return $dato;
	}
	
	
	
	
			function selectTipoTasaInteres (){
		$this->db->select('TIPO_TASA_HISTORICA.NOMBRE_TIPOTASA, TIPOTASAINTERES.NOMBRE_TASAINTERES, TIPO_TASA_HISTORICA.COD_TIPO_TASA_HIST');
		$this->db->join('TIPOTASAINTERES', 'TIPO_TASA_HISTORICA.COD_TIPO_TASAINTERES=TIPOTASAINTERES.COD_TIPO_TASAINTERES');
		$this->db->where("TIPO_TASA_HISTORICA.ELIMINADO IS NULL", NULL, FALSE); 
		$dato = $this->db->get("TIPO_TASA_HISTORICA");
		 return $dato;

	}
	
			function selectTipoTasaEd ($cod_tasa){
		$this->db->select('TIPOTASAINTERES.COD_TIPO_TASAINTERES, TIPOTASAINTERES.NOMBRE_TASAINTERES, TIPOTASAINTERES.COD_TIPOTASAINTERES, FORMAPAGOINTERESES.NOMBRE_FORMAPAGO,
						TIPOPERIODOTASA.NOMBRE_TIPOPERIODO');
					
		$this->db->join('FORMAPAGOINTERESES', 'TIPOTASAINTERES.COD_FORMAPAGO_INTERESES=FORMAPAGOINTERESES.COD_FORMA_PAGO_INTERESES');
		$this->db->join('TIPOPERIODOTASA', 'TIPOTASAINTERES.COD_TIPOPERIODO=TIPOPERIODOTASA.COD_TIPOPERIODOTASA');
		$this->db->where("ELIMINADO IS NULL", NULL, FALSE); 
        $this->db->where("COD_TIPO_TASAINTERES", $cod_tasa);
		$dato = $this->db->get("TIPOTASAINTERES");
		 return $dato->result_array[0];

	}
	
	
			function verificarExistencia ($nombre_cartera, $cod_cartera){
			
			 $query = $this->db->query(" SELECT COUNT(COD_TIPOCARTERA) AS EXIST FROM TIPOCARTERA WHERE NOMBRE_CARTERA='".$nombre_cartera."' AND  COD_TIPOCARTERA<>".$cod_cartera);
			//echo $this->db->last_query();
		return $query->result_array[0];
		//return $query->result();

	}
	
			function verificarExistenciaNueva ($nombre_cartera){
			
			 $query = $this->db->query(" SELECT COUNT(COD_TIPO_TASA_HIST) AS EXIST FROM TIPO_TASA_HISTORICA WHERE NOMBRE_TIPOTASA='".$nombre_cartera."' AND ELIMINADO IS NULL");
			//echo $this->db->last_query();
		return $query->result_array[0];
		//return $query->result();

	}	
	
       function getSelectTipoTasa($table,$fields){
        $query = $this->db->query(" SELECT ".$fields."  FROM ".$table." WHERE ELIMINADO IS NULL");
        return $query->result();
    }
	
    function getSequence($table,$name){
        $query = $this->db->query("SELECT ".$name."  FROM ".$table." ");
        $row = $query->row_array();
        return $row['NEXTVAL']-1;
        
    }
}