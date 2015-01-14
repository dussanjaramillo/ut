<?php

class Consultaprocesos_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    /**
     * Funcion getprocesosdaf
     * Obtiene todos los procesos de direccion administrativa y financiera
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     * @return Retorna los procesos de direccion administrativa y financiera
     */
    function getprocesosdaf() {
        $this->db->select('COD_TIPO_PROCESO, TIPO_PROCESO');
        $this->db->where('AREA', 'A');
        $this->db->order_by("TIPO_PROCESO", "DESC");
        $query = $this->db->get('TIPOPROCESO');
//        echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
    }

    /**
     * Funcion getprocesosdj
     * Obtiene todos los procesos de direccion Juridica
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     * @return Retorna los procesos de direccion juridica
     */
    function getprocesosdj() {
        $this->db->select('COD_TIPO_PROCESO, TIPO_PROCESO');
        $this->db->where('AREA', 'J');
        $this->db->order_by("TIPO_PROCESO", "DESC");
        $query = $this->db->get('TIPOPROCESO');
//        echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
    }

//    function gettraza() {
//        $this->db->select('GESTIONCOBRO.COD_FISCALIZACION_EMPRESA, EMPRESA.CODEMPRESA, EMPRESA.NOMBRE_EMPRESA, EMPRESA.TELEFONO_FIJO,EMPRESA.DIRECCION, EMPRESA.COD_DEPARTAMENTO,DEPARTAMENTO.NOM_DEPARTAMENTO,EMPRESA.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, EMPRESA.COD_REGIMEN,REGIMENTRIBUTARIO.DESC_REGIMEN,EMPRESA.CIIU');
//        $query = $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA = GESTIONCOBRO.NIT_EMPRESA');
//        $query = $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL = EMPRESA.COD_REGIONAL', 'LEFT');
//        $query = $this->db->join('DEPARTAMENTO', 'DEPARTAMENTO.COD_DEPARTAMENTO = EMPRESA.COD_DEPARTAMENTO', 'LEFT');
//        $query = $this->db->join('REGIMENTRIBUTARIO', 'REGIMENTRIBUTARIO.CODREGIMEN = EMPRESA.COD_REGIMEN', 'LEFT');
//        $query = $this->db->join('TIPOGESTION', 'TIPOGESTION.COD_GESTION = GESTIONCOBRO.COD_TIPOGESTION');
//        $query = $this->db->join('PROCESO', 'PROCESO.CODPROCESO = TIPOGESTION.CODPROCESO');
//        $query = $this->db->group_by('GESTIONCOBRO.COD_FISCALIZACION_EMPRESA, EMPRESA.CODEMPRESA, EMPRESA.NOMBRE_EMPRESA, EMPRESA.TELEFONO_FIJO,EMPRESA.DIRECCION, EMPRESA.COD_DEPARTAMENTO,DEPARTAMENTO.NOM_DEPARTAMENTO,EMPRESA.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, EMPRESA.COD_REGIMEN,REGIMENTRIBUTARIO.DESC_REGIMEN,EMPRESA.CIIU');
//        $query = $this->db->get('GESTIONCOBRO');
//        echo $this->db->last_query();
//        return $query;
//    }

    function gettraza() {
        $query = $this->db->query( "SELECT DISTINCT VISTA_ORIGEN,COD_PROCESO_COACTIVO,TITULO,NIT,NOMEMPRESA,TELEFONO,DIRECCION,DEPARTAMENTO,NOMDEPARTAMENTO,REGIONAL,NOMREGIONAL,
                           REGIMEN,NOMREGIMEN,CIIU,CODIGOPJ,FECHA_CONSULTAONBASE,FECHA_RESOLUCION,FECHA_EJECUTORIA,CORREO_ELECTRONICO FROM VW_TRAZA_ENC");
       
//        echo $this->db->last_query();
        return $query;
    }

    function gettrazanomisional() {
        $query = $this->db->query("SELECT COD_CARTERA_NOMISIONAL AS CODCARTERA, 
            COD_EMPRESA AS CODEMPRESA, 
            CNM_EMPRESA.RAZON_SOCIAL AS NOMEMPRESA, 
            CNM_EMPRESA.TELEFONO AS TELEFONO,
            CNM_EMPRESA.DIRECCION AS DIRECCION,
            CNM_EMPRESA.COD_REGIONAL AS CODREGIONAL,
            CNM_EMPRESA.CORREO_ELECTRONICO AS EMAIL,
            REGIONAL.NOMBRE_REGIONAL AS NOMREGIONAL
            FROM 
            CNM_CARTERANOMISIONAL
            INNER JOIN CNM_EMPRESA ON CNM_EMPRESA.COD_ENTIDAD = CNM_CARTERANOMISIONAL.COD_EMPRESA
            INNER JOIN REGIONAL ON REGIONAL.COD_REGIONAL = CNM_EMPRESA.COD_REGIONAL
            UNION
            SELECT COD_CARTERA_NOMISIONAL AS CODCARTERA, 
            TO_CHAR(CNM_CARTERANOMISIONAL.COD_EMPLEADO) AS CODEMPRESA, 
            CNM_EMPLEADO.NOMBRES||' '||CNM_EMPLEADO.APELLIDOS AS NOMEMPRESA, 
            CNM_EMPLEADO.TELEFONO AS TELEFONO,
            CNM_EMPLEADO.DIRECCION AS DIRECCION,
            CNM_EMPLEADO.COD_REGIONAL AS CODREGIONAL,
            CNM_EMPLEADO.CORREO_ELECTRONICO AS EMAIL,
            REGIONAL.NOMBRE_REGIONAL AS NOMREGIONAL
            FROM 
            CNM_CARTERANOMISIONAL
            INNER JOIN CNM_EMPLEADO ON CNM_EMPLEADO.IDENTIFICACION = CNM_CARTERANOMISIONAL.COD_EMPLEADO
            INNER JOIN REGIONAL ON REGIONAL.COD_REGIONAL = CNM_EMPLEADO.COD_REGIONAL
            ");
//        echo $this->db->last_query();
        return $query;
    }

    function gettrazajudicial() {
        $this->db->select('TITULOSJUDICIALES.COD_TITULO,TITULOSJUDICIALES.COD_PROPIETARIO,TITULOSJUDICIALES.NOMBRE_PROPIETARIO,TITULOSJUDICIALES.CORREO_PROPIETARIO,
TITULOSJUDICIALES.DIRECCION_INMUEBLE,DEPARTAMENTO.NOM_DEPARTAMENTO, REGIONAL.NOMBRE_REGIONAL, EMPRESA.CIIU,REGIONAL.COD_REGIONAL,DEPARTAMENTO.COD_DEPARTAMENTO');
        $query = $this->db->join('DEPARTAMENTO', 'DEPARTAMENTO.COD_DEPARTAMENTO = TITULOSJUDICIALES.COD_DEPARTAMENTO');
        $query = $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL = TITULOSJUDICIALES.COD_REGIONAL');
        $query = $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA = TITULOSJUDICIALES.COD_PROPIETARIO', 'LEFT');
        $query = $this->db->group_by('TITULOSJUDICIALES.COD_TITULO,TITULOSJUDICIALES.COD_PROPIETARIO,TITULOSJUDICIALES.NOMBRE_PROPIETARIO,TITULOSJUDICIALES.CORREO_PROPIETARIO,
TITULOSJUDICIALES.DIRECCION_INMUEBLE,DEPARTAMENTO.NOM_DEPARTAMENTO, REGIONAL.NOMBRE_REGIONAL, EMPRESA.CIIU,REGIONAL.COD_REGIONAL,DEPARTAMENTO.COD_DEPARTAMENTO');
        $query = $this->db->get('TITULOSJUDICIALES');
//        echo $this->db->last_query();
        return $query;
    }

    function gettrazadevolucion() {
        $this->db->select('SOLICITUDDEVOLUCION.COD_DEVOLUCION, VW_PERSONAS.CODEMPRESA, VW_PERSONAS.NOMBRE_EMPRESA AS RAZON_SOCIAL, VW_PERSONAS.TELEFONO_FIJO, VW_PERSONAS.DIRECCION, VW_PERSONAS.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO, VW_PERSONAS.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, VW_PERSONAS.COD_REGIMEN, REGIMENTRIBUTARIO.DESC_REGIMEN, VW_PERSONAS.CIIU');
        $query = $this->db->join('VW_PERSONAS', 'VW_PERSONAS.CODEMPRESA = SOLICITUDDEVOLUCION.NIT');
        $query = $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL = VW_PERSONAS.COD_REGIONAL', 'LEFT');
        $query = $this->db->join('DEPARTAMENTO', 'DEPARTAMENTO.COD_DEPARTAMENTO = VW_PERSONAS.COD_DEPARTAMENTO', 'LEFT');
        $query = $this->db->join('REGIMENTRIBUTARIO', 'REGIMENTRIBUTARIO.CODREGIMEN = VW_PERSONAS.COD_REGIMEN', 'LEFT');
        $query = $this->db->group_by('SOLICITUDDEVOLUCION.COD_DEVOLUCION, VW_PERSONAS.CODEMPRESA, VW_PERSONAS.NOMBRE_EMPRESA, VW_PERSONAS.TELEFONO_FIJO, VW_PERSONAS.DIRECCION, VW_PERSONAS.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO, VW_PERSONAS.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, VW_PERSONAS.COD_REGIMEN, REGIMENTRIBUTARIO.DESC_REGIMEN, VW_PERSONAS.CIIU');
        $query = $this->db->get('SOLICITUDDEVOLUCION');
//        echo $this->db->last_query();
        return $query;
    }

//    function actulizatraza($empresa, $ciudad, $nombre, $regional, $proceso, $fiscaliza) {
//        $this->db->select('GESTIONCOBRO.COD_FISCALIZACION_EMPRESA, EMPRESA.CODEMPRESA, EMPRESA.NOMBRE_EMPRESA, EMPRESA.TELEFONO_FIJO,EMPRESA.DIRECCION, EMPRESA.COD_DEPARTAMENTO,DEPARTAMENTO.NOM_DEPARTAMENTO,EMPRESA.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, EMPRESA.COD_REGIMEN,REGIMENTRIBUTARIO.DESC_REGIMEN,EMPRESA.CIIU');
//        $query = $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA = GESTIONCOBRO.NIT_EMPRESA');
//        $query = $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL = EMPRESA.COD_REGIONAL', 'LEFT');
//        $query = $this->db->join('DEPARTAMENTO', 'DEPARTAMENTO.COD_DEPARTAMENTO = EMPRESA.COD_DEPARTAMENTO', 'LEFT');
//        $query = $this->db->join('REGIMENTRIBUTARIO', 'REGIMENTRIBUTARIO.CODREGIMEN = EMPRESA.COD_REGIMEN', 'LEFT');
//        $query = $this->db->join('TIPOGESTION', 'TIPOGESTION.COD_GESTION = GESTIONCOBRO.COD_TIPOGESTION');
//        $query = $this->db->join('PROCESO', 'PROCESO.CODPROCESO = TIPOGESTION.CODPROCESO');
//        $query = $this->db->where('GESTIONCOBRO.NIT_EMPRESA', $empresa);
//        $query = $this->db->or_where('EMPRESA.COD_DEPARTAMENTO', $ciudad);
////        $query = $this->db->or_where('UPPER(EMPRESA.NOMBRE_EMPRESA)', $nombre);
//        $query = $this->db->or_where('EMPRESA.COD_REGIONAL', $regional);
//        //$query = $this->db->or_where('TIPOGESTION.CODPROCESO', $proceso);
//        $query = $this->db->or_where('COD_FISCALIZACION_EMPRESA', $fiscaliza);
//
//        $tal = '?';
//        if ($ciudad != '' || $empresa != '' || $fiscaliza != '' || $proceso != '' || $fiscaliza != '' || $regional != '') {
//            $query = $this->db->or_where("UPPER(EMPRESA.NOMBRE_EMPRESA) like '%" . $tal . "%' and 1=", 1, FALSE);
//        } else {
//            $query = $this->db->or_where("UPPER(EMPRESA.NOMBRE_EMPRESA) like '%" . $nombre . "%' and 1=", 1, FALSE);
//        }
//        $query = $this->db->group_by('GESTIONCOBRO.COD_FISCALIZACION_EMPRESA, EMPRESA.CODEMPRESA, EMPRESA.NOMBRE_EMPRESA, EMPRESA.TELEFONO_FIJO,EMPRESA.DIRECCION, EMPRESA.COD_DEPARTAMENTO,DEPARTAMENTO.NOM_DEPARTAMENTO,EMPRESA.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, EMPRESA.COD_REGIMEN,REGIMENTRIBUTARIO.DESC_REGIMEN,EMPRESA.CIIU');
//        $query = $this->db->get('GESTIONCOBRO');
//        echo $this->db->last_query();
//        return $query;
//    }

    function actulizatraza($empresa, $ciudad, $nombre, $regional, $proceso, $fiscaliza, $usuario, $usuar, $coactivo, $origen) {
        $this->db->select('DISTINCT VISTA_ORIGEN,COD_PROCESO_COACTIVO,TITULO,NIT,NOMEMPRESA,TELEFONO,DIRECCION,DEPARTAMENTO,NOMDEPARTAMENTO,REGIONAL,NOMREGIONAL,
                            REGIMEN,NOMREGIMEN,CIIU,CODIGOPJ,FECHA_CONSULTAONBASE,FECHA_RESOLUCION,FECHA_EJECUTORIA,CORREO_ELECTRONICO',FALSE);
        //echo $origen;die();
        if($empresa==0){
            $empre = '0';
        }else{
            $empre = $empresa;
        }
        $query = $this->db->where('VW_TRAZA_ENC.NIT', $empre);
        
        if($coactivo==0){
            $coa = '0';
        }else{
            $coa = $coactivo;
        }
        $query = $this->db->or_where('VW_TRAZA_ENC.CODIGOPJ', $coa);
        if($ciudad==0){
            $ciu = '0';
        }else{
            $ciu = $empresa;
        }
        
        $query = $this->db->or_where('VW_TRAZA_ENC.DEPARTAMENTO', $ciu);
//        $query = $this->db->or_where('UPPER(EMPRESA.NOMBRE_EMPRESA)', $nombre);
//        $query = $this->db->or_where('EMPRESA.COD_REGIONAL', $regional);
        //$query = $this->db->or_where('TIPOGESTION.CODPROCESO', $proceso);
        //echo $fiscaliza;die();
        if($fiscaliza==0){
            $fisca = '0';
        }else{
            $fisca = $fiscaliza;
        }
        
        $query = $this->db->or_where('VW_TRAZA_ENC.COD_PROCESO_COACTIVO', $fisca);
        $query = $this->db->or_where('VW_TRAZA_ENC.TITULO', $fisca);
        
        if($origen ==''){
            $orig = '0';
        }else{
            $orig = $origen;
        }
        $query = $this->db->or_where('VW_TRAZA_ENC.VISTA_ORIGEN', $orig);
        
        
        if($regional==0){
            $reg = '0';
        }else{
            $reg = $regional;
        }
        
        if($usuario==0){
            $usu = '0';
        }else{
            $usu = $usuario;
        }
        
        if ($usuario == 0) {
            $query = $this->db->or_where('VW_TRAZA_ENC.REGIONAL', $reg);
            $query = $this->db->or_where('VW_TRAZA_ENC.IDUSUARIO', $usu);
            
        } else {
            $query = $this->db->or_where('VW_TRAZA_ENC.REGIONAL', $reg);
            $query = $this->db->where('VW_TRAZA_ENC.IDUSUARIO', $usu);
            
        }
        //$query = $this->db->or_where('TIPOGESTION.CODPROCESO', $proceso);


        $tal = '?';
        if ($ciudad != '' || $empresa != '' || $fiscaliza != '' || $proceso != '' || $fiscaliza != '' || $regional != '' || $usuario != 0 || $usuar != ''|| $coactivo!=''|| $origen!='') {
            $query = $this->db->or_where("UPPER(VW_TRAZA_ENC.NOMEMPRESA) like '%" . $tal . "%' and 1=", 1, FALSE);
//            $query = $this->db->or_where("USUARIOS.NOMBRES like '%" . $tal . "%' and 1=", 1, FALSE);
        } else {
            $query = $this->db->or_where("UPPER(VW_TRAZA_ENC.NOMEMPRESA) like '%" . $nombre . "%' and 1=", 1, FALSE);
//            $query = $this->db->or_where("USUARIOS.NOMBRES like '%" . $usuar . "%') and 1=", 1, FALSE);
        }

        $tal = '?';
        if ($ciudad != '' || $empresa != '' || $fiscaliza != '' || $proceso != '' || $fiscaliza != '' || $regional != '' || $usuario != 0 || $nombre != ''|| $coactivo!=''|| $origen!='') {
            $query = $this->db->or_where("UPPER(VW_TRAZA_ENC.NOMBRE_USUARIO) like '%" . $tal . "%' and 1=", 1, FALSE);
            
        } else {
            $query = $this->db->or_where("UPPER(VW_TRAZA_ENC.NOMBRE_USUARIO) like REPLACE('$usuar',' ','%') and 1=", 1, FALSE);
        }
        $query = $this->db->get('VW_TRAZA_ENC');
//        echo $this->db->last_query();
        return $query;
    }

    function actulizatrazadevolucion($empresa, $ciudad, $nombre, $regional, $devolucion, $usuario, $usuar) {
        $this->db->select('SOLICITUDDEVOLUCION.COD_DEVOLUCION, VW_PERSONAS.CODEMPRESA, VW_PERSONAS.NOMBRE_EMPRESA AS RAZON_SOCIAL, VW_PERSONAS.TELEFONO_FIJO, VW_PERSONAS.DIRECCION, VW_PERSONAS.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO, VW_PERSONAS.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, VW_PERSONAS.COD_REGIMEN, REGIMENTRIBUTARIO.DESC_REGIMEN, VW_PERSONAS.CIIU');
        $query = $this->db->join('VW_PERSONAS', 'VW_PERSONAS.CODEMPRESA = SOLICITUDDEVOLUCION.NIT ','LEFT');
        $query = $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL = VW_PERSONAS.COD_REGIONAL', 'LEFT');
        $query = $this->db->join('DEPARTAMENTO', 'DEPARTAMENTO.COD_DEPARTAMENTO = VW_PERSONAS.COD_DEPARTAMENTO', 'LEFT');
        $query = $this->db->join('REGIMENTRIBUTARIO', 'REGIMENTRIBUTARIO.CODREGIMEN = VW_PERSONAS.COD_REGIMEN', 'LEFT');
        $query = $this->db->join('TRAZAPROCJUDICIAL', 'TRAZAPROCJUDICIAL.COD_DEVOLUCION = SOLICITUDDEVOLUCION.COD_DEVOLUCION', 'LEFT');
        $query = $this->db->join('USUARIOS', 'USUARIOS.IDUSUARIO = TRAZAPROCJUDICIAL.COD_USUARIO', 'LEFT');
        $query = $this->db->where('VW_PERSONAS.CODEMPRESA', $empresa);
        $query = $this->db->or_where('VW_PERSONAS.COD_DEPARTAMENTO', $ciudad);
//        $query = $this->db->or_where('UPPER(EMPRESA.NOMBRE_EMPRESA)', $nombre);
//        $query = $this->db->or_where('EMPRESA.COD_REGIONAL', $regional);
        //$query = $this->db->or_where('TIPOGESTION.CODPROCESO', $proceso);
        $query = $this->db->or_where('SOLICITUDDEVOLUCION.COD_DEVOLUCION', $devolucion);

        if ($usuario == 0) {
            $query = $this->db->or_where('VW_PERSONAS.COD_REGIONAL', $regional);
            $query = $this->db->or_where('TRAZAPROCJUDICIAL.COD_USUARIO', $usuario);
        } else {
            $query = $this->db->or_where('VW_PERSONAS.COD_REGIONAL', $regional);
            $query = $this->db->where('TRAZAPROCJUDICIAL.COD_USUARIO', $usuario);
        }
        //$query = $this->db->or_where('TIPOGESTION.CODPROCESO', $proceso);


        $tal = '?';
        if ($ciudad != '' || $empresa != '' || $devolucion != '' || $devolucion != '' || $regional != '' || $usuario != 0 || $usuar != '') {
            $query = $this->db->or_where("UPPER(VW_PERSONAS.NOMBRE_EMPRESA) like '%" . $tal . "%' and 1=", 1, FALSE);
//            $query = $this->db->or_where("USUARIOS.NOMBRES like '%" . $tal . "%' and 1=", 1, FALSE);
        } else {
            $query = $this->db->or_where("UPPER(VW_PERSONAS.NOMBRE_EMPRESA) like '%" . $nombre . "%' and 1=", 1, FALSE);
//            $query = $this->db->or_where("USUARIOS.NOMBRES like '%" . $usuar . "%') and 1=", 1, FALSE);
        }

        $tal = '?';
        if ($ciudad != '' || $empresa != '' || $devolucion != '' || $devolucion != '' || $regional != '' || $usuario != 0 || $nombre != '') {
            $query = $this->db->or_where("UPPER(USUARIOS.NOMBRES) like '%" . $tal . "%' and 1=", 1, FALSE);
            $query = $this->db->or_where("UPPER(USUARIOS.APELLIDOS) like '%" . $tal . "%' and 1=", 1, FALSE);
        } else {
            $nom = '';
            $noms = '';

            $nombreapellido = explode(' ', $usuar);
//            print_r($nombreapellido);
            for ($i = 0; $i <= count($nombreapellido) - 1; $i++):
                $nom = $nombreapellido[$i];
                $noms = "UPPER(USUARIOS.NOMBRES) like" . "'%" . $nom . "%' and 1=1 OR UPPER(USUARIOS.APELLIDOS) like" . "'%" . $nom . "%' and 1=";
                $query = $this->db->or_where($noms, 1, FALSE);
            endfor;
        }

        $query = $this->db->group_by('SOLICITUDDEVOLUCION.COD_DEVOLUCION, VW_PERSONAS.CODEMPRESA, VW_PERSONAS.NOMBRE_EMPRESA, VW_PERSONAS.TELEFONO_FIJO, VW_PERSONAS.DIRECCION, VW_PERSONAS.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO, VW_PERSONAS.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, VW_PERSONAS.COD_REGIMEN, REGIMENTRIBUTARIO.DESC_REGIMEN, VW_PERSONAS.CIIU');
        $query = $this->db->get('SOLICITUDDEVOLUCION');
//        echo $this->db->last_query();
        return $query;
    }

    function actualizatrazanomisional($empresa, $ciudad, $nombre, $regional, $proceso, $fiscaliza, $usuario, $usuar) {
        if ($usuario == 0) {
            $where1 = "(CNM_CARTERANOMISIONAL.COD_EMPLEADO = '" . $empresa . "' " .
                    "OR CNM_EMPLEADO.COD_REGIONAL = '" . $regional .
                    "'OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM_EMPLEADO.NOMBRES||' '||CNM_EMPLEADO.APELLIDOS) like '%?%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%')";
        } else {
            $where1 = "(CNM_CARTERANOMISIONAL.COD_EMPLEADO = '" . $empresa . "' " .
                    "OR (CNM_EMPLEADO.COD_REGIONAL = '" . $regional .
                    "' AND TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "') " .
                    "OR CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM_EMPLEADO.NOMBRES||' '||CNM_EMPLEADO.APELLIDOS) like '%?%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%')";
        }

        if ($nombre != '') {

            $where1 = "(CNM_CARTERANOMISIONAL.COD_EMPLEADO = '" . $empresa . "' " .
                    "OR CNM_EMPLEADO.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM_EMPLEADO.NOMBRES||' '||CNM_EMPLEADO.APELLIDOS) LIKE '%' || REPLACE('$nombre',' ','%') || '%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%')";
//            echo $where;die();
        }
//        else {
//            $where1 = "PC.IDENTIFICACION = '" . $empresa . "' " .
//                    "OR CNM.COD_REGIONAL = '" . $regional . "'" .
//                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
//                    "OR PC.COD_PROCESOPJ = '" . $fiscaliza . "' " .
//                    "OR UPPER(CNM.NOMBRES||' '||CNM.APELLIDOS) like '%?%'"
//                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
//                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%'";
//        }

        if ($usuar != '') {

            $nom = '';
            $noms = '';
//            echo $usuar;die();

            $nombreapellido = explode(' ', $usuar);
            // print_r($nombreapellido);die();
            for ($i = 0; $i <= count($nombreapellido) - 1; $i++):
                $nom = $nombreapellido[$i];
                $noms = $noms . " OR UPPER(USUARIOS.NOMBRES) like '%" . $nom . "%'"
                        . "OR UPPER(USUARIOS.APELLIDOS) like '%" . $nom . "%'";

//                echo $noms;die();
            endfor;

//            echo $noms;die();

            $where1 = "(CNM_CARTERANOMISIONAL.COD_EMPLEADO = '" . $empresa . "' " .
                    "OR CNM_EMPLEADO.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM_EMPLEADO.NOMBRES||' '||CNM_EMPLEADO.APELLIDOS) like '%?%' " .
                    $noms . ")";
        }

        if ($ciudad == '' && $empresa == '' && $proceso == '' && $fiscaliza == '' && $regional == '' && $usuario == 0 && $nombre == '' && $usuar == '') {
            $where1 = "UPPER(CNM_EMPLEADO.NOMBRES||' '||CNM_EMPLEADO.APELLIDOS) like '%" . $nombre . "%'";
        }

        if ($usuario == 0) {
            $where2 = "(CNM_CARTERANOMISIONAL.COD_EMPRESA = '" . $empresa . "' " .
                    "OR CNM_EMPRESA.COD_REGIONAL = '" . $regional .
                    "' OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM_EMPRESA.RAZON_SOCIAL) like '%?%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%')";
        } else {
            $where2 = "(CNM_CARTERANOMISIONAL.COD_EMPRESA = '" . $empresa . "' " .
                    "OR (CNM_EMPRESA.COD_REGIONAL = '" . $regional .
                    "' AND TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "') " .
                    "OR CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM_EMPRESA.RAZON_SOCIAL) like '%?%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%')";
        }

        if ($nombre != '') {

            $where2 = "(CNM_CARTERANOMISIONAL.COD_EMPRESA = '" . $empresa . "' " .
                    "OR CNM_EMPRESA.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM_EMPRESA.RAZON_SOCIAL) like '%" . $nombre . "%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%')";
//            echo $where;die();
        }
//        else {
//            $where2 = "PC.IDENTIFICACION = '" . $empresa . "' " .
//                    "OR CNM.COD_REGIONAL = '" . $regional . "'" .
//                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
//                    "OR PC.COD_PROCESOPJ = '" . $fiscaliza . "' " .
//                    "OR UPPER(CNM.RAZON_SOCIAL) like '%?%'"
//                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
//                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%'";
//        }

        if ($usuar != '') {

            $nom = '';
            $noms = '';
//            echo $usuar;die();

            $nombreapellido = explode(' ', $usuar);
            // print_r($nombreapellido);die();
            for ($i = 0; $i <= count($nombreapellido) - 1; $i++):
                $nom = $nombreapellido[$i];
                $noms = $noms . " OR UPPER(USUARIOS.NOMBRES) like '%" . $nom . "%'"
                        . "OR UPPER(USUARIOS.APELLIDOS) like '%" . $nom . "%'";

//                echo $noms;die();
            endfor;

//            echo $noms;die();

            $where2 = "(CNM_CARTERANOMISIONAL.COD_EMPRESA = '" . $empresa . "' " .
                    "OR CNM_EMPRESA.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM_EMPRESA.RAZON_SOCIAL) like '%?%' " . $noms . ")";
        }

        if ($ciudad == '' && $empresa == '' && $proceso == '' && $fiscaliza == '' && $regional == '' && $usuario == 0 && $nombre == '' && $usuar == '') {
            $where2 = "UPPER(CNM_EMPRESA.RAZON_SOCIAL) like '%" . $nombre . "%'";
        }

        $query = $this->db->query("SELECT COD_CARTERA_NOMISIONAL AS CODCARTERA, 
            COD_EMPRESA AS CODEMPRESA, 
            CNM_EMPRESA.RAZON_SOCIAL AS NOMEMPRESA, 
            CNM_EMPRESA.TELEFONO AS TELEFONO,
            CNM_EMPRESA.DIRECCION AS DIRECCION,
            CNM_EMPRESA.COD_REGIONAL AS CODREGIONAL,
            CNM_EMPRESA.CORREO_ELECTRONICO AS EMAIL,
            REGIONAL.NOMBRE_REGIONAL AS NOMREGIONAL
            FROM 
            CNM_CARTERANOMISIONAL
            INNER JOIN CNM_EMPRESA ON CNM_EMPRESA.COD_ENTIDAD = CNM_CARTERANOMISIONAL.COD_EMPRESA
            INNER JOIN REGIONAL ON REGIONAL.COD_REGIONAL = CNM_EMPRESA.COD_REGIONAL
            LEFT JOIN TRAZAPROCJUDICIAL ON TRAZAPROCJUDICIAL.COD_CARTERANOMISIONAL = CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL
            LEFT JOIN USUARIOS ON USUARIOS.IDUSUARIO = TRAZAPROCJUDICIAL.COD_USUARIO
            
            WHERE $where2
                
            UNION
            SELECT COD_CARTERA_NOMISIONAL AS CODCARTERA, 
            TO_CHAR(CNM_CARTERANOMISIONAL.COD_EMPLEADO) AS CODEMPRESA, 
            CNM_EMPLEADO.NOMBRES||' '||CNM_EMPLEADO.APELLIDOS AS NOMEMPRESA, 
            CNM_EMPLEADO.TELEFONO AS TELEFONO,
            CNM_EMPLEADO.DIRECCION AS DIRECCION,
            CNM_EMPLEADO.COD_REGIONAL AS CODREGIONAL,
            CNM_EMPLEADO.CORREO_ELECTRONICO AS EMAIL,
            REGIONAL.NOMBRE_REGIONAL AS NOMREGIONAL
            FROM 
            CNM_CARTERANOMISIONAL
            INNER JOIN CNM_EMPLEADO ON CNM_EMPLEADO.IDENTIFICACION = CNM_CARTERANOMISIONAL.COD_EMPLEADO
            INNER JOIN REGIONAL ON REGIONAL.COD_REGIONAL = CNM_EMPLEADO.COD_REGIONAL
            LEFT JOIN TRAZAPROCJUDICIAL ON TRAZAPROCJUDICIAL.COD_CARTERANOMISIONAL = CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL
            LEFT JOIN USUARIOS ON USUARIOS.IDUSUARIO = TRAZAPROCJUDICIAL.COD_USUARIO
            WHERE $where1
            ");
//        echo $this->db->last_query();
        return $query;
    }

    function actulizatrazajudicial($empresa, $ciudad, $nombre, $regional, $projudicial, $usuario, $usuar) {
        $this->db->select('TITULOSJUDICIALES.COD_TITULO,TITULOSJUDICIALES.COD_PROPIETARIO,TITULOSJUDICIALES.NOMBRE_PROPIETARIO,TITULOSJUDICIALES.CORREO_PROPIETARIO,
TITULOSJUDICIALES.DIRECCION_INMUEBLE,DEPARTAMENTO.NOM_DEPARTAMENTO, REGIONAL.NOMBRE_REGIONAL, EMPRESA.CIIU,REGIONAL.COD_REGIONAL,DEPARTAMENTO.COD_DEPARTAMENTO');
        $query = $this->db->join('DEPARTAMENTO', 'DEPARTAMENTO.COD_DEPARTAMENTO = TITULOSJUDICIALES.COD_DEPARTAMENTO');
        $query = $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL = TITULOSJUDICIALES.COD_REGIONAL');
        $query = $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA = TITULOSJUDICIALES.COD_PROPIETARIO', 'LEFT');
        $query = $this->db->join('TRAZAPROCJUDICIAL', 'TRAZAPROCJUDICIAL.COD_TITULO = TITULOSJUDICIALES.COD_TITULO', 'LEFT');
        $query = $this->db->join('USUARIOS', 'USUARIOS.IDUSUARIO = TRAZAPROCJUDICIAL.COD_USUARIO', 'LEFT');

        $query = $this->db->where('TITULOSJUDICIALES.COD_PROPIETARIO', $empresa);
        $query = $this->db->or_where('DEPARTAMENTO.COD_DEPARTAMENTO', $ciudad);
//        $query = $this->db->or_where('UPPER(EMPRESA.NOMBRE_EMPRESA)', $nombre);
//        $query = $this->db->or_where('REGIONAL.COD_REGIONAL', $regional);
        //$query = $this->db->or_where('TIPOGESTION.CODPROCESO', $proceso);
        $query = $this->db->or_where('TITULOSJUDICIALES.COD_TITULO', $projudicial);

        if ($usuario == 0) {
            $query = $this->db->or_where('TITULOSJUDICIALES.COD_REGIONAL', $regional);
            $query = $this->db->or_where('TRAZAPROCJUDICIAL.COD_USUARIO', $usuario);
        } else {
            $query = $this->db->or_where('TITULOSJUDICIALES.COD_REGIONAL', $regional);
            $query = $this->db->where('TRAZAPROCJUDICIAL.COD_USUARIO', $usuario);
        }
        //$query = $this->db->or_where('TIPOGESTION.CODPROCESO', $proceso);


        $tal = '?';
        if ($ciudad != '' || $empresa != '' || $projudicial != '' || $projudicial != '' || $regional != '' || $usuario != 0 || $usuar != '') {
            $query = $this->db->or_where("UPPER(TITULOSJUDICIALES.NOMBRE_PROPIETARIO) like '%" . $tal . "%' and 1=", 1, FALSE);
//            $query = $this->db->or_where("USUARIOS.NOMBRES like '%" . $tal . "%' and 1=", 1, FALSE);
        } else {
            $query = $this->db->or_where("UPPER(TITULOSJUDICIALES.NOMBRE_PROPIETARIO) like '%" . $nombre . "%' and 1=", 1, FALSE);
//            $query = $this->db->or_where("USUARIOS.NOMBRES like '%" . $usuar . "%') and 1=", 1, FALSE);
        }

        $tal = '?';
        if ($ciudad != '' || $empresa != '' || $projudicial != '' || $projudicial != '' || $regional != '' || $usuario != 0 || $nombre != '') {
            $query = $this->db->or_where("UPPER(USUARIOS.APELLIDOS) like '%" . $tal . "%' and 1=", 1, FALSE);
        } else {
            $nom = '';
            $noms = '';

            $nombreapellido = explode(' ', $usuar);
//            print_r($nombreapellido);
            for ($i = 0; $i <= count($nombreapellido) - 1; $i++):
                $nom = $nombreapellido[$i];
                $noms = "UPPER(USUARIOS.NOMBRES) like" . "'%" . $nom . "%' and 1=1 OR UPPER(USUARIOS.APELLIDOS) like" . "'%" . $nom . "%' and 1=";
                $query = $this->db->or_where($noms, 1, FALSE);
            endfor;
        }

        $query = $this->db->group_by('TITULOSJUDICIALES.COD_TITULO,TITULOSJUDICIALES.COD_PROPIETARIO,TITULOSJUDICIALES.NOMBRE_PROPIETARIO,TITULOSJUDICIALES.CORREO_PROPIETARIO,
TITULOSJUDICIALES.DIRECCION_INMUEBLE,DEPARTAMENTO.NOM_DEPARTAMENTO, REGIONAL.NOMBRE_REGIONAL, EMPRESA.CIIU,REGIONAL.COD_REGIONAL,DEPARTAMENTO.COD_DEPARTAMENTO');
        $query = $this->db->get('TITULOSJUDICIALES');
//        echo $this->db->last_query();
        return $query;
    }

    function verproceso($id,$titulo,$vista) {
        
        $this->db->select('VISTA_ORIGEN,COD_TIPO_INSTANCIA,COD_TIPO_PROCESO,TIPO_PROCESO,COD_TRAZAPROCJUDICIAL,COD_TIPOCARTERA,NOMBRE_CARTERA,
                            COD_CARTERANOMISIONAL,COD_EMPRESA, NOMEMPRESA, COD_TIPOGESTION,TIPOGESTION,COD_TIPO_RESPUESTA,NOMBRE_GESTION,COMENTARIOS,
                            COD_USUARIO,APELLIDOS,NOMBRES, CODPROCESO,FECHA');
        if($id!='' && $titulo=='' || $titulo=='Titulos Asignados Al Proceso'){
        $query = $this->db->where('COD_CARTERANOMISIONAL', $id);
        }else{
        $query = $this->db->where('COD_CARTERANOMISIONAL', $titulo);
        }
        
        $query = $this->db->where('VISTA_ORIGEN', $vista);
        $query = $this->db->order_by('FECHA', "DESC");
        $query = $this->db->get('VW_TRAZA_DET');
//        echo $this->db->last_query();
        return $query->result_array;
    }

    function verprocesodevolucion($id) {
        $procesos = array(24, 25, 26);
        $this->db->select('(SELECT COD_TIPO_INSTANCIA FROM INSTANCIAS_PROCESOS WHERE TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO AND ROWNUM = 1) COD_TIPO_INSTANCIA, 
(SELECT COD_TIPO_PROCESO FROM INSTANCIAS_PROCESOS WHERE TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO AND ROWNUM = 1) COD_TIPO_PROCESO', false);
        $this->db->select('TIPOPROCESO.TIPO_PROCESO, TRAZAPROCJUDICIAL.COD_TRAZAPROCJUDICIAL, TRAZAPROCJUDICIAL.COD_DEVOLUCION, SOLICITUDDEVOLUCION.NIT,
            VW_PERSONAS.NOMBRE_EMPRESA AS RAZON_SOCIAL, TRAZAPROCJUDICIAL.COD_TIPOGESTION, TIPOGESTION.TIPOGESTION, TRAZAPROCJUDICIAL.COD_TIPO_RESPUESTA, RESPUESTAGESTION.NOMBRE_GESTION, 
TRAZAPROCJUDICIAL.COMENTARIOS, TRAZAPROCJUDICIAL.COD_USUARIO, USUARIOS.APELLIDOS, USUARIOS.NOMBRES, TIPOGESTION.CODPROCESO, TIPOPROCESO.TIPO_PROCESO');
        $this->db->select('to_char("TRAZAPROCJUDICIAL"."FECHA",' . "'DD/MM/YYYY HH:MI am') AS FECHA", FALSE);
        $query = $this->db->join('SOLICITUDDEVOLUCION', 'SOLICITUDDEVOLUCION.COD_DEVOLUCION = TRAZAPROCJUDICIAL.COD_DEVOLUCION', 'LEFT');
        $query = $this->db->join('VW_PERSONAS', 'SOLICITUDDEVOLUCION.NIT = VW_PERSONAS.CODEMPRESA ', 'LEFT');
        $query = $this->db->join('USUARIOS', 'USUARIOS.IDUSUARIO = TRAZAPROCJUDICIAL.COD_USUARIO ', 'LEFT');
        $query = $this->db->join('TIPOGESTION', 'TRAZAPROCJUDICIAL.COD_TIPOGESTION = TIPOGESTION.COD_GESTION ', 'LEFT');
        $query = $this->db->join('TIPOPROCESO', 'TIPOGESTION.CODPROCESO = TIPOPROCESO.COD_TIPO_PROCESO', 'LEFT');
//        $query = $this->db->join('INSTANCIAS_PROCESOS', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO', 'LEFT');
//        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = TIPOPROCESO.COD_TIPO_INSTANCIA', 'LEFT');
//        $query = $this->db->join('INSTANCIAS_PROCESOS  A', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = A.COD_TIPO_INSTANCIA', 'left', false);
        $query = $this->db->join('RESPUESTAGESTION', 'TRAZAPROCJUDICIAL.COD_TIPO_RESPUESTA = RESPUESTAGESTION.COD_RESPUESTA', 'LEFT');

        $query = $this->db->where('TRAZAPROCJUDICIAL.COD_DEVOLUCION', $id);
        $query = $this->db->where('((TIPOGESTION.CODPROCESO IN(24,25,26))OR TIPOGESTION.CODPROCESO IS NULL)and 1=',1,FALSE);
        $query = $this->db->order_by('TRAZAPROCJUDICIAL.FECHA', "DESC");
//        $query = $this->db->limit(50);
//        $query = $this->db->where('GESTIONCOBRO.GESTIONCOBRO.NIT_EMPRESA', $id);
        $query = $this->db->get('TRAZAPROCJUDICIAL');
//        echo $this->db->last_query();

        return $query->result_array;
        //echo $this->db->last_query();
    }

    function verprocesojudicial($id) {
        $proceso = 13;
        $this->db->select('(SELECT COD_TIPO_INSTANCIA FROM INSTANCIAS_PROCESOS WHERE TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO AND ROWNUM = 1) COD_TIPO_INSTANCIA, 
(SELECT COD_TIPO_PROCESO FROM INSTANCIAS_PROCESOS WHERE TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO AND ROWNUM = 1) COD_TIPO_PROCESO', false);
        $this->db->select('TIPOPROCESO.TIPO_PROCESO, TRAZAPROCJUDICIAL.COD_TRAZAPROCJUDICIAL, TRAZAPROCJUDICIAL.COD_TITULO, TITULOSJUDICIALES.COD_PROPIETARIO, TITULOSJUDICIALES.NOMBRE_PROPIETARIO, TRAZAPROCJUDICIAL.COD_TIPOGESTION, TIPOGESTION.TIPOGESTION, TRAZAPROCJUDICIAL.COD_TIPO_RESPUESTA, RESPUESTAGESTION.NOMBRE_GESTION, 
TRAZAPROCJUDICIAL.COMENTARIOS, TRAZAPROCJUDICIAL.COD_USUARIO, USUARIOS.APELLIDOS, USUARIOS.NOMBRES, TIPOGESTION.CODPROCESO, TIPOPROCESO.TIPO_PROCESO');
        $this->db->select('to_char("TRAZAPROCJUDICIAL"."FECHA",' . "'DD/MM/YYYY HH:MI am') AS FECHA", FALSE);
        $query = $this->db->join('TITULOSJUDICIALES', 'TITULOSJUDICIALES.COD_TITULO = TRAZAPROCJUDICIAL.COD_TITULO', 'LEFT');
//        $query = $this->db->join('EMPRESA', 'SOLICITUDDEVOLUCION.NIT = EMPRESA.CODEMPRESA ', 'LEFT');
        $query = $this->db->join('USUARIOS', 'USUARIOS.IDUSUARIO = TRAZAPROCJUDICIAL.COD_USUARIO ', 'LEFT');
        $query = $this->db->join('TIPOGESTION', 'TRAZAPROCJUDICIAL.COD_TIPOGESTION = TIPOGESTION.COD_GESTION ', 'LEFT');
        $query = $this->db->join('TIPOPROCESO', 'TIPOGESTION.CODPROCESO = TIPOPROCESO.COD_TIPO_PROCESO', 'LEFT');
//        $query = $this->db->join('INSTANCIAS_PROCESOS', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO', 'LEFT');
//        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = TIPOPROCESO.COD_TIPO_INSTANCIA', 'LEFT');
//        $query = $this->db->join('INSTANCIAS_PROCESOS  A', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = A.COD_TIPO_INSTANCIA', 'left', false);
        $query = $this->db->join('RESPUESTAGESTION', 'TRAZAPROCJUDICIAL.COD_TIPO_RESPUESTA = RESPUESTAGESTION.COD_RESPUESTA', 'LEFT');

        $query = $this->db->where('TRAZAPROCJUDICIAL.COD_TITULO', $id);
        $query = $this->db->where('((TIPOGESTION.CODPROCESO IN(13))OR TIPOGESTION.CODPROCESO IS NULL)and 1=',1,FALSE);
        $query = $this->db->order_by('TRAZAPROCJUDICIAL.FECHA', "DESC");
//        $query = $this->db->limit(50);
//        $query = $this->db->where('GESTIONCOBRO.GESTIONCOBRO.NIT_EMPRESA', $id);
        $query = $this->db->get('TRAZAPROCJUDICIAL');

//        echo $this->db->last_query();

        return $query->result_array;
        //echo $this->db->last_query();
    }

    function verempresaprocesodevolucion($id) {
        $this->db->select('SOLICITUDDEVOLUCION.COD_DEVOLUCION, VW_PERSONAS.CODEMPRESA AS IDENTIFICACION, VW_PERSONAS.NOMBRE_EMPRESA AS NOMBRE_PROPIETARIO, VW_PERSONAS.TELEFONO_FIJO, VW_PERSONAS.DIRECCION, VW_PERSONAS.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO, VW_PERSONAS.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, VW_PERSONAS.COD_REGIMEN, REGIMENTRIBUTARIO.DESC_REGIMEN, VW_PERSONAS.CIIU');
        $query = $this->db->join('VW_PERSONAS', 'VW_PERSONAS.CODEMPRESA = SOLICITUDDEVOLUCION.NIT ');
        $query = $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL = VW_PERSONAS.COD_REGIONAL', 'LEFT');
        $query = $this->db->join('DEPARTAMENTO', 'DEPARTAMENTO.COD_DEPARTAMENTO = VW_PERSONAS.COD_DEPARTAMENTO', 'LEFT');
        $query = $this->db->join('REGIMENTRIBUTARIO', 'REGIMENTRIBUTARIO.CODREGIMEN = VW_PERSONAS.COD_REGIMEN', 'LEFT');
        $query = $this->db->where('SOLICITUDDEVOLUCION.COD_DEVOLUCION', $id);
        $query = $this->db->group_by('SOLICITUDDEVOLUCION.COD_DEVOLUCION, VW_PERSONAS.CODEMPRESA, VW_PERSONAS.NOMBRE_EMPRESA, VW_PERSONAS.TELEFONO_FIJO, VW_PERSONAS.DIRECCION, VW_PERSONAS.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO, VW_PERSONAS.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, VW_PERSONAS.COD_REGIMEN, REGIMENTRIBUTARIO.DESC_REGIMEN, VW_PERSONAS.CIIU');
        $query = $this->db->get('SOLICITUDDEVOLUCION');
//        echo $this->db->last_query();
        return $query->result_array;
    }

    function verempresaprocesojudicial($id) {
        $this->db->select('TITULOSJUDICIALES.COD_TITULO,TITULOSJUDICIALES.COD_PROPIETARIO AS IDENTIFICACION,TITULOSJUDICIALES.NOMBRE_PROPIETARIO,TITULOSJUDICIALES.CORREO_PROPIETARIO,
TITULOSJUDICIALES.DIRECCION_INMUEBLE,DEPARTAMENTO.NOM_DEPARTAMENTO, REGIONAL.NOMBRE_REGIONAL, EMPRESA.CIIU,REGIONAL.COD_REGIONAL,DEPARTAMENTO.COD_DEPARTAMENTO');
        $query = $this->db->join('DEPARTAMENTO', 'DEPARTAMENTO.COD_DEPARTAMENTO = TITULOSJUDICIALES.COD_DEPARTAMENTO');
        $query = $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL = TITULOSJUDICIALES.COD_REGIONAL');
        $query = $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA = TITULOSJUDICIALES.COD_PROPIETARIO', 'LEFT');
        $query = $this->db->where('TITULOSJUDICIALES.COD_TITULO', $id);
        $query = $this->db->group_by('TITULOSJUDICIALES.COD_TITULO,TITULOSJUDICIALES.COD_PROPIETARIO,TITULOSJUDICIALES.NOMBRE_PROPIETARIO,TITULOSJUDICIALES.CORREO_PROPIETARIO,
TITULOSJUDICIALES.DIRECCION_INMUEBLE,DEPARTAMENTO.NOM_DEPARTAMENTO, REGIONAL.NOMBRE_REGIONAL, EMPRESA.CIIU,REGIONAL.COD_REGIONAL,DEPARTAMENTO.COD_DEPARTAMENTO');
        $query = $this->db->get('TITULOSJUDICIALES');
//        echo $this->db->last_query();
        return $query->result_array;
    }

    function verempresaproceso($id) {
        $this->db->select('FISCALIZACION.COD_FISCALIZACION, EMPRESA.CODEMPRESA AS IDENTIFICACION, EMPRESA.RAZON_SOCIAL AS NOMBRE_PROPIETARIO, EMPRESA.TELEFONO_FIJO, EMPRESA.DIRECCION, EMPRESA.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO, EMPRESA.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, EMPRESA.COD_REGIMEN, REGIMENTRIBUTARIO.DESC_REGIMEN, EMPRESA.CIIU, CODIGO_PJ');
        $query = $this->db->join('ASIGNACIONFISCALIZACION', 'ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION = FISCALIZACION.COD_ASIGNACION_FISC', 'LEFT');
        $query = $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA = ASIGNACIONFISCALIZACION.NIT_EMPRESA');
        $query = $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL = EMPRESA.COD_REGIONAL', 'LEFT');
        $query = $this->db->join('DEPARTAMENTO', 'DEPARTAMENTO.COD_DEPARTAMENTO = EMPRESA.COD_DEPARTAMENTO', 'LEFT');
        $query = $this->db->join('REGIMENTRIBUTARIO', 'REGIMENTRIBUTARIO.CODREGIMEN = EMPRESA.COD_REGIMEN', 'LEFT');
        $query = $this->db->join('TIPOGESTION', 'TIPOGESTION.COD_GESTION = FISCALIZACION.COD_TIPOGESTION');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = TIPOGESTION.CODPROCESO');
        $query = $this->db->where('FISCALIZACION.COD_FISCALIZACION', $id);
        $query = $this->db->group_by('FISCALIZACION.COD_FISCALIZACION, EMPRESA.CODEMPRESA, EMPRESA.RAZON_SOCIAL,EMPRESA.TELEFONO_FIJO, EMPRESA.DIRECCION, EMPRESA.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO,EMPRESA.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, EMPRESA.COD_REGIMEN, REGIMENTRIBUTARIO.DESC_REGIMEN,EMPRESA.CIIU,CODIGO_PJ');
        $query = $this->db->get('FISCALIZACION');
        return $query->result_array;
    }

    function verempresaprocesojuridico($id) {
        $this->db->select("PROCESOS_COACTIVOS.IDENTIFICACION, Fn_Razon_Social(PROCESOS_COACTIVOS.IDENTIFICACION) AS Nombre_propietario");
        $query = $this->db->where('PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO', $id);
        $query = $this->db->get('PROCESOS_COACTIVOS');
        return $query->result_array;
    }
    
    function verempresaprocesojuridicotitulo($titulo) {
        $this->db->select("Fn_Recepcion_Id($titulo) AS IDENTIFICACION, Fn_Razon_Social(Fn_Recepcion_Id($titulo)) AS NOMBRE_PROPIETARIO");
        $query = $this->db->get('DUAL');
        return $query->result_array;
    }
    
    function verempresaprocesonomisional($id) {
        $this->db->select("fn_CarteraNoMisional_ID($id) AS IDENTIFICACION,Fn_Razon_Social(fn_CarteraNoMisional_ID($id)) AS NOMBRE_PROPIETARIO");
        $query = $this->db->get('DUAL');
//        echo $this->db->last_query();
        return $query->result_array;
    }
    

    function getprocesos() {
        $query = $this->db->select('TIPOPROCESO.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->order_by("TIPOPROCESO.TIPO_PROCESO", "ASC");
        $query = $this->db->get('TIPOPROCESO');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
                $datos[htmlspecialchars($row->COD_TIPO_PROCESO, ENT_QUOTES)] = htmlspecialchars($row->TIPO_PROCESO);
            //$query->free_result();
            //print_r($query);
            return $datos;
        }
    }

    function getciudades() {
        $this->db->select('DEPARTAMENTO.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO');
        $this->db->order_by("DEPARTAMENTO.NOM_DEPARTAMENTO", "ASC");
        $query = $this->db->get('DEPARTAMENTO');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
                $datos[htmlspecialchars($row->COD_DEPARTAMENTO, ENT_QUOTES)] = htmlspecialchars($row->NOM_DEPARTAMENTO);
            //$query->free_result();
            return $datos;
        }
    }

    function getregionales() {
        $this->db->select('REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL');
        $this->db->order_by("REGIONAL.NOMBRE_REGIONAL", "ASC");
        $query = $this->db->get('REGIONAL');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
                $datos[htmlspecialchars($row->COD_REGIONAL, ENT_QUOTES)] = htmlspecialchars($row->NOMBRE_REGIONAL);
            //$query->free_result();
            return $datos;
        }
    }

    function set_usuarios($nombre) {
        $this->nombre = $nombre;
    }

    function getusuario() {
        $this->db->select('USUARIOS.IDUSUARIO, UPPER(USUARIOS.NOMBRES), UPPER(USUARIOS.APELLIDOS)');
//        $this->db->from('USUARIOS');
        $this->db->order_by("USUARIOS.NOMBRES", "ASC");
        if (!empty($this->nombre)) {
            $this->db->like('UPPER(USUARIOS.NOMBRES)', $this->nombre);
        }
        $datos = $this->db->get('USUARIOS');
//        $datos = $this->db->last_query(); echo $datos; die();
        $datos = $datos->result_array();

        if (!empty($datos)) :
            $tmp = NULL;
//        $a = 0;
//        print_r($datos);
            foreach ($datos as $nombre) :
//                print_r($nombre);
                $tmp[] = array("value" => $nombre['UPPER(USUARIOS.NOMBRES)'] . " " . $nombre['UPPER(USUARIOS.APELLIDOS)'], "label" => $nombre['UPPER(USUARIOS.NOMBRES)'] . " " . $nombre['UPPER(USUARIOS.APELLIDOS)']);
//            $a++;
            endforeach;
            $datos = $tmp;
        endif;
        return $datos;
    }

    function getusuarios($usu = 0) {
        $this->db->select('USUARIOS.IDUSUARIO, USUARIOS.NOMBRES,USUARIOS.APELLIDOS');
        $this->db->where("USUARIOS.COD_REGIONAL", $usu);
        $this->db->order_by("USUARIOS.NOMBRES", "ASC");
        $query = $this->db->get('USUARIOS');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
                $datos[htmlspecialchars($row->IDUSUARIO, ENT_QUOTES)] = htmlspecialchars($row->NOMBRES) . ' ' . htmlspecialchars($row->APELLIDOS);
            //$query->free_result();
            return $datos;
        }
    }

    function set_nombrejud($nombre) {
        $this->nombre = $nombre;
    }

    function getnombrejud() {
        $this->db->select('TITULOSJUDICIALES.COD_PROPIETARIO,UPPER(TITULOSJUDICIALES.NOMBRE_PROPIETARIO)');
        $this->db->order_by("TITULOSJUDICIALES.NOMBRE_PROPIETARIO", "ASC");
        if (!empty($this->nombre)) {
            $this->db->like('UPPER(TITULOSJUDICIALES.NOMBRE_PROPIETARIO)', $this->nombre);
        }
        $datos = $this->db->get('TITULOSJUDICIALES');
        $datos = $datos->result_array();
        if (!empty($datos)) :
            $tmp = NULL;
            foreach ($datos as $nombre) :
                $tmp[] = array("value" => $nombre['UPPER(TITULOSJUDICIALES.NOMBRE_PROPIETARIO)'], "label" => $nombre['UPPER(TITULOSJUDICIALES.NOMBRE_PROPIETARIO)'] . " :: " . $nombre['COD_PROPIETARIO']);
            endforeach;
            $datos = $tmp;
        endif;
        return $datos;
    }

    function set_nombre($nombre) {
        $this->nombre = $nombre;
    }

    function getnombre() {
        $this->db->select('EMPRESA.CODEMPRESA, EMPRESA.RAZON_SOCIAL');
        $this->db->order_by("EMPRESA.RAZON_SOCIAL", "ASC");
        if (!empty($this->nombre)) {
            $this->db->like('RAZON_SOCIAL', $this->nombre);
        }
        $datos = $this->db->get('EMPRESA');
        $datos = $datos->result_array();
        if (!empty($datos)) :
            $tmp = NULL;
            foreach ($datos as $nombre) :
                $tmp[] = array("value" => $nombre['RAZON_SOCIAL'], "label" => $nombre['RAZON_SOCIAL'] . " :: " . $nombre['CODEMPRESA']);
            endforeach;
            $datos = $tmp;
        endif;
        return $datos;
    }
    
    function set_allnombre($nombre) {
        $this->nombre = $nombre;
    }

    function getallnombre() {
        $this->db->select('VW_PERSONAS.CODEMPRESA, VW_PERSONAS.NOMBRE_EMPRESA');
        $this->db->order_by("VW_PERSONAS.NOMBRE_EMPRESA", "ASC");
        if (!empty($this->nombre)) {
            $this->db->where("UPPER(NOMBRE_EMPRESA) LIKE '%' || REPLACE('$this->nombre',' ','%') || '%'");
        }
        $datos = $this->db->get('VW_PERSONAS');
        $datos = $datos->result_array();
        if (!empty($datos)) :
            $tmp = NULL;
            foreach ($datos as $nombre) :
                $tmp[] = array("value" => $nombre['NOMBRE_EMPRESA'], "label" => $nombre['NOMBRE_EMPRESA'] . " :: " . $nombre['CODEMPRESA']);
            endforeach;
            $datos = $tmp;
        endif;
        return $datos;
    }

    function set_coactivo($coactivo) {
        $this->coactivo = $coactivo;
    }

    function getcoactivo() {
        $this->db->select('PROCESOS_COACTIVOS.COD_PROCESOPJ');
        $this->db->order_by("PROCESOS_COACTIVOS.COD_PROCESOPJ", "ASC");
        if (!empty($this->coactivo)) {
            $this->db->like('PROCESOS_COACTIVOS.COD_PROCESOPJ', $this->coactivo);
        }
        $datos = $this->db->get('PROCESOS_COACTIVOS');
        $datos = $datos->result_array();
        if (!empty($datos)) :
            $tmp = NULL;
            foreach ($datos as $coactivo) :
                $tmp[] = array("value" => $coactivo['COD_PROCESOPJ'], "label" => $coactivo['COD_PROCESOPJ']);
            endforeach;
            $datos = $tmp;
        endif;
        return $datos;
    }

    function set_codigojud($nit) {
        $this->nit = $nit;
    }

    function getcodigojud() {

        $this->db->select('TITULOSJUDICIALES.COD_PROPIETARIO,TITULOSJUDICIALES.NOMBRE_PROPIETARIO');
        $this->db->order_by("TITULOSJUDICIALES.NOMBRE_PROPIETARIO", "ASC");
        if (!empty($this->nit)) {
            $this->db->like('TITULOSJUDICIALES.COD_PROPIETARIO', $this->nit);
        }
        $datos = $this->db->get('TITULOSJUDICIALES');
        $datos = $datos->result_array();
        if (!empty($datos)) :
            $tmp = NULL;
            foreach ($datos as $nit) :
                $tmp[] = array("value" => $nit['COD_PROPIETARIO'], "label" => $nit['COD_PROPIETARIO'] . " :: " . $nit['NOMBRE_PROPIETARIO']);
            endforeach;
            $datos = $tmp;
        endif;
        return $datos;
    }

    function set_codigo($nit) {
        $this->nit = $nit;
    }

    function getcodigo() {
         $this->db->select('VW_PERSONAS.CODEMPRESA, VW_PERSONAS.NOMBRE_EMPRESA');
        $this->db->order_by("VW_PERSONAS.NOMBRE_EMPRESA", "ASC");
        if (!empty($this->nombre)) {
            $this->db->like('NOMBRE_EMPRESA', $this->nombre);
        }
        $datos = $this->db->get('VW_PERSONAS');
        $datos = $datos->result_array();
        if (!empty($datos)) :
            $tmp = NULL;
            foreach ($datos as $nombre) :
                $tmp[] = array("value" => $nit['CODEMPRESA'], "label" => $nit['CODEMPRESA'] . " :: " . $nit['NOMBRE_EMPRESA']);
            endforeach;
            $datos = $tmp;
        endif;
        return $datos;
        
    }
    
    function set_allcodigo($nit) {
        $this->nit = $nit;
    }

    function getallcodigo() {
        $this->db->select('EMPRESA.CODEMPRESA, EMPRESA.RAZON_SOCIAL');
        $this->db->order_by("EMPRESA.RAZON_SOCIAL", "ASC");
        if (!empty($this->nit)) {
            $this->db->like('EMPRESA.CODEMPRESA', $this->nit);
        }
        $datos = $this->db->get('EMPRESA');
        $datos = $datos->result_array();
        if (!empty($datos)) :
            $tmp = NULL;
            foreach ($datos as $nit) :
                $tmp[] = array("value" => $nit['CODEMPRESA'], "label" => $nit['CODEMPRESA'] . " :: " . $nit['RAZON_SOCIAL']);
            endforeach;
            $datos = $tmp;
        endif;
        return $datos;
    }

    function gettrazaadmin() {
        $this->db->select('FISCALIZACION.COD_FISCALIZACION, EMPRESA.CODEMPRESA, EMPRESA.RAZON_SOCIAL, EMPRESA.TELEFONO_FIJO, EMPRESA.DIRECCION, EMPRESA.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO, EMPRESA.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, EMPRESA.COD_REGIMEN, REGIMENTRIBUTARIO.DESC_REGIMEN, EMPRESA.CIIU, CODIGO_PJ');
        $query = $this->db->join('ASIGNACIONFISCALIZACION', 'ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION = FISCALIZACION.COD_ASIGNACION_FISC', 'LEFT');
        $query = $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA = ASIGNACIONFISCALIZACION.NIT_EMPRESA');
        $query = $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL = EMPRESA.COD_REGIONAL', 'LEFT');
        $query = $this->db->join('DEPARTAMENTO', 'DEPARTAMENTO.COD_DEPARTAMENTO = EMPRESA.COD_DEPARTAMENTO', 'LEFT');
        $query = $this->db->join('REGIMENTRIBUTARIO', 'REGIMENTRIBUTARIO.CODREGIMEN = EMPRESA.COD_REGIMEN', 'LEFT');
        $query = $this->db->join('TIPOGESTION', 'TIPOGESTION.COD_GESTION = FISCALIZACION.COD_TIPOGESTION');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = TIPOGESTION.CODPROCESO', 'LEFT');
        $query = $this->db->join('GESTIONCOBRO', 'GESTIONCOBRO.COD_FISCALIZACION_EMPRESA = FISCALIZACION.COD_FISCALIZACION');
        //$query = $this->db->where('FISCALIZACION.CODIGO_PJ IS NULL');
        $query = $this->db->group_by('FISCALIZACION.COD_FISCALIZACION, EMPRESA.CODEMPRESA, EMPRESA.RAZON_SOCIAL,EMPRESA.TELEFONO_FIJO, EMPRESA.DIRECCION, EMPRESA.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO,EMPRESA.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, EMPRESA.COD_REGIMEN, REGIMENTRIBUTARIO.DESC_REGIMEN,EMPRESA.CIIU,CODIGO_PJ');
        $query = $this->db->get('FISCALIZACION');
//        echo $this->db->last_query();
        return $query;
    }

    function actulizatrazaadmin($empresa, $ciudad, $nombre, $regional, $proceso, $fiscaliza, $usuario, $usuar) {
        $this->db->select('FISCALIZACION.COD_FISCALIZACION, EMPRESA.CODEMPRESA, EMPRESA.RAZON_SOCIAL, EMPRESA.TELEFONO_FIJO, EMPRESA.DIRECCION, EMPRESA.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO, EMPRESA.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, EMPRESA.COD_REGIMEN, REGIMENTRIBUTARIO.DESC_REGIMEN, EMPRESA.CIIU,CODIGO_PJ');
        $query = $this->db->join('ASIGNACIONFISCALIZACION', 'ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION = FISCALIZACION.COD_ASIGNACION_FISC', 'LEFT');
        $query = $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA = ASIGNACIONFISCALIZACION.NIT_EMPRESA');
        $query = $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL = EMPRESA.COD_REGIONAL', 'LEFT');
        $query = $this->db->join('DEPARTAMENTO', 'DEPARTAMENTO.COD_DEPARTAMENTO = EMPRESA.COD_DEPARTAMENTO', 'LEFT');
        $query = $this->db->join('REGIMENTRIBUTARIO', 'REGIMENTRIBUTARIO.CODREGIMEN = EMPRESA.COD_REGIMEN', 'LEFT');
        $query = $this->db->join('TIPOGESTION', 'TIPOGESTION.COD_GESTION = FISCALIZACION.COD_TIPOGESTION');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = TIPOGESTION.CODPROCESO', 'LEFT');
        $query = $this->db->join('GESTIONCOBRO', 'GESTIONCOBRO.COD_FISCALIZACION_EMPRESA = FISCALIZACION.COD_FISCALIZACION');
        $query = $this->db->join('USUARIOS', 'GESTIONCOBRO.COD_USUARIO = USUARIOS.IDUSUARIO');
        $query = $this->db->where('(ASIGNACIONFISCALIZACION.NIT_EMPRESA', $empresa);
        $query = $this->db->or_where('EMPRESA.COD_DEPARTAMENTO', $ciudad);
//        $query = $this->db->or_where('UPPER(EMPRESA.NOMBRE_EMPRESA)', $nombre);
//        $query = $this->db->or_where('EMPRESA.COD_REGIONAL', $regional);
        //$query = $this->db->or_where('TIPOGESTION.CODPROCESO', $proceso);
        $query = $this->db->or_where('COD_FISCALIZACION', $fiscaliza);

        if ($usuario == 0) {
            $query = $this->db->or_where('EMPRESA.COD_REGIONAL', $regional);
            $query = $this->db->or_where('GESTIONCOBRO.COD_USUARIO', $usuario);
        } else {
            $query = $this->db->or_where('EMPRESA.COD_REGIONAL', $regional);
            $query = $this->db->where('GESTIONCOBRO.COD_USUARIO', $usuario);
        }
        //$query = $this->db->or_where('TIPOGESTION.CODPROCESO', $proceso);


        $tal = '?';
        if ($ciudad != '' || $empresa != '' || $fiscaliza != '' || $proceso != '' || $fiscaliza != '' || $regional != '' || $usuario != 0 || $usuar != '') {
            $query = $this->db->or_where("UPPER(EMPRESA.RAZON_SOCIAL) like '%" . $tal . "%' and 1=", 1, FALSE);
//            $query = $this->db->or_where("USUARIOS.NOMBRES like '%" . $tal . "%' and 1=", 1, FALSE);
        } else {
            $query = $this->db->or_where("UPPER(EMPRESA.RAZON_SOCIAL) like '%" . $nombre . "%'  and 1=", 1, FALSE);
//            $query = $this->db->or_where("USUARIOS.NOMBRES like '%" . $usuar . "%') and 1=", 1, FALSE);
        }

        $tal = '?';
        if ($ciudad != '' || $empresa != '' || $fiscaliza != '' || $proceso != '' || $fiscaliza != '' || $regional != '' || $usuario != 0 || $nombre != '') {
            $query = $this->db->or_where("UPPER(USUARIOS.NOMBRES) like '%" . $tal . "%' and 1=", 1, FALSE);
            $query = $this->db->or_where("UPPER(USUARIOS.APELLIDOS) like '%" . $tal . "%'  and 1=", 1, FALSE);
        } else {
            $nom = '';
            $noms = '';

            $nombreapellido = explode(' ', $usuar);
            for ($i = 0; $i <= count($nombreapellido) - 1; $i++):
                $nom = $nombreapellido[$i];
                $noms = "UPPER(USUARIOS.NOMBRES) like" . "'%" . $nom . "%' and 1=1 OR UPPER(USUARIOS.APELLIDOS) like" . "'%" . $nom . "%'";
                $query = $this->db->or_where($noms."and 1=", 1, FALSE);
            endfor;
        }
        $otro = "1=1)";
        $query = $this->db->where($otro."and 1=",1,FALSE);
        //$query = $this->db->where('FISCALIZACION.CODIGO_PJ IS NULL');
        $query = $this->db->group_by('FISCALIZACION.COD_FISCALIZACION, EMPRESA.CODEMPRESA, EMPRESA.RAZON_SOCIAL,EMPRESA.TELEFONO_FIJO, EMPRESA.DIRECCION, EMPRESA.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO,EMPRESA.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, EMPRESA.COD_REGIMEN, REGIMENTRIBUTARIO.DESC_REGIMEN,EMPRESA.CIIU,CODIGO_PJ');
        $query = $this->db->get('FISCALIZACION');
//        echo $this->db->last_query();
        return $query;
    }

    function verprocesoadmin($id) {
        $uno = 1;
        $dos = 7;
        $this->db->select('(SELECT COD_TIPO_INSTANCIA FROM INSTANCIAS_PROCESOS WHERE TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO AND ROWNUM = 1) COD_TIPO_INSTANCIA,
(SELECT COD_TIPO_PROCESO FROM INSTANCIAS_PROCESOS WHERE TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO AND ROWNUM = 1) COD_TIPO_PROCESO', false);
        $this->db->select('TIPOPROCESO.TIPO_PROCESO, GESTIONCOBRO.COD_GESTION_COBRO, 
GESTIONCOBRO.COD_FISCALIZACION_EMPRESA, GESTIONCOBRO.NIT_EMPRESA, EMPRESA.RAZON_SOCIAL, GESTIONCOBRO.COD_TIPOGESTION, 
TIPOGESTION.TIPOGESTION, GESTIONCOBRO.COD_TIPO_RESPUESTA, RESPUESTAGESTION.NOMBRE_GESTION, GESTIONCOBRO.COMENTARIOS, 
GESTIONCOBRO.COD_USUARIO, USUARIOS.APELLIDOS, USUARIOS.NOMBRES, TIPOGESTION.CODPROCESO, TIPOPROCESO.TIPO_PROCESO');
        $this->db->select('to_char("GESTIONCOBRO"."FECHA_CONTACTO",' . "'DD/MM/YYYY HH:MI am') AS FECHA_CONTACTO", FALSE);

        $query = $this->db->join('EMPRESA', 'GESTIONCOBRO.NIT_EMPRESA = EMPRESA.CODEMPRESA');
        $query = $this->db->join('USUARIOS', 'USUARIOS.IDUSUARIO = GESTIONCOBRO.COD_USUARIO');
        $query = $this->db->join('TIPOGESTION', 'GESTIONCOBRO.COD_TIPOGESTION = TIPOGESTION.COD_GESTION');
        $query = $this->db->join('TIPOPROCESO', 'TIPOGESTION.CODPROCESO = TIPOPROCESO.COD_TIPO_PROCESO', 'LEFT');
//        $query = $this->db->join('INSTANCIAS_PROCESOS', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
//        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = TIPOPROCESO.COD_TIPO_INSTANCIA');
//        $query = $this->db->join('INSTANCIAS_PROCESOS  A', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = A.COD_TIPO_INSTANCIA', false);
        $query = $this->db->join('RESPUESTAGESTION', 'GESTIONCOBRO.COD_TIPO_RESPUESTA = RESPUESTAGESTION.COD_RESPUESTA');
//        $query = $this->db->join('INSTANCIAS_PROCESOS', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->where('GESTIONCOBRO.COD_FISCALIZACION_EMPRESA', $id);
        $query = $this->db->where('((TIPOGESTION.CODPROCESO >=', $uno);
        $query = $this->db->where('TIPOGESTION.CODPROCESO <='.$dos.')OR TIPOGESTION.CODPROCESO IS NULL)and 1=',1,FALSE);
        $query = $this->db->order_by('GESTIONCOBRO.FECHA_CONTACTO', "DESC");
//        $query = $this->db->limit(50);
//        $query = $this->db->where('GESTIONCOBRO.GESTIONCOBRO.NIT_EMPRESA', $id);
        $query = $this->db->get('GESTIONCOBRO');
//        echo $this->db->last_query();

        return $query->result_array;
//        //echo $this->db->last_query();
//        
//        $this->db->select('TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA,GESTIONCOBRO.COD_GESTION_COBRO, GESTIONCOBRO.COD_FISCALIZACION_EMPRESA, GESTIONCOBRO.NIT_EMPRESA, EMPRESA.NOMBRE_EMPRESA, GESTIONCOBRO.COD_TIPOGESTION, TIPOGESTION.TIPOGESTION, GESTIONCOBRO.COD_TIPO_RESPUESTA, RESPUESTAGESTION.NOMBRE_GESTION, GESTIONCOBRO.COMENTARIOS, GESTIONCOBRO.COD_USUARIO, USUARIOS.APELLIDOS, USUARIOS.NOMBRES, TIPOGESTION.CODPROCESO, TIPOPROCESO.TIPO_PROCESO');
//        $this->db->select('to_char("GESTIONCOBRO"."FECHA_CONTACTO",' . "'DD/MM/YYYY HH:MI am') AS FECHA_CONTACTO", FALSE);
//        $query = $this->db->join('EMPRESA', 'GESTIONCOBRO.NIT_EMPRESA = EMPRESA.CODEMPRESA', 'LEFT');
//        $query = $this->db->join('USUARIOS', 'USUARIOS.IDUSUARIO = GESTIONCOBRO.COD_USUARIO', 'LEFT');
//        $query = $this->db->join('TIPOGESTION', 'GESTIONCOBRO.COD_TIPOGESTION = TIPOGESTION.COD_GESTION');
//        $query = $this->db->join('TIPOPROCESO', 'TIPOGESTION.CODPROCESO = TIPOPROCESO.COD_TIPO_PROCESO', 'LEFT');
//        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = TIPOPROCESO.COD_TIPO_INSTANCIA', 'LEFT');
//        $query = $this->db->join('RESPUESTAGESTION', 'GESTIONCOBRO.COD_TIPO_RESPUESTA = RESPUESTAGESTION.COD_RESPUESTA');
//        $query = $this->db->where('GESTIONCOBRO.COD_FISCALIZACION_EMPRESA', $id);
//        $query = $this->db->where('TIPOGESTION.CODPROCESO >=', $uno);
//        $query = $this->db->where('TIPOGESTION.CODPROCESO <=', $dos);
//        $query = $this->db->order_by("GESTIONCOBRO.FECHA_CONTACTO", "DESC");
////        $query = $this->db->where('GESTIONCOBRO.GESTIONCOBRO.NIT_EMPRESA', $id);
//
//        $query = $this->db->get('GESTIONCOBRO');
//        //echo $this->db->last_query();die();
//
//        return $query->result_array;
        //echo $this->db->last_query();
    }

//    function gettrazajuridico() {
//        $this->db->select('FISCALIZACION.COD_FISCALIZACION, EMPRESA.CODEMPRESA, EMPRESA.NOMBRE_EMPRESA, EMPRESA.TELEFONO_FIJO, EMPRESA.DIRECCION, EMPRESA.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO, EMPRESA.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, EMPRESA.COD_REGIMEN, REGIMENTRIBUTARIO.DESC_REGIMEN, EMPRESA.CIIU, CODIGO_PJ');
//        $this->db->select('to_char(MAX(GESTIONCOBRO.FECHA_CONTACTO),' . "'DD/MM/YYYY HH:MI am') AS FECHA_CONTACTO", FALSE);
//        $this->db->select('to_char(LIQUIDACION.FECHA_RESOLUCION,' . "'DD/MM/YYYY HH:MI am') AS FECHA_RESOLUCION", FALSE);
//        $this->db->select('to_char(LIQUIDACION.FECHA_EJECUTORIA,' . "'DD/MM/YYYY HH:MI am') AS FECHA_EJECUTORIA", FALSE);
//        $query = $this->db->join('ASIGNACIONFISCALIZACION', 'ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION = FISCALIZACION.COD_ASIGNACION_FISC', 'LEFT');
//        $query = $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA = ASIGNACIONFISCALIZACION.NIT_EMPRESA');
//        $query = $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL = EMPRESA.COD_REGIONAL', 'LEFT');
//        $query = $this->db->join('DEPARTAMENTO', 'DEPARTAMENTO.COD_DEPARTAMENTO = EMPRESA.COD_DEPARTAMENTO', 'LEFT');
//        $query = $this->db->join('REGIMENTRIBUTARIO', 'REGIMENTRIBUTARIO.CODREGIMEN = EMPRESA.COD_REGIMEN', 'LEFT');
//        $query = $this->db->join('TIPOGESTION', 'TIPOGESTION.COD_GESTION = FISCALIZACION.COD_TIPOGESTION');
//        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = TIPOGESTION.CODPROCESO');
//        $query = $this->db->join('GESTIONCOBRO', 'GESTIONCOBRO.COD_FISCALIZACION_EMPRESA = FISCALIZACION.COD_FISCALIZACION');
//        $query = $this->db->join('LIQUIDACION', 'LIQUIDACION.COD_FISCALIZACION = FISCALIZACION.COD_FISCALIZACION');
//        $query = $this->db->where('FISCALIZACION.CODIGO_PJ IS NOT NULL');
//        $query = $this->db->or_where('GESTIONCOBRO.COD_TIPOGESTION', 419);
//        $query = $this->db->group_by('FISCALIZACION.COD_FISCALIZACION, EMPRESA.CODEMPRESA, EMPRESA.NOMBRE_EMPRESA,EMPRESA.TELEFONO_FIJO, EMPRESA.DIRECCION, EMPRESA.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO,EMPRESA.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, EMPRESA.COD_REGIMEN, REGIMENTRIBUTARIO.DESC_REGIMEN,EMPRESA.CIIU,CODIGO_PJ,LIQUIDACION.FECHA_RESOLUCION, LIQUIDACION.FECHA_EJECUTORIA');
//        $query = $this->db->get('FISCALIZACION');
//        echo $this->db->last_query();
//        return $query;
//    }
    function gettrazajuridico() {
        $query = $this->db->query("SELECT PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO,
            NULL AS TITULO,
            EMPRESA.CODEMPRESA AS NIT, 
            EMPRESA.RAZON_SOCIAL AS NOMEMPRESA, 
            EMPRESA.TELEFONO_FIJO AS TELEFONO, 
            EMPRESA.DIRECCION AS DIRECCION,
            EMPRESA.COD_DEPARTAMENTO AS DEPARTAMENTO, 
            DEPARTAMENTO.NOM_DEPARTAMENTO AS NOMDEPARTAMENTO, 
            EMPRESA.COD_REGIONAL AS REGIONAL,
            REGIONAL.NOMBRE_REGIONAL AS NOMREGIONAL, 
            EMPRESA.COD_REGIMEN AS REGIMEN, 
            REGIMENTRIBUTARIO.DESC_REGIMEN AS NOMREGIMEN, 
            EMPRESA.CIIU AS CIIU, 
            RESOLUCION.NUMERO_RESOLUCION,
            PROCESOS_COACTIVOS.COD_PROCESOPJ AS CODIGOPJ,
            
            to_char(MAX(RECEPCIONTITULOS.FECHA_CONSULTAONBASE), 'DD/MM/YYYY HH:MI am') AS FECHA_CONSULTAONBASE, 
            to_char(MAX(PROCESOS_COACTIVOS.FECHA_AVOCA), 'DD/MM/YYYY HH:MI am') AS FECHA_AVOCA, 
            to_char(MAX(LIQUIDACION.FECHA_RESOLUCION), 'DD/MM/YYYY HH:MI am') AS FECHA_RESOLUCION, 
            to_char(MAX(LIQUIDACION.FECHA_EJECUTORIA), 'DD/MM/YYYY HH:MI am') AS FECHA_EJECUTORIA 

            FROM PROCESOS_COACTIVOS

            LEFT JOIN EMPRESA ON EMPRESA.CODEMPRESA = PROCESOS_COACTIVOS.IDENTIFICACION 
            INNER JOIN REGIONAL ON REGIONAL.COD_REGIONAL = EMPRESA.COD_REGIONAL 
            INNER JOIN DEPARTAMENTO ON DEPARTAMENTO.COD_DEPARTAMENTO = EMPRESA.COD_DEPARTAMENTO 
            LEFT JOIN REGIMENTRIBUTARIO ON REGIMENTRIBUTARIO.CODREGIMEN = EMPRESA.COD_REGIMEN 
            INNER JOIN ACUMULACION_COACTIVA ON ACUMULACION_COACTIVA.COD_PROCESO_COACTIVO = PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO
            INNER JOIN RECEPCIONTITULOS ON RECEPCIONTITULOS.COD_RECEPCIONTITULO = ACUMULACION_COACTIVA.COD_RECEPCIONTITULO
            INNER JOIN FISCALIZACION ON FISCALIZACION.COD_FISCALIZACION = RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA
            LEFT JOIN RESOLUCION ON RESOLUCION.COD_FISCALIZACION = FISCALIZACION.COD_FISCALIZACION
            INNER JOIN LIQUIDACION ON LIQUIDACION.COD_FISCALIZACION = FISCALIZACION.COD_FISCALIZACION

            WHERE PROCESOS_COACTIVOS.PROCEDENCIA = 1

            GROUP BY 
            PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO,
            EMPRESA.CODEMPRESA, 
            EMPRESA.RAZON_SOCIAL, 
            EMPRESA.TELEFONO_FIJO, 
            EMPRESA.DIRECCION,
            EMPRESA.COD_DEPARTAMENTO, 
            DEPARTAMENTO.NOM_DEPARTAMENTO, 
            EMPRESA.COD_REGIONAL,
            REGIONAL.NOMBRE_REGIONAL, 
            EMPRESA.COD_REGIMEN, 
            REGIMENTRIBUTARIO.DESC_REGIMEN, 
            EMPRESA.CIIU, 
            NUMERO_RESOLUCION,
            PROCESOS_COACTIVOS.COD_PROCESOPJ
           
            UNION
            (SELECT PC.COD_PROCESO_COACTIVO,
            NULL AS TITULO,
            TO_CHAR(CNM.IDENTIFICACION) AS NIT, 
            CNM.NOMBRES||' '||CNM.APELLIDOS AS NOMEMPRESA, 
            CNM.TELEFONO AS TELEFONO, 
            CNM.DIRECCION AS DIRECCION,
            NULL,
            NULL, 
            CNM.COD_REGIONAL AS REGIONAL, 
            RG.NOMBRE_REGIONAL AS NOMREGIONAL,
            NULL,
            NULL,
            NULL,
            NULL,
            PC.COD_PROCESOPJ AS CODIGOPJ,
            to_char(MAX(RECEPCIONTITULOS.FECHA_CONSULTAONBASE), 'DD/MM/YYYY HH:MI am') AS FECHA_LLEGA_COACTIVO, 
            to_char(MAX(PC.FECHA_AVOCA), 'DD/MM/YYYY HH:MI am') AS FECHA_AVOCA, 
            NULL,
            NULL
            
            FROM PROCESOS_COACTIVOS PC 

            INNER JOIN CNM_EMPLEADO CNM ON PC.IDENTIFICACION=CNM.IDENTIFICACION
            INNER JOIN REGIONAL RG ON CNM.COD_REGIONAL=RG.COD_REGIONAL
            INNER JOIN ACUMULACION_COACTIVA ON ACUMULACION_COACTIVA.COD_PROCESO_COACTIVO = PC.COD_PROCESO_COACTIVO
            INNER JOIN RECEPCIONTITULOS ON RECEPCIONTITULOS.COD_RECEPCIONTITULO = ACUMULACION_COACTIVA.COD_RECEPCIONTITULO

            WHERE PC.PROCEDENCIA = 0

            GROUP BY
            PC.COD_PROCESO_COACTIVO,
            TO_CHAR(CNM.IDENTIFICACION),
            CNM.NOMBRES||' '||CNM.APELLIDOS,
            CNM.TELEFONO,
            CNM.DIRECCION,
            CNM.COD_REGIONAL,
            RG.NOMBRE_REGIONAL,
            PC.COD_PROCESOPJ

            UNION
            (SELECT PC.COD_PROCESO_COACTIVO,
            NULL AS TITULO,
            TO_CHAR(CNM.COD_ENTIDAD) AS NIT, 
            CNM.RAZON_SOCIAL AS NOMEMPRESA, 
            CNM.TELEFONO AS TELEFONO, 
            CNM.DIRECCION AS DIRECCION,
            NULL,
            NULL, 
            CNM.COD_REGIONAL AS REGIONAL, 
            RG.NOMBRE_REGIONAL AS NOMREGIONAL,
            NULL,
            NULL,
            NULL,
            NULL,
            PC.COD_PROCESOPJ AS CODIGOPJ,
            to_char(MAX(RECEPCIONTITULOS.FECHA_CONSULTAONBASE), 'DD/MM/YYYY HH:MI am') AS FECHA_LLEGA_COACTIVO, 
            to_char(MAX(PC.FECHA_AVOCA), 'DD/MM/YYYY HH:MI am') AS FECHA_AVOCA, 
            NULL,
            NULL
            FROM PROCESOS_COACTIVOS PC 

            INNER JOIN CNM_EMPRESA CNM ON PC.IDENTIFICACION=CNM.COD_ENTIDAD
            INNER JOIN REGIONAL RG ON CNM.COD_REGIONAL=RG.COD_REGIONAL
            INNER JOIN ACUMULACION_COACTIVA ON ACUMULACION_COACTIVA.COD_PROCESO_COACTIVO = PC.COD_PROCESO_COACTIVO
            INNER JOIN RECEPCIONTITULOS ON RECEPCIONTITULOS.COD_RECEPCIONTITULO = ACUMULACION_COACTIVA.COD_RECEPCIONTITULO
   
            WHERE PC.PROCEDENCIA = 0

            GROUP BY
            PC.COD_PROCESO_COACTIVO,
            TO_CHAR(CNM.COD_ENTIDAD),
            CNM.RAZON_SOCIAL,
            CNM.TELEFONO,
            CNM.DIRECCION,
            CNM.COD_REGIONAL,
            RG.NOMBRE_REGIONAL,
            PC.COD_PROCESOPJ
            )
            )

            UNION(
            SELECT DISTINCT 
            PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO,
            RECEPCIONTITULOS.COD_RECEPCIONTITULO AS TITULO,
            EMPRESA.CODEMPRESA AS NIT,
            EMPRESA.RAZON_SOCIAL AS NOMEMPRESA, 
            EMPRESA.TELEFONO_FIJO AS TELEFONO,
            EMPRESA.DIRECCION AS DIRECCION,
            EMPRESA.COD_DEPARTAMENTO AS DEPARTAMENTO,
            DEPARTAMENTO.NOM_DEPARTAMENTO AS NOMDEPARTAMENTO, 
            REGIONAL.COD_REGIONAL AS REGIONAL,
            REGIONAL.NOMBRE_REGIONAL AS NOMREGIONAL,
            EMPRESA.COD_REGIMEN AS REGIMEN, 
            REGIMENTRIBUTARIO.DESC_REGIMEN AS NOMREGIMEN, 
            EMPRESA.CIIU AS CIIU, 
            RESOLUCION.NUMERO_RESOLUCION,
            PROCESOS_COACTIVOS.COD_PROCESOPJ AS CODIGOPJ, 
            to_char(MAX(RECEPCIONTITULOS.FECHA_CONSULTAONBASE), 'DD/MM/YYYY HH:MI am') AS fECHA_CONSULTAONBASE, 
            to_char(MAX(PROCESOS_COACTIVOS.FECHA_AVOCA), 'DD/MM/YYYY HH:MI am') AS FECHA_AVOCA, 
            to_char(MAX(LIQUIDACION.FECHA_RESOLUCION), 'DD/MM/YYYY HH:MI am') AS FECHA_RESOLUCION, 
            to_char(MAX(LIQUIDACION.FECHA_EJECUTORIA), 'DD/MM/YYYY HH:MI am') AS FECHA_EJECUTORIA

            FROM RECEPCIONTITULOS

            LEFT JOIN PROCESOS_COACTIVOS ON PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO = RECEPCIONTITULOS.COD_PROCESO
            INNER JOIN FISCALIZACION ON FISCALIZACION.COD_FISCALIZACION = RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA
            INNER JOIN ASIGNACIONFISCALIZACION ON ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION = FISCALIZACION.COD_ASIGNACION_FISC
            INNER JOIN EMPRESA ON EMPRESA.CODEMPRESA = ASIGNACIONFISCALIZACION.NIT_EMPRESA
            INNER JOIN DEPARTAMENTO ON DEPARTAMENTO.COD_DEPARTAMENTO = EMPRESA.COD_DEPARTAMENTO 
            LEFT JOIN REGIMENTRIBUTARIO ON REGIMENTRIBUTARIO.CODREGIMEN = EMPRESA.COD_REGIMEN 
            INNER JOIN CONCEPTOSFISCALIZACION ON CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION = FISCALIZACION.COD_CONCEPTO
            INNER JOIN RESPUESTAGESTION ON RESPUESTAGESTION.COD_RESPUESTA = RECEPCIONTITULOS.COD_TIPORESPUESTA
            INNER JOIN TIPOGESTION ON TIPOGESTION.COD_GESTION = RESPUESTAGESTION.COD_TIPOGESTION
            INNER JOIN TIPOPROCESO ON TIPOPROCESO.COD_TIPO_PROCESO = TIPOGESTION.CODPROCESO
            INNER JOIN REGIONAL ON REGIONAL.COD_REGIONAL = EMPRESA.COD_REGIONAL
            LEFT JOIN LIQUIDACION ON LIQUIDACION.COD_FISCALIZACION = FISCALIZACION.COD_FISCALIZACION
            LEFT JOIN RESOLUCION ON RESOLUCION.COD_FISCALIZACION = FISCALIZACION.COD_FISCALIZACION

            GROUP BY 
            PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO, RECEPCIONTITULOS.COD_RECEPCIONTITULO, EMPRESA.CODEMPRESA, EMPRESA.RAZON_SOCIAL,
            EMPRESA.TELEFONO_FIJO, EMPRESA.DIRECCION, EMPRESA.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO, REGIONAL.COD_REGIONAL,
            REGIONAL.NOMBRE_REGIONAL, EMPRESA.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, EMPRESA.COD_REGIMEN, REGIMENTRIBUTARIO.DESC_REGIMEN,
            EMPRESA.CIIU, PROCESOS_COACTIVOS.COD_PROCESOPJ,RESOLUCION.NUMERO_RESOLUCION

            UNION (
            SELECT DISTINCT 
            PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO,
            RECEPCIONTITULOS.COD_RECEPCIONTITULO AS TITULO,
            CNM_EMPRESA.COD_ENTIDAD AS NIT,
            CNM_EMPRESA.RAZON_SOCIAL AS NOMEMPRESA, 
            CNM_EMPRESA.TELEFONO AS TELEFONO,
            CNM_EMPRESA.DIRECCION AS DIRECCION,
            NULL,
            NULL, 
            REGIONAL.COD_REGIONAL AS REGIONAL,
            REGIONAL.NOMBRE_REGIONAL AS NOMREGIONAL,
            NULL, 
            NULL, 
            NULL, 
            NULL,
            PROCESOS_COACTIVOS.COD_PROCESOPJ AS CODIGOPJ, 
            to_char(MAX(RECEPCIONTITULOS.FECHA_CONSULTAONBASE), 'DD/MM/YYYY HH:MI am') AS FECHA_CONSULTAONBASE, 
            to_char(MAX(PROCESOS_COACTIVOS.FECHA_AVOCA), 'DD/MM/YYYY HH:MI am') AS FECHA_AVOCA, 
            NULL,
            NULL
            
            FROM RECEPCIONTITULOS

            LEFT JOIN PROCESOS_COACTIVOS ON PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO = RECEPCIONTITULOS.COD_PROCESO
            INNER JOIN CNM_CARTERANOMISIONAL ON CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL = RECEPCIONTITULOS.COD_CARTERA_NOMISIONAL
            INNER JOIN CNM_EMPRESA ON CNM_EMPRESA.COD_ENTIDAD = CNM_CARTERANOMISIONAL.COD_EMPRESA
            INNER JOIN TIPOCARTERA ON TIPOCARTERA.COD_TIPOCARTERA = CNM_CARTERANOMISIONAL.COD_TIPOCARTERA
            INNER JOIN RESPUESTAGESTION ON RESPUESTAGESTION.COD_RESPUESTA = RECEPCIONTITULOS.COD_TIPORESPUESTA
            INNER JOIN TIPOGESTION ON TIPOGESTION.COD_GESTION =RESPUESTAGESTION.COD_TIPOGESTION
            INNER JOIN REGIONAL ON REGIONAL.COD_REGIONAL = CNM_EMPRESA.COD_REGIONAL
            
            GROUP BY 
            PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO,RECEPCIONTITULOS.COD_RECEPCIONTITULO,CNM_EMPRESA.COD_ENTIDAD,CNM_EMPRESA.RAZON_SOCIAL, 
            CNM_EMPRESA.TELEFONO,CNM_EMPRESA.DIRECCION,REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL,PROCESOS_COACTIVOS.COD_PROCESOPJ

            UNION(
            SELECT DISTINCT
            PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO,
            RECEPCIONTITULOS.COD_RECEPCIONTITULO AS TITULO,
            TO_CHAR(CNM_EMPLEADO.IDENTIFICACION) AS NIT,
            CNM_EMPLEADO.NOMBRES || CNM_EMPLEADO.APELLIDOS AS NOMEMPRESA, 
            CNM_EMPLEADO.TELEFONO AS TELEFONO,
            CNM_EMPLEADO.DIRECCION AS DIRECCION,
            NULL,
            NULL, 
            REGIONAL.COD_REGIONAL AS REGIONAL,
            REGIONAL.NOMBRE_REGIONAL AS NOMREGIONAL,
            NULL, 
            NULL, 
            NULL,
            NULL,
            PROCESOS_COACTIVOS.COD_PROCESOPJ AS CODIGOPJ, 
            to_char(MAX(RECEPCIONTITULOS.FECHA_CONSULTAONBASE), 'DD/MM/YYYY HH:MI am') AS FECHA_CONSULTAONBASE, 
            to_char(MAX(PROCESOS_COACTIVOS.FECHA_AVOCA), 'DD/MM/YYYY HH:MI am') AS FECHA_AVOCA, 
            NULL,
            NULL
            
            FROM RECEPCIONTITULOS
            LEFT JOIN PROCESOS_COACTIVOS ON PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO = RECEPCIONTITULOS.COD_PROCESO
            INNER JOIN CNM_CARTERANOMISIONAL ON CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL = RECEPCIONTITULOS.COD_CARTERA_NOMISIONAL
            INNER JOIN CNM_EMPLEADO ON CNM_EMPLEADO.IDENTIFICACION = CNM_CARTERANOMISIONAL.COD_EMPLEADO
            INNER JOIN TIPOCARTERA ON TIPOCARTERA.COD_TIPOCARTERA = CNM_CARTERANOMISIONAL.COD_TIPOCARTERA
            INNER JOIN RESPUESTAGESTION ON RESPUESTAGESTION.COD_RESPUESTA = RECEPCIONTITULOS.COD_TIPORESPUESTA
            INNER JOIN TIPOGESTION ON TIPOGESTION.COD_GESTION =RESPUESTAGESTION.COD_TIPOGESTION
            INNER JOIN REGIONAL ON REGIONAL.COD_REGIONAL = CNM_EMPLEADO.COD_REGIONAL
            LEFT JOIN FISCALIZACION ON FISCALIZACION.COD_FISCALIZACION = RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA
            LEFT JOIN RESOLUCION ON RESOLUCION.COD_FISCALIZACION = FISCALIZACION.COD_FISCALIZACION
            LEFT JOIN LIQUIDACION ON LIQUIDACION.COD_FISCALIZACION = FISCALIZACION.COD_FISCALIZACION

            GROUP BY PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO,RECEPCIONTITULOS.COD_RECEPCIONTITULO,TO_CHAR(CNM_EMPLEADO.IDENTIFICACION),
            CNM_EMPLEADO.NOMBRES || CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.TELEFONO,CNM_EMPLEADO.DIRECCION,REGIONAL.COD_REGIONAL,
            REGIONAL.NOMBRE_REGIONAL,RESOLUCION.NUMERO_RESOLUCION,PROCESOS_COACTIVOS.COD_PROCESOPJ
            )
            )
            )");
//        $query = $query->result();
//        echo $this->db->last_query();
        return $query;
    }

//    function actulizatrazajuridico($empresa, $ciudad, $nombre, $regional, $proceso, $fiscaliza, $usuario, $usuar) {
//        $this->db->select('FISCALIZACION.COD_FISCALIZACION, EMPRESA.CODEMPRESA, EMPRESA.NOMBRE_EMPRESA, EMPRESA.TELEFONO_FIJO, EMPRESA.DIRECCION, EMPRESA.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO, EMPRESA.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, EMPRESA.COD_REGIMEN, REGIMENTRIBUTARIO.DESC_REGIMEN, EMPRESA.CIIU,CODIGO_PJ');
//        $this->db->select('to_char(MAX(GESTIONCOBRO.FECHA_CONTACTO),' . "'DD/MM/YYYY HH:MI am') AS FECHA_CONTACTO", FALSE);
//        $this->db->select('to_char(LIQUIDACION.FECHA_RESOLUCION,' . "'DD/MM/YYYY HH:MI am') AS FECHA_RESOLUCION", FALSE);
//        $this->db->select('to_char(LIQUIDACION.FECHA_EJECUTORIA,' . "'DD/MM/YYYY HH:MI am') AS FECHA_EJECUTORIA", FALSE);
//        $query = $this->db->join('ASIGNACIONFISCALIZACION', 'ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION = FISCALIZACION.COD_ASIGNACION_FISC', 'LEFT');
//        $query = $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA = ASIGNACIONFISCALIZACION.NIT_EMPRESA');
//        $query = $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL = EMPRESA.COD_REGIONAL', 'LEFT');
//        $query = $this->db->join('DEPARTAMENTO', 'DEPARTAMENTO.COD_DEPARTAMENTO = EMPRESA.COD_DEPARTAMENTO', 'LEFT');
//        $query = $this->db->join('REGIMENTRIBUTARIO', 'REGIMENTRIBUTARIO.CODREGIMEN = EMPRESA.COD_REGIMEN', 'LEFT');
//        $query = $this->db->join('TIPOGESTION', 'TIPOGESTION.COD_GESTION = FISCALIZACION.COD_TIPOGESTION');
//        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = TIPOGESTION.CODPROCESO');
//        $query = $this->db->join('GESTIONCOBRO', 'GESTIONCOBRO.COD_FISCALIZACION_EMPRESA = FISCALIZACION.COD_FISCALIZACION');
//        $query = $this->db->join('LIQUIDACION', 'LIQUIDACION.COD_FISCALIZACION = FISCALIZACION.COD_FISCALIZACION');
//        $query = $this->db->join('USUARIOS', 'GESTIONCOBRO.COD_USUARIO = USUARIOS.IDUSUARIO');
//        $query = $this->db->where('(ASIGNACIONFISCALIZACION.NIT_EMPRESA', $empresa);
//        $query = $this->db->or_where('EMPRESA.COD_DEPARTAMENTO', $ciudad);
////        $query = $this->db->or_where('UPPER(EMPRESA.NOMBRE_EMPRESA)', $nombre);
//        if ($usuario == 0) {
//            $query = $this->db->or_where('EMPRESA.COD_REGIONAL', $regional);
//            $query = $this->db->or_where('GESTIONCOBRO.COD_USUARIO', $usuario);
//        } else {
//            $query = $this->db->or_where('EMPRESA.COD_REGIONAL', $regional);
//            $query = $this->db->where('GESTIONCOBRO.COD_USUARIO', $usuario);
//        }
//        //$query = $this->db->or_where('TIPOGESTION.CODPROCESO', $proceso);
//        $query = $this->db->or_where('CODIGO_PJ', $fiscaliza);
//
//
//        $tal = '?';
//        if ($ciudad != '' || $empresa != '' || $fiscaliza != '' || $proceso != '' || $fiscaliza != '' || $regional != '' || $usuario != 0 || $nombre != '') {
//            $query = $this->db->or_where("UPPER(USUARIOS.NOMBRES) like '%" . $tal . "%' and 1=", 1, FALSE);
//            $query = $this->db->or_where("UPPER(USUARIOS.APELLIDOS) like '%" . $tal . "%' and 1=", 1, FALSE);
//        } else {
//            $nom = '';
//            $noms = '';
//
//            $nombreapellido = explode(' ', $usuar);
//            for ($i = 0; $i <= count($nombreapellido) - 1; $i++):
//                $nom = $nombreapellido[$i];
//                $noms = "UPPER(USUARIOS.NOMBRES) like" . "'%" . $nom . "%' and 1=1 OR UPPER(USUARIOS.APELLIDOS) like" . "'%" . $nom . "%' and 1=";
//                $query = $this->db->or_where($noms, 1, FALSE);
//            endfor;
//        }
//
//        $tal = '?';
//        if ($ciudad != '' || $empresa != '' || $fiscaliza != '' || $proceso != '' || $fiscaliza != '' || $regional != '' || $usuario != 0 || $usuar != '') {
//            $query = $this->db->or_where("UPPER(EMPRESA.NOMBRE_EMPRESA) like '%" . $tal . "%') and 1=", 1, FALSE);
////            $query = $this->db->or_where("USUARIOS.NOMBRES like '%" . $tal . "%' and 1=", 1, FALSE);
//        } else {
//            $query = $this->db->or_where("UPPER(EMPRESA.NOMBRE_EMPRESA) like '%" . $nombre . "%') and 1=", 1, FALSE);
////            $query = $this->db->or_where("USUARIOS.NOMBRES like '%" . $usuar . "%') and 1=", 1, FALSE);
//        }
//
//
//        $query = $this->db->where('FISCALIZACION.CODIGO_PJ IS NOT NULL');
//        $query = $this->db->or_where('GESTIONCOBRO.COD_TIPOGESTION', 419);
//        $query = $this->db->group_by('FISCALIZACION.COD_FISCALIZACION, EMPRESA.CODEMPRESA, EMPRESA.NOMBRE_EMPRESA,EMPRESA.TELEFONO_FIJO, EMPRESA.DIRECCION, EMPRESA.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO,EMPRESA.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, EMPRESA.COD_REGIMEN, REGIMENTRIBUTARIO.DESC_REGIMEN,EMPRESA.CIIU,CODIGO_PJ,LIQUIDACION.FECHA_RESOLUCION, LIQUIDACION.FECHA_EJECUTORIA');
//        $query = $this->db->get('FISCALIZACION');
//        echo $this->db->last_query();
//        return $query;
//    }

    function actulizatrazajuridico($empresa, $ciudad, $nombre, $regional, $proceso, $fiscaliza, $usuario, $usuar) {
//        echo $regional." ".$usuario;die();   
        if ($usuario == 0) {
            $where = "(PROCESOS_COACTIVOS.IDENTIFICACION = '" . $empresa . "' " .
                    "OR EMPRESA.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR PROCESOS_COACTIVOS.COD_PROCESOPJ = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .                    
                    "OR UPPER(EMPRESA.RAZON_SOCIAL) like '%?%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%') AND PROCESOS_COACTIVOS.PROCEDENCIA = 1";
        } else {
            $where = "(PROCESOS_COACTIVOS.IDENTIFICACION = '" . $empresa . "' " .
                    "OR (EMPRESA.COD_REGIONAL = '" . $regional . "'" .
                    " AND TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "') " .
                    "OR PROCESOS_COACTIVOS.COD_PROCESOPJ = '" . $fiscaliza . "' " .
                    "OR TO_CHAR(PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR UPPER(EMPRESA.RAZON_SOCIAL) like '%?%' "
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%')AND PROCESOS_COACTIVOS.PROCEDENCIA = 1";
        }

        if ($nombre != '') {

            $where = "(PROCESOS_COACTIVOS.IDENTIFICACION = '" . $empresa . "' " .
                    "OR EMPRESA.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR PROCESOS_COACTIVOS.COD_PROCESOPJ = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR UPPER(EMPRESA.RAZON_SOCIAL) like '%" . $nombre . "%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%') AND PROCESOS_COACTIVOS.PROCEDENCIA = 1";
//            echo $where;die();
        }
//        else {
//            $where = "PROCESOS_COACTIVOS.IDENTIFICACION = '" . $empresa . "' " .
//                    "OR EMPRESA.COD_REGIONAL = '" . $regional . "'" .
//                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
//                    "OR PROCESOS_COACTIVOS.COD_PROCESOPJ = '" . $fiscaliza . "' " .
//                    "OR UPPER(EMPRESA.NOMBRE_EMPRESA) like '%?%'"
//                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
//                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%'";
//        }

        if ($usuar != '') {

            $nom = '';
            $noms = '';
//            echo $usuar;die();

            $nombreapellido = explode(' ', $usuar);
            // print_r($nombreapellido);die();
            for ($i = 0; $i <= count($nombreapellido) - 2; $i++):
                $nom = $nombreapellido[$i];
                $noms = $noms . " OR UPPER(USUARIOS.NOMBRES) like '%" . $nom . "%'"
                        . "OR UPPER(USUARIOS.APELLIDOS) like '%" . $nom . "%'";

//                echo $noms;die();
            endfor;

//            echo $noms;die();

            $where = "(PROCESOS_COACTIVOS.IDENTIFICACION = '" . $empresa . "' " .
                    "OR EMPRESA.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR PROCESOS_COACTIVOS.COD_PROCESOPJ = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR UPPER(EMPRESA.RAZON_SOCIAL) like '%?%' " . $noms . ") AND PROCESOS_COACTIVOS.PROCEDENCIA = 1";
        }

        if ($ciudad == '' && $empresa == '' && $proceso == '' && $fiscaliza == '' && $regional == '' && $usuario == 0 && $nombre == '' && $usuar == '') {
            $where = "UPPER(EMPRESA.RAZON_SOCIAL) like '%" . $nombre . "%'";
        }

        if ($usuario == 0) {
            $where1 = "(PC.IDENTIFICACION = '" . $empresa . "' " .
                    "OR CNM.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR PC.COD_PROCESOPJ = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PC.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM.NOMBRES||' '||CNM.APELLIDOS) like '%?%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%') AND PC.PROCEDENCIA = 2";
        } else {
            $where1 = "(PC.IDENTIFICACION = '" . $empresa . "' " .
                    "OR (CNM.COD_REGIONAL = '" . $regional . "'" .
                    " AND TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "') " .
                    "OR PC.COD_PROCESOPJ = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PC.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM.NOMBRES||' '||CNM.APELLIDOS) like '%?%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%') AND PC.PROCEDENCIA = 2";
        }

        if ($nombre != '') {

            $where1 = "(PC.IDENTIFICACION = '" . $empresa . "' " .
                    "OR CNM.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR PC.COD_PROCESOPJ = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PC.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM.NOMBRES||' '||CNM.APELLIDOS) LIKE '%' || REPLACE('$nombre',' ','%') || '%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%') AND PC.PROCEDENCIA = 2";
//            echo $where;die();
        }
//        else {
//            $where1 = "PC.IDENTIFICACION = '" . $empresa . "' " .
//                    "OR CNM.COD_REGIONAL = '" . $regional . "'" .
//                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
//                    "OR PC.COD_PROCESOPJ = '" . $fiscaliza . "' " .
//                    "OR UPPER(CNM.NOMBRES||' '||CNM.APELLIDOS) like '%?%'"
//                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
//                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%'";
//        }

        if ($usuar != '') {

            $nom = '';
            $noms = '';
//            echo $usuar;die();

            $nombreapellido = explode(' ', $usuar);
            // print_r($nombreapellido);die();
            for ($i = 0; $i <= count($nombreapellido) - 2; $i++):
                $nom = $nombreapellido[$i];
                $noms = $noms . " OR UPPER(USUARIOS.NOMBRES) like '%" . $nom . "%'"
                        . "OR UPPER(USUARIOS.APELLIDOS) like '%" . $nom . "%'";

//                echo $noms;die();
            endfor;

//            echo $noms;die();

            $where1 = "(PC.IDENTIFICACION = '" . $empresa . "' " .
                    "OR CNM.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR PC.COD_PROCESOPJ = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PC.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM.NOMBRES||' '||CNM.APELLIDOS) like '%?%' " .
                    $noms . ") AND PC.PROCEDENCIA = 2";
        }

        if ($ciudad == '' && $empresa == '' && $proceso == '' && $fiscaliza == '' && $regional == '' && $usuario == 0 && $nombre == '' && $usuar == '') {
            $where1 = "UPPER(CNM.NOMBRES||' '||CNM.APELLIDOS) like '%" . $nombre . "%'";
        }

        if ($usuario == 0) {
            $where2 = "(PC.IDENTIFICACION = '" . $empresa . "' " .
                    "OR CNM.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR PC.COD_PROCESOPJ = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PC.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM.RAZON_SOCIAL) like '%?%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%') AND PC.PROCEDENCIA = 2";
        } else {
            $where2 = "(PC.IDENTIFICACION = '" . $empresa . "' " .
                    "OR (CNM.COD_REGIONAL = '" . $regional . "'" .
                    " AND TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "') " .
                    "OR PC.COD_PROCESOPJ = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PC.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM.RAZON_SOCIAL) like '%?%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%') AND PC.PROCEDENCIA = 2";
        }

        if ($nombre != '') {

            $where2 = "(PC.IDENTIFICACION = '" . $empresa . "' " .
                    "OR CNM.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR PC.COD_PROCESOPJ = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PC.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM.RAZON_SOCIAL) like '%" . $nombre . "%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%') AND PC.PROCEDENCIA = 2";
//            echo $where;die();
        }
//        else {
//            $where2 = "PC.IDENTIFICACION = '" . $empresa . "' " .
//                    "OR CNM.COD_REGIONAL = '" . $regional . "'" .
//                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
//                    "OR PC.COD_PROCESOPJ = '" . $fiscaliza . "' " .
//                    "OR UPPER(CNM.RAZON_SOCIAL) like '%?%'"
//                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
//                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%'";
//        }

        if ($usuar != '') {

            $nom = '';
            $noms = '';
//            echo $usuar;die();

            $nombreapellido = explode(' ', $usuar);
            // print_r($nombreapellido);die();
            for ($i = 0; $i <= count($nombreapellido) - 2; $i++):
                $nom = $nombreapellido[$i];
                $noms = $noms . " OR UPPER(USUARIOS.NOMBRES) like '%" . $nom . "%'"
                        . "OR UPPER(USUARIOS.APELLIDOS) like '%" . $nom . "%'";

//                echo $noms;die();
            endfor;

//            echo $noms;die();

            $where2 = "(PC.IDENTIFICACION = '" . $empresa . "' " .
                    "OR CNM.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR PC.COD_PROCESOPJ = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PC.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM.RAZON_SOCIAL) like '%?%' " . $noms . ") AND PC.PROCEDENCIA = 2";
        }

        if ($ciudad == '' && $empresa == '' && $proceso == '' && $fiscaliza == '' && $regional == '' && $usuario == 0 && $nombre == '' && $usuar == '') {
            $where2 = "UPPER(CNM.RAZON_SOCIAL) like '%" . $nombre . "%'";
        }
        
        if ($usuario == 0) {
            $where3 = "(EMPRESA.CODEMPRESA = '" . $empresa . "' " .
                    "OR EMPRESA.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR UPPER(EMPRESA.RAZON_SOCIAL) like '%?%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%')";
        } else {
            $where3 = "(EMPRESA.CODEMPRESA = '" . $empresa . "' " .
                    "OR (EMPRESA.COD_REGIONAL = '" . $regional . "'" .
                    " AND TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "') " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR UPPER(EMPRESA.RAZON_SOCIAL) like '%?%' "
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%')";
        }

        if ($nombre != '') {

            $where3 = "(EMPRESA.CODEMPRESA = '" . $empresa . "' " .
                    "OR EMPRESA.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR UPPER(EMPRESA.RAZON_SOCIAL) like '%" . $nombre . "%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%')";
        }
        if ($usuar != '') {

            $nom = '';
            $noms = '';
            $nombreapellido = explode(' ', $usuar);

            for ($i = 0; $i <= count($nombreapellido) - 2; $i++):
                $nom = $nombreapellido[$i];
                $noms = $noms . " OR UPPER(USUARIOS.NOMBRES) like '%" . $nom . "%'"
                        . "OR UPPER(USUARIOS.APELLIDOS) like '%" . $nom . "%'";

            endfor;

            $where3 = "(EMPRESA.CODEMPRESA = '" . $empresa . "' " .
                    "OR EMPRESA.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR UPPER(EMPRESA.RAZON_SOCIAL) like '%?%' " . $noms . ")";
        }

        if ($ciudad == '' && $empresa == '' && $proceso == '' && $fiscaliza == '' && $regional == '' && $usuario == 0 && $nombre == '' && $usuar == '') {
            $where3 = "UPPER(EMPRESA.RAZON_SOCIAL) like '%" . $nombre . "%'";
        }
        
        if ($usuario == 0) {
            $where4 = "(CNM_EMPRESA.COD_ENTIDAD = '" . $empresa . "' " .
                    "OR CNM_EMPRESA.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO = '" . $fiscaliza . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM_EMPRESA.RAZON_SOCIAL) like '%?%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%')";
        } else {
            $where4 = "(CNM_EMPRESA.COD_ENTIDAD = '" . $empresa . "' " .
                    "OR (CNM_EMPRESA.COD_REGIONAL = '" . $regional . "'" .
                    " AND TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "') " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM_EMPRESA.RAZON_SOCIAL) like '%?%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%')";
        }

        if ($nombre != '') {

            $where4 = "(CNM_EMPRESA.COD_ENTIDAD = '" . $empresa . "' " .
                    "OR CNM_EMPRESA.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM_EMPRESA.RAZON_SOCIAL) like '%" . $nombre . "%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%')";
//            echo $where;die();
        }
//        else {
//            $where2 = "PC.IDENTIFICACION = '" . $empresa . "' " .
//                    "OR CNM.COD_REGIONAL = '" . $regional . "'" .
//                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
//                    "OR PC.COD_PROCESOPJ = '" . $fiscaliza . "' " .
//                    "OR UPPER(CNM.RAZON_SOCIAL) like '%?%'"
//                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
//                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%'";
//        }

        if ($usuar != '') {

            $nom = '';
            $noms = '';
//            echo $usuar;die();

            $nombreapellido = explode(' ', $usuar);
            // print_r($nombreapellido);die();
            for ($i = 0; $i <= count($nombreapellido) - 2; $i++):
                $nom = $nombreapellido[$i];
                $noms = $noms . " OR UPPER(USUARIOS.NOMBRES) like '%" . $nom . "%'"
                        . "OR UPPER(USUARIOS.APELLIDOS) like '%" . $nom . "%'";

//                echo $noms;die();
            endfor;

//            echo $noms;die();

            $where4 = "(CNM_EMPRESA.COD_ENTIDAD = '" . $empresa . "' " .
                    "OR CNM_EMPRESA.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM_EMPRESA.RAZON_SOCIAL) like '%?%' " . $noms . ")";
        }

        if ($ciudad == '' && $empresa == '' && $proceso == '' && $fiscaliza == '' && $regional == '' && $usuario == 0 && $nombre == '' && $usuar == '') {
            $where4 = "UPPER(CNM_EMPRESA.RAZON_SOCIAL) like '%" . $nombre . "%'";
        }
        
        if ($usuario == 0) {
            $where5 = "(CNM_EMPLEADO.IDENTIFICACION = '" . $empresa . "' " .
                    "OR CNM_EMPLEADO.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM_EMPLEADO.NOMBRES||' '||CNM_EMPLEADO.APELLIDOS) like '%?%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%')";
        } else {
            $where5 = "(CNM_EMPLEADO.IDENTIFICACION = '" . $empresa . "' " .
                    "OR (CNM_EMPLEADO.COD_REGIONAL = '" . $regional . "'" .
                    " AND TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "') " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM_EMPLEADO.NOMBRES||' '||CNM_EMPLEADO.APELLIDOS) like '%?%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%')";
        }

        if ($nombre != '') {

            $where5 = "(CNM_EMPLEADO.IDENTIFICACION = '" . $empresa . "' " .
                    "OR CNM_EMPLEADO.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM_EMPLEADO.NOMBRES||' '||CNM_EMPLEADO.APELLIDOS) LIKE '%' || REPLACE('$nombre',' ','%') || '%'"
                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%')";
//            echo $where;die();
        }
//        else {
//            $where1 = "PC.IDENTIFICACION = '" . $empresa . "' " .
//                    "OR CNM.COD_REGIONAL = '" . $regional . "'" .
//                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
//                    "OR PC.COD_PROCESOPJ = '" . $fiscaliza . "' " .
//                    "OR UPPER(CNM.NOMBRES||' '||CNM.APELLIDOS) like '%?%'"
//                    . "OR UPPER(USUARIOS.NOMBRES) like '%?%'"
//                    . "OR UPPER(USUARIOS.APELLIDOS) like '%?%'";
//        }

        if ($usuar != '') {

            $nom = '';
            $noms = '';
//            echo $usuar;die();

            $nombreapellido = explode(' ', $usuar);
            // print_r($nombreapellido);die();
            for ($i = 0; $i <= count($nombreapellido) - 2; $i++):
                $nom = $nombreapellido[$i];
                $noms = $noms . " OR UPPER(USUARIOS.NOMBRES) like '%" . $nom . "%'"
                        . "OR UPPER(USUARIOS.APELLIDOS) like '%" . $nom . "%'";

//                echo $noms;die();
            endfor;

//            echo $noms;die();

            $where5 = "(CNM_EMPLEADO.IDENTIFICACION = '" . $empresa . "' " .
                    "OR CNM_EMPLEADO.COD_REGIONAL = '" . $regional . "'" .
                    "OR TRAZAPROCJUDICIAL.COD_USUARIO = '" . $usuario . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR  TO_CHAR(PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO) = '" . $fiscaliza . "' " .
                    "OR TO_CHAR(RECEPCIONTITULOS.COD_RECEPCIONTITULO) = '" . $fiscaliza . "' " .
                    "OR UPPER(CNM_EMPLEADO.NOMBRES||' '||CNM_EMPLEADO.APELLIDOS) like '%?%' " .
                    $noms . ")";
        }

        if ($ciudad == '' && $empresa == '' && $proceso == '' && $fiscaliza == '' && $regional == '' && $usuario == 0 && $nombre == '' && $usuar == '') {
            $where5 = "UPPER(CNM_EMPLEADO.NOMBRES||' '||CNM_EMPLEADO.APELLIDOS) like '%" . $nombre . "%'";
        }
        
        
        
        
        $query = $this->db->query("SELECT PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO,
            NULL AS TITULO,
            EMPRESA.CODEMPRESA AS NIT, 
            EMPRESA.RAZON_SOCIAL AS NOMEMPRESA, 
            EMPRESA.TELEFONO_FIJO AS TELEFONO, 
            EMPRESA.DIRECCION AS DIRECCION,
            EMPRESA.COD_DEPARTAMENTO AS DEPARTAMENTO, 
            DEPARTAMENTO.NOM_DEPARTAMENTO AS NOMDEPARTAMENTO, 
            EMPRESA.COD_REGIONAL AS REGIONAL,
            REGIONAL.NOMBRE_REGIONAL AS NOMREGIONAL, 
            EMPRESA.COD_REGIMEN AS REGIMEN, 
            REGIMENTRIBUTARIO.DESC_REGIMEN AS NOMREGIMEN, 
            EMPRESA.CIIU AS CIIU, 
            RESOLUCION.NUMERO_RESOLUCION,
            PROCESOS_COACTIVOS.COD_PROCESOPJ AS CODIGOPJ,
            to_char(MAX(RECEPCIONTITULOS.FECHA_CONSULTAONBASE), 'DD/MM/YYYY HH:MI am') AS FECHA_CONSULTAONBASE, 
            to_char(MAX(PROCESOS_COACTIVOS.FECHA_AVOCA), 'DD/MM/YYYY HH:MI am') AS FECHA_AVOCA, 
            to_char(MAX(LIQUIDACION.FECHA_RESOLUCION), 'DD/MM/YYYY HH:MI am') AS FECHA_RESOLUCION, 
            to_char(MAX(LIQUIDACION.FECHA_EJECUTORIA), 'DD/MM/YYYY HH:MI am') AS FECHA_EJECUTORIA 

            FROM PROCESOS_COACTIVOS

            LEFT JOIN EMPRESA ON EMPRESA.CODEMPRESA = PROCESOS_COACTIVOS.IDENTIFICACION 
            LEFT JOIN REGIONAL ON REGIONAL.COD_REGIONAL = EMPRESA.COD_REGIONAL 
            LEFT JOIN DEPARTAMENTO ON DEPARTAMENTO.COD_DEPARTAMENTO = EMPRESA.COD_DEPARTAMENTO 
            LEFT JOIN REGIMENTRIBUTARIO ON REGIMENTRIBUTARIO.CODREGIMEN = EMPRESA.COD_REGIMEN 
            INNER JOIN ACUMULACION_COACTIVA ON ACUMULACION_COACTIVA.COD_PROCESO_COACTIVO = PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO
            INNER JOIN RECEPCIONTITULOS ON RECEPCIONTITULOS.COD_RECEPCIONTITULO = ACUMULACION_COACTIVA.COD_RECEPCIONTITULO
            LEFT JOIN FISCALIZACION ON FISCALIZACION.COD_FISCALIZACION = RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA
            LEFT JOIN RESOLUCION ON RESOLUCION.COD_FISCALIZACION = FISCALIZACION.COD_FISCALIZACION
            LEFT JOIN LIQUIDACION ON LIQUIDACION.COD_FISCALIZACION = FISCALIZACION.COD_FISCALIZACION
            LEFT JOIN TRAZAPROCJUDICIAL ON TRAZAPROCJUDICIAL.COD_RECEPCIONTITULO = RECEPCIONTITULOS.COD_RECEPCIONTITULO
            LEFT JOIN USUARIOS ON USUARIOS.IDUSUARIO = TRAZAPROCJUDICIAL.COD_USUARIO

            WHERE" . " " . $where .
                "

            GROUP BY 
            PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO,
            EMPRESA.CODEMPRESA, 
            EMPRESA.RAZON_SOCIAL, 
            EMPRESA.TELEFONO_FIJO, 
            EMPRESA.DIRECCION,
            EMPRESA.COD_DEPARTAMENTO, 
            DEPARTAMENTO.NOM_DEPARTAMENTO, 
            EMPRESA.COD_REGIONAL,
            REGIONAL.NOMBRE_REGIONAL, 
            EMPRESA.COD_REGIMEN, 
            REGIMENTRIBUTARIO.DESC_REGIMEN, 
            EMPRESA.CIIU, 
            NUMERO_RESOLUCION,
            PROCESOS_COACTIVOS.COD_PROCESOPJ

            UNION
            (SELECT PC.COD_PROCESO_COACTIVO,
            NULL AS TITULO,
            TO_CHAR(CNM.IDENTIFICACION) AS NIT, 
            CNM.NOMBRES||' '||CNM.APELLIDOS AS NOMEMPRESA, 
            CNM.TELEFONO AS TELEFONO, 
            CNM.DIRECCION AS DIRECCION,
            NULL,
            NULL, 
            CNM.COD_REGIONAL AS REGIONAL, 
            RG.NOMBRE_REGIONAL AS NOMREGIONAL,
            NULL,
            NULL,
            NULL,
            NULL,
            PC.COD_PROCESOPJ AS CODIGOPJ,
            to_char(MAX(RECEPCIONTITULOS.FECHA_CONSULTAONBASE), 'DD/MM/YYYY HH:MI am') AS FECHA_LLEGA_COACTIVO, 
            to_char(MAX(PC.FECHA_AVOCA), 'DD/MM/YYYY HH:MI am') AS FECHA_AVOCA, 
            NULL,
            NULL
            FROM PROCESOS_COACTIVOS PC 

            INNER JOIN CNM_EMPLEADO CNM ON PC.IDENTIFICACION=CNM.IDENTIFICACION
            INNER JOIN REGIONAL RG ON CNM.COD_REGIONAL=RG.COD_REGIONAL
            INNER JOIN ACUMULACION_COACTIVA ON ACUMULACION_COACTIVA.COD_PROCESO_COACTIVO = PC.COD_PROCESO_COACTIVO
            INNER JOIN RECEPCIONTITULOS ON RECEPCIONTITULOS.COD_RECEPCIONTITULO = ACUMULACION_COACTIVA.COD_RECEPCIONTITULO
            LEFT JOIN TRAZAPROCJUDICIAL ON TRAZAPROCJUDICIAL.COD_JURIDICO = PC.COD_PROCESO_COACTIVO 
            LEFT JOIN USUARIOS ON USUARIOS.IDUSUARIO = TRAZAPROCJUDICIAL.COD_USUARIO 

            WHERE "." ". $where1.

            "GROUP BY
            PC.COD_PROCESO_COACTIVO,
            TO_CHAR(CNM.IDENTIFICACION),
            CNM.NOMBRES||' '||CNM.APELLIDOS,
            CNM.TELEFONO,
            CNM.DIRECCION,
            CNM.COD_REGIONAL,
            RG.NOMBRE_REGIONAL,
            PC.COD_PROCESOPJ
            
            UNION
            (SELECT PC.COD_PROCESO_COACTIVO,
            NULL AS TITULO,
            TO_CHAR(CNM.COD_ENTIDAD) AS NIT, 
            CNM.RAZON_SOCIAL AS NOMEMPRESA, 
            CNM.TELEFONO AS TELEFONO, 
            CNM.DIRECCION AS DIRECCION,
            NULL,
            NULL, 
            CNM.COD_REGIONAL AS REGIONAL, 
            RG.NOMBRE_REGIONAL AS NOMREGIONAL,
            NULL,
            NULL,
            NULL,
            NULL,
            PC.COD_PROCESOPJ AS CODIGOPJ,
            to_char(MAX(RECEPCIONTITULOS.FECHA_CONSULTAONBASE), 'DD/MM/YYYY HH:MI am') AS FECHA_LLEGA_COACTIVO, 
            to_char(MAX(PC.FECHA_AVOCA), 'DD/MM/YYYY HH:MI am') AS FECHA_AVOCA, 
            NULL,
            NULL
            
            FROM PROCESOS_COACTIVOS PC 

            INNER JOIN CNM_EMPRESA CNM ON PC.IDENTIFICACION=CNM.COD_ENTIDAD
            INNER JOIN REGIONAL RG ON CNM.COD_REGIONAL=RG.COD_REGIONAL
            INNER JOIN ACUMULACION_COACTIVA ON ACUMULACION_COACTIVA.COD_PROCESO_COACTIVO = PC.COD_PROCESO_COACTIVO
            INNER JOIN RECEPCIONTITULOS ON RECEPCIONTITULOS.COD_RECEPCIONTITULO = ACUMULACION_COACTIVA.COD_RECEPCIONTITULO
            LEFT JOIN TRAZAPROCJUDICIAL ON TRAZAPROCJUDICIAL.COD_JURIDICO = PC.COD_PROCESO_COACTIVO 
            LEFT JOIN USUARIOS ON USUARIOS.IDUSUARIO = TRAZAPROCJUDICIAL.COD_USUARIO  

            WHERE" . " " . $where2 .
            "GROUP BY
            PC.COD_PROCESO_COACTIVO,
            TO_CHAR(CNM.COD_ENTIDAD),
            CNM.RAZON_SOCIAL,
            CNM.TELEFONO,
            CNM.DIRECCION,
            CNM.COD_REGIONAL,
            RG.NOMBRE_REGIONAL,
            PC.COD_PROCESOPJ
            )
            )

            UNION
            SELECT DISTINCT 
            PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO,
            RECEPCIONTITULOS.COD_RECEPCIONTITULO AS TITULO,
            EMPRESA.CODEMPRESA AS NIT,
            EMPRESA.RAZON_SOCIAL AS NOMEMPRESA, 
            EMPRESA.TELEFONO_FIJO AS TELEFONO,
            EMPRESA.DIRECCION AS DIRECCION,
            EMPRESA.COD_DEPARTAMENTO AS DEPARTAMENTO,
            DEPARTAMENTO.NOM_DEPARTAMENTO AS NOMDEPARTAMENTO, 
            REGIONAL.COD_REGIONAL AS REGIONAL,
            REGIONAL.NOMBRE_REGIONAL AS NOMREGIONAL,
            EMPRESA.COD_REGIMEN AS REGIMEN, 
            REGIMENTRIBUTARIO.DESC_REGIMEN AS NOMREGIMEN, 
            EMPRESA.CIIU AS CIIU, 
            RESOLUCION.NUMERO_RESOLUCION,
            PROCESOS_COACTIVOS.COD_PROCESOPJ AS CODIGOPJ, 
            to_char(MAX(RECEPCIONTITULOS.FECHA_CONSULTAONBASE), 'DD/MM/YYYY HH:MI am') AS fECHA_CONSULTAONBASE, 
            to_char(MAX(PROCESOS_COACTIVOS.FECHA_AVOCA), 'DD/MM/YYYY HH:MI am') AS FECHA_AVOCA, 
            NULL,
            NULL
            
            FROM RECEPCIONTITULOS

            LEFT JOIN PROCESOS_COACTIVOS ON PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO = RECEPCIONTITULOS.COD_PROCESO
            INNER JOIN FISCALIZACION ON FISCALIZACION.COD_FISCALIZACION = RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA
            INNER JOIN ASIGNACIONFISCALIZACION ON ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION = FISCALIZACION.COD_ASIGNACION_FISC
            INNER JOIN EMPRESA ON EMPRESA.CODEMPRESA = ASIGNACIONFISCALIZACION.NIT_EMPRESA
            INNER JOIN DEPARTAMENTO ON DEPARTAMENTO.COD_DEPARTAMENTO = EMPRESA.COD_DEPARTAMENTO 
            LEFT JOIN REGIMENTRIBUTARIO ON REGIMENTRIBUTARIO.CODREGIMEN = EMPRESA.COD_REGIMEN 
            INNER JOIN CONCEPTOSFISCALIZACION ON CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION = FISCALIZACION.COD_CONCEPTO
            INNER JOIN RESPUESTAGESTION ON RESPUESTAGESTION.COD_RESPUESTA = RECEPCIONTITULOS.COD_TIPORESPUESTA
            INNER JOIN TIPOGESTION ON TIPOGESTION.COD_GESTION = RESPUESTAGESTION.COD_TIPOGESTION
            INNER JOIN TIPOPROCESO ON TIPOPROCESO.COD_TIPO_PROCESO = TIPOGESTION.CODPROCESO
            INNER JOIN REGIONAL ON REGIONAL.COD_REGIONAL = EMPRESA.COD_REGIONAL
            LEFT JOIN RESOLUCION ON RESOLUCION.COD_FISCALIZACION = FISCALIZACION.COD_FISCALIZACION
            LEFT JOIN LIQUIDACION ON LIQUIDACION.COD_FISCALIZACION = FISCALIZACION.COD_FISCALIZACION
            LEFT JOIN TRAZAPROCJUDICIAL ON TRAZAPROCJUDICIAL.COD_RECEPCIONTITULO = RECEPCIONTITULOS.COD_RECEPCIONTITULO
            LEFT JOIN USUARIOS ON USUARIOS.IDUSUARIO = TRAZAPROCJUDICIAL.COD_USUARIO
            WHERE" . " " . $where3 .
                "

            GROUP BY 
            PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO, RECEPCIONTITULOS.COD_RECEPCIONTITULO, EMPRESA.CODEMPRESA, EMPRESA.RAZON_SOCIAL,
            EMPRESA.TELEFONO_FIJO, EMPRESA.DIRECCION, EMPRESA.COD_DEPARTAMENTO, DEPARTAMENTO.NOM_DEPARTAMENTO, REGIONAL.COD_REGIONAL,
            REGIONAL.NOMBRE_REGIONAL,RESOLUCION.NUMERO_RESOLUCION, EMPRESA.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, EMPRESA.COD_REGIMEN, REGIMENTRIBUTARIO.DESC_REGIMEN,
            EMPRESA.CIIU, PROCESOS_COACTIVOS.COD_PROCESOPJ

            UNION (
            SELECT DISTINCT 
            PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO,
            RECEPCIONTITULOS.COD_RECEPCIONTITULO AS TITULO,
            CNM_EMPRESA.COD_ENTIDAD AS NIT,
            CNM_EMPRESA.RAZON_SOCIAL AS NOMEMPRESA, 
            CNM_EMPRESA.TELEFONO AS TELEFONO,
            CNM_EMPRESA.DIRECCION AS DIRECCION,
            NULL,
            NULL, 
            REGIONAL.COD_REGIONAL AS REGIONAL,
            REGIONAL.NOMBRE_REGIONAL AS NOMREGIONAL,
            NULL, 
            NULL, 
            NULL,
            NULL,
            PROCESOS_COACTIVOS.COD_PROCESOPJ AS CODIGOPJ, 
            to_char(MAX(RECEPCIONTITULOS.FECHA_CONSULTAONBASE), 'DD/MM/YYYY HH:MI am') AS FECHA_CONSULTAONBASE, 
            to_char(MAX(PROCESOS_COACTIVOS.FECHA_AVOCA), 'DD/MM/YYYY HH:MI am') AS FECHA_AVOCA, 
            NULL,
            NULL
            
            FROM RECEPCIONTITULOS

            LEFT JOIN PROCESOS_COACTIVOS ON PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO = RECEPCIONTITULOS.COD_PROCESO
            INNER JOIN CNM_CARTERANOMISIONAL ON CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL = RECEPCIONTITULOS.COD_CARTERA_NOMISIONAL
            INNER JOIN CNM_EMPRESA ON CNM_EMPRESA.COD_ENTIDAD = CNM_CARTERANOMISIONAL.COD_EMPRESA
            INNER JOIN TIPOCARTERA ON TIPOCARTERA.COD_TIPOCARTERA = CNM_CARTERANOMISIONAL.COD_TIPOCARTERA
            INNER JOIN RESPUESTAGESTION ON RESPUESTAGESTION.COD_RESPUESTA = RECEPCIONTITULOS.COD_TIPORESPUESTA
            INNER JOIN TIPOGESTION ON TIPOGESTION.COD_GESTION =RESPUESTAGESTION.COD_TIPOGESTION
            INNER JOIN REGIONAL ON REGIONAL.COD_REGIONAL = CNM_EMPRESA.COD_REGIONAL
            LEFT JOIN TRAZAPROCJUDICIAL ON TRAZAPROCJUDICIAL.COD_RECEPCIONTITULO = RECEPCIONTITULOS.COD_RECEPCIONTITULO
            LEFT JOIN USUARIOS ON USUARIOS.IDUSUARIO = TRAZAPROCJUDICIAL.COD_USUARIO
            WHERE" . " " . $where4 .
            "GROUP BY 
            PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO,RECEPCIONTITULOS.COD_RECEPCIONTITULO,CNM_EMPRESA.COD_ENTIDAD,CNM_EMPRESA.RAZON_SOCIAL, 
            CNM_EMPRESA.TELEFONO,CNM_EMPRESA.DIRECCION,REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL,PROCESOS_COACTIVOS.COD_PROCESOPJ

            UNION(
            SELECT DISTINCT
            PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO,
            RECEPCIONTITULOS.COD_RECEPCIONTITULO AS TITULO,
            TO_CHAR(CNM_EMPLEADO.IDENTIFICACION) AS NIT,
            CNM_EMPLEADO.NOMBRES || CNM_EMPLEADO.APELLIDOS AS NOMEMPRESA, 
            CNM_EMPLEADO.TELEFONO AS TELEFONO,
            CNM_EMPLEADO.DIRECCION AS DIRECCION,
            NULL,
            NULL, 
            REGIONAL.COD_REGIONAL AS REGIONAL,
            REGIONAL.NOMBRE_REGIONAL AS NOMREGIONAL,
            NULL, 
            NULL, 
            NULL,
            NULL,
            PROCESOS_COACTIVOS.COD_PROCESOPJ AS CODIGOPJ, 
            to_char(MAX(RECEPCIONTITULOS.FECHA_CONSULTAONBASE), 'DD/MM/YYYY HH:MI am') AS FECHA_CONSULTAONBASE, 
            to_char(MAX(PROCESOS_COACTIVOS.FECHA_AVOCA), 'DD/MM/YYYY HH:MI am') AS FECHA_AVOCA, 
            NULL,
            NULL            
            FROM RECEPCIONTITULOS
            LEFT JOIN PROCESOS_COACTIVOS ON PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO = RECEPCIONTITULOS.COD_PROCESO
            INNER JOIN CNM_CARTERANOMISIONAL ON CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL = RECEPCIONTITULOS.COD_CARTERA_NOMISIONAL
            INNER JOIN CNM_EMPLEADO ON CNM_EMPLEADO.IDENTIFICACION = CNM_CARTERANOMISIONAL.COD_EMPLEADO
            INNER JOIN TIPOCARTERA ON TIPOCARTERA.COD_TIPOCARTERA = CNM_CARTERANOMISIONAL.COD_TIPOCARTERA
            INNER JOIN RESPUESTAGESTION ON RESPUESTAGESTION.COD_RESPUESTA = RECEPCIONTITULOS.COD_TIPORESPUESTA
            INNER JOIN TIPOGESTION ON TIPOGESTION.COD_GESTION =RESPUESTAGESTION.COD_TIPOGESTION
            INNER JOIN REGIONAL ON REGIONAL.COD_REGIONAL = CNM_EMPLEADO.COD_REGIONAL
            LEFT JOIN TRAZAPROCJUDICIAL ON TRAZAPROCJUDICIAL.COD_RECEPCIONTITULO = RECEPCIONTITULOS.COD_RECEPCIONTITULO
            LEFT JOIN USUARIOS ON USUARIOS.IDUSUARIO = TRAZAPROCJUDICIAL.COD_USUARIO
            WHERE" . " " . $where5 .
            "GROUP BY PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO,RECEPCIONTITULOS.COD_RECEPCIONTITULO,TO_CHAR(CNM_EMPLEADO.IDENTIFICACION),
            CNM_EMPLEADO.NOMBRES || CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.TELEFONO,CNM_EMPLEADO.DIRECCION,REGIONAL.COD_REGIONAL,
            REGIONAL.NOMBRE_REGIONAL,PROCESOS_COACTIVOS.COD_PROCESOPJ
            )
            )
            ");
//        $query = $query->result();
//        echo $this->db->last_query();
        return $query;
    }

//    function verprocesojuridico($id) {
//        $uno = 8;
//        $dos = 23;
//        $this->db->select('(SELECT COD_TIPO_INSTANCIA FROM INSTANCIAS_PROCESOS WHERE TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO AND ROWNUM = 1) COD_TIPO_INSTANCIA,
//(SELECT COD_TIPO_PROCESO FROM INSTANCIAS_PROCESOS WHERE TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO AND ROWNUM = 1) COD_TIPO_PROCESO', false);
//        $this->db->select('TIPOPROCESO.TIPO_PROCESO, GESTIONCOBRO.COD_GESTION_COBRO, 
//GESTIONCOBRO.COD_FISCALIZACION_EMPRESA, GESTIONCOBRO.NIT_EMPRESA, EMPRESA.NOMBRE_EMPRESA, GESTIONCOBRO.COD_TIPOGESTION, 
//TIPOGESTION.TIPOGESTION, GESTIONCOBRO.COD_TIPO_RESPUESTA, RESPUESTAGESTION.NOMBRE_GESTION, GESTIONCOBRO.COMENTARIOS, 
//GESTIONCOBRO.COD_USUARIO, USUARIOS.APELLIDOS, USUARIOS.NOMBRES, TIPOGESTION.CODPROCESO, TIPOPROCESO.TIPO_PROCESO');
//        $this->db->select('to_char("GESTIONCOBRO"."FECHA_CONTACTO",' . "'DD/MM/YYYY HH:MI am') AS FECHA_CONTACTO", FALSE);
//
//        $query = $this->db->join('EMPRESA', 'GESTIONCOBRO.NIT_EMPRESA = EMPRESA.CODEMPRESA', 'LEFT');
//        $query = $this->db->join('USUARIOS', 'USUARIOS.IDUSUARIO = GESTIONCOBRO.COD_USUARIO', 'LEFT');
//        $query = $this->db->join('TIPOGESTION', 'GESTIONCOBRO.COD_TIPOGESTION = TIPOGESTION.COD_GESTION', 'LEFT');
//        $query = $this->db->join('TIPOPROCESO', 'TIPOGESTION.CODPROCESO = TIPOPROCESO.COD_TIPO_PROCESO', 'LEFT');
////        $query = $this->db->join('INSTANCIAS_PROCESOS', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO', 'LEFT');
////        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = TIPOPROCESO.COD_TIPO_INSTANCIA', 'LEFT');
////        $query = $this->db->join('INSTANCIAS_PROCESOS  A', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = A.COD_TIPO_INSTANCIA', 'left', false);
//        $query = $this->db->join('RESPUESTAGESTION', 'GESTIONCOBRO.COD_TIPO_RESPUESTA = RESPUESTAGESTION.COD_RESPUESTA', 'LEFT');
//        $query = $this->db->where('GESTIONCOBRO.COD_FISCALIZACION_EMPRESA', $id);
//        $query = $this->db->where('TIPOGESTION.CODPROCESO >=', $uno);
//        $query = $this->db->where('TIPOGESTION.CODPROCESO <=', $dos);
//        $query = $this->db->order_by("GESTIONCOBRO.FECHA_CONTACTO", "DESC");
/////        $query = $this->db->where('GESTIONCOBRO.GESTIONCOBRO.NIT_EMPRESA', $id);
//        $query = $this->db->get('GESTIONCOBRO');
////        echo $this->db->last_query();
//
//        return $query->result_array;
//        //echo $this->db->last_query();
//    }

    function verprocesojuridico($id,$titulo) {
        $proceso = 8;
        $proceso_fin = 23;
        if($id != '' && $titulo == ''){
        $this->db->select('(SELECT COD_TIPO_INSTANCIA FROM INSTANCIAS_PROCESOS WHERE TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO AND ROWNUM = 1) COD_TIPO_INSTANCIA, 
(SELECT COD_TIPO_PROCESO FROM INSTANCIAS_PROCESOS WHERE TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO AND ROWNUM = 1) COD_TIPO_PROCESO', false);
        $this->db->select('TIPOPROCESO.TIPO_PROCESO, TRAZAPROCJUDICIAL.COD_TRAZAPROCJUDICIAL, TRAZAPROCJUDICIAL.COD_JURIDICO, PROCESOS_COACTIVOS.IDENTIFICACION, Fn_Razon_Social(PROCESOS_COACTIVOS.IDENTIFICACION) AS Nombre_propietario, TRAZAPROCJUDICIAL.COD_TIPOGESTION, TIPOGESTION.TIPOGESTION, TRAZAPROCJUDICIAL.COD_TIPO_RESPUESTA, RESPUESTAGESTION.NOMBRE_GESTION, TRAZAPROCJUDICIAL.COMENTARIOS, TRAZAPROCJUDICIAL.COD_USUARIO, USUARIOS.APELLIDOS, USUARIOS.NOMBRES, TIPOGESTION.CODPROCESO, TIPOPROCESO.TIPO_PROCESO');
        $this->db->select('to_char("TRAZAPROCJUDICIAL"."FECHA",' . "'DD/MM/YYYY HH:MI am') AS FECHA", FALSE);
        $query = $this->db->join('PROCESOS_COACTIVOS', 'PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO = TRAZAPROCJUDICIAL.COD_JURIDICO', 'LEFT');
//        $query = $this->db->join('EMPRESA', 'SOLICITUDDEVOLUCION.NIT = EMPRESA.CODEMPRESA ', 'LEFT');
        $query = $this->db->join('USUARIOS', 'USUARIOS.IDUSUARIO = TRAZAPROCJUDICIAL.COD_USUARIO ', 'LEFT');
        $query = $this->db->join('TIPOGESTION', 'TRAZAPROCJUDICIAL.COD_TIPOGESTION = TIPOGESTION.COD_GESTION ', 'LEFT');
        $query = $this->db->join('TIPOPROCESO', 'TIPOGESTION.CODPROCESO = TIPOPROCESO.COD_TIPO_PROCESO', 'LEFT');
//        $query = $this->db->join('INSTANCIAS_PROCESOS', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO', 'LEFT');
//        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = TIPOPROCESO.COD_TIPO_INSTANCIA', 'LEFT');
//        $query = $this->db->join('INSTANCIAS_PROCESOS  A', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = A.COD_TIPO_INSTANCIA', 'left', false);
        $query = $this->db->join('RESPUESTAGESTION', 'TRAZAPROCJUDICIAL.COD_TIPO_RESPUESTA = RESPUESTAGESTION.COD_RESPUESTA', 'LEFT');

        $query = $this->db->where('TRAZAPROCJUDICIAL.COD_JURIDICO', $id);
        $query = $this->db->where('((TIPOGESTION.CODPROCESO>=', $proceso, FALSE);
        $query = $this->db->where('TIPOGESTION.CODPROCESO <='.$proceso_fin.')OR TIPOGESTION.CODPROCESO IS NULL)and 1=',1,FALSE);
        $query = $this->db->order_by('TRAZAPROCJUDICIAL.FECHA', "DESC");
//        $query = $this->db->limit(50);
//        $query = $this->db->where('GESTIONCOBRO.GESTIONCOBRO.NIT_EMPRESA', $id);
        $query = $this->db->get('TRAZAPROCJUDICIAL');

//        echo $this->db->last_query();

        return $query->result_array;
        }else{
         $this->db->select('(SELECT COD_TIPO_INSTANCIA FROM INSTANCIAS_PROCESOS WHERE TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO AND ROWNUM = 1) COD_TIPO_INSTANCIA, 
(SELECT COD_TIPO_PROCESO FROM INSTANCIAS_PROCESOS WHERE TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO AND ROWNUM = 1) COD_TIPO_PROCESO', false);
        $this->db->select('TIPOPROCESO.TIPO_PROCESO, TRAZAPROCJUDICIAL.COD_TRAZAPROCJUDICIAL, TRAZAPROCJUDICIAL.COD_RECEPCIONTITULO, 
Fn_Recepcion_Id('.$titulo.') AS IDENTIFICACION, Fn_Razon_Social(Fn_Recepcion_Id('.$titulo.')) AS NOMBRE_PROPIETARIO, TRAZAPROCJUDICIAL.COD_TIPOGESTION, TIPOGESTION.TIPOGESTION, TRAZAPROCJUDICIAL.COD_TIPO_RESPUESTA, RESPUESTAGESTION.NOMBRE_GESTION, TRAZAPROCJUDICIAL.COMENTARIOS, TRAZAPROCJUDICIAL.COD_USUARIO, USUARIOS.APELLIDOS, USUARIOS.NOMBRES, TIPOGESTION.CODPROCESO, TIPOPROCESO.TIPO_PROCESO');
        $this->db->select('to_char("TRAZAPROCJUDICIAL"."FECHA",' . "'DD/MM/YYYY HH:MI am') AS FECHA", FALSE);
        $query = $this->db->join('RECEPCIONTITULOS', 'RECEPCIONTITULOS.COD_RECEPCIONTITULO = TRAZAPROCJUDICIAL.COD_RECEPCIONTITULO', 'LEFT');
//        $query = $this->db->join('EMPRESA', 'SOLICITUDDEVOLUCION.NIT = EMPRESA.CODEMPRESA ', 'LEFT');
        $query = $this->db->join('USUARIOS', 'USUARIOS.IDUSUARIO = TRAZAPROCJUDICIAL.COD_USUARIO ', 'LEFT');
        $query = $this->db->join('TIPOGESTION', 'TRAZAPROCJUDICIAL.COD_TIPOGESTION = TIPOGESTION.COD_GESTION ', 'LEFT');
        $query = $this->db->join('TIPOPROCESO', 'TIPOGESTION.CODPROCESO = TIPOPROCESO.COD_TIPO_PROCESO', 'LEFT');
//        $query = $this->db->join('INSTANCIAS_PROCESOS', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO', 'LEFT');
//        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = TIPOPROCESO.COD_TIPO_INSTANCIA', 'LEFT');
//        $query = $this->db->join('INSTANCIAS_PROCESOS  A', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = A.COD_TIPO_INSTANCIA', 'left', false);
        $query = $this->db->join('RESPUESTAGESTION', 'TRAZAPROCJUDICIAL.COD_TIPO_RESPUESTA = RESPUESTAGESTION.COD_RESPUESTA', 'LEFT');
        $query = $this->db->where('TRAZAPROCJUDICIAL.COD_RECEPCIONTITULO', $titulo);
        $query = $this->db->where('TIPOGESTION.CODPROCESO>=', $proceso, FALSE);
        $query = $this->db->where('TIPOGESTION.CODPROCESO<=', $proceso_fin, FALSE);
        $query = $this->db->order_by('TRAZAPROCJUDICIAL.FECHA', "DESC");
//        $query = $this->db->limit(50);
//        $query = $this->db->where('GESTIONCOBRO.GESTIONCOBRO.NIT_EMPRESA', $id);
        $query = $this->db->get('TRAZAPROCJUDICIAL');

//        echo $this->db->last_query();

        return $query->result_array;   
        }
       
        //echo $this->db->last_query();
    }

//    function verprocesonomisional($id) {
//        $proceso = 27;
//        $this->db->select('(SELECT COD_TIPO_INSTANCIA FROM INSTANCIAS_PROCESOS WHERE TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO AND ROWNUM = 1) COD_TIPO_INSTANCIA, 
//(SELECT COD_TIPO_PROCESO FROM INSTANCIAS_PROCESOS WHERE TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO AND ROWNUM = 1) COD_TIPO_PROCESO', false);
//        $this->db->select('TIPOPROCESO.TIPO_PROCESO, TRAZAPROCJUDICIAL.COD_TRAZAPROCJUDICIAL, TRAZAPROCJUDICIAL.COD_JURIDICO, PROCESOS_COACTIVOS.IDENTIFICACION, Fn_Razon_Social(PROCESOS_COACTIVOS.IDENTIFICACION) AS Nombre_propietario, TRAZAPROCJUDICIAL.COD_TIPOGESTION, TIPOGESTION.TIPOGESTION, TRAZAPROCJUDICIAL.COD_TIPO_RESPUESTA, RESPUESTAGESTION.NOMBRE_GESTION, TRAZAPROCJUDICIAL.COMENTARIOS, TRAZAPROCJUDICIAL.COD_USUARIO, USUARIOS.APELLIDOS, USUARIOS.NOMBRES, TIPOGESTION.CODPROCESO, TIPOPROCESO.TIPO_PROCESO');
//        $this->db->select('to_char("TRAZAPROCJUDICIAL"."FECHA",' . "'DD/MM/YYYY HH:MI am') AS FECHA", FALSE);
//        $query = $this->db->join('PROCESOS_COACTIVOS', 'PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO = TRAZAPROCJUDICIAL.COD_JURIDICO', 'LEFT');
////        $query = $this->db->join('EMPRESA', 'SOLICITUDDEVOLUCION.NIT = EMPRESA.CODEMPRESA ', 'LEFT');
//        $query = $this->db->join('USUARIOS', 'USUARIOS.IDUSUARIO = TRAZAPROCJUDICIAL.COD_USUARIO ', 'LEFT');
//        $query = $this->db->join('TIPOGESTION', 'TRAZAPROCJUDICIAL.COD_TIPOGESTION = TIPOGESTION.COD_GESTION ', 'LEFT');
//        $query = $this->db->join('TIPOPROCESO', 'TIPOGESTION.CODPROCESO = TIPOPROCESO.COD_TIPO_PROCESO', 'LEFT');
////        $query = $this->db->join('INSTANCIAS_PROCESOS', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO', 'LEFT');
////        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = TIPOPROCESO.COD_TIPO_INSTANCIA', 'LEFT');
////        $query = $this->db->join('INSTANCIAS_PROCESOS  A', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = A.COD_TIPO_INSTANCIA', 'left', false);
//        $query = $this->db->join('RESPUESTAGESTION', 'TRAZAPROCJUDICIAL.COD_TIPO_RESPUESTA = RESPUESTAGESTION.COD_RESPUESTA', 'LEFT');
//
//        $query = $this->db->where('TRAZAPROCJUDICIAL.COD_JURIDICO', $id);
//        $query = $this->db->where('TIPOGESTION.CODPROCESO', $proceso);
//        $query = $this->db->order_by('TRAZAPROCJUDICIAL.FECHA', "DESC");
////        $query = $this->db->limit(50);
////        $query = $this->db->where('GESTIONCOBRO.GESTIONCOBRO.NIT_EMPRESA', $id);
//        $query = $this->db->get('TRAZAPROCJUDICIAL');
//
//        echo $this->db->last_query();
//
//        return $query->result_array;
//        //echo $this->db->last_query();
//    }

    function verprocesonomisional($id) {
        $query = $this->db->query("SELECT 
            (SELECT COD_TIPO_INSTANCIA FROM INSTANCIAS_PROCESOS 
            WHERE TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO AND ROWNUM = 1) COD_TIPO_INSTANCIA, 
            (SELECT COD_TIPO_PROCESO FROM INSTANCIAS_PROCESOS 
            WHERE TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO AND ROWNUM = 1) COD_TIPO_PROCESO, 
            TIPOPROCESO.TIPO_PROCESO, 
            TRAZAPROCJUDICIAL.COD_TRAZAPROCJUDICIAL,
            CNM_CARTERANOMISIONAL.COD_TIPOCARTERA,
            TIPOCARTERA.NOMBRE_CARTERA,
            TRAZAPROCJUDICIAL.COD_TRAZAPROCJUDICIAL, 
            TRAZAPROCJUDICIAL.COD_CARTERANOMISIONAL, 
            CNM_CARTERANOMISIONAL.COD_EMPRESA, 
            CNM_EMPRESA.RAZON_SOCIAL AS NOMEMPRESA, 
            TRAZAPROCJUDICIAL.COD_TIPOGESTION, 
            TIPOGESTION.TIPOGESTION, 
            TRAZAPROCJUDICIAL.COD_TIPO_RESPUESTA, 
            RESPUESTAGESTION.NOMBRE_GESTION, 
            TRAZAPROCJUDICIAL.COMENTARIOS, 
            TRAZAPROCJUDICIAL.COD_USUARIO, 
            USUARIOS.APELLIDOS, 
            USUARIOS.NOMBRES, 
            TIPOGESTION.CODPROCESO, 
            TIPOPROCESO.TIPO_PROCESO, 
            to_char(TRAZAPROCJUDICIAL.FECHA, 'DD/MM/YYYY HH:MI am') AS FECHA 
            FROM TRAZAPROCJUDICIAL 
            LEFT JOIN CNM_CARTERANOMISIONAL ON CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL = TRAZAPROCJUDICIAL.COD_CARTERANOMISIONAL 
            INNER JOIN CNM_EMPRESA ON CNM_EMPRESA.COD_ENTIDAD = CNM_CARTERANOMISIONAL.COD_EMPRESA
            LEFT JOIN TIPOCARTERA ON TIPOCARTERA.COD_TIPOCARTERA = CNM_CARTERANOMISIONAL.COD_TIPOCARTERA
            LEFT JOIN USUARIOS ON USUARIOS.IDUSUARIO = TRAZAPROCJUDICIAL.COD_USUARIO 
            LEFT JOIN TIPOGESTION ON TRAZAPROCJUDICIAL.COD_TIPOGESTION = TIPOGESTION.COD_GESTION 
            LEFT JOIN TIPOPROCESO ON TIPOGESTION.CODPROCESO = TIPOPROCESO.COD_TIPO_PROCESO 
            LEFT JOIN RESPUESTAGESTION ON TRAZAPROCJUDICIAL.COD_TIPO_RESPUESTA = RESPUESTAGESTION.COD_RESPUESTA 
            WHERE TRAZAPROCJUDICIAL.COD_CARTERANOMISIONAL = '$id' AND ((TIPOGESTION.CODPROCESO = 27)OR TIPOGESTION.CODPROCESO IS NULL)
            UNION(
            SELECT 
            (SELECT COD_TIPO_INSTANCIA FROM INSTANCIAS_PROCESOS 
            WHERE TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO AND ROWNUM = 1) COD_TIPO_INSTANCIA, 
            (SELECT COD_TIPO_PROCESO FROM INSTANCIAS_PROCESOS 
            WHERE TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO AND ROWNUM = 1) COD_TIPO_PROCESO, 
            TIPOPROCESO.TIPO_PROCESO, 
            TRAZAPROCJUDICIAL.COD_TRAZAPROCJUDICIAL,
            CNM_CARTERANOMISIONAL.COD_TIPOCARTERA,
            TIPOCARTERA.NOMBRE_CARTERA,
            TRAZAPROCJUDICIAL.COD_TRAZAPROCJUDICIAL, 
            TRAZAPROCJUDICIAL.COD_CARTERANOMISIONAL, 
            TO_CHAR(CNM_CARTERANOMISIONAL.COD_EMPLEADO), 
            (CNM_EMPLEADO.NOMBRES||' '||CNM_EMPLEADO.APELLIDOS) AS NOMEMPRESA, 
            TRAZAPROCJUDICIAL.COD_TIPOGESTION, 
            TIPOGESTION.TIPOGESTION, 
            TRAZAPROCJUDICIAL.COD_TIPO_RESPUESTA, 
            RESPUESTAGESTION.NOMBRE_GESTION, 
            TRAZAPROCJUDICIAL.COMENTARIOS, 
            TRAZAPROCJUDICIAL.COD_USUARIO, 
            USUARIOS.APELLIDOS, 
            USUARIOS.NOMBRES, 
            TIPOGESTION.CODPROCESO, 
            TIPOPROCESO.TIPO_PROCESO, 
            to_char(TRAZAPROCJUDICIAL.FECHA, 'DD/MM/YYYY HH:MI am') AS FECHA 
            FROM TRAZAPROCJUDICIAL 
            LEFT JOIN CNM_CARTERANOMISIONAL ON CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL = TRAZAPROCJUDICIAL.COD_CARTERANOMISIONAL 
            INNER JOIN CNM_EMPLEADO ON CNM_EMPLEADO.IDENTIFICACION = CNM_CARTERANOMISIONAL.COD_EMPLEADO
            LEFT JOIN TIPOCARTERA ON TIPOCARTERA.COD_TIPOCARTERA = CNM_CARTERANOMISIONAL.COD_TIPOCARTERA
            LEFT JOIN USUARIOS ON USUARIOS.IDUSUARIO = TRAZAPROCJUDICIAL.COD_USUARIO 
            LEFT JOIN TIPOGESTION ON TRAZAPROCJUDICIAL.COD_TIPOGESTION = TIPOGESTION.COD_GESTION 
            LEFT JOIN TIPOPROCESO ON TIPOGESTION.CODPROCESO = TIPOPROCESO.COD_TIPO_PROCESO 
            LEFT JOIN RESPUESTAGESTION ON TRAZAPROCJUDICIAL.COD_TIPO_RESPUESTA = RESPUESTAGESTION.COD_RESPUESTA 
            WHERE TRAZAPROCJUDICIAL.COD_CARTERANOMISIONAL = '$id' AND ((TIPOGESTION.CODPROCESO = 27)OR TIPOGESTION.CODPROCESO IS NULL) )
            ORDER BY 18 DESC ");
//        echo $this->db->last_query();
      //$query = $this->db->get('');
//        $query = 
        return $query->result_array();
    }
    
//*********************PROCESO ADMIN****************************************    
    

    function fiscalizacion() {
        $fiscaliza = 1;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $fiscaliza);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

    function acuerdopago() {
        $acuerdo = 2;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $acuerdo);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

    function notiaportesfic() {
        $notiafic = 3;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $notiafic);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

    function noticontrato() {
        $notiafic = 4;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $notiafic);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

    function liquidacion() {
        $liquida = 5;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $liquida);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

    function ejecutaficca() {
        $ejecutaa = 6;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $ejecutaa);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

    function ejecutamultas() {
        $ejecutam = 7;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $ejecutam);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

//******************PROCESO JURIDICO***************************************

    function tituloyavoca() {
        $avoca = 8;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $avoca);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

    function acercapersuasivo() {
        $acerca = 9;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $acerca);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

    function mandamiento() {
        $manda = 10;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $manda);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

    function investigacion() {
        $inves = 11;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $inves);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

    function mcavaluo() {
        $avalu = 12;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $avalu);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

    function projudiciales() {
        $judicial = 13;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $judicial);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

    function liquidacoactivo() {
        $liquidacoa = 14;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $liquidacoa);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

    function nulidades() {
        $nulidad = 15;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $nulidad);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

    function remisibilidad() {
        $remisi = 16;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $remisi);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

    function mcremate() {
        $remate = 17;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $remate);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

    function acuerdocoactivo() {
        $acuerdo = 18;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $acuerdo);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

    function verificacoactivo() {
        $vericoa = 19;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $vericoa);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;

        //echo $this->db->last_query();
    }

    function rireorganiza() {
        $reorganiza = 20;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $reorganiza);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;
        //echo $this->db->last_query();
    }

    function riliquidacion() {
        $liquidari = 21;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $liquidari);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;
        //echo $this->db->last_query();
    }

    function trasladojudicial() {
        $trasjudi = 22;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $trasjudi);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;
        //echo $this->db->last_query();
    }

    function resolupres() {
        $resopres = 23;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $resopres);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;
        //echo $this->db->last_query();
    }

//******************DEVOLUCIONES***************************************
    function devolucionrnm() {
        $devornm = 24;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $devornm);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;
        //echo $this->db->last_query();
    }

    function devoluciondg() {
        $devodg = 25;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $devodg);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;
        //echo $this->db->last_query();
    }

    function devolucionrm() {
        $devorm = 26;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $devorm);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;
        //echo $this->db->last_query();
    }

    //******************NO MISIONAL***************************************
    function nomisional() {
        $procesonm = 27;
        $estado = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA, INSTANCIAS_PROCESOS.COD_TIPO_PROCESO, TIPOPROCESO.TIPO_PROCESO');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
        $query = $this->db->where('TIPOPROCESO.COD_TIPO_PROCESO', $procesonm);
        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', $estado);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();

        return $query->result_array;
        //echo $this->db->last_query();
    }

}
