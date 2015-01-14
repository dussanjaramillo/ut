<?php

class Cnm_cuota_parte_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array') {

        $this->db->select($fields);
        $this->db->from($table);
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }

        $query = $this->db->get();

        $result = !$one ? $query->result($array) : $query->row();
        return $result;
    }

    function getoption($table, $where, $id) {

        $this->db->where($where, $id);
        $result = $this->db->get($table);
        return $result;
    }
	
					function detalleCarteras ($nit, $id_cartera, $estado, $id_deuda){
$this->db->select('CNM_EMPRESA.COD_ENTIDAD AS IDENTIFICACION, CNM_EMPRESA.RAZON_SOCIAL AS NOMBRES, CNM_EMPRESA.DIRECCION, CNM_EMPRESA.TELEFONO,
CNM_EMPRESA.CORREO_ELECTRONICO, REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA, CNM_CARTERANOMISIONAL.COD_TIPOCARTERA,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_CARTERANOMISIONAL.COD_ESTADO, CNM_CARTERA_CUOTA_PARTE.ADJUNTOS');
if(!empty($nit)){
$this->db->join('CNM_EMPRESA', "CNM_CARTERANOMISIONAL.COD_EMPRESA=CNM_EMPRESA.COD_ENTIDAD AND CNM_CARTERANOMISIONAL.COD_EMPRESA=".$nit);
}
else{
$this->db->join('CNM_EMPRESA', "CNM_CARTERANOMISIONAL.COD_EMPRESA=CNM_EMPRESA.COD_ENTIDAD");
}
$this->db->join('REGIONAL', "CNM_EMPRESA.COD_REGIONAL=REGIONAL.COD_REGIONAL");
$this->db->join('ESTADOCARTERA', "CNM_CARTERANOMISIONAL.COD_ESTADO=ESTADOCARTERA.COD_EST_CARTERA AND CNM_CARTERANOMISIONAL.COD_ESTADO=".$estado);
$this->db->join('CNM_CARTERA_CUOTA_PARTE', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_CUOTA_PARTE.COD_CARTERA");
$this->db->where("CNM_CARTERANOMISIONAL.COD_TIPOCARTERA", $id_cartera);
if(!empty($id_deuda)){
$this->db->where("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", $id_deuda);
}
		$dato = $this->db->get("CNM_CARTERANOMISIONAL");
		//echo $this->db->last_query();
		 return $dato;
	}

    function addbanco($table, $data) {

        $this->db->set('IDBANCO', $data['IDBANCO']);
        $this->db->set('NOMBREBANCO', $data['IDBANCO']);
        $this->db->set('FECHACREACION', "to_date('" . $data['FECHACREACION'] . "','dd/mm/yyyy')", false);
        $this->db->set('IDESTADO', $data['IDESTADO']);
        $this->db->insert($table);
        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }

        return FALSE;
    }

    function getSelectTipoCartera($table, $fields) {
        $query = $this->db->query(" SELECT " . $fields . "  FROM " . $table . " WHERE COD_ESTADO='1' AND TIPO='CREADA' AND COD_TIPOCARTERA='9'");
        return $query->result();
    }

    function getSequence($table, $name) {
        $query = $this->db->query("SELECT " . $name . "  FROM " . $table . " ");
        $row = $query->row_array();
        return $row['NEXTVAL'] - 1;
    }
	
	    function guardarfila($estructura, $archivo) {
        $fecha = date('d/m/Y H:i:s');

        $campos = array('IDENTIFICACION_EMPRESA', 'NOMBRE_EMPRESA', 'IDENTIFICACION_PENSIONADO', 'NOMBRE_PENSIONADO', 'CUOTA_PARTE_PENSION', 'CUOTA_PARTE_ACTUAL', 'CAPITAL', 'INTERESES',
            'FECHA_INICIAL', 'FECHA_FINAL');
	    
		$x = 0;		
		
			$this->db->trans_begin();
        foreach ($estructura as $data) {
            if ($x == 8 || $x == 9) {
                $this->db->set($campos[$x], "to_date('" . $data . "','dd/mm/yyyy HH24:MI:SS')", false);
            } else {
					   if(is_numeric($data))
		   {
		   $this->db->set($campos[$x], $data, false);
		   }
		   else{
		             $this->db->set($campos[$x], $data);
					 }
            }
            $x++;
        }

        $this->db->set('CREADO', 'S');
        $this->db->insert('CM_CUOTA_PARTE_PENSIONAL');
		
		$idcarteraantigua = $this->verificarIdCarteraAntigua($estructura[0])->result();
		
		if(!empty($idcarteraantigua)){
		//eliminación de datos
		
		$this->db->set('COD_ESTADO', '4');
	    $this->db->where('CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL', $idcarteraantigua[0]->COD_CARTERA_NOMISIONAL);
        $this->db->update('CNM_CARTERANOMISIONAL');
		
		$this->db->set('CNM_CUOTAS.ANULADO', 'S');
	    $this->db->where('CNM_CUOTAS.ID_DEUDA_E', $idcarteraantigua[0]->COD_CARTERA_NOMISIONAL);
        $this->db->update('CNM_CUOTAS');
		
		$this->db->where('IDENTIFICACION_EMPRESA', $estructura[0]);
		$this->db->delete('CNM_CUOTAS_PARTES_BENEF');
		
		}

		
		//inserción pensionados
		$this->db->set('IDENTIFICACION_EMPRESA', $estructura[0]);
		$this->db->set('NOMBRE_EMPRESA', $estructura[1]);
        $this->db->set('IDENTIFICACION_PENSIONADO', $estructura[2]);
        $this->db->set('NOMBRE_PENSIONADO',$estructura[3]);
        $this->db->set('CUOTA_PARTE_ACTUAL', $estructura[5], false);
        $this->db->set('CAPITAL', $estructura[6], false);
        $this->db->set('INTERESES', $estructura[7], false);
    	$this->db->set('FECHA_INICIAL', "to_date('" . $estructura[8] . "','dd/mm/yyyy HH24:MI:SS')", false);
		$this->db->set('FECHA_FINAL', "to_date('" . $estructura[9] . "','dd/mm/yyyy HH24:MI:SS')", false);
        $this->db->insert('CNM_CUOTAS_PARTES_BENEF');
        
		//verificación existencia deuda
		$idcartera = $this->verificarIdCartera($estructura[0])->result();
		if(!empty($idcartera)){
		//actualización datos deuda
		$this->db->set('VALOR_DEUDA', $estructura[5] + $idcartera[0]->VALOR_DEUDA);
        $this->db->set('SALDO_DEUDA', $estructura[5] + $idcartera[0]->SALDO_DEUDA);
	    $this->db->where('CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL', $idcartera[0]->COD_CARTERA_NOMISIONAL);
        $this->db->update('CNM_CARTERANOMISIONAL');
		//echo $this->db->last_query();
		$iddeuda = $idcartera[0]->COD_CARTERA_NOMISIONAL;
		}
else{

		//creación deuda
        $this->db->set('FECHA_CREACION', "to_date('" . $fecha . "','dd/mm/yyyy HH24:MI:SS')", false);
        $this->db->set('COD_TIPOCARTERA', '9');
        $this->db->set('COD_PROCESO_REALIZADO', '1');
        $this->db->set('COD_REGIONAL', '1');
        $this->db->set('COD_ESTADO', '2');
        $this->db->set('COD_FORMAPAGO', '2');
        $this->db->set('COD_EMPRESA', $estructura[0]);
        $this->db->set('VALOR_DEUDA', $estructura[5], false);
		$this->db->set('FECHA_ACTIVACION', "to_date('" . $fecha . "','dd/mm/yyyy HH24:MI:SS')", false);
        $this->db->set('SALDO_DEUDA', $estructura[5], false);
        $this->db->insert('CNM_CARTERANOMISIONAL');
		
		$query = $this->db->query("SELECT CNM_CarteraNo_cod_carteram_SEQ.CURRVAL FROM dual");
        $row = $query->row_array();
        $iddeuda = $row['CURRVAL'];

		$this->db->set('COD_CARTERA', $iddeuda);
		$this->db->set('PERIODO_REPORTAR_INI', "to_date('" . $estructura[8] . "','dd/mm/yyyy HH24:MI:SS')", false);
        $this->db->set('PERIODO_REPORTAR_FIN', "to_date('" . $estructura[9] . "','dd/mm/yyyy HH24:MI:SS')", false);
		$this->db->set('FECHA_ACTIVACION', "to_date('" . $fecha . "','dd/mm/yyyy HH24:MI:SS')", false);
        $this->db->insert('CNM_CARTERA_CUOTA_PARTE');
}


	
		//verificación existencia cuota
		$idcuota = $this->verificarIdCuota($estructura[0])->result();
		
		if(!empty($idcuota)){
        //actualización datos cuota
		$this->db->set('CAPITAL', $estructura[6] + $idcuota[0]->CAPITAL, false);
        $this->db->set('SALDO_CUOTA', $estructura[5] + $idcuota[0]->SALDO_CUOTA, false);
        $this->db->set('VALOR_CUOTA', $estructura[5] + $idcuota[0]->VALOR_CUOTA, false);
        $this->db->set('VALOR_INTERES_C', $estructura[7] + $idcuota[0]->VALOR_INTERES_C, false);
        $this->db->set('SALDO_INTERES_C', $estructura[7] + $idcuota[0]->SALDO_INTERES_C, false);
        $this->db->set('AMORTIZACION', $estructura[6] + $idcuota[0]->AMORTIZACION, false);
        $this->db->set('SALDO_AMORTIZACION', $estructura[6] + $idcuota[0]->SALDO_AMORTIZACION, false);
	    $this->db->where('CNM_CUOTAS.ID_DEUDA_E', $idcuota[0]->ID_DEUDA_E);
        $this->db->update('CNM_CUOTAS');
		}
		else{
		//creación cuota
		$fechacargue = date('d/m/Y H:i:s');
		$mesproy= date('YYYY-mm');
		
		$this->db->set('ID_DEUDA_E', $iddeuda);
		$this->db->set('NO_CUOTA', '1');
		$this->db->set('CONCEPTO', '9' );
        $this->db->set('CAPITAL', $estructura[6], false );
        $this->db->set('SALDO_CUOTA', $estructura[5], false );
        $this->db->set('VALOR_CUOTA', $estructura[5], false );
        $this->db->set('MES_PROYECTADO', '2014-10' );
        $this->db->set('VALOR_INTERES_C', $estructura[7], false );
        $this->db->set('SALDO_INTERES_C', $estructura[7], false );
        $this->db->set('AMORTIZACION', $estructura[6], false );
        $this->db->set('SALDO_FINAL_CAP', 0 );
        $this->db->set('FECHA_LIM_PAGO', "to_date('" . $fechacargue . "','dd/mm/yyyy HH24:MI:SS')", false);
        $this->db->set('SALDO_AMORTIZACION', $estructura[6], false );
        $this->db->set('MEDIO_INI_PAGO', '2' );
        
        $this->db->insert('CNM_CUOTAS');
		
		$this->db->set('COMENTARIOS', 'Cargue Realizado' );
        $this->db->set('COD_TIPO_RESPUESTA', '1495' );
        $this->db->set('COD_TIPOGESTION', '735' );
        $this->db->set('COD_TITULO', '');
        $this->db->set('COD_USUARIO', '');
        $this->db->set('COD_JURIDICO', '');
        $this->db->set('COD_CARTERANOMISIONAL', $iddeuda);
        $this->db->set('COD_DEVOLUCION', '');
        $this->db->set('COD_RECEPCIONTITULO', '');	
		$this->db->set('FECHA',  "to_date('" . $fechacargue . "','dd/mm/yyyy HH24:MI:SS')", false );
		 $this->db->insert('TRAZAPROCJUDICIAL');
		}
		
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

 			function verificarIdCuota ($nit_empresa){

			 $query = $this->db->query(" SELECT ID_DEUDA_E, CAPITAL, SALDO_CUOTA, CNM_CUOTAS.VALOR_CUOTA, VALOR_INTERES_C, SALDO_INTERES_C, AMORTIZACION, SALDO_FINAL_CAP,
										SALDO_AMORTIZACION FROM CNM_CUOTAS 
			 JOIN CNM_CARTERANOMISIONAL ON CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E
			 WHERE CNM_CARTERANOMISIONAL.COD_EMPRESA='".$nit_empresa."' AND CNM_CARTERANOMISIONAL.COD_ESTADO NOT IN (4,5,6)
			 AND CNM_CARTERANOMISIONAL.COD_TIPOCARTERA=9");

			//echo $this->db->last_query();
		return $query;
		//return $query->result();

	}
	
	
 			function verificarIdCartera ($nit_empresa){

			 $query = $this->db->query(" SELECT COD_CARTERA_NOMISIONAL, SALDO_DEUDA, VALOR_DEUDA
			 FROM CNM_CARTERANOMISIONAL 
			 WHERE CNM_CARTERANOMISIONAL.COD_EMPRESA='".$nit_empresa."' AND CNM_CARTERANOMISIONAL.COD_ESTADO NOT IN (4,5,6)
			 AND CNM_CARTERANOMISIONAL.COD_TIPOCARTERA=9 AND TRUNC(CNM_CARTERANOMISIONAL.FECHA_ACTIVACION) = TRUNC(SYSDATE)");

			//echo $this->db->last_query();
		return $query;
		//return $query->result();

	}
	
	function verificarIdCarteraAntigua ($nit_empresa){

			 $query = $this->db->query(" SELECT COD_CARTERA_NOMISIONAL, SALDO_DEUDA, VALOR_DEUDA
			 FROM CNM_CARTERANOMISIONAL 
			 WHERE CNM_CARTERANOMISIONAL.COD_EMPRESA='".$nit_empresa."' AND CNM_CARTERANOMISIONAL.COD_ESTADO NOT IN (4,5,6)
			 AND CNM_CARTERANOMISIONAL.COD_TIPOCARTERA=9 AND TRUNC(CNM_CARTERANOMISIONAL.FECHA_ACTIVACION) <> TRUNC(SYSDATE)");


		//echo $this->db->last_query();
		return $query;
		//return $query->result();

	}
  	
		  function detalleCuotaP($cod_cartera) {

$this->db->select('CNM_EMPRESA.COD_ENTIDAD AS IDENTIFICACION, CNM_EMPRESA.RAZON_SOCIAL AS NOMBRES, CNM_EMPRESA.DIRECCION, CNM_EMPRESA.SIGLA, CNM_EMPRESA.TELEFONO,
CNM_EMPRESA.CORREO_ELECTRONICO, REGIONAL.NOMBRE_REGIONAL, ESTADOCARTERA.DESC_EST_CARTERA,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, TIPOCARTERA.NOMBRE_CARTERA, 
CNM_CARTERANOMISIONAL.VALOR_DEUDA, CNM_CARTERANOMISIONAL.SALDO_DEUDA, CNM_CARTERANOMISIONAL.PLAZO_CUOTAS, CNM_FORMAPAGO.FORMA_PAGO,
CNM_CARTERA_CUOTA_PARTE.ADJUNTOS');
$this->db->select("to_char(CNM_CARTERANOMISIONAL.FECHA_ACTIVACION,'dd/mm/yyyy') AS FECHA_ACTIVACION",FALSE);
$this->db->join('CNM_CARTERANOMISIONAL', "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_CUOTA_PARTE.COD_CARTERA AND CNM_CARTERA_CUOTA_PARTE.COD_CARTERA=".$cod_cartera);
$this->db->join('CNM_EMPRESA', "CNM_CARTERANOMISIONAL.COD_EMPRESA=CNM_EMPRESA.COD_ENTIDAD");
$this->db->join('REGIONAL', "CNM_EMPRESA.COD_REGIONAL=REGIONAL.COD_REGIONAL");		
$this->db->join('ESTADOCARTERA', "CNM_CARTERANOMISIONAL.COD_ESTADO=ESTADOCARTERA.COD_EST_CARTERA");
$this->db->join('TIPOCARTERA', "CNM_CARTERANOMISIONAL.COD_TIPOCARTERA=TIPOCARTERA.COD_TIPOCARTERA");

$this->db->join('CNM_FORMAPAGO', "CNM_CARTERANOMISIONAL.COD_FORMAPAGO=CNM_FORMAPAGO.COD_FORMAPAGO");

$dato = $this->db->get("CNM_CARTERA_CUOTA_PARTE");

		 return $dato;
  }
  
  	function consultaPensionados ($id_cartera){

			 $query = $this->db->query(" SELECT CNM_CUOTAS_PARTES_BENEF.IDENTIFICACION_PENSIONADO, CNM_CUOTAS_PARTES_BENEF.NOMBRE_PENSIONADO, 
			 CNM_CUOTAS_PARTES_BENEF.CUOTA_PARTE_ACTUAL, CNM_CUOTAS_PARTES_BENEF.CAPITAL, CNM_CUOTAS_PARTES_BENEF.INTERESES,
			 to_char(CNM_CUOTAS_PARTES_BENEF.FECHA_INICIAL,'dd/mm/yyyy') AS FECHA_INICIAL, 
			 to_char(CNM_CUOTAS_PARTES_BENEF.FECHA_FINAL,'dd/mm/yyyy') AS FECHA_FINAL
			 FROM CNM_CARTERANOMISIONAL  
			 JOIN CNM_CUOTAS_PARTES_BENEF ON CNM_CUOTAS_PARTES_BENEF.IDENTIFICACION_EMPRESA=CNM_CARTERANOMISIONAL.COD_EMPRESA
			 WHERE CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL='".$id_cartera."'");


		//echo $this->db->last_query();
		
		return $query;

	}
	
}
