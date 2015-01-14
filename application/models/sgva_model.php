<?php

/**
 * Archivo para la administración de los metodos necesarios para el web service Sgva-Cartera
 *
 * @packageCartera
 * @subpackage Controllers
 * @author vferreira
 * @location./application/controllers/sgva_model.php * */
class Sgva_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /** Permite obtener el nit de la asignación de una fiscalización, en el caso que se realice una reasignación* */
    function NitAsignacion($cod_asignacion) {
        $this->db->select('NIT_EMPRESA');
        $this->db->from('ASIGNACIONFISCALIZACION');
        $this->db->where('COD_ASIGNACIONFISCALIZACION', $cod_asignacion);
        $resultado = $this->db->get();
        // echo $res=$this->db->last_query(); die();
        if ($resultado->num_rows() > 0):
            $resultado = $resultado->result_array();
            return $resultado[0];
        else:
            return FALSE;
        endif;
    }

    function ConsultaMonetizacion($datos) {

        /* Permite consultar los pagos realizados por concepto de  monetización para una empresa, en un periodo determinado */
        $cuota = 0;
        $this->db->select('NITEMPRESA, PERIODO_PAGADO, FECHA_PAGO, FECHA_APLICACION,VALOR_PAGADO,TICKETID');
        $this->db->from('PAGOSRECIBIDOS');
        $this->db->where('PAGOSRECIBIDOS.NITEMPRESA', $datos['nit']);
        $this->db->where('PAGOSRECIBIDOS.PERIODO_PAGADO', $datos['periodo']);
        $this->db->where('PAGOSRECIBIDOS.COD_CONCEPTO', 3);
        $this->db->where('PAGOSRECIBIDOS.COD_SUBCONCEPTO', 19);
        $resultado = $this->db->get();
        if ($resultado->num_rows() > 0):
            $cuota = $resultado->result_array();
        endif;
        return $cuota;
    }

    function ActualizarEmpresa($datos) {
        /* Guarda la información de los campos de contacto actualizados en empresa desde SGVA para ser actualizados en cartera */
       
        $this->db->set('NOMBRE_EMPRESA', 'N/A');
        $this->db->set('EMAILAUTORIZADO', $datos['email']);
        $this->db->set('TELEFONO_FIJO', $datos['telefono']);
        $this->db->set('DIRECCION', $datos['direccion']);
        $this->db->set('CUOTA_APRENDIZ', $datos['cuotaAprendiz']);
        $this->db->set('RESOLUCION', $datos['resolucion']);
        $this->db->set('PLANTA_PERSONAL', $datos['plantaPersonal']);
        $this->db->set('NUM_EMPLEADOS', $datos['numEmpleados']);
        $this->db->where('CODEMPRESA', $datos['nit']);
        $this->db->update('EMPRESA');
        if ($this->db->affected_rows() == '1') {
            $resultado = array('Respuesta' => "Se actualizo el registro: " . $datos['nit']);
        } else {
            $resultado = array('Respuesta' => "No se actualizo el registro para el nit : " . $datos['nit']);
        }
        return $resultado;
    }

    function UsuarioFiscalizador($id) {

        $resultado = 0;
        $this->db->select('US.FISCALIZADOR');
        $this->db->from('USUARIOS US');
        $this->db->where('US.IDUSUARIO', $id);
        $resultado = $this->db->get();
        // echo $this->db->last_query();
        if ($resultado->num_rows() > 0):
            $resultado->result_array();
        endif;
        return $resultado;
    }

    function consultaEmpresa($nit) {
        //Verifica si la empresa actualizada o creada existe en la tabla temporal.
        $existe = 0;
        $this->db->select('NIT_EMPRESA', $nit);
        $this->db->from('EMPRESA_SGVA_TMP');
        $this->db->where('EMPRESA_SGVA_TMP.NIT_EMPRESA', $nit);
        $resultado = $this->db->get();
        if ($resultado->num_rows() > 0):
            $existe = 1;
        endif;
        return $existe;
    }

    /**/

    function crearEmpresa($datos) {
        // return $datos;
        $this->db->set('NIT_EMPRESA', $datos['nit']);
        $this->db->set('NOMBRE_EMPRESA', strtoupper($datos['nombre']));
        $this->db->set('EMAIL', $datos['email']);
        $this->db->set('TELEFONO', $datos['telefono']);
        $this->db->set('DIRECCION', $datos['direccion']);
        $this->db->set('COD_REGIONAL', $datos['regional']);
        $this->db->set('ESTADO_EMPRESA', 'I');
        $this->db->set('REPRESENTANTE_LEGAL', strtoupper($datos['RLegal']));
        $this->db->set('FECHA_CREACION', 'SYSDATE', FALSE);
        $this->db->set('ORIGEN', 'SGVA');
        $existe = $this->consultaEmpresa($datos['nit']);

        //$resultado=  $existe;
        if ($existe == 0):
            $this->db->insert('EMPRESA_SGVA_TMP');
        else:
            $this->db->where('NIT_EMPRESA', $datos['nit']);
            $this->db->UPDATE('EMPRESA_SGVA_TMP');
        endif;
        if ($this->db->affected_rows() == '1') {
            $resultado = TRUE;
        } else {
            $resultado = FALSE;
        }
        return $resultado;
    }

    function creaFiscalizador($fiscalizador) {
        $this->db->set('CEDULA', $fiscalizador['id']);
        $this->db->set('NOMBRES', $fiscalizador['nombres']);
        $this->db->set('APELLIDOS', $fiscalizador['apellidos']);
        $this->db->set('REGIONAL', $fiscalizador['regional']);
        $this->db->set('TELEFONO', $fiscalizador['telefono']);
        $this->db->set('ESTADO_FISCALIZADOR', 'I');
        $this->db->set('EMAIL', $fiscalizador['email']);
        $this->db->set('ORIGEN', 'SGVA');
    }

    /* Consulta que la regional exista */

    function regional($id) {
        $this->db->select('REGIONAL.COD_REGIONAL');
        $this->db->from('REGIONAL');
        $this->db->where('COD_REGIONAL', $id);
        $resultado = $this->db->get();
        if ($resultado->num_rows() > 0):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function departamento($id) {
        $this->db->select('DEPARTAMENTO.COD_DEPARTAMENTO');
        $this->db->from('DEPARTAMENTO');
        $this->db->where('COD_DEPARTAMENTO', $id);
        $resultado = $this->db->get();
        if ($resultado->num_rows() > 0):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function municipio($id) {
        $this->db->select('MUNICIPIO.CODMUNICIPIO');
        $this->db->from('MUNICIPIO');
        $this->db->where('CODMUNICIPIO', $id);
        $resultado = $this->db->get();
        if ($resultado->num_rows() > 0):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function EmpresasCartera() {
        /* Función que permite realizar la consulta que se encuentra en la tabla temporal EMPRESA_SGVA_TMP, para ser creadas en el Sgva */
        
        
        $this->db->select('');
        $this->db->from('EMPRESA_SGVA_TMP');
        $this->db->where('ORIGEN', 'CARTERA');
        $this->db->where('ESTADO', 0);
        $resultado = $this->db->get();
        if ($resultado->num_rows() > 0):
            $resultado = $resultado->result_array();
            return $resultado; 
        else:
            return FALSE;
        endif;
    }

}

?>
