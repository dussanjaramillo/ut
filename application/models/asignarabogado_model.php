<?php

class Asignarabogado_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function datatable_titulos($estado) {


        $query = $this->db->query("SELECT T.COD_TITULO, T.COD_REGIONAL, R.NOMBRE_REGIONAL, T.NUM_ESCRITURA, T.NOTARIA, M.NOMBREMUNICIPIO, T.COD_PROPIETARIO, T.NOMBRE_PROPIETARIO, E.NOMBRE_ESTADOTITULO, GT.FECHA_GESTION 
                                    FROM TITULOSJUDICIALES T, MUNICIPIO M, DEPARTAMENTO D, GESTIONTITULOS GT, ESTADOTITULO E, REGIONAL R 
                                    WHERE R.COD_REGIONAL = T.COD_REGIONAL 
                                    AND E.COD_ESTADOTITULO = T.COD_ESTADOTITULO 
                                    AND M.CODMUNICIPIO = T.COD_CIUDAD 
                                    AND GT.COD_TITULO = T.COD_TITULO 
                                    AND D.COD_DEPARTAMENTO = T.COD_DEPARTAMENTO 
                                    AND M.COD_DEPARTAMENTO = D.COD_DEPARTAMENTO
                                    AND E.COD_ESTADOTITULO = '$estado'");
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    function abogados_judiciales($id_abogados) {
        $query = $this->db->query('SELECT U.IDUSUARIO,U.NOMBRES,U.APELLIDOS FROM USUARIOS U,GRUPOS G,USUARIOS_GRUPOS UG WHERE U.IDUSUARIO = UG.IDUSUARIO AND UG.IDGRUPO = G.IDGRUPO AND UG.IDGRUPO = ' . $id_abogados);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

}
