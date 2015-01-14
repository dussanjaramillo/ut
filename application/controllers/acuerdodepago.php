
<?php

class Acuerdodepago extends MY_Controller {

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
        $this->load->model('modelacuerdodepago_model');
        $this->load->model('modelacuerdoconsulta_model');
        $this->load->model('modelacuerdodepagojuridico_model');
        $this->load->model('ejecutoriaactoadmin_model');
        $this->load->model('acuerdopagodoc_model');

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
        $this->data['consultagrupo'] = $this->modelacuerdodepago_model->usuarios(ID_USER);
        $this->data['grupo'] = $this->data['consultagrupo'][0]['IDGRUPO'];
        $cargo = $this->data['consultagrupo'][0]['IDCARGO'];
        define("ID_CARGO", $cargo);
        $sesion = $this->session->userdata;
        define("ID_SECRETARIO", $sesion['id_secretario']);
        define("NOMBRE_SECRETARIO", $sesion['secretario']);
        define("ID_COORDINADOR", $sesion['id_coordinador_relaciones']);
        define("NOMBRE_COORDINADOR", $sesion['coordinador_relaciones']);

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
            $this->data['cargousuario'] = "COORDINADOR_RELACIONES";
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
        define("RUTA_INI", "./uploads/fiscalizaciones/resolucion");
        define("RUTA_DES", "uploads/fiscalizaciones/resolucion/COD_");
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
                $this->modelacuerdodepago_model->guardacamposeditadosadmingarantia($id, $garantia, $campo);
                break;
            case 2:
                echo $id = $this->input->post('id');
                die;
                $this->modelacuerdodepago_model->verificaGarantia();
                $this->modelacuerdodepago_model->eliminacamposeditadosadmingarantia($id);
                break;
        }

//       eliminacampos 
    }

    function acuerdo() {
        $nit = $this->input->post('nit');
        $liquidacion = $this->input->post('liquidacion');
        $acuerdo = $this->input->post('acuerdo');
        $fiscalizacion = $this->input->post('fiscalizacion');
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

        $cod_respuesta = $this->trazabilidad($tipogestion, $tiporespuesta, $fiscalizacion, $nit, $comentarios);
        $documentoresolucion = $this->modelacuerdodepago_model->acuerdoAprobado($data, $acuerdo);

        if ($documentoresolucion == true) {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Documento Subido correctamente.</div>');
            redirect(base_url() . 'index.php/acuerdodepago/gestionarAcuerdo');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Documento no se ha adjuntado correctamente.</div>');
            redirect(base_url() . 'index.php/acuerdodepago/gestionarAcuerdo');
        }
    }

    function buscar_empresa() {
        $empresa = $this->input->get("term");
        $consulta = $this->modelacuerdodepago_model->consultar_empresa($empresa);
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
        $result = $this->modelacuerdodepago_model->consultar_empresa($liquidacion);

        if (!empty($result)) :
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
        endif;
    }

    function acuerdomora() {
        if ($this->ion_auth->logged_in()) {
            if ($this->data['grupo'] == $this->data['abogadoj'] || $this->data['grupo'] == $this->data['abogado'] || $this->data['grupo'] == $this->data['administrador'] || $this->data['grupo'] == $this->data['abogado_relaciones'] || $this->data['grupo'] == $this->data['coordinador_relaciones']) {

                $this->data[] = array();
                $this->template->set('title', 'Autorizacion Acuerdo Juridico');
                $this->data['title'] = 'Acuerdo de pago juridico';
                $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
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
                $this->data['acuerdomoramenor'] = $this->modelacuerdodepago_model->acuerdomoramenor(@$acuerdo, $dato);
                $this->data['acuerdomoramayor'] = $this->modelacuerdodepago_model->acuerdomoramayor(@$acuerdo);
                $this->data['autorizaciones'] = $this->modelacuerdodepago_model->consultaautorizaciones(@$acuerdo);
                $this->template->load($this->template_file, 'acuerdodepago/acuerdomora', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
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
        $this->data['cod_fis'] = $this->input->post('cod_fis');
        $this->data['id'] = $this->input->post('id');
        $this->data['liquidacion'] = $this->input->post('liquidacion');
        $this->data['razon'] = $this->input->post('razon');
        $this->data['nit'] = $this->input->post('nit');
        $this->data['cod_estado'] = $this->input->post('cod_estado');
        $this->data['documentos'] = $this->input->post('documentos');

        $this->load->view('acuerdodepago/acuerdodepago_up', $this->data);
    }

    function acuerdoenviodocumentocancelacion30() {
        $acuerdo = $this->input->post('acuerdo');
        $dato = 1;
        if (empty($acuerdo))
            $acuerdomoramenor = $this->modelacuerdodepago_model->acuerdomoramenor($dato);
        else
            $acuerdomoramenor = $this->modelacuerdodepago_model->acuerdomoramenor($dato, $acuerdo);

        $this->data['totalacuerdos'] = $acuerdomoramenor;

        foreach ($acuerdomoramenor->result_array as $cancelacion) {
            $this->documentocancelacion30dias($cancelacion['NITEMPRESA'], $cancelacion['RAZON_SOCIAL'], $cancelacion['NRO_ACUERDOPAGO'], $cancelacion['NRO_RESOLUCION'], $cancelacion['NRO_LIQUIDACION'], $cancelacion['PROYACUPAG_NUMCUOTA'], $acuerdo);
        }
    }

    function documentocancelacion30dias($nit, $razon, $acuerdo, $resolucion, $liquidacion, $cuota, $existencia) {
        $this->data['existenacia'] = $existencia;
        if (empty($existencia)) {
            $actualizacion = $this->modelacuerdodepago_model->documentoacuerdoenviado($acuerdo, $cuota);
            $this->data['actualizacion'] = $actualizacion;
        }
        $this->data['moramenor'] = $this->modelacuerdodepago_model->cronmora($acuerdo, $cuota);
        $this->load->model('numeros_letras_model');
        $info = $this->numeros_letras_model->ValorEnLetras($this->data['moramenor']->result_array[0]['PROYACUPAG_CAPITALDEBE'], "PESOS");
        $this->data['info'] = $info;
        $this->load->view('acuerdodepago/documentocancelacion30dias', $this->data);
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
            $datos_ejecutoria = $this->ejecutoriaactoadmin_model->datos_ejecutoria($post['cod_fis']);
            $documentos.=$datos_ejecutoria['tabla'];
            $input.=$datos_ejecutoria['input'];
        }
        if ($post['revocatoria'] == 'true') {
            $datos_revocatoria = $this->ejecutoriaactoadmin_model->datos_revocatoria($post['id'], $post['cod_fis']);
            $documentos.=$datos_revocatoria['tabla'];
            $input.=$datos_revocatoria['input'];
        }
        if ($post['liquidacion'] == 'true') {
            $datos_liquidacion = $this->ejecutoriaactoadmin_model->datos_liquidacion($post['id'], $post['cod_fis']);
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
        $cod_respuesta = $this->trazabilidad($tipogestion, $tiporespuesta, $post['cod_fis'], $post['nit'], $comentarios);
        $this->ejecutoriaactoadmin_model->subir_archivo($this->data['post'], $guardar, $this->data['user'], $$cod_respuesta['COD_GESTION_COBRO']);
        $tipo = NULL;
        $data = array(
            'COD_RESPUESTA' => $tiporespuesta,
        );
        $insert = $this->modelacuerdodepago_model->actualizar_acuerdo($data, $post['acuerdo'], $tipo);
        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo se ha Modificado correctamente.</div>');
        redirect(base_url() . 'index.php/acuerdodepago/gestionarAcuerdo');
//        header("location:" . base_url('index.php/ejecutoriaactoadmin/'));
        //echo "<script>window.history.back()</script>";
    }

    function administraciongarantias() {
        if ($this->ion_auth->logged_in()) {
            if ($this->data['grupo'] == $this->data['abogadoj'] || $this->data['grupo'] == $this->data['secretario'] || $this->data['grupo'] == $this->data['ejecutor'] || $this->data['grupo'] == $this->data['administrador'] || $this->data['grupo'] == $this->data['abogado_relaciones'] || $this->data['grupo'] == $this->data['coordinador_relaciones']) {
                $this->data[] = array();
                $this->template->set('title', 'Garantias');
                $this->data['title'] = 'Garantias';

                $this->data['message'] = $this->session->flashdata('message');
                $this->load->model('modelacuerdodepagojuridico_model');
                $this->data['historiagarantia'] = $this->modelacuerdodepago_model->administraciongarantiascampos();
                $this->template->load($this->template_file, 'acuerdodepago/administraciongarantias', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function eliminar_garantias() {
        $post = $this->input->post();
        $eliminado = $this->modelacuerdodepago_model->eliminar_garantias($post);
        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha eliminado la Información.</div>');
        redirect(base_url() . 'index.php/acuerdodepago/administraciongarantias');
    }

    function tableacuerdopago() {
        $identificacion = $this->input->post('nit');
        $ejecutoria = $this->input->post('ejecutoria');
        $concepto = $this->input->post('concepto');
        $this->data['ncuotas'] = $this->input->post('cuotas');
        $cuotas = $this->input->post('cuotas');
        $this->data['liquidacion'] = $liquidacion = $this->input->post('liquidacion');
        $this->data['juridico'] = $this->input->post('juridico');

        if ($this->data['juridico'] == 4 || $this->data['juridico'] == 5) {

            //$deuda = $this->modelacuerdodepago_model->consultadeuda($liquidacion);              
            $deuda = recalcularLiquidacion_acuerdoPago($liquidacion);
            if ($deuda == ''):
                @$this->data['saldocapital'] = 0;
                @$this->data['interesCorriente'] = 0;
            else:
                @$this->data['saldocapital'] = $deuda['capital'];
                @$this->data['interesCorriente'] = $deuda['intereses'];
            endif;
            $fecha_hoy = date("d/m/Y");
            $tasaEfectiva = $this->modelacuerdodepago_model->tasaEfectiva($fecha_hoy);
            $tasamul = ($tasaEfectiva[0]['TASA_SUPERINTENDENCIA']) / 100;
            $tasaperiodica = pow((1 + $tasamul), (1 / 12)) - 1;
            $this->data['tasa'] = $tasaperiodica;
            $porcentaje = $this->modelacuerdodepago_model->cuotainicial();
            $this->data['porcentaje'] = $porcentaje[0]['PORCENTAJE'] / 100;
            $saldoInicial = $this->data['saldocapital'] - ($this->data['saldocapital'] * ($porcentaje[0]['PORCENTAJE'] / 100));
            $valorcuotaFija = ($saldoInicial * ($tasaperiodica * pow(1 + $tasaperiodica, $this->data['ncuotas']))) / (pow((1 + $tasaperiodica), $this->data['ncuotas']) - 1);
            $this->data['valorcuota'] = $valorcuotaFija;
            $cuotainicial = $this->modelacuerdodepago_model->cuotainicial();
            $this->data['nit'] = $this->input->post('nit');
            $this->data['razonsocial'] = $this->input->post('razonsocial');
            $this->data['finicial'] = date('d-m-Y');
            $this->data['fpcuota'] = date("d/m/Y", strtotime($this->data['finicial'] . "+1 month"));
            $this->data['vinicuota'] = ($this->data['saldocapital'] * $cuotainicial[0]['PORCENTAJE']) / 100;
            $this->data['intCorrienteCuotaCero'] = $deuda['intereses'] * ($porcentaje[0]['PORCENTAJE'] / 100);
            $this->data['interescalc'] = @$this->data['interesCorriente'] - $this->data['intCorrienteCuotaCero'];
            $this->data['intCorrienteCuota'] = $this->data['interescalc'] / $this->data['ncuotas'];
            if ($this->data['juridico'] == 4):
                $this->load->view('acuerdodepago/tableacuerdopago2', $this->data);
            elseif ($this->data['juridico'] == 5):

                $this->load->view('acuerdodepago/tablerecibo', $this->data);
            endif;
        } else {
            echo "2";
            $this->data['valorcuota'] = $this->input->post('valorcuota');
            $this->data['fpcuota'] = $this->input->post('fpcuota');
            $this->data['finicial'] = $this->input->post('finicial');
            $this->data['vinicuota'] = $this->input->post('vinicuota');
            $this->data['ncuotas'] = $this->input->post('ncuotas');
            $this->data['saldocapital'] = $this->input->post('saldocapital');
            $this->data['fecha_hoy'] = $fecha_hoy = date("d/m/Y");
            $tasaEfectiva = $this->modelacuerdodepago_model->tasaEfectiva($fecha_hoy);
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
            $this->load->view('acuerdodepago/tableacuerdopago', $this->data);
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

        redirect(base_url() . 'index.php/acuerdodepago/acuerdomora');
    }

    function tablagarantias() {

        $tipodeudor = $this->input->post('tipodeudor');
        $garantia = $this->input->post('garantia');

        if (!empty($garantia)) {
            $existencia = $this->modelacuerdodepago_model->consultaexistenciagarantia($garantia);
            if (!empty($existencia)) {
                $this->data['tipoexistente'] = 'El Tipo de Garantía ya Existe o esta Inactivo';
            } else {
                $this->modelacuerdodepago_model->insertargarantias($garantia, $tipodeudor);
                $this->data['tipoexistente'] = 'Se ha Guardado Con Exito';
            }
        } else {
            $this->data['tipoexistente'] = 1;
        }

        $this->data['garantias'] = $this->modelacuerdodepago_model->garantiasCrear();
        $this->load->view('acuerdodepago/tablagarantias', $this->data);
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

        $respuesta = $this->modelacuerdodepago_model->guardarcuantia($datos);
        $tablacuantias = $this->modelacuerdodepago_model->tablacuantias();
        $this->output->set_content_type('application/json')->set_output(json_encode($tablacuantias->result_array));
    }

    function totalgarantias() {
        $function = $this->modelacuerdodepago_model->administraciongarantiascampos();

        $this->output->set_content_type('application/json')->set_output(json_encode($function->result_array));
    }

    function exportaExcel() {
        //echo utf8_decode($_POST['datos_envio']);
        $post = $this->input->post();
        $fecha = date('Ymd-hi');
        $cRuta = "./uploads/acuerdopago/proyeccion/";
        if (!is_dir($cRuta)) {
            mkdir($cRuta, 0777, true);
        }
        $PHPExcel = new PHPExcel();
        $PHPExcel->getProperties()->setCreator("SENA")->setTitle("ACUERDO DE PAGO");
        $PHPExcel->setActiveSheetIndex(0);
        $PHPExcel->getActiveSheet()->setTitle('ACUERDO DE PAGO');

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'ACUERDO DE PAGO');
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

        header('Content-Description: File Transfer');
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . $fecha . ".xlsx" . '"');
        //header("Content-type: application/vnd.ms-excel-application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        //header('Content-Disposition: attachment;filename="' . $fecha . ".xlsx" . '"');
        header("Pragma: no-cache");
        header('Cache-Control: max-age=0');
        header("Expires: 0");
        ob_clean();
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save("./uploads/acuerdopago/proyeccion/" . $fecha . ".xlsx");
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
        $tasaEfectiva = $this->modelacuerdodepago_model->tasaEfectiva($fecha_hoy);
        $tasamul = ($tasaEfectiva[0]['TASA_SUPERINTENDENCIA']) / 100;
        $tasaperiodica = pow((1 + $tasamul), (1 / 12)) - 1;
        $porcentaje = $this->modelacuerdodepago_model->cuotainicial();
        //$saldoInicial               = $saldoInicial-($saldoInicial*($porcentaje[0]['PORCENTAJE']/100));
        $porcentajeTasa = $porcentaje[0]['PORCENTAJE'] / 100;
        $saldoInicial = $saldoInicial - ($saldoInicial * ($porcentajeTasa));
        $valorcuotaFija['VALOR'] = ($saldoInicial * ($tasaperiodica * pow(1 + $tasaperiodica, $cuotas))) / (pow((1 + $tasaperiodica), $cuotas) - 1);
        $tasa['tasaInteres'] = $tasamul;
        $datos = array('cuota' => $valorcuotaFija['VALOR'], 'tasa' => $tasa['tasaInteres']);
        $this->output->set_content_type('application/json')->set_output(json_encode($datos));
        //$this->output->set_content_type('application/json')->set_output(json_encode($valorcuotaFija['VALOR']));                
    }

    function trazabilidad($tipogestion, $tiporespuesta, $fiscalizacion, $nit, $mensaje) {
        $this->datos['idgestioncobro'] = trazar($tipogestion, $tiporespuesta, $fiscalizacion, $nit, $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios = $mensaje);
        $this->datos['idgestion'] = $this->datos['idgestioncobro']['COD_GESTION_COBRO'];
        return $this->datos['idgestion'];
    }

    function crearAcuerdo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepago/crearAcuerdo')) {
                $this->template->set('title', 'Acuerdo de pago');
                $this->data['title'] = 'Autorizacion Acuerdo de Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['concepto'] = $this->modelacuerdodepago_model->consultatipoconcepto();
                $this->data['acuerdosautorizar'] = $this->modelacuerdodepago_model->consultaautorizacionesacuerdo(REGIONAL_USER);
                $this->template->load($this->template_file, 'acuerdodepago/documentoautorizacionacuerdo', $this->data);
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
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepago/gestionarAcuerdo')) {
                $this->data['acuerdo'] = $this->input->post('acuerdo');
                $estado = $this->data['estado'] = $this->input->post('estado');
                $this->data['fecha'] = $this->input->post('fecha');
                $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
                $this->data['liquidacion'] = $this->input->post('liquidacion');
                $this->data['nit'] = $this->input->post('nit');
                $this->data['razon'] = $this->input->post('razon');
                $this->data['resolucion'] = $this->input->post('resolucion');
                $this->data['ajustado'] = $this->input->post('ajustado');

                $estado = $this->data['estado'];
                switch ($estado) {
                    case 1274:
                        $this->data['titulo'] = 'Resolución que legaliza facilidad de pago';
                        $valor = 122;
                        break;
                    case 16:
                        $this->data['titulo'] = 'Resolución que legaliza facilidad de pago';
                        $valor = 122;
                        break;
                    case 1393:
                        $this->data['titulo'] = 'Notificación Personal de Resolución que facilidad de pago';
                        $valor = 122;
                        break;
                    case 1400:
                        $this->data['titulo'] = 'Notificación Personal de Resolución que legaliza facilidad de pago';
                        $valor = 122;
                        break;
                    case 1399:
                        $this->data['titulo'] = 'Notificación por Correo de Resolución que legaliza facilidad de pago';
                        $valor = 122;
                        break;
                    case 1407:
                        $this->data['titulo'] = 'Notificación por Correo de Resolución que legaliza facilidad de pago';
                        $valor = 122;
                        break;
                    case 1406:
                        $this->data['titulo'] = 'Notificacion pagina web de Resolucion que legaliza facilidad de pago';
                        $valor = 122;
                        break;
                }
                $plantilla = $this->modelacuerdodepago_model->plantilla($valor);
                $this->data['nombre_plantilla'] = $plantilla;
                $datoAcuerdo = $this->modelacuerdodepago_model->consulta_acuerdo($this->data['acuerdo']);
                $datos = array(
                    'numResolucion' => $datoAcuerdo[0]['NRO_RESOLUCION'],
                    'razon' => $this->data['razon'],
                    'nit' => $this->data['nit'],
                    'deuda' => $datoAcuerdo[0]['VALOR_CAPITAL_FECHA'] + $datoAcuerdo[0]['VALO_RINTERESES_CAPITAL'],
                );
                $this->data['plantilla'] = template_tags("./uploads/plantillas/" . $plantilla, $datos);
                $this->load->view('acuerdodepago/crearResolucion', $this->data);
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
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepago/gestionarAcuerdo')) {
                $this->data['acuerdo'] = $this->input->post('acuerdo');
                $estado = $this->data['estado'] = $this->input->post('estado');
                $this->data['fecha'] = $this->input->post('fecha');
                $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
                $this->data['liquidacion'] = $this->input->post('liquidacion');
                $this->data['nit'] = $this->input->post('nit');
                $this->data['razon'] = $this->input->post('razon');
                $this->data['resolucion'] = $this->input->post('resolucion');
                $this->data['ajustado'] = $this->input->post('ajustado');
                $this->data['proceso'] = $this->acuerdopagodoc_model->get_plantilla_proceso($this->data['fiscalizacion']);
                $this->data['comentario'] = $this->acuerdopagodoc_model->get_comentarios_proceso($this->data['fiscalizacion']);


                if ($this->data['proceso']['NOMBRE_DOCUMENTO'] != "") {
                    $cFile = "uploads/acuerdopago/resolucionLegaliza/" . $this->data['fiscalizacion'];
                    $cFile .= "/" . $this->data['proceso']['NOMBRE_DOCUMENTO'];
                    $this->data['plantilla'] = read_template($cFile);
                }

                switch ($estado) {
                    case 1390:
                        $this->data['titulo'] = 'Resolución que legaliza facilidad de pago';
                        break;
                    case 1391:
                        $this->data['titulo'] = 'Resolución que legaliza facilidad de pago';
                        break;
                    case 1392:
                        $this->data['titulo'] = 'Resolución que legaliza facilidad de pago';
                        break;
                }

                $this->load->view('acuerdodepago/editarResolucion', $this->data);
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
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepago/gestionarAcuerdo')) {
                $this->data['acuerdo'] = $this->input->post('acuerdo');
                $estado = $this->data['estado'] = $this->input->post('estado');
                $this->data['fecha'] = $this->input->post('fecha');
                $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
                $this->data['liquidacion'] = $this->input->post('liquidacion');
                $this->data['nit'] = $this->input->post('nit');
                $this->data['razon'] = $this->input->post('razon');
                $this->data['resolucion'] = $this->input->post('resolucion');
                $this->data['ajustado'] = $this->input->post('ajustado');
                $this->data['proceso'] = $this->acuerdopagodoc_model->get_plantilla_proceso($this->data['fiscalizacion']);
                $this->data['comentario'] = $this->acuerdopagodoc_model->get_comentarios_proceso($this->data['fiscalizacion']);
                $this->data['motivos'] = $this->modelacuerdodepago_model->motivos();

                if ($this->data['proceso']['NOMBRE_DOCUMENTO'] != "") {
                    $cFile = "uploads/acuerdopago/resolucionLegaliza/" . $this->data['fiscalizacion'];
                    $cFile .= "/" . $this->data['proceso']['NOMBRE_DOCUMENTO'];
                    $this->data['plantilla'] = read_template($cFile);
                }


                switch ($estado) {
                    case 1394:
                        $this->data['titulo'] = 'Notificación Personal de Resolución que legaliza facilidad de pago';
                        break;
                    case 1395:
                        $this->data['titulo'] = 'Notificación Personal de Resolución que legaliza facilidad de pago';
                        break;
                    case 1396:
                        $this->data['titulo'] = 'Notificación Personal de Resolución que legaliza facilidad de pago';
                        break;
                    case 1397:
                        $this->data['titulo'] = 'Notificación Personal de Resolución que legaliza facilidad de pago';
                        break;
                    case 1401:
                        $this->data['titulo'] = 'Notificación por Correo de Resolución que legaliza facilidad de pago';
                        $valor = 122;
                        break;
                    case 1402:
                        $this->data['titulo'] = 'Notificación por Correo de Resolución que legaliza facilidad de pago';
                        $valor = 122;
                        break;
                    case 1403:
                        $this->data['titulo'] = 'Notificación por Correo de Resolución que legaliza facilidad de pago';
                        $valor = 122;
                        break;
                    case 1404:
                        $this->data['titulo'] = 'Notificación por Correo de Resolución que legaliza facilidad de pago';
                        $valor = 122;
                        break;
                    case 1408:
                        $this->data['titulo'] = 'Notificación por Pagina Web de Resolución que legaliza facilidad de pago';
                        $valor = 122;
                        break;
                    case 1409:
                        $this->data['titulo'] = 'Notificación por Pagina Web de Resolución que legaliza facilidad de pago';
                        $valor = 122;
                        break;
                }

                $this->load->view('acuerdodepago/editarNotificacion', $this->data);
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
        $fiscalizacion = $this->input->post('cod_fiscalizacion');
        $nit = $this->input->post('nit');
        $estado = $this->input->post('estado');
        @$adicionales = $this->input->post('adicionales');
        @$onbase = $this->input->post('onbase');
        @$fechanotificacion = $this->input->post('fechanotificacion');
        @$devolucion = $this->input->post('devolucion');
        @$motivo = $this->input->post('motivo');
        @$file = $this->input->post('file');
        $cRuta = "./uploads/acuerdopago/resolucionLegaliza/" . $fiscalizacion . "/";
        if (!is_dir($cRuta)) {
            mkdir($cRuta, 0777, true);
        }
        $sFile = create_template($cRuta, $this->input->post('notificacion'));

        $usuario_asignado = $this->modelacuerdodepago_model->consulta_acuerdo($acuerdo);
        $id_abogado = $usuario_asignado[0]['USUARIO_GENERA'];
        $datos["COD_FISCALIZACION"] = $this->input->post('cod_fiscalizacion');
        $datos["COD_RESPUESTA"] = $this->input->post('estado');
        $datos["NOMBRE_DOCUMENTO"] = $sFile;
        $datos["ABOGADO"] = ID_USER;
        $datos["EJECUTOR"] = ID_COORDINADOR;
        $datos["COMENTADO_POR"] = ID_USER;
        $datos["COMENTARIO"] = $this->input->post('comentarios');
        $datos["TITULO_ENCABEZADO"] = $this->input->post('Titulo_Encabezado');
        $datos["RADICADO_ONBASE"] = $onbase;
        $datos["FECHA_ONBASE"] = $fechanotificacion;

//        echo $id_abogado."<br>";
//        echo $estado."<br>";
        switch ($estado) {
            case 16:
                $tiporespuesta = 1390; //Resolucion que legaliza el acuerdo de pago Creado
                $tipogestion = 657;
                $comentarios = 'Resolucion que legaliza la facilidad de pago Creado';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodoc_model->insertar_comunicacion($datos);
                break;
            case 1390:
                if ($adicionales == 1):
                    $tiporespuesta = 1391; //Resolucion que legaliza el acuerdo de pago pre-aprobado
                    $tipogestion = 657;
                    $comentarios = 'Resolucion que legaliza la facilidad de pago pre-aprobado';
                    $asignado = $id_abogado;
                else:
                    $tiporespuesta = 1392; //Resolucion que legaliza el acuerdo de pago rechazado
                    $tipogestion = 657;
                    $comentarios = 'Resolucion que legaliza la facilidad de pago rechazado';
                    $asignado = $id_abogado;
                endif;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodoc_model->insertar_comunicacion($datos);
                break;
            case 1391:
                $tipoexpediente = 3;
                $carpeta = 'acuerdopago/legalizacion';
                $docFile = $this->do_upload($nit, $fiscalizacion, $carpeta);
                $dato = $this->modelacuerdodepago_model->eliminar_plantillas($fiscalizacion);
                $asignado = ID_USER;
                $tiporespuesta = 1393; //Resolucion que legaliza el acuerdo de pago aprobado
                $tipogestion = 657;
                $comentarios = 'Resolucion que legaliza la facilidad de pago Aprobado';
                $ruta = 'uploads/acuerdopago/legalizacion/' . $nit . '/pdf/' . $fiscalizacion . '/' . $docFile['upload_data']['file_name'];

                $data = array(
                    'RUTA_DOCUMENTO' => $ruta,
                    'NOMBRE_DOCUMENTO' => $docFile['upload_data']['file_name'],
                    'COD_RESPUESTAGESTION' => $tiporespuesta,
                    'COD_TIPO_EXPEDIENTE' => $tipoexpediente, //resolucion
                    'ID_USUARIO' => $id_abogado,
                    'FECHA_RADICADO' => date('d/m/Y'),
                    'COD_FISCALIZACION' => $fiscalizacion,
                    'RADICADO_ONBASE' => $onbase,
                    'FECHA_ONBASE' => $fechanotificacion
                );

                $msj = $this->modelacuerdodepago_model->insertar_expediente($data);
                break;
            case 1392:
                $tiporespuesta = 1390; //Resolucion que legaliza el acuerdo de pago Creado
                $tipogestion = 657;
                $comentarios = 'Resolucion que legaliza la facilidad de pago Creado';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodoc_model->insertar_comunicacion($datos);
                break;
            case 1393:
                $tiporespuesta = 1394; //Notificacion Personal de Resolucion que legaliza acuerdo de pago generada
                $tipogestion = 658;
                $comentarios = 'Notificacion Personal de Resolucion que legaliza la facilidad de pago generada';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodoc_model->insertar_comunicacion($datos);
                break;
            case 1400:
                $tiporespuesta = 1394; //Notificacion Personal de Resolucion que legaliza acuerdo de pago generada
                $tipogestion = 658;
                $comentarios = 'Notificacion Personal de Resolucion que legaliza la facilidad de pago generada';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodoc_model->insertar_comunicacion($datos);
                break;
            case 1394:
                if ($adicionales == 1):
                    $tiporespuesta = 1395; //Notificacion Personal de Resolucion que legaliza acuerdo de pago pre-aprobada
                    $tipogestion = 658;
                    $comentarios = 'Notificacion Personal de Resolucion que legaliza la facilidad de pago pre-aprobada';
                    $asignado = $id_abogado;
                else:
                    $tiporespuesta = 1396; //Notificacion Personal de Resolucion que legaliza acuerdo de pago rechazada
                    $tipogestion = 658;
                    $comentarios = 'Notificacion Personal de Resolucion que legaliza la facilidad de pago rechazada';
                    $asignado = $id_abogado;
                endif;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodoc_model->insertar_comunicacion($datos);
                break;
            case 1396:
                $tiporespuesta = 1394; //Resolucion que legaliza el acuerdo de pago Creado
                $tipogestion = 658;
                $comentarios = 'Notificacion Personal de Resolucion que legaliza la facilidad de pago generada';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodoc_model->insertar_comunicacion($datos);
                break;
            case 1395:
                $tiporespuesta = 1397; //Notificacion Personal de Resolucion que legaliza acuerdo de pago enviada
                $tipogestion = 658;
                $comentarios = 'Notificacion Personal de Resolucion que legaliza la facilidad de pago enviada';
                $asignado = $id_abogado;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodoc_model->insertar_comunicacion($datos);
                break;
            case 1397:
                $tipoexpediente = 7;
                if ($devolucion == 1):
                    if ($motivo == 4):
                        $tiporespuesta = 1400; //Notificacion Personal de Resolucion que legaliza acuerdo de pago devuelta por direccion erronea
                        $tipogestion = 658;
                        $comentarios = 'Notificacion Personal de Resolucion que legaliza la facilidad de pago devuelta por direccion erronea';
                    else :
                        $tiporespuesta = 1399; //Notificacion Personal de Resolucion que legaliza acuerdo de pago devuelta
                        $tipogestion = 658;
                        $comentarios = 'Notificacion Personal de Resolucion que legaliza la facilidad de pago devuelta';
                    endif;
                else:
                    $tiporespuesta = 1398; //Notificacion Personal de Resolucion que legaliza acuerdo de pago entregada
                    $tipogestion = 658;
                    $comentarios = 'Notificacion Personal de Resolucion que legaliza facilidad de pago entregada';
                endif;
                $carpeta = 'acuerdopago/resolucionLegaliza/' . $fiscalizacion . '/';
                $docFile = $this->do_upload($nit, $fiscalizacion, $carpeta);
                $dato = $this->modelacuerdodepago_model->eliminar_plantillas($fiscalizacion);
                $asignado = ID_USER;
                $ruta = 'uploads/acuerdopago/legalizacion/' . $nit . '/pdf/' . $fiscalizacion . '/' . $docFile['upload_data']['file_name'];

                $data = array(
                    'RUTA_DOCUMENTO' => $ruta,
                    'NOMBRE_DOCUMENTO' => $docFile['upload_data']['file_name'],
                    'COD_RESPUESTAGESTION' => $tiporespuesta,
                    'COD_TIPO_EXPEDIENTE' => $tipoexpediente, //resolucion
                    'ID_USUARIO' => $id_abogado,
                    'FECHA_RADICADO' => date('d/m/Y'),
                    'COD_FISCALIZACION' => $fiscalizacion,
                    'RADICADO_ONBASE' => $onbase,
                    'FECHA_ONBASE' => $fechanotificacion
                );

                $msj = $this->modelacuerdodepago_model->insertar_expediente($data);
                break;
            case 1399:
                $tiporespuesta = 1401; //Notificacion por correo de Resolucion que legaliza acuerdo de pago generada
                $tipogestion = 659;
                $comentarios = 'Notificacion por correo de Resolucion que legaliza la facilidad de pago generada';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodoc_model->insertar_comunicacion($datos);
                break;
            case 1401:
                if ($adicionales == 1):
                    $tiporespuesta = 1402; //Notificacion por correo de Resolucion que legaliza acuerdo de pago pre-aprobada
                    $tipogestion = 659;
                    $comentarios = 'Notificacion por correo de Resolucion que legaliza la facilidad de pago pre-aprobada';
                    $asignado = $id_abogado;
                else:
                    $tiporespuesta = 1403; //Notificacion por correo de Resolucion que legaliza acuerdo de pago rechazada
                    $tipogestion = 659;
                    $comentarios = 'Notificacion por correo de Resolucion que legaliza la facilidad de pago rechazada';
                    $asignado = $id_abogado;
                endif;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodoc_model->insertar_comunicacion($datos);
                break;
            case 1403:
                $tiporespuesta = 1401; //Notificacion por correo de Resolucion que legaliza acuerdo de pago generada
                $tipogestion = 659;
                $comentarios = 'Notificacion por correo de Resolucion que legaliza la facilidad de pago generada';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodoc_model->insertar_comunicacion($datos);
                break;
            case 1402:
                $tiporespuesta = 1404; //Notificacion por correo de Resolucion que legalliza acuerdo de pago enviada
                $tipogestion = 659;
                $comentarios = 'Notificacion por correo de Resolucion que legaliza la facilidad de pago enviada';
                $asignado = $id_abogado;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodoc_model->insertar_comunicacion($datos);
                break;
            case 1404:
                $tipoexpediente = 7;
                if ($devolucion == 1):
                    if ($motivo == 4):
                        $tiporespuesta = 1407; //Notificacion por correo de Resolucion que legaliza acuerdo de pago devuelta por direccion erronea
                        $tipogestion = 659;
                        $comentarios = 'Notificacion por correo de Resolucion que legaliza la facilidad de pago devuelta por direccion erronea';
                    else :
                        $tiporespuesta = 1406; //Notificacion por correo de Resolucion que legaliza acuerdo de pago devuelta
                        $tipogestion = 659;
                        $comentarios = 'Notificacion por correo de Resolucion que legaliza la facilidad de pago devuelta';
                    endif;
                else:
                    $tiporespuesta = 1405; //Notificacion por correo de Resolucion que legaliza acuerdo de pago entregada
                    $tipogestion = 659;
                    $comentarios = 'Notificacion por correo de Resolucion que legaliza la facilidad de pago entregada';
                endif;
                $carpeta = 'acuerdopago/legalizacion';
                $docFile = $this->do_upload($nit, $fiscalizacion, $carpeta);
                $dato = $this->modelacuerdodepago_model->eliminar_plantillas($fiscalizacion);
                $asignado = ID_USER;
                $ruta = 'uploads/acuerdopago/legalizacion/' . $nit . '/pdf/' . $fiscalizacion . '/' . $docFile['upload_data']['file_name'];

                $data = array(
                    'RUTA_DOCUMENTO' => $ruta,
                    'NOMBRE_DOCUMENTO' => $docFile['upload_data']['file_name'],
                    'COD_RESPUESTAGESTION' => $tiporespuesta,
                    'COD_TIPO_EXPEDIENTE' => $tipoexpediente, //resolucion
                    'ID_USUARIO' => $id_abogado,
                    'FECHA_RADICADO' => date('d/m/Y'),
                    'COD_FISCALIZACION' => $fiscalizacion,
                    'RADICADO_ONBASE' => $onbase,
                    'FECHA_ONBASE' => $fechanotificacion
                );

                $msj = $this->modelacuerdodepago_model->insertar_expediente($data);
                break;
            case 1407:
                $tiporespuesta = 1401; //Notificacion por correo de Resolucion que legaliza acuerdo de pago generada
                $tipogestion = 659;
                $comentarios = 'Notificacion por correo de Resolucion que legaliza la facilidad de pago generada';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodoc_model->insertar_comunicacion($datos);
                break;
            case 1406:
                $tiporespuesta = 1408; //Notificacion pagina web de Resolucion que legaliza acuerdo de pago generada
                $tipogestion = 660;
                $comentarios = 'Notificacion pagina web de Resolucion que legaliza  la facilidad de pago generada';
                $asignado = ID_COORDINADOR;
                $datos["COD_RESPUESTA"] = $tiporespuesta;
                $this->acuerdopagodoc_model->insertar_comunicacion($datos);
                break;
            case 1408:
                $tiporespuesta = 1409; //Notificacion pagina web de Resolucion que legaliza acuerdo de pago pre-aprobada
                $tipogestion = 660;
                $comentarios = 'Notificacion pagina web de Resolucion que legaliza  la facilidad de pago pre-aprobada';
                $asignado = $id_abogado;
                $this->acuerdopagodoc_model->insertar_comunicacion($datos);
                break;
            case 1409:
                $tipoexpediente = 8;
                $carpeta = 'acuerdopago/legalizacion';
                $docFile = $this->do_upload($nit, $fiscalizacion, $carpeta);
                $dato = $this->modelacuerdodepago_model->eliminar_plantillas($fiscalizacion);
                $asignado = ID_USER;
                $tiporespuesta = 1411; //Notificacion pagina web de Resolucion que legaliza acuerdo de pago enviada
                $tipogestion = 660;
                $comentarios = 'Notificacion pagina web de Resolucion que legaliza  la facilidad de pago enviada';
                $ruta = 'uploads/acuerdopago/legalizacion/' . $nit . '/pdf/' . $fiscalizacion . '/' . $docFile['upload_data']['file_name'];

                $data = array(
                    'RUTA_DOCUMENTO' => $ruta,
                    'NOMBRE_DOCUMENTO' => $docFile['upload_data']['file_name'],
                    'COD_RESPUESTAGESTION' => $tiporespuesta,
                    'COD_TIPO_EXPEDIENTE' => $tipoexpediente, //resolucion
                    'ID_USUARIO' => $id_abogado,
                    'FECHA_RADICADO' => date('d/m/Y'),
                    'COD_FISCALIZACION' => $fiscalizacion,
                    'RADICADO_ONBASE' => $onbase,
                    'FECHA_ONBASE' => $fechanotificacion
                );

                $msj = $this->modelacuerdodepago_model->insertar_expediente($data);
                break;
        }
        //die;


        $datos2["USUARIO_ASIGNADO"] = $asignado;
        $datos2["COD_FISCALIZACION"] = $fiscalizacion;
        $datos2["COD_RESPUESTA"] = $tiporespuesta;
        $this->acuerdopagodoc_model->actualizacion_acuerdopago($datos2);

        $cod_respuesta = $this->trazabilidad($tipogestion, $tiporespuesta, $fiscalizacion, $nit, $comentarios);

        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo se ha Modificado correctamente.</div>');
        redirect(base_url() . 'index.php/acuerdodepago/gestionarAcuerdo');
    }

    function eliminarcuantias() {
        $id = $this->input->post('id');
        $eliminarcuantia = $this->modelacuerdodepago_model->eliminacioncuantia($id);
        parent . window . location . reload();
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
        $this->load->view('acuerdodepago/generarrecibodepagocuotainicial', $this->data);
    }

    function gestionar_documento_juridico() {
        $this->data['post'] = $this->input->post();
        $txt = $this->ejecutoriaactoadmin_model->plantilla(121);
        if (!empty($txt)) {
            $info = $this->ejecutoriaactoadmin_model->informacion_plantilla_coactivo($this->data['post']['id']);
            $reemplazo = array();
            $reemplazo['NOMBRE_EMPRESA'] = $info['NOMBRE_EMPRESA'];
            $reemplazo['NIT'] = $info['CODEMPRESA'];
            $reemplazo['NRO_RESOLUCION'] = $info['NUMERO_RESOLUCION'];
            $reemplazo['FECHA_RESOLUCION'] = $info['FECHA_CREACION'];
            $reemplazo['FECHA_EJECUTORIA'] = $info['FECHA_EJECUTORIA'];
            $reemplazo['REGIONAL'] = $info['NOMBRE_REGIONAL'];
            $reemplazo['ciudad'] = $info['CIUDAD'];
            $reemplazo['FECHA'] = date('d/m/Y');
            $mes = array('', 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
            $reemplazo['mes'] = $mes[date('m')];
            $reemplazo['ano'] = date('Y');
            $reemplazo['dias'] = date('d');
            $reemplazo['COORDINADOR_REGIONAL'] = '';
            $reemplazo['USUARIO'] = NOMBRE_COMPLETO;

            $tabla = "<table><thead>"
                    . "<th>N Resolución</th>"
                    . "<th>Tipo de Obligación</th>"
                    . "<th>Valor</th>"
                    . "<th>Razón Social</th>"
                    . "<th>Nit de la Empresa</th>"
                    . "</thead>"
                    . "<tr>"
                    . "<td>" . $info['NUMERO_RESOLUCION'] . "</td>"
                    . "<td>" . $info['NOMBRE_CONCEPTO'] . "</td>"
                    . "<td>" . $info['SALDO_DEUDA'] . "</td>"
                    . "<td>" . $info['NOMBRE_EMPRESA'] . "</td>"
                    . "<td>" . $info['CODEMPRESA'] . "</td>"
                    . "<tr></table>";
            $reemplazo['tabla'] = $tabla;
            $this->data['txt'] = template_tags("./uploads/plantillas/" . $txt, $reemplazo);
        }
        $this->data['acuerdo'] = $this->input->post('acuerdo');
        $this->load->view('acuerdodepago/gestionar_documento_juridico', $this->data);
    }

    function generarrecibo() {

        $this->data['valorcuota'] = $this->input->post('valorcuota');
        $this->data['saldofinal'] = $this->input->post('saldofinal');
        $this->data['saldocapital'] = $this->input->post('saldocapital');
        $this->data['razon'] = $this->input->post('razon');
        $this->data['fecha'] = $this->input->post('fecha');
        $this->data['acuerdo'] = $this->input->post('acuerdo');
        $this->data['interes'] = $this->input->post('intereses');
        $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
        $this->data['liquidacion'] = $this->input->post('liquidacion');
        $this->data['nit'] = $this->input->post('nit');
        //$this->data['totaldeuda'] = $this->modelacuerdodepago_model->totalliquidacion($this->data['liquidacion']);
        $this->data['totaldeuda'] = recalcularLiquidacion_acuerdoPago($this->data['liquidacion']);
        $this->data['porcentajeInicial'] = $this->modelacuerdodepago_model->cuotainicial();
        $fecha_hoy = date("d/m/Y");
        $tasaEfectiva = $this->modelacuerdodepago_model->tasaEfectiva($fecha_hoy);
        $this->data['superintendencia'] = $tasaEfectiva[0]['TASA_SUPERINTENDENCIA'];
        $this->data['nit'] = $this->input->post('nit');
        $this->load->view('acuerdodepago/cuotaInicial', $this->data);
    }

    function generarTotalrecibo() {
        if ($this->ion_auth->logged_in()) {
            var_dump($_POST);
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
            $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
            $this->data['liquidacion'] = $this->input->post('liquidacion');
            $this->data['nit'] = $this->input->post('nit');
            $this->load->view('acuerdodepago/recibosCuota', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function gestionarAcuerdo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepago/gestionarAcuerdo')) {
                $this->template->set('title', 'Acuerdo de pago');
                $this->data['title'] = 'Verificación Facilidad de Pago';
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ID_USER'] = ID_USER;
                $this->data['acuerdosautorizar'] = $this->modelacuerdodepago_model->consultarResoluciones(ID_CARGO, REGIONAL_USER);
                $this->template->load($this->template_file, 'acuerdodepago/acuerdosPago', $this->data);
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
        $this->modelacuerdodepago_model->porcentaje($porcentaje, $idusuario);
    }

    function guardarAcuerdo() {
        $post = $this->input->post();
        $acuerdo = $this->input->post('acuerdo');
        $datoValida = $this->input->post('dato_valida');
        $fiscalizacion = $this->input->post('fiscalizacion');
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
        $acuerdos = $this->modelacuerdodepago_model->consultaProyeccion($acuerdo);


        $data = array(
            'COD_RESPUESTA' => $tiporespuesta,
            'VALOR_CUOTA' => round($post['valorcuota'][1]),
            'NUMERO_CUOTAS' => count($post['cuota'])
        );

        $cod_respuesta = $this->trazabilidad($tipogestion, $tiporespuesta, $fiscalizacion, $nit, $comentarios);
        $insert = $this->modelacuerdodepago_model->actualizar_acuerdo($data, $acuerdo, $tipo = 'guardar');
        $this->modelacuerdodepago_model->actualizacuotas($acuerdo);
        $data = array();
        for ($i = 1; $i < count($post['cuota']); $i++) {
            $this->modelacuerdodepago_model->insertacuerdopagocuotas(
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
        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo se ha Modificado correctamente.</div>');
        redirect(base_url() . 'index.php/acuerdodepago/gestionarAcuerdo');
    }

    function guardagarantiaingreso() {
        $post = $this->input->post();
        $acuerdo = $this->input->post('acuerdo');
        $fiscalizacion = $this->input->post('fiscalizacion');
        $nit = $this->input->post('nit');
        $resolucion = $this->input->post('resolucion');
        $liquidacion = $this->input->post('liquidacion');
        $avaluo = 0;


        for ($i = 0; $i < $post['contador']; $i++) {
            $cod_campo = NULL;
            for ($j = 0; $j < $post['datos'][$i]; $j++) {
                @$cod_campo .= $post['valorgarantia' . $j][$i] . "-";
            }

            $datos = array(
                'VALOR_CAMPO' => $cod_campo,
                'VALOR_AVALUO' => $post['vavaluo'][$i],
                'VALOR_COMERCIAL' => $post['vcomercial'][$i],
                'COD_CAMPO' => $garantia,
                'COD_TIPO_GARANTIA' => $post['garantia'][$i],
                'CODEMPRESA' => $nit,
                'COD_ACUERDO_PAGO' => $acuerdo,
                'NUM_LIQUIDACION' => $liquidacion,
                'COD_USUARIO' => ID_USER,
                'ESTADO' => '0'
            );
            $this->modelacuerdodepago_model->guardagarantia($datos);
            $avaluo = $avaluo + $post['vavaluo'][$i];
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

        $cod_respuesta = $this->trazabilidad($tipogestion, $tiporespuesta, $fiscalizacion, $nit, $comentarios);
        $insert = $this->modelacuerdodepago_model->actualizar_acuerdo($data, $acuerdo, $tipo);
        if ($insert == TRUE) {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo se ha Modificado correctamente.</div>');
            redirect(base_url() . 'index.php/acuerdodepago/gestionarAcuerdo');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo no se ha podido modificar.</div>');
            redirect(base_url() . 'index.php/acuerdodepago/gestionarAcuerdo');
        }
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
        $fiscalizacion = $this->input->post('fiscalizacion'); //fiscalizacion
        $nit = $this->input->post('nit'); //nit

        $data = array(
            'NRO_ACUERDOPAGO' => $acuerdo,
            'PROYACUPAG_NUMCUOTA' => $numcuota,
            'PROYACUPAG_VALORCUOTA' => round($totalcuota),
            'PROYACUPAG_FECHALIMPAGO' => $fpcuota,
            'PROYACUPAG_SALDOCAPITAL' => round($totalPagar),
            'PROYACUPAG_VALORINTERESESMORA' => 0,
            'PROYACUPAG_ESTADO' => 0,
            'PROACUPAG_CARTAAUT' => 1,
            'PROYACUPAG_CAPITALDEBE' => round($totalCapital),
            'PROYACUPAG_CAPITAL_CUOTA' => round($totalPagar),
            'PROYACUPAG_INTCORRIENTE' => round($totalIntereses),
            'PROYACUPAG_INTCORRIENTE_CUOTA' => round($pagarIntereses),
            'PROYACUPAG_INTACUERDO' => 0,
            'PROYACUPAG_SALDO_INTCORRIENTE' => round($pagarIntereses),
            'PROYACUPAG_SALDO_INTACUERDO' => 0,
            'PROYACUPAG_SALDO_CUOTA' => round($totalcuota),
        );

        $tipogestion = 12;
        $tiporespuesta = 1261;
        $insert = $this->modelacuerdodepago_model->cuotaCero($data);
        $update = $this->modelacuerdodepago_model->valoresCero($acuerdo, $fpcuota, round($totaldeuda), round($totalPagar), round($totalCapital), round($totalIntereses), round($porcentaje), $superintendencia);
        $comentarios = 'Elaborar Recibo de Cuota Cero';
        $cod_respuesta = $this->trazabilidad($tipogestion, $tiporespuesta, $fiscalizacion, $nit, $comentarios);
    }

    function guardaexcepcion() {
        $fiscalizacion = $this->input->post('fiscalizacion');
        $observacion = $this->input->post('observacion');
        $nit = $this->input->post('nit');
        $autorizacion = $this->input->post('autorizacion');
        $acuerdo = $this->input->post('acuerdo');
        $cantSolicitudes = $this->input->post('cantSolicitudes');

        $existencia = $this->modelacuerdodepago_model->consultaexcepcion($fiscalizacion);
        $excepcion = $this->modelacuerdodepago_model->guardaexcepcion($fiscalizacion, $observacion, $acuerdo);
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

        $cod_respuesta = $this->trazabilidad($tipogestion, $tiporespuesta, $fiscalizacion, $nit, $comentarios);
        $insert = $this->modelacuerdodepago_model->actualizar_acuerdo($data, $acuerdo, $tipo);
        if ($excepcion == TRUE) {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo se ha Modificado correctamente.</div>');
            redirect(base_url() . 'index.php/acuerdodepago/gestionarAcuerdo');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo no se ha podido modificar.</div>');
            redirect(base_url() . 'index.php/acuerdodepago/gestionarAcuerdo');
        }
    }

    function guardacamposgarantias() {
        $idgarantia = $this->input->post('listagarantia');
        $campogarantia = $this->input->post('campogarantia');

        for ($j = 0; $j < count($idgarantia); $j++) {

            $existegarantia = $this->modelacuerdodepago_model->existegarantia($idgarantia[$j], $campogarantia[$j]);
            echo $existegarantia;
            if ($existegarantia == 0) {
                $this->modelacuerdodepago_model->insertacamposgarantias($idgarantia[$j], $campogarantia[$j]);
            }
        }
    }

    function guardar_paso_juridico() {
        $this->data['post'] = $this->input->post();
        $post = $this->data['post'];
        $route_template = 'uploads/acuerdopagodoc/' . $post['cod_fis'] . '/ejecutoriaactoadmin/';
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
        $cod_respuesta = $this->trazabilidad($tipogestion, $tiporespuesta, $post['cod_fis'], $post['nit'], $comentarios);
        $dato = array('COD_RESPUESTA' => $tiporespuesta,);
        $insert = $this->modelacuerdodepago_model->actualizar_acuerdo($dato, $acuerdo, $tipo);
        //$codgest = trazar(418, 1093, $post['cod_fis'], $post['nit'], "S");
        echo "<script>window.history.back()</script>";
    }

    function guardar_verificacion() {
        if ($this->ion_auth->logged_in()) {
            @$acuerdo = $this->input->post('acuerdo');
            @$tipo = $this->input->post('tipo');
            $fiscalizacion = $this->input->post('fiscalizacion');
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
                    $usuario_asignado = $this->modelacuerdodepago_model->consulta_acuerdo($acuerdo);
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
                    $updateExcep = $this->modelacuerdodepago_model->actualizaautorizacionadministrativo($fiscalizacion, $verificacion, $observacion);
                    $tipo = 'autoriza_garantias';
                    break;
                case 'proyecto_acuerdo':
                    $tipogestion = 14;
                    $tiporespuesta = 20;
                    $comentarios = 'Formato de Acuerdo de pago Generado';
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                    );
                    $consulta = $this->modelacuerdodepago_model->consultaDatos($acuerdo);
                    $fiscalizacion = $consulta[0]['COD_FISCALIZACION'];
                    $nit = $consulta[0]['NITEMPRESA'];
                    $tipo = NULL;
                    break;
                case 'deudor_acuerdo':
                    if ($verificacion == 'S') {
                        $tipogestion = 516;
                        $tiporespuesta = 1270;
                        $comentarios = 'Deudor Firma Acuerdo de Pago';
                    } else {
                        $tipogestion = 516;
                        $tiporespuesta = 1271;
                        $comentarios = 'Deudor No Firma Acuerdo de Pago';
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
                        $comentarios = 'Acuerdo de Pago Terminado';
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
                    $comentarios = 'Carta de Imcumplimiento Acuerdo de Pago Generada';
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
                        $comentarios = 'El Recurso es Favorable al Deudo';
                    } else {
                        $tipogestion = 535;
                        $tiporespuesta = 1303;
                        $comentarios = 'El Recurso es No Favorable al Deudo';
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
            }
            $cod_respuesta = $this->trazabilidad($tipogestion, $tiporespuesta, $fiscalizacion, $nit, $comentarios);

            $insert = $this->modelacuerdodepago_model->actualizar_acuerdo($data, $acuerdo, $tipo);
            if ($insert == TRUE) {
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo se ha Modificado correctamente.</div>');
                redirect(base_url() . 'index.php/acuerdodepago/gestionarAcuerdo');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo no se ha podido modificar.</div>');
                redirect(base_url() . 'index.php/acuerdodepago/gestionarAcuerdo');
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

        $this->data['proyecto'] = $this->modelacuerdodepago_model->proyectaracuerdo($acuerdo);
        $this->data['datostabla'] = $this->modelacuerdodepago_model->consultacuotasacuerdopago($nit, $resolucion, $liquidacion);
        $info = $this->numeros_letras_model->ValorEnLetras($this->data['proyecto']->result_array[0]['VALOR_TOTAL_FINANCIADO'], "PESOS");
        $this->data['info'] = $info;
        $valor = $this->numeros_letras_model->ValorEnLetras($this->data['proyecto']->result_array[0]['PROYACUPAG_VALORCUOTA'], "PESOS");
        $this->data['valor'] = $valor;
        $cuota = $this->numeros_letras_model->ValorEnLetras($this->data['proyecto']->result_array[0]['VALOR_CUOTA'], "PESOS");
        $this->data['cuota'] = $cuota;
        $this->template->load($this->template_file, 'acuerdodepago/proyectoacuerdodepago', $this->data);
        //$this->load->view('acuerdodepago/proyectoacuerdodepago', $this->data);
    }

    function muestrahtmlcamposgarantias() {
        $this->data['garantia'] = $_POST['idgarantia'];
        $this->data['campos'] = $this->modelacuerdodepago_model->consultcamposgarantia($this->data['garantia']);
        $this->load->view('acuerdodepago/muestrahtmlcamposgarantias', $this->data);
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
            $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
            $this->data['razon'] = $this->input->post('razon');
            $fecha_hoy = date("d/m/Y");
            $this->data[] = array();
            $this->template->set('title', 'Consulta Proyeccion');
            $this->data['title'] = 'Consulta Proyeccion';
            $this->data['message'] = $this->session->flashdata('message');
            $this->data['concepto'] = $this->modelacuerdodepago_model->consultatipoconcepto();
            $this->data['tasaEfectiva'] = $this->modelacuerdodepago_model->tasaEfectiva($fecha_hoy);
            $this->template->load($this->template_file, 'acuerdodepago/proyeccion', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function proyeccionafuturo() {
        if ($this->ion_auth->logged_in()) {
            $this->data['numAcuerdo'] = $this->input->post('acuerdo');
            $this->data['nit'] = $this->input->post('nit');
            $this->data['liquidacion'] = $this->input->post('liquidacion');
            $this->data['resolucion'] = $this->input->post('resolucion');
            $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
            $this->data['razon'] = $this->input->post('razon');
            $this->data['idConcepto'] = $this->input->post('concepto');
            $fecha_hoy = date("d/m/Y");
            $this->data[] = array();
            $this->template->set('title', 'Consulta Proyeccion');
            $this->data['title'] = 'Consulta Proyeccion';
            $this->data['message'] = $this->session->flashdata('message');
            $this->data['concepto'] = $this->modelacuerdodepago_model->consultatipoconcepto();
            $this->data['tasaEfectiva'] = $this->modelacuerdodepago_model->tasaEfectiva($fecha_hoy);
            $this->template->load($this->template_file, 'acuerdodepago/proyeccionafuturo', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function subirDocumento() {
        $nit = $this->input->post('nit1');
        $tipo = $this->input->post('resolucion1');
        $liquidacion = $this->input->post('liquidacion');
        $fiscalizacion = $this->input->post('fiscalizacion');
        $conceptos = $this->input->post('concepto');
        $tipogestion = 272;
        $tiporespuesta = 1173;
        $mensaje = 'Envio de Informacion para Acuerdo de Pago';
        $carpeta = 'acuerdopago/cartaacuerdopagolegalizar';
        $docFile = $this->do_upload($nit, $tipo, $carpeta);


        if ($docFile["error"]) {
            $this->session->set_flashdata('message', '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button>El tamaño del archivo Excede el Límite permitido.</div>');
            redirect(base_url() . 'index.php/acuerdodepago/crearAcuerdo');
        } else {
            $ruta = 'uploads/acuerdodepago/legalizar/' . $fiscalizacion . '/pdf/' . $tipo . '/' . $docFile["upload_data"]["file_name"];
            //$this->load->model('expediente_model');
            //$this->expediente_model->guarda_expediente($fiscalizacion, $tiporespuesta, $docFile["upload_data"]["file_name"], $ruta, FALSE, FALSE, 2, 'Acuerdo de Pago', ID_USER);
            $data = array(
                'NITEMPRESA' => $nit,
                'NRO_RESOLUCION' => $tipo,
                'NRO_LIQUIDACION' => $liquidacion,
                'COD_FISCALIZACION' => $fiscalizacion,
                'COD_RESPUESTA' => $tiporespuesta,
                'USUARIO_GENERA' => ID_USER,
                'COD_CONCEPTO_COBRO' => $conceptos,
                'FECHA_CREACION' => date("d/m/Y"),
                'COD_REGIONAL' => REGIONAL_USER,
                'ESTADOACUERDO' => 1,
                'JURIDICO' => 0,
                'USUARIO_ASIGNADO' => ID_USER,
            );

            $cod_respuesta = $this->trazabilidad($tipogestion, $tiporespuesta, $fiscalizacion, $nit, $mensaje);
            $archivo = $docFile["upload_data"]["file_name"];
            $documentoresolucion = $this->modelacuerdodepago_model->documentoresolucion($nit, $tipo, $archivo);
            if ($documentoresolucion == true) {
                $insert = $this->modelacuerdodepago_model->insertAcuerdo($data);
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Documento Subido correctamente.</div>');
                redirect(base_url() . 'index.php/acuerdodepago/gestionarAcuerdo');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Documento no se ha adjuntado correctamente.</div>');
                redirect(base_url() . 'index.php/acuerdodepago/gestionarAcuerdo');
            }
        }
    }

    function verificar_acuerdo() {
//        error_reporting(E_ALL);
//        echo "<hr>";gestionarAcuerdo
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepago/gestionarAcuerdo')) {
                $this->data['acuerdo'] = $this->input->post('acuerdo');
                $this->data['estado'] = $this->input->post('estado');
                $this->data['fecha'] = $this->input->post('fecha');
                $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
                $this->data['liquidacion'] = $this->input->post('liquidacion');
                $this->data['nit'] = $this->input->post('nit');
                $this->data['razon'] = $this->input->post('razon');
                $this->data['resolucion'] = $this->input->post('resolucion');
                $this->data['tipo'] = $this->input->post('tipo');
                $this->data['ajustado'] = $this->input->post('ajustado');
                $this->data['excepcion'] = $this->modelacuerdodepago_model->excepcionesAcuerdo($this->data['fiscalizacion']);
                //@$deuda = $this->modelacuerdodepago_model->consultadeuda($this->data['liquidacion']);                
                //echo "<br>1";
                $deuda = recalcularLiquidacion_acuerdoPago($this->data['liquidacion']);
                if (!empty($deuda['capital'])):
                    @$this->data['saldocapital'] = $deuda['capital'];
                else:
                    @$this->data['saldocapital'] = 0;
                endif;
                //echo "<br>4";
                //echo $this->data['saldocapital'];
                $porcentaje = $this->modelacuerdodepago_model->cuotainicial();
                $porcentajeTasa = $porcentaje[0]['PORCENTAJE'] / 100;
                $this->data['cuotaDeuda'] = ($this->data['saldocapital'] - ($this->data['saldocapital'] * $porcentajeTasa));
                $this->data['pago_inicial'] = $this->modelacuerdodepago_model->verificaCero($this->data['acuerdo']);
                $this->load->view('acuerdodepago/verifica_acuerdo', $this->data);
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
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepago/gestionarAcuerdo')) {
                $this->data['nit'] = $this->input->post('nit');
                $this->data['resolucion'] = $this->input->post('resolucion');
                $this->data['estado'] = $this->input->post('estado');
                $this->data['acuerdo'] = $this->input->post('acuerdo');
                $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
                $this->data['tipo'] = $this->input->post('tipo');
                @$this->data['excepcion'] = $this->modelacuerdodepago_model->excepcionesAcuerdo($this->data['fiscalizacion']);

                $this->load->view('acuerdodepago/verifica_garantias', $this->data);
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
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepago/gestionarAcuerdo')) {
                $nit = $this->input->post('nit');
                $liquidacion = $this->input->post('liquidacion');
                $resolucion = $this->input->post('resolucion');
                $this->data['nit'] = $this->input->post('nit');
                $this->data['resolucion'] = $this->input->post('resolucion');
                $this->data['acuerdo'] = $this->input->post('acuerdo');
                $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
                $this->data['razon'] = $this->input->post('razon');
                $this->data['tipo'] = $this->input->post('tipo');
                $this->data['datostabla'] = $this->modelacuerdodepago_model->consultacuotasacuerdopago($nit, $resolucion, $liquidacion);
                $this->load->view('acuerdodepago/verificar_pago', $this->data);
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
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acuerdodepago/gestionarAcuerdo')) {
                $nit = $this->input->post('nit');
                $liquidacion = $this->input->post('liquidacion');
                $this->data['acuerdo'] = $this->input->post('acuerdo');
                $this->data['nit'] = $nit;
                $this->data['liquidacion'] = $liquidacion;
                $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
                $totalliq = $this->modelacuerdodepago_model->totalliquidacion($liquidacion);
                $this->data['totalliquidacion'] = $totalliq[0]['SALDO_DEUDA'];
                $uvt = $this->modelacuerdodepago_model->uvt();
                $valorUvt = $uvt[0]['VALOR_UVT'];
                $this->data['valorTotalUvt'] = $this->data['totalliquidacion'] / $valorUvt;

                $this->data['fiscalizacion'] = $totalliq[0]['COD_FISCALIZACION'];
                $this->data[] = array();
                $this->template->set('title', 'Acuerdo de pago');
                $this->data['title'] = 'Acuerdo de pago';
                @$garantias = $this->modelacuerdodepago_model->totalgarantias($this->data['acuerdo']);
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

                @$existencia = $this->modelacuerdodepago_model->consultaexcepcion2($this->data['acuerdo']);
                @$this->data['respuesta'] = $this->modelacuerdodepago_model->consultaexcepcion3($this->data['acuerdo']);

                //@$this->data['ultimarespuesta'] = $this->modelacuerdodepago_model->ultimarespuesta($this->data['acuerdo']);

                if (!empty($existencia[0]['CONTADOR'])) {
                    $this->data['contador'] = $existencia[0]['CONTADOR'];
                } else {
                    $this->data['contador'] = 0;
                }

                $this->data['message'] = $this->session->flashdata('message');
                $this->load->view('acuerdodepago/validacion', $this->data);
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
//        $salario = $this->modelacuerdodepago_model->salariominimo();   
//        $cuota1 = $financiar/$salario[0]['SALARIO_VIGENTE'];        
//        $cuotas = $this->modelacuerdodepago_model->consultacuota($cuota1);     
        $porcentaje = $this->modelacuerdodepago_model->cuotainicial();
        $porcentual['PORCENTAJE'] = $porcentaje[0]['PORCENTAJE'] / 100;
        $this->output->set_content_type('application/json')->set_output(json_encode($porcentual['PORCENTAJE']));
        //$this->output->set_content_type('application/json')->set_output(json_encode($porcentual));
    }

    function cuantias() {
        if ($this->ion_auth->logged_in()) {

            $this->data['tablacuantias'] = $this->modelacuerdodepago_model->tablacuantias();
            $salario = $this->modelacuerdodepago_model->salariominimo();

            $this->data['salariominimo'] = $salario->result_array[0]['SALARIO_VIGENTE'];
            $this->data['tablacuantias'] = $this->modelacuerdodepago_model->tablacuantias();
            $this->data['maxCuantia'] = $this->modelacuerdodepago_model->Maxcuantias();
            $this->data['cuotainicial'] = $this->modelacuerdodepago_model->cuotainicial();
            $this->data['admingarantia'] = $this->modelacuerdodepago_model->admingarantia();
            $this->data['ultimagarantia'] = $this->modelacuerdodepago_model->ultimagarantia();

            $this->data[] = array();
            $this->template->set('title', 'Cuantias');
            $this->data['title'] = 'Acuerdo de pago';

            $this->template->load($this->template_file, 'acuerdodepago/cuantias', $this->data);
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
            $deuda = $this->modelacuerdodepago_model->consultadeuda($identificacion);

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

        $salario = $this->modelacuerdodepago_model->salariominimo();
        $salariominimo = $salario->result_array[0]['SALARIO_VIGENTE'];
        $valor_salarios = $this->data['saldocapital'] / $salariominimo;
        $this->data['cuota'] = $this->modelacuerdodepago_model->consultacuota($valor_salarios);
        @$this->data['TotalCuota'] = $this->data['cuota'][0]['PLAZOMESES'];
        $this->load->view('acuerdodepago/cuotas', $this->data);
    }

    function modificaciongarantias() {
        if ($this->ion_auth->logged_in()) {
            if ($this->data['grupo'] == $this->data['abogadoj'] || $this->data['grupo'] == $this->data['administrador'] || $this->data['grupo'] == $this->data['abogado_relaciones'] || $this->data['grupo'] == $this->data['coordinador_relaciones']) {

                $documento = $this->input->post('nit');
                $this->data['tipo'] = $this->input->post('tipo');
                $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
                $this->data['liquidacion'] = $this->input->post('liquidacion');
                $this->data['resolucion'] = $this->input->post('resolucion');
                $this->data['acuerdo'] = $this->input->post('acuerdo');
                $this->data[] = array();
                $this->template->set('title', 'GARANTIAS');
                $this->data['title'] = 'GARANTIAS';
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['tipodocumento'] = $this->modelacuerdodepago_model->documento();
                $ciudad = $this->modelacuerdodepago_model->ciudad();
                $this->data['ciudad'] = $ciudad->result_array();
                $totalliq = $this->modelacuerdodepago_model->totalliquidacion($this->data['liquidacion']);
                $this->data['totalliquidacion'] = $totalliq[0]['SALDO_DEUDA'];
                $uvt = $this->modelacuerdodepago_model->uvt();
                $valorUvt = $uvt[0]['VALOR_UVT'];
                $this->data['valorTotalUvt'] = $this->data['totalliquidacion'] / $valorUvt;

                //$this->data['totalliquidacion'] = $totalliquidacion['saldocapita'];
                $datos = $this->modelacuerdodepago_model->consultagarantiasjuridicas("", $documento);
                $info = $datos->result_array();
                $this->data['datosgarantia'] = array();
                if (!empty($info)) {
                    foreach ($datos->result_array as $garantia) {
                        $campo = $garantia['VALOR_CAMPO'];
                        if (empty($campo)) {
                            $campo = '';
                        }
                        $this->data['datosgarantia'][$garantia['CODEMPRESA']][$garantia['COD_GARANTIA_ACUERDO']][$garantia['NOMBRE_TIPOGARANTIA']][$garantia['VALOR_AVALUO']][$garantia['VALOR_COMERCIAL']][$garantia['NOMBRE_CAMPO']] = $campo;
                    }
                }
                $this->data['nit'] = $documento;
                $this->data['garantias'] = $this->modelacuerdodepago_model->garantias();
                //$this->template->load($this->template_file, 'acuerdodepago/garantias', $this->data);
                $this->load->view('acuerdodepago/garantias', $this->data);
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
            if ($this->data['grupo'] == $this->data['abogadoj'] || $this->data['grupo'] == $this->data['abogado'] || $this->data['grupo'] == $this->data['administrador'] || $this->data['grupo'] == $this->data['abogado_relaciones'] || $this->data['grupo'] == $this->data['coordinador_relaciones']) {
                $fiscalizacion = $this->input->post('fiscalizacion');
                $acuerdo = $this->input->post('acuerdo');
                $nit = $this->input->post('nit');
                $liquidacion = $this->input->post('liquidacion');

                $tipogestion = 514;
                $tiporespuesta = 1274;
                $comentarios = 'El Cliente esta en Mora';

                $data = array(
                    'COD_RESPUESTA' => $tiporespuesta,
                );

                $cod_respuesta = $this->trazabilidad($tipogestion, $tiporespuesta, $fiscalizacion, $nit, $comentarios);
                $documentoresolucion = $this->modelacuerdodepago_model->actualizar_acuerdo($data, $acuerdo, $tipo = NULL);
                if ($documentoresolucion == TRUE) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acuerdo se ha Modificado correctamente.</div>');
                    redirect(base_url() . 'index.php/acuerdodepago/gestionarAcuerdo');
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
                $this->template->set('title', 'Autorizacion Acuerdo Juridico');
                $this->data['title'] = 'Acuerdo de pago juridico';
                $this->data['user'] = $this->ion_auth->user()->row();
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['autorizaciones'] = $this->modelacuerdodepago_model->singarantia();
                $this->template->load($this->template_file, 'acuerdodepago/singarantia', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardaautorizacion() {
        $fiscalizacion = $this->input->post('fiscalizacion');
        $codexcepcion = $this->input->post('codexcepcion');
        $opcion = $this->input->post('opcion');
        $observacion = $this->input->post('observa');
        $this->modelacuerdodepago_model->actualizaautorizacionadministrativo($codexcepcion, $fiscalizacion, $opcion, $observacion);
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

}

?>