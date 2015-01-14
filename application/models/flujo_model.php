<?php

//ruta prototiposena: application/models/flujo_model.php
//require_once 'sqlpadre.php';

class Flujo_model extends CI_model {

    function __construct() {
        parent::__construct();
    }

    /**
     * M?todo buscaRecordatorio
     * Verifica si la actividad tiene recordatorios asociados.
     *
     * (Trasladado a CodeIgniter el 28 I 2014)
     * @author Felipe R. Puerto :: Thomas MTI
     * @since 17 XII 2013
     *
     * @param Integer $codgestion C?d de la (GESTION)actividad para buscar sus recordatorios
     * @param String $tipo (27 XII 2013) Se agrega para diferenciar entre
     * pre (recordatorio) y pos (temporizador).
     * @return Array $matriz Repuesta de la consulta a la BD.
     */
    function buscaRecordatorio($codgestion, $tipo = 'pre', $opc = '') {
        if ($this->ion_auth->logged_in()) {
            $this->db->select("RECORDATORIO.CODRECORDATORIO");
            $this->db->select("RECORDATORIO.CODPLANTILLA");
            $this->db->select("TEXTO");
            $this->db->select("RECORDATORIO_CORREO");
            $this->db->select("RECORDATORIO_PANTALLA");
            $this->db->select("RECORDATORIO.ACTIVO");
            $this->db->select("TIEMPO_NUM");
            $this->db->select("TIEMPO_MEDIDA");
            $this->db->from('RECORDATORIO');
            $this->db->join('PLANTILLA', 'RECORDATORIO.CODPLANTILLA = PLANTILLA.CODPLANTILLA', 'left');
            $this->db->where("CODACTIVIDAD", $codgestion);
            $this->db->where("RECORDATORIO.ACTIVO", 'S');
            $this->db->where("TIPO_RECORDATORIO", $tipo);  //Ahora es posible con recordatorio 'pre' y bloqueos 'pos'
            if (!empty($opc)) {
                $this->db->where("OPCION", $opc);
            }
            $dato = $this->db->get();  //echo "<br>".__FILE__." > ".__LINE__.": ".$this->db->last_query();
            return $dato->result_array;
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * M?todo borraDisparaFecha
     * Borra los datos de DisparaFecha y DisparaUsuario seg?n un c?d.
     *
     * @author Felipe R. Puerto :: Thomas MTI
     * @since 7 II 2014
     *
     * @param Integer $coddisparafecha C?d. que se va a borrar
     */
    function inactivaDisparaFecha($coddisparafecha, $correo) {//$caso = 301; //$codrecordatorio = 28;
        if ($this->ion_auth->logged_in()) {
            $dat = array('ACTIVO' => 'N', 'USUARIO' => $correo);
            $this->db->where('CODDISPARAFECHA', $coddisparafecha);
            $this->db->update('DISPARAFECHA', $dat);       //$sql = " UPDATE DISPARAFECHA set ACTIVO = 'N', USUARIO = '$correo' WHERE CODDISPARAFECHA = $coddisparafecha ";
            $this->db->delete('DISPARAUSUARIO', array('COD_DISPARA_FECHA' => $coddisparafecha)); //$sql = " DELETE FROM DISPARAUSUARIO WHERE COD_DISPARA_FECHA = $coddisparafecha ";//$this->db->query($sql);//echo __LINE__" :".$this->db->last_query();
            return 'LISTO';
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * M?todo poneDisparaFecha
     * Si no hay datos para disparar un recordatorio los inserta
     *
     * (Trasladado a CodeIgniter el 28 I 2014)
     * @author Felipe R. Puerto :: Thomas MTI
     * @since 17 XII 2013
     *
     * @param Integer $caso C?d. del caso que se est? trabajando.
     * @param Integer $codrecordatorio C?d. del recordatorio que se va a enviar
     * @param date $fechaCreado Fecha de creaci?n del recordatorio (Fecha Actual)
     * @param date $fechaVencimiento Fecha de vencimiento del recordatorio (para lanzarlo)

     */
    function poneDisparaFecha($caso, $codjudicial, $codcoactivo, $coddevolucion, $codrecepcion, $codnomisional, $codrecordatorio, $fechaCreado, $fechaVencimiento) {//$caso = 301; //$codrecordatorio = 28;
        if ($this->ion_auth->logged_in()) {
            $this->db->select("CODRECORDATORIO");
            $this->db->select("CODDISPARAFECHA");
            $this->db->select("to_char(FECHA_CREACION,'yyyy-mm-dd') FECHA_CREACION ", FALSE);
            $this->db->select("to_char(FECHA_VENCIMIENTO,'yyyy-mm-dd') FECHA_VENCIMIENTO ", FALSE);
            $this->db->where("ACTIVO", 'S');
            $this->db->where("CODRECORDATORIO", $codrecordatorio);
            $this->db->where("(CASO", $caso);
            $this->db->or_where("COD_JUDICIAL", $codjudicial);
            $this->db->or_where("COD_PROCESO_COACTIVO", $codcoactivo);
            $this->db->or_where("COD_DEVOLUCION", $coddevolucion);
            $this->db->or_where("COD_RECEPCIONTITULO", $codrecepcion);
            $this->db->or_where("COD_CARTERA_NOMIISIONAL = $codnomisional ) AND 1=", 1, FALSE);
            $dato = $this->db->get("DISPARAFECHA");

            $row = $dato->row_array();
            if ($dato->num_rows() > 0) {
                $id = $row['CODDISPARAFECHA'];
                $fechaCreado = $row['FECHA_CREACION'];
                $fechaVencimiento = $row['FECHA_VENCIMIENTO'];
            }
            if ($dato->num_rows() == 0) {
                $fcreado = "to_date('$fechaCreado', 'yyyy-mm-dd')";
                $fvence = "to_date('$fechaVencimiento', 'yyyy-mm-dd')";
                $this->db->set('CASO', $caso);
                $this->db->set('COD_JUDICIAL', $codjudicial);
                $this->db->set('COD_PROCESO_COACTIVO', $codcoactivo);
                $this->db->set('COD_DEVOLUCION', $coddevolucion);
                $this->db->set('COD_RECEPCIONTITULO', $codrecepcion);
                $this->db->set('COD_CARTERA_NOMIISIONAL', $codnomisional);
                $this->db->set('CODRECORDATORIO', $codrecordatorio);
                $this->db->set('FECHA_CREACION', $fcreado, FALSE);
                $this->db->set('FECHA_VENCIMIENTO', $fvence, FALSE);
                $this->db->set('ACTIVO', 'S');
                $this->db->set('USUARIO', $this->ion_auth->user()->row()->EMAIL);
                $this->db->insert('DISPARAFECHA');

                /* Se obtiene el id reci?n insertado */
                $this->db->select('CODDISPARAFECHA AS CDF');
                $this->db->where('CODRECORDATORIO', $codrecordatorio);
                $this->db->where('FECHA_CREACION', $fcreado, FALSE);
                $this->db->where('FECHA_VENCIMIENTO', $fvence, FALSE);
                $this->db->where('ACTIVO', 'S');
                $this->db->where('(CASO', $caso);
                $this->db->or_where("COD_JUDICIAL", $codjudicial);
                $this->db->or_where("COD_PROCESO_COACTIVO", $codcoactivo);
                $this->db->or_where("COD_DEVOLUCION", $coddevolucion);
                $this->db->or_where("COD_RECEPCIONTITULO", $codrecepcion);
                $this->db->or_where("COD_CARTERA_NOMIISIONAL = $codnomisional ) AND 1=", 1, FALSE);
                $dato = $this->db->get('DISPARAFECHA');            //$this->db->select_max('CODDISPARAFECHA', 'CDF');
//            $dato = $this->db->get('DISPARAFECHA')
                $row = $dato->row_array();
                $this->poneDisparaUsuario($row['CDF']); //}
                $id = $row['CDF'];
            }
            $datos['id'] = $id; //echo " fc:".date("Y-m-d",strtotime($fechaCreado));//echo " fv:".date("Y-m-d",strtotime($fechaVencimiento));
            $datos['fechaCreado'] = $fechaCreado;
            $datos['fechaVencimiento'] = $fechaVencimiento;
            return $datos;
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * M?todo yaHayPoneRecordatorio
     * Verifica si ya hay un disparador de recordatorio para el código de gestión
     *
     * @author Felipe R. Puerto :: Thomas MTI
     * @since 1 IV 2014
     *
     * @param Integer $codgestioncobro C?d. de la gesti?n del cobro.
     * @return $array $matriz Arreglo de resultados de los disparadores de recordatorio.
     */
    function yaHayPoneRecordatorio($codrecordatorio, $codgestioncobro) {
        if ($this->ion_auth->logged_in()) {
            $sql = "SELECT CODRECORDATORIO, CODDISPARARECORDATORIO, to_char(FECHA_CREACION, 'yyyy-mm-dd') FECHA_CREACION, to_char(FECHA_VENCIMIENTO, 'yyyy-mm-dd') FECHA_VENCIMIENTO FROM DISPARARECORDATORIO WHERE  CODRECORDATORIO = '$codrecordatorio' AND ACTIVO = 'S' AND COD_GESTION_COBRO IN ( SELECT COD_GESTION_COBRO FROM GESTIONCOBRO WHERE COD_FISCALIZACION_EMPRESA = (SELECT COD_FISCALIZACION_EMPRESA FROM GESTIONCOBRO WHERE COD_GESTION_COBRO = $codgestioncobro))";
            $dato = $this->db->query($sql); //echo " ".__LINE__.$sql;
            $matriz = $dato->result_array;
            return $matriz;
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    //obtiene lainformacion en caso de bloqueo en la gestion que le envien como ppaRAMET5RO
    function informacion_bloqueo($gestion, $codrecordatorio) {
//SELECT RECORDATORIO.CODACTIVIDAD, TIPOGESTION.TIPOGESTION FROM RECORDATORIO
//JOIN TIPOGESTION ON COD_GESTION = 33 WHERE CODRECORDATORIO = 84
        $this->db->select('RECORDATORIO.CODACTIVIDAD, TIPOGESTION.TIPOGESTION');
        $query = $this->db->join('TIPOGESTION', 'TIPOGESTION.COD_GESTION = RECORDATORIO.CODACTIVIDAD');
        $query = $this->db->where('RECORDATORIO.CODRECORDATORIO', $codrecordatorio);
        $query = $this->db->where('TIPOGESTION.COD_GESTION', $gestion);
        $query = $this->db->get('RECORDATORIO');
        return $query->result_array();
        // return $datos="";
    }

    /**
     * M?todo poneDisparaRecordatorio
     * Basado en poneDisparaFecha
     *
     * (Trasladado a CodeIgniter el 28 I 2014)
     * @author Felipe R. Puerto :: Thomas MTI
     * @since 20 II 2014
     *
     * @param Integer $codgestioncobro C?d. de la gesti?n del cobro
     * @param Integer $codrecordatorio C?d. del recordatorio que se va a enviar
     * @param date $fechaCreado Fecha de creaci?n del recordatorio (Fecha Actual)
     * @param date $fechaVencimiento Fecha de vencimiento del recordatorio (para lanzarlo)
     */
    function poneDisparaRecordatorio($codgestioncobro, $codrecordatorio, $fechaCreado, $fechaVencimiento, $usuariosAdicionales = '') {//$caso = 301; //$codrecordatorio = 28;       //echo " poneDisparaRecordatorio";
        if ($this->ion_auth->logged_in()) {
            $fcreado = "to_date('$fechaCreado', 'yyyy-mm-dd')";
            $fvence = "to_date('$fechaVencimiento', 'yyyy-mm-dd')";
            $this->db->set('COD_GESTION_COBRO', $codgestioncobro);
            $this->db->set('CODRECORDATORIO', $codrecordatorio);
            $this->db->set('FECHA_CREACION', $fcreado, FALSE);
            $this->db->set('FECHA_VENCIMIENTO', $fvence, FALSE);
            $this->db->set('ACTIVO', 'S');
            $this->db->set('USUARIO', $this->ion_auth->user()->row()->IDUSUARIO);
            $this->db->insert('DISPARARECORDATORIO'); //echo "<br>".__FILE__." ".__LINE__.":".$this->db->last_query();
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /* $this->db->select('CODDISPARARECORDATORIO AS CDR');$this->db->where("COD_GESTION_COBRO",$codgestioncobro);$this->db->where('CODRECORDATORIO',$codrecordatorio);$this->db->where('FECHA_CREACION',$fcreado,FALSE);$this->db->where('FECHA_VENCIMIENTO',$fvence,FALSE);$this->db->where('ACTIVO','S');$this->db->where('USUARIO',$this->ion_auth->user()->row()->IDUSUARIO);$dato = $this->db->get('DISPARARECORDATORIO'); */

    function traeDisparaRecordatorio($codgestioncobro, $codrecordatorio, $fechaCreado, $fechaVencimiento) {
        if ($this->ion_auth->logged_in()) {
            $fcreado = "to_date('$fechaCreado', 'yyyy-mm-dd')";
            $fvence = "to_date('$fechaVencimiento', 'yyyy-mm-dd')";

            $usuario = $this->ion_auth->user()->row()->IDUSUARIO; //echo " L:".__LINE__." U:".$usuario;
            $this->db->select('CODDISPARARECORDATORIO AS CDR');
            $this->db->where('COD_GESTION_COBRO', $codgestioncobro);
            $this->db->where('CODRECORDATORIO', $codrecordatorio);
            $this->db->where('FECHA_CREACION', $fcreado, FALSE);
            $this->db->where('FECHA_VENCIMIENTO', $fvence, FALSE);
            $this->db->where('ACTIVO', 'S');
            $this->db->where('USUARIO', $usuario); // echo " L:".__LINE__;
            $dato = $this->db->get('DISPARARECORDATORIO');    //$this->db->select_max('CODDISPARARECORDATORIO','CDR'); //$dato = $this->db->get('DISPARARECORDATORIO');            ////echo "<br>".__FILE__." ".__LINE__.":".$this->db->last_query();
            $row = $dato->row_array();
            $id = $row['CDR'];
            return $id;
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function sdfa() {
        if ($this->ion_auth->logged_in()) {
            $this->poneDisparaRecUsuario($row['CDR'], $arrPar, $usuariosAdicionales);    //}
            $id = $row['CDR'];
            $datos['id'] = $id;
            $datos['fechaCreado'] = $fechaCreado;
            $datos['fechaVencimiento'] = $fechaVencimiento;
            return $datos;
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /* PROCEDIMIENTO USP_INSERTENCABEZADO:
     * **************************
      " CREATE OR REPLACE PROCEDURE 13USP_InsertEncabezado
      AS v_CurrVal NUMBER;
      BEGIN
      INSERT INTO encabezado (comentarios,fecha) VALUES ('ENCABEZAD1O',sysdate);
      SELECT Encabezado_cod_encabezado_SEQ.currval INTO v_CurrVal FROM dual;
      IF v_CurrVal >0  THEN
      INSERT INTO detalle (cod_encabezado,detalles,fecha) VALUES (v_CurrVal,'detalle10',sysdate);
      END IF;
      END USP_InsertEncabezado; ";
     */

    /**
     * M?todo inactivaDisparaRecordatorio
     * R?plica de inactivaDisparaFecha
     * Borra los datos de DisparaRecordatorio y DisparaUsuario seg?n un c?d de gesti?n.
     *
     * @author Felipe R. Puerto :: Thomas MTI
     * @since 20 II 2014
     *
     * @param Integer $coddisparafecha C?d. que se va a borrar
     */
    function inactivaDisparaRecordatorio($codgestioncobro) {//$caso = 301; //$codrecordatorio = 28;
        if ($this->ion_auth->logged_in()) {
            $this->db->select("CODDISPARARECORDATORIO");
            $this->db->where("COD_GESTION_COBRO", $codgestioncobro);
            $this->db->where("ACTIVO", 'S');
            $dato = $this->db->get("DISPARARECORDATORIO");
            //echo "<br>".__FILE__." ".__LINE__.":".$this->db->last_query();
            $row = $dato->row_array();
            if (!empty($row['CODDISPARARECORDATORIO'])) {
                $coddispararecordatorio = $row['CODDISPARARECORDATORIO'];
                $dat = array('ACTIVO' => 'N', 'USUARIO' => $this->ion_auth->user()->row()->EMAIL);
                $this->db->where('COD_GESTION_COBRO', $codgestioncobro);
                $this->db->update('DISPARARECORDATORIO', $dat);     //echo "<br>".__FILE__." ".__LINE__.":".$this->db->last_query();  //$sql = " UPDATE DISPARAFECHA set ACTIVO = 'N', USUARIO = '$correo' WHERE CODDISPARAFECHA = $coddisparafecha ";
                $this->db->delete('DISPARARECUSUARIO', array('COD_DISPARA_REC' => $coddispararecordatorio));  //echo "<br>".__FILE__." ".__LINE__.":".$this->db->last_query();//$sql = " DELETE FROM DISPARAUSUARIO WHERE COD_DISPARA_FECHA = $coddisparafecha ";//$this->db->query($sql);
                return true;
            } else {
                return false;       //echo "no hay para eliminar";
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
        ///return 'LISTO';       //echo " 98:".$this->db->last_query();
    }

    /**

     * M?todo inactivaDispararMenos
     * Inactiva los recordatorios de la misma fiscalizaci?n pero con c?digos de gestion inferiores
     *
     * @author Felipe R. Puerto :: Thomas MTI
     * @since 20 II 2014
     *
     * @param Integer $coddisparafecha C?d. que se va a borrar
     */
    function inactivaDispararMenos($codgestioncobro) {
        if ($this->ion_auth->logged_in()) {
            $sql = " UPDATE dispararecordatorio SET ACTIVO = 'N', USUARIO = '" . $this->ion_auth->user()->row()->EMAIL . "'
        WHERE ACTIVO = 'S'
        AND COD_GESTION_COBRO
        IN ( SELECT COD_GESTION_COBRO
        FROM gestioncobro
        WHERE COD_FISCALIZACION_EMPRESA = ( SELECT COD_FISCALIZACION_EMPRESA FROM GESTIONCOBRO
        WHERE COD_GESTION_COBRO = $codgestioncobro ) AND COD_GESTION_COBRO < $codgestioncobro )";
            $dato = $this->db->query($sql);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Método noDispararRecordatorio
     * Pone ACTIVO = 'N' en los dispararecordatorios de una fiscalización
     *
     * @author Felipe R. Puerto :: Thomas MTI
     * @since 25 III 2014
     *
     * @param Integer $coddisparafecha C?d. que se va a borrar
     */
    function noDispararRecordatorios($codfiscalizacion, $tipogestion, $tiporespuesta = '') {
        if ($this->ion_auth->logged_in()) {
            $sqlres = "";
            if (!empty($tiporespuesta)) {
                //$sqlres = " AND COD_TIPO_RESPUESTA <> $tiporespuesta ";
            }
            $sql = "
        UPDATE DISPARARECORDATORIO SET ACTIVO = 'N' WHERE CODDISPARARECORDATORIO IN(
            SELECT CODDISPARARECORDATORIO FROM DISPARARECORDATORIO WHERE ACTIVO = 'S' AND
            COD_GESTION_COBRO IN(
                SELECT COD_GESTION_COBRO FROM GESTIONCOBRO WHERE COD_FISCALIZACION_EMPRESA = $codfiscalizacion AND cod_tipogestion<> $tipogestion $sqlres
            )
        )";
            $dato = $this->db->query($sql);
            return true;
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * M?todo poneDisparaUsuario
     * Inserta los usuarios, correos y texto a los que se debe enviar un recordatorio.
     *
     * (Trasladado a CodeIgniter el 28 I 2014)
     * @author Felipe R. Puerto :: Thomas MTI
     * @since 18 XII 2013
     *
     * @param Integer $coddisparafecha C?digo que relaciona los usuarios con
     * la tabla que activa los disparadores (disparafecha)

     */
    function poneDisparaUsuario($coddisparafecha) {
        if ($this->ion_auth->logged_in()) {
            $sql = "INSERT INTO disparausuario (cod_dispara_fecha,cod_usuario,correo_usuario,recordatorio_correo,texto_plantilla)
        SELECT coddisparafecha, usuarios.idusuario,usuarios.email,recordatorio_correo,texto FROM usuariosrecordatorio
        INNER JOIN usuarios ON CAST (codrecordado AS integer) = idusuario
        INNER JOIN recordatorio ON recordatorio.codrecordatorio = usuariosrecordatorio.codrecordatorio
        INNER JOIN plantilla ON plantilla.codplantilla = recordatorio.codplantilla
        INNER JOIN disparafecha ON disparafecha.codrecordatorio = recordatorio.codrecordatorio
        WHERE coddisparafecha = $coddisparafecha ";
            $dato = $this->db->query($sql);  //echo "<br>".__FILE__." ".__LINE__.":".$this->db->last_query();
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Función traeFiscalizacion
     * Trea el cód de fiscalización según el cod de gestión cobro.
     *
     * @return type
     */
    function traeFiscalizacion($arrPar) {
        if ($this->ion_auth->logged_in()) {
            //Obtener el c?digo de fiscalizaci?n a partir del c?d de gesti?n.
            $this->db->select('COD_FISCALIZACION_EMPRESA');
            $this->db->where('COD_GESTION_COBRO', $arrPar['CODGESTIONCOBRO']);
            $dato = $this->db->get('GESTIONCOBRO');
            $fila = $dato->row_array();
            return $fila['COD_FISCALIZACION_EMPRESA'];
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Función traeRegional
     *
     * Traer el código de la regional a partir de la fiscalización.
     *
     * @param Integer $fiscalizacion: Código de fiscalización
     * @return Integer $codregional: Código de la regional
     */
    function traeRegional($fiscalizacion) {
        if ($this->ion_auth->logged_in()) {
            $this->db->select('COD_REGIONAL');
            $this->db->where('COD_FISCALIZACION_EMPRESA', $fiscalizacion);
            $this->db->where('NIT_EMPRESA IS NOT NULL');
            $this->db->join('GESTIONCOBRO', 'GESTIONCOBRO.NIT_EMPRESA = EMPRESA.CODEMPRESA ');
            $dato = $this->db->get('EMPRESA');
            $fila = $dato->row_array();
            return $fila['COD_REGIONAL'];
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Función traeAbogado
     *
     * Traer el cód del abogado asignado a la fiscalización.
     *
     * @param Integer $fiscalizacion: Código de fiscalización
     * @return Integer $codabogado: Cód del abogado asignado
     */
    function traeAbogado($fiscalizacion) {
        if ($this->ion_auth->logged_in()) {
            $this->db->select('COD_ABOGADO');
            $this->db->where('COD_FISCALIZACION', $fiscalizacion);
            $dato = $this->db->get('FISCALIZACION');
            $fila = $dato->row_array();
            return $fila['COD_ABOGADO'];
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Función traeAbogadoJudicial
     * Réplica de traeAbogado
     *
     * Traer el cód del abogado asignado al proceso judicial.
     *
     * @param Integer $fiscalizacion: Código de fiscalización
     * @return Integer $codabogado: Cód del abogado asignado
     */
    function traeAbogadoJudicial($titulo) {
        if ($this->ion_auth->logged_in()) {
            $this->db->select('COD_ABOGADO_ASIGNADO');
            $this->db->where('COD_TITULO', $titulo);
            $dato = $this->db->get('TITULOSJUDICIALES'); //echo " L:".__LINE__.$this->db->last_query();
            $fila = $dato->row_array();
            if (!empty($fila['COD_ABOGADO_ASIGNADO'])) {
                return $fila['COD_ABOGADO_ASIGNADO'];
            }
            return 0;
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /* Plantilla2:
      <h3>%APELLIDOS% %NOMBRES%:</h3>
      <p>Se le notifica que tiene pendiente por realizar la siguiente actividad:
      <br><b> <em>%NOMBREPROCESO%
      <br> >%TIPOGESTION%
      <br> >> %NOMBRERESPUESTA%</em></b>.
      <br>Creada el día: %FECHACREADO%.
      <br>Es necesario realizarla antes del <b>%FECHAVENCIMIENTO%</b>.<p>
      %URLGESTION%
      <br><sup>Cód gestión: %CODGESTIONCOBRO% </sup>
      <br><sup>Tiempo asignado: %TIEMPONUM% %TIEMPOMEDIDA%.</sup>
      <br><sup>cód.: %CODFISCALIZACION%.</sup>
     */

    /**
     * Función traeRecUsuarios
     *
     * Trae los usuarios (DEL ROL) y la regional a los cuales se les asignará el recordatorio
     *
     * @param type $coddispararecordatorio
     * @param type $usuariosAdicionales
     * @param type $regional
     * @return type
     */
    function traeRecUsuarios($coddispararecordatorio, $usuariosAdicionales = '', $regional = '') { /* ANTES: $sql = "SELECT DISTINCT CODDISPARARECORDATORIO, USUARIOS.IDUSUARIO, USUARIOS.EMAIL, RECORDATORIO_CORREO,TEXTO, NOMBREPROCESO, TIPOGESTION, NOMBRES, APELLIDOS, NOMBRE_GESTION, URLGESTION  FROM USUARIOSRECORDATORIO INNER JOIN usuarios ON CAST (codrecordado AS integer)   = idusuario INNER JOIN recordatorio ON recordatorio.codrecordatorio = usuariosrecordatorio.codrecordatorio INNER JOIN plantilla ON plantilla.codplantilla = recordatorio.codplantilla INNER JOIN dispararecordatorio ON dispararecordatorio.codrecordatorio = recordatorio.codrecordatorio INNER JOIN tipogestion ON tipogestion.cod_gestion = recordatorio.codactividad INNER JOIN proceso ON tipogestion.codproceso = proceso.codproceso INNER JOIN respuestagestion ON respuestagestion.cod_respuesta = recordatorio.opcion WHERE coddispararecordatorio = $coddispararecordatorio ";  */
        if ($this->ion_auth->logged_in()) {
            $sql = "SELECT DISTINCT CODDISPARARECORDATORIO, USUARIOS.IDUSUARIO, USUARIOS.EMAIL, RECORDATORIO_CORREO,TEXTO, NOMBREPROCESO, TIPOGESTION, NOMBRES, APELLIDOS, NOMBRE_GESTION, URLGESTION
        FROM USUARIOSRECORDATORIO
        INNER JOIN usuarios_grupos ON CAST (codrecordado AS integer)   = usuarios_grupos.idgrupo
        INNER JOIN usuarios ON usuarios.idusuario = usuarios_grupos.idusuario
        INNER JOIN recordatorio ON recordatorio.codrecordatorio = usuariosrecordatorio.codrecordatorio
        INNER JOIN plantilla ON plantilla.codplantilla = recordatorio.codplantilla
        INNER JOIN dispararecordatorio ON dispararecordatorio.codrecordatorio = recordatorio.codrecordatorio
        INNER JOIN tipogestion ON tipogestion.cod_gestion = recordatorio.codactividad
        INNER JOIN proceso ON tipogestion.codproceso = proceso.codproceso
        INNER JOIN respuestagestion ON respuestagestion.cod_respuesta = recordatorio.opcion
        WHERE coddispararecordatorio = $coddispararecordatorio ";
            if (!empty($regional)) {
                $sql .= " AND COD_REGIONAL = $regional";
            }

            if (!empty($usuariosAdicionales)) {
                //añadir comillas simples a cada número para poder hacer la consulta
                $usuariosString = explode(",", $usuariosAdicionales);
                for ($i = 0; $i < count($usuariosString); $i++) {
                    $usuariosString[$i] = "'" . $usuariosString[$i] . "'";
                }
                $usuariosAdicionales = implode(",", $usuariosString);
                $sql .= " UNION
            SELECT DISTINCT $coddispararecordatorio AS CODDISPARARECORDATORIO, USUARIOS.IDUSUARIO, USUARIOS.EMAIL, RECORDATORIO_CORREO, TEXTO, NOMBREPROCESO, TIPOGESTION, NOMBRES, APELLIDOS, NOMBRE_GESTION, URLGESTION
            FROM recordatorio  INNER JOIN dispararecordatorio on dispararecordatorio.codrecordatorio = recordatorio.codrecordatorio
            INNER JOIN plantilla ON plantilla.codplantilla = recordatorio.codplantilla
            INNER JOIN tipogestion ON tipogestion.cod_gestion = recordatorio.codactividad
            LEFT JOIN respuestagestion ON respuestagestion.cod_respuesta = recordatorio.opcion
            INNER JOIN proceso ON tipogestion.codproceso = proceso.codproceso
            INNER JOIN USUARIOS ON idusuario IN ($usuariosAdicionales)
            WHERE coddispararecordatorio = $coddispararecordatorio";
            }
            $dato = $this->db->query($sql);
            return $dato;
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * M?todo poneDisparaRecUsuario
     * R?plica de poneDisparaUsuario
     * Inserta los usuarios, correos y texto a los que se debe enviar un recordatorio.
     *
     * @author Felipe R. Puerto :: Thomas MTI
     * @since 21 II 2014
     *
     * @param Integer $coddisparafecha C?digo que relaciona los usuarios con
     * la tabla que activa los disparadores (disparafecha)
     */
    function poneDisparaRecUsuarios($fila, $texto) {
        if ($this->ion_auth->logged_in()) {
            $valores = array(
                "COD_DISPARA_REC" => $fila['CODDISPARARECORDATORIO'],
                "COD_USUARIO" => $fila['IDUSUARIO'],
                "CORREO_USUARIO" => $fila['EMAIL'],
                "RECORDATORIO_CORREO" => $fila['RECORDATORIO_CORREO'],
                "TEXTO_PLANTILLA" => "$texto"
            );
            $dato = $this->db->insert('DISPARARECUSUARIO', $valores); //echo "<br>".__FILE__." > ".__LINE__.": ".$this->db->last_query();
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funci?n tabla
     * Trae todos los datos de la tabla mencionada
     * @param String $nombreTabla nombre de la tabla a consultar
     * @return $matriz;
     */
    function tabla($nombreTabla, $order) {
        if ($this->ion_auth->logged_in()) {
            $sql = " SELECT * FROM $nombreTabla ";
            if ($order != "") {
                $sql .= " ORDER BY " . $order;
            }
            $dato = $this->db->query($sql);
            return $dato->result_array();
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * función trazaDuplicada
     * Trae la última fila de la traza según código de fiscalización
     * (Para luego revisar si la traza que se va a insertar es la misma que la última de esa fiscalización)
     *
     * @param Integer $codFiscalizacion Recibe un código de fiscalizacion para obtener su última traza
     * @return Array fila de datos
     */
    function trazaDuplicada($codFiscalizacion) {  //echo " ".__LINE__.
        if ($this->ion_auth->logged_in()) {
            $sql = "SELECT COD_GESTION_COBRO, COD_FISCALIZACION_EMPRESA, NIT_EMPRESA, COD_TIPO_RESPUESTA, COMENTARIOS, COD_TIPOGESTION, COD_USUARIO FROM GESTIONCOBRO WHERE COD_GESTION_COBRO = ( SELECT MAX(COD_GESTION_COBRO) FROM GESTIONCOBRO WHERE COD_FISCALIZACION_EMPRESA = $codFiscalizacion )";
            $dato = $this->db->query($sql);
            return $dato->row_array();
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * M?todo addTraza
     * R?plica de fiscalizacion_model->add
     *
     * @param String $table Tabla para insertar los datos
     * @param Array $data Arreglo de datos a ingresar en estilo llave=>valor
     * @param Array $date Arreglo que contiene la fecha actual.
     * @return boolean
     */
    function addTraza($table, $data, $date = '') {
        if ($this->ion_auth->logged_in()) {
            if ($date != '') {
                foreach ($data as $key => $value) {
                    $this->db->set($key, $value);
                }
                foreach ($date as $keyf => $valuef) {
                    $this->db->set($keyf, "to_date('" . $valuef . "','DD/MM/YYYY hh24:mi')", false);
                }
                $this->db->insert($table);
            } else {
                $this->db->insert($table, $data);
            }//echo "<br>".__FILE__." > ".__LINE__.": ".$this->db->last_query();
            if ($this->db->affected_rows() == '1') {
                return TRUE;
            }
            return FALSE;
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funci?n selectId
     * R?plica de fiscalizacion_model->selectId
     *
     * @param String $table "tabla a consultar"
     * @param Array $data Arreglo de datos a consultar
     * @param Array $date Arreglo de fecha
     * @return Array: fila de resultados de la consulta, en la llave ['COD_GESTION_COBRO'] viene el c?d de gesti?n
     */
    function selectId($table, $data, $date) {
        if ($this->ion_auth->logged_in()) {
            $this->db->select('COD_GESTION_COBRO');
            $this->db->where("COD_FISCALIZACION_EMPRESA", $data['COD_FISCALIZACION_EMPRESA']);
            $this->db->where("NIT_EMPRESA", $data['NIT_EMPRESA']);
            $this->db->where("FECHA_CONTACTO", 'to_date(\'' . $date['FECHA_CONTACTO'] . '\',\'dd-mm-yyyy hh24:mi\')', FALSE);
            $this->db->where("COMENTARIOS", $data['COMENTARIOS']);
            $this->db->where("COD_TIPOGESTION", $data['COD_TIPOGESTION']);
            $dato = $this->db->get($table);   //echo "<br>".__FILE__." > ".__LINE__.": ".$this->db->last_query();
            $id_idgestioncobro = $dato->result_array[0];
            return $id_idgestioncobro;
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funci?n updateGestionActual
     * R?plica de fiscalizacion_model->updateGestionActual
     *
     * @param String $table Tabla
     * @param Integer $gestion C?d de gesti?n
     * @param Integer $fiscalizacion C?d de fiscalizaci?n
     * @return boolean
     */
    function updateGestionActual($table, $gestion, $fiscalizacion) {
        if ($this->ion_auth->logged_in()) {
            $query = $this->db->query(" UPDATE " . $table . "  SET COD_GESTIONACTUAL='" . $gestion . "' WHERE COD_FISCALIZACION=" . $fiscalizacion . "");
            //echo "<br>".__FILE__." > ".__LINE__.": ".$this->db->last_query();
            if ($this->db->affected_rows() >= 0) {
                return TRUE;
            }
            return FALSE;
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function mostrarecordatorio($codigo_usuario) {
        if ($this->ion_auth->logged_in()) {
            /* $this->db->select('USUARIOS.IDUSUARIO, DISPARARECUSUARIO.CORREO_USUARIO,APELLIDOS,NOMBRES,TEXTO,DISPARARECORDATORIO.CODDISPARARECORDATORIO,DISPARARECORDATORIO.COD_GESTION_COBRO,DISPARARECORDATORIO.FECHA_CREACION, DISPARARECORDATORIO.FECHA_VENCIMIENTO, DISPARARECORDATORIO.ACTIVO, DISPARARECORDATORIO.APLAZADOAL');
              $query = $this->db->where('USUARIOS.IDUSUARIO', $codigo_usuario);
              $query = $this->db->where('DISPARARECORDATORIO.ACTIVO','S');
              $query = $this->db->or_where('DISPARARECORDATORIO.ACTIVO', 'A');
              $query = $this->db->join('DISPARARECUSUARIO','CODDISPARARECORDATORIO = DISPARARECUSUARIO.COD_DISPARA_REC');
              $query = $this->db->join('USUARIOS','USUARIOS.IDUSUARIO = DISPARARECUSUARIO.COD_USUARIO');
              $query = $this->db->join('RECORDATORIO','DISPARARECORDATORIO.CODRECORDATORIO = RECORDATORIO.CODRECORDATORIO');
              $query = $this->db->join('PLANTILLA','PLANTILLA.CODPLANTILLA = RECORDATORIO.CODPLANTILLA');
              $query = $this->db->get('DISPARARECORDATORIO'); */
            $this->db->select('IDUSUARIO,CORREO_USUARIO,APELLIDOS,NOMBRES,TEXTO,CODDISPARARECORDATORIO,COD_GESTION_COBRO,FECHA_CREACION,FECHA_VENCIMIENTO,ACTIVO,APLAZADOAL,NOMBREPROCESO,TIPOGESTION,NOMBRE_GESTION,URLGESTION,REC_CORREO,REC_PANTALLA');
            $query = $this->db->where('IDUSUARIO', $codigo_usuario);
            $query = $this->db->get('V_DISPARACORREOS');        //echo $this->db->last_query();
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return false;
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function recordatoriosFecha($fecha) {
        if ($this->ion_auth->logged_in()) {
            $fecha = "'" . $fecha . "'";
            $this->db->select('IDUSUARIO,CORREO_USUARIO,APELLIDOS,NOMBRES,TEXTO,FECHA_CREACION,FECHA_VENCIMIENTO');
            //$query = $this->db->where('IDUSUARIO', "1");
            //$query = $this->db->where('IDUSUARIO', "1");
            //$query = $this->db->where('FECHA_VENCIMIENTO', "TO_DATE('15-03-14','DD-MM-RR')",false);
            $query = $this->db->where('FECHA_VENCIMIENTO', "to_date($fecha,'yyyy-mm-dd')", false);
            $query = $this->db->get('V_DISPARACORREOS');
            //echo "<pre>";
            //print_r($query);
            //echo $this->db->last_query();
            //echo " >>>".$query->num_rows();

            /*
              $this->db->set('date',"to_date('$date','dd/mm/yyyy')",false);
              $this->db->insert('mytable');
             */

            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return 0;
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function siguienteGestion($tipogestion, $tiporespuesta = '') {
        if ($this->ion_auth->logged_in()) {
            $this->db->select('GESTIONDESTINO');
            $this->db->select('RTADESTINO');
            $this->db->where("GESTIONORIGEN", $tipogestion);
            if (!empty($tiporespuesta)) {
                $this->db->where("TIPORESPUESTA", $tiporespuesta);
            }
            $query = $this->db->get('FLUJO');  //echo "<br>".__FILE__." > ".__LINE__.": ".$this->db->last_query();
            $res = $query->row_array();
            if (!empty($res)) {
                return $res; //['GESTIONDESTINO'];
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Función selectIdProcesosJudiciales
     * Réplica de fiscalizacion_model->selectId
     *
     * @param String $table "tabla a consultar"
     * @param Array $data Arreglo de datos a consultar
     * @param Array $date Arreglo de fecha
     * @return type
     */
    function selectIdProcesosJudiciales($table, $data, $date) {
        if ($this->ion_auth->logged_in()) {
//          print_r($data);die();
            $this->db->select('COD_TRAZAPROCJUDICIAL');
            $this->db->where("(COD_TITULO", $data['COD_TITULO']);
            $this->db->or_where("COD_DEVOLUCION", $data['COD_DEVOLUCION']);
            $this->db->or_where("COD_CARTERANOMISIONAL", $data['COD_CARTERANOMISIONAL']);
            $this->db->or_where("COD_RECEPCIONTITULO", $data['COD_RECEPCIONTITULO']);
//        "UPPER(EMPRESA.NOMBRE_EMPRESA) like '%" . $tal . "%') and 1="
//        
            $this->db->or_where("COD_JURIDICO =' " . $data['COD_JURIDICO'] . "')");
            $this->db->where("FECHA", 'to_date(\'' . $date['FECHA'] . '\',\'dd-mm-yyyy hh24:mi\')', FALSE);
            $this->db->where("COMENTARIOS", $data['COMENTARIOS']);
            $this->db->where("COD_TIPOGESTION", $data['COD_TIPOGESTION']);
            $dato = $this->db->get($table);        //echo "<br>".__FILE__." > ".__LINE__.": ".$this->db->last_query();
            $id_procJudiciales = $dato->result_array[0];
            return $id_procJudiciales;
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

}

?>