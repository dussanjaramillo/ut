<?php
class Cnm_ac_variables_model extends CI_Model {

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
	$query = $this->db->query(" UPDATE ".$table." SET ELIMINADO='".$data['ELIMINADO']."' WHERE COD_VARIABLE=".$data['COD_VARIABLE']);
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
	
			function selectVariables ($cod_componente){
$this->db->select('NM_VARIABLE.COD_VARIABLE, NM_VARIABLE_ESPECIFICA.NOMBRE_VARIABLE_ESP, NM_VARIABLE.MINIMO, NM_VARIABLE.MAXIMO');
$this->db->join('NM_VARIABLE_ESPECIFICA', 'NM_VARIABLE.COD_VARIABLE_ESP=NM_VARIABLE_ESPECIFICA.COD_VARIABLE_ESP');

 $this->db->where("NM_VARIABLE.COD_COMPONENTE", $cod_componente);
 $this->db->where("ELIMINADO IS NULL", NULL, FALSE); 
	$dato = $this->db->get("NM_VARIABLE");
		 return $dato;
	}
	
				function selectAcuerdoEsp ($cod_acuerdo){
		$this->db->select('NOMBRE_ACUERDO, COD_TIPOCARTERA, TIPO_VINCULACION, TIPO_CUOTA, PORC_REFINANCIACION, PLAZO_MAX_REFINANC, FORMA_APLI_PAGO, MONTO_MAXIMO,
 PLAZO_MAXIMO, MANEJO_GRACIA, DURACION_GRACIA, INCREMENTO_CUOTA, PERIODO_INCREMENTO, ABONO_CESANTIAS, PORCE_ABONO_CESANT, ABONO_PRIMAS, PORCEN_ABONA_PRIMA');
		$this->db->select("to_char(FECHA_INI_VIGENCIA,'dd/mm/yyyy') AS FECHA_INICIO",FALSE); 
        $this->db->where("COD_ACUERDOLEY", $cod_acuerdo);

		$dato = $this->db->get("NM_ACUERDOLEY");
		 return $dato->result_array[0];
	}
	
				function selectInfoAcuerdo ($componente){
$this->db->select('NM_ACUERDOLEY.NOMBRE_ACUERDO, TIPOCARTERA.COD_TIPOCARTERA, TIPOCARTERA.NOMBRE_CARTERA');
$this->db->join('TIPOCARTERA', 'NM_ACUERDOLEY.COD_TIPOCARTERA=TIPOCARTERA.COD_TIPOCARTERA');
        $this->db->where("NM_ACUERDOLEY.COD_ACUERDOLEY", $componente);
		$dato = $this->db->get("NM_ACUERDOLEY");
		 return $dato->result_array[0];
	}
	
					function selectVarEsp (){
$this->db->select('COD_VARIABLE_ESP, NOMBRE_VARIABLE_ESP');
		$dato = $this->db->get("NM_VARIABLE_ESPECIFICA");
		 return $dato;
	}
	
					function selectAplicaa (){
$this->db->select('COD_APLICA_COMPONENTE, NOMBRE_APLICA_COMP');
		$dato = $this->db->get("NM_APLICA_COMPONENTE");
		 return $dato;
	}	
	
						function select_f_interes (){
$this->db->select('COD_FORMULA_INT, NOMBRE_FORMULA_INT');
		$dato = $this->db->get("NM_FORMULA_INTERES");
		 return $dato;
		}
		
		function select_tipo_tasa (){
$this->db->select('COD_TIPO_TASAINTERES, NOMBRE_TASAINTERES');
$this->db->where("ELIMINADO IS NULL", NULL, FALSE); 
		$dato = $this->db->get("TIPOTASAINTERES");
		 return $dato;
		}
		
				function select_tipo_tasa_h (){
$this->db->select('COD_TIPO_TASA_HIST, NOMBRE_TIPOTASA');
$this->db->where("ELIMINADO IS NULL", NULL, FALSE); 
		$dato = $this->db->get("TIPO_TASA_HISTORICA");
		 return $dato;
		}
		
	
		 						function select_calculo (){
$this->db->select('COD_CALCULO_COMPONENTE, NOMBRE_CALCULO_COMP');
		$dato = $this->db->get("NM_CALCULO_COMPONENTE");
		 return $dato;
	}	
			function selectAcuerdoLey(){
		$this->db->select('NM_ACUERDOLEY.COD_ACUERDOLEY, NM_ACUERDOLEY.NOMBRE_ACUERDO, TIPOCARTERA.NOMBRE_CARTERA');
		$this->db->select("to_char(NM_ACUERDOLEY.FECHA_INI_VIGENCIA,'dd/mm/yyyy') AS FECHA_INICIO",FALSE);

		$this->db->join('TIPOCARTERA', 'NM_ACUERDOLEY.COD_TIPOCARTERA=TIPOCARTERA.COD_TIPOCARTERA');
		//$this->db->where("ELIMINADO IS NULL", NULL, FALSE); 
		$dato = $this->db->get("NM_ACUERDOLEY");
		 return $dato;

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
	
	
			function verificarExistencia ($nombre_cartera, $cod_cartera){
			
			 $query = $this->db->query(" SELECT COUNT(COD_TIPOCARTERA) AS EXIST FROM TIPOCARTERA WHERE NOMBRE_CARTERA='".$nombre_cartera."' AND  COD_TIPOCARTERA<>".$cod_cartera);
			//echo $this->db->last_query();
		return $query->result_array[0];
		//return $query->result();

	}
	
			function verificarExistenciaNueva ($cod_Comp, $var_id){

			 $query = $this->db->query(" SELECT COUNT(COD_VARIABLE) AS EXIST FROM NM_VARIABLE WHERE COD_COMPONENTE='".$cod_Comp."' AND COD_VARIABLE_ESP='".$var_id."' AND ELIMINADO IS NULL");
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