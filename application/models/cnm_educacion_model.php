<?php
class Cnm_educacion_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    			    function updateCartera($table,$data,$date='',$id_deuda, $identificador){
					echo $id_deuda;
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
        if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
		
		return FALSE;       
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
    
	       function getSelectTipoCartera($table,$fields){
        $query = $this->db->query(" SELECT ".$fields."  FROM ".$table." WHERE COD_ESTADO='1' AND TIPO='CREADA' AND COD_TIPOCARTERA='9'");
        return $query->result();
    }
	
		function detalleCarteras ($cedula, $id_cartera, $estado, $id_deuda){
$this->db->select('CNM_EMPLEADO.IDENTIFICACION, CNM_EMPLEADO.NOMBRES, CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.DIRECCION, CNM_EMPLEADO.TELEFONO,
CNM_EMPLEADO.TELEFONO_CELULAR, CNM_EMPLEADO.CORREO_ELECTRONICO,  REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_CARTERANOMISIONAL.COD_ESTADO, CNM_CARTERA_EDUCATIVO_SANCION.ADJUNTOS, CNM_CARTERANOMISIONAL.COD_TIPOCARTERA');
$this->db->join('CNM_CARTERA_EDUCATIVO_SANCION', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_EDUCATIVO_SANCION.COD_CARTERA");
if(!empty($cedula)){
$this->db->join('CNM_EMPLEADO', "CNM_CARTERANOMISIONAL.COD_EMPLEADO=CNM_EMPLEADO.IDENTIFICACION AND CNM_CARTERANOMISIONAL.COD_EMPLEADO=".$cedula);
}
else{
$this->db->join('CNM_EMPLEADO', "CNM_CARTERANOMISIONAL.COD_EMPLEADO=CNM_EMPLEADO.IDENTIFICACION");
}
$this->db->join('REGIONAL', "CNM_EMPLEADO.COD_REGIONAL=REGIONAL.COD_REGIONAL");
$this->db->join('ESTADOCARTERA', "CNM_CARTERANOMISIONAL.COD_ESTADO=ESTADOCARTERA.COD_EST_CARTERA AND CNM_CARTERANOMISIONAL.COD_ESTADO=".$estado);
$this->db->where("CNM_CARTERANOMISIONAL.COD_TIPOCARTERA", $id_cartera);
if(!empty($id_deuda)){
$this->db->where("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", $id_deuda);
}
		$dato = $this->db->get("CNM_CARTERANOMISIONAL");
		 return $dato;
	}
	
	function detalleCarterasPrest ($cedula, $id_cartera, $estado, $id_deuda){
$this->db->select('CNM_EMPLEADO.IDENTIFICACION, CNM_EMPLEADO.NOMBRES, CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.DIRECCION, CNM_EMPLEADO.TELEFONO,
CNM_EMPLEADO.TELEFONO_CELULAR, CNM_EMPLEADO.CORREO_ELECTRONICO,  REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_CARTERANOMISIONAL.COD_ESTADO, CNM_CARTERA_PREST_EDUCATIVO.ADJUNTOS, CNM_CARTERANOMISIONAL.COD_TIPOCARTERA');
$this->db->join('CNM_CARTERA_PREST_EDUCATIVO', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_PREST_EDUCATIVO.COD_CARTERA");
if(!empty($cedula)){
$this->db->join('CNM_EMPLEADO', "CNM_CARTERANOMISIONAL.COD_EMPLEADO=CNM_EMPLEADO.IDENTIFICACION AND CNM_CARTERANOMISIONAL.COD_EMPLEADO=".$cedula);
}
else{
$this->db->join('CNM_EMPLEADO', "CNM_CARTERANOMISIONAL.COD_EMPLEADO=CNM_EMPLEADO.IDENTIFICACION");
}
$this->db->join('REGIONAL', "CNM_EMPLEADO.COD_REGIONAL=REGIONAL.COD_REGIONAL");
$this->db->join('ESTADOCARTERA', "CNM_CARTERANOMISIONAL.COD_ESTADO=ESTADOCARTERA.COD_EST_CARTERA AND CNM_CARTERANOMISIONAL.COD_ESTADO=".$estado);
$this->db->where("CNM_CARTERANOMISIONAL.COD_TIPOCARTERA", $id_cartera);
if(!empty($id_deuda)){
$this->db->where("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", $id_deuda);
}
		$dato = $this->db->get("CNM_CARTERANOMISIONAL");
		 return $dato;
	}
	


	
    function getSequence($table,$name){
        $query = $this->db->query("SELECT ".$name."  FROM ".$table." ");
        $row = $query->row_array();
        return $row['NEXTVAL']-1;
        
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
	
 
 		    function update($table,$data){

	$query = $this->db->query(" UPDATE ".$table." SET COD_CARTERA='".$data['COD_CARTERA']."' WHERE COD_CARTERA_CALAMIDAD=".$data['COD_CARTERA_CALAMIDAD']);
if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;       
    }
	
	 		    function updateadjuntos($table,$data){

	$query = $this->db->query(" UPDATE ".$table." SET ADJUNTOS='".$data['ADJUNTOS']."' WHERE COD_CARTERA_CALAMIDAD=".$data['COD_CARTERA_CALAMIDAD']);
if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;       
    }
	


		       function select_data_emp($cedula){
        $query = $this->db->query(" SELECT CNM_EMPLEADO.NOMBRES, CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.DIRECCION, CNM_EMPLEADO.TELEFONO, CNM_EMPLEADO.TELEFONO_CELULAR, 
		CNM_EMPLEADO.CORREO_ELECTRONICO,  REGIONAL.NOMBRE_REGIONAL  FROM CNM_EMPLEADO 
		JOIN REGIONAL ON CNM_EMPLEADO.COD_REGIONAL=REGIONAL.COD_REGIONAL
		WHERE IDENTIFICACION='".$cedula."'");
        return $query->result_array[0]; 
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
	
						function selectAcuerdo($idAcuerdo){
$this->db->select('COD_ACUERDOLEY, NOMBRE_ACUERDO');
$this->db->where('COD_TIPOCARTERA',$idAcuerdo);
		$dato = $this->db->get("NM_ACUERDOLEY");
		//echo $this->db->last_query();
		 return $dato;
	}
	
		
						function selectModalidad($idAcuerdo){
$this->db->select('COD_MODALIDAD, NOMBRE_MODALIDAD');
$this->db->where('COD_ACUERDO',$idAcuerdo);
		$this->db->where("ELIMINADO IS NULL", NULL, FALSE);
		$dato = $this->db->get("CNM_MODALIDADES");
		//echo $this->db->last_query();
		 return $dato;
	}
	

	
		  function set_nit($nit) {
    $this->nit = $nit;
  }
  
  
    function buscarnits() {
    $this->db->select('IDENTIFICACION, NOMBRES, APELLIDOS');
    if (!empty($this->nit)) {
      $this->db->like('IDENTIFICACION', $this->nit);
    }
    $datos = $this->db->get('CNM_EMPLEADO');
    $datos = $datos->result_array();
    if(!empty($datos)) :
      $tmp = NULL;
      foreach($datos as $nit) :
        $tmp[] = array("value" => $nit['IDENTIFICACION'], "label" => $nit['IDENTIFICACION']." :: ".$nit['NOMBRES']." ".$nit['APELLIDOS']);
      endforeach;
      $datos = $tmp;
    endif;
    return $datos;
  }
  
  function detalleprevioEducativo($cod_cartera) {
    $this->db->select('CNM_CARTERA_EDUCATIVO_SANCION.MOD_TASA_CORRIENTE, CNM_CARTERA_EDUCATIVO_SANCION.MOD_TASA_MORA, CNM_CARTERANOMISIONAL.CALCULO_CORRIENTE, CNM_CARTERANOMISIONAL.CALCULO_MORA');
$this->db->join('CNM_CARTERANOMISIONAL', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_EDUCATIVO_SANCION.COD_CARTERA AND CNM_CARTERA_EDUCATIVO_SANCION.COD_CARTERA=".$cod_cartera);
$dato = $this->db->get("CNM_CARTERA_EDUCATIVO_SANCION");

		return $dato;
  }
  

  	
		  function detalleEducativo($cod_cartera, $mod_t_corriente, $mod_t_mora, $calc_corr, $calc_mora) {

    $this->db->select('CNM_EMPLEADO.IDENTIFICACION, CNM_EMPLEADO.NOMBRES, CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.DIRECCION, CNM_EMPLEADO.TELEFONO,
CNM_EMPLEADO.TELEFONO_CELULAR, CNM_EMPLEADO.CORREO_ELECTRONICO,  REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, NM_ACUERDOLEY.NOMBRE_ACUERDO, TIPOCARTERA.NOMBRE_CARTERA, CNM_CARTERA_EDUCATIVO_SANCION.NUM_ACTA_COMP,
CNM_CARTERANOMISIONAL.VALOR_DEUDA, CNM_CARTERANOMISIONAL.SALDO_DEUDA, CNM_CARTERANOMISIONAL.PLAZO_CUOTAS, CNM_PLAZO.NOMBRE_PLAZO, CNM_FORMAPAGO.FORMA_PAGO,
CNM_CARTERA_EDUCATIVO_SANCION.ID_CODEUDOR, CNM_CARTERA_EDUCATIVO_SANCION.NOMBRE_CODEUDOR, CNM_CARTERA_EDUCATIVO_SANCION.ADJUNTOS');
$this->db->select("to_char(CNM_CARTERA_EDUCATIVO_SANCION.FECHA_SOLICITUD,'dd/mm/yyyy') AS FECHA_SOLICITUD",FALSE);
$this->db->select("to_char(CNM_CARTERA_EDUCATIVO_SANCION.FECHA_ACTIVACION,'dd/mm/yyyy') AS FECHA_ACTIVACION",FALSE);
$this->db->select("to_char(CNM_CARTERA_EDUCATIVO_SANCION.FECHA_ACTA_COMP,'dd/mm/yyyy') AS FECHA_ACTA_COMP",FALSE);
$this->db->join('CNM_CARTERANOMISIONAL', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_EDUCATIVO_SANCION.COD_CARTERA AND CNM_CARTERA_EDUCATIVO_SANCION.COD_CARTERA=".$cod_cartera);
$this->db->join('CNM_EMPLEADO', "CNM_CARTERANOMISIONAL.COD_EMPLEADO=CNM_EMPLEADO.IDENTIFICACION");
$this->db->join('REGIONAL', "CNM_EMPLEADO.COD_REGIONAL=REGIONAL.COD_REGIONAL");
$this->db->join('ESTADOCARTERA', "CNM_CARTERANOMISIONAL.COD_ESTADO=ESTADOCARTERA.COD_EST_CARTERA");
$this->db->join('NM_ACUERDOLEY', "CNM_CARTERANOMISIONAL.COD_TIPO_ACUERDO=NM_ACUERDOLEY.COD_ACUERDOLEY");
$this->db->join('TIPOCARTERA', "CNM_CARTERANOMISIONAL.COD_TIPOCARTERA=TIPOCARTERA.COD_TIPOCARTERA");

$this->db->join('CNM_PLAZO', "CNM_CARTERANOMISIONAL.COD_PLAZO=CNM_PLAZO.COD_PLAZO");
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
		  
		$dato = $this->db->get("CNM_CARTERA_EDUCATIVO_SANCION");

		 return $dato;
  }
  
    function detalleprevioEducativoPrest($cod_cartera) {
    $this->db->select('CNM_CARTERA_PREST_EDUCATIVO.MOD_TASA_CORRIENTE, CNM_CARTERA_PREST_EDUCATIVO.MOD_TASA_MORA, CNM_CARTERANOMISIONAL.CALCULO_CORRIENTE, CNM_CARTERANOMISIONAL.CALCULO_MORA');
$this->db->join('CNM_CARTERANOMISIONAL', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_PREST_EDUCATIVO.COD_CARTERA AND CNM_CARTERA_PREST_EDUCATIVO.COD_CARTERA=".$cod_cartera);
$dato = $this->db->get("CNM_CARTERA_PREST_EDUCATIVO");
		return $dato;
  }
  
  
  		  function detalleEducativoPrest($cod_cartera, $mod_t_corriente, $mod_t_mora, $calc_corr, $calc_mora) {

    $this->db->select('CNM_EMPLEADO.IDENTIFICACION, CNM_EMPLEADO.NOMBRES, CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.DIRECCION, CNM_EMPLEADO.TELEFONO,
CNM_EMPLEADO.TELEFONO_CELULAR, CNM_EMPLEADO.CORREO_ELECTRONICO,  REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, NM_ACUERDOLEY.NOMBRE_ACUERDO, TIPOCARTERA.NOMBRE_CARTERA,
CNM_CARTERANOMISIONAL.VALOR_DEUDA, CNM_CARTERANOMISIONAL.SALDO_DEUDA, CNM_CARTERANOMISIONAL.PLAZO_CUOTAS, CNM_PLAZO.NOMBRE_PLAZO, CNM_FORMAPAGO.FORMA_PAGO,
CNM_CARTERA_PREST_EDUCATIVO.ID_CODEUDOR, CNM_CARTERA_PREST_EDUCATIVO.NOMBRE_CODEUDOR, CNM_CARTERA_PREST_EDUCATIVO.ADJUNTOS, CNM_CARTERA_PREST_EDUCATIVO.NUMERO_RESOLUCION');
$this->db->select("to_char(CNM_CARTERA_PREST_EDUCATIVO.FECHA_SOLICITUD,'dd/mm/yyyy') AS FECHA_SOLICITUD",FALSE);
$this->db->select("to_char(CNM_CARTERA_PREST_EDUCATIVO.FECHA_ACTIVACION,'dd/mm/yyyy') AS FECHA_ACTIVACION",FALSE);
$this->db->select("to_char(CNM_CARTERA_PREST_EDUCATIVO.FECHA_ACTIVACION,'dd/mm/yyyy') AS FECHA_RESOLUCION",FALSE);
$this->db->join('CNM_CARTERANOMISIONAL', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_PREST_EDUCATIVO.COD_CARTERA AND CNM_CARTERA_PREST_EDUCATIVO.COD_CARTERA=".$cod_cartera);
$this->db->join('CNM_EMPLEADO', "CNM_CARTERANOMISIONAL.COD_EMPLEADO=CNM_EMPLEADO.IDENTIFICACION");
$this->db->join('REGIONAL', "CNM_EMPLEADO.COD_REGIONAL=REGIONAL.COD_REGIONAL");
$this->db->join('ESTADOCARTERA', "CNM_CARTERANOMISIONAL.COD_ESTADO=ESTADOCARTERA.COD_EST_CARTERA");
$this->db->join('NM_ACUERDOLEY', "CNM_CARTERANOMISIONAL.COD_TIPO_ACUERDO=NM_ACUERDOLEY.COD_ACUERDOLEY");
$this->db->join('TIPOCARTERA', "CNM_CARTERANOMISIONAL.COD_TIPOCARTERA=TIPOCARTERA.COD_TIPOCARTERA");

$this->db->join('CNM_PLAZO', "CNM_CARTERANOMISIONAL.COD_PLAZO=CNM_PLAZO.COD_PLAZO");
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
		  
		$dato = $this->db->get("CNM_CARTERA_PREST_EDUCATIVO");

		 return $dato;
  }
  
  function detalleCarterasPrestEdit ($id_cartera){
$this->db->select('CNM_EMPLEADO.IDENTIFICACION, CNM_EMPLEADO.NOMBRES, CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.DIRECCION, CNM_EMPLEADO.TELEFONO,
CNM_EMPLEADO.TELEFONO_CELULAR, CNM_EMPLEADO.CORREO_ELECTRONICO,  REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_CARTERANOMISIONAL.COD_ESTADO, CNM_CARTERANOMISIONAL.COD_TIPO_ACUERDO, CNM_CARTERANOMISIONAL.COD_EMPLEADO, 
CNM_CARTERANOMISIONAL.VALOR_DEUDA, CNM_CARTERANOMISIONAL.PLAZO_CUOTAS, 
CNM_CARTERANOMISIONAL.COD_PLAZO, CNM_CARTERANOMISIONAL.COD_FORMAPAGO, CNM_CARTERA_PREST_EDUCATIVO.MOD_TASA_CORRIENTE, CNM_CARTERA_PREST_EDUCATIVO.MOD_TASA_MORA,
CNM_CARTERANOMISIONAL.CALCULO_CORRIENTE, CNM_CARTERANOMISIONAL.TIPO_T_F_CORRIENTE, CNM_CARTERANOMISIONAL.TIPO_T_V_CORRIENTE, CNM_CARTERANOMISIONAL.VALOR_T_CORRIENTE, 
CNM_CARTERANOMISIONAL.CALCULO_MORA, CNM_CARTERANOMISIONAL.TIPO_T_F_MORA, CNM_CARTERANOMISIONAL.TIPO_T_V_MORA, CNM_CARTERANOMISIONAL.VALOR_T_MORA,
CNM_CARTERA_PREST_EDUCATIVO.ID_CODEUDOR, CNM_CARTERA_PREST_EDUCATIVO.NOMBRE_CODEUDOR, CNM_CARTERA_PREST_EDUCATIVO.NUMERO_RESOLUCION');
$this->db->select("to_char(CNM_CARTERA_PREST_EDUCATIVO.FECHA_SOLICITUD,'dd/mm/yyyy') AS FECHA_SOLICITUD",FALSE);
$this->db->select("to_char(CNM_CARTERA_PREST_EDUCATIVO.FECHA_ACTIVACION,'dd/mm/yyyy') AS FECHA_ACTIVACION",FALSE);
$this->db->select("to_char(CNM_CARTERA_PREST_EDUCATIVO.FECHA_RESOLUCION,'dd/mm/yyyy') AS FECHA_RESOLUCION",FALSE);
$this->db->join('CNM_CARTERA_PREST_EDUCATIVO', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_PREST_EDUCATIVO.COD_CARTERA");
$this->db->join('CNM_EMPLEADO', "CNM_CARTERANOMISIONAL.COD_EMPLEADO=CNM_EMPLEADO.IDENTIFICACION");
$this->db->join('REGIONAL', "CNM_EMPLEADO.COD_REGIONAL=REGIONAL.COD_REGIONAL");
$this->db->join('ESTADOCARTERA', "CNM_CARTERANOMISIONAL.COD_ESTADO=ESTADOCARTERA.COD_EST_CARTERA");
$this->db->where("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", $id_cartera);

		$dato = $this->db->get("CNM_CARTERANOMISIONAL");
		 return $dato;
	}
  
  function detalleCarterasEdit ($id_cartera){
$this->db->select('CNM_EMPLEADO.IDENTIFICACION, CNM_EMPLEADO.NOMBRES, CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.DIRECCION, CNM_EMPLEADO.TELEFONO,
CNM_EMPLEADO.TELEFONO_CELULAR, CNM_EMPLEADO.CORREO_ELECTRONICO,  REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_CARTERANOMISIONAL.COD_ESTADO, CNM_CARTERANOMISIONAL.COD_TIPO_ACUERDO, CNM_CARTERANOMISIONAL.COD_EMPLEADO, 
CNM_CARTERANOMISIONAL.VALOR_DEUDA, CNM_CARTERANOMISIONAL.PLAZO_CUOTAS, 
CNM_CARTERANOMISIONAL.COD_PLAZO, CNM_CARTERANOMISIONAL.COD_FORMAPAGO, CNM_CARTERA_EDUCATIVO_SANCION.MOD_TASA_CORRIENTE, CNM_CARTERA_EDUCATIVO_SANCION.MOD_TASA_MORA,
CNM_CARTERANOMISIONAL.CALCULO_CORRIENTE, CNM_CARTERANOMISIONAL.TIPO_T_F_CORRIENTE, CNM_CARTERANOMISIONAL.TIPO_T_V_CORRIENTE, CNM_CARTERANOMISIONAL.VALOR_T_CORRIENTE, 
CNM_CARTERANOMISIONAL.CALCULO_MORA, CNM_CARTERANOMISIONAL.TIPO_T_F_MORA, CNM_CARTERANOMISIONAL.TIPO_T_V_MORA, CNM_CARTERANOMISIONAL.VALOR_T_MORA,
CNM_CARTERA_EDUCATIVO_SANCION.ID_CODEUDOR, CNM_CARTERA_EDUCATIVO_SANCION.NOMBRE_CODEUDOR, CNM_CARTERA_EDUCATIVO_SANCION.NUM_ACTA_COMP');
$this->db->select("to_char(CNM_CARTERA_EDUCATIVO_SANCION.FECHA_SOLICITUD,'dd/mm/yyyy') AS FECHA_SOLICITUD",FALSE);
$this->db->select("to_char(CNM_CARTERA_EDUCATIVO_SANCION.FECHA_ACTIVACION,'dd/mm/yyyy') AS FECHA_ACTIVACION",FALSE);
$this->db->select("to_char(CNM_CARTERA_EDUCATIVO_SANCION.FECHA_ACTA_COMP,'dd/mm/yyyy') AS FECHA_ACTA",FALSE);
$this->db->join('CNM_CARTERA_EDUCATIVO_SANCION', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_EDUCATIVO_SANCION.COD_CARTERA");
$this->db->join('CNM_EMPLEADO', "CNM_CARTERANOMISIONAL.COD_EMPLEADO=CNM_EMPLEADO.IDENTIFICACION");
$this->db->join('REGIONAL', "CNM_EMPLEADO.COD_REGIONAL=REGIONAL.COD_REGIONAL");
$this->db->join('ESTADOCARTERA', "CNM_CARTERANOMISIONAL.COD_ESTADO=ESTADOCARTERA.COD_EST_CARTERA");
$this->db->where("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", $id_cartera);

		$dato = $this->db->get("CNM_CARTERANOMISIONAL");
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
	
	  	function countEmpleado ($cedula){
			
		$query = $this->db->query(" SELECT COUNT(IDENTIFICACION) AS EXIST FROM CNM_EMPLEADO WHERE IDENTIFICACION='".$cedula."'");
		return $query->result_array[0];
		

	}
}