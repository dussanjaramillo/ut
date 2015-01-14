<?php
class Cnm_seguros_model extends CI_Model {

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
	
							function selectSeguro(){
$this->db->select('COD_TIPOCARTERA, NOMBRE_CARTERA');
$this->db->where_in('COD_TIPOCARTERA', array(12,13));
		$dato = $this->db->get("TIPOCARTERA");
		//echo $this->db->last_query();
		 return $dato;
	}
	
				function verificarExistenciaNueva ($tipo_cartera_id, $fecha_ini){
			$inicio=str_replace("/", "-", $fecha_ini);
			 $query = $this->db->query(" SELECT COUNT(COD_TASA) AS EXIST FROM TASAS_SEGUROS WHERE CONCEPTO='".$tipo_cartera_id."' AND FECHA_INI_VIGENCIA>=TO_DATE('".$fecha_ini."','".'dd/mm/yyyy'."') AND FECHA_FIN_VIGENCIA IS NULL");
			//echo $this->db->last_query();
		return $query->result_array[0];
		//return $query->result();

	}
	
	
	function selectDetalleSeguro($id_concepto){
$this->db->select('ID_ASEGURADORA, ASEGURADORA, VALOR_TASA, TIPOCARTERA.NOMBRE_CARTERA, TASAS_SEGUROS.CONCEPTO');
$this->db->select("to_char(FECHA_INI_VIGENCIA,'dd/mm/yyyy') AS FECHA_INI_VIGENCIA",FALSE);
$this->db->select("to_char(FECHA_FIN_VIGENCIA,'dd/mm/yyyy') AS FECHA_FIN_VIGENCIA",FALSE);
$this->db->join('TIPOCARTERA', "TIPOCARTERA.COD_TIPOCARTERA=TASAS_SEGUROS.CONCEPTO");
$this->db->where('CONCEPTO', $id_concepto);
		$dato = $this->db->get("TASAS_SEGUROS");
		//echo $this->db->last_query();
		 return $dato;
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
	
		    function update($table,$data,$date){

	$query = $this->db->query(" UPDATE ".$table." SET ELIMINADO='".$data['ELIMINADO']."' WHERE COD_TIPO_TASAINTERES=".$data['COD_TIPO_TASAINTERES']);
if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;       
    }

	function delete ($table,$data){
	$query = $this->db->query(" UPDATE ".$table." SET ELIMINADO='".$data['ELIMINADO']."' WHERE COD_COMPONENTE=".$data['COD_COMPONENTE']);
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
	
			function selectComponentes ($cod_acuerdo){
$this->db->select('NM_COMPONENTE.COD_COMPONENTE, NM_COMPONENTE_ESPECIFICO.NOMBRE_COMP_ESP, NM_COMPONENTE.TIPO_TASA, NM_COMPONENTE.VALOR, NM_COMPONENTE.PUNTOS_ADICIONALES,  NM_COMPONENTE.CALCULO, NM_COMPONENTE.PLAZO_MINIMO, 
NM_COMPONENTE.PLAZO_MAXIMO, NM_APLICA_COMPONENTE.NOMBRE_APLICA_COMP AS APLICAR_A, NM_CALCULO_COMPONENTE.NOMBRE_CALCULO_COMP, NM_FORMULA_INTERES.NOMBRE_FORMULA_INT');
$this->db->join('NM_COMPONENTE_ESPECIFICO', 'NM_COMPONENTE.COD_COMPONENTE_ESP=NM_COMPONENTE_ESPECIFICO.COD_COMPONENTE_ESP');
$this->db->join('NM_APLICA_COMPONENTE', 'NM_COMPONENTE.APLICAR_A=NM_APLICA_COMPONENTE.COD_APLICA_COMPONENTE');
$this->db->join('NM_CALCULO_COMPONENTE', 'NM_COMPONENTE.CALCULO=NM_CALCULO_COMPONENTE.COD_CALCULO_COMPONENTE');
$this->db->join('NM_FORMULA_INTERES', 'NM_COMPONENTE.APLICAR_A=NM_FORMULA_INTERES.COD_FORMULA_INT');
 $this->db->where("NM_COMPONENTE.COD_ACUERDOLEY", $cod_acuerdo);
 $this->db->where("ELIMINADO IS NULL", NULL, FALSE); 
	$dato = $this->db->get("NM_COMPONENTE");
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
	
					function selectComponenteEsp (){
$this->db->select('COD_COMPONENTE_ESP, NOMBRE_COMP_ESP');
 $this->db->where("COD_COMPONENTE_ESP >", '1');
		$dato = $this->db->get("NM_COMPONENTE_ESPECIFICO");
		 return $dato;
	}
	
					function selectAplicaa (){
$this->db->select('COD_APLICA_COMPONENTE, NOMBRE_APLICA_COMP');
		$dato = $this->db->get("NM_APLICA_COMPONENTE");
		 return $dato;
	}	
	
						function select_f_interes (){
$this->db->select('COD_FORMULA_INT, NOMBRE_FORMULA_INT');
 $this->db->where("COD_FORMULA_INT", '1');
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
	

       function getSelectTipoTasa($table,$fields){
        $query = $this->db->query(" SELECT ".$fields."  FROM ".$table." WHERE ELIMINADO IS NULL");
        return $query->result();
    }
	
    function getSequence($table,$name){
        $query = $this->db->query("SELECT ".$name."  FROM ".$table." ");
        $row = $query->row_array();
        return $row['NEXTVAL']-1;
        
    }
	
	
						    function updateSeguro($table,$date,$id_concepto){
			
				$query = $this->db->query(" UPDATE ".$table."  SET FECHA_FIN_VIGENCIA=".'to_date(\''.$date.'\',\'dd-mm-yyyy\')-1'." WHERE CONCEPTO=".$id_concepto." AND FECHA_FIN_VIGENCIA IS NULL");
			if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;   
							
                    }
	   function seleccionarDeudasActivas(){
        $query = $this->db->query(" SELECT COD_CARTERA_NOMISIONAL FROM CNM_CARTERANOMISIONAL WHERE COD_TIPOCARTERA=8 AND COD_ESTADO=2 ");
        return $query;
    }
	
		   function borrarCuotasSeg($concepto){
        $query = $this->db->query(" DELETE FROM CNM_CUOTAS 
		WHERE CNM_CUOTAS.CONCEPTO='".$concepto."' AND VALOR_CUOTA=SALDO_CUOTA ");
        return $query;
    }
	
	
}