<?php
class Carteranomisional_model extends CI_Model {

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
	
		 		    function updateadjuntos($table,$data){
$post = $this->input->post();
if($post['agregar']==1){
		$querydoc = $this->db->query("SELECT ADJUNTOS FROM ".$table." WHERE ".$data['CODIGO_CARTERA']."='".$data['COD_CARTERA']."'");
        $row = $querydoc->row_array();
        $docactual = $row['ADJUNTOS'];
		if(empty($docactual)){
		$query = $this->db->query(" UPDATE ".$table." SET ADJUNTOS='".$data['ADJUNTOS']."' WHERE ".$data['CODIGO_CARTERA']."='".$data['COD_CARTERA']."'");
		}
		elseif(!empty($data['ADJUNTOS']))
		{
		$query = $this->db->query(" UPDATE ".$table." SET ADJUNTOS=ADJUNTOS || '::' || '".$data['ADJUNTOS']."' WHERE ".$data['CODIGO_CARTERA']."='".$data['COD_CARTERA']."'");
		}
			
}
else{
$query = $this->db->query(" UPDATE ".$table." SET ADJUNTOS='".$data['ADJUNTOS']."' WHERE ".$data['CODIGO_CARTERA']."='".$data['COD_CARTERA']."'");
}
	if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;       
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
	//esta función inserta los registros de los empleados provenientes del WS-Kactus
			    function add($table,$data){
            $this->db->insert($table, $data);

			if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
		
		return FALSE;       
    }
	//esta función actualiza los registros de los empleados provenientes del WS-Kactus
	function update($table,$data,$cedula,$nombre_c_ced){

			$this->db->where('"'.$nombre_c_ced.'"', $cedula);
            $this->db->update($table, $data);

        if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
		return FALSE;       
    }
	           function getSelectEstados($table,$fields){
        $query = $this->db->query(" SELECT ".$fields."  FROM ".$table." ORDER BY COD_EST_CARTERA");
        return $query->result();
    }
    
           function getSelectTipoCartera($table,$fields){
        $query = $this->db->query(" SELECT ".$fields."  FROM ".$table." WHERE COD_ESTADO='1' AND ELIMINADO IS NULL");
        return $query->result();
    }
	
		   function getSelectTipoCarteraCrear($table,$fields){
        $query = $this->db->query(" SELECT ".$fields."  FROM ".$table." WHERE COD_ESTADO='1' AND COD_TIPOCARTERA!='5' AND COD_TIPOCARTERA!='6' AND COD_TIPOCARTERA!='9' AND ELIMINADO IS NULL");
        return $query->result();
    }
	
    function getSequence($table,$name){
        $query = $this->db->query("SELECT ".$name."  FROM ".$table." ");
        $row = $query->row_array();
        return $row['NEXTVAL']-1;
        
    }
	
								function consultaadjuntos($tabla, $id){

$this->db->select('ADJUNTOS');
 $this->db->where("COD_CARTERA", $id);
		$dato = $this->db->get('"'.$tabla.'"');
//echo $this->db->last_query();
	//	die();
		 return $dato;
	}
	
		function detallePagos($id_deuda){
$this->db->select('CNM_CUOTAS.NO_CUOTA, CNM_CUOTAS.CAPITAL, CNM_CUOTAS.MES_PROYECTADO');
$this->db->select('CNM_CUOTAS.VALOR_CUOTA - "CNM_CUOTAS"."SALDO_CUOTA" + "CNM_CUOTAS"."INTERES_MORA_GEN" AS VALOR_PAGADOS');
$this->db->select('CNM_CUOTAS.CAPITAL AS SALDO, CNM_CUOTAS.VALOR_CUOTA, CNM_CUOTAS.AMORTIZACION, CNM_CUOTAS.VALOR_INTERES_C, CNM_CUOTAS.INTERES_MORA_GEN');
$this->db->select("to_char(CNM_CUOTAS.FECHA_LIM_PAGO,'dd/mm/yyyy') AS FECHA_PAGO",FALSE);	
$this->db->select("CNM_CUOTAS.SALDO_INTERES_C, CNM_CUOTAS.SALDO_AMORTIZACION");	
		$this->db->where('CNM_CUOTAS.ID_DEUDA_E',$id_deuda);
		$dato = $this->db->get("CNM_CUOTAS");
		//echo $this->db->last_query();
		 return $dato;
	}
	
			function detallePagosSeguros($id_deuda, $concepto){
$this->db->select('CNM_CUOTAS.NO_CUOTA, CNM_CUOTAS.CAPITAL, CNM_CUOTAS.MES_PROYECTADO');
$this->db->select('CNM_CUOTAS.VALOR_CUOTA - "CNM_CUOTAS"."SALDO_CUOTA" + "CNM_CUOTAS"."INTERES_MORA_GEN" AS VALOR_PAGADOS');
$this->db->select('CNM_CUOTAS.CAPITAL AS SALDO, CNM_CUOTAS.VALOR_CUOTA, CNM_CUOTAS.AMORTIZACION, CNM_CUOTAS.VALOR_INTERES_C, CNM_CUOTAS.INTERES_MORA_GEN');
$this->db->select("to_char(CNM_CUOTAS.FECHA_LIM_PAGO,'dd/mm/yyyy') AS FECHA_PAGO",FALSE);	
	
		$this->db->where('CNM_CUOTAS.ID_DEUDA_E',$id_deuda);
		$this->db->where('CNM_CUOTAS.CONCEPTO',$concepto);		
		$dato = $this->db->get("CNM_CUOTAS");
		//echo $this->db->last_query();
		 return $dato;
	}
	
			function detallePagosHipotecario($id_deuda){
$this->db->select('CNM_CUOTAS.NO_CUOTA, CNM_CUOTAS.CAPITAL, CNM_CUOTAS.MES_PROYECTADO');
$this->db->select('CNM_CUOTAS.VALOR_CUOTA - "CNM_CUOTAS"."SALDO_CUOTA" + "CNM_CUOTAS"."INTERES_MORA_GEN" AS VALOR_PAGADOS');
$this->db->select('CNM_CUOTAS.CAPITAL AS SALDO, CNM_CUOTAS.VALOR_CUOTA, CNM_CUOTAS.AMORTIZACION, CNM_CUOTAS.VALOR_INTERES_C, CNM_CUOTAS.INTERES_MORA_GEN');
$this->db->select('CNM_CUOTAS.SALDO_INTERES_NO_PAGOS, CNM_CUOTAS.CESANTIAS');
$this->db->select("to_char(CNM_CUOTAS.FECHA_LIM_PAGO,'dd/mm/yyyy') AS FECHA_PAGO",FALSE);	
$this->db->select("CNM_CUOTAS.SALDO_INTERES_C, CNM_CUOTAS.SALDO_AMORTIZACION");
$this->db->select("DECODE(CNM_CUOTAS.CESANTIAS_APLICADAS, 0, 0, CNM_CUOTAS.CESANTIAS ) CESANTIAS_APLICADA",FALSE);
	
		$this->db->where('CNM_CUOTAS.ID_DEUDA_E',$id_deuda);
		$this->db->where('CNM_CUOTAS.CONCEPTO','8');
		$dato = $this->db->get("CNM_CUOTAS");
		//echo $this->db->last_query();
		 return $dato;
	}
	
	
			function detallePagosEcollect($id_deuda){
$this->db->select('CNM_CUOTAS_ECOLLECT.NO_CUOTA, CNM_CUOTAS_ECOLLECT.SALDO_INICIAL_DEUDA');
$this->db->select('CNM_CUOTAS_ECOLLECT.VALOR_PAGADO AS VALOR_PAGADOS');
$this->db->select('CNM_CUOTAS_ECOLLECT.SALDO_INICIAL_DEUDA - "CNM_CUOTAS_ECOLLECT"."VALOR_PAGADO" AS SALDO');
$this->db->select("to_char(CNM_CUOTAS_ECOLLECT.FECHA_PAGO,'dd/mm/yyyy') AS FECHA_PAGO",FALSE);	

		$this->db->where('CNM_CUOTAS_ECOLLECT.ID_DEUDA_E',$id_deuda);
		$dato = $this->db->get("CNM_CUOTAS_ECOLLECT");
		//echo $this->db->last_query();
		 return $dato;
	}
	
				function getNovedadesCNM($periodo){
		$this->db->select('CNM_CUOTAS.ID_DEUDA_E AS IDDEUDA, CNM_CUOTAS.NO_CUOTA AS NRO_CTA, CNM_CUOTAS.SALDO_CUOTA AS VLR_CTA, 
		B.CONCEPTO_HOMOLOGADO, CNM_CARTERANOMISIONAL.COD_EMPLEADO');
		$this->db->join('CONCEPTO_HOMOLOGACION A', "A.CONCEPTO_HOMOLOGADO=CNM_CUOTAS.CONCEPTO AND A.TIPO_FUENTE='3'");
		$this->db->join('CONCEPTO_HOMOLOGACION B', "B.COD_CONCEPTO_RECAUDO=A.COD_CONCEPTO_RECAUDO AND B.TIPO_FUENTE='2'");
		$this->db->join("CNM_CARTERANOMISIONAL", "CNM_CUOTAS.ID_DEUDA_E=CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", 'LEFT');
		$this->db->join("CNM_EMPLEADO", "CNM_EMPLEADO.IDENTIFICACION=CNM_CARTERANOMISIONAL.COD_EMPLEADO", 'LEFT');
		$this->db->where('CNM_CUOTAS.MES_PROYECTADO',$periodo);
		$this->db->where('CNM_CUOTAS.MEDIO_INI_PAGO',1);
		$this->db->where('CNM_CARTERANOMISIONAL.COD_ESTADO',2);
		$this->db->where('CNM_CUOTAS.SALDO_CUOTA >',0);
		$this->db->where('CNM_EMPLEADO.COD_ESTADO_E','A');
		$dato = $this->db->get("CNM_CUOTAS");
		return $dato;
	}
	
					function getEmpleadosCNM(){
		$this->db->select('CNM_EMPLEADO.IDENTIFICACION');
		$dato = $this->db->get("CNM_EMPLEADO");
		//echo $this->db->last_query();
		//die();
		return $dato;
	}

					function getConceptoCNM($conceptoKact){
		$this->db->select('B.CONCEPTO_HOMOLOGADO');
		$this->db->join('CONCEPTO_HOMOLOGACION B', "B.COD_CONCEPTO_RECAUDO=A.COD_CONCEPTO_RECAUDO AND B.TIPO_FUENTE='3'");
		$this->db->where('A.CONCEPTO_HOMOLOGADO',$conceptoKact);
		$dato = $this->db->get("CONCEPTO_HOMOLOGACION A");
		//$this->db->join('CONCEPTO_HOMOLOGACION B', "B.COD_CONCEPTO_RECAUDO=A.COD_CONCEPTO_RECAUDO AND B.TIPO_FUENTE='2'");
		//echo $this->db->last_query();
		//die();
		return $dato->result_array;
	}

	
			 		    function updateEstadoCoac($table, $idcartera){

	$query = $this->db->query(" UPDATE ".$table." SET COD_ESTADO=5 WHERE COD_CARTERA_NOMISIONAL='".$idcartera."'");

	if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;       
    }
	
				 		    function updateEstadoJudicial($table, $idcartera){

	$query = $this->db->query(" UPDATE ".$table." SET COD_ESTADO=7 WHERE COD_CARTERA_NOMISIONAL='".$idcartera."'");

	if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;       
    }
	
	 function recepcionTituloNM($idcartera,$ndocumento,$archivos){
	
		$this->db->set('COD_CARTERA_NOMISIONAL', $idcartera);
		$this->db->set('NOMISIONAL', 'S');
        $this->db->set('NIT_EMPRESA', $ndocumento);
        $this->db->set('FECHA_RECEPCION', 'SYSDATE', false);
        $this->db->set('FECHA_CONSULTAONBASE', 'SYSDATE', false);
        $this->db->set('COD_TIPORESPUESTA', 1112);
        $this->db->insert('RECEPCIONTITULOS');
	
        $query = $this->db->query("SELECT RecepcionTitu_cod_recepcio_SEQ.CURRVAL FROM dual");
        $row = $query->row_array();
        $id = $row['CURRVAL'];
		
		$datosarchivo = explode("::", $archivos);
		
		//echo($datosarchivo[0]);
		//die();		
		
		if(!empty($datosarchivo[0])){
		foreach ($datosarchivo as $data){
				
            $this->db->set("COD_RECEPCIONTITULO", $id);
            $this->db->set('NOMBRE_DOCUMENTO', $data);
            $this->db->set('RUTA_ARCHIVO', base_url()."uploads/carteranomisional/".$idcartera."/documentoscartera/".$data);
            $this->db->set('FECHA_CARGA', 'SYSDATE', FALSE);
            $this->db->insert('TITULOS');
        }
	}
}
	function cod_recepcion($id){
        $this->db->select('RECEPCIONTITULOS.COD_RECEPCIONTITULO');
		$this->db->where('RECEPCIONTITULOS.COD_CARTERA_NOMISIONAL',$id);
        $datos =$this->db->get('RECEPCIONTITULOS');
		return $datos = $datos->result_array;
    }
	
	function titulos($id){
        $this->db->select('NOMBRE_DOCUMENTO,RUTA_ARCHIVO');
        $this->db->where('COD_RECEPCIONTITULO',$id);
        $datos =$this->db->get('TITULOS');
        return $datos = $datos->result_array;
    }
    function observaciones($id){
        $this->db->select('OBSERVACIONES');
        $this->db->where('COD_TITULO',$id);
		$this->db->order_by('COD_NOTIFICACIONTITULO', "asc"); 
        $datos =$this->db->get('NOTIFICACION_RECEPCIONTITULOS');
        return $datos = $datos->result_array;
    }
	
	function enviar_correcciones($post){
        $this->db->set('OBSERVACIONES', $post['informacion']);
        $this->db->where('COD_RECEPCIONTITULO',$post['id'] );
        $this->db->set('COD_TIPORESPUESTA',1112 );
        $this->db->update('RECEPCIONTITULOS');
    }
	

	
	function reenvioTitulos($idcartera, $idrecepcion, $archivos){
		
		$this->db->where('COD_RECEPCIONTITULO', $idrecepcion);
		$this->db->delete('TITULOS');
		
		$datosarchivo = explode("::", $archivos);
		//var_dump($datosarchivo);
		//die();		
		foreach ($datosarchivo as $data) {
				
            $this->db->set("COD_RECEPCIONTITULO", $idrecepcion);
            $this->db->set('NOMBRE_DOCUMENTO', $data);
            $this->db->set('RUTA_ARCHIVO', base_url()."uploads/carteranomisional/".$idcartera."/documentoscartera/".$data);
            $this->db->set('FECHA_CARGA', 'SYSDATE', FALSE);
            $this->db->insert('TITULOS');
        }
		
	}
	
	
			function actualizarCuotas ($mesproy){
	$query = $this->db->query("INSERT INTO CNM_CUOTAS_KACTUS(Id_Deuda_K, No_Cuota, Concepto, Valor_Pagado, Interes_Corriente, Amortizacion, Fecha_Pago, Cod_regional)
	SELECT ID_DEUDA_E, NO_CUOTA, CONCEPTO, SALDO_CUOTA, SALDO_INTERES_C, SALDO_AMORTIZACION, SYSDATE, '11' FROM CNM_CUOTAS WHERE MES_PROYECTADO='".$mesproy."' AND SALDO_CUOTA>0");
if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;  
}

			    function addNovedadVal($table,$data,$date=''){
        if ($date!='') {
           foreach ($data as $key => $value) {
		   if(is_numeric($value))
		   {
		   $this->db->set($key, $value, false);
		   }
		   else{
		             $this->db->set($key, $value);
					 }
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
	
	  					function detalleCarterasEdit ($id_cartera, $idtipo){
if($idtipo==11){
$this->db->select('CNM_EMPLEADO.IDENTIFICACION, CNM_EMPLEADO.NOMBRES, CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.DIRECCION, CNM_EMPLEADO.TELEFONO,
CNM_EMPLEADO.TELEFONO_CELULAR, CNM_EMPLEADO.CORREO_ELECTRONICO,  REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA, CNM_FORMAPAGO.FORMA_PAGO,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_CARTERANOMISIONAL.COD_ESTADO, CNM_CARTERANOMISIONAL.COD_TIPO_ACUERDO, CNM_CARTERANOMISIONAL.COD_EMPLEADO, 
CNM_PRESTAMO_AHORRO.COD_MODALIDAD, CNM_PRESTAMO_AHORRO.NUMERO_RESOLUCION, CNM_CARTERANOMISIONAL.VALOR_DEUDA,
CNM_CARTERANOMISIONAL.SALDO_DEUDA, CNM_CARTERANOMISIONAL.PLAZO_CUOTAS, 
CNM_CARTERANOMISIONAL.COD_PLAZO, CNM_CARTERANOMISIONAL.COD_FORMAPAGO, CNM_PRESTAMO_AHORRO.MOD_TASA_CORRIENTE, CNM_PRESTAMO_AHORRO.MOD_TASA_MORA,
CNM_CARTERANOMISIONAL.CALCULO_CORRIENTE, CNM_CARTERANOMISIONAL.TIPO_T_F_CORRIENTE, CNM_CARTERANOMISIONAL.TIPO_T_V_CORRIENTE, CNM_CARTERANOMISIONAL.VALOR_T_CORRIENTE, 
CNM_CARTERANOMISIONAL.CALCULO_MORA, CNM_CARTERANOMISIONAL.TIPO_T_F_MORA, CNM_CARTERANOMISIONAL.TIPO_T_V_MORA, CNM_CARTERANOMISIONAL.VALOR_T_MORA, CNM_PRESTAMO_AHORRO.ID_GARANTIA, CNM_PRESTAMO_AHORRO.DESC_GARANTIA,
CNM_PRESTAMO_AHORRO.DESC_GARANTIA, CNM_PRESTAMO_AHORRO.ID_CODEUDOR, CNM_PRESTAMO_AHORRO.NOMBRE_CODEUDOR');
$this->db->select("to_char(CNM_PRESTAMO_AHORRO.FECHA_SOLICITUD,'dd/mm/yyyy') AS FECHA_SOLICITUD",FALSE);
$this->db->select("to_char(CNM_PRESTAMO_AHORRO.FECHA_RESOLUCION,'dd/mm/yyyy') AS FECHA_RESOLUCION",FALSE);
$this->db->select("to_char(CNM_PRESTAMO_AHORRO.FECHA_ACTIVACION,'dd/mm/yyyy') AS FECHA_ACTIVACION",FALSE);
$this->db->join('CNM_PRESTAMO_AHORRO', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_PRESTAMO_AHORRO.COD_CARTERA");
$this->db->join('CNM_EMPLEADO', "CNM_CARTERANOMISIONAL.COD_EMPLEADO=CNM_EMPLEADO.IDENTIFICACION");
$this->db->join('REGIONAL', "CNM_EMPLEADO.COD_REGIONAL=REGIONAL.COD_REGIONAL");
$this->db->join('ESTADOCARTERA', "CNM_CARTERANOMISIONAL.COD_ESTADO=ESTADOCARTERA.COD_EST_CARTERA");
$this->db->join('CNM_FORMAPAGO', "CNM_CARTERANOMISIONAL.COD_FORMAPAGO=CNM_FORMAPAGO.COD_FORMAPAGO");
$this->db->where("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", $id_cartera);
}
else{
$this->db->select('CNM_EMPLEADO.IDENTIFICACION, CNM_EMPLEADO.NOMBRES, CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.DIRECCION, CNM_EMPLEADO.TELEFONO,
CNM_EMPLEADO.TELEFONO_CELULAR, CNM_EMPLEADO.CORREO_ELECTRONICO,  REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA, CNM_FORMAPAGO.FORMA_PAGO,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_CARTERANOMISIONAL.COD_ESTADO, CNM_CARTERANOMISIONAL.COD_TIPO_ACUERDO, CNM_CARTERANOMISIONAL.COD_EMPLEADO, 
CNM_CARTERA_PRESTAMO_HIPOTEC.COD_MODALIDAD, CNM_CARTERA_PRESTAMO_HIPOTEC.NUMERO_ESCRITURA, CNM_CARTERANOMISIONAL.VALOR_DEUDA, 
CNM_CARTERANOMISIONAL.SALDO_DEUDA, CNM_CARTERANOMISIONAL.PLAZO_CUOTAS, CNM_CARTERANOMISIONAL.COD_PLAZO,
CNM_CARTERANOMISIONAL.COD_FORMAPAGO, CNM_CARTERA_PRESTAMO_HIPOTEC.MOD_TASA_CORRIENTE, CNM_CARTERA_PRESTAMO_HIPOTEC.MOD_TASA_MORA, CNM_CARTERA_PRESTAMO_HIPOTEC.MOD_TASA_SEGURO,
CNM_CARTERANOMISIONAL.CALCULO_CORRIENTE, CNM_CARTERANOMISIONAL.TIPO_T_F_CORRIENTE, CNM_CARTERANOMISIONAL.TIPO_T_V_CORRIENTE, CNM_CARTERANOMISIONAL.VALOR_T_CORRIENTE, 
CNM_CARTERANOMISIONAL.CALCULO_MORA, CNM_CARTERANOMISIONAL.TIPO_T_F_MORA, CNM_CARTERANOMISIONAL.TIPO_T_V_MORA, CNM_CARTERANOMISIONAL.VALOR_T_MORA, 
CNM_CARTERANOMISIONAL.CALCULO_SEGURO, CNM_CARTERANOMISIONAL.TIPO_T_F_SEGURO, CNM_CARTERANOMISIONAL.TIPO_T_V_SEGURO, CNM_CARTERANOMISIONAL.VALOR_T_SEGURO, 
CNM_CARTERA_PRESTAMO_HIPOTEC.ID_GARANTIA, CNM_CARTERA_PRESTAMO_HIPOTEC.DESC_GARANTIA, 
CNM_CARTERA_PRESTAMO_HIPOTEC.DESC_GARANTIA, CNM_CARTERA_PRESTAMO_HIPOTEC.ID_CODEUDOR, CNM_CARTERA_PRESTAMO_HIPOTEC.NOMBRE_CODEUDOR, CNM_CARTERA_PRESTAMO_HIPOTEC.NUM_POLIZA
, CNM_CARTERA_PRESTAMO_HIPOTEC.ASEGURADORA, CNM_CARTERA_PRESTAMO_HIPOTEC.SALARIO, CNM_CARTERA_PRESTAMO_HIPOTEC.FACTOR_TIPO, CNM_CARTERA_PRESTAMO_HIPOTEC.TASA_IPC,
CNM_CARTERA_PRESTAMO_HIPOTEC.TASA_INTERES_CESANTIAS, CNM_CARTERA_PRESTAMO_HIPOTEC.VALOR_CUOTA_APROBADA, CNM_CARTERA_PRESTAMO_HIPOTEC.GRADIENTE, ');
$this->db->select("to_char(CNM_CARTERA_PRESTAMO_HIPOTEC.FECHA_RETIRO,'dd/mm/yyyy') AS FECHA_RETIRO",FALSE);
$this->db->select("to_char(CNM_CARTERA_PRESTAMO_HIPOTEC.FECHA_SOLICITUD,'dd/mm/yyyy') AS FECHA_SOLICITUD",FALSE);
$this->db->select("to_char(CNM_CARTERA_PRESTAMO_HIPOTEC.FECHA_ESCRITURA,'dd/mm/yyyy') AS FECHA_ESCRITURA",FALSE);
$this->db->select("to_char(CNM_CARTERA_PRESTAMO_HIPOTEC.FECHA_ACTIVACION,'dd/mm/yyyy') AS FECHA_ACTIVACION",FALSE);
$this->db->select("to_char(CNM_CARTERANOMISIONAL.FECHA_INCREMENTO,'dd/mm/yyyy') AS FECHA_INCREMENTO",FALSE);
$this->db->join('CNM_CARTERA_PRESTAMO_HIPOTEC', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_PRESTAMO_HIPOTEC.COD_CARTERA");
$this->db->join('CNM_EMPLEADO', "CNM_CARTERANOMISIONAL.COD_EMPLEADO=CNM_EMPLEADO.IDENTIFICACION");
$this->db->join('REGIONAL', "CNM_EMPLEADO.COD_REGIONAL=REGIONAL.COD_REGIONAL");
$this->db->join('ESTADOCARTERA', "CNM_CARTERANOMISIONAL.COD_ESTADO=ESTADOCARTERA.COD_EST_CARTERA");
$this->db->join('CNM_FORMAPAGO', "CNM_CARTERANOMISIONAL.COD_FORMAPAGO=CNM_FORMAPAGO.COD_FORMAPAGO");
$this->db->where("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", $id_cartera);
}


		$dato = $this->db->get("CNM_CARTERANOMISIONAL");
		 return $dato;
	}

		  					function detalleCarterasDobleM($id_cartera){

$this->db->select('CNM_EMPLEADO.IDENTIFICACION, CNM_EMPLEADO.NOMBRES, CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.DIRECCION, CNM_EMPLEADO.TELEFONO,
CNM_EMPLEADO.TELEFONO_CELULAR, CNM_EMPLEADO.CORREO_ELECTRONICO,  REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_CARTERANOMISIONAL.COD_ESTADO, CNM_CARTERA_DOBLE_MESADA.ADJUNTOS, 
CNM_CARTERANOMISIONAL.COD_TIPOCARTERA, CNM_CARTERANOMISIONAL.SALDO_DEUDA');
$this->db->join('CNM_CARTERA_DOBLE_MESADA', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_DOBLE_MESADA.COD_CARTERA");
$this->db->join('CNM_EMPLEADO', "CNM_CARTERANOMISIONAL.COD_EMPLEADO=CNM_EMPLEADO.IDENTIFICACION");
$this->db->join('REGIONAL', "CNM_EMPLEADO.COD_REGIONAL=REGIONAL.COD_REGIONAL");
$this->db->join('ESTADOCARTERA', "CNM_CARTERANOMISIONAL.COD_ESTADO=ESTADOCARTERA.COD_EST_CARTERA");
$this->db->where("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", $id_cartera);

		$dato = $this->db->get("CNM_CARTERANOMISIONAL");
		//echo $this->db->last_query();
		//die();
		 return $dato;
	}
	
						function detalleBeneficiarios ($id_cartera){
$this->db->select('CM_BENEFICIARIOS_DOBLEMESADA.IDENTIFICACION_BENEFICIARIO, CM_BENEFICIARIOS_DOBLEMESADA.NOMBRES, CM_BENEFICIARIOS_DOBLEMESADA.PARENTESCO');
$this->db->where("CM_BENEFICIARIOS_DOBLEMESADA.COD_CARTERAMISIONAL", $id_cartera);
$dato = $this->db->get("CM_BENEFICIARIOS_DOBLEMESADA");
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
	
	function selectTipoEstado(){
$this->db->select('COD_EST_CARTERA, DESC_EST_CARTERA');
$this->db->where("ESTADOCARTERA.CREACION", 'S');
		$dato = $this->db->get("ESTADOCARTERA");
		 return $dato;
	}
	
		function selectCuotasRestantes($id_cartera){
$this->db->select('COUNT(ID_DEUDA_E)');
$this->db->where("CONCEPTO", '8');
$this->db->where("ID_DEUDA_E", $id_cartera);
$this->db->where('VALOR_CUOTA <>', 'SALDO_CUOTA', FALSE);
		$dato = $this->db->get("CNM_CUOTAS");
return $dato->result_array[0];
	}
	
		function selectTipoMotivo($tipo){
$this->db->select('COD_MOTIVO, NOMBRE_MOTIVO');
$this->db->where("TIPO", $tipo);
		$dato = $this->db->get("MOTIVO_RELIQUIDACION");
		return $dato;
	}
	
			function selectIntNoPago($id_cartera){
$this->db->select('SALDO_INTERES_NO_PAGOS');
$this->db->where("CONCEPTO", '8');
$this->db->where('VALOR_CUOTA <>', 'SALDO_CUOTA', FALSE);
$this->db->where("ID_DEUDA_E", $id_cartera);
$this->db->order_by("NO_CUOTA DESC");
		$dato = $this->db->get("CNM_CUOTAS");
		//echo $this->db->last_query();
		//die();
		return $dato->result_array;
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
	
	
	
	function selectTasaEspCombo(){

$this->db->select('COD_TIPO_TASAINTERES AS COD_TASA, NOMBRE_TASAINTERES AS NOMBRE_TASA');
$this->db->where("TIPOTASAINTERES.ELIMINADO IS NULL", NULL, FALSE); 
		$dato = $this->db->get("TIPOTASAINTERES");
		
		return $dato;
	}
	
						function selectAcuerdo($idAcuerdo){
$this->db->select('COD_ACUERDOLEY, NOMBRE_ACUERDO');
$this->db->where('COD_TIPOCARTERA',$idAcuerdo);
		$dato = $this->db->get("NM_ACUERDOLEY");
		//echo $this->db->last_query();
		 return $dato;
	}
	
	function selectPeriodoLiq (){
$this->db->select('COD_PERIODOLIQUIDACION, NOMBRE_PERIODOLIQUIDACION');
        $this->db->where("COD_ESTADO", '1');
		$this->db->where("COD_PERIODOLIQUIDACION", '1');
		$dato = $this->db->get("PERIODOLIQUIDACION");
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
						    function updateCartera($table,$data,$date='',$id_deuda, $identificador){
        if ($date!='') {
           foreach ($data as $key => $value) {
                     		   if(is_numeric($value))
		   {
		   $this->db->set($key, $value, false);
	
	}
		   else{
		             $this->db->set($key, $value);
					 }
                 }
            foreach ($date as $keyf => $valuef) {
                     $this->db->set($keyf,"to_date('".$valuef."','dd/mm/yyyy hh24:mi:ss')",false);
                 }         
			$this->db->where('"'.$identificador.'"', $id_deuda);
            $this->db->update($table);
        }
		
		else{
		           foreach ($data as $key => $value) {
                     		   if(is_numeric($value))
		   {
		   $this->db->set($key, $value, false);
		   }
		   else{
		             $this->db->set($key, $value);
					 }
                 }
		
				$this->db->where('"'.$identificador.'"', $id_deuda);
            $this->db->update($table);
			//echo $this->db->last_query();
			//die();
        }
        if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
		
		return FALSE;       
    }
	
					function detalleFechaAct ($tabla_cart, $id_deuda){
					$this->db->select("to_char(FECHA_ACTIVACION,'dd/mm/yyyy') AS FECHA_ACTIVACION",FALSE);	
					$this->db->select('FORMA_PAGO');
					$this->db->where("COD_CARTERA", $id_deuda);
					$dato = $this->db->get($tabla_cart);
					return $dato->result_array[0];
	}
	
}