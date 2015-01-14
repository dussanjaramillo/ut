<?php

class Acuerdodepagojuridico extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->data['style_sheets'] = array('css/jquery.dataTables_themeroller.css' => 'screen', 'css/chosen.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen');
        $this->data['javascripts'] = array('js/jquery.dataTables.min.js', 'js/jquery.dataTables.defaults.js', 'js/chosen.jquery.min.js',
            'js/jquery.confirm.js', 'js/tinymce/tinymce.jquery.min.js', 'js/dataTables.tableTools.js', 'js/dataTables.tableTools.min.js',
            'js/ZeroClipboard.js', 'js/jquery.number.js', 'js/jquery.PrintArea.js');
        $this->data['message'] = $this->session->flashdata('message');
        $this->load->library('Datatables');
        $this->load->library("PHPExcel");
        $this->load->library('tcpdf/tcpdf.php');
        $this->load->library(array('pagination', 'libupload', 'datatables', 'session', 'form_validation', 'upload'));
        $this->load->model('modelacuerdoconsulta_model');
        $this->load->model('modelacuerdodepagojuridico_model');
        $this->load->model('ejecutoriaactoadmin_model');
        $this->load->model('acuerdopagodocjuridico_model');
        $this->load->model('documentospj_model');

        $this->load->model('numeros_letras_model');
        $this->load->file(APPPATH . "controllers/expedientes.php", TRUE);
        $this->load->file(APPPATH . "controllers/ejecutoriaactoadmin.php", TRUE);
        $this->load->model('expediente_model');
        $this->load->helper(array('form', 'url', 'liquidaciones_helper', 'codegen_helper', 'traza_fecha', 'traza_fecha_helper', 'date', 'template',));
        $this->data['user'] = $this->ion_auth->user()->row();
        define("ID_USER", $this->data['user']->IDUSUARIO);
        define("REGIONAL_USER", $this->data['user']->COD_REGIONAL);
        define("NOMBRE_COMPLETO", $this->data['user']->APELLIDOS . " " . $this->data['user']->NOMBRES);

        $this->data['nombreusuario'] = $this->data['user']->NOMBRES . " " . $this->data['user']->APELLIDOS;
        $this->data['consultagrupo'] = $this->modelacuerdodepagojuridico_model->usuarios(ID_USER);
        $this->data['grupo'] = $this->data['consultagrupo'][0]['IDGRUPO'];
        $cargo = $this->data['consultagrupo'][0]['IDCARGO'];
        define("ID_CARGO", $cargo);
        $sesion = $this->session->userdata;

        define("ID_SECRETARIO", $sesion['id_secretario']);
        define("NOMBRE_SECRETARIO", $sesion['secretario']);
        define("ID_COORDINADOR", $sesion['id_coordinador']);
        define("NOMBRE_COORDINADOR", $sesion['coordinador_relaciones']);
        define("MISIONAL", 1);
        define("NO_MISIONAL", 2);

        if ($this->data['grupo'] == 41)
            $this->data['cargousuario'] = "SECRETARIO";
        else if ($this->data['grupo'] == 42)
            $this->data['cargousuario'] = "COORDINADOR";
        else if ($this->data['grupo'] == 170)
            $this->data['cargousuario'] = "EJECUTOR";
        else if ($this->data['grupo'] == 1)
            $this->data['cargousuario'] = "ADMINISTRADOR";
        else if ($this->data['grupo'] == 43)
            $this->data['cargousuario'] = "ABOGADO";
        else if ($this->data['grupo'] == 45)
            $this->data['cargousuario'] = "ABOGADO";
        else if ($this->data['grupo'] == 183)
            $this->data['cargousuario'] = "ABOGADO";
        else if ($this->data['grupo'] == 44)
            $this->data['cargousuario'] = "ABOGADO_RELACIONES";
        else if ($this->data['grupo'] == 144)
            $this->data['cargousuario'] = "EJECUTOR";
//          define("ABOGADO_RELACIONES", '44');
//        define("COORDINADOR_RELACIONES", '144');
//      SECRETARIO  
        $this->data['secretario'] = 41;
//      COORDINADOR       
        $this->data['coodinador'] = 42;
        $this->data['ejecutor'] = 170;
//      ABOGADO      
//        $this->data['abogado'] = 43;
//      ADMINISTRADOR  
        $this->data['administrador'] = 1;
//      ABOGADO RELACIONES CORPORATIVAS
        $this->data['abogado'] = 45;
        $this->data['abogadoj'] = 183;
        $this->data['abogado_relaciones'] = 44;
        $this->data['coordinador_relaciones'] = 144;
        define("RUTA_INI", "./uploads/cod_coactivoes/resolucion");
        define("RUTA_DES", "uploads/cod_coactivoes/resolucion/COD_");
    }

    function index() {
        $this->gestionarAcuerdo();
    }

    function ingresarcambiosgarantias() {
        $opcion = $_POST['opcion'];

        switch ($opcion) {
            case 1:
                $id = $this->input->post('id');
                $garantia = $this->input->post('garantia');
                $campo = $this->input->post('modificacioncampo');
                $this->modelacuerdodepagojuridico_model->guardacamposeditadosadmingarantia($id, $garantia, $campo);
                break;
            case 2:
                echo $id = $this->input->post('id');
                die;
                $this->modelacuerdodepagojuridico_model->verificaGarantia();
                $this->modelacuerdodepagojuridico_model->eliminacamposeditadosadmingarantia($id);
                break;
        }

//       eliminacampos 
    }

    function acuerdo() {
        $nit = $this->input->post('nit');
        $liquidacion = $this->input->post('liquidacion');
        $acuerdo = $this->input->post('acuerdo');
        $cod_coactivo = $this->input->post('cod_coactivo');
        $decision = $this->input->post('selectMora');
        $carpeta = 'docaceptacion';
        $docFile = $this->do_upload($nit, $acuerdo, $carpeta);

        if ($decision == 2) {
            $tipogestion = 514;
            $tiporespuesta = 1273;
            $comentarios = 'El Cliente No es Moroso';
            $data = array(
                'DOC_ACEPTACION' => $docFile["upload_data"]["file_name"],
                'COD_RESPUESTA' => $tiporespuesta,
                'ID_USUARIO_AUTORIZA' => ID_USER,
            );
        }

        trazarProcesoJuridico($tipogestion, $tiporespuesta, '', $cod_coactivo, '', '', '', $comentarios = $comentarios, $usuariosAdicionales = '');
        $documentoresolucion = $this->modelacuerdodepagojuridico_model->acuerdoAprobado($data, $acuerdo);

        if ($documentoresolucion == true) {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Documento Subido correctamente.</div>');
            redirect(base_url() . 'index.php/bandejaunificada/procesos');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Documento no se ha adjuntado correctamente.</div>');
            redirect(base_url() . 'index.php/bandejaunificada/procesos');
        }
    }

    function buscar_empresa() {
        $empresa = $this->input->get("term");
        $consulta = $this->modelacuerdodepagojuridico_model->consultar_empresa($empresa);
        $temp = NULL;
        if (!is_null($consulta)) {
            foreach ($consulta as $datos) {
                $temp[] = array("value" => $datos['NUM_LIQUIDACION'], "label" => $datos['CODEMPRESA'] . " - " . $datos['RAZON_SOCIAL']);
            }
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($temp));
    }

    function traerempresa() {
        $liquidacion = $this->input->post('liquidacion');
        $result = $this->modelacuerdodepagojuridico_model->consultar_empresa($liquidacion);

        if (!empty($result)) :
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
        endif;
    }

    function acuerdomora() {
        if ($this->ion_auth->logged_in()) {
//            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepagojuridico/gestionarAcuerdo')) {

                $this->data[] = array();
                $this->template->set('title', 'Autorizacion Facilidad de Pago Juridico');
                $this->data['title'] = 'Facilidad de pago juridico';
                $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
                $this->data['nit'] = $this->input->post('nit');
                $this->load->library('datatables');
                $this->data['user'] = $this->ion_auth->user()->row();
                $this->data['message'] = $this->session->flashdata('message');
                $this->load->model('modelacuerdodepagojuridico_model');
                $dato = 2;
//               ADMINISTRATIVO 
                if ($this->data['grupo'] == $this->data['abogadoj'])
                    $acuerdo = 1;
//               JURIDICO  
                if ($this->data['grupo'] == $this->data['abogado'])
                    $acuerdo = 2;
//               ADMINISTRADOR  
                if ($this->data['grupo'] == $this->data['administrador'])
                    $acuerdo = 3;
                
                $this->data['acuerdomoramenor'] = $this->modelacuerdodepagojuridico_model->acuerdomoramenor(@$acuerdo, $dato);
                $this->data['acuerdomoramayor'] = $this->modelacuerdodepagojuridico_model->acuerdomoramayor(@$acuerdo,  $this->data['cod_coactivo']); 
                
              
                $this->data['autorizaciones'] = $this->modelacuerdodepagojuridico_model->consultaautorizaciones(@$acuerdo);
                $this->template->load($this->template_file, 'acuerdodepagojuridico/acuerdomora', $this->data);
//            } else {
//                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
//                redirect(base_url() . 'index.php/inicio');
//            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function adjuntar() {
        $this->data['post'] = $this->input->post();
        $post = $this->data['post'];
        $this->data['acuerdo'] = $this->input->post('acuerdo');
        $this->data['estado'] = $this->input->post('estado');
        $this->data['fecha'] = $this->input->post('fecha');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['id'] = $this->input->post('id');
        $this->data['liquidacion'] = $this->input->post('liquidacion');
        $this->data['razon'] = $this->input->post('razon');
        $this->data['nit'] = $this->input->post('nit');
        $this->data['cod_estado'] = $this->input->post('cod_estado');
        $this->data['documentos'] = $this->input->post('documentos');

        $this->load->view('acuerdodepagojuridico/acuerdodepago_up', $this->data);
    }

    function acuerdoenviodocumentocancelacion30() {
        $acuerdo = $this->input->post('acuerdo');
        $dato = 1;
        if (empty($acuerdo))
            $acuerdomoramenor = $this->modelacuerdodepagojuridico_model->acuerdomoramenor($dato);
        else
            $acuerdomoramenor = $this->modelacuerdodepagojuridico_model->acuerdomoramenor($dato, $acuerdo);

        $this->data['totalacuerdos'] = $acuerdomoramenor;

        foreach ($acuerdomoramenor->result_array as $cancelacion) {
            $this->documentocancelacion30dias($cancelacion['NITEMPRESA'], $cancelacion['RAZON_SOCIAL'], $cancelacion['NRO_ACUERDOPAGO'], $cancelacion['PROYACUPAG_NUMCUOTA'], $acuerdo);
        }
    }

    function documentocancelacion30dias($nit, $razon, $acuerdo, $cuota, $existencia) {
        $this->data['existenacia'] = $existencia;
        if (empty($existencia)) {
            $actualizacion = $this->modelacuerdodepagojuridico_model->documentoacuerdoenviado($acuerdo, $cuota);
            $this->data['actualizacion'] = $actualizacion;
        }
        $this->data['moramenor'] = $this->modelacuerdodepagojuridico_model->cronmora($acuerdo, $cuota);
        $this->load->model('numeros_letras_model');
        $info = $this->numeros_letras_model->ValorEnLetras($this->data['moramenor']->result_array[0]['PROYACUPAG_CAPITALDEBE'], "PESOS");
        $this->data['info'] = $info;
        $this->load->view('acuerdodepagojuridico/documentocancelacion30dias', $this->data);
    }

    function documentacion() {
        $post = $this->input->post();
        $documentos = "";
        $input = "";
        if ($post['resolucion'] == 'true') {
            $datos_resolucion = $this->ejecutoriaactoadmin_model->datos_resolucion($post['id']);
            $documentos.=$datos_resolucion['tabla'];
            $input.=$datos_resolucion['input'];
        }
        if ($post['citacion'] == 'true') {
            $datos_citacion = $this->ejecutoriaactoadmin_model->datos_citacion($post['id']);
            $documentos.=$datos_citacion['tabla'];
            $input.=$datos_citacion['input'];
        }
        if ($post['recurso'] == 'true') {
            $datos_recurso = $this->ejecutoriaactoadmin_model->datos_recurso($post['id']);
            $documentos.=$datos_recurso['tabla'];
            $input.=$datos_recurso['input'];
        }
        if ($post['ejecutoria'] == 'true') {
            $datos_ejecutoria = $this->ejecutoriaactoadmin_model->datos_ejecutoria($post['cod_coactivo']);
            $documentos.=$datos_ejecutoria['tabla'];
            $input.=$datos_ejecutoria['input'];
        }
        if ($post['revocatoria'] == 'true') {
            $datos_revocatoria = $this->ejecutoriaactoadmin_model->datos_revocatoria($post['id'], $post['cod_coactivo']);
            $documentos.=$datos_revocatoria['tabla'];
            $input.=$datos_revocatoria['input'];
        }
        if ($post['liquidacion'] == 'true') {
            $datos_liquidacion = $this->ejecutoriaactoadmin_model->datos_liquidacion($post['id'], $post['cod_coactivo']);
            $documentos.=$datos_liquidacion['tabla'];
            $input.=$datos_liquidacion['input'];
        }

//        echo  $documentos;
//        echo json_encode(array('total' => "2",'input' => "1"));
        return $this->output->set_content_type('appliation/json')->set_output(json_encode(array('total' => $documentos, 'input' => $input)));
    }

    function documento_juridico() {
        $this->data['post'] = $this->input->post();
        $post = $this->data['post'];
        $guardar = $this->do_upload($post['nit'], $post['acuerdo'], 'coactivo');
        $this->data['user'] = $this->ion_auth->user()->row();
        $tipogestion = 426;
        $tiporespuesta = 1112;
        $comentario = 'envio a coactivo aprobado';
        $cod_respuesta = trazarProcesoJuridico($tipogestion, $tiporespuesta, '', $cod_coactivo, '', '', '', $comentarios = $comentario, $usuariosAdicionales = '');
        $this->ejecutoriaactoadmin_model->subir_archivo($this->data['post'], $guardar, $this->data['user'], $cod_respuesta['COD_TRAZAPROCJUDICIAL']);
        $tipo = NULL;
        $data = array(
            'COD_RESPUESTA' => $tiporespuesta,
        );
        $insert = $this->modelacuerdodepagojuridico_model->actualizar_acuerdo($data, $post['acuerdo'], $tipo);
        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo se ha Modificado correctamente.</div>');
        redirect(base_url() . 'index.php/bandejaunificada/procesos');
//        header("location:" . base_url('index.php/ejecutoriaactoadmin/'));
        //echo "<script>window.history.back()</script>";
    }

    function administraciongarantias() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepagojuridico/gestionarAcuerdo')) {
                $this->data[] = array();
                $this->template->set('title', 'Garantias');
                $this->data['title'] = 'Garantias';

                $this->data['message'] = $this->session->flashdata('message');
                $this->load->model('modelacuerdodepagojuridico_model');
                $this->data['historiagarantia'] = $this->modelacuerdodepagojuridico_model->administraciongarantiascampos();
                $this->template->load($this->template_file, 'acuerdodepagojuridico/administraciongarantias', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function tableacuerdopago() {
        $identificacion = $this->input->post('nit');
        $ejecutoria = $this->input->post('ejecutoria');
        $concepto = $this->input->post('concepto');
        $this->data['ncuotas'] = $this->input->post('cuotas');
        $cuotas = $this->input->post('cuotas');
        $this->data['estado'] = $estado = $this->input->post('estado');
        $this->data['cod_coactivo'] = $cod_coactivo = $this->input->post('cod_coactivo');
        $this->data['juridico'] = $this->input->post('juridico');

        if ($this->data['juridico'] == 4 || $this->data['juridico'] == 5) {
            $this->data['concepto'] = $this->modelacuerdodepagojuridico_model->consultatipoconcepto($concepto);
            $acuerdo_juridico = $this->modelacuerdodepagojuridico_model->consultarDeudas($cod_coactivo, $estado);
            $deuda = $acuerdo_juridico[0]['TOTAL_DEUDA'];
            if ($deuda == ''):
                @$this->data['saldocapital'] = 0;
                @$this->data['interesCorriente'] = 0;
            else:
                @$this->data['saldocapital'] = $acuerdo_juridico[0]['TOTAL_CAPITAL'];
                @$this->data['interesCorriente'] = $acuerdo_juridico[0]['TOTAL_INTERES'];
            endif;
            $fecha_hoy = date("d/m/Y");
            $tasaEfectiva = $this->modelacuerdodepagojuridico_model->tasaEfectiva($fecha_hoy);
            $tasamul = ($tasaEfectiva[0]['TASA_SUPERINTENDENCIA']) / 100;
            $tasaperiodica = pow((1 + $tasamul), (1 / 12)) - 1;
            $this->data['tasa'] = $tasaperiodica;
            $porcentaje = $this->modelacuerdodepagojuridico_model->cuotainicial();
            $this->data['porcentaje'] = $porcentaje[0]['PORCENTAJE'] / 100;
            $saldoInicial = $this->data['saldocapital'] - ($this->data['saldocapital'] * ($porcentaje[0]['PORCENTAJE'] / 100));
            $valorcuotaFija = ($saldoInicial * ($tasaperiodica * pow(1 + $tasaperiodica, $this->data['ncuotas']))) / (pow((1 + $tasaperiodica), $this->data['ncuotas']) - 1);
            $this->data['valorcuota'] = $valorcuotaFija;
            $cuotainicial = $this->modelacuerdodepagojuridico_model->cuotainicial();
            $this->data['nit'] = $this->input->post('nit');
            $this->data['razonsocial'] = $this->input->post('razonsocial');
            $this->data['finicial'] = date('d-m-Y');
            $this->data['fpcuota'] = date("d/m/Y", strtotime($this->data['finicial'] . "+1 month"));
            $this->data['vinicuota'] = ($this->data['saldocapital'] * $cuotainicial[0]['PORCENTAJE']) / 100;
            $this->data['intCorrienteCuotaCero'] = $acuerdo_juridico[0]['TOTAL_INTERES'];
            $this->data['interescalc'] = @$this->data['interesCorriente'] - $this->data['intCorrienteCuotaCero'];
            $this->data['intCorrienteCuota'] = $this->data['interescalc'] / $this->data['ncuotas'];
            if ($this->data['juridico'] == 4):
                $this->load->view('acuerdodepagojuridico/tableacuerdopago2', $this->data);
            elseif ($this->data['juridico'] == 5):
                $this->load->view('acuerdodepagojuridico/tablerecibo', $this->data);
            endif;
        } else {
            $this->data['valorcuota'] = $this->input->post('valorcuota');
            $this->data['fpcuota'] = $this->input->post('fpcuota');
            $this->data['finicial'] = $this->input->post('finicial');
            $this->data['vinicuota'] = $this->input->post('vinicuota');
            $this->data['ncuotas'] = $this->input->post('ncuotas');
            $this->data['saldocapital'] = $this->input->post('saldocapital');
            $this->data['fecha_hoy'] = $fecha_hoy = date("d/m/Y");
            $tasaEfectiva = $this->modelacuerdodepagojuridico_model->tasaEfectiva($fecha_hoy);
            $tasamul = ($tasaEfectiva[0]['TASA_SUPERINTENDENCIA']) / 100;
            $tasaperiodica = pow((1 + $tasamul), (1 / 12)) - 1;
            $this->data['tasa'] = $tasaperiodica;
            $this->data['juridico'] = $this->input->post('juridico');
            $this->data['valorcapitalfecha'] = $this->input->post('valorcapitalfecha');
            $this->data['valorinteresesalafecha'] = $this->input->post('valorinteresesalafecha');
            $this->data['garantia'] = $this->input->post('garantia');
            $this->data['vcuota'] = $this->input->post('vcuota');
            $this->data['nit'] = $this->input->post('nit');
            $this->data['razonsocial'] = $this->input->post('razonsocial');
            $this->load->view('acuerdodepagojuridico/tableacuerdopago', $this->data);
        }
    }

    function recursojuridico() {
        $nit = $this->input->post('nit');
        $liquidacion = $this->input->post('liquidacion');
        $resolucion = $this->input->post('resolucion');
        $observacion = $this->input->post('observacion');
        $acuerdo = $this->input->post('acuerdo');
        $cuota = $this->input->post('cuota');
        $documento = $_FILES["archivo"]["name"];

        if ($_FILES["archivo"]["error"] > 0) {
            echo $_FILES["archivo"]["error"] . "";
        } else {
            if (file_exists("uploads/cartalegalizacionaceptar/" . $acuerdo . "/" . $_FILES["archivo"]["name"])) {
                echo $_FILES["archivo"]["name"] . " ya existe. ";
            } else {
                if (is_dir('uploads/cartalegalizacionaceptar/' . $acuerdo)) {
                    
                } else {
                    mkdir('uploads/cartalegalizacionaceptar/' . $acuerdo);
                }
                // Si no es un archivo repetido y no hubo ningun error, procedemos a subir a la carpeta /archivos, seguido de eso mostramos la imagen subida
                move_uploaded_file($_FILES["archivo"]["tmp_name"], "uploads/cartalegalizacionaceptar/" . $acuerdo . "/" . $_FILES["archivo"]["name"]);
            }
        }
        $data = array(
            "CODEMPRESA" => $nit,
            "NRO_LIQUIDACION" => $liquidacion,
            "OBSERVACIONES" => $observacion,
            "NRO_ACUERDOPAGO" => $acuerdo,
            "DOCUMENTO" => $documento,
            "CUOTA" => $cuota
        );

        $this->load->model('Modelacuerdodepago_model');
        $reproceso = $this->Modelacuerdodepago_model->reproceso($data);

        redirect(base_url() . 'index.php/acuerdodepagojuridico/acuerdomora');
    }

    function tablagarantias() {

        $tipodeudor = $this->input->post('tipodeudor');
        $garantia = $this->input->post('garantia');

        if (!empty($garantia)) {
            $existencia = $this->modelacuerdodepagojuridico_model->consultaexistenciagarantia($garantia);
            if (!empty($existencia)) {
                $this->data['tipoexistente'] = 'El Tipo de Garantía ya Existe o esta Inactivo';
            } else {
                $this->modelacuerdodepagojuridico_model->insertargarantias($garantia, $tipodeudor);
                $this->data['tipoexistente'] = 'Se ha Guardado Con Exito';
            }
        } else {
            $this->data['tipoexistente'] = 1;
        }

        $this->data['garantias'] = $this->modelacuerdodepagojuridico_model->garantiasCrear();
        $this->load->view('acuerdodepagojuridico/tablagarantias', $this->data);
    }

    function tablacuantias() {
        $mayora = $this->input->post('mayora');
        $menora = $this->input->post('menora');
        $datos = array(
            "HASTA" => $mayora,
            "DESDE" => $menora,
            "PORCENTAJE" => 0,
            "PLAZOMESES" => $this->input->post('plazo'),
            "TIPO_ACUERDO" => $this->input->post('tipo')
        );

        $respuesta = $this->modelacuerdodepagojuridico_model->guardarcuantia($datos);
        $tablacuantias = $this->modelacuerdodepagojuridico_model->tablacuantias();
        $this->output->set_content_type('application/json')->set_output(json_encode($tablacuantias->result_array));
    }

    function totalgarantias() {
        $function = $this->modelacuerdodepagojuridico_model->administraciongarantiascampos();

        $this->output->set_content_type('application/json')->set_output(json_encode($function->result_array));
    }

    function exportaExcel() {
        //echo utf8_decode($_POST['datos_envio']);
        $post = $this->input->post();
        $fecha = date('Ymd-hi');
        $cRuta = "./uploads/acuerdodepagojuridico/proyeccion/";
        if (!is_dir($cRuta)) {
            mkdir($cRuta, 0777, true);
        }
        $PHPExcel = new PHPExcel();
        $PHPExcel->getProperties()->setCreator("SENA")->setTitle("FACILIDAD DE PAGO");
        $PHPExcel->setActiveSheetIndex(0);
        $PHPExcel->getActiveSheet()->setTitle('FACILIDAD DE PAGO');

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'FACILIDAD DE PAGO');
        $PHPExcel->getActiveSheet()->setCellValue('A2', 'FECHA GENERACION: ' . $fecha);

        $PHPExcel->getActiveSheet()->setCellValue('A5', 'PERIODO');
        $PHPExcel->getActiveSheet()->setCellValue('B5', 'FECHA');
        $PHPExcel->getActiveSheet()->setCellValue('C5', 'SALDO A CAPITAL');
        $PHPExcel->getActiveSheet()->setCellValue('D5', 'INTERESES CORRIENTE');
        $PHPExcel->getActiveSheet()->setCellValue('E5', 'INTERESES CORRIENTE CUOTA');
        $PHPExcel->getActiveSheet()->setCellValue('F5', 'INTERESES ACUERDO');
        $PHPExcel->getActiveSheet()->setCellValue('G5', 'VALOR CUOTA');
        $PHPExcel->getActiveSheet()->setCellValue('H5', 'APORTE A CAPITAL');
        $PHPExcel->getActiveSheet()->setCellValue('I5', 'SALDO FINAL');
        $x = 6;

        for ($i = 0; $i < count($post['cuota']); $i++) {
            $PHPExcel->getActiveSheet()->setCellValue('A' . $x, $post['cuota'][$i]);
            $PHPExcel->getActiveSheet()->setCellValue('B' . $x, $post['fecha'][$i]);
            $PHPExcel->getActiveSheet()->setCellValue('C' . $x, "$ " . number_format($post['saldocapital'][$i]));
            $PHPExcel->getActiveSheet()->setCellValue('D' . $x, "$ " . number_format($post['intCorriente'][$i]));
            $PHPExcel->getActiveSheet()->setCellValue('E' . $x, "$ " . number_format($post['intCorrienteCuota'][$i]));
            $PHPExcel->getActiveSheet()->setCellValue('F' . $x, "$ " . number_format($post['interespormora'][$i]));
            $PHPExcel->getActiveSheet()->setCellValue('G' . $x, "$ " . number_format($post['valorcuota'][$i]));
            $PHPExcel->getActiveSheet()->setCellValue('H' . $x, "$ " . number_format($post['aportecapital'][$i]));
            $PHPExcel->getActiveSheet()->setCellValue('I' . $x, "$ " . number_format($post['saldofinal'][$i]));
            $x++;
        }

        header("Content-type: application/vnd.ms-excel-application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment;filename="' . $fecha . ".xlsx" . '"');
//        header('Content-Description: File Transfer');
//        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header('Content-Disposition: attachment;filename="' . $fecha . ".xlsx" . '"');
        //header('Cache-Control: max-age=0');        
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save("./uploads/acuerdodepagojuridico/proyeccion/" . $fecha . ".xlsx");
        ob_clean();
        $objWriter->save("php://output");

        //die();
    }

    function enviaCorreo() {
        $para = $this->input->post('correo');
        $asun = $this->input->post('asunto');
        $mensaje = $this->input->post('cuerpo');
        enviarcorreosena($para, $mensaje, $asun);
    }

    function calculo($capital) {
        
    }

    function consultaTasa() {
        $cuotas = $this->input->post('cuotas');
        $saldoInicial = $this->input->post('proyectar');
        $fecha_hoy = date("d/m/Y");
        $tasaEfectiva = $this->modelacuerdodepagojuridico_model->tasaEfectiva($fecha_hoy);
        $tasamul = ($tasaEfectiva[0]['TASA_SUPERINTENDENCIA']) / 100;
        $tasaperiodica = pow((1 + $tasamul), (1 / 12)) - 1;
        $porcentaje = $this->modelacuerdodepagojuridico_model->cuotainicial();
        //$saldoInicial               = $saldoInicial-($saldoInicial*($porcentaje[0]['PORCENTAJE']/100));
        $porcentajeTasa = $porcentaje[0]['PORCENTAJE'] / 100;
        $saldoInicial = $saldoInicial - ($saldoInicial * ($porcentajeTasa));
        $valorcuotaFija['VALOR'] = ($saldoInicial * ($tasaperiodica * pow(1 + $tasaperiodica, $cuotas))) / (pow((1 + $tasaperiodica), $cuotas) - 1);
        $tasa['tasaInteres'] = $tasamul;
        $datos = array('cuota' => $valorcuotaFija['VALOR'], 'tasa' => $tasa['tasaInteres']);
        $this->output->set_content_type('application/json')->set_output(json_encode($datos));
        //$this->output->set_content_type('application/json')->set_output(json_encode($valorcuotaFija['VALOR']));                
    }

    function trazabilidad($tipogestion, $tiporespuesta, $cod_coactivo, $nit, $mensaje) {
        $this->datos['idgestioncobro'] = trazar($tipogestion, $tiporespuesta, $cod_coactivo, $nit, $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios = $mensaje);
        $this->datos['idgestion'] = $this->datos['idgestioncobro']['COD_GESTION_COBRO'];
        return $this->datos['idgestion'];
    }

    function crearAcuerdo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepagojuridico/gestionarAcuerdo')) {
                $this->data['acuerdo'] = $this->input->post('acuerdo');
                $cod_coactivo = $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
                $estado = $this->data['estado'] = $this->input->post('estado');
                $this->data['fecha'] = $this->input->post('fecha');
                $this->data['nit'] = $this->input->post('nit');
                $this->data['razon'] = $this->input->post('razon');
                $this->template->set('title', 'Facilidad de pago');
                $this->data['title'] = 'Autorizacion Facilidad de Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $acuerdo_juridico = $this->modelacuerdodepagojuridico_model->consultarDeudas($cod_coactivo, $estado);
                $deuda = $acuerdo_juridico[0]['TOTAL_DEUDA'];
                $this->data['acuerdosautorizar'] = $this->modelacuerdodepagojuridico_model->consultaautorizacionesacuerdo(REGIONAL_USER);

                //$this->template->load($this->template_file, 'acuerdodepagojuridico/documentoautorizacionacuerdo', $this->data);
                $this->load->view('acuerdodepagojuridico/documentoautorizacionacuerdo', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function crearResolucion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepagojuridico/gestionarAcuerdo')) {
                $this->data['acuerdo'] = $this->input->post('acuerdo');
                $estado = $this->data['estado'] = $this->input->post('estado');
                $this->data['fecha'] = $this->input->post('fecha');
                $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
                $this->data['liquidacion'] = $this->input->post('liquidacion');
                $this->data['nit'] = $this->input->post('nit');
                $this->data['razon'] = $this->input->post('razon');
                $this->data['resolucion'] = $this->input->post('resolucion');
                $this->data['ajustado'] = $this->input->post('ajustado');

                $estado = $this->data['estado'];
                switch ($estado) {
                    case 1274:
                        $this->data['titulo'] = 'Resolución por la cual se concede una facilidad para el pago';
                        $valor = 122;
                        break;
                    case 16:
                        $this->data['titulo'] = 'Resolución por la cual se concede una facilidad para el pago';
                        $valor = 122;
                        break;
                    case 1496:
                        $this->data['titulo'] = 'Comunicación de Resolución por la cual se concede una facilidad para el pago';
                        $valor = 122;
                        break;
                    case 1400:
                        $this->data['titulo'] = 'Comunicación de Resolución por la cual se concede una facilidad para el pago';
                        $valor = 122;
                        break;
                    case 1399:
                        $this->data['titulo'] = 'Notificación por Correo de Resolución por la cual se concede una facilidad para el pago';
                        $valor = 122;
                        break;
                    case 1407:
                        $this->data['titulo'] = 'Notificación por Correo de Resolución por la cual se concede una facilidad para el pago';
                        $valor = 122;
                        break;
                    case 1406:
                        $this->data['titulo'] = 'Notificacion pagina web de Resolucion que legaliza Facilidad de pago';
                        $valor = 122;
                        break;
                    case 1265:
                        $this->data['titulo'] = 'Notificación Personal de garantías';
                        $valor = 122;
                        break;
                    case 1503:
                        $this->data['titulo'] = 'Notificación Personal de garantías';
                        $valor = 122;
                        break;
                }
                $plantilla = $this->modelacuerdodepagojuridico_model->plantilla($valor);
                $this->data['nombre_plantilla'] = $plantilla;
                $datoAcuerdo = $this->modelacuerdodepagojuridico_model->consulta_acuerdo($this->data['acuerdo']);
                $proy = $this->modelacuerdodepagojuridico_model->consultacuotasacuerdopago($this->data['acuerdo']);
                $i = 0;
                $saldo = 0;
                $cuota = 0;
                $table = "<table width='550' align='center' border='1'>";
                $table .="<tbody>";
                $table .="<tr><td><p><strong>No Cuota.</strong><p></td>
                                  <td><p><strong>Fecha de Pago</strong><p></td>
                                  <td><p><strong>Intereses</strong><p></td>
                                  <td><p><strong>Aporte a Capital</strong><p></td>
                                  <td><p><strong>Valor Cuota</strong><p></td>";
                foreach ($proy->result_array as $datos) {
                    $table .= "<tr>";
                    $table .= "<td>" . $datos['PROYACUPAG_NUMCUOTA'] . "</td>";
                    $table .= "<td>" . $datos['PROYACUPAG_FECHALIMPAGO'] . "</td>";
                    $table .= "<td>" . $datos['PROYACUPAG_SALDO_INTACUERDO'] . "</td>";
                    $table .= "<td>" . $datos['PROYACUPAG_CAPITAL_CUOTA'] . "</td>";
                    $table .= "<td>$ " . $datos['PROYACUPAG_VALORCUOTA'] . "</td>";
                    $table .="</tr>";
                    $saldo = $datos['PROYACUPAG_VALORCUOTA'] + $saldo;
                    $cuota++;
                }
                $table .="<tr><td colspan='4' align='right'>Saldo Total</td><td>$ " . $saldo . "</td></tr>";
                $table .="</tbody>";
                $table .="</table>";
                $datos = array(
                    'numResolucion' => $datoAcuerdo[0]['NRO_RESOLUCION'],
                    'razon' => $this->data['razon'],
                    'nit' => $this->data['nit'],
                    'deuda' => $datoAcuerdo[0]['VALOR_CAPITAL_FECHA'] + $datoAcuerdo[0]['VALO_RINTERESES_CAPITAL'],
                    'table' => $table,
                    'saldo' => $saldo,
                    'cuota' => $cuota,
                    'primerCuota' => @$datos[0]['PROYACUPAG_FECHALIMPAGO']
                );
                $this->data['plantilla'] = template_tags("./uploads/plantillas/" . $plantilla, $datos);
                $this->load->view('acuerdodepagojuridico/crearResolucion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function editarResolucion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepagojuridico/gestionarAcuerdo')) {
                $this->data['acuerdo'] = $this->input->post('acuerdo');
                $estado = $this->data['estado'] = $this->input->post('estado');
                $this->data['fecha'] = $this->input->post('fecha');
                $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
                $this->data['nit'] = $this->input->post('nit');
                $this->data['razon'] = $this->input->post('razon');
                $this->data['ajustado'] = $this->input->post('ajustado');
                $this->data['proceso'] = $this->acuerdopagodocjuridico_model->get_plantilla_proceso($this->data['cod_coactivo']);
                $this->data['comentario'] = $this->acuerdopagodocjuridico_model->get_comentarios_proceso($this->data['cod_coactivo']);

                if ($this->data['proceso']['NOMBRE_DOCUMENTO'] != "") {
                    $cFile = "uploads/acuerdodepagojuridico/resolucionLegaliza/" . $this->data['cod_coactivo'];
                    $cFile .= "/" . $this->data['proceso']['NOMBRE_DOCUMENTO'];
                    $this->data['plantilla'] = read_template($cFile);
                }

                switch ($estado) {
                    case 1390:
                        $this->data['titulo'] = 'Resolución por la cual se concede una facilidad para el pago';
                        break;
                    case 1391:
                        $this->data['titulo'] = 'Resolución por la cual se concede una facilidad para el pago';
                        break;
                    case 1392:
                        $this->data['titulo'] = 'Resolución por la cual se concede una facilidad para el pago';
                        break;
                    case 1393:
                        $this->data['titulo'] = 'Resolución por la cual se concede una facilidad para el pago';
                        break;
                }

                $this->load->view('acuerdodepagojuridico/editarResolucion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function editarNotificacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepagojuridico/gestionarAcuerdo')) {
                $this->data['acuerdo'] = $this->input->post('acuerdo');
                $estado = $this->data['estado'] = $this->input->post('estado');
                $this->data['fecha'] = $this->input->post('fecha');
                $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
                $this->data['liquidacion'] = $this->input->post('liquidacion');
                $this->data['nit'] = $this->input->post('nit');
                $this->data['razon'] = $this->input->post('razon');
                $this->data['resolucion'] = $this->input->post('resolucion');
                $this->data['ajustado'] = $this->input->post('ajustado');
                $this->data['proceso'] = $this->acuerdopagodocjuridico_model->get_plantilla_proceso($this->data['cod_coactivo']);
                $this->data['comentario'] = $this->acuerdopagodocjuridico_model->get_comentarios_proceso($this->data['cod_coactivo']);
                $this->data['motivos'] = $this->modelacuerdodepagojuridico_model->motivos();

                if ($this->data['proceso']['NOMBRE_DOCUMENTO'] != "") {
                    $cFile = "uploads/acuerdodepagojuridico/resolucionLegaliza/" . $this->data['cod_coactivo'];
                    $cFile .= "/" . $this->data['proceso']['NOMBRE_DOCUMENTO'];
                    $this->data['plantilla'] = read_template($cFile);
                }

                switch ($estado) {
                    case 1390:
                        $this->data['titulo'] = 'Resolución por la cual se concede una facilidad para el pago';
                        break;
                    case 1394:
                        $this->data['titulo'] = 'Comunicación - Resolución por la cual se concede una facilidad para el pago';
                        break;
                    case 1395:
                        $this->data['titulo'] = 'Comunicación - Resolución por la cual se concede una facilidad para el pago';
                        break;
                    case 1396:
                        $this->data['titulo'] = 'Comunicación - Resolución por la cual se concede una facilidad para el pago';
                        break;
                    case 1397:
                        $this->data['titulo'] = 'Comunicación - Resolución por la cual se concede una facilidad para el pago';
                        break;
                    case 1401:
                        $this->data['titulo'] = 'Comunicación por Correo de Resolución por la cual se concede una facilidad para el pago';
                        $valor = 122;
                        break;
                    case 1402:
                        $this->data['titulo'] = 'Comunicación por Correo de Resolución por la cual se concede una facilidad para el pago';
                        $valor = 122;
                        break;
                    case 1403:
                        $this->data['titulo'] = 'Comunicación por Correo de Resolución por la cual se concede una facilidad para el pago';
                        $valor = 122;
                        break;
                    case 1404:
                        $this->data['titulo'] = 'Comunicación por Correo de Resolución por la cual se concede una facilidad para el pago';
                        $valor = 122;
                        break;
                    case 1408:
                        $this->data['titulo'] = 'Comunicación por Pagina Web de Resolución por la cual se concede una facilidad para el pago';
                        $valor = 122;
                        break;
                    case 1409:
                        $this->data['titulo'] = 'Comunicación por Pagina Web de Resolución por la cual se concede una facilidad para el pago';
                        $valor = 122;
                        break;
                    case 1497:
                        $this->data['titulo'] = 'Comunicación Personal de garantías';
                        $valor = 122;
                        break;
                    case 1498:
                        $this->data['titulo'] = 'Comunicación Personal de garantías';
                        $valor = 122;
                        break;
                    case 1499:
                        $this->data['titulo'] = 'Comunicación Personal de garantías';
                        $valor = 122;
                        break;
                    case 1500:
                        $this->data['titulo'] = 'Comunicación Personal de garantías';
                        $valor = 122;
                        break;
                }

                $this->load->view('acuerdodepagojuridico/editarNotificacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardarResolucion() {
        $acuerdo = $this->input->post('acuerdo');
        $cod_coactivo = $this->input->post('cod_coactivo');
        $nit = $this->input->post('nit');
        $estado = $this->input->post('estado');
        @$adicionales = $this->input->post('adicionales');
        @$onbase = $this->input->post('onbase');
        @$fechanotificacion = $this->input->post('fechanotificacion');
        @$devolucion = $this->input->post('devolucion');
        @$motivo = $this->input->post('motivo');
        @$file = $this->input->post('file');
        $fechanotificacionefectiva=$this->input->post('notificacion_efectiva');
        $cRuta = "./uploads/acuerdodepagojuridico/resolucionLegaliza/" . $cod_coactivo . "/";
        if (!is_dir($cRuta)) {
            mkdir($cRuta, 0777, true);
        }
        $sFile = create_template($cRuta, $this->input->post('notificacion'));

        $usuario_asignado = $this->modelacuerdodepagojuridico_model->consulta_acuerdo($acuerdo);
        $id_abogado = $usuario_asignado[0]['USUARIO_GENERA'];
        $datos["COD_PROCESO_COACTIVO"] = $this->input->post('cod_coactivo');
        $datos["COD_RESPUESTA"] = $this->input->post('estado');
        $datos["NOMBRE_DOCUMENTO"] = $sFile;
        $datos["ABOGADO"] = ID_USER;
        $datos["EJECUTOR"] = ID_COORDINADOR;
        $datos["COMENTADO_POR"] = ID_USER;
        $datos["COMENTARIO"] = $this->input->post('comentarios');
        $datos["TITULO_ENCABEZADO"] = $this->input->post('Titulo_Encabezado');
        $datos["RADICADO_ONBASE"] = $onbase;
        if ($fechanotificacion != '') {
            $datos["FECHA_ONBASE"] = $fechanotificacion;
        }
        

//        echo $id_abogado."<br>";
//        echo $estado."<br>";die;
        switch ($estado) {
            case 16:
                $tiporespuesta = 1390; //Resolucion que legaliza el acuerdo de pago Creado
                $tipogestion = 657;
                $comentarios = 'Resolucion que legaliza el acuerdo de pago Creado';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1390:
                if ($adicionales == 1):
                    $tiporespuesta = 1391; //Resolucion que legaliza el acuerdo de pago pre-aprobado
                    $tipogestion = 657;
                    $comentarios = 'Resolucion que legaliza el acuerdo de pago pre-aprobado';
                    $asignado = $id_abogado;
                else:
                    $tiporespuesta = '1392'; //Resolucion que legaliza el acuerdo de pago rechazado
                    $tipogestion = 657;
                    $comentarios = 'Resolucion que legaliza el acuerdo de pago rechazado';
                    $asignado = $id_abogado;
                endif;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1391:
                if ($adicionales == 1):
                    $tiporespuesta = 1393; //Resolución Que Legaliza La Facilidad De Pago Aprobado
                    $tipogestion = 657;
                    $comentarios = 'Resolución Que Legaliza La Facilidad De Pago Aprobado';
                    $asignado = $id_abogado;
                else:
                    $tiporespuesta = 1392; //Resolucion que legaliza el acuerdo de pago rechazado
                    $tipogestion = 657;
                    $comentarios = 'Resolucion que legaliza el acuerdo de pago rechazado';
                    $asignado = $id_abogado;
                endif;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1393:
                $tipoexpediente = 3;
                $carpeta = 'acuerdopago/legalizacion';
                $docFile = $this->do_upload($nit, $cod_coactivo, $carpeta);
                $dato = $this->modelacuerdodepagojuridico_model->eliminar_plantillas($cod_coactivo);
                $asignado = ID_USER;
                $tiporespuesta = 1496; //Resolucion que legaliza el acuerdo de pago aprobado
                $tipogestion = 657;
                $comentarios = 'Resolucion que legaliza el acuerdo de pago Aprobado';
                $ruta = 'uploads/acuerdodepagojuridico/legalizacion/' . $nit . '/pdf/' . $cod_coactivo . '/' . $docFile['upload_data']['file_name'];

                $data = array(
                    'RUTA_DOCUMENTO' => $ruta,
                    'NOMBRE_DOCUMENTO' => $docFile['upload_data']['file_name'],
                    'COD_RESPUESTAGESTION' => $tiporespuesta,
                    'COD_TIPO_EXPEDIENTE' => $tipoexpediente, //resolucion
                    'ID_USUARIO' => $id_abogado,
                    'FECHA_RADICADO' => date('d/m/Y'),
                    'COD_PROCESO_COACTIVO' => $cod_coactivo,
                    'RADICADO_ONBASE' => $onbase,
                    'FECHA_ONBASE' => $fechanotificacion,
                    'FECHA_NOTIFICACION_EFECTIVA'=>$fechanotificacionefectiva    
                );

                $msj = $this->modelacuerdodepagojuridico_model->insertar_expediente($data);
                break;
            case 1392:
                $tiporespuesta = 1390; //Resolucion que legaliza el acuerdo de pago Creado
                $tipogestion = 657;
                $comentarios = 'Resolucion que legaliza la Facilidad de pago Creado';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1496:
                $tiporespuesta = 1394; //Notificacion Personal de Resolucion que legaliza acuerdo de pago generada
                $tipogestion = 658;
                $comentarios = 'Notificacion Personal de Resolucion que legaliza Facilidad de pago generada';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1400:
                $tiporespuesta = 1394; //Notificacion Personal de Resolucion que legaliza acuerdo de pago generada
                $tipogestion = 658;
                $comentarios = 'Notificacion Personal de Resolucion que legaliza Facilidad de pago generada';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1394:
                if ($adicionales == 1):
                    $tiporespuesta = 1395; //Notificacion Personal de Resolucion que legaliza acuerdo de pago pre-aprobada
                    $tipogestion = 658;
                    $comentarios = 'Notificacion Personal de Resolucion que legaliza acuerdo de pago pre-aprobada';
                    $asignado = $id_abogado;
                else:
                    $tiporespuesta = 1396; //Notificacion Personal de Resolucion que legaliza acuerdo de pago rechazada
                    $tipogestion = 658;
                    $comentarios = 'Notificacion Personal de Resolucion que legaliza acuerdo de pago rechazada';
                    $asignado = $id_abogado;
                endif;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1396:
                $tiporespuesta = 1394; //Resolucion que legaliza el acuerdo de pago Creado
                $tipogestion = 658;
                $comentarios = 'Notificacion Personal de Resolucion que legaliza acuerdo de pago generada';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1395:
                $tiporespuesta = 1397; //Notificacion Personal de Resolucion que legaliza acuerdo de pago enviada
                $tipogestion = 658;
                $comentarios = 'Notificacion Personal de Resolucion que legaliza acuerdo de pago enviada';
                $asignado = $id_abogado;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1397:
                $tipoexpediente = 7;
                if ($devolucion == 1):
                    if ($motivo == 4):
                        $tiporespuesta = 1400; //Notificacion Personal de Resolucion que legaliza acuerdo de pago devuelta por direccion erronea
                        $tipogestion = 658;
                        $comentarios = 'Notificacion Personal de Resolucion que legaliza acuerdo de pago devuelta por direccion erronea';
                    else :
                        $tiporespuesta = 1399; //Notificacion Personal de Resolucion que legaliza acuerdo de pago devuelta
                        $tipogestion = 658;
                        $comentarios = 'Notificacion Personal de Resolucion que legaliza acuerdo de pago devuelta';
                    endif;
                else:
                    $tiporespuesta = 1398; //Notificacion Personal de Resolucion que legaliza acuerdo de pago entregada
                    $tipogestion = 658;
                    $comentarios = 'Notificacion Personal de Resolucion que legaliza acuerdo de pago entregada';
                endif;
                $carpeta = 'acuerdopago/resolucionLegaliza/' . $cod_coactivo . '/';
                $docFile = $this->do_upload($nit, $cod_coactivo, $carpeta);
                $dato = $this->modelacuerdodepagojuridico_model->eliminar_plantillas($cod_coactivo);
                $asignado = ID_USER;
                $ruta = 'uploads/acuerdodepagojuridico/legalizacion/' . $nit . '/pdf/' . $cod_coactivo . '/' . $docFile['upload_data']['file_name'];

                $data = array(
                    'RUTA_DOCUMENTO' => $ruta,
                    'NOMBRE_DOCUMENTO' => $docFile['upload_data']['file_name'],
                    'COD_RESPUESTAGESTION' => $tiporespuesta,
                    'COD_TIPO_EXPEDIENTE' => $tipoexpediente, //resolucion
                    'ID_USUARIO' => $id_abogado,
                    'FECHA_RADICADO' => date('d/m/Y'),
                    'COD_PROCESO_COACTIVO' => $cod_coactivo,
                    'RADICADO_ONBASE' => $onbase,
                    'FECHA_ONBASE' => $fechanotificacion
                );

                $msj = $this->modelacuerdodepagojuridico_model->insertar_expediente($data);
                break;
            case 1399:
                $tiporespuesta = 1401; //Notificacion por correo de Resolucion que legaliza acuerdo de pago generada
                $tipogestion = 659;
                $comentarios = 'Notificacion por correo de Resolucion que legaliza Facilidad de pago generada';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1401:
                if ($adicionales == 1):
                    $tiporespuesta = 1402; //Notificacion por correo de Resolucion que legaliza acuerdo de pago pre-aprobada
                    $tipogestion = 659;
                    $comentarios = 'Notificacion por correo de Resolucion que legaliza Facilidad de pago pre-aprobada';
                    $asignado = $id_abogado;
                else:
                    $tiporespuesta = 1403; //Notificacion por correo de Resolucion que legaliza acuerdo de pago rechazada
                    $tipogestion = 659;
                    $comentarios = 'Notificacion por correo de Resolucion que legaliza Facilidad de pago rechazada';
                    $asignado = $id_abogado;
                endif;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1403:
                $tiporespuesta = 1401; //Notificacion por correo de Resolucion que legaliza acuerdo de pago generada
                $tipogestion = 659;
                $comentarios = 'Notificacion por correo de Resolucion que legaliza Facilidad de pago generada';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1402:
                $tiporespuesta = 1404; //Notificacion por correo de Resolucion que legaliza acuerdo de pago enviada
                $tipogestion = 659;
                $comentarios = 'Notificacion por correo de Resolucion que legaliza Facilidad de pago enviada';
                $asignado = $id_abogado;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1404:
                $tipoexpediente = 7;
                if ($devolucion == 1):
                    if ($motivo == 4):
                        $tiporespuesta = 1407; //Notificacion por correo de Resolucion que legaliza acuerdo de pago devuelta por direccion erronea
                        $tipogestion = 659;
                        $comentarios = 'Notificacion por correo de Resolucion que legaliza Facilidad de pago devuelta por direccion erronea';
                    else :
                        $tiporespuesta = 1406; //Notificacion por correo de Resolucion que legaliza acuerdo de pago devuelta
                        $tipogestion = 659;
                        $comentarios = 'Notificacion por correo de Resolucion que legaliza Facilidad de pago devuelta';
                    endif;
                else:
                    $tiporespuesta = 1405; //Notificacion por correo de Resolucion que legaliza acuerdo de pago entregada
                    $tipogestion = 659;
                    $comentarios = 'Notificacion por correo de Resolucion que legaliza Facilidad de pago entregada';
                endif;
                $carpeta = 'acuerdopago/legalizacion';
                $docFile = $this->do_upload($nit, $cod_coactivo, $carpeta);
                $dato = $this->modelacuerdodepagojuridico_model->eliminar_plantillas($cod_coactivo);
                $asignado = ID_USER;
                $ruta = 'uploads/acuerdodepagojuridico/legalizacion/' . $nit . '/pdf/' . $cod_coactivo . '/' . $docFile['upload_data']['file_name'];

                $data = array(
                    'RUTA_DOCUMENTO' => $ruta,
                    'NOMBRE_DOCUMENTO' => $docFile['upload_data']['file_name'],
                    'COD_RESPUESTAGESTION' => $tiporespuesta,
                    'COD_TIPO_EXPEDIENTE' => $tipoexpediente, //resolucion
                    'ID_USUARIO' => $id_abogado,
                    'FECHA_RADICADO' => date('d/m/Y'),
                    'COD_PROCESO_COACTIVO' => $cod_coactivo,
                    'RADICADO_ONBASE' => $onbase,
                    'FECHA_ONBASE' => $fechanotificacion
                );

                $msj = $this->modelacuerdodepagojuridico_model->insertar_expediente($data);
                break;
            case 1407:
                $tiporespuesta = 1401; //Notificacion por correo de Resolucion que legaliza acuerdo de pago generada
                $tipogestion = 659;
                $comentarios = 'Notificacion por correo de Resolucion que legaliza Facilidad de pago generada';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1406:
                $tiporespuesta = 1408; //Notificacion pagina web de Resolucion que legaliza acuerdo de pago generada
                $tipogestion = 660;
                $comentarios = 'Notificacion pagina web de Resolucion que legaliza Facilidad de pago generada';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1408:
                $tiporespuesta = 1409; //Notificacion pagina web de Resolucion que legaliza acuerdo de pago pre-aprobada
                $tipogestion = 660;
                $comentarios = 'Notificacion pagina web de Resolucion que legaliza Facilidad de pago pre-aprobada';
                $asignado = $id_abogado;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1409:
                $tipoexpediente = 8;
                $carpeta = 'acuerdopago/legalizacion';
                $docFile = $this->do_upload($nit, $cod_coactivo, $carpeta);
                $dato = $this->modelacuerdodepagojuridico_model->eliminar_plantillas($cod_coactivo);
                $asignado = ID_USER;
                $tiporespuesta = 1411; //Notificacion pagina web de Resolucion que legaliza acuerdo de pago enviada
                $tipogestion = 660;
                $comentarios = 'Notificacion pagina web de Resolucion que legaliza Facilidad de pago enviada';
                $ruta = 'uploads/acuerdodepagojuridico/legalizacion/' . $nit . '/pdf/' . $cod_coactivo . '/' . $docFile['upload_data']['file_name'];

                $data = array(
                    'RUTA_DOCUMENTO' => $ruta,
                    'NOMBRE_DOCUMENTO' => $docFile['upload_data']['file_name'],
                    'COD_RESPUESTAGESTION' => $tiporespuesta,
                    'COD_TIPO_EXPEDIENTE' => $tipoexpediente, //resolucion
                    'ID_USUARIO' => $id_abogado,
                    'FECHA_RADICADO' => date('d/m/Y'),
                    'COD_PROCESO_COACTIVO' => $cod_coactivo,
                    'RADICADO_ONBASE' => $onbase,
                    'FECHA_ONBASE' => $fechanotificacion
                );

                $msj = $this->modelacuerdodepagojuridico_model->insertar_expediente($data);
                break;
            case 1265:
                $tiporespuesta = 1497; //Notificación Personal de Garantías Generada  
                $tipogestion = 736;
                $comentarios = 'Notificación Personal de Garantías Generada';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1499:
                $tiporespuesta = 1497; //Notificación Personal de Garantías Generada  
                $tipogestion = 736;
                $comentarios = 'Notificación Personal de Garantías Generada';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1497:
                if ($adicionales == 1):
                    $tiporespuesta = 1498; // Notificación Personal de Garantías Pre-Aprobada
                    $tipogestion = 736;
                    $comentarios = ' Notificación Personal de Garantías Pre-Aprobada';
                    $asignado = $id_abogado;
                else:
                    $tiporespuesta = 1499; //Notificación Personal de Garantías Rechazada
                    $tipogestion = 736;
                    $comentarios = 'Notificación Personal de Garantías Rechazada';
                    $asignado = $id_abogado;
                endif;
                //echo $tiporespuesta;die;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1498:
                $tiporespuesta = 1500; //Notificación Personal de Garantías Enviada
                $tipogestion = 736;
                $comentarios = 'Notificación Personal de Garantías Enviada';
                $asignado = $id_abogado;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break;
            case 1500:
                $tipoexpediente = 7;
                if ($devolucion == 1):
                    if ($motivo == 4):
                        $tiporespuesta = 1503; //Notificación Personal de Garantías Devuelta por Dirección Erronea
                        $tipogestion = 736;
                        $comentarios = 'Notificación Personal de Garantías Devuelta por Dirección Erronea';
                    else :
                        $tiporespuesta = 1502; //Notificación Personal de Garantías Devuelta
                        $tipogestion = 736;
                        $comentarios = 'Notificación Personal de Garantías Devuelta';
                    endif;
                else:
                    $tiporespuesta = 1501; //Notificación Personal de Garantías Entregada
                    $tipogestion = 736;
                    $comentarios = 'Notificación Personal de Garantías Entregada';
                endif;
                $carpeta = 'acuerdopago/legalizacion';
                $docFile = $this->do_upload($nit, $cod_coactivo, $carpeta);
                $dato = $this->modelacuerdodepagojuridico_model->eliminar_plantillas($cod_coactivo);
                $asignado = ID_USER;
                $ruta = 'uploads/acuerdodepagojuridico/legalizacion/' . $nit . '/pdf/' . $cod_coactivo . '/' . $docFile['upload_data']['file_name'];

                $data = array(
                    'RUTA_DOCUMENTO' => $ruta,
                    'NOMBRE_DOCUMENTO' => $docFile['upload_data']['file_name'],
                    'COD_RESPUESTAGESTION' => $tiporespuesta,
                    'COD_TIPO_EXPEDIENTE' => $tipoexpediente, //resolucion
                    'ID_USUARIO' => $id_abogado,
                    'FECHA_RADICADO' => date('d/m/Y'),
                    'COD_PROCESO_COACTIVO' => $cod_coactivo,
                    'RADICADO_ONBASE' => $onbase,
                    'FECHA_ONBASE' => $fechanotificacion
                );

                $msj = $this->modelacuerdodepagojuridico_model->insertar_expediente($data);
                break;
            case 1503:
                $tiporespuesta = 1497; //Notificación Personal de Garantías Generada  
                $tipogestion = 736;
                $comentarios = 'Notificación Personal de Garantías Generada';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                break; //
        }
        //die;


        $datos2["USUARIO_ASIGNADO"] = $asignado;
        $datos2["COD_PROCESO_COACTIVO"] = $cod_coactivo;
        $datos2["COD_RESPUESTA"] = $tiporespuesta;
        $this->acuerdopagodocjuridico_model->actualizacion_acuerdopago($datos2);
        trazarProcesoJuridico($tipogestion, $tiporespuesta, '', $cod_coactivo, '', '', '', $comentarios = $this->input->post('comentarios'), $usuariosAdicionales = '');
        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo se ha Modificado correctamente.</div>');
        redirect(base_url() . 'index.php/bandejaunificada/procesos');
    }

    function eliminarcuantias() {
        $id = $this->input->post('id');
        $eliminarcuantia = $this->modelacuerdodepagojuridico_model->eliminacioncuantia($id);
        parent . window . location . reload();
    }

    function eliminargarantia() {
        $garantia = $this->input->post('garantia');
        $this->modelacuerdodepagojuridico_model->eliminagarantia($garantia);
    }

    function generarrecibodepagocuotainicial() {

        $this->data['valorcuota'] = $this->input->post('valorcuota');
        $this->data['saldofinal'] = $this->input->post('saldofinal');
        $this->data['saldocapital'] = $this->input->post('saldocapital');
        $this->data['razonsocial'] = $this->input->post('razonsocial');
        $this->data['fecha'] = $this->input->post('fecha');
        $this->data['acuerdo'] = $this->input->post('acuerdo');
        $this->data['interes'] = $this->input->post('intereses');
        $this->data['interesesCuota'] = $this->input->post('interesesCuota');

        if (empty($this->data['interes'])) {
            $this->data['interes'] = 0;
        }
        $this->data['nit'] = $this->input->post('nit');
        $this->load->view('acuerdodepagojuridico/generarrecibodepagocuotainicial', $this->data);
    }

    function gestionar_documento_juridico() {
        $this->data['post'] = $this->input->post();
        $acuerdo = $this->data['acuerdo'] = $this->input->post('acuerdo');
        $cod_coactivo = $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $estado = $this->data['estado'] = $this->input->post('estado');
        $this->data['fecha'] = $this->input->post('fecha');
        $this->data['nit'] = $this->input->post('nit');
        $this->data['razon'] = $this->input->post('razon');

        $insert = $this->modelacuerdodepagojuridico_model->incumpleAcuerdo($cod_coactivo, $estado);

        $tipogestion = 734;
        $tiporespuesta = 1493;
        $data = array(
            'COD_RESPUESTA' => $tiporespuesta
        );

        trazarProcesoJuridico($tipogestion, $tiporespuesta, '', $cod_coactivo, '', '', '', $comentarios = 'Incumplimiento de Facilidad de Pago', $usuariosAdicionales = '');
        $insert = $this->modelacuerdodepagojuridico_model->actualizar_acuerdo($data, $acuerdo, $tipo = NULL);

        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo se ha Modificado correctamente.</div>');
        redirect(base_url() . 'index.php/bandejaunificada/procesos');

        //$this->load->view('acuerdodepagojuridico/gestionar_documento_juridico', $this->data);
    }

    function generarrecibo() {
        $procedencia = $this->data['procedencia'] = $this->input->post('procedencia');
        $this->data['valorcuota'] = $this->input->post('valorcuota');
        $this->data['saldofinal'] = $this->input->post('saldofinal');
        $this->data['saldocapital'] = $this->input->post('saldocapital');
        $this->data['razon'] = $this->input->post('razon');
        $this->data['fecha'] = $this->input->post('fecha');
        $acuerdo = $this->data['acuerdo'] = $this->input->post('acuerdo');
        $this->data['interes'] = $this->input->post('intereses');
        $cod_coactivo = $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['nit'] = $this->input->post('nit');
        $estado = $this->data['estado'] = $this->input->post('estado');
        $acuerdo_juridico = $this->modelacuerdodepagojuridico_model->consultarDeudas($cod_coactivo, $estado);
        $this->data['deuda'] = $acuerdo_juridico[0]['TOTAL_DEUDA'];
        $this->data['capital'] = $acuerdo_juridico[0]['TOTAL_CAPITAL'];
        $this->data['intereses'] = $acuerdo_juridico[0]['TOTAL_INTERES'];
        $this->data['datos_recibido'] = $datos_recibido = $this->modelacuerdodepagojuridico_model->consultarAcuerdos($acuerdo, $estado);
        $this->data['porcentajeInicial'] = $this->modelacuerdodepagojuridico_model->cuotainicial();
        $fecha_hoy = date("d/m/Y");
        $tasaEfectiva = $this->modelacuerdodepagojuridico_model->tasaEfectiva($fecha_hoy);
        $this->data['superintendencia'] = $tasaEfectiva[0]['TASA_SUPERINTENDENCIA'];
        $this->data['nit'] = $this->input->post('nit');
        $this->load->view('acuerdodepagojuridico/cuotaInicial', $this->data);
    }

    function generarTotalrecibo() {
        if ($this->ion_auth->logged_in()) {
            $this->template->set('title', 'Generar Recibos');
            $this->data['title'] = 'Generar Recibos';
            $this->data['message'] = $this->session->flashdata('message');
            $this->data['razon'] = $this->input->post('razon');
            $this->data['fecha'] = $this->input->post('fecha');
            $this->data['saldoDeuda'] = $this->input->post('saldoDeuda');
            $this->data['saldoCuota'] = $this->input->post('saldoCuota');
            $this->data['saldoIntCorriente'] = $this->input->post('saldoIntCorriente');
            $this->data['saldoIntAcuerdo'] = $this->input->post('saldoIntAcuerdo');
            $this->data['aporte'] = $this->input->post('aporte');
            $this->data['acuerdo'] = $this->input->post('acuerdo');
            $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
            $this->data['liquidacion'] = $this->input->post('liquidacion');
            $this->data['nit'] = $this->input->post('nit');
            $this->data['resolucion'] = $this->modelacuerdodepagojuridico_model->numero_resolucion($this->data['cod_coactivo']);
            $this->data['resolucion'] = $this->data['resolucion'][0]['RADICADO_ONBASE'];
            $this->load->view('acuerdodepagojuridico/recibosCuota', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function generarBien($tipogestion, $tiporespuesta, $cod_coactivo, $comentarios) {
        $cod_gestioncobro = trazarProcesoJuridico($tipogestion, $tiporespuesta, '', $cod_coactivo, '', '', '', $comentarios = $comentarios, $usuariosAdicionales = '');
        $dato_inv["COD_PROCESO_COACTIVO"] = $cod_coactivo;
        $dato_inv["COD_RESPUESTAGESTION"] = $tiporespuesta;
        $dato_inv["BLOQUEO"] = 0;
        $dato_inv["COD_GESTIONCOBRO"] = $cod_gestioncobro;
        $dato_inv["GENARADO_POR"] = ID_USER;
        $this->modelacuerdodepagojuridico_model->insertar_investigacion_bienes($dato_inv);
    }

    function validaCautelares() {
        $cod_coactivo = $this->input->post('cod_coactivo');
        $acuerdo = $this->input->post('acuerdo');
        $estadoMandamiento = 204;

        $medida = $this->modelacuerdodepagojuridico_model->validaMedida($cod_coactivo);

        if ($medida == false):
            $tipogestion = 737;
            $tiporespuesta = 1506;
            $comentarios = 'Se Genero Mandamiento de pago';
            $cod_gestioncobro = $this->generarBien($tipogestion, $tiporespuesta, $cod_coactivo, $comentarios);
            $data = array('COD_RESPUESTA' => $tiporespuesta);
            $insert = $this->modelacuerdodepagojuridico_model->actualizar_acuerdo($data, $acuerdo, $tipo = NULL);
            if ($insert == true):
                $datos_mandamiento['CREADO_POR'] = ID_USER;
                $datos_mandamiento['ASIGNADO_A'] = ID_USER;
                $datos_mandamiento['COD_PROCESO_COACTIVO'] = $cod_coactivo;
                $datos_mandamiento['COD_GESTIONCOBRO'] = $cod_gestioncobro;
                $datos_mandamiento['ESTADO'] = $estadoMandamiento;
                $this->modelacuerdodepagojuridico_model->insertar_mandamientopago($datos_mandamiento);
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo se ha Modificado y se ha creado la medida Cautelar.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/procesos');
            endif;

        else:
            $tipogestion = 737;
            $tiporespuesta = 1507;
            $comentarios = 'Ya Existía Mandamiento de pago';
            $data = array('COD_RESPUESTA' => $tiporespuesta);
            $insert = $this->modelacuerdodepagojuridico_model->actualizar_acuerdo($data, $acuerdo, $tipo = NULL);
            if ($insert == true):
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo se ha Modificado correctamente, se ha verificado y ya existe medida cautelar.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/procesos');
            endif;
        endif;
    }

    function gestionarAcuerdo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepagojuridico/gestionarAcuerdo')) {
                $this->template->set('title', 'Facilidad de pago');
                $this->data['title'] = 'Verificación Facilidad de Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ID_USER'] = ID_USER;
                $cod_coactivo = $this->input->post('cod_coactivo');
                //$cod_coactivo = 37;
                $datos = $this->data['acuerdosautorizar'] = $this->modelacuerdodepagojuridico_model->consultarResoluciones(ID_CARGO, REGIONAL_USER, $cod_coactivo);
                $this->data['acuerdo'] = $datos[0]['NRO_ACUERDOPAGO'];
                $this->data['nit'] = $datos[0]['NITEMPRESA'];
                $this->data['gestion'] = $datos[0]['COD_RESPUESTA'];
                $this->data['cod_coactivo'] = $datos[0]['COD_PROCESO_COACTIVO'];
                $this->data['fecha'] = $datos[0]['FECHA_CREACION'];
                $this->data['concepto'] = $datos[0]['COD_CONCEPTO_COBRO'];
                $this->data['ajustado'] = $datos[0]['ACUERDO_AJUSTADO'];
                $this->data['regional'] = $datos[0]['COD_REGIONAL'];
                $this->data['razon'] = $datos[0]['NOMBRE_EMPRESA'];
                $this->data['deuda'] = $datos[0]['SALDO_DEUDA'];
                $this->data['capital'] = $datos[0]['SALDO_CAPITAL'];
                $this->data['intereses'] = $datos[0]['SALDO_INTERES'];
                $this->data['procedencia'] = $datos[0]['PROCEDENCIA'];


                $this->template->load($this->template_file, 'acuerdodepagojuridico/acuerdosPago', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardarporcentajecuotainicial() {
        $this->data['user'] = $this->ion_auth->user()->row();
        $idusuario = $this->data['user']->IDUSUARIO;
        $porcentaje = $this->input->post('porcentaje'); //        
        $this->modelacuerdodepagojuridico_model->porcentaje($porcentaje, $idusuario);
    }

    function guardarAcuerdo() {
        $post = $this->input->post();
        $acuerdo = $this->input->post('acuerdo');
        $datoValida = $this->input->post('dato_valida');
        $cod_coactivo = $this->input->post('cod_coactivo');
        $nit = $this->input->post('nit');
        $liquidacion = $this->input->post('liquidacion');
        if ($datoValida == 0) {
            $tipogestion = 12;
            $tiporespuesta = 16;
            $comentarios = 'Acuerdo Confirmado';
        } else {
            $tipogestion = 12;
            $tiporespuesta = 17;
            $comentarios = 'Acuerdo Rechazado';
        }
        $acuerdos = $this->modelacuerdodepagojuridico_model->consultaProyeccion($acuerdo);


        $data = array(
            'COD_RESPUESTA' => $tiporespuesta,
            'VALOR_CUOTA' => round($post['valorcuota'][1]),
            'NUMERO_CUOTAS' => count($post['cuota'])
        );

        trazarProcesoJuridico($tipogestion, $tiporespuesta, '', $cod_coactivo, '', '', '', $comentarios = $comentarios, $usuariosAdicionales = '');
        $insert = $this->modelacuerdodepagojuridico_model->actualizar_acuerdo($data, $acuerdo, $tipo = 'guardar');
        $this->modelacuerdodepagojuridico_model->actualizacuotas($acuerdo);
        $data = array();
        for ($i = 1; $i < count($post['cuota']); $i++) {
            $this->modelacuerdodepagojuridico_model->insertacuerdopagocuotas(
                    $acuerdo, //Valor del Acuerdo
                    $post['cuota'][$i], //Valor de la Cuota
                    $post['fecha'][$i], //Fecha Limite de Pago
                    $post['saldocapital'][$i], //Saldo Inicial
                    $post['intCorriente'][$i], //Interes Corriente
                    $post['intCorrienteCuota'][$i], //Interes Corriente Cuota -> Interes Liquidacion / ncuotas
                    $post['interespormora'][$i], // Interes Calculado del acuerdo
                    $post['valorcuota'][$i], //Valor de la Cuota
                    $post['aportecapital'][$i], //Aporte A Capital Neto Mensual     
                    $post['saldofinal'][$i], //Valor Final del Ejercicio
                    $datoValida, 1
            );
        }
    }

    function guardagarantiaingreso() {
        $post = $this->input->post();
        $acuerdo = $this->input->post('acuerdo');
        $cod_coactivo = $this->input->post('cod_coactivo');
        $nit = $this->input->post('nit');
        $avaluo = 0;

        for ($l = 0; $l < $post['contador']; $l++) {
            $valorgarantia = $post['valorgarantia'];
            $a = 0;
            for ($i = 0; $i < sizeof($post['datos']); $i++) {
                $texto = '';
                $valor = 0;
                for ($k = 0; $k <= $i; $k++) {
                    $valor = $valor + $post['datos'][$k];
                }
                for ($j = $a; $j < $valor; $j++) {
                    $texto = $texto . $valorgarantia[$j] . '-';
                }
                $a = $valor;

                $cod_campo = $final[$i] = $texto;
            }
            $datos = array(
                'VALOR_CAMPO' => $cod_campo,
                'VALOR_AVALUO' => $post['vavaluo'][$l],
                'VALOR_COMERCIAL' => $post['vcomercial'][$l],
                'COD_CAMPO' => $post['garantia'][$l],
                'COD_TIPO_GARANTIA' => $post['garantia'][$l],
                'CODEMPRESA' => $nit,
                'COD_ACUERDO_PAGO' => $acuerdo,
                //'NUM_LIQUIDACION' => $liquidacion,
                'COD_USUARIO' => ID_USER,
                'ESTADO' => '0'
            );
            $this->modelacuerdodepagojuridico_model->guardagarantia($datos);
            $avaluo = $avaluo + $post['vavaluo'][$l];
            //$cod_campo = '';
        }
        $avaluo;
        $tipogestion = 514;
        $tiporespuesta = 1272;
        $comentarios = 'Constituir y Presentar Garantias';
        $tipo = 'garantia';
        $data = array(
            'COD_RESPUESTA' => $tiporespuesta,
            'TOTAL_GARANTIA' => $avaluo,
        );

        trazarProcesoJuridico($tipogestion, $tiporespuesta, '', $cod_coactivo, '', '', '', $comentarios = $comentarios, $usuariosAdicionales = '');
        $medida = $this->modelacuerdodepagojuridico_model->validaMedidaCautelar($cod_coactivo);
        $insert = $this->modelacuerdodepagojuridico_model->actualizar_acuerdo($data, $acuerdo, $tipo);
        $medida = false;
        if ($insert == true):
            if ($medida == false):
                $this->generarBien($tipogestion, $tiporespuesta, $cod_coactivo, $comentarios);
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo se ha Modificado y se ha creado la medida Cautelar.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/procesos');
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo se ha Modificado correctamente, se ha verificado y ya existe medida cautelar.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/procesos');
            endif;
        else:
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo no se ha Modificado correctamente.</div>');
            redirect(base_url() . 'index.php/bandejaunificada/procesos');
        endif;
    }

    function guardar_cuotaCero() {
        $acuerdo = $this->input->post('acuerdo'); //Num de acuerdo
        $fpcuota = $this->input->post('fpcuota'); // fecha de vencimiento Primer Cuota
        $totaldeuda = $this->input->post('totaldeuda'); //Total deuda de liquidacion
        $totalCapital = $this->input->post('totalCapital'); //Valor del capital de la liquidacion
        $totalPagar = $this->input->post('totalPagar'); //% del capital a pagar
        $totalIntereses = $this->input->post('totalIntereses'); //Valor de los intereses de liquidacion
        $pagarIntereses = $this->input->post('pagarIntereses'); // % del total intereses
        $totalcuota = $this->input->post('totalcuota'); //El valor total a pagar en la cuota cero
        $numcuota = $this->input->post('numcuota'); //Numero de cuota
        $porcentaje = $this->input->post('porcentaje'); //Numero de cuota
        $superintendencia = $this->input->post('superintendencia'); //tasa superintendencia
        $cod_coactivo = $this->input->post('cod_coactivo'); //cod_coactivo
        $nit = $this->input->post('nit'); //nit
        $procedencia = $this->input->post('procedencia'); //nit

        if ($procedencia == MISIONAL):
            $data = array(
                'NRO_ACUERDOPAGO' => $acuerdo, 'PROYACUPAG_NUMCUOTA' => $numcuota, 'PROYACUPAG_VALORCUOTA' => round($totalcuota), 'PROYACUPAG_FECHALIMPAGO' => $fpcuota,
                'PROYACUPAG_SALDOCAPITAL' => round($totalPagar), 'PROYACUPAG_VALORINTERESESMORA' => 0, 'PROYACUPAG_ESTADO' => 0,
                'PROACUPAG_CARTAAUT' => 1, 'PROYACUPAG_CAPITALDEBE' => round($totalCapital), 'PROYACUPAG_CAPITAL_CUOTA' => round($totalPagar),
                'PROYACUPAG_INTCORRIENTE' => round($totalIntereses), 'PROYACUPAG_INTCORRIENTE_CUOTA' => round($pagarIntereses),
                'PROYACUPAG_INTACUERDO' => 0, 'PROYACUPAG_SALDO_INTCORRIENTE' => round($pagarIntereses),
                'PROYACUPAG_SALDO_INTACUERDO' => 0, 'PROYACUPAG_SALDO_CUOTA' => round($totalcuota),
            );

            $tipogestion = 12;
            $tiporespuesta = 1261;
            $insert = $this->modelacuerdodepagojuridico_model->cuotaCero($data);
            $update = $this->modelacuerdodepagojuridico_model->valoresCero($acuerdo, $fpcuota, round($totaldeuda), round($totalPagar), round($totalCapital), round($totalIntereses), round($porcentaje), $superintendencia);
            $comentarios = 'Elaborar Recibo de Cuota Cero';
            trazarProcesoJuridico($tipogestion, $tiporespuesta, '', $cod_coactivo, '', '', '', $comentarios = $comentarios, $usuariosAdicionales = '');
        endif;

        if ($procedencia == NO_MISIONAL):
            $data = array(
                'NRO_ACUERDOPAGO' => $acuerdo, 'PROYACUPAG_NUMCUOTA' => $numcuota, 'PROYACUPAG_VALORCUOTA' => round($totalcuota), 'PROYACUPAG_FECHALIMPAGO' => $fpcuota,
                'PROYACUPAG_SALDOCAPITAL' => round($totalPagar), 'PROYACUPAG_VALORINTERESESMORA' => 0, 'PROYACUPAG_ESTADO' => 0,
                'PROACUPAG_CARTAAUT' => 1, 'PROYACUPAG_CAPITALDEBE' => round($totalCapital), 'PROYACUPAG_CAPITAL_CUOTA' => round($totalPagar),
                'PROYACUPAG_INTCORRIENTE' => round($totalIntereses), 'PROYACUPAG_INTCORRIENTE_CUOTA' => round($pagarIntereses),
                'PROYACUPAG_INTACUERDO' => 0, 'PROYACUPAG_SALDO_INTCORRIENTE' => round($pagarIntereses),
                'PROYACUPAG_SALDO_INTACUERDO' => 0, 'PROYACUPAG_SALDO_CUOTA' => round($totalcuota),
            );

            $tipogestion = 12;
            $tiporespuesta = 1261;
            $insert = $this->modelacuerdodepagojuridico_model->cuotaCero($data);
            $update = $this->modelacuerdodepagojuridico_model->valoresCero($acuerdo, $fpcuota, round($totaldeuda), round($totalPagar), round($totalCapital), round($totalIntereses), round($porcentaje), $superintendencia);
            $comentarios = 'Elaborar Recibo de Cuota Cero';
            trazarProcesoJuridico($tipogestion, $tiporespuesta, '', $cod_coactivo, '', '', '', $comentarios = $comentarios, $usuariosAdicionales = '');
        endif;
    }

    function guardaexcepcion() {
        $cod_coactivo = $this->input->post('cod_coactivo');
        $observacion = $this->input->post('observacion');
        $nit = $this->input->post('nit');
        $autorizacion = $this->input->post('autorizacion');
        $acuerdo = $this->input->post('acuerdo');
        $cantSolicitudes = $this->input->post('cantSolicitudes');

        $existencia = $this->modelacuerdodepagojuridico_model->consultaexcepcion($cod_coactivo);
        $excepcion = $this->modelacuerdodepagojuridico_model->guardaexcepcion($cod_coactivo, $observacion, $acuerdo);
        if ($cantSolicitudes >= 2) {
            $tipogestion = 515;
            $tiporespuesta = 1267;
            $comentarios = 'Autorizacion No Enviada';
        } else {
            if ($autorizacion == 1) {
                $tipogestion = 515;
                $tiporespuesta = 1266;
                $comentarios = 'Autorizacion Enviada';
                $asignado = ID_COORDINADOR;
            } else {
                $tipogestion = 515;
                $tiporespuesta = 1267;
                $comentarios = 'Autorizacion No Enviada';
                $asignado = ID_USER;
            }
        }
        $tipo = 'excepcion';
        $data = array(
            'COD_RESPUESTA' => $tiporespuesta,
            'USUARIO_ASIGNADO' => $asignado,
        );

        trazarProcesoJuridico($tipogestion, $tiporespuesta, '', $cod_coactivo, '', '', '', $comentarios = $comentarios, $usuariosAdicionales = '');
        $insert = $this->modelacuerdodepagojuridico_model->actualizar_acuerdo($data, $acuerdo, $tipo);
        if ($excepcion == TRUE) {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Facilidad se ha Modificado correctamente.</div>');
            redirect(base_url() . 'index.php/bandejaunificada/procesos');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Facilidad no se ha podido modificar.</div>');
            redirect(base_url() . 'index.php/bandejaunificada/procesos');
        }
    }

    function guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, $usuario, $cod_coactivo) {
        $CI = & get_instance();
        $CI->load->model("Expediente_model");
        $model = new Expediente_model();
        $radicado = FALSE;
        $fecha_radicado = FALSE;
        $expediente = $model->agrega_expediente($respuesta, $nombre, $ruta, $radicado, $fecha_radicado, $tipo_expediente, $subproceso, $usuario, $cod_coactivo); //Guarda en la tabla expediente
        return $expediente;
    }

    function guardacamposgarantias() {
        $idgarantia = $this->input->post('listagarantia');
        $campogarantia = $this->input->post('campogarantia');

        for ($j = 0; $j < count($idgarantia); $j++) {

            $existegarantia = $this->modelacuerdodepagojuridico_model->existegarantia($idgarantia[$j], $campogarantia[$j]);
            echo $existegarantia;
            if ($existegarantia == 0) {
                $this->modelacuerdodepagojuridico_model->insertacamposgarantias($idgarantia[$j], $campogarantia[$j]);
            }
        }
    }

    function guardar_paso_juridico() {
        $this->data['post'] = $this->input->post();
        $post = $this->data['post'];
        $route_template = 'uploads/acuerdopagodoc/' . $post['cod_coactivo'] . '/ejecutoriaactoadmin/';
        if (!file_exists($route_template)) {
            if (!mkdir($route_template, 0777, true)) {
                
            }
        }
        $nombreDoc = create_template($route_template, $post['informacion']);
        $rutas = "";
        if (isset($post['rutas'])) {
            foreach ($post['rutas'] as $value) {
                $rutas.=$value . "///";
            }
            $nombre = "|||";
            foreach ($post['nombre'] as $value) {
                $nombre.=$value . "///";
            }
        } else {
            $nombre = "";
            $rutas = "";
        }

        $data = array(
            'DOCUMENTO_COBRO_COACTIVO' => $nombreDoc,
            'DETALLE_COBRO_COACTIVO' => base64_encode(utf8_decode($rutas . $nombre)),
            'COD_ESTADO' => $post['cod_estado']
        );
        $this->ejecutoriaactoadmin_model->updateEstadoResolucion3($post['id'], $data);
        $acuerdo = $this->input->post('acuerdo');
        $comentarios = 'Documentacion paso a Coactivo';
        $tipogestion = 419;
        $tiporespuesta = 1094;
        $tipo = NULL;

        trazarProcesoJuridico($tipogestion, $tiporespuesta, '', $cod_coactivo, '', '', '', $comentarios = $comentarios, $usuariosAdicionales = '');
        $dato = array('COD_RESPUESTA' => $tiporespuesta,);
        $insert = $this->modelacuerdodepagojuridico_model->actualizar_acuerdo($dato, $acuerdo, $tipo);
        //$codgest = trazar(418, 1093, $post['cod_coactivo'], $post['nit'], "S");
        echo "<script>window.history.back()</script>";
    }

    function guardar_verificacion() {
        if ($this->ion_auth->logged_in()) {
            @$acuerdo = $this->input->post('acuerdo');
            @$tipo = $this->input->post('tipo');
            $cod_coactivo = $this->input->post('cod_coactivo');
            $nit = $this->input->post('nit');
            $verificacion = $this->input->post('verificacion');
            @$observacion = $this->input->post('observacion');
            @$observaciones = $this->input->post('observa');
            @$ajustado = $this->input->post('ajustado');
            $fecha = date('d/m/Y');
            $user = $this->ion_auth->user()->row();
            $usuario = $user->IDUSUARIO;
            $ruta = 'uploads/acuerdopagodoc/recibo/';

            switch ($tipo) {
                case 'condiciones':
                    if ($verificacion == 'S') {
                        $tipogestion = 12;
                        $tiporespuesta = 1261;
                        $comentarios = 'Propuesta Al Deudor';
                    } else {
                        if ($ajustado == 'S'):
                            $tipogestion = 512;
                            $tiporespuesta = 1296;
                            $comentarios = 'No es la Primera Solicitud de Ajuste';
                        else:
                            $tipogestion = 512;
                            $tiporespuesta = 1259;
                            $comentarios = 'Deudor Para Ajustar Solicitud Requerido';
                        endif;
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                    );
                    $tipo = NULL;
                    break;
                case 'pago_minimo':
                    if ($verificacion == 'S') {
                        $tipogestion = 513;
                        $tiporespuesta = 1262;
                        $comentarios = 'Pago Minimo Efectuado';
                        $pagada = 's';
                    } else {
                        $tipogestion = 513;
                        $tiporespuesta = 1263;
                        $comentarios = 'Pago Minimo No Efectuado';
                        $pagada = 'n';
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                        'CUOTA_INICIAL_PAGADA' => $pagada,
                    );
                    break;
                case 'garantias':
                    if ($verificacion == 'S') {
                        $tipogestion = 514;
                        $tiporespuesta = 1264;
                        $comentarios = 'Requiere Garantias';
                    } else {
                        $tipogestion = 514;
                        $tiporespuesta = 1265;
                        $comentarios = 'No Requiere Garantias';
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                    );
                    $tipo = NULL;
                    break;
                case 'autoriza_garantias':
                    if ($verificacion == 'S') {
                        $tipogestion = 515;
                        $tiporespuesta = 1268;
                        $comentarios = 'Autorizacion Aprobada';
                        $estado = 1;
                    } else {
                        $tipogestion = 515;
                        $tiporespuesta = 1269;
                        $comentarios = 'Autorizacion No Aprobada';
                        $estado = 2;
                    }
                    $usuario_asignado = $this->modelacuerdodepagojuridico_model->consulta_acuerdo($acuerdo);
                    $asignado = $usuario_asignado[0]['USUARIO_GENERA'];
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                        'USUARIO_ASIGNADO' => $asignado
                    );
                    $dataExcepcion = array(
                        'EXCEPCION' => $verificacion,
                        'OBSERVACION_COORDINADOR' => $observaciones,
                        'ESTADO' => $estado,
                    );
                    $updateExcep = $this->modelacuerdodepagojuridico_model->actualizaautorizacionadministrativo($cod_coactivo, $verificacion, $observacion);
                    $tipo = 'autoriza_garantias';
                    break;
                case 'proyecto_acuerdo':
                    $tipogestion = 14;
                    $tiporespuesta = 20;
                    $comentarios = 'Formato de Facilidad de pago Generado';
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                    );
                    $consulta = $this->modelacuerdodepagojuridico_model->consultaDatos($acuerdo);
                    $cod_coactivo = $consulta[0]['COD_PROCESO_COACTIVO'];
                    $nit = $consulta[0]['NITEMPRESA'];
                    $tipo = NULL;
                    break;
                case 'deudor_acuerdo':
                    if ($verificacion == 'S') {
                        $tipogestion = 516;
                        $tiporespuesta = 1270;
                        $comentarios = 'Deudor Firma Facilidad de Pago';
                    } else {
                        $tipogestion = 516;
                        $tiporespuesta = 1271;
                        $comentarios = 'Deudor No Firma Facilidad de Pago';
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                    );
                    $tipo = NULL;
                    break;
                case 'verifico_pago':
                    if ($verificacion == 'S') {
                        $tipogestion = 17;
                        $tiporespuesta = 25;
                        $comentarios = 'Facilidad de Pago Terminado';
                    } else {
                        $tipogestion = 18;
                        $tiporespuesta = 26;
                        $comentarios = 'Elaborando Carta Por Incumplimiento';
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                    );
                    $tipo = NULL;
                    break;
                case 'voluntad_pago':
                    $tipogestion = 19;
                    $tiporespuesta = 27;
                    $comentarios = 'Carta de Imcumplimiento Facilidad de Pago Generada';
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                    );
                    $tipo = NULL;
                    break;
                case 'bloqueo':
                    $tipogestion = 512;
                    $tiporespuesta = 1297;
                    $comentarios = 'Resolver solicitud de ajuste';
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                    );
                    $tipo = NULL;
                    break;
                case 'solicitud_ajuste':
                    if ($verificacion == 'S') {
                        $tipogestion = 512;
                        $tiporespuesta = 1295;
                        $comentarios = 'Primera Solicitud de Ajuste';
                        $ajustado = 'S';
                    } else {
                        $tipogestion = 512;
                        $tiporespuesta = 1296;
                        $comentarios = 'No es la Primera Solicitud de Ajuste';
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                        'ACUERDO_AJUSTADO' => $ajustado
                    );

                    break;
                case 'presenta_recurso':
                    if ($verificacion == 'S') {
                        $tipogestion = 533;
                        $tiporespuesta = 1298;
                        $comentarios = 'Se presentaron recursos';
                    } else {
                        $tipogestion = 533;
                        $tiporespuesta = 1299;
                        $comentarios = 'No se presentaron recursos';
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                    );
                    $tipo = NULL;
                    break;
                case 'ejecutar_garantias':
                    if ($verificacion == 'S') {
                        $tipogestion = 534;
                        $tiporespuesta = 1300;
                        $comentarios = 'Existen Garantias Existentes Para Ejecutar';
                    } else {
                        $tipogestion = 534;
                        $tiporespuesta = 1301;
                        $comentarios = 'No Existen Garantias Existentes Para Ejecutar';
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                    );
                    $tipo = NULL;
                    break;
                case 'recurso_favorable':
                    if ($verificacion == 'S') {
                        $tipogestion = 535;
                        $tiporespuesta = 1302;
                        $comentarios = 'El Recurso es Favorable al Deudor';
                    } else {
                        $tipogestion = 535;
                        $tiporespuesta = 1303;
                        $comentarios = 'El Recurso es No Favorable al Deudor';
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                    );
                    $tipo = NULL;
                    break;
                case 'pago_total':
                    if ($verificacion == 'S') {
                        $tipogestion = 536;
                        $tiporespuesta = 1304;
                        $comentarios = 'El Deudor Efectuo el Pago Total de la Obligacion';
                        $pago = 'S';
                    } else {
                        $tipogestion = 536;
                        $tiporespuesta = 1305;
                        $comentarios = 'El Deudor No Efectuo el Pago Total de la Obligacion';
                        $pago = 'N';
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                        'AUTO_ACUERDO' => $pago,
                    );
                    break;
                case 'nuevas_garantias':
                    if ($verificacion == 'S') {
                        $tipogestion = 514;
                        $tiporespuesta = 1504;
                        $comentarios = 'Presento Nuevas Garantías';
                    } else {
                        $tipogestion = 514;
                        $tiporespuesta = 1505;
                        $comentarios = 'No Presento Nuevas Garantías';
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                    );
                    $tipo = NULL;
                    break;
            }

            trazarProcesoJuridico($tipogestion, $tiporespuesta, '', $cod_coactivo, '', '', '', $comentarios = $comentarios, $usuariosAdicionales = '');
            $insert = $this->modelacuerdodepagojuridico_model->actualizar_acuerdo($data, $acuerdo, $tipo);
            if ($insert == TRUE) {
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Facilidad se ha Modificado correctamente.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/procesos');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Facilidad no se ha podido modificar.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/procesos');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function proyectoacuerdodepago() {

        $acuerdo = $this->input->post('acuerdo');
        $nit = $this->input->post('nit');
        $liquidacion = $this->input->post('liquidacion');
        $resolucion = $this->input->post('resolucion');

        $this->data['proyecto'] = $this->modelacuerdodepagojuridico_model->proyectaracuerdo($acuerdo);
        $this->data['datostabla'] = $this->modelacuerdodepagojuridico_model->consultacuotasacuerdopago($nit, $resolucion, $liquidacion);
        $info = $this->numeros_letras_model->ValorEnLetras($this->data['proyecto']->result_array[0]['VALOR_TOTAL_FINANCIADO'], "PESOS");
        $this->data['info'] = $info;
        $valor = $this->numeros_letras_model->ValorEnLetras($this->data['proyecto']->result_array[0]['PROYACUPAG_VALORCUOTA'], "PESOS");
        $this->data['valor'] = $valor;
        $cuota = $this->numeros_letras_model->ValorEnLetras($this->data['proyecto']->result_array[0]['VALOR_CUOTA'], "PESOS");
        $this->data['cuota'] = $cuota;
        $this->template->load($this->template_file, 'acuerdodepagojuridico/proyectoacuerdodepago', $this->data);
        //$this->load->view('acuerdodepagojuridico/proyectoacuerdodepago', $this->data);
    }

    function muestrahtmlcamposgarantias() {
        $this->data['garantia'] = $_POST['idgarantia'];
        $this->data['campos'] = $this->modelacuerdodepagojuridico_model->consultcamposgarantia($this->data['garantia']);
        $this->load->view('acuerdodepagojuridico/muestrahtmlcamposgarantias', $this->data);
    }

    function bloqueo() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->load->view('acuerdodepagojuridico/bloquear', $this->data);
//        $this->load->view('resolucion/crono', $this->data);
    }

    function bloqueo_por_time() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->load->view('acuerdodepagojuridico/bloqueo_por_time', $this->data);
//        $this->load->view('resolucion/crono', $this->data);
    }

    function pdf() {
        $html = $this->input->post('informacion');
        $nombre_pdf = $this->input->post('nombre');
        $titulo = $this->input->post('titulo');
        $tipo = $this->input->post('tipo');
        $data[0] = $tipo;
        $data[1] = $titulo;
        createPdfTemplateOuput($nombre_pdf, $html, false, $data);
        exit();
    }

    function proyeccionacuerdo() {
        if ($this->ion_auth->logged_in()) {
            $this->data['numAcuerdo'] = $this->input->post('acuerdo');
            $this->data['nit'] = $this->input->post('nit');
            $this->data['liquidacion'] = $this->input->post('liquidacion');
            $this->data['resolucion'] = $this->input->post('resolucion');
            $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
            $this->data['razon'] = $this->input->post('razon');
            $fecha_hoy = date("d/m/Y");
            $this->data[] = array();
            $this->template->set('title', 'Consulta Proyeccion');
            $this->data['title'] = 'Consulta Proyeccion';
            $this->data['message'] = $this->session->flashdata('message');
            $this->data['concepto'] = $this->modelacuerdodepagojuridico_model->consultatipoconcepto();
            $this->data['tasaEfectiva'] = $this->modelacuerdodepagojuridico_model->tasaEfectiva($fecha_hoy);
            $this->template->load($this->template_file, 'acuerdodepagojuridico/proyeccion', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function proyeccionafuturo() {
        if ($this->ion_auth->logged_in()) {
            $this->data['numAcuerdo'] = $this->input->post('acuerdo');
            $this->data['nit'] = $this->input->post('nit');
            $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
            $this->data['razon'] = $this->input->post('razon');
            $this->data['estado'] = $this->input->post('estado');
            $concepto = $this->input->post('concepto');
            $fecha_hoy = date("d/m/Y");
            $this->data[] = array();
            $this->template->set('title', 'Consulta Proyeccion');
            $this->data['title'] = 'Consulta Proyeccion';
            $this->data['message'] = $this->session->flashdata('message');
            $dato = $this->modelacuerdodepagojuridico_model->consultatipoconcepto($concepto);
            $this->data['concepto'] = $dato[0]['NOMBRE_CONCEPTO'];
            $this->data['tasaEfectiva'] = $this->modelacuerdodepagojuridico_model->tasaEfectiva($fecha_hoy);
            $acuerdo_juridico = $this->modelacuerdodepagojuridico_model->consultarDeudas($this->data['cod_coactivo'], $this->data['estado']);
            $this->data['deuda'] = $acuerdo_juridico[0]['TOTAL_DEUDA'];
            $saldocapital = $acuerdo_juridico[0]['TOTAL_CAPITAL'];
            $cuotainicial = $this->modelacuerdodepagojuridico_model->cuotainicial();
            $this->data['vinicuota'] = ($saldocapital * $cuotainicial[0]['PORCENTAJE']) / 100;
            $this->data['capital'] = $acuerdo_juridico[0]['TOTAL_CAPITAL'];
            $this->data['intereses'] = $acuerdo_juridico[0]['TOTAL_INTERES'];
            $this->template->load($this->template_file, 'acuerdodepagojuridico/proyeccionafuturo', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function verificar_acuerdo() {
//        error_reporting(E_ALL);
//        echo "<hr>";gestionarAcuerdo
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepagojuridico/gestionarAcuerdo')) {
                $this->data['acuerdo'] = $this->input->post('acuerdo');
                $this->data['estado'] = $this->input->post('estado');
                $this->data['fecha'] = $this->input->post('fecha');
                $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
                $this->data['liquidacion'] = $this->input->post('liquidacion');
                $this->data['nit'] = $this->input->post('nit');
                $this->data['razon'] = $this->input->post('razon');
                $this->data['resolucion'] = $this->input->post('resolucion');
                $this->data['tipo'] = $this->input->post('tipo');
                $this->data['ajustado'] = $this->input->post('ajustado');
                $this->data['deuda'] = $this->input->post('deuda');
                $this->data['capital'] = $this->input->post('capital');
                $this->data['intereses'] = $this->input->post('intereses');
                $this->data['procedencia'] = $this->input->post('procedencia');
                $this->data['excepcion'] = $this->modelacuerdodepagojuridico_model->excepcionesAcuerdo($this->data['cod_coactivo']);
                //@$deuda = $this->modelacuerdodepagojuridico_model->consultadeuda($this->data['liquidacion']);                
                //echo "<br>1";
                $deuda = recalcularLiquidacion_acuerdoPago($this->data['liquidacion']);
                if (!empty($deuda['capital'])):
                    @$this->data['saldocapital'] = $deuda['capital'];
                else:
                    @$this->data['saldocapital'] = 0;
                endif;
                //echo "<br>4";
                //echo $this->data['saldocapital'];
                $porcentaje = $this->modelacuerdodepagojuridico_model->cuotainicial();
                $porcentajeTasa = $porcentaje[0]['PORCENTAJE'] / 100;
                $this->data['cuotaDeuda'] = ($this->data['saldocapital'] - ($this->data['saldocapital'] * $porcentajeTasa));
                $this->data['pago_inicial'] = $this->modelacuerdodepagojuridico_model->verificaCero($this->data['acuerdo']);
                $this->load->view('acuerdodepagojuridico/verifica_acuerdo', $this->data);
            }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function verificar_garantia() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepagojuridico/gestionarAcuerdo')) {
                $this->data['nit'] = $this->input->post('nit');
                $this->data['resolucion'] = $this->input->post('resolucion');
                $this->data['estado'] = $this->input->post('estado');
                $this->data['acuerdo'] = $this->input->post('acuerdo');
                $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
                $this->data['tipo'] = $this->input->post('tipo');
                @$this->data['excepcion'] = $this->modelacuerdodepagojuridico_model->excepcionesAcuerdo($this->data['cod_coactivo']);

                $this->load->view('acuerdodepagojuridico/verifica_garantias', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function verificar_pago() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepagojuridico/gestionarAcuerdo')) {
                $nit = $this->input->post('nit');
                $this->data['nit'] = $this->input->post('nit');
                $acuerdo = $this->data['acuerdo'] = $this->input->post('acuerdo');
                $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
                $this->data['razon'] = $this->input->post('razon');
                $this->data['tipo'] = $this->input->post('tipo');
                $this->data['datostabla'] = $this->modelacuerdodepagojuridico_model->consultacuotasacuerdopago($acuerdo);
                $this->data['resolucion'] = $this->modelacuerdodepagojuridico_model->numero_resolucion($this->data['cod_coactivo']);
                $this->data['resolucion'] = $this->data['resolucion'][0]['RADICADO_ONBASE'];
                $this->load->view('acuerdodepagojuridico/verificar_pago', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function validaciongarantias() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepagojuridico/gestionarAcuerdo')) {
                $nit = $this->input->post('nit');
                $liquidacion = $this->input->post('liquidacion');
                $this->data['acuerdo'] = $this->input->post('acuerdo');
                $this->data['nit'] = $nit;
                $cod_coactivo = $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
                $acuerdo_juridico = $this->modelacuerdodepagojuridico_model->consultarResoluciones(ID_CARGO, REGIONAL_USER, $cod_coactivo);
                $this->data['totalliquidacion'] = $acuerdo_juridico[0]['SALDO_DEUDA'];
                $uvt = $this->modelacuerdodepagojuridico_model->uvt();
                $valorUvt = $uvt[0]['VALOR_UVT'];
                $this->data['valorTotalUvt'] = $this->data['totalliquidacion'] / $valorUvt;
                $this->data[] = array();
                $this->template->set('title', 'Facilidad de pago');
                $this->data['title'] = 'Facilidad de pago';
                @$garantias = $this->modelacuerdodepagojuridico_model->totalgarantias($this->data['acuerdo']);
                $this->data['totalgarantias'] = 0;
                //foreach($garantias as $sumagarantia){
                //@$this->data['total'] = $sumagarantia['VALOR_COMERCIAL'] + $this->data['total'];                                

                if (@$garantias[0]['VALOR_AVALUO'] == '') {
                    @$total = 0;
                } else {
                    //@$this->data['total'] = @$garantias[0]['VALOR_AVALUO'];
                    foreach ($garantias as $sumagarantia) {
                        $this->data['totalgarantias'] = $sumagarantia['VALOR_AVALUO'] + $this->data['totalgarantias'];
                    }
                    $this->data['total'] = $this->data['totalgarantias'] / $valorUvt;
                }

                //}

                @$existencia = $this->modelacuerdodepagojuridico_model->consultaexcepcion2($this->data['acuerdo']);
                @$this->data['respuesta'] = $this->modelacuerdodepagojuridico_model->consultaexcepcion3($this->data['acuerdo']);

                //@$this->data['ultimarespuesta'] = $this->modelacuerdodepagojuridico_model->ultimarespuesta($this->data['acuerdo']);

                if (!empty($existencia[0]['CONTADOR'])) {
                    $this->data['contador'] = $existencia[0]['CONTADOR'];
                } else {
                    $this->data['contador'] = 0;
                }

                $this->data['message'] = $this->session->flashdata('message');
                $this->load->view('acuerdodepagojuridico/validacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function consultadatatableproyeccion() {
//        $financiar = $this->input->post('valorfinanciar');
//        $concepto = $this->input->post('concepto');        
//        $salario = $this->modelacuerdodepagojuridico_model->salariominimo();   
//        $cuota1 = $financiar/$salario[0]['SALARIO_VIGENTE'];        
//        $cuotas = $this->modelacuerdodepagojuridico_model->consultacuota($cuota1);     
        $porcentaje = $this->modelacuerdodepagojuridico_model->cuotainicial();
        $porcentual['PORCENTAJE'] = $porcentaje[0]['PORCENTAJE'] / 100;
        $this->output->set_content_type('application/json')->set_output(json_encode($porcentual['PORCENTAJE']));
        //$this->output->set_content_type('application/json')->set_output(json_encode($porcentual));
    }

    function cuantias() {
        if ($this->ion_auth->logged_in()) {

            $this->data['tablacuantias'] = $this->modelacuerdodepagojuridico_model->tablacuantias();
            $salario = $this->modelacuerdodepagojuridico_model->salariominimo();

            $this->data['salariominimo'] = $salario->result_array[0]['SALARIO_VIGENTE'];
            $this->data['tablacuantias'] = $this->modelacuerdodepagojuridico_model->tablacuantias();
            $this->data['maxCuantia'] = $this->modelacuerdodepagojuridico_model->Maxcuantias();
            $this->data['cuotainicial'] = $this->modelacuerdodepagojuridico_model->cuotainicial();
            $this->data['admingarantia'] = $this->modelacuerdodepagojuridico_model->admingarantia();
            $this->data['ultimagarantia'] = $this->modelacuerdodepagojuridico_model->ultimagarantia();

            $this->data[] = array();
            $this->template->set('title', 'Cuantias');
            $this->data['title'] = 'Facilidad de pago';

            $this->template->load($this->template_file, 'acuerdodepagojuridico/cuantias', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function cuotas() {
        $juridico = $this->input->post('juridico');
        if ($juridico == 4) {
            $this->data['juridico'] = $juridico;
            $identificacion = $this->input->post('identificacion');
            $ejecutoria = $this->input->post('ejecutoria');
            $concepto = $this->input->post('concepto');
            $deuda = $this->modelacuerdodepagojuridico_model->consultadeuda($identificacion);

            $this->data['saldocapital'] = "";
            foreach ($deuda as $total) {
                $this->data['saldocapital'] += $total['SALDO_DEUDA'];
            }
        } else {
            $vfinanciar = $this->input->post('financiar');
            $this->data['juridico'] = $juridico;
            $this->data['saldocapital'] = "";
            $this->data['saldocapital'] = $vfinanciar;
        }

        $salario = $this->modelacuerdodepagojuridico_model->salariominimo();
        $salariominimo = $salario->result_array[0]['SALARIO_VIGENTE'];
        $valor_salarios = $this->data['saldocapital'] / $salariominimo;
        $this->data['cuota'] = $this->modelacuerdodepagojuridico_model->consultacuota($valor_salarios);
        @$this->data['TotalCuota'] = $this->data['cuota'][0]['PLAZOMESES'];
        $this->load->view('acuerdodepagojuridico/cuotas', $this->data);
    }

    function modificaciongarantias() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepagojuridico/gestionarAcuerdo')) {

                $documento = $this->input->post('nit');
                $this->data['tipo'] = $this->input->post('tipo');
                $cod_coactivo = $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
                $acuerdo = $this->data['acuerdo'] = $this->input->post('acuerdo');
                $estado = $this->data['estado'] = $this->input->post('estado');
                $this->data[] = array();
                $this->template->set('title', 'GARANTIAS');
                $this->data['title'] = 'GARANTIAS';
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['tipodocumento'] = $this->modelacuerdodepagojuridico_model->documento();
                $ciudad = $this->modelacuerdodepagojuridico_model->ciudad();
                $this->data['ciudad'] = $ciudad->result_array();
                $acuerdo_juridico = $this->modelacuerdodepagojuridico_model->consultarDeudas($cod_coactivo, $estado);
                $this->data['totalliquidacion'] = $acuerdo_juridico[0]['TOTAL_DEUDA'];
                $uvt = $this->modelacuerdodepagojuridico_model->uvt();
                $valorUvt = $uvt[0]['VALOR_UVT'];
                $this->data['valorTotalUvt'] = $this->data['totalliquidacion'] / $valorUvt;

                //$this->data['totalliquidacion'] = $totalliquidacion['saldocapita'];
                $datos = $this->modelacuerdodepagojuridico_model->consultagarantiasjuridicas($acuerdo);
                if ($estado == 1504 && $datos == TRUE):
                    $this->modelacuerdodepagojuridico_model->eliminagarantia($acuerdo);
                endif;
//                $info = $datos->result_array();
//                $this->data['datosgarantia'] = array();
//                if (!empty($info)) {
//                    foreach ($datos->result_array as $garantia) {
//                        $campo = $garantia['VALOR_CAMPO'];
//                        if (empty($campo)) {
//                            $campo = '';
//                        }
//                        $this->data['datosgarantia'][$garantia['CODEMPRESA']][$garantia['COD_GARANTIA_ACUERDO']][$garantia['NOMBRE_TIPOGARANTIA']][$garantia['VALOR_AVALUO']][$garantia['VALOR_COMERCIAL']][$garantia['NOMBRE_CAMPO']] = $campo;
//                    }
//                }
                $this->data['nit'] = $documento;
                $this->data['garantias'] = $this->modelacuerdodepagojuridico_model->garantias();
                //$this->template->load($this->template_file, 'acuerdodepagojuridico/garantias', $this->data);
                $this->load->view('acuerdodepagojuridico/garantias', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function mora() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepagojuridico/gestionarAcuerdo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $acuerdo = $this->input->post('acuerdo');
                $nit = $this->input->post('nit');
                $liquidacion = $this->input->post('liquidacion');

                $tipogestion = 514;
                $tiporespuesta = 1274;
                $comentarios = 'El Cliente esta en Mora';

                $data = array(
                    'COD_RESPUESTA' => $tiporespuesta,
                );
                $this->generarBien($tipogestion, $tiporespuesta, $cod_coactivo, $comentarios);
                trazarProcesoJuridico($tipogestion, $tiporespuesta, '', $cod_coactivo, '', '', '', $comentarios = $comentarios, $usuariosAdicionales = '');
                $documentoresolucion = $this->modelacuerdodepagojuridico_model->actualizar_acuerdo($data, $acuerdo, $tipo = NULL);
                if ($documentoresolucion == TRUE) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo se ha Modificado correctamente.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/procesos');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function singarantia() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepagojuridico/gestionarAcuerdo')) {
                $this->data[] = array();
                $this->template->set('title', 'Autorizacion Facilidad de Pago Juridico');
                $this->data['title'] = 'Facilidad de pago juridico';
                $this->data['user'] = $this->ion_auth->user()->row();
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['autorizaciones'] = $this->modelacuerdodepagojuridico_model->singarantia();
                $this->template->load($this->template_file, 'acuerdodepagojuridico/singarantia', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function subirDocumento() {
        $nit = $this->input->post('nit');
        $cod_coactivo = $this->input->post('cod_coactivo');
        $conceptos = $this->input->post('concepto');
        $acuerdo = $this->input->post('acuerdo');
        $tipo = 'legalizar';
        $tipogestion = 272;
        $tiporespuesta = 1173;
        $mensaje = 'Envio de Informacion para Acuerdo de Pago';
        $carpeta = 'acuerdopagojuridico/cartaacuerdopagolegalizar';
        $docFile = $this->do_upload($nit, $tipo, $carpeta);

        if ($docFile["error"]) {
            $this->session->set_flashdata('message', '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button>El tamaño del archivo Excede el Límite permitido.</div>');
            redirect(base_url() . 'index.php/bandejaunificada/procesos');
        } else {
            $ruta = 'uploads/acuerdopagojuridico/' . $cod_coactivo . '/' . $tipo . '/pdf/' . $docFile["upload_data"]["file_name"];
            $nombre = $docFile["upload_data"]["file_name"];
            $tipo_expediente = 7;
            $subproceso = 'Legalizar Facilidad de Pago Juridico';
            $expediente = $this->guarda_expediente($tiporespuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $cod_coactivo);
            $data = array(
                'COD_RESPUESTA' => $tiporespuesta,
                'USUARIO_ASIGNADO' => ID_USER,
                'DOC_LEGALIZACION' => $ruta,
            );
            trazarProcesoJuridico($tipogestion, $tiporespuesta, '', $cod_coactivo, '', '', '', $comentarios = 'Legalizar Facilidad de Pago', $usuariosAdicionales = '');
            $insert = $this->modelacuerdodepagojuridico_model->insertAcuerdo($data, $acuerdo);

            if ($insert == true) {
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Documento Subido correctamente.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/procesos');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Documento no se ha adjuntado correctamente.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/procesos');
            }
        }
    }

    function guardaautorizacion() {
        $cod_coactivo = $this->input->post('cod_coactivo');
        $codexcepcion = $this->input->post('codexcepcion');
        $opcion = $this->input->post('opcion');
        $observacion = $this->input->post('observa');
        $this->modelacuerdodepagojuridico_model->actualizaautorizacionadministrativo($codexcepcion, $cod_coactivo, $opcion, $observacion);
    }

    private function do_upload($cProceso, $cTipo, $carpeta) {

        $cFile = "./uploads/" . $carpeta . "/" . $cProceso . "/pdf/";
        if (!is_dir($cFile)) {
            mkdir($cFile, 0777, true);
        }
        $cFile = $cFile . $cTipo . "/";
        if (!is_dir($cFile)) {
            mkdir($cFile, 0777, true);
        }

        $config['upload_path'] = $cFile;
        $config['allowed_types'] = '*';
        $config['max_size'] = '6048';
        $config['encrypt_name'] = true;

        $datos = $this->upload->initialize($config);

        if (!$this->upload->do_upload('file')) {
            return $error = array('error' => $this->upload->display_errors());
        } else {
            return $data = array('upload_data' => $this->upload->data());
        }
    }

    /*
     * OBSERVACION PARA SUBIR ARCHIVO Y VISUALIZARLO PARA CONFIRMAR
     */

    function Subir_ResolucionFacilidadPago() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Agregar_Expediente($this->input->post('cod_coactivo'), 'Resolución por la cual se concede una facilidad para el pago', 1496);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Expediente($cod_coactivo, $tipo, $cod_respuesta) {
        if ($this->ion_auth->logged_in()) {
            /*
             * CONSULTAR DOCUMENTO PARA IMPRIMIR
             */
            $documento = $this->documentospj_model->get_plantilla_proceso($cod_coactivo);
            $this->data['titulo_encabezado'] = $documento['TITULO_ENCABEZADO'];
            $urlplantilla2 = "uploads/acuerdodepagojuridico/resolucionLegaliza/" . $cod_coactivo . "/" . $documento['NOMBRE_DOCUMENTO'];
            $arreglo = array();
            $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
            /*
             * CARGAR VISTA
             */
            $this->template->set('title', 'Jurisdicción Coactiva -> Facilidad de Pago');
            $this->data['message'] = "Los archivos de soporte a subir son únicamente PDF";
            $this->data['tipo'] = $tipo;
            $this->data['cod_coactivo'] = $cod_coactivo;
            $this->data['cod_respuesta'] = $cod_respuesta;
            $this->template->load($this->template_file, 'acuerdodepagojuridico/subirexpediente_add', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Soporte_Expediente() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('cod_coactivo')) {
                /*
                 * RECIBIR DATOS
                 */
                $cod_coactivo = $this->input->post('cod_coactivo');
                $fecha = $this->input->post('fecha');
                $numero = $this->input->post('numero');
                $cod_respuesta = $this->input->post('cod_respuesta');
                $datos['COD_RESPUESTAGESTION'] = $cod_respuesta;
                $datos['FECHA_RADICADO'] = $fecha;
                $datos['NUMERO_RADICADO'] = $numero;
                $datos["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                $datos["ID_USUARIO"] = ID_USER;
                /*
                 * OBTENER NOMBRE DEL ARCHIVO
                 */
                $file = $this->do_multi_upload($cod_coactivo, "Expediente"); //1- pdf y archivos de imagen
                switch ($cod_respuesta) {
                    case 1496:
                        $datos['COD_TIPO_EXPEDIENTE'] = 2;
                        $datos['SUB_PROCESO'] = 'Resolución por la cual se concede una facilidad para el pago';
                        break;
                }
                /*
                 * POR CUANTOS ARCHIVOS SE ENCUENTRE UN INSERT SE REALIZA
                 */
                $cantidad_documentos = sizeof($file);
                for ($i = 0; $i < $cantidad_documentos; $i++) {
                    $datos["RUTA_DOCUMENTO"] = "uploads/acuerdodepagojuridico/" . $cod_coactivo . "/Expediente/" . $file[$i]["upload_data"]["file_name"];
                    $datos["NOMBRE_DOCUMENTO"] = $file[$i]["upload_data"]["file_name"];
                    $this->documentospj_model->insertar_expediente($datos);
                }
                /*
                 * CARGAR TABLA DE EXPEDIENTES
                 */
                $this->template->set('title', 'Jurisdicción Coactiva -> Facilidad de Pago');
                $verificar[0] = $cod_coactivo;
                $verificar[1] = $cod_respuesta;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->data['archivos'] = $this->documentospj_model->get_documentosexpediente($verificar);
                $this->template->load($this->template_file, 'acuerdodepagojuridico/comprobacionexp_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function VistaPrevia_Documento() {
        if ($this->ion_auth->logged_in()) {
            $cod_coactivo = $this->input->post('cod_coactivo');
            $documento = $this->documentospj_model->get_plantilla_proceso($cod_coactivo);
            $this->data['titulo_encabezado'] = $documento['TITULO_ENCABEZADO'];
            $urlplantilla2 = "uploads/acuerdodepagojuridico/resolucionLegaliza/" . $cod_coactivo . "/" . $documento['NOMBRE_DOCUMENTO'];
            $arreglo = array();
            $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
            $this->load->view('acuerdodepagojuridico/visualizardocumento_add', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Gestion_EliminarSoporte() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_soporte')) {
                /*
                 * Recibir los Datos
                 */
                $cod_soporte = $this->input->post('cod_soporte');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $cod_respuesta = $this->input->post('cod_respuesta');
                /*
                 * Eliminar el Archivo de la BD y del server
                 */
                $soporte[0] = $cod_coactivo;
                $soporte[1] = $cod_respuesta;
                $info_soporte = $this->documentospj_model->get_documentosexpediente($soporte);
                $estructura = "./" . $info_soporte[0]["RUTA_DOCUMENTO"];
                if (file_exists($estructura)) {
                    unlink($estructura);
                }
                $this->documentospj_model->eliminar_soporte($cod_soporte);
                /*
                 * VOLVER A VER LOS DOCUMENTOS
                 */
                $this->template->set('title', 'Jurisdicción Coactiva -> Facilidad de Pago');
                $verificar[0] = $cod_coactivo;
                $verificar[1] = $cod_respuesta;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->data['archivos'] = $this->documentospj_model->get_documentosexpediente($verificar);
                switch ($cod_respuesta) {
                    case 1496:
                        $tipo = 'Resolución por la cual se concede una facilidad para el pago';
                        break;
                }
                if (sizeof($this->data['archivos']) == 0) {
                    $this->Agregar_Expediente($cod_coactivo, $tipo, $cod_respuesta);
                } else {
                    $this->template->load($this->template_file, 'acuerdodepagojuridico/comprobacionexp_add', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Gestion_EliminarPlantillas() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                /*
                 * Recibir los Datos
                 */
                $cod_coactivo = $this->input->post('cod_coactivo');
                $cod_respuesta = $this->input->post('cod_respuesta');
                /* |
                 * Eliminar el Archivo de la BD y del server
                 */
                $plantillas = $this->documentospj_model->get_plantillasfiscalizacion($cod_coactivo);
                for ($i = 0; $i < sizeof($plantillas); $i++) {
                    $estructura = "./uploads/acuerdodepagojuridico/resolucionLegaliza/" . $cod_coactivo . "/" . $plantillas[$i]["NOMBRE_DOCUMENTO"];
                    if (file_exists($estructura)) {
                        unlink($estructura);
                    }
                    $this->documentospj_model->eliminar_plantilla($plantillas[$i]["COMUNICADO_PJ"]);
                }
                /*
                 * ACTUALIZAR EL ESTADO DEL TRASLADO
                 */
                $datos2["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                $datos2["COD_RESPUESTA"] = $cod_respuesta;
                $this->acuerdopagodocjuridico_model->actualizacion_acuerdopago($datos2);
                redirect(base_url() . 'index.php/bandejaunificada');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    private function do_multi_upload($cod_coactivo, $carpeta) {
        $estructura = "./uploads/acuerdodepagojuridico/" . $cod_coactivo . "/" . $carpeta . "/";
        if (!file_exists($estructura)) {
            if (!mkdir($estructura, 0777, true)) {
                die('Fallo al crear las carpetas...');
            }
        }
        $config = array();
        $config['upload_path'] = $estructura;
        $config['allowed_types'] = '*';
        $config['max_size'] = '2048';
        $this->upload->initialize($config);
        $files = $_FILES;
        if (sizeof($files) == 0) {
            return false;
        }
        $cpt = count($_FILES['userfile']['name']);
        for ($i = 0; $i < $cpt; $i++) {
            $_FILES['userfile']['name'] = $files['userfile']['name'][$i];
            $_FILES['userfile']['type'] = $files['userfile']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $files['userfile']['error'][$i];
            $_FILES['userfile']['size'] = $files['userfile']['size'][$i];
            if (!$this->upload->do_upload()) {
                return $error = array('error' => $this->upload->display_errors());
            } else {
                $data[$i] = array('upload_data' => $this->upload->data());
            }
        }
        return $data;
    }

}
