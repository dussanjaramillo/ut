<?php
class Cnm_tipo_tasa_interes_model extends CI_Model {

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
	$query = $this->db->query(" UPDATE ".$table." SET ELIMINADO='".$data['ELIMINADO']."' WHERE COD_TIPO_TASAINTERES=".$data['COD_TIPO_TASAINTERES']);
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
	
	
		function selectFormaPagoInt ($cod_cartera){
$this->db->select('COD_TIPOCARTERA, NOMBRE_CARTERA,COD_ESTADO');

        $this->db->where("COD_TIPOCARTERA", $cod_cartera);
		$dato = $this->db->get("TIPOCARTERA");
		 return $dato->result_array[0];

	}
	
			function selectFormaPago (){
$this->db->select('COD_FORMA_PAGO_INTERESES, NOMBRE_FORMAPAGO');
        $this->db->where("COD_ESTADO", '1');
		$dato = $this->db->get("FORMAPAGOINTERESES");
		 return $dato;
	}
	
				function selectPeriodoLiq (){
$this->db->select('COD_PERIODOLIQUIDACION, NOMBRE_PERIODOLIQUIDACION');
        $this->db->where("COD_ESTADO", '1');
		$dato = $this->db->get("PERIODOLIQUIDACION");
		 return $dato;
	}
	
					function selectTipoPeriodo (){
$this->db->select('COD_TIPOPERIODOTASA, NOMBRE_TIPOPERIODO');
        $this->db->where("COD_ESTADO", '1');
		$this->db->order_by('COD_TIPOPERIODOTASA', 'ASC');
		$dato = $this->db->get("TIPOPERIODOTASA");
		 return $dato;
	}
	
	
	
	
			function selectTipoTasaInteres (){
		$this->db->select('TIPOTASAINTERES.COD_TIPO_TASAINTERES, TIPOTASAINTERES.NOMBRE_TASAINTERES, TIPOTASAINTERES.COD_TIPOTASAINTERES, FORMAPAGOINTERESES.NOMBRE_FORMAPAGO,
						TIPOPERIODOTASA.NOMBRE_TIPOPERIODO');
		$this->db->select("to_char(TIPOTASAINTERES.FECHA_CREACION,'dd/mm/yyyy') AS FECHA_CREACION",FALSE);				
		$this->db->join('FORMAPAGOINTERESES', 'TIPOTASAINTERES.COD_FORMAPAGO_INTERESES=FORMAPAGOINTERESES.COD_FORMA_PAGO_INTERESES');
		$this->db->join('TIPOPERIODOTASA', 'TIPOTASAINTERES.COD_TIPOPERIODO=TIPOPERIODOTASA.COD_TIPOPERIODOTASA');
		$this->db->where("ELIMINADO IS NULL", NULL, FALSE); 
		$dato = $this->db->get("TIPOTASAINTERES");
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
			
			 $query = $this->db->query(" SELECT COUNT(COD_TIPO_TASAINTERES) AS EXIST FROM TIPOTASAINTERES WHERE NOMBRE_TASAINTERES='".$nombre_cartera."' AND ELIMINADO IS NULL");
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