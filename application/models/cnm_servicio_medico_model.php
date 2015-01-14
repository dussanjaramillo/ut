<?php
class Cnm_servicio_medico_model extends CI_Model {

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
 
 			function verificarExistenciaBeneficiario ($cod_beneficiario){

			 $query = $this->db->query(" SELECT COUNT(COD_EMPLEADO) AS EXIST FROM CNM_BENEFICIARIOS_SERVICIO_MED WHERE COD_BENEFICIARIO='".$cod_beneficiario."'");

			//echo $this->db->last_query();
		return $query->result_array[0];
		//return $query->result();

	}	
	
	 			function verificarExistenciaContratista ($id_contratista){

			 $query = $this->db->query(" SELECT COUNT(COD_CONTRATISTA) AS EXIST FROM CONTRATISTA WHERE COD_CONTRATISTA='".$id_contratista."'");

			//echo $this->db->last_query();
		return $query->result_array[0];
		//return $query->result();

	}	
	
	function verificarExistenciaEmpleado ($id_empleado){

			 $query = $this->db->query(" SELECT COUNT(IDENTIFICACION) AS EXIST FROM CNM_EMPLEADO WHERE IDENTIFICACION='".$id_empleado."'");

			//echo $this->db->last_query();
		return $query->result_array[0];
		//return $query->result();

	}	
	
	  function detalleprevioSMed($cod_cartera) {
    $this->db->select('CNM_EXCEDENTE_SERVICIO_MEDICO.MOD_TASA_CORRIENTE, CNM_EXCEDENTE_SERVICIO_MEDICO.MOD_TASA_MORA, CNM_CARTERANOMISIONAL.CALCULO_CORRIENTE, CNM_CARTERANOMISIONAL.CALCULO_MORA');
$this->db->join('CNM_CARTERANOMISIONAL', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_EXCEDENTE_SERVICIO_MEDICO.COD_CARTERA AND CNM_EXCEDENTE_SERVICIO_MEDICO.COD_CARTERA=".$cod_cartera);
$dato = $this->db->get("CNM_EXCEDENTE_SERVICIO_MEDICO");

		return $dato;
  }
  

  	
		  function detalleSMed($cod_cartera, $mod_t_corriente, $mod_t_mora, $calc_corr, $calc_mora) {

    $this->db->select('CNM_EMPLEADO.IDENTIFICACION, CNM_EMPLEADO.NOMBRES, CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.DIRECCION, CNM_EMPLEADO.TELEFONO,
CNM_EMPLEADO.TELEFONO_CELULAR, CNM_EMPLEADO.CORREO_ELECTRONICO,  REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, TIPOCARTERA.NOMBRE_CARTERA, CNM_EXCEDENTE_SERVICIO_MEDICO.NUM_RESOLUCION, CNM_EXCEDENTE_SERVICIO_MEDICO.COD_BENEFICIARIO,
CNM_CARTERANOMISIONAL.VALOR_DEUDA, CNM_CARTERANOMISIONAL.SALDO_DEUDA, CNM_CARTERANOMISIONAL.PLAZO_CUOTAS, CNM_FORMAPAGO.FORMA_PAGO, CNM_BENEFICIARIOS_SERVICIO_MED.NOMBRES_BENEFICIARIO,
CNM_BENEFICIARIOS_SERVICIO_MED.APELLIDOS_BENEFICIARIO, CONTRATISTA.NOMBRE_CONTRATISTA, CONTRATISTA.COD_CONTRATISTA, CNM_EXCEDENTE_SERVICIO_MEDICO.VALOR_EXCEDIDO, CNM_EXCEDENTE_SERVICIO_MEDICO.VALOR_REINTEGRAR,
CNM_EXCEDENTE_SERVICIO_MEDICO.RECIBO_PAGO, CNM_EXCEDENTE_SERVICIO_MEDICO.FECHA_ACTIVACION, CNM_PLAZO.NOMBRE_PLAZO,
CNM_EXCEDENTE_SERVICIO_MEDICO.LETRA, CNM_EXCEDENTE_SERVICIO_MEDICO.ORDEN, CNM_EXCEDENTE_SERVICIO_MEDICO.VALOR_ORDEN, CNM_EXCEDENTE_SERVICIO_MEDICO.ADJUNTOS');
$this->db->select("to_char(CNM_EXCEDENTE_SERVICIO_MEDICO.VIGENCIA,'dd/mm/yyyy') AS VIGENCIA",FALSE);
$this->db->select("to_char(CNM_EXCEDENTE_SERVICIO_MEDICO.FECHA_ORDEN,'dd/mm/yyyy') AS FECHA_ORDEN",FALSE);
$this->db->select("to_char(CNM_EXCEDENTE_SERVICIO_MEDICO.FECHA_PAGO,'dd/mm/yyyy') AS FECHA_PAGO",FALSE);
$this->db->select("to_char(CNM_EXCEDENTE_SERVICIO_MEDICO.FECHA_RESOLUCION,'dd/mm/yyyy') AS FECHA_RESOLUCION",FALSE);
$this->db->join('CNM_CARTERANOMISIONAL', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_EXCEDENTE_SERVICIO_MEDICO.COD_CARTERA AND CNM_EXCEDENTE_SERVICIO_MEDICO.COD_CARTERA=".$cod_cartera);
$this->db->join('CNM_EMPLEADO', "CNM_CARTERANOMISIONAL.COD_EMPLEADO=CNM_EMPLEADO.IDENTIFICACION");
$this->db->join('REGIONAL', "CNM_EMPLEADO.COD_REGIONAL=REGIONAL.COD_REGIONAL");
$this->db->join('ESTADOCARTERA', "CNM_CARTERANOMISIONAL.COD_ESTADO=ESTADOCARTERA.COD_EST_CARTERA");
$this->db->join('CNM_BENEFICIARIOS_SERVICIO_MED', "CNM_EXCEDENTE_SERVICIO_MEDICO.COD_BENEFICIARIO=CNM_BENEFICIARIOS_SERVICIO_MED.COD_BENEFICIARIO");
$this->db->join('CONTRATISTA', "CNM_EXCEDENTE_SERVICIO_MEDICO.COD_CONTRATISTA=CONTRATISTA.COD_CONTRATISTA");
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
		  
		$dato = $this->db->get("CNM_EXCEDENTE_SERVICIO_MEDICO");
//echo $this->db->last_query();
//die();
		 return $dato;
  }

	
	
       function getSelectTipoCartera($table,$fields){
        $query = $this->db->query(" SELECT ".$fields."  FROM ".$table." WHERE COD_ESTADO='1' AND TIPO='CREADA' AND COD_TIPOCARTERA='5'");
        return $query->result();
    }
	
	
			  function set_nit($nit) {
    $this->nit = $nit;
  }
  
  		 		    function updateCreado($orden){

	$query = $this->db->query(" UPDATE CM_SEVICIOS_MEDICOS SET CREADO='S' WHERE ORDEN='".$orden."'");

	if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;       
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

$this->db->select('CM_SEVICIOS_MEDICOS.COD_CARGA, CM_SEVICIOS_MEDICOS.LETRA, CM_SEVICIOS_MEDICOS.NOMBRE_BENEFICIARIO, CM_SEVICIOS_MEDICOS.APELLIDOS_BENEFICIARIO');
$this->db->select('CM_SEVICIOS_MEDICOS.COD_REGIONAL, CM_SEVICIOS_MEDICOS.ORDEN');
$this->db->select('CM_SEVICIOS_MEDICOS.VALOR_ORDEN, CM_SEVICIOS_MEDICOS.IDENTIFICACION_CONTRATISTA AS CONTRATISTA, CM_SEVICIOS_MEDICOS.VALOR_EXCEDIDO');
$this->db->select('CM_SEVICIOS_MEDICOS.IDENTIFICACION_BENEFICIARIO, CM_SEVICIOS_MEDICOS.NOMBRE_CONTRATISTA');
$this->db->select('CM_SEVICIOS_MEDICOS.VALOR_REINTEGRAR, CM_SEVICIOS_MEDICOS.RECIBO_PAGO, CM_SEVICIOS_MEDICOS.COD_DEUDOR');
$this->db->select("to_char(CM_SEVICIOS_MEDICOS.FECHA_ORDEN,'dd/mm/yyyy') AS FECHA_ORDEN",FALSE);
$this->db->select("to_char(CM_SEVICIOS_MEDICOS.VIGENCIA,'dd/mm/yyyy') AS VIGENCIA",FALSE);
 $this->db->where("CM_SEVICIOS_MEDICOS.COD_CARGA", $cod_carga);
		$dato = $this->db->get("CM_SEVICIOS_MEDICOS");
		//echo $this->db->last_query();
		 return $dato;

	         
    }
  
  
      function carguexorden($orden){

$this->db->select('CM_SEVICIOS_MEDICOS.COD_CARGA, CM_SEVICIOS_MEDICOS.LETRA, CM_SEVICIOS_MEDICOS.NOMBRE_BENEFICIARIO');
$this->db->select('CM_SEVICIOS_MEDICOS.VIGENCIA, CM_SEVICIOS_MEDICOS.COD_REGIONAL, CM_SEVICIOS_MEDICOS.ORDEN');
$this->db->select('CM_SEVICIOS_MEDICOS.VALOR_ORDEN, CM_SEVICIOS_MEDICOS.NOMBRE_CONTRATISTA  AS CONTRATISTA, CM_SEVICIOS_MEDICOS.VALOR_EXCEDIDO');
$this->db->select('CM_SEVICIOS_MEDICOS.VALOR_REINTEGRAR, CM_SEVICIOS_MEDICOS.RECIBO_PAGO, CM_SEVICIOS_MEDICOS.COD_DEUDOR');
$this->db->select("to_char(CM_SEVICIOS_MEDICOS.FECHA_ORDEN,'dd/mm/yyyy') AS FECHA_ORDEN",FALSE);
 $this->db->where("CM_SEVICIOS_MEDICOS.ORDEN", $orden);
  $this->db->where("CM_SEVICIOS_MEDICOS.CREADO", "N");
		$dato = $this->db->get("CM_SEVICIOS_MEDICOS");
		 return $dato;

	}
	
	
    function carguexcedula($cedula){
$this->db->select('CM_SEVICIOS_MEDICOS.COD_CARGA, CM_SEVICIOS_MEDICOS.LETRA, CM_SEVICIOS_MEDICOS.NOMBRE_BENEFICIARIO');
$this->db->select('CM_SEVICIOS_MEDICOS.VIGENCIA, CM_SEVICIOS_MEDICOS.COD_REGIONAL, CM_SEVICIOS_MEDICOS.ORDEN');
$this->db->select('CM_SEVICIOS_MEDICOS.VALOR_ORDEN, CM_SEVICIOS_MEDICOS.NOMBRE_CONTRATISTA  AS CONTRATISTA, CM_SEVICIOS_MEDICOS.VALOR_EXCEDIDO');
$this->db->select('CM_SEVICIOS_MEDICOS.VALOR_REINTEGRAR, CM_SEVICIOS_MEDICOS.RECIBO_PAGO');
$this->db->select("to_char(CM_SEVICIOS_MEDICOS.FECHA_ORDEN,'dd/mm/yyyy') AS FECHA_ORDEN",FALSE);
 $this->db->where("CM_SEVICIOS_MEDICOS.COD_DEUDOR", $cedula);
   $this->db->where("CM_SEVICIOS_MEDICOS.CREADO", "N");
		$dato = $this->db->get("CM_SEVICIOS_MEDICOS");
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
	
	
				function detalleCarteras ($cedula, $id_cartera, $estado, $id_deuda){
$this->db->select('CNM_EMPLEADO.IDENTIFICACION, CNM_EMPLEADO.NOMBRES, CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.DIRECCION, CNM_EMPLEADO.TELEFONO,
CNM_EMPLEADO.TELEFONO_CELULAR, CNM_EMPLEADO.CORREO_ELECTRONICO,  REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_CARTERANOMISIONAL.COD_ESTADO, CNM_EXCEDENTE_SERVICIO_MEDICO.ADJUNTOS, CNM_CARTERANOMISIONAL.COD_TIPOCARTERA');
$this->db->join('CNM_EXCEDENTE_SERVICIO_MEDICO', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_EXCEDENTE_SERVICIO_MEDICO.COD_CARTERA");
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
//echo $this->db->last_query();
//die();		
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
	
					       function select_data_orden($orden){
			 $query = $this->db->query(" SELECT COUNT(ORDEN) AS EXIST FROM CM_SEVICIOS_MEDICOS WHERE ORDEN='".$orden."'");

			//echo $this->db->last_query();
		return $query->result_array[0];
    }
	
			       function select_data_cedula($cedula){
			 $query = $this->db->query(" SELECT COUNT(IDENTIFICACION) AS EXIST FROM CNM_EMPLEADO WHERE IDENTIFICACION='".$cedula."'");

			//echo $this->db->last_query();
		return $query->result_array[0];
    }
	
	function detalleCarterasEdit ($id_cartera){
$this->db->select('CNM_EMPLEADO.IDENTIFICACION, CNM_EMPLEADO.NOMBRES, CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.DIRECCION, CNM_EMPLEADO.TELEFONO,
CNM_EMPLEADO.TELEFONO_CELULAR, CNM_EMPLEADO.CORREO_ELECTRONICO,  REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_CARTERANOMISIONAL.COD_ESTADO, CNM_CARTERANOMISIONAL.COD_TIPO_ACUERDO, CNM_CARTERANOMISIONAL.COD_EMPLEADO, 
CNM_EXCEDENTE_SERVICIO_MEDICO.NUM_RESOLUCION, CNM_CARTERANOMISIONAL.VALOR_DEUDA, CNM_CARTERANOMISIONAL.PLAZO_CUOTAS, 
CNM_CARTERANOMISIONAL.COD_PLAZO, CNM_CARTERANOMISIONAL.COD_FORMAPAGO, CNM_EXCEDENTE_SERVICIO_MEDICO.MOD_TASA_CORRIENTE, CNM_EXCEDENTE_SERVICIO_MEDICO.MOD_TASA_MORA,
CNM_CARTERANOMISIONAL.CALCULO_CORRIENTE, CNM_CARTERANOMISIONAL.TIPO_T_F_CORRIENTE, CNM_CARTERANOMISIONAL.TIPO_T_V_CORRIENTE, CNM_CARTERANOMISIONAL.VALOR_T_CORRIENTE, 
CNM_CARTERANOMISIONAL.CALCULO_MORA, CNM_CARTERANOMISIONAL.TIPO_T_F_MORA, CNM_CARTERANOMISIONAL.TIPO_T_V_MORA, CNM_CARTERANOMISIONAL.VALOR_T_MORA,
CNM_EXCEDENTE_SERVICIO_MEDICO.LETRA, CNM_EXCEDENTE_SERVICIO_MEDICO.COD_BENEFICIARIO, CNM_BENEFICIARIOS_SERVICIO_MED.NOMBRES_BENEFICIARIO, 
CNM_BENEFICIARIOS_SERVICIO_MED.APELLIDOS_BENEFICIARIO, CONTRATISTA.NOMBRE_CONTRATISTA, CNM_EXCEDENTE_SERVICIO_MEDICO.COD_CONTRATISTA, CNM_EXCEDENTE_SERVICIO_MEDICO.ORDEN, 
CNM_EXCEDENTE_SERVICIO_MEDICO.VALOR_ORDEN, CNM_EXCEDENTE_SERVICIO_MEDICO.VALOR_EXCEDIDO, CNM_EXCEDENTE_SERVICIO_MEDICO.VALOR_REINTEGRAR, CNM_EXCEDENTE_SERVICIO_MEDICO.RECIBO_PAGO,
CNM_EXCEDENTE_SERVICIO_MEDICO.FECHA_PAGO, CNM_EXCEDENTE_SERVICIO_MEDICO.NUM_RESOLUCION');

$this->db->select("to_char(CNM_EXCEDENTE_SERVICIO_MEDICO.FECHA_ACTIVACION,'dd/mm/yyyy') AS FECHA_ACTIVACION",FALSE);
$this->db->select("to_char(CNM_EXCEDENTE_SERVICIO_MEDICO.VIGENCIA,'dd/mm/yyyy') AS VIGENCIA",FALSE);
$this->db->select("to_char(CNM_EXCEDENTE_SERVICIO_MEDICO.FECHA_ORDEN,'dd/mm/yyyy') AS FECHA_ORDEN",FALSE);
$this->db->select("to_char(CNM_EXCEDENTE_SERVICIO_MEDICO.FECHA_RESOLUCION,'dd/mm/yyyy') AS FECHA_RESOLUCION",FALSE);
$this->db->join('CNM_EXCEDENTE_SERVICIO_MEDICO', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_EXCEDENTE_SERVICIO_MEDICO.COD_CARTERA");
$this->db->join('CNM_EMPLEADO', "CNM_CARTERANOMISIONAL.COD_EMPLEADO=CNM_EMPLEADO.IDENTIFICACION");
$this->db->join('REGIONAL', "CNM_EMPLEADO.COD_REGIONAL=REGIONAL.COD_REGIONAL");
$this->db->join('ESTADOCARTERA', "CNM_CARTERANOMISIONAL.COD_ESTADO=ESTADOCARTERA.COD_EST_CARTERA");
$this->db->join('CNM_BENEFICIARIOS_SERVICIO_MED', "CNM_EXCEDENTE_SERVICIO_MEDICO.COD_BENEFICIARIO=CNM_BENEFICIARIOS_SERVICIO_MED.COD_BENEFICIARIO");
$this->db->join('CONTRATISTA', "CNM_EXCEDENTE_SERVICIO_MEDICO.COD_CONTRATISTA=CONTRATISTA.COD_CONTRATISTA");
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
	 //cargue
    function getSequence($table, $name) {
        $query = $this->db->query("SELECT " . $name . "  FROM " . $table . " ");
        $row = $query->row_array();
        return $row['NEXTVAL'] - 1;
    }

    function guardarfila($estructura, $archivo) {
        $fecha = date('d/m/Y H:i:s');

        $campos = array('IDENTIFICACION', 'NOMBRE', 'IDENTIFICACION_BENEFICIARIO', 'NOMBRE_BENEFICIARIO', 'APELLIDOS_BENEFICIARIO',
            'COD_REGIONAL', 'VIGENCIA', 'ORDEN', 'FECHA_ORDEN', 'IDENTIFICACION_CONTRATISTA', 'NOMBRE_CONTRATISTA',
            'VALOR_ORDEN', 'VALOR_EXCEDIDO', 'VALOR_REINTEGRAR'
        );

        $x = 0;

        $this->db->trans_begin();
        $this->db->set('FECHA_CARGUE', "to_date('" . $fecha . "','dd/mm/yyyy HH24:MI:SS')", false);
        foreach ($estructura as $data) {
            if ($x == 6) {
                $this->db->set($campos[$x], "to_date('" . $data . "','yyyy HH24:MI:SS')", false);
            }
			elseif ($x == 8) {
                $this->db->set($campos[$x], "to_date('" . $data . "','dd/mm/yyyy HH24:MI:SS')", false);
            } 			
					
			else {
                $this->db->set($campos[$x], $data);
            }
            $x++;
        }
        $this->db->insert('CM_SEVICIOS_MEDICOS');

		 $empl = $this->verificarExistenciaEmpleado($estructura[0]);
        if ($empl["EXIST"] == 0) {
            $this->db->set('IDENTIFICACION', $estructura[0]);
            $this->db->set('NOMBRES', $estructura[1]);
			$this->db->set('COD_REGIONAL', '11');
			$this->db->insert('CNM_EMPLEADO');
        }
		
        $this->db->set('COD_EMPLEADO', $estructura[0]);
        $this->db->set('COD_TIPOCARTERA', '5');
        $this->db->set('COD_PROCESO_REALIZADO', '1');
        $this->db->set('COD_ESTADO', '3');
        $this->db->set('COD_PLAZO', '1');
        $this->db->set('COD_TIPO_ACUERDO', '0');
        $this->db->set('COD_FORMAPAGO', '2');
        $this->db->set('COD_REGIONAL', $estructura[5]);
        $this->db->set('VALOR_DEUDA', $estructura[13]);
        $this->db->set('SALDO_DEUDA', $estructura[13]);
        $this->db->insert('CNM_CARTERANOMISIONAL');


        $contratista = $this->verificarExistenciaContratista($estructura[9]);
        if ($contratista["EXIST"] == 0) {
            $this->db->set('COD_CONTRATISTA', $estructura[9]);
            $this->db->set('NOMBRE_CONTRATISTA', $estructura[10]);
            $this->db->insert('CONTRATISTA');
        }


        $beneficiario = $this->verificarExistenciaBeneficiario($estructura[2]);
        if ($beneficiario["EXIST"] == 0) {
            $this->db->set('COD_BENEFICIARIO', $estructura[2]);
            $this->db->set('COD_EMPLEADO', $estructura[0]);
            $this->db->set('NOMBRES_BENEFICIARIO', $estructura[3]);
            $this->db->set('APELLIDOS_BENEFICIARIO', $estructura[4]);
            $this->db->insert('CNM_BENEFICIARIOS_SERVICIO_MED');
        }
		
		       


        $query = $this->db->query("SELECT CNM_CarteraNo_cod_carteram_SEQ.CURRVAL FROM dual");
        $row = $query->row_array();
        $id_cartera = $row['CURRVAL'];
        $this->db->set('FECHA_CREACION', "to_date('" . $fecha . "','dd/mm/yyyy HH24:MI:SS')", false);
        $this->db->set('FECHA_ORDEN', "to_date('" . $estructura[8] . "','dd/mm/yyyy HH24:MI:SS')", false);
        $this->db->set('VIGENCIA', "to_date('" . $estructura[6] . "','yyyy HH24:MI:SS')", false);
        $this->db->set('VALOR_REINTEGRAR', $estructura[13]);
        $this->db->set('VALOR_EXCEDIDO', $estructura[12]);
        $this->db->set('VALOR_ORDEN', $estructura[11]);
        $this->db->set('ORDEN', $estructura[7]);
        $this->db->set('COD_CARTERA', $id_cartera);
        $this->db->set('COD_CONTRATISTA', $estructura[9]);
        $this->db->set('COD_BENEFICIARIO', $estructura[2]);
        $this->db->insert('CNM_EXCEDENTE_SERVICIO_MEDICO');

		
		
		$this->db->set('ID_DEUDA_E', $id_cartera);
		$this->db->set('NO_CUOTA', '1');
		$this->db->set('CONCEPTO', '5');
		$this->db->set('CAPITAL', $estructura[13]);
		$this->db->set('SALDO_CUOTA', $estructura[13]);
		$this->db->set('VALOR_CUOTA', $estructura[13]);
		$this->db->set('AMORTIZACION', $estructura[13]);
		$this->db->set('SALDO_AMORTIZACION', $estructura[13]);
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
		$this->db->set('FECHA',  "to_date('" . $fecha . "','dd/mm/yyyy HH24:MI:SS')", false );
		 $this->db->insert('TRAZAPROCJUDICIAL');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $id_cartera;
        }
    }

}
