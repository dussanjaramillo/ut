<?php

class Cargarextractoasobancaria extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url'));
        $this->load->model('cargarasobancaria_model', '', TRUE);
    }

    function index() {
        $this->manage();
    }

    function manage() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargarextractoasobancaria/manage')) {
//template data
                $this->template->set('title', 'Cargar Extracto Asobancaria');
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js'
                );
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'cargarextractoasobancaria/cargarextractoasobancaria_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function add() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargarextractoasobancaria/add')) {
                $this->data['errores'] = array();
                $this->data['result_archivo'] = array();
                $this->data['encabezado_archivo'] = array();
                $this->data['encabezado_lote'] = array();
                $this->data['detalle_planilla'] = array();
                $this->data['control_lotes'] = array();
                $this->data['control_archivo'] = array();
                $this->data['numlineas'] = 0;
                $this->template->load($this->template_file, 'cargarextractoasobancaria/cargarextractoasobancaria_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/cargarextractoasobancaria');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    private function directorios() {
        $errores = 0;
        $paths = array();
        $paths[] = "uploads/cargarextractoasobancaria/";
        $paths[] = "uploads/cargarextractoasobancaria/archivos";
        $paths[] = "uploads/cargarextractoasobancaria/errores";
        $paths[] = "uploads/cargarextractoasobancaria/generados";
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

    private function cargaarchivo($file, $filet) {
        $extension = trim($this->obtenerExtensionFichero($file));
        if ($extension != 'txt' && $extension != 'TXT') {
            array_push($this->data['errores'], $file . " ,Este archivo no es de texto. ");
            array_push($this->data['errores_totales'], $this->data['errores']);
            $this->data['negativos'] ++;
            return false;
        } else {
//abre el archivo temporal para su lectura
            $leer = fopen($filet, "r") or exit("Unable to open file!");
            while (!feof($leer)) {
                $linea = fgets($leer);
                ob_start();
                $this->tiporegistro($linea, $this->data['numlineas'], $file);
//echo "Procesando Linea: " . $this->data['numlineas'] . "<br>";
                ob_flush();
                flush();
                ob_end_flush();
                $this->data['numlineas'] ++;
            }
            fclose($leer);
            if (sizeof($this->data['errores']) == 0) {
                $estructura = $this->validaestructura($file);
            }
            if (sizeof($this->data['errores']) == 0) {
                $registros = $this->validaregistros($file);
            }
//            if (sizeof($this->data['errores']) == 0) {
//                $planillas = $this->validaplanillas($file);
//            }

            if (sizeof($this->data['errores']) > 0) {
                $this->crearfichero(0, $file, $filet);
                $this->data['negativos'] ++;
                $this->data['archivo'] = "errores/" . $file;
                $this->data['numlineas'] = 0;
                array_push($this->data['errores_totales'], $this->data['errores']);
            } else {
                try {
                    $carga = $this->cargarregistros($file, $this->data['encabezado_archivo'], $this->data['encabezado_lote'], $this->data['detalle_planilla'], $this->data['control_lotes'], $this->data['control_archivo'], $this->data['numlineas'], 'generados/' . $file);
                    $this->crearfichero(1, $file, $filet);
                    $this->data['positivos'] ++;
                    $this->data['archivo'] = "generados/" . $file;
                    $this->data['numlineas_totales'] += $this->data['numlineas'];
                } catch (Exception $exc) {
                    echo $exc->getTraceAsString();
                }
            }
        }
    }

    private function tiporegistro($linea, $numerolinea, $file) {
        $identifica = trim(substr($linea, 0, 1));
        switch ($identifica) {
            case 1:
                array_push($this->data['result_archivo'], 'Encabezado Archivo');
                if ($this->tieneformato(substr($linea, 0, 1), 1, 1)) {
                    $this->data['encabezado_archivo']['tiporeg'] = trim(substr($linea, 0, 1));
                } else {
                    array_push($this->data['errores'], 'Encabezado sin formato');
                }
                if ($this->tieneformato(substr($linea, 1, 8), 1, 8)) {
                    $this->data['encabezado_archivo']['fechar'] = trim(substr($linea, 1, 8));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", no numerico, Fecha recaudo. ");
                }
                if ($this->tieneformato(substr($linea, 9, 3), 1, 3)) {
                    $this->data['encabezado_archivo']['codigoentidad'] = trim(substr($linea, 9, 3));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", no numerico, Codigo Entidad. ");
                }
                if ($this->tieneformato(substr($linea, 12, 15), 2, 15)) {
                    $this->data['encabezado_archivo']['nitadm'] = trim(substr($linea, 12, 15));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", NIT Administradora. ");
                }
                if ($this->tieneformato(substr($linea, 27, 22), 2, 22)) {
                    $this->data['encabezado_archivo']['nombreadm'] = trim(substr($linea, 27, 22));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Nombre Administradora. ");
                }
                $this->data['encabezado_archivo']['reservado'] = trim(substr($linea, 49));
                break;
            case 5:
                array_push($this->data['result_archivo'], 'Encabezado Lote');
                if ($this->tieneformato(substr($linea, 0, 1), 1, 1)) {
                    $this->data['encabezado_lote']['tiporeg'] = trim(substr($linea, 0, 1));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", no numerico, Tipo registro. ");
                }
                if ($this->tieneformato(substr($linea, 1, 17), 2, 17)) {
                    $this->data['encabezado_lote']['numcuenta'] = trim(substr($linea, 1, 17));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Número Cuenta. ");
                }
                if ($this->tieneformato(substr($linea, 18, 2), 1, 2)) {
                    $this->data['encabezado_lote']['tipocuenta'] = trim(substr($linea, 18, 2));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Tipo Cuenta. ");
                }
                if ($this->tieneformato(substr($linea, 20, 2), 1, 2)) {
                    $this->data['encabezado_lote']['numerolote'] = trim(substr($linea, 20, 2));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Número Lote. ");
                }
                if ($this->tieneformato(substr($linea, 22, 2), 2, 2)) {
                    $this->data['encabezado_lote']['sistemapago'] = trim(substr($linea, 22, 2));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Sistema de Pago. ");
                }
                $this->data['encabezado_lote']['reservado'] = trim(substr($linea, 24));
                break;
            case 6:
                array_push($this->data['result_archivo'], 'Detalle Planilla');
                if ($this->tieneformato(substr($linea, 0, 1), 1, 1)) {
                    $this->data['detalle_planilla'][$numerolinea]['tiporeg'] = trim(substr($linea, 0, 1));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", no numerico, Tipo registro. ");
                }
                if ($this->tieneformato(substr($linea, 1, 16), 2, 16)) {
                    $this->data['detalle_planilla'][$numerolinea]['idaportante'] = trim(substr($linea, 1, 16));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", ID Aportante. ");
                }
                if ($this->tieneformato(substr($linea, 17, 16), 2, 16)) {
                    $this->data['detalle_planilla'][$numerolinea]['nombreaportante'] = trim(substr($linea, 17, 16));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Nombre Aportante. ");
                }
                if ($this->tieneformato(substr($linea, 33, 8), 1, 8)) {
                    $this->data['detalle_planilla'][$numerolinea]['codbancoaut'] = trim(substr($linea, 33, 8));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Código Banco Autorizador. ");
                }
                if ($this->tieneformato(substr($linea, 41, 15), 2, 15)) {
                    $this->data['detalle_planilla'][$numerolinea]['numplanillaliq'] = trim(substr($linea, 41, 15));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Número Planilla Liquidación. ");
                }
                if ($this->tieneformato(substr($linea, 56, 6), 2, 6)) {
                    $this->data['detalle_planilla'][$numerolinea]['periodopago'] = trim(substr($linea, 56, 6));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Periodo Pago. ");
                }
                if ($this->tieneformato(substr($linea, 62, 2), 2, 2)) {
                    $this->data['detalle_planilla'][$numerolinea]['canalpago'] = trim(substr($linea, 62, 2));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Canal de Pago. ");
                }
                if ($this->tieneformato(substr($linea, 64, 6), 1, 6)) {
                    $this->data['detalle_planilla'][$numerolinea]['numregistros'] = trim(substr($linea, 64, 6));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Número de Registros. ");
                }
                if ($this->tieneformato(substr($linea, 70, 2), 2, 2)) {
                    $this->data['detalle_planilla'][$numerolinea]['codoperadorinf'] = trim(substr($linea, 70, 2));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Código Operador Información. ");
                }
                if ($this->tieneformato(substr($linea, 72, 18), 1, 18)) {
                    $this->data['detalle_planilla'][$numerolinea]['valorplanilla'] = trim(substr($linea, 72, 16));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Valor Planilla. ");
                }
                if ($this->tieneformato(substr($linea, 90, 4), 1, 4)) {
                    $this->data['detalle_planilla'][$numerolinea]['horaminuto'] = trim(substr($linea, 90, 4));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Hora-Minuto. ");
                }
                if ($this->tieneformato(substr($linea, 94, 6), 1, 6)) {
                    $this->data['detalle_planilla'][$numerolinea]['numsecuencia'] = trim(substr($linea, 94, 6));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Número de Secuencia. ");
                }
                $this->data['detalle_planilla'][$numerolinea]['reservado'] = trim(substr($linea, 100));
                break;
            case 8:
                array_push($this->data['result_archivo'], 'Detalle Lote');
                if ($this->tieneformato(substr($linea, 0, 1), 1, 1)) {
                    $this->data['control_lotes']['tiporeg'] = trim(substr($linea, 0, 1));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", no numerico, Tipo registro. ");
                }
                if ($this->tieneformato(substr($linea, 1, 6), 1, 6)) {
                    $this->data['control_lotes']['numplanillas'] = trim(substr($linea, 1, 6));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Número de Planillas. ");
                }
                if ($this->tieneformato(substr($linea, 7, 6), 1, 6)) {
                    $this->data['control_lotes']['numregistros'] = trim(substr($linea, 7, 6));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Número de Registros. ");
                }
                if ($this->tieneformato(substr($linea, 13, 18), 1, 18)) {
                    $this->data['control_lotes']['valorrecaudo'] = trim(substr($linea, 13, 16));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Valor Recaudo. ");
                }
                $this->data['control_lotes']['reservado'] = trim(substr($linea, 31));
                break;
            case 9:
                array_push($this->data['result_archivo'], 'Control Archivo');
                if ($this->tieneformato(substr($linea, 0, 1), 1, 1)) {
                    $this->data['control_archivo']['tiporeg'] = trim(substr($linea, 0, 1));
                } else {
                    array_push($this->data['custom_error'], $file . " , Error en linea " . $numerolinea . ", no numerico, Tipo registro. ");
                }
                if ($this->tieneformato(substr($linea, 1, 8), 1, 8)) {
                    $this->data['control_archivo']['numtotalplanillas'] = trim(substr($linea, 1, 8));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Número Total de Planillas. ");
                }
                if ($this->tieneformato(substr($linea, 9, 6), 1, 6)) {
                    $this->data['control_archivo']['numtotalregistros'] = trim(substr($linea, 9, 6));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Número Total de Registros. ");
                }
                if ($this->tieneformato(substr($linea, 15, 18), 1, 18)) {
                    $this->data['control_archivo']['valortotalrecaudo'] = trim(substr($linea, 15, 16));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Valor Total Recaudo. ");
                }
                if ($this->tieneformato(substr($linea, 33, 6), 1, 6)) {
                    $this->data['control_archivo']['numtotallotes'] = trim(substr($linea, 33, 6));
                } else {
                    array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Número Total de Lotes. ");
                }
                $this->data['control_archivo']['reservado'] = trim(substr($linea, 39));
                break;
            case '':
                break;
            case ' ':
                break;
            case null:
                break;
            default:
                array_push($this->data['errores'], $file . " , Error en linea " . $numerolinea . ", Tipo de registro no valido. ");
                break;
        }
    }

    function cargue() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargarextractoasobancaria/add')) {
                ini_set('max_execution_time', '7200');
                $this->data['errores_totales'] = array();
                $this->data['numlineas_totales'] = 0;
                $this->data['errores'] = array();
                $this->data['carga_exito'] = array();
                $this->data['result_archivo'] = array();
                $this->data['positivos'] = 0;
                $this->data['negativos'] = 0;
                $path = "uploads/cargarextractoasobancaria/";
                $directorios = $this->directorios();
                if ($directorios) {
                    foreach (array_combine($_FILES['uploads']['name'], $_FILES['uploads']['tmp_name']) as $file => $filet) {
                        if ($file == '') {
                            array_push($this->data['errores'], $file . " , No selecciono ningun archivo. ");
                            array_push($this->data['errores_totales'], $this->data['errores']);
                        } elseif (file_exists($path . "archivos/" . $file)) {
                            array_push($this->data['errores'], $file . " , Este archivo ya existe. ");
                            array_push($this->data['errores_totales'], $this->data['errores']);
                            $this->data['numlineas'] = 0;
                            $this->data['negativos'] ++;
                        } else {
                            $this->data['errores'] = array();
                            $this->data['encabezado_archivo'] = array();
                            $this->data['encabezado_lote'] = array();
                            $this->data['detalle_planilla'] = array();
                            $this->data['control_lotes'] = array();
                            $this->data['control_archivo'] = array();
                            $this->data['numlineas'] = 0;
                            $this->data['archivo'] = array();
                            $archivo = $this->cargaarchivo($file, $filet);
                        }
                    }
                } else {
                    array_push($this->data['errores'], " Error en directorios del sistema ");
                    array_push($this->data['errores_totales'], " Error en directorios del sistema ");
                    $this->data['numlineas'] = 0;
                }
                $this->template->load($this->template_file, 'cargarextractoasobancaria/cargarextractoasobancaria_result', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class = "alert alert-info"><button type = "button" class = "close" data-dismiss = "alert">&times;
                </button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/cargarextractoasobancaria');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function cargarregistros($nombrearchivo, $encabezado_archivo, $encabezado_lote, $detalle_planilla, $control_lotes, $control_archivo, $lineas, $archivo) {
        $path = "uploads/cargarextractoasobancaria/";
        $dates = array(
            'FECHA_RECAUDO' => substr($encabezado_archivo['fechar'], 6) . '/' . substr($encabezado_archivo['fechar'], 4, 2) . '/' . substr($encabezado_archivo['fechar'], 0, 4),
            'FECHA_CARGA' => date('d/m/Y h:i:s'),
        );

        $numbers = array(
            'COD_ENTIDAD' => ltrim($encabezado_archivo['codigoentidad'], '0'),
            'VALOR_RECAUDADO' => ltrim(str_replace(",", ".", $control_lotes['valorrecaudo'])),
            'TOTAL_PLANILLAS' => ltrim($control_archivo['numtotalplanillas'], '0'),
            'TOTAL_REGISTROS' => ltrim($control_archivo['numtotalregistros'], '0'),
            'TOTAL_RECAUDADO' => ltrim(str_replace(",", ".", $control_archivo['valortotalrecaudo'])),
            'TOTAL_LOTES' => ltrim($control_archivo['numtotallotes'], '0'),
            'NUM_CUENTA' => ltrim($encabezado_lote['numcuenta'], '0'),
            'VALOR_TOTAL' => '',
            'CONCILIADO' => '',
            'NRO_PLANILLAS' => ltrim($control_lotes['numplanillas'], '0'),
            'NRO_REGISTROS' => ltrim($control_lotes['numregistros'], '0'),
        );

        $data = array(
            'NIT_ADMINISTRADORA' => ltrim($encabezado_archivo['nitadm'], '0'),
            'NOM_ADMINISTRADORA' => ltrim($encabezado_archivo['nombreadm'], '0'),
            'TIPO_CUENTA' => ltrim($encabezado_lote['tipocuenta'], '0'),
            'NRO_LOTE' => ltrim($encabezado_lote['numerolote'], '0'),
            'COD_SISTEMAPAGO' => ltrim($encabezado_lote['sistemapago'], '0'),
            'NOM_ARCHIVO_CARGADO' => $nombrearchivo,
        );

        foreach ($detalle_planilla as $detalle) {
            $cod = $this->digitoverifica($detalle['idaportante'], 0);
            $data2[] = array(
                'COD_APORTANTE' => $cod,
                'NOM_APORTANTE' => ltrim(utf8_encode($detalle['nombreaportante']), '0'),
                'COD_ENTIDAD' => ltrim($detalle['codbancoaut'], '0'),
                'NRO_PLANILLA' => ltrim($detalle['numplanillaliq'], '0'),
                'PERIODO_PAGO' => ltrim($detalle['periodopago'], '0'),
                'CANAL_PAGO' => ltrim($detalle['canalpago'], '0'),
                'NRO_REGISTROS' => ltrim($detalle['numregistros'], '0'),
                'COD_OPERADOR' => ltrim($detalle['codoperadorinf'], '0'),
                'VALOR_PLANILLA' => ltrim($detalle['valorplanilla'], '0'),
                'HORA_MINUTO' => ltrim($detalle['horaminuto'], '0'),
                'SECUENCIA' => ltrim($detalle['numsecuencia'], '0'),
            );
        }

        $data3 = array(
            'NRO_FILAS_ARCHIVO' => $lineas,
            'NRO_FILAS_CARGADAS' => $lineas,
            'ESTADOCARGUEARCHIVO' => 'TOTAL',
            'USUARIO' => $this->ion_auth->user()->row()->IDUSUARIO,
            'NRO_FILAS_NO_CARGADAS' => '0',
            'NRO_REGISTROS_CON_ERROR' => '0',
            'ARCHIVO' => $archivo,
        );

//CORRE LA TRANSACCION
        try {
            $carga = $this->cargarasobancaria_model->Cargar($dates, $numbers, $data, $data2, $data3);
            return $carga;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            return false;
        }
    }

    function tieneformato($campo, $tipo, $tamano) {
        if ($tipo == 1) {
            if ((is_numeric(trim($campo))) and (!is_null($campo)) and (strlen($campo) == $tamano)) {
                return true;
            } else {
                return false;
            }
        } elseif ($tipo == 2) {
//string
            if ((is_string(trim($campo))) and (!is_null($campo)) and (strlen($campo) == $tamano)) {
                return true;
            } else {
                return false;
            }
        }
    }

    function cargados() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin()) {

//template data
                $this->template->set('title', 'Listado Extractos Cargados');
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js'
                );

                $this->data['data1'] = $this->cargarasobancaria_model->vercargados();

                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'cargarextractoasobancaria/extractoscargados_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class = "alert alert-info"><button type = "button" class = "close" data-dismiss = "alert">&times;
                </button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function obtenerExtensionFichero($str) {
        return end(explode(".", $str));
    }

    function exporta() {
        $path = "./uploads/cargarextractoasobancaria/";
        $enlace = $path . $this->input->post('archivo');
        header("Content-Disposition: attachment; filename=reporte_cargue.txt");
        header("Content-Type: application/text");
        header("Content-Length: " . filesize($enlace));
        readfile($enlace);
    }

    private function crearfichero($tipo, $file, $filet) {
        $path = "uploads/cargarextractoasobancaria/";
        if ($tipo == 1) {
            $this->data['archivo'] = 'generados/' . $file;
            move_uploaded_file($filet, $path . "archivos/" . $file);
            array_push($this->data['carga_exito'], $file . ', Cargado con éxito.');
            array_push($this->data['carga_exito'], 'Cargue de estructura a base de datos.');
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

    function archivo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin()) {
                $id = $this->uri->segment(3);
                try {
                    $this->data['data1'] = $this->cargarasobancaria_model->detallecarga($id);
                    $this->load->view('cargarextractoasobancaria/detallecarga', $this->data);
                } catch (Exception $exc) {
                    echo $exc->getTraceAsString();
                }
            }
        }
    }

    function verestado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin()) {
                $id = $this->uri->segment(3);
                try {
                    $this->data['conciliacion'] = $this->cargarasobancaria_model->verconciliacion($id);
                    $this->load->view('cargarextractoasobancaria/detallecarga_conciliacion', $this->data);
                } catch (Exception $exc) {
                    echo $exc->getTraceAsString();
                }
            }
        }
    }

    function conciliar() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin()) {
//template data
                $this->template->set('title', 'Conciliación de pagos');
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js'
                );
                $this->cargarasobancaria_model->conciliar();
                $this->data['resumen'] = $this->cargarasobancaria_model->resumenconciliados();
                $this->template->load($this->template_file, 'cargarextractoasobancaria/conciliacion_result', $this->data);
            }
        }
    }

    function validaestructura($file) {
        if (sizeof($this->data['encabezado_archivo']) == 0 ||
                sizeof($this->data['encabezado_lote']) == 0 ||
                sizeof($this->data['detalle_planilla']) == 0 ||
                sizeof($this->data['control_lotes']) == 0 ||
                sizeof($this->data['control_archivo']) == 0
        ) {
            array_push($this->data['errores'], $file . " ,Este archivo no tiene integridad en sus componentes");
            return false;
        } else {
            return true;
        }
    }

    function validaplanillas($file) {
        foreach ($this->data['detalle_planilla'] as $planillas) {
            $carga = $this->cargarasobancaria_model->vplanilla($planillas);
            if ($carga[0] > 0) {
                array_push($this->data['errores'], $file . " ,Este archivo contiene planillas ya cargadas");
                array_push($this->data['errores'], "PLANILLA NUMERO: " . $planillas['numplanillaliq'] . " CON VALOR " . $planillas['valorplanilla'] . " YA HA SIDO CARGADA CON ANTERIORIDAD CON CODIGO CARGE " . $carga[1]);
            }
        }
    }

    function validaregistros($file) {
        if (number_format($this->data['control_lotes']['numplanillas']) != number_format($this->data['control_archivo']['numtotalplanillas']) || number_format($this->data['control_lotes']['numplanillas']) != number_format(sizeof($this->data['detalle_planilla']))) {
            array_push($this->data['errores'], $file . " ,Este archivo no tiene integridad en el numero de planillas");
        }

        if ($this->data['control_lotes']['valorrecaudo'] != $this->data['control_archivo']['valortotalrecaudo']) {
            array_push($this->data['errores'], $file . " ,Este archivo no tiene integridad en el valor recaudado");
        }
        return true;
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
                        array_push($this->data['ficheros'], $file);
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

    function digitoverifica($nit, $str = 0) {
        $nit = trim($nit);
        $suma = 0;
        $len = strlen(trim($nit)) - 1;
        $factores = array(71, 67, 59, 53, 47, 43, 41, 37, 29, 23, 19, 17, 13, 7, 3);
        if ($len > 0 && $len < 16) {
            $pos = str_split(str_pad($nit, 16, "0", STR_PAD_LEFT));
            for ($x = 0; $x < 15; $x++) {
                $suma += ($pos[$x] * $factores[$x]);
            }
            $digtemp = ($suma % 11);
            if ($digtemp != 0 && $digtemp != 1) {
                $digito = trim(11 - $digtemp);
            } else {
                $digito = trim($digtemp);
            }
            if ($str == 0) {
                $dig = substr($nit, -1);
                if ($dig === $digito) {
                    $ret = substr($nit, 0, $len);
                    return $ret;
                } else {
                    return $nit;
                }
            } else {
                return $digito;
            }
        } else {
            return false;
        }
    }

    function exportarconciliacion() {
        $fecha = date('Ymd-hi');
        $nombre = "PAGOSPILA-".$fecha;
        $conciliados = $this->cargarasobancaria_model->verconciliacion('1');
        $noconciliados = $this->cargarasobancaria_model->verconciliacion('0');
        $this->load->library("PHPExcel");
        $PHPExcel = new PHPExcel();
        $PHPExcel->getProperties()->setCreator("SENA")->setTitle("RELACION PAGOS CONCILIADOS Y SIN CONCILIAR ");
        $PHPExcel->setActiveSheetIndex(0);
        $PHPExcel->getActiveSheet()->setTitle('PAGOS CONCILIADOS');
        
        $PHPExcel->getActiveSheet()->setCellValue('A1','RELACION PAGOS CONCILIADOS');
        $PHPExcel->getActiveSheet()->setCellValue('A2','FECHA GENERACION: '.$fecha);
        $PHPExcel->getActiveSheet()->setCellValue('A5','NIT');
        $PHPExcel->getActiveSheet()->setCellValue('B5','CODIGO OPERADOR');
        $PHPExcel->getActiveSheet()->setCellValue('C5','PERIODO PAGADO');
        $PHPExcel->getActiveSheet()->setCellValue('D5','NUMERO PLANILLA');
        $PHPExcel->getActiveSheet()->setCellValue('E5','FECHA PAGO');
        $PHPExcel->getActiveSheet()->setCellValue('F5','VALOR PAGADO');
        $PHPExcel->getActiveSheet()->setCellValue('G5','BANCO');
        $PHPExcel->getActiveSheet()->setCellValue('H5','CONCILIADO');
        $x=6;
        foreach ($conciliados->result_array as $value) {
            $PHPExcel->getActiveSheet()->setCellValue('A'.$x, $value['NIT']);
            $PHPExcel->getActiveSheet()->setCellValue('B'.$x, $value['OPERADOR']);
            $PHPExcel->getActiveSheet()->setCellValue('C'.$x, $value['PERIODO']);
            $PHPExcel->getActiveSheet()->setCellValue('D'.$x, $value['PLANILLA']);
            $PHPExcel->getActiveSheet()->setCellValue('E'.$x, $value['FPAGO']);
            $PHPExcel->getActiveSheet()->setCellValue('F'.$x, $value['VALORPAGO']);
            $PHPExcel->getActiveSheet()->setCellValue('G'.$x, $value['BANCO']);
            $PHPExcel->getActiveSheet()->setCellValue('H'.$x, $value['ESTADO']);
            $x++;
        }
        
        $PHPExcel->createSheet(1);
        $PHPExcel->setActiveSheetIndex(1);
        $PHPExcel->getActiveSheet()->setTitle('PAGOS NO CONCILIADOS');
        
        $PHPExcel->getActiveSheet()->setCellValue('A1','RELACION PAGOS NO CONCILIADOS');
        $PHPExcel->getActiveSheet()->setCellValue('A2','FECHA GENERACION: '.$fecha);
        $PHPExcel->getActiveSheet()->setCellValue('A5','NIT');
        $PHPExcel->getActiveSheet()->setCellValue('B5','CODIGO OPERADOR');
        $PHPExcel->getActiveSheet()->setCellValue('C5','PERIODO PAGADO');
        $PHPExcel->getActiveSheet()->setCellValue('D5','NUMERO PLANILLA');
        $PHPExcel->getActiveSheet()->setCellValue('E5','FECHA PAGO');
        $PHPExcel->getActiveSheet()->setCellValue('F5','VALOR PAGADO');
        $PHPExcel->getActiveSheet()->setCellValue('G5','BANCO');
        $PHPExcel->getActiveSheet()->setCellValue('H5','CONCILIADO');
        $x=6;
        foreach ($noconciliados->result_array as $value) {
            $PHPExcel->getActiveSheet()->setCellValue('A'.$x, $value['NIT']);
            $PHPExcel->getActiveSheet()->setCellValue('B'.$x, $value['OPERADOR']);
            $PHPExcel->getActiveSheet()->setCellValue('C'.$x, $value['PERIODO']);
            $PHPExcel->getActiveSheet()->setCellValue('D'.$x, $value['PLANILLA']);
            $PHPExcel->getActiveSheet()->setCellValue('E'.$x, $value['FPAGO']);
            $PHPExcel->getActiveSheet()->setCellValue('F'.$x, $value['VALORPAGO']);
            $PHPExcel->getActiveSheet()->setCellValue('G'.$x, $value['BANCO']);
            $PHPExcel->getActiveSheet()->setCellValue('H'.$x, $value['ESTADO']);
            $x++;
        }
        
        //header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        ob_clean();
        $objWriter->save("./uploads/conciliacion/" . $nombre . ".xlsx");
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombre . ".xlsx" . '"');
        $objWriter->save("php://output");
        die();
    }

}

/* End of file cargarextractoasobancaria.php */
        /* Location: ./system/application/controllers/cargarextractoasobancaria.php */
        