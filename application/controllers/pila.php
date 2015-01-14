<?php

class Pila extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url', 'codegen_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
        );

        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
        );
        $this->load->model('modelpila_model');
    }

    function administracionpila() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {
                $this->load->library('datatables');
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
//                    'css/dataTables.tableTools.css' => 'screen',
//                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js'
//                    'js/dataTables.tableTools.js',
//                    'js/dataTables.tableTools.min.js',
//                    'js/moxie.js',
//                    'js/plupload.dev.js',
//                    'js/ZeroClipboard.as',
//                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );



                //template data
                $this->data[] = array();
                $this->template->set('title', 'Administracion Pila');
                $this->data['title'] = 'Aministracion Pila';
                $this->load->model('modelpila_model');
                $this->data['campos'] = $this->modelpila_model->administracioncampos();
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'pila/administracionpila', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function ingresarcampospila() {
        $nombre = $_POST['nombre'];
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $tipo = $_POST['tipo'];
        $this->load->model('Modelpila_model');


        $nombre = str_replace(" ", "_", $nombre);

        for ($i = 0; $i < count($nombre); $i++) {
//
            $longitud = $hasta[$i] - $desde[$i];
            $this->Modelpila_model->datoscaracterespila($nombre[$i], $desde[$i], $hasta[$i], $longitud, $tipo[$i]);
        }


        for ($i = 0; $i < count($nombre); $i++) {
//
//            $longitud = $hasta[$i] - $desde[$i];
//            $this->Modelpila_model->datoscaracterespila($nombre[$i], $desde[$i], $hasta[$i], $longitud, $tipo[$i]);

            switch ($tipo[$i]) {
                case "12345":
                    for ($i = 0; $i < count($nombre); $i++) {

                        $respuesta = $this->modelpila_model->campostablatipocero($nombre[$i]);

//                        echo "esta ak";die;
                        if (empty($respuesta->result_array)) {
//                            ECHO "DENTRO";DIE;
//                            echo "hola";die;

                            $this->modelpila_model->altertipocero($nombre[$i]);
                        }
                    }
                    break;
                case "00000":
                    for ($i = 0; $i < count($nombre); $i++) {

                        $respuesta = $this->modelpila_model->campostabla1($nombre[$i]);
//                        echo "esta ak";die;
                        if (empty($respuesta->result_array)) {
//                            ECHO "DENTRO";DIE;
                            $this->modelpila_model->altertipoenc($nombre[$i]);
                        }
                    }
                    break;
                case "00001":
//                    ECHO "paso por ak";die;
                    for ($i = 0; $i < count($nombre); $i++) {
                        $respuesta = $this->modelpila_model->campostabla2($nombre[$i]);

//                        var_dump($respuesta);die;
                        if (empty($respuesta->result_array)) {
                            $this->modelpila_model->altertipodet($nombre[$i]);
                        }
                    }
                    break;
                case "00031":

                    for ($i = 0; $i < count($nombre); $i++) {
                        $respuesta = $this->modelpila_model->campostabla3($nombre[$i]);

//                        var_dump($respuesta);die;
                        if (empty($respuesta->result_array)) {
                            $this->modelpila_model->altertipo3($nombre[$i]);
                        }
                    }

                case "00036":
                    for ($i = 0; $i < count($nombre); $i++) {
                        $respuesta = $this->modelpila_model->campostabla3($nombre[$i]);

//                        var_dump($respuesta->result_array);die;
                        if (empty($respuesta->result_array)) {
                            $this->modelpila_model->altertipo3($nombre[$i]);
                        }
                    }
                case "00039":
                    for ($i = 0; $i < count($nombre); $i++) {
                        $respuesta = $this->modelpila_model->campostabla3($nombre[$i]);

//                        var_dump($respuesta->result_array);die;
                        if (empty($respuesta->result_array)) {
                            $this->modelpila_model->altertipo3($nombre[$i]);
                        }
                    }
                    break;
            }
        }

//       echo $tipo[0];die;
//       var_dump($tipo);die;
    }

    function abrirplanilla() {
        $this->load->view('pila/abrirplanilla');
    }

    function generarcertificados() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js'
                );
                //template data
                $this->data[] = array();
                $this->data['nit1'] = $_POST['nit1'];
                $this->data['razonsocial1'] = $_POST['razonsocial1'];
                $this->template->set('title', 'Pila');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'Pila/generarcertificados', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function editarcampos() {
        $this->data['nombre'] = $this->input->post('nombre');
        $this->data['longitud'] = $this->input->post('longitud');
        $this->data['desde'] = $this->input->post('desde');
        $this->data['hasta'] = $this->input->post('hasta');
        $this->load->view('pila/editarcampos', $this->data);
    }

    function activarinactivar() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {
                $this->data['nit'] = $_POST['nit'];
                if (!empty($this->data['nit'])) {
                    $this->data['style_sheets'] = array(
                        'css/jquery.dataTables_themeroller.css' => 'screen'
                    );
                    $this->data['javascripts'] = array(
                        'js/jquery.dataTables.min.js',
                        'js/jquery.dataTables.defaults.js'
                    );
                    //template data


                    $this->data[] = array();
                    $this->data['nit'] = $this->input->post('nit');
                    $this->data['razonsocial'] = $_POST['razonsocial'];
                    $this->template->set('title', 'Pila');
                    $this->load->model('modelpila_model');
                    $this->data['title'] = 'Activar Inactivar Periodo';
                    $this->data['regional'] = $this->modelpila_model->regional();
//                    $this->data['cargo'] = $this->modelpila_model->cargo();
                    $this->data['message'] = $this->session->flashdata('message');

                    $activacion = $this->modelpila_model->consultainactivacion($this->data['nit']);

                    $i = array();

                    foreach ($activacion as $datosinactivos) {

                        $i[$datosinactivos['ANNO']][$datosinactivos['PERIODO']] = array();
                    }
//                    echo "<pre>";
//                    var_dump($i);die;

                    $this->data['anosactivos'] = $i;



                    $this->template->load($this->template_file, 'pila/activarinactivar', $this->data);
                } else {
                    redirect(base_url() . 'index.php/pila/consultarempresa');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardacampoinactivar() {
        $nit = $this->input->post('nit');
        if (!empty($nit)) {
            $radicado = $this->input->post('radicado');
            $fecha = $this->input->post('fechadocu');
            $nombre = $this->input->post('nombre');
            $cargo = $this->input->post('cargo');
            $regional = $this->input->post('regional');
            $exonerar = $this->input->post('exonerar');
            $usuario = $this->ion_auth->user()->row();
            $datos = $this->modelpila_model->consultaradicado($radicado);

            if (!isset($datos->result_array[0]['NRO_RADICADO'])) {
//                echo "2";die;
                $campo = array(
                    "NRO_RADICADO" => $radicado,
                    "FECHA_DOCUMENTO" => $fecha,
                    "NOMBRE_INFORMANTE" => $nombre,
                    "EXONERADO_CREE" => $exonerar,
                    "CARGO_INFORMANTE" => $cargo,
                    "REGIONAL_INFORMANTE" => $regional,
                );
                $datos = $this->modelpila_model->inactivacionempresa($campo);
                echo 1;
            } else {
                return $this->output->set_content_type('application/json')->set_output(json_encode($datos->result_array));
            }
        } else {
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function periodosactivarinactivar() {

        $nit = $this->input->post('nit');
        $this->load->model('modelpila_model');
        $ingresardatos = array();
        $m = 0;

//        $nit = 800800800;
        $nit = $this->input->post('nitempresa');

        $this->modelpila_model->eliminaperiodos($nit);

        $planilla = $this->input->post('nrad');

        $datosmes = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);

        foreach ($_POST as $mes => $anno) {
//            echo $mes;

            if (is_int($mes) == true) {
                if ($mes != "nit" && in_array($mes, $datosmes)) {
                    for ($i = 0; $i < count($anno); $i++) {
                        $m = $i + $m;
//                    echo $mes;die;
//                        echo $mes . "<br>";
                        $ingresardatos[$m] = array("COD_EMPRESA" => $nit, "PERIODO" => $mes, "ANNO" => $anno[$i], "NRO_RADICADO" => $planilla);
                    }
                    $m++;
                }
            }
        }

        $this->modelpila_model->periodosinactivos($ingresardatos);
    }

    function consultarempresa() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/dataTables.tableTools.css' => 'screen',
                    'css/dataTables.tableTools.min.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/dataTables.tableTools.js',
                    'js/dataTables.tableTools.min.js',
//                    'js/ZeroClipboard.as',
                    'js/ZeroClipboard.js'
//                    'js/ZeroClipboard.fla',
                );

                //template data
                $this->data[] = array();
                $this->template->set('title', 'Pila');
                $this->data['title'] = 'Consulta Empresa';
                $this->load->model('modelpila_model');
                $this->data['regional'] = $this->modelpila_model->regional();
                $this->load->model('modelpila_model');
                $this->data['tipodocumento'] = $this->modelpila_model->tipodocumento();
//                $this->load->model('modelpila_model');
//                $this->data['eliminacionempresa'] = $this->modelpila_model->eliminacionempresa();
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'pila/consultarempresa', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function autocompletaremrpesa() {
        $empresa = $this->input->get("term");
        $this->load->model('modelacuerdodepago_model');
        $consulta = $this->modelpila_model->razonsocial2($empresa);
        $temp = NULL;
        if (!is_null($consulta)) :
            foreach ($consulta as $datos) {
                $temp[] = array("value" => $datos['CODEMPRESA'], "label" => $datos['CODEMPRESA'] . "::" . $datos['RAZON_SOCIAL']);
//        echo "<pre>";
//        var_dump($consulta->result_array);die;
            }
        endif;
        return $this->output->set_content_type('application/json')->set_output(json_encode($temp));
    }

    function autocompletaremrpesarazonsocial() {

        $empresa = $this->input->get("term");
        $this->load->model('modelacuerdodepago_model');
        $consulta = $this->modelacuerdodepago_model->razonsocial($empresa);
        $temp = NULL;
        if (!is_null($consulta)) :
            foreach ($consulta as $datos) {
                $temp[] = array("value" => $datos['RAZON_SOCIAL'], "label" => $datos['CODEMPRESA'] . "::" . $datos['RAZON_SOCIAL']);
//        echo "<pre>";
//        var_dump($consulta->result_array);die;
            }
        endif;
        return $this->output->set_content_type('application/json')->set_output(json_encode($temp));
    }

    function eliminarempresa() {
        $nit = $_POST['nit'];
        $razonsocial = $_POST['razonsocial'];
        $nplanilla = $_POST['nplanilla'];

        $this->load->model('modelpila_model');
        $eliminacionempresa = $this->modelpila_model->eliminacionempresa($nit, $razonsocial, $nplanilla);
    }

    function detalleempresa() {
        $tipo = $this->input->post('tipo');
        $documento = $this->input->post('documento');
        $planilla = $this->input->post('planilla');
        $razon = $this->input->post('razon');
        $fechai = $this->input->post('fechai');
        $fechaf = $this->input->post('fechaf');
        $regional = $this->input->post('regional');
        $operador = $this->input->post('operador');
        $valordesde = $this->input->post('valordesde');
        $valorhasta = $this->input->post('valorhasta');
        $mesperiodofinal = $this->input->post('mesperiodofinal');
        $annoperiodofinal = $this->input->post('annoperiodofinal');
        $mesperiodoinicial = $this->input->post('mesperiodoinicial');
        $annoperiodoinicial = $this->input->post('annoperiodoinicial');

        if ($mesperiodofinal < 10)
            $mesperiodofinal = "0" . $mesperiodofinal;
        if ($mesperiodoinicial < 10)
            $mesperiodoinicial = "0" . $mesperiodoinicial;

        $funcion = $this->modelpila_model->razonsocial($tipo, $documento, $planilla, $razon, $fechai, $fechaf, $regional, $operador, $valordesde, $valorhasta, $mesperiodofinal, $annoperiodofinal, $mesperiodoinicial, $annoperiodoinicial);

//        foreach ($funcion->result_array() as $value) {
//            $value['N_TOTAL_EMPLEADOS'] = number_format($value['N_TOTAL_EMPLEADOS']);
//            
//        }
//      var_dump($funcion->result_array);die;

        $this->output->set_content_type('application/json')->set_output(json_encode($funcion->result_array));
    }

    function exportarexcel() {
        $this->load->view('pila/exportarexcel');
    }

    function registraplanillauno() {

        $razonsocial = $this->input->post('razonsocial');
        $nit = $this->input->post('nit');

        $this->load->model('modelpila_model');
        $this->data['consultarempresa'] = $this->modelpila_model->consultarempresa($razonsocial, $nit);

//
//        echo "<pre>";
//        var_dump($this->data['consultarempresa']);die;

        $this->load->view('pila/registraplanillauno', $this->data);
    }

    function descomprime($file, $filet) {
        require_once('pclzip.lib.php');
        $ruta = "uploads/cargarpila/temporal/";
        $this->deleteDir($ruta);
        $this->directorios2();
        $archive = new PclZip($filet);
        if ($archive->extract(PCLZIP_OPT_PATH, $ruta, PCLZIP_OPT_REMOVE_PATH, 'install/release') == 0) {
            array_push($this->data['errores'], $file . " , Error en la extraccion " . $archive->errorInfo(true));
            array_push($this->data['errores_totales'], $this->data['errores']);
            return false;
        } else {
            return true;
        }
    }

    function visualizarcarga() {
        $archivo = $this->input->post('archivo');
        $tipo = $this->input->post('tipo');

        switch ($tipo) {
            case 1:
                $tipoarchivo = $this->modelpila_model->visualizartipouno($archivo);
                break;
            case 2:
                $tipoarchivo = $this->modelpila_model->visualizartipodos($archivo);
                break;
            case 3:
                $tipoarchivo = $this->modelpila_model->visualizartipotres($archivo);
                break;
            case 0:
                $tipoarchivo = $this->modelpila_model->visualizartipocero($archivo);
                break;
        }

//        $datosresultado = array();
//        $i = 0;
//        foreach($tipoarchivo->result_array as $archivo => $campos){
//            
//            foreach($campos as $datos => $result){
//                if($result != "") $datosresultado[$i][$datos] = $result;
//            }
//            $i++;
//        }
//        echo "<pre>";
//        var_dump(str_replace(null,"&&", $tipoarchivo->result_array[0]));die;
//        $datos = str_replace("","",$tipoarchivo->result_array);

        $this->output->set_content_type('application/json')->set_output(json_encode($tipoarchivo->result_array));
    }

    function tipocero($archivo1, $linea, $funcioncero) {

        foreach ($funcioncero->result_array as $campos) {
            $campo = trim(substr($linea, $campos['DESDE'], $campos['LONGITUD']));
//            $campo = base64_encode(trim(substr($linea, $campos['DESDE'], $campos['LONGITUD'])));
            $obligatorio = $campos['OBLIGATORIO'];
            $clave = $campos['PALABLA_CLAVE'];

//            if ($obligatorio == 1 && $campo == "") {
//                                  CAMPO CON ERROR
//                                    $campoerror[$archivo1][$clave] = $campo;
//            } else {
//                $i[$archivo1]["tipocero"][str_replace(" ", "_", $campos['NOMBRE_CAMPO'])] = array($campo, $obligatorio, $clave);
            $guardar[0][$campos['NOMBRE_CAMPO']] = $campo;
            $guardar[0]["ARCHIVO"] = $archivo1;
//            }
        }
//        var_dump($guardar);die;

        $this->modelpila_model->insertatipocero($guardar);

//        return $i;
    }

    function tipouno($archivo1, $linea, $funcion) {

        $i = array();
        foreach ($funcion->result_array as $campos) {
//            $campo = base64_encode(str_replace("'", "", trim(substr($linea, $campos['DESDE'], $campos['LONGITUD']))));
            $campo = trim(substr($linea, $campos['DESDE'], $campos['LONGITUD']));
            $obligatorio = $campos['OBLIGATORIO'];
            $clave = $campos['PALABLA_CLAVE'];
//            if ($obligatorio == 1 && $campo == "") {
//                                  CAMPO CON ERROR
//                                    $campoerror[$archivo1][$clave] = $campo;
//            } else {
//                $i[$archivo1]["000001"][str_replace(" ", "_", $campos['NOMBRE_CAMPO'])] = array($campo, $obligatorio, $clave);
            $guardar[0][$campos['NOMBRE_CAMPO']] = $campo;
            $guardar[0]["ARCHIVO"] = $archivo1;
//            }
        }
        $id = $this->modelpila_model->guardatipouno($guardar, $archivo1);

        return $id;
//        return $i;
    }

    function tipodos($contadorlineastipodos, $archivo1, $linea, $idtipouno, $funcion2) {
        $tipo2 = substr($linea, 0, 6);
        for ($con = $contadorlineastipodos; $con < 10000; $con++) {
//            ECHO $contadorlineastipodos . "<br>";
            $numero = str_pad($con, 5, 0, STR_PAD_LEFT);
            if ($tipo2 === "$numero" . "2") {
//                echo $numero;
                foreach ($funcion2->result_array as $campos) {
//                    $campo = str_replace("'","", trim(substr($linea, $campos['DESDE'], $campos['LONGITUD'])));
                    $campo = trim(substr($linea, $campos['DESDE'], $campos['LONGITUD']));
                    $obligatorio = $campos['OBLIGATORIO'];
                    $clave = $campos['PALABLA_CLAVE'];
//                    if ($obligatorio == 1 && $campo == "") {
//                    } else {
//                        $i[$archivo1]["$numero" . "2"][str_replace(" ", "_", $campos['NOMBRE_CAMPO'])] = array($campo, $obligatorio, $clave);
                    $guardar[0][$campos['NOMBRE_CAMPO']] = $campo;
                    $guardar[0]["ARCHIVO"] = $archivo1;
                    $guardar[0]["COD_PLANILLAUNICA"] = $idtipouno;
//                    }
                }
                $con = 10000;
            }
        }
        $this->modelpila_model->guardatipodos($guardar);
    }

    function tipotres13($archivo1, $linea, $idtipouno, $funcion3) {

//        $i = array();
        foreach ($funcion3->result_array as $campos) {
//            $campo = str_replace("'","", trim(substr($linea, $campos['DESDE'], $campos['LONGITUD'])));
            $campo = trim(substr($linea, $campos['DESDE'], $campos['LONGITUD']));
            $obligatorio = $campos['OBLIGATORIO'];
            $clave = $campos['PALABLA_CLAVE'];
//            if ($obligatorio == 1 && $campo == "") {
//            } else {
//                $i[$archivo1]["000313"][str_replace(" ", "_", $campos['NOMBRE_CAMPO'])] = array($campo, $obligatorio, $clave);
            $guardar[0][$campos['NOMBRE_CAMPO']] = $campo;
            $guardar[0]["ARCHIVO"] = base64_encode($archivo1);
            $guardar[0]["COD_CAMPO"] = $idtipouno;
//            }
        }
        return $guardar;
    }

    function tipotres63($archivo1, $linea, $idtipouno, $funcion3_1) {
//        $i = array();
        foreach ($funcion3_1->result_array as $campos) {
//            $campo = str_replace("'","", trim(substr($linea, $campos['DESDE'], $campos['LONGITUD'])));
            $campo = trim(substr($linea, $campos['DESDE'], $campos['LONGITUD']));
            $obligatorio = $campos['OBLIGATORIO'];
            $clave = $campos['PALABLA_CLAVE'];
//            if ($obligatorio == 1 && $campo == "") {
//            } else {
//                $i[$archivo1]["000363"][str_replace(" ", "_", $campos['NOMBRE_CAMPO'])] = array($campo, $obligatorio, $clave);
            $guardar[0][$campos['NOMBRE_CAMPO']] = $campo;
            $guardar[0]["ARCHIVO"] = $archivo1;
            $guardar[0]["COD_CAMPO"] = $idtipouno;
//            }
        }
//        $this->modelpila_model->guardatipotres($guardar);
        return $guardar;
    }

    function tipotres93($archivo1, $linea, $idtipouno, $tiposanteriores, $datoanterior, $funcion3_2) {
//        $i = array();
        foreach ($funcion3_2->result_array as $campos) {
//            $campo = str_replace("'","", trim(substr($linea, $campos['DESDE'], $campos['LONGITUD'])));
            $campo = trim(substr($linea, $campos['DESDE'], $campos['LONGITUD']));
            $obligatorio = $campos['OBLIGATORIO'];
            $clave = $campos['PALABLA_CLAVE'];
//            if ($obligatorio == 1 && $campo == "") {
//            } else {
            if ($obligatorio == 1 && $campo == $clave || $obligatorio != 1) {
//                    $i[$archivo1]["000393"][str_replace(" ", "_", $campos['NOMBRE_CAMPO'])] = array($campo, $obligatorio, $clave);
                $guardar[0][$campos['NOMBRE_CAMPO']] = $campo;
                $guardar[0]["ARCHIVO"] = $archivo1;
                $guardar[0]["COD_CAMPO"] = $idtipouno;
//                }
            }
        }

        $datos[] = array_merge($tiposanteriores[0], $datoanterior[0], $guardar[0]);

        $this->modelpila_model->guardatipotres($datos);
//        return $i;
    }

    function guardarcampospila() {

        $nombre = $this->input->post('nombre');
        $longitud = $this->input->post('longitud');
        $desde = $this->input->post('desde');
        $hasta = $this->input->post('hasta');
        $palabraobligatoria = $this->input->post('palabraobligatoria');
        $obligatorio = $this->input->post('obligatorio');

        $insertar = array(
            "NOMBRE_CAMPO" => $nombre,
            "DESDE" => $desde,
            "HASTA" => $hasta,
            "LONGITUD" => $longitud
//                            "PALABLA_CLAVE"=>$palabraobligatoria
        );

        $this->modelpila_model->modificarobligatoriedad($insertar, $obligatorio, $palabraobligatoria);
    }

    function enviarcertificado() {
        $this->load->view('pila/enviarcertificado');
    }

    function registraplanillados() {
        $planilla = $this->input->post('planilla');

        if (!empty($planilla)) {

            $this->data['consulta'] = $this->modelpila_model->consultaempresapila($planilla);

//       $this->output->set_content_type('application/json')->set_output(json_encode($this->data['consulta']->result_array));
            $this->load->view('pila/registraplanillados', $this->data);
        }
    }

    function registraplanillatres() {
        $planilla = $this->input->post('planilla');

        if (!empty($planilla)) {
            $this->data['consulta'] = $this->modelpila_model->consultatipo3($planilla);
            $this->load->view('pila/registraplanillatres', $this->data);
        }
    }

    function registrobanco() {
        $planilla = $this->input->post('planilla');

        if (!empty($planilla)) {
            $this->data['consulta'] = $this->modelpila_model->consultabanco($planilla);
            $this->load->view('pila/registrobanco', $this->data);
        } else {
            redirect(base_url() . 'index.php/pila/consultarempresa');
        }
    }

    function eliminarplanilla() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js'
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'pila/eliminarplanilla', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function certificadosmasivos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js'
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->load->model('modelpila_model');
                $this->data['tipocertifi'] = $this->modelpila_model->tipocertifi();
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'pila/certificadosmasivos', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function masivosnit() {
        $nit = $_POST['nit'];

        $this->load->model('modelpila_model');
        $masivonit = $this->modelpila_model->masivonit($nit);

        echo $masivonit->result_array[0]["NOMBRE_EMPRESA"];


//      var_dump($masivonit);
    }

    function certificadoperiodo() {

        $certificado = $_POST['certificado'];
        $nit = $_POST['nit'];

        $this->load->model('modelpila_model');
        $this->data['periodo'] = $this->modelpila_model->periodo($nit);

        $this->load->view('pila/certificadoperiodo', $this->data);
    }

    function certificadoobra() {
        $certificado = $_POST['certificado'];
        $nit = $_POST['nit'];

        $this->load->model('modelpila_model');
        $this->data['obra'] = $this->modelpila_model->obra($nit);

        $this->load->view('pila/certificadoobra', $this->data);
    }

    function certificadoresolucion() {
        $certificado = $_POST['certificado'];
        $nit = $_POST['nit'];

        $this->load->model('modelpila_model');
        $this->data['resolucion'] = $this->modelpila_model->resolucion($nit);

        $this->load->view('pila/certificadoresolucion', $this->data);
    }

    function certificadoaportes() {
        $certificado = $_POST['certificado'];
        $nit = $_POST['nit'];

        $this->load->model('modelpila_model');
        $this->data['aportes'] = $this->modelpila_model->aportes($nit);


        $this->load->view('pila/certificadoaportes', $this->data);
    }

    function certificadotributario() {
        $certificado = $_POST['certificado'];
        $nit = $_POST['nit'];

        $this->load->model('modelpila_model');
        $this->data['tributario'] = $this->modelpila_model->tributario($nit);

        $this->load->view('pila/certificadotributario', $this->data);
    }

    function certificadodeuda() {
        $certificado = $_POST['certificado'];
        $nit = $_POST['nit'];

        $this->load->model('modelpila_model');
        $this->data['deuda'] = $this->modelpila_model->deuda($nit);

        $this->load->view('pila/certificadodeuda', $this->data);
    }

    function cargacodeigniter() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js'
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Cancelacion Acuerdo de pago');
                $this->data['title'] = 'Cancelacion Acuerdo Pago';
                $this->load->model('modelpila_model');
                $this->data['tipocertifi'] = $this->modelpila_model->tipocertifi();
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'pila/cargacodeigniter', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function carguepila() {
        ini_set('max_execution_time', -1);
        //@set_time_limit(300);
        $this->data['dateini'] = date('Y-m-d H:i:s');
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pila/pilaadd')) {

                //   Setear  variables de control 
                $this->data['errores_totales'] = array();
                $this->data['numlineas_totales'] = 0;
                $this->data['carga_exito'] = array();
                $this->data['cargados'] = array();
                $this->data['nocargados'] = array();
                $this->data['existentesz'] = array();
                $this->data['existentest'] = array();
                $this->data['positivos'] = 0;
                $this->data['negativos'] = 0;
                $this->data['errores'] = array();

                if (is_dir("uploads/cargarpila/temporal")) {
                    $this->deleteDir("uploads/cargarpila/temporal", '1');
                }
                $directorios = $this->directorios2();

                if ($directorios) {
                    foreach (array_combine($_FILES['uploads']['name'], $_FILES['uploads']['tmp_name']) as $file => $filet) {
                        if ($file == '') {
                            array_push($this->data['errores'], $file . " , No se selecciono ningun archivo. ");
                            array_push($this->data['errores_totales'], $this->data['errores']);
                        } else {
                            $this->data['numlineas'] = 0;
                            $this->data['archivo'] = array();
                            $this->data['carga'] = array();
                            $this->data['ficheros'] = array();

                            $path = "uploads/cargarpila/temporal/";
                            $extension = trim($this->obtenerExtensionFichero($file));
                            if ($extension != 'zip' && $extension != 'ZIP') {
                                array_push($this->data['errores'], $file . " ,Este archivo no es tipo zip. ");
                                array_push($this->data['errores_totales'], array($file . " ,Este archivo no es tipo zip. "));
                                $this->data['negativos'] ++;
                            } else {
                                $descomprime = $this->descomprime($file, $filet);
                                if ($descomprime) {
                                    $archivos = $this->listar_directorios_ruta($path);
                                }
                                if ($archivos) {
                                    foreach ($archivos as $value) {
                                        foreach ($value as $key => $archivo) {
                                            $cargar = $this->cargapila($key, $archivo);
                                        }
                                    }
                                }
                            }
                        }
                        if (sizeof($this->data['errores']) == 0) {
                            move_uploaded_file($filet, "uploads/cargarpila/archivos/" . $file);
                        }
                    }
                } else {
                    array_push($this->data['errores'], " Error en directorios del sistema ");
                    array_push($this->data['errores_totales'], array(" Error en directorios del sistema "));
                    $this->data['numlineas'] = 0;
                    unset($this->data['errores']);
                }
                $this->data['datefin'] = date('Y-m-d H:i:s');
                $this->template->load($this->template_file, 'pila/cargarpila_result', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class = "alert alert-info"><button type = "button" class = "close" data-dismiss = "alert">&times;
                </button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/cargarextractoasobancaria');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function listar_directorios_ruta($ruta) {
        $this->data['ficheros'] = array();
// abrir un directorio y listarlo recursivo
        if (is_dir($ruta)) {
            if ($dh = opendir($ruta)) {
                while (($file = readdir($dh)) !== false) {
//esta línea la utilizaríamos si queremos listar todo lo que hay en el directorio
//mostraría tanto archivos como directorios
                    if (filetype($ruta . $file) == 'file') {
                        array_push($this->data['ficheros'], array($ruta => $file));
                    }
//echo "<br>Nombre de archivo: $file : Es un: " . filetype($ruta . $file);
                    if (is_dir($ruta . $file) && $file != "." && $file != "..") {
//solo si el archivo es un directorio, distinto que "." y ".."
                        $this->listar_directorios_ruta($ruta . $file . "/");
                    }
                }
                closedir($dh);
            }
            return $this->data['ficheros'];
        } else {
            //array_push($this->data['errores'], "Ruta de archivo no valida. ");
            //array_push($this->data['errores_totales'], array("Ruta de archivo no valida. "));
            return false;
        }
    }

    function deleteDir($path, $tipo = 0) {
        if (!is_dir($path)) {
            throw new InvalidArgumentException("$path is not a directory");
        }
        if (substr($path, strlen($path) - 1, 1) != '/') {
            $path .= '/';
        }
        $dotfiles = glob($path . '.*', GLOB_MARK);
        $files = glob($path . '*', GLOB_MARK);
        $files = array_merge($files, $dotfiles);
        foreach ($files as $file) {
            if (basename($file) == '.' || basename($file) == '..') {
                continue;
            } else if (is_dir($file)) {
                self::deleteDir($file, '1');
            } else {
                unlink($file);
            }
        }
        if ($tipo == 1) {
            rmdir($path);
        }
    }

    private function directorios2() {
        $errores = 0;
        $paths = array();
        $paths[] = "uploads/cargarpila/";
        $paths[] = "uploads/cargarpila/archivos";
        $paths[] = "uploads/cargarpila/errores";
        $paths[] = "uploads/cargarpila/generados";
        $paths[] = "uploads/cargarpila/temporal";
        foreach ($paths as $path) {
            if (!is_dir($path)) {
                if (!mkdir($path, 0777, TRUE)) {
                    $errores++;
                }
            }
        }
        if ($errores > 0)
            return false;
        else
            return true;
    }

    function cargapila($ruta, $archivo) {
        //$ruta = "uploads/cargarpila/temporal/";
        $ruta2 = "/media/oracle/PILA/";

        $archivo1 = getcwd() . "/" . $ruta . $archivo;
        $archivo2 = $ruta2 . $archivo;
        $cmd = copy($archivo1, $archivo2);
        $command = "sh /bin/convertirarchivos.sh $archivo";
        exec($command);
        if ($cmd) {
            $carga = $this->modelpila_model->Cargarpila($archivo);
            if (trim($carga) != '') {
                array_push($this->data['errores'], $carga);
//                $this->crearfichero(0, $archivo, $ruta);
                $this->data['negativos'] ++;
                $this->data['archivo'] = "errores/" . $archivo;
                $this->data['numlineas'] = 0;
                array_push($this->data['errores_totales'], array($carga));
            } else {
//                $this->crearfichero(1, $archivo, $ruta);
                $this->data['positivos'] ++;
                $this->data['archivo'] = "generados/" . $archivo;
                $this->data['numlineas_totales'] += $this->data['numlineas'];
            }
            unlink($archivo1);
            unlink($archivo2);
        } else {
            echo "NO SE PUEDE CARGAR EL ARCHIVO: " . $archivo;
            echo "<br>";
        }
    }

    function obtenerExtensionFichero($str) {
        return end(explode(".", $str));
    }

    private function crearfichero($tipo, $file, $filet) {
        $path = "uploads/cargarpila/";
        if ($tipo == 1) {
            $this->data['archivo'] = 'generados/' . $file;
            move_uploaded_file($filet, $path . "archivos/" . $file);
            array_push($this->data['carga_exito'], $file . ', Cargado con Éxito.');
            $ar = fopen($path . "generados/" . $file, "a") or die("Problemas en la creacion");
            fputs($ar, "ARCHIVO CARGADO");
            foreach ($this->data['carga_exito'] as $value) {
                fputs($ar, trim($value) . "\r\n");
            }
            fputs($ar, trim("----------------------------------------------------") . "\r\n");
            fputs($ar, trim("Número total lineas cargadas: " . $this->data['numlineas']) . "\r\n");
            fputs($ar, trim("Número total Archivos cargados: " . $this->data['positivos']) . "\r\n");
            fputs($ar, trim("Número total Archivos no cargados: " . $this->data['negativos']) . "\r\n");
            fputs($ar, trim("----------------------------------------------------") . "\r\n");
            fclose($ar);
        } else {
            $ar = fopen($path . "errores/" . $file, "a") or die("Problemas en la creacion");
            fputs($ar, "ARCHIVO " . $file . " NO CARGADO");
            fputs($ar, "\r\n");
            foreach ($this->data['errores'] as $value) {
                fputs($ar, trim($value) . "\r\n");
            }
            fputs($ar, "--------------------------------------------------------");
            fclose($ar);
        }
    }

    function exportar_excel() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('ecollect/manage')) {
                header("Content-type: application/vnd.ms-excel; name='excel'");
                header("Content-Disposition: filename=ficheroExcel.xls");
                header("Pragma: no-cache");
                header("Expires: 0");

                echo $_POST['datos_a_enviar'];
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function pilaadd() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pila/pilaadd')) {
                $this->data['errores'] = array();
                $this->data['result_archivo'] = array();
                $this->data['numlineas'] = 0;
                $this->template->load($this->template_file, 'pila/cargarpila_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function exporta() {
        $path = "./uploads/cargarpila/";
        $enlace = $path . $this->input->post('archivo');
        header("Content-Disposition: attachment; filename=reporte_cargue.txt");
        header("Content-Type: application/text");
        header("Content-Length: " . filesize($enlace));
        readfile($enlace);
    }

    function planillasencero() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pila/pilaadd')) {
                @mkdir("uploads/cargarpila/planillasencero/", 0777, TRUE);
                $fechaini = $this->input->post('fecha_inicio') ? $this->input->post('fecha_inicio') : '';
                $fechafin = $this->input->post('fecha_fin') ? $this->input->post('fecha_fin') : '';
                if ($fechaini != '' || $fechafin != '') {
                    $planillas = $this->modelpila_model->planillasencero($fechaini, $fechafin);
                    if (sizeof($planillas->result_array()) > 0) {

                        $this->load->library("PHPExcel");
                        $fecha = date('Ymd-hi');
                        $nombre = "PLANILLASENCERO-" . $fecha;
                        $PHPExcel = new PHPExcel();
                        $PHPExcel->getProperties()->setCreator("SENA")->setTitle("RELACION PLANILLAS EN CERO");
                        $PHPExcel->setActiveSheetIndex(0);
                        $PHPExcel->getActiveSheet()->setTitle('PLANILLAS EN CERO');

                        $PHPExcel->getActiveSheet()->setCellValue('A1', 'RELACION PLANILLAS EN CERO');
                        $PHPExcel->getActiveSheet()->setCellValue('A2', 'FECHA GENERACION: ' . $fecha);
                        $PHPExcel->getActiveSheet()->setCellValue('A5', 'IDENTIFICACION APORTANTE');
                        $PHPExcel->getActiveSheet()->setCellValue('B5', 'DIGITO VERIFICACION');
                        $PHPExcel->getActiveSheet()->setCellValue('C5', 'TIPO IDENTIFICACION');
                        $PHPExcel->getActiveSheet()->setCellValue('D5', 'TIPO APORTANTE');
                        $PHPExcel->getActiveSheet()->setCellValue('E5', 'CIU');
                        $PHPExcel->getActiveSheet()->setCellValue('F5', 'DIRECCION');
                        $PHPExcel->getActiveSheet()->setCellValue('G5', 'TELEFONO');
                        $PHPExcel->getActiveSheet()->setCellValue('H5', 'FAX');
                        $PHPExcel->getActiveSheet()->setCellValue('I5', 'CORREO ELECTRONICO');
                        $PHPExcel->getActiveSheet()->setCellValue('J5', 'PERIODO PAGO');
                        $PHPExcel->getActiveSheet()->setCellValue('K5', 'TIPO PLANILLA');
                        $PHPExcel->getActiveSheet()->setCellValue('L5', 'FECHA PAGO');
                        $PHPExcel->getActiveSheet()->setCellValue('M5', 'NUMERO PLANILLA');
                        $PHPExcel->getActiveSheet()->setCellValue('N5', 'NUMERO RADICACION');
                        $PHPExcel->getActiveSheet()->setCellValue('O5', 'OPERADOR');
                        $PHPExcel->getActiveSheet()->setCellValue('P5', 'ARCHIVO');
                        $x = 6;
                        foreach ($planillas->result_array as $value) {
                            $PHPExcel->getActiveSheet()->setCellValue('A' . $x, $value['N_INDENT_APORTANTE']);
                            $PHPExcel->getActiveSheet()->setCellValue('B' . $x, $value['DIG_VERIF_APORTANTE']);
                            $PHPExcel->getActiveSheet()->setCellValue('C' . $x, $value['TIPO_DOC_APORTANTE']);
                            $PHPExcel->getActiveSheet()->setCellValue('D' . $x, $value['TIPO_APORTANTE']);
                            $PHPExcel->getActiveSheet()->setCellValue('E' . $x, $value['COD_CIU_O_MUN']);
                            $PHPExcel->getActiveSheet()->setCellValue('F' . $x, $value['DIREC_CORRESPONDENCIA']);
                            $PHPExcel->getActiveSheet()->setCellValue('G' . $x, $value['TELEFONO']);
                            $PHPExcel->getActiveSheet()->setCellValue('H' . $x, $value['FAX']);
                            $PHPExcel->getActiveSheet()->setCellValue('I' . $x, $value['CORREO_ELECTRO']);
                            $PHPExcel->getActiveSheet()->setCellValue('J' . $x, $value['PERIDO_PAGO']);
                            $PHPExcel->getActiveSheet()->setCellValue('K' . $x, $value['TIPO_PLANILLA']);
                            $PHPExcel->getActiveSheet()->setCellValue('L' . $x, $value['FECHA__PAGO']);
                            $PHPExcel->getActiveSheet()->setCellValue('M' . $x, $value['N_PLANILLA_']);
                            $PHPExcel->getActiveSheet()->setCellValue('N' . $x, $value['N_RADICACION']);
                            $PHPExcel->getActiveSheet()->setCellValue('O' . $x, $value['COD_OPERADOR']);
                            $PHPExcel->getActiveSheet()->setCellValue('P' . $x, $value['ARCHIVO']);
                            $x++;
                        }

                        //header('Cache-Control: max-age=0');
                        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
                        ob_clean();
                        $objWriter->save("./uploads/cargarpila/planillasencero/" . $nombre . ".xlsx");
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                        header('Content-Disposition: attachment;filename="' . $nombre . ".xlsx" . '"');
                        $objWriter->save("php://output");
                        die();
                    } else {
                        $this->data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>No existen planillas en cero en este periodo de tiempo</div>';
                    }
                    $this->template->load($this->template_file, 'pila/planillasencero', $this->data);
                } else {
                    $this->template->load($this->template_file, 'pila/planillasencero');
                }
            } else {
                $this->session->set_flashdata('message', '<div class = "alert alert-info"><button type = "button" class = "close" data-dismiss = "alert">&times;
                </button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/cargarextractoasobancaria');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

}
