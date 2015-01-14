<?php
class Fiscalizacion_model extends CI_Model {

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
    
    function addnotivisita($table,$data, $date){

        $this->db->set('COD_GESTION_COBRO', $data['COD_GESTION_COBRO']);
        $this->db->set('NOMBRE_ARCHIVO_NOTIFICACION', $data['NOMBRE_ARCHIVO_NOTIFICACION']);
        $this->db->set('FECHA_COMUNICADO',"to_date('".$date['FECHA_COMUNICADO']."','dd/mm/yyyy')",false);
		$this->db->set('FECHA_VISITA',"to_date('".$date['FECHA_VISITA']."','dd/mm/yyyy')",false);
		$this->db->set('HORA_VISITA_INICIO',"to_date('".$date['HORA_VISITA_INICIO']."','dd/mm/yyyy hh24:mi')",false);
		$this->db->set('HORA_VISITA_FIN',"to_date('".$date['HORA_VISITA_FIN']."','dd/mm/yyyy hh24:mi')",false);
        $this->db->insert($table);
        if ($this->db->affected_rows() == '1')
        {
            return TRUE;
        }
        
        return FALSE;
    }
	
	    function addcomunicacionsena($table,$data){

        $this->db->set('COD_MOTIVO_CITACION', $data['COD_MOTIVO_CITACION']);
        $this->db->set('COD_GESTION_COBRO', $data['COD_GESTION_COBRO']);
		$this->db->set('NIT_EMPRESA', $data['NIT_EMPRESA']);
        $this->db->set('PROYECTO', $data['PROYECTO']);
		$this->db->set('COORDINADOR', $data['COORDINADOR']);
        $this->db->set('NOMBRE_DOCUMENTO', $data['NOMBRE_DOCUMENTO']);

        $this->db->insert($table);
        if ($this->db->affected_rows() == '1')
        {
            return TRUE;
        }
        
        return FALSE;
    }
		    function addactagubernativa($table,$date,$data){

        $this->db->set('FECHA_ENVIO_ACTA', "to_date('".$date['FECHA_ENVIO_ACTA']."','dd/mm/yyyy')",false);
        $this->db->set('COD_INFORME_VISITA', $data['COD_INFORME_VISITA']);
		$this->db->set('COD_GESTION_COBRO', $data['COD_GESTION_COBRO']);
        $this->db->set('DOCUMENTOS_SOPORTE', $data['DOCUMENTOS_SOPORTE']);

        $this->db->insert($table);
        if ($this->db->affected_rows() == '1')
        {
            return TRUE;
        }
        
        return FALSE;
    }
	
	function addinformevisita($data){

        $this->db->set('COD_NOTIFICACION_VISITA', $data['COD_NOTIFICACION_VISITA']);
        $this->db->set('COD_GESTION_COBRO', $data['COD_GESTION_COBRO']);
		$this->db->set('FECHA_DOCUMENTO',"to_date('".$data['FECHA_DOCUMENTO']."','dd/mm/yyyy')",false);
        $this->db->set('NOMBRE_DOCUMENTO', $data['NOMBRE_DOCUMENTO']);


        $this->db->insert('INFORMEVISITA');
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
	
	function addgestion($table,$data,$date=''){
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
	
	    function addFiscalizacion($table,$data,$date=''){
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
	
	
	 function addsoportevalidacion($table,$data,$date=''){
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
	
	 function autorizacionemail($table,$data,$date=''){
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
    
    function edit($table,$data,$fieldID,$ID){
        $this->db->where($fieldID,$ID);
        $this->db->update($table, $data);

        if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;       
    }
    
    function delete($table,$fieldID,$ID){
        $this->db->where($fieldID,$ID);
        $this->db->delete($table);
        if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
		
		return FALSE;        
    }   
	
	function count($table){
		return $this->db->count_all($table);
	}
     

     function max($table,$field){
        $this->db->select_max($field);
        $query = $this->db->get($table);
        if($query->num_rows() > 0) {
             return $query->row_array(); //return the row as an associative array
        }
        return  FALSE; 
    }

   function getSelect($table,$fields,$where=''){
        $query = $this->db->query(" SELECT ".$fields."  FROM ".$table." ".$where." ");
        return $query->result();
    }
	
	
   function getSelectConceptos($table,$fields){
        $query = $this->db->query(" SELECT ".$fields."  FROM ".$table." WHERE COD_CPTO_FISCALIZACION <='2' ");
        return $query->result();
    }
	
	   function getSelectConceptosConsulta($table,$fields){
        $query = $this->db->query(" SELECT ".$fields."  FROM ".$table." WHERE COD_CPTO_FISCALIZACION <='3' ");
        return $query->result();
    }

function selectId ($table,$data,$date){
$this->db->select('COD_GESTION_COBRO');
        $this->db->where("COD_FISCALIZACION_EMPRESA", $data['COD_FISCALIZACION_EMPRESA']);
        $this->db->where("NIT_EMPRESA", $data['NIT_EMPRESA']);
        $this->db->where("FECHA_CONTACTO", 'to_date(\''.$date['FECHA_CONTACTO'].'\',\'dd-mm-yyyy\')', FALSE);
        $this->db->where("COMENTARIOS", $data['COMENTARIOS']);
        $this->db->where("COD_TIPOGESTION", $data['COD_TIPOGESTION']);
		$dato = $this->db->get($table);
		 $id_idgestioncobro=$dato->result_array[0];
return $id_idgestioncobro;
	}
	
	
function selectIdGestion ($table,$data,$date){
$this->db->select('COD_GESTION_COBRO');
        $this->db->where("COD_FISCALIZACION_EMPRESA", $data['COD_FISCALIZACION_EMPRESA']);
        $this->db->where("NIT_EMPRESA", $data['NIT_EMPRESA']);
        $this->db->where("FECHA_CONTACTO", 'to_date(\''.$date['FECHA_CONTACTO'].'\',\'dd-mm-yyyy hh24:mi\')', FALSE);
        $this->db->where("COMENTARIOS", $data['COMENTARIOS']);
        $this->db->where("COD_TIPOGESTION", $data['COD_TIPOGESTION']);
		$dato = $this->db->get($table);
		 $id_idgestioncobro=$dato->result_array[0];
return $id_idgestioncobro;
	}
	
	function selectIdInfVisita ($table,$codfisc){
$this->db->select('INFORMEVISITA.COD_INFORME_VISITA');
        $this->db->join('GESTIONCOBRO', 'INFORMEVISITA.COD_GESTION_COBRO=GESTIONCOBRO.COD_GESTION_COBRO');
        $this->db->join('FISCALIZACION', 'GESTIONCOBRO.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION');
        $this->db->where("FISCALIZACION.COD_FISCALIZACION", $codfisc);
        $this->db->where("GESTIONCOBRO.COD_TIPO_RESPUESTA", '7');
		$dato = $this->db->get($table);
		 $id_inf_v=$dato->result_array[0];
return $id_inf_v;
	}
	
	function selectAgenda ($ini,$fin,$usuario){
		$this->db->select('EMPRESA.NOMBRE_EMPRESA,EMPRESA.DIRECCION,EMPRESA.TELEFONO_FIJO');
		$this->db->select("to_char(NOTIFICACIONVISITA.FECHA_VISITA,'dd/mm/yyyy') AS FECHA_VISITA",FALSE);
		$this->db->select("to_char(NOTIFICACIONVISITA.HORA_VISITA_INICIO,'hh24:mi') AS HORA_INICIO",FALSE);
		$this->db->select("to_char(NOTIFICACIONVISITA.HORA_VISITA_FIN,'hh24:mi') AS HORA_FIN",FALSE);
		$this->db->join('GESTIONCOBRO', 'NOTIFICACIONVISITA.COD_GESTION_COBRO=GESTIONCOBRO.COD_GESTION_COBRO');
		$this->db->join('FISCALIZACION', 'GESTIONCOBRO.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION');
		$this->db->join('ASIGNACIONFISCALIZACION', 'FISCALIZACION.COD_ASIGNACION_FISC=ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION');
		$this->db->join('EMPRESA', 'ASIGNACIONFISCALIZACION.NIT_EMPRESA=EMPRESA.CODEMPRESA');
		$this->db->where("ASIGNACIONFISCALIZACION.ASIGNADO_A", $usuario);
		$this->db->where("NOTIFICACIONVISITA.FECHA_VISITA BETWEEN'".$ini."' AND '".$fin."'", NULL, FALSE);
		

		$datos['aaData'] = $this->db->get('NOTIFICACIONVISITA');
		//echo $this->db->last_query();
		$datos['aaData'] = $datos['aaData']->result_array;
		if (!empty($datos['aaData'])) {
			$datos['ok'] = true;
			$tmp = array();
			foreach ($datos['aaData'] as $data) {
				$fechaexplode=explode("/", $data['FECHA_VISITA']);
				//var_dump($fechaexplode);
				//die();
				$horaexplode=explode(":", $data['HORA_INICIO']);
				$fechacontacto=$fechaexplode[2].$fechaexplode[1].$fechaexplode[0].$horaexplode[0].$horaexplode[1];

				$fechadia=$fechaexplode[2]."-".$fechaexplode[1]."-".$fechaexplode[0];
				$dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','','');
				$fecha = $dias[date('N', strtotime($fechadia))];
				$tmp[] = array(
					$fechacontacto,
					$fecha,
					$data['FECHA_VISITA'],
					$data['HORA_INICIO'],
					$data['HORA_FIN'],
					$data['NOMBRE_EMPRESA'],
					$data['DIRECCION'],
					$data['TELEFONO_FIJO']
				);
			}
			$datos['aaData'] = $tmp;
		} else $datos['ok'] = false;
		return $datos;
	}
	
	function selectVerAgenda ($ini,$fin,$usuario){
		$this->db->select('EMPRESA.NOMBRE_EMPRESA,EMPRESA.DIRECCION,EMPRESA.TELEFONO_FIJO');
		$this->db->select("to_char(NOTIFICACIONVISITA.FECHA_VISITA,'dd/mm/yyyy') AS FECHA_VISITA",FALSE);
		$this->db->select("to_char(NOTIFICACIONVISITA.HORA_VISITA_INICIO,'hh24:mi') AS HORA_INICIO",FALSE);
		$this->db->select("to_char(NOTIFICACIONVISITA.HORA_VISITA_FIN,'hh24:mi') AS HORA_FIN",FALSE);
		$this->db->join('GESTIONCOBRO', 'NOTIFICACIONVISITA.COD_GESTION_COBRO=GESTIONCOBRO.COD_GESTION_COBRO');
		$this->db->join('FISCALIZACION', 'GESTIONCOBRO.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION');
		$this->db->join('ASIGNACIONFISCALIZACION', 'FISCALIZACION.COD_ASIGNACION_FISC=ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION');
		$this->db->join('EMPRESA', 'ASIGNACIONFISCALIZACION.NIT_EMPRESA=EMPRESA.CODEMPRESA');
		$this->db->where("ASIGNACIONFISCALIZACION.ASIGNADO_A", $usuario);
		$this->db->where("NOTIFICACIONVISITA.FECHA_VISITA BETWEEN TO_DATE('".$ini."', 'dd/mm/yyyy') AND TO_DATE('".$fin."', 'dd/mm/yyyy')", NULL, FALSE);
		$datos = $this->db->get('NOTIFICACIONVISITA');
		//echo $this->db->last_query();
		return $datos;
		
	}
	
	
		function selectListarFiscalizadores ($regional, $idcargo){
$this->db->select('USUARIOS.IDUSUARIO, USUARIOS.NOMBREUSUARIO');
        $this->db->where("USUARIOS.IDCARGO", $idcargo);
        $this->db->where("USUARIOS.COD_REGIONAL", $regional);
		$query = $this->db->get('USUARIOS');
return $query->result();
	}
	

	
	function selectIdFisc ($table,$data,$date){
$this->db->select('COD_FISCALIZACION');

        $this->db->where("COD_CONCEPTO", $data['COD_CONCEPTO']);
        $this->db->where("COD_TIPOGESTION", $data['COD_TIPOGESTION']);
        $this->db->where("PERIODO_INICIAL", 'to_date(\''.$date['PERIODO_INICIAL'].'\',\'dd-mm-yyyy\')', FALSE);
        $this->db->where("PERIODO_FINAL", 'to_date(\''.$date['PERIODO_FINAL'].'\',\'dd-mm-yyyy\')', FALSE);
        $this->db->where("COD_ASIGNACION_FISC", $data['COD_ASIGNACION_FISC']);
		$dato = $this->db->get($table);
		 $id_idgestioncobro=$dato->result_array[0];
return $id_idgestioncobro;
	}
	
		function selectIdFiscConsec ($table,$data,$date){
$this->db->select('FISCALIZACION.COD_FISCALIZACION, EMPRESA.COD_REGIONAL, FISCALIZACION.COD_CONCEPTO, USUARIOS.IDENTIFICADOR');
$this->db->join('ASIGNACIONFISCALIZACION', 'ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC');
		$this->db->join('EMPRESA', 'ASIGNACIONFISCALIZACION.NIT_EMPRESA=EMPRESA.CODEMPRESA');
		$this->db->join('USUARIOS', 'USUARIOS.IDUSUARIO=ASIGNACIONFISCALIZACION.ASIGNADO_A');
        $this->db->where("FISCALIZACION.COD_CONCEPTO", $data['COD_CONCEPTO']);
        $this->db->where("FISCALIZACION.COD_TIPOGESTION", $data['COD_TIPOGESTION']);
        $this->db->where("FISCALIZACION.PERIODO_INICIAL", 'to_date(\''.$date['PERIODO_INICIAL'].'\',\'dd-mm-yyyy\')', FALSE);
        $this->db->where("FISCALIZACION.PERIODO_FINAL", 'to_date(\''.$date['PERIODO_FINAL'].'\',\'dd-mm-yyyy\')', FALSE);
        $this->db->where("FISCALIZACION.COD_ASIGNACION_FISC", $data['COD_ASIGNACION_FISC']);
		$dato = $this->db->get($table);
 $id_idgestioncobro=$dato->result_array[0];
		 
return $id_idgestioncobro;
	}
	
	function selectIdNotiVisita ($idgestion){
$this->db->select('NOTIFICACIONVISITA.COD_NOTIFICACION_VISITA');
        $this->db->where("NOTIFICACIONVISITA.COD_GESTION_COBRO", $idgestion);
		$dato = $this->db->get('NOTIFICACIONVISITA');
		 $id_idgestioncobro=$dato->result_array[0];
return $id_idgestioncobro;
	}
	
		function selectNitxCodasign ($idasignacion){
$this->db->select('ASIGNACIONFISCALIZACION.NIT_EMPRESA');
        $this->db->where("ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION", $idasignacion);
		$dato = $this->db->get('ASIGNACIONFISCALIZACION');
		 $id_idgestioncobro=$dato->result_array[0];
return $id_idgestioncobro;
	}
	
			function selectCodasignxCodFisc ($idfiscalizacion){
$this->db->select('FISCALIZACION.COD_ASIGNACION_FISC');
        $this->db->where("FISCALIZACION.COD_FISCALIZACION", $idfiscalizacion);
		$dato = $this->db->get('FISCALIZACION');
		 $id_idgestioncobro=$dato->result_array[0];
return $id_idgestioncobro;
	}
	
function selectFiscalizador ($codFisc){
$this->db->select('ASIGNACIONFISCALIZACION.ASIGNADO_A');
		$this->db->join('FISCALIZACION', 'ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC');
        $this->db->where("FISCALIZACION.COD_FISCALIZACION", $codFisc);
		$dato = $this->db->get('ASIGNACIONFISCALIZACION');
		 $id_fiscalizador=$dato->result_array[0];
return $id_fiscalizador;
	}
	
	function selectCoordinador ($nit){
$this->db->select('USUARIOS.IDUSUARIO');
		$this->db->join('EMPRESA', 'USUARIOS.COD_REGIONAL=EMPRESA.COD_REGIONAL');
        $this->db->where("EMPRESA.CODEMPRESA", $nit);
		$dato = $this->db->get('USUARIOS');
		 $id_coordinador=$dato->result_array[0];
return $id_coordinador;
	}
	
	

function selectcod_fiscnit ($cod_gestion){
$this->db->select('GESTIONCOBRO.COD_FISCALIZACION_EMPRESA, GESTIONCOBRO.NIT_EMPRESA');
        $this->db->where("GESTIONCOBRO.COD_GESTION_COBRO", $cod_gestion);
		$dato = $this->db->get('GESTIONCOBRO');
		 $id_idgestioncobro=$dato->result_array[0];
return $id_idgestioncobro;
	}	
	

function select_datos_visita ($cod_fisc){
		$this->db->select("to_char(NOTIFICACIONVISITA.HORA_VISITA_INICIO,'dd/mm/yyyy hh24:mi') AS HORA_INICIO",FALSE);
$this->db->select('GESTIONCOBRO.NRO_RADICADO_ONBASE');
        $this->db->join('GESTIONCOBRO', 'NOTIFICACIONVISITA.COD_GESTION_COBRO=GESTIONCOBRO.COD_GESTION_COBRO');
		$this->db->where("GESTIONCOBRO.COD_FISCALIZACION_EMPRESA", $cod_fisc);
		$dato = $this->db->get('NOTIFICACIONVISITA');
		 $id_idgestioncobro=$dato->result_array[0];
return $id_idgestioncobro;
	}		
	
	
	function selectEmpresa ($nit){
$this->db->select('CODEMPRESA,NOMBRE_EMPRESA, DIRECCION, TELEFONO_CELULAR, TELEFONO_FIJO, REPRESENTANTE_LEGAL, ACTIVIDADECONOMICA, NUM_EMPLEADOS, CORREOELECTRONICO');

        $this->db->where("CODEMPRESA", $nit);
		$dato = $this->db->get("EMPRESA");
		 return $dato->result_array[0];

	}
	
		function selectAsignado ($cod_asign){
$this->db->select('ASIGNACIONFISCALIZACION.ASIGNADO_POR,ASIGNACIONFISCALIZACION.ASIGNADO_A');

        $this->db->where("ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION", $cod_asign);
		$dato = $this->db->get("ASIGNACIONFISCALIZACION");
		 return $dato->result_array[0];

	}
	
	
			function infoRegional ($codRegional){
$this->db->select('REGIONAL.NOMBRE_REGIONAL,REGIONAL.DIRECCION_REGIONAL, REGIONAL.TELEFONO_REGIONAL');

        $this->db->where("REGIONAL.COD_REGIONAL", $codRegional);
		$dato = $this->db->get("REGIONAL");
		 return $dato->result_array[0];

	}
	
	
	function selectAsignadoxCodfisc ($codFisc){
$this->db->select('ASIGNACIONFISCALIZACION.ASIGNADO_POR,ASIGNACIONFISCALIZACION.ASIGNADO_A, FISCALIZACION.COD_TIPOGESTION');
$this->db->join('FISCALIZACION', 'FISCALIZACION.COD_ASIGNACION_FISC=ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION');
        $this->db->where("FISCALIZACION.COD_FISCALIZACION", $codFisc);
		$dato = $this->db->get("ASIGNACIONFISCALIZACION");
		 return $dato->result_array[0];

	}
	
	function selectDatosComOfi($cod_gest){
	$this->db->select('MUNICIPIO.NOMBREMUNICIPIO AS CIUDAD_EMPRESA,USUARIOS.NOMBREUSUARIO AS NOMBRE_COORDINADOR, USUARIOS.NOMBRES, USUARIOS.APELLIDOS, USUARIOS.COD_REGIONAL');
$this->db->join('MUNICIPIO', 'EMPRESA.COD_MUNICIPIO=MUNICIPIO.CODMUNICIPIO AND EMPRESA.COD_DEPARTAMENTO=MUNICIPIO.COD_DEPARTAMENTO');
$this->db->join('GESTIONCOBRO', 'EMPRESA.CODEMPRESA=GESTIONCOBRO.NIT_EMPRESA');
$this->db->join('REGIONAL', 'EMPRESA.COD_REGIONAL=REGIONAL.COD_REGIONAL');
$this->db->join('USUARIOS', 'EMPRESA.COD_REGIONAL=USUARIOS.COD_REGIONAL');
$this->db->join('FISCALIZACION', 'FISCALIZACION.COD_FISCALIZACION=GESTIONCOBRO.COD_FISCALIZACION_EMPRESA');
$this->db->join('ASIGNACIONFISCALIZACION', 'FISCALIZACION.COD_ASIGNACION_FISC=ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION');
$this->db->where("GESTIONCOBRO.COD_GESTION_COBRO", $cod_gest);	
$this->db->where("USUARIOS.IDCARGO", '3');	
$dato = $this->db->get("EMPRESA");
return $dato->result_array[0];
}


	
	function selectDatosComOfi2($cod_gest){
	$this->db->select('MUNICIPIO.NOMBREMUNICIPIO AS CIUDAD_REGIONAL, USUARIOS.NOMBREUSUARIO AS NOMBRE_FISCALIZADOR, 
	REGIONAL.NOMBRE_REGIONAL, USUARIOS.EMAIL, USUARIOS.TELEFONO, USUARIOS.EXTENSION,EMPRESA.REPRESENTANTE_LEGAL, 
	EMPRESA.NOMBRE_EMPRESA, EMPRESA.CODEMPRESA, EMPRESA.DIRECCION, USUARIOS.NOMBRES, USUARIOS.APELLIDOS');
$this->db->join('REGIONAL', 'EMPRESA.COD_REGIONAL=REGIONAL.COD_REGIONAL');
$this->db->join('MUNICIPIO', 'REGIONAL.COD_CIUDAD=MUNICIPIO.CODMUNICIPIO AND REGIONAL.COD_DEPARTAMENTO=MUNICIPIO.COD_DEPARTAMENTO');
$this->db->join('GESTIONCOBRO', 'EMPRESA.CODEMPRESA=GESTIONCOBRO.NIT_EMPRESA');
$this->db->join('FISCALIZACION', 'FISCALIZACION.COD_FISCALIZACION=GESTIONCOBRO.COD_FISCALIZACION_EMPRESA');
$this->db->join('ASIGNACIONFISCALIZACION', 'FISCALIZACION.COD_ASIGNACION_FISC=ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION');
$this->db->join('USUARIOS', 'ASIGNACIONFISCALIZACION.ASIGNADO_A=USUARIOS.IDUSUARIO');
$this->db->where("GESTIONCOBRO.COD_GESTION_COBRO", $cod_gest);	

$dato = $this->db->get("EMPRESA");
return $dato->result_array[0];
}

	function selectInformeV($cod_fisc){
	$this->db->select('MUNICIPIO.NOMBREMUNICIPIO AS CIUDAD_EMPRESA, USUARIOS.EMAIL, USUARIOS.TELEFONO, USUARIOS.EXTENSION,USUARIOS.NOMBRES, USUARIOS.APELLIDOS,
	EMPRESA.REPRESENTANTE_LEGAL, EMPRESA.NOMBRE_EMPRESA, EMPRESA.CODEMPRESA, EMPRESA.DIRECCION, EMPRESA.ACTIVIDADECONOMICA,
	EMPRESA.CIIU, EMPRESA.FAX, EMPRESA.CORREOELECTRONICO, EMPRESA.TELEFONO_FIJO, EMPRESA.TELEFONO_CELULAR');
$this->db->join('ASIGNACIONFISCALIZACION', 'FISCALIZACION.COD_ASIGNACION_FISC=ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION');
$this->db->join('USUARIOS', 'ASIGNACIONFISCALIZACION.ASIGNADO_A=USUARIOS.IDUSUARIO');
$this->db->join('EMPRESA', 'ASIGNACIONFISCALIZACION.NIT_EMPRESA=EMPRESA.CODEMPRESA');
$this->db->join('MUNICIPIO', 'EMPRESA.COD_MUNICIPIO=MUNICIPIO.CODMUNICIPIO AND EMPRESA.COD_DEPARTAMENTO=MUNICIPIO.COD_DEPARTAMENTO');
$this->db->where("FISCALIZACION.COD_FISCALIZACION", $cod_fisc);	
$dato = $this->db->get("FISCALIZACION");
return $dato->result_array[0];
}

	function selectDatosComPrSena($cod_fisc){
	$this->db->select('MUNICIPIO.NOMBREMUNICIPIO AS CIUDAD_EMPRESA,USUARIOS.NOMBREUSUARIO AS NOMBRE_COORDINADOR, USUARIOS.NOMBRES, USUARIOS.APELLIDOS');
$this->db->join('ASIGNACIONFISCALIZACION', 'FISCALIZACION.COD_ASIGNACION_FISC=ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION');
$this->db->join('EMPRESA', 'ASIGNACIONFISCALIZACION.NIT_EMPRESA=EMPRESA.CODEMPRESA');
$this->db->join('MUNICIPIO', 'EMPRESA.COD_MUNICIPIO=MUNICIPIO.CODMUNICIPIO AND EMPRESA.COD_DEPARTAMENTO=MUNICIPIO.COD_DEPARTAMENTO');
$this->db->join('REGIONAL', 'EMPRESA.COD_REGIONAL=REGIONAL.COD_REGIONAL');
$this->db->join('USUARIOS', 'REGIONAL.COD_REGIONAL=USUARIOS.COD_REGIONAL');
$this->db->where("USUARIOS.IDCARGO", '3');	
$this->db->where("FISCALIZACION.COD_FISCALIZACION", $cod_fisc);	
$dato = $this->db->get("FISCALIZACION");
return $dato->result_array[0];
	
}
function selectDatosComPrSena2($cod_fisc){
	$this->db->select('MUNICIPIO.NOMBREMUNICIPIO AS CIUDAD_REGIONAL, USUARIOS.NOMBREUSUARIO AS NOMBRE_FISCALIZADOR, 
	REGIONAL.NOMBRE_REGIONAL, USUARIOS.EMAIL, USUARIOS.TELEFONO, USUARIOS.EXTENSION,EMPRESA.REPRESENTANTE_LEGAL, 
	EMPRESA.NOMBRE_EMPRESA, EMPRESA.CODEMPRESA, EMPRESA.DIRECCION, USUARIOS.NOMBRES, USUARIOS.APELLIDOS, REGIONAL.NOMBRE_REGIONAL, REGIONAL.COD_REGIONAL');
$this->db->join('ASIGNACIONFISCALIZACION', 'FISCALIZACION.COD_ASIGNACION_FISC=ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION');
$this->db->join('EMPRESA', 'ASIGNACIONFISCALIZACION.NIT_EMPRESA=EMPRESA.CODEMPRESA');
$this->db->join('USUARIOS', 'ASIGNACIONFISCALIZACION.ASIGNADO_A=USUARIOS.IDUSUARIO');
$this->db->join('REGIONAL', 'USUARIOS.COD_REGIONAL=REGIONAL.COD_REGIONAL');
$this->db->join('MUNICIPIO', 'REGIONAL.COD_CIUDAD=MUNICIPIO.CODMUNICIPIO AND REGIONAL.COD_DEPARTAMENTO=MUNICIPIO.COD_DEPARTAMENTO');
$this->db->where("FISCALIZACION.COD_FISCALIZACION", $cod_fisc);	
$dato = $this->db->get("FISCALIZACION");
return $dato->result_array[0];
	
}

		function selectNotiVisita ($codgestion){
$this->db->select('NOTIFICACIONVISITA.NOMBRE_ARCHIVO_NOTIFICACION,GESTIONCOBRO.COD_FISCALIZACION_EMPRESA');
$this->db->join('GESTIONCOBRO', 'NOTIFICACIONVISITA.COD_GESTION_COBRO=GESTIONCOBRO.COD_GESTION_COBRO');
        $this->db->where("NOTIFICACIONVISITA.COD_GESTION_COBRO", $codgestion);
		$dato = $this->db->get("NOTIFICACIONVISITA");
		 return $dato->result_array[0];

	}

			function selectArchivo ($codgestion){
$this->db->select('GESTIONCOBRO.DOCUMENTO');
        $this->db->where("GESTIONCOBRO.COD_GESTION_COBRO", $codgestion);
		$dato = $this->db->get("GESTIONCOBRO");
		 return $dato->result_array[0];

	}
	
		function selectComSena ($codgestion){
$this->db->select('CITACIONEMPRESA.NOMBRE_DOCUMENTO,GESTIONCOBRO.COD_FISCALIZACION_EMPRESA');
$this->db->join('GESTIONCOBRO', 'CITACIONEMPRESA.COD_GESTION_COBRO=GESTIONCOBRO.COD_GESTION_COBRO');
        $this->db->where("CITACIONEMPRESA.COD_GESTION_COBRO", $codgestion);
		$dato = $this->db->get("CITACIONEMPRESA");
		 return $dato->result_array[0];

	}	
	
			function selectDocActa ($codgestion){
$this->db->select('CITACIONEMPRESA.NOMBRE_DOCUMENTO,GESTIONCOBRO.COD_FISCALIZACION_EMPRESA');
$this->db->join('GESTIONCOBRO', 'CITACIONEMPRESA.COD_GESTION_COBRO=GESTIONCOBRO.COD_GESTION_COBRO');
        $this->db->where("CITACIONEMPRESA.COD_GESTION_COBRO", $codgestion);
		$dato = $this->db->get("CITACIONEMPRESA");
		 return $dato->result_array[0];

	}	
			function selectInfVisita ($codgestion){
$this->db->select('INFORMEVISITA.NOMBRE_DOCUMENTO,GESTIONCOBRO.COD_FISCALIZACION_EMPRESA');
$this->db->join('GESTIONCOBRO', 'INFORMEVISITA.COD_GESTION_COBRO=GESTIONCOBRO.COD_GESTION_COBRO');
        $this->db->where("INFORMEVISITA.COD_GESTION_COBRO", $codgestion);
		$dato = $this->db->get("INFORMEVISITA");
		 return $dato->result_array[0];

	}	

	function selectSopPagoIngreso ($codgestion){
$this->db->select('SOPORTESGESTIONCOBRO.NOMBRE_ARCHIVO,GESTIONCOBRO.COD_FISCALIZACION_EMPRESA');
$this->db->join('GESTIONCOBRO', 'SOPORTESGESTIONCOBRO.COD_GESTION_COBRO=GESTIONCOBRO.COD_GESTION_COBRO');
        $this->db->where("SOPORTESGESTIONCOBRO.COD_GESTION_COBRO", $codgestion);
		$dato = $this->db->get("SOPORTESGESTIONCOBRO");
		 return $dato->result_array[0];

	}	
	
	function selectSopPago ($codgestion){
$this->db->select('SOPORTESGESTIONCOBRO.NOMBRE_ARCHIVO,GESTIONCOBRO.COD_FISCALIZACION_EMPRESA');
$this->db->join('GESTIONCOBRO', 'SOPORTESGESTIONCOBRO.COD_GESTIONCOBRO_RESP=GESTIONCOBRO.COD_GESTION_COBRO');
        $this->db->where("SOPORTESGESTIONCOBRO.COD_GESTIONCOBRO_RESP", $codgestion);
		$dato = $this->db->get("SOPORTESGESTIONCOBRO");
		 return $dato->result_array[0];

	}	
			function selectActaGub ($codgestion){
$this->db->select('PROCESOGUBERNATIVO.DOCUMENTOS_SOPORTE,GESTIONCOBRO.COD_FISCALIZACION_EMPRESA');
$this->db->join('GESTIONCOBRO', 'PROCESOGUBERNATIVO.COD_GESTION_COBRO=GESTIONCOBRO.COD_GESTION_COBRO');
        $this->db->where("PROCESOGUBERNATIVO.COD_GESTION_COBRO", $codgestion);
		$dato = $this->db->get("PROCESOGUBERNATIVO");
		 return $dato->result_array[0];

	}		
	
		function selectAprobacion ($cod_gestion){
$this->db->select('SOPORTESGESTIONCOBRO.COD_SOPORTE_GESTION,SOPORTESGESTIONCOBRO.NOMBRE_ARCHIVO,SOPORTESGESTIONCOBRO.COMENTARIOS,FISCALIZACION.COD_CONCEPTO,FISCALIZACION.COD_FISCALIZACION,GESTIONCOBRO.COD_GESTION_COBRO');
$this->db->select("to_char(FECHA_DOCUMENTO,'dd/mm/yyyy hh24:mi') AS FECHA",FALSE);
$this->db->join('GESTIONCOBRO', 'SOPORTESGESTIONCOBRO.COD_GESTION_COBRO=GESTIONCOBRO.COD_GESTION_COBRO');
$this->db->join('FISCALIZACION', 'GESTIONCOBRO.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION');
        $this->db->where("SOPORTESGESTIONCOBRO.COD_GESTION_COBRO", $cod_gestion);
		$dato = $this->db->get("SOPORTESGESTIONCOBRO");
		 return $dato->result_array[0];

	}
	
			function selectEmpresaFisc ($usuario){
$this->db->select('EMPRESA.CODEMPRESA,EMPRESA.NOMBRE_EMPRESA, ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION');
$this->db->select("to_char(ASIGNACIONFISCALIZACION.FECHA_ASIGNACION,'dd/mm/yyyy') AS FECHA_ASIGNACION",FALSE);
$this->db->join('ASIGNACIONFISCALIZACION', 'EMPRESA.CODEMPRESA=ASIGNACIONFISCALIZACION.NIT_EMPRESA');
$this->db->join('USUARIOS', 'ASIGNACIONFISCALIZACION.ASIGNADO_A=USUARIOS.IDUSUARIO');
 $this->db->where("USUARIOS.IDUSUARIO", $usuario);
		$dato = $this->db->get("EMPRESA");
		 return $dato;

	}
	
				function selectEmpresaFiscporNit ($nit){
$this->db->select('EMPRESA.CODEMPRESA, EMPRESA.NOMBRE_EMPRESA, USUARIOS.NOMBREUSUARIO, ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION');
$this->db->select("to_char(ASIGNACIONFISCALIZACION.FECHA_ASIGNACION,'dd/mm/yyyy') AS FECHA_ASIGNACION",FALSE);
$this->db->join('ASIGNACIONFISCALIZACION', 'EMPRESA.CODEMPRESA=ASIGNACIONFISCALIZACION.NIT_EMPRESA');
$this->db->join('USUARIOS', 'ASIGNACIONFISCALIZACION.ASIGNADO_A=USUARIOS.IDUSUARIO');
 $this->db->where("ASIGNACIONFISCALIZACION.NIT_EMPRESA", $nit);
		$dato = $this->db->get("EMPRESA");
		 return $dato;

	}
	
	function selectRespuesta (){
$this->db->select('RESPUESTAGESTION.COD_RESPUESTA,RESPUESTAGESTION.NOMBRE_GESTION');
        $this->db->where("RESPUESTAGESTION.COD_TIPOGESTION", '11');
		$query= $this->db->get("RESPUESTAGESTION");
return $query->result();

	}
	
		function selectEmail ($cod_gestion){
$this->db->select('CORREOELECTRONICO.FECHA_ENVIO,CORREOELECTRONICO.ASUNTO,CORREOELECTRONICO.DIRECCION_DESTINO,CORREOELECTRONICO.MENSAJE,
CORREOELECTRONICO.CC,CORREOELECTRONICO.CCO,CORREOELECTRONICO.NOMBRE_ADJUNTO, CORREOELECTRONICO.COD_INTERNO_PROCESO');
        $this->db->where("CORREOELECTRONICO.COD_GESTION_COBRO", $cod_gestion);
		$query= $this->db->get("CORREOELECTRONICO");
return $query->result_array[0];
}
	
			function selectAutorizacionNoti ($cod_gestion){
$this->db->select('AUTORIZACION_NOTI_EMAIL.AUTORIZA,AUTORIZACION_NOTI_EMAIL.NOMBRE_CONTACTO,AUTORIZACION_NOTI_EMAIL.EMAIL_AUTORIZADO,
AUTORIZACION_NOTI_EMAIL.FECHA_AUTORIZACION,AUTORIZACION_NOTI_EMAIL.DOCUMENTO_AUTORIZACION,GESTIONCOBRO.COD_FISCALIZACION_EMPRESA');
$this->db->join('GESTIONCOBRO', 'AUTORIZACION_NOTI_EMAIL.COD_GESTION=GESTIONCOBRO.COD_GESTION_COBRO');
        $this->db->where("AUTORIZACION_NOTI_EMAIL.COD_GESTION", $cod_gestion);
		$query= $this->db->get("AUTORIZACION_NOTI_EMAIL");
return $query->result_array[0];

	}
	
				function selectAprobacionEmail ($cod_fiscalizacion){
$this->db->select('AUTORIZACION_NOTI_EMAIL.AUTORIZA, AUTORIZACION_NOTI_EMAIL.EMAIL_AUTORIZADO, GESTIONCOBRO.COD_GESTION_COBRO');
$this->db->join('GESTIONCOBRO', 'AUTORIZACION_NOTI_EMAIL.COD_GESTION=GESTIONCOBRO.COD_GESTION_COBRO');
$this->db->join('FISCALIZACION', 'GESTIONCOBRO.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION');

        $this->db->where("FISCALIZACION.COD_FISCALIZACION", $cod_fiscalizacion);
		$query= $this->db->get("AUTORIZACION_NOTI_EMAIL");
return $query;

	}
					function selectCountVisitas ($cod_fiscalizacion){
$this->db->select('COUNT(GESTIONCOBRO.COD_GESTION_COBRO) AS VISITAS');
$this->db->join('FISCALIZACION', 'GESTIONCOBRO.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION');
        $this->db->where("FISCALIZACION.COD_FISCALIZACION", $cod_fiscalizacion);
		$this->db->where("GESTIONCOBRO.COD_TIPO_RESPUESTA", '4');
		$query= $this->db->get("GESTIONCOBRO");
return $query->result_array[0];

	}
	
						function selectCountLiq ($cod_fiscalizacion){
$this->db->select('COUNT(LIQUIDACION.COD_FISCALIZACION) AS LIQUIDACION');
        $this->db->where("LIQUIDACION.COD_FISCALIZACION", $cod_fiscalizacion);
		$query= $this->db->get("LIQUIDACION");
return $query->result_array[0];
	}
						function selectDetLiq ($cod_fiscalizacion){
$this->db->select('LIQUIDACION.EN_FIRME AS LIQUIDACION');
$this->db->select("to_char(LIQUIDACION.FECHA_RESOLUCION,'dd-mm-yyyy') AS FECHA_RESOLUCION",FALSE);
        $this->db->where("LIQUIDACION.COD_FISCALIZACION", $cod_fiscalizacion);
		$query= $this->db->get("LIQUIDACION");
return $query->result_array[0];
	}
	
						function selectNombreRegional ($cod_regional){
$this->db->select('REGIONAL.NOMBRE_REGIONAL');

		$this->db->where("REGIONAL.COD_REGIONAL", $cod_regional);
		$query= $this->db->get("REGIONAL");
return $query->result_array[0];

	}
	
	
						function selectCountInformes ($cod_fiscalizacion){
$this->db->select('COUNT(GESTIONCOBRO.COD_GESTION_COBRO) AS INFORMES');
$this->db->join('FISCALIZACION', 'GESTIONCOBRO.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION');

        $this->db->where("FISCALIZACION.COD_FISCALIZACION", $cod_fiscalizacion);
		$this->db->where("GESTIONCOBRO.COD_TIPOGESTION", '5');
		$query= $this->db->get("GESTIONCOBRO");
return $query->result_array[0];

	}
	
					function selectCountVisitasPresencia ($cod_fiscalizacion){
$this->db->select('COUNT(GESTIONCOBRO.COD_GESTION_COBRO) AS VISITAS');
$this->db->join('FISCALIZACION', 'GESTIONCOBRO.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION');
        $this->db->where("FISCALIZACION.COD_FISCALIZACION", $cod_fiscalizacion);
		$this->db->where("GESTIONCOBRO.COD_TIPO_RESPUESTA", '10');
		$query= $this->db->get("GESTIONCOBRO");
return $query->result_array[0];

	}	
	
						function selectCountInformePresencia ($cod_fiscalizacion){
$this->db->select('COUNT(GESTIONCOBRO.COD_GESTION_COBRO) AS INFORMES');
$this->db->join('FISCALIZACION', 'GESTIONCOBRO.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION');
        $this->db->where("FISCALIZACION.COD_FISCALIZACION", $cod_fiscalizacion);
		$this->db->where("GESTIONCOBRO.COD_TIPOGESTION", '248');
		$query= $this->db->get("GESTIONCOBRO");
return $query->result_array[0];

	}	

	function selectFechaFisc ($concepto, $cod_asign){
		$this->db->select("to_char(FISCALIZACION.PERIODO_INICIAL,'dd-mm-yyyy') AS PERIODO_INI",FALSE);
		$this->db->select("to_char(FISCALIZACION.PERIODO_FINAL,'dd-mm-yyyy') AS PERIODO_FIN",FALSE);
		$this->db->join('GESTIONCOBRO', 'GESTIONCOBRO.COD_GESTION_COBRO=FISCALIZACION.COD_GESTIONACTUAL');
        $this->db->where("FISCALIZACION.COD_CONCEPTO", $concepto);
		$this->db->where("FISCALIZACION.COD_ASIGNACION_FISC", $cod_asign);
		 $this->db->where("FISCALIZACION.COD_TIPOGESTION !=", '309');
		$this->db->where("GESTIONCOBRO.COD_TIPO_RESPUESTA !=", '880');
		$query= $this->db->get("FISCALIZACION");
return $query->result();

	}
	

	function updateRespuestaAprobacion ($table,$data,$date){
	$query = $this->db->query(" UPDATE ".$table."  SET COMENTARIOS_APROBACION='".$data['COMENTARIOS_APROBACION']."',COD_GESTIONCOBRO_RESP='".$data['COD_GESTIONCOBRO_RESP']."', FECHA_APROBACION=".'to_date(\''.$date['FECHA_APROBACION'].'\',\'dd-mm-yyyy hh24:mi\')'." WHERE COD_SOPORTE_GESTION=".$data['COD_SOPORTE_GESTION']);
if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;   
	}
	
	
		function updateGestion ($table,$data,$date){
	$query = $this->db->query(" UPDATE ".$table."  SET NRO_RADICADO_ONBASE='".$data['NRO_RADICADO_ONBASE']."', FECHA_RADICADO=".'to_date(\''.$date['FECHA_RADICADO'].'\',\'dd-mm-yyyy\')'." WHERE COD_GESTION_COBRO=".$data['COD_GESTION_COBRO']);
if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;   
	}
		
	function updateGestionActual ($table,$gestion,$fiscalizacion,$tipogest){
	$query = $this->db->query(" UPDATE ".$table."  SET COD_GESTIONACTUAL='".$gestion."',COD_TIPOGESTION='".$tipogest."' WHERE COD_FISCALIZACION=".$fiscalizacion);

if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;   
	}
	
		function updateGestionActualActaG ($table,$gestion,$fiscalizacion,$tipogest){
		$fin='S';
	$query = $this->db->query(" UPDATE ".$table."  SET COD_GESTIONACTUAL='".$gestion."',COD_TIPOGESTION='".$tipogest."', FIN_FISCALIZACION='".$fin."' WHERE COD_FISCALIZACION=".$fiscalizacion);

if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;   
	}
	
	function updateGestionActualConsec ($table,$fiscalizacion,$nro_expediente){
	$query = $this->db->query(" UPDATE ".$table."  SET NRO_EXPEDIENTE='".$fiscalizacion."',COD_FISCALIZACION='".$nro_expediente."' WHERE COD_FISCALIZACION=".$fiscalizacion);

if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;   
	}
	
	
		function updateTipoGestionActual ($table,$gestion,$fiscalizacion,$tipogest){
	$query = $this->db->query(" UPDATE ".$table."  SET COD_TIPOGESTION='".$tipogest."' WHERE COD_FISCALIZACION=".$fiscalizacion);

if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;   
	}
	
	
function datatable1 ($nit, $asignacion_fisc){
							$this->db->select('FISCALIZACION.COD_FISCALIZACION,FISCALIZACION.COD_FISCALIZACION AS NO_EXPEDIENTE,FISCALIZACION.COD_ASIGNACION_FISC,ASIGNACIONFISCALIZACION.NIT_EMPRESA,
								FISCALIZACION.COD_CONCEPTO,FISCALIZACION.FIN_FISCALIZACION,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO, FISCALIZACION.COD_TIPOGESTION, FISCALIZACION.NRO_EXPEDIENTE,
								TIPOGESTION.TIPOGESTION,,USUARIOS.NOMBREUSUARIO');
								$this->db->select("to_char(FISCALIZACION.PERIODO_INICIAL,'dd/mm/yyyy') AS PERIODO_INI",FALSE);
								$this->db->select("to_char(FISCALIZACION.PERIODO_FINAL,'dd/mm/yyyy') AS PERIODO_FIN",FALSE);
								$this->db->join('CONCEPTOSFISCALIZACION', 'CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=FISCALIZACION.COD_CONCEPTO');
								$this->db->join('TIPOGESTION', 'FISCALIZACION.COD_TIPOGESTION=TIPOGESTION.COD_GESTION');
								$this->db->join('ASIGNACIONFISCALIZACION', 'FISCALIZACION.COD_ASIGNACION_FISC=ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION');
								$this->db->join('USUARIOS', 'ASIGNACIONFISCALIZACION.ASIGNADO_A=USUARIOS.IDUSUARIO');
								$this->db->join('EMPRESA', 'ASIGNACIONFISCALIZACION.NIT_EMPRESA=EMPRESA.CODEMPRESA');
								$this->db->where('ASIGNACIONFISCALIZACION.NIT_EMPRESA', $nit);
								$this->db->where('ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION', $asignacion_fisc);
								return $this->db->get('FISCALIZACION');
					
								 	}	

	
	function datatablegestion ($cod_fisc){
	$this->db->select('GESTIONCOBRO.COD_GESTION_COBRO,GESTIONCOBRO.COD_FISCALIZACION_EMPRESA,USUARIOS.NOMBREUSUARIO, TIPOGESTION.TIPOGESTION, RESPUESTAGESTION.NOMBRE_GESTION, RESPUESTAGESTION.COD_RESPUESTA, GESTIONCOBRO.NRO_RADICADO_ONBASE,
															USUARIOS.IDUSUARIO, GESTIONCOBRO.COMENTARIOS, FISCALIZACION.COD_GESTIONACTUAL');
								$this->db->select("to_char(GESTIONCOBRO.FECHA_RADICADO,'dd/mm/yyyy') AS FECHA_RADICADO",FALSE);
								$this->db->select("to_char(GESTIONCOBRO.FECHA_CONTACTO,'dd/mm/yyyy') AS FECHA_CONTACTO",FALSE);
								$this->db->join('FISCALIZACION', 'GESTIONCOBRO.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION');
								$this->db->join('ASIGNACIONFISCALIZACION','FISCALIZACION.COD_ASIGNACION_FISC=ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION');
								$this->db->join('USUARIOS', 'GESTIONCOBRO.COD_USUARIO=USUARIOS.IDUSUARIO');
								$this->db->join('TIPOGESTION', 'GESTIONCOBRO.COD_TIPOGESTION=TIPOGESTION.COD_GESTION');
								$this->db->join('RESPUESTAGESTION', 'GESTIONCOBRO.COD_TIPO_RESPUESTA=RESPUESTAGESTION.COD_RESPUESTA');
								$this->db->where('GESTIONCOBRO.COD_FISCALIZACION_EMPRESA', $cod_fisc);
									
								return $this->db->get('GESTIONCOBRO');
					
								  
	}
	
	function dataliquidacion ($concepto, $tipo, $inicio, $fin, $nit){

		switch($tipo){
							
	case 0:
	$this->db->select('LIQUIDACION.NUM_LIQUIDACION AS NUMEROLIQ');
		$this->db->where('LIQUIDACION.FECHA_RESOLUCION IS NULL', NULL, FALSE);
		break;
    case 1:
    $this->db->select('RESOLUCION.NUMERO_RESOLUCION AS NUMERORES, TIPOGESTION.TIPOGESTION, RESOLUCION.VALOR_TOTAL');
	$this->db->join('RESOLUCION', 'LIQUIDACION.COD_FISCALIZACION=RESOLUCION.COD_FISCALIZACION');
	$this->db->join('TIPOGESTION', 'RESOLUCION.COD_ESTADO=TIPOGESTION.COD_GESTION');
	$this->db->where('LIQUIDACION.FECHA_RESOLUCION IS NOT NULL', NULL, FALSE);
        break;
	default;

	
	break;
							}
							
	switch($concepto){
							
	case 1:
	$this->db->where('LIQUIDACION.COD_CONCEPTO', $concepto);
		break;
    case 2:
	$this->db->where('LIQUIDACION.COD_CONCEPTO', $concepto);
	    break;
		
		case 3:
    $this->db->where('LIQUIDACION.COD_CONCEPTO', $concepto);
        break;
        
	default;
	
	
	break;
							}						
							
	
			
		if(!empty($inicio) && 
			!empty($fin)){
	$inicio=str_replace("/", "-", $inicio);
	$fin=str_replace("/", "-", $fin);
	$p_inicio = date("m-Y", strtotime($inicio));
$p_fin = date("m-Y", strtotime($fin));
//echo $p_fin;
//die();


	
			$this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN TO_DATE('".$p_inicio."', 'mm-yyyy') AND TO_DATE( '".$p_fin."', 'mm-yyyy')", NULL, FALSE);
			$this->db->where("LIQUIDACION.FECHA_FIN BETWEEN TO_DATE('".$p_inicio."', 'mm-yyyy')  AND TO_DATE( '".$p_fin."', 'mm-yyyy')", NULL, FALSE);
			}
								
								$this->db->select("to_char(LIQUIDACION.FECHA_INICIO,'dd/mm/yyyy') AS PERIODO_INI",FALSE);
								$this->db->select("to_char(LIQUIDACION.FECHA_FIN,'dd/mm/yyyy') AS PERIODO_FIN",FALSE);
								$this->db->select("to_char(LIQUIDACION.FECHA_EJECUTORIA,'dd/mm/yyyy') AS FECHA_EJECUTORIA",FALSE);
								$this->db->select('CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO, LIQUIDACION.TOTAL_LIQUIDADO, LIQUIDACION.FECHA_RESOLUCION, LIQUIDACION.SALDO_DEUDA');
								$this->db->join('CONCEPTOSFISCALIZACION', 'CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO');
								$this->db->where('LIQUIDACION.NITEMPRESA', $nit);
							
								$query= $this->db->get("LIQUIDACION");
								//echo $this->db->last_query();
								
								return $query;
								 	}


	function datapagosaplicados ($concepto, $inicio, $fin, $nit){
	

							
	switch($concepto){
							
	case 1:
	$this->db->where('PAGOSRECIBIDOS.COD_CONCEPTO', $concepto);
		break;
    case 2:
	$this->db->where('PAGOSRECIBIDOS.COD_CONCEPTO', $concepto);
	    break;
		
		case 3:
    $this->db->where('PAGOSRECIBIDOS.COD_CONCEPTO', $concepto);
        break;
        
	default;
	
	
	break;
							}						
							
	
			
		if(!empty($inicio) && 
			!empty($fin)){
$p_inicio = date("Y-m", strtotime($inicio));
$p_fin = date("Y-m", strtotime($fin));
	
			$this->db->where("TO_DATE(PAGOSRECIBIDOS.PERIODO_PAGADO, 'yyyy-mm') BETWEEN TO_DATE('".$p_inicio."', 'yyyy-mm') AND TO_DATE('".$p_fin."', 'yyyy-mm')", NULL, FALSE);

			}
							
								$this->db->select("to_char(PAGOSRECIBIDOS.PERIODO_PAGADO) AS FECHA",FALSE);
								$this->db->select('CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO, PAGOSRECIBIDOS.APLICADO, PAGOSRECIBIDOS.VALOR_PAGADO, PAGOSRECIBIDOS.COD_CONCEPTO');
								$this->db->select('PAGOSRECIBIDOS.DISTRIBUCION_CAPITAL, PAGOSRECIBIDOS.DISTRIBUCION_INTERES, PAGOSRECIBIDOS.DISTRIBUCION_INTERES_MORA');
								$this->db->join('CONCEPTOSFISCALIZACION', 'CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=PAGOSRECIBIDOS.COD_CONCEPTO');
								$this->db->where('PAGOSRECIBIDOS.NITEMPRESA', $nit);
								$this->db->where('PAGOSRECIBIDOS.APLICADO', '1');
								return $this->db->get('PAGOSRECIBIDOS');
					
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
    $this->db->select('CODEMPRESA, RAZON_SOCIAL');
    if (!empty($this->nit)) {
      $this->db->like('CODEMPRESA', $this->nit);
    }
    $datos = $this->db->get('EMPRESA');
    $datos = $datos->result_array();
    if(!empty($datos)) :
      $tmp = NULL;
      foreach($datos as $nit) :
        $tmp[] = array("value" => $nit['CODEMPRESA'], "label" => $nit['CODEMPRESA']." :: ".$nit['RAZON_SOCIAL']);
      endforeach;
      $datos = $tmp;
    endif;
    return $datos;
  }
  
  	  function getLastInserted($table, $id) {
	$this->db->select_max($id);
	$Q = $this->db->get($table);
	$row = $Q->row_array();
	return $row[$id];
 }
  
}