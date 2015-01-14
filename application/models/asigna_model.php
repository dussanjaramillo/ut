<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Asigna_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getDatax($ciu, $ciudad, $empresa, $regional, $reg, $search, $lenght = 10) {
        $this->db->distinct();
        $this->db->select('EV.COD_EMPRESA,
                            E.RAZON_SOCIAL,
                            M.NOMBREMUNICIPIO,
                            T.NOM_TIPO_EMP,
                            E.CIIU  ');
        $this->db->from('EMPRESASEVASORAS EV');
        $this->db->join('EMPRESA E', 'E.CODEMPRESA = EV.COD_EMPRESA', 'inner');
		$this->db->join('REGIONAL R', 'E.COD_REGIONAL = R.COD_REGIONAL', 'inner');
        $this->db->join('MUNICIPIO M', 'M.COD_DEPARTAMENTO = R.COD_DEPARTAMENTO AND M.CODMUNICIPIO=E.COD_MUNICIPIO', 'inner');
        $this->db->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = E.COD_TIPOEMPRESA', 'inner');
        $this->db->where("EV.COD_EMPRESA NOT IN (SELECT NIT_EMPRESA FROM ASIGNACIONFISCALIZACION)");
        $this->db->where('EV.FISCALIZADA', null);
        $this->db->where('E.COD_REGIONAL', $regional);


        if ($ciu > 0) {
            $this->db->where('E.CIIU', $ciu);
        }
        if ($empresa > 0) {
            $this->db->where('E.COD_TIPOEMPRESA', $empresa);
        }
        if ($ciudad > 0) {
            $this->db->where('E.COD_MUNICIPIO', $ciudad);
        }




        if ($search) {
            $array = array(
                    //'EV.COD_EMP_EVASORA'        => $search,
                    // 'EM.NOMBRE_EMPRESA'    => $search,
                    // 'MM.VALOR'             => $search
            );
            $this->db->or_like($array);
        }
        //$this->db->group_by("EV.COD_EMPRESA"); 
        $this->db->limit($lenght, $reg);
        $query = $this->db->get();
		//echo $this->db->last_query();
        return $query->result();
    }

    function totalData($ciu, $ciudad, $empresa, $regional, $search) {
        $this->db->distinct();
        $this->db->select('EV.COD_EMPRESA,
                            E.RAZON_SOCIAL,
                            M.NOMBREMUNICIPIO,
                            T.NOM_TIPO_EMP,
                            E.CIIU  ');
        $this->db->from('EMPRESASEVASORAS EV');
        $this->db->join('EMPRESA E', 'E.CODEMPRESA = EV.COD_EMPRESA', 'inner');
        $this->db->join('MUNICIPIO M', 'M.CODMUNICIPIO = E.COD_MUNICIPIO AND M.COD_DEPARTAMENTO = E.COD_REGIONAL', 'inner');
        $this->db->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = E.COD_TIPOEMPRESA', 'inner');
        $this->db->where("EV.COD_EMPRESA NOT IN (SELECT NIT_EMPRESA FROM ASIGNACIONFISCALIZACION)");
        $this->db->where('EV.FISCALIZADA', null);
        $this->db->where('E.COD_REGIONAL', $regional);

        if ($ciu > 0) {
            $this->db->where('E.CIIU', $ciu);
        }
        if ($empresa > 0) {
            $this->db->where('E.COD_TIPOEMPRESA', $empresa);
        }
        if ($ciudad > 0) {
            $this->db->where('E.COD_MUNICIPIO', $ciudad);
        }


        if ($search) {
            $array = array(
                    //'EV.COD_EMP_EVASORA'        => $search,
                    // 'EM.NOMBRE_EMPRESA'    => $search,
                    // 'MM.VALOR'             => $search
            );
            $this->db->or_like($array);
        }
        //$this->db->group_by("EV.COD_EMPRESA");
        $query = $this->db->get();
        if ($query->num_rows() == 0)
            return '0';
        return $query->num_rows();
    }

    /*     * ********************************************** EMPIEZAN LAS CONSULTAS PARA GENERAL LAS TABLAS ***************************************************** */

    function asignacionNuevas() {
        if ($this->ion_auth->is_admin() || $this->session->userdata('regional') == 999) {




            $this->db->select('EMPRESA.CODEMPRESA,
                                                EMPRESA.RAZON_SOCIAL,
                                                EMPRESA.TELEFONO_FIJO,
                                                EMPRESA.DIRECCION,
                                                R.NOMBRE_REGIONAL,
                                                T.NOM_TIPO_EMP,
                                                EMPRESA.CIIU,
                                                EMPRESA.NUM_EMPLEADOS,
                                                A.NOM_TIPO_ENT,
                                                E.NOMBREESTADO');

            $this->db->from('EMPRESA');
            $this->db->join('REGIONAL R', 'R.COD_REGIONAL = EMPRESA.COD_REGIONAL', 'inner');
            $this->db->join('ESTADOS E', 'E.IDESTADO = EMPRESA.ACTIVO', 'left');
            //$this->db->join('MUNICIPIO M','M.CODMUNICIPIO = EMPRESA.COD_MUNICIPIO', 'inner');
            $this->db->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = EMPRESA.COD_TIPOEMPRESA', 'left');
            $this->db->join('TIPOENTIDAD A', 'A.CODTIPOENTIDAD = EMPRESA.COD_TIPOENTIDAD', 'left');

            $this->db->where("EMPRESA.CODEMPRESA NOT IN (SELECT COD_EMPRESA FROM EMPRESASEVASORAS)");
            $this->db->where("EMPRESA.CODEMPRESA NOT IN (SELECT NIT_EMPRESA FROM ASIGNACIONFISCALIZACION)");
        } else {

            if ($this->ion_auth->in_menu('asignarfiscalizacion/asignar')) {


                $this->db->select('EMPRESA.CODEMPRESA,
                                                    EMPRESA.RAZON_SOCIAL,
                                                    EMPRESA.TELEFONO_FIJO,
                                                    EMPRESA.DIRECCION,
                                                    R.NOMBRE_REGIONAL,
                                                    T.NOM_TIPO_EMP,
                                                    EMPRESA.CIIU,
                                                    EMPRESA.NUM_EMPLEADOS,
                                                    A.NOM_TIPO_ENT,
                                                    E.NOMBREESTADO');

                $this->db->from('EMPRESA');
                $this->db->join('REGIONAL R', 'R.COD_REGIONAL = EMPRESA.COD_REGIONAL', 'inner');
                $this->db->join('ESTADOS E', 'E.IDESTADO = EMPRESA.ACTIVO', 'left');
                //$this->db->join('MUNICIPIO M','M.CODMUNICIPIO = EMPRESA.COD_MUNICIPIO', 'inner');
                $this->db->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = EMPRESA.COD_TIPOEMPRESA', 'left');
                $this->db->join('TIPOENTIDAD A', 'A.CODTIPOENTIDAD = EMPRESA.COD_TIPOENTIDAD', 'left');
                $this->db->where("EMPRESA.CODEMPRESA NOT IN (SELECT COD_EMPRESA FROM EMPRESASEVASORAS)");
                $this->db->where("EMPRESA.CODEMPRESA NOT IN (SELECT NIT_EMPRESA FROM ASIGNACIONFISCALIZACION)");
                $this->db->where('EMPRESA.COD_REGIONAL', $this->session->userdata('regional'));
            } else {


                $this->db->select('EMPRESA.CODEMPRESA,
                                                    EMPRESA.RAZON_SOCIAL,
                                                    EMPRESA.TELEFONO_FIJO,
                                                    EMPRESA.DIRECCION,
                                                    R.NOMBRE_REGIONAL,
                                                    T.NOM_TIPO_EMP,
                                                    EMPRESA.CIIU,
                                                    EMPRESA.NUM_EMPLEADOS,
                                                    A.NOM_TIPO_ENT,
                                                    E.NOMBREESTADO');

                $this->db->from('EMPRESA');
                $this->db->join('REGIONAL R', 'R.COD_REGIONAL = EMPRESA.COD_REGIONAL', 'inner');
                $this->db->join('ESTADOS E', 'E.IDESTADO = EMPRESA.ACTIVO', 'left');
                //$this->db->join('MUNICIPIO M','M.CODMUNICIPIO = EMPRESA.COD_MUNICIPIO', 'inner');
                $this->db->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = EMPRESA.COD_TIPOEMPRESA', 'left');
                $this->db->join('TIPOENTIDAD A', 'A.CODTIPOENTIDAD = EMPRESA.COD_TIPOENTIDAD', 'left');
                $this->db->where("EMPRESA.CODEMPRESA NOT IN (SELECT COD_EMPRESA FROM EMPRESASEVASORAS)");
                $this->db->where("EMPRESA.CODEMPRESA NOT IN (SELECT NIT_EMPRESA FROM ASIGNACIONFISCALIZACION)");
                $this->db->where('EMPRESA.COD_REGIONAL', $this->session->userdata('regional'));
            }
        }

        return $this->db->get();
    }

    function asignaEvasoras() {
        if ($this->ion_auth->is_admin() || $this->session->userdata('regional') == 999) {




            $this->db->distinct();
            $this->db->select("EMPRESASEVASORAS.COD_EMPRESA,
                                                            E.RAZON_SOCIAL,
                                                            E.TELEFONO_FIJO,
                                                            E.DIRECCION,
                                                            R.NOMBRE_REGIONAL,
                                                            T.NOM_TIPO_EMP,
                                                            E.CIIU,
                                                            E.NUM_EMPLEADOS,
                                                            A.NOM_TIPO_ENT,
                                                            S.NOMBREESTADO");

            $this->db->from('EMPRESASEVASORAS');
            $this->db->join('EMPRESA E', 'E.CODEMPRESA = EMPRESASEVASORAS.COD_EMPRESA', 'inner');
            $this->db->join('REGIONAL R', 'R.COD_REGIONAL = E.COD_REGIONAL', 'inner');
            $this->db->join('ESTADOS S', 'S.IDESTADO = E.ACTIVO', 'left');
            $this->db->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = E.COD_TIPOEMPRESA', 'left');
            $this->db->join('TIPOENTIDAD A', 'A.CODTIPOENTIDAD = E.COD_TIPOENTIDAD', 'left');
            $this->db->where("EMPRESASEVASORAS.COD_EMPRESA NOT IN (SELECT NIT_EMPRESA FROM ASIGNACIONFISCALIZACION)");
            $this->db->where('EMPRESASEVASORAS.FISCALIZADA', null);
            //$this->db->group_by('E.RAZON_SOCIAL');
        } else {

            if ($this->ion_auth->in_menu('asignarfiscalizacion/asignarevasoras')) {

                $this->db->distinct();
                $this->db->select("EMPRESASEVASORAS.COD_EMPRESA,
                                                            E.RAZON_SOCIAL,
                                                            E.TELEFONO_FIJO,
                                                            E.DIRECCION,
                                                            R.NOMBRE_REGIONAL,
                                                            T.NOM_TIPO_EMP,
                                                            E.CIIU,
                                                            E.NUM_EMPLEADOS,
                                                            A.NOM_TIPO_ENT,
                                                            S.NOMBREESTADO");

                $this->db->from('EMPRESASEVASORAS');
                $this->db->join('EMPRESA E', 'E.CODEMPRESA = EMPRESASEVASORAS.COD_EMPRESA', 'inner');
                $this->db->join('REGIONAL R', 'R.COD_REGIONAL = E.COD_REGIONAL', 'inner');
                $this->db->join('ESTADOS S', 'S.IDESTADO = E.ACTIVO', 'left');
                $this->db->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = E.COD_TIPOEMPRESA', 'left');
                $this->db->join('TIPOENTIDAD A', 'A.CODTIPOENTIDAD = E.COD_TIPOENTIDAD', 'left');
                $this->db->where("EMPRESASEVASORAS.COD_EMPRESA NOT IN (SELECT NIT_EMPRESA FROM ASIGNACIONFISCALIZACION)");
                $this->db->where('EMPRESASEVASORAS.FISCALIZADA', null);
                $this->db->where('E.COD_REGIONAL', $this->session->userdata('regional'));
            } else {

                $this->db->distinct();
                $this->db->select("EMPRESASEVASORAS.COD_EMPRESA,
                                                            E.RAZON_SOCIAL,
                                                            E.TELEFONO_FIJO,
                                                            E.DIRECCION,
                                                            R.NOMBRE_REGIONAL,
                                                            T.NOM_TIPO_EMP,
                                                            E.CIIU,
                                                            E.NUM_EMPLEADOS,
                                                            A.NOM_TIPO_ENT,
                                                            S.NOMBREESTADO");

                $this->db->from('EMPRESASEVASORAS');
                $this->db->join('EMPRESA E', 'E.CODEMPRESA = EMPRESASEVASORAS.COD_EMPRESA', 'inner');
                $this->db->join('REGIONAL R', 'R.COD_REGIONAL = E.COD_REGIONAL', 'inner');
                $this->db->join('ESTADOS S', 'S.IDESTADO = E.ACTIVO', 'left');
                $this->db->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = E.COD_TIPOEMPRESA', 'left');
                $this->db->join('TIPOENTIDAD A', 'A.CODTIPOENTIDAD = E.COD_TIPOENTIDAD', 'left');
                $this->db->where("EMPRESASEVASORAS.COD_EMPRESA NOT IN (SELECT NIT_EMPRESA FROM ASIGNACIONFISCALIZACION)");
                $this->db->where('EMPRESASEVASORAS.FISCALIZADA', null);
                $this->db->where('E.COD_REGIONAL', $this->session->userdata('regional'));
            }
        }

        return $this->db->get();
    }

    function fiscalizadores() {
        if ($this->ion_auth->is_admin() || $this->session->userdata('regional') == 999) {




            $this->db->select('USUARIOS.IDUSUARIO,
                                        USUARIOS.NOMBRES,
                                        USUARIOS.APELLIDOS,
                                        USUARIOS.DIRECCION,
                                        USUARIOS.TELEFONO,
                                        USUARIOS.CELULAR,
                                        USUARIOS.EMAIL,
                                        USUARIOS.CORREO_PERSONAL,
                                        R.NOMBRE_REGIONAL,
                                        USUARIOS.ACTIVO');
            $this->db->from('USUARIOS');
            $this->db->join('REGIONAL R', 'R.COD_REGIONAL = USUARIOS.COD_REGIONAL', ' inner ');
            //$this->db->join('ESTADOS E','E.IDESTADO = USUARIOS.ACTIVO', ' inner ');
            $this->db->where('FISCALIZADOR', 'S');
        } else {

            if ($this->ion_auth->in_menu('consultarusuariofiscalizador/edit')) {



                $this->db->select('USUARIOS.IDUSUARIO,
                                                                USUARIOS.NOMBRES,
                                                                USUARIOS.APELLIDOS,
                                                                USUARIOS.DIRECCION,
                                                                USUARIOS.TELEFONO,
                                                                USUARIOS.CELULAR,
                                                                USUARIOS.EMAIL,
                                                                USUARIOS.CORREO_PERSONAL,
                                                                R.NOMBRE_REGIONAL,
                                                                USUARIOS.ACTIVO');
                $this->db->from('USUARIOS');
                $this->db->join('REGIONAL R', 'R.COD_REGIONAL = USUARIOS.COD_REGIONAL', ' inner ');
                //$this->db->join('ESTADOS E','E.IDESTADO = USUARIOS.ACTIVO', ' inner ');
                $this->db->where('FISCALIZADOR', 'S');
                $this->db->where('USUARIOS.COD_REGIONAL', $this->session->userdata('regional'));
            } else {



                $this->db->select('USUARIOS.IDUSUARIO,
                                                                USUARIOS.NOMBRES,
                                                                USUARIOS.APELLIDOS,
                                                                USUARIOS.DIRECCION,
                                                                USUARIOS.TELEFONO,
                                                                USUARIOS.CELULAR,
                                                                USUARIOS.EMAIL,
                                                                USUARIOS.CORREO_PERSONAL,
                                                                R.NOMBRE_REGIONAL,
                                                                USUARIOS.ACTIVO');
                $this->db->from('USUARIOS');
                $this->db->join('REGIONAL R', 'R.COD_REGIONAL = USUARIOS.COD_REGIONAL', ' inner ');
                //$this->db->join('ESTADOS E','E.IDESTADO = USUARIOS.ACTIVO', ' inner ');
                $this->db->where('FISCALIZADOR', 'S');
                $this->db->where('USUARIOS.COD_REGIONAL', $this->session->userdata('regional'));
            }
        }
        return $this->db->get();
    }

    function conempresasProCobro() {
        if ($this->ion_auth->is_admin() || $this->session->userdata('regional') == 999) {

            $this->db->select("EMPRESASEVASORAS.COD_EMPRESA,
                                                            E.RAZON_SOCIAL,
                                                            E.TELEFONO_FIJO,
                                                            E.DIRECCION,
                                                            M.NOMBREMUNICIPIO,
                                                            R.NOMBRE_REGIONAL,
                                                            T.NOM_TIPO_EMP,
                                                            E.CIIU,
                                                            E.NUM_EMPLEADOS,
                                                            S.NOMBREESTADO,
                                                            (U.NOMBRES ||' '|| U.APELLIDOS) AS FISCALIZADOR");

            $this->db->from('EMPRESASEVASORAS');
            $this->db->join('EMPRESA E', 'E.CODEMPRESA = EMPRESASEVASORAS.COD_EMPRESA', 'inner');
            $this->db->join('REGIONAL R', 'R.COD_REGIONAL = E.COD_REGIONAL', 'inner');
            $this->db->join('ESTADOS S', 'S.IDESTADO = E.ACTIVO', 'left');
            $this->db->join('MUNICIPIO M', 'M.CODMUNICIPIO = E.COD_MUNICIPIO AND M.COD_DEPARTAMENTO = E.COD_REGIONAL ', 'inner');
            $this->db->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = E.COD_TIPOEMPRESA', 'left');
            $this->db->join('ASIGNACIONFISCALIZACION A', 'A.NIT_EMPRESA = E.CODEMPRESA', 'left');
            $this->db->join('USUARIOS U', 'U.IDUSUARIO = A.ASIGNADO_A', 'inner');
        } else {

            if ($this->ion_auth->in_menu('conempresasprocobro/reasignar')) {


                $this->db->select("EMPRESASEVASORAS.COD_EMPRESA,
                                                                E.RAZON_SOCIAL,
                                                                E.TELEFONO_FIJO,
                                                                E.DIRECCION,
                                                                M.NOMBREMUNICIPIO,
                                                                R.NOMBRE_REGIONAL,
                                                                T.NOM_TIPO_EMP,
                                                                E.CIIU,
                                                                E.NUM_EMPLEADOS,
                                                                S.NOMBREESTADO,
                                                                (U.NOMBRES ||' '|| U.APELLIDOS) AS FISCALIZADOR");

                $this->db->from('EMPRESASEVASORAS');
                $this->db->join('EMPRESA E', 'E.CODEMPRESA = EMPRESASEVASORAS.COD_EMPRESA', 'inner');
                $this->db->join('REGIONAL R', 'R.COD_REGIONAL = E.COD_REGIONAL', 'inner');
                $this->db->join('ESTADOS S', 'S.IDESTADO = E.ACTIVO', 'left');
                $this->db->join('MUNICIPIO M', 'M.CODMUNICIPIO = E.COD_MUNICIPIO AND M.COD_DEPARTAMENTO = E.COD_REGIONAL ', 'inner');
                $this->db->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = E.COD_TIPOEMPRESA', 'left');
                $this->db->join('ASIGNACIONFISCALIZACION A', 'A.NIT_EMPRESA = E.CODEMPRESA', 'left');
                $this->db->join('USUARIOS U', 'U.IDUSUARIO = A.ASIGNADO_A', 'inner');
                $this->db->where('E.COD_REGIONAL', $this->session->userdata('regional'));
            } else {


                $this->db->select("EMPRESASEVASORAS.COD_EMPRESA,
                                                        E.RAZON_SOCIAL,
                                                        E.TELEFONO_FIJO,
                                                        E.DIRECCION,
                                                        R.NOMBRE_REGIONAL,
                                                        T.NOM_TIPO_EMP,
                                                        E.CIIU,
                                                        E.NUM_EMPLEADOS,
                                                        S.NOMBREESTADO,
                                                        (U.NOMBRES ||' '|| U.APELLIDOS) AS FISCALIZADOR");

                $this->db->from('EMPRESASEVASORAS');
                $this->db->join('EMPRESA E', 'E.CODEMPRESA = EMPRESASEVASORAS.COD_EMPRESA', 'inner');
                $this->db->join('REGIONAL R', 'R.COD_REGIONAL = E.COD_REGIONAL', 'inner');
                $this->db->join('ESTADOS S', 'S.IDESTADO = E.ACTIVO', 'left');
                $this->db->join('MUNICIPIO M', 'M.CODMUNICIPIO = E.COD_MUNICIPIO AND M.COD_DEPARTAMENTO = E.COD_REGIONAL ', 'inner');
                $this->db->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = E.COD_TIPOEMPRESA', 'left');
                $this->db->join('ASIGNACIONFISCALIZACION A', 'A.NIT_EMPRESA = E.CODEMPRESA', 'left');
                $this->db->join('USUARIOS U', 'U.IDUSUARIO = A.ASIGNADO_A', 'inner');
                $this->db->where('E.COD_REGIONAL', $this->session->userdata('regional'));
            }
        }
        return $this->db->get();
    }

    function empresasNuevas() {
        if ($this->ion_auth->is_admin() || $this->session->userdata('regional') == 999) {




            $this->db->select('EMPRESA.CODEMPRESA,
                                                    EMPRESA.RAZON_SOCIAL,
                                                    EMPRESA.TELEFONO_FIJO,
                                                    EMPRESA.DIRECCION,
                                                    M.NOMBREMUNICIPIO,
                                                    R.NOMBRE_REGIONAL,
                                                    T.NOM_TIPO_EMP,
                                                    EMPRESA.CIIU,
                                                    EMPRESA.NUM_EMPLEADOS');
            //A.NOM_TIPO_ENT,
            //E.NOMBREESTADO');
            $this->db->from('EMPRESA');
            $this->db->join('REGIONAL R', 'R.COD_REGIONAL = EMPRESA.COD_REGIONAL', 'inner');
            //$this->db->join('ESTADOS E','E.IDESTADO = EMPRESA.ACTIVO', 'left');
            $this->db->join('MUNICIPIO M', 'M.CODMUNICIPIO = EMPRESA.COD_MUNICIPIO AND M.COD_DEPARTAMENTO = EMPRESA.COD_REGIONAL ', 'inner');
            $this->db->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = EMPRESA.COD_TIPOEMPRESA', 'left');
            //$this->db->join('TIPOENTIDAD A','A.CODTIPOENTIDAD = EMPRESA.COD_TIPOENTIDAD', 'left');
        } else {

            if ($this->ion_auth->in_menu('consultarinfoempresas/manage')) {


                $this->db->select('EMPRESA.CODEMPRESA,
                                                        EMPRESA.RAZON_SOCIAL,
                                                        EMPRESA.TELEFONO_FIJO,
                                                        EMPRESA.DIRECCION,
                                                        M.NOMBREMUNICIPIO,
                                                        R.NOMBRE_REGIONAL,
                                                        T.NOM_TIPO_EMP,
                                                        EMPRESA.CIIU,
                                                        EMPRESA.NUM_EMPLEADOS');
                //A.NOM_TIPO_ENT,
                //E.NOMBREESTADO');
                $this->db->from('EMPRESA');
                $this->db->join('REGIONAL R', 'R.COD_REGIONAL = EMPRESA.COD_REGIONAL', 'inner');
                //$this->db->join('ESTADOS E','E.IDESTADO = EMPRESA.ACTIVO', 'left');
                $this->db->join('MUNICIPIO M', 'M.CODMUNICIPIO = EMPRESA.COD_MUNICIPIO AND M.COD_DEPARTAMENTO = EMPRESA.COD_REGIONAL ', 'inner');
                $this->db->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = EMPRESA.COD_TIPOEMPRESA', 'left');
                //$this->db->join('TIPOENTIDAD A','A.CODTIPOENTIDAD = EMPRESA.COD_TIPOENTIDAD', 'left');
                $this->db->where('EMPRESA.COD_REGIONAL', $this->session->userdata('regional'));
            }
        }

        return $this->db->get();
    }

}
