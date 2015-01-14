<?php
class Cnm_rango_tipo_tasa_interes_historica_model extends CI_Model {

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
	 function addrango($table,$data,$date=''){
			$this->db->set('COD_TIPO_TASA_HIST', $data['COD_TIPO_TASA_HIST']);
            $this->db->set('VALOR_TASA', $data['VALOR_TASA']);
			$this->db->set('FECHA_INI_VIGENCIA', "to_date('" . $date['FECHA_INI_VIGENCIA'] . "','dd/mm/yyyy HH24:MI:SS')", false);
			$this->db->set('FECHA_FIN_VIGENCIA', "to_date('" . $date['FECHA_FIN_VIGENCIA'] . "','dd/mm/yyyy HH24:MI:SS')", false);
            $this->db->insert($table);
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
	$query = $this->db->query(" UPDATE ".$table." SET ELIMINADO='".$data['ELIMINADO']."' WHERE COD_RANGOTASA='".$data['COD_RANGOTASA']."'");
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
	
	
	
	
			function selectRangoTasaInteres ($cod_tasa_hist){
		$this->db->select('RANGOSTASAHISTORICA.COD_TIPO_TASA_HIST, RANGOSTASAHISTORICA.VALOR_TASA, RANGOSTASAHISTORICA.COD_RANGOTASA, TIPO_TASA_HISTORICA.NOMBRE_TIPOTASA');
		$this->db->join('TIPO_TASA_HISTORICA', 'RANGOSTASAHISTORICA.COD_TIPO_TASA_HIST=TIPO_TASA_HISTORICA.COD_TIPO_TASA_HIST AND RANGOSTASAHISTORICA.COD_TIPO_TASA_HIST='.$cod_tasa_hist);
		$this->db->select("to_char(RANGOSTASAHISTORICA.FECHA_INI_VIGENCIA,'dd/mm/yyyy') AS FECHA_INI",FALSE);
		$this->db->select("to_char(RANGOSTASAHISTORICA.FECHA_FIN_VIGENCIA,'dd/mm/yyyy') AS FECHA_FIN",FALSE);
		$this->db->where("RANGOSTASAHISTORICA.ELIMINADO IS NULL", NULL, FALSE); 
		$dato = $this->db->get("RANGOSTASAHISTORICA");
		 return $dato;


	}




			function selectInformacion ($cod_tasa_hist){
		$this->db->select('TIPO_TASA_HISTORICA.NOMBRE_TIPOTASA, TIPOTASAINTERES.NOMBRE_TASAINTERES');
		$this->db->join('TIPOTASAINTERES', 'TIPO_TASA_HISTORICA.COD_TIPO_TASAINTERES=TIPOTASAINTERES.COD_TIPO_TASAINTERES');
		$this->db->where("TIPO_TASA_HISTORICA.COD_TIPO_TASA_HIST", $cod_tasa_hist); 		
		$this->db->where("TIPO_TASA_HISTORICA.ELIMINADO IS NULL", NULL, FALSE); 
		$dato = $this->db->get("TIPO_TASA_HISTORICA");
				 return $dato->result_array[0];
	}
	
			function selectTipoTasaEd ($cod_tasa){
		$this->db->select('TIPOTASAINTERES.COD_TIPO_TASAINTERES, TIPOTASAINTERES.NOMBRE_TASAINTERES, TIPOTASAINTERES.COD_TIPOTASAINTERES, FORMAPAGOINTERESES.NOMBRE_FORMAPAGO,
						TIPOPERIODOTASA.NOMBRE_TIPOPERIODO, PERIODOLIQUIDACION.NOMBRE_PERIODOLIQUIDACION');
					
		$this->db->join('FORMAPAGOINTERESES', 'TIPOTASAINTERES.COD_FORMAPAGO_INTERESES=FORMAPAGOINTERESES.COD_FORMA_PAGO_INTERESES');
		$this->db->join('TIPOPERIODOTASA', 'TIPOTASAINTERES.COD_TIPOPERIODO=TIPOPERIODOTASA.COD_TIPOPERIODOTASA');
		$this->db->join('PERIODOLIQUIDACION', 'TIPOTASAINTERES.COD_PER_LIQUIDACION=PERIODOLIQUIDACION.COD_PERIODOLIQUIDACION');
		$this->db->where("ELIMINADO IS NULL", NULL, FALSE); 
        $this->db->where("COD_TIPO_TASAINTERES", $cod_tasa);
		$dato = $this->db->get("TIPOTASAINTERES");
		 return $dato->result_array[0];

	}
	
	
			function verificarExistencia ($fecha_ini, $fecha_fin, $cod_tasa_hist){
			$inicio=str_replace("/", "-", $fecha_ini);
			$fin=str_replace("/", "-", $fecha_fin);

			$this->db->select('COUNT(RANGOSTASAHISTORICA.COD_RANGOTASA) AS EXIST');
			$this->db->where("RANGOSTASAHISTORICA.COD_TIPO_TASA_HIST", $cod_tasa_hist);
			$this->db->where("RANGOSTASAHISTORICA.ELIMINADO IS NULL", NULL, FALSE); 
			$this->db->where("(TO_DATE('".$inicio."', 'dd-mm-yyyy') BETWEEN RANGOSTASAHISTORICA.FECHA_INI_VIGENCIA AND RANGOSTASAHISTORICA.FECHA_FIN_VIGENCIA OR 
			TO_DATE( '".$fin."', 'dd-mm-yyyy') BETWEEN RANGOSTASAHISTORICA.FECHA_INI_VIGENCIA AND RANGOSTASAHISTORICA.FECHA_FIN_VIGENCIA)", NULL, FALSE);
			$dato = $this->db->get("RANGOSTASAHISTORICA");
		//echo $this->db->last_query();
			 return $dato->result_array[0];

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