<?php
class Cnm_convenio_model extends CI_Model {

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

    function addbanco($table,$data){

        $this->db->set('IDBANCO', $data['IDBANCO']);
        $this->db->set('NOMBREBANCO', $data['IDBANCO']);
        $this->db->set('FECHACREACION',"to_date('".$data['FECHACREACION']."','dd/mm/yyyy')",false);
        $this->db->set('IDESTADO', $data['IDESTADO']);
        $this->db->insert($table);
        if ($this->db->affected_rows() == '1')
        {
            return TRUE;
        }
        
        return FALSE;
    }
	
	  function add($table,$data,$date=''){
        if ($date!='') {
           foreach ($data as $key => $value) {
                     $this->db->set($key, $value);
                 }
            foreach ($date as $keyf => $valuef) {
                     $this->db->set($keyf,"to_date('".$valuef."','dd/mm/yyyy hh24:mi:ss')",false);
                 }         

            $this->db->insert($table);
        }
		
		else{
            $this->db->insert($table, $data);
        }
        if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
		
		return FALSE;       
    }
	
		  function getLastInserted($table, $id) {
	$this->db->select_max($id);
	$Q = $this->db->get($table);
	$row = $Q->row_array();
	return $row[$id];
 }
	
				function tipoCartera ($id_cartera){
$this->db->select('COD_TIPOCARTERA, NOMBRE_CARTERA');
        $this->db->where("COD_ESTADO", '1');
		$this->db->where("COD_TIPOCARTERA", $id_cartera);
		$this->db->where("ELIMINADO IS NULL", NULL, FALSE);
		$dato = $this->db->get("TIPOCARTERA");
		 return $dato->result_array[0];
	}
    
				function selectTipoEstado(){
$this->db->select('COD_EST_CARTERA, DESC_EST_CARTERA');
$this->db->where("ESTADOCARTERA.CREACION", 'S');
		$dato = $this->db->get("ESTADOCARTERA");
		 return $dato;
	}
	
					function selectFormaPago(){
$this->db->select('COD_FORMAPAGO, FORMA_PAGO');
		$dato = $this->db->get("CNM_FORMAPAGO");
		 return $dato;
	}
	
					function selectPlazo(){
$this->db->select('COD_DURACION_GRACIA, NOMBRE_DURACCION');
		$dato = $this->db->get("CNM_DURACION_GRACIA");
		 return $dato;
	}
	
	function selectTipoTasaCombo(){
$this->db->select('COD_CALCULO_COMPONENTE, NOMBRE_CALCULO_COMP');
		$dato = $this->db->get("NM_CALCULO_COMPONENTE");
		 return $dato;
	}
	
	function selectTasaEspCombo(){

$this->db->select('COD_TIPO_TASAINTERES AS COD_TASA, NOMBRE_TASAINTERES AS NOMBRE_TASA');
$this->db->where("TIPOTASAINTERES.ELIMINADO IS NULL", NULL, FALSE); 
		$dato = $this->db->get("TIPOTASAINTERES");
		
		return $dato;
	}
	
	
	       function getSelectTipoCartera($table,$fields){
        $query = $this->db->query(" SELECT ".$fields."  FROM ".$table." WHERE COD_ESTADO='1' AND TIPO='CREADA' AND COD_TIPOCARTERA='9'");
        return $query->result();
    }

		function detalleCarteras ($nit, $id_cartera, $estado, $id_deuda){
$this->db->select('CNM_EMPRESA.COD_ENTIDAD AS IDENTIFICACION, CNM_EMPRESA.RAZON_SOCIAL AS NOMBRES, CNM_EMPRESA.DIRECCION, CNM_EMPRESA.TELEFONO,
CNM_EMPRESA.CORREO_ELECTRONICO, REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA, CNM_CARTERANOMISIONAL.COD_TIPOCARTERA,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_CARTERANOMISIONAL.COD_ESTADO, CNM_CARTERA_CONVENIOS.ADJUNTOS');
if(!empty($nit)){
$this->db->join('CNM_EMPRESA', "CNM_CARTERANOMISIONAL.COD_EMPRESA=CNM_EMPRESA.COD_ENTIDAD AND CNM_CARTERANOMISIONAL.COD_EMPRESA=".$nit);
}
else{
$this->db->join('CNM_EMPRESA', "CNM_CARTERANOMISIONAL.COD_EMPRESA=CNM_EMPRESA.COD_ENTIDAD");
}

$this->db->join('REGIONAL', "CNM_EMPRESA.COD_REGIONAL=REGIONAL.COD_REGIONAL");
$this->db->join('ESTADOCARTERA', "CNM_CARTERANOMISIONAL.COD_ESTADO=ESTADOCARTERA.COD_EST_CARTERA AND CNM_CARTERANOMISIONAL.COD_ESTADO=".$estado);
$this->db->join('CNM_CARTERA_CONVENIOS', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_CONVENIOS.COD_CARTERA");
$this->db->where("CNM_CARTERANOMISIONAL.COD_TIPOCARTERA", $id_cartera);
if(!empty($id_deuda)){
$this->db->where("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", $id_deuda);
}
		$dato = $this->db->get("CNM_CARTERANOMISIONAL");
		 return $dato;
	}
	
					function detalleCarterasEdit ($id_cartera){
$this->db->select('CNM_EMPRESA.COD_ENTIDAD AS IDENTIFICACION, CNM_EMPRESA.RAZON_SOCIAL AS NOMBRES, CNM_EMPRESA.SIGLA, CNM_EMPRESA.DIRECCION, CNM_EMPRESA.TELEFONO,
CNM_EMPRESA.CORREO_ELECTRONICO, REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA, CNM_CARTERANOMISIONAL.COD_ESTADO, CNM_CARTERA_CONVENIOS.NUMERO_CONVENIO, CNM_CARTERA_CONVENIOS.NUMERO_ACTA_LIQ,
CNM_CARTERA_CONVENIOS.VALOR_TOTAL_CONVENIO, CNM_CARTERA_CONVENIOS.APORTE_SENA, CNM_CARTERA_CONVENIOS.VALOR_RENDIMIENTOS, CNM_CARTERA_CONVENIOS.VALOR_REINTEGRO,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_CARTERANOMISIONAL.COD_TIPOCARTERA, CNM_CARTERANOMISIONAL.VALOR_DEUDA, CNM_CARTERANOMISIONAL.SALDO_DEUDA, 
CNM_CARTERANOMISIONAL.PLAZO_CUOTAS, CNM_CARTERANOMISIONAL.COD_PLAZO, CNM_CARTERANOMISIONAL.COD_FORMAPAGO, CNM_CARTERA_CONVENIOS.ADJUNTOS, CNM_CARTERA_CONVENIOS.TERMINO_CONVENIO,
CNM_CARTERA_CONVENIOS.MOD_TASA_CORRIENTE, CNM_CARTERA_CONVENIOS.MOD_TASA_MORA,
CNM_CARTERANOMISIONAL.CALCULO_CORRIENTE, CNM_CARTERANOMISIONAL.TIPO_T_F_CORRIENTE, CNM_CARTERANOMISIONAL.TIPO_T_V_CORRIENTE, CNM_CARTERANOMISIONAL.VALOR_T_CORRIENTE, 
CNM_CARTERANOMISIONAL.CALCULO_MORA, CNM_CARTERANOMISIONAL.TIPO_T_F_MORA, CNM_CARTERANOMISIONAL.TIPO_T_V_MORA, CNM_CARTERANOMISIONAL.VALOR_T_MORA,');
$this->db->select("to_char(CNM_CARTERA_CONVENIOS.FECHA_ACTIVACION,'dd/mm/yyyy') AS FECHA_ACTIVACION",FALSE);
$this->db->select("to_char(CNM_CARTERA_CONVENIOS.FECHA_SUSCRIPCION,'dd/mm/yyyy') AS FECHA_SUSCRIPCION",FALSE);
$this->db->select("to_char(CNM_CARTERA_CONVENIOS.FECHA_ACTA_LIQ,'dd/mm/yyyy') AS FECHA_ACTA_LIQ",FALSE);
$this->db->select("to_char(CNM_CARTERA_CONVENIOS.FECHA_MAX_PAGO,'dd/mm/yyyy') AS FECHA_MAX_PAGO",FALSE);
$this->db->join('CNM_CARTERA_CONVENIOS', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_CONVENIOS.COD_CARTERA");
$this->db->join('CNM_EMPRESA', "CNM_CARTERANOMISIONAL.COD_EMPRESA=CNM_EMPRESA.COD_ENTIDAD");
$this->db->join('REGIONAL', "CNM_EMPRESA.COD_REGIONAL=REGIONAL.COD_REGIONAL");
$this->db->join('ESTADOCARTERA', "CNM_CARTERANOMISIONAL.COD_ESTADO=ESTADOCARTERA.COD_EST_CARTERA");
$this->db->where("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", $id_cartera);

		$dato = $this->db->get("CNM_CARTERANOMISIONAL");

		return $dato;
	}	
	
	
    function getSequence($table,$name){
        $query = $this->db->query("SELECT ".$name."  FROM ".$table." ");
        $row = $query->row_array();
        return $row['NEXTVAL']-1;
        
    }
	
			       function select_data_emp($nit){
        $query = $this->db->query(" SELECT CNM_EMPRESA.RAZON_SOCIAL, CNM_EMPRESA.SIGLA, CNM_EMPRESA.DIRECCION, CNM_EMPRESA.TELEFONO, CNM_EMPRESA.CORREO_ELECTRONICO, 
		REGIONAL.NOMBRE_REGIONAL  FROM CNM_EMPRESA 
		JOIN REGIONAL ON CNM_EMPRESA.COD_REGIONAL=REGIONAL.COD_REGIONAL
		WHERE CNM_EMPRESA.COD_ENTIDAD='".$nit."'");
        return $query->result_array[0]; 
    }
	
			  function set_nit($nit) {
    $this->nit = $nit;
  }
  
  
    function buscarnits() {
    $this->db->select('COD_ENTIDAD, RAZON_SOCIAL');
    if (!empty($this->nit)) {
      $this->db->like('COD_ENTIDAD', $this->nit);
    }
    $datos = $this->db->get('CNM_EMPRESA');
    $datos = $datos->result_array();
    if(!empty($datos)) :
      $tmp = NULL;
      foreach($datos as $nit) :
        $tmp[] = array("value" => $nit['COD_ENTIDAD'], "label" => $nit['COD_ENTIDAD']." :: ".$nit['RAZON_SOCIAL']);
      endforeach;
      $datos = $tmp;
    endif;
    return $datos;
  }
  
  		  function detalleprevioConvenio($cod_cartera) {
$this->db->select('CNM_CARTERA_CONVENIOS.MOD_TASA_CORRIENTE, CNM_CARTERA_CONVENIOS.MOD_TASA_MORA, CNM_CARTERANOMISIONAL.CALCULO_CORRIENTE, CNM_CARTERANOMISIONAL.CALCULO_MORA,
CNM_CARTERANOMISIONAL.COD_TIPO_ACUERDO');
$this->db->join('CNM_CARTERANOMISIONAL', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_CONVENIOS.COD_CARTERA AND CNM_CARTERA_CONVENIOS.COD_CARTERA=".$cod_cartera);
$dato = $this->db->get("CNM_CARTERA_CONVENIOS");

		 return $dato;
  }
  	
		  function detalleConvenio($cod_cartera, $mod_t_corriente, $mod_t_mora, $calc_corr, $calc_mora, $acuerdo) {

    $this->db->select('CNM_EMPRESA.COD_ENTIDAD, CNM_EMPRESA.RAZON_SOCIAL, CNM_EMPRESA.SIGLA, CNM_EMPRESA.DIRECCION, CNM_EMPRESA.TELEFONO, CNM_EMPRESA.CORREO_ELECTRONICO, CNM_EMPRESA.COD_REGIONAL, 
	REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA,CNM_CARTERA_CONVENIOS.NUMERO_CONVENIO, CNM_CARTERA_CONVENIOS.NUMERO_ACTA_LIQ,
	CNM_CARTERA_CONVENIOS.VALOR_TOTAL_CONVENIO, CNM_CARTERA_CONVENIOS.APORTE_SENA, CNM_CARTERA_CONVENIOS.VALOR_RENDIMIENTOS, CNM_CARTERA_CONVENIOS.VALOR_REINTEGRO,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, TIPOCARTERA.NOMBRE_CARTERA, CNM_CARTERANOMISIONAL.VALOR_DEUDA, CNM_CARTERANOMISIONAL.SALDO_DEUDA, 
CNM_CARTERANOMISIONAL.PLAZO_CUOTAS, CNM_FORMAPAGO.FORMA_PAGO, CNM_CARTERA_CONVENIOS.ADJUNTOS, CNM_CARTERA_CONVENIOS.TERMINO_CONVENIO, 
CNM_CARTERA_CONVENIOS.TASA_RENDIMIENTO');
$this->db->select("to_char(CNM_CARTERA_CONVENIOS.FECHA_INICIO_CALCULO_R,'dd/mm/yyyy') AS FECHA_INICIO_CALCULO_R",FALSE);
$this->db->select("to_char(CNM_CARTERA_CONVENIOS.FECHA_FIN_CALCULO_R,'dd/mm/yyyy') AS FECHA_FIN_CALCULO_R",FALSE);
$this->db->select("to_char(CNM_CARTERA_CONVENIOS.FECHA_SUSCRIPCION,'dd/mm/yyyy') AS FECHA_SUSCRIPCION",FALSE);
$this->db->select("to_char(CNM_CARTERA_CONVENIOS.FECHA_ACTA_LIQ,'dd/mm/yyyy') AS FECHA_ACTA_LIQ",FALSE);
$this->db->select("to_char(CNM_CARTERA_CONVENIOS.FECHA_MAX_PAGO,'dd/mm/yyyy') AS FECHA_MAX_PAGO",FALSE);
$this->db->join('CNM_CARTERANOMISIONAL', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_CONVENIOS.COD_CARTERA AND CNM_CARTERA_CONVENIOS.COD_CARTERA=".$cod_cartera);
$this->db->join('CNM_EMPRESA', "CNM_CARTERANOMISIONAL.COD_EMPRESA=CNM_EMPRESA.COD_ENTIDAD");
$this->db->join('REGIONAL', "CNM_EMPRESA.COD_REGIONAL=REGIONAL.COD_REGIONAL");
$this->db->join('ESTADOCARTERA', "CNM_CARTERANOMISIONAL.COD_ESTADO=ESTADOCARTERA.COD_EST_CARTERA");
if(!empty($acuerdo)){
$this->db->join('NM_ACUERDOLEY', "CNM_CARTERANOMISIONAL.COD_TIPO_ACUERDO=NM_ACUERDOLEY.COD_ACUERDOLEY");
}
$this->db->join('TIPOCARTERA', "CNM_CARTERANOMISIONAL.COD_TIPOCARTERA=TIPOCARTERA.COD_TIPOCARTERA");
$this->db->join('CNM_FORMAPAGO', "CNM_CARTERANOMISIONAL.COD_FORMAPAGO=CNM_FORMAPAGO.COD_FORMAPAGO");


		  		  
		  if($mod_t_corriente=='S')
		  {
		  $this->db->select('CORRIENTE.NOMBRE_CALCULO_COMP AS NOMBRE_CALCULO_COMP_CORR');
		  $this->db->join('NM_CALCULO_COMPONENTE CORRIENTE', "CNM_CARTERANOMISIONAL.CALCULO_CORRIENTE=CORRIENTE.COD_CALCULO_COMPONENTE");
		  
				   if($calc_corr==1)
				  {
				  $this->db->select('T_F_CORRIENTE.NOMBRE_TASAINTERES AS NOMBRE_TASAINTERES_CORR, CNM_CARTERANOMISIONAL.VALOR_T_CORRIENTE');
				  $this->db->join('TIPOTASAINTERES T_F_CORRIENTE', "CNM_CARTERANOMISIONAL.TIPO_T_F_CORRIENTE=T_F_CORRIENTE.COD_TIPO_TASAINTERES");
				  }
				  else
				  {
				  $this->db->select('T_V_CORRIENTE.NOMBRE_TIPOTASA AS NOMBRE_TASAINTERES_CORR');
				  $this->db->join('TIPO_TASA_HISTORICA T_V_CORRIENTE', "CNM_CARTERANOMISIONAL.TIPO_T_V_CORRIENTE=T_V_CORRIENTE.COD_TIPO_TASA_HIST");
				  }
		  
		  }
		  		  if($mod_t_mora=='S')
		  {
		 $this->db->select('MORA.NOMBRE_CALCULO_COMP AS NOMBRE_CALCULO_COMP_MORA');
		 $this->db->join('NM_CALCULO_COMPONENTE MORA', "CNM_CARTERANOMISIONAL.CALCULO_MORA=MORA.COD_CALCULO_COMPONENTE");
		    	  if($calc_mora==1)
				  {
				  $this->db->select('T_F_MORA.NOMBRE_TASAINTERES AS NOMBRE_TASAINTERES_MORA, CNM_CARTERANOMISIONAL.VALOR_T_MORA');
				  $this->db->join('TIPOTASAINTERES T_F_MORA', "CNM_CARTERANOMISIONAL.TIPO_T_F_MORA=T_F_MORA.COD_TIPO_TASAINTERES");
				  }
				  else
				  {
				  $this->db->select('T_V_MORA.NOMBRE_TIPOTASA AS NOMBRE_TASAINTERES_MORA');
				  $this->db->join('TIPO_TASA_HISTORICA T_V_MORA', "CNM_CARTERANOMISIONAL.TIPO_T_V_MORA=T_V_MORA.COD_TIPO_TASA_HIST");
				  }
		  }
		  
		$dato = $this->db->get("CNM_CARTERA_CONVENIOS");
//echo $this->db->last_query();
//die();
		 return $dato;
  }
  
  								function selectTasaEspComboEdit($calculo){
								
	if($calculo==1){							
		$this->db->select('COD_TIPO_TASAINTERES AS COD_TASA, NOMBRE_TASAINTERES AS NOMBRE_TASA');
		$this->db->where("TIPOTASAINTERES.ELIMINADO IS NULL", NULL, FALSE); 
		$dato = $this->db->get("TIPOTASAINTERES");
		}
		elseif($calculo==2)
		{
		$this->db->select('COD_TIPO_TASA_HIST AS COD_TASA, NOMBRE_TIPOTASA AS NOMBRE_TASA');
		$this->db->where("TIPO_TASA_HISTORICA.ELIMINADO IS NULL", NULL, FALSE); 
		$dato = $this->db->get("TIPO_TASA_HISTORICA");
		}
		else{
		$dato="";}
		
		return $dato;
	}
	
				    function updateCartera($table,$data,$date='',$id_deuda, $identificador){
        if ($date!='') {
           foreach ($data as $key => $value) {
                     $this->db->set($key, $value);
                 }
            foreach ($date as $keyf => $valuef) {
                     $this->db->set($keyf,"to_date('".$valuef."','dd/mm/yyyy hh24:mi:ss')",false);
                 }         
			$this->db->where('"'.$identificador.'"', $id_deuda);
            $this->db->update($table);
        }
		
		else{
				$this->db->where('"'.$identificador.'"', $id_deuda);
            $this->db->update($table, $data);
        }
		//echo $this->db->last_query();
		//die();
        if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
		
		return FALSE;       
    }
	
	  	function countEmpresa ($nit){
			
		$query = $this->db->query(" SELECT COUNT(COD_ENTIDAD) AS EXIST FROM CNM_EMPRESA WHERE COD_ENTIDAD='".$nit."'");
		return $query->result_array[0];
		}	
}