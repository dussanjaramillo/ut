<?php
class Cnm_responsabilidad_bienes_model extends CI_Model {

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
           function getSelectTipoCartera($table,$fields){
        $query = $this->db->query(" SELECT ".$fields."  FROM ".$table." WHERE COD_ESTADO='1' AND TIPO='CREADA' AND COD_TIPOCARTERA='6'");
        return $query->result();
    }

	
		function getSequence($table,$name){
        $query = $this->db->query("SELECT ".$name."  FROM ".$table." ");
        $row = $query->row_array();
        return $row['NEXTVAL']-1;
        
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
  
          function selectDatosc($cod_carga){

$this->db->select('CM_ACTIVOS_BAJA.CODIGO_BIEN, CM_ACTIVOS_BAJA.PLACA_INVENATARIO, CM_ACTIVOS_BAJA.DESCRIPCION_ELEMENTO, CM_ACTIVOS_BAJA.COD_ACTIVOS_BAJA');
$this->db->select('CM_ACTIVOS_BAJA.COD_REGIONAL, CM_ACTIVOS_BAJA.MOTIVO_BAJA, CM_ACTIVOS_BAJA.VALOR_COMPRA');
$this->db->select('CM_ACTIVOS_BAJA.VALOR_DEPRECIACION, CM_ACTIVOS_BAJA.VALOR_X_DEPRECIAR, CM_ACTIVOS_BAJA.VALOR_DEDUCIBLE, CM_ACTIVOS_BAJA.NUM_IDENTIFICACION, CM_ACTIVOS_BAJA.NOMBRE_EMPLEADO');
$this->db->select("to_char(CM_ACTIVOS_BAJA.FECHA_DADO_BAJO,'dd/mm/yyyy') AS FECHA_BAJA",FALSE);
$this->db->where("CM_ACTIVOS_BAJA.COD_ACTIVOS_BAJA", $cod_carga);
		$dato = $this->db->get("CM_ACTIVOS_BAJA");
		//echo $this->db->last_query();
		 return $dato;

	         
    }
  
        function carguexorden($orden){

$this->db->select('CM_ACTIVOS_BAJA.CODIGO_BIEN, CM_ACTIVOS_BAJA.PLACA_INVENATARIO, CM_ACTIVOS_BAJA.DESCRIPCION_ELEMENTO, CM_ACTIVOS_BAJA.COD_ACTIVOS_BAJA');
$this->db->select('CM_ACTIVOS_BAJA.COD_REGIONAL, CM_ACTIVOS_BAJA.MOTIVO_BAJA, CM_ACTIVOS_BAJA.VALOR_COMPRA');
$this->db->select('CM_ACTIVOS_BAJA.VALOR_DEPRECIACION, CM_ACTIVOS_BAJA.VALOR_X_DEPRECIAR, CM_ACTIVOS_BAJA.VALOR_DEDUCIBLE, CM_ACTIVOS_BAJA.NUM_IDENTIFICACION');
$this->db->select("to_char(CM_ACTIVOS_BAJA.FECHA_DADO_BAJO,'dd/mm/yyyy') AS FECHA_BAJA",FALSE);
$this->db->where("CM_ACTIVOS_BAJA.CODIGO_BIEN", $orden);
   $this->db->where("CM_ACTIVOS_BAJA.CREADO", "N");
		$dato = $this->db->get("CM_ACTIVOS_BAJA");
		 return $dato;
	         
    }
	
	
		function carguexcedula($cedula){
$this->db->select('CM_ACTIVOS_BAJA.CODIGO_BIEN, CM_ACTIVOS_BAJA.PLACA_INVENATARIO, CM_ACTIVOS_BAJA.DESCRIPCION_ELEMENTO, CM_ACTIVOS_BAJA.COD_ACTIVOS_BAJA');
$this->db->select('CM_ACTIVOS_BAJA.COD_REGIONAL, CM_ACTIVOS_BAJA.MOTIVO_BAJA, CM_ACTIVOS_BAJA.VALOR_COMPRA');
$this->db->select('CM_ACTIVOS_BAJA.VALOR_DEPRECIACION, CM_ACTIVOS_BAJA.VALOR_X_DEPRECIAR, CM_ACTIVOS_BAJA.VALOR_DEDUCIBLE, CM_ACTIVOS_BAJA.NUM_IDENTIFICACION');
$this->db->select("to_char(CM_ACTIVOS_BAJA.FECHA_DADO_BAJO,'dd/mm/yyyy') AS FECHA_BAJA",FALSE);
$this->db->where("CM_ACTIVOS_BAJA.NUM_IDENTIFICACION", $cedula);
   $this->db->where("CM_ACTIVOS_BAJA.CREADO", "N");
		$dato = $this->db->get("CM_ACTIVOS_BAJA");
		 return $dato;
               
    }

	    function infoempleado($cedula){
$this->db->select('CNM_EMPLEADO.NOMBRES, CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.IDENTIFICACION');
$this->db->select('CNM_EMPLEADO.TELEFONO_CELULAR, CNM_EMPLEADO.TELEFONO, CNM_EMPLEADO.CORREO_ELECTRONICO');
$this->db->select('CNM_EMPLEADO.COD_REGIONAL, CNM_EMPLEADO.DIRECCION');
$this->db->where("CNM_EMPLEADO.IDENTIFICACION", $cedula);
		$dato = $this->db->get("CNM_EMPLEADO");
		 return $dato;
               
    }
	
		function tipoCartera ($id_cartera){
$this->db->select('COD_TIPOCARTERA, NOMBRE_CARTERA');
        $this->db->where("COD_ESTADO", '1');
		$this->db->where("COD_TIPOCARTERA", $id_cartera);
		$this->db->where("ELIMINADO IS NULL", NULL, FALSE);
		$dato = $this->db->get("TIPOCARTERA");
		 return $dato->result_array[0];
	}
	
				       function select_data_activo($activo){
			 $query = $this->db->query(" SELECT COUNT(CODIGO_BIEN) AS EXIST FROM CM_ACTIVOS_BAJA WHERE CODIGO_BIEN='".$activo."'");

			//echo $this->db->last_query();
		return $query->result_array[0];
    }
	
			       function select_data_cedula($cedula){
			 $query = $this->db->query(" SELECT COUNT(IDENTIFICACION) AS EXIST FROM CNM_EMPLEADO WHERE IDENTIFICACION='".$cedula."'");

			//echo $this->db->last_query();
		return $query->result_array[0];
    }
	
	
		function detalleCarteras ($cedula, $id_cartera, $estado, $id_deuda){
$this->db->select('CNM_EMPLEADO.IDENTIFICACION, CNM_EMPLEADO.NOMBRES, CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.DIRECCION, CNM_EMPLEADO.TELEFONO,
CNM_EMPLEADO.TELEFONO_CELULAR, CNM_EMPLEADO.CORREO_ELECTRONICO,  REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_CARTERANOMISIONAL.COD_ESTADO, CNM_CARTERA_RESPON_BIENES.ADJUNTOS, CNM_CARTERANOMISIONAL.COD_TIPOCARTERA');
$this->db->join('CNM_CARTERA_RESPON_BIENES', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_RESPON_BIENES.COD_CARTERA");
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
		
		function selectTipoEstado(){
$this->db->select('COD_EST_CARTERA, DESC_EST_CARTERA');
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
  
 			function verificarExistenciaBien($cod_bien){

			 $query = $this->db->query(" SELECT COUNT(COD_TIPOACTIVO) AS EXIST FROM CM_TIPOACTIVO WHERE COD_TIPOACTIVO='".$cod_bien."'");

			//echo $this->db->last_query();
		return $query->result_array[0];
		//return $query->result();

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
 
   		 		    function updateCreado($cod_b){

	$query = $this->db->query(" UPDATE CM_ACTIVOS_BAJA SET CREADO='S' WHERE CODIGO_BIEN='".$cod_b."'");

	if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;       
    }
	
	
		  function detalleprevioRespB($cod_cartera) {
    $this->db->select('CNM_CARTERA_RESPON_BIENES.MOD_TASA_CORRIENTE, CNM_CARTERA_RESPON_BIENES.MOD_TASA_MORA, CNM_CARTERANOMISIONAL.CALCULO_CORRIENTE, CNM_CARTERANOMISIONAL.CALCULO_MORA');
$this->db->join('CNM_CARTERANOMISIONAL', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_RESPON_BIENES.COD_CARTERA AND CNM_CARTERA_RESPON_BIENES.COD_CARTERA=".$cod_cartera);
$dato = $this->db->get("CNM_CARTERA_RESPON_BIENES");

		return $dato;
  }
  

  	
		  function detalleRespB($cod_cartera, $mod_t_corriente, $mod_t_mora, $calc_corr, $calc_mora) {

    $this->db->select('CNM_EMPLEADO.IDENTIFICACION, CNM_EMPLEADO.NOMBRES, CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.DIRECCION, CNM_EMPLEADO.TELEFONO,
CNM_EMPLEADO.TELEFONO_CELULAR, CNM_EMPLEADO.CORREO_ELECTRONICO,  REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, TIPOCARTERA.NOMBRE_CARTERA, CNM_CARTERA_RESPON_BIENES.NUM_RESOLUCION, CNM_CARTERA_RESPON_BIENES.COD_TIPO_ACTIVO,
CNM_CARTERANOMISIONAL.VALOR_DEUDA, CNM_CARTERANOMISIONAL.SALDO_DEUDA, CNM_CARTERANOMISIONAL.PLAZO_CUOTAS, CNM_FORMAPAGO.FORMA_PAGO, 
CNM_CARTERA_RESPON_BIENES.MOTIVO_DADO_BAJA, CNM_CARTERA_RESPON_BIENES.VALOR_DEDUCIBLE, CNM_CARTERA_RESPON_BIENES.VALOR_DEPRECIADO, CM_TIPOACTIVO.VALOR_COMPRA"-"CNM_CARTERA_RESPON_BIENES.VALOR_DEPRECIADO AS VALOR_X_DEPRECIAR,
CNM_CARTERA_RESPON_BIENES.ADJUNTOS, CM_TIPOACTIVO.NOMBRE_TIPOACTIVO, CM_TIPOACTIVO.PLACA_INVENTARIO, CM_TIPOACTIVO.VALOR_COMPRA, RBIEN.NOMBRE_REGIONAL AS REGIONAL_BIEN, CNM_PLAZO.NOMBRE_PLAZO,');
$this->db->select("to_char(CNM_CARTERA_RESPON_BIENES.FECHA_ACTIVACION,'dd/mm/yyyy') AS FECHA_ACTIVACION",FALSE);
$this->db->select("to_char(CNM_CARTERA_RESPON_BIENES.FECHA_RESOLUCION,'dd/mm/yyyy') AS FECHA_RESOLUCION",FALSE);
$this->db->select("to_char(CNM_CARTERA_RESPON_BIENES.FECHA_BAJA,'dd/mm/yyyy') AS FECHA_BAJA",FALSE);
$this->db->join('CNM_CARTERANOMISIONAL', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_RESPON_BIENES.COD_CARTERA AND CNM_CARTERA_RESPON_BIENES.COD_CARTERA=".$cod_cartera);
$this->db->join('CNM_EMPLEADO', "CNM_CARTERANOMISIONAL.COD_EMPLEADO=CNM_EMPLEADO.IDENTIFICACION");
$this->db->join('REGIONAL', "CNM_EMPLEADO.COD_REGIONAL=REGIONAL.COD_REGIONAL");
$this->db->join('ESTADOCARTERA', "CNM_CARTERANOMISIONAL.COD_ESTADO=ESTADOCARTERA.COD_EST_CARTERA");
$this->db->join('CM_TIPOACTIVO', "CNM_CARTERA_RESPON_BIENES.COD_TIPO_ACTIVO=CM_TIPOACTIVO.COD_TIPOACTIVO");
$this->db->join('REGIONAL RBIEN', "CM_TIPOACTIVO.COD_REGIONAL=RBIEN.COD_REGIONAL");
//$this->db->join('CONTRATISTA', "CNM_CARTERA_RESPON_BIENES.COD_CONTRATISTA=CONTRATISTA.COD_CONTRATISTA");
//$this->db->join('NM_ACUERDOLEY', "CNM_CARTERANOMISIONAL.COD_TIPO_ACUERDO=NM_ACUERDOLEY.COD_ACUERDOLEY");
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
		  
		$dato = $this->db->get("CNM_CARTERA_RESPON_BIENES");
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
        if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
		
		return FALSE;       
    }
  
  function guardarfila($estructura, $archivo) {
        $fecha = date('d/m/Y H:i:s)');
        $x = 0;
		$this->db->trans_begin();
		
		$this->db->set('NUM_IDENTIFICACION', $estructura[0]);
        $this->db->set('NOMBRE_EMPLEADO', $estructura[1]);
		$this->db->set('FECHA_DADO_BAJO', "to_date('" . $estructura[2] . "','dd/mm/yyyy HH24:MI:SS')", false);
        $this->db->set('PLACA_INVENATARIO', $estructura[3]);
        $this->db->set('CODIGO_BIEN', $estructura[4]);
        $this->db->set('DESCRIPCION_ELEMENTO', $estructura[5]);
        $this->db->set('COD_REGIONAL', $estructura[6]);
        $this->db->set('MOTIVO_BAJA', $estructura[7]);
        $this->db->set('VALOR_COMPRA', $estructura[8]);
		$this->db->set('VALOR_DEPRECIACION', $estructura[9]);
		$this->db->set('VALOR_X_DEPRECIAR', $estructura[10]);
		$this->db->set('VALOR_DEDUCIBLE', $estructura[11]);
        $this->db->insert('CM_ACTIVOS_BAJA');

		$this->db->set('FECHA_CREACION', "to_date('" . date("d/m/Y H:i:s") . "','dd/mm/yyyy HH24:MI:SS')", false);
		$this->db->set('COD_TIPOCARTERA', '6');
		$this->db->set('COD_PROCESO_REALIZADO', '1');
		$this->db->set('COD_REGIONAL', $this->ion_auth->user()->row()->COD_REGIONAL);
		$this->db->set('COD_EMPLEADO', $estructura[0]);
		$this->db->set('COD_ESTADO', '1');
		$this->db->set('VALOR_DEUDA', $estructura[11]);											
		$this->db->set('COD_FORMAPAGO', '2');
		$this->db->set('SALDO_DEUDA', $estructura[11]);
		$this->db->set('COD_PLAZO', '1');
		$this->db->insert('CNM_CARTERANOMISIONAL');
		
		    $existbien = $this->verificarExistenciaBien($estructura[4]);
        if ($existbien["EXIST"] == 0) {
			$this->db->set('FECHA_CREACION', "to_date('" . date("d/m/Y H:i:s") . "','dd/mm/yyyy HH24:MI:SS')", false);
            $this->db->set('COD_TIPOACTIVO', $estructura[4]);
            $this->db->set('NOMBRE_TIPOACTIVO', $estructura[5]);
            $this->db->set('COD_REGIONAL', $estructura[6]);
            $this->db->set('PLACA_INVENTARIO', $estructura[3]);
			$this->db->set('VALOR_COMPRA', $estructura[8]);
            $this->db->insert('CM_TIPOACTIVO');
        }
		
		$query = $this->db->query("SELECT CNM_CarteraNo_cod_carteram_SEQ.CURRVAL FROM dual");
        $row = $query->row_array();
        $id_cartera = $row['CURRVAL'];
		
		$this->db->set('FECHA_CREACION', "to_date('" . date("d/m/Y H:i:s") . "','dd/mm/yyyy HH24:MI:SS')", false);
		$this->db->set('FECHA_BAJA', "to_date('" . $estructura[2] . "','dd/mm/yyyy HH24:MI:SS')", false);
		$this->db->set('COD_TIPO_ACTIVO', $estructura[4]);
		$this->db->set('MOTIVO_DADO_BAJA', $estructura[7]);
		$this->db->set('VALOR_DEPRECIADO', $estructura[9]);
		$this->db->set('VALOR_DEDUCIBLE', $estructura[11]);
		$this->db->set('FORMA_PAGO', '2');
		$this->db->set('COD_CARTERA', $id_cartera);
		$this->db->insert('CNM_CARTERA_RESPON_BIENES');
		

		$this->db->set('ID_DEUDA_E', $id_cartera);
		$this->db->set('NO_CUOTA', '1');
		$this->db->set('CONCEPTO', '6');
		$this->db->set('CAPITAL', $estructura[11]);
		$this->db->set('SALDO_CUOTA', $estructura[11]);
		$this->db->set('VALOR_CUOTA', $estructura[11]);
		$this->db->set('AMORTIZACION', $estructura[11]);
		$this->db->set('SALDO_AMORTIZACION', $estructura[11]);
		$this->db->set('MES_PROYECTADO',date("Y-m"));
		$this->db->set('MEDIO_INI_PAGO', '2');
		$this->db->insert('CNM_CUOTAS');
		
				$this->db->set('COMENTARIOS', 'Cargue Realizado' );
        $this->db->set('COD_TIPO_RESPUESTA', '1495' );
        $this->db->set('COD_TIPOGESTION', '735' );
        $this->db->set('COD_TITULO', '');
        $this->db->set('COD_USUARIO', '');
        $this->db->set('COD_JURIDICO', '');
        $this->db->set('COD_CARTERANOMISIONAL', $id_cartera);
        $this->db->set('COD_DEVOLUCION', '');
        $this->db->set('COD_RECEPCIONTITULO', '');	
		$this->db->set('FECHA',  "to_date('" . date("d/m/Y H:i:s") . "','dd/mm/yyyy HH24:MI:SS')", false );
		 $this->db->insert('TRAZAPROCJUDICIAL');
		
		        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $id_cartera;
        }
		
    }
	
		function detalleCarterasEdit ($id_cartera){
$this->db->select('CNM_EMPLEADO.IDENTIFICACION, CNM_EMPLEADO.NOMBRES, CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.DIRECCION, CNM_EMPLEADO.TELEFONO,
CNM_EMPLEADO.TELEFONO_CELULAR, CNM_EMPLEADO.CORREO_ELECTRONICO,  REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_CARTERANOMISIONAL.COD_ESTADO, CNM_CARTERANOMISIONAL.COD_TIPO_ACUERDO, CNM_CARTERANOMISIONAL.COD_EMPLEADO, 
CNM_CARTERA_RESPON_BIENES.NUM_RESOLUCION, CNM_CARTERANOMISIONAL.VALOR_DEUDA, CNM_CARTERANOMISIONAL.PLAZO_CUOTAS, 
CNM_CARTERANOMISIONAL.COD_PLAZO, CNM_CARTERANOMISIONAL.COD_FORMAPAGO, CNM_CARTERA_RESPON_BIENES.MOD_TASA_CORRIENTE, CNM_CARTERA_RESPON_BIENES.MOD_TASA_MORA,
CNM_CARTERANOMISIONAL.CALCULO_CORRIENTE, CNM_CARTERANOMISIONAL.TIPO_T_F_CORRIENTE, CNM_CARTERANOMISIONAL.TIPO_T_V_CORRIENTE, CNM_CARTERANOMISIONAL.VALOR_T_CORRIENTE, 
CNM_CARTERANOMISIONAL.CALCULO_MORA, CNM_CARTERANOMISIONAL.TIPO_T_F_MORA, CNM_CARTERANOMISIONAL.TIPO_T_V_MORA, CNM_CARTERANOMISIONAL.VALOR_T_MORA,
CNM_CARTERA_RESPON_BIENES.NUM_RESOLUCION, CNM_CARTERA_RESPON_BIENES.COD_TIPO_ACTIVO, CM_TIPOACTIVO.NOMBRE_TIPOACTIVO, CM_TIPOACTIVO.PLACA_INVENTARIO, 
REGIONALBIEN.NOMBRE_REGIONAL AS NOMBRE_REGIONAL_BIEN, CNM_CARTERA_RESPON_BIENES.MOTIVO_DADO_BAJA, CNM_CARTERA_RESPON_BIENES.VALOR_DEPRECIADO, CM_TIPOACTIVO.VALOR_COMPRA,
CNM_CARTERA_RESPON_BIENES.VALOR_DEDUCIBLE');

$this->db->select("to_char(CNM_CARTERA_RESPON_BIENES.FECHA_ACTIVACION,'dd/mm/yyyy') AS FECHA_ACTIVACION",FALSE);
$this->db->select("to_char(CNM_CARTERA_RESPON_BIENES.FECHA_BAJA,'dd/mm/yyyy') AS FECHA_BAJA",FALSE);
$this->db->select("to_char(CNM_CARTERA_RESPON_BIENES.FECHA_RESOLUCION,'dd/mm/yyyy') AS FECHA_RESOLUCION",FALSE);
$this->db->join('CNM_CARTERA_RESPON_BIENES', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_RESPON_BIENES.COD_CARTERA");
$this->db->join('CNM_EMPLEADO', "CNM_CARTERANOMISIONAL.COD_EMPLEADO=CNM_EMPLEADO.IDENTIFICACION");
$this->db->join('REGIONAL', "CNM_EMPLEADO.COD_REGIONAL=REGIONAL.COD_REGIONAL");
$this->db->join('ESTADOCARTERA', "CNM_CARTERANOMISIONAL.COD_ESTADO=ESTADOCARTERA.COD_EST_CARTERA");
$this->db->join('CM_TIPOACTIVO', "CNM_CARTERA_RESPON_BIENES.COD_TIPO_ACTIVO=CM_TIPOACTIVO.COD_TIPOACTIVO");
$this->db->join('REGIONAL REGIONALBIEN', "CM_TIPOACTIVO.COD_REGIONAL=REGIONALBIEN.COD_REGIONAL");
$this->db->where("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", $id_cartera);

$dato = $this->db->get("CNM_CARTERANOMISIONAL");
return $dato;
	}
  
}