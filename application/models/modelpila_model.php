<?php

class Modelpila_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function datoscaracterespila($nombre, $desde, $hasta, $longitud, $tipo) {
        $desde1 = $desde - 1;
        $hasta1 = $hasta;

        $longitud = $hasta1 - $desde1;
//        $longitud = $hasta-$desde;
//        var_dump($tipo);
//                die();

        $data = array("NOMBRE_CAMPO" => $nombre,
            "LONGITUD" => $longitud,
            "DESDE" => $desde1,
            "HASTA" => $hasta1,
            "TIPO_ARCHIVO" => $tipo
        );

        $this->db->insert("CAMPOSARCHIVO", $data);
    }

    function administracioncampos() {
        $this->db->select('COD_CAMPOARCHIVO,NOMBRE_CAMPO,LONGITUD,DESDE,HASTA,TIPO_ARCHIVO,OBLIGATORIO,PALABLA_CLAVE');
        $this->db->order_by('DESDE', "asc");
        return $this->db->get('CAMPOSARCHIVO');
    }

    function altertipocero($campo) {
        return $this->db->query("ALTER TABLE REGISTROTIPOCERO ADD " . $campos . " VARCHAR2(50)");
    }

    function altertipodet($campo) {
        return $this->db->query("ALTER TABLE PLANILLAUNICA_DET ADD " . $campos . " VARCHAR2(50)");
    }

    function altertipoenc($campo) {
        return $this->db->query("ALTER TABLE PLANILLAUNICA_ENC ADD " . $campos . " VARCHAR2(50)");
    }

    function altertipo3($campo) {
        return $this->db->query("ALTER TABLE REGISTROTIPO3 ADD " . $campos . " VARCHAR2(50)");
    }

    function administracioncamposarchivos() {
        $this->db->select('NOMBRE_CAMPO,LONGITUD,DESDE,HASTA,OBLIGATORIO,PALABLA_CLAVE');
        $this->db->order_by('DESDE', "asc");
        $this->db->where("TIPO_ARCHIVO", "00000");
        return $this->db->get('CAMPOSARCHIVO');
    }

    function administracioncamposarchivostipo2() {
//       se consulta todos los campos de tipo uno de la administracion

        $this->db->select('NOMBRE_CAMPO,LONGITUD,DESDE,HASTA,OBLIGATORIO,PALABLA_CLAVE');
        $this->db->order_by('DESDE', "asc");
        $this->db->where('TIPO_ARCHIVO', "00001");
        return $this->db->get('CAMPOSARCHIVO');
    }

    function administracioncamposarchivostipocero() {
//       se consulta todos los campos de tipo uno de la administracion

        $this->db->select('NOMBRE_CAMPO,LONGITUD,DESDE,HASTA,OBLIGATORIO,PALABLA_CLAVE');
        $this->db->order_by('DESDE', "asc");
        $this->db->where('TIPO_ARCHIVO', "12345");
        return $this->db->get('CAMPOSARCHIVO');
    }

    function administracioncamposarchivostipo3() {
//       se consulta todos los campos de tipo uno de la administracion

        $this->db->select('NOMBRE_CAMPO,LONGITUD,DESDE,HASTA,OBLIGATORIO,PALABLA_CLAVE');
        $this->db->order_by('DESDE', "asc");
        $this->db->where('TIPO_ARCHIVO', "00031");
        return $this->db->get('CAMPOSARCHIVO');
    }

    function administracioncamposarchivostipo3_1() {
        $this->db->select('NOMBRE_CAMPO,LONGITUD,DESDE,HASTA,OBLIGATORIO,PALABLA_CLAVE');
        $this->db->order_by('DESDE', "asc");
        $this->db->where('TIPO_ARCHIVO', "00036");
        return $this->db->get('CAMPOSARCHIVO');
    }

    function administracioncamposarchivostipo3_2() {
        $this->db->select('NOMBRE_CAMPO,LONGITUD,DESDE,HASTA,OBLIGATORIO,PALABLA_CLAVE');
        $this->db->order_by('DESDE', "asc");
        $this->db->where('TIPO_ARCHIVO', "00039");
        return $this->db->get('CAMPOSARCHIVO');
    }

    function insertacampospila($campos) {
//        var_dump($campos);die;
        $this->db->insert("PLANILLAUNICA_DET", $campos);
    }

    function insertacampospilatipo2($campos) {
        $this->db->insert("PLANILLAUNICA_ENC", $campos);
    }

    function campostablatipocero($campo) {

        $this->db->where("TABLE_NAME", "REGISTROTIPOCERO");
        $this->db->where("COLUMN_NAME", $campo);
        RETURN $this->db->get('ALL_TAB_COLUMNS');
    }

    function campostabla1($campo) {

        $this->db->where("TABLE_NAME", "PLANILLAUNICA_DET");
        $this->db->where("COLUMN_NAME", $campo);
        RETURN $this->db->get('ALL_TAB_COLUMNS');
    }

    function campostabla2($campo) {

        $this->db->where("TABLE_NAME", "PLANILLAUNICA_ENC");
        $this->db->where("COLUMN_NAME", $campo);
        RETURN $this->db->get('ALL_TAB_COLUMNS');
    }

    function campostabla3($campo) {

        $this->db->where("TABLE_NAME", "REGISTROTIPO3");
        $this->db->where("COLUMN_NAME", $campo);
        RETURN $this->db->get('ALL_TAB_COLUMNS');
    }

    function empresas() {
        return $this->db->get('EMPRESA');
    }

    function nit($nit) {
        $this->db->where('CODEMPRESA', $nit);
        return $this->db->get('EMPRESA');
    }

    function razonsocial($tipo, $documento, $planilla, $razon, $fechai, $fechaf, $regional, $operador, $valordesde, $valorhasta, $mesperiodofinal, $annoperiodofinal, $mesperiodoinicial, $annoperiodoinicial) {
        $annoperiodo = $annoperiodoinicial . "-" . $mesperiodoinicial;

        if (!empty($valordesde))
            $this->db->where('REGISTROTIPO3.INGRESO >=', $valordesde);
        if (!empty($valorhasta))
            $this->db->where('REGISTROTIPO3.INGRESO <=', $valorhasta);
        if (!empty($fechai))
            $this->db->where('FECHA__PAGO >=', $fechai);
        if (!empty($fechaf))
            $this->db->where('FECHA__PAGO <=', $fechaf);
        if (!empty($mesperiodoinicial) && !empty($annoperiodoinicial))
            $this->db->where('SUBSTR(PERIDO_PAGO, 0, 7) =', $annoperiodo);

        if (!empty($operador))
            $this->db->where("COD_OPERADOR", $operador);
        if (!empty($tipo))
            $this->db->where("TIPO_DOC_APORTANTE", $tipo);
        if (!empty($documento))
            $this->db->where("N_INDENT_APORTANTE", $documento);
        if (!empty($planilla))
            $this->db->where("N_RADICACION", $planilla);
        if (!empty($razon))
            $this->db->where("NOM_APORTANTE", $razon);

//        $this->db->select('CONCAT(PLANILLAUNICA_ENC.PERIDO_PAGO,'."-1".') AS PERIODOPAGO ');

        $this->db->select('PLANILLAUNICA_ENC.DIG_VERIF_APORTANTE,PLANILLAUNICA_ENC.N_INDENT_APORTANTE,PLANILLAUNICA_ENC.NOM_APORTANTE,PLANILLAUNICA_ENC.N_RADICACION'
                . ',PLANILLAUNICA_ENC.N_TOTAL_EMPLEADOS,PLANILLAUNICA_ENC.PERIDO_PAGO,REGISTROTIPO3.TOTAL_APORTES,REGISTROTIPO3.INGRESO');
        $this->db->join('EMPRESA', 'PLANILLAUNICA_ENC.N_IDENT_CCF_ICBF_=EMPRESA.CODEMPRESA', 'LEFT');
        $this->db->join('REGISTROTIPO3', 'REGISTROTIPO3.COD_CAMPO = PLANILLAUNICA_ENC.COD_PLANILLAUNICA');

//        $this->db->where('ESTADO',3);
        $consulta = $this->db->get('PLANILLAUNICA_ENC');

//       echo $this->db->last_query();DIE;
        return $consulta;
    }

    function regional() {
        $this->db->select("COD_REGIONAL,NOMBRE_REGIONAL");
        return $this->db->get('REGIONAL');
    }

    function cargo() {
        $this->db->select('IDCARGO,NOMBRECARGO');
        return $this->db->get('CARGOS');
    }

    function tipodocumento() {
        $this->db->select("DESCRIPCION,NOMBRETIPODOC");
        $this->db->order_by('NOMBRETIPODOC', 'asc');
        return $this->db->get('TIPODOCUMENTO');
    }

    function consultarempresa($razonsocial, $nit) {

        $this->db->select('PLANILLAUNICA_ENC.NOM_APORTANTE,'
                . 'PLANILLAUNICA_ENC.TIPO_DOC_APORTANTE,'
                . 'PLANILLAUNICA_ENC.N_INDENT_APORTANTE,'
                . 'REGISTROTIPOCERO.TIPO_APORTANTE,'
                . 'REGISTROTIPOCERO.NAT_JURIDICA,'
                . 'PLANILLAUNICA_ENC.TIPO_APORTANTE,'
                . 'PLANILLAUNICA_ENC.FORMA_PRESENTACION,'
                . 'PLANILLAUNICA_ENC.DIREC_CORRESPONDENCIA,'
                . 'PLANILLAUNICA_ENC.COD_CIU_O_MUN,'
                . 'PLANILLAUNICA_ENC.COD_DEPARTAMENTO,'
                . 'REGISTROTIPOCERO.COD_DANE,'
                . 'PLANILLAUNICA_ENC.TELEFONO,'
                . 'PLANILLAUNICA_ENC.FAX,'
                . 'PLANILLAUNICA_ENC.CORREO_ELECTRO,'
                . 'PLANILLAUNICA_ENC.N_REGISTROAPORTANTE,'
                . 'PLANILLAUNICA_ENC.DIG_VERIF_NIT,'
                . 'PLANILLAUNICA_ENC.TIPO_DOC_APORTANTE,'
                . 'PLANILLAUNICA_ENC.NOM_APORTANTE,'
                . 'REGISTROTIPOCERO.FECHA_INICIO,'
                . 'REGISTROTIPOCERO.TIPO_ACCION,'
                . 'REGISTROTIPOCERO.FECHA_TERMINO,'
                . 'PLANILLAUNICA_ENC.COD_OPERADOR,'
                . 'PLANILLAUNICA_ENC.PERIDO_PAGO,'
                . 'PLANILLAUNICA_ENC.TIPO_APORTANTE');

        $this->db->where('N_INDENT_APORTANTE', $nit);
        $this->db->join('REGISTROTIPOCERO', 'REGISTROTIPOCERO.N_IDEN_APORTANTE = PLANILLAUNICA_ENC.N_INDENT_APORTANTE');
        $registro = $this->db->get('PLANILLAUNICA_ENC');

//        echo $this->db->last_query();die;

        return $registro;
    }

    function eliminacionempresa($nit, $razonsocial, $nplanilla) {
        $datos = array("N_INDENT_APORTANTE" => $nit,
            "NOM_APORTANTE" => $razonsocial
//            "N_RADICACION" => $nplanilla
        );

        $this->db->where($datos);
        $modificar = array("ESTADO" => 1);
        $this->db->update('PLANILLAUNICA_ENC', $modificar);
    }

    function masivonit($nit) {
        $this->db->where('CODEMPRESA', $nit);
        $this->db->select('NOMBRE_EMPRESA');
        return $this->db->get('EMPRESA');
    }

    function tipocertifi() {
        $this->db->select("COD_TIPO_CERTIFICADO,NOMBRE_CERTIFICADO");
        return $this->db->get('TIPOCERTIFICADO');
    }

    function inactivacionempresa($campo) {
        foreach ($campo as $key => $value) {
            if($key == 'FECHA_DOCUMENTO')
                $this->db->set($key, "to_date('" . $value . "','dd/mm/yyyy HH24:MI:SS')", false);
            else
                $this->db->set($key, $value);
        }
        $this->db->insert('SOPORTEINACTIVACION');
    }

    function consultaradicado($radicado) {
        $this->db->where('NRO_RADICADO', $radicado);
        $dato = $this->db->get('SOPORTEINACTIVACION');
//        echo $this->db->last_query();
        return $dato;
    }

    function periodosinactivos($campo) {
        $this->db->insert_batch("PERIODOSINACTIVOS", $campo);
    }

    function eliminaperiodos($empresa) {
        $this->db->where('COD_EMPRESA', $empresa);
        $data = array('ESTADO' => 2
        );
        $this->db->update('PERIODOSINACTIVOS', $data);
    }

    function consultaempresapila($planilla) {
        $this->db->select('     PLANILLAUNICA_DET.TIPO_IDENT_COTIZ,
                                PLANILLAUNICA_DET.N_IDENT_COTIZ,
                                PLANILLAUNICA_DET.TIPO_COTIZ, 
                                PLANILLAUNICA_DET.PRIMER_APELLIDO,
                                PLANILLAUNICA_DET.SEGUN_APELLIDO,
                                PLANILLAUNICA_DET.PRIMER_NOMBRE,
                                PLANILLAUNICA_DET.SEGUN_NOMBRE,
                                PLANILLAUNICA_DET.TARIFA,
                                PLANILLAUNICA_DET.APORTE_OBLIG,
                                PLANILLAUNICA_DET.SALARIO_BASICO,
                                PLANILLAUNICA_DET.ING_BASE_COTIZ,
                                PLANILLAUNICA_DET.DIAS_COTIZ,
                                PLANILLAUNICA_DET.COD_DEPARTA_UBI_LAB,
                                PLANILLAUNICA_DET.COD_MUNI_UBI_LAB_,
                                PLANILLAUNICA_DET.ING,
                                PLANILLAUNICA_DET.RET,
                                PLANILLAUNICA_DET.VST,
                                PLANILLAUNICA_DET.VARIACIONES ');

        $this->db->join("PLANILLAUNICA_DET", "PLANILLAUNICA_DET.COD_PLANILLAUNICA = PLANILLAUNICA_ENC.COD_PLANILLAUNICA");
        $this->db->join("REGISTROTIPO3", "REGISTROTIPO3.COD_CAMPO = PLANILLAUNICA_ENC.COD_PLANILLAUNICA");
        $this->db->where("N_RADICACION", $planilla);
        $consulta = $this->db->get('PLANILLAUNICA_ENC');
        return $consulta;
    }

    function consultabanco($planilla) {
        $this->db->select('PLANILLAUNICA_ENC.N_INDENT_APORTANTE,PLANILLAUNICA_ENC.NOM_APORTANTE,PLANILLAUNICA_ENC.N_RADICACION');
        $this->db->join("PLANILLAUNICA_DET", "PLANILLAUNICA_DET.COD_PLANILLAUNICA = PLANILLAUNICA_ENC.COD_PLANILLAUNICA");
        $this->db->join("REGISTROTIPO3", "REGISTROTIPO3.COD_CAMPO = PLANILLAUNICA_ENC.COD_PLANILLAUNICA");
        $this->db->where("N_RADICACION", $planilla);
        $consulta = $this->db->get('PLANILLAUNICA_ENC');
        return $consulta;
    }

    function consultatipo3($planilla) {
        $this->db->select('REGISTROTIPO3.APORTE_OBLIG,REGISTROTIPO3.TIPO_REGISTRO,PLANILLAUNICA_ENC.DIAS_MORA,REGISTROTIPO3.MORA_APORTES,REGISTROTIPO3.TOTAL_APORTES');
        $this->db->distinct('REGISTROTIPO3.TIPO_REGISTRO,PLANILLAUNICA_ENC.DIAS_MORA,REGISTROTIPO3.MORA_APORTES,REGISTROTIPO3.TOTAL_APORTES');
        $this->db->join("PLANILLAUNICA_DET", "PLANILLAUNICA_DET.COD_PLANILLAUNICA = PLANILLAUNICA_ENC.COD_PLANILLAUNICA");
        $this->db->join("REGISTROTIPO3", "REGISTROTIPO3.COD_CAMPO = PLANILLAUNICA_ENC.COD_PLANILLAUNICA");
        $this->db->where("N_RADICACION", $planilla);
        $consulta = $this->db->get('PLANILLAUNICA_ENC');
        return $consulta;
    }

    function modificarobligatoriedad($datos, $obligatorio, $palabraobligatoria) {
        $this->db->where($datos);
        $dato = array('OBLIGATORIO' => $obligatorio,
            'PALABLA_CLAVE' => $palabraobligatoria
        );
        $this->db->update('CAMPOSARCHIVO', $dato);
    }

    function guardagarantia($campos) {
        $this->db->insert_batch("GARANTIA_ACUERDO", $campos);
    }

    function todasgarantiassegunacuerdo($acuerdo) {
//-----------------------------------------------------------------------------
//POR: Gerson Javier Barbosa        
//Objetivo : Se consultan las Garantias para la vista acuerdodepagoporpagos
//Fecha : 04/03/2014        
//------------------------------------------------------------------------------      
        $this->db->select('GARANTIA_ACUERDO.COD_ACUERDO_PAGO,GARANTIA_ACUERDO.VALOR_CAMPO,GARANTIA_ACUERDO.VALOR_AVALUO,GARANTIA_ACUERDO.VALOR_COMERCIAL,'
                . 'CAMPOSGARANTIA.NOMBRE_CAMPO,TIPOGARANTIA.NOMBRE_TIPOGARANTIA');
        $this->db->join('CAMPOSGARANTIA', "GARANTIA_ACUERDO.COD_CAMPO = CAMPOSGARANTIA.COD_CAMPO");
        $this->db->join('TIPOGARANTIA', 'TIPOGARANTIA.COD_TIPO_GARANTIA =  GARANTIA_ACUERDO.COD_TIPO_GARANTIA"');
        $this->db->where("COD_ACUERDO_PAGO", $acuerdo);
        return $this->db->get("GARANTIA_ACUERDO");
    }

    function aportes($nit) {
//        $this->db->join('REGIONAL','REGIONAL.COD_DEPARTAMENTO=PLANILLAUNICA_ENC.COD_DEPARTAMENTO');
        $this->db->where("N_INDENT_APORTANTE", $nit);
        return $this->db->get('PLANILLAUNICA_ENC');
    }

    function obra($nit) {
        $this->db->where("N_INDENT_APORTANTE", $nit);
        return $this->db->get('PLANILLAUNICA_ENC');
    }

    function resolucion($nit) {
        $this->db->where("N_INDENT_APORTANTE", $nit);
        return $this->db->get('PLANILLAUNICA_ENC');
    }

    function periodo($nit) {
        $this->db->where("N_INDENT_APORTANTE", $nit);
        return $this->db->get('PLANILLAUNICA_ENC');
    }

    function tributario($nit) {
        $this->db->where("N_INDENT_APORTANTE", $nit);
        $consulta = $this->db->get('PLANILLAUNICA_ENC');
//         echo $this->db->last_query();die;
        return $consulta;
    }

    function deuda($nit) {
        $this->db->where("N_INDENT_APORTANTE", $nit);
        return $this->db->get('PLANILLAUNICA_ENC');
    }

    function visualizartipouno($archivo) {
        $this->db->where('ARCHIVO', $archivo);
        $tipo = $this->db->get('PLANILLAUNICA_ENC');
        return $tipo;
    }

    function visualizartipodos($archivo) {
        $this->db->where('ARCHIVO', $archivo);
        $tipo = $this->db->get('PLANILLAUNICA_DET');
        return $tipo;
    }

    function visualizartipotres($archivo) {
        $this->db->where('ARCHIVO', $archivo);
        $tipo = $this->db->get('REGISTROTIPO3');
        return $tipo;
    }

    function visualizartipocero($archivo) {
        $this->db->where('ARCHIVO', $archivo);
        $tipo = $this->db->get('REGISTROTIPOCERO');
        return $tipo;
    }

    function consultainactivacion($codempresa) {
        $this->db->where('COD_EMPRESA', $codempresa);
        $this->db->where('ESTADO', 1);
        $datos = $this->db->get('PERIODOSINACTIVOS');
        return $datos->result_array();
    }

    function Cargarpila($archivo) {
        $dbname = $this->db->database;
        $dbuser = $this->db->username;      //db user with all priviliges
        $dbpassword = $this->db->password;   // password of user
        $dbConnString = $this->db->hostname;    // connection string for this we must create TNS entry for Oracle

        $v_oDataConn = oci_connect($dbuser, $dbpassword, $dbConnString);
        if (!$v_oDataConn) {
            $v_oErroCntr = oci_error();
            trigger_error(htmlentities($v_oErroCntr['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $query = "BEGIN SENA_CAPACITACION.PKG_Planilla.Carga_Archivo('PILA_DIR', '" . $archivo . "', ".$this->ion_auth->user()->row()->IDUSUARIO.",:pio_Message_Line); END;";
        $v_cMessErro = "";
        $v_oStnd_Out = oci_parse($v_oDataConn, $query) or die('Can not parse query');
        oci_bind_by_name($v_oStnd_Out, ":pio_Message_Line", $v_cMessErro, 32000) or die('Can not bind variable');
        oci_execute($v_oStnd_Out);
        oci_close($v_oDataConn);
        return $v_cMessErro;
    }

    function razonsocial2($term) {
        if (!empty($term)) :
            $this->db->select('CODEMPRESA, UPPER(RAZON_SOCIAL) AS RAZON_SOCIAL');
            $this->db->like('CODEMPRESA', $term);
            $this->db->or_like('RAZON_SOCIAL', $term);
            $datos = $this->db->get('EMPRESA'); //echo $this->db->last_query();
            return $datos->result_array();
        endif;
        return NULL;
    }
    
    function planillasencero( $fechaini='', $fechafin='' ){
        if($fechaini != '')  $this->db->where("PE.FECHA__PAGO >= '" . $fechaini . "'" );
        if($fechafin != '')  $this->db->where("PE.FECHA__PAGO <= '" . $fechafin . "'" );
        $this->db->from('PLANILLAUNICA_ENC PE, REGISTROTIPO3 R3');
        $this->db->where('R3.TOTAL_APORTES', '0000000000');
        $this->db->where("PE.COD_PLANILLAUNICA = R3.COD_CAMPO");
        $consulta = $this->db->get();
        return $consulta;
    }

}
