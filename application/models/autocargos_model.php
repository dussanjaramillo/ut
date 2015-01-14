<?php

// Responsable: Leonardo Molina
class Autocargos_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getDatax($reg, $search) {
        $this->db->select("LIQUIDACION.NUM_LIQUIDACION AS ESTADO_NRO_ESTADO,LIQUIDACION.NITEMPRESA AS ESTADO_NIT_EMPRESA, EMPRESA.NOMBRE_EMPRESA, 
LIQUIDACION.FECHA_LIQUIDACION AS ESTADO_FECHA_CREACION,'' ESTADO_ESTADO,LIQUIDACION.SALDO_DEUDA AS ESTADO_VALOR_FINAL,LIQUIDACION.COD_FISCALIZACION", false);
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA = LIQUIDACION.NITEMPRESA");
        $this->db->join('FISCALIZACION F', 'F.COD_FISCALIZACION = LIQUIDACION.COD_FISCALIZACION');
        $this->db->where("LIQUIDACION.SALDO_DEUDA >", '0');
        $this->db->where("LIQUIDACION.COD_CONCEPTO", '3');
        $this->db->where("NOT EXISTS(
SELECT 'X'
FROM AUTOSJURIDICOS
WHERE AUTOSJURIDICOS.COD_FISCALIZACION = LIQUIDACION.COD_FISCALIZACION
) AND 1=", '1',FALSE);
        $query = $this->db->get('LIQUIDACION');

        return $query->result();
    }

    function totalData($search) {
        $this->db->select("LIQUIDACION.NUM_LIQUIDACION AS ESTADO_NRO_ESTADO,LIQUIDACION.NITEMPRESA AS ESTADO_NIT_EMPRESA, EMPRESA.NOMBRE_EMPRESA, 
LIQUIDACION.FECHA_LIQUIDACION AS ESTADO_FECHA_CREACION,'' ESTADO_ESTADO,LIQUIDACION.SALDO_DEUDA AS ESTADO_VALOR_FINAL", false);
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA = LIQUIDACION.NITEMPRESA");
        $this->db->join('FISCALIZACION F', 'F.COD_FISCALIZACION = LIQUIDACION.COD_FISCALIZACION');
        $this->db->where("LIQUIDACION.SALDO_DEUDA <>", '0');
        $this->db->where("LIQUIDACION.COD_CONCEPTO", '3');
        $this->db->where("NOT EXISTS(
SELECT 'X'
FROM AUTOSJURIDICOS
WHERE AUTOSJURIDICOS.COD_FISCALIZACION = LIQUIDACION.COD_FISCALIZACION
) AND 1=", '1',FALSE);
        $query = $this->db->get('LIQUIDACION');
        if ($query->num_rows() == 0)
            return '0';
        return $query->num_rows();
    }
    function guardar_abogado($post){
        
        $this->db->set('COD_ABOG_RELACIONES',$post['post']['abogado']);
        $this->db->where('COD_FISCALIZACION',$post['post']['cod_fis']);
        $this->db->update('FISCALIZACION');
    }
    function abogados($id) {
        $this->db->select("USUARIOS.IDUSUARIO,USUARIOS.APELLIDOS,USUARIOS.NOMBRES");
        $this->db->join("USUARIOS_GRUPOS", "USUARIOS.IDUSUARIO=USUARIOS_GRUPOS.IDUSUARIO");
        $this->db->join("GRUPOS", "USUARIOS_GRUPOS.IDGRUPO=GRUPOS.IDGRUPO");
        $this->db->where("GRUPOS.IDGRUPO", "44");
        $this->db->where("USUARIOS.COD_REGIONAL", $id);
        $dato = $this->db->get("USUARIOS");
        return $dato->result_array;
    }

    ////// funciones para traer datos de los auto de cargos

    function getDataxAutos($reg, $search) {
        $this->db->select('LIQUIDACION.NUM_LIQUIDACION,LIQUIDACION.NITEMPRESA,LIQUIDACION.FECHA_LIQUIDACION,PAP.PROYACUPAG_FECHALIMPAGO,LIQUIDACION.TOTAL_LIQUIDADO');
        $this->db->from('LIQUIDACION');
        $this->db->join('PROYECCIONACUERDOPAGO PAP', 'PAP.LIQ_NUMLIQUIDACION = LIQUIDACION.NUM_LIQUIDACION', ' inner');
        $this->db->group_by('LIQUIDACION.NUM_LIQUIDACION,LIQUIDACION.NITEMPRESA,LIQUIDACION.FECHA_LIQUIDACION,PAP.PROYACUPAG_FECHALIMPAGO,LIQUIDACION.TOTAL_LIQUIDADO');
        $this->db->limit(10, $reg);
        $this->db->like('LIQUIDACION.NUM_LIQUIDACION', $search);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    function totalDataAutos($search) {
        $this->db->select('LIQUIDACION.NUM_LIQUIDACION,LIQUIDACION.NITEMPRESA,LIQUIDACION.FECHA_LIQUIDACION,PAP.PROYACUPAG_FECHALIMPAGO,LIQUIDACION.TOTAL_LIQUIDADO');
        $this->db->from('LIQUIDACION');
        $this->db->join('PROYECCIONACUERDOPAGO PAP', 'PAP.LIQ_NUMLIQUIDACION = LIQUIDACION.NUM_LIQUIDACION', ' left');
        $this->db->group_by('LIQUIDACION.NUM_LIQUIDACION,LIQUIDACION.NITEMPRESA,LIQUIDACION.FECHA_LIQUIDACION,PAP.PROYACUPAG_FECHALIMPAGO,LIQUIDACION.TOTAL_LIQUIDADO');
        $this->db->like('LIQUIDACION.NUM_LIQUIDACION', $search);
        $query = $this->db->get();
        if ($query->num_rows() == 0)
            return '0';
        return $query->num_rows();
    }

    function getEmpresas($ecuenta) {
//        $this->db->select('SEC.ESTADO_NRO_ESTADO,SEC.ESTADO_NIT_EMPRESA,F.COD_FISCALIZACION,EM.*');
//        $this->db->from('SGVA_ESTDO_DE_CUENTA SEC');
//        $this->db->join('EMPRESA EM','EM.CODEMPRESA = SEC.ESTADO_NIT_EMPRESA','inner');
//        $this->db->join('ASIGNACIONFISCALIZACION AF','AF.NIT_EMPRESA = SEC.ESTADO_NIT_EMPRESA');
//        $this->db->join('FISCALIZACION F','F.COD_ASIGNACION_FISC = AF.COD_ASIGNACIONFISCALIZACION');
//        $this->db->where('SEC.ESTADO_NRO_ESTADO',$ecuenta);
//        $this->db->where('F.COD_CONCEPTO',3);// contrato de aprendizaje
//        $this->db->where('F.PERIODO_INICIAL','SEC.ESTADO_FECHA_INICIO',false);
//        $this->db->where('F.PERIODO_FINAL','SEC.ESTADO_FECHA_FIN',false);
//        $query = $this->db->get();
        $this->db->select("LIQUIDACION.NUM_LIQUIDACION AS ESTADO_NRO_ESTADO,LIQUIDACION.COD_FISCALIZACION  ,LIQUIDACION.NITEMPRESA AS CODEMPRESA, 
            EMPRESA.NOMBRE_EMPRESA, EMPRESA.DIRECCION,
LIQUIDACION.FECHA_LIQUIDACION AS ESTADO_FECHA_CREACION,'' ESTADO_ESTADO,LIQUIDACION.SALDO_DEUDA AS ESTADO_VALOR_FINAL", false);
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA = LIQUIDACION.NITEMPRESA");
        $this->db->where("LIQUIDACION.COD_CONCEPTO", '3');
        $this->db->where('LIQUIDACION.NUM_LIQUIDACION', $ecuenta);
        $query = $this->db->get('LIQUIDACION');

        return $query->row();
    }

    function saveAuto($cod_tipo_auto, $cod_fiscalizacion, $cod_estadoauto, $cod_tipo_proceso, $creado_por, $asignado_a, $cod_gestioncobro, $nombreDoc, $sgvanro
    ) {

        $this->db->set("COD_TIPO_AUTO", $cod_tipo_auto);
        $this->db->set("COD_FISCALIZACION", $cod_fiscalizacion);
        $this->db->set("COD_ESTADOAUTO", $cod_estadoauto);
        $this->db->set("COD_TIPO_PROCESO", $cod_tipo_proceso);
        $this->db->set("CREADO_POR", $creado_por);
        $this->db->set("ASIGNADO_A", $asignado_a);
        $this->db->set("FECHA_CREACION_AUTO", 'SYSDATE', false);
        $this->db->set("COD_GESTIONCOBRO", $cod_gestioncobro);
        $this->db->set("NOMBRE_DOC_GENERADO", $nombreDoc);
        $this->db->set("SGVA_NRO_CUENTA", $sgvanro);
        $this->db->set("FECHA_GESTION", 'SYSDATE', false);
        $query = $this->db->insert("AUTOSJURIDICOS");

        return $query;
    }

}
